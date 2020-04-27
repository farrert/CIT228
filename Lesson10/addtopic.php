<?php 

include 'BeginNav.php' ?>
<!DOCTYPE html>
<html>
<head>
<title>Begin Conversation</title>
<link href="discussion.css" rel="stylesheet" type="text/css" />
</head>
<body>
<h1>Start a conversation</h1>
<form method="post" action="do_addtopic.php">

<p><label for="disc_creator">Your Name:</label><br/>
<input type="text" id="disc_creator" name="disc_creator" size="40"
        maxlength="150" required="required" /></p>

<p><label for="disc_name">Conversation Title:</label><br/>
<input type="text" id="disc_name" name="disc_name" size="40"
        maxlength="150" required="required" /></p>

<p><label for="response_text">Add to conversation:</label><br/>
<textarea id="response_text" name="response_text" rows="8"
          cols="40" ></textarea></p>

<button type="submit" name="submit" value="submit">Start new conversation</button>
<input type="button" name="menu" id="menu" value="Return to Menu" onclick="location.href='discussionMenu.php'">
</form>
<?php include 'EndNav.php' ?>