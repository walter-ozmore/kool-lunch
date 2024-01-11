$(document).ready(async function() {
	addPage("Individuals", async (page)=>{
		let data = await post("/ajax/admin.php", {
			function: 2
		});

		let tableDiv = mktable(data, {
			headerNames: tableHeaderNames,
			triggers: tableTriggers,
      onRowClick: inspectIndividual
		});
		page.append(tableDiv);
	});
});