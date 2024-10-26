<?php

use App\Models\ChatButton;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\EvaluatorController;
use App\Http\Controllers\MessagingController;
use App\Http\Controllers\SecretaryController;
use App\Http\Controllers\ChatButtonController;
use App\Http\Controllers\UserPasswordController;

Route::get('/', function () {
    return view('auth.login');
})->middleware('guestOnly');


// Route::get('/', [HomeController::class,'index'])->middleware('guestOnly');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard')->middleware('guestOnly');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';




Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/_admin', [AdminController::class, 'index']);
    Route::get('/admin_dashboard', [AdminController::class, 'admin_dashboard'])->name('admin.dashboard');
    Route::get('/add_department', [AdminController::class, 'add_department']);
    Route::post('/create_department', [AdminController::class, 'create_department']);
    Route::get('/view_departments', [AdminController::class, 'view_departments']);
    Route::get('/choose_department', [AdminController::class, 'choose_department']);
    Route::get('/add_course/{id}',[AdminController::class,'add_course']);
    Route::post('/create_course/{id}',[AdminController::class,'create_course']);
    Route::get('/view_course_department',[AdminController::class,'view_course_department']);
    Route::get('/view_course/{id}',[AdminController::class,'view_course']);


    
    Route::get('/add_admin',[AdminController::class,'add_admin']);
  
    
    Route::post('/create_admin',[AdminController::class,'create_admin']);

    Route::get('/add_evaluator',[AdminController::class,'add_evaluator'])->name('add_evaluator');
    Route::post('/create_evaluator',[AdminController::class,'create_evaluator']);
    Route::put('/evaluators/{id}', [AdminController::class, 'update_eval'])->name('evaluators.update');

    Route::get('/sidebar',[AdminController::class,'user_details']);


    Route::get('/add_secretary',[AdminController::class,'add_secretary']);
    Route::post('/create_secretary',[AdminController::class,'create_secretary']);
    Route::put('update_secretary', [AdminController::class, 'update_secretary'])->name('update_secretary');






    Route::get('/add_students',[AdminController::class,'add_students']);
    Route::get('/add_student_course/{id}',[AdminController::class,'add_student_course']);
    Route::get('/create_student_form/{id}',[AdminController::class,'create_student_form']);
    Route::post('/create_student_account',[AdminController::class,'create_student_account']);

    // CHAT
        Route::get('/chat/manage', [AdminController::class, 'manage'])->name('chat.manage');
        Route::post('/chat/keyword/store', [AdminController::class, 'storeKeyword'])->name('chat.storeKeyword');
        Route::post('/chat/question/store', [AdminController::class, 'storeQuestion'])->name('chat.storeQuestion');
        Route::put('/chat/keyword/update/{id}', [AdminController::class, 'updateKeyword'])->name('chat.updateKeyword');
        Route::put('/chat/question/update/{id}', [AdminController::class, 'updateQuestion'])->name('chat.updateQuestion');
        Route::delete('/chat/keyword/delete/{id}', [AdminController::class, 'deleteKeyword'])->name('chat.deleteKeyword');
        Route::delete('/chat/question/delete/{id}', [AdminController::class, 'deleteQuestion'])->name('chat.deleteQuestion');


        // SEMESTER UPDATE

        Route::post('/update-semester', [AdminController::class, 'updateSemester'])->name('update_semester');



        Route::get('/ChatView', [MessagingController::class, 'user_chats'])->name('chat.view_list');
        Route::get('/ChatView/{userId}', [MessagingController::class, 'viewUserMessages'])->name('chat.userMessages');
        Route::post('/chat/reply/{userId}', [MessagingController::class, 'reply'])->name('reply.chat');
        

});





Route::middleware(['auth', 'role:evaluator'])->group(function () {
    Route::get('/evaluator_home', [EvaluatorController::class, 'index'])->name('evaluator.dashboard');
    Route::get('/add_curriculum_dep', [EvaluatorController::class, 'add_curriculum_dep']);
    Route::get('/add_curriculum_course/{id}', [EvaluatorController::class, 'add_curriculum_course']);
    Route::get('/add_curriculum/{id}', [EvaluatorController::class, 'add_curriculum']);

    Route::post('/Generate_Curriculum', [EvaluatorController::class, 'Generate_Curriculum']);

    Route::get('/view_curriculum_dep', [EvaluatorController::class, 'view_curriculum_dep']);
    Route::get('/view_curriculum_course/{id}', [EvaluatorController::class, 'view_curriculum_course']);
    Route::get('/view_curriculums/{id}', [EvaluatorController::class, 'view_curriculums']);
    Route::get('/view_curriculum_details/{id}', [EvaluatorController::class, 'view_curriculum_details']);

    Route::get('/view_students_dep', [EvaluatorController::class, 'view_students_dep']);
    Route::get('/view_students_course/{id}', [EvaluatorController::class, 'view_students_course']);
    Route::get('/view_students/{id}', [EvaluatorController::class, 'view_students']);
    Route::get('/view_student_details/{id}', [EvaluatorController::class, 'view_student_details']);

    Route::get('/manage_students_dep', [EvaluatorController::class, 'manage_students_dep']);
    Route::get('/manage_students/{id}', [EvaluatorController::class, 'manage_students']);
    Route::get('/manage_student_details/{id}', [EvaluatorController::class, 'manage_student_details']);

    Route::post('/update_grade/{id}', [EvaluatorController::class, 'update_grade']);

    Route::get('/add_student_curriculum/{id}', [EvaluatorController::class, 'add_student_curriculum']);
    Route::get('/manage_student_curriculum/{id}', [EvaluatorController::class, 'manage_student_curriculum']);
    Route::post('/give_student_curriculum/{id}', [EvaluatorController::class, 'give_student_curriculum']);

    Route::get('/update_multiple_curriculums_courses/{id}', [EvaluatorController::class, 'update_multiple_curriculums_courses']);
    Route::get('/update_multiple_students/{id}', [EvaluatorController::class, 'update_multiple_students']);
    Route::post('/give_multiple_curriculum', [EvaluatorController::class, 'give_multiple_curriculum']);

    Route::post('/update_student_account', [EvaluatorController::class, 'update_student_account']);
    
    Route::post('/update_student_semester', [EvaluatorController::class, 'update_student_semester']);

    
    Route::get('/view_pending_enrollees', [EvaluatorController::class, 'view_pending_enrollees'])->name('view_pending_enrollees');

    Route::get('/view_enrolled_students', [EvaluatorController::class, 'view_enrolled_students'])->name('view_enrolled_students');

    Route::post('EnrollStudent/{advisingId}', [EvaluatorController::class, 'EnrollStudent'])->name('EnrollStudent');


    Route::get('/enrollee_advising_details/{id}', [EvaluatorController::class, 'enrollee_advising_details']);
    Route::get('/enrolled_student_details/{id}', [EvaluatorController::class, 'enrolled_student_details']);
    
    Route::get('tor-requests', [EvaluatorController::class, 'viewTorRequests'])->name('tor.view');

    Route::get('/print-tor/{id}', [EvaluatorController::class, 'printTOR'])->name('print.tor');
    

});

