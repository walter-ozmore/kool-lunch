/**
 * Draws all the forms loaded in the form area
 */
function drawForms() {
  // Draw forms
  for(let index in data["forms"]) {
    let formEle = createFormElement(index, false);
    $("#forms-page").append(formEle); // Append to the forms page
  }
}