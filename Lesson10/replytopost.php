<?php
include 'connect.php';
include 'BeginNav.php';
doDB();

//check to see if we're showing the form or adding the post
if (!$_POST) {
   // showing the form; check for required item in query string
   if (!isset($_GET['response_id'])) {
      header("Location: topiclist.php");
      exit;
   }

   //create safe values for use
   $safe_response_id = mysqli_real_escape_string($mysqli, $_GET['response_id']);

   //still have to verify topic and post
   $verify_sql = "SELECT ACt.disc_id, ACt.disc_name FROM AC_responses
                  AS ACr LEFT JOIN AC_topics AS ACt ON ACr.disc_id =
                  ACt.disc_id WHERE ACr.response_id = '".$safe_response_id."'";

   $verify_res = mysqli_query($mysqli, $verify_sql)
                 or die(mysqli_error($mysqli));

   if (mysqli_num_rows($verify_res) < 1) {
      //this post or topic does not exist
      header("Location: topiclist.php");
      exit;
   } else {
      //get the topic id and title
      while($disc_info = mysqli_fetch_array($verify_res)) {
         $disc_id = $disc_info['disc_id'];
         $disc_name = stripslashes($disc_info['disc_name']);
      }
?>
      <!DOCTYPE html>
      <html>
      <head>
      <title>Post Your Reply in <?php echo $disc_name; ?></title>
      <link href="discussion.css" rel="stylesheet" type="text/css" />
      </head>
      <body>
      <h1>Post Your Reply in <?php echo $disc_name; ?></h1>
      <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <p><label for="responder">Your Name:</label><br/>
      <input type="text" id="responder" name="responder" size="40"
         maxlength="150" required="required"></p>
      <p><label for="response_text">Post Text:</label><br/>
      <textarea id="response_text" name="response_text" rows="8" cols="40"
         required="required"></textarea></p>
      <input type="hidden" name="disc_id" value="<?php echo $disc_id; ?>">
      <button type="submit" name="submit" value="submit">Add Post</button>
      </form>
      </body>
      </html>
<?php
      //free result
      mysqli_free_result($verify_res);

      //close connection to MySQL
      mysqli_close($mysqli);
   }

} else if ($_POST) {
      //check for required items from form
      if ((!$_POST['disc_id']) || (!$_POST['response_text']) ||
          (!$_POST['responder'])) {
          header("Location: topiclist.php");
          exit;
      }

      //create safe values for use
      $safe_disc_id = mysqli_real_escape_string($mysqli, $_POST['disc_id']);
      $safe_response_text = mysqli_real_escape_string($mysqli, $_POST['response_text']);
      $safe_responder = mysqli_real_escape_string($mysqli, $_POST['responder']);

      //add the post
      $add_post_sql = "INSERT INTO ac_responses (disc_id,response_text,
                       response_time,responder) VALUES
                       ('".$safe_disc_id."', '".$safe_response_text."',
                       now(),'".$safe_responder."')";
      $add_post_res = mysqli_query($mysqli, $add_post_sql)
                      or die(mysqli_error($mysqli));

      //close connection to MySQL
      mysqli_close($mysqli);

      //redirect user to topic
      header("Location: showtopic.php?disc_id=".$_POST['disc_id']);
      exit;
}
include 'EndNav.php'
?>

