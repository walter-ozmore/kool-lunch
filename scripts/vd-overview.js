/**
 * Draws an overall summary that contains no PII
 */
function drawSummary() {
  // Calculate stats client side
  let counter = calculateStats();

  // Create lists of information to track
  let locations = data["locations"];
  let display = $("<div>").addClass("content");
  $("#stats-page").append(display);

  // drawObjectToHTML(counter);

  for(let day of days) {
    let table = $("<table>")
      .append($("<tr>")
        .append( $("<th>").text("Location") )
        .append( $("<th>").text("Lunches" ) )
        .append( $("<th>").text("Allergies" ) )
      )
    ;

    let marginTop = (day == "monday")? "0em" : "2.5em";
    let divEle = $("<div>")
      .append(
        $("<h2>")
          .text(day)
          .css("margin", `${marginTop} 0em .5em 0em`)
      )
      .append( table )
    ;



    for(let location of locations) {
      table.append($("<tr>")
        .append( $("<td>").text(location ) )
        .append( $("<td>").text(counter[day][location].lunches) )
        .append( createAllergyEle(counter[day][location].allergies) )
      );
    }

    table.append($("<tr>")
      .append( $("<td>").text("Total") )
      .append( $("<td>").text(counter[day].lunches) )
      .append( createAllergyEle(counter[day].allergies) )
    );

    display.append(divEle);
  }

  for(let x of data.counts) {
    let str = `${x.date}: ${x.count}`;
    $("#stats").append($("<p>").text(str))
  }

}

/**
 * Draws the given object in to a table with clickable links
 *
 * @param {*} objList
 * @returns
 */
function createAllergyEle(objList) {
  let allergyEle = $("<td>");

  for(let obj of objList) {
    allergyEle.append(
      $("<p>")
        .text(obj.allergies)
        .click(function() {
          createFormElement(obj.formIndex);
        })
    );
  }

  return allergyEle;
}