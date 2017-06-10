<?php
echo "<table><tr><td>SCODE</td><td>SUBJ_CODE</td><td>GRADE</td></tr>";
foreach($idnos as $idno){
   
    $match= \App\User::where('idno',$idno->SCODE)->first();
    if(count($match)==0){
        echo "<tr><td>".$idno->SCODE."</td><td>".$idno->SUBJ_CODE."</td><td>".$idno->GRADE_PASS1."</td></tr>";
    }
}
echo "</table>"
?>

