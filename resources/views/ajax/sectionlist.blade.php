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
    ?>
    <tr style="cursor:pointer;">
        <td>{{$student->class_no}}</td>
        <td>{{$student->idno}}</td>
        <td>{{$name->lastname}}, {{$name->firstname}} {{substr($name->middlename,0,1)}}.</td>

    </tr>
    @endforeach
</table>