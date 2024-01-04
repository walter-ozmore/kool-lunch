$(document).ready(async function() {	
	addPage("Volunteer Forms", async (page)=>{
		page.append($("<p>").text("Volunteer Forms"));
		let data = await post("/ajax/admin.php", {
			function: 1
		});

		console.log(data);
		let tableDiv = mktable(data, {
			headerNames: {
				timeSubmitted: "Submit Time",
				weekInTheSummer: "For a Week",
				bagDecoration: "Bag Decoration",
				fundraising: "Fundraising",
				supplyGathering: "Supplies"
			},
			triggers: [
				{ case: ["weekInTheSummer", "bagDecoration", "fundraising", "supplyGathering"],
					func: function(data) { return (data == "1")? "Yes": "No"; }
				},
				{ case: ["timeSubmitted"],
					func: function(data) { return unixToHuman(data); }
				}
			]
		});
		page.append(tableDiv);
	});
});