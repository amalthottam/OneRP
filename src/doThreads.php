<?php
include 'dbFunctions.php';
date_default_timezone_set("Asia/Singapore");



// connect to the database
        //do a query for the topics
$topic_id = mysqli_real_escape_string($link, $_GET['topic_id']);
$_SESSION['avoidarray'] = array("<", ">", "%3C", "%3E", "javascript");
$topic_id = str_replace($_SESSION['avoidarray'], " ", $topic_id);
    
          $sql = "SELECT
                      thread.thread_id,
                      thread.thread_name,
                      thread.thread_message,
                      thread.thread_timestamp,
                      thread.topic_id,
                      user.username, 
                      user.user_id
                  FROM  thread
                  INNER JOIN user on user.user_id = thread.thread_by_user
                  WHERE
                  thread.topic_id = " . $topic_id;



        $result = mysqli_query($link, $sql);


?>
