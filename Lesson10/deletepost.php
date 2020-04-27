<?php
// Start the session
session_start();
?>
<?php
include 'connect.php';
doDB();

	//haven't seen the selection form, so show it
	$display_block = "<h1>Deletion Confirmation</h1>";
	$master_id = $_SESSION['disc_id'];
	//get parts of records
	$get_list_sql = "SELECT * FROM ac_topics WHERE disc_id = $master_id;";
	$get_list_res = mysqli_query($mysqli, $get_list_sql) or die(mysqli_error($mysqli));

	if (mysqli_num_rows($get_list_res) < 1) {
		//no records
		$display_block .= "<p><em>There was an error retrieving the Conversation!</em></p>";

	} else {
		//has a record, so display results for confirmation
		$rec = mysqli_fetch_array($get_list_res);
		$display_title = stripslashes($rec['disc_name']);
		$display_block .= "<p>Discussion Name: ".$display_title."</p>";
		$display_block .= "<p><a href='do_delete.php'>Confirm Deletion</a></p>";
		$display_block .= "<p><a href='discussionMenu.php'>Cancel Deletion and Return to Menu</a></p>";
	}
	//free result
	mysqli_free_result($get_list_res);

?>
<!DOCTYPE html>
<html>
<head>
<title>Delete Posting</title>
<link href="discussion.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php echo $display_block;
include 'EndNav.php';
?>

