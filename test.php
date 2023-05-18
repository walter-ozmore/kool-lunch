<!DOCTYPE html>
<html>
  <head>
    <title>Kool Lunches</title>

    <?php
      require realpath($_SERVER["DOCUMENT_ROOT"])."/res/head.php";
      require realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";
    ?>

    <style>
      .row {
        display: grid;
        grid-template-columns: 90% 10%;
        width: 100%;
      }

      .row p {
        margin: 0em;
        padding: 0em;
      }

      .row input {
        transform: scale(1.5);
        margin: auto;
      }
    </style>

    <script>
      function addPickup(form) {
        let ele = mkEle("p", "test");
        document.body.appendChild( ele );
      }

      onWindowLoad(function() {
        addPickup({formId: 12, });
      });
    </script>
  </head>

  <header>
    <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/res/header.php"; ?>
  </header>

  <body>
    <div class="content">
      <div class="row">
        <p>Lorem ipsum</p>
        <!-- <button>Picked Up</button> -->
        <input type="checkbox">
      </div>
    </div>
  </body>

  <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/res/footer.php"; ?>
</html>
