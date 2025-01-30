<?php
// Add CORS headers
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

session_start();

include "../Connection/conn.inc.php";
include "../Includes/functions.inc.php";

$inputData = json_decode(file_get_contents('php://input'), true);
$debug_data = ["input" => $inputData]; // Store input for debugging

$user_id = $_SESSION["user_id"] ?? null;
$exam_id = $_SESSION["exam_id"] ?? null;

if (empty($inputData) || !isset($inputData["questions"])) {
    if ($user_id && $exam_id) {
        $sql = "INSERT INTO result(user_id, score, exam_id, questions_attempted_id, correctly_questions_attempted_id) 
                VALUES ('$user_id', '0', '$exam_id', '0', '0')";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo json_encode(["message" => "No questions submitted, result saved."]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Error saving empty result"]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["message" => "Session data is missing"]);
    }
    exit();
}

$sql = "SELECT * FROM user_exam_mapping WHERE user_id = '$user_id' AND exam_id = '$exam_id'";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) == 0) {
    die;
}

$sql = "DELETE FROM user_exam_mapping WHERE user_id = '$user_id' AND exam_id = '$exam_id'";
mysqli_query($conn, $sql);

if(count($inputData["questions"]) == 0) {
    $sql = "INSERT INTO result (user_id, score, exam_id, questions_attempted_id, correctly_questions_attempted_id)
            VALUES ('$user_id', '0', '$exam_id', '0', '0')";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        http_response_code(500);
        echo json_encode(["message" => "Error saving question result"]);
        exit();
    }
} else {
    $debug_info = [];
    $score = 0;
    
    // First pass - calculate score
    foreach ($inputData["questions"] as $question) {
        $question_id = $question["question_id"] ?? null;
        $selected_options = isset($question["selectedOptions"]) ? (array)$question["selectedOptions"] : [];
        
        if (!$question_id || empty($selected_options)) continue;
        
        // Explicitly cast each selected option to integer
        $selected_options_int = [];
        foreach ($selected_options as $opt) {
            $selected_options_int[] = (int)$opt;
        }

        // Get correct options from database
        $sql = "SELECT o.id 
               FROM options o 
               JOIN questions q ON o.question_id = q.id 
               WHERE q.id = '$question_id' 
               AND o.is_correct = 1";
               
        $result = mysqli_query($conn, $sql);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $correct_options = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $correct_options[] = (int)$row['id'];
            }
            
            sort($selected_options_int);
            sort($correct_options);
            
            $is_matching = true;
            if (count($selected_options_int) === count($correct_options)) {
                for ($i = 0; $i < count($selected_options_int); $i++) {
                    if ($selected_options_int[$i] !== $correct_options[$i]) {
                        $is_matching = false;
                        break;
                    }
                }
            } else {
                $is_matching = false;
            }
            
            if ($is_matching) {
                $score++;
            }
            
            $debug_info[] = [
                'question_id' => $question_id,
                'selected' => $selected_options_int,
                'correct' => $correct_options,
                'is_correct' => $is_matching
            ];
        }
    }
    
    // Insert main result
    $sql = "INSERT INTO result(user_id, score, exam_id, questions_attempted_id, correctly_questions_attempted_id) 
            VALUES ('$user_id', '$score', '$exam_id', '0', '0')";
    $result = mysqli_query($conn, $sql);
    
    if (!$result) {
        http_response_code(500);
        echo json_encode([
            "message" => "Error saving main result",
            "debug_info" => $debug_info,
            "mysql_error" => mysqli_error($conn)
        ]);
        exit();
    }
    
    $result_id = mysqli_insert_id($conn);
    
    // Second pass - save individual answers
    foreach ($inputData["questions"] as $question) {
        $question_id = $question["question_id"] ?? null;
        $selected_options = isset($question["selectedOptions"]) ? (array)$question["selectedOptions"] : [];
        
        if (!$question_id) continue;
        
        // Get correct answer for this question
        $sql = "SELECT o.id 
               FROM options o 
               JOIN questions q ON o.question_id = q.id 
               WHERE q.id = '$question_id' 
               AND o.is_correct = 1";
        $result = mysqli_query($conn, $sql);
        
        $correct_options = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $correct_options[] = (int)$row['id'];
        }
        
        $selected_options_int = array_map('intval', $selected_options);
        
        sort($selected_options_int);
        sort($correct_options);
        
        $is_correct = (count($selected_options_int) === count($correct_options));
        if ($is_correct) {
            for ($i = 0; $i < count($selected_options_int); $i++) {
                if ($selected_options_int[$i] !== $correct_options[$i]) {
                    $is_correct = false;
                    break;
                }
            }
        }
        
        // Update attempt counts
        if ($is_correct) {
            $sql = "UPDATE questions 
                   SET no_of_times_attempted = no_of_times_attempted + 1,
                       no_of_times_correctly_attempted = no_of_times_correctly_attempted + 1 
                   WHERE id = '$question_id'";
        } else {
            $sql = "UPDATE questions 
                   SET no_of_times_attempted = no_of_times_attempted + 1 
                   WHERE id = '$question_id'";
        }
        mysqli_query($conn, $sql);
        
        // Save answer attempt
        $selected_options_str = !empty($selected_options_int) ? implode(",", $selected_options_int) : '0';
        $sql = "INSERT INTO answer_attempts (result_id, question_id, selected_option_id) 
                VALUES ('$result_id', '$question_id', '$selected_options_str')";
        
        if (!mysqli_query($conn, $sql)) {
            http_response_code(500);
            echo json_encode([
                "message" => "Error saving answer attempt",
                "question_id" => $question_id,
                "mysql_error" => mysqli_error($conn),
                "debug_info" => $debug_info
            ]);
            exit();
        }
    }
}

// Return success with debug info
echo json_encode([
    "status" => "success",
    "message" => "All results submitted successfully.",
    "score" => $score,
    "debug_info" => $debug_info,
    "input_data" => $debug_data
]);
?>