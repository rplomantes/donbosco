<?php

$max=0;
?>
<style>
    tr:hover{
        background: #a1c9d2!important;
    }
</style>
<table class="table table-striped">
    <thead>
            <th>Class No.</th>
            <th>ID No.</th>
            <th style="text-align: center">Name</th>

    </thead>

    @foreach($students as $student)
    <?php
    $name = \App\User::where('idno',$student->idno)->first();
    $max=$max+1;
    ?>

    <tr style="cursor:pointer;">
        
        @if($student->class_no > 0)
        <td>{{$student->class_no}}</td>
        @else
        <td style="color:red">{{$max}}</td>
        @endif
        <td>{{$student->idno}}</td>
        <td>{{$name->lastname}}, {{$name->firstname}} {{substr($name->middlename,0,1)}}.</td>
        
    </tr>
    @endforeach
</table>
<a href="" class="col-md-12 btn btn-danger">Print Section</a>
<script>
    
</script>