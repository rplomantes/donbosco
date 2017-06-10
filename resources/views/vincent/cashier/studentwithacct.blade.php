@extends('appcashier')
@section('content')
<style type="text/css" media="print">
    .container{
        width:100%;
    }
    td{
        border:1px solid black;
        padding-left: 10px;
        padding-right: 10px;
    }
    .footer{
        display:none;
    }
</style>
<style type="text/css" >
    .center{
        text-align:center;
    }
</style>
<div class="container">
    <table class="table-striped" width="100%">
            <thead>
                <tr >
                    <td colspan="3" style="padding-left: 0px;padding-right: 0px;border:none;">
                        <table width="100%">
                            <tr>
                                <td style="padding-left: 0px;padding-right: 0px;border:none;">
                                    <b>Account:</b>{{$request->subsidy}}<br>
                                    <b>Amount:</b>{{$request->amount}}                                    
                                </td>
                                <td style="text-align: right;padding-left: 0px;padding-right: 0px;vertical-align: top;border:none;">
                                    <b>Date Issued: </b><?php echo date('M d Y') ?><br>
                                    <b>Level: </b>{{$request->level}}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="center">Student No.</td>
                    <td class="center">Name</td>
                    <td class="center">Section</td>
                </tr>                
            </thead>
            @foreach($students as $student)
            <tr>
                <td class="center">{{$student->idno}}</td>
                <td>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->extensionname}}</td>
                <td class="center">{{$student->section}}</td>
            </tr>
            @endforeach
    </table>
</div>

@stop
