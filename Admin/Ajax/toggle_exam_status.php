<?php
    include "../Connection/conn.inc.php";
    include "../Includes/functions.inc.php";
    header("Access-Control-Allow-Origin: *"); // Replace '*' with a specific domain in production, e.g., "https://example.com"
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Allow specific HTTP methods
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With"); // Allow specific headers
    if(isset($_POST["id"]) && isset($_POST["value"])) {
        $id = $_POST["id"];
        $value = get_safe_value($conn, $_POST["value"]);
        $sql = "UPDATE exam_portal SET status = '$value' WHERE id = '$id'";
        $result = mysqli_query($conn, $sql);
        if($result) {
            echo "success";
        }
    }
?>