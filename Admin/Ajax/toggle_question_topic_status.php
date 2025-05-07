<?php
include "../Connection/conn.inc.php";
include "../Includes/functions.inc.php";

// header("Access-Control-Allow-Origin: *"); // Replace '*' with a specific domain in production, e.g., "https://example.com"
// header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Allow specific HTTP methods
// header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With"); // Allow specific headers

$question_id = $_POST["questionId"];
$topic_id = get_safe_value($conn, $_POST["topicId"]);
$value = get_safe_value($conn, $_POST["value"]);

// Check if the entry already exists
$check_sql = "SELECT * FROM question_topic_mapping WHERE question_id = '$question_id' AND topic_id = '$topic_id'";
$check_result = mysqli_query($conn, $check_sql);

if ($value == 1) {
    if (mysqli_num_rows($check_result) == 0) {
        // Insert only if the entry does not exist
        $sql = "INSERT INTO question_topic_mapping (question_id, topic_id, status) VALUES ('$question_id', '$topic_id', '$value')";
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
        $sql = "DELETE FROM question_topic_mapping WHERE question_id = '$question_id' AND topic_id = '$topic_id'";
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
