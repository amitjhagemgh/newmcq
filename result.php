<?php
    define("PAGE", "MCQ Exam Result");
    define("TYPE", "");
    include "Connection/conn.inc.php";
    include "Includes/header.php";
    include "Includes/functions.inc.php";
    if(!isset($_SESSION["is_login"])) {
        header("location: Authentication/login.php");
    }
?>
<div class="container my-3">
    <table class="table" id="user-result-table">
    <thead>
        <tr>
            <!-- <th scope="col">Sr. No.</th> -->
            <th scope="col">Name</th>
            <th scope="col">Email ID</th>
            <th scope="col">Score</th>
            <th scope="col">Exam Name</th>
            <th scope="col">Exam Attended Time</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $email_id = $_SESSION["email_id"];
            $user_sql = "SELECT id, name, email_id FROM users WHERE email_id = '$email_id'";
            $user_result = mysqli_query($conn, $user_sql);
            $row = mysqli_fetch_assoc($user_result);
            $user_id = $row["id"];
            $name = $row["name"];
            // array_output_die($_POST);
            $email_id_sql = "SELECT user_id FROM result WHERE user_id = '$user_id' GROUP BY user_id";
            $email_id_sql_result = mysqli_query($conn, $email_id_sql);
            if(mysqli_num_rows($email_id_sql_result) > 0) {
                $exam_name_sql = "SELECT DISTINCT user_id, exam_id, score, exam_attended_time FROM result WHERE user_id = '$user_id'";
                $exam_name_sql_result = mysqli_query($conn, $exam_name_sql);
                    while($row = mysqli_fetch_assoc($exam_name_sql_result)) { ?>
                    <?php // array_output($row); ?>
                        <tr>
                            <!-- <th scope="row"><?php // echo $sr_no; ?></th> -->
                            <td><?= $name; ?></td>
                            <td><?= $email_id; ?></td>
                            <td><?php
                                    // echo $row["exam_id"] . "<br />";
                                    $total_marks_sql = "SELECT * FROM questions WHERE exam_id = $row[exam_id] AND question_type != 'title'";
                                    $total_marks = mysqli_num_rows(mysqli_query($conn, $total_marks_sql));
                                    echo $row["score"] . "/" . $total_marks;
                                ?></td>
                            <td><?php
                                $exam_id = $row["exam_id"];
                                $exam_name_sql = "SELECT * FROM exam_portal WHERE id = '$exam_id'";
                                $exam_name_result = mysqli_query($conn, $exam_name_sql);
                                $exam_name_row = mysqli_fetch_assoc($exam_name_result);
                                echo $exam_name_row["exam_name"];
                            ?></td>
                            <td><?php echo $row["exam_attended_time"];?></td>
                        </tr>
                    <?php
                        // $sr_no++;
                    }
                } else {
                    ?>
                        <tr>
                            <td colspan="5" class="text-center">No results found.</td>
                        </tr>
                    <?php
                }
        ?>
        <!-- <tr>
            <th scope="row">1</th>
            <td>Mark</td>
            <td>Otto</td>
            <td>@mdo</td>
        </tr> -->
    </tbody>
    </table>
</div>
<?php
    include "Includes/footer.php";
?>