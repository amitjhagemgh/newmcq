<?php
    define("PAGE", "Home Page");
    include "Connection/conn.inc.php";
    include "Includes/header.php";
    include "Includes/functions.inc.php";

    if (!isset($_SESSION["is_login"])) {
        header("location: Authentication/login.php");
    }

    if (isset($_SESSION["is_admin"])) {
        header("location: Admin/Authentication/login.php");
    }

    date_default_timezone_set("Asia/Kolkata"); // Set timezone

    // Debugging session
    // echo "<pre>";
    // print_r($_SESSION);
    // echo "</pre>";
    // if(isset($_SESSION["exam_id"]) && isset($_SESSION["user_id"])) {
    //     $sql = "DELETE FROM user_exam_mapping WHERE user_id = '$_SESSION[user_id]' AND exam_id = '$_SESSION[exam_id]'";
    //     $result = mysqli_query($conn, $sql);
    //     header("location: Authentication/logout.php");
    //     die;
    // }
    // SQL query
    $sql = "SELECT 
    users.id AS user_id,
    users.name AS user_name,
    user_exam_mapping.exam_id AS exam_id,
    exam_portal.exam_name AS exam_name,
    exam_portal.status AS status,
    MAX(COALESCE(
        CASE 
            WHEN exam_portal.exam_name = 'Four Axis Personality Type Test' THEN opinion_result.exam_attended_time 
            ELSE result.exam_attended_time 
        END, 
        'Not Available'
    )) AS exam_attended_time,
    CASE 
        WHEN exam_portal.exam_name != 'Four Axis Personality Type Test' THEN MAX(COALESCE(result.score, 0)) 
        ELSE NULL 
    END AS score
FROM 
    users
JOIN user_exam_mapping ON users.id = user_exam_mapping.user_id 
JOIN exam_portal ON user_exam_mapping.exam_id = exam_portal.id
LEFT JOIN result ON users.id = result.user_id AND result.exam_id = user_exam_mapping.exam_id
LEFT JOIN opinion_result ON users.id = opinion_result.user_id AND opinion_result.exam_id = user_exam_mapping.exam_id
WHERE 
    email_id = '" . get_safe_value($conn, $_SESSION["email_id"]) . "' 
    AND exam_portal.status = 1
GROUP BY 
    user_exam_mapping.exam_id, 
    users.id, 
    users.name, 
    exam_portal.exam_name, 
    exam_portal.status;";

    // echo "<pre>Query: $sql</pre>"; // Debugging query

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        echo "Query Error: " . mysqli_error($conn);
        die;
    }

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        // echo "<pre>Row: ";
        // print_r($row);
        // echo "</pre>"; // Debug each row
        $data[] = $row;
    }
?>

<div class="container my-3 min-height-100-vh">
    <div class="row">
        <?php
foreach ($data as $row) {
?>
    <div class="col-4 d-flex justify-content-center align-items-start mt-2">
        <div class="card w-100">
            <div class="card-body">
                <h5 class="card-title">
                    <span><?= htmlspecialchars($row["exam_name"]); ?></span>
                    <span class="<?php if ($row["status"] == 1) { ?>bg-success<?php } ?> p-1 rounded-circle float-end status-highlight"></span>
                </h5>
                <p class="card-text mb-0">Exam Name: <?= htmlspecialchars($row["exam_name"]); ?></p>
                <?php if ($row['exam_attended_time'] !== "Not Available") { ?>
                    <!-- <p class="card-text mb-0">Status: Completed</p> -->
                    <?php if ($row["exam_name"] !== "Four Axis Personality Type Test") { ?>
                        <!-- <p class="card-text">Score: <?= $row["score"]; ?></p> -->
                    <?php } ?>
                    <!-- <p class="card-text">Exam Attended Time: <?= htmlspecialchars($row["exam_attended_time"]); ?></p> -->
                <?php } else { ?>
                    <!-- <p class="card-text">Status: Pending to Attend</p>
                    <a href="<?php if($row["exam_name"]!=="Four Axis Personality Type Test") {echo "take_exam";}else {echo "take_opinion_exam";}?>.php?exam=<?= urlencode($row["exam_name"]); ?>" class="btn btn-primary">Attend</a> -->
                    <?php } ?>
                    <p class="card-text">Status: Pending to Attend</p>
                    <a href="<?php if($row["exam_name"]=="Four Axis Personality Type Test") {echo "take_opinion_exam";} elseif($row["exam_name"]=="Emotional Intelligence Test") {echo "take_eit_exam";}else {echo "take_exam";}?>.php?exam=<?= urlencode($row["exam_name"]); ?>" class="btn btn-primary">Attend</a>
            </div>
        </div>
    </div>
<?php }
if(count($data) == 0) {
    echo '<div class="alert alert-success" role="alert">
  <b>You have no pending exams. You can log out.</b>
</div>';
} ?>
    </div>
</div>

<?php
    include "Includes/footer.php";
?>
