<?php
// Start the session
session_start();
?>
<?php
include 'connect.php';
doDB();
$master_id = $_SESSION['disc_id'];
//perform deletion from ac_topics
$del_topic_sql = "DELETE FROM ac_topics WHERE disc_id = $master_id;";
$del_topic_res = mysqli_query($mysqli, $del_topic_sql) or die(mysqli_error($mysqli));
// perform deletion from forum_posts
$del_post_sql = "DELETE FROM ac_responses WHERE disc_id = $master_id;";
$del_post_res = mysqli_query($mysqli, $del_post_sql) or die(mysqli_error($mysqli));

$display_block = "<hr><h2><em>Your conversation has been deleted.</em></h2>";
$display_block .= "<p><a href='discussionMenu.php'>Return to Menu</a></p><hr>";

//close connection
mysqli_close($mysqli);
include 'BeginNav.php';
?>
<!DOCTYPE html>
<html>
<head>
<title>Deletion Confirmation</title>
<link href="discussion.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php echo $display_block; 
include 'EndNav.php'; 
?>

