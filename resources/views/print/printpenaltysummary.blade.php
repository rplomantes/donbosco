<html>
    <head>
        <style>
            table tr td {font-size:10pt;}
        </style>
    </head>
    <body>
        <h3>Don Bosco Technical Institute - Makati</h3>
        <p>Plan : {{$plan}}</br>
           For the of month of {{$duemonth}}: 
        </p>   
      <table border="1" cellpadding="5" cellspacing = "0"><thead><tr><th>Id No</th><th>Student Name</th><th>Level</th><th>Section</th><th>Balance</th><th>Penalty</th></tr></thead>  
        <tbody>
          <?php
        $count = count($idno);
        for($i=1; $i<= $count; $i++){
            echo "<tr><td>" . $idno[$i] ."</td>";
            echo "<td>" . $sname[$i]. "</td>";
            echo "<td>" . $level[$i]. "</td>";
            echo "<td>" . $section[$i]. "</td>";
            echo "<td align=\"right\">" . number_format($balance[$i],2). "</td>";
            echo "<td align=\"right\">" . number_format($penalty[$i],2). "</td></tr>";
        }
      
        ?>
            <tbody>
        </table>  
    </body>
</html>
