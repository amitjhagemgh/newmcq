let agreeToUpdate = true;
// import { jsPDF } from "https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.es.min.js";
function dashboardSearchingFeature() {
    let sectionSelectorElement = document.getElementById("section-selector");
    let searchInputElement = document.getElementById("search");
    let allCards = document.querySelectorAll(".card");
    let searchIn = ""
    sectionSelectorElement.addEventListener("change", () => {
        searchIn = sectionSelectorElement.value;
    });
    searchInputElement.addEventListener("input", () => {
        let searchValue = searchInputElement.value.toLowerCase();
        if (searchValue === "") {
            Array.from(allCards).forEach((e, i) => {
                e.parentElement.classList.remove("d-none");
                // if (e.querySelector(".status").textContent.toLowerCase().includes(searchValue)) {
                //     e.parentElement.classList.remove("d-none");
                // }
                // if (e.querySelector(".exam-name").textContent.toLowerCase().includes(searchValue)) {
                //     e.parentElement.classList.remove("d-none");
                // }
                // if (e.querySelector(".duration").textContent.toLowerCase().includes(searchValue)) {
                //     e.parentElement.classList.remove("d-none");
                // }
            });
        } else {
            Array.from(allCards).forEach((e, i) => {
                e.parentElement.classList.add("d-none");
            });
            if (searchIn !== "") {
                Array.from(allCards).forEach((e, i) => {
                    if (e.querySelector("." + searchIn).textContent.toLowerCase().includes(searchValue)) {
                        e.parentElement.classList.remove("d-none");
                    }
                });
            } else {
                Array.from(allCards).forEach((e, i) => {
                    e.parentElement.classList.add("d-none");
                    if (e.querySelector(".status").textContent.toLowerCase().includes(searchValue)) {
                        e.parentElement.classList.remove("d-none");
                    }
                    if (e.querySelector(".exam-name").textContent.toLowerCase().includes(searchValue)) {
                        e.parentElement.classList.remove("d-none");
                    }
                    if (e.querySelector(".duration").textContent.toLowerCase().includes(searchValue)) {
                        e.parentElement.classList.remove("d-none");
                    }
                });
            }
        }
    });
}
function resultPage() {
    let srNo = document.querySelectorAll(".sr_no");
    Array.from(srNo).forEach((e, i) => {
        e.innerHTML = i + 1;
    });

}
if (document.querySelector("#all-user-table")) {
    if (!document.querySelector("#all-user-table").innerHTML.includes("No users found.")) {
        var table = $('#all-user-table').DataTable({
            pageLength: 10, // Set default number of rows to display
            scrollX: true,
            paging:false,
            initComplete: function () {
                // Check if table needs scroll
                var scrollCheck = function () {
                    var scrollWidth = $('.dataTables_scroll')[0];
                    var width = $('.dataTables_scroll').width();
                    $('.dataTables_scroll').toggleClass('has-scroll', scrollWidth > width);
                };

                // Initial check
                scrollCheck();

                // Check on window resize
                $(window).on('resize', scrollCheck);
            },
            lengthMenu: [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
            columns: [
                null, // For Sr. No. (auto-detected)
                { "data": "name" }, // Name
                { "data": "email_id" }, // Email ID
                { "data": "assign_quizzes" }, // Exam Name
                null,  // For Edit button (auto-detected)
                null  // For Delete button (auto-detected)
            ]
        });
    }
}
if (document.querySelector("#result-table")) {
    if (!document.querySelector("#result-table").innerHTML.includes("No results found.")) {
        var table = $('#result-table').DataTable({
            pageLength: 10, // Set default number of rows to display
            scrollX: true,
            initComplete: function () {
                // Check if table needs scroll
                var scrollCheck = function () {
                    var scrollWidth = $('.dataTables_scroll')[0];
                    var width = $('.dataTables_scroll').width();
                    $('.dataTables_scroll').toggleClass('has-scroll', scrollWidth > width);
                };

                // Initial check
                scrollCheck();

                // Check on window resize
                $(window).on('resize', scrollCheck);
            },
            lengthMenu: [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
            columns: [
                null, // For Sr. No. (auto-detected)
                { "data": "name" }, // Name
                { "data": "email_id" }, // Email ID
                { "data": "exam_name" }, // Exam Name
                { "data": "score" }, // Score
                { "data": "exam_attended_time" },
                null  // For View button (auto-detected)
            ]
        });
    }
}
if (document.querySelector("#opinion-result")) {
    if (!document.querySelector("#opinion-result").innerHTML.includes("No results found.")) {
        var table = $('#opinion-result').DataTable({
            pageLength: 10, // Set default number of rows to display
            scrollX: true,
            initComplete: function () {
                // Check if table needs scroll
                var scrollCheck = function () {
                    var scrollWidth = $('.dataTables_scroll')[0];
                    var width = $('.dataTables_scroll').width();
                    $('.dataTables_scroll').toggleClass('has-scroll', scrollWidth > width);
                };

                // Initial check
                scrollCheck();

                // Check on window resize
                $(window).on('resize', scrollCheck);
            },
            lengthMenu: [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
            columns: [
                null, // For Sr. No. (auto-detected)
                { "data": "name" }, // Name
                { "data": "username" }, // Email ID
                { "data": "exam_name" }, // Exam Name
                { "data": "exam_attended_time" }, // Exam Name
                { "data": "view" }, // View
                { "data": "delete" },
                // null  // For View button (auto-detected)
            ]
        });
    }
}
if (document.querySelector("#eit-result-table")) {
    if (!document.querySelector("#eit-result-table").innerHTML.includes("No results found.")) {
        var table = $('#eit-result-table').DataTable({
            pageLength: 10, // Set default number of rows to display
            scrollX: true,
            initComplete: function () {
                // Check if table needs scroll
                var scrollCheck = function () {
                    var scrollWidth = $('.dataTables_scroll')[0];
                    var width = $('.dataTables_scroll').width();
                    $('.dataTables_scroll').toggleClass('has-scroll', scrollWidth > width);
                };

                // Initial check
                scrollCheck();

                // Check on window resize
                $(window).on('resize', scrollCheck);
            },
            lengthMenu: [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
            columns: [
                null, // For Sr. No. (auto-detected)
                { "data": "name" }, // Name
                { "data": "email_id" }, // Email ID
                { "data": "exam_name" }, // Exam Name
                { "data": "self_awareness" }, // Self Awareness
                { "data": "managing_emotions" }, // Managing Emotions
                { "data": "motivating_oneself" }, // Motivating Oneself
                { "data": "empathy" }, // Empathy
                { "data": "handling_relationships" }, // Handling Relationships
                { "data": "total" }, // Total
                { "data": "exam_attended_time" }, // Exam Attended Time
                { "data": "delete" }, // Delete
                // null  // For View button (auto-detected)
            ]
        });
    }
}
if (document.querySelector("#question-bank-table")) {
    if (!document.querySelector("#question-bank-table").innerHTML.includes("No questions found.")) {
        var selectedColumn = "all"; // Default: search in all columns
        $('#columnSelector').on('change', function () {
            selectedColumn = $(this).val();
            table.draw(); // Refresh the table after selecting a column
        });
        var table = $('#question-bank-table').DataTable({
            paging: false,
            pageLength: 10, // Set default number of rows to display
            scrollX: true,
            initComplete: function () {

                // Check if table needs scroll
                var scrollCheck = function () {
                    var scrollWidth = $('.dataTables_scroll')[0];
                    var width = $('.dataTables_scroll').width();
                    $('.dataTables_scroll').toggleClass('has-scroll', scrollWidth > width);
                };

                // Initial check
                scrollCheck();

                // Check on window resize
                $(window).on('resize', scrollCheck);
            },
            lengthMenu: [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
            columns: [
                null, // For Sr. No. (auto-detected)
                null,
                null,  // For View button (auto-detected)
                { "data": "add_to_quiz" },
                { "data": "question_id" }, // Name
                { "data": "topic" }, // Email ID
                { "data": "main_group" }, // Exam Name
                { "data": "sub_group" }, // Exam Name
                { "data": "questions" }, // View
                { "data": "no_of_correctly_attempted" },
                { "data": "no_of_times_attempted" },
                { "data": "percentage_correctly_attempted" },
                { "data": "difficulty_level" }
            ]
        });
        var selectedColumn = "all"; // Default: search in all columns

        $('#columnSelector').on('change', function () {
            selectedColumn = $(this).val();
            table.draw(); // Refresh the table after selecting a column
        });

        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
            var searchTerm = table.search().trim().toLowerCase();
            if (!searchTerm) return true; // Agar search term nahi hai toh sab show karo

            function stripHtml(html) {
                var tempElement = document.createElement("div");
                tempElement.innerHTML = html;

                // `.d-none` class wale elements ko remove karo
                tempElement.querySelectorAll('.d-none').forEach(el => el.remove());

                // console.log("Original HTML:", html);
                // console.log("Extracted Text:", tempElement.textContent.trim());

                return tempElement.textContent.trim() || "";
            }



            if (selectedColumn === "all") {
                var rowText = data.map(col => stripHtml(col)).join(" ").toLowerCase();
                return rowText.includes(searchTerm);
            } else {
                var colIndex = parseInt(selectedColumn, 10);
                var columnText = stripHtml(data[colIndex]).toLowerCase();
                return columnText.includes(searchTerm);
            }
        });


        // Redraw table on search input change
        $('#example_filter input').on('input', function () {
            table.draw();
        });


        // $(function () {
        //     $("#question-bank-table").resizableColumns({
        //         // optional
        //         store: window.store,
        //     });
        // });
        // table.on('init', function () {
        //     $("#question-bank-table").resizableColumns({
        //         selector: function($table) {
        //             if ($table.find('thead').length) {
        //                 return _constants.SELECTOR_TH;
        //             }
        //             return _constants.SELECTOR_TD;
        //         },
        //         syncHandlers: true,
        //         resizeFromBody: true,
        //         maxWidth: null,
        //         minWidth: 0.01
        //     });
        // });
        // $(function(){
        //     $('table.resizable').resizableColumns();
        //   })
    }
}
// $.ajax({
//     url: 'ajax/get_questions.php',
//     type: 'POST',
//     dataType: 'json',
//     data: {
//         draw: 1,
//         start: 0,
//         length: 10,
//         search: { value: "" },
//         order: [{ column: 0, dir: "asc" }]
//     },
//     success: function (response) {
//         let html = "";
//         console.log(response);
//         console.log(JSON.stringify(response));
//         // document.write(JSON.stringify(response));
//         let srNo = 0
//         for(let i = 0; i < response.data.length; i++) {
//             console.log(response.data[i].questions);
//             srNo++;
//             html += `<tr>
//                 <td>${srNo}</td>
//                 <td><div>${response.data[i].questions}</div>`;
//             for(let j = 0; j < response.data[i].options.length; j++) {
//                 html += `<div class="d-flex">`;
//                 html += `<input type="checkbox" name="options[]" id="options-${j + 1}" ${response.data[i].options[j].is_correct ? "checked" : ""}/>`;
//                 html += `<label for="options-${j + 1}" class="w-100 ms-2" question-id="${response.data[i].question_id}" data-option="${response.data[i].options[j].text}">`;
//                 html += `&#${65 + j}.
//                     ${response.data[i].options[j].text} ${response.data[i].options[j].is_correct ? " (correct)" : ""}
//                     <!--<td>${response.data[i].options[j].is_correct ? " (correct)" : ""}</td> -->
//                     <!--<td>${response.data[i].options[j].text}</td>-->`;
//                 html += `</label></div>`;
//                 console.log(response.data[i].options[j].text + (response.data[i].options[j].is_correct ? " (correct)" : ""));
//             }
//         }
//         html += `</tr>`;
//         let questionBankTable = document.getElementById("question-bank-table");
//         questionBankTable.firstElementChild.nextElementSibling.nextElementSibling.nextElementSibling.innerHTML = html;
//     },
//     error: function (xhr, status, error) {
//         console.error("AJAX Error: ", error);
//     }
// });

if (document.querySelector("#question-table")) {
    if (!document.querySelector("#question-table").innerHTML.includes("No questions found.")) {
        var table = $('#question-bank-table').DataTable({
            paging: false,
            pageLength: 10,
            lengthMenu: [[10,25,50,100],[10,25,50,100]],
            scrollX: true,
            order: [[4, 'asc']],
            searchDelay: 500,
            stateSave: true,
            rowId: 'question_id',
            columns: [
              { data: null,  orderable: false, searchable: false }, // Sr. no.
              { data: null,  orderable: false, searchable: false }, // Edit
              { data: null,  orderable: false, searchable: false }, // Delete
              { data: 'add_to_quiz', orderable: false, searchable: false },
              { data: 'question_id' },
              { data: 'topic' },
              { data: 'main_group' },
              { data: 'sub_group' },
              { data: 'questions' },
              { data: 'no_of_correctly_attempted' },
              { data: 'no_of_times_attempted' },
              { data: 'percentage_correctly_attempted' },
              { data: 'difficulty_level' }
            ],
            columnDefs: [
              {
                targets: 0,
                render: function(data, type, row, meta) {
                  return meta.row + meta.settings._iDisplayStart + 1;
                }
              },
              {
                targets: 1,
                render: function(data, type, row) {
                  return '<button class="btn btn-primary individual-edit-question" data-id="'+ row.question_id +'">Edit</button>';
                }
              },
              {
                targets: 2,
                render: function(data, type, row) {
                  return '<button class="btn btn-danger individual-delete-question" data-id="'+ row.question_id +'">Delete</button>';
                }
              }
            ],
            initComplete: function(settings, json) {
              // Force the first AJAX fetch if it hasn't run
              this.api().ajax.reload(null, false);
            }
          });
        var selectedColumn = "all"; // Default: search in all columns

        $('#columnSelector').on('change', function () {
            selectedColumn = $(this).val();
            table.draw(); // Refresh the table after selecting a column
        });

        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
            var searchTerm = table.search().trim().toLowerCase();

            if (!searchTerm) return true; // If no search term, show all

            if (selectedColumn === "all") {
                // Default behavior: search in all columns
                return data.join(" ").toLowerCase().includes(searchTerm);
            } else {
                // Search only in the selected column
                return data[selectedColumn].toLowerCase().includes(searchTerm);
            }
        });

        // Redraw table on search input change
        $('#example_filter input').on('keyup', function () {
            table.draw();
        });
    }
}
// console.log(table);

// $('#result-table').DataTable({
//     "columns": [
//         null,       // For Sr. No. (auto-detected)
//         { "data": "name" },
//         { "data": "email_id" },
//         { "data": "exam_name" },
//         null        // For View button (auto-detected)
//     ]
// });



async function generateExcel() {
    const workbook = new ExcelJS.Workbook();
    const worksheet = workbook.addWorksheet('Sheet 1');

    // Function to add table data to the worksheet in the same column with gaps
    function addTableToSheet(tableId, startRow, startColumn) {
        const table = document.getElementById(tableId);
        // console.log(table);
        const rows = table.rows;
        let currentRow = startRow;

        // Loop through table rows
        for (let i = 0; i < rows.length; i++) {
            const row = rows[i];
            const rowData = [];

            // Loop through cells of each row
            for (let j = 0; j < row.cells.length; j++) {
                // console.log(row.cells[j].querySelectorAll("[class*='remove-']"));
                if(row.cells[j].querySelectorAll("[class^='remove-']")) {
                    Array.from(row.cells[j].querySelectorAll("[class*='remove-']")).forEach((e) => {
                        // console.log(e);
                        e.innerHTML = ",&nbsp;";
                    });
                }
                rowData.push(row.cells[j].innerText); // Get cell data
                if(row.cells[j].querySelectorAll("[class^='remove-']")) {
                    Array.from(row.cells[j].querySelectorAll("[class*='remove-']")).forEach((e) => {
                        // console.log(e);
                        e.innerHTML = "X";
                    });
                }
            }

            // Add row data to the worksheet in the same column but different rows
            for (let col = 0; col < rowData.length; col++) {
                worksheet.getRow(currentRow).getCell(startColumn + col).value = rowData[col];
            }

            // Increment row for the next row of the same table
            currentRow++;

            // Add gap of 2 rows after each table
            if (i === rows.length - 1) {
                currentRow += 2; // Add 2 empty rows after the table
            }
        }
    }

    // Start adding tables from row 1, column 1
    let startRow = 1;
    let startColumn = 1;

    // Add data for Table 1 (Users)
    if (document.getElementById("result-table")) {
        if (document.getElementById("result-table-excel-output")) {
            addTableToSheet('result-table-excel-output', startRow, startColumn);
        } else {
            addTableToSheet('result-table', startRow, startColumn);
        }
    }
    if (document.getElementById('question-table')) {
        addTableToSheet('question-table', startRow, startColumn);
    }
    if (document.getElementById('eit-result-table')) {
        addTableToSheet('eit-result-table', startRow, startColumn);
    }
    if (document.getElementById('question-bank-table')) {
        addTableToSheet('question-bank-table', startRow, startColumn);
    }

    // Add data for Table 2 (Exams) starting after a gap in the same column
    // addTableToSheet('result-values-1', startRow + 13, startColumn + 1); // Start after a gap of 13 rows and 1 columns

    // Add data for Table 3 (Countries) starting after a gap in the same column
    // addTableToSheet('result-values-2', startRow + 13, startColumn + 4); // Start after a gap of 13 rows and 4 columns

    // Add data for Table 4 (Countries) starting after a gap in the same column
    // addTableToSheet('result-values-3', startRow + 13, startColumn + 7); // Start after a gap of 13 rows and 7 columns

    // Add data for Table 5 (Countries) starting after a gap in the same column
    // addTableToSheet('result-values-4', startRow + 13, startColumn + 10); // Start after a gap of 13 rows and 10 columns

    if (document.title == "Personality Type Test Result") {
        addTableToSheet('result-calculation-table', startRow, startColumn);
        // Add data for Table 2 (Exams) starting after a gap in the same column
        addTableToSheet('result-values-1', startRow + 13, startColumn + 1); // Start after a gap of 13 rows and 1 columns

        // Add data for Table 3 (Countries) starting after a gap in the same column
        addTableToSheet('result-values-2', startRow + 13, startColumn + 4); // Start after a gap of 13 rows and 4 columns

        // Add data for Table 4 (Countries) starting after a gap in the same column
        addTableToSheet('result-values-3', startRow + 13, startColumn + 7); // Start after a gap of 13 rows and 7 columns

        // Add data for Table 5 (Countries) starting after a gap in the same column
        addTableToSheet('result-values-4', startRow + 13, startColumn + 10); // Start after a gap of 13 rows and 10 columns
    }

    // Write the Excel file to buffer
    const buffer = await workbook.xlsx.writeBuffer();

    // Save the file
    saveAs(new Blob([buffer]), 'multiple_tables_output_with_gaps.xlsx');
}
function questionsPage() {
    let submitQuestion = document.getElementById("submit-question");
    let addQuestion = document.getElementById("add-question");
    let deleteQuestion = document.querySelectorAll(".delete-question");
    let deleteQuestionSubmit = document.querySelectorAll(".delete_question_submit");
    Array.from(deleteQuestion).forEach((e, i) => {
        e.addEventListener("click", () => {
            deleteQuestionSubmit[i].click();
        })
    });
    addQuestion.addEventListener("click", () => {
        submitQuestion.click();
    });

}

function mcqQuestions() {
    let addOption = document.querySelectorAll(".add-option");
    let addQuestionForm = document.getElementById("add-question-form");
    let questionElement = document.getElementById("question");
    let addQuestionInputElements = document.querySelectorAll("#add-question-form input[type='text']");
    let individualEditQuestion = document.querySelectorAll(".individual-edit-question");
    let questionType = document.querySelectorAll(".question-type");
    // console.log(addQuestionInputElement);
    let addQuestion = document.getElementById("add-question");
    let submitQuestion = document.getElementById("submit-question");
    let checkBoxElements = document.querySelectorAll("input[type='checkbox']:has(+[data-question-id][data-option])");
    let resetButtonElements = document.querySelectorAll("button[type='reset']");
    let removeQuestion = document.querySelectorAll(".remove-question");
    let removeQuestionSubmit = document.querySelectorAll(".remove_question_submit");
    // console.log(removeQuestion);
    // console.log(removeQuestionSubmit);
    // let btnCloseElements = document.querySelectorAll(".btn-close");
    // let closeModalBtnElements = document.querySelectorAll(".close-modal-btn");
    // console.log(resetButtonElements.length);
    // console.log(btnCloseElements.length);
    // console.log(closeModalBtnElements.length);
    // console.log(resetButtonElements);
    let correctOption = 0;
    let editCorrectOption = 0;
    Array.from(removeQuestion).forEach((e, i) => {
        e.addEventListener("click", () => {
            removeQuestionSubmit[i].click();
        })
    });
    document.addEventListener("DOMContentLoaded", () => {
        // Get all question type select elements for both add and edit forms
        const questionTypes = document.querySelectorAll(".question-type");

        Array.from(questionTypes).forEach((e) => {
            let removedElements = [];

            e.addEventListener("change", () => {
                let currentElement = e.closest(".add-question-type-box").nextElementSibling;

                if (e.value !== "weighted") {
                    e.parentElement.parentElement.querySelectorAll(".toggle-correct").forEach((toggle) => {
                        if (toggle.classList.contains("d-none")) {
                            toggle.classList.remove("d-none");
                        }
                    });
                    let invisibleCorrectIncorrectEement = e.parentElement.parentElement.querySelectorAll(".invisible");
                    let toggleCorrectElement = e.parentElement.parentElement.querySelectorAll(".toggle-correct");
                    for (let i = 0; i < invisibleCorrectIncorrectEement.length; i++) {
                        if (!toggleCorrectElement[i].classList.contains("text-success")) {
                            if (invisibleCorrectIncorrectEement[i].value == "correct") {
                                invisibleCorrectIncorrectEement[i].value = "incorrect";
                            }
                        }
                    }

                }
                if (e.value === "title") {
                    while (currentElement) {
                        let next = currentElement.nextElementSibling;
                        if (currentElement.classList.contains("option-container")) {
                            removedElements.push(currentElement);
                            currentElement.remove();
                        }
                        currentElement = next;
                    }
                } else if (e.value == "weighted") {
                    e.parentElement.parentElement.querySelectorAll(".toggle-correct").forEach((toggle) => {
                        toggle.classList.add("d-none");
                    });
                    let invisibleCorrectIncorrectEement = e.parentElement.parentElement.querySelectorAll(".invisible");
                    Array.from(invisibleCorrectIncorrectEement).forEach(e => {
                        e.value = "correct";
                    });

                    let optionContainers = e.parentElement.parentElement.querySelectorAll(".option-container");
                    optionContainers.forEach((option, index) => {
                        // Check if already added to avoid duplication
                        if (!option.querySelector(".marking-field")) {
                            let markingFieldElement = document.createElement("input");
                            let markingLabelElement = document.createElement("label");
                            let spacingElement = document.createElement("div");

                            spacingElement.classList.add("mb-3");

                            markingLabelElement.innerText = String.fromCharCode(65 + index) + " Mark"; // Fix charAt issue
                            markingLabelElement.setAttribute("for", "marking-" + String.fromCharCode(65 + index).toLowerCase());
                            markingFieldElement.setAttribute("type", "text");
                            markingFieldElement.setAttribute("id", "marking-" + String.fromCharCode(65 + index).toLowerCase());
                            markingFieldElement.setAttribute("min", "0");
                            markingFieldElement.setAttribute("oninput", "this.value = this.value.split('').filter(e => !isNaN(e) && e != ' ').join('');");
                            markingFieldElement.setAttribute("maxlength", "3");
                            markingFieldElement.setAttribute("name", "marking[]");
                            markingFieldElement.setAttribute("required", "true");
                            markingFieldElement.value = "1"; // Default marks
                            markingFieldElement.classList.add("marking-field");
                            markingFieldElement.classList.add("form-control");
                            markingLabelElement.classList.add("marking-label");
                            markingLabelElement.classList.add("form-label");

                            option.appendChild(spacingElement);
                            option.appendChild(markingLabelElement);
                            option.appendChild(markingFieldElement);

                            // Allow only numeric input
                            // markingFieldElement.addEventListener("input", () => {
                            //     markingFieldElement.value = markingFieldElement.value.replace(/\D/g, ""); // Remove non-numeric chars
                            // });
                        }
                    });
                } else {
                    e.parentElement.parentElement.querySelectorAll(".toggle-correct").forEach((toggle) => {
                        if (toggle.hasAttribute("style")) {
                            toggle.removeAttribute("style");
                        }
                    });

                    // Remove marking fields if not weighted
                    e.parentElement.parentElement.querySelectorAll(".option-container").forEach((option) => {
                        option.querySelectorAll(".marking-field, .marking-label").forEach((el) => el.remove());
                    });

                    if (removedElements.length > 0) {
                        const parent = e.closest("form");
                        removedElements.forEach((el) => {
                            parent.appendChild(el);
                        });
                        removedElements = [];
                    }
                }
            });
        });

    });
    document.addEventListener("DOMContentLoaded", () => {
        // Get all question type select elements for both add and edit forms
        const questionTypes = document.querySelectorAll(".question-type");

        Array.from(questionTypes).forEach((e) => {
            // Store removed elements to restore them if needed
            let removedElements = [];

            e.addEventListener("change", () => {
                let currentElement;
                try {
                    currentElement = e.closest(".edit-question-type-box").nextElementSibling;
                } catch (error) { }

                if (e.value === "title") {
                    // Remove all siblings with the class "option-container"
                    while (currentElement) {
                        let next = currentElement.nextElementSibling; // Store next sibling
                        if (currentElement.classList.contains("option-container")) {
                            removedElements.push(currentElement); // Store removed element
                            currentElement.remove(); // Remove element from DOM
                        }
                        currentElement = next; // Move to the next sibling
                    }
                    // } else if(e.value === "weighted") {
                    //     e.parentElement.parentElement.querySelectorAll(".toggle-correct").forEach((toggle) => {
                    //         toggle.style.display = "none";
                    //     })
                } else {
                    // Restore previously removed elements if they exist
                    if (removedElements.length > 0) {
                        const parent = e.closest("form"); // Parent form to append elements
                        removedElements.forEach((el) => {
                            parent.appendChild(el); // Re-add the element at the end of the form
                        });
                        removedElements = []; // Clear the array
                    }
                }
            });
        });
    });


    addQuestion.addEventListener("click", () => {
        // let isAnyEmptyField = false;
        // Array.from(addQuestionInputElements).forEach((e) => {
        //     if(e.target.value == "") {
        //         isAnyEmptyField = true;
        //     } else {
        //         isAnyEmptyField = false;
        //     }
        // });
        // console.log(addQuestion.parentElement.parentElement.querySelector(".question-type"));
        if (correctOption == 0 && addQuestion.parentElement.parentElement.querySelectorAll(".option-container").length > 0) {
            alert("Please select correct option");
        } else {
            submitQuestion.click();
        }
        // if(isAnyEmptyField) {
        //     submitQuestion.click();
        // } else {
        // }
    });

    Array.from(addOption).forEach((e, i) => {
        e.addEventListener("click", () => {
            let addQuestionsOptionsElement;

            // Check if it's Add Form or Edit Form
            addQuestionsOptionsElement = e.previousElementSibling.querySelectorAll(".add-questions-options");
            // if (e.classList.contains("add-question-add-option")) {
                // addQuestionsOptionsElement = e.previousElementSibling.querySelectorAll(".add-questions-options");
            // } else if (e.classList.contains("edit-question-add-option")) {
                // addQuestionsOptionsElement = e.previousElementSibling.querySelectorAll(".add-questions-options");
            // }

            let option;
            let nextOption;

            // Case 1: If no options exist, start with 'A'
            if (addQuestionsOptionsElement.length === 0) {
                nextOption = 'A';
            } else {
                // Case 2: If options exist, get the last option's ID and determine next option
                let lastAddQuestionsOptionsElement = addQuestionsOptionsElement[addQuestionsOptionsElement.length - 1];
                option = lastAddQuestionsOptionsElement.getAttribute("id").slice(-1); // Get last option letter
                // Calculate next option letter (A -> B -> C -> D -> E...)
                nextOption = String.fromCharCode(option.charCodeAt(0) + 1);
            }


            // ✅ Fix: Ensure form exists before checking "weighted" type
            let formElement = e.previousElementSibling;
            let questionTypeElement = formElement ? formElement.querySelector(".question-type") : null;
            let isWeighted = questionTypeElement && questionTypeElement.value === "weighted";

            // ✅ Generate marking input field if "weighted" is selected
            let markingFieldHTML = isWeighted
                ? `<div class="mb-3"></div>
                <label for="marking-${nextOption}" class="marking-label form-label">${nextOption.toUpperCase()} Mark</label>
                   <input type="text" id="marking-${nextOption}" class="marking-field form-control" oninput="this.value = this.value.split('').filter(e => !isNaN(e) && e != ' ').join('');" value="1" min="0" name="marking[]" maxlength="3" required>`
                : "";
            // console.log(markingFieldHTML);
            // console.log(isWeighted);
            // console.log(questionTypeElement);
            // console.log(formElement);
            // console.log(e);
            let newElement;

            // Case for Add Form
            if (e.classList.contains("add-question-add-option")) {
                newElement = `<div class="mb-3 option-container">
                                <label for="opt_${nextOption}" class="form-label">Option ${nextOption.toUpperCase()}</label>
                                <i class="fa-regular fa-circle toggle-correct ${isWeighted ? "d-none" : ""}"></i>
                                <input type="text" class="form-control d-inline-block border border-dark add-questions-options ${(e.classList.contains("deleted-question-add-option")) ? "deleted-questions-options" : ""}" id="opt_${nextOption}" name="options[]" required>
                                <input type="hidden" value="${isWeighted ? "correct" : "incorrect"}" class="form-control invisible add-questions-options" id="correct_opt_${nextOption}" name="correct_options[]">
                                <i class="fa-solid fa-xmark remove-option" style="cursor: pointer;"></i>
                                ${markingFieldHTML}
                            </div>`;
            }
            // Case for Edit Form
            else if (e.classList.contains("edit-question-add-option")) {
                newElement = `<div class="mb-3 option-container">
                                <label for="edit_options_${nextOption}" class="form-label">Option ${nextOption.toUpperCase()}</label>
                                <i class="fa-regular fa-circle toggle-correct ${isWeighted ? "d-none" : ""}"></i>
                                <input type="text" class="form-control d-inline-block border border-dark add-questions-options ${(e.classList.contains("deleted-question-add-option")) ? "deleted-questions-options" : ""}" id="edit_options_${nextOption}" name="options[]" required>
                                <input type="hidden" value="${isWeighted ? "correct" : "incorrect"}" class="form-control invisible add-questions-options" id="correct_opt_${nextOption}" name="correct_options[]">
                                <i class="fa-solid fa-xmark remove-option" style="cursor: pointer;"></i>
                                ${markingFieldHTML}
                            </div>`;
            }

            // Insert the new option element after the last option, or if no options, after the add button
            if (addQuestionsOptionsElement.length === 0) {
                console.log(e);
                console.log(newElement);
                // newElement = `<div class="wrapper">${newElement}</div>`;
                // let notRequiredElement = document.createElement("div");
                // notRequiredElement.innerHTML = newElement;
                // let newElementWrapper = notRequiredElement.querySelector(".wrapper");
                // console.log(newElementWrapper);
                e.previousElementSibling.insertAdjacentHTML('beforeend', newElement);
            } else {
                addQuestionsOptionsElement[addQuestionsOptionsElement.length - 1].parentElement.insertAdjacentHTML('afterend', newElement);
            }

            // Add functionality to the newly added options (remove and toggle correct)
            addRemoveOptionEvent();
            toggleCorrect();

            // ✅ Add event listener to new marking fields
            if (isWeighted) {
                let newMarkingField = formElement.querySelector(".option-container:last-child .marking-field");
                if (newMarkingField) {
                    newMarkingField.addEventListener("input", () => {
                        newMarkingField.value = newMarkingField.value.replace(/\D/g, ""); // Allow only numbers
                    });
                }
            }
        });

        // Add functionality to initially available options
        addRemoveOptionEvent();
        toggleCorrect();
    });




    let editQuestion = document.querySelectorAll(".edit-question");
    let editQuestionSubmit = document.querySelectorAll(".edit_question_submit");
    Array.from(editQuestion).forEach((e, i) => {
        e.addEventListener("click", () => {
            // console.log(editQuestion);
            if (editCorrectOption == 0 && e.parentElement.parentElement.querySelectorAll(".option-container").length > 0) {
                alert("Please select correct option");
            } else {
                // console.log(editCorrectOption);
                editQuestionSubmit[i].click();
            }
        });
    });
    Array.from(individualEditQuestion).forEach(e => {
        e.addEventListener("click", (event) => {
            editCorrectOption = 0;
            // console.log(e.dataset.bsTarget);
            let questionEditModalId = event.target.dataset.bsTarget.split("#")[1];
            // console.log(questionEditModalId);
            let questionEditModal = document.getElementById(questionEditModalId);
            let textSuccessElements = questionEditModal.querySelectorAll(".text-success");
            let allOptions = questionEditModal.querySelectorAll(".option-container");
            console.log(allOptions);
            editCorrectOption = textSuccessElements.length;
            console.log(editCorrectOption);
            // console.log(event.target.nextElementSibling.innerHTML);
            // event.target.nextElementSibling.innerHTML = event.target.nextElementSibling.innerHTML.replace(/class\s*=\s*"(font-size-20)"/g, "class='$1'");
            console.log(event.target.nextElementSibling.innerHTML);
            // console.log(JSON.parse(event.target.nextElementSibling.innerHTML.replace(/class\s*=\s*"(font-size-20)"/g, "class='$1'")));
            let questionData = event.target.nextElementSibling.children;
            let editIdElement = document.getElementById("edit_id");
            let editQuestionIdElement = document.getElementById("edit_question_id");
            let editQuestionElement = document.getElementById("edit_question");
            let oldQuestionElement = document.getElementById("old_question");
            let editQuestionTypeElement = document.getElementById("edit-question-type");
            console.log(questionData);
            editIdElement.value = questionData[0].children[1].innerHTML;
            editQuestionIdElement.value = questionData[4].children[1].innerHTML;
            oldQuestionElement.value = questionData[1].children[1].innerHTML;
            editQuestionElement.value = questionData[1].children[1].innerHTML;
            console.log(editQuestionTypeElement);
            Array.from(editQuestionTypeElement.children).forEach(e => {
                if(questionData[3].children[1].innerHTML == "single" || questionData[3].children[1].innerHTML == "multiple") {
                    if(e.value == "single" || e.value == "multiple") {
                        if(e.disabled == true) {
                            e.disabled = false;
                        }
                    }
                    if(e.value == "title") {
                        if(e.disabled == false) {
                            e.disabled = true;
                        }
                    }
                }
                if(questionData[3].children[1].innerHTML == "title") {
                    if(e.value == "title") {
                        if(e.disabled == true) {
                            e.disabled = false;
                        }
                    }
                    if(e.value == "single" || e.value == "multiple") {
                        if(e.disabled == false) {
                            e.disabled = true;
                        }
                    }
                }
                if (e.value == questionData[3].children[1].innerHTML) {
                    e.selected = true;
                }
            });
            Array.from(allOptions).forEach(options => {
                options.parentElement.removeChild(options);
            });
            Array.from(e.nextElementSibling.querySelectorAll(".mb-2:has(.options)")).forEach(hiddenOptions => {
                document.querySelector(".edit-question-add-option").click();
                let optionContainers = questionEditModal.querySelector(".option-container:last-child");
                if(hiddenOptions.firstElementChild.dataset.isCorrect == "1") {
                    optionContainers.querySelector(".toggle-correct").classList.add("text-success");
                    optionContainers.querySelector(".toggle-correct").classList.replace("fa-circle", "fa-circle-check");
                    optionContainers.querySelector(".invisible").value = "correct";
                }
                optionContainers.querySelector(".form-control").value = hiddenOptions.lastElementChild.innerHTML.trim();
            })
        });
    });
    let deleteQuestion = document.querySelectorAll(".delete-question");
    let deleteQuestionSubmit = document.querySelectorAll(".delete_question_submit");
    Array.from(deleteQuestion).forEach((e, i) => {
        e.addEventListener("click", () => {
            deleteQuestionSubmit[i].click();
        });
    });

    // Function to toggle correct option
    function toggleCorrect() {
        document.querySelectorAll(".toggle-correct").forEach((e) => {
            // console.log(getEventListeners(e));
            e.removeEventListener("click", toggleCorrectEvent);
            e.addEventListener("click", toggleCorrectEvent);
        });
    }
    function toggleCorrectEvent(event) {
        // console.log(event.target);
        if (event.target.classList.contains("fa-circle-check")) {
            event.target.classList.remove("text-success");
            event.target.classList.replace("fa-circle-check", "fa-circle");
            event.target.nextElementSibling.nextElementSibling.checked = false;
            event.target.nextElementSibling.nextElementSibling.value = "incorrect";
            if (!event.target.nextElementSibling.id.includes("edit")) {
                correctOption--;
            } else {
                editCorrectOption--;
            }
        } else {
            event.target.parentElement.parentElement.querySelectorAll(".toggle-correct").forEach(e => {
                if (e.parentElement.parentElement.querySelector(".question-type").value == "single") {
                    if (e.classList.contains("text-success")) {
                        e.classList.remove("text-success");
                    }
                    e.classList.replace("fa-circle-check", "fa-circle");
                    e.nextElementSibling.nextElementSibling.checked = false;
                    e.nextElementSibling.nextElementSibling.value = "incorrect";
                }
            });
            event.target.classList.add("text-success");
            event.target.classList.replace("fa-circle", "fa-circle-check");
            event.target.nextElementSibling.nextElementSibling.checked = true;
            event.target.nextElementSibling.nextElementSibling.value = "correct";
            if (!event.target.nextElementSibling.id.includes("edit")) {
                correctOption++;
            } else {
                editCorrectOption++;
            }
        }
        // console.log(event.target.nextElementSibling.id.includes("edit"));
    }

    // Function to add remove option functionality
    function addRemoveOptionEvent() {
        let removeOptionButtons = document.querySelectorAll(".remove-option");

        Array.from(removeOptionButtons).forEach((removeButton) => {
            removeButton.removeEventListener("click", handleRemoveOption); // Remove previous listeners
            removeButton.addEventListener("click", handleRemoveOption);
        });
    }

    // Function to handle option removal
    function handleRemoveOption(event) {
        // console.log(event.target);
        let allOptions = event.target.parentElement.parentElement.querySelectorAll(".option-container");
        // console.log(allOptions);

        if (allOptions.length <= 1) {
            alert("At least one option is required."); // Prevent removing the last remaining option
            return;
        }

        let clickedOption = event.target.parentElement; // Parent div of the clicked remove button
        let clickedIndex = Array.from(allOptions).indexOf(clickedOption);

        // Shift values upwards
        for (let i = clickedIndex; i < allOptions.length - 1; i++) {
            // console.log(allOptions[i]);
            let currentInput = allOptions[i].querySelector("input");
            let nextInput = allOptions[i + 1].querySelector("input");

            // Shift value from next input to current input
            currentInput.value = nextInput.value;
        }

        // Safely remove the last option
        let lastOption = allOptions[allOptions.length - 1];
        if (lastOption) {
            lastOption.remove();
        }
    }
    $(document).ready(function () {
        $("input[type='checkbox']:has(+[data-question-id][data-option])").change(function (e) {
            // $("input[type='checkbox'][data-question-id][data-option]").not(this).prop("checked", false);
            // debugger;
            e.preventDefault();
            if (e.target.nextElementSibling.dataset.questionType == "single") {
                Array.from(e.target.parentElement.parentElement.querySelectorAll("input[type='checkbox']:checked:has(+[data-question-id][data-option])")).forEach((elem) => {
                    elem.checked = false;
                })
                e.target.checked = true;
            }
            // console.log(e);
            let questionId = $(this).next().data("question-id");
            let option = $(this).next().data("option");
            let questionType = $(this).next().data("question-type");
            let srNo = $(this).parent().parent().prev().text();
            if ($(this).is(":checked")) {
                isCorrect = "correct";
            } else {
                isCorrect = "incorrect";
            }
            // console.log(question);
            $.ajax({
                url: 'Ajax/toggle_correct_option.php',
                type: 'POST',
                data: {
                    questionId: questionId,
                    option: option,
                    isCorrect: isCorrect,
                    questionType: questionType
                },
                dataType: 'text',
                success: function (response) {
                    // console.log(response);
                    // if(response == "success") {
                    // console.log(srNo);
                    // let questionEditModal = document.getElementById("questionEditModal" + srNo);
                    // let toggleCorrect = questionEditModal.querySelectorAll(".toggle-correct");
                    // // console.log(questionEditModal);
                    // console.log(toggleCorrect[index].previousElementSibling);
                    // console.log(index);
                    // console.log(e.target);
                    // }
                }
            })
        })
        // mcqQuestions();
    });
    // let isCorrect = "incorrect";
    // Array.from(checkBoxElements).forEach((e) => {
    //     // console.log(e);
    //     e.nextElementSibling.addEventListener("click", () => {
    //         let question = e.nextElementSibling.dataset.question;
    //         // console.log(e.nextElementSibling.dataset);
    //         let option = e.nextElementSibling.dataset.option;
    //         // console.log(option);
    //         if (e.checked) {
    //             isCorrect = "correct";
    //         }
    //         $.ajax({
    //             url: 'Ajax/toggle_correct_option.php',
    //             type: 'POST',
    //             data: {
    //                 question: question,
    //                 option: option,
    //                 isCorrect: isCorrect
    //             },
    //             dataType: 'text',
    //             success: function (response) {
    //                 // console.log(response);
    //             }
    //         })
    //     })
    // })

    Array.from(resetButtonElements).forEach((e) => {
        e.parentElement.parentElement.nextElementSibling.lastElementChild.addEventListener("click", () => {
            Array.from(e.parentElement.querySelectorAll("input")).forEach((e) => {
                if (e.hasAttribute("disabled")) {
                    e.removeAttribute("disabled");
                }
            });
            e.click();
        });
        e.parentElement.parentElement.previousElementSibling.lastElementChild.addEventListener("click", () => {
            Array.from(e.parentElement.querySelectorAll("input")).forEach((e) => {
                if (e.hasAttribute("disabled")) {
                    e.removeAttribute("disabled");
                }
            });
            e.click();
        });
    });
    const _constants = {
        SELECTOR_TH: 'th',
        SELECTOR_TD: 'td'
    };
    // let questionBankTable = document.getElementById("question-bank-table");
    // $(function () {
    //     $("#question-bank-table").resizableColumns({
    //         // optional
    //         store: window.store,
    //     });
    // });
    // questionBankTable.on('init', function () {
    //     $("#question-bank-table").resizableColumns({
    //         selector: function selector($table) {
    //             if ($table.find('thead').length) {
    //                 return _constants.SELECTOR_TH;
    //             }
    //             return _constants.SELECTOR_TD;
    //         },
    //         store: window.store,
    //         syncHandlers: true,
    //         resizeFromBody: true,
    //         maxWidth: null,
    //         minWidth: 0.01
    //     });
    // });
}


function questionBank() {
    let addTopicElement = document.getElementById("add-topic");
    let submitTopicElement = document.getElementById("submit-topic");
    let addMainGroup = document.getElementById("add-main-group");
    let submitMainGroup = document.getElementById("submit-main-group");
    let addSubGroup = document.getElementById("add-sub-group");
    let submitSubGroup = document.getElementById("submit-sub-group");
    let newQuizElement = document.getElementById("new-quiz");
    let selectQuizElement = document.getElementById("select_quiz");
    let createQuizElement = document.getElementById("create-quiz");
    let submitCreateQuiz = document.getElementById("submit-create-quiz");
    let createQuizForm = document.getElementById("create-quiz-form");
    let addToQuiz = document.querySelectorAll(".add-to-quiz");
    let examDurationInputElement = document.getElementById("exam-duration");
    let assignTopics = document.querySelectorAll(".assign-topics");
    let assignMainGroups = document.querySelectorAll(".assign-main-groups");
    let assignSubGroups = document.querySelectorAll(".assign-sub-groups");
    let searchTopicElement = document.querySelectorAll(".search-topic");
    let searchMainGroupElement = document.querySelectorAll(".search-main-group");
    let searchSubGroupElement = document.querySelectorAll(".search-sub-group");
    // let editDeletedQuestionElement = document.getElementById("edit_deleted_question");
    // let editDeletedQuestionImageElement = document.getElementById("edit-deleted-question-image");
    // let editDeletedQuestionTypeElement = document.getElementById("edit-deleted-question-type");
    // let editQuestionElement = document.querySelectorAll("edit-question");

    Array.from(searchTopicElement).forEach((e, i) => {
        e.addEventListener("input", (event) => {
            // event.stopPropagation();
            let topicContainer = e.nextElementSibling;
            // console.log(topicContainer.firstElementChild.innerText);
            Array.from(topicContainer.children).forEach((child, index) => {
                // console.log(child.innerText);
                if(child.innerText.toLowerCase().includes(event.target.value.toLowerCase())) {
                    if(child.classList.contains("d-none")) {
                        child.classList.remove("d-none");
                    }
                } else {
                    if(!child.classList.contains("d-none")) {
                        child.classList.add("d-none");
                    }
                }
            })
        });
    });
    Array.from(searchMainGroupElement).forEach((e, i) => {
        e.addEventListener("input", (event) => {
            // event.stopPropagation();
            let mainGroupContainer = e.nextElementSibling;
            // console.log(mainGroupContainer.firstElementChild.innerText);
            Array.from(mainGroupContainer.children).forEach((child, index) => {
                // console.log(child.innerText);
                if(child.innerText.toLowerCase().includes(event.target.value.toLowerCase())) {
                    if(child.classList.contains("d-none")) {
                        child.classList.remove("d-none");
                    }
                } else {
                    if(!child.classList.contains("d-none")) {
                        child.classList.add("d-none");
                    }
                }
            })
        });
    });
    Array.from(searchSubGroupElement).forEach((e, i) => {
        e.addEventListener("input", (event) => {
            // event.stopPropagation();
            let subGroupContainer = e.nextElementSibling;
            // console.log(mainGroupContainer.firstElementChild.innerText);
            Array.from(subGroupContainer.children).forEach((child, index) => {
                // console.log(child.innerText);
                if(child.innerText.toLowerCase().includes(event.target.value.toLowerCase())) {
                    if(child.classList.contains("d-none")) {
                        child.classList.remove("d-none");
                    }
                } else {
                    if(!child.classList.contains("d-none")) {
                        child.classList.add("d-none");
                    }
                }
            })
        });
    });
    // console.log($);
    $(document).ready(function () {
        // Input event: AJAX call karke already exists flag set karo
        $(".edit_question").on("input", async function () {
            let $this = $(this);
            try {
                let response = await $.ajax({
                    url: 'Ajax/is_already_exists.php',
                    type: 'POST',
                    data: {
                        question: $this.val(),
                        question_id: $this.data("question-id")
                    },
                    dataType: 'text'
                });
                
                let alreadyExists = Boolean(parseInt(response));
                // Flag store kar rahe hain; isse later submit event me use karenge
                $this.data("alreadyExists", alreadyExists);
            } catch (error) {
                console.error("Error:", error);
            }
        });
        
        // Submit button click event: yahan confirmation dialog dikhega
        $(".edit_question_submit").on("click", function (e) {
            // Assume corresponding .edit_question ka index same order me hai
            let index = $(".edit_question_submit").index(this);
            let $input = $(".edit_question").eq(index);
            let exists = $input.data("alreadyExists");
            
            // Agar question already exist karta hai, toh confirmation dialog dikhao
            if (exists) {
                let agreeToUpdate = confirm("This question is already available. Do you want to continue?");
                if (!agreeToUpdate) {
                    // Agar user cancel karta hai, toh submission cancel karo
                    e.preventDefault();
                    // console.log("Submission cancelled by user.");
                    return false;
                }
            }
        });
    });
    
    
    
    // let previousEditDeletedQuestionElementValue = editDeletedQuestionElement.value;
    // let previousEditDeletedQuestionImageElementValue = editDeletedQuestionImageElement.value;
    // let previousEditDeletedQuestionTypeElementValue = editDeletedQuestionTypeElement.value;
    // remove disabled from the form of adding the deleted questions on any change in the form
    // console.log(editDeletedQuestionElement);
    // editDeletedQuestionElement.addEventListener("input", () => {
    // if(editDeletedQuestionElement.value.trim() != previousEditDeletedQuestionElementValue.trim()) {
    // if(editDeletedQuestionElement.parentElement.parentElement.parentElement.nextElementSibling.querySelector(".edit-question").hasAttribute("disabled")) {
    // editDeletedQuestionElement.parentElement.parentElement.parentElement.nextElementSibling.querySelector(".edit-question").removeAttribute("disabled");
    // }
    // } else {
    // if(editDeletedQuestionImageElement.value.trim() == previousEditDeletedQuestionImageElementValue.trim() && editDeletedQuestionTypeElement.value.trim() == previousEditDeletedQuestionTypeElementValue.trim()) {
    // if(!editDeletedQuestionElement.parentElement.parentElement.parentElement.nextElementSibling.querySelector(".edit-question").hasAttribute("disabled")) {
    // editDeletedQuestionElement.parentElement.parentElement.parentElement.nextElementSibling.querySelector(".edit-question").setAttribute("disabled", "true");
    // }
    // }
    // }
    // });
    // editDeletedQuestionImageElement.addEventListener("change", () => {
    // if(editDeletedQuestionImageElement.value.trim() != previousEditDeletedQuestionImageElementValue.trim()) {
    // if(editDeletedQuestionImageElement.parentElement.parentElement.parentElement.nextElementSibling.querySelector(".edit-question").hasAttribute("disabled")) {
    // editDeletedQuestionImageElement.parentElement.parentElement.parentElement.nextElementSibling.querySelector(".edit-question").removeAttribute("disabled");
    // }
    // } else {
    // if(previousEditDeletedQuestionElementValue.trim() == editDeletedQuestionElement.value.trim() && previousEditDeletedQuestionTypeElementValue.trim() == editDeletedQuestionTypeElement.value.trim()) {
    // if(!editDeletedQuestionImageElement.parentElement.parentElement.parentElement.nextElementSibling.querySelector(".edit-question").hasAttribute("disabled")) {
    // editDeletedQuestionImageElement.parentElement.parentElement.parentElement.nextElementSibling.querySelector(".edit-question").setAttribute("disabled", "true");
    // }
    // }
    // }
    // });
    // console.log(editDeletedQuestionTypeElement);
    // console.log(previousEditDeletedQuestionTypeElementValue);
    // editDeletedQuestionTypeElement.addEventListener("change", () => {
    // if(editDeletedQuestionTypeElement.value.trim() != previousEditDeletedQuestionTypeElementValue.trim()) {
    // if(editDeletedQuestionTypeElement.parentElement.parentElement.parentElement.nextElementSibling.querySelector(".edit-question").hasAttribute("disabled")) {
    // editDeletedQuestionTypeElement.parentElement.parentElement.parentElement.nextElementSibling.querySelector(".edit-question").removeAttribute("disabled");
    // }
    // } else {
    // if(previousEditDeletedQuestionElementValue.trim() == editDeletedQuestionElement.value.trim() && previousEditDeletedQuestionImageElementValue.trim() == editDeletedQuestionImageElement.value.trim()) {
    // if(!editDeletedQuestionTypeElement.parentElement.parentElement.parentElement.nextElementSibling.querySelector(".edit-question").hasAttribute("disabled")) {
    // editDeletedQuestionTypeElement.parentElement.parentElement.parentElement.nextElementSibling.querySelector(".edit-question").setAttribute("disabled", "true");
    // }
    // }
    // }
    // });
    // Topic Assignment to Question Ajax
    $(document).ready(function () {
        function removeTopicAjax() {
            $(".assign-topics .remove-topic").off("click", removeTopicEvent);
            $(".assign-topics .remove-topic").on("click", removeTopicEvent);
        }
        function removeTopicEvent(e) {
            e.stopPropagation();
            // console.log($(this).data("value"));
            let statusBtn = $(this)[0].previousElementSibling;
            let questionId = $(this).closest(".assign-topics").find(".toggle-status").data("question-id");
            let topic = $(this)[0].previousElementSibling.dataset.topic;
            let topicId = $(this)[0].previousElementSibling.dataset.topicId;
            // console.log(examId);
            let value = $(this).closest(".assign-topics").find(".toggle-status").data("value");
            // console.log(value);
            // console.log(examName);
            $.ajax({
                url: 'Ajax/toggle_question_topic_status.php',
                type: 'POST',
                data: {
                    questionId: questionId,
                    topicId: topicId,
                    topic: topic,
                    value: value
                },
                dataType: 'text',
                success: function (response) {
                    // console.log(response); // Log response to verify server output
                    // console.log(statusBtn);
                    // console.log(userId);
                    // console.log(examId);
                    statusBtn.parentElement.remove();
                    document.querySelector(".toggle-topics-assignment[data-question-id='" + questionId + "'][data-topic='" + topic + "']").checked = false;
                    toggleTopicFun();
                },
                error: function (xhr, status, error) {
                    console.error("Error: " + error);
                }
            });
        }
        function toggleTopicFun() {
            $(".toggle-topics-assignment").off("click", toggleTopicFunEvent);
            $(".toggle-topics-assignment").on("click", toggleTopicFunEvent);
        }
        function toggleTopicFunEvent(e) {
            let questionId = $(this).data("question-id");
            let topicId = $(this).data("topic-id");
            let topic = $(this).data("topic");
            let checkStatus = $(this).prop("checked");
            let value = checkStatus ? 1 : 0;
            $.ajax({
                url: "Ajax/toggle_question_topic_status.php",
                type: "POST",
                data: {
                    questionId: questionId,
                    topicId: topicId,
                    topic: topic,
                    value: value
                },
                dataType: "text",
                success: function (response) {
                    // console.log(value);
                    // console.log(response); // Log response to verify server output
                    if (value == 1 && !e.target.closest(".assign-topics").querySelector(`.toggle-status[data-question-id="${questionId}"][data-topic-id="${topicId}"][data-topic="${topic}"]`)) {
                        let html = `<div class="assigned-topics p-1">
                        <button class="btn btn-success toggle-status" data-value="0" data-question-id="${questionId}" data-topic-id="${topicId}" data-topic="${topic}">${topic}</button>
                        <span class="bg-danger rounded-circle text-white remove-topic">X</span>
                        </div>`;
                        // console.log(value);
                        e.target.closest(".assign-topics").insertAdjacentHTML('afterbegin', html);
                    }
                    if (value == 0 && e.target.closest(".assign-topics").querySelector(`.assigned-topics .toggle-status[data-question-id="${questionId}"][data-topic="${topic}"]`)) {
                        // console.log(value);
                        e.target.closest(".assign-topics").querySelector(`.assigned-topics:has(.toggle-status[data-question-id="${questionId}"][data-topic="${topic}"])`).remove();
                    }
                    removeTopicAjax();
                }
            });
        }
        removeTopicAjax();
        toggleTopicFun();
    });
    Array.from(assignTopics).forEach((e) => {
        e.lastElementChild.addEventListener("click", function (event) {
            event.stopPropagation();
        });
        e.addEventListener("click", () => {
            if (e.lastElementChild.classList.contains("d-none")) {
                e.lastElementChild.classList.remove("d-none");
            } else {
                e.lastElementChild.classList.add("d-none");
            }
        });
    });
    // Main Group Assignment to Question Ajax
    $(document).ready(function () {
        function removeMainGroupAjax() {
            $(".assign-main-groups .remove-main-group").off("click", removeMainGroupEvent);
            $(".assign-main-groups .remove-main-group").on("click", removeMainGroupEvent);
        }
        function removeMainGroupEvent(e) {
            e.stopPropagation();
            // console.log($(this).data("value"));
            let statusBtn = $(this)[0].previousElementSibling;
            let questionId = $(this).closest(".assign-main-groups").find(".toggle-status").data("question-id");
            let mainGroup = $(this)[0].previousElementSibling.dataset.mainGroup;
            let mainGroupId = $(this)[0].previousElementSibling.dataset.mainGroupId;
            // console.log(examId);
            let value = $(this).closest(".assign-main-groups").find(".toggle-status").data("value");
            // console.log(value);
            // console.log(examName);
            $.ajax({
                url: 'Ajax/toggle_question_main_group_status.php',
                type: 'POST',
                data: {
                    questionId: questionId,
                    mainGroupId: mainGroupId,
                    mainGroup: mainGroup,
                    value: value
                },
                dataType: 'text',
                success: function (response) {
                    // console.log(response); // Log response to verify server output
                    // console.log(statusBtn);
                    // console.log(userId);
                    // console.log(examId);
                    statusBtn.parentElement.remove();
                    document.querySelector(".toggle-main-groups-assignment[data-question-id='" + questionId + "'][data-main-group='" + mainGroup + "']").checked = false;
                    toggleMainGroupFun();
                },
                error: function (xhr, status, error) {
                    console.error("Error: " + error);
                }
            });
        }
        function toggleMainGroupFun() {
            $(".toggle-main-groups-assignment").off("click", toggleMainGroupFunEvent);
            $(".toggle-main-groups-assignment").on("click", toggleMainGroupFunEvent);
        }
        function toggleMainGroupFunEvent(e) {
            let questionId = $(this).data("question-id");
            let mainGroupId = $(this).data("main-group-id");
            let mainGroup = $(this).data("main-group");
            let checkStatus = $(this).prop("checked");
            let value = checkStatus ? 1 : 0;
            $.ajax({
                url: "Ajax/toggle_question_main_group_status.php",
                type: "POST",
                data: {
                    questionId: questionId,
                    mainGroupId: mainGroupId,
                    mainGroup: mainGroup,
                    value: value
                },
                dataType: "text",
                success: function (response) {
                    // console.log(value);
                    // console.log(response); // Log response to verify server output
                    if (value == 1 && !e.target.closest(".assign-main-groups").querySelector(`.toggle-status[data-question-id="${questionId}"][data-main-group-id="${mainGroupId}"][data-main-group="${mainGroup}"]`)) {
                        let html = `<div class="assigned-main-groups p-1">
                        <button class="btn btn-success toggle-status" data-value="0" data-question-id="${questionId}" data-main-group-id="${mainGroupId}" data-main-group="${mainGroup}">${mainGroup}</button>
                        <span class="bg-danger rounded-circle text-white remove-main-group">X</span>
                        </div>`;
                        // console.log(value);
                        e.target.closest(".assign-main-groups").insertAdjacentHTML('afterbegin', html);
                    }
                    if (value == 0 && e.target.closest(".assign-main-groups").querySelector(`.assigned-main-groups .toggle-status[data-question-id="${questionId}"][data-main-group="${mainGroup}"]`)) {
                        // console.log(value);
                        e.target.closest(".assign-main-groups").querySelector(`.assigned-main-groups:has(.toggle-status[data-question-id="${questionId}"][data-main-group="${mainGroup}"])`).remove();
                    }
                    removeMainGroupAjax();
                }
            });
        }
        removeMainGroupAjax();
        toggleMainGroupFun();
    });
    Array.from(assignMainGroups).forEach((e) => {
        e.lastElementChild.addEventListener("click", function (event) {
            event.stopPropagation();
        });
        e.addEventListener("click", () => {
            if (e.lastElementChild.classList.contains("d-none")) {
                e.lastElementChild.classList.remove("d-none");
            } else {
                e.lastElementChild.classList.add("d-none");
            }
        });
    });
    // Sub Group Assignment to Question Ajax
    $(document).ready(function () {
        function removeSubGroupAjax() {
            $(".assign-sub-groups .remove-sub-group").off("click", removeSubGroupEvent);
            $(".assign-sub-groups .remove-sub-group").on("click", removeSubGroupEvent);
        }
        function removeSubGroupEvent(e) {
            e.stopPropagation();
            // console.log($(this).data("value"));
            let statusBtn = $(this)[0].previousElementSibling;
            let questionId = $(this).closest(".assign-sub-groups").find(".toggle-status").data("question-id");
            let subGroup = $(this)[0].previousElementSibling.dataset.subGroup;
            let subGroupId = $(this)[0].previousElementSibling.dataset.subGroupId;
            // console.log(examId);
            let value = $(this).closest(".assign-sub-groups").find(".toggle-status").data("value");
            // console.log(value);
            // console.log(examName);
            $.ajax({
                url: 'Ajax/toggle_question_sub_group_status.php',
                type: 'POST',
                data: {
                    questionId: questionId,
                    subGroupId: subGroupId,
                    subGroup: subGroup,
                    value: value
                },
                dataType: 'text',
                success: function (response) {
                    // console.log(response); // Log response to verify server output
                    // console.log(statusBtn);
                    // console.log(userId);
                    // console.log(examId);
                    statusBtn.parentElement.remove();
                    document.querySelector(".toggle-sub-groups-assignment[data-question-id='" + questionId + "'][data-sub-group='" + subGroup + "']").checked = false;
                    toggleMainGroupFun();
                },
                error: function (xhr, status, error) {
                    console.error("Error: " + error);
                }
            });
        }
        function toggleSubGroupFun() {
            $(".toggle-sub-groups-assignment").off("click", toggleSubGroupFunEvent);
            $(".toggle-sub-groups-assignment").on("click", toggleSubGroupFunEvent);
        }
        function toggleSubGroupFunEvent(e) {
            let questionId = $(this).data("question-id");
            let subGroupId = $(this).data("sub-group-id");
            let subGroup = $(this).data("sub-group");
            let checkStatus = $(this).prop("checked");
            let value = checkStatus ? 1 : 0;
            $.ajax({
                url: "Ajax/toggle_question_sub_group_status.php",
                type: "POST",
                data: {
                    questionId: questionId,
                    subGroupId: subGroupId,
                    subGroup: subGroup,
                    value: value
                },
                dataType: "text",
                success: function (response) {
                    // console.log(value);
                    // console.log(response); // Log response to verify server output
                    if (value == 1 && !e.target.closest(".assign-sub-groups").querySelector(`.toggle-status[data-question-id="${questionId}"][data-sub-group-id="${subGroupId}"][data-sub-group="${subGroup}"]`)) {
                        let html = `<div class="assigned-sub-groups p-1">
                        <button class="btn btn-success toggle-status" data-value="0" data-question-id="${questionId}" data-sub-group-id="${subGroupId}" data-sub-group="${subGroup}">${subGroup}</button>
                        <span class="bg-danger rounded-circle text-white remove-sub-group">X</span>
                        </div>`;
                        // console.log(value);
                        e.target.closest(".assign-sub-groups").insertAdjacentHTML('afterbegin', html);
                    }
                    if (value == 0 && e.target.closest(".assign-sub-groups").querySelector(`.assigned-sub-groups .toggle-status[data-question-id="${questionId}"][data-sub-group="${subGroup}"]`)) {
                        // console.log(value);
                        e.target.closest(".assign-sub-groups").querySelector(`.assigned-sub-groups:has(.toggle-status[data-question-id="${questionId}"][data-sub-group="${subGroup}"])`).remove();
                    }
                    removeSubGroupAjax();
                }
            });
        }
        removeSubGroupAjax();
        toggleSubGroupFun();
    });
    Array.from(assignSubGroups).forEach((e) => {
        e.lastElementChild.addEventListener("click", function (event) {
            event.stopPropagation();
        });
        e.addEventListener("click", () => {
            if (e.lastElementChild.classList.contains("d-none")) {
                e.lastElementChild.classList.remove("d-none");
            } else {
                e.lastElementChild.classList.add("d-none");
            }
        });
    });
    $('#add_question_topic').select2({
        theme: "bootstrap-5",
        width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
        placeholder: $(this).data('placeholder'),
        closeOnSelect: false,
    });
    $('#add_question_main_group').select2({
        theme: "bootstrap-5",
        width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
        placeholder: $(this).data('placeholder'),
        closeOnSelect: false,
    });
    $('#add_question_sub_group').select2({
        theme: "bootstrap-5",
        width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
        placeholder: $(this).data('placeholder'),
        closeOnSelect: false,
    });
    let addQuestionTopicElement = document.getElementById("add_question_topic");
    let addQuestionMainGroupElement = document.getElementById("add_question_main_group");
    let addQuestionSubGroupElement = document.getElementById("add_question_sub_group");
    // console.log(addQuestionTopicElement.nextElementSibling);
    addQuestionTopicElement.nextElementSibling.classList.add("border");
    addQuestionTopicElement.nextElementSibling.classList.add("border-dark");
    addQuestionTopicElement.nextElementSibling.classList.add("border-1");
    addQuestionTopicElement.nextElementSibling.classList.add("rounded-2");
    addQuestionTopicElement.nextElementSibling.firstElementChild.firstElementChild.style.borderColor = "transparent";
    addQuestionTopicElement.nextElementSibling.firstElementChild.firstElementChild.style.backgroundColor = "transparent";
    addQuestionMainGroupElement.nextElementSibling.classList.add("border");
    addQuestionMainGroupElement.nextElementSibling.classList.add("border-dark");
    addQuestionMainGroupElement.nextElementSibling.classList.add("border-1");
    addQuestionMainGroupElement.nextElementSibling.classList.add("rounded-2");
    addQuestionMainGroupElement.nextElementSibling.firstElementChild.firstElementChild.style.borderColor = "transparent";
    addQuestionMainGroupElement.nextElementSibling.firstElementChild.firstElementChild.style.backgroundColor = "transparent";
    addQuestionSubGroupElement.nextElementSibling.classList.add("border");
    addQuestionSubGroupElement.nextElementSibling.classList.add("border-dark");
    addQuestionSubGroupElement.nextElementSibling.classList.add("border-1");
    addQuestionSubGroupElement.nextElementSibling.classList.add("rounded-2");
    addQuestionSubGroupElement.nextElementSibling.firstElementChild.firstElementChild.style.borderColor = "transparent";
    addQuestionSubGroupElement.nextElementSibling.firstElementChild.firstElementChild.style.backgroundColor = "transparent";
    // $('#edit_question_topic').select2({
    //     theme: "bootstrap-5",
    //     width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
    //     placeholder: $(this).data('placeholder'),
    //     closeOnSelect: false,
    // });
    // $('#edit_question_main_group').select2({
    //     theme: "bootstrap-5",
    //     width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
    //     placeholder: $(this).data('placeholder'),
    //     closeOnSelect: false,
    // });
    // $('#edit_question_sub_group').select2({
    //     theme: "bootstrap-5",
    //     width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
    //     placeholder: $(this).data('placeholder'),
    //     closeOnSelect: false,
    // });
    // colResize = {
    //     isEnabled: true,
    //     saveState: false,
    //     hoverClass: 'dt-colresizable-hover',
    //     hasBoundCheck: true,
    //     minBoundClass: 'dt-colresizable-bound-min',
    //     maxBoundClass: 'dt-colresizable-bound-max',
    //     isResizable: function (column) {
    //         return true;
    //     },
    //     onResizeStart: function (column, columns) {
    //     },
    //     onResize: function (column) {
    //     },
    //     onResizeEnd: function (column, columns) {
    //     },
    //     getMinWidthOf: function ($thNode) {
    //     },
    //     stateSaveCallback: function (settings, data) {
    //     },
    //     stateLoadCallback: function (settings) {
    //     }
    // }
    // let options = { ...colResize };
    // Either:
    // var table = $('#question-bank-table').DataTable({
    // colResize: options
    // });

    // Or:
    // var table = $('#question-bank-table').DataTable();
    // new $.fn.dataTable.ColResize(table, options);

    // Available methods:
    // table.colResize.enable();  // enable plugin (i.e. when options was isEnabled: false)
    // table.colResize.disable(); // remove all events
    // table.colResize.reset();   // reset column.sWidth values
    // table.colResize.save();    // save the current state (defaults to localstorage)
    // table.colResize.restore(); // restore the state from storage (defaults to localstorage)
    // let addQuestionTopicInputElement = document.getElementById("add-question-topic");
    // let topicsListElements = document.querySelectorAll(".topics-list");
    // console.log(addQuestionTopicInputElement);
    // console.log(topicsListElements);
    // addQuestionTopicInputElement.addEventListener("input", (event) => {
    //     // console.log(event.target.value);
    //     Array.from(topicsListElements).forEach((e) => {
    //         if(e.lastElementChild.innerText.toLowerCase().includes(event.target.value.toLowerCase())) {
    //             if(e.classList.contains("d-none")) {
    //                 e.classList.remove("d-none");
    //             }
    //         } else {
    //             if(!e.classList.contains("d-none")) {
    //                 e.classList.add("d-none");
    //             }
    //         }
    //         if(event.target.value == "") {
    //             if(!e.classList.contains("d-none")) {
    //                 e.classList.add("d-none");
    //             }
    //         }
    //     });
    // });
    if (addTopicElement) {
        addTopicElement.addEventListener("click", () => {
            submitTopicElement.click();
        });
    }
    if (addMainGroup) {
        addMainGroup.addEventListener("click", () => {
            submitMainGroup.click();
        });
    }
    if (addSubGroup) {
        addSubGroup.addEventListener("click", () => {
            submitSubGroup.click();
        });
    }
    if (createQuizElement) {
        createQuizElement.addEventListener("click", () => {
            submitCreateQuiz.click();
        });
    }
    if (selectQuizElement) {
        selectQuizElement.addEventListener("change", () => {
            if (selectQuizElement.value == "") {
                if (newQuizElement.getAttribute("disabled")) {
                    newQuizElement.removeAttribute("disabled");
                }
                if (examDurationInputElement.getAttribute("disabled")) {
                    examDurationInputElement.removeAttribute("disabled");
                }
            } else {
                if (!newQuizElement.getAttribute("disabled")) {
                    newQuizElement.setAttribute("disabled", true);
                }
                if (!examDurationInputElement.getAttribute("disabled")) {
                    examDurationInputElement.setAttribute("disabled", true);
                }
            }
        });
    }

    Array.from(addToQuiz).forEach(e => {
        e.addEventListener("click", function () {
            // console.log(e.parentElement.parentElement);
            let uniqueQuestionID = e.parentElement.parentElement.querySelectorAll(".dt-type-numeric")[1];
            if (e.checked == true) {
                // console.log(uniqueQuestionID);
                let questionHiddenElement = document.createElement("input");
                questionHiddenElement.setAttribute("type", "hidden");
                questionHiddenElement.setAttribute("value", uniqueQuestionID.innerText.trim());
                questionHiddenElement.setAttribute("name", "unique-question-id-" + uniqueQuestionID.innerText.trim());
                questionHiddenElement.setAttribute("id", "unique-question-id-" + uniqueQuestionID.innerText.trim());
                // console.log(submitCreateQuiz);
                createQuizForm.insertBefore(questionHiddenElement, submitCreateQuiz);
            } else {
                // console.log("unique-question-id-" + uniqueQuestionID.innerText);
                if (createQuizForm.contains(document.getElementById("unique-question-id-" + uniqueQuestionID.innerText))) {
                    createQuizForm.removeChild(document.getElementById("unique-question-id-" + uniqueQuestionID.innerText));
                }
            }
        });
    });
}


const download_button =
    document.getElementById('download_Btn');
// download_button.addEventListener("click", downloadAsPDF);
// function downloadAsPDF() {
if (document.getElementById("result-table")) {
    var content =
        document.getElementById('result-table');
    // console.log(content);
}
if (document.getElementById("user-result-table-pdf")) {
    var content =
        document.getElementById('user-result-table-pdf');
    // console.log(content);
}

// download_button.addEventListener
//     ('click', async function () {
//         const filename = 'exam_data.pdf';

//         try {
//             const opt = {
//                 margin: 1,
//                 filename: filename,
//                 image: { type: 'jpeg', quality: 0.98 },
//                 html2canvas: { scale: 2 },
//                 jsPDF: {
//                     unit: 'in', format: 'letter',
//                     orientation: 'portrait'
//                 }
//             };
//             await html2pdf().set(opt).
//                 from(content).save();
//         } catch (error) {
//             console.error('Error:', error.message);
//         }
//     });
window.onload = function () {
    try {
        download_button.addEventListener
            ('click', async function () {
                const filename = 'exam_data.pdf';

                try {
                    const opt = {
                        margin: 0.5,
                        filename: filename,
                        enableLinks: true,
                        pagebreak: {
                            mode: ['avoid-all', 'css', 'legacy'],
                            before: '.page-break-before',
                            after: '.page-break-after'
                        },
                        // image: { type: 'jpeg', quality: 0.98 },
                        html2canvas: {
                            scale: 2,
                            useCORS: true,
                            letterRendering: true,
                            scrollX: 0,
                            scrollY: 0,
                            windowWidth: document.documentElement.offsetWidth,
                            windowHeight: document.documentElement.offsetHeight
                        },
                        jsPDF: {
                            unit: 'in',
                            format: document.title == "Personality Type Test Result" ? 'letter' : [14, 8.5],
                            orientation: document.title == "Personality Type Test Result" ? 'portrait' : 'landscape',
                            compress: true,
                            precision: 2,
                            putTotalPages: true
                        }
                    };
                    const contentElement = content.cloneNode(true);

                    // Ensure all content is visible before conversion
                    contentElement.style.height = 'auto';
                    contentElement.style.overflow = 'visible';

                    // Create a temporary container
                    const container = document.createElement('div');
                    container.appendChild(contentElement);
                    container.style.position = 'absolute';
                    container.style.left = '-9999px';
                    document.body.appendChild(container);

                    await html2pdf()
                        .set(opt)
                        .from(contentElement)
                        .toPdf()
                        .get('pdf')
                        .then((pdf) => {
                            // Add page numbers if needed
                            const totalPages = pdf.internal.getNumberOfPages();
                            for (let i = 1; i <= totalPages; i++) {
                                pdf.setPage(i);
                                pdf.setFontSize(10);
                                pdf.text(`Page ${i} of ${totalPages}`,
                                    pdf.internal.pageSize.getWidth() - 1.5,
                                    pdf.internal.pageSize.getHeight() - 0.5);
                            }
                        })
                        .save()
                        .then(() => {
                            // Clean up
                            document.body.removeChild(container);
                        });

                } catch (error) {
                    console.error('Error:', error.message);
                }
            });
    } catch {
        // console.log("handled");
    }
}
// }


function toggleExamStatus() {
    $(document).ready(function () {
        $(".toggle-status").click(function () {
            let toggleStatus = this;
            let id = $(this).data("id");
            let value = $(this).data("value");
            let highlighter = $(this).parent().find(".status-highlight");
            let status = $(this).parent().find(".status");
            let examName = $(this).parent().find(".exam-name");
            let examNameArr = examName.text().split(" ");
            // console.log(status);
            // let fixStrting = examNameArr[0] + examNameArr[1];
            let fixStrting = "";
            examNameArr.forEach(((elem, index) => {
                fixStrting += " " + elem.charAt(0).toUpperCase() + elem.slice(1);
            }))
            $.ajax({
                url: 'Ajax/toggle_exam_status.php',
                type: 'POST',
                data: {
                    id: id,
                    value: value === 0 ? 1 : 0
                },
                dataType: 'text',
                success: function (response) {
                    // console.log(response); // Log response to verify server output
                    if ($(toggleStatus).hasClass("btn-success")) {
                        $(toggleStatus).toggleClass("btn-success btn-danger");
                        $(toggleStatus).text("Deactivate");
                        $(toggleStatus).attr("data-value", "1"); // Use .attr() to update the HTML attribute
                        $(toggleStatus).data("value", 1);
                        $(highlighter).addClass("bg-success").removeClass("bg-danger");
                        $(status).text("Status: Activated");
                    } else if ($(toggleStatus).hasClass("btn-danger")) {
                        $(toggleStatus).toggleClass("btn-danger btn-success");
                        $(toggleStatus).text("Activate");
                        $(toggleStatus).attr("data-value", "0"); // Use .attr() to update the HTML attribute
                        $(toggleStatus).data("value", 0);
                        $(highlighter).removeClass("bg-success").addClass("bg-danger");
                        $(status).text("Status: Deactivated");
                        $(examName).text(fixStrting);
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error: " + error);
                }
            });
        });
    });
}

function usersPage() {
    let assignQizes = document.querySelectorAll(".assign-quizzes");
    let toggleQuizzesAssignment = document.querySelectorAll(".toggle-quizzes-assignment");
    let examNameSearchElement = document.querySelectorAll(".exam_name");
    var selectedColumn = "all"; // Default: search in all columns
    Array.from(examNameSearchElement).forEach((e, i) => {
        e.addEventListener("input", (event) => {
            // event.stopPropagation();
            let quizContainer = e.nextElementSibling;
            // console.log(quizContainer.firstElementChild.innerText);
            Array.from(quizContainer.children).forEach((child, index) => {
                // console.log(child.innerText);
                if(child.innerText.toLowerCase().includes(event.target.value.toLowerCase())) {
                    if(child.classList.contains("d-none")) {
                        child.classList.remove("d-none");
                    }
                } else {
                    if(!child.classList.contains("d-none")) {
                        child.classList.add("d-none");
                    }
                }
            })
        });
    });
    $('#columnSelector').on('change', function () {
        selectedColumn = $(this).val();
        table.draw(); // Refresh the table after selecting a column
    });
    Array.from(assignQizes).forEach((e, i) => {
        e.addEventListener("click", () => {
            e.querySelector(".card").classList.contains("d-none") ? e.querySelector(".card").classList.remove("d-none") : e.querySelector(".card").classList.add("d-none");
        });
        e.querySelector(".card").addEventListener("click", (e) => {
            e.stopPropagation();
        });
    });

    var selectedColumn = "all"; // Default: search in all columns

    $('#columnSelector').on('change', function () {
        selectedColumn = $(this).val();
        table.draw(); // Refresh the table after selecting a column
    });

    $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
        var searchTerm = table.search().trim().toLowerCase();

        if (!searchTerm) return true; // If no search term, show all

        if (selectedColumn === "all") {
            // Default behavior: search in all columns
            return data.join(" ").toLowerCase().includes(searchTerm);
        } else {
            // Search only in the selected column
            return data[selectedColumn].toLowerCase().includes(searchTerm);
        }
    });

    // Redraw table on search input change
    $('#example_filter input').on('keyup', function () {
        table.draw();
    });

    $(document).ready(function () {
        function removeExamAjax() {
            $(".assign-quizzes .remove-exam").off("click", removeExamEvent);
            $(".assign-quizzes .remove-exam").on("click", removeExamEvent);
        }
        function removeExamEvent(e) {
            e.stopPropagation();
            // console.log($(this).data("value"));
            let statusBtn = $(this)[0].previousElementSibling;
            let userId = $(this).closest(".assign-quizzes").find(".toggle-status").data("id");
            let examName = $(this)[0].previousElementSibling.dataset.examName;
            let examId = $(this)[0].previousElementSibling.dataset.examId;
            // console.log(examId);
            let value = $(this).closest(".assign-quizzes").find(".toggle-status").data("value");
            // console.log(value);
            // console.log(examName);
            $.ajax({
                url: 'Ajax/toggle_individual_exam_status.php',
                type: 'POST',
                data: {
                    userId: userId,
                    examId: examId,
                    examName: examName,
                    value: value
                },
                dataType: 'text',
                success: function (response) {
                    // console.log(response); // Log response to verify server output
                    // console.log(statusBtn);
                    // console.log(userId);
                    // console.log(examId);
                    statusBtn.parentElement.remove();
                    document.querySelector(".toggle-quizzes-assignment[data-id='" + userId + "'][data-exam-name='" + examName + "']").checked = false;
                    toggleExamFun();
                },
                error: function (xhr, status, error) {
                    console.error("Error: " + error);
                }
            });
        }
        function toggleExamFun() {
            $(".toggle-quizzes-assignment").off("click", toggleExamFunEvent);
            $(".toggle-quizzes-assignment").on("click", toggleExamFunEvent);
        }
        function toggleExamFunEvent(e) {
            let userId = $(this).data("id");
            let examId = $(this).data("exam-id");
            let examName = $(this).data("exam-name");
            let checkStatus = $(this).prop("checked");
            let value = checkStatus ? 1 : 0;
            $.ajax({
                url: "Ajax/toggle_individual_exam_status.php",
                type: "POST",
                data: {
                    userId: userId,
                    examId: examId,
                    examName: examName,
                    value: value
                },
                dataType: "text",
                success: function (response) {
                    // console.log(value);
                    // console.log(response); // Log response to verify server output
                    if (value == 1 && !e.target.closest(".assign-quizzes").querySelector(`.toggle-status[data-id="${userId}"][data-exam-id="${examId}"][data-exam-name="${examName}"]`)) {
                        let html = `<div class="assigned-exams p-1">
                        <button class="btn btn-success toggle-status" data-value="0" data-id="${userId}" data-exam-id="${examId}" data-exam-name="${examName}">${examName}</button>
                        <span class="bg-danger rounded-circle text-white remove-exam">X</span>
                        </div>`;
                        // console.log(value);
                        e.target.closest(".assign-quizzes").insertAdjacentHTML('afterbegin', html);
                    }
                    if (value == 0 && e.target.closest(".assign-quizzes").querySelector(`.assigned-exams .toggle-status[data-id="${userId}"][data-exam-name="${examName}"]`)) {
                        // console.log(value);
                        e.target.closest(".assign-quizzes").querySelector(`.assigned-exams:has(.toggle-status[data-id="${userId}"][data-exam-name="${examName}"])`).remove();
                    }
                    removeExamAjax();
                }
            });
        }
        removeExamAjax();
        toggleExamFun();
    });
    // Edit User
    let editUserButton = document.querySelectorAll(".edit-user-button");
    let editId = document.getElementById("edit_id");
    let editName = document.getElementById("edit_name");
    let editEmailId = document.getElementById("edit_email_id");

    Array.from(editUserButton).forEach((e, i) => {
        e.addEventListener("click", () => {
            // console.log(e.dataset);
            editId.value = e.dataset.id;
            editName.value = e.dataset.name;
            editEmailId.value = e.dataset.emailId;
            // editEmailId.value = "Kaam nahi kar raha hai";
        })
    })
    // Delete User
    let deleteUserFrom = document.getElementById("deleteUserForm");
    let deleteUserId = deleteUserFrom.querySelector("#deleteUserId");
    let deleteUserButton = document.querySelectorAll(".delete-user-button");
    Array.from(deleteUserButton).forEach((e, i) => {
        e.addEventListener("click", () => {
            // console.log(e);
            deleteUserId.value = e.dataset.id;
        })
    });
}


$('#columnSelector').on('change', function () {
    selectedColumn = $(this).val();
    table.draw(); // Refresh the table after selecting a column
});
var selectedColumn = "all"; // Default: search in all columns

$('#columnSelector').on('change', function () {
    selectedColumn = $(this).val();
    table.draw(); // Refresh the table after selecting a column
});

$.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
    var searchTerm = table.search().trim().toLowerCase();

    if (!searchTerm) return true; // If no search term, show all

    if (selectedColumn === "all") {
        // Default behavior: search in all columns
        return data.join(" ").toLowerCase().includes(searchTerm);
    } else {
        // Search only in the selected column
        return data[selectedColumn].toLowerCase().includes(searchTerm);
    }
});

// Redraw table on search input change
$('#example_filter input').on('keyup', function () {
    table.draw();
});
