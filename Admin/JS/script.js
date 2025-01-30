// import { jsPDF } from "https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.es.min.js";
function resultPage() {
    let srNo = document.querySelectorAll(".sr_no");
    Array.from(srNo).forEach((e, i) => {
        e.innerHTML = i + 1;
    });

}
if (document.querySelector("#result-table")) {
    if (!document.querySelector("#result-table").innerHTML.includes("No results found.")) {
        var table = $('#result-table').DataTable({
            pageLength: 500, // Set default number of rows to display
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
            pageLength: 500, // Set default number of rows to display
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
if (document.querySelector("#question-bank-table")) {
    if (!document.querySelector("#question-bank-table").innerHTML.includes("No results found.")) {
        var table = $('#question-bank-table').DataTable({
            pageLength: 500, // Set default number of rows to display
            lengthMenu: [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
            columns: [
                null, // For Sr. No. (auto-detected)
                { "data": "question_id" }, // Name
                { "data": "topic" }, // Email ID
                { "data": "main_group" }, // Exam Name
                { "data": "sub_group" }, // Exam Name
                { "data": "questions" }, // View
                { "data": "no_of_correctly_attempted" },
                { "data": "no_of_times_attempted" },
                { "data": "percentage_correctly_attempted" },
                { "data": "difficulty_level" },
                { "data": "add_to_quiz" },
                null,
                null  // For View button (auto-detected)
            ]
        });
    }
}
console.log(table);

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
        console.log(table);
        const rows = table.rows;
        let currentRow = startRow;

        // Loop through table rows
        for (let i = 0; i < rows.length; i++) {
            const row = rows[i];
            const rowData = [];

            // Loop through cells of each row
            for (let j = 0; j < row.cells.length; j++) {
                rowData.push(row.cells[j].innerText); // Get cell data
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
    })
    addQuestion.addEventListener("click", () => {
        submitQuestion.click();
    });
    let editQuestion = document.querySelectorAll(".edit-question");
    let editQuestionSubmit = document.querySelectorAll(".edit_question_submit");
    Array.from(editQuestion).forEach((e, i) => {
        e.addEventListener("click", () => {
            editQuestionSubmit[i].click();
        });
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
    // let btnCloseElements = document.querySelectorAll(".btn-close");
    // let closeModalBtnElements = document.querySelectorAll(".close-modal-btn");
    // console.log(resetButtonElements.length);
    // console.log(btnCloseElements.length);
    // console.log(closeModalBtnElements.length);
    // console.log(resetButtonElements);
    let correctOption = 0;
    let editCorrectOption = 0;
    document.addEventListener("DOMContentLoaded", () => {
        // Get all question type select elements for both add and edit forms
        const questionTypes = document.querySelectorAll(".question-type");

        Array.from(questionTypes).forEach((e) => {
            let removedElements = [];
        
            e.addEventListener("change", () => {
                let currentElement = e.closest(".add-question-type-box").nextElementSibling;
        
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
                        toggle.style.display = "none";
                    });
        
                    let optionContainers = e.parentElement.parentElement.querySelectorAll(".option-container");
                    optionContainers.forEach((option, index) => {
                        // Check if already added to avoid duplication
                        if (!option.querySelector(".marking-field")) {
                            let markingFieldElement = document.createElement("input");
                            let markingLabelElement = document.createElement("label");
        
                            markingLabelElement.innerText = String.fromCharCode(65 + index) + " Mark"; // Fix charAt issue
                            markingFieldElement.setAttribute("type", "text");
                            markingFieldElement.setAttribute("min", "0");
                            markingFieldElement.value = "1"; // Default marks
                            markingFieldElement.classList.add("marking-field");
                            markingFieldElement.classList.add("form-control");
                            markingLabelElement.classList.add("marking-label");
                            markingLabelElement.classList.add("form-label");
        
                            option.appendChild(markingLabelElement);
                            option.appendChild(markingFieldElement);
        
                            // Allow only numeric input
                            markingFieldElement.addEventListener("input", () => {
                                markingFieldElement.value = markingFieldElement.value.replace(/\D/g, ""); // Remove non-numeric chars
                            });
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
                let currentElement = e.closest(".edit-question-type-box").nextElementSibling;

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
        console.log(addQuestion.parentElement.parentElement);
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
            if (e.classList.contains("add-question-add-option")) {
                addQuestionsOptionsElement = e.previousElementSibling.querySelectorAll(".add-questions-options");
            } else if (e.classList.contains("edit-question-add-option")) {
                addQuestionsOptionsElement = e.previousElementSibling.querySelectorAll(".add-questions-options");
            }
    
            let option;
    
            // Case 1: If no options exist, start with 'A'
            if (addQuestionsOptionsElement.length === 0) {
                option = 'A';
            } else {
                // Case 2: If options exist, get the last option's ID and determine next option
                let lastAddQuestionsOptionsElement = addQuestionsOptionsElement[addQuestionsOptionsElement.length - 1];
                option = lastAddQuestionsOptionsElement.getAttribute("id").slice(-1); // Get last option letter
            }
    
            // Calculate next option letter (A -> B -> C -> D -> E...)
            let nextOption = String.fromCharCode(option.charCodeAt(0) + 1);
    
            // ✅ Fix: Ensure form exists before checking "weighted" type
            let formElement = e.previousElementSibling;
            let questionTypeElement = formElement ? formElement.querySelector(".question-type") : null;
            let isWeighted = questionTypeElement && questionTypeElement.value === "weighted";
    
            // ✅ Generate marking input field if "weighted" is selected
            let markingFieldHTML = isWeighted
                ? `<label class="marking-label form-label">${nextOption.toUpperCase()} Mark</label>
                   <input type="text" class="marking-field form-control" value="1" min="0" required>`
                : "";
            console.log(markingFieldHTML);
            console.log(isWeighted);
            console.log(questionTypeElement);
            console.log(formElement);
            console.log(e);
            let newElement;
    
            // Case for Add Form
            if (e.classList.contains("add-question-add-option")) {
                newElement = `<div class="mb-3 option-container">
                                <label for="opt_${nextOption}" class="form-label">Option ${nextOption.toUpperCase()}</label>
                                <i class="fa-regular fa-circle toggle-correct"></i>
                                <input type="text" class="form-control d-inline-block add-questions-options" id="opt_${nextOption}" name="options[]" required>
                                <input type="hidden" class="form-control invisible add-questions-options" id="correct_opt_${nextOption}" name="correct_options[]">
                                <i class="fa-solid fa-xmark remove-option" style="cursor: pointer;"></i>
                                ${markingFieldHTML}
                            </div>`;
            }
            // Case for Edit Form
            else if (e.classList.contains("edit-question-add-option")) {
                newElement = `<div class="mb-3 option-container">
                                <label for="edit_options_${nextOption}" class="form-label">Option ${nextOption.toUpperCase()}</label>
                                <i class="fa-regular fa-circle toggle-correct"></i>
                                <input type="text" class="form-control d-inline-block add-questions-options" id="edit_options_${nextOption}" name="options[]" required>
                                <input type="hidden" class="form-control invisible add-questions-options" id="correct_opt_${nextOption}" name="correct_options[]">
                                <i class="fa-solid fa-xmark remove-option" style="cursor: pointer;"></i>
                                ${markingFieldHTML}
                            </div>`;
            }
    
            // Insert the new option element after the last option, or if no options, after the add button
            if (addQuestionsOptionsElement.length === 0) {
                e.nextElementSibling.insertAdjacentHTML('afterend', newElement);
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
            console.log(editQuestion);
            if (editCorrectOption == 0 && e.parentElement.parentElement.querySelectorAll(".option-container").length > 0) {
                alert("Please select correct option");
            } else {
                // console.log(editCorrectOption);
                editQuestionSubmit[i].click();
            }
        });
    });
    Array.from(individualEditQuestion).forEach((e, i) => {
        e.addEventListener("click", (event) => {
            editCorrectOption = 0;
            // console.log(e.dataset.bsTarget);
            let questionEditModalId = event.target.dataset.bsTarget.split("#")[1];
            // console.log(questionEditModalId);
            let questionEditModal = document.getElementById(questionEditModalId);
            let textSuccessElements = questionEditModal.querySelectorAll(".text-success");
            editCorrectOption = textSuccessElements.length;
            console.log(editCorrectOption);
        })
    })
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
        console.log(event.target.nextElementSibling.id.includes("edit"));
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
            console.log(allOptions[i]);
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
            Array.from(e.target.parentElement.parentElement.querySelectorAll("input[type='checkbox']:checked:has(+[data-question-id][data-option])")).forEach((elem) => {
                elem.checked = false;
            })
            // console.log(e);
            e.target.checked = true;
            let questionId = $(this).next().data("question-id");
            let option = $(this).next().data("option");
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
                    isCorrect: isCorrect
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
    })
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
    addTopicElement.addEventListener("click", () => {
        submitTopicElement.click();
    });
    addMainGroup.addEventListener("click", () => {
        submitMainGroup.click();
    });
    addSubGroup.addEventListener("click", () => {
        submitSubGroup.click();
    });
    createQuizElement.addEventListener("click", () => {
        submitCreateQuiz.click();
    });
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

    examDurationInputElement.addEventListener("input", (event) => {
        event.target.value = event.target.value.split("").filter(e => !isNaN(e) && e != " ").join("");
    })

    Array.from(addToQuiz).forEach(e => {
        e.addEventListener("click", function () {
            // console.log(e.parentElement.parentElement);
            let uniqueQuestionID = e.parentElement.parentElement.querySelectorAll(".dt-type-numeric")[1];
            if (e.checked == true) {
                console.log(uniqueQuestionID);
                let questionHiddenElement = document.createElement("input");
                questionHiddenElement.setAttribute("type", "hidden");
                questionHiddenElement.setAttribute("value", uniqueQuestionID.innerText.trim());
                questionHiddenElement.setAttribute("name", "unique-question-id-" + uniqueQuestionID.innerText.trim());
                questionHiddenElement.setAttribute("id", "unique-question-id-" + uniqueQuestionID.innerText.trim());
                console.log(submitCreateQuiz);
                createQuizForm.insertBefore(questionHiddenElement, submitCreateQuiz);
            } else {
                // console.log("unique-question-id-" + uniqueQuestionID.innerText);
                if (createQuizForm.contains(document.getElementById("unique-question-id-" + uniqueQuestionID.innerText))) {
                    createQuizForm.removeChild(document.getElementById("unique-question-id-" + uniqueQuestionID.innerText));
                }
            }
        });
    })
}


const download_button =
    document.getElementById('download_Btn');
// download_button.addEventListener("click", downloadAsPDF);
// function downloadAsPDF() {
if (document.getElementById("result-table")) {
    var content =
        document.getElementById('result-table');
    console.log(content);
}
if (document.getElementById("user-result-table-pdf")) {
    var content =
        document.getElementById('user-result-table-pdf');
    console.log(content);
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
                            format: document.title == "MBTI Result" ? 'letter' : [14, 8.5],
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
        console.log("handled");
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
            console.log(status);
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
                        $(highlighter).addClass("bg-success");
                        $(status).text("Status: Activated");
                    } else if ($(toggleStatus).hasClass("btn-danger")) {
                        $(toggleStatus).toggleClass("btn-danger btn-success");
                        $(toggleStatus).text("Activate");
                        $(toggleStatus).attr("data-value", "0"); // Use .attr() to update the HTML attribute
                        $(toggleStatus).data("value", 0);
                        $(highlighter).removeClass("bg-success");
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
    Array.from(assignQizes).forEach((e, i) => {
        e.addEventListener("click", () => {
            e.querySelector(".card").classList.contains("d-none") ? e.querySelector(".card").classList.remove("d-none") : e.querySelector(".card").classList.add("d-none");
        });
        e.querySelector(".card").addEventListener("click", (e) => {
            e.stopPropagation();
        });
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
            console.log(value);
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
                        console.log(value);
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
            console.log(e.dataset);
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
