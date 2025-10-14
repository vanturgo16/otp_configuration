<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use DB;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Exports\StockProdExport;
use App\Exports\StockRMExport;
use App\Exports\StockWIPExport;
use App\Exports\StockFGExport;
use App\Exports\StockTAExport;

// Model
use App\Models\HistoryStock;
use App\Models\MstFGs;
use App\Models\MstRawMaterials;
use App\Models\MstSpareparts;
use App\Models\MstWips;
use App\Models\BarcodeDetail;
use App\Models\DetailGoodReceiptNoteDetail;
use App\Models\GoodReceiptNoteDetail;
use App\Models\LMTS;
use App\Models\PackingList;
use App\Models\ReportBag;
use App\Models\ReportBlow;
use App\Models\ReportSf;
use App\Models\MstGroupSubs;
use App\Models\RecapStocks;

class HistoryStockController extends Controller
{
    use AuditLogsTrait;
    
    // RAW MATERIAL
    public function indexRM(Request $request)
    {
        // Search & Filter Variable
        $rm_code = $request->get('rm_code');
        $description = $request->get('description');

        $datas = MstRawMaterials::select('master_raw_materials.*', 'master_units.unit_code')
            ->leftjoin('master_units', 'master_raw_materials.id_master_units', 'master_units.id');
        // Search & Filter
        if($rm_code != null){
            $datas = $datas->where('rm_code', 'like', '%'.$rm_code.'%');
        }
        if($description != null){
            $datas = $datas->where('description', 'like', '%'.$description.'%');
        }
        // Final Data
        $datas = $datas->orderBy('created_at', 'desc')->get();

        // Datatables
        if ($request->ajax()) {
            return DataTables::of($datas)
                ->addColumn('action', function ($data) {
                    return view('historystock.rm.action', compact('data'));
                })->make(true);
        }

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

        //Audit Log
        $this->auditLogsShort('View Detail History Stock RM');
        return view('historystock.rm.index', compact('rm_code', 'description', 'idUpdated', 'page_number'));
    }
    public function historyRM(Request $request, $id)
    {
        $id = decrypt($id);
        // Search & Filter Variable
        $searchDate = $request->get('searchDate');
        $month = $request->get('month');

        $detail = MstRawMaterials::select('rm_code', 'description', 'stock')->where('id', $id)->first();
        $query = HistoryStock::where('id_master_products', $id)->where('type_product', 'RM');

        if ($month) {
            [$year, $monthNum] = explode('-', $month);
            $query->whereYear('date', $year)->whereMonth('date', $monthNum);
        }

        // Execute the query and process the results
        $datas = $query->orderBy('date')->get()->map(function ($item) {
            $idDetail = $item->id_good_receipt_notes_details;
            $item->id_detail = null;
            $item->note_stock = null;
            $item->number = null;
            $item->status = null;
            $item->tableJoin = null;
            $item->source = null;

            if (Str::contains($idDetail, 'LMTS')) {
                $lmts = LMTS::where('no_lmts', $idDetail)->first();
                if ($lmts) {
                    $item->id_detail = $lmts->id ?? null;
                    $item->note_stock = $lmts->lmts_notes ?? null;
                    $item->number = $lmts->no_lmts ?? null;
                    $item->status = 'Closed';
                    $item->tableJoin = 'LMTS';
                    $item->source = 'LMTS';
                }
            } elseif (Str::startsWith($idDetail, 'RBM')) {
                $rbm = ReportBag::where('report_number', $idDetail)->first();
                if ($rbm) {
                    $item->id_detail = $rbm->id ?? null;
                    $item->note_stock = $rbm->note ?? null;
                    $item->number = $rbm->report_number ?? null;
                    $item->status = $rbm->status ?? null;
                    $item->tableJoin = 'RBM';
                    $item->source = 'Report';
                }
            } elseif (Str::startsWith($idDetail, ['RSL', 'RF'])) {
                $rfs = ReportSf::where('report_number', $idDetail)->first();
                if ($rfs) {
                    $item->id_detail = $rfs->id ?? null;
                    $item->note_stock = $rfs->note ?? null;
                    $item->number = $rfs->report_number ?? null;
                    $item->status = $rfs->status ?? null;
                    $item->tableJoin = 'RSLRF';
                    $item->source = 'Report';
                }
            } elseif (Str::startsWith($idDetail, 'RB')) {
                $rb = ReportBlow::where('report_number', $idDetail)->first();
                if ($rb) {
                    $item->id_detail = $rb->id ?? null;
                    $item->note_stock = null;
                    $item->number = $rb->report_number ?? null;
                    $item->status = $rb->status ?? null;
                    $item->tableJoin = 'RB';
                    $item->source = 'Report';
                }
            } elseif (is_numeric($idDetail)) {
                if ($item->type_stock == 'OUT') {
                    $packing = PackingList::where('packing_number', $idDetail)->first();
                    if ($packing) {
                        $item->id_detail = $packing->id ?? null;
                        $item->note_stock = $packing->note ?? null;
                        $item->number = $packing->packing_number ?? null;
                        $item->status = $packing->status ?? null;
                        $item->tableJoin = 'PL';
                        $item->source = 'Packing List';
                    }
                } elseif ($item->type_stock == 'IN') {
                    $grn = GoodReceiptNoteDetail::where('id', $idDetail)->first();
                    if ($grn) {
                        $item->id_detail = $grn->id ?? null;
                        $item->note_stock = $grn->note ?? null;
                        $item->number = $grn->lot_number ?? null;
                        $item->status = 'Closed';
                        $item->tableJoin = 'GRN';
                        $item->source = 'GRN';
                    }
                } else {
                    $item->id_detail = 'Undefined';
                    $item->note_stock = $item->remarks ?? null;
                    $item->number = $item->id_good_receipt_notes_details ?? null;
                    $item->status = 'Undefined';
                    $item->source = 'Undefined';
                }
            } else {
                $item->id_detail = 'Undefined';
                $item->note_stock = $item->remarks ?? null;
                $item->number = $item->id_good_receipt_notes_details ?? null;
                $item->status = 'Undefined';
                $item->source = 'Undefined';
            }
            return $item;
        })
        ->sortByDesc('id')
        ->unique('id_good_receipt_notes_details')
        ->values();

        $total_in = $datas->filter(function ($item) {
            return $item->type_stock === 'IN' && $item->status === 'Closed';
        })->sum('qty');
        $total_out = $datas->filter(function ($item) {
            return $item->type_stock === 'OUT' && $item->status === 'Closed';
        })->sum('qty');
        $total = $total_in - $total_out;

        if ($request->ajax()) {
            return DataTables::of($datas)
                ->addColumn('action', function ($data) {
                    return view('historystock.rm.history.action', compact('data'));
                })->make(true);
        }

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
        
        //Audit Log
        $this->auditLogsShort('View Detail History Stock RM Code '.$detail->rm_code);
        return view('historystock.rm.history.index', compact('id', 'searchDate', 'month', 'detail', 'total_in', 'total_out', 'total', 'idUpdated', 'page_number'));
    }
    public function detailHistRM(Request $request, $id, $tableJoin)
    {
        $id = decrypt($id);

        $historyStock = HistoryStock::where('id', $id)->first();
        $dataHistories = HistoryStock::where('id_good_receipt_notes_details', $historyStock->id_good_receipt_notes_details)->orderBy('created_at')->get();

        $number = $historyStock->id_good_receipt_notes_details ?? null;
        $product = MstRawMaterials::where('id', $historyStock->id_master_products)->first();
        $fromGRN = false;

        if($tableJoin == 'LMTS') {
            $datas = LMTS::where('no_lmts', $number)->first();
        } elseif($tableJoin == 'PL') {
            $datas = PackingList::select('packing_lists.packing_number', 'packing_lists.date', 'packing_lists.status', 'master_customers.name as customer_name')
                ->leftJoin('master_customers', "packing_lists.id_master_customers", 'master_customers.id')
                ->where('packing_lists.packing_number', $number)
                ->first();
        } elseif (in_array($tableJoin, ['RB', 'RSLRF', 'RBM'])) {
            $columnKnown = [
                'RB' => 'know_by',
                'RSLRF' => 'know_by',
                'RBM' => 'known_by',
            ];
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
            $known = $columnKnown[$tableJoin];
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
            ->leftJoin('master_employees as kb', "$alias.$known", 'kb.id')
            ->where("$alias.report_number", $number)
            ->first();
        } else {
            $fromGRN = true;
            $datas = DetailGoodReceiptNoteDetail::select(
                    'detail_good_receipt_note_details.id',
                    'detail_good_receipt_note_details.id_grn',
                    'detail_good_receipt_note_details.id_grn_detail',
                    'good_receipt_notes.receipt_number',
                    'good_receipt_notes.date as date_grn',
                    'good_receipt_note_details.lot_number',
                    'good_receipt_note_details.qc_passed',
                    'good_receipt_note_details.note',
                    'good_receipt_note_details.master_units_id',
                    'good_receipt_note_details.type_product',
                    'detail_good_receipt_note_details.ext_lot_number',
                    'detail_good_receipt_note_details.qty',
                    'detail_good_receipt_note_details.qty_out',
                    DB::raw('ROUND(detail_good_receipt_note_details.qty - detail_good_receipt_note_details.qty_out, 2) as glq'),
                    'master_units.unit_code',
                    'lmts.no_lmts',
                    'lmts.date as lmts_date',
                    'lmts.updated_at as lmts_last_updated',
                    'lmts.status as lmts_status',
                    'lmts.button_active as lmts_disposisi',
                    'lmts.remarks as hold_remarks',
                    'lmts.lmts_notes as lmts_remarks',
                )
                ->leftJoin('good_receipt_note_details', 'detail_good_receipt_note_details.id_grn_detail', 'good_receipt_note_details.id')
                ->leftJoin('good_receipt_notes', 'detail_good_receipt_note_details.id_grn', 'good_receipt_notes.id')
                ->leftJoin('master_units', 'good_receipt_note_details.master_units_id', 'master_units.id')
                ->leftJoin('lmts', 'detail_good_receipt_note_details.id', 'lmts.id_detail_grn_detail')
                ->where('detail_good_receipt_note_details.id_grn_detail', $number)
                ->get();
            $number = $datas[0]->lot_number;
        }
        // dd($datas);

        if ($request->ajax()) {
            if($fromGRN){
                return DataTables::of($datas)
                ->addColumn('action', function ($data) use ($product){
                    return view('historystock.action_detail', compact('data', 'product'));
                })->make(true);
            }
            return DataTables::of($datas)->make(true);
        }
        //Audit Log
        $this->auditLogsShort('View Detail History Stock RM Lot/Report/Packing/LMTS Number '.$number);

        return view('historystock.rm.history.detail', compact('dataHistories', 'product', 'datas', 'number', 'id', 'tableJoin'));
    }
    public function exportRM(Request $request)
    {
        $keyword = $request->get('keyword');
        $month = $request->get('month');

        $query = HistoryStock::select(
                'history_stocks.type_product', 'history_stocks.qty', 'history_stocks.type_stock', 'history_stocks.date', 
                'history_stocks.id_good_receipt_notes_details', 'history_stocks.created_at', 'history_stocks.remarks',
                'master_raw_materials.rm_code as code', 'master_raw_materials.description'
            )
            ->leftjoin('master_raw_materials', 'history_stocks.id_master_products', 'master_raw_materials.id')
            ->whereIn('history_stocks.type_stock', ['IN', 'OUT'])
            ->where('history_stocks.type_product', 'RM');

        // Apply date range filter if provided
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('master_raw_materials.rm_code', 'like', '%' . $keyword . '%')
                    ->orWhere('master_raw_materials.description', 'like', '%' . $keyword . '%');
            });
        }
        if ($month) {
            [$year, $monthNum] = explode('-', $month);
            $query->whereYear('history_stocks.date', $year)->whereMonth('history_stocks.date', $monthNum);
        }

        // Execute the query and process the results
        $datas = $query->orderBy('history_stocks.date', 'asc')->get()
        ->map(function ($item) {
            $idDetail = $item->id_good_receipt_notes_details;
            $item->status = null;

            if (Str::contains($idDetail, 'LMTS')) {
                $lmts = LMTS::where('no_lmts', $idDetail)->first();
                if ($lmts) {
                    $item->status = 'Closed';
                }
            } elseif (Str::startsWith($idDetail, 'RBM')) {
                $rbm = ReportBag::where('report_number', $idDetail)->first();
                if ($rbm) {
                    $item->status = $rbm->status ?? null;
                }
            } elseif (Str::startsWith($idDetail, ['RSL', 'RF'])) {
                $rfs = ReportSf::where('report_number', $idDetail)->first();
                if ($rfs) {
                    $item->status = $rfs->status ?? null;
                }
            } elseif (Str::startsWith($idDetail, 'RB')) {
                $rb = ReportBlow::where('report_number', $idDetail)->first();
                if ($rb) {
                    $item->status = $rb->status ?? null;
                }
            } elseif (is_numeric($idDetail)) {
                if ($item->type_stock == 'OUT') {
                    $packing = PackingList::where('packing_number', $idDetail)->first();
                    if ($packing) {
                        $item->status = $packing->status ?? null;
                    }
                } elseif ($item->type_stock == 'IN') {
                    $grn = GoodReceiptNoteDetail::where('id', $idDetail)->first();
                    if ($grn) {
                        $item->status = 'Closed';
                    }
                } else {
                    $item->status = 'Closed';
                }
            } else {
                $item->status = 'Closed';
            }
            return $item;
        })
        ->filter(function ($item) {
            return $item->status === 'Closed';
        })
        ->unique('id_good_receipt_notes_details')
        ->sortByDesc('id')
        ->values();

        $totalIn = $totalOut = 0;
        $totalIn = $datas->filter(function ($item) {
            return $item->type_stock === 'IN';
        })->sum('qty');
        $totalOut = $datas->filter(function ($item) {
            return $item->type_stock === 'OUT';
        })->sum('qty');
        $allTotal = [
            'totalIn' => $totalIn,
            'totalOut' => $totalOut,
        ];

        $exportType = $request->get('export_type') ?? 'excel';
        if ($exportType === 'pdf') {
            $pdf = PDF::loadView('exports.pdf.stock', [
                'typeProd' => 'RM',
                'datas' => $datas,
                'request' => $request,
                'allTotal' => $allTotal,
                'month' => $month,
                'exportedBy' => auth()->user()->email,
                'exportedAt' => now()->format('d-m-Y H:i:s'),
            ])->setPaper('A4', 'portrait');
            $filename = 'Print_Stock_RM_' . Carbon::now()->format('d_m_Y_H_i') . '.pdf';
            return $pdf->download($filename);
        } else {
            $filename = 'Export_Stock_RM_' . Carbon::now()->format('d_m_Y_H_i') . '.xlsx';
            return Excel::download(new StockRMExport($datas, $request, $allTotal), $filename);
        }
    }
    public function exportRMProd(Request $request, $id)
    {
        $id = decrypt($id);
        $month = $request->get('month');

        $type = 'RM';
        $detail = MstRawMaterials::select('rm_code', 'description')->where('id', $id)->first();
        $query = HistoryStock::select(
                'type_product', 'qty', 'type_stock', 'date', 
                'id_good_receipt_notes_details', 'created_at', 'remarks'
            )
            ->whereIn('type_stock', ['IN', 'OUT'])
            ->where('id_master_products', $id)
            ->where('type_product', $type);

        if ($month) {
            [$year, $monthNum] = explode('-', $month);
            $query->whereYear('date', $year)->whereMonth('date', $monthNum);
        }

        // Execute the query and process the results
        $datas = $query->orderBy('date', 'asc')->get()
        ->map(function ($item) {
            $idDetail = $item->id_good_receipt_notes_details;
            $item->number = null;
            $item->status = null;

            if (Str::contains($idDetail, 'LMTS')) {
                $lmts = LMTS::where('no_lmts', $idDetail)->first();
                if ($lmts) {
                    $item->number = $lmts->no_lmts ?? null;
                    $item->status = 'Closed';
                }
            } elseif (Str::startsWith($idDetail, 'RBM')) {
                $rbm = ReportBag::where('report_number', $idDetail)->first();
                if ($rbm) {
                    $item->number = $rbm->report_number ?? null;
                    $item->status = $rbm->status ?? null;
                }
            } elseif (Str::startsWith($idDetail, ['RSL', 'RF'])) {
                $rfs = ReportSf::where('report_number', $idDetail)->first();
                if ($rfs) {
                    $item->number = $rfs->report_number ?? null;
                    $item->status = $rfs->status ?? null;
                }
            } elseif (Str::startsWith($idDetail, 'RB')) {
                $rb = ReportBlow::where('report_number', $idDetail)->first();
                if ($rb) {
                    $item->number = $rb->report_number ?? null;
                    $item->status = $rb->status ?? null;
                }
            } elseif (is_numeric($idDetail)) {
                if ($item->type_stock == 'OUT') {
                    $packing = PackingList::where('packing_number', $idDetail)->first();
                    if ($packing) {
                        $item->number = $packing->packing_number ?? null;
                        $item->status = $packing->status ?? null;
                    }
                } elseif ($item->type_stock == 'IN') {
                    $grn = GoodReceiptNoteDetail::where('id', $idDetail)->first();
                    if ($grn) {
                        $item->number = $grn->lot_number ?? null;
                        $item->status = 'Closed';
                    }
                } else {
                    $item->number = 'Undefined';
                    $item->status = 'Closed';
                }
            } else {
                $item->number = 'Undefined';
                $item->status = 'Closed';
            }

            return $item;
        })
        ->filter(function ($item) {
            return $item->status === 'Closed';
        })
        ->unique('id_good_receipt_notes_details')
        ->sortByDesc('id')
        ->values();

        $totalIn = $totalOut = 0;
        $totalIn = $datas->filter(function ($item) {
            return $item->type_stock === 'IN';
        })->sum('qty');
        $totalOut = $datas->filter(function ($item) {
            return $item->type_stock === 'OUT';
        })->sum('qty');
        $sumTotal = $totalIn - $totalOut;

        $recap = RecapStocks::where('type_product', 'RM')->where('id_master_product', $id)->where('period', $month)->first();

        $allTotal = [
            'totalIn' => $totalIn,
            'totalOut' => $totalOut,
            'sumTotal' => $sumTotal,
            'InitialStock' => $recap->qty_start ?? 0,
            'EndingStock' => $recap->qty_end ?? 0,
        ];

        $exportType = $request->get('export_type') ?? 'excel';
        if ($exportType === 'pdf') {
            $pdf = PDF::loadView('exports.pdf.stock_prod', [
                'typeProd' => $type,
                'detail' => $detail,
                'datas' => $datas,
                'request' => $request,
                'allTotal' => $allTotal,
                'month' => $month,
                'exportedBy' => auth()->user()->email,
                'exportedAt' => now()->format('d-m-Y H:i:s'),
            ])->setPaper('A4', 'portrait');
            $filename = 'Print_Stock_RM_Prod_' . Carbon::now()->format('d_m_Y_H_i') . '.pdf';
            return $pdf->download($filename);
        } else {
            $filename = 'Export_Stock_RM_Prod_' . Carbon::now()->format('d_m_Y_H_i') . '.xlsx';
            return Excel::download(new StockProdExport($type, $detail, $datas, $request, $allTotal), $filename);
        }
    }

    // WORK IN PROGRESS
    public function indexWIP(Request $request)
    {
        // Variable
        $group_subs = MstGroupSubs::get();

        // Search & Filter Variable
        $wip_code = $request->get('wip_code');
        $description = $request->get('description');
        $type = $request->get('type');
        $thickness = $request->get('thickness');
        $id_master_group_subs = $request->get('id_master_group_subs');
        
        $datas = MstWips::select(
            'master_wips.id', 'master_wips.wip_code', 'master_wips.description',
            'master_wips.thickness', 'master_wips.type', 'master_wips.stock', 'master_wips.weight', 'master_wips.weight_stock',
            'master_units.unit_code', 'master_group_subs.name as sub_groupname',
        )
            ->leftjoin('master_units', 'master_wips.id_master_units', 'master_units.id')
            ->leftjoin('master_group_subs', 'master_wips.id_master_group_subs', 'master_group_subs.id');
        // Search & Filter
        if($wip_code != null){
            $datas = $datas->where('master_wips.wip_code', 'like', '%'.$wip_code.'%');
        }
        if($description != null){
            $datas = $datas->where('master_wips.description', 'like', '%'.$description.'%');
        }
        if ($type != null) {
            $datas->where('master_wips.type', $type);
        }
        if ($thickness != null) {
            $datas->where('master_wips.thickness', $thickness);
        }
        if ($id_master_group_subs != null) {
            $datas->where('master_wips.id_master_group_subs', $id_master_group_subs);
        }
        // Final Data
        $datas = $datas->orderBy('master_wips.created_at', 'desc')->get();

        // Datatables
        if ($request->ajax()) {
            return DataTables::of($datas)
                ->addColumn('action', function ($data) {
                    return view('historystock.wip.action', compact('data'));
                })->make(true);
        }

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

        //Audit Log
        $this->auditLogsShort('View Detail History Stock WIP');
        return view('historystock.wip.index', compact(
            'group_subs',
            'wip_code', 'description', 'type', 'thickness', 'id_master_group_subs', 'idUpdated', 'page_number'
        ));
    }
    public function historyWIP(Request $request, $id)
    {
        $id = decrypt($id);
        // Search & Filter Variable
        $searchDate = $request->get('searchDate');
        $month = $request->get('month');

        $detail = MstWips::select('wip_code', 'description', 'stock')->where('id', $id)->first();
        $query = HistoryStock::where('id_master_products', $id)->where('type_product', 'WIP');
        
        if ($month) {
            [$year, $monthNum] = explode('-', $month);
            $query->whereYear('date', $year)->whereMonth('date', $monthNum);
        }

        // Execute the query and process the results
        $datas = $query->orderBy('date')->get()
        ->map(function ($item) {
            $idDetail = $item->id_good_receipt_notes_details;
            $item->id_detail = null;
            $item->note_stock = null;
            $item->number = null;
            $item->status = null;
            $item->tableJoin = null;
            $item->source = null;

            if (Str::contains($idDetail, 'LMTS')) {
                $lmts = LMTS::where('no_lmts', $idDetail)->first();
                if ($lmts) {
                    $item->id_detail = $lmts->id ?? null;
                    $item->note_stock = $lmts->lmts_notes ?? null;
                    $item->number = $lmts->no_lmts ?? null;
                    $item->status = 'Closed';
                    $item->tableJoin = 'LMTS';
                    $item->source = 'LMTS';
                }
            } elseif (Str::startsWith($idDetail, 'RBM')) {
                $rbm = ReportBag::where('report_number', $idDetail)->first();
                if ($rbm) {
                    $item->id_detail = $rbm->id ?? null;
                    $item->note_stock = $rbm->note ?? null;
                    $item->number = $rbm->report_number ?? null;
                    $item->status = $rbm->status ?? null;
                    $item->tableJoin = 'RBM';
                    $item->source = 'Report';
                }
            } elseif (Str::startsWith($idDetail, ['RSL', 'RF'])) {
                $rfs = ReportSf::where('report_number', $idDetail)->first();
                if ($rfs) {
                    $item->id_detail = $rfs->id ?? null;
                    $item->note_stock = $rfs->note ?? null;
                    $item->number = $rfs->report_number ?? null;
                    $item->status = $rfs->status ?? null;
                    $item->tableJoin = 'RSLRF';
                    $item->source = 'Report';
                }
            } elseif (Str::startsWith($idDetail, 'RB')) {
                $rb = ReportBlow::where('report_number', $idDetail)->first();
                if ($rb) {
                    $item->id_detail = $rb->id ?? null;
                    $item->note_stock = null;
                    $item->number = $rb->report_number ?? null;
                    $item->status = $rb->status ?? null;
                    $item->tableJoin = 'RB';
                    $item->source = 'Report';
                }
            } elseif (is_numeric($idDetail)) {
                if ($item->type_stock == 'OUT') {
                    $packing = PackingList::where('packing_number', $idDetail)->first();
                    if ($packing) {
                        $item->id_detail = $packing->id ?? null;
                        $item->note_stock = $packing->note ?? null;
                        $item->number = $packing->packing_number ?? null;
                        $item->status = $packing->status ?? null;
                        $item->tableJoin = 'PL';
                        $item->source = 'Packing List';
                    }
                } elseif ($item->type_stock == 'IN') {
                    $grn = GoodReceiptNoteDetail::where('id', $idDetail)->first();
                    if ($grn) {
                        $item->id_detail = $grn->id ?? null;
                        $item->note_stock = $grn->note ?? null;
                        $item->number = $grn->lot_number ?? null;
                        $item->status = 'Closed';
                        $item->tableJoin = 'GRN';
                        $item->source = 'GRN';
                    }
                } else {
                    $item->id_detail = 'Undefined';
                    $item->note_stock = $item->remarks ?? null;
                    $item->number = $item->id_good_receipt_notes_details ?? null;
                    $item->status = 'Undefined';
                    $item->source = 'Undefined';
                }
            } else {
                $item->id_detail = 'Undefined';
                $item->note_stock = $item->remarks ?? null;
                $item->number = $item->id_good_receipt_notes_details ?? null;
                $item->status = 'Undefined';
                $item->source = 'Undefined';
            }
            return $item;
        })
        ->sortByDesc('id')
        ->unique('id_good_receipt_notes_details')
        ->values();

        $total_in = $datas->filter(function ($item) {
            return $item->type_stock === 'IN' && $item->status === 'Closed';
        })->sum('qty');
        $total_out = $datas->filter(function ($item) {
            return $item->type_stock === 'OUT' && $item->status === 'Closed';
        })->sum('qty');
        $total = $total_in - $total_out;

        if ($request->ajax()) {
            return DataTables::of($datas)
                ->addColumn('action', function ($data) {
                    return view('historystock.wip.history.action', compact('data'));
                })->make(true);
        }

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

        //Audit Log
        $this->auditLogsShort('View Detail History Stock WIP Code '.$detail->wip_code);
        return view('historystock.wip.history.index', compact('id', 'searchDate', 'month', 'detail', 'total_in', 'total_out', 'total', 'idUpdated', 'page_number'));
    }
    public function detailHistWIP(Request $request, $id, $tableJoin)
    {
        $id = decrypt($id);

        $historyStock = HistoryStock::where('id', $id)->first();
        $dataHistories = HistoryStock::where('id_good_receipt_notes_details', $historyStock->id_good_receipt_notes_details)->orderBy('created_at')->get();

        $number = $historyStock->id_good_receipt_notes_details ?? null;
        $product = MstWips::where('id', $historyStock->id_master_products)->first();
        $fromGRN = false;

        if($tableJoin == 'LMTS') {
            $datas = LMTS::where('no_lmts', $number)->first();
        } elseif($tableJoin == 'PL') {
            $datas = PackingList::select('packing_lists.packing_number', 'packing_lists.date', 'packing_lists.status', 'master_customers.name as customer_name')
                ->leftJoin('master_customers', "packing_lists.id_master_customers", 'master_customers.id')
                ->where('packing_lists.packing_number', $number)
                ->first();
        } elseif (in_array($tableJoin, ['RB', 'RSLRF', 'RBM'])) {
            $columnKnown = [
                'RB' => 'know_by',
                'RSLRF' => 'know_by',
                'RBM' => 'known_by',
            ];
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
            $known = $columnKnown[$tableJoin];
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
            ->leftJoin('master_employees as kb', "$alias.$known", 'kb.id')
            ->where("$alias.report_number", $number)
            ->first();
        } else {
            $fromGRN = true;
            $datas = DetailGoodReceiptNoteDetail::select(
                    'detail_good_receipt_note_details.id',
                    'detail_good_receipt_note_details.id_grn',
                    'detail_good_receipt_note_details.id_grn_detail',
                    'good_receipt_notes.receipt_number',
                    'good_receipt_notes.date as date_grn',
                    'good_receipt_note_details.lot_number',
                    'good_receipt_note_details.qc_passed',
                    'good_receipt_note_details.note',
                    'good_receipt_note_details.master_units_id',
                    'good_receipt_note_details.type_product',
                    'detail_good_receipt_note_details.ext_lot_number',
                    'detail_good_receipt_note_details.qty',
                    'detail_good_receipt_note_details.qty_out',
                    DB::raw('ROUND(detail_good_receipt_note_details.qty - detail_good_receipt_note_details.qty_out, 2) as glq'),
                    'master_units.unit_code',
                    'lmts.no_lmts',
                    'lmts.date as lmts_date',
                    'lmts.updated_at as lmts_last_updated',
                    'lmts.status as lmts_status',
                    'lmts.button_active as lmts_disposisi',
                    'lmts.remarks as hold_remarks',
                    'lmts.lmts_notes as lmts_remarks',
                )
                ->leftJoin('good_receipt_note_details', 'detail_good_receipt_note_details.id_grn_detail', 'good_receipt_note_details.id')
                ->leftJoin('good_receipt_notes', 'detail_good_receipt_note_details.id_grn', 'good_receipt_notes.id')
                ->leftJoin('master_units', 'good_receipt_note_details.master_units_id', 'master_units.id')
                ->leftJoin('lmts', 'detail_good_receipt_note_details.id', 'lmts.id_detail_grn_detail')
                ->where('detail_good_receipt_note_details.id_grn_detail', $number)
                ->get();
            $number = $datas[0]->lot_number;
        }
        // dd($datas);

        if ($request->ajax()) {
            if($fromGRN){
                return DataTables::of($datas)
                ->addColumn('action', function ($data) use ($product){
                    return view('historystock.action_detail', compact('data', 'product'));
                })->make(true);
            }
            return DataTables::of($datas)->make(true);
        }
        //Audit Log
        $this->auditLogsShort('View Detail History Stock WIP Lot/Report/Packing/LMTS Number '.$number);

        return view('historystock.wip.history.detail', compact('dataHistories', 'product', 'datas', 'number', 'id', 'tableJoin'));
    }
    public function exportWIP(Request $request)
    {
        $keyword = $request->get('keyword');
        $type = $request->get('type');
        $thickness = $request->get('thickness');
        $id_master_group_subs = $request->get('id_master_group_subs');
        $month = $request->get('month');

        $query = HistoryStock::select(
                'history_stocks.type_product', 'history_stocks.qty', 'history_stocks.type_stock', 'history_stocks.date', 
                'history_stocks.id_good_receipt_notes_details', 'history_stocks.created_at', 'history_stocks.remarks',
                'master_wips.wip_code as code', 'master_wips.description'
            )
            ->leftjoin('master_wips', 'history_stocks.id_master_products', 'master_wips.id')
            ->whereIn('history_stocks.type_stock', ['IN', 'OUT'])
            ->where('history_stocks.type_product', 'WIP');

        // Apply date range filter if provided
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('master_wips.wip_code', 'like', '%' . $keyword . '%')
                    ->orWhere('master_wips.description', 'like', '%' . $keyword . '%');
            });
        }
        if ($type != null) {
            $query->where('master_wips.type', $type);
        }
        if ($thickness != null) {
            $query->where('master_wips.thickness', $thickness);
        }
        $group_subs = null;
        if ($id_master_group_subs != null) {
            $query->where('master_wips.id_master_group_subs', $id_master_group_subs);
            $group_subs = MstGroupSubs::where('id', $id_master_group_subs)->first()->name;
        }
        if ($month) {
            [$year, $monthNum] = explode('-', $month);
            $query->whereYear('history_stocks.date', $year)->whereMonth('history_stocks.date', $monthNum);
        }

        // Execute the query and process the results
        $datas = $query->orderBy('history_stocks.date', 'asc')->get()
        ->map(function ($item) {
            $idDetail = $item->id_good_receipt_notes_details;
            $item->status = null;

            if (Str::contains($idDetail, 'LMTS')) {
                $lmts = LMTS::where('no_lmts', $idDetail)->first();
                if ($lmts) {
                    $item->status = 'Closed';
                }
            } elseif (Str::startsWith($idDetail, 'RBM')) {
                $rbm = ReportBag::where('report_number', $idDetail)->first();
                if ($rbm) {
                    $item->status = $rbm->status ?? null;
                }
            } elseif (Str::startsWith($idDetail, ['RSL', 'RF'])) {
                $rfs = ReportSf::where('report_number', $idDetail)->first();
                if ($rfs) {
                    $item->status = $rfs->status ?? null;
                }
            } elseif (Str::startsWith($idDetail, 'RB')) {
                $rb = ReportBlow::where('report_number', $idDetail)->first();
                if ($rb) {
                    $item->status = $rb->status ?? null;
                }
            } elseif (is_numeric($idDetail)) {
                if ($item->type_stock == 'OUT') {
                    $packing = PackingList::where('packing_number', $idDetail)->first();
                    if ($packing) {
                        $item->status = $packing->status ?? null;
                    }
                } elseif ($item->type_stock == 'IN') {
                    $grn = GoodReceiptNoteDetail::where('id', $idDetail)->first();
                    if ($grn) {
                        $item->status = 'Closed';
                    }
                } else {
                    $item->status = 'Closed';
                }
            } else {
                $item->status = 'Closed';
            }
            return $item;
        })
        ->filter(function ($item) {
            return $item->status === 'Closed';
        })
        ->unique('id_good_receipt_notes_details')
        ->sortByDesc('id')
        ->values();

        $totalIn = $totalOut = 0;
        $totalIn = $datas->filter(function ($item) {
            return $item->type_stock === 'IN';
        })->sum('qty');
        $totalOut = $datas->filter(function ($item) {
            return $item->type_stock === 'OUT';
        })->sum('qty');
        $allTotal = [
            'totalIn' => $totalIn,
            'totalOut' => $totalOut,
        ];

        $exportType = $request->get('export_type') ?? 'excel';
        if ($exportType === 'pdf') {
            $pdf = PDF::loadView('exports.pdf.stock', [
                'typeProd' => 'WIP',
                'datas' => $datas,
                'request' => $request,
                'group_subs' => $group_subs,
                'allTotal' => $allTotal,
                'month' => $month,
                'exportedBy' => auth()->user()->email,
                'exportedAt' => now()->format('d-m-Y H:i:s'),
            ])->setPaper('A4', 'portrait');
            $filename = 'Print_Stock_WIP_' . Carbon::now()->format('d_m_Y_H_i') . '.pdf';
            return $pdf->download($filename);
        } else {
            $filename = 'Export_Stock_WIP_' . Carbon::now()->format('d_m_Y_H_i') . '.xlsx';
            return Excel::download(new StockWIPExport($datas, $request, $group_subs, $allTotal), $filename);
        }
    }
    public function exportWIPProd(Request $request, $id)
    {
        $id = decrypt($id);
        $month = $request->get('month');

        $type = 'WIP';
        $detail = MstWips::select('wip_code', 'description')->where('id', $id)->first();
        $query = HistoryStock::select(
                'type_product', 'qty', 'type_stock', 'date', 
                'id_good_receipt_notes_details', 'created_at', 'remarks'
            )
            ->whereIn('type_stock', ['IN', 'OUT'])
            ->where('id_master_products', $id)
            ->where('type_product', $type);

        if ($month) {
            [$year, $monthNum] = explode('-', $month);
            $query->whereYear('date', $year)->whereMonth('date', $monthNum);
        }

        // Execute the query and process the results
        $datas = $query->orderBy('date', 'asc')->get()
        ->map(function ($item) {
            $idDetail = $item->id_good_receipt_notes_details;
            $item->number = null;
            $item->status = null;

            if (Str::contains($idDetail, 'LMTS')) {
                $lmts = LMTS::where('no_lmts', $idDetail)->first();
                if ($lmts) {
                    $item->number = $lmts->no_lmts ?? null;
                    $item->status = 'Closed';
                }
            } elseif (Str::startsWith($idDetail, 'RBM')) {
                $rbm = ReportBag::where('report_number', $idDetail)->first();
                if ($rbm) {
                    $item->number = $rbm->report_number ?? null;
                    $item->status = $rbm->status ?? null;
                }
            } elseif (Str::startsWith($idDetail, ['RSL', 'RF'])) {
                $rfs = ReportSf::where('report_number', $idDetail)->first();
                if ($rfs) {
                    $item->number = $rfs->report_number ?? null;
                    $item->status = $rfs->status ?? null;
                }
            } elseif (Str::startsWith($idDetail, 'RB')) {
                $rb = ReportBlow::where('report_number', $idDetail)->first();
                if ($rb) {
                    $item->number = $rb->report_number ?? null;
                    $item->status = $rb->status ?? null;
                }
            } elseif (is_numeric($idDetail)) {
                if ($item->type_stock == 'OUT') {
                    $packing = PackingList::where('packing_number', $idDetail)->first();
                    if ($packing) {
                        $item->number = $packing->packing_number ?? null;
                        $item->status = $packing->status ?? null;
                    }
                } elseif ($item->type_stock == 'IN') {
                    $grn = GoodReceiptNoteDetail::where('id', $idDetail)->first();
                    if ($grn) {
                        $item->number = $grn->lot_number ?? null;
                        $item->status = 'Closed';
                    }
                } else {
                    $item->number = 'Undefined';
                    $item->status = 'Closed';
                }
            } else {
                $item->number = 'Undefined';
                $item->status = 'Closed';
            }

            return $item;
        })
        ->filter(function ($item) {
            return $item->status === 'Closed';
        })
        ->unique('id_good_receipt_notes_details')
        ->sortByDesc('id')
        ->values();

        $totalIn = $totalOut = 0;
        $totalIn = $datas->filter(function ($item) {
            return $item->type_stock === 'IN';
        })->sum('qty');
        $totalOut = $datas->filter(function ($item) {
            return $item->type_stock === 'OUT';
        })->sum('qty');
        $sumTotal = $totalIn - $totalOut;

        $recap = RecapStocks::where('type_product', 'RM')->where('id_master_product', $id)->where('period', $month)->first();

        $allTotal = [
            'totalIn' => $totalIn,
            'totalOut' => $totalOut,
            'sumTotal' => $sumTotal,
            'InitialStock' => $recap->qty_start ?? 0,
            'EndingStock' => $recap->qty_end ?? 0,
        ];

        $exportType = $request->get('export_type') ?? 'excel';
        if ($exportType === 'pdf') {
            $pdf = PDF::loadView('exports.pdf.stock_prod', [
                'typeProd' => $type,
                'detail' => $detail,
                'datas' => $datas,
                'request' => $request,
                'allTotal' => $allTotal,
                'month' => $month,
                'exportedBy' => auth()->user()->email,
                'exportedAt' => now()->format('d-m-Y H:i:s'),
            ])->setPaper('A4', 'portrait');
            $filename = 'Print_Stock_WIP_Prod_' . Carbon::now()->format('d_m_Y_H_i') . '.pdf';
            return $pdf->download($filename);
        } else {
            $filename = 'Export_Stock_WIP_Prod_' . Carbon::now()->format('d_m_Y_H_i') . '.xlsx';
            return Excel::download(new StockProdExport($type, $detail, $datas, $request, $allTotal), $filename);
        }
    }

    // FINISH GOOD 
    public function indexFG(Request $request)
    {
        // Variable
        $group_subs = MstGroupSubs::get();

        // Search & Filter Variable
        $product_code = $request->get('product_code');
        $description = $request->get('description');
        $type = $request->get('type');
        $thickness = $request->get('thickness');
        $id_master_group_subs = $request->get('id_master_group_subs');
        
        $datas = MstFGs::select(
            'master_product_fgs.id', 'master_product_fgs.product_code', 'master_product_fgs.description',
            'master_product_fgs.thickness', 'master_product_fgs.perforasi', 'master_product_fgs.stock', 'master_product_fgs.weight', 'master_product_fgs.weight_stock',
            'master_units.unit_code', 'master_group_subs.name as sub_groupname',
        )
        ->leftjoin('master_units', 'master_product_fgs.id_master_units', 'master_units.id')
        ->leftjoin('master_group_subs', 'master_product_fgs.id_master_group_subs', 'master_group_subs.id');

        // Search & Filter
        if($product_code != null){
            $datas = $datas->where('master_product_fgs.product_code', 'like', '%'.$product_code.'%');
        }
        if($description != null){
            $datas = $datas->where('master_product_fgs.description', 'like', '%'.$description.'%');
        }
        if ($type != null) {
            $datas->where('master_product_fgs.type', $type);
        }
        if ($thickness != null) {
            $datas->where('master_product_fgs.thickness', $thickness);
        }
        if ($id_master_group_subs != null) {
            $datas->where('master_product_fgs.id_master_group_subs', $id_master_group_subs);
        }
        // Final Data
        $datas = $datas->orderBy('master_product_fgs.created_at', 'desc')->get();

        // Datatables
        if ($request->ajax()) {
            return DataTables::of($datas)
                ->addColumn('action', function ($data) {
                    return view('historystock.fg.action', compact('data'));
                })->make(true);
        }

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

        //Audit Log
        $this->auditLogsShort('View Detail History Stock FG');
        return view('historystock.fg.index', compact(
            'group_subs',
            'product_code', 'description', 'type', 'thickness', 'id_master_group_subs', 'idUpdated', 'page_number'
        ));
    }
    public function historyFG(Request $request, $id)
    {
        $id = decrypt($id);
        // Search & Filter Variable
        $searchDate = $request->get('searchDate');
        $month = $request->get('month');

        $detail = MstFGs::select('product_code', 'description', 'stock')->where('id', $id)->first();
        $query = HistoryStock::where('id_master_products', $id) ->where('type_product', 'FG');
        
        if ($month) {
            [$year, $monthNum] = explode('-', $month);
            $query->whereYear('date', $year)->whereMonth('date', $monthNum);
        }

        // Execute the query and process the results
        $datas = $query->orderBy('date')->get()
        ->map(function ($item) {
            $idDetail = $item->id_good_receipt_notes_details;
            $item->id_detail = null;
            $item->note_stock = null;
            $item->number = null;
            $item->status = null;
            $item->tableJoin = null;
            $item->source = null;

            if (Str::contains($idDetail, 'LMTS')) {
                $lmts = LMTS::where('no_lmts', $idDetail)->first();
                if ($lmts) {
                    $item->id_detail = $lmts->id ?? null;
                    $item->note_stock = $lmts->lmts_notes ?? null;
                    $item->number = $lmts->no_lmts ?? null;
                    $item->status = 'Closed';
                    $item->tableJoin = 'LMTS';
                    $item->source = 'LMTS';
                }
            } elseif (Str::startsWith($idDetail, 'RBM')) {
                $rbm = ReportBag::where('report_number', $idDetail)->first();
                if ($rbm) {
                    $item->id_detail = $rbm->id ?? null;
                    $item->note_stock = $rbm->note ?? null;
                    $item->number = $rbm->report_number ?? null;
                    $item->status = $rbm->status ?? null;
                    $item->tableJoin = 'RBM';
                    $item->source = 'Report';
                }
            } elseif (Str::startsWith($idDetail, ['RSL', 'RF'])) {
                $rfs = ReportSf::where('report_number', $idDetail)->first();
                if ($rfs) {
                    $item->id_detail = $rfs->id ?? null;
                    $item->note_stock = $rfs->note ?? null;
                    $item->number = $rfs->report_number ?? null;
                    $item->status = $rfs->status ?? null;
                    $item->tableJoin = 'RSLRF';
                    $item->source = 'Report';
                }
            } elseif (Str::startsWith($idDetail, 'RB')) {
                $rb = ReportBlow::where('report_number', $idDetail)->first();
                if ($rb) {
                    $item->id_detail = $rb->id ?? null;
                    $item->note_stock = null;
                    $item->number = $rb->report_number ?? null;
                    $item->status = $rb->status ?? null;
                    $item->tableJoin = 'RB';
                    $item->source = 'Report';
                }
            } elseif (is_numeric($idDetail)) {
                if ($item->type_stock == 'OUT') {
                    $packing = PackingList::where('packing_number', $idDetail)->first();
                    if ($packing) {
                        $item->id_detail = $packing->id ?? null;
                        $item->note_stock = $packing->note ?? null;
                        $item->number = $packing->packing_number ?? null;
                        $item->status = $packing->status ?? null;
                        $item->tableJoin = 'PL';
                        $item->source = 'Packing List';
                    }
                } elseif ($item->type_stock == 'IN') {
                    $grn = GoodReceiptNoteDetail::where('id', $idDetail)->first();
                    if ($grn) {
                        $item->id_detail = $grn->id ?? null;
                        $item->note_stock = $grn->note ?? null;
                        $item->number = $grn->lot_number ?? null;
                        $item->status = 'Closed';
                        $item->tableJoin = 'GRN';
                        $item->source = 'GRN';
                    }
                } else {
                    $item->id_detail = 'Undefined';
                    $item->note_stock = $item->remarks ?? null;
                    $item->number = $item->id_good_receipt_notes_details ?? null;
                    $item->status = 'Undefined';
                    $item->source = 'Undefined';
                }
            } else {
                $item->id_detail = 'Undefined';
                $item->note_stock = $item->remarks ?? null;
                $item->number = $item->id_good_receipt_notes_details ?? null;
                $item->status = 'Undefined';
                $item->source = 'Undefined';
            }

            return $item;
        })
        ->sortByDesc('id')
        ->unique('id_good_receipt_notes_details')
        ->values();

        $total_in = $datas->filter(function ($item) {
            return $item->type_stock === 'IN' && $item->status === 'Closed';
        })->sum('qty');
        $total_out = $datas->filter(function ($item) {
            return $item->type_stock === 'OUT' && $item->status === 'Closed';
        })->sum('qty');
        $total = $total_in - $total_out;

        if ($request->ajax()) {
            return DataTables::of($datas)
                ->addColumn('action', function ($data) {
                    return view('historystock.fg.history.action', compact('data'));
                })->make(true);
        }

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

        //Audit Log
        $this->auditLogsShort('View Detail History Stock FG Code '.$detail->product_code);
        return view('historystock.fg.history.index', compact('id', 'searchDate', 'month', 'detail', 'total_in', 'total_out', 'total', 'idUpdated', 'page_number'));
    }
    public function detailHistFG(Request $request, $id, $tableJoin)
    {
        $id = decrypt($id);

        $historyStock = HistoryStock::where('id', $id)->first();
        $dataHistories = HistoryStock::where('id_good_receipt_notes_details', $historyStock->id_good_receipt_notes_details)->orderBy('created_at')->get();

        $number = $historyStock->id_good_receipt_notes_details ?? null;
        $product = MstFGs::where('id', $historyStock->id_master_products)->first();
        $fromGRN = false;

        if($tableJoin == 'LMTS') {
            $datas = LMTS::where('no_lmts', $number)->first();
        } elseif($tableJoin == 'PL') {
            $datas = PackingList::select('packing_lists.packing_number', 'packing_lists.date', 'packing_lists.status', 'master_customers.name as customer_name')
                ->leftJoin('master_customers', "packing_lists.id_master_customers", 'master_customers.id')
                ->where('packing_lists.packing_number', $number)
                ->first();
        } elseif (in_array($tableJoin, ['RB', 'RSLRF', 'RBM'])) {
            $columnKnown = [
                'RB' => 'know_by',
                'RSLRF' => 'know_by',
                'RBM' => 'known_by',
            ];
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
            $known = $columnKnown[$tableJoin];
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
            ->leftJoin('master_employees as kb', "$alias.$known", 'kb.id')
            ->where("$alias.report_number", $number)
            ->first();
        } else {
            $fromGRN = true;
            $datas = DetailGoodReceiptNoteDetail::select(
                    'detail_good_receipt_note_details.id',
                    'detail_good_receipt_note_details.id_grn',
                    'detail_good_receipt_note_details.id_grn_detail',
                    'good_receipt_notes.receipt_number',
                    'good_receipt_notes.date as date_grn',
                    'good_receipt_note_details.lot_number',
                    'good_receipt_note_details.qc_passed',
                    'good_receipt_note_details.note',
                    'good_receipt_note_details.master_units_id',
                    'good_receipt_note_details.type_product',
                    'detail_good_receipt_note_details.ext_lot_number',
                    'detail_good_receipt_note_details.qty',
                    'detail_good_receipt_note_details.qty_out',
                    DB::raw('ROUND(detail_good_receipt_note_details.qty - detail_good_receipt_note_details.qty_out, 2) as glq'),
                    'master_units.unit_code',
                    'lmts.no_lmts',
                    'lmts.date as lmts_date',
                    'lmts.updated_at as lmts_last_updated',
                    'lmts.status as lmts_status',
                    'lmts.button_active as lmts_disposisi',
                    'lmts.remarks as hold_remarks',
                    'lmts.lmts_notes as lmts_remarks',
                )
                ->leftJoin('good_receipt_note_details', 'detail_good_receipt_note_details.id_grn_detail', 'good_receipt_note_details.id')
                ->leftJoin('good_receipt_notes', 'detail_good_receipt_note_details.id_grn', 'good_receipt_notes.id')
                ->leftJoin('master_units', 'good_receipt_note_details.master_units_id', 'master_units.id')
                ->leftJoin('lmts', 'detail_good_receipt_note_details.id', 'lmts.id_detail_grn_detail')
                ->where('detail_good_receipt_note_details.id_grn_detail', $number)
                ->get();
            $number = $datas[0]->lot_number;
        }
        // dd($datas);

        if ($request->ajax()) {
            if($fromGRN){
                return DataTables::of($datas)
                ->addColumn('action', function ($data) use ($product){
                    return view('historystock.action_detail', compact('data', 'product'));
                })->make(true);
            }
            return DataTables::of($datas)->make(true);
        }
        //Audit Log
        $this->auditLogsShort('View Detail History Stock FG Lot/Report/Packing Number '.$number);

        return view('historystock.fg.history.detail', compact('dataHistories', 'product', 'datas', 'number', 'id', 'tableJoin'));
    }
    public function exportFG(Request $request)
    {
        $keyword = $request->get('keyword');
        $type = $request->get('type');
        $thickness = $request->get('thickness');
        $id_master_group_subs = $request->get('id_master_group_subs');
        $month = $request->get('month');

        $query = HistoryStock::select(
                'history_stocks.type_product', 'history_stocks.qty', 'history_stocks.type_stock', 'history_stocks.date', 
                'history_stocks.id_good_receipt_notes_details', 'history_stocks.created_at', 'history_stocks.remarks',
                'master_product_fgs.product_code as code', 'master_product_fgs.description'
            )
            ->leftjoin('master_product_fgs', 'history_stocks.id_master_products', 'master_product_fgs.id')
            ->whereIn('history_stocks.type_stock', ['IN', 'OUT'])
            ->where('history_stocks.type_product', 'FG');

        // Apply date range filter if provided
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('master_product_fgs.product_code', 'like', '%' . $keyword . '%')
                    ->orWhere('master_product_fgs.description', 'like', '%' . $keyword . '%');
            });
        }
        if ($type != null) {
            $query->where('master_product_fgs.type_product', $type);
        }
        if ($thickness != null) {
            $query->where('master_product_fgs.thickness', $thickness);
        }
        $group_subs = null;
        if ($id_master_group_subs != null) {
            $query->where('master_product_fgs.id_master_group_subs', $id_master_group_subs);
            $group_subs = MstGroupSubs::where('id', $id_master_group_subs)->first()->name;
        }
        if ($month) {
            [$year, $monthNum] = explode('-', $month);
            $query->whereYear('history_stocks.date', $year)->whereMonth('history_stocks.date', $monthNum);
        }

        // Execute the query and process the results
        $datas = $query->orderBy('history_stocks.date', 'asc')->get()
        ->map(function ($item) {
            $idDetail = $item->id_good_receipt_notes_details;
            $item->status = null;

            if (Str::contains($idDetail, 'LMTS')) {
                $lmts = LMTS::where('no_lmts', $idDetail)->first();
                if ($lmts) {
                    $item->status = 'Closed';
                }
            } elseif (Str::startsWith($idDetail, 'RBM')) {
                $rbm = ReportBag::where('report_number', $idDetail)->first();
                if ($rbm) {
                    $item->status = $rbm->status ?? null;
                }
            } elseif (Str::startsWith($idDetail, ['RSL', 'RF'])) {
                $rfs = ReportSf::where('report_number', $idDetail)->first();
                if ($rfs) {
                    $item->status = $rfs->status ?? null;
                }
            } elseif (Str::startsWith($idDetail, 'RB')) {
                $rb = ReportBlow::where('report_number', $idDetail)->first();
                if ($rb) {
                    $item->status = $rb->status ?? null;
                }
            } elseif (is_numeric($idDetail)) {
                if ($item->type_stock == 'OUT') {
                    $packing = PackingList::where('packing_number', $idDetail)->first();
                    if ($packing) {
                        $item->status = $packing->status ?? null;
                    }
                } elseif ($item->type_stock == 'IN') {
                    $grn = GoodReceiptNoteDetail::where('id', $idDetail)->first();
                    if ($grn) {
                        $item->status = 'Closed';
                    }
                } else {
                    $item->status = 'Closed';
                }
            } else {
                $item->status = 'Closed';
            }
            return $item;
        })
        ->filter(function ($item) {
            return $item->status === 'Closed';
        })
        ->unique('id_good_receipt_notes_details')
        ->sortByDesc('id')
        ->values();

        $totalIn = $totalOut = 0;
        $totalIn = $datas->filter(function ($item) {
            return $item->type_stock === 'IN';
        })->sum('qty');
        $totalOut = $datas->filter(function ($item) {
            return $item->type_stock === 'OUT';
        })->sum('qty');
        $allTotal = [
            'totalIn' => $totalIn,
            'totalOut' => $totalOut,
        ];

        $exportType = $request->get('export_type') ?? 'excel';
        if ($exportType === 'pdf') {
            $pdf = PDF::loadView('exports.pdf.stock', [
                'typeProd' => 'FG',
                'datas' => $datas,
                'request' => $request,
                'group_subs' => $group_subs,
                'allTotal' => $allTotal,
                'month' => $month,
                'exportedBy' => auth()->user()->email,
                'exportedAt' => now()->format('d-m-Y H:i:s'),
            ])->setPaper('A4', 'portrait');
            $filename = 'Print_Stock_FG_' . Carbon::now()->format('d_m_Y_H_i') . '.pdf';
            return $pdf->download($filename);
        } else {
            $filename = 'Export_Stock_FG_' . Carbon::now()->format('d_m_Y_H_i') . '.xlsx';
            return Excel::download(new StockFGExport($datas, $request, $group_subs, $allTotal), $filename);
        }
    }
    public function exportFGProd(Request $request, $id)
    {
        $id = decrypt($id);
        $month = $request->get('month');

        $type = 'FG';
        $detail = MstFGs::select('product_code', 'description')->where('id', $id)->first();
        $query = HistoryStock::select(
                'type_product', 'qty', 'type_stock', 'date', 
                'id_good_receipt_notes_details', 'created_at', 'remarks'
            )
            ->whereIn('type_stock', ['IN', 'OUT'])
            ->where('id_master_products', $id)
            ->where('type_product', $type);

        if ($month) {
            [$year, $monthNum] = explode('-', $month);
            $query->whereYear('date', $year)->whereMonth('date', $monthNum);
        }

        // Execute the query and process the results
        $datas = $query->orderBy('date', 'asc')->get()
        ->map(function ($item) {
            $idDetail = $item->id_good_receipt_notes_details;
            $item->number = null;
            $item->status = null;

            if (Str::contains($idDetail, 'LMTS')) {
                $lmts = LMTS::where('no_lmts', $idDetail)->first();
                if ($lmts) {
                    $item->number = $lmts->no_lmts ?? null;
                    $item->status = 'Closed';
                }
            } elseif (Str::startsWith($idDetail, 'RBM')) {
                $rbm = ReportBag::where('report_number', $idDetail)->first();
                if ($rbm) {
                    $item->number = $rbm->report_number ?? null;
                    $item->status = $rbm->status ?? null;
                }
            } elseif (Str::startsWith($idDetail, ['RSL', 'RF'])) {
                $rfs = ReportSf::where('report_number', $idDetail)->first();
                if ($rfs) {
                    $item->number = $rfs->report_number ?? null;
                    $item->status = $rfs->status ?? null;
                }
            } elseif (Str::startsWith($idDetail, 'RB')) {
                $rb = ReportBlow::where('report_number', $idDetail)->first();
                if ($rb) {
                    $item->number = $rb->report_number ?? null;
                    $item->status = $rb->status ?? null;
                }
            } elseif (is_numeric($idDetail)) {
                if ($item->type_stock == 'OUT') {
                    $packing = PackingList::where('packing_number', $idDetail)->first();
                    if ($packing) {
                        $item->number = $packing->packing_number ?? null;
                        $item->status = $packing->status ?? null;
                    }
                } elseif ($item->type_stock == 'IN') {
                    $grn = GoodReceiptNoteDetail::where('id', $idDetail)->first();
                    if ($grn) {
                        $item->number = $grn->lot_number ?? null;
                        $item->status = 'Closed';
                    }
                } else {
                    $item->number = 'Undefined';
                    $item->status = 'Closed';
                }
            } else {
                $item->number = 'Undefined';
                $item->status = 'Closed';
            }

            return $item;
        })
        ->filter(function ($item) {
            return $item->status === 'Closed';
        })
        ->unique('id_good_receipt_notes_details')
        ->sortByDesc('id')
        ->values();

        $totalIn = $totalOut = 0;
        $totalIn = $datas->filter(function ($item) {
            return $item->type_stock === 'IN';
        })->sum('qty');
        $totalOut = $datas->filter(function ($item) {
            return $item->type_stock === 'OUT';
        })->sum('qty');
        $sumTotal = $totalIn - $totalOut;

        $recap = RecapStocks::where('type_product', 'RM')->where('id_master_product', $id)->where('period', $month)->first();

        $allTotal = [
            'totalIn' => $totalIn,
            'totalOut' => $totalOut,
            'sumTotal' => $sumTotal,
            'InitialStock' => $recap->qty_start ?? 0,
            'EndingStock' => $recap->qty_end ?? 0,
        ];

        $exportType = $request->get('export_type') ?? 'excel';
        if ($exportType === 'pdf') {
            $pdf = PDF::loadView('exports.pdf.stock_prod', [
                'typeProd' => $type,
                'detail' => $detail,
                'datas' => $datas,
                'request' => $request,
                'allTotal' => $allTotal,
                'month' => $month,
                'exportedBy' => auth()->user()->email,
                'exportedAt' => now()->format('d-m-Y H:i:s'),
            ])->setPaper('A4', 'portrait');
            $filename = 'Print_Stock_FG_Prod_' . Carbon::now()->format('d_m_Y_H_i') . '.pdf';
            return $pdf->download($filename);
        } else {
            $filename = 'Export_Stock_FG_Prod_' . Carbon::now()->format('d_m_Y_H_i') . '.xlsx';
            return Excel::download(new StockProdExport($type, $detail, $datas, $request, $allTotal), $filename);
        }
    }

    // TOOL & AUXALARY
    public function indexTA(Request $request)
    {
        // Search & Filter Variable
        $code = $request->get('code');
        $description = $request->get('description');
        $type = $request->get('type');

        $datas = MstSpareparts::select(
            'master_tool_auxiliaries.id', 'master_tool_auxiliaries.code', 'master_tool_auxiliaries.description', 'master_tool_auxiliaries.weight_stock',
            'master_tool_auxiliaries.type', 'master_tool_auxiliaries.stock',
            'master_units.unit_code'
        )
        ->leftjoin('master_units', 'master_tool_auxiliaries.id_master_units', 'master_units.id');

        // Search & Filter
        if($code != null){
            $datas = $datas->where('master_tool_auxiliaries.code', 'like', '%'.$code.'%');
        }
        if($description != null){
            $datas = $datas->where('master_tool_auxiliaries.description', 'like', '%'.$description.'%');
        }
        if ($type != null) {
            $datas->where('master_tool_auxiliaries.type', $type);
        }
        // Final Data
        $datas = $datas->orderBy('master_tool_auxiliaries.created_at', 'desc')->get();

        // Datatables
        if ($request->ajax()) {
            return DataTables::of($datas)
                ->addColumn('action', function ($data) {
                    return view('historystock.ta.action', compact('data'));
                })->make(true);
        }

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

        //Audit Log
        $this->auditLogsShort('View Detail History Stock TA');
        return view('historystock.ta.index', compact(
            'code', 'description', 'type', 'idUpdated', 'page_number'
        ));
    }
    public function historyTA(Request $request, $id)
    {
        $id = decrypt($id);
        // Search & Filter Variable
        $searchDate = $request->get('searchDate');
        $month = $request->get('month');

        $detail = MstSpareparts::select('code', 'description', 'stock')->where('id', $id)->first();
        $query = HistoryStock::where('id_master_products', $id)->whereIn('type_product', ['TA', 'Other']);
        
        if ($month) {
            [$year, $monthNum] = explode('-', $month);
            $query->whereYear('date', $year)->whereMonth('date', $monthNum);
        }

        // Execute the query and process the results
        $datas = $query->orderBy('date')->get()
        ->map(function ($item) {
            $idDetail = $item->id_good_receipt_notes_details;
            $item->id_detail = null;
            $item->note_stock = null;
            $item->number = null;
            $item->status = null;
            $item->tableJoin = null;
            $item->source = null;

            if (Str::contains($idDetail, 'LMTS')) {
                $lmts = LMTS::where('no_lmts', $idDetail)->first();
                if ($lmts) {
                    $item->id_detail = $lmts->id ?? null;
                    $item->note_stock = $lmts->lmts_notes ?? null;
                    $item->number = $lmts->no_lmts ?? null;
                    $item->status = 'Closed';
                    $item->tableJoin = 'LMTS';
                    $item->source = 'LMTS';
                }
            } elseif (Str::startsWith($idDetail, 'RBM')) {
                $rbm = ReportBag::where('report_number', $idDetail)->first();
                if ($rbm) {
                    $item->id_detail = $rbm->id ?? null;
                    $item->note_stock = $rbm->note ?? null;
                    $item->number = $rbm->report_number ?? null;
                    $item->status = $rbm->status ?? null;
                    $item->tableJoin = 'RBM';
                    $item->source = 'Report';
                }
            } elseif (Str::startsWith($idDetail, ['RSL', 'RF'])) {
                $rfs = ReportSf::where('report_number', $idDetail)->first();
                if ($rfs) {
                    $item->id_detail = $rfs->id ?? null;
                    $item->note_stock = $rfs->note ?? null;
                    $item->number = $rfs->report_number ?? null;
                    $item->status = $rfs->status ?? null;
                    $item->tableJoin = 'RSLRF';
                    $item->source = 'Report';
                }
            } elseif (Str::startsWith($idDetail, 'RB')) {
                $rb = ReportBlow::where('report_number', $idDetail)->first();
                if ($rb) {
                    $item->id_detail = $rb->id ?? null;
                    $item->note_stock = null;
                    $item->number = $rb->report_number ?? null;
                    $item->status = $rb->status ?? null;
                    $item->tableJoin = 'RB';
                    $item->source = 'Report';
                }
            } elseif (is_numeric($idDetail)) {
                if ($item->type_stock == 'OUT') {
                    $packing = PackingList::where('packing_number', $idDetail)->first();
                    if ($packing) {
                        $item->id_detail = $packing->id ?? null;
                        $item->note_stock = $packing->note ?? null;
                        $item->number = $packing->packing_number ?? null;
                        $item->status = $packing->status ?? null;
                        $item->tableJoin = 'PL';
                        $item->source = 'Packing List';
                    }
                } elseif ($item->type_stock == 'IN') {
                    $grn = GoodReceiptNoteDetail::where('id', $idDetail)->first();
                    if ($grn) {
                        $item->id_detail = $grn->id ?? null;
                        $item->note_stock = $grn->note ?? null;
                        $item->number = $grn->lot_number ?? null;
                        $item->status = 'Closed';
                        $item->tableJoin = 'GRN';
                        $item->source = 'GRN';
                    }
                } else {
                    $item->id_detail = 'Undefined';
                    $item->note_stock = $item->remarks ?? null;
                    $item->number = $item->id_good_receipt_notes_details ?? null;
                    $item->status = 'Undefined';
                    $item->source = 'Undefined';
                }
            } else {
                $item->id_detail = 'Undefined';
                $item->note_stock = $item->remarks ?? null;
                $item->number = $item->id_good_receipt_notes_details ?? null;
                $item->status = 'Undefined';
                $item->source = 'Undefined';
            }
            return $item;
        })
        ->sortByDesc('id')
        ->unique('id_good_receipt_notes_details')
        ->values();

        $total_in = $datas->filter(function ($item) {
            return $item->type_stock === 'IN' && $item->status === 'Closed';
        })->sum('qty');
        $total_out = $datas->filter(function ($item) {
            return $item->type_stock === 'OUT' && $item->status === 'Closed';
        })->sum('qty');
        $total = $total_in - $total_out;

        if ($request->ajax()) {
            return DataTables::of($datas)
                ->addColumn('action', function ($data) {
                    return view('historystock.ta.history.action', compact('data'));
                })->make(true);
        }

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

        //Audit Log
        $this->auditLogsShort('View Detail History Stock TA Code '.$detail->code);
        return view('historystock.ta.history.index', compact('id', 'searchDate', 'month', 'detail', 'total_in', 'total_out', 'total', 'idUpdated', 'page_number'));
    }
    public function detailHistTA(Request $request, $id, $tableJoin)
    {
        $id = decrypt($id);

        $historyStock = HistoryStock::where('id', $id)->first();
        $dataHistories = HistoryStock::where('id_good_receipt_notes_details', $historyStock->id_good_receipt_notes_details)->orderBy('created_at')->get();

        $number = $historyStock->id_good_receipt_notes_details ?? null;
        $product = MstSpareparts::where('id', $historyStock->id_master_products)->first();
        $fromGRN = false;

        if($tableJoin == 'LMTS') {
            $datas = LMTS::where('no_lmts', $number)->first();
        } elseif($tableJoin == 'PL') {
            $datas = PackingList::select('packing_lists.packing_number', 'packing_lists.date', 'packing_lists.status', 'master_customers.name as customer_name')
                ->leftJoin('master_customers', "packing_lists.id_master_customers", 'master_customers.id')
                ->where('packing_lists.packing_number', $number)
                ->first();
        } elseif (in_array($tableJoin, ['RB', 'RSLRF', 'RBM'])) {
            $columnKnown = [
                'RB' => 'know_by',
                'RSLRF' => 'know_by',
                'RBM' => 'known_by',
            ];
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
            $known = $columnKnown[$tableJoin];
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
            ->leftJoin('master_employees as kb', "$alias.$known", 'kb.id')
            ->where("$alias.report_number", $number)
            ->first();
        } else {
            $fromGRN = true;
            $datas = DetailGoodReceiptNoteDetail::select(
                    'detail_good_receipt_note_details.id',
                    'detail_good_receipt_note_details.id_grn',
                    'detail_good_receipt_note_details.id_grn_detail',
                    'good_receipt_notes.receipt_number',
                    'good_receipt_notes.date as date_grn',
                    'good_receipt_note_details.lot_number',
                    'good_receipt_note_details.qc_passed',
                    'good_receipt_note_details.note',
                    'good_receipt_note_details.master_units_id',
                    'good_receipt_note_details.type_product',
                    'detail_good_receipt_note_details.ext_lot_number',
                    'detail_good_receipt_note_details.qty',
                    'detail_good_receipt_note_details.qty_out',
                    DB::raw('ROUND(detail_good_receipt_note_details.qty - detail_good_receipt_note_details.qty_out, 2) as glq'),
                    'master_units.unit_code',
                    'lmts.no_lmts',
                    'lmts.date as lmts_date',
                    'lmts.updated_at as lmts_last_updated',
                    'lmts.status as lmts_status',
                    'lmts.button_active as lmts_disposisi',
                    'lmts.remarks as hold_remarks',
                    'lmts.lmts_notes as lmts_remarks',
                )
                ->leftJoin('good_receipt_note_details', 'detail_good_receipt_note_details.id_grn_detail', 'good_receipt_note_details.id')
                ->leftJoin('good_receipt_notes', 'detail_good_receipt_note_details.id_grn', 'good_receipt_notes.id')
                ->leftJoin('master_units', 'good_receipt_note_details.master_units_id', 'master_units.id')
                ->leftJoin('lmts', 'detail_good_receipt_note_details.id', 'lmts.id_detail_grn_detail')
                ->where('detail_good_receipt_note_details.id_grn_detail', $number)
                ->get();
            $number = $datas[0]->lot_number;
        }
        // dd($datas);

        if ($request->ajax()) {
            if($fromGRN){
                return DataTables::of($datas)
                ->addColumn('action', function ($data) use ($product){
                    return view('historystock.action_detail', compact('data', 'product'));
                })->make(true);
            }
            return DataTables::of($datas)->make(true);
        }
        //Audit Log
        $this->auditLogsShort('View Detail History Stock TA Lot/Report/Packing Number '.$number);

        return view('historystock.ta.history.detail', compact('dataHistories', 'product', 'datas', 'number', 'id', 'tableJoin'));
    }
    public function exportTA(Request $request)
    {
        $keyword = $request->get('keyword');
        $type = $request->get('type');
        $month = $request->get('month');

        $query = HistoryStock::select(
                'history_stocks.type_product', 'history_stocks.qty', 'history_stocks.type_stock', 'history_stocks.date', 
                'history_stocks.id_good_receipt_notes_details', 'history_stocks.created_at', 'history_stocks.remarks',
                'master_tool_auxiliaries.code as code', 'master_tool_auxiliaries.description'
            )
            ->leftjoin('master_tool_auxiliaries', 'history_stocks.id_master_products', 'master_tool_auxiliaries.id')
            ->whereIn('history_stocks.type_stock', ['IN', 'OUT'])
            ->whereIn('history_stocks.type_product', ['TA', 'Other']);

        // Apply date range filter if provided
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('master_tool_auxiliaries.code', 'like', '%' . $keyword . '%')
                    ->orWhere('master_tool_auxiliaries.description', 'like', '%' . $keyword . '%');
            });
        }
        if ($type != null) {
            $query->where('master_tool_auxiliaries.type_product', $type);
        }
        if ($month) {
            [$year, $monthNum] = explode('-', $month);
            $query->whereYear('history_stocks.date', $year)->whereMonth('history_stocks.date', $monthNum);
        }

        // Execute the query and process the results
        $datas = $query->orderBy('history_stocks.date', 'asc')->get()
        ->map(function ($item) {
            $idDetail = $item->id_good_receipt_notes_details;
            $item->status = null;

            if (Str::contains($idDetail, 'LMTS')) {
                $lmts = LMTS::where('no_lmts', $idDetail)->first();
                if ($lmts) {
                    $item->status = 'Closed';
                }
            } elseif (Str::startsWith($idDetail, 'RBM')) {
                $rbm = ReportBag::where('report_number', $idDetail)->first();
                if ($rbm) {
                    $item->status = $rbm->status ?? null;
                }
            } elseif (Str::startsWith($idDetail, ['RSL', 'RF'])) {
                $rfs = ReportSf::where('report_number', $idDetail)->first();
                if ($rfs) {
                    $item->status = $rfs->status ?? null;
                }
            } elseif (Str::startsWith($idDetail, 'RB')) {
                $rb = ReportBlow::where('report_number', $idDetail)->first();
                if ($rb) {
                    $item->status = $rb->status ?? null;
                }
            } elseif (is_numeric($idDetail)) {
                if ($item->type_stock == 'OUT') {
                    $packing = PackingList::where('packing_number', $idDetail)->first();
                    if ($packing) {
                        $item->status = $packing->status ?? null;
                    }
                } elseif ($item->type_stock == 'IN') {
                    $grn = GoodReceiptNoteDetail::where('id', $idDetail)->first();
                    if ($grn) {
                        $item->status = 'Closed';
                    }
                } else {
                    $item->status = 'Closed';
                }
            } else {
                $item->status = 'Closed';
            }
            return $item;
        })
        ->filter(function ($item) {
            return $item->status === 'Closed';
        })
        ->unique('id_good_receipt_notes_details')
        ->sortByDesc('id')
        ->values();

        $totalIn = $totalOut = 0;
        $totalIn = $datas->filter(function ($item) {
            return $item->type_stock === 'IN';
        })->sum('qty');
        $totalOut = $datas->filter(function ($item) {
            return $item->type_stock === 'OUT';
        })->sum('qty');
        $allTotal = [
            'totalIn' => $totalIn,
            'totalOut' => $totalOut,
        ];

        $exportType = $request->get('export_type') ?? 'excel';
        if ($exportType === 'pdf') {
            $pdf = PDF::loadView('exports.pdf.stock', [
                'typeProd' => 'TA',
                'datas' => $datas,
                'request' => $request,
                'allTotal' => $allTotal,
                'month' => $month,
                'exportedBy' => auth()->user()->email,
                'exportedAt' => now()->format('d-m-Y H:i:s'),
            ])->setPaper('A4', 'portrait');
            $filename = 'Print_Stock_TA_Other_' . Carbon::now()->format('d_m_Y_H_i') . '.pdf';
            return $pdf->download($filename);
        } else {
            $filename = 'Export_Stock_TA_Other_' . Carbon::now()->format('d_m_Y_H_i') . '.xlsx';
            return Excel::download(new StockTAExport($datas, $request, $allTotal), $filename);
        }
    }
    public function exportTAProd(Request $request, $id)
    {
        $id = decrypt($id);
        $month = $request->get('month');

        $type = 'TA';
        $detail = MstSpareparts::select('code', 'description')->where('id', $id)->first();
        $query = HistoryStock::select(
                'type_product', 'qty', 'type_stock', 'date', 
                'id_good_receipt_notes_details', 'created_at', 'remarks'
            )
            ->whereIn('type_stock', ['IN', 'OUT'])
            ->where('id_master_products', $id)
            ->whereIn('type_product', ['TA', 'Other']);

        if ($month) {
            [$year, $monthNum] = explode('-', $month);
            $query->whereYear('date', $year)->whereMonth('date', $monthNum);
        }

        // Execute the query and process the results
        $datas = $query->orderBy('date', 'asc')->get()
        ->map(function ($item) {
            $idDetail = $item->id_good_receipt_notes_details;
            $item->number = null;
            $item->status = null;

            if (Str::contains($idDetail, 'LMTS')) {
                $lmts = LMTS::where('no_lmts', $idDetail)->first();
                if ($lmts) {
                    $item->number = $lmts->no_lmts ?? null;
                    $item->status = 'Closed';
                }
            } elseif (Str::startsWith($idDetail, 'RBM')) {
                $rbm = ReportBag::where('report_number', $idDetail)->first();
                if ($rbm) {
                    $item->number = $rbm->report_number ?? null;
                    $item->status = $rbm->status ?? null;
                }
            } elseif (Str::startsWith($idDetail, ['RSL', 'RF'])) {
                $rfs = ReportSf::where('report_number', $idDetail)->first();
                if ($rfs) {
                    $item->number = $rfs->report_number ?? null;
                    $item->status = $rfs->status ?? null;
                }
            } elseif (Str::startsWith($idDetail, 'RB')) {
                $rb = ReportBlow::where('report_number', $idDetail)->first();
                if ($rb) {
                    $item->number = $rb->report_number ?? null;
                    $item->status = $rb->status ?? null;
                }
            } elseif (is_numeric($idDetail)) {
                if ($item->type_stock == 'OUT') {
                    $packing = PackingList::where('packing_number', $idDetail)->first();
                    if ($packing) {
                        $item->number = $packing->packing_number ?? null;
                        $item->status = $packing->status ?? null;
                    }
                } elseif ($item->type_stock == 'IN') {
                    $grn = GoodReceiptNoteDetail::where('id', $idDetail)->first();
                    if ($grn) {
                        $item->number = $grn->lot_number ?? null;
                        $item->status = 'Closed';
                    }
                } else {
                    $item->number = 'Undefined';
                    $item->status = 'Closed';
                }
            } else {
                $item->number = 'Undefined';
                $item->status = 'Closed';
            }

            return $item;
        })
        ->filter(function ($item) {
            return $item->status === 'Closed';
        })
        ->unique('id_good_receipt_notes_details')
        ->sortByDesc('id')
        ->values();

        $totalIn = $totalOut = 0;
        $totalIn = $datas->filter(function ($item) {
            return $item->type_stock === 'IN';
        })->sum('qty');
        $totalOut = $datas->filter(function ($item) {
            return $item->type_stock === 'OUT';
        })->sum('qty');
        $sumTotal = $totalIn - $totalOut;

        $recap = RecapStocks::where('type_product', 'RM')->where('id_master_product', $id)->where('period', $month)->first();

        $allTotal = [
            'totalIn' => $totalIn,
            'totalOut' => $totalOut,
            'sumTotal' => $sumTotal,
            'InitialStock' => $recap->qty_start ?? 0,
            'EndingStock' => $recap->qty_end ?? 0,
        ];

        $exportType = $request->get('export_type') ?? 'excel';
        if ($exportType === 'pdf') {
            $pdf = PDF::loadView('exports.pdf.stock_prod', [
                'typeProd' => $type,
                'detail' => $detail,
                'datas' => $datas,
                'request' => $request,
                'allTotal' => $allTotal,
                'month' => $month,
                'exportedBy' => auth()->user()->email,
                'exportedAt' => now()->format('d-m-Y H:i:s'),
            ])->setPaper('A4', 'portrait');
            $filename = 'Print_Stock_TA_Prod_' . Carbon::now()->format('d_m_Y_H_i') . '.pdf';
            return $pdf->download($filename);
        } else {
            $filename = 'Export_Stock_TA_Other_Prod_' . Carbon::now()->format('d_m_Y_H_i') . '.xlsx';
            return Excel::download(new StockProdExport($type, $detail, $datas, $request, $allTotal), $filename);
        }
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
    public static function generateNoLmts()
    {
        return DB::transaction(function () {
            $prefix = 'Q&D/LMTS';
            $month = date('m');
            $year = date('y');
            // Roman numerals for months
            $romanMonths = [
                'I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'
            ];
            $romanMonth = $romanMonths[$month - 1];
            // Lock the table for current month/year to prevent race conditions
            $latest = DB::table('lmts')
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', '20' . $year)
                ->lockForUpdate()
                ->orderBy('id', 'desc')
                ->first();
            if ($latest && !empty($latest->no_lmts)) {
                // Extract the first 3 digits from existing code (e.g. 001)
                $lastNo = intval(substr($latest->no_lmts, 0, 3));
                $nextNo = str_pad($lastNo + 1, 3, '0', STR_PAD_LEFT);
            } else {
                $nextNo = '001';
            }
            return "{$nextNo}/{$prefix}/{$romanMonth}/{$year}";
        });
    }
    public function hold(Request $request)
    {
        $request->validate([
            'id_good_receipt_notes' => 'required',
            'id_good_receipt_notes_details' => 'required',
            'id_detail_grn_detail' => 'required',
            'receipt_number' => 'required',
            'date' => 'required',
            'lot_number' => 'required',
            'id_master_products' => 'required',
            'description' => 'required',
            'type_product' => 'required',
            'qty' => 'required',
            'total_glq' => 'required',
            'id_master_units' => 'required',
            'unit' => 'required',
            'status' => 'required',
            'remarks' => 'required',
            'button_active' => 'required',
        ]);
        // dd($request->all());

        $external_lot = $request->external_lot == '-' ? null : $request->external_lot;
        $button_active = [
            'is_return' => in_array('is_return', $request->button_active ?? []) ? 1 : 0,
            'is_repair' => in_array('is_repair', $request->button_active ?? []) ? 1 : 0,
            'is_scrap'  => in_array('is_scrap', $request->button_active ?? []) ? 1 : 0,
        ];
        $button_active_json = json_encode($button_active);

        DB::beginTransaction();
        try{
            $no_lmts = $this->generateNoLmts();

            LMTS::create([
                'no_lmts' => $no_lmts,
                'id_good_receipt_notes' => $request->id_good_receipt_notes,
                'receipt_number' => $request->receipt_number,
                'lot_number' => $request->lot_number,
                'id_good_receipt_notes_details' => $request->id_good_receipt_notes_details,
                'id_master_products' => $request->id_master_products,
                'description' => $request->description,
                'id_detail_grn_detail' => $request->id_detail_grn_detail,
                'external_lot' => $external_lot,
                'date' => $request->date,
                'qty' => $request->qty,
                'total_glq' => $request->total_glq,
                'id_master_units' => $request->id_master_units,
                'type_product' => $request->type_product,
                'status' => $request->status,
                'remarks' => $request->remarks,
                'unit' => $request->unit,
                'button_active' => $button_active_json,
            ]);

            //Audit Log
            $this->auditLogsShort('Hold Detail GRN Detail ID ('. $request->id_detail_grn_detail . ')');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Hold']);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Hold!']);
        }
    }
}