Route::middleware(['auth', 'role:secretary'])->group(function () {
    Route::get('/secretary_home', [SecretaryController::class, 'index'])->name('secretary.dashboard');
    Route::get('/view_departments_sec', [SecretaryController::class, 'view_departments_sec']);
    Route::get('/add_subjects/{id}', [SecretaryController::class, 'add_subjects']);
    Route::get('/view_course_sec/{id}', [SecretaryController::class, 'view_course_sec']);
    Route::post('/create_subjects', [SecretaryController::class, 'create_subjects']);
    Route::get('/manage_subjects_dep', [SecretaryController::class, 'manage_subjects_dep']);
    Route::get('/subject_dep_course/{id}', [SecretaryController::class, 'subject_dep_course']);
    Route::get('/manage_subjects/{id}', [SecretaryController::class, 'manage_subjects']);
    Route::post('updateSubjectRelations', [SecretaryController::class, 'updateSubjectRelations']);
    Route::get('/view_department_sub', [SecretaryController::class, 'view_department_sub']);
    Route::get('/view_course_sub/{id}', [SecretaryController::class, 'view_course_sub']);
    Route::get('/view_subjects/{id}', [SecretaryController::class, 'view_subjects']);
    Route::get('/manage_advising_dep', [SecretaryController::class, 'manage_advising_dep']);
    Route::get('/manage_advising_course/{id}', [SecretaryController::class, 'manage_advising_course']);
    Route::get('/manage_advising/{id}', [SecretaryController::class, 'manage_advising']);
    Route::post('updateAdvising', [SecretaryController::class, 'updateAdvising']);
    Route::get('/view_advising_dep', [SecretaryController::class, 'view_advising_dep']);
    Route::get('/view_advising_course/{id}', [SecretaryController::class, 'view_advising_course']);
    Route::get('/view_advising/{id}', [SecretaryController::class, 'view_advising']);
    Route::get('/advising_records', [SecretaryController::class, 'advising_records'])->name('advising_records');
    Route::get('/advising_details/{id}', [SecretaryController::class, 'advising_details']);
    Route::post('updateAdvisingDetails', [SecretaryController::class, 'updateAdvisingDetails']);

    Route::post('cancelAdvisingDetails/{advisingId}', [SecretaryController::class, 'cancelAdvisingDetails'])->name('cancelAdvisingDetails');

    Route::get('cancelled_advising_details/{id}', [SecretaryController::class, 'cancelled_advising_details'])->name('cancelledAdvisingDetails');
    Route::get('enrolled_advising_details/{id}', [SecretaryController::class, 'enrolled_advising_details'])->name('enrolledAdvisingDetails');


    Route::get('/manage_advising_request', [SecretaryController::class, 'manage_advising_request'])->name('manage_advising_request');
    
    Route::post('single_create_subjects', [SecretaryController::class, 'single_create_subjects']);


});

Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/student_home', [StudentController::class, 'index'])->name('student.dashboard');
    Route::get('/view_subjects', [StudentController::class, 'view_subjects']);
    Route::get('/advising', [StudentController::class, 'advising']);
    Route::post('/advise', [StudentController::class, 'advise']);
    Route::get('/advise', [StudentController::class, 'advise']);
    Route::get('/chatHistory', [ChatController::class, 'chatHistory']);

    Route::post('/submitAdvising', [StudentController::class, 'submitAdvising']);
    Route::post('/submitOverloadAdvising', [StudentController::class, 'submitOverloadAdvising'])->name('submitOverloadAdvising');


    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::get('/chat/answer/{questionId}', [ChatController::class, 'getAnswer'])->name('chat.getAnswer');


    Route::post('/tor/request', [StudentController::class, 'requestTor'])->name('tor.request');


    Route::get('/chat_support', [MessagingController::class, 'index']);
    Route::post('/chat/send1', [MessagingController::class, 'send'])->name('send_chat');




    
    
});




Route::get('/change_password', [UserPasswordController::class, 'showChangePasswordForm'])->name('change.password');
Route::post('/change_password', [UserPasswordController::class, 'changePassword'])->name('change.password.post')->middleware('auth');


Route::get('/chat/support', [MessagingController::class, 'showChatSupport'])->name('chat.support');


