<?php
    define("PAGE", "Home Page");
    include "Connection/conn.inc.php";
    include "Includes/header.php";
    include "Includes/functions.inc.php";
    if(!isset($_SESSION["is_login"])) {
        header("location: ./Authentication/login.php");
    }
    // echo "<pre>";
    // print_r($_SESSION);
    // echo "</pre>";
    if(isset($_SESSION["is_admin"])) {
        header("location: ./Admin/Authentication/login.php");
    }
    // $sql = "SELECT * FROM result WHERE email_id = '$_SESSION[email_id]'";
    // $result = mysqli_query($conn, $sql);
    // if(mysqli_num_rows($result) > 0) {
    //     header("location: result.php");
    // }
?>
<div class="container dashboard-container">
    <div class="row">
        <div class="col-6 d-flex justify-centent-center align-items-center">
            <a href="all_quizes.php" class="btn btn-lg btn-primary quizes-btn">Quizes</a>
        </div>
        <?php
            // $user_sql = "SELECT * FROM users WHERE username = '$_SESSION[username]'";
            // $user_result = mysqli_query($conn, $user_sql);
            // if($user_result) {
                //     $user_active_exams = mysqli_fetch_assoc($user_result)["active_exam"];
                // }
                // $user_active_exams = explode(',', str_replace(['[', ']'], '', $user_active_exams));
                // array_output($user_active_exams);
                // $sql = "SELECT e.exam_name, e.status
                //     FROM users u
                //     JOIN exam_portal e
                //     ON FIND_IN_SET(e.exam_name, REPLACE(REPLACE(u.active_exam, '[', ''), ']', ''))
                //     LEFT JOIN result r
                //     ON e.exam_name = r.exam_name AND r.email_id = '$_SESSION[email_id]'
                //     WHERE u.email_id = '$_SESSION[email_id]' 
                //     AND e.status = 1
                //     AND r.exam_name IS NULL
                //     ORDER BY e.id ASC;";
            // echo $sql;
            // die;
            // $result = mysqli_query($conn, $sql);
            // if($result) {
                // $num_of_exam = 0;
                // while($row = mysqli_fetch_assoc($result)) { 
                    // echo array_output($row);
                    // $num_of_exam++;
                    ?>
                    <!-- <div class="col-6 d-flex justify-centent-center align-items-center"> -->
                        <!-- <a href="all_quizes.php" class="btn btn-lg btn-primary quizes-btn">Quizes</a> -->
                        <!-- <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <span><?= $row["exam_name"]; ?></span>
                                    <span class="<?php if($row["status"] == 1) {?>bg-success<?php }?> p-1 rounded-circle float-end status-highlight"></span>
                            </h5>
                                <p class="card-text mb-0">Exam Name: Written Communication</p>
                                <p class="card-text">Status: <?php if($row["status"] == 0) {?>Deactivated<?php } else {?>Pending to Attend<?php }?></p>
                                <?php if($num_of_exam == 1) {?>
                                <a href="take_exam.php?exam=<?= $row["exam_name"]; ?>" class="btn btn-primary">Attend</a>
                                <?php } else {?>
                                <a href="take_mcq_exam.php?exam=<?= $row["exam_name"]; ?>" class="btn btn-primary">Attend</a>
                                <?php }?>
                                <button class="btn <?php if($row["status"] == 0) {?>btn-success<?php } else {?>btn-danger<?php }?> toggle-status" data-value="<?= $row["status"];?>" data-id="<?= $row["id"];?>"><?php if($row["status"] == 0) {?>Activate<?php } else {?>Deactivate<?php }?></button>
                            </div>
                        </div> -->
                    <!-- </div> -->
               <?php //}
            //}
        ?>
        <!-- <div class="col-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Exam Name</h5>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the
                        card's content.</p>
                    <a href="#" class="btn btn-primary">Go somewhere</a>
                </div>
            </div>
        </div> -->
        <!-- <div class="col-6">
            <?php

            ?>
        </div> -->
        <div class="col-6 d-flex justify-centent-center align-items-center">
            <div>
                <a href="result.php" class="btn btn-lg btn-primary quizes-btn">Results</a><br /><br />
                <a href="opinion_result.php" class="btn btn-lg btn-primary quizes-btn opinion-btn">Personality Type Test Results</a>
            </div>
        </div>
    </div>
</div>
<?php
    include "Includes/footer.php";
?>