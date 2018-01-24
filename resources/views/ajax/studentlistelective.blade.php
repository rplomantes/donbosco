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
    $section  = App\CtrElectiveSection::find($student->classify);
    ?>
    <tr style="cursor:pointer;" onclick="{{$action}}('{{$student->idno}}')">
        <td>{{$student->idno}}</td>
        <td>{{$name->lastname}}, {{$name->firstname}} {{substr($name->middlename,0,1)}}.</td>
        <td>
            @if($section)
            {{$section->elecode}} - <span style="float: right">{{$section->section}}</span>
            @endif
        </td>
    </tr>
    @endforeach
</table>