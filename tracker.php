<!DOCTYPE html>
<html>
  <head>
    <title>Tracker | Kool Lunches</title>
    <meta name="robots" content="noindex">
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

      .display {
        margin: 1em auto;
        max-width: 45em;
      }

      .display h2:not(:first-of-type) {
        margin-top: 2em;
      }

      #selectors input {
        font-size: 1.5em;
      }
    </style>

    <script src="/scripts/tracker.js"></script>
    <script>
      async function loadFreshData(unixTime) {
        $("#display").empty();
        // Math.floor(Date.parse("4-15-2024")/1000) // Test data
        // Stores jquery object to use later with a key of the location
        let storage = {};

        // Grab our data from the database
        let obj = await post("/ajax/admin.php", {
          function: 5,
          date: unixTime,
          startTime: 1704088800,
          endTime: 1735711200-1
        });
        console.log("Obj:", obj);
        let data = obj["data"];
        console.log("Data:", data);
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
            $("<input>", {type: "checkbox", checked: row.pickedUp}).click(function (){
              clickCheckbox($(this), row.formID, unixTime);
            }),
          ));
        }
      }

      async function clickCheckbox(checkbox, formID, unixTime) {
        // let checkbox = $(this); // Grab jquery object
        checkbox.prop('disabled', true); // Disable the checkbox to prevent double request

        // Submit the request and if there is an error then undo the checkbox
        let obj = await post("/ajax/admin.php", {
          function: 30,
            formID: formID,
            setTo: checkbox.is(':checked'),
            date: unixTime
        });
        console.log("Return data", obj);

        // Enable the checkbox
        checkbox.prop('disabled', false);
      }

      $(document).ready(async function() {
        // Check if we have a user logged in
        let user = await account_getUser();
        if(user == null) {
          Account.createWindow();
          return;
        }

        var today = new Date(); // Get today's date
        // Format the date as yyyy-MM-dd (required format for input type="date")
        var formattedDate = today.getFullYear() + '-' + ('0' + (today.getMonth() + 1)).slice(-2) + '-' + ('0' + today.getDate()).slice(-2);
        $('#set-date').val(formattedDate);


        $('#set-date').change(function() {
          let unixTime = 0;

          if(currentDateValue === formattedDate) {
            var currentDate = new Date(); // Get the current date and time
            var unixTimeMilliseconds = currentDate.getTime(); // Get the current Unix time (in milliseconds)
            unixTime = Math.floor(unixTimeMilliseconds / 1000); // Convert milliseconds to seconds (Unix time is typically in seconds)
          } else {
            let currentDateValue = $(this).val(); // Get the value of the input element
            let selectedDate = new Date(currentDateValue); // Convert selected date to a Date object
            unixTime = Math.floor(selectedDate.getTime() / 1000); // Convert selected date to Unix timestamp
          }

          loadFreshData(unixTime);
        });

        let selectedDate = new Date(); // Convert selected date to a Date object
        unixTime = Math.floor(selectedDate.getTime() / 1000); // Convert selected date to Unix timestamp
        loadFreshData(unixTime);
      });
    </script>
  </head>

  <header>
    <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/res/header.php"; ?>
  </header>

  <body>
    <div id="selectors" style="text-align: center;">
      <!-- Maybe we should add a selector for location -->
      <!-- <select id="location-selector" onchange="checkSelector()"></select><br> -->
      <input id="set-date" type="date">
    </div>


    <div id="display" class="display"></div>
  </body>

  <?php require realpath($_SERVER["DOCUMENT_ROOT"])."/res/footer.php"; ?>
</html>
