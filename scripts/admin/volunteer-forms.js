$(document).ready(async function() {
	addPage("Volunteer Forms", async (page)=>{
		let data = (await post("/ajax/admin.php", {
			function: 1
		})).data;

    // Alter the data so it shows on the table a little better
    for(let volForm of data) {
      // Merge our volunteer options
      let string = "";
      string += "For a Week:"+volForm.weekInTheSummer+"<br>";
      string += "Bag Decoration:"+volForm.bagDecoration+"<br>";
      string += "Fundraising:"+volForm.fundraising+"<br>";
      string += "Supply Gathering:"+volForm.supplyGathering+"<br>";
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

      // Delete mostly debug data
      delete volForm.orgID;
      delete volForm.individualID;
    }

		let tableDiv = mktable(data, {
			headerNames: tableHeaderNames,
			triggers: tableTriggers,
      onRowClick: inspectVolunteerForm
		});
		page.append(tableDiv);
	});
});