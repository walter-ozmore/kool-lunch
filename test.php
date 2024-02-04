<!DOCTYPE html>
<html>
  <head>
    <title>Test Cases | Kool Lunches</title>

    <?php
      require realpath($_SERVER["DOCUMENT_ROOT"])."/res/head.php";
    ?>

    <script>
      async function buttonClick() {
        let data = await post("/ajax/admin.php", {
          function: 5,
          uid: 1
        });
        console.log(data);
      }

    </script>
  </head>

  <header>
    <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/res/header.php"; ?>
  </header>

  <body>
    <div class="content">
      <button onclick="buttonClick()">Test</button>
    </div>
  </body>

  <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/res/footer.php"; ?>
</html>
