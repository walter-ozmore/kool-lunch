function inspectIndividual(individualData) {
  console.log(individualData);

  let div = $("<div>", {class: "notification induce-blur"});
  let divGrid = $("<div>", {style: "display: grid; grid-template-columns: 1fr 2fr; margin-bottom: 1em;"})
  div.append(
    $("<h2>").text("Inspect Individual"),
    divGrid,
  );

  // Apply to div grid
  divGrid.append(
    $("<label>").text("Individual Name:"), $("<p>").text(individualData.individualName),
  );

  if(individualData.phoneNumber != null) divGrid.append($("<label>").text("Phone Number:"), $("<p>").text(individualData.phoneNumber));
  if(individualData.email != null) divGrid.append($("<label>").text("Email:"), $("<p>").text(individualData.email));
  if(individualData.facebookMessenger != null) divGrid.append($("<label>").text("Messenger:"), $("<p>").text(individualData.facebookMessenger));
  divGrid.append(
    $("<label>").text("Prefered Contact:"),
    $("<p>").text((individualData.preferredContact == null)? "None Specified": individualData.preferredContact),
  );

  // Add a close button so the user isnt stuck
  div.append( $("<center>").append(
    $("<button>", {disabled: true})
      .text("Delete")
      .click(async ()=>{  }),
    $("<button>")
      .text("OK")
      .click(async ()=>{ div.remove(); checkBlur(); }),
  ));
  $("body").append(div);
  checkBlur();
}

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