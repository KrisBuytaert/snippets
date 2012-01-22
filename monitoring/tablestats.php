<?php

# Prefix String for Graphite 
$basename   = "mss.myss";
# MySQL Connection 
$dbname     = "myss";
$mysql_host = '127.0.0.1';
$mysql_user = 'myss';
$mysql_pass = '1234';
$mysql_port = 3306;
$mysql_ssl  = FALSE;   # Whether to use SSL to connect to MySQL.

# You should not need to change anything below this line.  


    # Connect to MySQL.

    $host_str  = "$mysql_host:$mysql_port";
    if ( !extension_loaded('mysql') ) {
        die("The MySQL extension is not loaded");
    }
    else {
        $conn = mysql_connect($host_str, $mysql_user, $mysql_pass);
    }
    if ( !$conn ) {
        die("MySQL: " . mysql_error());
    }
    
    $timestamp = time();
    $sql = "SHOW TABLES FROM $dbname";
    $result = @mysql_query($sql, $conn);
    $resultset = array();
    while ( $row = @mysql_fetch_array($result) ) {
        $resultset[] = $row;
        # print "$row[0] \n ";
        $detailsql = "
            select TABLE_ROWS, AVG_ROW_LENGTH,  DATA_LENGTH, MAX_DATA_LENGTH, INDEX_LENGTH  
            from information_schema.tables
            where table_name='$row[0]'";
        $detail = @mysql_query ($detailsql,$conn);
        $detailset = array();
        while ( $drow = @mysql_fetch_array($detail)) {
            $detailset = $drow;
            print "$basename.$row[0]-nrrows      $drow[0]  $timestamp \n"; 
            print "$basename.$row[0]-avglength   $drow[1]  $timestamp \n";
            print "$basename.$row[0]-datalength  $drow[2]  $timestamp \n";
            print "$basename.$row[0]-maxlength   $drow[3]  $timestamp \n"; 
            print "$basename.$row[0]-indexlength $drow[4]  $timestamp \n";
        }

    }

?>
