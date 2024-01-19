<!DOCTYPE html>
<html>
  <head>
    <title>Tracker | Kool Lunches</title>

    <?php
      require realpath($_SERVER["DOCUMENT_ROOT"])."/res/head.php";
      require realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";
    ?>

    <style>
      /* Row/Form of information */
      .row {
        display: grid;
        grid-template-columns: 65% 25% 10%;
        padding: .5em .25em;
        border-bottom: 1px solid black;
        font-size: 1.5em;
        /* min-height: 5em; */
      }

      /* Name */
      .row p {
        margin: auto 0em;
        padding: 0em;
      }

      /* Check boxes */
      .row input {
        transform: scale(2);
        margin: auto;
        height: 100%;
      }

      /* Sets the right side of the data to align right */
      .row > span {
        padding: 0em .5em; /* This is kind of a bandaid fix, need to be better */
        text-align: right;
      }

      .quantity {
        padding-right: .75em;
        height: 100%;
        text-align: right;
      }

      #selectors input {
        font-size: 1.5em;
      }
    </style>

    <script src="/scripts/tracker.js"></script>
    <script>
      $(document).ready(async function() {
        // Check if we have a user logged in
        let user = await account_getUser();
        if(user == null) {
          Account.createWindow();
          return;
        }

        let clickCheckbox = async ()=>{
          let checkbox = $(this); // Grab jquery object
          checkbox.prop('disabled', true); // Disable the checkbox to prevent double request

          // Submit the request and if there is an error then undo the checkbox
          await $.ajax({ type: "POST", url: "/ajax/admin.php",
            data: JSON.stringify({ function: 6 }),
            contentType: "application/json",
            error: function() {
              // Flip the checkbox back if it fails
              $('#myCheckbox').prop('checked', !checkbox.is(':checked'));
            }
          });

          // Enable the checkbox
          checkbox.prop('disabled', false);
        };

        // Stores jquery object to use later with a key of the location
        let storage = {};

        // Grab our data from the database
        let data = await post("/ajax/admin.php", {
          function: 5,
          date: 1717445432,
        });
        console.log(data);
        for(let row of data) {
          let div; // Stores the div that we put the rows in to

          // If there isnt storage for this location, then we should make it
          if(row["location"] in storage == false) {
            storage[row["location"]] = $("<div>", {class: "content"});
            $("#display").append($("<h2>").text(row["location"]), storage[row["location"]]);
          }

          // Select the jquery object
          div = storage[row["location"]];

          // Write the row to the jquery object
          div.append($("<div>", {class: "row"}).append(
            $("<p>").text(row.individualName),
            $("<span>", {class: "quantity"}).text(("lunchesNeeded" in row)? row.lunchesNeeded + "x": "-"),
            $("<input>", {type: "checkbox", checked: row.pickedUp, disabled: true}).click(clickCheckbox),
          ));
        }
        console.log(data);
      });
    </script>
  </head>

  <header>
    <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/res/header.php"; ?>
  </header>

  <body>
    <div id="selectors" style="text-align: center; display: none;">
      <!-- <select id="location-selector" onchange="checkSelector()"></select><br> -->
      <input type="date" id="date-selector">
    </div>


    <div id="display"></div>
  </body>

  <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/res/footer.php"; ?>
</html>
