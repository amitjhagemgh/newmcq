<?php
    define("PAGE", "Questions");
    include "Connection/conn.inc.php";
    include "Includes/header.php";
    include "Includes/functions.inc.php";
    if(!isset($_SESSION["is_login"])) {
        header("location: Authentication/login.php");
    }
    if(!isset($_GET["exam"])) {
        header("location: index.php");
    }

    $exam_name = get_safe_value($conn, $_GET['exam']);
    $sql = "SELECT id FROM exam_portal WHERE exam_name = '$exam_name'";
    $result = mysqli_query($conn, $sql);
    $exam_id = mysqli_fetch_assoc($result)["id"];
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        if(isset($_POST["add_question"])) {
            $question = get_safe_value($conn, $_POST["question"]);
            $opt_a = get_safe_value($conn, $_POST["opt_a"]);
            $opt_b = get_safe_value($conn, $_POST["opt_b"]);
            $check_sql = "SELECT * FROM opinion_questions WHERE   questions = '$question'";
            $check_result = mysqli_query($conn, $check_sql);
            if(mysqli_num_rows($check_result) < 1) {
                $sql = "INSERT INTO opinion_questions(questions, opt_a, opt_b, exam_id) VALUES ('$question', '$opt_a', '$opt_b', '$exam_id')";
                $result = mysqli_query($conn, $sql);
                if($result) {
                    echo "<script>alert('Question added successfully!')</script>";
                }
            } else {
                echo "<script>alert('Question is already Exist.')</script>";
            }
        } elseif(isset($_POST["edit_question_submit"])) {
            $id = get_safe_value($conn, $_POST["edit_id"]);
            $question = get_safe_value($conn, $_POST["edit_question"]);
            $opt_a = get_safe_value($conn, $_POST["edit_opt_a"]);
            $opt_b = get_safe_value($conn, $_POST["edit_opt_b"]);
            $sql = "UPDATE opinion_questions SET questions = '$question', opt_a = '$opt_a', opt_b = '$opt_b' WHERE id = '$id'";
            $result = mysqli_query($conn, $sql);
            if($result) {
                echo "<script>alert('Question updated successfully!')</script>";
            }
        } elseif(isset($_POST["delete_question"])) {
            $id = get_safe_value($conn, $_POST["delete_question"]);
            $sql = "DELETE FROM opinion_questions WHERE id = '$id'";
            $result = mysqli_query($conn, $sql);
            if($result) {
                echo "<script>alert('Question deleted successfully!')</script>";
            }
        }
    }
?>
<h2 class="text-center">Exam Name: <?= $_GET["exam"];?></h2>
<div class="container my-2 d-flex justify-content-end">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#questionModal">Add
        Questions</button>
    <!-- Modal -->
    <div class="modal fade" id="questionModal" tabindex="-1" aria-labelledby="questionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add Question</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="questions.php?exam=<?= $_GET["exam"]; ?>" method="POST">
                        <div class="mb-3">
                            <label for="question" class="form-label">Question</label>
                            <textarea class="form-control" id="question" name="question" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="opt_a" class="form-label">Option A</label>
                            <input type="input" class="form-control" id="opt_a" name="opt_a">
                        </div>
                        <div class="mb-3">
                            <label for="opt_b" class="form-label">Option B</label>
                            <input type="input" class="form-control" id="opt_b" name="opt_b">
                        </div>
                        <button type="submit" class="btn btn-primary d-none" id="submit-question" name="add_question">Submit</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="add-question">Add</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">Sr. no.</th>
                <th scope="col">Questions</th>
                <th scope="col">Option A</th>
                <th scope="col">Option B</th>
                <th scope="col">Edit</th>
                <th scope="col">Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $sql = "SELECT * FROM opinion_questions WHERE exam_id = '$exam_id'";
                $result = mysqli_query($conn, $sql);
                $sr_no = 1;
                if(mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                                <th scope='row'><?php echo $sr_no; ?></th>
                                <td><?php echo $row["questions"]; ?></td>
                                <td><?php echo $row["opt_a"]; ?></td>
                                <td><?php echo $row["opt_b"]; ?></td>
                                <td>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#questionEditModal<?php echo $sr_no; ?>">Edit</button>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#questionDeleteModal<?php echo $sr_no; ?>">Delete</button>
                                </td>
                                <!-- Edit Modal -->
                                <div class="modal fade" id="questionEditModal<?php echo $sr_no; ?>" tabindex="-1"
                                    aria-labelledby="questionModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Question</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="questions.php?exam=<?= $_GET["exam"]; ?>" method="POST">
                                                    <div class="mb-3 d-none">
                                                        <input type="text" class="form-control" id="edit_id" name="edit_id" value="<?php echo $row["id"]; ?>" readonly>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="question" class="form-label">Question</label>
                                                        <textarea class="form-control" id="edit_question" name="edit_question" rows="3"><?php echo $row["questions"]; ?></textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="opt_a" class="form-label">Option A</label>
                                                        <input type="text" class="form-control" id="edit_opt_a" name="edit_opt_a" value="<?php echo $row["opt_a"]; ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="opt_b" class="form-label">Option B</label>
                                                        <input type="text" class="form-control" id="edit_opt_b" name="edit_opt_b" value="<?php echo $row["opt_b"]; ?>">
                                                    </div>
                                                    <button type="submit" class="btn btn-primary d-none edit_question_submit"
                                                        id="submit-question<?php echo $sr_no; ?>" name="edit_question_submit">Submit</button>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary edit-question" id="edit-question<?php echo $sr_no; ?>">Edit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Delete Modal -->
                                <div class="modal fade" id="questionDeleteModal<?php echo $sr_no; ?>" tabindex="-1"
                                    aria-labelledby="questionModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Question</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure to delete this question?</p>
                                                <form action="questions.php?exam=<?= $_GET["exam"]; ?>" method="POST">
                                                    <button type="submit" class="btn btn-primary d-none delete_question_submit"
                                                        id="delete_question<?php echo $sr_no; ?>" name="delete_question" value="<?= $row["id"];?>">Submit</button>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-danger delete-question">Delete</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </tr>
                <?php
                        $sr_no++;
                    }
                } else { ?>
                    <tr><td colspan='6'>No questions found</td></tr>
                <?php }
                ?>
        </tbody>
    </table>
</div>
<?php
    include "Includes/footer.php";
?>