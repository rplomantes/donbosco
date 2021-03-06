<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
        <meta author="Roy Plomantes">
        <meta poweredby = "Nephila Web Technology, Inc">
        <style type="text/css" media="print">
            .top_head{display:none}
        </style>    
        @if (Auth::guest())
        <title>Don Bosco Technical Institute of Makati, Inc.</title>
        @else
	<title>{{ Auth::user()->firstname }} {{ Auth::user()->lastname }} - Don Bosco Technical Institute</title>
        @endif
        
	<link href="{{ asset('/css/app_1.css') }}" rel="stylesheet">
        <link href="{{ asset('/css/fileinput.css') }}" rel="stylesheet">
        
        <link href="{{ asset('/css/jquery-ui.css') }}" rel="stylesheet">
        
        <link href="{{ asset('font-awesome/css/font-awesome.css') }}" rel="stylesheet">
        <link href="{{ asset('font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">        
        
        <link href="{{ asset('jquery.ui.combify/jquery.ui.combify.css') }}" rel="stylesheet">        
        <link href="{{ asset('/css/datepicker.css') }}" rel="stylesheet">
        
        <!--Script-->
        <script src="{{asset('/js/jquery.js')}}"></script>
        <script src="{{asset('/js/jquery.min.js')}}"></script>
        <script src="{{asset('/js/bootstrap.min.js')}}"></script>
        <script src="{{asset('/js/jquery-ui.js')}}"></script>
        <script src="{{asset('/js/fileinput.js')}}"></script>
        <script src="{{asset('jquery.ui.combify/jquery.ui.combify.js')}}"></script>
        
        <link href="{{ asset('js/TableMultiFreezer/multifreezer.css') }}" rel="stylesheet">
        <script src="{{asset('js/TableMultiFreezer/multifreezer.js')}}"></script>
        </head>
<body> 
    
<div class= "container-fluid top_head">
         <div class="col-md-12">
          <div class="col-md-1"> 
         <img class ="img-responsive" style ="margin-top:10px;" src = "{{ asset('/images/logo.png') }}" alt="Don Bosco Technical School" />
         </div>
            <div class="col-md-11" style="padding-top: 20px"><span style="font-size: 14pt; font-weight: bold;">Don Bosco Technical School of Makati</span><br>Chino Roces Ave., Makati City<br>Tel No : 892-01-01
         </div>   
    </div>
