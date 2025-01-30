<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/login.css">
</head>

<body class="d-flex flex-column justify-content-center align-items-center">
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
    $username = get_safe_value($conn, $_POST['username']);
    $password = get_safe_value($conn, $_POST['password']);

    // Query the database to check if the user exists
    $sql = "SELECT password FROM admin_info WHERE username='$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Check if the password matches
        if ($row['password'] === $password) {
            // Successful login
            $_SESSION["is_login"] = true;  // Set the session variable
            $_SESSION["is_admin"] = true;  // Set the session variable
            $_SESSION['username'] = $username;  // Store the username in session
            header("location: ../index.php");  // Redirect to home page
            exit();
        } else {
            $info = "Invalid password.";
        }
    } else {
        $info = "User not found.";
    }
}

mysqli_close($conn);
?>
    <div>
        <img src="../IMG/logo.png" alt="GEM Logo" class="mb-3" />
    </div>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Admin Login Form</h5>
            <form action="login.php" method="POST">
                <div class="mb-3">
                  <label for="exampleInputEmail1" class="form-label">Email address</label>
                  <input type="email" class="form-control" name="username" id="username" aria-describedby="emailHelp" required>
                </div>
                <div class="mb-3">
                  <label for="exampleInputPassword1" class="form-label">Password</label>
                  <input type="password" class="form-control" name="password" id="password" required>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
                <a href="../../Authentication/login.php" class="btn btn-warning">User Login</a>
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