function inspectOrganizations(organizationsData) {
  console.log(organizationsData);

  let div = $("<div>", {class: "notification induce-blur"});
  let divGrid = $("<div>", {style: "display: grid; grid-template-columns: 1fr 2fr; margin-bottom: 1em;"})
  div.append(
    $("<h2>").text("Inspect Organizations"),
    divGrid,
  );

  // Add a close button so the user isnt stuck
  div.append( $("<center>").append(
    $("<button>")
      .text("OK")
      .click(async ()=>{ div.remove(); checkBlur(); }),
  ));
  $("body").append(div);
  checkBlur();
}

$(document).ready(async function() {
	addPage("Organizations", async (page)=>{
		let data = (await post("/ajax/admin.php", {
			function: 4
		})).data;
    
    // Pull data out to be one layer
    for(let row of data) {
      if("mainContact" in row) {
        let id = row.mainContact.individualID;
        let name = row.mainContact.individualName;
        delete row.mainContact;
        row.mainContact = name;
        row.mainContactID = id;
      }
      if("signupContact" in row) {
        let id = row.signupContact.individualID;
        let name = row.signupContact.individualName;
        delete row.signupContact;
        row.signupContact = name;
        row.signupContactID = id;
      }
    }

		let tableDiv = mktable(data, {
			headerNames: tableHeaderNames,
			triggers: tableTriggers,
      onRowClick: inspectOrganization
		});
		page.append(tableDiv);
	});
});