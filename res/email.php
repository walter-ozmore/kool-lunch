<?php
  // Test error reporting, do not ship with these uncommented
  // error_reporting(E_ALL);
  // ini_set('display_errors', '1');

  /**
   * Sends an email to the email $to with a subject and a message. If $isHTML is
   * set to true then the email message should be in an HTML format.
   * 
   * @param to The email address that will receive the email
   * @param subject The subject of the email
   * @param message The message of the email, if the email is an HTML email then this should be the HTML text.
   * @param isHTML Boolean that indicates if the send email should be HTML or plain text
   * 
   * @return code
   *  - 0: Success
   *  - 1: General Error
   */
  function sendEmail($to, $subject, $message, $isHTML=False) {
    // Define the sender and recipient's emails.
    $from = 'noreply@koollunches.org';

    // Define the headers for the email.
    $headers = 'From: ' . $from . "\r\n" .
              'Reply-To: ' . $from . "\r\n" .
              'X-Mailer: PHP/' . phpversion();

    if($isHTML) {
      // Set the content-type to HTML
      $headers .= "MIME-Version: 1.0\r\n";
      $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    }

    // Use the mail() function to send the email.
    if (mail($to, $subject, $message, $headers)) {
      return 0;
    }
    return 1;
  }

  // This code will only run when the url is "/email.php" this is only for
  // testing and making sure that this code works properly
  if (parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) == '/res/email.php') {
    if(isset($_GET["email"]) == false) {
      echo "No email was provided";
      exit();
    }

    $email = $_GET["email"];
    sendEmail(
      $email,
      "Test Email.",
      "<html><body>This is a test email from the following site to ensure that
      emails are working properly here is an inline link for you to enjoy
      <a href=\"https://www.koollunches.org/\">inline</a></body></html>",
      True
    );

    echo "A test email has been sent to $email";
  }
?>