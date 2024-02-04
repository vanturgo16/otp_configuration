<?php

use App\Http\Controllers\AuditLogController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MstApprovalsController;
use App\Http\Controllers\MstBagiansController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MstCompaniesController;
use App\Http\Controllers\MstCostCentersController;
use App\Http\Controllers\MstProvincesController;
use App\Http\Controllers\MstCountriesController;
use App\Http\Controllers\MstCurrenciesController;
use App\Http\Controllers\MstCustomerAddressController;
use App\Http\Controllers\MstCustomersController;
use App\Http\Controllers\MstDepartmentsController;
use App\Http\Controllers\MstDowntimesController;
use App\Http\Controllers\MstDropdownsController;
use App\Http\Controllers\MstEmployeesController;
use App\Http\Controllers\MstFGRefsController;
use App\Http\Controllers\MstFGsController;
use App\Http\Controllers\MstGroupsController;
use App\Http\Controllers\MstGroupSubsController;
use App\Http\Controllers\MstOperatorsController;
use App\Http\Controllers\MstProcessProductionsController;
use App\Http\Controllers\MstRawMaterialsController;
use App\Http\Controllers\MstReasonsController;
use App\Http\Controllers\MstRegusController;
use App\Http\Controllers\MstSalesmansController;
use App\Http\Controllers\MstSparepartsController;
use App\Http\Controllers\MstSuppliersController;
use App\Http\Controllers\MstTermPaymentsController;
use App\Http\Controllers\MstUnitsController;
use App\Http\Controllers\MstVehiclesController;
use App\Http\Controllers\MstWarehousesController;
use App\Http\Controllers\MstWastesController;
use App\Http\Controllers\MstWipRefsController;
use App\Http\Controllers\MstWipRefWipsController;
use App\Http\Controllers\MstWipsController;
use App\Http\Controllers\MstWorkCentersController;
use App\Models\MstProcessProductions;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Route Login
Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('auth/login', [AuthController::class, 'postlogin'])->name('postlogin')->middleware("throttle:5,2");

