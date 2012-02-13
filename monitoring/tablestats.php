<?php

# TODO make schema and tables to graph / monitor configurable , currently grabbing everythin :(
# Prefix String for Graphite  / maybe replace by uname -a + schema name 

$basename = 'mss.myss';

# This in case u don't wanna give arguments... these are defaults 

$mysql_user = 'mysqluser';
$mysql_pass = 'mysqlpass';
$mysql_port = 3306;
$mysql_ssl  = FALSE;   # Whether to use SSL to connect to MySQL.
$mysql_db = 'default';


$debug     = FALSE; # Define whether you want debugging behavior.

$no_http_headers = true;

# Checking if debug should be activated

if ( $debug ) {
   ini_set('display_errors', true);
   ini_set('display_startup_errors', true);
   ini_set('error_reporting', 2147483647);
}
else {
   ini_set('error_reporting', E_ERROR);
}



# Validating the arguments given

function validate_options($options) {
   # debug($options);
   $opts = array('host', 'user', 'pass', 'port', 'db');
   # Required command-line options
   foreach ( array('host') as $option ) {
      if ( !isset($options[$option]) || !$options[$option] ) {
         usage("Required option --$option is missing");
      }
   }

}

# Function usage

function usage($message) {
   global $mysql_user, $mysql_pass, $mysql_port, $mysql_db;

   $usage = <<<EOF
$message
Usage: php tablestats.php --host [OPTION]

   --host      Hostname to connect to; use host:port syntax to specify a port
               Use :/path/to/socket if you want to connect via a UNIX socket
   --user      MySQL username; defaults to $mysql_user if not given
   --pass      MySQL password; defaults to $mysql_pass if not given
   --port      MySQL port
   --db        What database to query

EOF;
   die($usage);
}

# Fetch the arguments...

function parse_cmdline( $args ) {
   $result = array();
   $cur_arg = '';
   foreach ($args as $val) {
      if ( strpos($val, '--') === 0 ) {
         if ( strpos($val, '--no') === 0 ) {
            # It's an option without an argument, but it's a --nosomething so
            # it's OK.
            $result[substr($val, 2)] = 1;
            $cur_arg = '';
         }
         elseif ( $cur_arg ) { # Maybe the last --arg was an option with no arg
            if ( $cur_arg == '--user' || $cur_arg == '--pass' || $cur_arg == '--port' ) {
               # Special case because Cacti will pass these without an arg
               $cur_arg = '';
            }
            else {
               die("No arg: $cur_arg\n");
            }
         }
         else {
            $cur_arg = $val;
         }
      }
      else {
         $result[substr($cur_arg, 2)] = $val;
         $cur_arg = '';
      }
   }
   if ( $cur_arg && ($cur_arg != '--user' && $cur_arg != '--pass' && $cur_arg != '--port') ) {
      die("No arg: $cur_arg\n");
   }
   debug($result);
   return $result;
}

# sdog's lovely code for fetching table information

function get_table_stats( $options ) {

 # Connect to MySQL.
   $user = isset($options['user']) ? $options['user'] : $mysql_user;
   $pass = isset($options['pass']) ? $options['pass'] : $mysql_pass;
   $port = isset($options['port']) ? $options['port'] : $mysql_port;
   $db = isset($options['db']) ? $options['db'] : $mysql_db;
   $basename = 'mss.myss';


# If there is a port, or if it's a non-standard port, we add ":$port" to the
   # hostname.
   $host_str  = $options['host']
              . (isset($options['port']) || $port != 3306 ? ":$port" : '');
   debug(array('connecting to', $host_str, $user, $pass));
   if ( !extension_loaded('mysql') ) {
      debug("The MySQL extension is not loaded");
      die("The MySQL extension is not loaded");
   }



# You should not need to change anything below this line.  

   $conn = mysql_connect($host_str, $user, $pass);
   if ( !$conn ) {
      die("MySQL: " . mysql_error());
   }

    # Set Time 
    $timestamp = time();
    $sql = "SHOW TABLES FROM $db";
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
}


## Script commandline behaviour


if (!isset($called_by_script_server)) {
   debug($_SERVER["argv"]);
   array_shift($_SERVER["argv"]); # Strip off this script's filename
   $options = parse_cmdline($_SERVER["argv"]);
   validate_options($options);
   $result = get_table_stats($options);
   debug($result);
   if ( !$debug ) {
      # Throw away the buffer, which ought to contain only errors.
      ob_end_clean();
   }
   else {
      ob_end_flush(); # In debugging mode, print out the errors.
   }

   # Split the result up and extract only the desired parts of it.
   #$output = array();
   debug(array("Final result", $output));
   #print_r($result);
}

# Debug logging funtion

function debug($val) {
   global $debug_log;
   if ( !$debug_log ) {
      return;
   }
   if ( $fp = fopen($debug_log, 'a+') ) {
      $trace = debug_backtrace();
      $calls = array();
      $i    = 0;
      $line = 0;
      $file = '';
      foreach ( debug_backtrace() as $arr ) {
         if ( $i++ ) {
            $calls[] = "$arr[function]() at $file:$line";
         }
         $line = array_key_exists('line', $arr) ? $arr['line'] : '?';
         $file = array_key_exists('file', $arr) ? $arr['file'] : '?';
      }
      if ( !count($calls) ) {
         $calls[] = "at $file:$line";
      }
      fwrite($fp, date('Y-m-d h:i:s') . ' ' . implode(' <- ', $calls));
      fwrite($fp, "\n" . var_export($val, TRUE) . "\n");
      fclose($fp);
   }
   else { # Disable logging
      print("Warning: disabling debug logging to $debug_log\n");
      $debug_log = FALSE;
   }
}

?>