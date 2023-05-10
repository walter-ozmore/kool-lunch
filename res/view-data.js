function createLogin() {
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
}

/**
 * Draw the form with the given id to the screen
 */
function showForm(formId) {
  let form = data["forms"][formId];

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

  header = mkEle("h3", "Adults");
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

  header = mkEle("h3", "Children");
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

    row = mkEle("tr");
    row.appendChild( mkEle("td", individual["IndividualName"]) );
    row.appendChild( mkEle("td", individual["Allergies"]) );

    let str = (individual["AllowPhotos"] == 0)? false: true;
    row.appendChild( mkEle("td", str) );
    table.appendChild(row);
  }
  document.getElementById("form-area").appendChild( div );

  alertTo(div);
}

/**
 * Alert the user to the element by scrolling to it and flashing the boxShadow
 *
 * @param {HTMLElement} ele
 */
function alertTo(ele) {
  // Scroll to the div
  ele.scrollIntoView({ behavior: "smooth" });

  let toggle = false;
  // ele.style.border = "2px solid yellow";
  ele.style.boxShadow = '0 0 5px yellow';
  const intervalId = setInterval(() => {
    ele.style.boxShadow = ((toggle)? "": "0 0 5px yellow");
    toggle = !toggle;
  }, 275);

  setTimeout(() => {
    clearInterval(intervalId);
    ele.style.boxShadow = "";
  }, 2000);
}

function drawData(obj) {
  data = obj;

  // If the user does not have permission then show error message
  if(obj.code != 0) {
    let str = `<p style="text-align: center">${obj.message}</p>`;
    document.getElementById("stats").innerHTML = str;

    createLogin();
    return;
  }

  // Remove the login div and reset the stats div if they exist
  let loginEle = document.getElementById("login");
  if( loginEle != undefined ) {
    document.getElementById("stats").innerHTML = "";
    document.body.removeChild( loginEle.parentElement );
  }

  // Draw stats
  let daily = {
    Monday   : {lunches: 0, allergies: []},
    Tuesday  : {lunches: 0, allergies: []},
    Wednesday: {lunches: 0, allergies: []},
    Thursday : {lunches: 0, allergies: []}
  };

  // Load form and create stats
  for(let formId in obj["forms"]) {
    let form = obj["forms"][formId];

    // Build rows children
    for(let key in form["individuals"]) {
      let individual = form["individuals"][key];
      if(individual["IsAdult"] == 1) continue;

      let allergies = individual["Allergies"];

      for(let key in daily) {
        if( form["Pickup"+key] == 1 ) {
          let day = daily[key];
          day.lunches += 1;

          if(allergies !== undefined && allergies.length > 0)
            day.allergies.push({allergies: allergies, formId: formId});
        }
      }
    }
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
    // Show allergies
    let html = "";
    for(let allergyObj of daily[key]["allergies"]) {
      console.log(allergyObj);
      let allergies = allergyObj.allergies;
      let formId    = allergyObj.formId;
      html += `<a style="margin-right: .25em" onclick="showForm(${formId})">${allergies}</a>`;
    }
    row.appendChild(mkEle("td", html));
    table.appendChild(row);
  }
}

function draw() {
  ajaxJson("/ajax/fetch-data.php", drawData);
}

var data;
draw();