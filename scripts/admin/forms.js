$(document).ready(async function() {
	addPage("Forms", async (page)=>{
		let data = await post("/ajax/admin.php", {
			function: 3
		});

    // Alter some data

    for(let row of data) {
      let pickupDays = "";
      if(row.pickupMon == 1) pickupDays += "M ";
      if(row.pickupTue == 1) pickupDays += "Tu ";
      if(row.pickupWed == 1) pickupDays += "W ";
      if(row.pickupThu == 1) pickupDays += "Th ";
      if(row.pickupFri == 1) pickupDays += "F ";
      row.pickupDays = pickupDays;

      delete row.pickupMon;
      delete row.pickupTue;
      delete row.pickupWed;
      delete row.pickupThu;
      delete row.pickupFri;
    }
    //

		let tableDiv = mktable(data, {
			headerNames: tableHeaderNames,
			triggers: tableTriggers,
      // onRowClick: inspectForm
		});
		page.append(tableDiv);
	});
});