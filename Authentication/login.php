<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log In</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/login.css">
</head>

<body class="d-flex flex-column justify-content-center align-items-center">
<?php if(isset($_SESSION["is_login"])) { ?>
<script>
    const cookies = document.cookie.split(";");

    for (let i = 0; i < cookies.length; i++) {
        const cookie = cookies[i];
        const cookieName = cookie.split("=")[0].trim();
        document.cookie = cookieName + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    }
</script>
<?php } ?>
<?php
session_start();
if(isset($_SESSION["is_login"])) {
    header("location: ../index.php");
}
require "../Connection/conn.inc.php";
require "../Includes/functions.inc.php";
$info = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the submitted form data
    $email_id = get_safe_value($conn, $_POST['email_id']);
    
    // Query the database to check if the user exists
    $sql = "SELECT email_id FROM users WHERE email_id='$email_id'";
    // echo "<pre>";
    // echo $sql;
    // echo "</pre>";
    // die;
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Check if the Email ID matches
        if ($row['email_id'] === $email_id) {
            // Successful login
            $_SESSION["is_login"] = true;  // Set the session variable
            $_SESSION['email_id'] = $email_id;  // Store the username in session
            header("location: ../index.php");  // Redirect to home page
            exit();
        } else {
            $info = "Invalid Email ID.";
        }
    } else {
        $info = "User not found.";
    }
}

// mysqli_close($conn);
?>
    <div>
        <img src="../IMG/logo.png" alt="GEM Logo" class="mb-3" />
    </div>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Login Form</h5>
            <form action="login.php" method="POST">
                <div class="mb-3">
                  <label for="exampleInputEmail1" class="form-label">Email ID</label>
                  <input type="text" class="form-control" name="email_id" id="email_id" required>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
                <a href="../Admin/Authentication/login.php" class="btn btn-warning">Admin Login</a>
                <?php
                    echo $info;
                ?>
              </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>