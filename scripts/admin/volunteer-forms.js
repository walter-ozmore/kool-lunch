$(document).ready(async function() {
	addPage("Volunteer Forms", async (page)=>{
		let rawData = (await post("/ajax/admin.php", {
			function: 1
		}));

    console.log("Volunteer Forms Data:", rawData);
    if(rawData.data == undefined) {
      displayError(JSON.stringify(rawData));
      return;
    }
    let data = rawData.data;

    // Alter the data so it shows on the table a little better
    for(let volForm of data) {
      // Merge our volunteer options
      let string = "";
      if(volForm["weekInTheSummer"] == "1") string += "For a Week<br>";
      if(volForm["bagDecoration"] == "1") string += "Bag Decoration<br>";
      if(volForm["fundraising"] == "1") string += "Fundraising<br>";
      if(volForm["supplyGathering"] == "1") string += "Supply Gathering<br>";
      volForm.volOptions = string;

      delete volForm.weekInTheSummer;
      delete volForm.bagDecoration;
      delete volForm.fundraising;
      delete volForm.supplyGathering;

      // Merge all the contact data in to one column as most individuals only have one
      let contactHTML = "";
      if(volForm.email != null)
        contactHTML += "Email: "+volForm.email+"<br>";
      if(volForm.phoneNumber != null)
        contactHTML += "Phone Number: "+displayPhoneNumber(volForm.phoneNumber)+"<br>";
      if(volForm.facebookMessenger != null)
        contactHTML += "Facebook: "+volForm.facebookMessenger+"<br>";
      volForm.contacts = contactHTML;

      // Remove contact data so that they don't show up on the table
      delete volForm.email;
      delete volForm.phoneNumber;
      delete volForm.facebookMessenger;
    }

		let tableDiv = mktable(data, {
			headerNames: tableHeaderNames,
			triggers: tableTriggers,
      onRowClick: inspectVolunteerForm,
      ignore: ["individualID", "orgID"]
		});
		page.append(tableDiv);
	});
});