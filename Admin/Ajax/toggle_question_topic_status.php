<?php
include "../Connection/conn.inc.php";
include "../Includes/functions.inc.php";

// header("Access-Control-Allow-Origin: *"); // Replace '*' with a specific domain in production, e.g., "https://example.com"
// header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Allow specific HTTP methods
// header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With"); // Allow specific headers

$user_id = $_POST["userId"];
$exam_id = get_safe_value($conn, $_POST["examId"]);
$value = get_safe_value($conn, $_POST["value"]);

// Check if the entry already exists
$check_sql = "SELECT * FROM user_exam_mapping WHERE user_id = '$user_id' AND exam_id = '$exam_id'";
$check_result = mysqli_query($conn, $check_sql);

if ($value == 1) {
    if (mysqli_num_rows($check_result) == 0) {
        // Insert only if the entry does not exist
        $sql = "INSERT INTO user_exam_mapping (user_id, exam_id, status) VALUES ('$user_id', '$exam_id', '$value')";
        if (mysqli_query($conn, $sql)) {
            echo "success";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Entry already exists, no action needed.";
    }
} else {
    if (mysqli_num_rows($check_result) > 0) {
        // Delete only if the entry exists
        $sql = "DELETE FROM user_exam_mapping WHERE user_id = '$user_id' AND exam_id = '$exam_id'";
        if (mysqli_query($conn, $sql)) {
            echo "success";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "No entry to delete.";
    }
}

?>
