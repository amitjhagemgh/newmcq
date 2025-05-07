<?php
    define("PAGE", "All EIT Results");
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
                $test_series = get_safe_value($conn, $_POST['delete_test_series']);
                $get_exam_id_sql = "SELECT id FROM exam_portal WHERE exam_name = '$exam_name'";
                $exam_id_result = mysqli_query($conn, $get_exam_id_sql);
                $exam_id_row = mysqli_fetch_assoc($exam_id_result);
                $exam_id = $exam_id_row["id"];
                // Fetching result ID for deleting the answer_attempts records accordingly result_id
                // $result_id = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM result WHERE user_id = '$user_id' AND exam_id = '$exam_id' AND test_series = '$test_series'"))["id"];
                $sql = "UPDATE eit_result SET status = 0 WHERE user_id = '$user_id' AND test_series = '$test_series'";
                $result = mysqli_query($conn, $sql);
                // $sql = "DELETE FROM self_awareness_result WHERE user_id = '$user_id' AND series = '$test_series'";
                // $result = mysqli_query($conn, $sql);
                // $sql = "DELETE FROM managing_emotions_result WHERE user_id = '$user_id' AND series = '$test_series'";
                // $result = mysqli_query($conn, $sql);
                // $sql = "DELETE FROM motivating_oneself_result WHERE user_id = '$user_id' AND series = '$test_series'";
                // $result = mysqli_query($conn, $sql);
                // $sql = "DELETE FROM empathy_result WHERE user_id = '$user_id' AND series = '$test_series'";
                // $result = mysqli_query($conn, $sql);
                // $sql = "DELETE FROM handling_relationships_result WHERE user_id = '$user_id' AND series = '$test_series'";
                // $result = mysqli_query($conn, $sql);
                if($result) {
                    echo "<script>alert('Result deleted successfully.');</script>";
                }   
            }
        }
    ?>
    <div class="container mt-5">
        <h2 class="text-center">EIT Results</h2>
    </div>
    <div class="container d-flex justify-content-end">
        <label class="">Search in: 
            <select id="columnSelector">
                <option value="all">All Columns</option>
                <option value="1">Name</option>
                <option value="2">Email ID</option>
            </select>
        </label>
    </div>
    <div class="container mb-5 table-responsive min-height-100-vh">

        <!-- User Table -->
        <table class="table table-bordered mt-4" id="eit-result-table">
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Delete</th>
                    <th>Name</th>
                    <th>Email ID</th>
                    <th>Exam Name</th>
                    <th>Self Awareness</th>
                    <th>Managing Emotions</th>
                    <th>Motivating Oneself</th>
                    <th>Empathy</th>
                    <th>Handling Relationships</th>
                    <th>Total</th>
                    <th>Exam Attended Time</th>
                </tr>
            </thead>
            <tbody id="userTable">
                <?php
                // Fetch users from the database
                // Right before the query
                $query = "SELECT 
    result.user_id AS user_id,
    result.exam_id AS exam_id,
    exam_portal.exam_name,
    users.email_id AS email_id,
    result.test_series,
    GROUP_CONCAT(DISTINCT users.name) AS name 
