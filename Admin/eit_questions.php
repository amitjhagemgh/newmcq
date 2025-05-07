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
            $domain_id = get_safe_value($conn, $_POST["domain"]);
            $opt_a = get_safe_value($conn, $_POST["opt_a"]);
            $marks_a = get_safe_value($conn, $_POST["marks_a"]);
            $opt_b = get_safe_value($conn, $_POST["opt_b"]);
            $marks_b = get_safe_value($conn, $_POST["marks_b"]);
            $opt_c = get_safe_value($conn, $_POST["opt_c"]);
            $marks_c = get_safe_value($conn, $_POST["marks_c"]);
            $opt_d = get_safe_value($conn, $_POST["opt_d"]);
            $marks_d = get_safe_value($conn, $_POST["marks_d"]);
            $opt_e = get_safe_value($conn, $_POST["opt_e"]);
            $marks_e = get_safe_value($conn, $_POST["marks_e"]);
            $check_sql = "SELECT * FROM eit_questions WHERE questions = '$question'";
            $check_result = mysqli_query($conn, $check_sql);
            if(mysqli_num_rows($check_result) < 1) {
                $sql = "INSERT INTO eit_questions(questions, domain_id, opt_a, marks_a, opt_b, marks_b, opt_c, marks_c, opt_d, marks_d, opt_e, marks_e, exam_id) VALUES ('$question', '$domain_id', '$opt_a', '$marks_a', '$opt_b', '$marks_b', '$opt_c', '$marks_c', '$opt_d', '$marks_d', '$opt_e', '$marks_e', '$exam_id')";
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
            $marks_a = get_safe_value($conn, $_POST["edit_marks_a"]);
            $opt_b = get_safe_value($conn, $_POST["edit_opt_b"]);
            $marks_b = get_safe_value($conn, $_POST["edit_marks_b"]);
            $opt_c = get_safe_value($conn, $_POST["edit_opt_c"]);
            $marks_c = get_safe_value($conn, $_POST["edit_marks_c"]);
            $opt_d = get_safe_value($conn, $_POST["edit_opt_d"]);
            $marks_d = get_safe_value($conn, $_POST["edit_marks_d"]);
            $opt_e = get_safe_value($conn, $_POST["edit_opt_e"]);
            $marks_e = get_safe_value($conn, $_POST["edit_marks_e"]);
            $sql = "UPDATE eit_questions SET questions = '$question', opt_a = '$opt_a', opt_b = '$opt_b', opt_c = '$opt_c', opt_d = '$opt_d', opt_e = '$opt_e', marks_a = '$marks_a', marks_b = '$marks_b', marks_c = '$marks_c', marks_d = '$marks_d', marks_e = '$marks_e' WHERE id = '$id'";
            $result = mysqli_query($conn, $sql);
            if($result) {
                echo "<script>alert('Question updated successfully!')</script>";
            }
        } elseif(isset($_POST["delete_question"])) {
            $id = get_safe_value($conn, $_POST["delete_question"]);
            $sql = "DELETE FROM eit_questions WHERE id = '$id'";
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
    <div class="modal fade" id="questionModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="questionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add Question</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="eit_questions.php?exam=<?= $_GET["exam"]; ?>" method="POST">
                        <div class="mb-3">
                            <label for="question" class="form-label">Question</label>
                            <textarea class="form-control border border-dark" id="question" name="question" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="domain" class="form-label">Domain</label>
                            <select name="domain" id="domain" class="form-select border border-dark" required>
                                <option value="">Select Domain</option>
                                <?php
                                    $domain_sql = "SELECT * FROM domain";
                                    $domain_result = mysqli_query($conn, $domain_sql);
                                    if(mysqli_num_rows($domain_result) > 0) {
                                        while($row = mysqli_fetch_assoc($domain_result)) {
                                            echo "<option value='" . $row["id"] . "'>" . $row["domains"] . "</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="opt_a" class="form-label">Option A</label>
                            <input type="input" class="form-control border border-dark" id="opt_a" name="opt_a" required>
                        </div>
                        <div class="mb-3">
                            <label for="opt_a" class="form-label">Marks A</label>
                            <input type="input" class="form-control border border-dark" id="marks_a" name="marks_a" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="3" required>
                        </div>
                        <div class="mb-3">
                            <label for="opt_b" class="form-label">Option B</label>
                            <input type="input" class="form-control border border-dark" id="opt_b" name="opt_b" required>
                        </div>
                        <div class="mb-3">
                            <label for="opt_b" class="form-label">Marks B</label>
                            <input type="input" class="form-control border border-dark" id="marks_b" name="marks_b" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="3" required>
                        </div>
                        <div class="mb-3">
                            <label for="opt_b" class="form-label">Option C</label>
                            <input type="input" class="form-control border border-dark" id="opt_c" name="opt_c" required>
                        </div>
                        <div class="mb-3">
                            <label for="opt_b" class="form-label">Marks C</label>
                            <input type="input" class="form-control border border-dark" id="marks_c" name="marks_c" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="3" required>
                        </div>
                        <div class="mb-3">
                            <label for="opt_b" class="form-label">Option D</label>
                            <input type="input" class="form-control border border-dark" id="opt_d" name="opt_d" required>
                        </div>
                        <div class="mb-3">
                            <label for="opt_b" class="form-label">Marks D</label>
                            <input type="input" class="form-control border border-dark" id="marks_d" name="marks_d" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="3" required>
                        </div>
                        <div class="mb-3">
                            <label for="opt_b" class="form-label">Option E</label>
                            <input type="input" class="form-control border border-dark" id="opt_e" name="opt_e" required>
                        </div>
                        <div class="mb-3">
                            <label for="opt_b" class="form-label">Marks E</label>
                            <input type="input" class="form-control border border-dark" id="marks_e" name="marks_e" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength="3" required>
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

<div class="container min-height-100-vh table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">Sr. no.</th>
                <th scope="col">Edit</th>
                <th scope="col">Delete</th>
                <th scope="col">Questions</th>
                <th scope="col">Domain</th>
                <th scope="col">Option A</th>
                <th scope="col">Option B</th>
                <th scope="col">Option C</th>
                <th scope="col">Option D</th>
                <th scope="col">Option E</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $sql = "SELECT * FROM eit_questions WHERE exam_id = '$exam_id'";
                $result = mysqli_query($conn, $sql);
                $sr_no = 1;
                if(mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                                <th scope='row'><?php echo $sr_no; ?></th>
                                <td>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#questionEditModal<?php echo $sr_no; ?>">Edit</button>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#questionDeleteModal<?php echo $sr_no; ?>">Delete</button>
                                </td>
                                <td><?php echo $row["questions"]; ?></td>
                                <td><?php
                                    $domain_id = $row["domain_id"];
                                    $sql = "SELECT domains FROM domain WHERE id = $domain_id";
                                    $domain_result = mysqli_query($conn, $sql);
                                    if(mysqli_num_rows($domain_result) > 0) {
                                        echo mysqli_fetch_assoc($domain_result)["domains"];
                                    }
                                ?></td>
                                <td><?php echo "<div class='eit-options-container'><span>" . $row["opt_a"] . "</span> <span>(" . $row["marks_a"] . " Marks)</span>"; ?></td>
                                <td><?php echo "<div class='eit-options-container'><span>" . $row["opt_b"] . "</span> <span>(" . $row["marks_b"] . " Marks)</span>"; ?></td>
                                <td><?php echo "<div class='eit-options-container'><span>" . $row["opt_c"] . "</span> <span>(" . $row["marks_c"] . " Marks)</span>"; ?></td>
                                <td><?php echo "<div class='eit-options-container'><span>" . $row["opt_d"] . "</span> <span>(" . $row["marks_d"] . " Marks)</span>"; ?></td>
                                <td><?php echo "<div class='eit-options-container'><span>" . $row["opt_e"] . "</span> <span>(" . $row["marks_e"] . " Marks)</span>"; ?></td>
                                <!-- Edit Modal -->
                                <div class="modal fade" data-bs-backdrop="static" id="questionEditModal<?php echo $sr_no; ?>" tabindex="-1"
                                    aria-labelledby="questionModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Question</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="eit_questions.php?exam=<?= $_GET["exam"]; ?>" method="POST">
                                                    <div class="mb-3 d-none">
                                                        <input type="text" class="form-control border border-dark" id="edit_id" name="edit_id" value="<?php echo $row["id"]; ?>" readonly>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="question" class="form-label">Question</label>
                                                        <textarea class="form-control border border-dark" id="edit_question" name="edit_question" rows="3"><?php echo $row["questions"]; ?></textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="opt_a" class="form-label">Option A</label>
                                                        <input type="text" class="form-control border border-dark" id="edit_opt_a" name="edit_opt_a" value="<?php echo $row["opt_a"]; ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="opt_a" class="form-label">Marks A</label>
                                                        <input type="text" class="form-control border border-dark" id="edit_marks_a" name="edit_marks_a" value="<?php echo $row["marks_a"]; ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="opt_b" class="form-label">Option B</label>
                                                        <input type="text" class="form-control border border-dark" id="edit_opt_b" name="edit_opt_b" value="<?php echo $row["opt_b"]; ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="opt_b" class="form-label">Marks B</label>
                                                        <input type="text" class="form-control border border-dark" id="edit_marks_b" name="edit_marks_b" value="<?php echo $row["marks_b"]; ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="opt_b" class="form-label">Option C</label>
                                                        <input type="text" class="form-control border border-dark" id="edit_opt_c" name="edit_opt_c" value="<?php echo $row["opt_c"]; ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="opt_b" class="form-label">Marks C</label>
                                                        <input type="text" class="form-control border border-dark" id="edit_marks_c" name="edit_marks_c" value="<?php echo $row["marks_c"]; ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="opt_b" class="form-label">Option D</label>
                                                        <input type="text" class="form-control border border-dark" id="edit_opt_d" name="edit_opt_d" value="<?php echo $row["opt_d"]; ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="opt_b" class="form-label">Marks D</label>
                                                        <input type="text" class="form-control border border-dark" id="edit_marks_d" name="edit_marks_d" value="<?php echo $row["marks_d"]; ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="opt_b" class="form-label">Option E</label>
                                                        <input type="text" class="form-control border border-dark" id="edit_opt_e" name="edit_opt_e" value="<?php echo $row["opt_e"]; ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="opt_b" class="form-label">Marks E</label>
                                                        <input type="text" class="form-control border border-dark" id="edit_marks_e" name="edit_marks_e" value="<?php echo $row["marks_e"]; ?>">
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
                                                <form action="eit_questions.php?exam=<?= $_GET["exam"]; ?>" method="POST">
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
                    <tr><td colspan='14' class="text-center">No questions found</td></tr>
                <?php }
                ?>
        </tbody>
    </table>
</div>
<?php
    include "Includes/footer.php";
?>