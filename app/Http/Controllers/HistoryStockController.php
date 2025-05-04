<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

// Model
use App\Models\HistoryStock;
use App\Models\MstFGs;
use App\Models\MstRawMaterials;
use App\Models\MstSpareparts;
use App\Models\MstWips;
use App\Models\BarcodeDetail;
use App\Models\DetailGoodReceiptNoteDetail;
use App\Models\GoodReceiptNoteDetail;
use App\Models\PackingList;
use App\Models\ReportBag;
use App\Models\ReportBlow;
use App\Models\ReportSf;

class HistoryStockController extends Controller
{
    use AuditLogsTrait;
    
    // RAW MATERIAL
    public function indexRM(Request $request)
    {

        $datas = MstRawMaterials::select('master_raw_materials.*', 'master_units.unit_code')
            ->leftjoin('master_units', 'master_raw_materials.id_master_units', 'master_units.id');
        
        $datas = $datas->orderBy('created_at', 'desc')->get();

        // Get Page Number
        $idUpdated = $request->get('idUpdated');
        $page_number = 1;
        if ($idUpdated) {
            $page_size = 5; $item = $datas->firstWhere('id', $idUpdated);
            if ($item) {
                $index = $datas->search(function ($value) use ($idUpdated) {
                    return $value->id == $idUpdated;
                });
                $page_number = (int) ceil(($index + 1) / $page_size);
            } else { $page_number = 1; }
        }

        // Datatables
        if ($request->ajax()) {
            return DataTables::of($datas)
                ->addColumn('action', function ($data) {
                    return view('historystock.rm.action', compact('data'));
                })->make(true);
        }
        //Audit Log
        $this->auditLogsShort('View Detail History Stock RM');
        return view('historystock.rm.index', compact('idUpdated', 'page_number'));
    }
    public function historyRM(Request $request, $id)
    {
        $id = decrypt($id);
        $detail = MstRawMaterials::select('rm_code', 'description', 'stock')->where('id', $id)->first();
        $datas = HistoryStock::where('id_master_products', $id)
        ->where('type_product', 'RM')->orderBy('created_at')->get()
        ->map(function ($item) {
            $idDetail = $item->id_good_receipt_notes_details;
            $item->id_detail = null;
            $item->status_stock = null;
            $item->note_stock = null;
            $item->number = null;
            $item->status = null;
            $item->tableJoin = null;

            if (Str::startsWith($idDetail, 'RB')) {
                $rb = ReportBlow::where('report_number', $idDetail)->first();
                if ($rb) {
                    $item->id_detail = $rb->id ?? null;
                    $item->status_stock = $rb->status ?? null;
                    $item->note_stock = null;
                    $item->number = $rb->report_number ?? null;
                    $item->status = $rb->status ?? null;
                    $item->tableJoin = 'RB';
                }
            } elseif (Str::startsWith($idDetail, ['RSL', 'RF'])) {
                $rfs = ReportSf::where('report_number', $idDetail)->first();
                if ($rfs) {
                    $item->id_detail = $rfs->id ?? null;
                    $item->status_stock = $rfs->status ?? null;
                    $item->note_stock = $rfs->note ?? null;
                    $item->number = $rfs->report_number ?? null;
                    $item->status = $rfs->status ?? null;
                    $item->tableJoin = 'RSLRF';
                }
            } elseif (Str::startsWith($idDetail, 'RBM')) {
                $rbm = ReportBag::where('report_number', $idDetail)->first();
                if ($rbm) {
                    $item->id_detail = $rbm->id ?? null;
                    $item->status_stock = $rbm->status ?? null;
                    $item->note_stock = $rbm->note ?? null;
                    $item->number = $rbm->report_number ?? null;
                    $item->status = $rbm->status ?? null;
                    $item->tableJoin = 'RBM';
                }
            } elseif (is_numeric($idDetail)) {
                if ($item->type_stock == 'OUT') {
                    $packing = PackingList::where('packing_number', $idDetail)->first();
                    if ($packing) {
                        $item->id_detail = $packing->id ?? null;
                        $item->status_stock = $packing->status ?? null;
                        $item->note_stock = $packing->note ?? null;
                        $item->number = $packing->packing_number ?? null;
                        $item->status = $packing->status ?? null;
                        $item->tableJoin = 'PL';
                    }
                } else {
                    $grn = GoodReceiptNoteDetail::where('id', $idDetail)->first();
                    if ($grn) {
                        $item->id_detail = $grn->id ?? null;
                        $item->status_stock = $grn->status ?? null;
                        $item->note_stock = $grn->note ?? null;
                        $item->number = $grn->lot_number ?? null;
                        $item->status = 'Closed';
                        $item->tableJoin = 'GRN';
                    }
                }
            }

            return $item;
        })
        ->sortByDesc('created_at')
        ->unique('id_good_receipt_notes_details')
        ->values();

        $total_in = $datas->filter(function ($item) {
            return $item->type_stock === 'IN' && $item->status === 'Closed';
        })->sum('qty');
        $total_out = $datas->filter(function ($item) {
            return $item->type_stock === 'OUT' && $item->status === 'Closed';
        })->sum('qty');

        if ($request->ajax()) {
            return DataTables::of($datas)
                ->addColumn('action', function ($data) {
                    return view('historystock.rm.history.action', compact('data'));
                })->make(true);
        }
        //Audit Log
        $this->auditLogsShort('View Detail History Stock RM Code '.$detail->rm_code);
        return view('historystock.rm.history.index', compact('id', 'detail', 'total_in', 'total_out'));
    }
    public function detailHistRM(Request $request, $id, $tableJoin)
    {
        $id = decrypt($id);
        $historyStock = HistoryStock::where('id', $id)->first();
        $dataHistories = HistoryStock::where('id_good_receipt_notes_details', $historyStock->id_good_receipt_notes_details)->orderBy('created_at')->get();

        $number = $historyStock->id_good_receipt_notes_details ?? null;
        $product = MstRawMaterials::where('id', $historyStock->id_master_products)->first();

        if($tableJoin == 'PL') {
            $datas = PackingList::select('packing_lists.packing_number', 'packing_lists.date', 'packing_lists.status', 'master_customers.name as customer_name')
                ->leftJoin('master_customers', "packing_lists.id_master_customers", 'master_customers.id')
                ->where('packing_lists.packing_number', $number)
                ->first();
        } elseif (in_array($tableJoin, ['RB', 'RSLRF', 'RBM'])) {
            $modelMap = [
                'RB' => ReportBlow::class,
                'RSLRF' => ReportSf::class,
                'RBM' => ReportBag::class,
            ];
            $tableAlias = [
                'RB' => 'report_blows',
                'RSLRF' => 'report_sfs',
                'RBM' => 'report_bags',
            ];
            $model = $modelMap[$tableJoin];
            $alias = $tableAlias[$tableJoin];

            $datas = $model::select(
                "$alias.report_number", "$alias.order_name", "$alias.date",
                "$alias.status", "$alias.shift", 'master_regus.regu as regus_name',
                'master_customers.name as customer_name',
                'kr.name as kr_name', 'op.name as op_name', 'kb.name as kb_name'
            )
            ->leftJoin('master_customers', "$alias.id_master_customers", 'master_customers.id')
            ->leftJoin('master_regus', "$alias.id_master_regus", 'master_regus.id')
            ->leftJoin('master_employees as kr', "$alias.ketua_regu", 'kr.id')
            ->leftJoin('master_employees as op', "$alias.operator", 'op.id')
            ->leftJoin('master_employees as kb', "$alias.know_by", 'kb.id')
            ->where("$alias.report_number", $number)
            ->first();
            // dd($datas);
        } else {
            $grn = GoodReceiptNoteDetail::where('id', $id)->first();
            $datas = DetailGoodReceiptNoteDetail::where('id_grn_detail', $number)->get();
            $number = $datas[0]->lot_number;
        }

        if ($request->ajax()) {
            return DataTables::of($datas)->make(true);
        }
        //Audit Log
        $this->auditLogsShort('View Detail History Stock RM Lot/Report/Packing Number '.$number);

        return view('historystock.rm.history.detail', compact('dataHistories', 'product', 'datas', 'number', 'id', 'tableJoin'));
    }

