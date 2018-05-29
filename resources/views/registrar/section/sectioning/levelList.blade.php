
<table id="level-list">
    <thead>
        <tr>
            <th>Idno</th>
            <th>Name</th>
            <th>Section</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    @foreach($students as $name)
    <tr>
        <td>{{$name->idno}}</td>
        <td>{{$name->name}}</td>
        <td>{{$name->section}}</td>
        <td class="clickable" onclick="addStudent('{{$name->idno}}')" align="center" style="font-size:20px;color: #4545e8"><i class="fa fa-plus"></i></td>
    </tr>
    @endforeach        
    </tbody>

</table>
<script>
    $('#level-list').DataTable({
        "bLengthChange": false,
        "pageLength": 25
    });
</script>