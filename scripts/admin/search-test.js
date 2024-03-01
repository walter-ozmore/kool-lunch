async function openSearchWindow(returnFunction) {
  let div = $("<div>", {class: "notification induce-blur"});
  let divGrid = $("<div>", {style: "display: grid; grid-template-columns: 1fr 2fr; margin-bottom: 1em;"})
  div.append(
    $("<h2>").text("Search Individual"),
    divGrid,
  );

  let nameInput = $("<input>", {type: "text"});
  divGrid.append(
    $("<label>").text("Name"),
    nameInput
  );

  // Add a close button so the user isnt stuck
  div.append( $("<center>").append(
    $("<button>")
      .text("Search")
      .click(async function() {
        // Show loading
        $(this).prop("disabled", true);

        // Fetch data
        let data = (await post("/ajax/admin.php", {
          function: 26,
          searchTerm: nameInput.val()
        })).data;
        returnFunction(data);

        // Remove loading
        div.remove();
        checkBlur();
      }),
  ));
  $("body").append(div);
  checkBlur();
}

$(document).ready(async function() {
	addPage("Search Test", async (page)=>{
    let data = null;
    page.append("<button>")
      .text("Prompt Search")
      .click(async ()=>{
        // Open up a search window
        openSearchWindow((searchResult)=>{
          data = searchResult;
          console.log(data);

          page.empty();
          let tableDiv = mktable(data, {
            headerNames: tableHeaderNames,
            triggers: tableTriggers,
            // onRowClick: inspectOrganization
          });
          page.append(tableDiv);
        });
      })
    ;


	});
});