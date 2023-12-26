$(document).ready(async function() {	
	addPage("Volunteer Forms", async (page)=>{
		page.append($("<p>").text("Volunteer Forms"));
		let data = await post("/ajax/admin.php", {
			function: 1
		});

		console.log(data);
		let tableDiv = mktable(data);
		page.append(tableDiv);
	});
});