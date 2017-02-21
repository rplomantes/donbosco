@extends('app')

@section('content')
<form onsubmit="return confirm('Continue to assess this student ?');" class="form-horizontal" id = "assess" role="form" method="POST" action="{{ url('/registrar/assessment') }}">
  {!! csrf_field() !!}  
<input type="hidden" name="id" id="id" value="{{ $student->idno }}">
<input type="hidden" name="idno" id="idno" value="{{ $student->idno }}">
<div class="container_fuid clearfix">
    <div class="col-md-6">
        <div class="col-md-12 panel-body">
            <a class="btn btn-primary" href="{{url('/')}}">Back</a>
            <a class="btn btn-primary" href="{{url('/registrar/edit',$student->idno)}}">Edit Name</a>
            <a class="btn btn-primary" href="{{url('/registrar/evaluate',$student->idno)}}">Default</a>
        </div>    
       
        <div class="col-md-12">
            <table class="table table-striped">
                <tr><td>Student ID</td><td>{{$student->idno}}</td></tr>
                <tr><td>Student Name</td><td><strong>{{$student->lastname}},  {{$student->firstname}} {{$student->middlename}} {{$student->extensionname}}</strong></td></tr>
                <tr><td>Gender</td><td>{{$student->gender}}</td></tr>
            </table>
        </div>
    
        <div class="col-md-12">
            <table border ="1"  class="table table-bordered">
                <thead><tr><th>Status</th><th>Current Balance</th></tr>
                </thead>
                
                <tbody><tr><td>
                    @if(is_null($status))
                        <i>No status</i>
                        <input type="hidden" name="action" value="add">
                    @else
          
                        @if($status->status == '1' && $currentschoolyear->schoolyear == $status->schoolyear && $currentschoolyear->period == $status->period)
                            <b> Assessed </b>
                            <input type="hidden" name="action" value="reassessed">
                        @elseif($status->status == '2' && $currentschoolyear->schoolyear == $status->schoolyear && $currentschoolyear->period == $status->period)
                            <strong style="color:red">Enrolled</strong>
                        @elseif($status->status == '3' && $currentschoolyear->schoolyear == $status->schoolyear && $currentschoolyear->period == $status->period)
                         <strong style="color:red">Dropped</strong>
                        @else
                            <i>Registered</i> 
                            <input type="hidden" name="action" value="update">
                        @endif
                        
                    @endif
                        </td><td><strong style="color:red">{{ number_format($balance-$reservation,2)}}</strong></td></tr>
                </tbody>
            </table>
        </div>  
         
        <!-- if student has status-->
        @if(isset($status->status))
            <!-- if student status 0 or 1-->
            @if($currentschoolyear->schoolyear == $status->schoolyear && $currentschoolyear->period == $status->period)
                @if($status->status=='2')
                    <div class="panel-body">
                        <h5>This student is already <b>ENROLLED</b>. Please see accounting for payment details</h5>
                    </div>
                @elseif($status->status == '3')
                 <div class="panel-body">
                        <h5>This student is already <b>DROPPED</b>. Please see accounting to verify remaining account.</h5>
                    </div>
                @elseif($status->status == '1')
                    <div class="col-md-6">
                        <div class="col-md-12">Program</div>
                        <div class="col-md-12">
                            <span class="form-control">{{$status->department}}</span>
                            <input type="hidden" name="department" value="{{$status->department}}">
                        </div>     
                    </div>
   
                    <div class="col-md-6">
                        <div id="levelcontainer"> 
                    
                    @if($status->department != "TVET")   
                    
                            <div class="col-md-12">Level</div>
                            <div class="col-md-12">
                                <span class="form-control">{{$status->level}}</span>
                            </div>     
                   
                      
                    @endif
                        </div>
                      
                        <div id="coursecontainer">
                    @if($status->department == 'TVET')
                     
                            <div class="col-md-12">Course
                            </div>    
                            <div class="col-md-12">
                                <span class="form-control">{{$status->course}}</span>
                            </div>
                         
                    @endif               
                        
                        </div> 
                    
                    </div>   
                
                
                    <div class="col-md-6">    
                        <div id="trackcontainer">
                            @if($status->department=="Senior High School" || $status->level == "Grade 9" || $status->level == "Grade 10")
                        
                                <div class="col-md-12">Specialization</div>
                                <div class="col-md-12">
                                     <span class="form-control">{{$status->strand}}</span>
                                </div>
                             @elseif($status->department == "TVET")       
                            <div class="col-md-12">Batch</div>
                                <div class="col-md-12">
                                     <span class="form-control">{{$currentschoolyear->period}}</span>
                                     <input type="hidden" name="batch" value="{{$currentschoolyear->period}}">
                                </div>
                            @endif 
                            
                        </div>
                    </div>
                
                    <div class="col-md-6">    
                    <div id = "plancontainer">
                        <div class="col-md-12">Plan</div>
                        <div class="col-md-12">
                             <span class="form-control">{{$status->plan}}</span>
                        </div>   
                   
                    </div>
                    </div>
                    <div class="col-md-12">
                        <div id="discountcontainer">
                            <div id = "discountcontainer">
                                <div class="col-md-12">Discount</div>
                                <div class="col-md-12">
                            @if(isset($mydiscount->description))
                                    <span class="form-control">{{$mydiscount->description}}</span>
                            @else
                                    <span class="form-control">None</span>
                            @endif
                                </div>   
                   
                            </div>
                   
                        </div>
                        <div id="assessbuttoncontainer">    
                        </div>
                    </div>
                @elseif($status->status == '0')
                    <div class="col-md-6">
                        <div class="col-md-12">Program</div>
                        <div class="col-md-12">
                            <select id="department" name="department" class="form-control" onchange ="getlevel(this.value)">
                            <option value="">Select Program</option>    
                            @foreach($programs as $program)
                            <option value = "{{$program->department}}">{{$program->department}}</option>
                            @endforeach
                            <option value="TVET">TVET</option></select> 
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div id="levelcontainer">
                        </div>
                        <div id="coursecontainer">    
                        </div>
                    </div>
                
                    <div class="col-md-6">
                        <div id="trackcontainer">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div id="plancontainer">    
                        </div>
                    </div>
                   
                    <div class="col-md-12">
                        <div id="discountcontainer">
                   
                        </div>
                        <div id="assessbuttoncontainer">    
                        </div>
                    </div>
                @endif
            @else
            
                <div class="col-md-6">
                        <div class="col-md-12">Program</div>
                        <div class="col-md-12">
                            <select id="department" name="department" class="form-control" onchange ="getlevel(this.value)">
                            <option value="">Select Program</option>    
                            @foreach($programs as $program)
                            <option value = "{{$program->department}}">{{$program->department}}</option>
                            @endforeach
                            <option value="TVET">TVET</option></select> 
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div id="levelcontainer">
                        </div>
                        <div id="coursecontainer">    
                        </div>
                    </div>
                
                    <div class="col-md-6">
                        <div id="trackcontainer">
                        </div>
                        <div id="plancontainer">    
                        </div>
                    </div>
                
                    <div class="col-md-12">
                        <div id="discountcontainer">
                   
                        </div>
                        <div id="assessbuttoncontainer">    
                        </div>
                    </div>
            
            
            
            
            @endif       <!-- end of no status-->
        @else
       
        <div class="col-md-6">
        <div class="col-md-12">Program</div>
        <div class="col-md-12">
        <select id="department" name="department" class="form-control" onchange ="getlevel(this.value)">
        <option value="">Select Program</option>    
            @foreach($programs as $program)
                <option value = "{{$program->department}}">{{$program->department}}</option>
            @endforeach
                <option value="TVET">TVET</option></select> 
                </div>
                </div>
                <script src="{{url('/js/nephilajs/getlevel.js')}}"></script>
                <div class="col-md-6">
                <div id="levelcontainer">
                </div>
                <div id="coursecontainer">    
                </div>
                </div>
                
                <div class="col-md-6">
                <div id="trackcontainer">
                </div>
                <div id="plancontainer">    
                </div>
                </div>
                
                 <div class="col-md-12">
                <div id="discountcontainer">
                   
                </div>
                <div id="assessbuttoncontainer">    
                </div>
                </div>
          
        @endif
        


