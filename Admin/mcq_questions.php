<?php
    define("PAGE", "MCQ Questions");
    include "Connection/conn.inc.php";
    include "Includes/header.php";
    include "Includes/functions.inc.php";
    header('Content-type: text/html; charset=ASCII');

    if (!isset($_SESSION["is_login"])) {
        header("location: Authentication/login.php");
    }
    if (!isset($_GET["exam"])) {
        header("location: index.php");
    }

    $exam_name = get_safe_value($conn, $_GET['exam']);
    $exam_name_id_sql = "SELECT id FROM exam_portal WHERE exam_name = '$exam_name'";
    $exam_name_id_result = mysqli_query($conn, $exam_name_id_sql);
    if(mysqli_num_rows($exam_name_id_result)==0){
        echo "<script>alert('$_GET[exam] Exam does not exist!');window.location.href='index.php';</script>";
    };
    $exam_id = mysqli_fetch_assoc($exam_name_id_result)["id"];
    $alert_message = ''; // Initialize an empty alert message

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["add_question"])) {
            $check_sql_question = "SELECT * FROM questions WHERE questions = '" . get_safe_value($conn, $_POST['question']) . "' AND exam_id = '$exam_id'";
            $check_result_question = mysqli_query($conn, $check_sql_question);

            if (mysqli_num_rows($check_result_question) > 0) {
                $alert_message = "<div class=\"alert alert-warning alert-dismissible fade show\" role=\"alert\">
                <strong>Question already exists!</strong> Please add a different Question.
                <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>
                </div>";
            } else {
                // Sanitize inputs
                $question = get_safe_value($conn, $_POST["question"]);
                $exam_name = get_safe_value($conn, $_GET["exam"]);
                $question_type = get_safe_value($conn, $_POST["question-type"]);
                // Handle image upload
                $questionImage = '';
                if (!empty($_FILES["question-image"]["tmp_name"])) {
                    $uploadDir = "IMG/Questions/";
                    $questionImage = basename($_FILES["question-image"]["name"]);
                    move_uploaded_file($_FILES["question-image"]["tmp_name"], $uploadDir . $questionImage);
                }
                if($_POST["question-type"] == "title"){
                    $sql = "INSERT INTO questions (questions, question_image, question_type, exam_id) 
                                VALUES ('$question', '', 'title', '$exam_id')";
                    $result = mysqli_query($conn, $sql);
                    if($result) {
                        $alert_message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                            Title added successfully!
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>';
                    }
                } else {
                    // Combine Options and Correct Status in Single Array
                    $options = $_POST['options'];
                    $correct_options = $_POST['correct_options'];
    
                    // Insert into Database
                    $sql = "INSERT INTO questions (questions, question_image, question_id, question_type, exam_id, no_of_times_correctly_attempted, no_of_times_attempted, status) 
                                VALUES ('$question', '$questionImage', '', '$question_type', '$exam_id', 0, 0, 1)";
    
                    $result = mysqli_query($conn, $sql);
    
                    if ($result) {
                        $select_question_sql = "SELECT id FROM questions WHERE questions = '$question' AND exam_id = '$exam_id'";
                        $select_question_result = mysqli_query($conn, $select_question_sql);
                        $question_id = mysqli_fetch_assoc($select_question_result)["id"];
                        
                        for ($i = 0; $i < count($options); $i++) {
                            $is_correct = ($correct_options[$i] == "correct") ? 1 : 0;
                            $option_sql = "INSERT INTO options (question_id, answers, is_correct, status) VALUES 
                            ('$question_id', '" . get_safe_value($conn, $options[$i]) . "', '" . $is_correct . "', 1)";
                            // echo $option_sql;
                            // die;
                            $option_result = mysqli_query($conn, $option_sql);
    
                            if (!$option_result) {
                                die("Error inserting option: " . mysqli_error($conn));
                            }
                        }
    
                        $alert_message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                            Question added successfully!
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>';
                    }
                }
            }
        } elseif (isset($_POST["edit_question_submit"])) {
            $id = get_safe_value($conn, $_POST["edit_id"]);
            $old_question = get_safe_value($conn, $_POST["old_question"]);
            $question = get_safe_value($conn, $_POST["edit_question"]);
            // array_output_die($_POST);

            // Handle image upload
            $questionImage = '';
            if (!empty($_FILES["edit-question-image"]["name"])) {
                $uploadDir = "IMG/Questions/";
                $fileExtension = pathinfo($_FILES["edit-question-image"]["name"], PATHINFO_EXTENSION);
                $questionImage = uniqid() . '.' . $fileExtension;
                $uploadPath = $uploadDir . $questionImage;

                if (!move_uploaded_file($_FILES["edit-question-image"]["tmp_name"], $uploadPath)) {
                    $alert_message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        Failed to upload image!
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>';
                }
            }

            // Delete existing entries for this question
            $delete_existing_sql = "DELETE FROM options WHERE question_id = '$id'";
            $all_insertions_successful = true;
            mysqli_query($conn, $delete_existing_sql);
            $question_type = get_safe_value($conn, $_POST["edit-question-type"]);
            mysqli_query($conn, "UPDATE questions SET questions = '$question', question_image = '$questionImage', question_type = '$question_type' WHERE id = '$id'");
            if($_POST["edit-question-type"] !== "title"){
                // Insert new question and options
                $options = $_POST['options'];
                $correct_options = $_POST['correct_options'];
                foreach ($options as $i => $option) {
                    $correct = get_safe_value($conn, $correct_options[$i]);
                    $is_correct = ($correct == "correct") ? 1 : 0;
                    $sql = "INSERT INTO options (question_id, answers, is_correct, status) 
                VALUES ('$id', '$option', '$is_correct', '1')";
                    $result = mysqli_query($conn, $sql);

                    if (!$result) {
                        $all_insertions_successful = false;
                        break;
                    }
                }
            }

            $alert_message = $all_insertions_successful
                ? '<div class="alert alert-success alert-dismissible fade show" role="alert">Question updated successfully!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                : '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error updating question!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        // } elseif (isset($_POST["delete_question"])) {
        //     $id = get_safe_value($conn, $_POST["question_id"]);
        //     if($_POST["question_type"] == "title"){
        //         $delete_sql = "DELETE FROM questions WHERE id = $id;";
        //     } else {
        //         $delete_sql = "DELETE q, o FROM questions AS q JOIN options AS o ON q.id = o.question_id WHERE q.id = $id;";
        //     }
        //     $delete_result = mysqli_query($conn, $delete_sql);

        //     $alert_message = $delete_result
        //         ? '<div class="alert alert-success alert-dismissible fade show" role="alert">Question deleted successfully!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
        //         : '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error deleting question!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        } elseif(isset($_POST["remove_question"])) {
            $question_id = $_POST["question_id"];
            $remove_question_sql = "DELETE FROM question_exam_mapping WHERE exam_id = $exam_id AND question_id = $question_id";
            // echo $remove_question_sql;
            $remove_question_result = mysqli_query($conn, $remove_question_sql);
            $alert_message = $remove_question_result ? '<div class="alert alert-success alert-dismissible fade show" role="alert">Question Removed from this quiz successfully!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
            : '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error removing question!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        }
    }

    echo $alert_message;
