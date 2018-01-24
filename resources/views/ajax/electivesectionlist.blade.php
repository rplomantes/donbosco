<style>
    tr:hover{
        background: #a1c9d2!important;
    }
</style>
<table class="table table-striped">
    <thead>
        <tr>
            <td>ID No.</td>
            <td>CN</td>
            <td>Name</td>
        <tr>
    </thead>
    <?php $cn = 1; ?>
    @foreach($students as $student)
    <?php
    $name = \App\User::where('idno',$student->idno)->first();
    ?>
    <tr style="cursor:pointer;" onclick="{{$action}}('{{$student->idno}}')">
        <td>{{$student->idno}}</td>
        <td>{{$cn}}</td>
        <td>{{$name->lastname}}, {{$name->firstname}} {{substr($name->middlename,0,1)}}.</td>

    </tr>
    <?php $cn++; ?>
    @endforeach
</table>
<button class="btn btn-danger col-md-12" onclick="printElective()">Print</button>
