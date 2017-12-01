<style type="text/css">
    html,body{
        margin-top:80px;
        margin-left:10px;
        margin-right:10px;
        font-family: calibri;
    }
    #header { 
        position: fixed; 
        left: 0px; 
        top: -80px; 
        right: 0px; 
        height: 100px; 
        text-align: center;
        font-size: 15px; 
    }
</style>
<div id="header">
    <table border = '0'celpacing="0" cellpadding = "0" width="100%" align="center">
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
        <tr><td style="text-align:center;">{{date("M d, Y",strtotime($fromtran))}} to {{date("M d, Y",strtotime($totran))}}</td></tr>
        <img src="<?php echo $_SERVER['DOCUMENT_ROOT']; ?>/images/DBTI.png"  style="position:absolute;width:100px;height:auto;top:0px;left:150px;top:20px;">
    </table>
    
    <hr>
</div>