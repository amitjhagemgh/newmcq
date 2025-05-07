<?php
    define("PAGE", "All Personality Type Test Results");
    include "Connection/conn.inc.php";
    include "Includes/header.php";
    include "Includes/functions.inc.php";
    if(!isset($_SESSION["is_login"])) {
        header("location: Authentication/login.php");
    }
?>
    <?php
        // array_output($_SESSION);
        // $user_id_sql = "SELECT * FROM users WHERE email_id = '$_SESSION[email_id]'";
        // $user_id_result = mysqli_query($conn, $user_id_sql);
        // $user_id = mysqli_fetch_assoc($user_id_result)["id"];
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            if(isset($_POST['delete-result'])) {
                $email_id = get_safe_value($conn, $_POST['delete_email_id']);
                $exam_name = get_safe_value($conn, $_POST['delete_exam_name']);
                $test_series = get_safe_value($conn, $_POST['delete_test_series']);
                $user_id_sql = "SELECT id FROM users WHERE email_id = '$email_id'";
                $user_id = mysqli_fetch_assoc(mysqli_query($conn, $user_id_sql))["id"];
                $exam_id_sql = "SELECT id FROM exam_portal WHERE exam_name = '$exam_name'";
                $exam_id = mysqli_fetch_assoc(mysqli_query($conn, $exam_id_sql))["id"];
                $sql = "UPDATE opinion_result SET status = 0 WHERE user_id='$user_id' AND exam_id='$exam_id' AND test_series='$test_series'";
                $result = mysqli_query($conn, $sql);
                if($result) {
                    echo "<script>alert('Result deleted successfully.');</script>";
                }   
            }
        }
    ?>
    <div class="container my-5 min-height-100-vh">
        <h2 class="text-center">Four Axis Personality Type Test Result</h2>

        <!-- User Table -->
        <table class="table table-bordered mt-4" id="opinion-result">
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>View</th>
                    <th>Delete</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Exam Name</th>
                    <th>Exam Attended Time</th>
                </tr>
            </thead>
            <tbody id="userTable">
                <?php
                // Fetch users from the database
                $query = "SELECT 
    opinion_result.user_id,
    opinion_result.exam_id,
    opinion_result.status,
    GROUP_CONCAT(DISTINCT users.name) AS name,
    GROUP_CONCAT(DISTINCT users.email_id) AS email_id,
    exam_portal.exam_name,
    GROUP_CONCAT(DISTINCT opinion_result.exam_attended_time) AS exam_attended_time,
    GROUP_CONCAT(DISTINCT opinion_result.test_series) AS test_series
FROM opinion_result 
JOIN users ON opinion_result.user_id = users.id
JOIN exam_portal ON opinion_result.exam_id = exam_portal.id 
WHERE opinion_result.status = 1
GROUP BY opinion_result.user_id, opinion_result.exam_id, exam_portal.exam_name, opinion_result.test_series;
";
                $result = mysqli_query($conn, $query);
                $i=0;
                if(mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $i++;
                        ?>
                        <tr>
                            <td><?= $i; ?></td>
                            <td><a href="view_opinion_result.php?email_id=<?= $row['email_id']; ?>&exam_name=<?= $row['exam_name']; ?>&test_series=<?= $row['test_series']; ?>" class="btn btn-primary">View</a></td>
                            <td><button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteResultModal<?= $row['email_id']; ?>-<?= $row['test_series']?>" data-id="<?= $row['email_id']; ?>" data-exam_name="<?= $row['exam_name'];?>" data-test-series="<?= $row['test_series'];?>">Delete</button></td>
                            <td><?= $row['name']; ?></td>
                            <td><?= $row['email_id']; ?></td>
                            <td><?= $row['exam_name']; ?></td>
                            <td><?= $row['exam_attended_time']; ?></td>
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
                // Fetch users from the database
                $query = "SELECT 
    GROUP_CONCAT(DISTINCT opinion_result.user_id) AS user_id,
    GROUP_CONCAT(DISTINCT opinion_result.exam_id) AS exam_id,
    GROUP_CONCAT(DISTINCT opinion_result.test_series) AS test_series,
    GROUP_CONCAT(DISTINCT users.name) AS name,
    GROUP_CONCAT(DISTINCT users.email_id) AS email_id,
    exam_portal.exam_name
FROM opinion_result 
JOIN users ON opinion_result.user_id = users.id
JOIN exam_portal ON opinion_result.exam_id = exam_portal.id 
GROUP BY exam_portal.exam_name, opinion_result.test_series;
";
                $result = mysqli_query($conn, $query);
                $i=0;
                if(mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $i++;
                        ?>
    <!-- Modal -->
        <div class="modal fade" id="deleteResultModal<?= $row['email_id']; ?>-<?= $row['test_series']?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <form action="all_opinion_results.php" method="POST" id="deleteResultForm">
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
        <?php
                    }
                }
        ?>
<?php
    include "Includes/footer.php";
?>