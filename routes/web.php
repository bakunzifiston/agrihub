<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgribusinessDashboardController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\TenantAuthController;
use App\Http\Controllers\CooperativeDashboardController;
use App\Http\Controllers\FarmerDashboardController;
use App\Http\Controllers\Farmer\ClientController as FarmerClientController;
use App\Http\Controllers\Farmer\EmployeeController as FarmerEmployeeController;
use App\Http\Controllers\Farmer\PreOrderListingController as FarmerPreOrderListingController;
use App\Http\Controllers\Farmer\PreOrderController as FarmerPreOrderController;
use App\Http\Controllers\Farmer\CropController;
use App\Http\Controllers\Farmer\FarmInputApplicationController;
use App\Http\Controllers\Farmer\FarmInputController;
use App\Http\Controllers\Farmer\FarmOutputController;
use App\Http\Controllers\Farmer\FarmProfileController;
use App\Http\Controllers\Farmer\FarmSaleController;
use App\Http\Controllers\Farmer\LivestockController;
use App\Http\Controllers\Farmer\ProductionRecordController;
use App\Http\Controllers\Farmer\RegisteredCropController;
use App\Http\Controllers\Farmer\ReportController;
use App\Http\Controllers\Cooperative\ClientController;
use App\Http\Controllers\Cooperative\CollectionController;
use App\Http\Controllers\Cooperative\CooperativeProfileController;
use App\Http\Controllers\Cooperative\CropController as CooperativeCropController;
use App\Http\Controllers\Cooperative\InventoryController as CooperativeInventoryController;
use App\Http\Controllers\Cooperative\LivestockController as CooperativeLivestockController;
use App\Http\Controllers\Cooperative\MemberController;
use App\Http\Controllers\Cooperative\OrderController as CooperativeOrderController;
use App\Http\Controllers\Cooperative\PaymentController;
use App\Http\Controllers\Cooperative\PerformanceController;
use App\Http\Controllers\Cooperative\WarehouseController as CooperativeWarehouseController;
use App\Http\Controllers\Agribusiness\ContractController;
use App\Http\Controllers\Agribusiness\CustomerController as AgribusinessCustomerController;
use App\Http\Controllers\Agribusiness\EmployeeController as AgribusinessEmployeeController;
use App\Http\Controllers\Agribusiness\DistributionController;
use App\Http\Controllers\Agribusiness\InventoryController as AgribusinessInventoryController;
use App\Http\Controllers\Agribusiness\ProcessingController;
use App\Http\Controllers\Agribusiness\ReportController as AgribusinessReportController;
use App\Http\Controllers\Agribusiness\SupplierController;
use App\Http\Controllers\Agribusiness\WarehouseController as AgribusinessWarehouseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Tenant\TenantPermissionController;
use App\Http\Controllers\Tenant\TenantRoleController;
use App\Http\Controllers\Tenant\TenantUserController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
})->name('home');

// DB-aware health check for load balancers/monitoring (no auth). Use /up for framework default.
Route::get('/health', function () {
    try {
        DB::connection()->getPdo();
        return response()->json(['status' => 'ok'], 200);
    } catch (\Throwable $e) {
        return response()->json(['status' => 'unhealthy', 'message' => 'Database unreachable'], 503);
    }
})->name('health');

