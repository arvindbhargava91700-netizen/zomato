<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Hrms\AuthController;
use App\Http\Controllers\Api\Hrms\AttendanceController;
use App\Http\Controllers\Api\Hrms\SalaryController;
use App\Http\Controllers\Api\Hrms\LeaveController;
use App\Http\Controllers\Api\Hrms\TaskController;
use App\Http\Controllers\Api\Hrms\NoticeController;
use App\Http\Controllers\Api\Hrms\ExpenseController;
use App\Http\Controllers\Api\Hrms\StaffController;
use App\Http\Controllers\Api\Hrms\OrganizationController;
use App\Http\Controllers\Api\Hrms\ProductController;
use App\Http\Controllers\Api\Hrms\CrmController;
use App\Http\Controllers\Api\Hrms\CommissionController;
use App\Http\Controllers\Api\Hrms\AdvancePaymentController;
use App\Http\Controllers\Api\Hrms\DashboardController;

// HRMS API Routes
// These routes are prefixed with /api/hrms automatically by routes/api.php

Route::middleware('force.json')->group(function () {
    // Auth (Public)
    Route::post('/login', [AuthController::class, 'login']);

    // Protected Routes (JWT)
    Route::middleware('api.auth:hrms_api')->group(function () {
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::match(['put', 'post'], '/profile', [AuthController::class, 'updateProfile']);
        Route::post('/logout', [AuthController::class, 'logout']);
        // account Delete
        Route::delete('/account/destroy', [AuthController::class, 'destroyAccount']);

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index']);

        // Attendance
        Route::get('/attendance/checklists', [AttendanceController::class, 'checklists']);
        Route::post('/attendance/submit-checklist', [AttendanceController::class, 'submitChecklist']);
        Route::post('/attendance/punch-in', [AttendanceController::class, 'punchIn']);
        Route::post('/attendance/punch-out', [AttendanceController::class, 'punchOut']);
        Route::get('/attendance/today', [AttendanceController::class, 'today']);
        Route::get('/attendance/history', [AttendanceController::class, 'history']);
        Route::get('/attendance/settings', [AttendanceController::class, 'settings']);
        Route::get('/attendance/{id}', [AttendanceController::class, 'show']);
        
        // Team Attendance
        Route::get('/team-attendance/employees', [AttendanceController::class, 'teamEmployees']);
        Route::get('/team-attendance/today', [AttendanceController::class, 'teamToday']);
        Route::get('/team-attendance/history', [AttendanceController::class, 'teamHistory']);

        // Salary Management
        Route::get('/employees/{id}/salary-structure', [SalaryController::class, 'getSalaryStructure']);
        Route::post('/employees/{id}/salary-structure', [SalaryController::class, 'updateSalaryStructure']);

        // Leaves
        Route::get('/leaves/categories', [LeaveController::class, 'categories']);
        Route::get('/leaves', [LeaveController::class, 'index']);
        Route::post('/leaves/apply', [LeaveController::class, 'apply']);
        Route::get('/team-leaves', [LeaveController::class, 'teamLeaves']);
        Route::post('/team-leaves/{id}/status', [LeaveController::class, 'updateStatus']);

        // Tasks
        Route::get('/tasks', [TaskController::class, 'index']);
        Route::put('/tasks/{id}', [TaskController::class, 'update']);
        Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);
        Route::post('/tasks/{id}/status', [TaskController::class, 'updateStatus']);
        Route::get('/team-tasks', [TaskController::class, 'teamTasks']);
        Route::post('/team-tasks', [TaskController::class, 'store']);

        // Notices
        Route::get('/notices', [NoticeController::class, 'index']);
        Route::post('/notices', [NoticeController::class, 'store']);
        Route::get('/notices/{id}', [NoticeController::class, 'show']);
        Route::put('/notices/{id}', [NoticeController::class, 'update']);
        Route::delete('/notices/{id}', [NoticeController::class, 'destroy']);

        // Expenses
        Route::get('/expenses', [ExpenseController::class, 'index']);
        Route::post('/expenses', [ExpenseController::class, 'store']);
        Route::put('/expenses/{id}', [ExpenseController::class, 'update']);
        Route::delete('/expenses/{id}', [ExpenseController::class, 'destroy']);
        Route::get('/team-expenses', [ExpenseController::class, 'teamExpenses']);
        Route::post('/team-expenses/{id}/status', [ExpenseController::class, 'updateStatus']);

        // Staff & Organization
        Route::get('/staff', [StaffController::class, 'index']);
        Route::get('/staff/{id}', [StaffController::class, 'show']);
        Route::post('/staff', [StaffController::class, 'store']);
        Route::put('/staff/{id}', [StaffController::class, 'update']);
        Route::delete('/staff/{id}', [StaffController::class, 'destroy']);

        Route::get('/permissions', [OrganizationController::class, 'getPermissions']);
        Route::get('/roles', [OrganizationController::class, 'getRoles']);
        Route::get('/roles/{id}', [OrganizationController::class, 'getRole']);
        Route::post('/roles', [OrganizationController::class, 'createRole']);
        Route::put('/roles/{id}', [OrganizationController::class, 'updateRole']);
        Route::delete('/roles/{id}', [OrganizationController::class, 'deleteRole']);

        Route::get('/departments', [OrganizationController::class, 'getDepartments']);
        Route::post('/departments', [OrganizationController::class, 'createDepartment']);
        Route::put('/departments/{id}', [OrganizationController::class, 'updateDepartment']);
        Route::delete('/departments/{id}', [OrganizationController::class, 'deleteDepartment']);

        Route::get('/branches', [OrganizationController::class, 'getBranches']);
        Route::post('/branches', [OrganizationController::class, 'createBranch']);
        Route::put('/branches/{id}', [OrganizationController::class, 'updateBranch']);
        Route::delete('/branches/{id}', [OrganizationController::class, 'deleteBranch']);

        Route::get('/work-shifts', [OrganizationController::class, 'getWorkShifts']);
        Route::post('/work-shifts', [OrganizationController::class, 'createWorkShift']);
        Route::put('/work-shifts/{id}', [OrganizationController::class, 'updateWorkShift']);
        Route::delete('/work-shifts/{id}', [OrganizationController::class, 'deleteWorkShift']);

        // Products
        Route::get('/product-categories', [ProductController::class, 'getCategories']);
        Route::post('/product-categories', [ProductController::class, 'createCategory']);
        Route::put('/product-categories/{id}', [ProductController::class, 'updateCategory']);
        Route::delete('/product-categories/{id}', [ProductController::class, 'deleteCategory']);
        
        Route::get('/products', [ProductController::class, 'getProducts']);
        Route::post('/products', [ProductController::class, 'createProduct']);
        Route::put('/products/{id}', [ProductController::class, 'updateProduct']);
        Route::delete('/products/{id}', [ProductController::class, 'deleteProduct']);

        // CRM & Sales
        Route::get('/leads', [CrmController::class, 'getLeads']);
        Route::post('/leads', [CrmController::class, 'createLead']);
        Route::put('/leads/{id}', [CrmController::class, 'updateLead']);
        Route::delete('/leads/{id}', [CrmController::class, 'deleteLead']);
        Route::put('/leads/{id}/status', [CrmController::class, 'updateLeadStatus']);

        Route::get('/customer-visits', [CrmController::class, 'getCustomerVisits']);
        Route::post('/customer-visits', [CrmController::class, 'createCustomerVisit']);
        Route::put('/customer-visits/{id}', [CrmController::class, 'updateCustomerVisit']);
        Route::delete('/customer-visits/{id}', [CrmController::class, 'deleteCustomerVisit']);

        Route::get('/lead-orders', [CrmController::class, 'getOrders']);
        Route::post('/lead-orders', [CrmController::class, 'createOrder']);
        Route::put('/lead-orders/{id}', [CrmController::class, 'updateOrder']);
        Route::delete('/lead-orders/{id}', [CrmController::class, 'deleteOrder']);

        // Targets & Commissions
        Route::get('/my-targets', [CommissionController::class, 'getTargets']);
        Route::get('/commissions/history', [CommissionController::class, 'getHistory']);

        // Payroll & Advances
        Route::get('/payroll/my', [SalaryController::class, 'getMyPayroll']);
        Route::get('/advance-payments', [AdvancePaymentController::class, 'index']);
        Route::post('/advance-payments', [AdvancePaymentController::class, 'store']);

    });
});
