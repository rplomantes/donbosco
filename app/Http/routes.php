<?php
    Route::group(['middleware' => 'web'], function () {
    // Commit 2018-01_24 1
   
        Route::get('/test123/{date}','Accounting\IncomeStatement@index');
        Route::get('/printincomestatement/{date}','Accounting\IncomeStatement@printview');
        
        Route::get('/createSched','EntranceExam\ExamSchedule@create');
        Route::get('/applicantSched','EntranceExam\AssignExaminee@index');
        Route::post('/assignStudent','EntranceExam\AssignExaminee@save');
        
        Route::get('/applicantlist','EntranceExam\ApplicantList@index');
        Route::get('/updateapplicantlist','EntranceExam\ApplicantList@updateview');
        
        Route::get('/updateapplicantstatus','EntranceExam\ApplicantStatusController@updateStatus');
          
    //END Commit 2018-01_24 1
    Route::auth();
    
    // Commit 2018-01_24
    Route::get('/chart/{fromdate}/{todate}','Economer\OperationIncome@index');
    Route::get('/bankfunds/{fromdate}/{todate}','Admin\BankFunds@index');
    //END 2 Commit 2018-01_24
    Route::get('/gradeMigration2','Update\UpdateController@gradeMigration2');
    
    Route::get('/studentinfo/{idno}','Miscellaneous\StudentInfoController@index');
    Route::get('/disbursementtotal', 'Economer\DisbursementSummary@index');
    Route::get('/acadincome/{schoolyear}', 'Accounting\AcademicDeptincomeController@index');
    
    Route::get('/samplewidget', 'Widget\ConsolidatedReport@piechart');
    Route::get('/consolidatedbar', 'Widget\ConsolidatedReport@barchart');
    
    Route::get('/permanentrec/{idno}/{sy}', 'Registrar\PermanentRecord@index');
    Route::post('/juniorpermanentrec/{idno}', 'Registrar\PermanentRecord@viewjuniorPermanentRec');
    Route::get('/permanentrecint/{idno}/{sy}', 'Registrar\PermanentRecord@internal');
    
    Route::post('/elempermanentrec/{idno}', 'Registrar\PermanentRecord@viewelemPermanentRec');
    
    
    Route::get('/discounting', 'Update\UpdateController@updateDiscount');
    Route::get('/', 'MainController@index');
    //Book Store Module
    Route::get('books/{idno}', 'Miscellaneous\BookController@index');
    Route::get('unclaim/{idno}', 'Miscellaneous\AjaxController@unclaim');
    Route::post('/books/update', 'Miscellaneous\BookController@updatebooks');
    Route::get('/getsearchbookstore/{student}','Miscellaneous\AjaxController@getsearchbookstore');
    //Registrar module
    Route::get('sheeta','Registrar\SheetAController@index');
    
    
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
    
     
     Route::get('printreportcard','Registrar\GradeController@printreportcard');
     
     
//cashier module
    Route::get('cashier/{idno}','Cashier\CashierController@view');
    Route::post('payment','Cashier\CashierController@payment');
    Route::get('/setreceipt/{id}','Cashier\CashierController@setreceipt');
    Route::post('/setreceipt','Cashier\CashierController@setOR');
    Route::get('/viewreceipt/{refno}/{idno?}','Cashier\ReceiptController@viewreceipt');
    Route::get('otherpayment/{idno}','Cashier\CashierController@otherpayment');
    Route::post('othercollection','Cashier\CashierController@othercollection');
    Route::get('collectionreport/{transactiondate}','Cashier\CashierController@collectionreport');
    Route::get('cancell/{refno}/{idno}','Cashier\CashierController@cancell');
    Route::get('restore/{refno}/{idno}','Cashier\CashierController@restore');
    Route::get('encashment','Cashier\CashierController@encashment');
    Route::post('encashment','Cashier\CashierController@postencashment');
    Route::get('encashmentreport/{date}','Cashier\CashierController@encashmentreport');
    Route::get('viewencashmentdetail/{refno}', 'Cashier\CashierController@viewencashmentdetail');
    Route::get('reverseencashment/{refno}', 'Cashier\CashierController@reverseencashment');
    Route::get('printregistration/{idno}','Registrar\AssessmentController@printregistration');
    Route::get('printreceipt/{refno}/{idno}','Cashier\ReceiptController@printreceipt');
    Route::get('previous/{idno}','Cashier\CashierController@previous');
    Route::get('actualcashcheck/{batch}/{transactiondate}','Cashier\CashierController@actualcashcheck');
    Route::get('printencashment/{idno}/{date}','Cashier\CashierController@printencashment');
    Route::get('printcollection/{idno}/{transactiondate}','Cashier\CashierController@printcollection');
    Route::get('nonstudent','Cashier\OtherPaymentControll@nonstudent');
    Route::post('nonstudent','Cashier\OtherPaymentControll@postnonstudent');
    Route::get('checklist/{trandate}','Cashier\CashierController@checklist');
    Route::post('postactual','Cashier\CashierController@postactual');
    Route::get('printactualcash/{transactiondate}','Cashier\CashierController@printactualcash');
    Route::get('actualdeposit/{trasactiondate}', 'Cashier\CashierController@actualdeposit');
    Route::get('cutoff/{transactiondate}','Cashier\CashierController@cutoff');
    Route::get('printactualdeposit/{transactiondate}', 'Cashier\CashierController@printactualdeposit');
    Route::get('addtoaccount/{studentid}','Cashier\AddtoAccountController@addtoaccount');
    Route::post('addtoaccount','Cashier\AddtoAccountController@posttoaccount');
    Route::get('addtoaccountdelete/{id}','Cashier\AddtoAccountController@addtoaccountdelete');
     
    //accounting module
    Route::get('accounting/{idno}','Accounting\AccountingController@view');
    Route::post('debitcredit','Accounting\DebitMemoController@debitcredit');
    Route::get('viewdm/{refno}/{idno}','Accounting\DebitMemoController@viewdm');
    Route::get('printdm/{refno}','Accounting\DebitMemoController@printdm');
    Route::get('dmcmreport/{transationdate}','Accounting\DebitMemoController@dmcmreport');
    Route::get('dmcmallreport/{fromtran}/{totran}','Accounting\DebitMemoController@dmcmallreport');
    Route::get('collectionreport/{datefrom}/{dateto}','Accounting\AccountingController@collectionreport');
    Route::get('printdmcmreport/{idno}/{transactiondate}','Accounting\AccountingController@printdmcmreport');
    Route::get('summarymain/{schoolyear}','Accounting\AccountingController@summarymain');
    Route::get('maincollection/{entry}/{fromtran}/{totran}','Accounting\AccountingController@maincollection');
    Route::get('printmaincollection/{entry}/{fromtran}/{totran}','Accounting\AccountingController@printmaincollection');
    Route::get('studentledger/{level}','Accounting\AccountingController@studentledger');
    Route::get('cashcollection/{transactiondate}','Accounting\AccountingController@cashcollection');
    Route::get('overallcollection/{transactiondate}','Accounting\AccountingController@overallcollection');
    Route::get('printactualoverall/{transactiondate}','Accounting\AccountingController@printactualoverall');
    Route::get('cashreceipts/{transactiondate}','Accounting\CashReceiptController@cashreceipts');
    Route::get('printcashreceipts/{transactiondate}','Accounting\CashReceiptController@printcashreceipts');
    Route::get('statementofaccount','Accounting\AccountingController@statementofaccount');
    Route::get('tvetsoa','Accounting\TvetSoaController@tvetsoa');
    Route::post('gettvetsoasummary','Accounting\TvetSoaController@gettvetsoasummary');
    Route::get('printtvetsoasummary/{period}/{course}/{section}/{trandate}/{display}','Accounting\TvetSoaController@printtvetsoasummary');
    Route::get('printtvetallsoa/{period}/{course}/{section}/{trandate}/{display}','Accounting\TvetSoaController@printtvetallsoa');
    Route::post('studentsoa/{idno}','Accounting\AccountingController@studentsoa');
    Route::get('printsoa/{idno}/{tradate}','Accounting\AccountingController@printsoa');
    Route::get('/printallsoa/{level}/{strand}/{section}/{trandate}/{amtover}','Accounting\AccountingController@printallsoa');
    Route::get('/getsoasummary/{level}/{strand}/{section}/{trandate}/{plan}/{amtover}','Accounting\AccountingController@getsoasummary');
    //Route::get('/getsoasummary','Accounting\AccountingController@getsoasummary');
    Route::post('/getsoasummary','Accounting\AccountingController@setsoasummary');
    Route::get('/printsoasummary/{level}/{strand}/{section}/{trandate}/{amtover}','Accounting\AccountingController@printsoasummary');
    Route::get('penalties','Accounting\PenaltyController@penalties');
    Route::post('postpenalties','Accounting\PenaltyController@postpenalties');
    Route::post('postviewpenalty','Accounting\PenaltyController@postviewpenalty');
    Route::get('subsidiary','Accounting\AccountingController@subsidiary');
    Route::post('subsidiary','Accounting\AccountingController@postsubsidiary');
    Route::get('showjournallist','Accounting\JournalController@ShowJournalList');
    Route::get('printjournalvoucher/{referenceid}','Accounting\JournalController@printjournalvoucher');
    Route::get('dailyjournallist/{trandate}','Accounting\JournalController@dailyjournallist');
    Route::get('dailyalljournallist/{fromtran}/{totran}','Accounting\JournalController@dailyalljournallist');
    Route::get('printpdfjournalvoucher/{refno}','Accounting\JournalController@printpdfjournalvoucher');
    Route::get('restorecanceljournal/{kind}/{refno}','Accounting\JournalController@restorecanceljournal');
    Route::get('adddisbursement','Accounting\DisbursementController@adddisbursement');
    Route::get('printdisbursement/{refno}','Accounting\DisbursementController@printdisbursement');
    Route::get('restorecanceldisbursement/{kind}/{refno}','Accounting\DisbursementController@restorecanceldisbursement');
    Route::get('printcheckdetails/{refno}','Accounting\DisbursementController@printcheckdetails');
    Route::get('printcheckvoucher/{refno}','Accounting\DisbursementController@printcheckvoucher');
    Route::get('dailydisbursementlist/{trandate}','Accounting\DisbursementController@dailydisbursementlist');
    Route::get('dailydisbursementalllist/{fromdate}/{todate}','Accounting\DisbursementController@dailydisbursementalllist');
    Route::get('printdisbursementlistpdf/{trandate}','Accounting\DisbursementController@printdisbursementlistpdf');
    Route::get('printjournallistpdf/{trandate}','Accounting\JournalController@printjournallistpdf');
    Route::get('viewdebitmemo/{idno}','Accounting\DebitMemoController@viewdebitmemo');
    Route::get('disbursement/{trandate}','Accounting\DisbursementController@disbursement');
    Route::get('generaljournal/{trandate}','Accounting\JournalController@generaljournal');
    Route::get('restorecanceldm/{iscancel}/{refno}','Accounting\DebitMemoController@restorecanceldm');

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
    //Route::get('/updateentrytype','Update\UpdateController@updateentrytype');
    //Route::get('/updatedmtoaccounting','Update\UpdateController@updatedmtoaccounting');
    //Route::get('updatedebitmemo','Update\UpdateController@updatedebitmemo');
    Route::get('updateforwardedcdb','Update\UpdateController@updatecdb');
    Route::get('updatecdbdepartment','Update\UpdateController@updatecdbdepartment');
    Route::get('updatecdbaccountname','Update\UpdateController@updatecdbaccountname');
    Route::get('updatecdbmain','Update\UpdateController@updatecdbmain');
    Route::get('updatecdbaccounting','Update\UpdateController@updatecdbaccounting');
    Route::get('updatecdbdrcr','Update\UpdateController@updatecdbdrcr');
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
    Route::get('/addbatchaccount','Cashier\AddtoBatchController@batchposting');
    Route::post('/addtobatchaccount','Cashier\AddtoBatchController@savebatchposting');
    Route::get('/searchor','Vincent\CashierController@searchor');
    Route::post('/searchor','Vincent\CashierController@findor');

    Route::get('/viewnonstudent/{id}','Cashier\SearchNonSutdentController@viewtransactions');
    Route::get('/nonstudents','Cashier\SearchNonSutdentController@search');
    
    //Accounting VINCENT (10-13-2016)
    Route::get('/disbursementreport/{voucherno?}', 'Miscellaneous\DisburstmentReportController@index');
    Route::get('checksummary/{from}/{to}','Accounting\DisbursementController@checkSummary');
    Route::get('printchecksummary/{from}/{to}','Accounting\DisbursementController@printcheckSummary');

    Route::get('deptincome/{account}/{fromtran}/{totran}', 'Accounting\DeptIncomeController@index');
    Route::get('printconsolidate/{account}/{fromtran}/{totran}', 'Accounting\DeptIncomeController@printreport');

    Route::get('/departmentalsummary/{fromdate}/{todate}/{dept}/{acctcode}', 'Accounting\OfficeSumController@index');
    Route::get('/printdepartmentalsummary/{fromdate}/{todate}/{dept}/{acctcode}', 'Accounting\OfficeSumController@printOfficeSum');

    Route::get('/individualsummary/{fromdate}/{todate}', 'Accounting\AccountSummaryController@index');
    Route::get('/printindividualsummary/{fromdate}/{todate}/{account}', 'Accounting\AccountSummaryController@printaccountSummary');

    Route::get('/subaccountsummary/{fromdate}/{todate}', 'Accounting\SubAccountSummarryController@index');
    Route::get('/printsubaccountsummary/{from}/{to}/{account}', 'Accounting\SubAccountSummarryController@printAccount');

    Route::get('/tvetledger','Vincent\TvetController@tvetledger');
    Route::get('/studentsledger/{batch}/{cours}/{section}','Vincent\TvetController@getsectionstudent');
    Route::get('/studentsledger/{batch}/{cours}/{section}/edit','Vincent\TvetController@edittvetcontribution');
    Route::post('/studentsledger/{batch}/{cours}/{section}/edit','Vincent\TvetController@savetvetChanges');
    Route::get('/addentry','Accounting\JournalController@addEntry');
    //Route::post('/addentry','Vincent\JournalController@saveEntry');
    Route::get('/listofentry','Vincent\JournalController@listofentry');
    Route::get('/accountingview/{refno}','Vincent\JournalController@accountingview');
    Route::get('/editjournalentry/{refno}','Vincent\JournalController@editjournalentry');

    Route::get('/searchvoucher','Accounting\DisbursementController@searchvoucher');
    Route::post('/searchvoucher','Accounting\DisbursementController@findvoucher');

    Route::get('/searchpayee','Accounting\DisbursementController@searchpayee');
    Route::post('/searchPayee','Accounting\DisbursementController@findpayee');
    //ACADEMIC VINCENT
    Route::get('/registerAdviser','Vincent\TvetController@tvetledger');
    Route::get('/enrollmentreport','Vincent\TvetController@enrollmentreport');
    Route::get('/download/{batch}','Vincent\TvetController@download');
    
    //TOOLS
    Route::get('/addoldstudent','DBFixer@addOldStudent');
    Route::get('/fixdiscount/{refno}','DBFixer@fixdiscount');
    Route::get('/fulldiscount/{refno}','DBFixer@fullDiscount');
    Route::get('/gensubj','DBFixer@gensubjects');
    Route::get('/updateentrytype','DBFixer@updateentrytype');
    Route::get('/updatetvet','DBFixer@updatetvet');
    Route::get('/updateacct','DBFixer@fixYouthAssistance');
    Route::get('/updaterank/{level}/{sy}/{course}/{quarter}','Registrar\Ranking\OverallRanking@setOARankingAcad');
    Route::get('/updateunearned','DBFixer@updateunearned');
    
    
    
    //ACADEMIC
    Route::post('/saveentry ','GradeSubmitController@saveentry');
    Route::get('/upload/grade','GradeSubmitController@index');
    Route::post('/upload/grade','GradeSubmitController@importgrade');
    
    Route::get('trialbalance/{fromtran}/{totran}','Accounting\TrialBalanceController@viewtrilaBalance');
    Route::get('printtrialbalance/{fromtran}/{totran}','Accounting\TrialBalanceController@printtrilaBalance');
    Route::get('downloadtrialbal/{fromtran}/{totran}','Accounting\TrialBalanceController@download');
    Route::get('generalledger/{basic}/{title}/{fromdate}/{todate}','Accounting\GenLedgerController@index');
    Route::get('generalledger/print/{basic}/{title}/{fromdate}/{todate}','Accounting\GenLedgerController@printledger');
    Route::get('balancesheet','Vincent\BalanceSheetController@index');
    Route::get('dmreport/{trandate}','Accounting\DMReportController@index');
    
    Route::get('dmsummary/{trandate}','Accounting\DebitDCSummaryController@index');
    Route::get('printdmjournal/{fromtran}/{totran}','Accounting\DebitDCSummaryController@printsummary');
    // Commit 2018-01_24 3
    
    Route::get('ledger/{idno}','Admin\StudentLedger@index');
    //END Commit 2018-01_24 3
    Route::get('/finalsheetb/{quarter}/{level}/{section}/{strand?}','Registrar\SheetBController@finalSheetB');
    
    Route::get('updatecdb2','Update\UpdateController@updatecdb2');
});

