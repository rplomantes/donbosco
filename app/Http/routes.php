<?php
    Route::group(['middleware' => 'web'], function () {
    Route::auth();
    Route::get('/', 'MainController@index');
    //Registrar module
    Route::get('studentlist','Registrar\StudentlistController@studentlist');
    Route::get('enrollmentstat','Registrar\EnrollmentstatController@enrollmentstat');
    Route::get('registrar/assessment','Registrar\AssessmentController@index');
    Route::get('registrar/show', 'Registrar\AssessmentController@show');
    Route::get('registrar/evaluate/{id}','Registrar\AssessmentController@evaluate');
    Route::get('registrar/edit/{id}','Registrar\AssessmentController@edit');
    Route::post('registrar/editname','Registrar\AssessmentController@editname');
    Route::post('registrar/assessment','Registrar\AssessmentController@assess');
    Route::get('studentregister', 'MainController@getid');
    Route::post('studentregister', 'MainController@addstudent');
    Route::get('/sectionk','Registrar\SectionController@sectionk');
    Route::get('printsection/{level}/{section}/{strand}', 'Registrar\SectionController@printsection1');
    Route::get('printsection/{level}/{section}', 'Registrar\SectionController@printsection');
    Route::get('printinfo','Registrar\StudentlistController@printinfo');
    Route::get('studentinfokto12/{idno}','Registrar\Studentinfokto12Controller@studentinfokto12edit');
    Route::post('studentinfokto12/{idno}','Registrar\Studentinfokto12Controller@updateInfo');
    Route::get('studentinfokto12/{idno}/delete','Registrar\Studentinfokto12Controller@deleteStudent');
    Route::get('studentinfokto12/{idno}/print','Registrar\Studentinfokto12Controller@printInfo');    
    Route::get('studentinfokto12','Registrar\Studentinfokto12Controller@studentinfokto12');
    Route::post('studentinfokto12','Registrar\Studentinfokto12Controller@saveInfo');
    Route::get('importGrade', 'ExportController@importGrade');
    Route::post('importConduct', 'ExportController@importExcelConduct');
    //VINCENT 10-8-2016
    //Route::post('importAttendance', 'ExportController@importExcelAttendance');
    Route::post('importAttendance', 'Vincent\AttendanceController@importMonthlyAttendance');
    Route::post('importGrade', 'ExportController@importExcelGrade');
    Route::post('importCompetence', 'ExportController@importExcelCompetence');
    
     Route::get('/seegrade/{idno}','Registrar\GradeController@seegrade');
     Route::get('printreportcard','Registrar\GradeController@printreportcard');
     
     
//cashier module
    Route::get('cashier/{idno}','Cashier\CashierController@view');
    Route::post('payment','Cashier\CashierController@payment');
    Route::get('/setreceipt/{id}','Cashier\CashierController@setreceipt');
    Route::post('/setreceipt','Cashier\CashierController@setOR');
    Route::get('/viewreceipt/{refno}/{idno?}','Cashier\CashierController@viewreceipt');
    Route::get('otherpayment/{idno}','Cashier\CashierController@otherpayment');
    Route::post('othercollection','Cashier\CashierController@othercollection');
    Route::get('collectionreport/{transactiondate}','Cashier\CashierController@collectionreport');
    Route::get('cancell/{refno}/{idno}','Cashier\CashierController@cancell');
    Route::get('restore/{refno}/{idno}','Cashier\CashierController@restore');
    Route::get('encashment','Cashier\CashierController@encashment');
    Route::post('encashment','Cashier\CashierController@postencashment');
    Route::get('encashmentreport','Cashier\CashierController@encashmentreport');
    Route::get('viewencashmentdetail/{refno}', 'Cashier\CashierController@viewencashmentdetail');
    Route::get('reverseencashment/{refno}', 'Cashier\CashierController@reverseencashment');
    Route::get('printregistration/{idno}','Registrar\AssessmentController@printregistration');
    Route::get('printreceipt/{refno}/{idno}','Cashier\CashierController@printreceipt');
    Route::get('previous/{idno}','Cashier\CashierController@previous');
    Route::get('actualcashcheck/{batch}/{transactiondate}','Cashier\CashierController@actualcashcheck');
    Route::get('printencashment/{idno}','Cashier\CashierController@printencashment');
    Route::get('printcollection/{idno}/{transactiondate}','Cashier\CashierController@printcollection');
    Route::get('nonstudent','Cashier\CashierController@nonstudent');
    Route::post('nonstudent','Cashier\CashierController@postnonstudent');
    Route::get('checklist/{trandate}','Cashier\CashierController@checklist');
    Route::post('postactual','Cashier\CashierController@postactual');
    Route::get('printactualcash/{transactiondate}','Cashier\CashierController@printactualcash');
    Route::get('actualdeposit/{trasactiondate}', 'Cashier\CashierController@actualdeposit');
    Route::get('cutoff/{transactiondate}','Cashier\CashierController@cutoff');
    Route::get('printactualdeposit/{transactiondate}', 'Cashier\CashierController@printactualdeposit');
    Route::get('addtoaccount/{studentid}','Cashier\CashierController@addtoaccount');
    Route::post('addtoaccount','Cashier\CashierController@posttoaccount');
    Route::get('addtoaccountdelete/{id}','Cashier\CashierController@addtoaccountdelete');
     
    //accounting module
    Route::get('accounting/{idno}','Accounting\AccountingController@view');
    Route::post('debitcredit','Accounting\AccountingController@debitcredit');
    Route::get('viewdm/{refno}/{idno}','Accounting\AccountingController@viewdm');
    Route::get('printdmcm/{refno}/{idno}','Accounting\AccountingController@printdmcm');
    Route::get('dmcmreport/{transationdate}','Accounting\AccountingController@dmcmreport');
    Route::get('dmcmallreport/{transactiondate}','Accounting\AccountingController@dmcmallreport');
    Route::get('collectionreport/{datefrom}/{dateto}','Accounting\AccountingController@collectionreport');
    Route::get('printdmcmreport/{idno}/{transactiondate}','Accounting\AccountingController@printdmcmreport');
    Route::get('summarymain','Accounting\AccountingController@summarymain');
    Route::get('maincollection/{fromtran}/{totran}','Accounting\AccountingController@maincollection');
    Route::get('printmaincollection/{fromtran}/{totran}','Accounting\AccountingController@printmaincollection');
    Route::get('studentledger/{level}','Accounting\AccountingController@studentledger');
    Route::get('cashcollection/{transactiondate}','Accounting\AccountingController@cashcollection');
    Route::get('overallcollection/{transactiondate}','Accounting\AccountingController@overallcollection');
    Route::get('printactualoverall/{transactiondate}','Accounting\AccountingController@printactualoverall');
    Route::get('cashreceipts/{transactiondate}','Accounting\AccountingController@cashreceipts');
    Route::get('printcashreceipts/{transactiondate}','Accounting\AccountingController@printcashreceipts');
    Route::get('statementofaccount','Accounting\AccountingController@statementofaccount');
    Route::get('tvetsoa','Accounting\AccountingController@tvetsoa');
    Route::post('gettvetsoasummary','Accounting\AccountingController@gettvetsoasummary');
    Route::get('printtvetsoasummary/{period}/{course}/{section}/{trandate}','Accounting\AccountingController@printtvetsoasummary');
    Route::get('printtvetallsoa/{period}/{course}/{section}/{trandate}','Accounting\AccountingController@printtvetallsoa');
    
    Route::get('printsoa/{idno}/{tradate}','Accounting\AccountingController@printsoa');
     Route::get('/printallsoa/{level}/{strand}/{section}/{trandate}/{amtover}','Accounting\AccountingController@printallsoa');
    Route::get('/getsoasummary/{level}/{strand}/{section}/{trandate}/{plan}/{amtover}','Accounting\AccountingController@getsoasummary');
    //Route::get('/getsoasummary','Accounting\AccountingController@getsoasummary');
    Route::post('/getsoasummary','Accounting\AccountingController@setsoasummary');
    Route::get('/printsoasummary/{level}/{strand}/{section}/{trandate}/{amtover}','Accounting\AccountingController@printsoasummary');
    Route::get('penalties','Accounting\AccountingController@penalties');
    Route::post('postpenalties','Accounting\AccountingController@postpenalties');
    Route::post('postviewpenalty','Accounting\AccountingController@postviewpenalty');
    Route::get('subsidiary','Accounting\AccountingController@subsidiary');
    Route::post('subsidiary','Accounting\AccountingController@postsubsidiary');
    //update module
    //Elective submitted by registrar on STEM
    //Route::get('updateelective','Registrar\AssessmentController@updateelective');
    //Update grades of students
    //Route::get('updategrades','Registrar\AssessmentController@updategrades');
    //Route::get('updatemapeh','Registrar\AssessmentController@updatemapeh');
    //Route::get('updatehsconduct','Update\UpdateController@updatehsconduct');
    //Route::get('updatehsgrade','Update\UpdateController@updatehsgrade');
    //Route::get('checkno','Update\UpdateController@checkno');
    //Route::get('updatehsattendance','Update\UpdateController@updatehsattendance');
    //Route::get('updatecashdiscount','Update\UpdateController@updatecashdiscount');
    //Route::get('updateacctcode','Update\UpdateController@updateacctcode');
    Route::get('makepaymentschedule',function(){
        return view("update.makepaymentschedule");
    });
    Route::post('makepaymentschedule','Update\UpdateController@makepaymentschedule');
    //Registrar VINCENT
    Route::get('/reportcards/{level}/{section}','Vincent\GradeController@viewSectionGrade');    
    Route::get('/reportcard/{level}/{section}/{quarter}','Vincent\GradeController@viewSectionKinder');    
    Route::get('/reportcards/{level}/{shop}/{section}','Vincent\GradeController@viewSectionGrade9to10');    
    Route::get('/reportcards/{level}/{shop}/{section}/{sem}','Vincent\GradeController@viewSectionGrade11to12');
    Route::get('/resetgrades','Vincent\GradeController@reset');  
    Route::get('/studentgrade/{idno}','Vincent\GradeController@studentGrade'); 
    Route::get('sheetA','Vincent\ReportController@sheetA'); 
    Route::get('overallrank', 'Vincent\GradeController@overallRank');
    Route::post('test', 'Vincent\AttendanceController@importMonthlyAttendance');
    Route::get('test', 'Vincent\AttendanceController@index');
    Route::get('/printsheetA/{level}/{section}/{subject}', 'Vincent\ReportController@printSheetAElem');
    Route::get('/printsheetA/{level}/{strand}/{section}/{subject}/{sem}', 'Vincent\ReportController@printSheetASHS');
    
    Route::get('conduct', 'Vincent\ReportController@conduct');
    Route::get('sheetaconduct/{level}/{section}/{quarter}', 'Vincent\ReportController@printSheetAConduct');
    Route::get('sheetaAttendance/{level}/{section}/{quarter}', 'Vincent\ReportController@printSheetaAttendance');
    Route::get('attendance', 'Vincent\ReportController@attendance');
    Route::get('/sheetb', 'Vincent\ReportController@sheetB');
    
    Route::get('/sectiontvet','Vincent\SectionController@sectiontvet');
    Route::post('/changecourses/{batch}/{idno}','Vincent\TvetController@changecourses');
    
    Route::get('/finalreport','Vincent\ReportController@finalreport');
    Route::get('/prevgrade','Vincent\MigrationController@grademigration');
    
    Route::get('TOR/{idno}','Vincent\TORController@index');
    //Cashier VINCENT
    Route::get('/addbatchaccount','Vincent\CashierController@batchposting');
    Route::post('/addtobatchaccount','Vincent\CashierController@savebatchposting');
    Route::get('/searchor','Vincent\CashierController@searchor');
    Route::post('/searchor','Vincent\CashierController@findor');
    
    //Accounting VINCENT (10-13-2016)
    Route::get('/tvetledger','Vincent\TvetController@tvetledger');
    Route::get('/studentsledger/{batch}/{cours}/{section}','Vincent\TvetController@getsectionstudent');
    Route::get('/studentsledger/{batch}/{cours}/{section}/edit','Vincent\TvetController@edittvetcontribution');
    Route::post('/studentsledger/{batch}/{cours}/{section}/edit','Vincent\TvetController@savetvetChanges');
    Route::get('/addentry','Vincent\JournalController@addEntry');
    //Route::post('/addentry','Vincent\JournalController@saveEntry');
    Route::get('/listofentry','Vincent\JournalController@listofentry');
    Route::get('/accountingview/{refno}','Vincent\JournalController@accountingview');
    Route::get('/editjournalentry/{refno}','Vincent\JournalController@editjournalentry');
    //ACADEMIC VINCENT
    Route::get('/registerAdviser','Vincent\TvetController@tvetledger');
    Route::get('/enrollmentreport','Vincent\TvetController@enrollmentreport');
    Route::get('/download/{batch}','Vincent\TvetController@download');
    
    //TOOLS
    Route::get('/addoldstudent','DBFixer@addOldStudent');
    Route::get('/gensubj','DBFixer@gensubjects');
    
    
    //ACADEMIC
    Route::post('/saveentry ','GradeSubmitController@saveentry');
    Route::get('/upload/grade','GradeSubmitController@index');
    Route::post('/upload/grade','GradeSubmitController@importgrade');
    
    Route::get('trialbalance/{fromtran}/{totran}','Vincent\TrialBalanceController@viewtrilaBalance');
    
});