Route::get('/dashboard', function () {
    $user = auth()->user();
    if (! $user) {
        return redirect()->route('home');
    }
    if ($user->isSuperAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route("{$user->tenant_type}.dashboard");
})->middleware(['auth', 'verified', 'tenant.approved'])->name('dashboard');

Route::get('/approval/pending', function () {
    return view('approval.pending');
})->middleware(['auth'])->name('approval.pending');

// Tenant-specific dashboards (users can ONLY access their own)
Route::middleware(['auth', 'verified', 'tenant.approved', 'tenant.type:farmer'])->prefix('farmer')->name('farmer.')->group(function () {
    Route::get('/dashboard', [FarmerDashboardController::class, 'index'])->name('dashboard');
    Route::resource('farm-profile', FarmProfileController::class)->parameters(['farm-profile' => 'farmProfile']);
    Route::resource('registered-crops', RegisteredCropController::class)->except(['show'])->parameters(['registered-crops' => 'registeredCrop']);
    Route::resource('crops', CropController::class)->except(['show']);
    Route::resource('livestock', LivestockController::class)->except(['show']);
    Route::resource('production-records', ProductionRecordController::class)->except(['show']);
    Route::resource('inputs', FarmInputController::class)->except(['show'])->parameters(['input' => 'farmInput']);
    Route::resource('input-applications', FarmInputApplicationController::class)->except(['show'])->parameters(['input-application' => 'farmInputApplication']);
    Route::resource('outputs', FarmOutputController::class)->except(['show'])->parameters(['output' => 'farmOutput']);
    Route::resource('clients', FarmerClientController::class)->except(['show'])->parameters(['clients' => 'client']);
    Route::resource('employees', FarmerEmployeeController::class)->except(['show'])->parameters(['employees' => 'employee']);
    Route::resource('sales', FarmSaleController::class)->except(['show'])->parameters(['sale' => 'farmSale']);
    Route::resource('pre-order-listings', FarmerPreOrderListingController::class)->except(['show'])->parameters(['pre-order-listings' => 'preOrderListing']);
    Route::get('pre-orders', [FarmerPreOrderController::class, 'index'])->name('pre-orders.index');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/users', [TenantUserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [TenantUserController::class, 'create'])->name('users.create');
    Route::post('/users', [TenantUserController::class, 'store'])->name('users.store');
    Route::get('/users/{editUser}/edit', [TenantUserController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{editUser}', [TenantUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{editUser}', [TenantUserController::class, 'destroy'])->name('users.destroy');
    Route::get('/roles', [TenantRoleController::class, 'index'])->name('roles.index');
    Route::get('/permissions', [TenantPermissionController::class, 'index'])->name('permissions.index');
});

Route::middleware(['auth', 'verified', 'tenant.approved', 'tenant.type:cooperative'])->prefix('cooperative')->name('cooperative.')->group(function () {
    Route::get('/dashboard', [CooperativeDashboardController::class, 'index'])->name('dashboard');
    Route::resource('cooperative-profile', CooperativeProfileController::class)->except(['show'])->parameters(['cooperative-profile' => 'cooperativeProfile']);
    Route::resource('members', MemberController::class)->except(['show'])->parameters(['members' => 'member']);
    Route::resource('collections', CollectionController::class)->except(['show'])->parameters(['collections' => 'collection']);
    Route::resource('crops', CooperativeCropController::class)->except(['show'])->parameters(['crops' => 'crop']);
    Route::resource('livestock', CooperativeLivestockController::class)->except(['show'])->parameters(['livestock' => 'livestock']);
    Route::resource('clients', ClientController::class)->except(['show'])->parameters(['clients' => 'client']);
    Route::resource('orders', CooperativeOrderController::class)->except(['show'])->parameters(['orders' => 'order']);
    Route::resource('warehouses', CooperativeWarehouseController::class)->except(['show'])->parameters(['warehouses' => 'warehouse']);
    Route::resource('inventory', CooperativeInventoryController::class)->except(['show']);
    Route::resource('payments', PaymentController::class)->except(['show']);
    Route::get('/performance', [PerformanceController::class, 'index'])->name('performance.index');
    Route::get('/users', [TenantUserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [TenantUserController::class, 'create'])->name('users.create');
    Route::post('/users', [TenantUserController::class, 'store'])->name('users.store');
    Route::get('/users/{editUser}/edit', [TenantUserController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{editUser}', [TenantUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{editUser}', [TenantUserController::class, 'destroy'])->name('users.destroy');
    Route::get('/roles', [TenantRoleController::class, 'index'])->name('roles.index');
    Route::get('/permissions', [TenantPermissionController::class, 'index'])->name('permissions.index');
});

Route::middleware(['auth', 'verified', 'tenant.approved', 'tenant.type:agribusiness'])->prefix('agribusiness')->name('agribusiness.')->group(function () {
    Route::get('/dashboard', [AgribusinessDashboardController::class, 'index'])->name('dashboard');
    Route::resource('suppliers', SupplierController::class)->except(['show']);
    Route::resource('contracts', ContractController::class)->except(['show']);
    Route::resource('processing', ProcessingController::class)->except(['show']);
    Route::resource('warehouses', AgribusinessWarehouseController::class)->except(['show'])->parameters(['warehouses' => 'warehouse']);
    Route::resource('inventory', AgribusinessInventoryController::class)->except(['show']);
    Route::resource('customers', AgribusinessCustomerController::class)->except(['show'])->parameters(['customers' => 'customer']);
    Route::resource('distributions', DistributionController::class)->except(['show']);
    Route::resource('employees', AgribusinessEmployeeController::class)->except(['show'])->parameters(['employees' => 'employee']);
    Route::get('/reports', [AgribusinessReportController::class, 'index'])->name('reports.index');
    Route::get('/users', [TenantUserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [TenantUserController::class, 'create'])->name('users.create');
    Route::post('/users', [TenantUserController::class, 'store'])->name('users.store');
    Route::get('/users/{editUser}/edit', [TenantUserController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{editUser}', [TenantUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{editUser}', [TenantUserController::class, 'destroy'])->name('users.destroy');
    Route::get('/roles', [TenantRoleController::class, 'index'])->name('roles.index');
    Route::get('/permissions', [TenantPermissionController::class, 'index'])->name('permissions.index');
});

// Tenant auth routes (Farmer, Cooperative, Agribusiness)
Route::middleware('guest')->group(function () {
    Route::get('/farmer/login', [TenantAuthController::class, 'showLoginForm'])->defaults('tenantType', 'farmer')->name('farmer.login');
    Route::post('/farmer/login', [TenantAuthController::class, 'login'])->defaults('tenantType', 'farmer')->name('farmer.login.post');

    Route::get('/cooperative/login', [TenantAuthController::class, 'showLoginForm'])->defaults('tenantType', 'cooperative')->name('cooperative.login');
    Route::post('/cooperative/login', [TenantAuthController::class, 'login'])->defaults('tenantType', 'cooperative')->name('cooperative.login.post');

    Route::get('/agribusiness/login', [TenantAuthController::class, 'showLoginForm'])->defaults('tenantType', 'agribusiness')->name('agribusiness.login');
    Route::post('/agribusiness/login', [TenantAuthController::class, 'login'])->defaults('tenantType', 'agribusiness')->name('agribusiness.login.post');
});

// Tenant registration - accessible when logged in (to create another tenant account)
Route::get('/farmer/register', [TenantAuthController::class, 'showRegisterForm'])->defaults('tenantType', 'farmer')->name('farmer.register');
Route::post('/farmer/register', [TenantAuthController::class, 'register'])->defaults('tenantType', 'farmer')->name('farmer.register.post');

Route::get('/cooperative/register', [TenantAuthController::class, 'showRegisterForm'])->defaults('tenantType', 'cooperative')->name('cooperative.register');
Route::post('/cooperative/register', [TenantAuthController::class, 'register'])->defaults('tenantType', 'cooperative')->name('cooperative.register.post');

Route::get('/agribusiness/register', [TenantAuthController::class, 'showRegisterForm'])->defaults('tenantType', 'agribusiness')->name('agribusiness.register');
Route::post('/agribusiness/register', [TenantAuthController::class, 'register'])->defaults('tenantType', 'agribusiness')->name('agribusiness.register.post');

// Super Admin auth (login only)
Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
});

// Super Admin dashboard (approve tenants, feature toggles)
Route::middleware(['auth', 'super.admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/feature-toggles', [AdminController::class, 'featureToggles'])->name('feature-toggles');
    Route::post('/feature-toggles', [AdminController::class, 'updateFeatureToggle'])->name('feature-toggles.update');
    Route::get('/tenants/{user}/features', [AdminController::class, 'tenantFeatures'])->name('tenants.features');
    Route::post('/tenants/{user}/features', [AdminController::class, 'updateTenantFeature'])->name('tenants.features.update');
    Route::post('/tenants/{user}/approve', [AdminController::class, 'approve'])->name('tenants.approve');
    Route::post('/tenants/{user}/reject', [AdminController::class, 'reject'])->name('tenants.reject');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
