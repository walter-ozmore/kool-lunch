function inspectVolunteerForm(formData) {
  console.log(formData);

  let div = $("<div>", {class: "notification induce-blur"});
  let divGrid = $("<div>", {style: "display: grid; grid-template-columns: 1fr 2fr; margin-bottom: 1em;"})
  div.append(
    $("<h2>").text("Inspect Volunteer Form"),
    divGrid,
  );

  // Apply to div grid
  divGrid.append(
    $("<label>").text("Individual Name:"), $("<p>").text(formData.individualName),
    $("<label>").text("Time Submitted:"), $("<p>").text(unixToHuman(formData.timeSubmitted)),
  );

  if(formData.phoneNumber != null) divGrid.append($("<label>").text("Phone Number:"), $("<p>").text(formData.phoneNumber));
  if(formData.email != null) divGrid.append($("<label>").text("Email:"), $("<p>").text(formData.email));
  if(formData.facebookMessenger != null) divGrid.append($("<label>").text("Messenger:"), $("<p>").text(formData.facebookMessenger));

  // Add checkbox stuff
  let checkbox;
  checkbox = $("<input>", {type: "checkbox", disabled: true})
  if(formData.weekInTheSummer == "1") checkbox.prop('checked', true);
  div.append( checkbox, $("<label>").text("Week in the summer"), $("<br>"), );

  checkbox = $("<input>", {type: "checkbox", disabled: true})
  if(formData.bagDecoration == "1") checkbox.prop('checked', true);
  div.append( checkbox, $("<label>").text("Bag Decoration"), $("<br>"), );

  checkbox = $("<input>", {type: "checkbox", disabled: true})
  if(formData.fundraising == "1") checkbox.prop('checked', true);
  div.append( checkbox, $("<label>").text("Fundraising"), $("<br>"), );

  checkbox = $("<input>", {type: "checkbox", disabled: true})
  if(formData.supplyGathering == "1") checkbox.prop('checked', true);
  div.append( checkbox, $("<label>").text("Supply Gathering"), $("<br>"), );

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
	addPage("Volunteer Forms", async (page)=>{
		let data = await post("/ajax/admin.php", {
			function: 1
		});

		let tableDiv = mktable(data, {
			headerNames: tableHeaderNames,
			triggers: tableTriggers,
      onRowClick: inspectVolunteerForm
		});
		page.append(tableDiv);
	});
});