//Ajax route
   Route::get('/myDeposit','AjaxController@myDeposit');
    Route::get('/getid/{varid}','AjaxController@getid');
    Route::get('/getlevel/{vardepartment}','AjaxController@getlevel');
    Route::get('/gettrack/{vardepartment}','AjaxController@gettrack');
    Route::get('/getplan/{varlevelcourse}/{vardepartment}','AjaxController@getplan');
    Route::get('/gettrackplan','AjaxController@gettrackplan');
    Route::get('/getdiscount','AjaxController@getdiscount');
    Route::get('/getsearch/{varsearch}','AjaxController@getsearch');
    Route::get('/getsearchcashier/{varsearch}','AjaxController@getsearchcashier');
    Route::get('/getsearchaccounting/{varsearch}','AjaxController@getsearchaccounting');
    Route::get('/compute','AjaxController@compute');
    Route::get('/getpaymenttype/{ptype}','AjaxController@getpaymenttype');
    Route::get('/getparticular/{group}/{particular}','AjaxController@getparticular');
    Route::get('/getprevious/{idno}/{schoolyear}','AjaxController@getprevious');
    Route::get('/studentlist/{level}','AjaxController@studentlist');
    Route::get('/strand/{strand}/{level}','AjaxController@strand');
    Route::get('/removeslip/{refid}','AjaxController@removeslip');
    Route::get('/getstudentlist/{level}','AjaxController@getstudentlist');
    Route::get('/getsection/{level}','AjaxController@getsection');
    Route::get('/getsection2/{level}','AjaxController@getsection1');
    Route::get('/getsectionlist/{level}/{section}','AjaxController@getsectionlist');
    Route::get('/setsection/{id}/{section}','AjaxController@setsection');
    Route::get('/rmsection/{id}','AjaxController@rmsection');
    Route::get('/getstrand/{level}','AjaxController@getstrand');
    Route::get('/updateadviser/{id}/{value}','AjaxController@updateadviser');
    Route::get('/getsectionstrand/{level}/{strand}','AjaxController@getsectionstrand');
    Route::get('/displaygrade','AjaxController@displaygrade');
    Route::get('/gettvetplan/{batch}/{course}','AjaxController@gettvetplan');
    Route::get('/getaccountcode','AjaxController@getaccountcode');
    Route::get('/postpartialentry','AjaxController@postpartialentry');
    Route::get('/removeacctgpost','AjaxController@removeacctgpost');
    Route::get('/removeacctgall','AjaxController@removeacctgall');
    Route::get('/getpartialentry/{refno}','AjaxController@getpartialentry');
    Route::get('/postacctgremarks','AjaxController@postacctgremarks');
    
    // Route::get('/getsoasummary/{level}/{strand}/{section}/{trandate}','AjaxController@getsoasummary');
   
    //Ajax Route Sheryl
   
    //AJAX 
    Route::get('/getsubjs','Vincent\AjaxController@getsubjs');
    Route::get('/showgrades', 'Vincent\AjaxController@showgrades');
    Route::get('/showgradestvet', 'Vincent\AjaxController@showgradestvet');
    Route::get('/setacadrank', 'Vincent\AjaxController@setRankingAcad');
    Route::get('/settechrank', 'Vincent\AjaxController@setRankingTech');
    Route::get('/getsection1/{level}','Vincent\AjaxController@getsection1');
    Route::get('/getstrand/{level}','Vincent\AjaxController@getstrand');
    Route::get('/getadviser','Vincent\AjaxController@getadviser');
    Route::get('/getsectioncon/{level}','Vincent\AjaxController@getsection');
    Route::get('/getsubjects/{level}','Vincent\AjaxController@getsubjects');
    Route::get('/getdays','Vincent\AjaxController@getdos');
    Route::get('/getlist/{level}/{section}','AjaxController@studentContact');
    Route::get('showallrank/{level}','Vincent\AjaxController@viewallrank');
    Route::get('setallrank','Vincent\AjaxController@setOARank');
    Route::get('/getpreregid/{varid}','Vincent\AjaxController@getid');
    Route::get('getsearchtvet/{search}','Vincent\AjaxController@searchStudtvet');
    Route::get('/changeTotal/{total}','Vincent\AjaxController@changeTotal');
    Route::get('/changeSponsor/{total}','Vincent\AjaxController@changeSponsor');
    Route::get('/changeSubsidy/{total}','Vincent\AjaxController@changeSubsidy');
    Route::get('/gettvetstudentlist/{batch}/{strand}','Vincent\AjaxController@gettvetstudentlist');    
    Route::get('/gettvetsection/{batch}','Vincent\AjaxController@gettvetsection');
    Route::get('/gettvetsectionlist/{batch}/{section}','Vincent\AjaxController@gettvetsectionlist');
    Route::get('/gettvetledgersection/{batch}/{course}','Vincent\AjaxController@gettvetledgersection');
    Route::get('/dropStudent/{idno}','Vincent\AjaxController@dropStudent');
    Route::get('/getSubsidy/{account}','Vincent\AjaxController@getsubsidy');
    Route::get('/getaccountlevel','Vincent\AjaxController@getlevel');
    Route::get('/studentchecklist','Vincent\AjaxController@studentselect');
    Route::get('/showfinale','Vincent\AjaxController@getfinal');
    Route::get('/getcourse/{batch}/{action?}','Vincent\AjaxController@getCourse'); 
    Route::get('/gettvetsection/{action?}','Vincent\AjaxController@gettvetsections');
    
    Route::get('/getsubsidiary','Vincent\AjaxController@getsubsidiary');
    
    
    Route::get('/pullrecords','Update\UpdateController@prevgrade');
    
    
    
// Registrar Group
Route::group(['middleware' => ['web','registrar']], function () {
   Route::get('/sheetA/{record}',function($record){
       $levels = \App\CtrLevel::get();
       return view('vincent.registrar.sheetAv2',compact('levels','record'));
   });
   
});
    
   
