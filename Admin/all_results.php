<?php
    define("PAGE", "All Results");
    include "Connection/conn.inc.php";
    include "Includes/header.php";
    include "Includes/functions.inc.php";
    if(!isset($_SESSION["is_login"])) {
        header("location: Authentication/login.php");
    }
?>
    <?php
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            if(isset($_POST['delete-result'])) {
                $email_id = get_safe_value($conn, $_POST['delete_email_id']);
                $get_user_id_sql = "SELECT id FROM users WHERE email_id = '$email_id'";
                $user_id_result = mysqli_query($conn, $get_user_id_sql);
                $user_id_row = mysqli_fetch_assoc($user_id_result);
                $user_id = $user_id_row["id"];
                $exam_name = get_safe_value($conn, $_POST['delete_exam_name']);
                $exam_attended_time = get_safe_value($conn, $_POST['delete_exam_attended_time']);
                $get_exam_id_sql = "SELECT id FROM exam_portal WHERE exam_name = '$exam_name'";
                $exam_id_result = mysqli_query($conn, $get_exam_id_sql);
                $exam_id_row = mysqli_fetch_assoc($exam_id_result);
                $exam_id = $exam_id_row["id"];
                // Fetching result ID for deleting the answer_attempts records accordingly result_id
                $result_id = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM result WHERE user_id = '$user_id' AND exam_id = '$exam_id' AND exam_attended_time = '$exam_attended_time'"))["id"];
                // $sql = "DELETE FROM answer_attempts WHERE result_id = '$result_id'";
                // $result = mysqli_query($conn, $sql);
                $sql = "UPDATE result AS r
                        JOIN users AS u ON r.user_id = u.id
                        JOIN exam_portal AS ep ON ep.id = r.exam_id
                        SET r.status = 0
                        WHERE r.exam_id = '$exam_id' AND r.user_id = '$user_id';";
                $result = mysqli_query($conn, $sql);
                if($result) {
                    // $sql = "DELETE FROM answer_attempts WHERE result_id = '$result_id'";
                    // $result = mysqli_query($conn, $sql);
                    echo "<script>alert('Result deleted successfully.');</script>";
                }   
            }
        }
    ?>
    <div class="container my-5 min-height-100-vh">
        <h2 class="text-center">Results</h2>
        <div class="container d-flex justify-content-end p-0">
    <label class="">Search in: 
        <select id="columnSelector">
            <option value="all">All Columns</option>
            <option value="1">Name</option>
            <option value="2">Email ID</option>
            <option value="3">Exam Name</option>
        </select>
    </label>
</div>

        <!-- User Table -->
        <table class="table table-bordered mt-4" id="result-table">
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Name</th>
                    <th>Email ID</th>
                    <th>Exam Name</th>
                    <th>Score</th>
                    <th>Exam Attended Time</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody id="userTable">
                <?php
                // Fetch users from the database
                // Right before the query
                $query = "SELECT 
    result.user_id,
    result.exam_id,
    MAX(result.score) AS score,
    MAX(result.total_marks) AS total_marks,
    exam_portal.exam_name,
    users.email_id AS email_id,
    MAX(result.exam_attended_time) AS exam_attended_time,
    GROUP_CONCAT(DISTINCT users.name) AS name 
FROM result 
INNER JOIN users ON result.user_id = users.id 
INNER JOIN exam_portal ON result.exam_id = exam_portal.id 
WHERE result.status = 1
GROUP BY result.user_id, result.exam_id, exam_portal.exam_name, users.email_id;";
                        // echo $query;
                $result = mysqli_query($conn, $query);
                $i=0;
                if(mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $i++;
                        ?>
                        <tr>
                            <td><?= $i; ?></td>
                            <td><?= $row['name']; ?></td>
                            <td><?= $row['email_id']; ?></td>
                            <td><?= $row['exam_name']; ?></td>
                            <td>
                            <?php
                                echo $row['score'] . "/" . $row['total_marks'];
                            ?>
                            </td>
                            <td><?= $row['exam_attended_time']; ?></td>
                            <td><button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteResultModal<?= $row['email_id']; ?>" data-id="<?= $row['email_id']; ?>" data-exam_name="<?= $row['exam_name'];?>">Delete</button></td>
                            <!-- Modal -->
                            <div class="modal fade" id="deleteResultModal<?= $row['email_id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                    <form action="all_results.php" method="POST" id="deleteResultForm">
                                        <div class="modal-body">
                                            <input type="hidden" id="delete_email_id" name="delete_email_id" value="<?= $row['email_id']; ?>">
                                            <input type="hidden" id="delete_exam_name" name="delete_exam_name" value="<?= $row['exam_name']; ?>">
                                            <input type="hidden" id="delete_exam_attended_time" name="delete_exam_attended_time" value="<?= $row['exam_attended_time']; ?>">
                                            <div class="form-group">
                                                <p>Are you sure you want to delete?</p>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-danger" name="delete-result">Delete</button>
                                        </div>
                                    </form>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </tr>
            <?php
                    }
                } else { ?>
                    <tr>
                        <td colspan="7" class="text-center">No results found.</td>
                    </tr>
                <?php }
                // mysqli_close($conn);
            ?>
            </tbody>
        </table>
        
        
        <!-- User Result Table for Excel Output -->
        <table class="table table-bordered mt-4" id="result-table-excel-output">
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Name</th>
                    <th>Email ID</th>
                    <th>Exam Name</th>
                    <th>Score</th>
                    <th>Exam Attended Time</th>
                    <!--<th>User ID</th>-->
                    <!--<th>Exam ID</th>-->
                </tr>
            </thead>
            <tbody id="userTable">
                <?php
                // Fetch users from the database
                // Right before the query
                $query = "SELECT 
    result.user_id,
    result.exam_id,
    MAX(result.score) AS score,
    MAX(result.total_marks) AS total_marks,
    exam_portal.exam_name,
    users.email_id AS email_id,
    MAX(result.exam_attended_time) AS exam_attended_time,
    GROUP_CONCAT(DISTINCT users.name) AS name 
