<!DOCTYPE html>
<html>
  <head>
    <title>Sign Up | KoolLunches</title>

    <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/res/head.php"; ?>

    <script type="text/javascript">
      // Detect the user's language
      var userLang = navigator.language || navigator.userLanguage;
      // alert ("The language is: " + userLang);
    </script>
  </head>

  <header>
    <?php include realpath($_SERVER["DOCUMENT_ROOT"])."/res/header.php"; ?>
  </header>

  <body>
    <div class="content">
      <!-- Form header/Information -->
      <div>
        <center>
          <h1>Sign Up</h1>

          <p><b><u>Dates served are May 31st-August 4th Monday-Thursdays</u></b></p>
        </center>
      </div>


      <!-- Warning -->
      <div>
        <p>Sack lunches will be prepared for pick up at local parks and areas in Bonham. Lunches will be four days a week, Monday through Thursday, with the exception of July 4th in order to observe Independence Day.</p>

        <p>If you are interested in your child or children participating please fill out the questionnaire submit.</p>

        <p>If you have any questions, please contact The Kool Lunches Program at <a href="mailto:thekoollunchesprogram@gmail.com">thekoollunchesprogram@gmail.com</a> or message us on <a href="https://www.facebook.com/thekoollunchesprogram/">Facebook</a>.</p>

        <p>I understand that I do not let the Kool Lunches Program know before <b>10:55</b> that I will not be picking up lunches that day, my name will removed until contact the Kool Lunches Program to begin receiving lunches again.</p>
        <center>
          <!-- Button will hide the warning -->
          <button onclick="showForm()">I understand</button>
        </center>
      </div>
    </div>
  </body>

  <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/res/footer.php"; ?>
</html>