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
        $user_id_sql = "SELECT * FROM users WHERE email_id = '$_SESSION[email_id]'";
        $user_id_result = mysqli_query($conn, $user_id_sql);
        $user_id = mysqli_fetch_assoc($user_id_result)["id"];
    ?>
    <div class="container my-5 min-height-100-vh">
        <h2 class="text-center">Personality Type Test Result</h2>

        <!-- User Table -->
        <table class="table table-bordered mt-4" id="user-opinion-result-table">
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Exam Name</th>
                    <th>Exam Attended Time</th>
                    <th>View</th>
                </tr>
            </thead>
            <tbody id="userTable">
<?php
                // Fetch users from the database
                $query = "SELECT exam_attended_time,
                            MIN(opinion_result.id)      AS id,
                            MIN(opinion_result.user_id) AS user_id,
                            MIN(opinion_result.status)  AS status,
                            MIN(users.email_id)         AS email_id,
                            MIN(users.name)             AS name,
                            MIN(exam_portal.exam_name)  AS exam_name,
                            MIN(opinion_result.test_series) AS test_series
                        FROM   opinion_result
                            JOIN users
                                ON opinion_result.user_id = users.id
                            JOIN exam_portal
                                ON opinion_result.exam_id = exam_portal.id
                        WHERE  users.id = $user_id AND opinion_result.status = 1
                        GROUP  BY exam_attended_time;";
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
                            <td><?= $row['exam_attended_time']; ?></td>
                            <td><a href="view_opinion_result.php?email_id=<?= $row['email_id']; ?>&exam_name=<?= $row['exam_name']; ?>&test_series=<?= $row['test_series']; ?>" class="btn btn-primary">View</a></td>
                        </tr>
<?php
                    }
                } else { ?>
                    <tr>
                        <td colspan="6">No results found.</td>
                    </tr>
                <?php }
                mysqli_close($conn);
            ?>
            </tbody>
        </table>
    </div>
<?php
    include "Includes/footer.php";
?>