<?php
    define("PAGE", "Question Bank");
    include "Connection/conn.inc.php";
    include "Includes/header.php";
    include "Includes/functions.inc.php";
    header('Content-type: text/html; charset=ASCII');

    if (!isset($_SESSION["is_login"])) {
        header("location: Authentication/login.php");
    }
    // if (!isset($_GET["exam"])) {
    //     header("location: index.php");
    // }

    // $exam_name = get_safe_value($conn, $_GET['exam']);
    // $exam_name_id_sql = "SELECT id FROM exam_portal WHERE exam_name = '$exam_name'";
    // $exam_name_id_result = mysqli_query($conn, $exam_name_id_sql);
    // $exam_id = mysqli_fetch_assoc($exam_name_id_result)["id"];
    $alert_message = ''; // Initialize an empty alert message
    // array_output(json_decode('["Meter","Gram","Second","Liter","Kelvin","Ampere"]', true));
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // array_output_die($_POST);
        if (isset($_POST["add_question"])) {
            $check_sql_question = "SELECT * FROM questions WHERE questions = '" . get_safe_value($conn, $_POST['question']) . "'";
            $check_result_question = mysqli_query($conn, $check_sql_question);

            if (mysqli_num_rows($check_result_question) > 0) {
                $available_id = mysqli_fetch_assoc($check_result_question)["id"];
                $check_status_sql = "SELECT * FROM questions WHERE id = $available_id AND status = 1";
                $check_status_request = mysqli_query($conn, $check_status_sql);
                if(mysqli_num_rows($check_status_request) > 0) {
                    // $adding_options = $_POST["options"];
                    // $already_available_options_arr = array();
                    // $fetch_available_options_sql = "SELECT * FROM options WHERE question_id = $available_id";
                    // $fetch_available_options_result = mysqli_query($conn, $fetch_available_options_sql);
                    // if (mysqli_num_rows($fetch_available_options_result) > 0) {
                    //     while($fetch_available_options_row = mysqli_fetch_assoc($fetch_available_options_result)) {
                    //         array_push($already_available_options_arr, $fetch_available_options_row["answers"]);
                    //     }
                    // }
                    // sort($adding_options);
                    // sort($already_available_options_arr);
                    // if(implode("➡", $adding_options) == implode("➡", $already_available_options_arr)) {
                    //     $alert_message = "<div class=\"alert alert-warning alert-dismissible fade show\" role=\"alert\">
                    //     <strong>Question already exists!</strong> Please add a different Question.
                    //     <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>
                    //     </div>";
                    // } else {
                    //         // We have to do something here
                    //     }
                    $alert_message = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <span>Question is already Exist!</span>
                            <!-- <form class="d-inline-block" action="question_bank.php" method="post">
                                <input type="hidden" value="' . $available_id . '" name="previously-deleted-question-id">
                                <button type="submit" class="btn mx-3" name="activate-previously-deleted-question-add">Do you want to edit that question?</button>
                            </form> -->
                            <!-- <button type="submit" class="btn mx-3" name="activate-previously-deleted-question" data-bs-toggle="modal" data-bs-target="#detetedQuestionEditModal">Do you want to edit that question?</button> -->
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
                } else {
                    $check_status_sql = "SELECT * FROM questions WHERE id = $available_id AND status = 0";
                    $check_status_result = mysqli_query($conn, $check_status_sql);
                    if(mysqli_num_rows($check_status_result) > 0) {
                        $get_deleted_question_back_sql = "UPDATE questions SET status = 1 WHERE id = $available_id";
                        $get_deleted_question_back_result = mysqli_query($conn, $get_deleted_question_back_sql);
                        $remove_old_options_of_question_sql = "UPDATE options SET status = 0 WHERE question_id = $available_id";
                        $remove_old_options_of_question_result = mysqli_query($conn, $remove_old_options_of_question_sql);
                        if($get_deleted_question_back_result) {
                            $question = get_safe_value($conn, $_POST["question"]);
                            $question_type = get_safe_value($conn, $_POST["question-type"]);
                            $questionImage = '';
                            if (!empty($_FILES["question-image"]["tmp_name"])) {
                                $uploadDir = "IMG/Questions/";
                                $questionImage = basename($_FILES["question-image"]["name"]);
                                move_uploaded_file($_FILES["question-image"]["tmp_name"], $uploadDir . $questionImage);
                            }
                            if($_POST["question-type"] == "title"){
                                // $sql = "INSERT INTO questions (questions, question_image, question_type, exam_id) 
                                //             VALUES ('$question', '', 'title', '$exam_id')";
                                $sql = "UPDATE questions SET status = 1 WHERE id = $available_id";
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
                                // $sql = "INSERT INTO questions (questions, question_image, question_id, question_type, exam_id, no_of_times_correctly_attempted, no_of_times_attempted, status) 
                                            // VALUES ('$question', '$questionImage', '', '$question_type', '$exam_id', 0, 0, 1)";
                
                                $sql = "UPDATE questions SET questions = '$question', question_image = '$questionImage', question_id = '$available_id', question_type = '$question_type', status = 1 WHERE id = '$available_id'";
                
                                $result = mysqli_query($conn, $sql);
                
                                if ($result) {
                                    $select_question_sql = "SELECT id FROM questions WHERE questions = '$question'";
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
                        $alert_message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                                    Question added successfully!
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                </div>';
                    } else {
                        // Sanitize inputs
                        $question = get_safe_value($conn, $_POST["question"]);
                        // $exam_name = get_safe_value($conn, $_GET["exam"]);
                        $question_type = get_safe_value($conn, $_POST["question-type"]);
                        $questionImage = '';
                        if (!empty($_FILES["question-image"]["tmp_name"])) {
                            $uploadDir = "IMG/Questions/";
                            $questionImage = basename($_FILES["question-image"]["name"]);
                            move_uploaded_file($_FILES["question-image"]["tmp_name"], $uploadDir . $questionImage);
                        }
                        if($_POST["question-type"] == "title"){
                            // $sql = "INSERT INTO questions (questions, question_image, question_type, exam_id) 
                            //             VALUES ('$question', '', 'title', '$exam_id')";
                            $sql = "UPDATE questions SET status = 1 WHERE id = $available_id";
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
                            // $sql = "INSERT INTO questions (questions, question_image, question_id, question_type, exam_id, no_of_times_correctly_attempted, no_of_times_attempted, status) 
                                        // VALUES ('$question', '$questionImage', '', '$question_type', '$exam_id', 0, 0, 1)";
                            $sql = "SELECT unique_question_id FROM unique_question_id LIMIT 1";
                            $result = mysqli_query($conn, $sql);
                            if(mysqli_num_rows($result)>0) {
                                $question_unique_id = mysqli_fetch_assoc($result)["unique_question_id"] + 1;
                            }
    
                            $sql = "UPDATE questions SET questions = '$question', question_image = '$questionImage', question_id = '$question_unique_id', question_type = '$question_type', status = 1 WHERE id = '$available_id'";
            
                            $result = mysqli_query($conn, $sql);
                            mysqli_query($conn, "UPDATE unique_question_id SET unique_question_id = '$question_unique_id'");
            
                            if ($result) {
                                $select_question_sql = "SELECT id FROM questions WHERE questions = '$question'";
                                $select_question_result = mysqli_query($conn, $select_question_sql);
                                $question_id = mysqli_fetch_assoc($select_question_result)["id"];
                                
                                $check_options_sql = "SELECT * FROM options WHERE question_id = '$question_id' AND status = 1";
                                $check_options_result = mysqli_query($conn, $check_options_sql);
                                if(mysqli_num_rows($check_options_result) > 0) {
                                    // $delete_existing_sql = "UPDATE options SET status = 0 WHERE question_id = '$question_id'";
                                    // mysqli_query($conn, $delete_existing_sql);
                                }
    
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

                }
            } else {
                // Sanitize inputs
                $question = get_safe_value($conn, $_POST["question"]);
                $question_type = get_safe_value($conn, $_POST["question-type"]);
                $topic = isset($_POST["add_question_topic"])?get_safe_value($conn, $_POST["add_question_topic"]):[];
                $main_group = isset($_POST["add_question_main_group"])?get_safe_value($conn, $_POST["add_question_main_group"]):[];
                $sub_group = isset($_POST["add_question_sub_group"])?get_safe_value($conn, $_POST["add_question_sub_group"]):[];   
                // $sql = "SELECT * FROM questions ORDER BY question_id DESC LIMIT 1";
                // $result = mysqli_query($conn, $sql);
                // if(mysqli_num_rows($result)>0) {
                //     $question_unique_id = mysqli_fetch_assoc($result)["question_id"] + 1;
                // } else {
                $sql = "SELECT unique_question_id FROM unique_question_id LIMIT 1";
                $result = mysqli_query($conn, $sql);
                if(mysqli_num_rows($result)>0) {
                    $question_unique_id = mysqli_fetch_assoc($result)["unique_question_id"] + 1;
                }
                // }
                mysqli_query($conn, "UPDATE unique_question_id SET unique_question_id = $question_unique_id");
                // Handle image upload
                $questionImage = '';
                if (!empty($_FILES["question-image"]["tmp_name"])) {
                    $uploadDir = "IMG/Questions/";
                    $questionImage = basename($_FILES["question-image"]["name"]);
                    move_uploaded_file($_FILES["question-image"]["tmp_name"], $uploadDir . $questionImage);
                }
                if($_POST["question-type"] == "title"){
                    $question_order_sql = "SELECT question_order FROM questions WHERE question_type = 'title' AND status = 1 ORDER BY question_order DESC LIMIT 1";
                    $question_order_result = mysqli_query($conn, $question_order_sql);
                    if(mysqli_num_rows($question_order_result)>0) {
                        $question_order = mysqli_fetch_assoc($question_order_result)["question_order"] + 1;
                    } else {
                        $question_order = 1;
                    }
                    $sql = "INSERT INTO questions (questions, question_image, question_type, question_id, no_of_times_correctly_attempted, no_of_times_attempted, question_order, status) 
                                VALUES ('$question', '', 'title', $question_unique_id, 0, 0, $question_order, 1)";
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

                    $question_order_sql = "SELECT question_order FROM questions WHERE question_type = 'title' AND status = 1 ORDER BY question_order DESC LIMIT 1";
                    $question_order_result = mysqli_query($conn, $question_order_sql);
                    if(mysqli_num_rows($question_order_result)>0) {
                        $question_order = mysqli_fetch_assoc($question_order_result)["question_order"] + 1;
                    } else {
                        $question_order = 1;
                    }
    
                    // Insert into Database
                    $sql = "INSERT INTO questions (questions, question_image, question_type, question_id, no_of_times_correctly_attempted, no_of_times_attempted, question_order, status) 
                                VALUES ('$question', '$questionImage', '$question_type', $question_unique_id, 0, 0, $question_order, 1)";
    
                    $result = mysqli_query($conn, $sql);
    
                    if ($result) {
                        $select_question_sql = "SELECT id FROM questions WHERE questions = '$question'";
                        $select_question_result = mysqli_query($conn, $select_question_sql);
                        $question_id = mysqli_fetch_assoc($select_question_result)["id"];
                        
                        for ($i = 0; $i < count($options); $i++) {
                            $is_correct = ($correct_options[$i] == "correct") ? 1 : 0;
                            $option_sql = "INSERT INTO options (question_id, answers, is_correct, status) VALUES 
                            ('$question_id', '" . get_safe_value($conn, $options[$i]) ."', '" . $is_correct . "', 1)";
                            // echo $option_sql;
                            // die;
                            $option_result = mysqli_query($conn, $option_sql);
    
                            if (!$option_result) {
                                die("Error inserting option: " . mysqli_error($conn));
                            }
                        }

                        for($i = 0; $i < count($topic); $i++) {
                            $topic_sql = "INSERT INTO question_topic_mapping (question_id, topic_id) VALUES ('$question_id', '$topic[$i]')";
                            $topic_result = mysqli_query($conn, $topic_sql);
    
                            if (!$topic_result) {
                                die("Error inserting topic: " . mysqli_error($conn));
                            }
                        }

                        for($i = 0; $i < count($main_group); $i++) {
                            $main_group_sql = "INSERT INTO question_main_group_mapping (question_id, main_group_id) VALUES ('$question_id', '$main_group[$i]')";
                            $main_group_result = mysqli_query($conn, $main_group_sql);
    
                            if (!$main_group_result) {
                                die("Error inserting main group: " . mysqli_error($conn));
                            }
                        }

                        for($i = 0; $i < count($sub_group); $i++) {
                            $sub_group_sql = "INSERT INTO question_sub_group_mapping (question_id, sub_group_id) VALUES ('$question_id', '$sub_group[$i]')";
                            $sub_group_result = mysqli_query($conn, $sub_group_sql);
    
                            if (!$sub_group_result) {
                                die("Error inserting sub group: " . mysqli_error($conn));
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
            // Basic inputs aur debugging ke liye question id print kar rahe hain.
            $edit_question_id = get_safe_value($conn, $_POST["edit_question_id"]);
            // echo "<div class='text-danger bg-warning p-5 fs-1'>Edit Question ID: $edit_question_id on line 250</div>";
        
            // Input values sanitize karke store karein.
            $id = get_safe_value($conn, $_POST["edit_id"]);
            $old_question = get_safe_value($conn, $_POST["old_question"]);
            $question = get_safe_value($conn, $_POST["edit_question"]);
            $exam_ids_arr = array();
            $exam_ids_sql = "SELECT exam_id FROM question_exam_mapping WHERE question_id = $id AND status = 1";
            $exam_ids_result = mysqli_query($conn, $exam_ids_sql);
            if(mysqli_num_rows($exam_ids_result) > 0) {
                while($exam_ids_row = mysqli_fetch_assoc($exam_ids_result)) {
                    array_push($exam_ids_arr, $exam_ids_row["exam_id"]);
                }
            }
            $topic_ids_arr = array();
            $topic_ids_sql = "SELECT topic_id FROM question_topic_mapping WHERE question_id = $id";
            $topic_ids_result = mysqli_query($conn, $topic_ids_sql);
            if(mysqli_num_rows($topic_ids_result) > 0) {
                while($topic_ids_row = mysqli_fetch_assoc($topic_ids_result)) {
                    array_push($topic_ids_arr, $topic_ids_row["topic_id"]);
                }
            }
            $main_group_ids_arr = array();
            $main_group_ids_sql = "SELECT main_group_id FROM question_main_group_mapping WHERE question_id = $id";
            $main_group_ids_result = mysqli_query($conn, $main_group_ids_sql);
            if(mysqli_num_rows($main_group_ids_result) > 0) {
                while($main_group_ids_row = mysqli_fetch_assoc($main_group_ids_result)) {
                    array_push($main_group_ids_arr, $main_group_ids_row["main_group_id"]);
                }
            }
            $sub_group_ids_arr = array();
            $sub_group_ids_sql = "SELECT sub_group_id FROM question_sub_group_mapping WHERE question_id = $id";
            $sub_group_ids_result = mysqli_query($conn, $sub_group_ids_sql);
            if(mysqli_num_rows($sub_group_ids_result) > 0) {
                while($sub_group_ids_row = mysqli_fetch_assoc($sub_group_ids_result)) {
                    array_push($sub_group_ids_arr, $sub_group_ids_row["sub_group_id"]);
                }
            }
            // $remove_old_question_sql = "UPDATE questions SET status = 0 WHERE questions = '$old_question'";
            // $remove_old_question_result = mysqli_query($conn, $remove_old_question_sql);
            // // Handle image upload, agar image upload ho rahi hai.
            $questionImage = '';
            // if (!empty($_FILES["edit-question-image"]["name"])) {
            //     $uploadDir = "IMG/Questions/";
            //     $fileExtension = pathinfo($_FILES["edit-question-image"]["name"], PATHINFO_EXTENSION);
            //     $questionImage = uniqid() . '.' . $fileExtension;
            //     $uploadPath = $uploadDir . $questionImage;
        
            //     if (!move_uploaded_file($_FILES["edit-question-image"]["tmp_name"], $uploadPath)) {
            //         $alert_message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            //                             Failed to upload image!
            //                             <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            //                             </div>';
            //     }
            // }
            
            // Duplicate check: agar koi aur question same text ke saath exist karta hai, toh duplicate hai.
            $is_already_available_sql = "SELECT * FROM questions WHERE questions = '$question' AND id != '$id'";
            $is_already_available_result = mysqli_query($conn, $is_already_available_sql);
            
            if (mysqli_num_rows($is_already_available_result) > 0) {
                $all_insertions_successful = true;
                $question_exists = false;
                while($row = mysqli_fetch_assoc($is_already_available_result)) {
                    if($row["status"] == 1) {
                        $question_exists = true;
                    }
                }
                if($question_exists) {
                // Duplicate mil gaya, toh sirf alert message show karein.
                $alert_message = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    <span>Question already exists!</span>
                                    <!-- <button type="submit" class="btn mx-3" name="activate-previously-deleted-question" data-bs-toggle="modal" data-bs-target="#detetedQuestionEditModal">Do you want to edit that question?</button> -->
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>';
                } else {
                    $remove_old_question_sql = "UPDATE questions SET status = 0 WHERE questions = '$old_question'";
                    $remove_old_question_result = mysqli_query($conn, $remove_old_question_sql);
                    // Handle image upload, agar image upload ho rahi hai.
                    // $questionImage = '';
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
                    
                    // // Duplicate check: agar koi aur question same text ke saath exist karta hai, toh duplicate hai.
                    // $is_already_available_sql = "SELECT * FROM questions WHERE questions = '$question' AND id != '$id'";
                    // $is_already_available_result = mysqli_query($conn, $is_already_available_sql);
                    // echo "<script>confirm('Question already exists!');</script>";
                    $restore_question_sql = "UPDATE questions SET status = 1 WHERE questions = '$question'";
                    $restore_question_result = mysqli_query($conn, $restore_question_sql);
                    $find_inserted_question_id_sql = "SELECT id FROM questions WHERE questions = '$question'";
                    $find_inserted_question_id_result = mysqli_query($conn, $find_inserted_question_id_sql);
                    $inserted_question_id = mysqli_fetch_assoc($find_inserted_question_id_result)["id"];
                    if($restore_question_result) {
                        foreach ($exam_ids_arr as $key => $value) {
                            $map_question_to_exam_sql = "INSERT INTO question_exam_mapping (questions, question_id, exam_id, status) 
                                                            VALUES ('$question', '$inserted_question_id', $value, 1)";
                            $map_question_to_exam_result = mysqli_query($conn, $map_question_to_exam_sql);
                        }
                        $remove_old_question__from_exam = "DELETE FROM question_exam_mapping WHERE question_id = $id";
                        $remove_old_question__from_exam_result = mysqli_query($conn, $remove_old_question__from_exam);
                        foreach ($topic_ids_arr as $key => $value) {
                            $map_question_to_topic_sql = "INSERT INTO question_topic_mapping (question_id, topic_id, status) 
                                                            VALUES ('$inserted_question_id', $value, 1)";
                            $map_question_to_topic_result = mysqli_query($conn, $map_question_to_topic_sql);
                        }
                        $remove_old_question__from_topic = "DELETE FROM question_topic_mapping WHERE question_id = $id";
                        $remove_old_question__from_topic_result = mysqli_query($conn, $remove_old_question__from_topic);
                        foreach ($main_group_ids_arr as $key => $value) {
                            $map_question_to_main_group_sql = "INSERT INTO question_main_group_mapping (question_id, main_group_id, status) 
                                                                VALUES ('$inserted_question_id', $value, 1)";
                            $map_question_to_main_group_result = mysqli_query($conn, $map_question_to_main_group_sql);
                        }
                        $remove_old_question__from_main_group = "DELETE FROM question_main_group_mapping WHERE question_id = $id";
                        $remove_old_question__from_main_group_result = mysqli_query($conn, $remove_old_question__from_main_group);
                        foreach ($sub_group_ids_arr as $key => $value) {
                            $map_question_to_sub_group_sql = "INSERT INTO question_sub_group_mapping (question_id, sub_group_id, status) 
                                                                VALUES ('$inserted_question_id', $value, 1)";
                            $map_question_to_sub_group_result = mysqli_query($conn, $map_question_to_sub_group_sql);
                        }
                        $remove_old_question__from_sub_group = "DELETE FROM question_sub_group_mapping WHERE question_id = $id";
                        $remove_old_question__from_sub_group_result = mysqli_query($conn, $remove_old_question__from_sub_group);
                    }
                    // Agar question type "title" nahi hai, toh options bhi insert karein.
                    if ($_POST["edit-question-type"] !== "title") {
                        $options = $_POST['options'];
                        $correct_options = $_POST['correct_options'];
            
                        // Newly inserted question ka auto-generated ID fetch karein.
                        $new_question_id_sql = "SELECT * FROM questions WHERE questions = '$question' AND status = 1";
                        $new_question_id_result = mysqli_query($conn, $new_question_id_sql);
                        $inserted_question_id = mysqli_fetch_assoc($new_question_id_result)["id"];
                        mysqli_query($conn, "UPDATE options SET status = 0 WHERE question_id = '$inserted_question_id'");
            
                        foreach ($options as $i => $option) {
                            $correct = get_safe_value($conn, $correct_options[$i]);
                            $is_correct = ($correct == "correct") ? 1 : 0;
                            $sql = "INSERT INTO options (question_id, answers, is_correct, status) 
                                    VALUES ('$inserted_question_id', '$option', '$is_correct', '1')";
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
                }
            } else {
                // Duplicate nahin hai, toh naya insertion process shuru karein.
        
                // Purane question ke options ka status 0 set karein.
                $delete_existing_sql = "UPDATE options SET status = 0 WHERE question_id = '$id'";
                mysqli_query($conn, $delete_existing_sql);
        
                $all_insertions_successful = true;
                $question_type = get_safe_value($conn, $_POST["edit-question-type"]);
                $edit_question_id = get_safe_value($conn, $_POST["edit_question_id"]);
        
                // Unique question id generate karne ke liye table se current value fetch karein.
                $new_question_id = (int)mysqli_fetch_assoc(mysqli_query($conn, "SELECT unique_question_id FROM unique_question_id LIMIT 1"))["unique_question_id"] + 1;
                // Update unique_question_id table for next usage.
                mysqli_query($conn, "UPDATE unique_question_id SET unique_question_id = '$new_question_id'");
        
                // Purane question ko deactivate karein.
                $remove_previous_question_sql = "UPDATE questions SET status = 0 WHERE id = '$id'";
                mysqli_query($conn, $remove_previous_question_sql);
                
                $old_question_order_sql = "SELECT question_order FROM questions WHERE id = '$id'";
                $old_question_order_result = mysqli_query($conn, $old_question_order_sql);
                $old_question_order = mysqli_fetch_assoc($old_question_order_result)["question_order"];
                // Naya question insert karein.
                $insert_new_question_sql = "INSERT INTO questions (questions, question_image, question_id, question_type, no_of_times_correctly_attempted, no_of_times_attempted, question_order, status) 
                                                VALUES ('$question', '$questionImage', '$new_question_id', '$question_type', 0, 0, '$old_question_order', '1')";
                $insert_new_question_result = mysqli_query($conn, $insert_new_question_sql);
                $find_inserted_question_id_sql = "SELECT id FROM questions WHERE questions = '$question'";
                $find_inserted_question_id_result = mysqli_query($conn, $find_inserted_question_id_sql);
                $inserted_question_id = mysqli_fetch_assoc($find_inserted_question_id_result)["id"];
                if($insert_new_question_result) {
                    foreach ($exam_ids_arr as $key => $value) {
                        $map_question_to_exam_sql = "INSERT INTO question_exam_mapping (questions, question_id, exam_id, status) 
                                                        VALUES ('$question', '$inserted_question_id', $value, 1)";
                        $map_question_to_exam_result = mysqli_query($conn, $map_question_to_exam_sql);
                    }
                    $remove_old_question__from_exam = "DELETE FROM question_exam_mapping WHERE question_id = $id";
                    $remove_old_question__from_exam_result = mysqli_query($conn, $remove_old_question__from_exam);
                    foreach ($topic_ids_arr as $key => $value) {
                        $map_question_to_topic_sql = "INSERT INTO question_topic_mapping (question_id, topic_id, status) 
                                                        VALUES ('$inserted_question_id', $value, 1)";
                        $map_question_to_topic_result = mysqli_query($conn, $map_question_to_topic_sql);
                    }
                    $remove_old_question__from_topic = "DELETE FROM question_topic_mapping WHERE question_id = $id";
                    $remove_old_question__from_topic_result = mysqli_query($conn, $remove_old_question__from_topic);
                    foreach ($main_group_ids_arr as $key => $value) {
                        $map_question_to_main_group_sql = "INSERT INTO question_main_group_mapping (question_id, main_group_id, status) 
                                                            VALUES ('$inserted_question_id', $value, 1)";
                        $map_question_to_main_group_result = mysqli_query($conn, $map_question_to_main_group_sql);
                    }
                    $remove_old_question__from_main_group = "DELETE FROM question_main_group_mapping WHERE question_id = $id";
                    $remove_old_question__from_main_group_result = mysqli_query($conn, $remove_old_question__from_main_group);
                    foreach ($sub_group_ids_arr as $key => $value) {
                        $map_question_to_sub_group_sql = "INSERT INTO question_sub_group_mapping (question_id, sub_group_id, status) 
                                                            VALUES ('$inserted_question_id', $value, 1)";
                        $map_question_to_sub_group_result = mysqli_query($conn, $map_question_to_sub_group_sql);
                    }
                    $remove_old_question__from_sub_group = "DELETE FROM question_sub_group_mapping WHERE question_id = $id";
                    $remove_old_question__from_sub_group_result = mysqli_query($conn, $remove_old_question__from_sub_group);
                }
        
                // Agar question type "title" nahi hai, toh options bhi insert karein.
                if ($_POST["edit-question-type"] !== "title") {
                    $options = $_POST['options'];
                    $correct_options = $_POST['correct_options'];
        
                    // Newly inserted question ka auto-generated ID fetch karein.
                    $new_question_id_sql = "SELECT * FROM questions WHERE questions = '$question' AND status = 1";
                    $new_question_id_result = mysqli_query($conn, $new_question_id_sql);
                    $inserted_question_id = mysqli_fetch_assoc($new_question_id_result)["id"];
        
                    foreach ($options as $i => $option) {
                        $correct = get_safe_value($conn, $correct_options[$i]);
                        $is_correct = ($correct == "correct") ? 1 : 0;
                        $sql = "INSERT INTO options (question_id, answers, is_correct, status) 
                                VALUES ('$inserted_question_id', '$option', '$is_correct', '1')";
                        $result = mysqli_query($conn, $sql);
        
                        if (!$result) {
                            $all_insertions_successful = false;
                            break;
                        }
                    }
                }
        
                // Final alert message set karein.
                $alert_message = $all_insertions_successful
                    ? '<div class="alert alert-success alert-dismissible fade show" role="alert">Question updated successfully!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                    : '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error updating question!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                }
        } elseif (isset($_POST["delete_question"])) {
            $id = get_safe_value($conn, $_POST["question_id"]);
            // if($_POST["question_type"] == "title"){
            //     $delete_sql = "DELETE FROM questions WHERE id = $id;";
            // } else {
            //     $delete_sql = "DELETE q, o FROM questions AS q JOIN options AS o ON q.id = o.question_id WHERE q.id = $id;";
            // }
            $delete_sql = "UPDATE questions SET status = 0 WHERE id = $id;";
            $delete_result = mysqli_query($conn, $delete_sql);

            if($delete_result) {
                $remove_question_from_exam = "DELETE FROM question_exam_mapping WHERE question_id = $id";
                $remove_question_from_exam_result = mysqli_query($conn, $remove_question_from_exam);
                $remove_question_from_topic = "DELETE FROM question_topic_mapping WHERE question_id = $id";
                $remove_question_from_topic_result = mysqli_query($conn, $remove_question_from_topic);
                $remove_question_from_main_group = "DELETE FROM question_main_group_mapping WHERE question_id = $id";
                $remove_question_from_main_group_result = mysqli_query($conn, $remove_question_from_main_group);
                $remove_question_from_sub_group = "DELETE FROM question_sub_group_mapping WHERE question_id = $id";
                $remove_question_from_sub_group_result = mysqli_query($conn, $remove_question_from_sub_group);
            }

            $alert_message = $delete_result
                ? '<div class="alert alert-success alert-dismissible fade show" role="alert">Question deleted successfully!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                : '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error in deleting question!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        } elseif(isset($_POST["add_topic"])) {
            $topic = get_safe_value($conn, $_POST["topic"]);
            $select_topic = get_safe_value($conn, $_POST["select_topic"]);
            $sql = "SELECT * FROM topic WHERE topic = '$topic'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) == 0) {
                $sql = (empty($select_topic))?"INSERT INTO topic (topic) VALUES ('$topic')":"UPDATE topic SET topic = '$topic' WHERE id = '$select_topic'";
                $result = mysqli_query($conn, $sql);
                $alert_message = $result ? '<div class="alert alert-success alert-dismissible fade show" role="alert">Topic ' . ((empty($select_topic))?"added":"updated") . ' successfully!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>' : '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error adding topic!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            } else {
                $alert_message = '<div class="alert alert-warning alert-dismissible fade show" role="alert">Topic already exists!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            }
        } elseif(isset($_POST["add_main_group"])) {
            $main_group = get_safe_value($conn, $_POST["main_group"]);
            $select_main_group = get_safe_value($conn, $_POST["select_main_group"]);
            $sql = "SELECT * FROM main_group WHERE main_group = '$main_group'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) == 0) {
                $sql = (empty($select_main_group))?"INSERT INTO main_group (main_group) VALUES ('$main_group')":"UPDATE main_group SET main_group = '$main_group' WHERE id = '$select_main_group'";
                $result = mysqli_query($conn, $sql);
                $alert_message = $result ? '<div class="alert alert-success alert-dismissible fade show" role="alert">Main Group ' . ((empty($select_main_group))?"added":"updated") . ' successfully!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>' : '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error adding main group!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            } else {
                $alert_message = '<div class="alert alert-warning alert-dismissible fade show" role="alert">Main Group is already exists!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            }
        } elseif(isset($_POST["add_sub_group"])) {
            $sub_group = get_safe_value($conn, $_POST["sub_group"]);
            $select_sub_group = get_safe_value($conn, $_POST["select_sub_group"]);
            $sql = "SELECT * FROM sub_group WHERE sub_group = '$sub_group'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) == 0) {
                $sql = (empty($select_sub_group))?"INSERT INTO sub_group (sub_group) VALUES ('$sub_group')":"UPDATE sub_group SET sub_group = '$sub_group' WHERE id = '$select_sub_group'";
                $result = mysqli_query($conn, $sql);
                $alert_message = $result ? '<div class="alert alert-success alert-dismissible fade show" role="alert">Sub Group ' . ((empty($select_sub_group))?"added":"updated") . ' successfully!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>' : '<div class="alert alert-danger alert-dismissible fade show" role="alert">Error adding sub group!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            } else {
                $alert_message = '<div class="alert alert-warning alert-dismissible fade show" role="alert">Sub Group is already exists!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            }
        } elseif(isset($_POST["submit_create_quiz"])) {
            // array_output_die($_POST);
            $question_selected = false;
            foreach($_POST as $key => $value) {
                if(str_contains($key, 'unique-question-id')) {
                    $question_selected = true;
                    break;
                }
            }
            if($question_selected == false) {
                $alert_message = '<div class="alert alert-warning alert-dismissible fade show" role="alert">Please select the questions to add in quizes.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            } else {
                // array_output($_POST);
                $questions_unique_ids = array();
                // for($i = 1; $i < count($_POST) - 1; $i++) {
                //     array_push($questions_unique_ids, array_values($_POST)[$i]);
                // }
                foreach($_POST as $key => $value) {
                    // echo $key . ": " . $value . "<br />";
                    if(str_contains($key, 'unique-question-id')) {
                        array_push($questions_unique_ids, $value);
                    }
                }
                // array_output_die($questions_unique_ids);
                if(!empty($_POST["select_quiz"])) {
                    $result = true;
                    foreach($questions_unique_ids as $key => $value) {
                        $exam_id = get_safe_value($conn, $_POST["select_quiz"]);
                        $question_table = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id, questions FROM questions WHERE question_id = $value"));
                        $question = get_safe_value($conn, $question_table["questions"]);
                        $question_id = get_safe_value($conn, $question_table["id"]);
                        $question = get_safe_value($conn, mysqli_fetch_assoc(mysqli_query($conn, "SELECT questions FROM questions WHERE question_id = $value"))["questions"]);
                        $check_sql = "SELECT * FROM question_exam_mapping WHERE question_id = $question_id AND exam_id = $exam_id";
                        // echo $check_sql . "<br />";
                        $check_result = mysqli_query($conn, $check_sql);
                        // echo mysqli_num_rows($check_result);
                        if(mysqli_num_rows($check_result) == 0) {
                            $sql = "INSERT INTO question_exam_mapping (questions, question_id, exam_id, status) VALUES ('$question', $question_id, $exam_id, 1)";
                            // echo $sql;
                            $result = mysqli_query($conn, $sql);
                        }
                    }
                    if($result) {
                        $alert_message = '<div class="alert alert-success alert-dismissible fade show" role="alert">Successfully! All questions are added to the quizes.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                    }
                } else {
                    $new_quiz = $_POST["new_quiz"];
                    $sql = "SELECT * FROM exam_portal WHERE exam_name = '$new_quiz'";
                    $result = mysqli_query($conn, $sql);
                    if(mysqli_num_rows($result) > 0) {
                        $alert_message = '<div class="alert alert-warning alert-dismissible fade show" role="alert">Quiz already exists!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                    } else {
                        $sql = "INSERT INTO exam_portal (exam_name, duration, status) VALUES ('$new_quiz', $_POST[exam_duration], 1)";
                        $result = mysqli_query($conn, $sql);
                        $sql = "SELECT id FROM exam_portal WHERE exam_name = '$new_quiz'";
                        $result = mysqli_query($conn, $sql);
                        $exam_id = mysqli_fetch_assoc($result)["id"];
                        foreach($questions_unique_ids as $key => $value) {
                            $question = get_safe_value($conn, mysqli_fetch_assoc(mysqli_query($conn, "SELECT questions FROM questions WHERE question_id = $value"))["questions"]);
                            $question_id = get_safe_value($conn, mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM questions WHERE question_id = $value"))["id"]);
                            $check_sql = "SELECT * FROM question_exam_mapping WHERE question_id = $question_id AND exam_id = $exam_id";
                            // echo $check_sql . "<br />";
                            $check_result = mysqli_query($conn, $check_sql);
                            if(mysqli_num_rows($check_result) == 0) {
                                $sql = "INSERT INTO question_exam_mapping (questions, question_id, exam_id, status) VALUES ('$question', $question_id, $exam_id, 1)";
                                $result = mysqli_query($conn, $sql);
                            }
                        }
                        if($result) {
                            $alert_message = '<div class="alert alert-success alert-dismissible fade show" role="alert">Successfully! All questions are added to the quizes.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                        }
                    }
                }
            }
        } elseif(isset($_POST["activate-previously-deleted-question"])) {
            $current_question_id = get_safe_value($conn, $_POST["current-question-id"]);
            $previously_deleted_question_id = get_safe_value($conn, $_POST["previously-deleted-question-id"]);
            $sql = "UPDATE questions SET status = 1 WHERE id = $previously_deleted_question_id";
            $result = mysqli_query($conn, $sql);
            if($result) {
                $sql = "UPDATE questions SET status = 0 WHERE id = $current_question_id";
                $result = mysqli_query($conn, $sql);
                if($result) {
                    $alert_message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                                Question added successfully!
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>';
                }
            }
        } elseif(isset($_POST["activate-previously-deleted-question-add"])) {
            $previously_deleted_question_id = get_safe_value($conn, $_POST["previously-deleted-question-id"]);
            $sql = "UPDATE questions SET status = 1 WHERE id = $previously_deleted_question_id";
            $result = mysqli_query($conn, $sql);
            if($result) {
                $alert_message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                                Question added successfully!
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>';
            }
        }
    }

    echo $alert_message;
?>

<div class="container my-2 d-flex justify-content-end">
    <button type="button" class="btn btn-danger me-2 question-bank-top-buttons" data-bs-toggle="modal" data-bs-target="#topicModal">Add Topic</button>
    <button type="button" class="btn btn-warning me-2 question-bank-top-buttons" data-bs-toggle="modal" data-bs-target="#mainModal">Add Main Group</button>
    <button type="button" class="btn btn-success me-2 question-bank-top-buttons" data-bs-toggle="modal" data-bs-target="#subModal">Add Sub Group</button>
    <button type="button" class="btn btn-primary me-2 question-bank-top-buttons" data-bs-toggle="modal" data-bs-target="#questionModal">Add Questions</button>
    <button type="button" class="btn btn-success question-bank-top-buttons" data-bs-toggle="modal" data-bs-target="#createQuizModal">Create Quiz</button>
    <!-- Topic Modal -->
    <div class="modal fade" id="topicModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="topicModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add Topic</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body position-relative">
                    <!--<button class="btn btn-primary position-absolute add-option add-question-add-option">Add Option</button>-->
                    <form action="question_bank.php" id="add-topic-form" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="question" class="form-label">Topic</label>
                            <input type="text" class="form-control d-inline-block border border-dark" id="topic" name="topic" required>
                        </div>
                        <div class="mb-3">
                            <label for="question" class="form-label">Select Topic</label>
                            <select name="select_topic" class="form-select border border-dark" id="select_topic">
                                <option value="">Select Topic</option>
                                <?php
                                    $sql = "SELECT * FROM topic";
                                    $result = mysqli_query($conn, $sql);
                                    if(mysqli_num_rows($result) > 0) {
                                        while($row = mysqli_fetch_assoc($result)) {
                                            echo '<option value="' . $row["id"] . '">' . $row["topic"] . '</option>';
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary d-none" id="submit-topic" name="add_topic">Submit</button>
                        <button type="reset" class="btn btn-secondary d-none" id="reset-question">Reset</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <!--<button class="btn btn-primary position-absolute add-option add-question-add-option">Add Option</button>-->
                    <button type="button" class="btn btn-danger" id="add-topic">Save</button>
                    <button type="button" class="btn btn-secondary close-modal-btn" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Main Modal -->
    <div class="modal fade" id="mainModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="mainModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add Main Group</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body position-relative">
                    <!--<button class="btn btn-primary position-absolute add-option add-question-add-option">Add Option</button>-->
                    <form action="question_bank.php" id="add-main-group-form" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="question" class="form-label">Main Group</label>
                            <input type="text" class="form-control d-inline-block border border-dark" id="main-group" name="main_group" required>
                        </div>
                        <div class="mb-3">
                            <label for="question" class="form-label">Select Main Group</label>
                            <select name="select_main_group" class="form-select border border-dark" id="select_main_group">
                                <option value="">Select Main Group</option>
                                <?php
                                    $sql = "SELECT * FROM main_group";
                                    $result = mysqli_query($conn, $sql);
                                    if(mysqli_num_rows($result) > 0) {
                                        while($row = mysqli_fetch_assoc($result)) {
                                            echo '<option value="' . $row["id"] . '">' . $row["main_group"] . '</option>';
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary d-none" id="submit-main-group" name="add_main_group">Submit</button>
                        <button type="reset" class="btn btn-secondary d-none" id="reset-question">Reset</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <!--<button class="btn btn-primary position-absolute add-option add-question-add-option">Add Option</button>-->
                    <button type="button" class="btn btn-warning" id="add-main-group">Save</button>
                    <button type="button" class="btn btn-secondary close-modal-btn" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Sub Modal -->
    <div class="modal fade" id="subModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="subModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add Sub Group</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body position-relative">
                    <!--<button class="btn btn-primary position-absolute add-option add-question-add-option">Add Option</button>-->
                    <form action="question_bank.php" id="add-sub-group-form" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="question" class="form-label">Sub Group</label>
                            <input type="text" class="form-control d-inline-block border border-dark" id="sub-group" name="sub_group" required>
                        </div>
                        <div class="mb-3">
                            <label for="question" class="form-label">Select Sub Group</label>
                            <select name="select_sub_group" class="form-select border border-dark" id="select_sub_group">
                                <option value="">Select Sub Group</option>
                                <?php
                                    $sql = "SELECT * FROM sub_group";
                                    $result = mysqli_query($conn, $sql);
                                    if(mysqli_num_rows($result) > 0) {
                                        while($row = mysqli_fetch_assoc($result)) {
                                            echo '<option value="' . $row["id"] . '">' . $row["sub_group"] . '</option>';
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary d-none" id="submit-sub-group" name="add_sub_group">Submit</button>
                        <button type="reset" class="btn btn-secondary d-none" id="reset-question">Reset</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <!--<button class="btn btn-primary position-absolute add-option add-question-add-option">Add Option</button>-->
                    <button type="button" class="btn btn-success" id="add-sub-group">Save</button>
                    <button type="button" class="btn btn-secondary close-modal-btn" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Modal -->
    <div class="modal fade" id="questionModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="questionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add Question</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body position-relative">
                    <!--<button class="btn btn-primary position-absolute add-option add-question-add-option">Add Option</button>-->
                    <form action="question_bank.php" id="add-question-form" method="POST" enctype="multipart/form-data">
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
                            <textarea class="form-control border border-dark" id="question" name="question" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="question-image" class="form-label">Choose Image</label>
                            <input class="form-control border border-dark" type="file" id="question-image" name="question-image" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label for="add_question_topic" class="form-label">Topic</label>
                            <select id="add_question_topic" name="add_question_topic[]" class="form-select border border-dark" multiple>
                                <option value="0">Select Topic</option>
                                <?php
                                    $sql = "SELECT * FROM topic";
                                    $result = mysqli_query($conn, $sql);
                                    if(mysqli_num_rows($result) > 0) {
                                        while($row = mysqli_fetch_assoc($result)) {?>
                                        <option value="<?= $row["id"];?>"><?= $row["topic"];?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="add_question_main_group" class="form-label">Main Group</label>
                            <select id="add_question_main_group" name="add_question_main_group[]" class="form-select border border-dark" multiple>
                                <option value="0">Select Main Group</option>
                                <?php
                                    $sql = "SELECT * FROM main_group";
                                    $result = mysqli_query($conn, $sql);
                                    if(mysqli_num_rows($result) > 0) {
                                        while($row = mysqli_fetch_assoc($result)) {?>
                                        <option value="<?= $row["id"];?>"><?= $row["main_group"];?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="add_question_sub_group" class="form-label">Sub Group</label>
                            <select id="add_question_sub_group" name="add_question_sub_group[]" class="form-select border border-dark" multiple>
                                <option value="0">Select Sub Group</option>
                                <?php
                                    $sql = "SELECT * FROM sub_group";
                                    $result = mysqli_query($conn, $sql);
                                    if(mysqli_num_rows($result) > 0) {
                                        while($row = mysqli_fetch_assoc($result)) {?>
                                        <option value="<?= $row["id"];?>"><?= $row["sub_group"];?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3 add-question-type-box">
                            <label for="question-type" class="form-label">Question Type</label>
                            <select class="form-select question-type border border-dark" aria-label="Default select example" name="question-type" id="question-type" required>
                                <option value="" selected>Select Question Type</option>
                                <option value="title">Title</option>
                                <option value="single">Single Answer</option>
                                <option value="multiple">Multiple Answer</option>
                            </select>
                        </div>
                        <div class="mb-3 option-container">
                            <label for="opt_a" class="form-label">Option A</label>
                            <i class="fa-regular fa-circle toggle-correct"></i><input type="text" class="form-control d-inline-block border border-dark add-questions-options" id="opt_a" name="options[]" required><input type="hidden" class="form-control invisible add-questions-options" value="incorrect" id="correct_opt_a" name="correct_options[]" required><i class="fa-solid fa-xmark remove-option"></i>
                        </div>
                        <div class="mb-3 option-container">
                            <label for="opt_b" class="form-label">Option B</label>
                            <i class="fa-regular fa-circle toggle-correct"></i><input type="text" class="form-control d-inline-block border border-dark add-questions-options" id="opt_b" name="options[]" required><input type="hidden" class="form-control invisible add-questions-options" value="incorrect" id="correct_opt_b" name="correct_options[]" required><i class="fa-solid fa-xmark remove-option"></i>
                        </div>
                        <div class="mb-3 option-container">
                            <label for="opt_c" class="form-label">Option C</label>
                            <i class="fa-regular fa-circle toggle-correct"></i><input type="text" class="form-control d-inline-block border border-dark add-questions-options" id="opt_c" name="options[]" required><input type="hidden" class="form-control invisible add-questions-options" value="incorrect" id="correct_opt_c" name="correct_options[]" required><i class="fa-solid fa-xmark remove-option"></i>
                        </div>
                        <div class="mb-3 option-container">
                            <label for="opt_d" class="form-label">Option D</label>
                            <i class="fa-regular fa-circle toggle-correct"></i><input type="text" class="form-control d-inline-block border border-dark add-questions-options" id="opt_d" name="options[]" required><input type="hidden" class="form-control invisible add-questions-options" value="incorrect" id="correct_opt_d" name="correct_options[]" required><i class="fa-solid fa-xmark remove-option"></i>
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
                        <button type="reset" class="btn btn-secondary d-none" id="reset-question">Reset</button>
                    </form>
                    <button class="btn btn-primary add-option add-question-add-option">Add Option</button>
                </div>
                <div class="modal-footer">
                    <!--<button class="btn btn-primary position-absolute add-option add-question-add-option">Add Option</button>-->
                    <button type="button" class="btn btn-primary" id="add-question">Save</button>
                    <button type="button" class="btn btn-secondary close-modal-btn" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Create Quiz Modal -->
    <div class="modal fade" id="createQuizModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="subModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Create Quiz</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body position-relative">
                    <!--<button class="btn btn-primary position-absolute add-option add-question-add-option">Add Option</button>-->
                    <form action="question_bank.php" id="create-quiz-form" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="question" class="form-label">New Quiz Name</label>
                            <input type="text" class="form-control d-inline-block border border-dark" id="new-quiz" name="new_quiz" required>
                        </div>
                        <div class="mb-3">
                            <label for="question" class="form-label d-block">Exam Duration</label>
                            <input type="text" class="form-control d-inline-block border border-dark" id="exam-duration" name="exam_duration" maxlength="3" required><span class="ms-2">(Minutes)</span>
                        </div>
                        <div class="mb-3">
                            <label for="question" class="form-label">Select Quiz</label>
                            <select class="form-select border border-dark" name="select_quiz" id="select_quiz">
                                <option value="">Select Quiz</option>
                                <?php
                                    $sql = "SELECT * FROM exam_portal WHERE exam_name != 'Four Axis Personality Type Test' AND exam_name != 'Emotional Intelligence Test'";
                                    $result = mysqli_query($conn, $sql);
                                    if (mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo '<option value="' . $row["id"] . '">' . $row["exam_name"] . '</option>';
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary d-none" id="submit-create-quiz" name="submit_create_quiz">Submit</button>
                        <button type="reset" class="btn btn-secondary d-none" id="reset-question">Reset</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <!--<button class="btn btn-primary position-absolute add-option add-question-add-option">Add Option</button>-->
                    <button type="button" class="btn btn-success" id="create-quiz">Save</button>
                    <button type="button" class="btn btn-secondary close-modal-btn" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<h2 class="text-center">Question Bank</h2>
<!-- <div class="container d-flex justify-content-end">
    <label class="">Search in: 
        <select id="columnSelector">
            <option value="all">All Columns</option>
            <option value="4">Question ID</option>
            <option value="5">Topic</option>
            <option value="6">Main Group</option>
            <option value="7">Sub Group</option>
            <option value="8">Questions</option>
        </select>
    </label>
</div> -->
<div class="container min-height-100-vh table-responsive">
    <table class="table table-bordered resizable" data-resizable-columns-id="question-bank-table" id="question-bank-table" style="width: 1500px;">
        <thead>
            <tr>
                <th scope="col" data-resizable-column-id="sr_no">Sr. no.</th>
                <th scope="col" data-resizable-column-id="edit">Edit</th>
                <th scope="col" data-resizable-column-id="delete">Delete</th>
                <th scope="col" data-resizable-column-id="add_to_quiz">Add to Quiz</th>
                <th scope="col" data-resizable-column-id="question_id">Question ID</th>
                <th scope="col" data-resizable-column-id="topic">Topic</th>
                <th scope="col" data-resizable-column-id="main_group">Main Group</th>
                <th scope="col" data-resizable-column-id="sub_group">Sub Group</th>
                <th scope="col" data-resizable-column-id="questions">Questions</th>
                <th scope="col" data-resizable-column-id="no_of_times_correctly_attempted">No. of Times Correctly Attempted</th>
                <th scope="col" data-resizable-column-id="no_of_times_attempted">No. of Times Attempted</th>
                <th scope="col" data-resizable-column-id="percentage_correctly_attempted">Percentage Correctly Attempted</th>
                <th scope="col" data-resizable-column-id="difficulty_level">Difficulty Level</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $exam_id_sql = "SELECT id, exam_name FROM exam_portal";
                $exam_id_sql_result = mysqli_query($conn, $exam_id_sql);
                $exam_id = mysqli_fetch_assoc($exam_id_sql_result)["id"];
                $exam_name = mysqli_fetch_assoc($exam_id_sql_result)["exam_name"];
                $sql = "SELECT * FROM questions WHERE status = 1 ORDER BY id ASC";
                $result = mysqli_query($conn, $sql);
                $sr_no = 1;
                $show_series = 0;
                $edit_and_delete_sr_no = 1;
                if(mysqli_num_rows($result) > 0) {
                    // mysqli_set_charset($conn, "utf8mb4");
                    // echo array_output_die(mysqli_fetch_assoc($result));
                    while($row = mysqli_fetch_assoc($result)) {
                        $question_id = $row["id"];
                        $get_question = $row["questions"];
                        // $exam_id = $row["exam_id"];
                        $topic_id_array = array();
                        $main_group_id_array = array();
                        $sub_group_id_array = array();
                        $topic_ids_sql = "SELECT * FROM question_topic_mapping WHERE question_id = $question_id AND status = 1";
                        $topic_ids_sql_result = mysqli_query($conn, $topic_ids_sql);
                        if(mysqli_num_rows($topic_ids_sql_result) > 0) {
                            while($topic_id_row = mysqli_fetch_assoc($topic_ids_sql_result)) {
                                $topic_id_array[] = $topic_id_row["topic_id"];
                            }
                        }
                        $main_group_ids_sql = "SELECT * FROM question_main_group_mapping WHERE question_id = $question_id AND status = 1";
                        $main_group_ids_sql_result = mysqli_query($conn, $main_group_ids_sql);
                        if(mysqli_num_rows($main_group_ids_sql_result) > 0) {
                            while($main_group_id_row = mysqli_fetch_assoc($main_group_ids_sql_result)) {
                                $main_group_id_array[] = $main_group_id_row["main_group_id"];
                            }
                        }
                        $sub_group_ids_sql = "SELECT * FROM question_sub_group_mapping WHERE question_id = $question_id AND status = 1";
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
                                <button type="button" class="btn btn-primary individual-edit-question" data-bs-toggle="modal"
                                data-bs-target="#questionEditModal">Edit</button>
                                <span class="d-none"><?php
                                    $edit_data_array = array();
                                    foreach ($row as $key => $value) {
                                        if($key == "status" || $key == "created_on" || $key == "no_of_times_attempted" || $key == "no_of_times_correctly_attempted" || $key == "question_order") continue;
                                        $edit_data_array[$key] = $value;
                                        echo "<div class='mb-2'><strong class='$key'>" . str_replace("_", " ", $key) . ": </strong> <span>" . $value . "</span></div>";
                                    }
                                    // $edit_option_data_array = array();
                                    $edit_option_data_sql = "SELECT answers, is_correct FROM options WHERE question_id = $question_id AND status = 1 ORDER BY id ASC";
                                    $edit_option_data_sql_result = mysqli_query($conn, $edit_option_data_sql);
                                    if(mysqli_num_rows($edit_option_data_sql_result) > 0) {
                                        while($edit_option_data_row = mysqli_fetch_assoc($edit_option_data_sql_result)) {
                                            // $edit_option_data_array[] = $edit_option_data_row;
                                            echo "<div class='mb-2'><strong class='options' data-is-correct='" . $edit_option_data_row["is_correct"] . "'>Option: </strong><span>" . $edit_option_data_row["answers"] . "</span></div>";
                                        }
                                    }
                                    // $edit_data_array["options"] = $edit_option_data_array;
                                    // echo get_safe_value($conn, str_replace("\\r", "", str_replace("\\n", "", json_encode($edit_data_array, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT))));
                                ?></span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                data-bs-target="#questionDeleteModal">Delete</button>
                                <?php //$edit_and_delete_sr_no++;?>
                            </td>
                            <td class="text-center">
                                <input type="checkbox" class="add-to-quiz d-none" id="add-to-quiz-<?php echo $row["id"];?>" name="add-to-quiz-<?php echo $row["id"];?>">
                                <label for="add-to-quiz-<?php echo $row["id"];?>" class="w-100"><i class="fa-regular fa-circle"></i></label>
                                <label for="add-to-quiz-<?php echo $row["id"];?>" class="w-100"><i class="fa-regular fa-circle-check text-success"></i></label>
                            </td>
                            <td><?= $row["question_id"]; ?></td>
                            <td class="assign-topics">
                                <?php
                                    // array_output_die($topic_id_array);
                                    if(count($topic_id_array) > 0) {
                                        $sql = "SELECT * FROM topic WHERE id IN (" . implode(",", $topic_id_array) . ") ORDER BY FIELD(id, " . implode(",", $topic_id_array) . ")";
                                        // echo $sql;
                                        $topic_result = mysqli_query($conn, $sql);
                                        if(mysqli_num_rows($topic_result) > 0) {
                                            while($topic_row = mysqli_fetch_assoc($topic_result)) {
                                                echo "<div class='assigned-topics p-1'><button class='btn toggle-status' data-value='0' data-question-id='" . $row["id"] . "' data-topic-id='" . $topic_row["id"] . "' data-topic='" . $topic_row["topic"] . "'>" . $topic_row["topic"] . "</button><span class='bg-danger rounded-circle text-white remove-topic'>X</span></div>";
                                            }
                                        }
                                    }
                                ?>
                                <div class='card d-none' style='width: 18rem;'>
                                    <div class='card-body'>
                                        <input type="text" class="form-control search-topic" name="search-topic" placeholder="Search for Topic...">
                                        <div class="topic-container mt-2">
                                            <?php
                                                $topic_sql = "SELECT * FROM topic";
                                                $topic_result = mysqli_query($conn, $topic_sql);
                                                if($topic_result) {
                                                    while($topic_row = mysqli_fetch_assoc($topic_result)) {
                                                        // if(in_array($row["exam_name"], $assigned_exam_array)) {
                                                            $is_available = in_array($topic_row["id"], $topic_id_array) ? 1 : 0;
                                                            echo "<div><input type='checkbox' class='form-check-input toggle-topics-assignment' data-question-id='" . $row["id"] . "' data-topic-id='" . $topic_row["id"] . "' data-topic='" . $topic_row["topic"] . "' data-value='0' " . ($is_available ? "checked" : "") . "><p class='card-title d-inline-block mx-2'>" . $topic_row["topic"] . "</p></div>";
                                                        // }
                                                    }
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="assign-main-groups">
                                <?php
                                    // array_output_die($main_group_id_array);
                                    if(count($main_group_id_array) > 0) {
                                        $sql = "SELECT * FROM main_group WHERE id IN (" . implode(",", $main_group_id_array) . ") ORDER BY FIELD(id, " . implode(",", $main_group_id_array) . ")";
                                        $main_group_result = mysqli_query($conn, $sql);
                                        if(mysqli_num_rows($main_group_result) > 0) {
                                            while($main_group_row = mysqli_fetch_assoc($main_group_result)) {
                                                echo "<div class='assigned-main-groups p-1'><button class='btn toggle-status' data-value='0' data-question-id='" . $row["id"] . "' data-main-group-id='" . $main_group_row["id"] . "' data-main-group='" . $main_group_row["main_group"] . "'>" . $main_group_row["main_group"] . "</button><span class='bg-danger rounded-circle text-white remove-main-group'>X</span></div>";
                                            }
                                        }
                                    }
                                ?>
                                <div class='card d-none' style='width: 18rem;'>
                                    <div class='card-body'>
                                        <input type="text" class="form-control search-main-group" name="search-main-group" placeholder="Search for Main Group...">
                                        <div class="main-group-container mt-2">
                                        <?php
                                            $main_group_sql = "SELECT * FROM main_group";
                                            $main_group_result = mysqli_query($conn, $main_group_sql);
                                            if(mysqli_num_rows($main_group_result) > 0) {
                                                while($main_group_row = mysqli_fetch_assoc($main_group_result)) {
                                                    // if(in_array($row["exam_name"], $assigned_exam_array)) {
                                                        $is_available = in_array($main_group_row["id"], $main_group_id_array) ? 1 : 0;
                                                        echo "<div><input type='checkbox' class='form-check-input toggle-main-groups-assignment' data-question-id='" . $row["id"] . "' data-main-group-id='" . $main_group_row["id"] . "' data-main-group='" . $main_group_row["main_group"] . "' data-value='0' " . ($is_available ? "checked" : "") . "><p class='card-title d-inline-block mx-2'>" . $main_group_row["main_group"] . "</p></div>";
                                                    // }
                                                }
                                            }
                                        ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <!-- <td <?php if($row["question_type"] !== "title") {?> class="d-none" <?php } ?>></td> -->
                            <td class="assign-sub-groups">
                                <?php
                                    // array_output_die($sub_group_id_array);
                                    if(count($sub_group_id_array) > 0) {
                                        $sql = "SELECT * FROM sub_group WHERE id IN (" . implode(",", $sub_group_id_array) . ") ORDER BY FIELD(id, " . implode(",", $sub_group_id_array) . ")";
                                        $sub_group_result = mysqli_query($conn, $sql);
                                        if(mysqli_num_rows($sub_group_result) > 0) {
                                            while($sub_group_row = mysqli_fetch_assoc($sub_group_result)) {
                                                echo "<div class='assigned-sub-groups p-1'><button class='btn toggle-status' data-value='0' data-question-id='" . $row["id"] . "' data-sub-group-id='" . $sub_group_row["id"] . "' data-sub-group='" . $sub_group_row["sub_group"] . "'>" . $sub_group_row["sub_group"] . "</button>
                                                <span class='bg-danger rounded-circle text-white remove-sub-group'>X</span></div>";
                                            }
                                        }
                                    }
                                ?>
                                <div class='card d-none' style='width: 18rem;'>
                                    <div class='card-body'>
                                        <input type="text" class="form-control search-sub-group" name="search-sub-group" placeholder="Search for Sub Group...">
                                        <div class="sub-group-container mt-2">
                                        <?php
                                            $sub_group_sql = "SELECT * FROM sub_group";
                                            $sub_group_result = mysqli_query($conn, $sub_group_sql);
                                            if($sub_group_result) {
                                                while($sub_group_row = mysqli_fetch_assoc($sub_group_result)) {
                                                    // if(in_array($row["exam_name"], $assigned_exam_array)) {
                                                        $is_available = in_array($sub_group_row["id"], $sub_group_id_array) ? 1 : 0;
                                                        echo "<div><input type='checkbox' class='form-check-input toggle-sub-groups-assignment' data-question-id='" . $row["id"] . "' data-sub-group-id='" . $sub_group_row["id"] . "' data-sub-group='" . $sub_group_row["sub_group"] . "' data-value='0' " . ($is_available ? "checked" : "") . "><p class='card-title d-inline-block mx-2'>" . $sub_group_row["sub_group"] . "</p></div>";
                                                    // }
                                                }
                                            }
                                        ?>
                                        </div>
                                    </div>
                                </div>
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
                                $sql = "SELECT * FROM options WHERE question_id = '" . $question_id . "' AND status = 1";
                                // echo $sql;
                                $option_result = mysqli_query($conn, $sql);
                                if(mysqli_num_rows($option_result) > 0) {
                                    while($row2 = mysqli_fetch_assoc($option_result)) { ?>
                                            <div class="d-flex">
                                            <input type="checkbox" name="options[]" id="options-<?php echo $sr_no; ?>" class="" <?php if($row2["is_correct"] == "1") {echo "checked";}?>>
                                            <label for="options-<?php echo $sr_no; ?>" class="w-100 ms-2" data-question-id="<?= $question_id; ?>" data-option="<?= $row2["answers"]; ?>" data-question-type="<?= $row["question_type"];?>"><?php echo chr($char_sr_no) . ". <span>" . $row2["answers"] . "</span>";?></label>
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
                                <input type="checkbox" class="add-to-quiz d-none" id="add-to-quiz-<?php echo $row["id"];?>" name="add-to-quiz-<?php echo $row["id"];?>">
                                <label for="add-to-quiz-<?php echo $row["id"];?>" class="w-100"><i class="fa-regular fa-circle"></i></label>
                                <label for="add-to-quiz-<?php echo $row["id"];?>" class="w-100"><i class="fa-regular fa-circle-check text-success"></i></label>
                            </td>
                            <td>
                                <button type="button" class="btn btn-primary individual-edit-question" data-bs-toggle="modal"
                                    data-bs-target="#questionEditModal<?php echo $edit_and_delete_sr_no; ?>">Edit</button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                data-bs-target="#questionDeleteModal<?php echo $edit_and_delete_sr_no; ?>">Delete</button>
                                <?php $edit_and_delete_sr_no++;?>
                            </td> -->
                        </tr>
                <?php
                        $sr_no++;
                    }
                } else { ?>
                    <tr><td colspan='8' class="text-center">No questions found.</td></tr>
                <?php }
                ?>
                <tfoot>
                    <tr>
                        <th scope="col">Sr. no.</th>
                        <th scope="col">Edit</th>
                        <th scope="col">Delete</th>
                        <th scope="col">Add to Quiz</th>
                        <th scope="col">Question ID</th>
                        <th scope="col">Topic</th>
                        <th scope="col">Main Group</th>
                        <th scope="col">Sub Group</th>
                        <th scope="col">Questions</th>
                        <th scope="col">No. of Times Correctly Attempted</th>
                        <th scope="col">No. of Times Attempted</th>
                        <th scope="col">Percentage Correctly Attempted</th>
                        <th scope="col">Difficulty Level</th>
                    </tr>
                </tfoot>
        </tbody>
    </table>
</div>


<?php
    // $modal_sql = "SELECT * FROM questions WHERE status = 1";
    // $modal_result = mysqli_query($conn, $modal_sql);
    // $sr_no = 1;
    // if(mysqli_num_rows($modal_result) > 0) {
        // while($modal_row = mysqli_fetch_assoc($modal_result)) {
            // $char_sr_no = 65;
            // $edit_question_id = $modal_row["id"];
            // $question_type = $modal_row["question_type"];
            // $option_sql = "SELECT * FROM questions WHERE questions = '$modal_row[questions]'";
?>
<!-- Edit Modal -->
<div class="modal fade" id="questionEditModal<?php // echo $sr_no; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="questionEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Question</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <!--<button type="button" class="btn btn-primary position-absolute add-option edit-question-add-option">Add Option</button>-->
            <form action="question_bank.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3 d-none">
                        <input type="text" class="form-control border border-dark" id="edit_id" name="edit_id" value="<?php // echo $modal_row["id"]; ?>" readonly>
                    </div>
                    <div class="mb-3 d-none">
                    <textarea class="form-control border border-dark" id="old_question" name="old_question" rows="3" readonly><?php // echo $modal_row["questions"]; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="question" class="form-label">Question</label>
                        <textarea class="form-control edit_question border border-dark" id="edit_question" name="edit_question" data-question-id="<?php // echo $modal_row["id"]; ?>" rows="3" required><?php // echo $modal_row["questions"]; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit-question-image" class="form-label">Choose Image</label>
                        <input class="form-control border border-dark" type="file" id="edit-question-image" name="edit-question-image" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <div class="form-label">Question ID</div>
                        <input class="form-control border border-dark" type="text" name="edit_question_id" value="<?php // echo $modal_row["question_id"]; ?>" id="edit_question_id" readonly>
                    </div>
                    <div class="mb-3 edit-question-type-box">
                        <label for="edit-question-type" class="form-label">Question Type</label>
                        <select class="form-select border border-dark question-type" aria-label="Default select example" name="edit-question-type" id="edit-question-type" required>
                            <option value="" selected>Select Question Type</option>
                            <option value="title" <?php // if($modal_row["question_type"] == 'title') {?><?php // } ?> <?php // if($modal_row["question_type"] !== 'title') {?><?php // } ?>>Title</option>
                            <option value="single" <?php // if($modal_row["question_type"] == 'single') {?><?php // } ?> <?php // if($modal_row["question_type"] == 'title') {?><?php // } ?>>Single Answer</option>
                            <option value="multiple" <?php // if($modal_row["question_type"] == 'multiple') {?><?php // } ?> <?php // if($modal_row["question_type"] == 'title') {?><?php // } ?>>Multiple Answer</option>
                        </select>
                    </div>
                    <?php
                        // $edit_sr_no = 1;
                        // echo "<span style='color: red;'>" . $character . "</span>";
                        // die;
                        // $sql = "SELECT * FROM options WHERE question_id = '" . $modal_row["id"] . "' AND status = 1";
                        // echo $sql;
                        // $option_result = mysqli_query($conn, $sql);
                        // if(mysqli_num_rows($option_result) > 0) {
                            // while($row2 = mysqli_fetch_assoc($option_result)) {
                                // $character = chr(64 + $edit_sr_no);
                                // if($question_type == "single" || $question_type == "multiple") {
                                ?>
                                    <!-- <div>
                                    <input type="checkbox" name="options[]" id="edit-options-<?php // echo $edit_sr_no; ?>" class="d-none">
                                    <label for="edit-options-<?php // echo $edit_sr_no; ?>" class="d-block"><?php // echo $edit_sr_no . ". " . $row2["options"];?></label>
                                </div> -->
                                <div class="mb-3 option-container">
                                    <label for="edit_options_a" class="form-label">Option A<?php // echo $character;?></label> <i class="fa-regular <?php // if($row2["is_correct"] == "1"){echo 'fa-circle-check';} else {echo 'fa-circle';}?>fa-circle toggle-correct <?php // if($row2["is_correct"] == "1"){echo 'text-success';}?>" aria-hidden="true"></i>
                                    <input type="text" class="form-control border border-dark d-inline-block add-questions-options" id="edit_options_a" name="options[]" value="<?php // echo $row2["answers"]; ?>" required>
                                    <input type="hidden" class="form-control invisible add-questions-options" id="correct_opt_a" name="correct_options[]" value="<?php // echo $row2["is_correct"] == "1" ? "correct" : "incorrect"; ?>incorrect">
                                    <i class="fa-solid fa-xmark remove-option" aria-hidden="true"></i>
                                    <!-- <input type="checkbox" class="form-control" id="correct_edit_opttions_<?php //echo $edit_sr_no; ?>" name="correct_edit_opttions_[]" value="<?php //echo $row2["options"]; ?>"> -->
                                </div>
                            <?php // $edit_sr_no++;}
                        // }
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
                        <button type="reset" class="btn btn-secondary d-none" id="reset-question">Reset</button>

                </form>
                <button type="button" class="btn btn-primary add-option edit-question-add-option">Add Option</button>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary edit-question" id="edit-question<?php // echo $sr_no; ?>">Save</button>
                <button type="button" class="btn btn-secondary close-modal-btn" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Delete Modal -->
<div class="modal fade" id="questionDeleteModal<?php // echo $sr_no; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
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
                <form action="question_bank.php" method="POST">
                    <input type="hidden" name="question_id" value="<?php // echo $modal_row["id"]; ?>">
                    <input type="hidden" name="question_type" value="<?php // echo $modal_row["question_type"]; ?>">
                    <button type="submit" class="btn btn-primary d-none delete_question_submit"
                        id="delete_question<?php // echo $sr_no; ?>" name="delete_question" value="<?php // echo $modal_row["id"];?>">Submit</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger delete-question">Delete</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php
// $sr_no++;
//         }
//     }
    ?>
<?php
    // if($_SERVER["REQUEST_METHOD"] == "POST") {
    //     if(isset($_POST["add_question"]) || isset($_POST["edit_question_submit"])) {
    //         $edit_question = get_safe_value($conn, $_POST["edit_question"]);
    //         $select_already_available_question_id_sql = "SELECT * FROM questions WHERE questions = '$edit_question'";
    //         $select_already_available_question_id_result = mysqli_query($conn, $select_already_available_question_id_sql);
    //         if(mysqli_num_rows($select_already_available_question_id_result) > 0) {
    //             $already_available_question_id = get_safe_value($conn, mysqli_fetch_assoc($select_already_available_question_id_result)["id"]);
    //         }
    //         $modal_sql = "SELECT * FROM questions WHERE id = '" . $already_available_question_id . "'";
    //         $modal_result = mysqli_query($conn, $modal_sql);
    //         $sr_no = 1;
    //         if(mysqli_num_rows($modal_result) > 0) {
    //             while($modal_row = mysqli_fetch_assoc($modal_result)) {
    //                 $char_sr_no = 65;
    //                 $edit_question_id = $modal_row["id"];
    //                 $question_type = $modal_row["question_type"];
                    // $option_sql = "SELECT * FROM questions WHERE questions = '$modal_row[questions]'";
?>
<!-- Edit Modal -->
<!-- <div class="modal fade" id="detetedQuestionEditModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="DeletedQuestionEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Question</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body"> -->
            <!--<button type="button" class="btn btn-primary position-absolute add-option edit-question-add-option">Add Option</button>-->
            <!-- <form action="question_bank.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3 d-none">
                        <input type="text" class="form-control" id="edit_deleted_question_id" name="edit_deleted_question_id" value="<?php // echo $modal_row["id"]; ?>" readonly>
                    </div>
                    <div class="mb-3 d-none">
                    <textarea class="form-control" id="old_deleted_question" name="old_deleted_question" rows="3" required><?php // echo $modal_row["questions"]; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="question" class="form-label">Question</label>
                        <textarea class="form-control" id="edit_deleted_question" name="edit_deleted_question" rows="3" required><?php // echo $modal_row["questions"]; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit-question-image" class="form-label">Choose Image</label>
                        <input class="form-control" type="file" id="edit-deleted-question-image" name="edit-deleted-question-image" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <div class="form-label">Question ID</div>
                        <input class="form-control" type="text" name="edit_deleted_question_id" value="<?php // echo $modal_row["question_id"]; ?>" id="edit_deleted_question_id" readonly>
                    </div>
                    <div class="mb-3 edit-deleted-question-type-box">
                        <label for="edit-deleted-question-type" class="form-label">Question Type</label>
                        <select class="form-select question-type" aria-label="Default select example" name="edit-deleted-question-type" id="edit-deleted-question-type" required>
                            <option value="" selected>Select Question Type</option>
                            <option value="title" <?php // if($modal_row["question_type"] == 'title') {?>selected<?php // } ?> <?php // if($modal_row["question_type"] !== 'title') {?>disabled<?php // } ?>>Title</option>
                            <option value="single" <?php // if($modal_row["question_type"] == 'single') {?>selected<?php // } ?> <?php // if($modal_row["question_type"] == 'title') {?>disabled<?php // } ?>>Single Answer</option>
                            <option value="multiple" <?php // if($modal_row["question_type"] == 'multiple') {?>selected<?php // } ?> <?php // if($modal_row["question_type"] == 'title') {?>disabled<?php // } ?>>Multiple Answer</option>
                        </select> -->
                    </div>
                    <?php
                        // $edit_sr_no = 1;
                        // echo "<span style='color: red;'>" . $character . "</span>";
                        // die;
                        // $sql = "SELECT * FROM options WHERE question_id = '" . $modal_row["id"] . "' AND status = 1";
                        // echo $sql;
                        // echo $sql;
                        // $option_result = mysqli_query($conn, $sql);
                        // if(mysqli_num_rows($option_result) > 0) {
                            // while($row2 = mysqli_fetch_assoc($option_result)) {
                                // $character = chr(64 + $edit_sr_no);
                                // if($question_type == "single" || $question_type == "multiple") {
                                ?>
                                    <!-- <div>
                                    <input type="checkbox" name="options[]" id="edit-options-<?php // echo $edit_sr_no; ?>" class="d-none">
                                    <label for="edit-options-<?php // echo $edit_sr_no; ?>" class="d-block"><?php // echo $edit_sr_no . ". " . $row2["options"];?></label>
                                </div> -->
                                <!-- <div class="mb-3 option-container">
                                    <label for="edit_options_<?php // echo chr(96 + $edit_sr_no); ?>" class="form-label">Option <?php // echo $character;?></label><i class="fa-regular <?php // if($row2["is_correct"] == "1"){echo 'fa-circle-check';} else {echo 'fa-circle';}?> toggle-correct <?php // if($row2["is_correct"] == "1"){echo 'text-success';}?>" aria-hidden="true"></i>
                                    <input type="text" class="form-control d-inline-block add-questions-options deleted-questions-options" id="edit_options_<?php // echo chr(96 + $edit_sr_no); ?>" name="options[]" value="<?php // echo $row2["answers"]; ?>" required>
                                    <input type="hidden" class="form-control invisible add-questions-options" id="correct_opt_<?php // echo chr(96 + $edit_sr_no); ?>" name="correct_options[]" value="<?php // echo $row2["is_correct"] == "1" ? "correct" : "incorrect"; ?>">
                                    <i class="fa-solid fa-xmark remove-option" aria-hidden="true"></i> -->
                                    <!-- <input type="checkbox" class="form-control" id="correct_edit_opttions_<?php //echo $edit_sr_no; ?>" name="correct_edit_opttions_[]" value="<?php // echo $row2["options"]; ?>"> -->
                                </div>
                            <?php // $edit_sr_no++;}
                        // }
                    ?>
                    <!-- <div class="mb-3">
                        <label for="opt_a" class="form-label">Option A</label>
                        <input type="text" class="form-control" id="edit_opt_a" name="edit_opt_a" value="<?php //echo $row["opt_a"]; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="opt_b" class="form-label">Option B</label>
                        <input type="text" class="form-control" id="edit_opt_b" name="edit_opt_b" value="<?php //echo $row["opt_b"]; ?>">
                    </div> -->
                    <!-- <button type="submit" class="btn btn-primary d-none edit_question_submit"
                        id="submit-question<?php // echo $sr_no; ?>" name="edit_question_submit">Submit</button>
                        <button type="reset" class="btn btn-secondary d-none" id="reset-question">Reset</button> -->

                <!-- </form> -->
                <!-- <button type="button" class="btn btn-primary add-option edit-question-add-option deleted-question-add-option">Add Option</button> -->

            <!-- </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary edit-question" id="edit-question<?php echo $sr_no; ?>" disabled>Save</button>
                <button type="button" class="btn btn-secondary close-modal-btn" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div> -->
<!-- Delete Modal -->
<!-- <div class="modal fade" id="questionDeleteModal<?php echo $sr_no; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
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
                <form action="question_bank.php" method="POST">
                    <input type="hidden" name="question_id" value="<?php // echo $modal_row["id"]; ?>">
                    <input type="hidden" name="question_type" value="<?php // echo $modal_row["question_type"]; ?>">
                    <button type="submit" class="btn btn-primary d-none delete_question_submit"
                        id="delete_question<?php // echo $sr_no; ?>" name="delete_question" value="<?php // echo $modal_row["id"];?>">Submit</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger delete-question">Delete</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div> -->
<?php
// $sr_no++;
        // }
    // }
// }
    // }
    ?>
<?php
    include "Includes/footer.php";
?>