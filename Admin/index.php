<?php
    define("PAGE", "Dashboard");
    include "Connection/conn.inc.php";
    include "Includes/header.php";
    if(!isset($_SESSION["is_login"])) {
        header("location: Authentication/login.php");
    }
?>
<h2 class="text-center mt-4">Quizzes</h2>
<div class="container d-flex align-items-end flex-column">
    <div>Search in: <select id="section-selector"><option value="all">All</option><option value="status">Status</option><option value="exam-name">Exam Name</option><option value="duration">Duration</option></select></div>
    <div class="mt-2">Search: <input type="text" name="search" id="search"></div>
</div>
<div class="container dashboard-container mb-4">
    <div class="row">
        <?php
            $sql = "SELECT * FROM exam_portal";
            $result = mysqli_query($conn, $sql);
            $first_exam_name = mysqli_fetch_assoc(mysqli_query($conn, $sql))["exam_name"];
            // echo $first_exam_name;
            // die;
            if($result) {
                $series = 65;
                while($row = mysqli_fetch_assoc($result)) { ?>
                    <div class="col-4 mt-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?= chr($series++); ?><span class="<?php if($row["status"] == 1) {?>bg-success<?php } else {?>bg-danger<?php }?> p-2 rounded-circle float-end status-highlight"></span></h5>
                                <p class="card-text mb-0 status">Status: <?php if($row["status"] == 0) {?>Deactivated<?php } else {?>Activated<?php }?></p>
                                <p class="card-text mb-0 exam-name">Exam Name: <span><?= $row["exam_name"];?></span></p>
                                <p class="card-text duration">Duration: <span><?= $row["duration"] . " minutes";?></span></p>
                                <a href="<?php if($row["exam_name"]=="Four Axis Personality Type Test") {echo "questions";} elseif($row["exam_name"]=="Emotional Intelligence Test") {echo "eit_questions";} else {echo "mcq_questions";}?>.php?exam=<?= $row["exam_name"]; ?>" class="btn btn-primary">Questions</a>
                                <button class="btn <?php if($row["status"] == 0) {?>btn-success<?php } else {?>btn-danger<?php }?> toggle-status" data-value="<?= $row["status"];?>" data-id="<?= $row["id"];?>"><?php if($row["status"] == 0) {?>Activate<?php } else {?>Deactivate<?php }?></button>
                                <!-- <form action="Questions/questions.php" method="POST" class="d-inline">
                                    <input type="hidden" name="exam_name" value="<?= $row["id"];?>">
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteExamModal">Delete</button>
                                </form> -->
                            </div>
                        </div>
                    </div>
               <?php }
            }
        ?>
        <!-- Modal -->
        <div class="modal fade" id="deleteExamModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modal-title">Delete Exam</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure to delete this exam?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <form action="Questions/questions.php" method="POST">
                    <input type="hidden" name="exam_id" value="">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
            </div>
        </div>
        </div>
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
    </div>
</div>
<?php
    include "Includes/footer.php";
?>