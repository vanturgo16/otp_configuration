<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

// Model
use App\Models\HistoryStock;
use App\Models\MstFGs;
use App\Models\MstRawMaterials;
use App\Models\MstSpareparts;
use App\Models\MstWips;

class HistoryStockController extends Controller
{
    use AuditLogsTrait;

    // public function index(Request $request)
    // {

    //     // Search Variable
    //     $searchDate = $request->get('searchDate');
    //     $startdate = $request->get('startdate');
    //     $enddate = $request->get('enddate');
    //     $flag = $request->get('flag');

    //     // $datas = HistoryStock::select(
    //     //     'history_stocks.type_product',
    //     //     'history_stocks.id_master_products',
    //     //     DB::raw('
    //     //             CASE 
    //     //                 WHEN history_stocks.type_product = "RM" THEN master_raw_materials.rm_code 
    //     //                 WHEN history_stocks.type_product = "WIP" THEN master_wips.wip_code 
    //     //                 WHEN history_stocks.type_product = "FG" THEN master_product_fgs.product_code 
    //     //                 WHEN history_stocks.type_product = "TA" THEN master_tool_auxiliaries.code 
    //     //             END as product_code'),
    //     //     DB::raw('
    //     //             CASE 
    //     //                 WHEN history_stocks.type_product = "RM" THEN master_raw_materials.description 
    //     //                 WHEN history_stocks.type_product = "WIP" THEN master_wips.description 
    //     //                 WHEN history_stocks.type_product = "FG" THEN master_product_fgs.description 
    //     //                 WHEN history_stocks.type_product = "TA" THEN master_tool_auxiliaries.description 
    //     //             END as product_desc'),
    //     //     DB::raw('
    //     //             CASE 
    //     //                 WHEN history_stocks.type_product = "RM" THEN master_raw_materials.stock 
    //     //                 WHEN history_stocks.type_product = "WIP" THEN master_wips.stock 
    //     //                 WHEN history_stocks.type_product = "FG" THEN master_product_fgs.stock 
    //     //                 WHEN history_stocks.type_product = "TA" THEN master_tool_auxiliaries.stock 
    //     //             END as stock_master'),
    //     //     DB::raw('CAST(SUM(CASE WHEN history_stocks.type_product = "RM" AND history_stocks.type_stock = "IN" THEN history_stocks.qty ELSE 0 END) AS UNSIGNED) as total_in'),
    //     //     DB::raw('CAST(SUM(CASE WHEN history_stocks.type_product = "RM" AND history_stocks.type_stock = "OUT" THEN history_stocks.qty ELSE 0 END) AS UNSIGNED) as total_out'),
    //     //     DB::raw('
    //     //             CASE 
    //     //                 WHEN history_stocks.type_product = "RM" THEN master_raw_materials.id_master_departements 
    //     //                 WHEN history_stocks.type_product = "WIP" THEN master_wips.id_master_departements 
    //     //                 WHEN history_stocks.type_product = "FG" THEN master_product_fgs.id_master_departements 
    //     //                 WHEN history_stocks.type_product = "TA" THEN master_tool_auxiliaries.id_master_departements 
    //     //             END as id_master_departements'),
    //     //     'master_departements.name as departement_name'
    //     // )
    //     //     ->leftJoin('master_raw_materials', function ($join) {
    //     //         $join->on('history_stocks.id_master_products', '=', 'master_raw_materials.id')
    //     //             ->on('history_stocks.type_product', '=', DB::raw('"RM"'));
    //     //     })
    //     //     ->leftJoin('master_wips', function ($join) {
    //     //         $join->on('history_stocks.id_master_products', '=', 'master_wips.id')
    //     //             ->on('history_stocks.type_product', '=', DB::raw('"WIP"'));
    //     //     })
    //     //     ->leftJoin('master_product_fgs', function ($join) {
    //     //         $join->on('history_stocks.id_master_products', '=', 'master_product_fgs.id')
    //     //             ->on('history_stocks.type_product', '=', DB::raw('"FG"'));
    //     //     })
    //     //     ->leftJoin('master_tool_auxiliaries', function ($join) {
    //     //         $join->on('history_stocks.id_master_products', '=', 'master_tool_auxiliaries.id')
    //     //             ->on('history_stocks.type_product', '=', DB::raw('"TA"'));
    //     //     })
    //     //     ->leftJoin('master_departements', function ($join) {
    //     //         $join->on(DB::raw('
    //     //         CASE 
    //     //             WHEN history_stocks.type_product = "RM" THEN master_raw_materials.id_master_departements 
    //     //             WHEN history_stocks.type_product = "WIP" THEN master_wips.id_master_departements 
    //     //             WHEN history_stocks.type_product = "FG" THEN master_product_fgs.id_master_departements 
    //     //             WHEN history_stocks.type_product = "TA" THEN master_tool_auxiliaries.id_master_departements 
    //     //         END
    //     //     '), '=', 'master_departements.id');
    //     //     })
    //     //     ->groupBy(
    //     //         'history_stocks.type_product',
    //     //         'history_stocks.id_master_products',
    //     //         'master_raw_materials.rm_code',
    //     //         'master_raw_materials.description',
    //     //         'master_raw_materials.stock',
    //     //         'master_raw_materials.id_master_departements',
    //     //         'master_wips.wip_code',
    //     //         'master_wips.description',
    //     //         'master_wips.stock',
    //     //         'master_wips.id_master_departements',
    //     //         'master_product_fgs.product_code',
    //     //         'master_product_fgs.description',
    //     //         'master_product_fgs.stock',
    //     //         'master_product_fgs.id_master_departements',
    //     //         'master_tool_auxiliaries.code',
    //     //         'master_tool_auxiliaries.description',
    //     //         'master_tool_auxiliaries.stock',
    //     //         'master_tool_auxiliaries.id_master_departements',
    //     //         'master_departements.name'
    //     //     )
    //     //     ->where('history_stocks.type_product', "TA")
    //     //     ->limit(10)
    //     //     ->get();

