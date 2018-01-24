<!DOCTYPE html>
<html lang="en">
    <?php $sy = App\ctrSchoolYear::first()->schoolyear;?>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
        <meta author="Roy Plomantes">
        <meta poweredby = "Nephila Web Technology, Inc">
        
        @if (Auth::guest())
        <title>Don Bosco Technical Institute of Makati, Inc.</title>
        @else
	<title>{{ Auth::user()->firstname }} {{ Auth::user()->lastname }} - Don Bosco Technical Institute</title>

	<script>
            
        
        </script>	

        @endif
        
	<link href="{{ asset('/css/app_1.css') }}" rel="stylesheet">
        <link href="{{ asset('/css/fileinput.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/datepicker.css') }}" rel="stylesheet">
        <link href="{{ asset('font-awesome/css/font-awesome.css') }}" rel="stylesheet">
        <link href="{{ asset('font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
        <link href="{{ asset('/tablesorter/themes/blue/style.css')}}">
        

        
        <style type="text/css">
            .overall tr td,.overall thead td {
               padding-left: 5px;
               padding-right: 5px;
           }
            .section {
               cursor: pointer;
           }
           .quarter{
               border-radius:0px;
           }
           
           .headers{
               display:none;
           }
           
        </style>
        <style type="text/css" media="print">
           .headers{
               display: table-row;
           }            
            .no-print, #menu{
                display: none;
            }
            #display{
                padding-left: 0px;
                padding-right:0px;
            }
            .btn{
                border-color:#fff;
            }
            *{
                font-size:12px;
            }
            span{
                font-size: 16px;
            }
            .sy{
                font-size: 16px;
            }
           
        </style>        
       
        <script src="{{asset('/js/jquery.js')}}"></script>
        <script src="{{asset('/js/bootstrap.min.js')}}"></script>
        <script src="{{asset('/js/fileinput.js')}}"></script>
        <script src="{{asset('/js/bootstrap-datepicker.js')}}"></script>
        <script src="{{asset('/tablesorter/jquery.tablesorter.js')}}"></script>
        <script src="{{asset('/chart.js/dist/Chart.js')}}"></script>
        <script src="{{asset('/chart.js/dist/Chart.min.js')}}"></script>
        
        </head>
<body> 
<div class= "container-fluid no-print" >
        <div class="col-md-12">
          <div class="col-md-1"> 
         <img class ="img-responsive" style ="margin-top:10px;" src = "{{ asset('/images/logo.png') }}" alt="Don Bosco Technical School" />
         </div>
         <div class="col-md-11" style="padding-top: 20px"><span style="font-size: 14pt; font-weight: bold;">Don Bosco Technical Institute of Makati</span><br>Chino Roces Ave., Makati City<br>Tel No : 892-01-01
         </div>   
         
            
</div>
    </div>
   
       <nav class="navbar navbar-default">
            <div class="container-fluid">
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
                        @if(!Auth::guest())
                           @if(Auth::user()->accesslevel == env('USER_ADMIN'))
                        <li><a href="{{url('/')}}" >Home</a></li>

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">Transaction Reports<span class="caret"></span></a>   
                            <ul class="dropdown-menu">
                                <li><a href="{{url('dailydisbursementalllist',array(date('Y-m-d'),date('Y-m-d')))}}">Disbursement Report</a></li>
                                <li><a href="{{url('overallcollection',date('Y-m-d'))}}">Collection Report</a></li>
                            </ul>
                        </li>
                        
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">Management Report<span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{url('deptincome',array(1,date('Y-m-d'),date('Y-m-d')))}}"> Consolidated Departmental Assets</a></li>
                                <li><a href="{{url('deptincome',array(4,date('Y-m-d'),date('Y-m-d')))}}"> Consolidated Departmental Income</a></li>
                                <li><a href="{{url('deptincome',array(5,date('Y-m-d'),date('Y-m-d')))}}"> Consolidated Departmental Expense</a></li>
                            </ul>
                        </li>
                        
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">Other Report<span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{url('bankfunds',array(date('Y-m-d'),date('Y-m-d')))}}"> Bank DB/CD</a></li>
                            </ul>
                        </li>
                        
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">Tools<span class="caret"></span></a>   
                            <ul class="dropdown-menu">
                                <li><a href="{{url('searchvoucher')}}">Search Voucher</a></li>
                                <li><a href="{{url('searchpayee')}}">Search Payee</a></li>
                            </ul>
                        </li>
                            @endif
                        @endif
                    </ul>

                    <ul class="nav navbar-nav navbar-right">
                    @if(!Auth::guest())
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}  <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                        </ul>
                    </li>
                    @endif
                    </ul>
                </div>
            </div>
	</nav>

    @yield('content')

	<!-- Scripts -->
	

<div class="container_fluid no-print">
     <div class="col-md-12"> 
<p class="text-muted"> Copyright 2016, Don Bosco Technical Institute of Makati, Inc.  All Rights Reserved.<br />
 <a href="http://www.nephilaweb.com.ph">Powered by: Nephila Web Technology, Inc.</a></p>
</div>
  </div>
</body>
</html>
