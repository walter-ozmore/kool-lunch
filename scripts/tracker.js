/**
       * Creates a row and adds it to the page
       */
function addPickup(index) {
  let form = data["forms"][index];

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

  data["forms"][index]["rowEle"] = row;
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
 * Added the given location to the location selector
 */
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


/**
 * Check the location selected by the user then hides all of the divs that
 * do not match the location
 */
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

fetchData( function() {
  authenticateUser();
  for(let location of data["locations"]) {
    addLocation(location);
  }
  checkSelector();

  // Draw all rows
  for(let index in data["forms"]) {
    addPickup(index);
  }
});