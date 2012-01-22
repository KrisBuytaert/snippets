<?php

# TODO externalize config,   load mysql config from a config file 
# TODO make schema and tables to graph / monitor configurable , currently grabbing everythin :(

# Prefix String for Graphite  / maybe replace by uname -a + schema name 
$basename   = "mss.myss";
# MySQL Connection 
$dbname     = "myss";


# 
$mysql_host = '127.0.0.1';
$mysql_user = 'myss';
$mysql_pass = '1234';
$mysql_port = 3306;




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

    # Set Time 
    $timestamp = time();
    $sql = "SHOW TABLES FROM $dbname";
    $result = mysql_query($sql, $conn);
    while ( $row = mysql_fetch_array($result) ) {
        # print "$row[0] \n ";
        $detailsql = "
            select TABLE_ROWS, AVG_ROW_LENGTH,  DATA_LENGTH, MAX_DATA_LENGTH, INDEX_LENGTH  
            from information_schema.tables
            where table_name='$row[0]'";
        $detail = mysql_query ($detailsql,$conn);
        while ( $drow = mysql_fetch_array($detail)) {
            print "$basename.$row[0]-nrrows      $drow[0]  $timestamp \n"; 
            print "$basename.$row[0]-avglength   $drow[1]  $timestamp \n";
            print "$basename.$row[0]-datalength  $drow[2]  $timestamp \n";
            print "$basename.$row[0]-maxlength   $drow[3]  $timestamp \n"; 
            print "$basename.$row[0]-indexlength $drow[4]  $timestamp \n";
        }

    }

?>
