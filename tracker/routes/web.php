<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExpenseController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('incomes.index');
    });
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/incomes/export', [IncomeController::class, 'export'])->name('incomes.export');
    Route::resource('incomes', IncomeController::class);
    Route::resource('expenses', ExpenseController::class);
    Route::resource('budgets', App\Http\Controllers\BudgetController::class);
    Route::post('/incomes/{income}/duplicate', [IncomeController::class, 'duplicate'])->name('incomes.duplicate');
    Route::post('/expenses/{expense}/duplicate', [ExpenseController::class, 'duplicate'])->name('expenses.duplicate');
    
    // Money Requests
    Route::get('/requests', [App\Http\Controllers\MoneyRequestController::class, 'index'])->name('requests.index');
    Route::get('/requests/create', [App\Http\Controllers\MoneyRequestController::class, 'create'])->name('requests.create');
    Route::post('/requests', [App\Http\Controllers\MoneyRequestController::class, 'store'])->name('requests.store');
    Route::patch('/requests/{moneyRequest}/status', [App\Http\Controllers\MoneyRequestController::class, 'updateStatus'])->name('requests.update-status');
    Route::post('/requests/{moneyRequest}/proof', [App\Http\Controllers\MoneyRequestController::class, 'uploadProof'])->name('requests.upload-proof');
    
    // Mark notifications read
    Route::post('/notifications/mark-read', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.read');

    // Household
    Route::get('/household', [App\Http\Controllers\HouseholdController::class, 'index'])->name('household.index');
    Route::post('/household/link', [App\Http\Controllers\HouseholdController::class, 'link'])->name('household.link');
    Route::post('/household/leave', [App\Http\Controllers\HouseholdController::class, 'leave'])->name('household.leave');

    // SMS Parser
    Route::get('/sms-parser', [App\Http\Controllers\SMSParserController::class, 'index'])->name('sms-parser.index');
    Route::post('/sms-parser/parse', [App\Http\Controllers\SMSParserController::class, 'parse'])->name('sms-parser.parse');

    // Savings Goals
    Route::get('/savings', [App\Http\Controllers\SavingsGoalController::class, 'index'])->name('savings.index');
    Route::post('/savings', [App\Http\Controllers\SavingsGoalController::class, 'store'])->name('savings.store');
    Route::post('/savings/{savingsGoal}/contribute', [App\Http\Controllers\SavingsGoalController::class, 'contribute'])->name('savings.contribute');
    // Savings Goals
    Route::get('/savings', [App\Http\Controllers\SavingsGoalController::class, 'index'])->name('savings.index');
    Route::post('/savings', [App\Http\Controllers\SavingsGoalController::class, 'store'])->name('savings.store');
    Route::post('/savings/{savingsGoal}/contribute', [App\Http\Controllers\SavingsGoalController::class, 'contribute'])->name('savings.contribute');
    Route::delete('/savings/{savingsGoal}', [App\Http\Controllers\SavingsGoalController::class, 'destroy'])->name('savings.destroy');

    // Calendar
    Route::get('/calendar', [App\Http\Controllers\CalendarController::class, 'index'])->name('calendar.index');

    // Assets, Subscriptions, Bills, Loans
    Route::resource('assets', App\Http\Controllers\AssetController::class);
    Route::resource('subscriptions', App\Http\Controllers\SubscriptionController::class);
    Route::resource('bills', App\Http\Controllers\BillController::class);
    Route::resource('loans', App\Http\Controllers\LoanController::class);

    // Accounts
    Route::get('/accounts', [App\Http\Controllers\AccountController::class, 'index'])->name('accounts.index');
});
