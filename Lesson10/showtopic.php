<?php
include 'connect.php';
include 'BeginNav.php';
doDB();

//check for required info from the query string
if (!isset($_GET['disc_id'])) {
	header("Location: topiclist.php");
	exit;
}

//create safe values for use
$safe_disc_id = mysqli_real_escape_string($mysqli, $_GET['disc_id']);

//verify the topic exists
$verify_topic_sql = "SELECT disc_name FROM ac_topics WHERE disc_id = '".$safe_disc_id."'";
$verify_topic_res =  mysqli_query($mysqli, $verify_topic_sql) or die(mysqli_error($mysqli));

if (mysqli_num_rows($verify_topic_res) < 1) {
	//this topic does not exist
	$display_block = "<p><em>There is no conversation here.<br/>
	Please <a href=\"topiclist.php\">try again</a>.</em></p>";
} else {
	//get the topic title
	while ($disc_info = mysqli_fetch_array($verify_topic_res)) {
		$disc_name = stripslashes($disc_info['disc_name']);
	}

	//gather the posts
	$get_posts_sql = "SELECT response_id, response_text, DATE_FORMAT(response_time, '%b %e %Y<br/>%r') AS dt_disc_time, responder FROM ac_responses WHERE disc_id = '".$safe_disc_id."' ORDER BY response_time ASC";
	$get_posts_res = mysqli_query($mysqli, $get_posts_sql) or die(mysqli_error($mysqli));

	//create the display string
	$display_block = <<<END_OF_TEXT
	<p>Showing posts for the <strong>$disc_name</strong> conversation:</p>
	<table>
	<tr>
	<th>AUTHOR</th>
	<th>Conversation</th>
	</tr>
END_OF_TEXT;

	while ($response_info = mysqli_fetch_array($get_posts_res)) {
		$response_id = $response_info['response_id'];
		$response_text = nl2br(stripslashes($response_info['response_text']));
		$response_time = $response_info['dt_disc_time'];
		$responder = stripslashes($response_info['responder']);

		//add to display
	 	$display_block .= <<<END_OF_TEXT
		<tr>
		<td>$responder<br/><br/>created on:<br/>$response_time</td>
		<td>$response_text<br/><br/>
		<a href="replytopost.php?response_id=$response_id"><strong>REPLY TO POST</strong></a></td>
		</tr>
END_OF_TEXT;
	}

	//free results
	mysqli_free_result($get_posts_res);
	mysqli_free_result($verify_topic_res);

	//close connection to MySQL
	mysqli_close($mysqli);

	//close up the table
	$display_block .= "</table>";
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Conversations</title>
<link href="discussion.css" rel="stylesheet" type="text/css" />
<style type="text/css">
	table {
		border: 1px solid black;
		border-collapse: collapse;
	}
	th {
		border: 1px solid black;
		padding: 6px;
		font-weight: bold;
		background: #ccc;
	}
	td {
		border: 1px solid black;
		padding: 6px;
		vertical-align: top;
	}
	.num_posts_col { text-align: center; }
</style>
</head>
<body>
<h1>Posts in Conversation</h1>
<?php echo $display_block; ?>
<p>Would you like to <a href="discussionMenu.php">return to main</a>?</p>
<?php include 'EndNav.php' ?>

