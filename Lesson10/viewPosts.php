<?php
include 'BeginNav.php';
$xmlList = simplexml_load_file("topics.xml") or die("Error: Cannot Create");
foreach($xmlList->ac_topics as $act){
    $topic = $act->disc_name;
    $author = $act->disc_creator;

echo "<div style='width:40%'><p style='color:black;border-bottom:2px yellow solid;font-weight:900;'>Topic: " . $topic . " Author: " . $author . "<br></p></div>";
}
?>