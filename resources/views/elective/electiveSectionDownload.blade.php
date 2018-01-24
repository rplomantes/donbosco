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
    <tr>
        <td>{{$student->idno}}</td>
        <td>{{$cn}}</td>
        <td>{{$name->lastname}}, {{$name->firstname}} {{substr($name->middlename,0,1)}}.</td>

    </tr>
    <?php $cn++; ?>
    @endforeach
</table>