FROM result 
INNER JOIN users ON result.user_id = users.id 
INNER JOIN exam_portal ON result.exam_id = exam_portal.id 
WHERE result.status = 1
GROUP BY result.user_id, result.exam_id, exam_portal.exam_name, users.email_id;
";
                        // echo $query;
                $result = mysqli_query($conn, $query);
                $i=0;
                if(mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $i++;
                        // array_output($row);
                        // die;
                        ?>
                        <tr>
                            <td><?= $i; ?></td>
                            <td><?= $row['name']; ?></td>
                            <!--<td class="user_id"><?= $row['user_id']; ?></td>-->
                            <td><?= $row['email_id']; ?></td>
                            <td><?= $row['exam_name']; ?></td>
                            <td><?php
                                $total_marks_sql = "SELECT * FROM result WHERE exam_id = $row[exam_id]";
                                // echo $row["exam_id"];
                                // echo $total_marks_sql;
                                $total_marks = mysqli_fetch_assoc(mysqli_query($conn, $total_marks_sql))["total_marks"];
                                echo $row['score'] . "/" . $total_marks;
                            ?></td>
                            <td><?= $row['exam_attended_time']; ?></td>
                            <?php
                                    $user_id = $row["user_id"];
                                    $exam_id = $row["exam_id"];
                                    $exam_attended_time = $row["exam_attended_time"];
                                    $question_sql = "SELECT q.id, q.questions FROM questions AS q JOIN question_exam_mapping AS qem ON q.id = qem.question_id WHERE qem.exam_id = $exam_id AND q.question_type != 'title'";
                                                //  echo "<br /><br /><br /><br /><pre>" . $question_sql . "</pre>";
                                    $question_result = mysqli_query($conn, $question_sql);
                                    if(mysqli_num_rows($question_result) > 0) {
                                        while($question_row = mysqli_fetch_assoc($question_result)) {
                                            $question_id = $question_row["id"];
                                            // echo $question_id;
                                            ?>
                                            <td><?= $question_row["questions"];?></td>
                                            <!--<td class="question_id"><?php //echo $question_row["id"];?></td>-->
                                        <td><?php
                                                $is_correct_sql = "SELECT o.is_correct FROM result AS r JOIN answer_attempts AS aa ON r.id = aa.result_id JOIN options AS o ON o.id = aa.selected_option_id WHERE aa.question_id = $question_id AND r.user_id = $user_id AND r.exam_id = $exam_id LIMIT 1";
                                                // echo "<div class='is_correct_sql_values'>" . $is_correct_sql . "</div><br />";
                                                $is_correct_result = mysqli_query($conn, $is_correct_sql);
                                                if(mysqli_num_rows($is_correct_result) > 0) {
                                                    while($is_correct_row = mysqli_fetch_assoc($is_correct_result)) {
                                                        // echo $is_correct_sql;
                                                        // array_output($is_correct_row);
                                                        if((int)$is_correct_row["is_correct"] == 1) {
                                                            echo 1;
                                                        } else {
                                                            echo 0;
                                                        }
                                                    }
                                                } else {
                                                    $is_correct_sql = "SELECT * FROM result WHERE questions_attempted_id = $question_id AND user_id = $user_id AND exam_id = $exam_id LIMIT 1";
                                                $is_correct_result = mysqli_query($conn, $is_correct_sql);
                                                if(mysqli_num_rows($is_correct_result) > 0) {
                                                    while($is_correct_row = mysqli_fetch_assoc($is_correct_result)) {
                                                        if(($is_correct_row["questions_attempted_id"] == $is_correct_row["correctly_questions_attempted_id"]) || ($is_correct_row["correctly_questions_attempted_id"] == 1)) {
                                                            echo 1;
                                                        } else {
                                                            echo 0;
                                                        }
                                                    }
                                                } else {
                                                    echo 0;
                                                }
                                                }
                                        ?></td>
                                            <?php
                                        }
                                    }
                                ?>
                            </tr>
            <?php
                    }
                } else { ?>
                    <tr>
                        <td colspan="7" class="text-center">No results found.</td>
                    </tr>
                <?php }
                // mysqli_close($conn);
            ?>
            </tbody>
        </table>


        
    </div>
<?php
    include "Includes/footer.php";
?>