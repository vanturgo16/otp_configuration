<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Browser;

// Model
use App\Models\MstDropdowns;

class MstDropdownsController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        $categories = $request->get('categories');
        $name_value = $request->get('name_value');
        $searchDate = $request->get('searchDate');
        $startdate = $request->get('startdate');
        $enddate = $request->get('enddate');
        $flag = $request->get('flag');

        $datas = MstDropdowns::select(
            DB::raw('ROW_NUMBER() OVER (ORDER BY id) as no'),
            'master_dropdowns.*'
        );

        if($categories != null){
            $datas = $datas->where('category', $categories);
        }
        if($name_value != null){
            $datas = $datas->where('name_value', 'like', '%'.$name_value.'%');
        }
        if($startdate != null && $enddate != null){
            $datas = $datas->whereDate('created_at','>=',$startdate)->whereDate('created_at','<=',$enddate);
        }
        
        if($request->flag != null){
            $datas = $datas->get()->makeHidden(['id']);
            return $datas;
        }

        $datas = $datas->paginate(10);

        $category = MstDropdowns::select('category')->get();
        $category = $category->unique('category');
        
        //Audit Log
        $username= auth()->user()->email; 
        $ipAddress=$_SERVER['REMOTE_ADDR'];
        $location='0';
        $access_from=Browser::browserName();
        $activity='View List Mst Dropdown';
        $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

        return view('dropdown.index',compact('datas', 'category',
            'categories', 'name_value', 'searchDate', 'startdate', 'enddate', 'flag'));
    }
    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'category' => 'required',
            'name_value' => 'required',
            'code_format' => 'required',
        ]);

        if($request->category == "NewCat"){
            $category = $request->addcategory;
        }
        else{
            $category = $request->category;
        }

        DB::beginTransaction();
        try{
            
            MstDropdowns::create([
                'category' => $category,
                'name_value' => $request->name_value,
                'code_format' => $request->code_format
            ]);

            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Create New Dropdown';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Create New Dropdown']);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Create New Dropdown!']);
        }
    }
    public function update(Request $request, $id)
    {
        // dd($request->all());

        $id = decrypt($id);

        $request->validate([
            'category' => 'required',
            'name_value' => 'required',
            'code_format' => 'required',
        ]);

        if($request->category == "NewCat"){
            $category = $request->addcategory;
        }
        else{
            $category = $request->category;
        }

        $databefore = MstDropdowns::where('id', $id)->first();
        $databefore->category = $category;
        $databefore->name_value = $request->name_value;
        $databefore->code_format = $request->code_format;

        if($databefore->isDirty()){
            DB::beginTransaction();
            try{
                MstDropdowns::where('id', $id)->update([
                    'category' => $category,
                    'name_value' => $request->name_value,
                    'code_format' => $request->code_format
                ]);

                //Audit Log
                $username= auth()->user()->email; 
                $ipAddress=$_SERVER['REMOTE_ADDR'];
                $location='0';
                $access_from=Browser::browserName();
                $activity='Update Dropdown';
                $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

                DB::commit();
                return redirect()->back()->with(['success' => 'Success Update Dropdown']);
            } catch (\Exception $e) {
                dd($e);
                return redirect()->back()->with(['fail' => 'Failed to Update Dropdown!']);
            }
        } else {
            return redirect()->back()->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
        }
    }
    public function delete($id)
    {
        $id = decrypt($id);

        // dd($id);

        DB::beginTransaction();
        try{
            $name = MstDropdowns::where('id', $id)->first()->name_value;

            MstDropdowns::where('id', $id)->delete();


            //Audit Log
            $username= auth()->user()->email; 
            $ipAddress=$_SERVER['REMOTE_ADDR'];
            $location='0';
            $access_from=Browser::browserName();
            $activity='Delete Dropdown ('. $name . ')';
            $this->auditLogs($username,$ipAddress,$location,$access_from,$activity);

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Delete Dropdown ' . $name]);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with(['fail' => 'Failed to Delete Dropdown ' . $name .'!']);
        }
    }
}