//Ajax route
    Route::get('/getsections/{action?}','Vincent\AjaxController@getsections');
    Route::get('/getlevelsections/{all}/{action?}','Vincent\AjaxController@getlevelsections');
    Route::get('/getlevelstrands/{action?}','Vincent\AjaxController@getlevelstrands');
    Route::get('/getlevelsubjects/{action?}','Registrar\SheetA\Helper@getSubjects');
    Route::get('/getlevelquarter/{action?}','Registrar\SheetA\Helper@getQuarter');

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
    Route::get('/getsearchadmin/{varsearch}','AjaxController@getsearchaccounting');
    Route::get('/compute','AjaxController@compute');
    Route::get('/getpaymenttype/{ptype}','AjaxController@getpaymenttype');
    Route::get('/getparticular/{group}/{particular}','AjaxController@getparticular');
    Route::get('/getaccount/{group}','Cashier\AjaxController@getaccount');
    Route::get('/getaccount2/{group}','Cashier\AjaxController@getaccount2');
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
    
    Route::get('/gettvetplan/{batch}/{course}','AjaxController@gettvetplan');
    Route::get('/getaccountcode','AjaxController@getaccountcode');
    Route::get('/postpartialentry','AjaxController@postpartialentry');
    Route::get('/removeacctgpost','AjaxController@removeacctgpost');
    Route::get('/removeacctgall','AjaxController@removeacctgall');
    Route::get('/getpartialentry/{refno}','AjaxController@getpartialentry');
    Route::get('/postacctgremarks','AjaxController@postacctgremarks');
    Route::get('/getjournallist','AjaxController@getjournallist');
    Route::get('/processdisbursement','AjaxController@processdisbursement');
    // Route::get('/getsoasummary/{level}/{strand}/{section}/{trandate}','AjaxController@getsoasummary');
   
    //Ajax Route Sheryl
   
    //AJAX 
    Route::get('/getparticulars/{group}','Cashier\AjaxController@getparticular');
    Route::get('/searchnonstudent','Cashier\AjaxController@searchnonstudent');
    
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
    Route::get('/studentslist/{level}/{sy}', 'Registrar\AjaxController@levelStudent');
    
    Route::get('/setoverallrank', 'Registrar\OverallRankController@setOARank');  
    
    //Elective
    Route::get('/strandStudent/{level}', 'Registrar\AjaxController@strandStudent');
    Route::get('/getelectives/{action}', 'Registrar\AjaxController@getelectives');
    Route::get('/getelectivesection/{action}', 'Registrar\AjaxController@getelectivesection');
    Route::get('/electiveadviser', 'Registrar\AjaxController@electiveadviser');
    Route::get('/sectionelectivelist', 'Registrar\AjaxController@electiveStudent');
    
    
    
    Route::get('/addtoelesection', 'Registrar\AjaxController@addtoelesection');
    Route::get('/removetoelesection', 'Registrar\AjaxController@removetoelesection');
    
    Route::get('/sheetAelectivesection/{action?}', 'Registrar\AjaxController@electivesheetAsection');
    Route::get('/sheetAelectivelist', 'Registrar\AjaxController@sheetAelectivelist');
    
    
    

    Route::get('/getindividualaccount/{access}', 'Accounting\AjaxController@individualAccount');
    Route::get('/getsubaccountsum', 'Accounting\AjaxController@subAccountSummary');
    
    
    
    Route::get('/studentslist/{level}/{sy}', 'Registrar\AjaxController@levelStudent');
    Route::get('/getsectionstudents', 'Registrar\AjaxController@getsectionstudents');
    Route::get('/displaygrade','AjaxController@displaygrade');
    
    
