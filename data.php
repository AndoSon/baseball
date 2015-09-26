<?php

	header('Content-Type: application/json; charset=utf-8');

	$name = $_POST['name'];

	$year = substr_replace($name, '01-01', 5, 5); 

	$queryFull = "select teamA_id, teamB_id, A_score, B_score from competition c where (c.com_date <= '" . $name . "') and ( '" . $year . "' < c.com_date) ; " ;

	$con = mysql_connect("localhost","root","4575");
	mysql_query("SET character_set_results=utf8", $con);
    mb_language('uni'); 
    mb_internal_encoding('UTF-8');
    
	mysql_select_db("test",$con);

    mysql_query("set names 'utf8'",$con);

	$result = mysql_query($queryFull);


	$originArray = array(array());
    
	$originArray = array_fill(0, 12, array_fill(0, 6, 0));
    
	for($i=0; $i<12; $i++)
		$originArray[$i][0] = $i;

	//orginArray [ teamid : 0 ] [ win : 1] [ lose : 2] [ draw : 3] [ total : 4] [ rate : 5] 
    // 승률 = 승수 / (총경기수  - 무승부) 


	if($result){
        while($row = mysql_fetch_array($result)){

        	$originArray[$row[0]][4]++;
        	$originArray[$row[1]][4]++;
        	if($row[2] == $row[3])
        	{
        		//draw
        		$originArray[$row[0]][3]++;
        		$originArray[$row[1]][3]++;
        	}
        	if($row[2] > $row[3])
        	{
        		$originArray[$row[0]][1]++;
        		$originArray[$row[1]][2]++;
        		//A is winner
        	}
        	if($row[2] < $row[3])
        	{
        		$originArray[$row[0]][2]++;
        		$originArray[$row[1]][1]++;
        		//B is winner
        	}
            
        	}
        }

    for($i=0; $i<12; $i++){
        // 승률 = 승수 / (총경기수  - 무승부) 
        if($originArray[$i][4] - $originArray[$i][3] != 0)
        {
            $originArray[$i][5] = $originArray[$i][1] / ($originArray[$i][4] - $originArray[$i][3]); 
        }
    }

    //page rank


    foreach ($originArray as $key => $row) {
    $rate[$key]  = $row[5];
    //$edition[$key] = $row['edition'];
    }
    

   for($i=0; $i<12; $i++){
        // 승률 = 승수 / (총경기수  - 무승부) 
        if($originArray[$i][0] == 1)
        {
             $originArray[$i][0] = "삼성";
        }
        if($originArray[$i][0] == 2)
        {
             $originArray[$i][0] = "넥센";
        }if($originArray[$i][0] == 3)
        {
             $originArray[$i][0] = "한화";
        }if($originArray[$i][0] == 4)
        {
             $originArray[$i][0] = "LG";
        }if($originArray[$i][0] == 5)
        {
             $originArray[$i][0] = "SK";
        }if($originArray[$i][0] == 6)
        {
             $originArray[$i][0] = "두산";
        }if($originArray[$i][0] == 7)
        {
             $originArray[$i][0] = "롯데";
        }if($originArray[$i][0] == 8)
        {
             $originArray[$i][0] = "KIA";
        }if($originArray[$i][0] == 9)
        {
             $originArray[$i][0] = "NC";
        }if($originArray[$i][0] == 10)
        {
             $originArray[$i][0] = "kt";
        }
    }
    

    array_multisort($rate, SORT_DESC, $originArray);
    //다인자의 경우 
    //array_multisort($rate, SORT_DESC, $edition, SORT_ASC, $originArray);  

	echo json_encode($originArray);

	mysql_close($con);
	


?>