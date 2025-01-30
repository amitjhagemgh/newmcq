<?php
    define("PAGE", "MCQ Exam");
    define("TYPE", "");
    include "Connection/conn.inc.php";
    include "Includes/header.php";
    include "Includes/functions.inc.php";
    if(!isset($_SESSION["is_login"])) {
        header("location: Authentication/login.php");
    }
    if(!isset($_GET["exam"])) {
        header("location: index.php");
    }
    // array_output_die($_GET);
?>
<div class="container mt-3">
    <?php
        $exam_id_sql = "SELECT * FROM exam_portal WHERE exam_name = '$_GET[exam]'";
        $exam_id_result = mysqli_query($conn, $exam_id_sql);
        $exam_id = mysqli_fetch_assoc($exam_id_result)["id"];
        $sql = "SELECT * FROM users WHERE email_id = '$_SESSION[email_id]'";
        $result = mysqli_query($conn, $sql);
        if($result) {
            $row = mysqli_fetch_assoc($result);
            $user_id = $row["id"];
    ?>
    <div class="row">
        <div class="col-12">
            Name:
            <?= $row["name"];?><br />
            Email ID:
            <?= $row["email_id"];?>
        </div>
        <?php
            // if(isset($_SESSION["exam_id"]) && isset($_SESSION["user_id"])) {
            //     $sql = "DELETE FROM user_exam_mapping WHERE user_id = '$_SESSION[user_id]' AND exam_id = '$_SESSION[exam_id]'";
            //     $result = mysqli_query($conn, $sql);
            //     header("location: Authentication/logout.php");
            //     die;
            // }
        ?>
        <?php
            // $_SESSION["exam_id"] = $exam_id;
            // $_SESSION["user_id"] = $user_id;
        ?>
        <div class="col-12">
            <ul class="mt-4">
            <h3>Instructions:</h3>
            <?php
                $instruction_sql = "SELECT * FROM instructions";
                $instruction_result = mysqli_query($conn, $instruction_sql);
                while($instruction_row = mysqli_fetch_assoc($instruction_result)) {
                    echo "<li>" . $instruction_row["instructions"] . "</li>";
                }
            ?>
        </ul>
        </div>
    </div>
    <?php }
    // $redirect_sql = "SELECT * FROM opinion_result WHERE user_id = $user_id AND exam_id = $exam_id";
    // $redirect_result = mysqli_query($conn, $redirect_sql);
    // if(mysqli_num_rows($redirect_result) > 0) {
        // echo "It is running <br />";
        // echo mysqli_num_rows($result);
        // die;
        // echo "<script>location.href = 'all_quizes.php';</script>";
    // }
    ?>
</div>
        <?php
            if($_SERVER["REQUEST_METHOD"] == "POST") {
                $keys = array_keys($_POST);
                $values = array_values($_POST);
                // pre_post();
                $sql = "INSERT INTO opinion_result(user_id, question, answer, exam_id) VALUES ";
                for($i = 0; $i < count($keys); $i++) {
                    if($keys[$i] == "exam-submit") {
                        continue;
                    }
            // $user_id = $row["id"];
                    $sql .= "('$user_id', '$keys[$i]', '" . get_safe_value($conn, $values[$i]) . "', '$exam_id'),";
                }
                $sql = substr($sql, 0, -1);
                if(sizeof($values) == 1) {
                    $sql .= "('$row[2]', '', '', '$exam_id');";
                }
                // echo $sql;
                // die;
                $result = mysqli_query($conn, $sql);
                if($result) {
                    header("location: all_quizes.php");
                }
            }
        ?>
<div class="container mt-3 <?php if($_SERVER["REQUEST_METHOD"] == "POST") {?>d-none<?php } ?>">
    <div class="row">
        <div class="col-12">
            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title">Exam: <?= htmlspecialchars($_GET["exam"]) ?></h5>
                    <form action="take_opinion_exam.php?exam=<?php echo $_GET["exam"]; ?>" method="POST">
                        <div id="question-container">
                            <?php
                                $sql_display_questions = "SELECT * FROM opinion_questions WHERE exam_id = '$exam_id'";
                                $result_display_questions = mysqli_query($conn, $sql_display_questions);
                                if (mysqli_num_rows($result_display_questions) > 0) {
                                    $sr_no = 1;
                                    while ($row = mysqli_fetch_assoc($result_display_questions)) { ?>
                            <p>
                                <?php echo $sr_no . ". " . $row["questions"];?>
                                </h5>
                            <p><input type="radio" name="question_<?php echo $row["id"];?>" value="Option A" id="opt_a_<?php echo $row["id"];?>" required><label for="opt_a_<?php echo $row["id"];?>" class="ms-2">
                                    A. <?php echo $row["opt_a"];?>
                                </label>
                            </p>
                            <p><input type="radio" name="question_<?php echo $row["id"];?>" value="Option B" id="opt_b_<?php echo $row["id"];?>" required><label for="opt_b_<?php echo $row["id"];?>" class="ms-2">
                                    B. <?php echo $row["opt_b"];?>
                                </label>
                            </p>
                            <?php
                                    $sr_no++;
                                    }
                                }
                            ?>
                            <input type="submit" value="Submit" class="btn btn-success my-2" name="exam-submit" id="exam-submit">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container my-3 <?php if(!($_SERVER["REQUEST_METHOD"] == "POST")) {?>d-none<?php } ?>">
    <div class="row">
        <div class="col-12">
            <div class="card bg-success">
                <div class="card-body">
                    <h5 class="card-title">Exam Over</h5>
                    <!-- <p class="card-text">Some quick example text to build on the card title and make up the bulk of the
                        card's content.</p> -->
                    <!-- <a href="#" class="btn btn-primary">Go somewhere</a> -->
                </div>
            </div>
        </div>
    </div>
</div>
<div id="countdown" class="<?php if(mysqli_num_rows($result_display_questions) == 0) {?>d-none<?php }?><?php if($_SERVER["REQUEST_METHOD"] == "POST") {?>d-none<?php } ?>">15 minutes:00 seconds</div>
<?php
    include "Includes/footer.php";
?>