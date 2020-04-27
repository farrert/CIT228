<?php
// Start the session
session_start();
include 'connect.php';
doDB();

if (!$_POST) {
	//haven't seen the selection form, so show it
	$display_block = "<h1>Edit Post</h1>";
	$master_id = $_SESSION['disc_id'];
	// $master_title=false;
	// $master_post_text=false;	
	
	// $master_title=$_SESSION["disc_name"];
	// $master_post_text = $_SESSION['response_text'];

	//get record from topics table
	$get_topic_sql = "SELECT * FROM ac_topics WHERE disc_id = $master_id;";
	$get_topic_res = mysqli_query($mysqli, $get_topic_sql) or die(mysqli_error($mysqli));
	// get record from topic posting table
	$get_post_sql = "SELECT * FROM ac_responses WHERE disc_id = $master_id;";
	$get_post_res = mysqli_query($mysqli, $get_post_sql) or die(mysqli_error($mysqli));

	if (mysqli_num_rows($get_topic_res) < 1) {
		//no records
		$display_block .= "<p><em>There was an error retrieving your topic!</em></p>";
	} else {
		//topic record exists, so display topic and post information for editing
		$rec = mysqli_fetch_array($get_topic_res);
		$display_id = stripslashes($rec['disc_id']);
		$display_title = stripslashes($rec['disc_name']);
		$display_block .= "<form method='post' action='".$_SERVER['PHP_SELF']."'>";
		$display_block .="<p>Conversation Name: <input type='text' id='disc_name' name='disc_name' value='".$display_title."'></p>";
		$postRec = mysqli_fetch_array($get_post_res);
		$display_post = stripslashes($postRec['response_text']);
		$display_block .="<p>Post Text: <textarea  style='vertical-align:text-top;' id='response_text' name='response_text'>".$display_post."</textarea></p>";
		$display_block .= "<button type='submit' id='change' name='change' value='change'>Change entry</button></p>";
		$display_block .="</form>";
	}
	//free result
	mysqli_free_result($get_post_res);
	mysqli_free_result($get_topic_res);
}
// posted form, so tables should update
else
{
	$clean_topic_title = mysqli_real_escape_string($mysqli, $_POST['disc_name']);
	$clean_post_text = mysqli_real_escape_string($mysqli, $_POST['response_text']);

	//create and issue the forum_topic update
	$update_topic_sql = "UPDATE ac_topics SET disc_name = '".$clean_topic_title ."' WHERE disc_id =".$_SESSION['disc_id'];
	$update_topic_res = mysqli_query($mysqli, $update_topic_sql) or die(mysqli_error($mysqli));

	//create and issue the forum_post update
	$update_post_sql = "UPDATE ac_responses SET response_text='" .$clean_post_text."' WHERE disc_id= ".$_SESSION['disc_id'];
	$update_post_res = mysqli_query($mysqli, $update_post_sql) or die(mysqli_error($mysqli));

	//close connection to MySQL
	mysqli_close($mysqli);

	//create nice message for user
	$display_block ="<h2>Your posting has been modified...</h2>";
	$display_block.="<p>The conversation name has been modified to: <strong><em>".$clean_topic_title."</em></strong><br>";
	$display_block.="The text has been modified to: <strong><em>".$clean_post_text."</em></strong></p>";

}
include 'BeginNav.php';
echo $display_block;
include 'EndNav.php';
?>

