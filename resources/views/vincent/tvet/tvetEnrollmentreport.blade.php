@extends('app')
@section('content') 
<div class="container">
    <div class="form-group col-md-3">
        <div class="col-md-3">Batch</div>
        <div class="col-md-7">
            <select class="form-control" id="batch">
                <?php 
                $index = 0;
                $len = count($batches); 
                ?>
                @foreach($batches as $batch)
                <option value="{{$batch->period}}" 
                       @if($index == ($len-1))
                       selected="selected"
                       @endif
                       <?php $index++; ?>
                       >Batch {{$batch->period}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <!--a href="#" onclick="viewreport()" class="btn btn-warning">OK</a-->
            <a href="{{url('/download/88')}}" class="btn btn-warning">DL</a>
        </div>
    </div>
    <div class="col-md-12">
        <table>
            <tr>
                <td></td>
            </tr>
        </table>
    </div>
</div>
<script type="text/javascript">
    function viewreport(){
        
    }
</script>
@stop