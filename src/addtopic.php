<?php
include 'rolecheck.php';
session_start();
include 'dbFunctions.php';
$username = "";
$email    = "";
$errors = "";
unset ($_SESSION['topic_error']);
date_default_timezone_set("Asia/Singapore");

// connect to the database

if($_SERVER['REQUEST_METHOD'] != 'POST')
{
  header("Location: adminaccess.php");
  $errors = 'This file cannot be called directly.';
}

else
{
    $topic_name = mysqli_real_escape_string($link, $_POST['topic-name']);
    $topic_message = mysqli_real_escape_string($link, $_POST['topic-message']);
    $topic_cat = mysqli_real_escape_string($link, $_POST['topic-cat']);
    $_SESSION['avoidarray'] = array("<", ">");
    $topic_name = str_replace($_SESSION['avoidarray'], " ", $topic_name);
    $topic_message = str_replace($_SESSION['avoidarray'], " ", $topic_message);
    $topic_cat = str_replace($_SESSION['avoidarray'], " ", $topic_cat);

    //check for sign in status
        //a real user posted a real reply
        $sql = "INSERT INTO
                    topic(topic_name,
                          topic_message,
                          category_id,
                          topic_by_user)
                VALUES ('" . $topic_name . "',
                  '" . $topic_message . "',
                  '" . $topic_cat . "',
                  " . $_SESSION['user_id'] . ")";

        $result = mysqli_query($link, $sql);

        if(!$result)
        {
           header("Location: adminaccess.php");
            $errors = 'Your topic has not been saved, please try again later.';
        }
        else
        {
            header("Location: adminaccess.php");
            $errors = 'Your topic has been saved.';

        }
    }


$_SESSION['topic_error'] = $errors;
?>
