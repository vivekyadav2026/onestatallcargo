<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\RazorpayController;

/*
|--------------------------------------------------------------------------
| Web Routes - Testwise MP Police Constable GD 2026 Portal
|--------------------------------------------------------------------------
*/

// Public Website Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/courses', [HomeController::class, 'courses'])->name('courses');
Route::get('/exam/{slug}', [HomeController::class, 'examDetails'])->name('exam.details');
Route::get('/enroll/{id}', [HomeController::class, 'enroll'])->name('enroll');
Route::get('/free-content', [HomeController::class, 'freeContent'])->name('free-content');
Route::get('/verify-certificate', [HomeController::class, 'verifyCertificate'])->name('verify-certificate');
Route::get('/exam-info', [HomeController::class, 'examInfo'])->name('exam-info');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/api/search', [HomeController::class, 'search'])->name('api.search');

// Authentication & Quick Role Switcher Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/logout', [AuthController::class, 'logout']);

// Google OAuth Authentication Routes
Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Password Reset Routes
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');

// Razorpay Payment Gateway Routes
Route::post('/razorpay/create-order', [RazorpayController::class, 'createOrder'])->name('razorpay.create-order')->middleware('auth');
Route::post('/razorpay/verify-payment', [RazorpayController::class, 'verifyPayment'])->name('razorpay.verify-payment')->middleware('auth');


// Student Portal Routes
Route::redirect('/dashboard', '/student/my-courses');
Route::prefix('student')->name('student.')->middleware('auth')->group(function () {
    Route::get('/my-courses', [StudentController::class, 'myCourses'])->name('my-courses');
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
    Route::get('/course', [StudentController::class, 'course'])->name('course');
    Route::get('/chapter-tests', [StudentController::class, 'course'])->name('chapter-tests');
    Route::get('/notes/{id}', [StudentController::class, 'readNotes'])->name('notes');
    Route::get('/mock-tests', [StudentController::class, 'mockTests'])->name('mock-tests');
    Route::get('/cbt-test/{type}/{id}', [StudentController::class, 'takeCbtTest'])->name('cbt-test');
    Route::post('/submit-test/{type}/{id}', [StudentController::class, 'submitCbtTest'])->name('submit-test');
    Route::get('/test-result/{attemptId}', [StudentController::class, 'testResult'])->name('test-result');
    Route::get('/mistakes', [StudentController::class, 'mistakes'])->name('mistakes');
    Route::get('/weak-topics', [StudentController::class, 'weakTopics'])->name('weak-topics');
    Route::get('/certificate', [StudentController::class, 'certificate'])->name('certificate');
    Route::get('/performance', [StudentController::class, 'performance'])->name('performance');
    Route::get('/profile', [StudentController::class, 'profile'])->name('profile');
    Route::put('/profile/update', [StudentController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [StudentController::class, 'updatePassword'])->name('profile.password');
    Route::post('/unlock-pro', [StudentController::class, 'unlockPro'])->name('unlock-pro');
    Route::post('/switch-course', [StudentController::class, 'switchCourse'])->name('switch-course');
});

// Admin Console Routes
Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin')->middleware('auth');
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Courses
    Route::get('/courses', [AdminController::class, 'courses'])->name('courses');
    Route::post('/courses/store', [AdminController::class, 'storeCourse'])->name('courses.store');
    Route::put('/courses/{id}', [AdminController::class, 'updateCourse'])->name('courses.update');
    Route::delete('/courses/{id}', [AdminController::class, 'destroyCourse'])->name('courses.destroy');
    
    // Subjects
    Route::get('/subjects', [AdminController::class, 'subjects'])->name('subjects');
    Route::post('/subjects/store', [AdminController::class, 'storeSubject'])->name('subjects.store');
    Route::put('/subjects/{id}', [AdminController::class, 'updateSubject'])->name('subjects.update');
    Route::delete('/subjects/{id}', [AdminController::class, 'destroySubject'])->name('subjects.destroy');
    
    Route::get('/chapters', [AdminController::class, 'chapters'])->name('chapters');
    Route::post('/chapters/{id}/toggle-free', [AdminController::class, 'toggleFreePreview'])->name('chapters.toggle-free');
    Route::post('/chapters/store', [AdminController::class, 'storeChapter'])->name('chapters.store');
    Route::put('/chapters/{id}', [AdminController::class, 'updateChapter'])->name('chapters.update');
    Route::delete('/chapters/{id}', [AdminController::class, 'destroyChapter'])->name('chapters.destroy');
    
    Route::get('/questions', [AdminController::class, 'questions'])->name('questions');
    Route::post('/questions/store', [AdminController::class, 'storeQuestion'])->name('questions.store');
    Route::delete('/questions/{id}', [AdminController::class, 'destroyQuestion'])->name('questions.destroy');
    
    Route::get('/mock-tests', [AdminController::class, 'mockTests'])->name('mock-tests');
    Route::post('/mock-tests/store', [AdminController::class, 'storeMockTest'])->name('mock-tests.store');
    Route::put('/mock-tests/{id}', [AdminController::class, 'updateMockTest'])->name('mock-tests.update');
    Route::delete('/mock-tests/{id}', [AdminController::class, 'destroyMockTest'])->name('mock-tests.destroy');
    Route::get('/students', [AdminController::class, 'students'])->name('students');
    Route::post('/students/store', [AdminController::class, 'storeStudent'])->name('students.store');
    Route::put('/students/{id}', [AdminController::class, 'updateStudent'])->name('students.update');
    Route::delete('/students/{id}', [AdminController::class, 'destroyStudent'])->name('students.destroy');
    Route::post('/students/{id}/toggle-pro', [AdminController::class, 'togglePro'])->name('students.toggle-pro');
    Route::get('/payments', [AdminController::class, 'payments'])->name('payments');
    Route::put('/payments/{id}', [AdminController::class, 'updatePayment'])->name('payments.update');
    Route::delete('/payments/{id}', [AdminController::class, 'destroyPayment'])->name('payments.destroy');
    Route::get('/certificates', [AdminController::class, 'certificates'])->name('certificates');
    Route::post('/certificates/store', [AdminController::class, 'storeCertificate'])->name('certificates.store');
    Route::put('/certificates/{id}', [AdminController::class, 'updateCertificate'])->name('certificates.update');
    Route::delete('/certificates/{id}', [AdminController::class, 'destroyCertificate'])->name('certificates.destroy');
});
