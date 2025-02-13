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
use App\Models\BarcodeDetail;
use App\Models\DetailGoodReceiptNoteDetail;
use App\Models\GoodReceiptNoteDetail;

class HistoryStockController extends Controller
{
    use AuditLogsTrait;
    

    // RAW MATERIAL
    public function indexRM(Request $request)
    {
        $datas = HistoryStock::select(
            'history_stocks.type_product',
            'history_stocks.id_master_products',
            DB::raw('MIN(CASE WHEN history_stocks.barcode IS NOT NULL THEN history_stocks.barcode END) as barcode'),
            'master_raw_materials.rm_code',
            'master_raw_materials.description',
            'master_raw_materials.stock',
            DB::raw('SUM(CASE WHEN history_stocks.type_product = "RM" AND history_stocks.type_stock = "IN" THEN history_stocks.qty ELSE 0 END) as total_in'),
            DB::raw('SUM(CASE WHEN history_stocks.type_product = "RM" AND history_stocks.type_stock = "OUT" THEN history_stocks.qty ELSE 0 END) as total_out'),
            'master_raw_materials.id_master_departements',
            'master_departements.name as departement_name',
            DB::raw('TRIM(TRAILING ".0" FROM (master_raw_materials.stock + 
                    SUM(CASE WHEN history_stocks.type_product = "RM" AND history_stocks.type_stock = "IN" THEN history_stocks.qty ELSE 0 END) - 
                    SUM(CASE WHEN history_stocks.type_product = "RM" AND history_stocks.type_stock = "OUT" THEN history_stocks.qty ELSE 0 END)
                    )) AS total_stock')
        )
        ->leftJoin('master_raw_materials', 'history_stocks.id_master_products', '=', 'master_raw_materials.id')
        ->leftJoin('master_departements', 'master_raw_materials.id_master_departements', '=', 'master_departements.id')
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
                ->addColumn('barcode', function ($data) {
                    return view('historystock.barcode', compact('data'));
                })
                ->addColumn('action', function ($data) {
                    return view('historystock.rm.action', compact('data'));
                })
                ->make(true);
        }

        //Audit Log
        $this->auditLogsShort('View Detail History Stock RM');
        return view('historystock.rm.index');
    }
    public function historyRM(Request $request, $id)
    {
        $id = decrypt($id);
        $detail = MstRawMaterials::where('id', $id)->first();
        $datas = HistoryStock::select('good_receipt_note_details.lot_number', 'good_receipt_note_details.id as idGrnDetail', 'history_stocks.*')
            ->leftjoin('good_receipt_note_details', 'history_stocks.id_good_receipt_notes_details', 'good_receipt_note_details.id')
            ->where('history_stocks.id_master_products', $id)
            ->where('history_stocks.type_product', 'RM')
            ->get();

        if ($request->ajax()) {
            return DataTables::of($datas)
                ->addColumn('action', function ($data) {
                    return view('historystock.rm.history.action', compact('data'));
                })->make(true);
        }
        //Audit Log
        $this->auditLogsShort('View Detail History Stock RM Code '.$detail->rm_code);

        return view('historystock.rm.history.index', compact('detail', 'id'));
    }
    public function detailLotRM(Request $request, $id)
    {
        $id = decrypt($id);

        $grnDetail = GoodReceiptNoteDetail::where('id', $id)->first();
        $detail = MstRawMaterials::where('id', $grnDetail->id_master_products)->first();
        $datas = DetailGoodReceiptNoteDetail::where('id_grn_detail', $id)->get();

        if ($request->ajax()) {
            return DataTables::of($datas)->make(true);
        }
        //Audit Log
        $this->auditLogsShort('View Detail History Stock RM Lot Number '.$grnDetail->lot_number);

        return view('historystock.rm.history.detailLot', compact('grnDetail', 'detail', 'datas', 'id'));
    }


    // WORK IN PROGRESS
    public function indexWIP(Request $request)
    {
        $datas = [];

        // Datatables
        if ($request->ajax()) {
            return DataTables::of($datas)
                ->addColumn('barcode', function ($data) {
                    return view('historystock.barcode', compact('data'));
                })
                ->addColumn('action', function ($data) {
                    return view('historystock.wip.action', compact('data'));
                })
                ->make(true);
        }

        //Audit Log
        $this->auditLogsShort('View Detail History Stock WIP');
        return view('historystock.wip.index');
    }


    // FINISH GOOD 
    public function indexFG(Request $request)
    {
        $datas = [];
        
        // Datatables
        if ($request->ajax()) {
            return DataTables::of($datas)
                ->addColumn('barcode', function ($data) {
                    return view('historystock.barcode', compact('data'));
                })
                ->addColumn('action', function ($data) {
                    return view('historystock.fg.action', compact('data'));
                })
                ->make(true);
        }
        
        //Audit Log
        $this->auditLogsShort('View Detail History Stock FG');
        return view('historystock.fg.index');
    }


    // TOOL & AUXALARY
    public function indexTA(Request $request)
    {
        $datas = [];
        
        // Datatables
        if ($request->ajax()) {
            return DataTables::of($datas)
                ->addColumn('barcode', function ($data) {
                    return view('historystock.barcode', compact('data'));
                })
                ->addColumn('action', function ($data) {
                    return view('historystock.ta.action', compact('data'));
                })
                ->make(true);
        }
        
        //Audit Log
        $this->auditLogsShort('View Detail History Stock TA');
        return view('historystock.ta.index');
    }
}
