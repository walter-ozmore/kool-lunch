<!DOCTYPE html>
<html>
  <head>
    <title>Kool Lunches</title>
    <link rel="stylesheet" href="/res/rf-gallery.css"/>
    <?php
      require realpath($_SERVER["DOCUMENT_ROOT"])."/res/head.php";
      include realpath($_SERVER["DOCUMENT_ROOT"])."/res/secret.php";
      require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";
      require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/Parsedown.php";

      /**
       * Loads and converts the FAQ data in to a more useable array of answer &
       * questions
       */
      function loadFAQ() {
        $data = [];
        $setting = Database::getSetting("faqText");
        $markdown = $setting["value"];

        // Split the string into an array of lines using the newline character (\n) as the delimiter
        $lines = explode("\n", $markdown);

        // Iterate over each line
        $num = 0; // If zero then nothing, if one then question, if two then answer
        $question = "";
        $answer = "";
        foreach ($lines as $line) {
          if(strpos($line, "<Question>") === 0) {
            if($num == 2) {
              // This is the start of another question append to data
              $data[] = ["question"=>$question, "answer"=>$answer];
              $question = "";
              $answer = "";
            }
            $num = 1;
            continue;
          }

          if(strpos($line, "<Answer>") === 0) {
            $num = 2;
            continue;
          }

          if($num == 1) $question .= $line."\n\n";
          if($num == 2) $answer   .= $line."\n\n";
        }

        if(strlen($question) > 0 && strlen($answer)) {
          // This is the start of another question append to data
          $data[] = ["question"=>$question, "answer"=>$answer];
          $question = ""; $answer = "";
        }

        return $data;
      }

      $Parsedown = new Parsedown();
    ?>

    <script>
      function faqClick(element) {
        let ele = $(element);
        let faqDiv = ele.parent();
        let questionEle = faqDiv.children().eq(0);
        let answerEle = faqDiv.children().eq(1);
        answerEle.toggle(); // Toggles show/hide on the element
      }

      function resizeIframe(obj) {
        obj.style.height = obj.contentWindow.document.documentElement.scrollHeight + 'px';
      }
    </script>
  </head>

  <header>
    <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/redesign/header.php"; ?>
  </header>

  <body>
    <div class="content">
        <!-- Gallery -->
        <div class="img-gallery"><center>
            <img id="current-img" src="" alt="Gallery loading"></center>
        </div>
        <script src="/res/rf-gallery.js"></script>
        <script>
          let galleryImages = {
            "/res/images/kl-1.jpg" : "Kool Lunches Volunteers",
            "/res/images/kl-2.jpg" : "Kool Lunches Volunteers",
            "/res/images/kl-3.jpg" : "Kool Lunches Volunteers",
            "/res/images/kl-4.jpg" : "Kool Lunches Volunteers",
            "/res/images/kl-5.jpg" : "Kool Lunches Volunteers"
          };
          let gallery = new Gallery(galleryImages, "auto", 2000);
        </script>
      <div class="banner"><h1 class="lexend-deca-header stroke">OUR STORY</h2></div>
      <div class="section lexend-body">
        <?php
          $setting = Database::getSetting("homePageText");
          $markdown = $setting["value"];

          echo $Parsedown->text($markdown);
        ?>
      </div>
    </div>

    <div class="content">
      <div class="banner"><h1 class="lexend-deca-header stroke">FAQ'S</h2></div>
      <div id="faq" class="faq section">
        <?php
          $qnaData = loadFAQ();
          foreach($qnaData as $qna) {
            $question = $Parsedown->text( $qna["question"] );
            $answer   = $Parsedown->text( $qna["answer"] );

            echo "<div class='faqElement'>
              <div class='question lexend-bold' style='display: flex; justify-content: space-between;' onclick='faqClick(this)'>
                $question
                <p style='text-align: right;'>+</p>
              </div>
              <div class='answer lexend-body' style='display: none'>
                $answer
              </div>
              <hr>
            </div>";
          }
        ?>
        <p class="lexend-body center-text" style="margin-bottom: 0em;">
          All other questions can be sent through
        </p>
        <p class="lexend-body link-row center-text">
          <a href="https://www.facebook.com/koollunches/" target="_blank" rel="noreferrer noopener">Facebook</a>
          <a href="https://www.remind.com/join/koollunch5" target="_blank" rel="noreferrer noopener">Remind</a>
          <a href="#">Email</a>
        </p>
      </div>
    </div>
  </body>

  <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/redesign/footer.php"; ?>
</html>
