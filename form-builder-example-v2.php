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
			$(document).ready(async function() {
				fb = new FormBuilder({
					"parentEle": $("#form")
				});

				fb.addMultiSelect({
					"title": "Pick a form",
					"name": "form.type",
					"options": [
						{"desc": "Form A", "value": "A"},
						{"desc": "Form B", "value": "B"},
					]
				});

				fb.addKeyInput({
					"name": "org.name",
					"title": "What is the name of your organization?"
				});

				fb.formDiv.append($("<div>"));

				console.log( fb.collect() );
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