    //     // dd($datas);

    //     $datas = HistoryStock::select(
    //         'history_stocks.id',
    //         'good_receipt_notes.receipt_number',
    //         'history_stocks.type_product',
    //         DB::raw('
    //                 CASE 
    //                     WHEN history_stocks.type_product = "RM" THEN master_raw_materials.rm_code 
    //                     WHEN history_stocks.type_product = "WIP" THEN master_wips.wip_code 
    //                     WHEN history_stocks.type_product = "FG" THEN master_product_fgs.product_code 
    //                     WHEN history_stocks.type_product = "TA" THEN master_tool_auxiliaries.code 
    //                 END as product_code'),
    //         DB::raw('
    //                 CASE 
    //                     WHEN history_stocks.type_product = "RM" THEN master_raw_materials.description 
    //                     WHEN history_stocks.type_product = "WIP" THEN master_wips.description 
    //                     WHEN history_stocks.type_product = "FG" THEN master_product_fgs.description 
    //                     WHEN history_stocks.type_product = "TA" THEN master_tool_auxiliaries.description 
    //                 END as product_desc'),
    //         'history_stocks.qty',
    //         'history_stocks.type_stock',
    //         'history_stocks.date',
    //         'history_stocks.remarks',
    //         'history_stocks.created_at',
    //         'history_stocks.updated_at',
    //     )
    //         ->leftjoin('good_receipt_note_details', 'history_stocks.id_good_receipt_notes_details', 'good_receipt_note_details.id')
    //         ->leftjoin('good_receipt_notes', 'good_receipt_note_details.id_good_receipt_notes', 'good_receipt_notes.id')
    //         ->leftJoin('master_raw_materials', function ($join) {
    //             $join->on('history_stocks.id_master_products', '=', 'master_raw_materials.id')
    //                 ->where('history_stocks.type_product', '=', 'RM');
    //         })
    //         ->leftJoin('master_wips', function ($join) {
    //             $join->on('history_stocks.id_master_products', '=', 'master_wips.id')
    //                 ->where('history_stocks.type_product', '=', 'WIP');
    //         })
    //         ->leftJoin('master_product_fgs', function ($join) {
    //             $join->on('history_stocks.id_master_products', '=', 'master_product_fgs.id')
    //                 ->where('history_stocks.type_product', '=', 'FG');
    //         })
    //         ->leftJoin('master_tool_auxiliaries', function ($join) {
    //             $join->on('history_stocks.id_master_products', '=', 'master_tool_auxiliaries.id')
    //                 ->where('history_stocks.type_product', '=', 'TA');
    //         })
    //         //Exclude FG & WIP (Show RM & TA)
    //         ->whereIn('history_stocks.type_product', ['RM', 'TA']);

    //     if ($startdate != null && $enddate != null) {
    //         $datas = $datas->whereDate('history_stocks.created_at', '>=', $startdate)->whereDate('history_stocks.created_at', '<=', $enddate);
    //     }

