<?php
// error_reporting(E_ALL);
// ini_set('display_errors', '1');


// date_default_timezone_set("America/New_York");
// header("Cache-Control: no-cache");
// header("Content-Type: text/event-stream");

// $counter = rand(1, 10);
// while (true) {
//   // Every second, send a "ping" event.
  
//   echo "event: ping\n";
//   $curDate = date(DATE_ISO8601);
//   echo 'data: {"time": "' . $curDate . '"}';
//   echo "\n\n";
  
//   // Send a simple message at random intervals.
  
//   $counter--;
  
//   if (!$counter) {
//     echo 'data: This is a message at time ' . $curDate . "\n\n";
//     $counter = rand(1, 10);
//   }
  
//   ob_end_flush();
//   flush();
//   sleep(1);
// }

?>