    // WORK IN PROGRESS
    public function indexWIP(Request $request)
    {
        // Datatables
        if ($request->ajax()) {
            $datas = MstWips::select(
                'master_wips.id', 'master_wips.wip_code', 'master_wips.description',
                'master_wips.thickness', 'master_wips.type', 'master_wips.stock', 'master_wips.weight',
                'master_units.unit_code', 'master_group_subs.name as sub_groupname',
            )
                ->leftjoin('master_units', 'master_wips.id_master_units', 'master_units.id')
                ->leftjoin('master_group_subs', 'master_wips.id_master_group_subs', 'master_group_subs.id')
                ->get();

            return DataTables::of($datas)
                ->addColumn('action', function ($data) {
                    return view('historystock.wip.action', compact('data'));
                })->make(true);
        }

        //Audit Log
        $this->auditLogsShort('View Detail History Stock WIP');
        return view('historystock.wip.index');
    }
    public function historyWIP(Request $request, $id)
    {
        $id = decrypt($id);
        $detail = MstWips::select('wip_code', 'description', 'stock')->where('id', $id)->first();
        $datas = HistoryStock::where('id_master_products', $id)
        ->where('type_product', 'WIP')->orderBy('created_at')->get()
        ->map(function ($item) {
            $idDetail = $item->id_good_receipt_notes_details;
            $item->id_detail = null;
            $item->status_stock = null;
            $item->note_stock = null;
            $item->number = null;
            $item->status = null;
            $item->tableJoin = null;

            if (Str::startsWith($idDetail, 'RB')) {
                $rb = ReportBlow::where('report_number', $idDetail)->first();
                if ($rb) {
                    $item->id_detail = $rb->id ?? null;
                    $item->status_stock = $rb->status ?? null;
                    $item->note_stock = null;
                    $item->number = $rb->report_number ?? null;
                    $item->status = $rb->status ?? null;
                    $item->tableJoin = 'RB';
                }
            } elseif (Str::startsWith($idDetail, ['RSL', 'RF'])) {
                $rfs = ReportSf::where('report_number', $idDetail)->first();
                if ($rfs) {
                    $item->id_detail = $rfs->id ?? null;
                    $item->status_stock = $rfs->status ?? null;
                    $item->note_stock = $rfs->note ?? null;
                    $item->number = $rfs->report_number ?? null;
                    $item->status = $rfs->status ?? null;
                    $item->tableJoin = 'RSLRF';
                }
            } elseif (Str::startsWith($idDetail, 'RBM')) {
                $rbm = ReportBag::where('report_number', $idDetail)->first();
                if ($rbm) {
                    $item->id_detail = $rbm->id ?? null;
                    $item->status_stock = $rbm->status ?? null;
                    $item->note_stock = $rbm->note ?? null;
                    $item->number = $rbm->report_number ?? null;
                    $item->status = $rbm->status ?? null;
                    $item->tableJoin = 'RBM';
                }
            } elseif (is_numeric($idDetail)) {
                if ($item->type_stock == 'OUT') {
                    $packing = PackingList::where('packing_number', $idDetail)->first();
                    if ($packing) {
                        $item->id_detail = $packing->id ?? null;
                        $item->status_stock = $packing->status ?? null;
                        $item->note_stock = $packing->note ?? null;
                        $item->number = $packing->packing_number ?? null;
                        $item->status = $packing->status ?? null;
                        $item->tableJoin = 'PL';
                    }
                } else {
                    $grn = GoodReceiptNoteDetail::where('id', $idDetail)->first();
                    if ($grn) {
                        $item->id_detail = $grn->id ?? null;
                        $item->status_stock = $grn->status ?? null;
                        $item->note_stock = $grn->note ?? null;
                        $item->number = $grn->lot_number ?? null;
                        $item->status = 'Closed';
                        $item->tableJoin = 'GRN';
                    }
                }
            }

            return $item;
        })
        ->sortByDesc('created_at')
        ->unique('id_good_receipt_notes_details')
        ->values();

        $total_in = $datas->filter(function ($item) {
            return $item->type_stock === 'IN' && $item->status === 'Closed';
        })->sum('qty');
        $total_out = $datas->filter(function ($item) {
            return $item->type_stock === 'OUT' && $item->status === 'Closed';
        })->sum('qty');

        if ($request->ajax()) {
            return DataTables::of($datas)
                ->addColumn('action', function ($data) {
                    return view('historystock.wip.history.action', compact('data'));
                })->make(true);
        }
        //Audit Log
        $this->auditLogsShort('View Detail History Stock WIP Code '.$detail->wip_code);
        return view('historystock.wip.history.index', compact('id', 'detail', 'total_in', 'total_out'));
    }
    public function detailHistWIP(Request $request, $id, $tableJoin)
    {
        $id = decrypt($id);
        $historyStock = HistoryStock::where('id', $id)->first();
        $dataHistories = HistoryStock::where('id_good_receipt_notes_details', $historyStock->id_good_receipt_notes_details)->orderBy('created_at')->get();

        $number = $historyStock->id_good_receipt_notes_details ?? null;
        $product = MstWips::where('id', $historyStock->id_master_products)->first();

        if($tableJoin == 'PL') {
            $datas = PackingList::select('packing_lists.packing_number', 'packing_lists.date', 'packing_lists.status', 'master_customers.name as customer_name')
                ->leftJoin('master_customers', "packing_lists.id_master_customers", 'master_customers.id')
                ->where('packing_lists.packing_number', $number)
                ->first();
        } elseif (in_array($tableJoin, ['RB', 'RSLRF', 'RBM'])) {
            $modelMap = [
                'RB' => ReportBlow::class,
                'RSLRF' => ReportSf::class,
                'RBM' => ReportBag::class,
            ];
            $tableAlias = [
                'RB' => 'report_blows',
                'RSLRF' => 'report_sfs',
                'RBM' => 'report_bags',
            ];
            $model = $modelMap[$tableJoin];
            $alias = $tableAlias[$tableJoin];

            $datas = $model::select(
                "$alias.report_number", "$alias.order_name", "$alias.date",
                "$alias.status", "$alias.shift", 'master_regus.regu as regus_name',
                'master_customers.name as customer_name',
                'kr.name as kr_name', 'op.name as op_name', 'kb.name as kb_name'
            )
            ->leftJoin('master_customers', "$alias.id_master_customers", 'master_customers.id')
            ->leftJoin('master_regus', "$alias.id_master_regus", 'master_regus.id')
            ->leftJoin('master_employees as kr', "$alias.ketua_regu", 'kr.id')
            ->leftJoin('master_employees as op', "$alias.operator", 'op.id')
            ->leftJoin('master_employees as kb', "$alias.know_by", 'kb.id')
            ->where("$alias.report_number", $number)
            ->first();
        } else {
            $grn = GoodReceiptNoteDetail::where('id', $id)->first();
            $datas = DetailGoodReceiptNoteDetail::where('id_grn_detail', $number)->get();
            $number = $datas[0]->lot_number;
        }

        if ($request->ajax()) {
            return DataTables::of($datas)->make(true);
        }
        //Audit Log
        $this->auditLogsShort('View Detail History Stock WIP Lot/Report/Packing Number '.$number);

        return view('historystock.wip.history.detail', compact('dataHistories', 'product', 'datas', 'number', 'id', 'tableJoin'));
    }

