$(document).ready(async function() {
	addPage("Organizations", async (page)=>{
		let rawData = (await post("/ajax/admin.php", {
			function: 4
		}));

    console.log("Organizations Page Data:", rawData);
    if(rawData.data == undefined) {
      displayError(JSON.stringify(rawData));
      return;
    }
    let data = rawData.data;

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
      onRowClick: inspectOrganization,
      ignore: ["signupContactID", "mainContactID"]
		});
		page.append(tableDiv);
	});
});