<table class="table table-bordered">
    <thead>
        <tr>
            <th>Idno</th>
            <th>CN</th>
            <th>Name</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php $cn = 1;?>
    @foreach($students as $name)
    <tr>
        <td>{{$name->idno}}</td>
        <td>{{$cn}}</td>
        <td>{{$name->name}}</td>
        <td class="clickable" onclick="removeStudent('{{$name->idno}}')" align="center" style="color:red"><i class="fa fa-minus"></i></td>
    </tr>
    <?php $cn++;?>
    @endforeach        
    </tbody>

</table>