<?php
    define("PAGE", "MCQ Exam");
    define("TYPE", "");
    include "Connection/conn.inc.php";
    include "Includes/header.php";
    include "Includes/functions.inc.php";

    if (!isset($_SESSION["is_login"])) {
        header("location: Authentication/login.php");
    }

    if (!isset($_GET["exam"])) {
        header("location: index.php");
        die;
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
?>

<div class="container my-3 min-height-100-vh">
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
    <div class="row">
        <div class="col-12">
            Name:
            <?= $row["1"];?><br />
            Email ID:
            <?= $row["2"];}?>
        </div>
        <div class="col-12">
            <ul class="mt-4">
                <h3>Instructions:</h3>
                <?php
                    $instruction_sql = "SELECT * FROM instructions";
                    $instruction_result = mysqli_query($conn, $instruction_sql);
                    while ($instruction_row = mysqli_fetch_assoc($instruction_result)) {
                        echo "<li>" . $instruction_row["instructions"] . "</li>";
                    }
                ?>
            </ul>
        </div>
    </div>
    
    <div class="card mt-3">
        <div class="card-body">
            <h5 class="card-title">Exam: <?= htmlspecialchars($_GET["exam"]) ?></h5>
            <form action="take_exam.php?exam=<?= $_GET["exam"] ?>" method="POST">
                <div id="question-container">
                    <?php
                    $sql_display_questions = "SELECT q.id, q.questions, q.question_image, q.question_type 
                        FROM questions AS q 
                        JOIN exam_portal AS ep ON q.exam_id = ep.id 
                        WHERE ep.id = '$exam_id' 
                        ORDER BY q.id";
                    $result_display_questions = mysqli_query($conn, $sql_display_questions);

                    $total_questions = 0;
                    $question_number = 1;
                    $question_type_map = [];
                    $current_title_index = -1;

                    if (mysqli_num_rows($result_display_questions) > 0) {
                        while ($row = mysqli_fetch_assoc($result_display_questions)) {
                            if ($row['question_type'] == 'title') {
                                $current_title_index++;
                                ?>
                                <div class="title-header d-none" id="title-<?= $current_title_index ?>">
                                    <h4 class="mt-4 mb-3"><?= $row['questions'] ?></h4>
                                </div>
                                <?php
                            } else {
                                $is_active = $total_questions === 0 ? "d-block" : "d-none";
                                $question_type_map[$total_questions] = $current_title_index;
                                ?>
                                <div class="question-card <?= $is_active ?>" id="question-<?= $total_questions ?>">
                                    <h6>Q<?= $total_questions + 1 ?>: <?= $row['questions'] ?></h6>
                                    <img src="Admin/IMG/Questions/<?= $row['question_image'] ?>" 
                                         alt="Question Image" 
                                         class="img-fluid <?= empty($row['question_image']) ? 'd-none' : '' ?>">
                                    <div class="options">
                                        <?php
                                        $option_sql = "SELECT o.id AS id, o.answers AS options 
                                                       FROM options AS o 
                                                       WHERE o.question_id = '{$row['id']}'";
                                        $option_result = mysqli_query($conn, $option_sql);

                                        $option_char = ord("A");
                                        while ($option = mysqli_fetch_assoc($option_result)) {
                                            ?>
                                            <div class="form-check">
                                                <input type="radio" 
                                                        data-question-id="<?= $row["id"] ?>"
                                                        data-question="<?= $row["questions"] ?>"
                                                        class="options option-question-<?= $row["id"] ?>"
                                                        value="<?= $option["id"] ?>" 
                                                        name="question_<?= $row["id"] ?>"
                                                       id="opt_<?= chr($option_char + 32) ?>_question_<?= $row["id"] ?>">
                                                <label for="opt_<?= chr($option_char + 32) ?>_question_<?= $row["id"] ?>">
                                                <?= chr($option_char) ?>. 
                                                    <span data-question-id="<?= $row["id"] ?>"
                                                          data-option-id="<?= $option["id"] ?>"
                                                          data-question-series="<?= $question_number ?>"
                                                          data-question-type="<?= $row["question_type"] ?>">
                                                        <?= $option["options"] ?>
                                                    </span>
                                                </label>
                                            </div>
                                            <?php
                                            $option_char++;
                                        }
                                        ?>
                                    </div>
                                </div>
                                <?php
                                $question_number++;
                                $total_questions++;
                            }
                        }
                    }
                    ?>
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <button type="button" class="btn btn-primary" id="prev-btn" onclick="prevQuestion()" disabled>Previous</button>
                    <button type="button" class="btn btn-primary" id="next-btn" onclick="nextQuestion()">Next</button>
                    <input type="button" value="Submit" class="btn btn-success d-none" 
                           name="exam-submit" id="exam-submit" 
                           data-exam-id="<?= $exam_id ?>" 
                           data-user-id="<?= $user_id ?>" 
                           data-num-of-questions="<?= $total_questions ?>">
                </div>
            </form>
        </div>
    </div>
</div>
<div id="countdown" class="<?php if(mysqli_num_rows($result_display_questions) == 0) {?>d-none<?php }?><?php if($_SERVER["REQUEST_METHOD"] == "POST") {?>d-none<?php } ?>"><?= $duration;?> minutes:00 seconds</div>
<script>
    let currentQuestion = 0;
    const totalQuestions = <?= $total_questions ?>;
    const questionCards = document.querySelectorAll('.question-card');
    const titleHeaders = document.querySelectorAll('.title-header');
    const questionTypeMap = <?= json_encode($question_type_map) ?>;

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
        document.getElementById('exam-submit').classList.toggle('d-none', index !== totalQuestions - 1);
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

    showQuestion(currentQuestion);
</script>

<?php include "Includes/footer.php"; ?>
