
<form method='post' action='{{route("postsummary")}}' id='iasform'>
    {!!csrf_field()!!}
    <div class='panel panel-default'>
        <div class='panel-heading'>Filter</div>
        <div class='panel-body'>
            <div class='form-group'>
                <label>From</label>
                <input class='form-control date' id='from' name='from' value="{{$request->input('from')}}">
            </div>
            <div class='form-group'>
                <label>To</label>
                <input class='form-control date' id='to' name='to' value="{{$request->input('to')}}">
            </div>
            
            <div class='form-group'>
                <label>Account</label>
                <select name='account' id='account' class="form-control">
                    <option value='' hidden='hidden'>--Select or Type In Account--</option>
                    @foreach($accounts->sortBy('accountname') as $entry_account)
                    <option value='{{$entry_account->acctcode}}' <?php echo $request->input('account') == $entry_account->acctcode? "selected='selected'":null;?> >{{$entry_account->accountname}}</option>
                    @endforeach
                </select>
            </div>
            
            <div class='form-group'>
                <label>Transaction</label>
                <select name='type' id='type' class="form-control">
                    <option value='' <?php echo $request->input('type') == ""? "selected='selected'":null;?> >All</option>
                    <option value='1' <?php echo $request->input('type') == 1? "selected='selected'":null;?> >Cash Receipt</option>
                    <option value='2' <?php echo $request->input('type') == 2? "selected='selected'":null;?> >Debit Memo</option>
                    <option value='3' <?php echo $request->input('type') == 3? "selected='selected'":null;?> >General Journal</option>
                    <option value='4' <?php echo $request->input('type') == 4? "selected='selected'":null;?> >Disbursement</option>
                    <option value='5' <?php echo $request->input('type') == 5? "selected='selected'":null;?> >System Generated</option>
                    
                    
                </select>
            </div>

            <div class='form-group'>
                <label>Department</label>
                <select name='department' id='department' class="form-control">
                    <option value='' <?php echo $request->input('department') == ""?"selected='selected'":"";?>>All</option>
                    <option value='None' <?php echo $request->input('department') == "None"?"selected='selected'":"";?>>None</option>
                    @foreach($departments->unique('main_department')->sortBy('main_department') as $department)
                    <option value='{{$department->main_department}}' <?php echo $request->input('department') == $department->main_department?"selected='selected'":"";?>>{{$department->main_department}}</option>
                    @endforeach
                </select>
            </div>

            <div class='form-group'>
                <label>Office</label>
                <select name='office' id='office' class="form-control">
                    <option value='' <?php echo $request->input('office') == ""?"selected='selected'":"";?>>All</option>
                    <option value='None' <?php echo $request->input('office') == "None"?"selected='selected'":"";?>>None</option>
                    @foreach($departments->sortBy('sub_department') as $office)
                    <option value='{{$office->sub_department}}' <?php echo $request->input('office') == $office->sub_department?"selected='selected'":"";?>>{{$office->sub_department}}</option>
                    @endforeach
                </select>
            </div>
            <div class='form-group'>
                <label>Remarks Contain</label>
                <textarea style="resize: none;" class='form-control date' id='remarks' name='remarks' >{{$request->input('remarks')}}</textarea>
            </div>
        </div>
        
    </div>
    
    <div class='panel panel-default'>
        <div class='panel-heading'>Grouping</div>
        <div class='panel-body'>
            <div class="radio">
                <label><input type="radio" name="grouping" value="">None</label>
            </div>
            <div class="radio">
              <label><input type="radio" name="grouping" value="subAccount">By Particular</label>
            </div>
            <div class="radio">
              <label><input type="radio" name="grouping" value="byDepartment">By Department</label>
            </div>
        </div>
    </div>
    <div><button type="submit" class="col-md-12 btn btn-danger">Go</button></div>
</form>

<script>
    $("#account").combify();
    $("#department").combify();
    $("#office").combify();
    $('.date').datepicker({dateFormat: 'yy-mm-dd'});
    
    $("[name=grouping][value='{{$request->input('grouping')}}']").attr('checked', 'checked');
</script>