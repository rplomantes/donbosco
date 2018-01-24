
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
        <meta author="Roy Plomantes">
        <meta poweredby = "Nephila Web Technology, Inc">
       
        
 <style>
    .body table, th  , .body td{
    border: 1px solid black;
    font-size: 10pt;
}

td{
    padding-right: 10px;
    padding-left: 10px;
}

table {
    border-collapse: collapse;
    width: 100%;
}

th {
    height: 20px;
}

.notice{
    font-size: 10pt;
    padding:5px;
    border: 1px solid #000;
    text-indent: 10px;
    margin-top: 5px;
}
.footer{
  padding-top:10px;
    
}
.heading{
    padding-top: 10px;
    font-size: 12pt;
    font-weight: bold;
}
        </style>
	<!-- Fonts -->
	
        </head>
<body> 
    <table border = '0'celpacing="0" cellpadding = "0" width="550px" align="center"><tr><td width="10px">
        <img src = "{{ asset('/images/logo.png') }}" alt="DBTI" align="middle"  width="70px"/></td>
            <td width="530px"><p align="center"><span style="font-size:20pt;">Don Bosco Technical Institute of Makati, Inc. </span><br>
        Chino Roces Ave., Makati City <br>
        Tel No : 892-01-01
        </p>
    </td>
    </tr>
    </table>
    
    <h3 align="center"> Statement of Account Summary</h3>
    <table>
                <tr><td>Level/Section : {{$level}} - {{$section}}</td></tr>
            @if($strand != "none")
            <tr><td>
            Strand/Shop : {{$strand}}
                    
            </td></tr>
            @endif
            <tr><td>Statement Date : {{date('M d Y',strtotime($trandate))}}
                    
            <hr />
   </table>    
    <div class="body">
    <table class="table table-stripped"><thead><tr><th>Student No</th><th>Name</th><th>Plan</th><th>Level</th><th>Section</th><th>Balance</th></tr></thead>
        <tbody>
       @foreach($soasummary as $soa)
       @if($soa->amount > 0)
       <tr><td>{{$soa->idno}}</td><td>{{$soa->lastname}}, {{$soa->firstname}} {{$soa->middlename}}</td>
           <td>{{$soa->plan}}</td><td>{{$soa->level}}</td><td>{{$soa->section}}</td><td align="right">{{number_format($soa->amount,2)}}</td>
           </tr>
       @endif
       @endforeach
       </tbody>
 </table> 
        </div>
    </body>
    </html>