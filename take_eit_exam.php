<?php 
    define("PAGE", "MCQ Exam");
    define("TYPE", "");
    include "Connection/conn.inc.php";
    include "Includes/header.php";
    include "Includes/functions.inc.php";

    if (!isset($_SESSION["is_login"])) {
        header("location: Authentication/login.php");
        die;
    }

    if (!isset($_GET["exam"])) {
        header("location: index.php");
        die;
    }

    if(isset($_SESSION["exam_id"]) && isset($_SESSION["user_id"])) {
        if($_SERVER["REQUEST_METHOD"] !== "POST") {
            $sql = "DELETE FROM user_exam_mapping WHERE user_id = '$_SESSION[user_id]' AND exam_id = '$_SESSION[exam_id]'";
            $result = mysqli_query($conn, $sql);
            header("location: Authentication/logout.php");
            die;
        }
    }

    $exam_data_sql = "SELECT * FROM exam_portal WHERE exam_name = '$_GET[exam]'";
    $exam_data_result = mysqli_query($conn, $exam_data_sql);

    if (mysqli_num_rows($exam_data_result) > 0) {
        $row = mysqli_fetch_assoc($exam_data_result);
        $exam_id = $row["id"];
        $duration = $row["duration"];
        $_SESSION["exam_id"] = $exam_id;
    }

    $user_id_sql = "SELECT * FROM users WHERE email_id = '$_SESSION[email_id]'";
    $user_id_result = mysqli_query($conn, $user_id_sql);

    if (mysqli_num_rows($user_id_result) > 0) {
        $user_id = mysqli_fetch_assoc($user_id_result)["id"];
        $_SESSION["user_id"] = $user_id;
    }

    $check_assigned = "SELECT * FROM user_exam_mapping WHERE user_id = '$user_id' AND exam_id = '$exam_id'";
    $check_assigned_result = mysqli_query($conn, $check_assigned);

    if (mysqli_num_rows($check_assigned_result) == 0) {
        header("location: all_quizes.php");
        die;
    }

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $_SESSION["exam_submited"] = true;
        // array_output($question_ids);
        // pre_post();
        $keys = array_keys($_POST);
        $values = array_values($_POST);
        $test_series_sql = "SELECT * FROM eit_result WHERE user_id = '$user_id' AND exam_id = '$exam_id' ORDER BY test_series DESC";
        $test_series_result = mysqli_query($conn, $test_series_sql);
        $test_series = (mysqli_num_rows($test_series_result) > 0)?mysqli_fetch_assoc($test_series_result)["test_series"]:0;
        $new_test_series = (int)$test_series + 1;
        // pre_post();
        $sql = "INSERT INTO eit_result(user_id, question, answer, exam_id, test_series, status) VALUES ";
        for($i = 0; $i < count($keys); $i++) {
            if($keys[$i] == "exam-submit") {
                continue;
            }
    // $user_id = $row["id"];
            $sql .= "('$user_id', '$keys[$i]', '" . get_safe_value($conn, $values[$i]) . "', '$exam_id', '$new_test_series', 1),";
        }
        $sql = substr($sql, 0, -1);
        if(sizeof($values) == 0) {
            $sql .= "('$user_id', '', '', '$exam_id', '$new_test_series', 1);";
        }
        // echo $sql;
        // die;
        $result = mysqli_query($conn, $sql);
        if($result) {
            $self_awareness_score = 0;
            $managing_emotions_score = 0;
            $motivating_oneself_score = 0;
            $empathy_score = 0;
            $handling_relationships_score = 0;
            $question_ids = array();
            for($i=0;$i<count(array_keys($_POST));$i++) {
                array_push($question_ids, explode("_", array_keys($_POST)[$i])[1]);
            }
            function score_sql_management($conn, $question_ids, $values, &$score, $iteration, $domain_id) {
                $chars = ["a", "b", "c", "d", "e"];
                for($j=0;$j<count($chars);$j++) {
                    $score_calculation_sql = "SELECT * FROM eit_questions WHERE id = $question_ids[$iteration] AND opt_$chars[$j] = '$values[$iteration]' AND domain_id = $domain_id;";
                    // echo $score_calculation_sql . "<br />";
                    $score_calculation_result = mysqli_query($conn, $score_calculation_sql);
                    if(mysqli_num_rows($score_calculation_result) > 0) {
                        $score += (int)mysqli_fetch_assoc($score_calculation_result)["marks_$chars[$j]"];
                    }
                }
            }
            for($i=0;$i<count($question_ids);$i++) {
                score_sql_management($conn, $question_ids, $values, $self_awareness_score, $i, 1);
                score_sql_management($conn, $question_ids, $values, $managing_emotions_score, $i, 2);
                score_sql_management($conn, $question_ids, $values, $motivating_oneself_score, $i, 3);
                score_sql_management($conn, $question_ids, $values, $empathy_score, $i, 4);
                score_sql_management($conn, $question_ids, $values, $handling_relationships_score, $i, 5);
                // $score_calculation_sql = "SELECT * FROM eit_questions WHERE id = $question_ids[$i] AND opt_a = $values[$i] AND domain_id = 1";
                // $score_calculation_result = mysqli_query($conn, $score_calculation_sql);
                // if(mysqli_num_rows($score_calculation_result) > 0) {
                //     $self_awareness_score += mysqli_num_rows($score_calculation_result)["marks_a"];
                // }
                // $score_calculation_sql = "SELECT * FROM eit_questions WHERE id = $question_ids[$i] AND opt_a = $values[$i] AND domain_id = 2";
                // $score_calculation_result = mysqli_query($conn, $score_calculation_sql);
                // if(mysqli_num_rows($score_calculation_result) > 0) {
                //     $self_awareness_score += mysqli_num_rows($score_calculation_result)["marks_a"];
                // }
                // $score_calculation_sql = "SELECT * FROM eit_questions WHERE id = $question_ids[$i] AND opt_b = $values[$i] AND domain_id = 1";
                // $score_calculation_result = mysqli_query($conn, $score_calculation_sql);
                // if(mysqli_num_rows($score_calculation_result) > 0) {
                //     $self_awareness_score += mysqli_num_rows($score_calculation_result)["marks_b"];
                // }
                // $score_calculation_sql = "SELECT * FROM eit_questions WHERE id = $question_ids[$i] AND opt_c = $values[$i] AND domain_id = 1";
                // $score_calculation_result = mysqli_query($conn, $score_calculation_sql);
                // if(mysqli_num_rows($score_calculation_result) > 0) {
                //     $self_awareness_score += mysqli_num_rows($score_calculation_result)["marks_c"];
                // }
                // $score_calculation_sql = "SELECT * FROM eit_questions WHERE id = $question_ids[$i] AND opt_d = $values[$i] AND domain_id = 1";
                // $score_calculation_result = mysqli_query($conn, $score_calculation_sql);
                // if(mysqli_num_rows($score_calculation_result) > 0) {
                //     $self_awareness_score += mysqli_num_rows($score_calculation_result)["marks_d"];
                // }
                // $score_calculation_sql = "SELECT * FROM eit_questions WHERE id = $question_ids[$i] AND opt_e = $values[$i] AND domain_id = 1";
                // $score_calculation_result = mysqli_query($conn, $score_calculation_sql);
                // if(mysqli_num_rows($score_calculation_result) > 0) {
                //     $self_awareness_score += mysqli_num_rows($score_calculation_result)["marks_e"];
                // }
            }
            // echo "Self Awareness Score: " . $self_awareness_score . "<br />";
            // echo "Managing Emotions Score: " . $managing_emotions_score . "<br />";
            // echo "Motivating Oneself Score: " . $motivating_oneself_score . "<br />";
            // echo "Empathy Score: " . $empathy_score . "<br />";
            // echo "Handling Relationships Score: " . $handling_relationships_score . "<br />";

            // Self Awareness
            $self_awareness_series_sql = "SELECT series FROM self_awareness_result ORDER BY series DESC";
            $self_awareness_result = mysqli_query($conn, $self_awareness_series_sql);
            $self_awareness_series = (mysqli_num_rows($self_awareness_result) > 0)?((int)mysqli_fetch_assoc($self_awareness_result)["series"] + 1):1;
            $insert_self_awareness_score_sql = "INSERT INTO self_awareness_result (user_id, score, series, status) VALUES ($user_id, $self_awareness_score, $self_awareness_series, 1)";
            $insert_self_awareness_score_result = mysqli_query($conn, $insert_self_awareness_score_sql);

            // Managing Emotions
            $managing_emotions_series_sql = "SELECT series FROM managing_emotions_result ORDER BY series DESC";
            $managing_emotions_result = mysqli_query($conn, $managing_emotions_series_sql);
            $managing_emotions_series = (mysqli_num_rows($managing_emotions_result) > 0)?((int)mysqli_fetch_assoc($managing_emotions_result)["series"] + 1):1;
            $insert_managing_emotions_score_sql = "INSERT INTO managing_emotions_result (user_id, score, series, status) VALUES ($user_id, $managing_emotions_score, $managing_emotions_series, 1)";
            $insert_managing_emotions_score_result = mysqli_query($conn, $insert_managing_emotions_score_sql);

            // Motivating Oneself
            $motivating_oneself_series_sql = "SELECT series FROM motivating_oneself_result ORDER BY series DESC";
            $motivating_oneself_result = mysqli_query($conn, $motivating_oneself_series_sql);
            $motivating_oneself_series = (mysqli_num_rows($motivating_oneself_result) > 0)?((int)mysqli_fetch_assoc($motivating_oneself_result)["series"] + 1):1;
            $insert_motivating_oneself_score_sql = "INSERT INTO motivating_oneself_result (user_id, score, series, status) VALUES ($user_id, $motivating_oneself_score, $motivating_oneself_series, 1)";
            $insert_motivating_oneself_score_result = mysqli_query($conn, $insert_motivating_oneself_score_sql);

            // Empathy
            $empathy_series_sql = "SELECT series FROM empathy_result ORDER BY series DESC";
            $empathy_result = mysqli_query($conn, $empathy_series_sql);
            $empathy_series = (mysqli_num_rows($empathy_result) > 0)?((int)mysqli_fetch_assoc($empathy_result)["series"] + 1):1;
            $insert_empathy_score_sql = "INSERT INTO empathy_result (user_id, score, series, status) VALUES ($user_id, $empathy_score, $empathy_series, 1)";
            $insert_empathy_score_result = mysqli_query($conn, $insert_empathy_score_sql);
            
            // Handling Relationships
            $handling_relationships_series_sql = "SELECT series FROM handling_relationships_result ORDER BY series DESC";
            $handling_relationships_result = mysqli_query($conn, $handling_relationships_series_sql);
            $handling_relationships_series = (mysqli_num_rows($handling_relationships_result) > 0)?((int)mysqli_fetch_assoc($handling_relationships_result)["series"] + 1):1;
            $insert_handling_relationships_score_sql = "INSERT INTO handling_relationships_result (user_id, score, series, status) VALUES ($user_id, $handling_relationships_score, $handling_relationships_series, 1)";
            $insert_handling_relationships_score_result = mysqli_query($conn, $insert_handling_relationships_score_sql);

            header("location: all_quizes.php");
            die;
        }
    }
