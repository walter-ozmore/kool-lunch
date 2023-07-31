/**
 * Day overview page
 * Data shown. Amount picked up for each location, amount left over at each location
 */

addPage({
  id: "day-page",
  name: "Day View",
  init: function() {
    let page = $("#day-page");

    page.append(
      $("<div>").addClass("content")
    );

    let locations = data["locations"];
  }
});