?>

<div class="container my-2 d-flex justify-content-end">
    <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#questionModal">Add
        Questions</button> -->
    <!-- Modal -->
    <div class="modal fade" id="questionModal" tabindex="-1" aria-labelledby="questionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add Question</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body position-relative">
                    <!--<button class="btn btn-primary position-absolute add-option add-question-add-option">Add Option</button>-->
                    <form action="mcq_questions.php?exam=<?= $_GET["exam"]; ?>" id="add-question-form" method="POST" enctype="multipart/form-data">
                        <?php
                            // $get_order_sql = "SELECT MAX(CAST(question_order AS UNSIGNED)) AS max_question_order FROM questions WHERE exam_name = '{$_GET["exam"]}' LIMIT 1";
                            // $get_order_result = mysqli_query($conn, $get_order_sql);
                            // if($get_order_result) {
                            //     $order = mysqli_fetch_assoc($get_order_result)["max_question_order"];
                            //     $order = $order + 1;
                            //     echo '<input type="hidden" name="question_order" id="question_order" value="'.$order.'">';
                            // }
                        ?>
                        <div class="mb-3">
                            <label for="question" class="form-label">Question</label>
                            <textarea class="form-control" id="question" name="question" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="question-image" class="form-label">Choose Image</label>
                            <input class="form-control" type="file" id="question-image" name="question-image" accept="image/*">
                        </div>
                        <div class="mb-3 add-question-type-box">
                            <label for="question-type" class="form-label">Question Type</label>
                            <select class="form-select question-type" aria-label="Default select example" name="question-type" id="question-type" required>
                                <option value="" selected>Select Question Type</option>
                                <option value="title">Title</option>
                                <option value="single">Single Answer</option>
                                <option value="multiple">Multiple Answer</option>
                            </select>
                        </div>
                        <div class="mb-3 option-container">
                            <label for="opt_a" class="form-label">Option A</label>
                            <i class="fa-regular fa-circle toggle-correct"></i><input type="text" class="form-control d-inline-block add-questions-options" id="opt_a" name="options[]" required><input type="hidden" class="form-control invisible add-questions-options" value="incorrect" id="correct_opt_a" name="correct_options[]" required><i class="fa-solid fa-xmark remove-option"></i>
                        </div>
                        <div class="mb-3 option-container">
                            <label for="opt_b" class="form-label">Option B</label>
                            <i class="fa-regular fa-circle toggle-correct"></i><input type="text" class="form-control d-inline-block add-questions-options" id="opt_b" name="options[]" required><input type="hidden" class="form-control invisible add-questions-options" value="incorrect" id="correct_opt_b" name="correct_options[]" required><i class="fa-solid fa-xmark remove-option"></i>
                        </div>
                        <div class="mb-3 option-container">
                            <label for="opt_c" class="form-label">Option C</label>
                            <i class="fa-regular fa-circle toggle-correct"></i><input type="text" class="form-control d-inline-block add-questions-options" id="opt_c" name="options[]" required><input type="hidden" class="form-control invisible add-questions-options" value="incorrect" id="correct_opt_c" name="correct_options[]" required><i class="fa-solid fa-xmark remove-option"></i>
                        </div>
                        <div class="mb-3 option-container">
                            <label for="opt_d" class="form-label">Option D</label>
                            <i class="fa-regular fa-circle toggle-correct"></i><input type="text" class="form-control d-inline-block add-questions-options" id="opt_d" name="options[]" required><input type="hidden" class="form-control invisible add-questions-options" value="incorrect" id="correct_opt_d" name="correct_options[]" required><i class="fa-solid fa-xmark remove-option"></i>
                        </div>
                        <!-- <div class="mb-3">
                        <select class="form-select" aria-label="Default select example">
                            <option selected>Choose Correct Option</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                        </div> -->
                        <button type="submit" class="btn btn-primary d-none" id="submit-question" name="add_question">Submit</button>
                    </form>
                    <button class="btn btn-primary add-option add-question-add-option">Add Option</button>
                </div>
                <div class="modal-footer">
                    <!--<button class="btn btn-primary position-absolute add-option add-question-add-option">Add Option</button>-->
                    <button type="button" class="btn btn-primary" id="add-question">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
