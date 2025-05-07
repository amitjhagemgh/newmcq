<?php
    include "../Connection/conn.inc.php";  // Include your database connection file
    session_start();
    if(isset($_SESSION["exam_id"]) && isset($_SESSION["user_id"])) {
        // Assuming you have a database connection established

        $user_id = $_SESSION["user_id"];
        $exam_id = $_SESSION["exam_id"];

        // Delete the user-exam mapping from the database
        $sql = "DELETE FROM user_exam_mapping WHERE user_id = '$user_id' AND exam_id = '$exam_id'";
        mysqli_query($conn, $sql);
        // Check for errors in the query execution
    }
    session_unset();  // Unset all session variables
    session_destroy();  // Destroy the session
    
    // Optionally, delete session cookie if it's set
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
    }
    
    header("Location: ../index.php");  // Redirect after logout
    exit();
?>