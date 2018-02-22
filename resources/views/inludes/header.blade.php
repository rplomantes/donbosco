<style type="text/css">
    html{
        margin-top:80px;
        margin-left:20px;
        margin-right:20px;
    }
    body{
        margin-top:60px;
        margin-left:10px;
        margin-right:10px;
        font-family: calibri;
    }
    #header { 
        position: fixed; 
        left: 0px; 
        top: -80px; 
        right: 0px; 
        height: 80px; 
        text-align: center;
        font-size: 15px; 
    }
    
</style>
<script src="{{asset('/js/jquery.js')}}"></script>
<div id="header">

    <div style="position:relative;top:10px;left:-200px;"><img src="<?php echo $_SERVER['DOCUMENT_ROOT']; ?>/images/logo.png"  style="width:80px;height:auto;"></div>
    <table border = '0' celpacing="0" cellpadding = "0" align="center" style="position: relative;top:-90px">
        <tr>
            <td>
                <p align="center">
                    <span style="font-size:12pt;">
                        Don Bosco Technical Institute of Makati, Inc.<br>
                        Chino Roces Ave., Makati City 
                    </span>
                </p>
            </td>
        </tr>
        <tr>
            <td style="font-size:12pt;text-align:center;"><b><u>{{$title}}</u></b></td>
        </tr>
        @if(isset($transactiondate))
        <tr><td style="text-align:center;">For {{date("M d, Y",strtotime($transactiondate))}}</td></tr>
        @else
        <tr><td style="text-align:center;">{{date("M d, Y",strtotime($from))}} to {{date("M d, Y",strtotime($to))}}</td></tr>
        @endif
    </table>
    
    <hr style="position: relative;top:-90px">

</div>