<?php
$tuition = $grant->tuitionfee;
$registration = $grant->registrationfee;
$misc = $grant->miscfee;
$elearning = $grant->elearningfee;
$department = $grant->departmentfee;
$book = $grant->bookfee;

$total =$tuition+$registration+$misc+$elearning+$department+$book;

?>
<table class='table table-bordered'>
    <tr>
        <td><b>{{$grant->discountGroup->description}}<b></td>
        <td style="text-align: right">{{number_format($total,2)}}</td>
    </tr>
    
    @if($tuition > 0)
    <tr>
        <td>Tuition Fee</td>
        <td style="text-align: right">{{number_format($tuition,2)}}</td>
    </tr>
    @endif
    
    @if($registration > 0)
    <tr>
        <td>Registration Fee</td>
        <td style="text-align: right">{{number_format($registration,2)}}</td>
    </tr>
    @endif
    
    @if($misc > 0)
    <tr>
        <td>Miscellaneous Fee</td>
        <td style="text-align: right">{{number_format($misc,2)}}</td>
    </tr>
    @endif
    
    @if($elearning > 0)
    <tr>
        <td>E-learning Fee</td>
        <td style="text-align: right">{{number_format($elearning,2)}}</td>
    </tr>
    @endif
    
    @if($department > 0)
    <tr>
        <td>Department Facilities</td>
        <td style="text-align: right">{{number_format($department,2)}}</td>
    </tr>
    @endif

    @if($book > 0)
    <tr>
        <td>Books</td>
        <td style="text-align: right">{{number_format($book,2)}}</td>
    </tr>
    @endif    
</table>