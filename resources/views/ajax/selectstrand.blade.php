<label>Strand</label>
<select name="strand" id="strand" class="form-control" 
        @if($action != null)
        onchange="{{$action}}(this.value)"
        @endif
        >
    <option value="" hidden="hidden">-- Select Strand --</option>
    @if($showall == 1)
    <option value="All">All</option>
    @endif
    @foreach($strands as $strand)
    <option value="{{$strand->strand}}">{{$strand->strand}}</option>
    @endforeach
</select>