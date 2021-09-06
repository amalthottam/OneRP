<?php

session_start();
include 'dbFunctions.php';
$username = "";
$email    = "";
date_default_timezone_set("Asia/Singapore");

unset ($_SESSION['thread_error']);


// connect to the database
if($_SERVER['REQUEST_METHOD'] != 'POST')
{
  header("Location: threads.php?topic_id=" . htmlentities($_GET['id']));
  $errors = 'This file cannot be called directly.';
}
else
{
    //check for sign in status
    if(!($_SESSION['username']))
    {
      header("Location: threads.php?topic_id=" . htmlentities($_GET['id']));
        $errors = 'You must be signed in to post a reply.';
    }
else
{
    //check for sign in status
        //a real user posted a real reply
    $thread_name = mysqli_real_escape_string($link, $_POST['thread-name']);
    $thread_message = mysqli_real_escape_string($link, $_POST['thread-message']);
    $id = mysqli_real_escape_string($link, $_GET['id']);
    $_SESSION['avoidarray'] = array("<", ">", "%3C", "%3E", "javascript");
    $thread_name = str_replace($_SESSION['avoidarray'], " ", $thread_name);
    $thread_message = str_replace($_SESSION['avoidarray'], " ", $thread_message);
    $id = str_replace($_SESSION['avoidarray'], " ", $id);
    
        $sql = "INSERT INTO
                    thread(thread_name,
                          thread_message,
                          topic_id,
                          thread_by_user)
                VALUES ('" . $thread_name . "',
                  '" . $thread_message . "',
                        " . $id . ",
                        " . $_SESSION['user_id'] . ")";

        $result = mysqli_query($link, $sql);

        if(!$result)
        {
           header("Location: threads.php?topic_id=" . htmlentities($_GET['id']));
            $errors = 'Your thread has not been saved, please try again later.';
        }
        else
        {

          header("Location: threads.php?topic_id=" . htmlentities($_GET['id']));
        }
    }
}

$_SESSION['thread_error'] = $errors;
?>
