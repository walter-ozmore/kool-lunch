$(document).ready(async function() {
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