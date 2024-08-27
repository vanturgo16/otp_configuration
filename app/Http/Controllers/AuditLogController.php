<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

// Model
use App\Models\AuditLog;

class AuditLogController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request){

        // Search Variable
        $searchDate = $request->get('searchDate');
        $startdate = $request->get('startdate');
        $enddate = $request->get('enddate');
        $flag = $request->get('flag');
        
        $logs = AuditLog::select(DB::raw('ROW_NUMBER() OVER (ORDER BY id) as no'), 'audit_logs_config.*');

        if($startdate != null && $enddate != null){
            $logs = $logs->whereDate('audit_logs_config.created_at','>=',$startdate)->whereDate('audit_logs_config.created_at','<=',$enddate);
        }

        if($request->flag != null){
            $logs = $logs->get()->makeHidden([
                'id'
            ]);
            return $logs;
        }

        $logs = $logs->orderBy('created_at', 'desc')->get();
        
        // Datatables
        if ($request->ajax()) {
            return DataTables::of($logs)->make(true);
        }
        
        //Audit Log
        $this->auditLogsShort('View List Audit Log');

        return view('auditlog.index',compact('logs', 'searchDate', 'startdate', 'enddate', 'flag'));
    }
}
