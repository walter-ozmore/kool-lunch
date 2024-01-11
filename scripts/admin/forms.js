$(document).ready(async function() {
	addPage("Forms", async (page)=>{
		let data = await post("/ajax/admin.php", {
			function: 3
		});

		let tableDiv = mktable(data, {
			headerNames: tableHeaderNames,
			triggers: tableTriggers,
      // onRowClick: inspectForm
		});
		page.append(tableDiv);
	});
});