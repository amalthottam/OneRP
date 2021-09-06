<?php

session_start();
include 'dbFunctions.php';
$username = "";
$email    = "";
date_default_timezone_set("Asia/Singapore");
unset ($_SESSION['post_error']);


// connect to the database
if($_SERVER['REQUEST_METHOD'] != 'POST')
{
  header("Location: post.php?thread_id=" . htmlentities($_GET['id']));
  $errors = 'This file cannot be called directly.';
}
else
{
    //check for sign in status
    if(!($_SESSION['username']))
    {
      header("Location: post.php?thread_id=" . htmlentities($_GET['id']));
        $errors = 'You must be signed in to post a reply.';
    }
else
{
    $reply_content = mysqli_real_escape_string($link, $_POST['reply-content']);
    $id = mysqli_real_escape_string($link, $_GET['id']);
    $_SESSION['avoidarray'] = array("<", ">", "%3C", "%3E", "javascript");
    $reply_content = str_replace($_SESSION['avoidarray'], "", $reply_content);
    $id = str_replace($_SESSION['avoidarray'], " ", $id);

    //check for sign in status
        //a real user posted a real reply
        $sql = "INSERT INTO
                    post(message,
                          post_thread_id,
                          post_by_user)
                VALUES ('" . $reply_content . "',
                        " . $id . ",
                        " . $_SESSION['user_id'] . ")";

        $result = mysqli_query($link, $sql);

        if(!$result)
        {
          header("Location: threads.php?topic_id=" . htmlentities($_GET['id']));
            $errors = 'Your reply has not been saved, please try again later.';
        }
        else
        {
          header("Location: post.php?thread_id=" . htmlentities($_GET['id']));
        }
    }
}

$_SESSION['post_error'] = $errors;
?>
