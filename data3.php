<?php

	header('Content-Type: application/json; charset=utf-8');

	$name = $_POST['name'];

	$year = substr_replace($name, '01-01', 5, 5); 
    $team_num = substr($name, 0, 4);

    // echo $team_num;

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

    // echo $team_num;

	$queryFull = "select teamA_id, teamB_id, A_score, B_score from competition c where (c.com_date <= '" . $name . "') and ( '" . $year . "' < c.com_date) ; " ;

	$con = mysql_connect("localhost","root","4575");
	mysql_query("SET character_set_results=utf8", $con);
    mb_language('uni'); 
    mb_internal_encoding('UTF-8');
    
	mysql_select_db("test",$con);

    mysql_query("set names 'utf8'",$con);

	$result = mysql_query($queryFull);


    $graph = array(array());
    
	$graph = array_fill(0, $team_num, array_fill(0, $team_num, 0));
    $PR = array_fill(0, $team_num, array( 0, 1 / $team_num));
	$oPR = array_fill(0, $team_num, array( 0, 1 / $team_num));
    $outline = array_fill(0,$team_num,1);

    for($i=0; $i<$team_num; $i++)
    {
        $oPR[$i][0] = $PR[$i][0] = $i + 1;
        $graph[$i][$i] = 1;
    }

    // row[0] : team_A, row[1] : team_B, row[2] : A_score, row[3] : B_score

    if($result){
        while($row = mysql_fetch_array($result)){

            if($row[2] > $row[3])
            {
                if($graph[$row[1] - 1][$row[1] - 1] == 1)
                {
                    //first time;
                    $graph[$row[1] - 1][$row[1] - 1]--;
                    $outline[$row[1] - 1]--;
                }
                
                //A is winner
                $graph[$row[1] - 1][$row[0] - 1]++;
                $outline[$row[1] - 1]++;
            }
            if($row[2] < $row[3])
            {
                
                if($graph[$row[0] - 1][$row[0] - 1] == 1)
                {
                    //first time;
                    $graph[$row[0] - 1][$row[0] - 1]--;
                    $outline[$row[0] - 1]--;
                }
                
             
                $graph[$row[0] - 1][$row[1] - 1]++;
                $outline[$row[0] - 1]++;
                   //B is winner
            }            
        }
    }

    $iter = 100;


    for($i=0; $i< $iter; $i++)
    {
        for ($j = 0; $j < $team_num; $j++)
        {
        	
            $x = 0.0;
            for ($k = 0; $k < $team_num; $k++)
            {

                if ($outline[$k] != 0) //dangling 처리
                {
                    $x += $oPR[$k][1] * $graph[$k][$j] / $outline[$k];
                }
            }

            $PR[$j][1] = ($x);
        }
		for ($j = 0; $j < $team_num; $j++)
		{
			$oPR[$j][1] = $PR[$j][1]; 
		}
		
    }

    //page rank


    foreach ($PR as $key => $row) {
    $rate[$key]  = $row[1];
    //$edition[$key] = $row['edition'];
    }

    array_multisort($rate, SORT_DESC, $PR);
    //다인자의 경우 
    //array_multisort($rate, SORT_DESC, $edition, SORT_ASC, $PR);    



   for($i=0; $i< $team_num; $i++){
        // 승률 = 승수 / (총경기수  - 무승부) 
        if($PR[$i][0] == 1)
        {
             $PR[$i][0] = "삼성";
        }
        if($PR[$i][0] == 2)
        {
             $PR[$i][0] = "넥센";
        }if($PR[$i][0] == 3)
        {
             $PR[$i][0] = "한화";
        }if($PR[$i][0] == 4)
        {
             $PR[$i][0] = "LG";
        }if($PR[$i][0] == 5)
        {
             $PR[$i][0] = "SK";
        }if($PR[$i][0] == 6)
        {
             $PR[$i][0] = "두산";
        }if($PR[$i][0] == 7)
        {
             $PR[$i][0] = "롯데";
        }if($PR[$i][0] == 8)
        {
             $PR[$i][0] = "KIA";
        }if($PR[$i][0] == 9)
        {
             $PR[$i][0] = "NC";
        }if($PR[$i][0] == 10)
        {
             $PR[$i][0] = "kt";
        }
    }

    echo json_encode($PR);

	mysql_close($con);
	


?>