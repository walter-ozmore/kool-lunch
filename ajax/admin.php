<?php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	require_once realpath($_SERVER["DOCUMENT_ROOT"])."/res/lib.php";
	/**
	 * All admin fetches start here, we check the function code then
	 * send it too the correct function. POST only.
	 */

	switch($_POST["function"]) {
		case 1: // Fetch volunteer forms
			$data = [];

			$conn = connectDB("lunch");
			$query = "SELECT * FROM Volunteer";
			$result = $conn->query($query);
			while ($row = $result->fetch_assoc()) {
				$data[] = $row;
			}

			echo json_encode($data);
			break;
	}
?>