<?php

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

////Auth::routes(); Login and Logout
Route::get('/login',                                         'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login',                                        'Auth\LoginController@attemptLogin')->name('login');
Route::get('/logout', 								         'Auth\LoginController@logout')->name('logout'); 
Route::group(['middleware' => ['GoToHttps']], function () 
{
  Route::get('/',                                              'WelcomeController@index')->name('guest');
});
//PARENT/VISITOR ROUET 
Route::group(['middleware' => ['guest'], 'middleware' => ['clear-history'], 'middleware' => ['GoToHttps']], function () 
{
    Route::get('/',                                              'WelcomeController@index')->name('guest');
    Route::post('/student-report-sheet',                         'WelcomeController@postParentParameters')->name('postViewStudentReport');
    Route::get('/student-report-sheet',                          'WelcomeController@viewStudentResult')->name('viewStudentReport');
    Route::get('/log-me-out-now',                                'WelcomeController@parentLogout')->name('parentLogout');
});
//Dashboard and AUTHENTICATION
Route::group(['middleware' => ['auth'], 'middleware' => ['GoToHttps'], 'middleware' => ['clear-history']], function () 
{
    //Home / Dashboard
    Route::get('/home',                                         'HomeController@home')->name('home');
    //Search Current or Old Student
    Route::get('/search_current_graduate_student_json/{ID}',    'BaseController@searchAllCurrentOrOldStudent')->name('searchCurrentOrOldStudent');
    Route::any('/search-student-fee-payment',                  'BaseController@searchActiveStudentFromClass')->name('searchStudentFeePayment'); 
});
//AUTHENTICATION ROUTE
Route::group(['middleware' => ['auth'], 'middleware' => ['GoToHttps'], 'middleware' => ['clear-history'], 'middleware' => ['checkRoleRoute']], function () 
{
    //Update Profile
    Route::get('/profile',                                       'TeacherController@createEditProfile')->name('updateProfile');
    Route::post('/profile',                                      'TeacherController@updateProfile')->name('postUpdateProfile');
    //Class Set Up
    Route::get('/create-class',                                  'ClassController@createClass')->name('createClass');
    Route::post('/create-class',                                 'ClassController@storeClass')->name('createClass');
    Route::get('/remove-class/{id?}',                            'ClassController@removeClass')->name('removeClass');
    Route::get('/get-class-details-json/{ID?}',                  'ClassController@getClassDetailJson');
    Route::get('/edit/class/{id?}',                              'ClassController@editClass')->name('editClass');
    Route::get('/cancel-class-editing',                          'ClassController@cancelEditClass')->name('cancelEditClass');
    //Subject Set Up
    Route::get('/create-subject',                                'SubjectController@createSubject')->name('createSubject');
    Route::post('/create-subject',                               'SubjectController@storeSubject')->name('createSubject');
    Route::get('/remove-subject/{id?}',                          'SubjectController@removeSubject')->name('removeSubject');
    Route::get('/get-subject-details-json/{ID?}',                'SubjectController@getSubjectDetailJson');
    Route::get('/get_subject_list_by_class_json/{classID?}',     'BaseController@getStudentSubject');
    Route::get('/edit/subject/{id?}',                            'SubjectController@editSubject')->name('editSubject');
    Route::get('/cancel-subject-editing',                        'SubjectController@cancelEditSubject')->name('cancelEditSubject');
    //Extra Curricular Set Up
    Route::get('/create-extra-curricular',                       'ExtraCurricularController@createExtraCurricular')->name('createExtra');
    Route::post('/create-extra-curricular',                      'ExtraCurricularController@storeExtraCurricular')->name('postExtra');
    Route::get('/remove-extra-curricular/{id?}',                 'ExtraCurricularController@removeExtraCurricular')->name('removeExtra');
    //Student Set Up
    Route::get('/create-student',                                'StudentController@createStudent')->name('createStudent');
    Route::post('/create-student',                               'StudentController@storeStudent')->name('storeStudent');
    Route::get('/remove-student/{id?}',                          'StudentController@removeStudent')->name('removeStudent');
    Route::get('/student-list',                                  'StudentController@listAllStudent')->name('viewAllStudent');
    Route::get('/student-details/{studentID?}',                  'StudentController@studentDetails')->name('studentDetails');
    Route::get('/activate-deactivate-student/{studentID?}/{statusID?}', 'StudentController@activateDeactivateStudent')->name('activateDeactivateStudent');
    Route::get('/show-student-details/{studentID?}',             'StudentController@createUpdateStudent')->name('editStudent');
    Route::get('/update-student-details',                       'StudentController@listAllStudent');
    Route::post('/update-student-details',                       'StudentController@updateStudentDetails')->name('updateStudentDetails');
    Route::get('/search-student-list',                           'StudentController@listAllStudent');
    Route::post('/search-student-list',                          'StudentController@searchStudentList')->name('searchStudentList');
    Route::get('/student-admission-letter/{studentID?}',        'StudentController@printAdmissionLetter')->name('printAdmissionLetter');
    //Student Quality
    Route::get('/update-student-quality-rating',                 'StudentQualitySetUpController@createStudentQualitySetup')->name('studentQualitySetUp');
    Route::post('/update-student-quality-rating',                'StudentQualitySetUpController@updateStudentQualitySetup')->name('poststudentQualitySetUp');
    Route::get('/search-student-quality-rating',                 'StudentQualitySetUpController@createStudentQualitySetup');
    Route::post('/search-student-quality-rating',                'StudentQualitySetUpController@searchStudentQualitySetup')->name('searchStudentQuality');
    //School Session
    Route::get('/create-school-session',                         'SchoolSessionController@createSchoolSession')->name('createSchoolSession');
    Route::post('/create-school-session',                        'SchoolSessionController@postSchoolSession')->name('postSchoolSession');
    //Teacher Set Up
    Route::get('/create-teacher',                                'TeacherController@createTeacher')->name('createTeacher');
    Route::post('/create-teacher',                               'TeacherController@storeTeacher')->name('storeTeacher');
    Route::get('/remove-teacher/{id?}',                          'TeacherController@removeTeacher')->name('removeTeacher');
    Route::get('/teacher-list',                                  'TeacherController@listAllTeacher')->name('viewAllTeacher');
    Route::get('/activate-suspend-user/{userID?}/{statusID?}',   'TeacherController@activateSuspendUser'); 
    Route::get('/view-user/{userID?}',                           'TeacherController@viewUserDetails')->name('viewUser'); 
    Route::get('/update-teacher-details',                        'TeacherController@listAllTeacher');
    Route::post('/update-teacher-details',                       'TeacherController@updateTeacherDetails')->name('updateTeacher');
    Route::get('/show-teacher-details/{userID?}',                'TeacherController@createUpdateTeaacher')->name('editTeacher');
    //Compute Mark/Score Type
    Route::get('/set-score-type-json/{id?}',                     'ComputeResultController@setScoreType');
    Route::get('/submit-mark-for-students',                      'ComputeResultController@create')->name('createMark');
    Route::post('/submit-mark-for-students',                     'ComputeResultController@compute')->name('createMark');
    Route::get('/find-student-class',                            'ComputeResultController@create');
    Route::post('/find-student-class',                           'ComputeResultController@findStudentInClass')->name('findStudentClass');
    Route::get('/submit-student-scores',                         'ComputeResultController@create');
    Route::post('/submit-student-scores',                        'ComputeResultController@saveStudentScore')->name('submitScores');
    Route::get('/publish-all-result',                            'PublishAllResultPerSessionController@createPublishResult')->name('publishResult');
    Route::post('/publish-all-result',                           'PublishAllResultPerSessionController@savePublishResult')->name('postPublishResult');
    Route::get('/view-mark-sheet',                               'ComputeResultController@createMarkSheet')->name('viewMarkSheet');
    Route::post('/view-mark-sheet',                              'ComputeResultController@postMarkSheet')->name('searchMarkSheet');
    Route::post('/view-student-report-sheet',                    'ComputeResultController@postStudentReportSheetParameters')->name('postStudentReportSheet');
    Route::get('/view-student-report-sheet',                     'ComputeResultController@viewStudentReportSheet')->name('viewStudentReportSheet');
    Route::get('/preview-report-sheet',                          'ComputeResultController@previewReportSheet')->name('previewReportSheet');
    Route::get('/get_student_in_class_for_result_json/{classID?}','BaseController@allStudentForResult');
    Route::get('/get_student_in_class_for_result_from_mark_json/{classID?}','BaseController@allStudentForResultFromMark');
    Route::get('/get_subject_in_class_for_computation_json/{classID?}','BaseController@allStudentForResult');
    Route::get('/delete-mark-for-student-per-subject/{markID?}', 'ComputeResultController@deleteScoreMarkFromMark')->name('deleteMark');
    Route::post('/delete-mark-for-selected-student-per-subject', 'ComputeResultController@deleteSelectedScoreMarkFromMark')->name('deleteSelectedMark');
    Route::get('/delete-mark-for-selected-student-per-subject',  'ComputeResultController@createMarkSheet');//FALLBACK
    //Grade Point Set up
    Route::get('/grade-point-set-up',                            'GradePointController@createGradePoint')->name('createGradePoint');
    Route::post('/grade-point-set-up',                           'GradePointController@saveGradePoint')->name('saveGradePoint');
    Route::get('/remove/grade-point/{id?}',                      'GradePointController@removeGradePoint')->name('removeGradePoint');
    Route::get('/edit/grade-point/{id?}',                        'GradePointController@editGrade')->name('editGradePoint');
    Route::get('/cancel-grade-editing',                          'GradePointController@cancelEditGrade')->name('cancelEditGrade');
    //School Profile Set up
    Route::get('/school-profile-set-up',                         'SchoolProfileController@createProfile')->name('createProfile');
    Route::post('/school-profile-set-up',                        'SchoolProfileController@saveUpdateProfile')->name('postProfile');
    Route::post('/turn-on-off-auto-registration',                'SchoolProfileController@updateRegNumberByJSON');
    //School PIN management
    Route::get('/generate-result-pin-checker',                   'PINManagementController@createResultCheckerPIN')->name('createResultPIN');
    Route::post('/generate-result-pin-checker',                  'PINManagementController@generateResultCheckerPIN')->name('postProcessResultPIN');
    Route::get('/result-pin-checker',                            'PINManagementController@viewResultPIN')->name('viewResultPIN');
    Route::post('/result-pin-checker',                           'PINManagementController@searchResultPIN')->name('postViewResultPIN');
    Route::post('/enable-disable-PIN-Json',                      'PINManagementController@enableDisablePIN');
    //Module and SubModule Set up
    Route::get('/create-route-module',                            'ModuleController@createRouteModule')->name('createModule');
    Route::post('/create-route-module',                           'ModuleController@saveModule')->name('addModule');
    Route::get('/remove/module/{id?}',                            'ModuleController@removeModule')->name('removeModule');
    Route::get('/edit/module/{id?}',                              'ModuleController@editModule')->name('editModule');
    Route::get('/cancel-module-editing',                          'ModuleController@cancelEditModule')->name('cancelEditModule');
    //SubModule and SubModule Set up
    Route::get('/create-route-submodule',                         'SubModuleController@createRouteSubModule')->name('createSubModule');
    Route::post('/create-route-submodule',                        'SubModuleController@saveSubModule')->name('addSubModule');
    Route::get('/remove/submodule/{id?}',                         'SubModuleController@removeSubModule')->name('removeSubModule');
    Route::get('/edit/submodule/{id?}',                           'SubModuleController@editSubModule')->name('editSubModule');
    Route::get('/cancel-submodule-editing',                       'SubModuleController@cancelEditSubModule')->name('cancelEditSubModule');
     //Role and Permission
     Route::get('/create-update-role',                             'RolePermissionController@createRole')->name('createRole');
     Route::post('/create-update-role',                            'RolePermissionController@saveRole')->name('saveRole');
     Route::get('/remove-role/{id?}',                              'RolePermissionController@removeRole')->name('removeRole');
     Route::get('/edit-role/{id?}',                                'RolePermissionController@editRole')->name('editRole');
     Route::get('/cancel-role-editing',                            'RolePermissionController@cancelEditRole')->name('cancelEditRole');
     Route::get('/assigning-submodule-to-role',                    'RolePermissionController@createSubmoduleToRole')->name('createSubmoduleAssignment');
     Route::post('/assigning-submodule-to-role',                   'RolePermissionController@saveSubmoduleToRole')->name('postSubmoduleAssignment');
     Route::get('/assigning-role-to-user',                         'RolePermissionController@createRole');
     Route::post('/assigning-role-to-user',                        'RolePermissionController@assignRoleToUser')->name('assignRoleToUser');
    //IMPORT STUDENT FROM EXCEL FILE
    Route::get('/upload-student-via-file',                         'StudentController@createImportExport')->name('loadImportPage');
    Route::get('/download-excel/{type?}',                          'StudentController@downloadExcel')->name('downloadExcel');
    Route::get('/download-new-excel/{type?}',                      'StudentController@downloadNewExcel')->name('downloadNewExcel');
    Route::get('/import-student-from-excel-file',                  'StudentController@createImportExport');
    Route::post('/import-student-from-excel-file',                 'StudentController@importExcel')->name('importExcel');
    Route::get('/submit-student-standard-list',                    'StudentController@createImportExport');
    Route::post('/submit-student-standard-list',                   'StudentController@registerStudentImportExcel')->name('registerStudentImport');     
    Route::get('/remove-student-from-temp-student/{ID?}',          'StudentController@deleteRowFromImportData')->name('deleteRowImport');  
    //IMPORT MARK FROM EXCEL FILE
    Route::get('/upload-marks-via-file',                            'ComputeResultController@createMarkImport')->name('loadMarkImportPage');
    Route::get('/download-mark-excel/{type?}',                      'ComputeResultController@downloadMarkExcel')->name('downloadMarkExcel');
    Route::get('/download-new-mark-excel/{type?}',                  'ComputeResultController@downloadNewMarkExcel')->name('downloadNewMarkExcel');
    Route::get('/import-mark-from-excel-file',                      'ComputeResultController@createMarkImport');
    Route::post('/import-mark-from-excel-file',                     'ComputeResultController@importMarkFromExcel')->name('importMarkExcel');
    Route::get('/submit-marks-to-score-list',                       'ComputeResultController@loadMarkImportPage'); 
    Route::post('/submit-marks-to-score-list',                      'ComputeResultController@submitMarkScoreList')->name('submitMarkImported');     
    Route::get('/remove-mark-from-temp-mark/{ID?}',                 'ComputeResultController@deleteMarkFromTempMark')->name('deleteRowMarkImport');
    //class
    Route::get('/print-list-of-classes',                            'ClassController@printClass')->name('printClass'); 
    Route::get('/export-list-of-classes/{type?}',                   'ClassController@exportClass')->name('exportClass'); 
    //subject
    Route::get('/print-list-of-subjects',                           'SubjectController@printSubject')->name('printSubject'); 
    Route::get('/export-list-of-subject/{type?}',                   'SubjectController@exportSubject')->name('exportSubject'); 
    //student
    Route::get('/print-list-of-student',                           'StudentController@printStudent')->name('printStudent'); 
    Route::get('/export-basic-list-of-student/{type?}',            'StudentController@exportBasicStudent')->name('exportBasicStudent');
    Route::get('/export-full-list-of-student/{type?}',             'StudentController@exportFullStudent')->name('exportFullStudent');
    Route::get('/export-full-list-of-student-pdf',                 'StudentController@exportBasicStudentPDF')->name('exportStudentPDF'); 
    //Student Promotion
    Route::get('/create-student-promotion',                         'StudentController@createStudentPromotion')->name('createStudentPromotion');
    Route::post('/create-student-promotion',                        'StudentController@storeStudentPromotion')->name('processStudentPromotion'); 
    //User/Teacher
    Route::get('/print-list-of-teacher',                           'TeacherController@printTeacher')->name('printTeacher'); 
    Route::get('/export-basic-list-of-teacher/{type?}',            'TeacherController@exportBasicTeacher')->name('exportBasicTeacher');
    Route::get('/export-full-list-of-teacher/{type?}',             'TeacherController@exportFullTeacher')->name('exportFullTeacher');
    Route::get('/export-full-list-of-teacher-pdf',                 'TeacherController@exportBasicTeacherPDF')->name('exportSTeacherPDF'); 
    //STUDENT ATTENDANCE  
    Route::get('/student-attandance-update',                        'StudentAttendanceController@createStudentAttendance')->name('studentAttandance');
    Route::post('/student-attandance-update',                       'StudentAttendanceController@updateStudentAttendanceSetup')->name('postStudentAttandance');
    Route::get('/student-attandance-search-student',                'StudentAttendanceController@createStudentQualitySetup');
    Route::post('/student-attandance-search-student',               'StudentAttendanceController@searchStudentAttendance')->name('searchStudentAttendance');
    //SMS   
    Route::get('/create-and-send-sms',                              'SMSController@createSMS')->name('createSMS');
    Route::post('/create-and-send-sms',                             'SMSController@sendSMS')->name('sendSMS');
    //SEND EMAIL   
    Route::get('/create-and-send-email',                            'EmailController@createEmail')->name('createEmail');
    Route::post('/create-and-send-email',                           'EmailController@sendEmail')->name('sendEmail');
    //School Type
    Route::get('/create-school-type',                              'SchoolType@createSchoolType')->name('createSchoolType');
    Route::post('/create-school-type',                             'SchoolType@stroreSchoolType')->name('storeSchoolType');
    //Fee Setup
    Route::get('/create-fees',                                      'FeeSetupController@createFees')->name('createFees');
    Route::post('/create-fees',                                     'FeeSetupController@storeFees')->name('storeFees');
    Route::get('/edit-fees/{feeID}',                                'FeeSetupController@editFees')->name('editFee');
    Route::get('/edit-fees',                                        'FeeSetupController@cancelEdit')->name('cancelEditFees');
    Route::get('/remove-fees/{feeID}',                              'FeeSetupController@removeFees')->name('removeFee');
    //Class Fee Setup
    Route::get('/create-class-fee-setup',                           'FeeSetupController@createClassFeesSetup')->name('classFeeSetup');
    Route::post('/create-class-fee-setup',                          'FeeSetupController@storeClassFeesSetup')->name('postClassFeeSetup');
    //Student Fee Setup
    Route::get('/create-student-fee-setup',                          'FeeSetupController@createStudentFeesSetup')->name('studentFeeSetup');
    Route::post('/create-student-fee-setup',                         'FeeSetupController@storeStudentFeesSetup')->name('postStudentFeeSetup');
    Route::any('/search-student-class-fee-setup',                   'BaseController@searchActiveStudentFromClass')->name('searchStudentClassFee'); 
    Route::any('/update-student-fee-setup',                         'FeeSetupController@updateStudentAssignedFeeSetup'); 
    Route::any('/add-more-fee-for-student-setup',                   'FeeSetupController@addMoreFeeForStudentFeeSetup')->name('AddMoreFeeStudent'); 
    Route::get('/remove-additional-fee-from-student-setup/{studentFeeSetupID}', 'FeeSetupController@removeAdditionalFeeForStudent')->name('removeAdditionalFee'); 
    //Student Fee Payment
    Route::get('/student-fee-payment',                                'StudentFeePaymentController@createStudentFeePayment')->name('createStudentFeePayment');
    Route::post('/student-fee-payment',                               'StudentFeePaymentController@storeStudentFeePayment')->name('processStudentFeePayment');
    Route::get('/remove-student-payment-history/{ID}',                'StudentFeePaymentController@deleteStudentPaymentHistory')->name('deletePaymentHistory'); 
    //Get All Paid, Debtors students
    Route::get('/payment-fee-report',                                   'FeePaymentReportController@createFeePaymentReport')->name('getFeePaymentReport');
    //View Graduate/Withdrawal student list
    Route::get('/graduate-withdrawal-student-list',                     'StudentController@graduateWithdrawalList')->name('getGraduateWithdrawalStudent');
    //View Student Payment Receipt
    Route::get('/student-payment-receipt/{TransactionID}',              'FeePaymentReportController@studentPaymentReceipt')->name('studentPaymentReceipt');
    //Fee Enquiry
    Route::get('/student-fee-enquiry-for-parent-visitor',               'FeeEnquiryController@createFeeEnquiry')->name('createFeeEnquiry');
    Route::post('/student-fee-enquiry-for-parent-visitor',              'FeeEnquiryController@searchClassFeeEnquiry')->name('searchClassFeeEnquiry');
    //Daily, Weekly, Monthly Fee Setup
    Route::get('/daily-weekly-monthly-student-fee-setup',               'DailyWeeklyFeeController@createStudentDailyWeeklyMonthlyFeeSetup')->name('createDailyFeeSetup');
    Route::post('/daily-weekly-monthly-student-fee-setup',              'DailyWeeklyFeeController@processStudentDailyWeeklyMonthlyFeeSetup')->name('processDailyFeeSetup');
    Route::get('/daily-weekly-monthly-make-payment',                    'DailyWeeklyFeeController@createDailyWeeklyMonthlyPayment')->name('createDailyPayment');
    Route::post('/daily-weekly-monthly-make-payment',                   'DailyWeeklyFeeController@processDailyWeeklyMonthlyPayment')->name('processDailyPayment');
    Route::get('/daily-weekly-monthly-make-payment-report',             'DailyWeeklyFeeController@createDailyWeeklyMonthlyPaymentReport')->name('createDailyPaymentReport');
    Route::post('/daily-weekly-monthly-make-payment-report',            'DailyWeeklyFeeController@generateDailyWeeklyMonthlyPaymentReport')->name('processDailyPaymentReport');
    Route::post('/daily-weekly-monthly-search-student-list',            'DailyWeeklyFeeController@searchStudentList')->name('dailyWeeklyMonthlySearchStudentList');
    Route::get('/daily-payment-report',                                 'DailyWeeklyFeeController@createDailyOnlyPaymentReport')->name('dailyPaymentReport');
    
    

});




