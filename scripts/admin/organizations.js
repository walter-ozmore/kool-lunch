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