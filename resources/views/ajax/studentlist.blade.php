<style>
    tr:hover{
        background: #a1c9d2!important;
    }
</style>
<table class="table table-striped">
    <thead>
        <tr>
            <td>ID No.</td>
            <td>Name</td>
            <td>Section</td>
        <tr>
    </thead>
    @foreach($students as $student)
    <?php
    $name = \App\User::where('idno',$student->idno)->first();
    ?>
    <tr style="cursor:pointer;">
        <td>{{$student->idno}}</td>
        <td>{{$name->lastname}}, {{$name->firstname}} {{substr($name->middlename,0,1)}}.</td>
        <td>{{$student->section}}</td>
    </tr>
    @endforeach
</table>