addPage({
  id: "forms-page",
  name: "Forms",
  init: function() {
    // Draw forms
    for(let index in data["forms"]) {
      let formEle = createFormElement(index, false);
      $("#forms-page").append(formEle); // Append to the forms page
    }
  }
});