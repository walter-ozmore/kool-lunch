$(document).ready(async function() {
	addPage("Volunteer Forms", async (page)=>{
		let data = (await post("/ajax/admin.php", {
			function: 1
		})).data;

		let tableDiv = mktable(data, {
			headerNames: tableHeaderNames,
			triggers: tableTriggers,
      onRowClick: inspectVolunteerForm
		});
		page.append(tableDiv);
	});
});