// Registrar Group
Route::group(['middleware' => ['web','registrar']], function () {
    
    Route::get('/kto12sectioning/{sy}', 'Registrar\SectionController@sectioning');
    Route::get('/classno', 'Registrar\SectionController@assignClassNo');
    Route::get('/overallranking/{sy}', 'Registrar\OverallRankController@index');
    Route::get('/autosection/{level}/{strand?}', 'Registrar\AjaxController@autoSectioning');
    Route::get('/classno', 'Registrar\SectionController@assignClassNo');
    Route::get('/card/{idno}/{sy}/{sem?}', 'Registrar\ReportCardController@studentReport');
    Route::get('/seegrade/{idno}','Registrar\GradeController@seegrade');

    Route::get('/createrec/{idno}','Registrar\MakeRecord@createRecord');
    Route::post('/createrec','Registrar\MakeRecord@saveRecord');
    
    Route::get('/electivesection','Registrar\Elective\SectionController@electiveSection');
    Route::get('/printelectivesection/{section}','Registrar\Elective\SectionController@printSection');
    Route::get('/downloadelectiveection/{sectionid}', 'Registrar\Elective\SectionController@downloadSection');
    
    Route::get('/gradesheeta/{selectedSY}','Registrar\SheetA\Grade@index');
    Route::get('/attendancesheeta/{selectedSY}','Registrar\SheetA\Attendance@index');
    Route::get('/printgradesheeta/{sy}/{level}/{semester}/{section}/{subject}','Registrar\SheetA\Grade@printSheetA')->where('subject', '(.*)');
    Route::get('/printattendancesheeta/{sy}/{level}/{course}/{section}/{semester}/{qtr}','Registrar\SheetA\Attendance@printSheetA');
    
    Route::get('/sheetB/{selectedSY}','Registrar\SheetBController@index');
    Route::get('/printsheetb/{sy}/{level}/{strand}/{section}/{sem}/{quarter}','Registrar\SheetBController@printSheetBList');
    
    Route::get('/overallRank/{selectedSY}','Registrar\Ranking\OverallRanking@index');
    
    Route::get('/electivesheeta/{selectedSY}','Registrar\Elective\SheetAController@index');
    Route::get('/printelectivesheeta/{section}','Registrar\Elective\SheetAController@printElective');
    
    Route::get('/reportcards','Registrar\ReportCards\ReportCardController@sectionCards');
    Route::get('/printcards/{lvl}/{strnd}/{sec}/{quarter}/{sem}','Registrar\ReportCards\ReportCardController@printSectionCards');
    
    Route::get('/promotion/{sy}','Registrar\PromotionController@index');
    Route::get('/editpromotion/{sy}/{level}','Registrar\PromotionController@editpromotion');
    Route::get('/printpromotion/{sy}/{level}','Registrar\PromotionController@printpromotion');
    Route::post('/savepromotion/{sy}/{level}','Registrar\PromotionController@savepromotion');
    
    Route::get('/changegrade/{idno}/{sy}','Registrar\Grade\ChangeGrade@index');
    
    
   Route::get('/sheetA/{record}',function($record){
       $levels = \App\CtrLevel::get();
       return view('vincent.registrar.sheetAv2',compact('levels','record'));
   
    });
    
    Route::get('/getUnearned','Update\UpdateController@getUnearned');
    Route::get('/fixDepartment','Update\UpdateController@fixDepartment');
    Route::get('/fixDepartmentCredit','Update\UpdateController@fixDepartmentCredit');
    Route::get('/givePass','Update\UpdateController@givePass');
    
    //Route::get('/getfiscalyear/{date}','Accounting\Helper@getfiscalyear');
    
    
    
});
   Route::get('/getstudent/{accesslevel}/{search}','Miscellaneous\AjaxController@findstudent');
   Route::get('setoverallrank/{section?}','Registrar\Ranking\RankController@setRank');
   
   
   Route::get('/viewpromotion/{sy}/{level}','Registrar\PromotionController@viewreport');
    
   Route::get('/gradeSheetAList','Registrar\SheetA\Helper@gradeSheetAList');
   Route::get('/updatesubjectteacher','Registrar\SheetA\Helper@updatesubjectteacher');
   Route::get('/gradeSheetBList','Registrar\SheetBController@gradeSheetBList');
   Route::get('/overallRankList', 'Registrar\Ranking\OverallRanking@getOARanking');
   Route::get('/getGradeForm/{subjecttype}', 'Registrar\Grade\ChangeGrade@getForm');
   Route::get('/updateElemGrade', 'Registrar\Grade\ChangeGrade@updateGrade');
   
   //Entrance Exam
   Route::get('/addschedule/{level}', 'EntranceExam\Helper@createSched');
   Route::get('/removeschedule/{id}', 'EntranceExam\Helper@deleteSched');
   Route::get('/updatesched', 'EntranceExam\Helper@updateSched');
   Route::get('/getschedule/{level}', 'EntranceExam\Helper@getLevelSchedule');
   Route::get('/changestudentstat', 'EntranceExam\ApplicantStatusController@changeStatus');