FROM eit_result AS result
INNER JOIN users ON result.user_id = users.id 
INNER JOIN exam_portal ON result.exam_id = exam_portal.id 
WHERE exam_portal.exam_name = 'Emotional Intelligence Test' AND result.status = 1
GROUP BY result.user_id, result.exam_id, result.test_series;";
                        // echo $query;
                $result = mysqli_query($conn, $query);
                $i=0;
                if(mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $i++;
                        ?>
                        <tr>
                            <td><?= $i; ?></td>
                            <td><button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteResultModal<?= $row['email_id']; ?>" data-id="<?= $row['email_id']; ?>" data-exam_name="<?= $row['exam_name'];?>">Delete</button></td>
                            <td><?= $row['name']; ?></td>
                            <td><?= $row['email_id']; ?></td>
                            <td><?= $row['exam_name']; ?></td>
                            <td>
                            <?php
                                $self_awareness_marks_sql = "SELECT * FROM self_awareness_result WHERE user_id = " . $row["user_id"] . " AND series = " . $row["test_series"];
                                $self_awareness_marks_result = mysqli_query($conn, $self_awareness_marks_sql);
                                if(mysqli_num_rows($self_awareness_marks_result) > 0) {
                                    $self_awareness_marks_row = mysqli_fetch_assoc($self_awareness_marks_result);
                                    echo $self_awareness_marks_row["score"];
                                } else {
                                    echo "0";
                                }
                            ?>
                            </td>
                            <td>
                            <?php
                                $managing_emotions_marks_sql = "SELECT * FROM managing_emotions_result WHERE user_id = " . $row["user_id"] . " AND series = " . $row["test_series"];
                                $managing_emotions_marks_result = mysqli_query($conn, $managing_emotions_marks_sql);
                                if(mysqli_num_rows($managing_emotions_marks_result) > 0) {
                                    $managing_emotions_marks_row = mysqli_fetch_assoc($managing_emotions_marks_result);
                                    echo $managing_emotions_marks_row["score"];
                                } else {
                                    echo "0";
                                }
                            ?>
                            </td>
                            <td>
                            <?php
                                $motivating_oneself_marks_sql = "SELECT * FROM motivating_oneself_result WHERE user_id = " . $row["user_id"] . " AND series = " . $row["test_series"];
                                $motivating_oneself_marks_result = mysqli_query($conn, $motivating_oneself_marks_sql);
                                if(mysqli_num_rows($motivating_oneself_marks_result) > 0) {
                                    $motivating_oneself_marks_row = mysqli_fetch_assoc($motivating_oneself_marks_result);
                                    echo $motivating_oneself_marks_row["score"];
                                } else {
                                    echo "0";
                                }
                            ?>
                            </td>
                            <td>
                            <?php
                                $empathy_marks_sql = "SELECT * FROM empathy_result WHERE user_id = " . $row["user_id"] . " AND series = " . $row["test_series"];
                                $empathy_marks_result = mysqli_query($conn, $empathy_marks_sql);
                                if(mysqli_num_rows($empathy_marks_result) > 0) {
                                    $empathy_marks_row = mysqli_fetch_assoc($empathy_marks_result);
                                    echo $empathy_marks_row["score"];
                                } else {
                                    echo "0";
                                }
                            ?>
                            </td>
                            <td>
                            <?php
                                $handling_relationships_marks_sql = "SELECT * FROM handling_relationships_result WHERE user_id = " . $row["user_id"] . " AND series = " . $row["test_series"];
                                $handling_relationships_marks_result = mysqli_query($conn, $handling_relationships_marks_sql);
                                if(mysqli_num_rows($handling_relationships_marks_result) > 0) {
                                    $handling_relationships_marks_row = mysqli_fetch_assoc($handling_relationships_marks_result);
                                    echo $handling_relationships_marks_row["score"];
                                }else {
                                    echo "0";
                                }
                            ?>
                            </td>
                            <td>
                                <?php
                                    $total_score_sql = "SELECT 
            all_users.user_id,
            all_users.series,
            COALESCE(sar.score, 0) + 
            COALESCE(mer.score, 0) + 
            COALESCE(mor.score, 0) + 
            COALESCE(er.score, 0) + 
            COALESCE(hrr.score, 0) as total_score
          FROM (SELECT user_id, series FROM self_awareness_result 
                UNION 
                SELECT user_id, series FROM managing_emotions_result
                UNION 
                SELECT user_id, series FROM motivating_oneself_result
                UNION 
                SELECT user_id, series FROM empathy_result
                UNION 
                SELECT user_id, series FROM handling_relationships_result) all_users
          LEFT JOIN self_awareness_result sar ON all_users.user_id = sar.user_id AND all_users.series = sar.series
          LEFT JOIN managing_emotions_result mer ON all_users.user_id = mer.user_id AND all_users.series = mer.series
          LEFT JOIN motivating_oneself_result mor ON all_users.user_id = mor.user_id AND all_users.series = mor.series
          LEFT JOIN empathy_result er ON all_users.user_id = er.user_id AND all_users.series = er.series
          LEFT JOIN handling_relationships_result hrr ON all_users.user_id = hrr.user_id AND all_users.series = hrr.series
          WHERE all_users.user_id = " . $row["user_id"] . " 
          AND all_users.series = " . $row["test_series"];
                                    $total_score_result = mysqli_query($conn, $total_score_sql);
                                    $total_marks_sql = "SELECT COUNT(*) FROM eit_questions";
                                    $total_marks_result = mysqli_query($conn, $total_marks_sql);
                                    $total_marks_row = mysqli_fetch_assoc($total_marks_result);
                                    if(mysqli_num_rows($total_score_result) > 0) {
                                        $total_score_row = mysqli_fetch_assoc($total_score_result);
                                        // array_output($row);
                                        echo $total_score_row["total_score"] . "/" . $total_marks_row["COUNT(*)"] * 5;
                                    } else {
                                        echo 0 . "/" . $total_marks_row["COUNT(*)"] * 5;
                                    }
                                ?>
                            </td>
                            <td><?php
                                $exam_attended_time_sql = "SELECT * FROM self_awareness_result WHERE user_id = " . $row["user_id"] . " AND series = " . $row["test_series"];
                                $exam_attended_time_result = mysqli_query($conn, $exam_attended_time_sql);
                                if(mysqli_num_rows($exam_attended_time_result) > 0) {
                                    $exam_attended_time_row = mysqli_fetch_assoc($exam_attended_time_result);
                                    echo $exam_attended_time_row["created_on"];
                                }
                            ?></td>
                            <!-- Modal -->
                            <div class="modal fade" id="deleteResultModal<?= $row['email_id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                    <form action="all_eit_results.php" method="POST" id="deleteResultForm">
                                        <div class="modal-body">
                                            <input type="hidden" id="delete_email_id" name="delete_email_id" value="<?= $row['email_id']; ?>">
                                            <input type="hidden" id="delete_exam_name" name="delete_exam_name" value="<?= $row['exam_name']; ?>">
                                            <input type="hidden" id="delete_test_series" name="delete_test_series" value="<?= $row['test_series']; ?>">
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
                        <td colspan="12" class="text-center">No results found.</td>
                    </tr>
                <?php }
                // mysqli_close($conn);
            ?>
            </tbody>
        </table>
        
        <!-- <div class="table-responsive">
            User Result Table for Excel Output
            <table class="table table-bordered mt-4 d-none" id="result-table-excel-output">
                <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th>Name</th>
                        <th>Email ID</th>
                        <th>Exam Name</th>
                        <th>Score</th>
                        <th>Exam Attended Time</th>
                        <th>User ID</th>
                        <th>Exam ID</th>
                    </tr>
                </thead>
                <tbody id="userTable">
                    <?php
                    // Fetch users from the database
                    // Right before the query
    //                 $query = "SELECT 
    //     result.user_id AS user_id,
    //     result.exam_id AS exam_id,
    //     MAX(result.score) AS score,
    //     exam_portal.exam_name,
    //     users.email_id AS email_id,
    //     MAX(result.exam_attended_time) AS exam_attended_time,
    //     GROUP_CONCAT(DISTINCT users.name) AS name 
    // FROM result 
    // INNER JOIN users ON result.user_id = users.id 
    // INNER JOIN exam_portal ON result.exam_id = exam_portal.id 
    // GROUP BY result.user_id, result.exam_id, exam_portal.exam_name, users.email_id;
    // ";
                            // echo $query;
                    // $result = mysqli_query($conn, $query);
                    // $i=0;
                    // if(mysqli_num_rows($result) > 0) {
                        // while ($row = mysqli_fetch_assoc($result)) {
                            // $i++;
                            // array_output($row);
                            // die;
                            ?>
                            <tr>
                                <td><?php // echo $i; ?></td>
                                <td><?php // echo $row['name']; ?></td>
                                <td class="user_id"><?php // echo $row['user_id']; ?></td>
                                <td><?php // echo $row['email_id']; ?></td>
                                <td><?php // echo $row['exam_name']; ?></td>
                                <td><?php
                                    // $total_marks_sql = "SELECT * FROM questions WHERE question_type != 'title'";
                                    // echo $row["exam_id"];
                                    // echo $total_marks_sql;
                                    // $total_marks = mysqli_num_rows(mysqli_query($conn, $total_marks_sql));
                                    // echo $row['score'] . "/" . $total_marks;
                                ?></td>
                                <td><?php // echo $row['exam_attended_time']; ?></td>
                                <?php
                                    // $user_id = $row["user_id"];
                                    // $exam_id = $row["exam_id"];
                                    // $exam_attended_time = $row["exam_attended_time"];
                                    // $question_sql = "SELECT * FROM questions WHERE question_type != 'title'";
                                                //  echo "<br /><br /><br /><br /><pre>" . $question_sql . "</pre>";
                                    // $question_result = mysqli_query($conn, $question_sql);
                                    // if(mysqli_num_rows($question_result) > 0) {
                                        // while($question_row = mysqli_fetch_assoc($question_result)) {
                                            // $question_id = $question_row["id"];
                                            ?>
                                            <td><?php // echo $question_row["questions"];?></td>
                                            <td class="question_id"><?php //echo $question_row["id"];?></td>
                                        <td><?php
                                                // $is_correct_sql = "SELECT * FROM result WHERE questions_attempted_id = $question_id AND user_id = $user_id AND exam_id = $exam_id LIMIT 1";
                                                // $is_correct_result = mysqli_query($conn, $is_correct_sql);
                                                // if(mysqli_num_rows($is_correct_result) > 0) {
                                                    // while($is_correct_row = mysqli_fetch_assoc($is_correct_result)) {
                                                        // if(($is_correct_row["questions_attempted_id"] == $is_correct_row["correctly_questions_attempted_id"]) || ($is_correct_row["correctly_questions_attempted_id"] == 1)) {
                                                            // echo 1;
                                                        // } else {
                                                            // echo 0;
                                                        // }
                                                    // }
                                                // } else {
                                                    // echo 0;
                                                // }
                                        ?></td>
                                            <?php
                                        // }
                                    // }
                                ?>
                            </tr>
                <?php
                        // }
                    // } else { ?>
                        <tr>
                            <td colspan="7" class="text-center">No results found.</td>
                        </tr>
                    <?php // }
                    // mysqli_close($conn);
                ?>
                </tbody>
            </table>
        </div> -->


        
    </div>
<?php
    include "Includes/footer.php";
?>