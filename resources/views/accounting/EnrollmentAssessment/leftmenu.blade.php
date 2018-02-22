<style>
    #leftmenu a
    {
        display:block;
        text-decoration:none;
        font-size:15px;
    }
    #leftmenu td:hover
    {
        background-color: blue;
    }
    #leftmenu td:hover a
    {
        color: white;
    }
    
    #leftmenu tr:nth-child(<?php echo $module_info['menu']; ?>) td{
        background-color: blue;
    }
    #leftmenu tr:nth-child(<?php echo $module_info['menu']; ?>) td a{
        color: white;
    }
</style>
<h4><b>Asessment Generation</b></h4>
<hr>
<table class='table' id='leftmenu'>
    <tr><td><a href='{{route("indexbreakdown")}}'>Levels Account Breakdown</a></td></tr>
    <tr><td><a href='{{route("indexbooks")}}'>Books</a></td></tr>
    <tr><td><a href='#'>Discount</a></td></tr>
    <tr><td><a href='#'>Plan Setting</a></td></tr>  
    <tr><td><a href='#'>Generate Plan Schedule</a></td></tr>
    <tr><td><a href='#'>Generated Plan Schedule</a></td></tr>
</table>