//Route Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    //Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //User
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::post('/user', [UserController::class, 'index'])->name('user.index');
    Route::post('user/create', [UserController::class, 'store'])->name('user.store');
    Route::post('user/update/{id}', [UserController::class, 'update'])->name('user.update');
    Route::post('user/activate/{id}', [UserController::class, 'activate'])->name('user.activate');
    Route::post('user/deactivate/{id}', [UserController::class, 'deactivate'])->name('user.deactivate');
    Route::post('user/delete/{id}', [UserController::class, 'delete'])->name('user.delete');

    //Company
    Route::get('/company', [MstCompaniesController::class, 'index'])->name('company.index');
    Route::post('/company', [MstCompaniesController::class, 'index'])->name('company.index');
    Route::post('company/create', [MstCompaniesController::class, 'store'])->name('company.store');
    Route::post('company/update/{id}', [MstCompaniesController::class, 'update'])->name('company.update');
    Route::post('company/activate/{id}', [MstCompaniesController::class, 'activate'])->name('company.activate');
    Route::post('company/deactivate/{id}', [MstCompaniesController::class, 'deactivate'])->name('company.deactivate');

    //Province
    Route::get('/province', [MstProvincesController::class, 'index'])->name('province.index');
    Route::post('/province', [MstProvincesController::class, 'index'])->name('province.index');
    Route::post('province/create', [MstProvincesController::class, 'store'])->name('province.store');
    Route::post('province/update/{id}', [MstProvincesController::class, 'update'])->name('province.update');
    Route::post('province/activate/{id}', [MstProvincesController::class, 'activate'])->name('province.activate');
    Route::post('province/deactivate/{id}', [MstProvincesController::class, 'deactivate'])->name('province.deactivate');

    //Country
    Route::get('/country', [MstCountriesController::class, 'index'])->name('country.index');
    Route::post('/country', [MstCountriesController::class, 'index'])->name('country.index');
    Route::post('country/create', [MstCountriesController::class, 'store'])->name('country.store');
    Route::post('country/update/{id}', [MstCountriesController::class, 'update'])->name('country.update');
    Route::post('country/activate/{id}', [MstCountriesController::class, 'activate'])->name('country.activate');
    Route::post('country/deactivate/{id}', [MstCountriesController::class, 'deactivate'])->name('country.deactivate');

    //Currency
    Route::get('/currency', [MstCurrenciesController::class, 'index'])->name('currency.index');
    Route::post('/currency', [MstCurrenciesController::class, 'index'])->name('currency.index');
    Route::post('currency/create', [MstCurrenciesController::class, 'store'])->name('currency.store');
    Route::post('currency/update/{id}', [MstCurrenciesController::class, 'update'])->name('currency.update');
    Route::post('currency/activate/{id}', [MstCurrenciesController::class, 'activate'])->name('currency.activate');
    Route::post('currency/deactivate/{id}', [MstCurrenciesController::class, 'deactivate'])->name('currency.deactivate');

    //Department
    Route::get('/department', [MstDepartmentsController::class, 'index'])->name('department.index');
    Route::post('/department', [MstDepartmentsController::class, 'index'])->name('department.index');
    Route::post('department/create', [MstDepartmentsController::class, 'store'])->name('department.store');
    Route::post('department/update/{id}', [MstDepartmentsController::class, 'update'])->name('department.update');
    Route::post('department/activate/{id}', [MstDepartmentsController::class, 'activate'])->name('department.activate');
    Route::post('department/deactivate/{id}', [MstDepartmentsController::class, 'deactivate'])->name('department.deactivate');
    Route::get('department/mappingBagian/{id}', [MstDepartmentsController::class, 'mappingBagian'])->name('department.mappingBagian');

    //Bagian
    Route::get('/bagian/{id}', [MstBagiansController::class, 'index'])->name('bagian.index');
    Route::post('/bagian/{id}', [MstBagiansController::class, 'index'])->name('bagian.index');
    Route::post('bagian/create/{id}', [MstBagiansController::class, 'store'])->name('bagian.store');
    Route::post('bagian/update/{id}', [MstBagiansController::class, 'update'])->name('bagian.update');
    Route::post('bagian/activate/{id}', [MstBagiansController::class, 'activate'])->name('bagian.activate');
    Route::post('bagian/deactivate/{id}', [MstBagiansController::class, 'deactivate'])->name('bagian.deactivate');

    //Salesman
    Route::get('/salesman', [MstSalesmansController::class, 'index'])->name('salesman.index');
    Route::post('/salesman', [MstSalesmansController::class, 'index'])->name('salesman.index');
    Route::post('salesman/create', [MstSalesmansController::class, 'store'])->name('salesman.store');
    Route::post('salesman/update/{id}', [MstSalesmansController::class, 'update'])->name('salesman.update');
    Route::post('salesman/activate/{id}', [MstSalesmansController::class, 'activate'])->name('salesman.activate');
    Route::post('salesman/deactivate/{id}', [MstSalesmansController::class, 'deactivate'])->name('salesman.deactivate');

    //Group
    Route::get('/group', [MstGroupsController::class, 'index'])->name('group.index');
    Route::post('/group', [MstGroupsController::class, 'index'])->name('group.index');
    Route::post('group/create', [MstGroupsController::class, 'store'])->name('group.store');
    Route::post('group/update/{id}', [MstGroupsController::class, 'update'])->name('group.update');
    Route::post('group/activate/{id}', [MstGroupsController::class, 'activate'])->name('group.activate');
    Route::post('group/deactivate/{id}', [MstGroupsController::class, 'deactivate'])->name('group.deactivate');

    //Group Sub
    Route::get('/groupsub', [MstGroupSubsController::class, 'index'])->name('groupsub.index');
    Route::post('/groupsub', [MstGroupSubsController::class, 'index'])->name('groupsub.index');
    Route::post('groupsub/create', [MstGroupSubsController::class, 'store'])->name('groupsub.store');
    Route::post('groupsub/update/{id}', [MstGroupSubsController::class, 'update'])->name('groupsub.update');
    Route::post('groupsub/activate/{id}', [MstGroupSubsController::class, 'activate'])->name('groupsub.activate');
    Route::post('groupsub/deactivate/{id}', [MstGroupSubsController::class, 'deactivate'])->name('groupsub.deactivate');

    //Unit
    Route::get('/unit', [MstUnitsController::class, 'index'])->name('unit.index');
    Route::post('/unit', [MstUnitsController::class, 'index'])->name('unit.index');
    Route::post('unit/create', [MstUnitsController::class, 'store'])->name('unit.store');
    Route::post('unit/update/{id}', [MstUnitsController::class, 'update'])->name('unit.update');
    Route::post('unit/activate/{id}', [MstUnitsController::class, 'activate'])->name('unit.activate');
    Route::post('unit/deactivate/{id}', [MstUnitsController::class, 'deactivate'])->name('unit.deactivate');

    //Term Payment
    Route::get('/termpayment', [MstTermPaymentsController::class, 'index'])->name('termpayment.index');
    Route::post('/termpayment', [MstTermPaymentsController::class, 'index'])->name('termpayment.index');
    Route::post('termpayment/create', [MstTermPaymentsController::class, 'store'])->name('termpayment.store');
    Route::post('termpayment/update/{id}', [MstTermPaymentsController::class, 'update'])->name('termpayment.update');
    Route::post('termpayment/activate/{id}', [MstTermPaymentsController::class, 'activate'])->name('termpayment.activate');
    Route::post('termpayment/deactivate/{id}', [MstTermPaymentsController::class, 'deactivate'])->name('termpayment.deactivate');

    //Cost Center
    Route::get('/costcenter', [MstCostCentersController::class, 'index'])->name('costcenter.index');
    Route::post('/costcenter', [MstCostCentersController::class, 'index'])->name('costcenter.index');
    Route::post('costcenter/create', [MstCostCentersController::class, 'store'])->name('costcenter.store');
    Route::post('costcenter/update/{id}', [MstCostCentersController::class, 'update'])->name('costcenter.update');
    Route::post('costcenter/activate/{id}', [MstCostCentersController::class, 'activate'])->name('costcenter.activate');
    Route::post('costcenter/deactivate/{id}', [MstCostCentersController::class, 'deactivate'])->name('costcenter.deactivate');

    //Process Production
    Route::get('/processproduction', [MstProcessProductionsController::class, 'index'])->name('processproduction.index');
    Route::post('/processproduction', [MstProcessProductionsController::class, 'index'])->name('processproduction.index');
    Route::post('processproduction/create', [MstProcessProductionsController::class, 'store'])->name('processproduction.store');
    Route::post('processproduction/update/{id}', [MstProcessProductionsController::class, 'update'])->name('processproduction.update');
    Route::post('processproduction/activate/{id}', [MstProcessProductionsController::class, 'activate'])->name('processproduction.activate');
    Route::post('processproduction/deactivate/{id}', [MstProcessProductionsController::class, 'deactivate'])->name('processproduction.deactivate');

    //WorkCenter
    Route::get('/workcenter/{id}', [MstWorkCentersController::class, 'index'])->name('workcenter.index');
    Route::post('/workcenter/{id}', [MstWorkCentersController::class, 'index'])->name('workcenter.index');
    Route::post('workcenter/create/{id}', [MstWorkCentersController::class, 'store'])->name('workcenter.store');
    Route::post('workcenter/update/{id}', [MstWorkCentersController::class, 'update'])->name('workcenter.update');
    Route::post('workcenter/activate/{id}', [MstWorkCentersController::class, 'activate'])->name('workcenter.activate');
    Route::post('workcenter/deactivate/{id}', [MstWorkCentersController::class, 'deactivate'])->name('workcenter.deactivate');

    //Regu
    Route::get('/regu/{id}', [MstRegusController::class, 'index'])->name('regu.index');
    Route::post('/regu/{id}', [MstRegusController::class, 'index'])->name('regu.index');
    Route::post('regu/create/{id}', [MstRegusController::class, 'store'])->name('regu.store');
    Route::post('regu/update/{id}', [MstRegusController::class, 'update'])->name('regu.update');
    Route::post('regu/activate/{id}', [MstRegusController::class, 'activate'])->name('regu.activate');
    Route::post('regu/deactivate/{id}', [MstRegusController::class, 'deactivate'])->name('regu.deactivate');

    //Operator
    Route::get('/operator/{id}', [MstOperatorsController::class, 'index'])->name('operator.index');
    Route::post('/operator/{id}', [MstOperatorsController::class, 'index'])->name('operator.index');
    Route::post('operator/create/{id}', [MstOperatorsController::class, 'store'])->name('operator.store');
    Route::post('operator/delete/{id}', [MstOperatorsController::class, 'delete'])->name('operator.delete');

    //Waste
    Route::get('/waste', [MstWastesController::class, 'index'])->name('waste.index');
    Route::post('/waste', [MstWastesController::class, 'index'])->name('waste.index');
    Route::post('waste/create', [MstWastesController::class, 'store'])->name('waste.store');
    Route::post('waste/update/{id}', [MstWastesController::class, 'update'])->name('waste.update');
    Route::post('waste/activate/{id}', [MstWastesController::class, 'activate'])->name('waste.activate');
    Route::post('waste/deactivate/{id}', [MstWastesController::class, 'deactivate'])->name('waste.deactivate');

    //Downtime
    Route::get('/downtime', [MstDowntimesController::class, 'index'])->name('downtime.index');
    Route::post('/downtime', [MstDowntimesController::class, 'index'])->name('downtime.index');
    Route::post('downtime/create', [MstDowntimesController::class, 'store'])->name('downtime.store');
    Route::post('downtime/update/{id}', [MstDowntimesController::class, 'update'])->name('downtime.update');
    Route::post('downtime/activate/{id}', [MstDowntimesController::class, 'activate'])->name('downtime.activate');
    Route::post('downtime/deactivate/{id}', [MstDowntimesController::class, 'deactivate'])->name('downtime.deactivate');

    //Warehouse
    Route::get('/warehouse', [MstWarehousesController::class, 'index'])->name('warehouse.index');
    Route::post('/warehouse', [MstWarehousesController::class, 'index'])->name('warehouse.index');
    Route::post('warehouse/create', [MstWarehousesController::class, 'store'])->name('warehouse.store');
    Route::post('warehouse/update/{id}', [MstWarehousesController::class, 'update'])->name('warehouse.update');
    Route::post('warehouse/activate/{id}', [MstWarehousesController::class, 'activate'])->name('warehouse.activate');
    Route::post('warehouse/deactivate/{id}', [MstWarehousesController::class, 'deactivate'])->name('warehouse.deactivate');

    //Vehicle
    Route::get('/vehicle', [MstVehiclesController::class, 'index'])->name('vehicle.index');
    Route::post('/vehicle', [MstVehiclesController::class, 'index'])->name('vehicle.index');
    Route::post('vehicle/create', [MstVehiclesController::class, 'store'])->name('vehicle.store');
    Route::post('vehicle/update/{id}', [MstVehiclesController::class, 'update'])->name('vehicle.update');
    Route::post('vehicle/activate/{id}', [MstVehiclesController::class, 'activate'])->name('vehicle.activate');
    Route::post('vehicle/deactivate/{id}', [MstVehiclesController::class, 'deactivate'])->name('vehicle.deactivate');

    //Reason
    Route::get('/reason', [MstReasonsController::class, 'index'])->name('reason.index');
    Route::post('/reason', [MstReasonsController::class, 'index'])->name('reason.index');
    Route::post('reason/create', [MstReasonsController::class, 'store'])->name('reason.store');
    Route::post('reason/update/{id}', [MstReasonsController::class, 'update'])->name('reason.update');
    Route::post('reason/activate/{id}', [MstReasonsController::class, 'activate'])->name('reason.activate');
    Route::post('reason/deactivate/{id}', [MstReasonsController::class, 'deactivate'])->name('reason.deactivate');

    //Approval
    Route::get('/approval', [MstApprovalsController::class, 'index'])->name('approval.index');
    Route::post('/approval', [MstApprovalsController::class, 'index'])->name('approval.index');
    Route::post('approval/create', [MstApprovalsController::class, 'store'])->name('approval.store');
    Route::post('approval/update/{id}', [MstApprovalsController::class, 'update'])->name('approval.update');
    Route::post('approval/activate/{id}', [MstApprovalsController::class, 'activate'])->name('approval.activate');
    Route::post('approval/deactivate/{id}', [MstApprovalsController::class, 'deactivate'])->name('approval.deactivate');

    //Employee
    Route::get('/employee', [MstEmployeesController::class, 'index'])->name('employee.index');
    Route::post('/employee', [MstEmployeesController::class, 'index'])->name('employee.index');
    Route::post('employee/create', [MstEmployeesController::class, 'store'])->name('employee.store');
    Route::post('employee/update/{id}', [MstEmployeesController::class, 'update'])->name('employee.update');
    Route::post('employee/activate/{id}', [MstEmployeesController::class, 'activate'])->name('employee.activate');
    Route::post('employee/deactivate/{id}', [MstEmployeesController::class, 'deactivate'])->name('employee.deactivate');
    
    //Customer
    Route::get('/customer', [MstCustomersController::class, 'index'])->name('customer.index');
    Route::post('/customer', [MstCustomersController::class, 'index'])->name('customer.index');
    Route::post('customer/create', [MstCustomersController::class, 'store'])->name('customer.store');
    Route::post('customer/update/{id}', [MstCustomersController::class, 'update'])->name('customer.update');
    Route::post('customer/activate/{id}', [MstCustomersController::class, 'activate'])->name('customer.activate');
    Route::post('customer/deactivate/{id}', [MstCustomersController::class, 'deactivate'])->name('customer.deactivate');
    
    //CustomerAddress
    Route::get('/customeraddress/{id}', [MstCustomerAddressController::class, 'index'])->name('customeraddress.index');
    Route::post('/customeraddress/{id}', [MstCustomerAddressController::class, 'index'])->name('customeraddress.index');
    Route::post('customeraddress/create/{id}', [MstCustomerAddressController::class, 'store'])->name('customeraddress.store');
    Route::post('customeraddress/update/{id}', [MstCustomerAddressController::class, 'update'])->name('customeraddress.update');
    Route::post('customeraddress/activate/{id}', [MstCustomerAddressController::class, 'activate'])->name('customeraddress.activate');
    Route::post('customeraddress/deactivate/{id}', [MstCustomerAddressController::class, 'deactivate'])->name('customeraddress.deactivate');
    
    //Supplier
    Route::get('/supplier', [MstSuppliersController::class, 'index'])->name('supplier.index');
    Route::post('/supplier', [MstSuppliersController::class, 'index'])->name('supplier.index');
    Route::post('supplier/create', [MstSuppliersController::class, 'store'])->name('supplier.store');
    Route::post('supplier/update/{id}', [MstSuppliersController::class, 'update'])->name('supplier.update');
    Route::post('supplier/activate/{id}', [MstSuppliersController::class, 'activate'])->name('supplier.activate');
    Route::post('supplier/deactivate/{id}', [MstSuppliersController::class, 'deactivate'])->name('supplier.deactivate');
    
    //Supplier
    Route::get('/sparepart', [MstSparepartsController::class, 'index'])->name('sparepart.index');
    Route::post('/sparepart', [MstSparepartsController::class, 'index'])->name('sparepart.index');
    Route::post('sparepart/create', [MstSparepartsController::class, 'store'])->name('sparepart.store');
    Route::post('sparepart/update/{id}', [MstSparepartsController::class, 'update'])->name('sparepart.update');
    Route::post('sparepart/activate/{id}', [MstSparepartsController::class, 'activate'])->name('sparepart.activate');
    Route::post('sparepart/deactivate/{id}', [MstSparepartsController::class, 'deactivate'])->name('sparepart.deactivate');
    
    //RawMaterial
    Route::get('/rawmaterial', [MstRawMaterialsController::class, 'index'])->name('rawmaterial.index');
    Route::post('/rawmaterial', [MstRawMaterialsController::class, 'index'])->name('rawmaterial.index');
    Route::post('rawmaterial/create', [MstRawMaterialsController::class, 'store'])->name('rawmaterial.store');
    Route::post('rawmaterial/update/{id}', [MstRawMaterialsController::class, 'update'])->name('rawmaterial.update');
    Route::post('rawmaterial/activate/{id}', [MstRawMaterialsController::class, 'activate'])->name('rawmaterial.activate');
    Route::post('rawmaterial/deactivate/{id}', [MstRawMaterialsController::class, 'deactivate'])->name('rawmaterial.deactivate');
    
    //Wip
    Route::get('/wip', [MstWipsController::class, 'index'])->name('wip.index');
    Route::post('/wip', [MstWipsController::class, 'index'])->name('wip.index');
    Route::get('/wip/export', [MstWipsController::class, 'export'])->name('wip.export');
    Route::post('wip/create', [MstWipsController::class, 'store'])->name('wip.store');
    Route::post('wip/update/{id}', [MstWipsController::class, 'update'])->name('wip.update');
    Route::post('wip/activate/{id}', [MstWipsController::class, 'activate'])->name('wip.activate');
    Route::post('wip/deactivate/{id}', [MstWipsController::class, 'deactivate'])->name('wip.deactivate');

    //WipRef
    Route::get('/wipref/{id}', [MstWipRefsController::class, 'index'])->name('wipref.index');
    Route::post('/wipref/{id}', [MstWipRefsController::class, 'index'])->name('wipref.index');
    Route::post('wipref/create/{id}', [MstWipRefsController::class, 'store'])->name('wipref.store');
    Route::post('wipref/update/{id}', [MstWipRefsController::class, 'update'])->name('wipref.update');
    Route::post('wipref/delete/{id}', [MstWipRefsController::class, 'delete'])->name('wipref.delete');

    //WipRefWip
    Route::get('/wiprefwip/{id}', [MstWipRefWipsController::class, 'index'])->name('wiprefwip.index');
    Route::post('/wiprefwip/{id}', [MstWipRefWipsController::class, 'index'])->name('wiprefwip.index');
    Route::post('wiprefwip/create/{id}', [MstWipRefWipsController::class, 'store'])->name('wiprefwip.store');
    Route::post('wiprefwip/update/{id}', [MstWipRefWipsController::class, 'update'])->name('wiprefwip.update');
    Route::post('wiprefwip/delete/{id}', [MstWipRefWipsController::class, 'delete'])->name('wiprefwip.delete');
    
    //Product FG
    Route::get('/fg', [MstFGsController::class, 'index'])->name('fg.index');
    Route::post('/fg', [MstFGsController::class, 'index'])->name('fg.index');
    Route::post('fg/create', [MstFGsController::class, 'store'])->name('fg.store');
    Route::post('fg/update/{id}', [MstFGsController::class, 'update'])->name('fg.update');
    Route::post('fg/activate/{id}', [MstFGsController::class, 'activate'])->name('fg.activate');
    Route::post('fg/deactivate/{id}', [MstFGsController::class, 'deactivate'])->name('fg.deactivate');
    
    //Product FG Ref
    Route::get('/fgref/{id}', [MstFGRefsController::class, 'index'])->name('fgref.index');
    Route::post('/fgref/{id}', [MstFGRefsController::class, 'index'])->name('fgref.index');
    Route::post('fgref/create/{id}', [MstFGRefsController::class, 'store'])->name('fgref.store');
    Route::post('fgref/update/{id}', [MstFGRefsController::class, 'update'])->name('fgref.update');
    Route::post('fgref/delete/{id}', [MstFGRefsController::class, 'delete'])->name('fgref.delete');

    //Dropdown
    Route::get('/dropdown', [MstDropdownsController::class, 'index'])->name('dropdown.index');
    Route::post('/dropdown', [MstDropdownsController::class, 'index'])->name('dropdown.index');
    Route::post('dropdown/create', [MstDropdownsController::class, 'store'])->name('dropdown.store');
    Route::post('dropdown/update/{id}', [MstDropdownsController::class, 'update'])->name('dropdown.update');
    Route::post('dropdown/delete/{id}', [MstDropdownsController::class, 'delete'])->name('dropdown.delete');

    //Audit Log
    Route::get('/auditlog', [AuditLogController::class, 'index'])->name('auditlog');
});

