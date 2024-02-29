<!DOCTYPE html>
<html>
  <head>
    <title>Test Cases | Kool Lunches</title>
    <meta name="robots" content="noindex">
    <?php
      require realpath($_SERVER["DOCUMENT_ROOT"])."/res/head.php";
    ?>

    <script>
      async function buttonClick() {
        let data = await post("/ajax/admin.php", {
          function: 16,
          formID: 246
        });

        console.log(data);
      }

      async function postTest() {
        let obj = JSON.parse($("#postTestJson").val());
        let data = await post("/ajax/admin.php", obj);
        console.log(data);
      }
    </script>
  </head>

  <header>
    <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/res/header.php"; ?>
  </header>

  <body>
    <div class="content">
      <p>User logged in as <span name="account-username"></span></p>
      <button onclick="buttonClick()">Test</button>
    </div>
    <div class="content">
      <h2>Manual Post</h2>
      <div>
        <label>Json</label>
        <textarea id="postTestJson" rows="4" cols="50"></textarea>
      </div>
      <button onclick="postTest()">Test</button>
    </div>
  </body>

  <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/res/footer.php"; ?>
</html>
