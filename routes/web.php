<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Dompdf\Dompdf;
use App\Http\Controllers\JobPositionController;
use App\Http\Controllers\MemoController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TicketController;
use LINE\LINEBot\EchoBot\Setting;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DamageReportController;
use App\Http\Controllers\HomeController;

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('/');

Route::get('/damage-report', [DamageReportController::class, 'index'])->name('damage-report');

Route::post('/damage-report/store', [DamageReportController::class, 'store'])->name('damage.store');

Route::get('/get-product', [DamageReportController::class, 'getProduct'])->name('get.product');
Route::get('/get-employee', [DamageReportController::class, 'getEmployee'])->name('get.employee');

Route::get('/manager-approve', [DamageReportController::class, 'managerApprove'])->name('manager-approve');
Route::post('/damage-report/approve-action', [DamageReportController::class, 'approveAction'])->name('damage.approve.action');

Route::get('/admin-approve', [DamageReportController::class, 'adminApprove'])->name('admin-approve');
Route::post('/damage-report/admin-action', [DamageReportController::class, 'adminAction'])->name('damage.admin.action');

Route::get('/destroy-list', [DamageReportController::class, 'destroyList'])->name('destroy.list');
Route::get('/destroy-form/{id}', [DamageReportController::class, 'destroyForm'])->name('destroy.form');
Route::post('/destroy-store', [DamageReportController::class, 'destroyStore'])->name('destroy.store');
Route::get('/destroy-print/{id}', [DamageReportController::class, 'destroyPrint'])->name('destroy.print');

Route::get('/branch-sap', [DamageReportController::class, 'branchSap'])->name('branch.sap');
Route::post('/branch-sap-save', [DamageReportController::class, 'branchSapSave'])->name('branch.sap.save');
Route::get('/hr-approve', [DamageReportController::class, 'hrApprove'])->name('hr.approve');
Route::post('/hr-save', [DamageReportController::class, 'hrSave'])->name('hr.save');

Route::get('discount-list', [DamageReportController::class, 'discountList'])->name('discount.list');
Route::get('discount-print/{id}', [DamageReportController::class, 'discountPrint'])->name('discount.print');
Route::post('discount-print-save', [DamageReportController::class, 'discountPrintSave'])->name('discount.print.save');




// Route::get('/storage-link', function () {
//     // ลบลิงก์เดิมก่อน (ถ้ามี)
//     $link = public_path('storage');
//     if (is_link($link) || file_exists($link)) {
//         unlink($link);
//     }

//     // สร้างลิงก์ใหม่
//     Artisan::call('storage:link');
//     return "✅ Storage link has been created successfully!";
// });

Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'validateLogin'])->name('login');

Route::post('/custom-logout', [App\Http\Controllers\HomeController::class, 'logout'])->name('custom-logout');

Route::get('/comming-soon', function () {
    return view("pages-comingsoon");
});
Route::get('refresh_captcha', [App\Http\Controllers\Auth\LoginController::class, 'refreshCaptcha'])->name('refresh_captcha');
// Route::post('/memo/store', [App\Http\Controllers\MemoController::class, 'store'])->name('memo.store');

Route::middleware(['auth'])->group(function () {
    Route::post('/update-profile/{id}', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('updateProfile');
    Route::post('/update-password/{id}', [App\Http\Controllers\HomeController::class, 'updatePassword'])->name('updatePassword');

    Route::get('/branch-manage', [SettingController::class, 'branch_manage'])->name('branch_manage');
    Route::get('/branch-add', [SettingController::class, 'branch_add'])->name('branch_add');
    Route::post('/branch-store', [SettingController::class, 'branch_store'])->name('branch_store');
    Route::get('/branch-edit/{id}', [SettingController::class, 'branch_edit'])->name('branch_edit');
    Route::post('/branch-update/{id}', [SettingController::class, 'branch_update'])->name('branch_update');
    Route::delete('/branch/delete/{id}', [SettingController::class, 'branch_delete'])->name('branch_delete');
    Route::get('/user-management', [App\Http\Controllers\SettingController::class, 'user_manage'])->name('user_manage');
    Route::post('/deleteUser', [App\Http\Controllers\SettingController::class, 'deleteUser'])->name('deleteUser');
    Route::get('/user-add', [SettingController::class, 'user_add'])->name('user_add');
    Route::get('/get-type-by-section', [SettingController::class, 'getTypeBySection'])->name('getTypeBySection');




    // Route::post('/user-add/store', [UserController::class, 'store'])->name('user_add.store');
    Route::get('/user-edit', [App\Http\Controllers\SettingController::class, 'user_edit'])->name('user_edit');
    Route::post('/addUserDB', [App\Http\Controllers\SettingController::class, 'addUserDB'])->name('addUserDB');
    Route::post('/editUserDB', [App\Http\Controllers\SettingController::class, 'editUserDB'])->name('editUserDB');
    Route::post('/userblock', [App\Http\Controllers\SettingController::class, 'userblock'])->name('userblock');





    Route::get('/sections/by-branch', [SettingController::class, 'sectionsByBranch'])->name('sections-byBranch');
    Route::get('/departments-by-section', [SettingController::class, 'departmentsBySection'])->name('departments-bySection');



    Route::get('/type-request', [SettingController::class, 'type_request_manage'])->name('type_request_manage');

    Route::get('/type-request/add', [SettingController::class, 'type_request_add'])->name('type_request_add');
    Route::post('/type-request/store', [SettingController::class, 'type_request_store'])->name('type_request_store');

    Route::get('/type-request/edit/{id}', [SettingController::class, 'type_request_edit'])->name('type_request_edit');
    Route::post('/type-request/update/{id}', [SettingController::class, 'type_request_update'])->name('type_request_update');

    Route::post('/type-request/delete/{id}', [SettingController::class, 'type_request_delete'])->name('type_request_delete');
    // Route::get('/run-storage-link', function () {
    //     // ลบลิงก์เดิมก่อน (ถ้ามี)
    //     $link = public_path('storage');
    //     if (is_link($link) || file_exists($link)) {
    //         unlink($link);
    //     }

    //     // สร้างลิงก์ใหม่
    //     Artisan::call('storage:link');
    //     return "✅ Storage link has been created successfully!";
    // });
});
