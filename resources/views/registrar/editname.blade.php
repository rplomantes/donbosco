@extends("app")

@section("content")
<div class="container">
 <form onsubmit="return confirm('Continue to Edit Names ?');" class="form-horizontal" id = "editname" role="form" method="POST" action="{{ url('/registrar/editname') }}">
  {!! csrf_field() !!}    
  <table class="table table-striped">
      <tr><td>Student ID </td><td>{{$student->idno}}<input type = "hidden" name="idno" value="{{$student->idno}}"></td></tr>
      <tr><td>Last Name </td><td><input class = "form-control" type = "text" name="lastname" value="{{$student->lastname}}"></td></tr>
      <tr><td>First Name </td><td><input class = "form-control" type = "text" name="firstname" value="{{$student->firstname}}"></td></tr>
      <tr><td>Middle Name </td><td><input class = "form-control" type = "text" name="middlename" value="{{$student->middlename}}"></td></tr>
      <tr><td>Extention Name </td><td><input class = "form-control"  type = "text" name="extensionname" value="{{$student->extensionname}}"></td></tr>
      <tr><td>Gender </td><td>
              <div class="btn-group" data-toggle="buttons">
               <label class="btn btn-default
                      @if($student->gender == "Male")
                      active
                      @endif
                      ">    
               <input class = "form-control"  type = "radio" name="gender" value="Male"
                                     @if($student->gender == "Male")
                                     checked = "checked"
                                     @endif
                                     >Male
               </label>
               <label class="btn btn-default 
                      @if($student->gender == "Female")
                      active
                      @endif
                      ">    
              <input class = "form-control"  type = "radio" name="gender" value="Female"
                                     @if($student->gender == "Female")
                                     checked = "checked"
                                     @endif
                                     >Female
                </label>
                                     </div>
          </td></tr>
      <tr><td><input type = "submit" value="Update" class="btn btn-danger form-control"></td><td><a href="{{url('/registrar/evaluate',$student->idno)}}" class="form-control btn btn-primary">Back to previous</a></td></tr>
  </table>    
  </form>
</div>
@stop
