<?php
	include_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/secret.php";
	require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Volunteer | Kool Lunches</title>
		<link rel="stylesheet" href="/res/rf-gallery.css"/>

		<?php
			require realpath($_SERVER["DOCUMENT_ROOT"])."/res/head.php";
		?>

		<script>
			/**
			 * Submits the form info to the back end
			 */
			function submit() {

			}

			/**
			 * Checks that all the information on the given page is correct and that 
			 * the user may continue. This function will be ran frequently to update 
			 * the page's continue button
			 * 
			 * @param page int Page index that shall be showns
			 */
			function checkPage(page) {
				switch(page) {
					case 1: break
					case 2: break
					case 3: break
					default:
						console.log("This page is not set up to be checked yet.")
				}
			}

			/**
			 * Hides all pages from view then shows the page provided
			 * 
			 * @param page int Page index that shall be shown
			 */
			function showPage(page) {
				pageIndex = page;
				$('[id^="page-"]').hide();
				$('#page-'+page+'').show();
			}


			/**
			 * Shows all waits below the given index, this function can not be used
			 * to hide waits above the given index
			 */
			function showWait(waitIndex) {
				$('#wait-'+waitIndex+'').show();
			}
			
			let pageIndex = 1;
			$(document).ready(async function() {
				// Start tick for the check function, every 1/2 second it will check if
				// user can continue
				// setInterval(function() {
				// 	checkPage(pageIndex);
				// }, 500); // 500ms = 0.5 seconds

				$("#radioCommunication").change(function() {

					var selectedValue = $("input[name='prefer-comms']:checked").val();

					showWait(4);

					// Hide all options
					$("#contact-phone-section").hide();
					$("#contact-email-section").hide();
					$("#contact-messenger-section").hide();

					switch (selectedValue) {
						case "call":
						case "text":
							$("#contact-phone-section").show();
							break;
						case "email":
							$("#contact-email-section").show();
							break;
						case "fbm":
							$("#contact-messenger-section").show();
							break;
					}
				});
			});
		</script>
	</head>
	<header>
			<?php require realpath($_SERVER["DOCUMENT_ROOT"])."/res/header.php"; ?>
	</header>
	<body>
		<div class="content">

			<!-- Info / Intro warn the user about age limits -->
			<div id = "intro">
				<div class="banner"><h1 class="lexend-deca-header stroke">WANT TO GET INVOLVED?</h1></div>

				<div class="section" id="ageGate">
					<p class="lexend-body">The KOOL Lunches Program is growing and we are finding more ways for you to get involved! Volunteers can help with bag decoration, fundraising, supply gathering, lunch making, and distribution. If you wish to get involved, please fill out this form and we will get in touch.</p>
					<center><button onclick="$('#intro').hide(); $('#volunteerForm').show();" class="lexend-body stroke">I am 18 or older</button>
				</div>
			</div>

			<!-- Form section -->
			<div id="volunteerForm" style="display: None;">
				<div class="banner"><h1 class="lexend-deca-header stroke">WE JUST NEED A LITTLE INFO</h1></div>
				
				<div class="form">
					<!-- Individual or Org -->
					<div class="section" id="wait-0">
						<label>Are you signing up yourself or your group/organization?</label>
						<div class="radio">
							<script>
								function toggle(value, name) {
									if(value) $("[name='org-options']").show();
									else $("[name='org-options']").hide();
								}
							</script>
							<input type="radio" value="false" name="volunteer-type" onclick="toggle(false, 'org-options'); showWait(2);"><label>I am an individual</label><br>
							<input type="radio" value="true"  name="volunteer-type" onclick="toggle(true , 'org-options'); showWait(2);"><label>I am a part of a group or organization</label>
						</div>
					</div>

					<!-- Org Information -->
					<div class="section" name="org-options" style="display: none">
						<label>Are you the main point of contact in the organization?</label>
						<div class="radio" onclick="showWait(2)">
							<input type="radio" value="true"  name="is-main-contact"><label>Yes, I am the main contact</label><br>
							<input type="radio" value="false" name="is-main-contact"><label>No, I am not the main contact</label>
						</div>
						<br>

						<div name="org-spec">
							<label name="question">What is the name of your organization?</label>
							<input type="text" placeholder="Organization Name" id="org-name" value="">
						</div>
					</div>

					<!-- Filler-outer's Name -->
					<script>
						function checkNames() {
							if($("#first-name").val() === "") { console.log("FIRSTNAME NOT DONE"); return; }
							if($("#last-name").val()  === "") { console.log("LASTNAME  NOT DONE"); return; }

							showWait(3);
						}
					</script>
					<div class="section" id="wait-2" style="display: none">
						<label name="question">Name of person filling out the form</label>
						<div class="flex">
							<input type="text" placeholder="First Name" id="first-name" value="" onchange="checkNames()">
							<input type="text" placeholder="Last Name"  id="last-name"  value="" onchange="checkNames()">
						</div>
					</div>

					<!-- Communication  -->
					<div class="section" id="wait-3" style="display: none">
						<label name="question">Preferred Communication Method</label>
						<div class="radio" id="radioCommunication">
							<input type="radio" name="prefer-comms" value="call"><label>Phone Call</label><br>
							<input type="radio" name="prefer-comms" value="text"><label>Text Message</label><br>
							<input type="radio" name="prefer-comms" value="email"><label>Email</label><br>
							<input type="radio" name="prefer-comms" value="fbm"><label>Facebook Messenger</label><br>
						</div>
					</div>

						<div class="section" id="contact-phone-section" style="display: none">
							<label name="question">Phone Number<span name="contact-person"> of main contact person</span></label>
							<input type="text" placeholder="(000) 000-0000" id="phone-number">
						</div>

						<div class="section" id="contact-email-section" style="display: none">
							<label name="question">Email<span name="contact-person">  of main contact person</span></label>
							<input type="text" placeholder="example@example.com" id="email">
						</div>

						<div class="section" id="contact-messenger-section" style="display: none">
							<label name="question">Facebook Messenger<span name="contact-person">  of main contact person</span></label>
							<input type="text" placeholder="" id="fbm">
						</div>

					<!-- Opportunities -->
					<div class="section" id="wait-4" style="display: none">
						<label>Volunteer Opportunities</label>
						<div class="radio" onclick="showSubmit()">
							<input type="checkbox" id="vol-option-1"><label>Week in the summer</label><br>
							<input type="checkbox" id="vol-option-2"><label>Bag Decoration</label><br>
							<input type="checkbox" id="vol-option-3"><label>Fundraising</label><br>
							<input type="checkbox" id="vol-option-4"><label>Supply Gathering</label><br>
						</div>
						
						<center><button class="large-button" onclick="submit()">Submit</button></center>
					</div>


				</div>
			</div>
		</div>

		<?php require realpath($_SERVER["DOCUMENT_ROOT"])."/res/footer.php"; ?>
	</body>
</html>