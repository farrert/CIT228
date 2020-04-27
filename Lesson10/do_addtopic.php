<?php
session_start();
include 'connect.php';
include 'BeginNav.php';
doDB();



//check for required fields from the form
if ((!$_POST['disc_creator']) || (!$_POST['disc_name']) || (!$_POST['response_text'])) {
	header("Location: addtopic.php");
	exit;
}

//create safe values for input into the database
$clean_disc_creator = mysqli_real_escape_string($mysqli, $_POST['disc_creator']);
$clean_disc_name = mysqli_real_escape_string($mysqli, $_POST['disc_name']);
$clean_response_text = mysqli_real_escape_string($mysqli, $_POST['response_text']);

//create and issue the first query
$add_topic_sql = "INSERT INTO ac_topics (disc_name, disc_time, disc_creator) VALUES ('".$clean_disc_name ."', now(), '".$clean_disc_creator."')";

$add_topic_res = mysqli_query($mysqli, $add_topic_sql) or die(mysqli_error($mysqli));

//get the id of the last query
$disc_id = mysqli_insert_id($mysqli);
$_SESSION["disc_id"]=$disc_id;
$_SESSION['disc_name']=$clean_disc_name;
$_SESSION['response_text']=$clean_response_text;

//create and issue the second query
$add_post_sql = "INSERT INTO ac_responses (disc_id, response_text, response_time, responder) VALUES ('".$disc_id."', '".$clean_response_text."',  now(), '".$clean_disc_creator."')";

$add_post_res = mysqli_query($mysqli, $add_post_sql) or die(mysqli_error($mysqli));

//close connection to MySQL
mysqli_close($mysqli);

//create nice message for user
$display_block = "<p>The <strong>".$_POST["disc_name"]."</strong> converstion has begun!</p>";
?>
<!DOCTYPE html>
<html>
<head>
<title>New Conversation Started</title>
<link href="discussion.css" rel="stylesheet" type="text/css" />
</head>
<body>
<h1>New conversation started</h1>
<?php echo $display_block; ?>
<form>
<input type="button" name="menu" id="menu" value="Return to Menu" onclick="location.href='discussionMenu.php'">
<input type='button' name='edit' id='edit' value='Edit Post' onclick='location.href="editpost.php"'>
<input type='button' name='delete' id='delete' value='Delete Post' onclick='location.href="deletepost.php"'>
</form>
<?php include 'EndNav.php' ?>