</div>
    <div class="col-md-6">
        <div id="screendisplay">
            @if(isset($status->status))
            @if($status->status == '1' && $currentschoolyear->schoolyear == $status->schoolyear && $currentschoolyear->period == $status->period)
                <table class ="table table-bordered"><tr><td>Description</td><td>Amount</td><tr>
                <?php $totalamount = 0; $totalplandiscount=0; $totalotherdiscount=0; ?>
                @foreach($ledgers as $ledger)
                <?php $totalamount = $totalamount + $ledger->amount; 
                      $totalplandiscount = $totalplandiscount + $ledger->plandiscount;
                      $totalotherdiscount = $totalotherdiscount + $ledger->otherdiscount;
                ?>
                <tr><td>{{$ledger->receipt_details}}</td><td align="right">{{$ledger->amount}}</td></tr>
                @endforeach
                <tr><td>Sub Total</td><td align="right"><?php echo number_format($totalamount,2); ?></td><tr>
                <tr><td>Less: Plan Discount</td><td align="right"><span style="color:red">(<?php echo number_format($totalplandiscount,2); ?>)</span></td><tr>    
                <tr><td> Other Discount</td><td align="right"><span style="color:red">(<?php echo number_format($totalotherdiscount,2); ?>)</span></td><tr> 
                <tr><td>Reservation</td><td align="right"><span style="color:red">(<?php echo number_format($reservation,2); ?>)</span></td><tr>
                <tr><td>Total</td><td align="right"><b><?php echo number_format($totalamount-$totalotherdiscount-$totalplandiscount-$reservation,2); ?></td></b><tr>
                </table>
                <div class="col-md-6">
                <input type="submit" class="btn btn-primary form-control" value = "Reassess">
                </div>
               
                <div class="col-md-6">
                 <a href="{{url('printregistration',$status->idno)}}" class="btn btn-primary form-control">Print Registration Form</a>  
                </div>
               
            @endif  
            @endif
        </div>
    </div>
<script src="{{url('/js/nephilajs/getlevel.js')}}"></script>    
<script src="{{url('/js/nephilajs/getplan.js')}}"></script>
<script src="{{url('/js/nephilajs/gettrackplan.js')}}"></script>
<script src="{{url('/js/nephilajs/getdiscount.js')}}"></script>
<script src="{{url('/js/nephilajs/getlevel.js')}}"></script>
 
  </form>      
@stop

