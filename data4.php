<?php

	header('Content-Type: application/json; charset=utf-8');

    $name = $_POST['name'];
    $year = substr_replace($name, "01-01", 5, 5); 
    $team_num = substr($name, 0, 4);


    if($team_num >= 2015)
    {
        $team_num = 10;
    }else if($team_num == 2014 || $team_num == 2013)
    {
        $team_num = 9;
    }else if($team_num <= 2012)
    {
        $team_num = 8;
    }


	$queryFull = "select teamA_id, teamB_id, A_score, B_score from competition c where (c.com_date <= '" . $name . "') and ( '" . $year . "' < c.com_date) ; " ;
	 
	

	
	$con = mysql_connect("localhost","root","4575");
	mysql_query("SET character_set_results=utf8", $con);
    mb_language('uni'); 
    mb_internal_encoding('UTF-8');
    
	mysql_select_db("test",$con);

    mysql_query("set names 'utf8'" ,$con);

	$result = mysql_query($queryFull);

	$header=array();
	//$fields = mysql_num_fields($result);

	for ($i = 0; $i < $team_num; $i++) {
	$header[] = mysql_field_name($result, $i);
	}
	$f=fopen("file2.csv","wt");
	fputcsv($f,$header);
	while ($row = mysql_fetch_row($result)) {
		
	if($row[0] == 1)
	{
		$row[0] = "삼성";
	}
	if($row[0] == 2)
	{
		$row[0] = "넥센";
	}
	if($row[0] == 3)
	{
		$row[0] = "한화";
	}
	if($row[0] == 4)
	{
		$row[0] = "LG";
	}
	if($row[0] == 5)
	{
		$row[0] = "SK";
	}
	if($row[0] == 6)
	{
		$row[0] = "두산";
	}
	if($row[0] == 7)
	{
		$row[0] = "롯데";
	}
	if($row[0] == 8)
	{
		$row[0] = "KIA";
	}
	if($row[0] == 9)
	{
		$row[0] = "NC";
	}
	if($row[0] == 10)
	{
		$row[0] = "kt";
	}
	if($row[1] == 1)
	{
		$row[1] = "삼성";
	}
	if($row[1] == 2)
	{
		$row[1] = "넥센";
	}
	if($row[1] == 3)
	{
		$row[1] = "한화";
	}
	if($row[1] == 4)
	{
		$row[1] = "LG";
	}
	if($row[1] == 5)
	{
		$row[1] = "SK";
	}
	if($row[1] == 6)
	{
		$row[1] = "두산";
	}
	if($row[1] == 7)
	{
		$row[1] = "롯데";
	}
	if($row[1] == 8)
	{
		$row[1] = "KIA";
	}
	if($row[1] == 9)
	{
		$row[1] = "NC";
	}
	if($row[1] == 10)
	{
		$row[1] = "kt";
	}
	
	fputcsv($f,$row);
	}
	fclose($f);
	mysql_close($con);


	exec("Rscript ./script.R", $error);


	$f = fopen("save.csv", "r");

	$row = 0;

	$array = array(
    array("팀명","Ability")
);
	$check = 1;

	while (($data = fgetcsv($f)) !== false) {
		$num = count($data);
		if($check)
		{
			$check = 0;
			continue;
		}
        for ($c=0; $c < $num; $c++) {
        	$a[$row][$c] = $data[$c];
            //echo $data[$c] . "<br />\n";
        }
        $row++;

	}	
	fclose($f);
	echo json_encode($a);


/*
	//reading save.csv and 
	echo "<table>\n";
	$f = fopen("save.csv", "r");
	while (($line = fgetcsv($f)) !== false) {
        	echo "<tr>";
        	foreach ($line as $cell) {
                	echo "<td>" . htmlspecialchars($cell) . "</td>";
        	}
        	echo "</tr>\n";
	}	
	fclose($f);
	echo "</table>";
*/

?>
