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



        @endif
        
	<link href="{{ asset('/css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('/css/donbosco.css') }}" rel="stylesheet">
        <style type="text/css">
            .section {
               cursor: pointer;
           }
           .quarter{
               border-radius:0px;
           }
        </style>
        <style type="text/css" media="print">
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
        
        <script src="{{asset('/js/jquery.min.js')}}"></script>
        <script src="{{asset('/js/bootstrap.min.js')}}"></script>
        
        </head>
<body> 
<div class= "container-fluid no-print">
        <div class="col-md-12">
         <img class ="img-responsive" style ="margin-top:10px;" src = "{{ asset('/images/logo.png') }}" alt="Don Bosco Technical School" />
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
                                <li> <a href="{{url('/')}}" >Home</a>
                                </li>
                                
                                
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">Grading Sheets
                                    <span class="caret"></span></a>
                               
                                    <ul class="dropdown-menu" role="menu">
                           
                                        <li><a href="{{url('sheeta')}}"><i class="fa fa-btn fa-sign-out"></i>Sheet A </a></li>
                                         <li><a href="{{url('conduct')}}"><i class="fa fa-btn fa-sign-out"></i>Conduct</a></li>
                                          <li><a href="{{url('attendance')}}"><i class="fa fa-btn fa-sign-out"></i>Attendance</a></li>
                                           <li><a href="{{url('sheetb')}}"><i class="fa fa-btn fa-sign-out"></i>Sheet B</a></li>
                                    </ul>
                                </li>
                                        
                            </ul>

                            <ul class="nav navbar-nav navbar-right">
		   @if (!Auth::guest())
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