    //     if ($request->flag != null) {
    //         $datas = $datas->get()->makeHidden([
    //             'id'
    //         ]);
    //         return $datas;
    //     }

    //     $datas = $datas->orderBy('created_at', 'desc')->get();

    //     // Datatables
    //     if ($request->ajax()) {
    //         return DataTables::of($datas)
    //             ->make(true);
    //     }

    //     //Audit Log
    //     $this->auditLogsShort('View List HistoryStock');

    //     return view('historystock.index', compact('datas', 'searchDate', 'startdate', 'enddate', 'flag'));
    // }

    public function index(Request $request)
    {
        //Audit Log
        $this->auditLogsShort('View List History Stock');
        return view('historystock.index');
    }

    public function indexRM(Request $request)
    {
        $datas = HistoryStock::select(
            'history_stocks.type_product',
            'history_stocks.id_master_products',
            'master_raw_materials.rm_code',
            'master_raw_materials.description',
            'master_raw_materials.stock',
            DB::raw('CAST(SUM(CASE WHEN history_stocks.type_product = "RM" AND history_stocks.type_stock = "IN" THEN history_stocks.qty ELSE 0 END) AS UNSIGNED) as total_in'),
            DB::raw('CAST(SUM(CASE WHEN history_stocks.type_product = "RM" AND history_stocks.type_stock = "OUT" THEN history_stocks.qty ELSE 0 END) AS UNSIGNED) as total_out'),
            'master_raw_materials.id_master_departements',
            'master_departements.name as departement_name'
        )
            ->leftjoin('master_raw_materials', 'history_stocks.id_master_products', 'master_raw_materials.id')
            ->leftjoin('master_departements', 'master_raw_materials.id_master_departements', 'master_departements.id')
            ->where('history_stocks.type_product', "RM")
            ->groupBy(
                'history_stocks.type_product',
                'history_stocks.id_master_products',
                'master_raw_materials.rm_code',
                'master_raw_materials.description',
                'master_raw_materials.stock',
                'master_raw_materials.id_master_departements',
                'master_departements.name'
            )
            ->get();

        // Datatables
        if ($request->ajax()) {
            return DataTables::of($datas)
                ->addColumn('action', function ($data) {
                    return view('historystock.action', compact('data'));
                })->make(true);
        }
    }

    public function indexWIP(Request $request)
    {
        $datas = HistoryStock::select(
            'history_stocks.type_product',
            'history_stocks.id_master_products',
            'master_wips.wip_code',
            'master_wips.description',
            'master_wips.stock',
            'master_wips.id_master_departements',
            'master_departements.name as departement_name'
        )
            ->leftjoin('master_wips', 'history_stocks.id_master_products', 'master_wips.id')
            ->leftjoin('master_departements', 'master_wips.id_master_departements', 'master_departements.id')
            ->where('history_stocks.type_product', "WIP")
            ->groupBy(
                'history_stocks.type_product',
                'history_stocks.id_master_products',
                'master_wips.wip_code',
                'master_wips.description',
                'master_wips.stock',
                'master_wips.id_master_departements',
                'master_departements.name'
            )
            ->get();

        // Datatables
        if ($request->ajax()) {
            return DataTables::of($datas)
                ->addColumn('action', function ($data) {
                    return view('historystock.action', compact('data'));
                })->make(true);
        }
    }

    public function indexFG(Request $request)
    {
        $datas = HistoryStock::select(
            'history_stocks.type_product',
            'history_stocks.id_master_products',
            'master_product_fgs.product_code',
            'master_product_fgs.description',
            'master_product_fgs.stock',
            'master_product_fgs.id_master_departements',
            'master_departements.name as departement_name'
        )
            ->leftjoin('master_product_fgs', 'history_stocks.id_master_products', 'master_product_fgs.id')
            ->leftjoin('master_departements', 'master_product_fgs.id_master_departements', 'master_departements.id')
            ->where('history_stocks.type_product', "FG")
            ->groupBy(
                'history_stocks.type_product',
                'history_stocks.id_master_products',
                'master_product_fgs.product_code',
                'master_product_fgs.description',
                'master_product_fgs.stock',
                'master_product_fgs.id_master_departements',
                'master_departements.name'
            )
            ->get();

        // Datatables
        if ($request->ajax()) {
            return DataTables::of($datas)
                ->addColumn('action', function ($data) {
                    return view('historystock.action', compact('data'));
                })->make(true);
        }
    }

