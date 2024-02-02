$(document).ready(async function() {
	addPage("Forms", async (page)=>{
		let data = await post("/ajax/admin.php", {
			function: 3
		});
    console.log(data);

    // Alter some data to fit our table format
    for(let row of data) {
      // Trim days to match the format of M Tu W Th F
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

      // Make individuals show up on the same row
      if('individual' in row) {
        let tempStr = "";
        for(let individual of row.individual) {
          tempStr += individual + "<br>";
        }
        row.individual = tempStr;
      } else {
        row.individual = "Data not found";
      }
    }
    //

		let tableDiv = mktable(data, {
			headerNames: tableHeaderNames,
			triggers: tableTriggers,
      onRowClick: inspectForm
		});
		page.append(tableDiv);
	});
});