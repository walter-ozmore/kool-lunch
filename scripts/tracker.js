/**
 * Creates a row and adds it to the page, each row is a form
 */
function createRow(index) {
  let form = data["forms"][index];

  if(form == undefined) return;

  // Skip disabled forms
  if(form.isEnabled != 1) return;

  // Create a list of adults that we can display in HTML
  let individuals = "";
  for(let ind of form["individuals"]) {
    if(ind["IsAdult"] == 1)
      individuals += ind["IndividualName"] + "<br>";
  }

  return $("<div>")
    .addClass( "row" )
    .append( $("<a>", {onclick: `createFormElement(${index})`}).html(individuals) )
    .append(
      $("<span>")
        .append(
          $("<span>")
            .text(("lunchesNeeded" in form)? form.lunchesNeeded + "x": "-")
            .addClass("quantity")
        )
        .append(
          $("<input>", {type: "checkbox", checked: form.pickedUp})
            .change(function() {
              let checkbox = $(this);
              checkbox.prop('disabled', true);
              $.ajax({
                type: "POST",
                url: "/ajax/tracker",
                data: JSON.stringify({
                  hasPickedUp: $(this).is(':checked'),
                  formId: form["FormId"],
                  date: $('#date-selector').val()
                }),
                contentType: "application/json",
                success: function(data) {
                  // console.log(data);
                  checkbox.prop('disabled', false);
                },
                error: function() {
                  $('#myCheckbox').prop('checked', !checkbox.is(':checked'));
                }
              });
            })
        )
    )
  ;
}


function drawData() {
  // Clear data
  $("#display").empty();

  if( authenticateUser() == false) return;

  // Show the selectors
  $("#selectors").show();

  for(let index in data["locations"]) {
    let location = data["locations"][index];

    // Add a display div
    $("#display").append(
      $("<div>", {id: "location"+index})
        .addClass("content")
        .append(
          $("<h2>").text( location )
        )
    );

    // Add to drop down
    $("#location-selector").append(
      $("<option>")
        .text(location)
        .val(index)
    );
  }

  for(let index in data["forms"]) {
    let row = createRow( index );
    if(row == null) continue;
    // div.append(row);
    let locationIndex = data["locations"].indexOf( data["forms"][index]["Location"] );
    $("#location"+locationIndex).append( row );
  }

  // Remove loading screen
  doneLoading();
}

function loadData(selectedDate = null) {
  showLoading();

  let date, day, weekdays = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
  let dayIndex;

  date     = (selectedDate == null)? new Date()    : new Date(selectedDate);
  dayIndex = (selectedDate == null)? date.getDay() : date.getDay() + 1;
  day      = weekdays[dayIndex];

  // Args for fetching data
  let args = {
    // Get the day of the week
    day: day,
    enabled: true
  }

  if(selectedDate != null) args["date"] = selectedDate;

  fetchData(drawData, args);
}

function setUpDate() {
  // Set the date input to today by default
  var today = new Date();

  var year = today.getFullYear();
  var month = (today.getMonth() + 1).toString().padStart(2, '0');
  var day = today.getDate().toString().padStart(2, '0');

  var formattedDate = year + '-' + month + '-' + day;

  let dateSelector = $('#date-selector');
  dateSelector
    .val(formattedDate)
    .change(function() {
      let selectedDate = $(this).val();
      $("#display").empty();
      loadData(selectedDate);
    })
  ;
}

$(document).ready(function() {
  loadData();

  setUpDate();
});