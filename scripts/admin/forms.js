function inspectForm(formData) {
  console.log(formData);

  let div = $("<div>", {class: "notification induce-blur"});
  let divGrid = $("<div>", {style: "display: grid; grid-template-columns: 1fr 2fr; margin-bottom: 1em;"})
  div.append(
    $("<h2>").text("Inspect Form"),
    divGrid,
  );

  // Add a close button so the user isnt stuck
  div.append( $("<center>").append(
    $("<button>")
      .text("OK")
      .click(async ()=>{ div.remove(); checkBlur(); }),
  ));
  $("body").append(div);
  checkBlur();
}

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