</div>
<nav class="navbar navbar-default">
   
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
                                <li><a href="#">DBTI - Makati School Information System</a></li>
                                <li><a href="/">Home</a></li>
                                 @if(Auth::guest())
                                 @else
                                    @if(Auth::user()->accesslevel == env('USER_ACCOUNTING') || Auth::user()->accesslevel == env('USER_ACCOUNTING_HEAD'))
                                        <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">Accounting Entries
                                        <span class="caret"></span></a>
                                         <ul class="dropdown-menu" role="menu">
                                             <li>
                                            <a href="{{url('adddisbursement')}}"><i class="fa fa-btn"></i>Disbursement</a>
                                            <a href="{{url('dailydisbursementlist',date('Y-m-d',strtotime(\Carbon\Carbon::now())))}}"><i class="fa fa-btn"></i>Disbursement Daily Summary</a>
                                            <a href="{{url('addentry')}}"><i class="fa fa-btn"></i>Journal Entry</a>
                                            <a href="{{url('dailyjournallist',date('Y-m-d',strtotime(\Carbon\Carbon::now())))}}"><i class="fa fa-btn"></i>Journal Entry Daily Summary</a>
                                            <a href="{{url('showjournallist')}}"><i class="fa fa-btn"></i>Search Journal Entries</a>
                                            <a href="{{url('/')}}"><i class="fa fa-btn"></i>Debit Memo</a>
                                            <a href="{{url('dmcmreport',date('Y-m-d'))}}"><i class="fa fa-btn fa-sign-out"></i>Debit Memo Daily Summary</a>
                                            <hr/>
                                            <a href="#"><i class="fa fa-btn"></i>Chart of Accounts</a>
                                            </li>
                                         </ul>    
                                        </li>    
                                        @if(Auth::user()->accesslevel == env('USER_ACCOUNTING'))
						 <li class="dropdown">
						   <a href ="{{url('disbursementreport')}}"><i class="fa fa-btn"></i>Disbursement Accounts</a>
						</li>
						<li class="dropdown">
 						   <a href="{{url('searchvoucher')}}"><i class="fa fa-btn fa-sign-out"></i>Search Voucher</a></li>
						</li>
						<li class="dropdown">
 						   <a href="{{url('searchpayee')}}"><i class="fa fa-btn fa-sign-out"></i>Search Payee</a></li>
						</li>
                                                <li class="dropdown">
                                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">Others
                                                    <span class="caret"></span></a>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li>    
                                                            <a href ="{{route('ias')}}"><i class="fa fa-btn"></i>Individual Account Summary</a>
                                                        </li>  
                                                    </ul>
                                                </li>
					@endif
                                        @if(Auth::user()->accesslevel == env('USER_ACCOUNTING_HEAD'))
                                         <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">Book of Accounts
                                        <span class="caret"></span></a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li>
                                                <a href ="{{url('disbursementbook',array(date('Y-m-d'),date('Y-m-d')))}}"><i class="fa fa-btn"></i>Cash Disbursement</a>
                                                <a href="{{url('maincollection/4',array(date('Y-m-d'),date('Y-m-d')))}}"><i class="fa fa-btn"></i>Cash Disbursement Debit/Credit Summary </a>
                                                <a href ="{{url('cashreceipt', date('Y-m-d'))}}"><i class="fa fa-btn"></i>Cash Receipts</a>
                                                <a href="{{url('maincollection/1',array(date('Y-m-d'),date('Y-m-d')))}}"><i class="fa fa-btn"></i>Cash Receipt Debit/Credit Summary</a>
                                                <a href ="#"><i class="fa fa-btn"></i>General Journal</a>
                                                 <a href="{{url('maincollection/3',array(date('Y-m-d'),date('Y-m-d')))}}"><i class="fa fa-btn"></i>General Journal Debit/Credit Summary </a>
                                                <a href ="{{url(('dmreport'),date('Y-m-d'))}}"><i class="fa fa-btn"></i>Debit Memo Journal</a>
                                                <a href="{{url('maincollection/2',array(date('Y-m-d'),date('Y-m-d')))}}"><i class="fa fa-btn"></i>Debit Memo  Debit/Credit Summary </a>
                                            </li>  
                                        </ul></li>
                                        
                                        <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">Ledger Reports
                                        <span class="caret"></span></a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li>
                                                <a href ="{{url('generalledger/0/0',array(date('Y-m-d'),date('Y-m-d')))}}"><i class="fa fa-btn"></i>General Ledger</a>
                                                <a href ="{{url('trialbalance',array(date('Y-m-d'),date('Y-m-d')))}}"><i class="fa fa-btn"></i>Trial Balance</a>
                                                
                                            </li>  
                                        </ul></li>
                                        
                                        <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">Financial Statement
                                        <span class="caret"></span></a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li>
                                               
                                                <a href ="#"><i class="fa fa-btn"></i>Statement of Income</a>
                                                <a href ="#"><i class="fa fa-btn"></i>Statement of Financial Position</a>
                                                <a href ="#"><i class="fa fa-btn"></i>Retained Earnings</a>
                                                <a href ="#"><i class="fa fa-btn"></i>Statement of Cash Flows</a>
                                            </li>  
                                        </ul></li>
                                        
                                       
                                        <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">Management Reports
                                        <span class="caret"></span></a>
                               
                                        <ul class="dropdown-menu" role="menu">
                                       
                                       @if(\Auth::user()->accesslevel == env('USER_ACCOUNTING_HEAD'))
                                       
                                       <li>
                                           <a href="{{url('summarymain',\App\CtrSchoolYear::first()->schoolyear)}}"><i class="fa fa-btn"></i>Account Summary</a>
                                           <a href="{{url('studentledger','all')}}"><i class="fa fa-btn"></i>Student Ledger Summary</a>
                                           
                                           <a href="{{url('cashcollection',date('Y-m-d'))}}"><i class="fa fa-btn"></i>Actual Deposit</a>
                                           
                                           
                                          
                                       </li>  
                                       <li><a href="{{url('statementofaccount')}}"><i class="fa fa-btn fa-sign-out"></i>Statement of Account</a></li>
                                       <li><hr style="margin-top: 1px;margin-bottom: 1px;"></li>
                                       <li><a href="{{url('deptincome',array(1,date('Y-m-d'),date('Y-m-d')))}}"><i class="fa fa-btn fa-sign-out"></i>Consolidated Departmental Assets</a></li>
                                       <li><a href="{{url('deptincome',array(4,date('Y-m-d'),date('Y-m-d')))}}"><i class="fa fa-btn fa-sign-out"></i>Consolidated Departmental Income</a></li>
                                       <li><a href="{{url('deptincome',array(5,date('Y-m-d'),date('Y-m-d')))}}"><i class="fa fa-btn fa-sign-out"></i>Consolidated Departmental Expense</a></li>
		                       <li><hr style="margin-top: 1px;margin-bottom: 1px;"></li>
                                       <li><a href="{{url('departmentalsummary',array(date('Y-m-d'),date('Y-m-d'),'Student Services',1))}}"><i class="fa fa-btn fa-sign-out"></i>Departmental Assets</a></li>
		                       <li><a href="{{url('departmentalsummary',array(date('Y-m-d'),date('Y-m-d'),'Student Services',4))}}"><i class="fa fa-btn fa-sign-out"></i>Departmental Income</a></li>
		                       <li><a href="{{url('departmentalsummary',array(date('Y-m-d'),date('Y-m-d'),'Student Services',5))}}"><i class="fa fa-btn fa-sign-out"></i>Departmental Expense</a></li>
                                        @endif
                                        
                                        </ul>
                                         </li>
                                         
                                         <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">Other Features
                                        <span class="caret"></span></a>
                               
                                        <ul class="dropdown-menu" role="menu">
                                       <li>
                                        <a href="{{url('penalties')}}"><i class="fa fa-btn"></i>Over Due Charges </a>
                                        <a href="{{url('searchor')}}"><i class="fa fa-btn fa-sign-out"></i>Search OR</a>
					<a href="{{url('searchvoucher')}}"><i class="fa fa-btn fa-sign-out"></i>Search Voucher</a>
 				        <a href="{{url('searchpayee')}}"><i class="fa fa-btn fa-sign-out"></i>Search Payee</a>
				       </li>
                                        <hr>
                                        <li>
                                        <a href="{{route('indexbreakdown')}}"><i class="fa fa-btn"></i>Assessment Schedule Generate</a>
                                        </li>
                                       <hr>
                                       <li>
                                           <a href="{{url('overallcollection',date('Y-m-d'))}}"><i class="fa fa-btn"></i>Collection Report</a>
                                           <a href="{{url('disbursement', date('Y-m-d'))}}"><i class="fa fa-btn"></i>Disbursement Details</a>
                                           <a href="{{url('dailydisbursementalllist',array(date('Y-m-d'),date('Y-m-d')))}}"><i class="fa fa-btn"></i>Disbursement Summary</a>
                                           <a href="{{url('dmsummary',date('Y-m-d'))}}"><i class="fa fa-btn"></i>Debit Memo Details</a>
                                           <a href="{{url('dmcmallreport',array(date('Y-m-d'),date('Y-m-d') ))}}"><i class="fa fa-btn"></i>Debit Memo Summary</a>
                                           <a href="{{url('generaljournal',date('Y-m-d'))}}"><i class="fa fa-btn"></i>Journal Entry Details</a>
                                           <a href ="{{route('ias')}}"><i class="fa fa-btn"></i>Individual Account Summary</a>

                                           <a href="{{url('dailyalljournallist',array(date('Y-m-d'),date('Y-m-d')))}}"><i class="fa fa-btn"></i>Journal Entry List</a>
                                           <a href ="{{url('checksummary',array(date('Y-m-d'),date('Y-m-d')))}}"><i class="fa fa-btn"></i>Disbursement Check Summary</a>
                                       </li>
                                        </ul>
                                         </li>
                                         
                                        @endif
                                       
                                        
                                    @endif
                                 @endif
                                 
                                 @if(Auth::guest())
                                 @else
                                 @endif
				</ul>

                            <ul class="nav navbar-nav navbar-right">
		   @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">Login</a></li>
                        
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}  <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/logout') }}">Logout</a></li>
                            </ul>
                        </li>
                    @endif
				</ul>
			</div>
                
  </nav>

    @yield('content')

	<!-- Scripts -->
	

<div class="container_fluid">
    <div class="col-md-12">    
<p class="text-muted"> Copyright 2016, Don Bosco Technical Institute of Makati, Inc.  All Rights Reserved.<br />
 <a href="http://www.nephilaweb.com.ph">Powered by: Nephila Web Technology, Inc.</a></p>
</div>
</div>

<script src="{{url('/Inputmask-4.x/dist/jquery.inputmask.bundle.js')}}"></script>
<script>
    $('.divide').inputmask("numeric", {
    radixPoint: ".",
    groupSeparator: ",",
    digits: 2,
    autoGroup: true,
    rightAlign: true,
    oncleared: function (){ self.Value(''); },
    autoUnmask : true,
    removeMaskOnSubmit  :   true
});
</script>     
</body>
</html>


