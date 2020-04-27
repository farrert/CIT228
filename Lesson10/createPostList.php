<?php
include 'BeginNav.php';

	//connect to server and select database; you may need it
	//$mysqli = mysqli_connect("localhost", "root", "", "acdiscforum");
	$mysqli = mysqli_connect("localhost", "lisabalbach_farrert", "CIT2020001", "lisabalbach_farrert");
	
	//if connection fails, stop script execution
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }

    $getAcTopics = "SELECT * FROM ac_topics;";
    $getAcRes = mysqli_query($mysqli, $getAcTopics) or die(mysqli_error($mysqli));

$xml = "<conversations>";
while($r = mysqli_fetch_array($getAcRes)){
    $xml .= "<ac_topics>";
    $xml .= "<disc_name>".$r['disc_name']."</disc_name>";
    $xml .= "<disc_creator>".$r['disc_creator']."</disc_creator>";
    $xml .= "</ac_topics>";
}
$xml .= "</conversations>";
$sxe = new SimpleXMLElement($xml);
$sxe->asXML("topics.xml");
echo "<h2>success!</h2>";
echo "<p><a href='viewPosts.php'>[View Topics List]</a>";
?>