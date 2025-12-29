<?php

namespace App\Http\Controllers;

use App\Traits\AuditLogsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Browser;

// Model
use App\Models\MstDropdowns;

class MstDropdownsController extends Controller
{
    use AuditLogsTrait;

    public function index(Request $request)
    {
        // Initiate Variable
        $category = MstDropdowns::select('category')->get();
        $category = $category->unique('category');

        // Search Variable
        $categories = $request->get('categories');
        $name_value = $request->get('name_value');
        $searchDate = $request->get('searchDate');
        $startdate = $request->get('startdate');
        $enddate = $request->get('enddate');
        $flag = $request->get('flag');

        $idUpdated = $request->get('idUpdated');

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

        $datas = $datas->orderBy('created_at', 'desc');
        
        // Datatables
        if ($request->ajax()) {
            return DataTables::of($datas)
                ->addColumn('action', function ($data) use ($category){
                    return view('dropdown.action', compact('data', 'category'));
                })
                ->addColumn('bulk-action', function ($data) {
                    $checkBox = '<input type="checkbox" id="checkboxdt" name="checkbox" data-id-data="' . $data->id . '" />';
                    return $checkBox;
                })
                ->rawColumns(['bulk-action'])
                ->make(true);
        }
        
        // Get Page Number
        $page_number = 1;
        if ($idUpdated) {
            $page_size = 5;
            $datas = $datas->get();
            $item = $datas->firstWhere('id', $idUpdated);
            if ($item) {
                $index = $datas->search(function ($value) use ($idUpdated) {
                    return $value->id == $idUpdated;
                });
                $page_number = (int) ceil(($index + 1) / $page_size);
            } else {
                $page_number = 1;
            }
        }
        
        //Audit Log
        $this->auditLogsShort('View List Mst Dropdown');

        return view('dropdown.index',compact('datas', 'category',
            'categories', 'name_value', 'searchDate', 'startdate', 'enddate', 'flag', 'idUpdated', 'page_number'));
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
            $this->auditLogsShort('Create New Dropdown');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Create New Dropdown']);
        } catch (Exception $e) {
            DB::rollback();
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
                $this->auditLogsShort('Update Dropdown');

                DB::commit();
                return redirect()->route('dropdown.index', ['idUpdated' => $id])->with(['success' => 'Success Update Dropdown']);
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->route('dropdown.index', ['idUpdated' => $id])->with(['fail' => 'Failed to Update Dropdown!']);
            }
        } else {
            return redirect()->route('dropdown.index', ['idUpdated' => $id])->with(['info' => 'Nothing Change, The data entered is the same as the previous one!']);
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
            $this->auditLogsShort('Delete Dropdown ('. $name . ')');

            DB::commit();
            return redirect()->back()->with(['success' => 'Success Delete Dropdown ' . $name]);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['fail' => 'Failed to Delete Dropdown ' . $name .'!']);
        }
    }
    public function deleteselected(Request $request)
    {
        $idselected = $request->input('idChecked');

        DB::beginTransaction();
        try{
            $name_value = MstDropdowns::whereIn('id', $idselected)->pluck('name_value')->toArray();;
            $delete = MstDropdowns::whereIn('id', $idselected)->delete();

            //Audit Log
            $this->auditLogsShort('Delete Dropdown Selected : ' . implode(', ', $name_value));

            DB::commit();
            return response()->json(['message' => 'Successfully Deleted Data : ' . implode(', ', $name_value), 'type' => 'success'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to Delete Data', 'type' => 'error'], 500);
        }
    }
}
