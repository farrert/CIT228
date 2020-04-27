<?php
session_start();
include 'connect.php';
include 'BeginNav.php';
doDB();

//gather the topics
$get_topics_sql = "SELECT disc_id, disc_name, DATE_FORMAT(disc_time,  '%b %e %Y at %r') as fmt_disc_time, disc_creator FROM ac_topics ORDER BY disc_time DESC";
$get_topics_res = mysqli_query($mysqli, $get_topics_sql) or die(mysqli_error($mysqli));

if (mysqli_num_rows($get_topics_res) < 1) {
	//there are no topics, so say so
	$display_block = "<p><em>No conversations exist.</em></p>";
} else {
	//create the display string
    $display_block = <<<END_OF_TEXT
    <table style="border: 1px solid black; border-collapse: collapse;">
    <tr>
    <th>Conversation</th>
    <th># of responses</th>
    </tr>
END_OF_TEXT;

	while ($disc_info = mysqli_fetch_array($get_topics_res)) {
		$disc_id = $disc_info['disc_id'];
		$disc_name = stripslashes($disc_info['disc_name']);
		$disc_time = $disc_info['fmt_disc_time'];
		$disc_creator = stripslashes($disc_info['disc_creator']);

		//get number of posts
		$get_num_posts_sql = "SELECT COUNT(response_id) AS response_count FROM ac_responses WHERE disc_id = '".$disc_id."'";
		$get_num_posts_res = mysqli_query($mysqli, $get_num_posts_sql) or die(mysqli_error($mysqli));

		while ($disc_info = mysqli_fetch_array($get_num_posts_res)) {
			$num_posts = $disc_info['response_count'];
		}

		//add to display
		$display_block .= <<<END_OF_TEXT
		<tr>
		<td><a href="showtopic.php?disc_id=$disc_id"><strong>$disc_name</strong></a><br/>
		Created on $disc_time by $disc_creator</td>
		<td class="num_posts_col">$num_posts</td>
		</tr>
END_OF_TEXT;
	}
	//free results
	mysqli_free_result($get_topics_res);
	mysqli_free_result($get_num_posts_res);

	//close connection to MySQL
	mysqli_close($mysqli);

	//close up the table
	$display_block .= "</table>";
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Conversations in My Forum</title>
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
	}
	.num_posts_col { text-align: center; }
</style>
</head>
<body>
<h1>Conversations in Forum</h1>
<?php echo $display_block; ?>
<p>Would you like to <a href="addtopic.php">start a conversation?</a>?</p>
<p>Would you like to <a href="discussionMenu.php">return to main</a>?</p>
<?php include 'EndNav.php' ?>

