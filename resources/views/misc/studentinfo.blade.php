@extends('appmisc')
@section('content')
    <div class="conatainer">
        <div class="col-md-offset-3 col-md-6">
            <h4>{{$users->lastname}}, {{$users->firstname}} {{$users->middlename}} {{$users->extensionname}}  -  {{$users->idno}}</h4>
            
            <div class="panel panel-default">
                <div class="panel-heading">Information</div>
                <div class="panel-body">
                    <table width="100%" cellspacing="2">
                        <tr>
                        <td width="30%"><b>House No/Street/Subd: </b></td>
                        <td>{{$info->address1}} {{$info->address2}}</td>
                        </tr>
                    </table>
                    <table width="100%" cellspacing="2"> 
                        <tr>
                            <td width="25%"><b>District: </b></td>
                            <td width="25%">{{$info->address5}}</td>
                            <td width="25%"><b>City: </b></td>
                            <td width="25%">{{$info->address3}}</td>
                        </tr>
                        <tr>
                            <td width="25%"><b>Region: </b></td>
                            <td width="25%">{{$info->address4}}</td>
                            <td width="25%"><b>Zipcode: </b></td>
                            <td width="25%">{{$info->zipcode}}</td>
                        </tr>
                    </table>
                    <br>
                    <table width="100%" cellspacing="2">
                        <tr>
                        <td width="30%"><b>Email: </b></td>
                        <td>{{$info->email}}</td>
                        </tr>
                    </table>
                    <table width="100%" cellspacing="2">
                        <tr>
                            <td width="25%"><b>Landline: </b></td>
                            <td width="25%">{{$info->phone1}}</td>
                            <td width="25%"><b>Mobile: </b></td>
                            <td width="25%">{{$info->phone2}}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">Parents</div>
                <div class="panel-body">
                    <table width="100%" cellspacing="2">
                        <tr>
                        <td width="20%"><b>Father: </b></td>
                        <td>{{$info->fname}}</td>
                        </tr>
                        <tr>
                        <td width="20%"><b>Email: </b></td>
                        <td>{{$info->femail}}</td>
                        </tr>
                    </table>
                    <table width="100%" cellspacing="2"> 
                        <tr>
                            <td width="25%"><b>Mobile: </b></td>
                            <td width="25%">{{$info->fmobile}}</td>
                            <td width="25%"><b>Landline: </b></td>
                            <td width="25%">{{$info->flandline}}</td>
                        </tr>
                        <tr>
                            <td width="25%"><b>Office Phone No: </b></td>
                            <td width="25%">{{$info->fOfficePhone}}</td>
                            <td width="25%"><b>Office Fax No: </b></td>
                            <td width="25%">{{$info->ffax}}</td>
                        </tr>
                    </table>
                    <br>
                    <table width="100%" cellspacing="2">
                        <tr>
                        <td width="20%"><b>Mother: </b></td>
                        <td>{{$info->mname}}</td>
                        </tr>
                        <tr>
                        <td width="20%"><b>Email: </b></td>
                        <td>{{$info->memail}}</td>
                        </tr>
                    </table>
                    <table width="100%" cellspacing="2"> 
                        <tr>
                            <td width="25%"><b>Mobile: </b></td>
                            <td width="25%">{{$info->mmobile}}</td>
                            <td width="25%"><b>Landline: </b></td>
                            <td width="25%">{{$info->mlandline}}</td>
                        </tr>
                        <tr>
                            <td width="25%"><b>Office Phone No: </b></td>
                            <td width="25%">{{$info->mOfficePhone}}</td>
                            <td width="25%"><b>Office Fax No: </b></td>
                            <td width="25%">{{$info->mfax}}</td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="panel panel-default">
                <div class="panel-heading">Guardian</div>
                <div class="panel-body">
                    <table width="100%" cellspacing="2">
                        <tr>
                        <td width="20%"><b>Guariardian: </b></td>
                        <td>{{$info->guardianname}}</td>
                        </tr>
                        <tr>
                        <td width="20%"><b>Contact #: </b></td>
                        <td>{{$info->guardianmobile}}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop