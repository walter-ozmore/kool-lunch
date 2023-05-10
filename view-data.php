<!DOCTYPE html>
<html>
  <head>
    <title>Data | KoolLunches</title>
    <?php
      require realpath($_SERVER["DOCUMENT_ROOT"])."/res/head.php";
      require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";
    ?>
    <style>
      table {
        width: 100%;
        border-collapse: collapse;
      }
      td {
        border: 1px solid;
      }

      .content h2 {
        margin-bottom: 0em;
      }

      .content p {
        margin: 0em;
        padding: 0em;
      }
    </style>

    <script src="/account/version-3/lib.js"></script>
    <script>
      function drawData(obj) {
        // Draw stats
        let daily = {
          Monday   : {lunches: 0, allergies: []},
          Tuesday  : {lunches: 0, allergies: []},
          Wednesday: {lunches: 0, allergies: []},
          Thursday : {lunches: 0, allergies: []}
        };

        // If the user does not have permission then show error message
        if(obj.code != 0) {
          let str = `<p style="text-align: center">${obj.message}</p>`;
          document.getElementById("stats").innerHTML = str;

          // Create login page
          let loginDiv = mkEle("div");
          loginDiv.classList.add("content");
          document.body.appendChild(loginDiv);
          loginDiv.innerHTML = `
          <div class="login" id="login">
            <label>Username</label>
            <input type="text" name="uname">

            <label>Password</label>
            <input type="password" name="pword">
          </div>
          <center><button onclick="account_login(draw)">Login</button></center>
          `;

          return;
        }

        // Remove the login div and reset the stats div if they exist
        let loginEle = document.getElementById("login");
        if( loginEle != undefined ) {
          document.getElementById("stats").innerHTML = "";
          document.body.removeChild( loginEle.parentElement );
        }

        for(let formId in obj["forms"]) {
          let form = obj["forms"][formId];

          let div = mkEle("div");
          div.classList.add("content");

          let days = "Pickup Days: ";
          if( form["PickupMonday"]    == 1 ) days += "M ";
          if( form["PickupTuesday"]   == 1 ) days += "T ";
          if( form["PickupWednesday"] == 1 ) days += "W ";
          if( form["PickupThursday"]  == 1 ) days += "Th ";

          div.appendChild( mkEle("p", days) );

          var formattedTime = timeConverter(form["TimeSubmited"]);
          div.appendChild(mkEle("p", "Time Submited: " +formattedTime ));

          // Draw users
          let table, row, header;

          header = mkEle("h2", "Adults");
          header.style.textAlign = "center";
          div.appendChild( header );

          table = mkEle("table");
          div.appendChild(table);

          // Build header
          row = mkEle("tr");
          row.appendChild( mkEle("th", "Name") );
          row.appendChild( mkEle("th", "Phone Number") );
          row.appendChild( mkEle("th", "Remind Status") );
          table.appendChild(row);

          // Build rows adults
          for(let key in form["individuals"]) {
            let individual = form["individuals"][key];
            if(individual["IsAdult"] == 0) continue;

            row = mkEle("tr");
            row.appendChild( mkEle("td", individual["IndividualName"]) );
            row.appendChild( mkEle("td", individual["PhoneNumber"]) );
            row.appendChild( mkEle("td", individual["RemindStatus"]) );

            // let str = (individual["AllowPhotos"] == 0)? false: true;
            // row.appendChild( mkEle("td", str) );
            table.appendChild(row);
          }

          header = mkEle("h2", "Children");
          header.style.textAlign = "center";
          div.appendChild( header );

          table = mkEle("table");
          div.appendChild(table);

          // Build header
          row = mkEle("tr");
          row.appendChild( mkEle("th", "Name") );
          row.appendChild( mkEle("th", "Allergies") );
          row.appendChild( mkEle("th", "Allow Photos") );
          table.appendChild(row);

          // Build rows children
          for(let key in form["individuals"]) {
            let individual = form["individuals"][key];
            if(individual["IsAdult"] == 1) continue;

            let allergies = individual["Allergies"];

            // Track the lunches needed
            if( form["PickupMonday"] == 1 ) {
              let day = daily["Monday"];
              day.lunches += 1;

              if(allergies !== undefined && allergies.length > 0)
                day.allergies.push(allergies);
            }

            if( form["PickupTuesday"] == 1 ) {
              let day = daily["Tuesday"];
              day.lunches += 1;

              if(allergies !== undefined && allergies.length > 0)
                day.allergies.push(allergies);
            }

            if( form["PickupWednesday"] == 1 ) {
              let day = daily["Wednesday"];
              day.lunches += 1;

              if(allergies !== undefined && allergies.length > 0)
                day.allergies.push(allergies);
            }

            if( form["PickupThursday"] == 1 ) {
              let day = daily["Thursday"];
              day.lunches += 1;

              if(allergies !== undefined && allergies.length > 0)
                day.allergies.push(allergies);
            }


            row = mkEle("tr");
            row.appendChild( mkEle("td", individual["IndividualName"]) );
            row.appendChild( mkEle("td", individual["Allergies"]) );

            let str = (individual["AllowPhotos"] == 0)? false: true;
            row.appendChild( mkEle("td", str) );
            table.appendChild(row);
          }

          document.body.appendChild(div);

        }
        // Create stats screen
        table = mkEle("table");
        row = mkEle("tr");
        row.appendChild( mkEle("th", "Day") );
        row.appendChild( mkEle("th", "# Lunches") );
        row.appendChild( mkEle("th", "Allergies") );
        table.appendChild( row );
        document.getElementById("stats").appendChild(table);

        for(let key in daily) {
          row = mkEle("tr");
          row.appendChild( mkEle("td", key) );
          row.appendChild( mkEle("td", daily[key]["lunches"]) );
          row.appendChild( mkEle("td", daily[key]["allergies"]) );
          table.appendChild( row );
        }
      }

      function draw() {
        ajaxJson("/ajax/fetch-data.php", drawData);
      }

      var data;
      draw();
    </script>
  </head>

  <header>
    <?php
      include realpath($_SERVER["DOCUMENT_ROOT"])."/res/header.php";
    ?>
  </header>

  <body>
    <div id="stats" class="content"></div>
  </body>
</html>