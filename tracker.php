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

      .filter select {
        font-size: 1.5em;
      }
    </style>

    <script src="/res/data.js"></script>
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
          <p onclick="createFormElement(${form["FormId"]})">${individuals}</p>
          <input type="checkbox" onchange="checkboxUpdate(this, ${form["FormId"]});" ${checked}>
        `;

        let row = mkEle("div", innerHTML);
        row.classList.add("row");

        let location = form["Location"];
        document.getElementById(location).appendChild( row );

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

        ajaxJson("/ajax/tracker", null, args);
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

      /**
       * Draws the rows to the screen
       */
      function drawRows() {
        for(let formId in data["forms"]) {
          let form = data["forms"][formId];

          addPickup(form);
        }
      }

      function addLocation(location) {
        let option = mkEle("option", location);
        option.value = location;
        document.getElementById("location-selector").appendChild(option);

        let group = mkEle("div");
        group.classList.add("content");
        group.id = location;
        group.style.display = "none";
        document.getElementById("display").appendChild(group);
      }

      function checkSelector() {
        let selector = document.getElementById("location-selector");
        // Hide all
        let children = document.getElementById("display").childNodes;
        for(let child of children) {
          child.style.display = "none";
        }

        document.getElementById( selector.value ).style.display = "block";
      }

      // Get the day of the week
      let args = {
        day: ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"][new Date().getDay()]
      }

      ajaxJson(
        "/ajax/fetch-data",
        function(obj) {
          data = obj;
          console.log(data);
          for(let location of data["locations"]) {
            addLocation(location);
          }
          checkSelector();
          drawRows();
        },
        args
      );
    </script>
  </head>

  <header>
    <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/res/header.php"; ?>
  </header>

  <body>
    <div class="content filter" style="text-align: center;">
      <select id="location-selector" onchange="checkSelector()"></select>
    </div>


    <div id="display"></div>
  </body>

  <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/res/footer.php"; ?>
</html>
