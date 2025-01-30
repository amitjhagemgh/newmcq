<?php
    define("PAGE", "MCQ Exam");
    define("TYPE", "");
    include "Connection/conn.inc.php";
    include "Includes/header.php";
    include "Includes/functions.inc.php";

    if (!isset($_SESSION["is_login"])) {
        header("location: Authentication/login.php");
        exit;
    }

    if (!isset($_GET["exam"])) {
        header("location: index.php");
        exit;
    }

    // Get exam details
    $exam_name = $_GET['exam'];
    $exam_id_sql = "SELECT * FROM exam_portal WHERE exam_name = '" . mysqli_real_escape_string($conn, $exam_name) . "'";
    $exam_id_result = mysqli_query($conn, $exam_id_sql);
    $exam = mysqli_fetch_assoc($exam_id_result);

    if (!$exam) {
        echo "<p>Invalid exam!</p>";
        exit;
    }

    $exam_id = $exam['id'];
    $duration = $exam['duration'];

    // Get user details
    $user_sql = "SELECT * FROM users WHERE email_id = '" . $_SESSION['email_id'] . "'";
    $user_result = mysqli_query($conn, $user_sql);
    $user = mysqli_fetch_assoc($user_result);
    $user_id = $user['id'];

    // Fetch all questions
    $questions_sql = "SELECT * FROM opinion_questions WHERE exam_id = '$exam_id'";
    $questions_result = mysqli_query($conn, $questions_sql);
    $questions = mysqli_fetch_all($questions_result, MYSQLI_ASSOC);
    $total_questions = count($questions);

    $test_series_sql = "SELECT test_series FROM opinion_result WHERE user_id = '$user_id' AND exam_id = '$exam_id' ORDER BY id DESC LIMIT 1";
    $test_series_result = mysqli_query($conn, $test_series_sql);
    if (mysqli_num_rows($test_series_result) == 0) {
        $test_series = 0;
    } else {
        $test_series = (int)mysqli_fetch_assoc($test_series_result)["test_series"];
    }

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $keys = array_keys($_POST);
        $values = array_values($_POST);
        // pre_post();
        $sql = "INSERT INTO opinion_result(user_id, question, answer, exam_id, test_series) VALUES ";
        for($i = 0; $i < count($keys); $i++) {
            if($keys[$i] == "exam-submit") {
                continue;
            }
    // $user_id = $row["id"];
            $sql .= "('$user_id', '$keys[$i]', '" . get_safe_value($conn, $values[$i]) . "', '$exam_id', '" . (int)$test_series + 1 . "'),";
        }
        $sql = substr($sql, 0, -1);
        if(sizeof($values) == 1) {
            $sql .= "('$user_id', '', '', '$exam_id', '" . (int)$test_series + 1 . "');";
        }
        // echo $sql;
        // die;
        $result = mysqli_query($conn, $sql);
        if($result) {
            // header("location: all_quizes.php");
            echo "<script>location.href = 'all_quizes.php';</script>";
            die;
        }
    } else {
        // if(isset($_SESSION["exam_id"]) && isset($_SESSION["user_id"])) {
        //     $sql = "DELETE FROM user_exam_mapping WHERE user_id = '$_SESSION[user_id]' AND exam_id = '$_SESSION[exam_id]'";
        //     $result = mysqli_query($conn, $sql);
        //     header("location: Authentication/logout.php");
        //     die;
        // }
        // $_SESSION["exam_id"] = $exam_id;
        // $_SESSION["user_id"] = $user_id;
    }
?>

<div class="container my-3 min-height-100-vh">
    <div class="row">
        <div class="col-12">
            <div>Name: <?= htmlspecialchars($user['name']) ?></div>
            <div>Email: <?= htmlspecialchars($user['email_id']) ?></div>
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
            <h5>Exam: <?= htmlspecialchars($exam_name) ?></h5>
            <form action="take_opinion_exam.php?exam=<?= $_GET["exam"] ?>" method="POST" id="exam-form" novalidate>
                <div id="question-container">
                    <?php foreach ($questions as $index => $question): ?>
                        <div class="question-card <?= $index === 0 ? 'd-block' : 'd-none' ?>" id="question-<?= $index ?>">
                            <h6>Q<?= $index + 1 ?>: <?= htmlspecialchars($question['questions']) ?></h6>
                            <div class="options mt-3">
                                <div class="form-check">
                                    <input type="radio" name="question_<?= $question["id"] ?>" value="Option A" id="opt_a_<?= $question["id"] ?>" required>
                                    <label for="opt_a_<?= $question["id"] ?>" class="ms-2">
                                        A. <?= htmlspecialchars($question['opt_a']) ?>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" name="question_<?= $question["id"] ?>" value="Option B" id="opt_b_<?= $question["id"] ?>" required>
                                    <label for="opt_b_<?= $question["id"] ?>" class="ms-2">
                                        B. <?= htmlspecialchars($question['opt_b']) ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-primary" id="prev-btn" onclick="prevQuestion()" disabled>Previous</button>
                    <button type="button" class="btn btn-primary" id="next-btn" onclick="nextQuestion()">Next</button>
                    <input type="submit" value="Submit" class="btn btn-success d-none" name="exam-submit" id="exam-submit">
                </div>
            </form>
        </div>
    </div>
</div>
<?php
    $result_display_questions = mysqli_query($conn, "SELECT * FROM opinion_questions WHERE exam_id = '$exam_id'");
?>

<div id="countdown" class="<?php if(mysqli_num_rows($result_display_questions) == 0) {?>d-none<?php }?><?php if($_SERVER["REQUEST_METHOD"] == "POST") {?>d-none<?php } ?>"><?= $duration;?> minutes:00 seconds</div>

<script>
    let currentQuestion = 0;
    const totalQuestions = <?= $total_questions ?>;
    const questionCards = document.querySelectorAll('.question-card');

    function showQuestion(index) {
        questionCards.forEach(card => card.classList.add('d-none'));
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
    console.log(totalQuestions);
    document.getElementById('exam-submit').addEventListener('click', function (e) {
        if(document.querySelectorAll("input[type='radio'][required]").length > 0) {
            let remainingQuestions = [];
            let allAnswered = true;
            questionCards.forEach((card, index) => {
                const radios = card.querySelectorAll('input[type="radio"]');
                const isAnswered = Array.from(radios).some(radio => radio.checked);

                if (!isAnswered) {
                    allAnswered = false;
                    card.classList.remove('d-none');
                    if(remainingQuestions.length > 0) {
                        remainingQuestions.push(" Q." + (index + 1));
                    } else {
                        remainingQuestions.push("Q." + (index + 1));
                    }
                    currentQuestion = index;
                    showQuestion(index);
                }
            });
            
            if (!allAnswered) {
                alert(`Please answer question${remainingQuestions.length > 1 ? "s" : ""} ${remainingQuestions}`);
            }
            if(remainingQuestions.length < totalQuestions) {
                e.preventDefault();
            }
        }
    });

    showQuestion(currentQuestion);
</script>

<?php include "Includes/footer.php"; ?>
