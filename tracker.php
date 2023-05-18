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
        margin: 1em 0em;
        border-bottom: 1px solid black;
        font-size: 1.5em;
        /* min-height: 5em; */
      }

      .row p {
        margin: auto 0em;
        padding: 0em;
      }

      .row input {
        transform: scale(2);
        margin: auto;
      }
    </style>

    <script>
      /**
       * Creates a row and adds it to the page
       */
      function addPickup(form) {
        // Grab adults
        let individuals = "";
        for(let ind of form["individuals"]) {
          if(ind["IsAdult"] == 1)
            individuals += ind["IndividualName"] + "<br>";
        }

        let checked = (form.pickedUp)? "checked": "";

        let innerHTML = `
          <p>${individuals}</p>
          <input type="checkbox" onchange="checkboxUpdate(this, ${form["FormId"]});" ${checked}>
        `;

        let row = mkEle("div", innerHTML);
        row.classList.add("row");
        document.getElementById("idk").appendChild( row );

        data["forms"][form["FormId"]]["rowEle"] = row;
      }


      /**
       * Called when a check box is clicked then updates the database the
       * checked rows will be moved to the bottom
       */
      function checkboxUpdate(checkbox, formId) {
        let args = {
          hasPickedUp: checkbox.checked,
          formId: formId
        };

        ajaxJson(
          "/ajax/tracker",
          function(obj) {

          },
          args
        );
      }

      function submit() {
        let args = [];

        for(let formId in data["forms"]) {
          let form = data["forms"][formId];
          console.log(form);
        }
      }


      /**
       * Bubble sort algorithm to sort the data by
       */
      function sort(data) {
        for(let i=0;i<n;i++) {
          for(let j=0;j<n-1;j++) {
            // Compare
            if(arr[j] < arr[j+1]) {
              let temp = arr[j];
              arr[j] = arr[j+1];
              arr[j+1] = temp;
            }
          }
        }
      }

      // Get the day of the week
      let args = {
        // day: ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"][new Date().getDay()]
        day: "Monday"
      }

      ajaxJson(
        "/ajax/fetch-data",
        function(obj) {
          data = obj;
          console.log(data);
          for(let formId in obj["forms"]) {
            let form = obj["forms"][formId];

            addPickup(form);
          }
        },
        args
      );
    </script>
  </head>

  <header>
    <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/res/header.php"; ?>
  </header>

  <body>
    <center><button onclick="submit()">Submit Changes</button></center>
    <div class="content" id="idk"></div>
  </body>

  <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/res/footer.php"; ?>
</html>
