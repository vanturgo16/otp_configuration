<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

// Model
use App\Models\HistoryStock;

class HistoryStockController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request){

        // Search Variable
        $searchDate = $request->get('searchDate');
        $startdate = $request->get('startdate');
        $enddate = $request->get('enddate');
        $flag = $request->get('flag');
        
        $datas = HistoryStock::select(
                'history_stocks.id',
                'good_receipt_notes.receipt_number',
                'history_stocks.type_product',
                DB::raw('
                    CASE 
                        WHEN history_stocks.type_product = "RM" THEN master_raw_materials.rm_code 
                        WHEN history_stocks.type_product = "WIP" THEN master_wips.wip_code 
                        WHEN history_stocks.type_product = "FG" THEN master_product_fgs.product_code 
                        WHEN history_stocks.type_product = "TA" THEN master_tool_auxiliaries.code 
                    END as product_code'),
                DB::raw('
                    CASE 
                        WHEN history_stocks.type_product = "RM" THEN master_raw_materials.description 
                        WHEN history_stocks.type_product = "WIP" THEN master_wips.description 
                        WHEN history_stocks.type_product = "FG" THEN master_product_fgs.description 
                        WHEN history_stocks.type_product = "TA" THEN master_tool_auxiliaries.description 
                    END as product_desc'),
                'history_stocks.qty',
                'history_stocks.type_stock',
                'history_stocks.date',
                'history_stocks.remarks',
                'history_stocks.created_at',
                'history_stocks.updated_at',
            )
            ->leftjoin('good_receipt_note_details', 'history_stocks.id_good_receipt_notes_details', 'good_receipt_note_details.id')
            ->leftjoin('good_receipt_notes', 'good_receipt_note_details.id_good_receipt_notes', 'good_receipt_notes.id')
            ->leftJoin('master_raw_materials', function ($join) {
                $join->on('history_stocks.id_master_products', '=', 'master_raw_materials.id')
                    ->where('history_stocks.type_product', '=', 'RM');
            })
            ->leftJoin('master_wips', function ($join) {
                $join->on('history_stocks.id_master_products', '=', 'master_wips.id')
                    ->where('history_stocks.type_product', '=', 'WIP');
            })
            ->leftJoin('master_product_fgs', function ($join) {
                $join->on('history_stocks.id_master_products', '=', 'master_product_fgs.id')
                    ->where('history_stocks.type_product', '=', 'FG');
            })
            ->leftJoin('master_tool_auxiliaries', function ($join) {
                $join->on('history_stocks.id_master_products', '=', 'master_tool_auxiliaries.id')
                    ->where('history_stocks.type_product', '=', 'TA');
            })
            //Exclude FG & WIP (Show RM & TA)
            ->whereIn('history_stocks.type_product', ['RM', 'TA']);

        if($startdate != null && $enddate != null){
            $datas = $datas->whereDate('history_stocks.created_at','>=',$startdate)->whereDate('history_stocks.created_at','<=',$enddate);
        }

        if($request->flag != null){
            $datas = $datas->get()->makeHidden([
                'id'
            ]);
            return $datas;
        }

        $datas = $datas->orderBy('created_at', 'desc')->get();
        
        // Datatables
        if ($request->ajax()) {
            return DataTables::of($datas)
            ->addColumn('bulk-action', function ($data) {
                $checkBox = '<input type="checkbox" id="checkboxdt" name="checkbox" data-id-data="' . $data->id . '" />';
                return $checkBox;
            })
            ->rawColumns(['bulk-action'])
            ->make(true);
        }
        
        //Audit Log
        $this->auditLogsShort('View List HistoryStock');

        return view('historystock.index',compact('datas', 'searchDate', 'startdate', 'enddate', 'flag'));
    }
}
