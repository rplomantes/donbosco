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
    
    #footer{
        position: fixed; 
        bottom:0px;
    }
    .pagenum:before {content: counter(page); }
</style>
<div id="header">

    <img src="<?php echo $_SERVER['DOCUMENT_ROOT']; ?>/images/logo.png"  style="position:absolute;width:80px;height:auto;left:30%;top:20px;">
    <table border = '0'celpacing="0" cellpadding = "0" align="center" width="100%">
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
        <tr><td style="font-size:12pt;text-align:center;"><b><u>{{$title}}</u></b></td></tr>
        @if(isset($transactiondate))
        <tr><td style="text-align:center;">For {{date("M d, Y",strtotime($transactiondate))}}</td></tr>
        @else
        <tr><td style="text-align:center;">{{date("M d, Y",strtotime($from))}} to {{date("M d, Y",strtotime($to))}}</td></tr>
        @endif
    </table>
    
    <hr>
</div>
<div id="footer">Page <span class="pagenum"></span> of 
    @if(count($chunk->last()) <= ($group_count-5))
    {{count($chunk)}}
    @else
    {{count($chunk)+1}}
    @endif
</div>