    // FINISH GOOD 
    public function indexFG(Request $request)
    {
        // Datatables
        if ($request->ajax()) {
            $datas = MstFGs::select(
                'master_product_fgs.id', 'master_product_fgs.product_code', 'master_product_fgs.description',
                'master_product_fgs.thickness', 'master_product_fgs.perforasi', 'master_product_fgs.stock', 'master_product_fgs.weight',
                'master_units.unit_code', 'master_group_subs.name as sub_groupname',
            )
            ->leftjoin('master_units', 'master_product_fgs.id_master_units', 'master_units.id')
            ->leftjoin('master_group_subs', 'master_product_fgs.id_master_group_subs', 'master_group_subs.id')
            ->get();

            return DataTables::of($datas)
                ->addColumn('action', function ($data) {
                    return view('historystock.fg.action', compact('data'));
                })->make(true);
        }

        //Audit Log
        $this->auditLogsShort('View Detail History Stock FG');
        return view('historystock.fg.index');
    }
    public function historyFG(Request $request, $id)
    {
        $id = decrypt($id);
        $detail = MstFGs::select('product_code', 'description', 'stock')->where('id', $id)->first();
        $datas = HistoryStock::where('id_master_products', $id)
        ->where('type_product', 'FG')->orderBy('created_at')->get()
        ->map(function ($item) {
            $idDetail = $item->id_good_receipt_notes_details;
            $item->id_detail = null;
            $item->status_stock = null;
            $item->note_stock = null;
            $item->number = null;
            $item->status = null;
            $item->tableJoin = null;

            if (Str::startsWith($idDetail, 'RB')) {
                $rb = ReportBlow::where('report_number', $idDetail)->first();
                if ($rb) {
                    $item->id_detail = $rb->id ?? null;
                    $item->status_stock = $rb->status ?? null;
                    $item->note_stock = null;
                    $item->number = $rb->report_number ?? null;
                    $item->status = $rb->status ?? null;
                    $item->tableJoin = 'RB';
                }
            } elseif (Str::startsWith($idDetail, ['RSL', 'RF'])) {
                $rfs = ReportSf::where('report_number', $idDetail)->first();
                if ($rfs) {
                    $item->id_detail = $rfs->id ?? null;
                    $item->status_stock = $rfs->status ?? null;
                    $item->note_stock = $rfs->note ?? null;
                    $item->number = $rfs->report_number ?? null;
                    $item->status = $rfs->status ?? null;
                    $item->tableJoin = 'RSLRF';
                }
            } elseif (Str::startsWith($idDetail, 'RBM')) {
                $rbm = ReportBag::where('report_number', $idDetail)->first();
                if ($rbm) {
                    $item->id_detail = $rbm->id ?? null;
                    $item->status_stock = $rbm->status ?? null;
                    $item->note_stock = $rbm->note ?? null;
                    $item->number = $rbm->report_number ?? null;
                    $item->status = $rbm->status ?? null;
                    $item->tableJoin = 'RBM';
                }
            } elseif (is_numeric($idDetail)) {
                if ($item->type_stock == 'OUT') {
                    $packing = PackingList::where('packing_number', $idDetail)->first();
                    if ($packing) {
                        $item->id_detail = $packing->id ?? null;
                        $item->status_stock = $packing->status ?? null;
                        $item->note_stock = $packing->note ?? null;
                        $item->number = $packing->packing_number ?? null;
                        $item->status = $packing->status ?? null;
                        $item->tableJoin = 'PL';
                    }
                } else {
                    $grn = GoodReceiptNoteDetail::where('id', $idDetail)->first();
                    if ($grn) {
                        $item->id_detail = $grn->id ?? null;
                        $item->status_stock = $grn->status ?? null;
                        $item->note_stock = $grn->note ?? null;
                        $item->number = $grn->lot_number ?? null;
                        $item->status = 'Closed';
                        $item->tableJoin = 'GRN';
                    }
                }
            }

            return $item;
        })
        ->sortByDesc('created_at')
        ->unique('id_good_receipt_notes_details')
        ->values();

        $total_in = $datas->filter(function ($item) {
            return $item->type_stock === 'IN' && $item->status === 'Closed';
        })->sum('qty');
        $total_out = $datas->filter(function ($item) {
            return $item->type_stock === 'OUT' && $item->status === 'Closed';
        })->sum('qty');

        if ($request->ajax()) {
            return DataTables::of($datas)
                ->addColumn('action', function ($data) {
                    return view('historystock.fg.history.action', compact('data'));
                })->make(true);
        }
        //Audit Log
        $this->auditLogsShort('View Detail History Stock FG Code '.$detail->product_code);
        return view('historystock.fg.history.index', compact('id', 'detail', 'total_in', 'total_out'));
    }
    public function detailHistFG(Request $request, $id, $tableJoin)
    {
        $id = decrypt($id);
        $historyStock = HistoryStock::where('id', $id)->first();
        $dataHistories = HistoryStock::where('id_good_receipt_notes_details', $historyStock->id_good_receipt_notes_details)->orderBy('created_at')->get();

        $number = $historyStock->id_good_receipt_notes_details ?? null;
        $product = MstFGs::where('id', $historyStock->id_master_products)->first();

        if($tableJoin == 'PL') {
            $datas = PackingList::select('packing_lists.packing_number', 'packing_lists.date', 'packing_lists.status', 'master_customers.name as customer_name')
                ->leftJoin('master_customers', "packing_lists.id_master_customers", 'master_customers.id')
                ->where('packing_lists.packing_number', $number)
                ->first();
        } elseif (in_array($tableJoin, ['RB', 'RSLRF', 'RBM'])) {
            $modelMap = [
                'RB' => ReportBlow::class,
                'RSLRF' => ReportSf::class,
                'RBM' => ReportBag::class,
            ];
            $tableAlias = [
                'RB' => 'report_blows',
                'RSLRF' => 'report_sfs',
                'RBM' => 'report_bags',
            ];
            $model = $modelMap[$tableJoin];
            $alias = $tableAlias[$tableJoin];

            $datas = $model::select(
                "$alias.report_number", "$alias.order_name", "$alias.date",
                "$alias.status", "$alias.shift", 'master_regus.regu as regus_name',
                'master_customers.name as customer_name',
                'kr.name as kr_name', 'op.name as op_name', 'kb.name as kb_name'
            )
            ->leftJoin('master_customers', "$alias.id_master_customers", 'master_customers.id')
            ->leftJoin('master_regus', "$alias.id_master_regus", 'master_regus.id')
            ->leftJoin('master_employees as kr', "$alias.ketua_regu", 'kr.id')
            ->leftJoin('master_employees as op', "$alias.operator", 'op.id')
            ->leftJoin('master_employees as kb', "$alias.know_by", 'kb.id')
            ->where("$alias.report_number", $number)
            ->first();
            // dd($datas);
        } else {
            $grn = GoodReceiptNoteDetail::where('id', $id)->first();
            $datas = DetailGoodReceiptNoteDetail::where('id_grn_detail', $number)->get();
            $number = $datas[0]->lot_number;
        }

        if ($request->ajax()) {
            return DataTables::of($datas)->make(true);
        }
        //Audit Log
        $this->auditLogsShort('View Detail History Stock FG Lot/Report/Packing Number '.$number);

        return view('historystock.fg.history.detail', compact('dataHistories', 'product', 'datas', 'number', 'id', 'tableJoin'));
    }

