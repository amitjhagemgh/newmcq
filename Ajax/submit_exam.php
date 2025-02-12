<?php
// Add CORS headers
header("Access-Control-Allow-Origin: *"); // Replace '*' with a specific domain in production
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header('Content-Type: application/json'); // Ensure responses are JSON

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Start session
session_start();

// Include necessary files
include "../Connection/conn.inc.php";
include "../Includes/functions.inc.php";

// Read and decode JSON payload
$inputData = json_decode(file_get_contents('php://input'), true);

// Check if POST data is empty
$user_id = $_SESSION["user_id"] ?? null;
$exam_id = $_SESSION["exam_id"] ?? null;
$_SESSION["exam_submited"] = true;

$get_total_marks_sql = "SELECT * FROM question_exam_mapping WHERE exam_id = '$exam_id'";
$total_marks = mysqli_num_rows(mysqli_query($conn, $get_total_marks_sql));
if (empty($inputData) || !isset($inputData["questions"])) {
    if ($user_id && $exam_id) {
        $sql = "INSERT INTO result(user_id, score, total_marks, exam_id, questions_attempted_id, correctly_questions_attempted_id) 
                VALUES ('$user_id', '0', '$total_marks', '$exam_id', '0', '0')";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo json_encode(["message" => "No questions submitted, result saved."]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Error saving empty result."]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["message" => "Session data is missing."]);
    }
    exit();
}

$exam_id = $_SESSION["exam_id"];
$user_id = $_SESSION["user_id"];

$sql = "DELETE FROM user_exam_mapping WHERE user_id = '$user_id' AND exam_id = '$exam_id'";
$result = mysqli_query($conn, $sql);

if(count($inputData["questions"]) == 0) {
    $sql = "INSERT INTO result (user_id, score, total_marks, exam_id, questions_attempted_id, correctly_questions_attempted_id)
            VALUES ('$user_id', '0', '$total_marks', '$exam_id', '0', '0')";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        http_response_code(500);
        echo json_encode(["message" => "Error saving question result."]);
        exit();
    }
} else {
    $score = 0;
    foreach ($inputData["questions"] as $question) {
        $question_id = $question["question_id"] ?? null;
        $selected_options = $question["selectedOptions"] ?? [];

        // Fetch correct options from the database
        $select_correct_options_sql = "SELECT o.id FROM options AS o
                                       JOIN questions AS q ON o.question_id = q.id
                                       WHERE q.id = '$question_id' AND o.is_correct = 1";
        $select_correct_options_result = mysqli_query($conn, $select_correct_options_sql);

        if (mysqli_num_rows($select_correct_options_result) > 0) {
            $correct_options = [];
            while ($row = mysqli_fetch_assoc($select_correct_options_result)) {
                $correct_options[] = $row["id"];
            }
            // Check if selected options match correct options
            if (join("", $selected_options) == join("", $correct_options)) {
                $score += 1;
            }
        }
    }

    // Process questions
    foreach ($inputData["questions"] as $question) {
        $question_id = $question["question_id"] ?? null;
        $selected_options = $question["selectedOptions"] ?? [];

        // Determine if the answer is correct
        $select_correct_options_sql = "SELECT o.id FROM options AS o
                                       JOIN questions AS q ON o.question_id = q.id
                                       WHERE q.id = '$question_id' AND o.is_correct = 1";
        $select_correct_options_result = mysqli_query($conn, $select_correct_options_sql);

        $correct_options = [];
        while ($row = mysqli_fetch_assoc($select_correct_options_result)) {
            $correct_options[] = $row["id"];
        }

        $is_correct = ($selected_options == $correct_options) ? 1 : 0;

        // Update question attempt counts
        if($is_correct) {
            $update_no_times_sql = "UPDATE questions SET no_of_times_attempted = no_of_times_attempted + 1, no_of_times_correctly_attempted = no_of_times_correctly_attempted + 1 WHERE id = '$question_id'";
        } else {
            $update_no_times_sql = "UPDATE questions SET no_of_times_attempted = no_of_times_attempted + 1 WHERE id = '$question_id'";
        }
        $update_num_times_result = mysqli_query($conn, $update_no_times_sql);

        // Insert result
        $sql = "INSERT INTO result(user_id, score, total_marks, exam_id, questions_attempted_id, correctly_questions_attempted_id) 
                VALUES ('$user_id', '$score', '$total_marks', '$exam_id', '$question_id', '$is_correct')";
        $result = mysqli_query($conn, $sql);
        if($result) {
            $sql = "SELECT id FROM result WHERE user_id = '$user_id' AND exam_id = '$exam_id'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            $result_id = $row["id"];
            $option_value = [];
            foreach($selected_options as $option_id) {
                $answer_value = mysqli_fetch_assoc(mysqli_query($conn, "SELECT answers FROM options WHERE id = '$option_id'"))["answers"];
                $option_value[] = $answer_value;
            }
            $sql = "INSERT INTO answer_attempts (result_id, question_id, selected_option_id, selected_options) VALUES ('$result_id', '$question_id', '" . get_safe_value($conn,join(",", $selected_options)) . "', '" . get_safe_value($conn, join(",", $option_value)) . "')";
            // echo $sql;
            $result = mysqli_query($conn, $sql);
        }
        if (!$result) {
            http_response_code(500);
            echo json_encode(["message" => "Error saving question result for question ID: $question_id"]);
            exit();
        }
    }
}

// If all operations succeeded
http_response_code(200);
echo json_encode(["message" => "All results submitted successfully."]);
?>