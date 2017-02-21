<!DOCTYPE html>
<html lang="en">
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
               visibility:collapse;
           }
           
        </style>
        <style type="text/css" media="print">
           .headers{
               visibility: visible;
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
	<!-- Fonts 
	<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>
 -->
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
       
        <script src="{{asset('/js/jquery.js')}}"></script>
        <script src="{{asset('/js/bootstrap.min.js')}}"></script>
        <script src="{{asset('/js/fileinput.js')}}"></script>
        <script src="{{asset('/js/bootstrap-datepicker.js')}}"></script>
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
                                 @if(Auth::guest())
                                 @else
                                    @if(Auth::user()->accesslevel == env('USER_REGISTRAR'))
                                    
                                    
                                        <li>
                                        <a href="{{url('/')}}" >Home</a>
                                        
                                        <?php $prereg = \App\RegistrarSchoolyear::first();?>
                                        </li>
                                        
                                         <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">Assessment/Registration
                                        <span class="caret"></span></a>
                               
                                        <ul class="dropdown-menu" role="menu">
                           
                                        <li><a href="{{url('/')}}"><i class="fa fa-btn fa-sign-out"></i>Assessment</a></li>
                                        <li><a href="{{url('studentinfokto12')}}"><i class="fa fa-btn fa-sign-out"></i>Pre-Register ({{$prereg->schoolyear}} Series)</a></li>
                                        <!--li><a href="{{url('studentregister')}}"><i class="fa fa-btn fa-sign-out"></i>Register</a></li-->
                                        
                                        </ul>
                                        </li>
                                        
                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"> Reports
                                        <span class="caret"></span></a>   
                                        <ul class="dropdown-menu">
                                            <li><a href="{{url('enrollmentstat')}}"><i class="fa fa-btn"></i>Enrollment Statistics</a></li>
                                            <li><a href="{{url('studentlist')}}"><i class="fa fa-btn"></i>Student Contact</a></li>
                                            <li><a href="{{url('importGrade')}}"><i class="fa fa-btn"></i>Import Grades</a></li>
                                            <li><a href="{{url('printreportcard')}}"><i class="fa fa-btn"></i>Report Card</a></li>
                                            <li><hr></li>
                                            <li><a href="{{url('sheetb')}}"><i class="fa fa-btn"></i>Sheet B</a></li>
                                            <li><a href="{{url('sheetA')}}"><i class="fa fa-btn"></i>Sheet A Subjects</a></li>
                                            <li><a href="{{url('conduct')}}"><i class="fa fa-btn"></i>Sheet A Conducts</a></li>
                                            <li><a href="{{url('attendance')}}"><i class="fa fa-btn"></i>Sheet A Attendance</a></li>
                                            <li><hr></li>
                                            <li><a href="{{url('overallrank')}}"><i class="fa fa-btn"></i>Overall Ranking</a></li>
                                            <li><a href="{{url('finalreport')}}"><i class="fa fa-btn"></i>Final Report</a></li>
                                         </ul>   
                                        
                                        <li>    
                                    @endif
                                 @endif
                                 
                                 @if(Auth::guest())
                                 @else
                                 @if(Auth::user()->accesslevel == env('USER_REGISTRAR'))
                                <li class="dropdown">
                                    <a href="#"  class="dropdown-toggle" data-toggle="dropdown" role="button"> Sectioning
                                    <span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="/sectionk"><i class="fa fa-btn"></i>K-12</a></li>
                                        <li><a href="/sectiontvet"><i class="fa fa-btn"></i>TVET</a></li>
                                     </ul>   
                                
                                </li>
                                 @endif
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
