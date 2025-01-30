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
            Eamil ID:
            <?= $row["2"];?>
        </div>
        <div class="col-12">
            <ul class="mt-4">
            <h3>Instructions:</h3>
            <?php
                $instruction_sql = "SELECT * FROM instructions";
                $instruction_result = mysqli_query($conn, $instruction_sql);
                while($instruction_row = mysqli_fetch_assoc($instruction_result)) {
                    echo "<li>" . $instruction_row["instructions"] . "</li>";
                }
            ?>
        </ul>
        </div>
    </div>
    <?php }
    ?>
    <div class="card mt-3">
        <div class="card-body">
            <h5 class="card-title">Exam: <?= htmlspecialchars($_GET["exam"]) ?></h5>
            <form action="take_exam.php?exam=<?php echo $_GET["exam"]; ?>" method="POST">
            <div id="question-container">
                <?php
                $sql_display_questions = "SELECT q.questions AS questions, q.id AS id, q.question_image AS question_image, q.question_type AS question_type FROM questions AS q JOIN exam_portal AS ep ON q.exam_id = ep.id WHERE ep.id = '$exam_id'";
                $result_display_questions = mysqli_query($conn, $sql_display_questions);

                $questions = [];
                if (mysqli_num_rows($result_display_questions) > 0) {
                    while ($row = mysqli_fetch_assoc($result_display_questions)) {
                        $questions[] = $row;
                    }
                }

                $i = 1;
                foreach ($questions as $index => $row) {
                    $is_active = $index === 0 ? "d-block" : "d-none";
                    // array_output($row);
                    ?>
                    <div class="question-card <?= $is_active ?>" id="question-<?= $index ?>">
                    <input type="hidden" name="all_questions_attempted[]" value="<?= str_replace(array("\\r", "\\n"), "", get_safe_value($conn, $row["questions"]));?>" data-check="">
                    <input type="hidden" name="all_questions_attempted_id[]" value="<?= str_replace(array("\\r", "\\n"), "", get_safe_value($conn, $row["id"]));?>" data-check="">
                        <h6 class="question-text"><span class="<?= ($row["question_type"] == "title")?"d-none":""?>">Q<?= ($row["question_type"] == "title")?--$i:$i?>: </span><?= $row["questions"] ?></h6>
                        <img src="Admin/IMG/Questions/<?= $row["question_image"] ?>" alt="Question Image" class="img-fluid <?= empty($row["question_image"]) ? 'd-none' : '' ?>">
                        <div class="options">
                            <?php
                            $option_sql = "SELECT o.id AS id, o.answers AS options, o.is_correct AS is_correct FROM options AS o WHERE o.question_id = '{$row['id']}'";
                            $option_result = mysqli_query($conn, $option_sql);

                            $i++;
                            $option_char = ord("A");
                            while ($option = mysqli_fetch_assoc($option_result)) {
                                $option_no = 1;
                                $sr_no = 1;
                                ?>
                                <div class="form-check">
                                <input type="<?= $row["question_type"] == "single" ? "radio" : "checkbox"; ?>" data-question-id="<?= $row["id"];?>" data-question="<?= str_replace(array("\\r", "\\n"), "", get_safe_value($conn, $row["questions"]));?>" class="options option-question-<?php echo $row["id"];?>" name="question_<?php echo $row["id"];?><?= $row["question_type"] == "single" ? "" : "[]"; ?>" value="<?= $option["id"];?>" id="opt_<?php echo chr($option_char + 32) . "_" . $option_no . "question_" . $row["id"];?>">
                                <label for="opt_<?php echo chr($option_char + 32) . "_" . $option_no . "question_" . $row["id"];?>" class="ms-2 option-labels"><?php
                                ?>
                                <?php echo chr($option_char) . ". <span data-question-id='" . $row["id"] . "' data-option-id='" . $option["id"] . "' data-question-series='" . $sr_no . "' question-type='" . $row["question_type"] . "'>" . $option["options"] . "</span>";?>
                            </label>
                                </div>
                                <?php
                                $option_no++;
                                $option_char++;
                                $sr_no++;
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>

            <div class="d-flex justify-content-between mt-3">
                <button type="button" class="btn btn-primary" id="prev-btn" onclick="prevQuestion()" disabled>Previous</button>
                <button type="button" class="btn btn-primary" id="next-btn" onclick="nextQuestion()">Next</button>
                <input type="button" value="Submit" class="btn btn-success d-none" name="exam-submit" id="exam-submit" data-exam-id="<?= $exam_id;?>" data-user-id="<?= $user_id;?>" data-num-of-questions="<?= $sr_no - 1;?>">
            </div>
            </form>
        </div>
    </div>
</div>
<div id="countdown" class="<?php if(mysqli_num_rows($result_display_questions) == 0) {?>d-none<?php }?><?php if($_SERVER["REQUEST_METHOD"] == "POST") {?>d-none<?php } ?>"><?= $duration;?> minutes:00 seconds</div>
<script>
    let currentQuestion = 0;
    const totalQuestions = <?= count($questions) ?>;

    function showQuestion(index) {
        document.querySelectorAll('.question-card').forEach((card, i) => {
            card.classList.toggle('d-block', i === index);
            card.classList.toggle('d-none', i !== index);
        });

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

    document.getElementById('submit-btn').addEventListener('click', function () {
        alert('Exam Submitted!');
        // Here, you can submit the form via AJAX or redirect to a submission page.
    });

    showQuestion(currentQuestion);
</script>

<?php
    include "Includes/footer.php";
?>
