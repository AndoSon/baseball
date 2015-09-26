<?php

	header('Content-Type: application/json; charset=utf-8');


    $handle = fopen("db_crawling_log.txt", "r");
    if ($handle)
    {
        $queryFull = fgets($handle);
    } 

	$con = mysql_connect("localhost","root","4575");
	mysql_query("SET character_set_results=utf8", $con);
    mb_language('uni'); 
    mb_internal_encoding('UTF-8');
    
	mysql_select_db("test",$con);

    mysql_query("set names 'utf8'",$con);

	$result = mysql_query($queryFull);
	


?>