//Entrance Exam
   
   //TVET Cards//
   Route::group(['middleware' => 'web'], function () {
       Route::get('/reportcards/tvet','Registrar\ReportCards\TVETCards@view')->name('tvetcards');
       Route::get('/tvet/{batch}/{idno}/{sem}','Registrar\ReportCards\TVETCards@TVETStudentCard')->name('individual_TvetCard');
   });
       Route::get('/option_tvetsection','Registrar\ReportCards\TVETCards@get_section')->name('option_tvetsection');
       Route::get('/classList_tvetsection','Registrar\ReportCards\TVETCards@get_classList')->name('classList_tvetsection');
   
   //END TVET Cards//
       
   //Import Conduct//
   Route::group(['middleware' => 'web'], function () {
       Route::get('/importconduct','Registrar\Upload\Conducts@index');
       Route::post('/uploadconductecr','Registrar\Upload\Conducts@postconducts');
       Route::post('/saveconductupload','Registrar\Upload\Conducts@saveConduct');
       
   });
   
   //END Import Conduct//
   
   
   //Pre-registration
   Route::group(['middleware' => 'web'], function () {
       Route::get('/preregister','Registrar\PreRegistration\PreregistrationController@preregForm');

       
   });
   //END Pre-registration
   
   
   //Disbursement
   Route::group(['middleware' => 'web'], function () {
       Route::get('/editdisbursement','Accounting\DisbursementController@editDisbursement');
       
   });
   //END Disbursement
   
   //Disbursement Books
    Route::group(['middleware' => 'web'], function () {
        Route::get('disbursementbook/{from}/{trandate}','Accounting\DisbursementController@disbursementbook');
        Route::get('printdisbursementpdf/{from}/{trandate}','Accounting\DisbursementController@printdisbursementpdf');
        
        Route::get('dldisbursementbook/{from}/{trandate}','Accounting\DisbursementController@disbursementbookexcel');
    });
   //END Disbursement Books
   
   //Cash Receipt Books
    Route::group(['middleware' => 'web'], function () {
        Route::get('cashreceipt/{transactiondate}','Accounting\CashReceiptController@cashreceiptbook');
        Route::get('printcashreceipt/{transactiondate}','Accounting\CashReceiptController@cashreceiptpdf');
    //    Route::get('printcashbreakdown/{fromtran}/{totran}','Accounting\CashReceiptController@breakdownpdf');
        
        Route::get('dlcashreceipt/{transactiondate}','Accounting\CashReceiptController@cashreceiptexcel');
    });
   //END Cash Receipt Books

    
   //Elem Attedance Fixer
   Route::group(['middleware' => 'web'], function () {
       Route::get('/attendanceFix','Vincent\AttendanceFixer@index')->name('attFixer');
       Route::post('/submitAttFix','Vincent\AttendanceFixer@fetchFromExcel');

       
   });
   //END Elem Attedance Fixer