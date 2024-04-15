$(document).ready(async function() {
	addPage("Individuals", async (page)=>{
		let rawData = (await post("/ajax/admin.php", {
			function: 2,
      startTime: 1704088800,
      endTime: 1735711200-1
		}));

    console.log("Individuals Page Data:", rawData);
    if(rawData.data == undefined) {
      displayError(JSON.stringify(rawData));
      return;
    }
    let data = rawData.data;

    // Alter the data so it shows on the table a little better
    for(let individual of data) {
      // Merge all the contact data in to one column as most individuals only have one
      let contactHTML = "";
      if(individual.email != null)
        contactHTML += "Email: "+individual.email+"<br>";
      if(individual.phoneNumber != null)
        contactHTML += "Phone Number: "+displayPhoneNumber(individual.phoneNumber)+"<br>";
      if(individual.facebookMessenger != null)
        contactHTML += "Facebook: "+individual.facebookMessenger+"<br>";
      individual.contacts = contactHTML;

      // Remove contact data so that they don't show up on the table
      delete individual.email;
      delete individual.phoneNumber;
      delete individual.facebookMessenger;

      // Make remind status human readable
      switch(individual.remindStatus) {
        case null: individual.remindStatus = "Missing data"; break;
        case "0": individual.remindStatus = ""; break;
        case "1": individual.remindStatus = "Remind requested"; break;
        case "2": individual.remindStatus = "Remind sent"; break;
      }
    }


		let tableDiv = mktable(data, {
			headerNames: tableHeaderNames,
			triggers: tableTriggers,
      onRowClick: inspectIndividual
		});
		page.append(tableDiv);
	});
});