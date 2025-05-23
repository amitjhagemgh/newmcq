<footer class="footer mt-auto py-3 bg-body-tertiary text-center" id="footer">
    <div class="container">
        <span>
            Copyright © at
            <?php echo date("Y");?> as <a href="https://gemengserv.com" target="_blank">gemengserv.com</a> All rights reserved.
        </span>
        <!-- <a href="<?php echo 'Admin/Authentication/login.php';?>" target="_blank" class="<?php if(isset($_SESSION['is_login'])) {echo " d-none";}?>"><small>Admin Login</small></a> -->
    </div>
</footer>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <!-- Include html2pdf library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <!-- <script src="https://unpkg.com/jspdf@latest/dist/jspdf.umd.min.js"></script> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/2.3.6/purify.min.js"></script> Add dompurify -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/canvg@4.0.2/+esm"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" 
        integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ" 
        crossorigin="anonymous">
    </script>
    <!-- <script src="jQueryPlugins/jQuery-Plugin-Resizable-Table-Columns/jQuery.resizableColumns.js"></script> -->
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css"/>
    <!-- <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script> -->
    <script src="https://kit.fontawesome.com/35f8a6764d.js" crossorigin="anonymous"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/@coreui/coreui@5.3.1/dist/js/coreui.bundle.min.js" integrity="sha384-8QmUFX1sl4cMveCP2+H1tyZlShMi1LeZCJJxTZeXDxOwQexlDdRLQ3O9L78gwBbe" crossorigin="anonymous"></script> -->
    <!-- SELECT2 JavaScript CDN-->
    <!-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.0/dist/jquery.slim.min.js"></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- colResize -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/datatables.net-colresize-unofficial@latest/jquery.dataTables.colResize.js"></script> -->
    <!-- Drag and Resize HTML Table Columns - jQuery resizable-columns -->
    <!-- <script src="jQueryPlugins/drag-resize-columns/dist/jquery.resizableColumns.min.js"></script> -->
    <script src="JS/script.js"></script>
    <?php if(PAGE == "Result") { ?>
        <script>
            resultPage();
            downloadAsPDF();
        </script>
    <?php }?>
    <?php if(PAGE == "Dashboard") { ?><script>toggleExamStatus();dashboardSearchingFeature();</script><?php }?>
    <?php if(PAGE == "Questions") { ?><script>questionsPage();</script><?php }?>
    <?php if(PAGE == "Users") { ?><script>usersPage();</script><?php }?>
    <?php if(PAGE == "MCQ Questions" || PAGE == "Question Bank") { ?><script>mcqQuestions();</script><?php }?>
    <?php if(PAGE == "MCQ Questions" || PAGE == "Question Bank") { ?><script>questionBank();</script><?php }?>
    <?php if(PAGE == "Personality Type Test Result") { ?><script>resultPage();</script><?php }?>
    <?php
        if(PAGE=="Personality Type Test Result") {
    ?>
            <script>
                resultPage();
                let startRow = 1;
                let startColumn = 1;
                addTableToSheet('result-calculation-table', startRow, startColumn);
                addTableToSheet('result-values-1', startRow + 13, startColumn + 1);
                addTableToSheet('result-values-2', startRow + 13, startColumn + 4);
                addTableToSheet('result-values-3', startRow + 13, startColumn + 7);
                addTableToSheet('result-values-4', startRow + 13, startColumn + 10);
            </script>
    <?php
        }
        if(PAGE == "All Results") {
            ?>
            <script>
                let deleteResultBtnElements = document.querySelectorAll(".delete-result-btn");
                let deleteEmailIdElement = document.getElementById("delete_email_id");
                let deleteExamNameElement = document.getElementById("delete_exam_name");
                let deleteTestSeriesElement = document.getElementById("delete_test_series");
                Array.from(deleteResultBtnElements).forEach(e => {
                    // console.log(e);
                    // console.log(e.dataset.id);
                    // console.log(e.dataset.exam_name);
                    // console.log(e.dataset.testSeries);
                    e.addEventListener("click", (e) => {
                        deleteEmailIdElement.value = e.target.dataset.id;
                        deleteExamNameElement.value = e.target.dataset.exam_name;
                        deleteTestSeriesElement.value = e.target.dataset.testSeries;
                    });
                })
            </script>
<?php
} elseif(PAGE == "Question Bank") {
?>
<script>
  let count = 1;
  let isLoading = false;

  $(function(){
    $(window).off('scroll.questionBank')
             .on('scroll.questionBank', function() {
      if (isLoading) return;
      if ($(window).scrollTop() + $(window).height() >= $(document).height() - 1000) {
        isLoading = true;
        // console.log("Loading...", count);
        // $("body").append("<div>Loading...<span style='display:inline-block;height:10px;width:10px;background-color:#ff0000;'></span></div>");

        $.ajax({
          url: "Ajax/get_questions.php",
          type: "GET",
          data: {
            draw: 1,
            start: count,
            length: 10,
            search: { value: "a", regex: false }
          },
          success: function(data) {
            let questionData = JSON.parse(data);
            console.log("Data received:", questionData);
            // Agar questions khatam
            if (questionData.data.length === 0) {
              console.log("No more questions to load");
              $(window).off('scroll.questionBank');
              return;
            }

            // Naya data append karo ya DataTable re-init
            $('#question-bank-table').DataTable().destroy();
            for(let i = 0; i < questionData.data.length; i++) {
                let question = questionData.data[i].questions;
                let questionImage = questionData.data[i].question_image;
                let questionId = questionData.data[i].question_id;
                let questionType = questionData.data[i].question_type;
                let questionUniqueId = questionData.data[i].question_unique_id;
                let questionStatus = questionData.data[i].status;
                let questionAnswer = questionData.data[i].answer;
                let questionOptions = questionData.data[i].options;
                
                let questionDataHtml = `
                    <tr>
                        <th class="main-question-table-series"></th>
                        <td>
                            <button type="button" class="btn btn-primary individual-edit-question" data-bs-toggle="modal" data-bs-target="#questionEditModal">Edit</button>
                            <span class="d-none">
                                <div class="mb-2">
                                    <strong>id: </strong>
                                    <span>${questionId}</span>
                                </div>
                                <div class="mb-2">
                                    <strong>questions: </strong>
                                    <span>${question}</span>
                                </div>
                                <div class="mb-2">
                                    <strong>question image: </strong>
                                    <span>${questionImage}</span>
                                </div>
                                <div class="mb-2">
                                    <strong>question type: </strong>
                                    <span>${questionType}</span>
                                </div>
                                <div class="mb-2">
                                    <strong>question id: </strong>
                                    <span>${questionUniqueId}</span>
                                </div>`;
                        // console.log(questionData.data[i].options);
                        for(let j = 0; j < questionData.data[i].options.length; j++) {
                            questionDataHtml += `
                                <div class="mb-2">
                                    <strong class="options" data-is-correct="${questionData.data[i].options[j].is_correct?1:0}">option: </strong>
                                    <span>${questionData.data[i].options[j].text}</span>
                                </div>
                            `;
                        }
                    questionDataHtml += `
                            </span>
                        </td>
                        <td><button type="button" class="btn btn-danger individual-delete-question" data-bs-toggle="modal" data-bs-target="#questionDeleteModal">Delete</button></td>
                        <td class='text-center'>
                            <input type="checkbox" class="add-to-quiz d-none" id="add-to-quiz-${questionId}" name="add-to-quiz-${questionId}">
                            <label for="add-to-quiz-${questionId}" class="w-100">
                                <i class="fa-regular fa-circle" aria-hidden="true"></i>
                            </label>
                            <label for="add-to-quiz-${questionId}" class="w-100">
                                <i class="fa-regular fa-circle-check text-success" aria-hidden="true"></i>
                            </label>
                        </td>
                        <td>${questionUniqueId}</td>
                        <td class="assign-topics">`;
                        for(let j = 0; j < questionData.data[i].all_topics.length; j++) {
                            if(questionData.data[i].all_topics[j][2] == 1) {
                                questionDataHtml += `
                                <div class='assigned-topics p-1'>
                                    <button class="btn toggle-status" data-value="0" data-question-id="${questionId}" data-topic-id=${questionData.data[i].all_topics[j][0]} data-topic="${questionData.data[i].all_topics[j][1]}">${questionData.data[i].all_topics[j][1]}</button>
                                    <span class="bg-danger rounded-circle text-white remove-topic">X</span>
                                </div>`;
                            }
                        }
                        questionDataHtml += `
                            <div class="card d-none" style="width: 18rem;">
                            <div class="card-body">
                            <input type="text" class="form-control search-topic" name="search-topic" placeholder="Search for Topic...">
                            <div class="topic-container mt-2">`;
                            // console.log(questionData.data[i].all_topics);
                            for(let j = 0; j < questionData.data[i].all_topics.length; j++) {
                                questionDataHtml += `
                                <div>
                                    <input type="checkbox" class="form-check-input toggle-topics-assignment" data-question-id=${questionId} data-topic-id=${questionData.data[i].all_topics[j][0]} data-topic="${questionData.data[i].all_topics[j][1]}" data-value="0" ${questionData.data[i].all_topics[j][2] == 1?"checked":""} />
                                    <p class="card-title d-inline-block mx-2">${questionData.data[i].all_topics[j][1]}</p>
                                </div>
                                `;
                            }
                        questionDataHtml += `
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="assign-main-groups">`;
                        for(let j = 0; j < questionData.data[i].all_main_groups.length; j++) {
                            if(questionData.data[i].all_main_groups[j][2] == 1) {
                                questionDataHtml += `
                                <div class='assigned-main-groups p-1'>
                                    <button class="btn toggle-status" data-value="0" data-question-id="${questionId}" data-main-group-id=${questionData.data[i].all_main_groups[j][0]} data-main-group="${questionData.data[i].all_main_groups[j][1]}">${questionData.data[i].all_main_groups[j][1]}</button>
                                    <span class="bg-danger rounded-circle text-white remove-main-group">X</span>
                                </div>`;
                            }
                        }
                        questionDataHtml += `
                            <div class="card d-none" style="width: 18rem;">
                            <div class="card-body">
                            <input type="text" class="form-control search-main-group" name="search-main-group" placeholder="Search for Main Group...">
                            <div class="main-group-container mt-2">`;
                            // console.log(questionData.data[i].all_topics);
                            for(let j = 0; j < questionData.data[i].all_main_groups.length; j++) {
                                questionDataHtml += `
                                <div>
                                    <input type="checkbox" class="form-check-input toggle-main-groups-assignment" data-question-id=${questionId} data-main-group-id=${questionData.data[i].all_main_groups[j][0]} data-main-group="${questionData.data[i].all_main_groups[j][1]}" data-value="0" ${questionData.data[i].all_main_groups[j][2] == 1?"checked":""} />
                                    <p class="card-title d-inline-block mx-2">${questionData.data[i].all_main_groups[j][1]}</p>
                                </div>
                                `;
                            }
                        questionDataHtml += `
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="assign-sub-groups">`;
                        for(let j = 0; j < questionData.data[i].all_sub_groups.length; j++) {
                            if(questionData.data[i].all_sub_groups[j][2] == 1) {
                                questionDataHtml += `
                                <div class='assigned-sub-groups p-1'>
                                    <button class="btn toggle-status" data-value="0" data-question-id="${questionId}" data-sub-group-id=${questionData.data[i].all_sub_groups[j][0]} data-sub-group="${questionData.data[i].all_sub_groups[j][1]}">${questionData.data[i].all_sub_groups[j][1]}</button>
                                    <span class="bg-danger rounded-circle text-white remove-sub-group">X</span>
                                </div>`;
                            }
                        }
                        questionDataHtml += `
                            <div class="card d-none" style="width: 18rem;">
                            <div class="card-body">
                            <input type="text" class="form-control search-sub-group" name="search-sub-group" placeholder="Search for Sub Group...">
                            <div class="sub-group-container mt-2">`;
                            // console.log(questionData.data[i].all_topics);
                            for(let j = 0; j < questionData.data[i].all_sub_groups.length; j++) {
                                questionDataHtml += `
                                <div>
                                    <input type="checkbox" class="form-check-input toggle-sub-groups-assignment" data-question-id=${questionId} data-sub-group-id=${questionData.data[i].all_sub_groups[j][0]} data-sub-group="${questionData.data[i].all_sub_groups[j][1]}" data-value="0" ${questionData.data[i].all_sub_groups[j][2] == 1?"checked":""} />
                                    <p class="card-title d-inline-block mx-2">${questionData.data[i].all_sub_groups[j][1]}</p>
                                </div>
                                `;
                            }
                        questionDataHtml += `
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>${questionData.data[i].questions}
                            ${questionData.data[i].question_image != "" ? `<img src="IMG/Questions/${questionData.data[i].question_image}" alt="Question Image" class="question-image mb-2">` : ''}
                            </div>`;
                            let newOptionCount = parseInt([...document.querySelectorAll("input[id^=\"options\"]")].pop().getAttribute("id").split("-").pop());
                            for(let j = 0; j < questionData.data[i].options.length; j++) {
                                newOptionCount++;
                                questionDataHtml += `
                                    <div class="d-flex">
                                        <input type="checkbox" name="options[]" id="options-${newOptionCount}" value="${questionData.data[i].options[j].text}" ${questionData.data[i].options[j].is_correct == 1 ? "checked" : ""} />
                                        <label for="options-${newOptionCount}" class="w-100 ms-2" data-question-id="${questionId}" data-option="${questionData.data[i].options[j].text}" data-question-type="${questionData.data[i].question_type}">${questionData.data[i].options[j].text}</label>
                                    </div>
                                `;
                            }
                        questionDataHtml += `</td>
                        <td>${questionData.data[i].correct_count}</td>
                        <td>${questionData.data[i].attempt_count}</td>
                        <td>${questionData.data[i].percentage}</td>
                        <td>${questionData.data[i].difficulty}</td>
                    </tr>
                `;
                // Append the new data to the table
                $('#question-bank-table tbody').append(questionDataHtml);
                // console.log(questionData.data[i].questions);
            }
            Array.from(document.querySelectorAll(".main-question-table-series")).forEach((e, i) => {
                e.innerHTML = i + 1;
            });
            $('#question-bank-table').DataTable(dataTableData);
            // let individualEditQuestionFunVar = individualEditQuestionFun();
            // Array.from(document.querySelectorAll(".individual-edit-question")).forEach(element => {
            //     element.removeEventListener("click", individualEditQuestionFunVar);
            // })
            mcqQuestions();
            questionBank();
            count += 10;
            $('[data-resizable-column-id="sr_no"]').click();
            $('[data-resizable-column-id="sr_no"]').click();
          },
          error: function(xhr, status, error) {
            console.error("Error loading more questions:", error);
          },
          complete: function() {
            isLoading = false;
          }
        });
      }
    });
  });
</script>
<?php
}
?>


</body>

</html>