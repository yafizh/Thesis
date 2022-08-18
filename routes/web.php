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
use App\Models\Research;
use App\Models\Study;
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
Route::resource('/users', UserController::class)->except('show')->middleware('auth');


Route::put('/employees/{employee}/manage-password', [EmployeeController::class, 'manage_password'])->middleware('auth');
Route::post('/employees/report', [EmployeeController::class, 'report'])->middleware('auth');
Route::resource('/employees', EmployeeController::class)->middleware('auth');

Route::post('/guests/report', [GuestController::class, 'report']);
Route::resource('/guests', GuestController::class)->middleware('auth');

Route::post('/proposal-research/report', [ProposalResearchController::class, 'report'])->middleware('auth');
Route::put('proposal-research/approve/{proposal_research}', [ProposalResearchController::class, 'approve'])->middleware('auth');
Route::resource('proposal-research', ProposalResearchController::class)->middleware('auth');

Route::get('/research/permit/{research}', [ResearchController::class, 'permit'])->middleware('auth');
Route::post('/research/report', [ResearchController::class, 'report'])->middleware('auth');
Route::get('research/budget/{research}', [ResearchController::class, 'budget'])->middleware('auth');
Route::resource('research', ResearchController::class)->only(['index', 'show'])->middleware('auth');

Route::post('/report-research/report', [ReportResearchController::class, 'report'])->middleware('auth');
Route::put('report-research/approve/{report_research}', [ReportResearchController::class, 'approve'])->middleware('auth');
Route::resource('report-research', ReportResearchController::class)->middleware('auth');

Route::post('/proposal-study/report', [ProposalStudyController::class, 'report'])->middleware('auth');
Route::put('proposal-study/approve/{proposal_study}', [ProposalStudyController::class, 'approve'])->middleware('auth');
Route::resource('/proposal-study', ProposalStudyController::class)->middleware('auth');

Route::get('/study/permit/{study}', [StudyController::class, 'permit'])->middleware('auth');
Route::post('/study/report', [StudyController::class, 'report'])->middleware('auth');
Route::get('study/budget/{study}', [StudyController::class, 'budget'])->middleware('auth');
Route::resource('study', StudyController::class)->only(['index', 'show'])->middleware('auth');

Route::post('/report-study/report', [ReportStudyController::class, 'report'])->middleware('auth');
Route::put('report-study/approve/{report_study}', [ReportStudyController::class, 'approve'])->middleware('auth');
Route::resource('/report-study', ReportStudyController::class)->middleware('auth');

Route::post('/research-member/report', [ResearchController::class, 'member_report'])->middleware('auth');
Route::post('/study-member/report', [StudyController::class, 'member_report'])->middleware('auth');

// Authentication
Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::get('/logout', [LoginController::class, 'logout']);