    public function indexTA(Request $request)
    {
        $datas = HistoryStock::select(
            'history_stocks.type_product',
            'history_stocks.id_master_products',
            'master_tool_auxiliaries.code',
            'master_tool_auxiliaries.description',
            'master_tool_auxiliaries.stock',
            DB::raw('CAST(SUM(CASE WHEN history_stocks.type_product = "TA" AND history_stocks.type_stock = "IN" THEN history_stocks.qty ELSE 0 END) AS UNSIGNED) as total_in'),
            DB::raw('CAST(SUM(CASE WHEN history_stocks.type_product = "TA" AND history_stocks.type_stock = "OUT" THEN history_stocks.qty ELSE 0 END) AS UNSIGNED) as total_out'),
            'master_tool_auxiliaries.id_master_departements',
            'master_departements.name as departement_name'
        )
            ->leftjoin('master_tool_auxiliaries', 'history_stocks.id_master_products', 'master_tool_auxiliaries.id')
            ->leftjoin('master_departements', 'master_tool_auxiliaries.id_master_departements', 'master_departements.id')
            ->where('history_stocks.type_product', "TA")
            ->groupBy(
                'history_stocks.type_product',
                'history_stocks.id_master_products',
                'master_tool_auxiliaries.code',
                'master_tool_auxiliaries.description',
                'master_tool_auxiliaries.stock',
                'master_tool_auxiliaries.id_master_departements',
                'master_departements.name'
            )
            ->get();

        // Datatables
        if ($request->ajax()) {
            return DataTables::of($datas)
                ->addColumn('action', function ($data) {
                    return view('historystock.action', compact('data'));
                })->make(true);
        }
    }

    public function historyRM(Request $request, $id)
    {
        $id = decrypt($id);
        $detail = MstRawMaterials::where('id', $id)->first();
        $datas = HistoryStock::select('detail_good_receipt_note_details.lot_number', 'history_stocks.*')
            ->leftjoin('detail_good_receipt_note_details', 'history_stocks.id_good_receipt_notes_details', 'detail_good_receipt_note_details.id')
            ->where('id_master_products', $id)
            ->get();
        if ($request->ajax()) {
            return DataTables::of($datas)->make(true);
        }
        //Audit Log
        $this->auditLogsShort('View Detail History Stock RM');

        return view('historystock.rm.history', compact('detail', 'id'));
    }

    public function historyWIP(Request $request, $id)
    {
        $id = decrypt($id);
        $detail = MstWips::where('id', $id)->first();
        $datas = HistoryStock::select('detail_good_receipt_note_details.lot_number', 'history_stocks.*')
            ->leftjoin('detail_good_receipt_note_details', 'history_stocks.id_good_receipt_notes_details', 'detail_good_receipt_note_details.id')
            ->where('id_master_products', $id)
            ->get();
        if ($request->ajax()) {
            return DataTables::of($datas)->make(true);
        }
        //Audit Log
        $this->auditLogsShort('View Detail History Stock WIP');

        return view('historystock.wip.history', compact('detail', 'id'));
    }

    public function historyFG(Request $request, $id)
    {
        $id = decrypt($id);
        $detail = MstFGs::where('id', $id)->first();
        $datas = HistoryStock::select('detail_good_receipt_note_details.lot_number', 'history_stocks.*')
            ->leftjoin('detail_good_receipt_note_details', 'history_stocks.id_good_receipt_notes_details', 'detail_good_receipt_note_details.id')
            ->where('id_master_products', $id)
            ->get();
        if ($request->ajax()) {
            return DataTables::of($datas)->make(true);
        }
        //Audit Log
        $this->auditLogsShort('View Detail History Stock FG');

        return view('historystock.fg.history', compact('detail', 'id'));
    }

    public function historyTA(Request $request, $id)
    {
        $id = decrypt($id);
        $detail = MstSpareparts::where('id', $id)->first();
        $datas = HistoryStock::select('detail_good_receipt_note_details.lot_number', 'history_stocks.*')
            ->leftjoin('detail_good_receipt_note_details', 'history_stocks.id_good_receipt_notes_details', 'detail_good_receipt_note_details.id')
            ->where('id_master_products', $id)
            ->get();
        if ($request->ajax()) {
            return DataTables::of($datas)->make(true);
        }
        //Audit Log
        $this->auditLogsShort('View Detail History Stock TA');

        return view('historystock.ta.history', compact('detail', 'id'));
    }
}
