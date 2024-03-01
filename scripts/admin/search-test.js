// async function openSearchWindow(returnFunction) {
//   let div = $("<div>", {class: "notification induce-blur"});
//   let divGridInputs = $("<div>", {style: "display: grid; grid-template-columns: 1fr 2fr; margin-bottom: 1em;"})
//   let divGrid = $("<div>", {style: "display: grid; grid-template-columns: 1fr 2fr; margin-bottom: 1em;"})
//   let outputDiv = $("<div>");
//   divGrid.append(divGridInputs, outputDiv);
//   div.append(
//     $("<h2>").text("Search Individual"),
//     divGrid,
//   );

//   let nameInput = $("<input>", {type: "text"});
//   divGridInputs.append(

//     $("<label>").text("Name"),
//     nameInput
//   );

//   // Add a close button so the user isnt stuck
//   div.append( $("<center>").append(
//     $("<button>")
//       .text("Search")
//       .click(async function() {
//         // Show loading
//         $(this).prop("disabled", true);

//         // Fetch data
//         let data = (await post("/ajax/admin.php", {
//           function: 26,
//           searchTerm: nameInput.val()
//         })).data;

//         let tableDiv = mktable(data, {
//           headerNames: tableHeaderNames,
//           triggers: tableTriggers,
//           // onRowClick: inspectOrganization
//         });

//         outputDiv.empty().append(tableDiv);
//       }),
//   ));
//   $("body").append(div);
//   checkBlur();
// }

$(document).ready(async function() {
	addPage("Search Test", async (page)=>{
    let data = null;
    page.append("<button>")
      .text("Prompt Search")
      .click(async ()=>{
        // // Open up a search window
        // openSearchWindow((searchResult)=>{
        //   data = searchResult;
        //   console.log(data);
        // });
        searchIndividuals((selection)=>{
          console.log(selection);
        });
      })
    ;


	});
});