@extends('app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Register</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
                        {!! csrf_field() !!}

                        

                            <div class="form-group{{ $errors->has('idno') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Id No</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="idno" value="{{ old('idno') }}">

                                @if ($errors->has('idno'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('idno') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('firstname') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">First Name</label>    
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="firstname" value="{{ old('firstname') }}">

                                @if ($errors->has('firstname'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('firstname') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('middlename') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Middle Name</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="middlename" value="{{ old('middkename') }}">

                                @if ($errors->has('middlename'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('middlename') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        
                        <div class="form-group{{ $errors->has('lastname') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Last Name</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="lastname" value="{{ old('lastname') }}">

                                @if ($errors->has('lastname'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('lastname') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('extensionname') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Extension Name</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="extensionname" value="{{ old('extrensionname') }}">

                                @if ($errors->has('extensionname'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('extensionname') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        
                        
                         <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Email</label>

                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" value="{{ old('extrensionname') }}">

                                @if ($errors->has('extensionname'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                       
                        

                        <div class="form-group{{ $errors->has('accesslevel') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Access Level</label>

                            <div class="col-md-6">
                                <select class="form-control" name="accesslevel">
                                <option value = "1">Registration</option>
                                 <option value = "2">Cashier</option>
                                  <option value = "3">Cashier Head</option>
                                  <option value = "4">Accounting</option>
                                  <option value = "5">Accounting Head</option>
                                  <option value = "6">High School Department Principal</option>
                                  <option value = "7">Elementary Department  Principal</option>
                                  <option value = "8">Assistant High School Department Principal</option>
                                  <option value = "9">Assistant Elementary Department  Principal</option>
                                  <option value = "10">TVET Coordinator</option>
                                  <option value = "12">TVET Clerk</option>  
                                  
                                    </select>    

                                @if ($errors->has('accesslevel'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('accesslevel') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password">

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Confirm Password</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password_confirmation">

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-user"></i>Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection