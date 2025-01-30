<?php
    include "../Connection/conn.inc.php";
    include "../Includes/functions.inc.php";
    header("Access-Control-Allow-Origin: *"); // Replace '*' with a specific domain in production, e.g., "https://example.com"
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Allow specific HTTP methods
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With"); // Allow specific headers
    // array_output_die($_POST);
    if(isset($_POST["option"]) && isset($_POST["questionId"])) {
        $option = $_POST["option"];
        $question_id = get_safe_value($conn, $_POST["questionId"]);
        $is_correct = get_safe_value($conn, $_POST["isCorrect"])=="correct"?1:0;
        $reset_sql = "UPDATE options SET is_correct = '0' WHERE question_id = '$question_id'";
        // echo $reset_sql . "<br />";
        $reset_result = mysqli_query($conn, $reset_sql);
        $sql = "UPDATE options SET is_correct = '$is_correct' WHERE question_id = '$question_id' AND answers = '$option'";
        // echo $sql;
        // die;
        $result = mysqli_query($conn, $sql);
        if($result) {
            echo "success";
        }
    }
?>