<h2 class="text-center">Exam Name: <?= $_GET["exam"];?></h2>
<div class="container d-flex justify-content-end">
    <label class="">Search in: 
        <select id="columnSelector">
            <option value="all">All Columns</option>
            <option value="1">Question ID</option>
            <option value="2">Topic</option>
            <option value="3">Main Group</option>
            <option value="4">Sub Group</option>
            <option value="5">Questions</option>
        </select>
    </label>
</div>
<div class="container min-height-100-vh">
    <table class="table table-bordered" id="question-table">
        <thead>
            <tr>
                <th scope="col">Sr. no.</th>
                <th scope="col">Remove</th>
                <th scope="col">Question ID</th>
                <th scope="col">Topic</th>
                <th scope="col">Main Group</th>
                <th scope="col">Sub Group</th>
                <th scope="col">Questions</th>
                <th scope="col">No. of Times Correctly Attempted</th>
                <th scope="col">No. of Times Attempted</th>
                <th scope="col">Percentage Correctly Attempted</th>
                <th scope="col">Difficulty Level</th>
                <!-- <th scope="col">Edit</th> -->
            </tr>
        </thead>
        <tbody>
            <?php
                $exam_id_sql = "SELECT id FROM exam_portal WHERE exam_name = '$exam_name'";
                $exam_id_sql_result = mysqli_query($conn, $exam_id_sql);
                $exam_id = mysqli_fetch_assoc($exam_id_sql_result)["id"];
                $sql = "SELECT q.id, q.question_id, q.questions, q.no_of_times_attempted, q.no_of_times_correctly_attempted, q.question_type, q.question_image FROM questions AS q JOIN question_exam_mapping AS qem ON qem.question_id = q.id WHERE qem.exam_id = '$exam_id' AND q.status = 1 ORDER BY q.id ASC";
                // echo $sql;
                $result = mysqli_query($conn, $sql);
                $sr_no = 1;
                $show_series = 0;
                $remove_sr_no = 1;
                if(mysqli_num_rows($result) > 0) {
                    mysqli_set_charset($conn, "utf8mb4");
                    // echo array_output_die(mysqli_fetch_assoc($result));
                    while($row = mysqli_fetch_assoc($result)) {
                        $question_id = $row["id"];
                        $question_unique_id = $row["question_id"];
                        $get_question = $row["questions"];
                        $topic_id_array = array();
                        $main_group_id_array = array();
                        $sub_group_id_array = array();
                        $topic_ids_sql = "SELECT * FROM question_topic_mapping WHERE question_id = $question_id";
                        $topic_ids_sql_result = mysqli_query($conn, $topic_ids_sql);
                        if(mysqli_num_rows($topic_ids_sql_result) > 0) {
                            while($topic_id_row = mysqli_fetch_assoc($topic_ids_sql_result)) {
                                $topic_id_array[] = $topic_id_row["topic_id"];
                            }
                        }
                        $main_group_ids_sql = "SELECT * FROM question_main_group_mapping WHERE question_id = $question_id";
                        $main_group_ids_sql_result = mysqli_query($conn, $main_group_ids_sql);
                        if(mysqli_num_rows($main_group_ids_sql_result) > 0) {
                            while($main_group_id_row = mysqli_fetch_assoc($main_group_ids_sql_result)) {
                                $main_group_id_array[] = $main_group_id_row["main_group_id"];
                            }
                        }
                        $sub_group_ids_sql = "SELECT * FROM question_sub_group_mapping WHERE question_id = $question_id";
                        $sub_group_ids_sql_result = mysqli_query($conn, $sub_group_ids_sql);
                        if(mysqli_num_rows($sub_group_ids_sql_result) > 0) {
                            while($sub_group_id_row = mysqli_fetch_assoc($sub_group_ids_sql_result)) {
                                $sub_group_id_array[] = $sub_group_id_row["sub_group_id"];
                            }
                        }
                        // $topic_id = $row["topic_id"];
                        // $main_group_id = $row["main_group_id"];
                        // $sub_group_id = $row["sub_group_id"];
                        $char_sr_no = 65;
                        ?>
                        <tr>
                            <th scope='row'><?php echo ++$show_series; ?></th>
                            <td>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#questionRemoveModal<?php echo $remove_sr_no; ?>">Remove</button>
                                    <?php $remove_sr_no++;?>
                                </td>
                                <td><?= $question_unique_id; ?></td>
                                <td>
                                <?php
                                    if(count($topic_id_array) > 0) {
                                        $sql = "SELECT topic FROM topic WHERE id IN (" . implode(",", $topic_id_array) . ") ORDER BY FIELD(id, " . implode(",", $topic_id_array) . ")";
                                        echo $sql;
                                        $topic_result = mysqli_query($conn, $sql);
                                        if(mysqli_num_rows($topic_result) > 0) {
                                            echo mysqli_fetch_assoc($topic_result)["topic"];
                                        }
                                    }
                                ?>
                            </td>
                            <td>
                                <?php
                                    // array_output_die($main_group_id_array);
                                    if(count($main_group_id_array) > 0) {
                                        $sql = "SELECT main_group FROM main_group WHERE id IN (" . implode(",", $main_group_id_array) . ") ORDER BY FIELD(id, " . implode(",", $main_group_id_array) . ")";
                                        $main_group_result = mysqli_query($conn, $sql);
                                        if(mysqli_num_rows($main_group_result) > 0) {
                                            echo mysqli_fetch_assoc($main_group_result)["main_group"];
                                        }
                                    }
                                ?>
                            </td>
                            <!-- <td <?php if($row["question_type"] !== "title") {?> class="d-none" <?php } ?>></td> -->
                            <td>
                            <?php
                                    // array_output_die($sub_group_id_array);
                                    if(count($sub_group_id_array) > 0) {
                                        $sql = "SELECT sub_group FROM sub_group WHERE id IN (" . implode(",", $sub_group_id_array) . ") ORDER BY FIELD(id, " . implode(",", $sub_group_id_array) . ")";
                                        $sub_group_result = mysqli_query($conn, $sql);
                                        if(mysqli_num_rows($sub_group_result) > 0) {
                                            echo mysqli_fetch_assoc($sub_group_result)["sub_group"];
                                        }
                                    }
                                ?>
                            </td>
                                <td <?php if($row["question_type"] == "title") {?> colspan="5" <?php }
                                ?>>
                                <div><?php
                                // echo "<pre>";
                                // print_r($get_question);
                                // print_r($row);
                                // echo "</pre>";
                                // echo join("<code>–</code>", explode("–", $get_question));
                                echo $get_question;
                                ?>
                                </div>
                                <img src="IMG/Questions/<?php echo $row["question_image"]; ?>" alt="<?php echo $row["question_image"]; ?>" class="question-image <?php if($row["question_image"] == "") { echo "d-none"; }?> mb-2"/>
                                <?php
                                    $sql = "SELECT * FROM options WHERE question_id = '" . $question_id . "'";
                                    $option_result = mysqli_query($conn, $sql);
                                    if(mysqli_num_rows($option_result) > 0) {
                                        while($row2 = mysqli_fetch_assoc($option_result)) { ?>
                                             <div class="d-flex">
                                                <input type="checkbox" name="options[]" id="options-<?php echo $sr_no; ?>" class="" <?php if($row2["is_correct"] == "1") {echo "checked";}?>>
                                                <label for="options-<?php echo $sr_no; ?>" class="d-inline-block w-100 ms-2" data-question-id="<?= $question_id; ?>" data-option="<?= $row2["answers"]; ?>" data-question-type="<?= $row["question_type"]?>"><?= chr($char_sr_no) . ". " . $row2["answers"];?></label>
                                            </div>
                                        <?php $sr_no++;$char_sr_no++;}
                                    }
                                ?>
                                </td>
                                    <?php
                                        // $num_of_correctly_attempted_score = 0;
                                        // $no_of_times_correctly_attempted_sql = "SELECT correctly_questions_attempted_id FROM result WHERE exam_id = '$exam_id' AND correctly_questions_attempted_id = '" . get_safe_value($conn, $question_id) . "'";
                                        // $no_of_times_correctly_attempted_result = mysqli_query($conn, $no_of_times_correctly_attempted_sql);
                                        // if(mysqli_num_rows($no_of_times_correctly_attempted_result) > 0) {
                                        //     while($row2 = mysqli_fetch_assoc($no_of_times_correctly_attempted_result)) {
                                        //         $num_of_correctly_attempted_score += 1;
                                        //         // echo $row2["correctly_questions_attempted"];
                                        //     }
                                        // }
                                        ?>
                                <td <?php if($row["question_type"] == "title") {?> class="d-none" <?php } ?>>
                                    <?php
                                        echo $row["no_of_times_correctly_attempted"];
                                        // echo $num_of_correctly_attempted_score;
                                    ?>
                                </td>
                                    <?php
                                        // $num_of_attempted_score = 0;
                                        // $no_of_times_attempted_sql = "SELECT correctly_questions_attempted_id FROM result WHERE exam_id = '$exam_id' AND questions_attempted_id = '" . get_safe_value($conn, $question_id) . "'";
                                        // $no_of_times_attempted_result = mysqli_query($conn, $no_of_times_attempted_sql);
                                        // // echo $row["questions"];
                                        // if(mysqli_num_rows($no_of_times_attempted_result) > 0) {
                                        //     while($row2 = mysqli_fetch_assoc($no_of_times_attempted_result)) {
                                        //         // die;
                                        //         $num_of_attempted_score += 1;
                                        //         // echo $num_of_attempted_score;
                                        //     }
                                        // }
                                        ?>
                                <td <?php if($row["question_type"] == "title") {?> class="d-none" <?php } ?>>
                                    <?php
                                        echo $row["no_of_times_attempted"];
                                        // echo $num_of_attempted_score;
                                    ?>
                                </td>
                                <td <?php if($row["question_type"] == "title") {?> class="d-none" <?php } ?>><?php echo $row["no_of_times_correctly_attempted"] == 0 ? "0%" : round(($row["no_of_times_correctly_attempted"]/$row["no_of_times_attempted"])*100, 2) . "%";?></td>
                                <?php
                                    if($row["no_of_times_attempted"] > 0) {
                                        if(round(($row["no_of_times_correctly_attempted"]/$row["no_of_times_attempted"])*100, 2) > 75) {
                                            $difficulty_level = "Easy";
                                        } elseif(round(($row["no_of_times_correctly_attempted"]/$row["no_of_times_attempted"])*100, 2) < 30) {
                                            $difficulty_level = "Difficult";
                                        } else {
                                            $difficulty_level = "Normal";
                                        }
                                    }
                                ?>
                                <td <?php if($row["question_type"] == "title") {?> class="d-none" <?php } ?>><?php echo $row["no_of_times_attempted"] == 0 ? "N/A" : $difficulty_level;?></td>
                                <!-- <td>
                                    <button type="button" class="btn btn-primary individual-edit-question" data-bs-toggle="modal"
                                        data-bs-target="#questionEditModal<?php echo $remove_sr_no; ?>">Edit</button>
                                </td> -->
                                <!-- <td>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#questionRemoveModal<?php echo $remove_sr_no; ?>">Remove</button>
                                    <?php $remove_sr_no++;?>
                                </td> -->
                            </tr>
                <?php
                        $sr_no++;
                    }
                } else { ?>
                    <tr><td colspan='11' class="text-center">No questions found</td></tr>
                <?php }
                ?>
        </tbody>
    </table>
