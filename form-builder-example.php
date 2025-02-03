<?php
	require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Volunteer | Kool Lunches</title>
		<link rel="stylesheet" href="/res/rf-gallery.css"/>

		<style>
			.form-line {
				display: block;
				width: 100%;
				margin: 2.5em auto;
			}

			.input-title {
				display: block;
				font-weight: bold;
			}
		</style>

		<?php
			require realpath($_SERVER["DOCUMENT_ROOT"])."/res/head.php";
		?>

		<script src="/res/form-builder.js"></script>

		<script>
			function createIndividualForm() {
				let indDiv = $("<div>").hide();

				// Individual signup
				tmp = new CustomKeyInput({
					"title": "First Name",
					"parentEle": indDiv
				});

				tmp = new CustomKeyInput({
					"title": "Last Name",
					"parentEle": indDiv
				});

				new CustomMultiSelect({
					"title": "Preferred Communication Method",
					"parentEle": indDiv,
					"options": [
						{"value": "Phone Call"},
						{"value": "Text Message"},
						{"value": "Email"},
						{"value": "Facebook Messenger"},
					]
				});

				new CustomMultiSelect({
					"title": "Volunteer Opportunities",
					"parentEle": indDiv,
					"maxOptions": -1,
					"options": [
						{"value": "Week in the summer"},
						{"value": "Bag Decoration"},
						{"value": "Fundraising"},
						{"value": "Supply Gathering"},
					]
				});

				return indDiv;
			}

			function createOrgForm() {
				let indDiv = $("<div>").hide();

				new CustomMultiSelect({
					"title": "Are you the main point of contact in the organization?",
					"parentEle": indDiv,
					"options": [
						{"value": "Yes, I am the main contact"},
						{"value": "No, I am not the main contact"},
					]
				});

				tmp = new CustomKeyInput({
					"title": "What is the name of your organization?",
					"parentEle": indDiv
				});

				// Individual signup
				tmp = new CustomKeyInput({
					"title": "First Name",
					"parentEle": indDiv
				});

				tmp = new CustomKeyInput({
					"title": "Last Name",
					"parentEle": indDiv
				});

				new CustomMultiSelect({
					"title": "Preferred Communication Method",
					"parentEle": indDiv,
					"options": [
						{"value": "Phone Call"},
						{"value": "Text Message"},
						{"value": "Email"},
						{"value": "Facebook Messenger"},
					]
				});

				new CustomMultiSelect({
					"title": "Volunteer Opportunities",
					"parentEle": indDiv,
					"maxOptions": -1,
					"options": [
						{"value": "Week in the summer"},
						{"value": "Bag Decoration"},
						{"value": "Fundraising"},
						{"value": "Supply Gathering"},
					]
				});

				return indDiv;
			}

			$(document).ready(async function() {
				let parentEle = $("#form");
				let formEle = $("<div>").hide();
				let tmp;

				// Age gate
				tmp = new CustomMultiSelect();
				tmp.addOption({
					"value": "I am 18 or older",
					"desc": "I am 18 or older",
					"onSelect": ()=>{
						console.log("onSelect");
						formEle.show();
					},
					"onUnselect": ()=>{
						console.log("onUnselect");
						formEle.hide();
					},
				});
				tmp.setParent( parentEle );

				parentEle.append(formEle); // Add our form below

				// Group or individual
				tmp = new CustomMultiSelect({
					"title": "Are you signing up yourself or your organization?",
					
				});
				tmp.addOption({
					"value": "I am an individual",
					"onSelect"  : ()=>{ indDiv.show(); },
					"onUnselect": ()=>{ indDiv.hide(); },
				});
				tmp.addOption({
					"value": "I am a part of a group or organization",
					"onSelect"  : ()=>{ orgDiv.show(); },
					"onUnselect": ()=>{ orgDiv.hide(); },
				});
				tmp.setParent( formEle );

				let indDiv = createIndividualForm();
				formEle.append(indDiv);
				indDiv.css("background-color", "rgb(100, 100, 200)"); // Set the background color for dev
				// indDiv.show();

				// Add submit button
				indDiv.append(
					$("<button>")
						.text("Submit")
						.click(function() {
							console.log( select.collect() )
						})
				);

				// Organization signup
				let orgDiv = createOrgForm();
				formEle.append(orgDiv);
				orgDiv.css("background-color", "rgb(100, 200, 100)"); // Set the background color for dev
				// orgDiv.show();

				// Add submit button
				orgDiv.append(
					$("<button>")
						.text("Submit")
						.click(function() {
							console.log( select.collect() )
						})
				);
			});
		</script>
	</head>
	<header>
			<?php require realpath($_SERVER["DOCUMENT_ROOT"])."/res/header.php"; ?>
	</header>
	<body>
		<div class="banner"><h1 class="lexend-deca-header stroke">WE JUST NEED A LITTLE INFO</h1></div>
		<div id="form"></div>

		<?php require realpath($_SERVER["DOCUMENT_ROOT"])."/res/footer.php"; ?>
	</body>
</html>