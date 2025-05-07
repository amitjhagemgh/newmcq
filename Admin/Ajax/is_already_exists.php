<?php
    include "../Connection/conn.inc.php";
    include "../Includes/functions.inc.php";
    header("Access-Control-Allow-Origin: *"); // Replace '*' with a specific domain in production, e.g., "https://example.com"
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Allow specific HTTP methods
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With"); // Allow specific headers

    if($_SERVER['REQUEST_METHOD'] == "POST") {
        $question = get_safe_value($conn, $_POST['question']);
        $question_id = get_safe_value($conn, $_POST['question_id']);
        // echo $question . "<br/><br/><br/>";
        $sql = "SELECT * FROM questions WHERE questions = '$question' AND id != '$question_id' AND status = 0";
        // echo $sql;
        // die;
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            echo 1;
        } else {
            echo 0;
        }
    }
?>