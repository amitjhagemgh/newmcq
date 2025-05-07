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
<div class="container my-3 min-height-100-vh">
<div class="container d-flex justify-content-end p-0">
    <label class="">Search in: 
        <select id="columnSelector">
            <option value="all">All Columns</option>
            <option value="3">Exam Name</option>
        </select>
    </label>
</div>
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
                $exam_name_sql = "SELECT 
    result.user_id AS user_id, 
    result.exam_id AS exam_id, 
    MAX(result.score) AS score,
    MAX(result.total_marks) AS total_marks,
    exam_portal.exam_name, 
    users.email_id AS email_id, 
    MAX(result.test_series) AS test_series,
    result.exam_attended_time, 
    users.name AS name 
FROM 
    result 
INNER JOIN 
    users ON result.user_id = users.id 
INNER JOIN 
    exam_portal ON result.exam_id = exam_portal.id
WHERE result.status = 1 AND user_id = '$user_id'
GROUP BY 
    result.user_id, 
    result.exam_id";
                $exam_name_sql_result = mysqli_query($conn, $exam_name_sql);
                    while($row = mysqli_fetch_assoc($exam_name_sql_result)) { ?>
                    <?php // array_output($row); ?>
                        <tr>
                            <!-- <th scope="row"><?php // echo $sr_no; ?></th> -->
                            <td><?= $name; ?></td>
                            <td><?= $email_id; ?></td>
                            <td><?php
                                    // echo $row["exam_id"] . "<br />";
                                    $total_marks_sql = "SELECT * FROM questions WHERE question_type != 'title'";
                                    $total_marks = mysqli_num_rows(mysqli_query($conn, $total_marks_sql));
                                    echo "$row[score]/$row[total_marks]";
                                    // echo $row["score"] . "/" . $row["total_marks"] . " (" . round(($row["score"]/$row["total_marks"])*100, 2) . "%)";
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