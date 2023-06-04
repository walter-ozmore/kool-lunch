function createFormElement(index, isNotification = true) {
  if(data == undefined) {
    console.log("Data is not found");
    fetchData(null, createFormElement(index));
    return;
  }

  // Create a container to hold elements
  let div = mkEle("div");
  div.classList.add("content");
  if(isNotification) {
    div.classList.add("notification");
    // Add close button
    let closeButton = mkEle("button", "X");
    closeButton.onclick = function() {div.parentElement.removeChild(div); }
    closeButton.style.float = "right";
    div.appendChild(closeButton);
  }


  /************************************************
   * Draw header information
   ***********************************************/
  let form = data["forms"][index];
  if(form == undefined) return null;

  div.appendChild(mkEle("h2", "Form #" + form["FormId"]));

  // Convert days to a string for the user
  let days = "Pickup Days: ";
  if( form["PickupMonday"]    == 1 ) days += "M ";
  if( form["PickupTuesday"]   == 1 ) days += "T ";
  if( form["PickupWednesday"] == 1 ) days += "W ";
  if( form["PickupThursday"]  == 1 ) days += "Th ";
  div.appendChild( mkEle("p", days) );

  var formattedTime = timeConverter(form["TimeSubmited"]);
  div.appendChild(mkEle("p", "Time Submited: " +formattedTime ));

  div.appendChild(mkEle("p", "Pickup Location: " + form["Location"]));

  let $div = $(div);
  // Check box for enabled
  let checkbox = $("<input>", {
      type: "checkbox",
      checked: (form["isEnabled"] == 1)? true: false
    })
    .change(function() {
      let args = { formId: form["FormId"], isEnabled: ((this.checked)? 1: 0) };
      ajaxJson("/ajax/update-form.php", null, args);
    }
  );

  $div.append( checkbox )
      .append( $("<label>").text("Enabled") )
      .append( $("<br>") );


  // Lunch overide
  let numInput = $("<input>", {type: "number", value: form["lunchOverideAmount"]}).hide();
  numInput.change(function() {
    let args = { formId: form["FormId"], lunchOverideAmount: this.value };
    ajaxJson("/ajax/update-form.php", null, args);
  });

  checkbox = $("<input>", {
    type: "checkbox",
    checked: (form["lunchOverideAmount"] == null)? false: true
  }).change(function() {
    if(this.checked) {
      numInput.show();
      return;
    }
    numInput.hide();

    let args = { formId: form["FormId"], lunchOverideAmount: "NULL" };
    ajaxJson("/ajax/update-form.php", null, args);
  });

  if(checkbox.prop('checked')) numInput.show();

  $div.append( checkbox )
      .append( $("<label>").text("Lunch Overide") )
      .append( numInput )
      .append( $("<br>") );


  // Delete form

  let deleteButton = $("<button>").click(function() {
    let args = {formId: form["FormId"]};

    let inputFormId = prompt("Please enter the form id", "");

    if (args.formId != inputFormId) {
      return;
    }

    window.location.reload();
    ajaxJson("/ajax/delete-form.php", null, args);
  }).text("Delete Form");
  $div.append(deleteButton);


  /************************************************
   * Draw adult table
   ***********************************************/
  let table, row, header;

  div.appendChild( mkEle("br") );
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

    // Remind
    let str = "";
    switch( Number(individual["RemindStatus"]) ) {
      case 0: str = "Not requested"; break;
      case 1: str = "Requested"; break;
      case 2: str = "Sent request"; break;
      default: str = individual["RemindStatus"];
    }
    row.appendChild(mkEle("td", str));

    // let str = (individual["AllowPhotos"] == 0)? false: true;
    // row.appendChild( mkEle("td", str) );
    table.appendChild(row);
  }


  /************************************************
   * Draw children table
   ***********************************************/
  div.appendChild( mkEle("br") );
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

    let str = (individual["AllowPhotos"] == 0)? "Yes": "No";
    row.appendChild( mkEle("td", str) );
    table.appendChild(row);
  }
  // document.getElementById("form-area").appendChild( div );
  document.body.appendChild(div);
  return div;
}