?>

<div class="container my-3">
    <?php
        $get_exam_id_sql = "SELECT * FROM exam_portal WHERE exam_name = '$_GET[exam]'";
        $get_exam_id_result = mysqli_query($conn, $get_exam_id_sql);
        $exam_id = mysqli_fetch_assoc($get_exam_id_result)["id"];
        $sql = "SELECT * FROM users WHERE email_id = '$_SESSION[email_id]'";
        $result = mysqli_query($conn, $sql);
        if($result) {
            $row = mysqli_fetch_row($result);
            $user_id = $row[0];
    ?>
    <h5 class="card-title">User Information</h5>
    <p class="mb-0">
        Name: <?= $row["1"];?><br>
        Email ID: <?= $row["2"];?>
    </p>
    <div class="col-12">
        <h3 class="card-title mt-4">Instructions</h3>
        <ul class="mt-3">
            <?php
                $instruction_sql = "SELECT * FROM instructions";
                $instruction_result = mysqli_query($conn, $instruction_sql);
                while ($instruction_row = mysqli_fetch_assoc($instruction_result)) {
                    echo "<li>" . $instruction_row["instructions"] . "</li>";
                }
            ?>
        </ul>
    </div>
    <?php } ?>
    
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Exam: <?= htmlspecialchars($_GET["exam"]) ?></h5>
            <form action="take_eit_exam.php?exam=<?= $_GET["exam"] ?>" method="POST">
                <div id="question-container">
                    <?php
                    $sql_display_questions = "SELECT * FROM eit_questions ORDER BY RAND()";

                    $result_display_questions = mysqli_query($conn, $sql_display_questions);

                    $total_questions = 0;
                    $question_number = 1;
                    $question_type_map = [];
                    $current_title_index = -1;

                    if (mysqli_num_rows($result_display_questions) > 0) {
                        while ($row = mysqli_fetch_assoc($result_display_questions)) {
                                $is_active = $total_questions === 0 ? "d-block" : "d-none";
                                $question_type_map[$total_questions] = $current_title_index;
                                ?>
                                <div class="question-card card mb-3 <?= $is_active ?>" id="question-<?= $total_questions ?>">
                                    <div class="card-body">
                                        <h6 class="card-title mb-3">Q<?= $total_questions + 1 ?>: <?= $row['questions'] ?></h6>
                                        <?php if (!empty($row['question_image'])) : ?>
                                            <img src="Admin/IMG/Questions/<?= $row['question_image'] ?>" 
                                                 alt="Question Image" 
                                                 class="img-fluid mb-3">
                                        <?php endif; ?>
                                        <div class="options">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input options option-question-<?= $row["id"] ?>" type="radio" data-question-id="<?= $row["id"] ?>" data-question="<?= $row["questions"] ?>" value="<?= $row["opt_a"] ?>" name="question_<?= $row["id"] ?>" id="opt_a_question_<?= $row["id"] ?>" required>
                                                <label class="form-check-label" for="opt_a_question_<?= $row["id"] ?>">
                                                    A. 
                                                    <span>
                                                        <?= $row["opt_a"] ?>
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input options option-question-<?= $row["id"] ?>" type="radio" data-question-id="<?= $row["id"] ?>" data-question="<?= $row["questions"] ?>" value="<?= $row["opt_b"] ?>" name="question_<?= $row["id"] ?>" id="opt_b_question_<?= $row["id"] ?>" required>
                                                <label class="form-check-label" for="opt_b_question_<?= $row["id"] ?>">
                                                    B. 
                                                    <span>
                                                        <?= $row["opt_b"] ?>
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input options option-question-<?= $row["id"] ?>" type="radio" data-question-id="<?= $row["id"] ?>" data-question="<?= $row["questions"] ?>" value="<?= $row["opt_c"] ?>" name="question_<?= $row["id"] ?>" id="opt_c_question_<?= $row["id"] ?>" required>
                                                <label class="form-check-label" for="opt_c_question_<?= $row["id"] ?>">
                                                    C. 
                                                    <span>
                                                        <?= $row["opt_c"] ?>
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input options option-question-<?= $row["id"] ?>" type="radio" data-question-id="<?= $row["id"] ?>" data-question="<?= $row["questions"] ?>" value="<?= $row["opt_d"] ?>" name="question_<?= $row["id"] ?>" id="opt_d_question_<?= $row["id"] ?>" required>
                                                <label class="form-check-label" for="opt_d_question_<?= $row["id"] ?>">
                                                    D. 
                                                    <span>
                                                        <?= $row["opt_d"] ?>
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input options option-question-<?= $row["id"] ?>" type="radio" data-question-id="<?= $row["id"] ?>" data-question="<?= $row["questions"] ?>" value="<?= $row["opt_e"] ?>" name="question_<?= $row["id"] ?>" id="opt_e_question_<?= $row["id"] ?>" required>
                                                <label class="form-check-label" for="opt_e_question_<?= $row["id"] ?>">
                                                    E. 
                                                    <span>
                                                        <?= $row["opt_e"] ?>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                $question_number++;
                                $total_questions++;
                            }
                        }
                    ?>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-primary" id="prev-btn" disabled>Previous</button>
                    <button type="button" class="btn btn-primary" id="next-btn">Next</button>
                    <button type="submit" class="btn btn-success d-none" 
                            id="exam-submit-btn"
                            data-exam-id="<?= $exam_id ?>" 
                            data-user-id="<?= $user_id ?>" 
                            data-num-of-questions="<?= $total_questions ?>">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="countdown">
    <?= $duration;?> minutes:00 seconds
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentQuestion = 0;
    const totalQuestions = <?= $total_questions ?>;
    const questionCards = document.querySelectorAll('.question-card');
    const titleHeaders = document.querySelectorAll('.title-header');
    const questionTypeMap = <?= json_encode($question_type_map) ?>;
    const duration = <?= $duration ?>;
    let timeLeft = duration * 60;

    function showQuestion(index) {
        questionCards.forEach(card => card.classList.add('d-none'));
        titleHeaders.forEach(title => title.classList.add('d-none'));

        const currentTitleIndex = questionTypeMap[index];
        if (titleHeaders[currentTitleIndex]) {
            titleHeaders[currentTitleIndex].classList.remove('d-none');
        }

        questionCards[index].classList.remove('d-none');
        document.getElementById('prev-btn').disabled = index === 0;
        document.getElementById('next-btn').classList.toggle('d-none', index === totalQuestions - 1);
        document.getElementById('exam-submit-btn').classList.toggle('d-none', index !== totalQuestions - 1);

        currentQuestion = index;
    }

    function prevQuestion() {
        if (currentQuestion > 0) {
            currentQuestion--;
            showQuestion(currentQuestion);
        }
    }

    function nextQuestion() {
        if (currentQuestion < totalQuestions - 1) {
            currentQuestion++;
            showQuestion(currentQuestion);
        }
    }

    document.getElementById('exam-submit-btn').addEventListener('click', function(e) {
        const unanswered = [];
        questionCards.forEach((card, index) => {
            const questionId = card.querySelector('input[type="radio"]').dataset.questionId;
            const answered = document.querySelector(`input[name="question_${questionId}"]:checked`);
            
            if (!answered) {
                unanswered.push(index + 1);
            }
        });

        if (unanswered.length > 0) {
            alert(`Please answer all questions. Missing questions: ${unanswered.join(', ')}`);
            showQuestion(unanswered[0] - 1);
            return;
        }

        if (confirm('Are you sure you want to submit the exam?')) {
            document.querySelector('form').submit();
        }
    });

    function startTimer() {
        const countdownElement = document.getElementById('countdown');
        const timer = setInterval(() => {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            
            countdownElement.textContent = 
                `${minutes} minute${minutes !== 1 ? 's' : ''} : ${seconds.toString().padStart(2, '0')} second${seconds !== 1 ? 's' : ''}`;
            
            if (timeLeft <= 0) {
                clearInterval(timer);
                document.querySelector('form').submit();
            }
            
            timeLeft--;
        }, 1000);
    }

    document.getElementById('prev-btn').addEventListener('click', prevQuestion);
    document.getElementById('next-btn').addEventListener('click', nextQuestion);

    document.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowLeft') {
            prevQuestion();
        } else if (e.key === 'ArrowRight') {
            nextQuestion();
        }
    });

    document.querySelectorAll('input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const questionCard = this.closest('.question-card');
            if (questionCard) {
                questionCard.classList.add('answered');
            }
        });
    });

    showQuestion(0);
    startTimer();
});
</script>

<?php include "Includes/footer.php"; ?>