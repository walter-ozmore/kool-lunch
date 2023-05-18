<!DOCTYPE html>
<html>
  <head>
    <title>Kool Lunches</title>

    <?php
      require realpath($_SERVER["DOCUMENT_ROOT"])."/res/head.php";
      require realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";
    ?>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <script>
      function resetPassword(rcode) {
        let div = document.getElementById("login");
        let uname = div.querySelector("[name='uname']").value;
        let pword = div.querySelector("[name='pword']").value;
        let rword = div.querySelector("[name='rword']").value;

        // Set up ajax
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
          if (this.readyState != 4 || this.status != 200) return;
          let data = JSON.parse(this.responseText);
          if(returnFunction != null)
            returnFunction(data);
        };

        xhttp.open("POST", "/account/version-3/ajax/reset-password", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        let args = `uname=${uname}&pword=${pword}&rcode=${rcode}`;
        xhttp.send(args);
      }
    </script>
  </head>

  <header>
    <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/res/header.php"; ?>

    <style>
      .account {
        width: 15em;
      }
      .account h2 {
        margin-top: 0em;
      }
      .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 48;
        font-size: 5em;
      }
    </style>
  </header>

  <body>
    <div class="content account" style="text-align: center">
      <span class="material-symbols-outlined">lock_reset</span>
      <h2>Reset Password</h2>
      <input type="text" placeholder="Username"><br>

      <input type="password" placeholder="New Password"><br>

      <input type="password" placeholder="Repeat Password"><br><br>

      <button>Reset Password</button>
    </div>
  </body>

  <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/res/footer.php"; ?>
</html>
