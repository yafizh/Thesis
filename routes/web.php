<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProposalResearchController;
use App\Http\Controllers\ProposalStudyController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReportResearchController;
use App\Http\Controllers\ReportStudyController;
use App\Http\Controllers\ResearchController;
use App\Http\Controllers\StudyController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [DashboardController::class, 'index'])->middleware('auth');
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth');
Route::resource('/employees', EmployeeController::class)->middleware('auth');
Route::resource('/users', UserController::class)->middleware('auth');
Route::resource('/guests', GuestController::class)->middleware('auth');

Route::put('proposal-research/approve/{proposal_research}', [ProposalResearchController::class, 'approve']);
Route::resource('proposal-research', ProposalResearchController::class);

Route::resource('research', ResearchController::class)->only(['index', 'show'])->middleware('auth');

Route::put('report-research/approve/{report_research}', [ReportResearchController::class, 'approve']);
Route::resource('report-research', ReportResearchController::class)->middleware('auth');

Route::put('proposal-study/approve/{proposal_study}', [ProposalStudyController::class, 'approve']);
Route::resource('/proposal-study', ProposalStudyController::class)->middleware('auth');

Route::resource('study', StudyController::class)->only(['index', 'show'])->middleware('auth');

Route::put('report-study/approve/{report_study}', [ReportStudyController::class, 'approve']);
Route::resource('/report-study', ReportStudyController::class)->middleware('auth');

// Report
Route::get('/report/employee', [ReportController::class, 'employee']);
Route::post('/report/employee', [ReportController::class, 'employee_report']);
Route::get('/report/proposal-research', [ReportController::class, 'proposal_research']);
Route::post('/report/proposal-research', [ReportController::class, 'proposal_research_report']);

// Authentication
Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::get('/logout', [LoginController::class, 'logout']);
