<?php
use App\Http\Middleware\CheckUserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\Expenses;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware('auth:sanctum')->group(function () {

    Route::post('/roles', [RoleController::class, 'store']);
    Route::get('/roles', [RoleController::class, 'getAllRoles']);
    Route::get('/roles/{id}', [RoleController::class, 'getRole']);
    Route::put('/roles/{id}', [RoleController::class, 'updateRole']);
    Route::delete('/roles/{id}', [RoleController::class, 'deleteRole']);
    //USERS

    Route::get('/auth-user', [UserController::class, 'authenticatedUser']);
    Route::post('/password/change', [UserController::class, 'changePassword']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users', [UserController::class, 'getAllUser']);
    Route::get('/users/{id}', [UserController::class, 'getUser']);
    Route::put('/users/{id}', [UserController::class, 'updateUser']);
    Route::delete('/users/{id}', [UserController::class, 'deleteUser']);
    //EXPENSE CATEGORY
    Route::post('/expense-category', [ExpenseController::class, 'store']);
    Route::get('/expense-category', [ExpenseController::class, 'getAllExpenseC']);
    Route::get('/expense-category/{id}', [ExpenseController::class, 'getExpenseC']);
    Route::put('/expense-category/{id}', [ExpenseController::class, 'updateExpenseC']);
    Route::delete('/expense-category/{id}', [ExpenseController::class, 'deleteExpenseC']);
    //EXPENSES
    Route::post('/expenses', [Expenses::class, 'store']);
    Route::get('/expenses', [Expenses::class, 'getAllExpenses']);
    Route::get('/expenses/{id}', [Expenses::class, 'getExpenses']);
    Route::put('/expenses/{id}', [Expenses::class, 'updateExpenses']);
    Route::delete('/expenses/{id}', [Expenses::class, 'deleteExpenses']);
});