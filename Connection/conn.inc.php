<?php
    $conn = mysqli_connect("localhost", "root", "", "new_mcq");
    if(!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    };
?>