    // TOOL & AUXALARY
    public function indexTA(Request $request)
    {
        // Datatables
        if ($request->ajax()) {
            $datas = MstSpareparts::select(
                'master_tool_auxiliaries.id', 'master_tool_auxiliaries.code', 'master_tool_auxiliaries.description',
                'master_tool_auxiliaries.type', 'master_tool_auxiliaries.stock',
                'master_units.unit_code'
            )
            ->leftjoin('master_units', 'master_tool_auxiliaries.id_master_units', 'master_units.id')
            ->get();

            return DataTables::of($datas)
                ->addColumn('action', function ($data) {
                    return view('historystock.ta.action', compact('data'));
                })->make(true);
        }

        //Audit Log
        $this->auditLogsShort('View Detail History Stock FG');
        return view('historystock.ta.index');
    }
    public function historyTA(Request $request, $id)
    {
        $id = decrypt($id);
        $detail = MstSpareparts::select('code', 'description', 'stock')->where('id', $id)->first();
        $datas = HistoryStock::where('id_master_products', $id)
        ->whereIn('type_product', ['TA', 'Other'])->orderBy('created_at')->get()
        ->map(function ($item) {
            $idDetail = $item->id_good_receipt_notes_details;
            $item->id_detail = null;
            $item->status_stock = null;
            $item->note_stock = null;
            $item->number = null;
            $item->status = null;
            $item->tableJoin = null;

            if (Str::startsWith($idDetail, 'RB')) {
                $rb = ReportBlow::where('report_number', $idDetail)->first();
                if ($rb) {
                    $item->id_detail = $rb->id ?? null;
                    $item->status_stock = $rb->status ?? null;
                    $item->note_stock = null;
                    $item->number = $rb->report_number ?? null;
                    $item->status = $rb->status ?? null;
                    $item->tableJoin = 'RB';
                }
            } elseif (Str::startsWith($idDetail, ['RSL', 'RF'])) {
                $rfs = ReportSf::where('report_number', $idDetail)->first();
                if ($rfs) {
                    $item->id_detail = $rfs->id ?? null;
                    $item->status_stock = $rfs->status ?? null;
                    $item->note_stock = $rfs->note ?? null;
                    $item->number = $rfs->report_number ?? null;
                    $item->status = $rfs->status ?? null;
                    $item->tableJoin = 'RSLRF';
                }
            } elseif (Str::startsWith($idDetail, 'RBM')) {
                $rbm = ReportBag::where('report_number', $idDetail)->first();
                if ($rbm) {
                    $item->id_detail = $rbm->id ?? null;
                    $item->status_stock = $rbm->status ?? null;
                    $item->note_stock = $rbm->note ?? null;
                    $item->number = $rbm->report_number ?? null;
                    $item->status = $rbm->status ?? null;
                    $item->tableJoin = 'RBM';
                }
            } elseif (is_numeric($idDetail)) {
                if ($item->type_stock == 'OUT') {
                    $packing = PackingList::where('packing_number', $idDetail)->first();
                    if ($packing) {
                        $item->id_detail = $packing->id ?? null;
                        $item->status_stock = $packing->status ?? null;
                        $item->note_stock = $packing->note ?? null;
                        $item->number = $packing->packing_number ?? null;
                        $item->status = $packing->status ?? null;
                        $item->tableJoin = 'PL';
                    }
                } else {
                    $grn = GoodReceiptNoteDetail::where('id', $idDetail)->first();
                    if ($grn) {
                        $item->id_detail = $grn->id ?? null;
                        $item->status_stock = $grn->status ?? null;
                        $item->note_stock = $grn->note ?? null;
                        $item->number = $grn->lot_number ?? null;
                        $item->status = 'Closed';
                        $item->tableJoin = 'GRN';
                    }
                }
            }

            return $item;
        })
        ->sortByDesc('created_at')
        ->unique('id_good_receipt_notes_details')
        ->values();

        $total_in = $datas->filter(function ($item) {
            return $item->type_stock === 'IN' && $item->status === 'Closed';
        })->sum('qty');
        $total_out = $datas->filter(function ($item) {
            return $item->type_stock === 'OUT' && $item->status === 'Closed';
        })->sum('qty');

        if ($request->ajax()) {
            return DataTables::of($datas)
                ->addColumn('action', function ($data) {
                    return view('historystock.ta.history.action', compact('data'));
                })->make(true);
        }
        //Audit Log
        $this->auditLogsShort('View Detail History Stock TA Code '.$detail->product_code);
        return view('historystock.ta.history.index', compact('id', 'detail', 'total_in', 'total_out'));
    }
    public function detailHistTA(Request $request, $id, $tableJoin)
    {
        $id = decrypt($id);
        $historyStock = HistoryStock::where('id', $id)->first();
        $dataHistories = HistoryStock::where('id_good_receipt_notes_details', $historyStock->id_good_receipt_notes_details)->orderBy('created_at')->get();

        $number = $historyStock->id_good_receipt_notes_details ?? null;
        $product = MstSpareparts::where('id', $historyStock->id_master_products)->first();

        if($tableJoin == 'PL') {
            $datas = PackingList::select('packing_lists.packing_number', 'packing_lists.date', 'packing_lists.status', 'master_customers.name as customer_name')
                ->leftJoin('master_customers', "packing_lists.id_master_customers", 'master_customers.id')
                ->where('packing_lists.packing_number', $number)
                ->first();
        } elseif (in_array($tableJoin, ['RB', 'RSLRF', 'RBM'])) {
            $modelMap = [
                'RB' => ReportBlow::class,
                'RSLRF' => ReportSf::class,
                'RBM' => ReportBag::class,
            ];
            $tableAlias = [
                'RB' => 'report_blows',
                'RSLRF' => 'report_sfs',
                'RBM' => 'report_bags',
            ];
            $model = $modelMap[$tableJoin];
            $alias = $tableAlias[$tableJoin];

            $datas = $model::select(
                "$alias.report_number", "$alias.order_name", "$alias.date",
                "$alias.status", "$alias.shift", 'master_regus.regu as regus_name',
                'master_customers.name as customer_name',
                'kr.name as kr_name', 'op.name as op_name', 'kb.name as kb_name'
            )
            ->leftJoin('master_customers', "$alias.id_master_customers", 'master_customers.id')
            ->leftJoin('master_regus', "$alias.id_master_regus", 'master_regus.id')
            ->leftJoin('master_employees as kr', "$alias.ketua_regu", 'kr.id')
            ->leftJoin('master_employees as op', "$alias.operator", 'op.id')
            ->leftJoin('master_employees as kb', "$alias.know_by", 'kb.id')
            ->where("$alias.report_number", $number)
            ->first();
            // dd($datas);
        } else {
            $grn = GoodReceiptNoteDetail::where('id', $id)->first();
            $datas = DetailGoodReceiptNoteDetail::where('id_grn_detail', $number)->get();
            $number = $datas[0]->lot_number;
        }

        if ($request->ajax()) {
            return DataTables::of($datas)->make(true);
        }
        //Audit Log
        $this->auditLogsShort('View Detail History Stock TA Lot/Report/Packing Number '.$number);

        return view('historystock.ta.history.detail', compact('dataHistories', 'product', 'datas', 'number', 'id', 'tableJoin'));
    }


    public function barcode(Request $request, $barcode)
    {
        $datas = BarcodeDetail::where('barcode_number', $barcode)->get();
        if ($request->ajax()) {
            return DataTables::of($datas)->make(true);
        }
        //Audit Log
        $this->auditLogsShort('View Detail Barcode History Stock');

        return view('historystock.barcode_detail', compact('barcode'));
    }
}