</div>


<?php
    $modal_sql = "SELECT *, q.id FROM questions AS q JOIN question_exam_mapping AS qem ON q.id = qem.question_id JOIN exam_portal AS ep ON qem.exam_id = ep.id WHERE ep.id = '$exam_id'";
    // echo $modal_sql;
    $modal_result = mysqli_query($conn, $modal_sql);
    $sr_no = 1;
    if(mysqli_num_rows($modal_result) > 0) {
        while($modal_row = mysqli_fetch_assoc($modal_result)) {
            $char_sr_no = 65;
            $edit_question_id = $modal_row["id"];
            $question_type = $modal_row["question_type"];
            // $option_sql = "SELECT * FROM questions WHERE questions = '$modal_row[questions]'";
?>
<!-- Edit Modal -->
<div class="modal fade" id="questionEditModal<?php echo $sr_no; ?>" tabindex="-1" aria-labelledby="questionEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Question</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <!--<button type="button" class="btn btn-primary position-absolute add-option edit-question-add-option">Add Option</button>-->
            <form action="mcq_questions.php?exam=<?= $_GET["exam"]; ?>" method="POST" enctype="multipart/form-data">
                    <div class="mb-3 d-none">
                        <input type="text" class="form-control" id="edit_id" name="edit_id" value="<?php echo $modal_row["id"]; ?>" readonly>
                    </div>
                    <div class="mb-3 d-none">
                    <textarea class="form-control" id="old_question" name="old_question" rows="3" required><?php echo $modal_row["questions"]; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="question" class="form-label">Question</label>
                        <textarea class="form-control" id="edit_question" name="edit_question" rows="3" required><?php echo $modal_row["questions"]; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit-question-image" class="form-label">Choose Image</label>
                        <input class="form-control" type="file" id="edit-question-image" name="edit-question-image" accept="image/*">
                    </div>
                    <div class="mb-3 edit-question-type-box">
                        <label for="edit-question-type" class="form-label">Question Type</label>
                        <select class="form-select question-type" aria-label="Default select example" name="edit-question-type" id="edit-question-type" required>
                            <option value="" selected>Select Question Type</option>
                            <option value="title" <?php if($modal_row["question_type"] == 'title') {?>selected<?php } ?> <?php if($modal_row["question_type"] !== 'title') {?>disabled<?php } ?>>Title</option>
                            <option value="single" <?php if($modal_row["question_type"] == 'single') {?>selected<?php } ?> <?php if($modal_row["question_type"] == 'title') {?>disabled<?php } ?>>Single Answer</option>
                            <option value="multiple" <?php if($modal_row["question_type"] == 'multiple') {?>selected<?php } ?> <?php if($modal_row["question_type"] == 'title') {?>disabled<?php } ?>>Multiple Answer</option>
                        </select>
                    </div>
                    <?php
                        $edit_sr_no = 1;
                        // echo "<span style='color: red;'>" . $character . "</span>";
                        // die;
                        $sql = "SELECT * FROM options WHERE question_id = '" . $modal_row["id"] . "'";
                        // echo $sql;
                        $option_result = mysqli_query($conn, $sql);
                        if(mysqli_num_rows($option_result) > 0) {
                            while($row2 = mysqli_fetch_assoc($option_result)) {
                                $character = chr(64 + $edit_sr_no);
                                // if($question_type == "single" || $question_type == "multiple") {
                                ?>
                                    <!-- <div>
                                    <input type="checkbox" name="options[]" id="edit-options-<?php echo $edit_sr_no; ?>" class="d-none">
                                    <label for="edit-options-<?php echo $edit_sr_no; ?>" class="d-block"><?= $edit_sr_no . ". " . $row2["options"];?></label>
                                </div> -->
                                <div class="mb-3 option-container">
                                    <label for="edit_options_<?php echo chr(96 + $edit_sr_no); ?>" class="form-label">Option <?php echo $character;?></label><i class="fa-regular <?php if($row2["is_correct"] == "1"){echo 'fa-circle-check';} else {echo 'fa-circle';}?> toggle-correct <?php if($row2["is_correct"] == "1"){echo 'text-success';}?>" aria-hidden="true"></i>
                                    <input type="text" class="form-control d-inline-block add-questions-options" id="edit_options_<?php echo chr(96 + $edit_sr_no); ?>" name="options[]" value="<?php echo $row2["answers"]; ?>" required>
                                    <input type="hidden" class="form-control invisible add-questions-options" id="correct_opt_<?php echo chr(96 + $edit_sr_no); ?>" name="correct_options[]" value="<?php echo $row2["is_correct"] == "1" ? "correct" : "incorrect"; ?>">
                                    <i class="fa-solid fa-xmark remove-option" aria-hidden="true"></i>
                                    <!-- <input type="checkbox" class="form-control" id="correct_edit_opttions_<?php echo $edit_sr_no; ?>" name="correct_edit_opttions_[]" value="<?php echo $row2["options"]; ?>"> -->
                                </div>
                            <?php $edit_sr_no++;}
                        }
                    ?>
                    <!-- <div class="mb-3">
                        <label for="opt_a" class="form-label">Option A</label>
                        <input type="text" class="form-control" id="edit_opt_a" name="edit_opt_a" value="<?php //echo $row["opt_a"]; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="opt_b" class="form-label">Option B</label>
                        <input type="text" class="form-control" id="edit_opt_b" name="edit_opt_b" value="<?php //echo $row["opt_b"]; ?>">
                    </div> -->
                    <button type="submit" class="btn btn-primary d-none edit_question_submit"
                        id="submit-question<?php echo $sr_no; ?>" name="edit_question_submit">Submit</button>
                </form>
                <button type="button" class="btn btn-primary add-option edit-question-add-option">Add Option</button>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary edit-question" id="edit-question<?php echo $sr_no; ?>">Save</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Delete Modal -->
<!-- <div class="modal fade" id="questionDeleteModal<?php echo $sr_no; ?>" tabindex="-1"
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
                <form action="mcq_questions.php?exam=<?= $_GET["exam"]; ?>" method="POST">
                    <input type="hidden" name="question_id" value="<?= $modal_row["id"]; ?>">
                    <input type="hidden" name="question_type" value="<?= $modal_row["question_type"]; ?>">
                    <button type="submit" class="btn btn-primary d-none delete_question_submit"
                        id="delete_question<?php echo $sr_no; ?>" name="delete_question" value="<?= $modal_row["id"];?>">Submit</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger delete-question">Delete</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div> -->
<!-- Remove Modal -->
<div class="modal fade" id="questionRemoveModal<?php echo $sr_no; ?>" tabindex="-1"
    aria-labelledby="questionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Remove Question</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure to remove this question from this exam?</p>
                <form action="mcq_questions.php?exam=<?= $_GET["exam"]; ?>" method="POST">
                    <input type="hidden" name="question_id" value="<?= $modal_row["id"]; ?>">
                    <input type="hidden" name="question_type" value="<?= $modal_row["question_type"]; ?>">
                    <button type="submit" class="btn btn-primary d-none remove_question_submit"
                        id="remove_question<?php echo $sr_no; ?>" name="remove_question" value="<?= $modal_row["id"];?>">Submit</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger remove-question">Remove</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php
$sr_no++;
        }
    }
    ?>
<?php
    include "Includes/footer.php";
?>