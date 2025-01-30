if(document.querySelector("#user-result-table")) {
    if(!document.querySelector("#user-result-table").innerHTML.includes("No results found.")) {
    var table = $('#user-result-table').DataTable({
        pageLength: 500, // Set default number of rows to display
        lengthMenu: [ [10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"] ],
        columns: [
            { "data": "name" }, // Name
            { "data": "email_id" }, // Email ID
            { "data": "score" }, // Score
            { "data": "exam_name" },// Exam Name
            { "data": "exam_attended_time" }
        ]
    });
    }
    }
    if(document.querySelector("#user-opinion-result-table")) {
        if(!document.querySelector("#user-opinion-result-table").innerHTML.includes("No results found.")) {
        var table = $('#user-opinion-result-table').DataTable({
            pageLength: 500, // Set default number of rows to display
            lengthMenu: [ [10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"] ],
            columns: [
                null, // For Sr. No. (auto-detected)
                { "data": "name" }, // Name
                { "data": "username" }, // Email ID
                { "data": "exam_name" }, // Exam Name
                { "data": "exam_attended_time" }, // Exam Name
                null  // For View button (auto-detected)
            ]
        });
        }
    }
    console.log(table);
    // let numOfSelections = 0;
    function resultPage() {
        let srNo = document.querySelectorAll(".sr_no");
        Array.from(srNo).forEach((e, i) => {
            e.innerHTML = i + 1;
        });
    
    }
    function countdownTimer() {
        let countdown = document.getElementById("countdown");
        if (!(countdown.classList.contains("d-none"))) {
            let countdownBegin = "";
            let i = 0;
                // countdownBegin += countdown.innerHTML[i];
                while(countdown.innerHTML[i] != " ") {
                countdownBegin += countdown.innerHTML[i];
                i++;
            }
            // Set the starting time in seconds (15 minutes = 900 seconds)
            timeLeft = Number(countdownBegin) * 60;
    
            // Update countdown every second
            const countdownTimer = setInterval(() => {
                // Calculate minutes and seconds
                let minutes = Math.floor(timeLeft / 60);
                let seconds = timeLeft % 60;
    
                // Format seconds to always show two digits
                seconds = seconds < 10 ? "0" + seconds : seconds;
    
                // Display the countdown
                countdown.innerHTML = `${minutes} minutes:${seconds} seconds`;
    
                // Check if the time is up
                if (timeLeft <= 0) {
                    clearInterval(countdownTimer);
                    countdown.innerHTML = "Time's up!";
                }
    
                // Decrease time left by 1 second
                timeLeft--;
                let examSubmit = document.getElementById("exam-submit");
                if (timeLeft == 0) {
                    Array.from(document.querySelectorAll("input[type='radio']")).forEach((e) => e.removeAttribute("required"));
                    examSubmit.click();
                }
            }, 1000);
        }
    }
    
    function takeExam() {
        let selectedQuestionIds = new Set();
        // let score = 0;
        $(".options[type='radio']").on("click", function() {
            let optionElements = this.nextElementSibling;
            let optionSpanElements = optionElements.firstElementChild;
            if(this.nextElementSibling.firstElementChild.dataset.selected === "true") {
                $(this).prop("checked", false);
                // numOfSelections--;
                optionSpanElements.removeAttribute("data-selected");
                selectedQuestionIds.delete(optionSpanElements.dataset.questionId);
                // console.log(new Array(...selectedQuestionIds));
            }
        });
        $(".options").on("change", function() {
            let optionElements = this.nextElementSibling;
            // console.log(this.nextElementSibling);
            if($(this).is(":checked")) {
                let optionSpanElements = optionElements.firstElementChild;
                optionSpanElements.dataset.selected = "true";
                // numOfSelections++;
                selectedQuestionIds.add(optionSpanElements.dataset.questionId);
                // console.log(new Array(...selectedQuestionIds));
            } 
            // If it's a radio button, ensure other options in the same group lose "data-selected"
            if ($(this).attr("type") === "radio") {
                // Find all radios in the same group
                let groupName = $(this).attr("name");
                $(`input[name="${groupName}"]`).not(this).each(function() {
                    let sibling = this.nextElementSibling;
                    if (sibling && sibling.firstElementChild) {
                        sibling.firstElementChild.removeAttribute("data-selected");
                    }
                });
            }
        })
        $("#exam-submit").on("click", function () {
            // let numOfQuestions = this.dataset.numOfQuestions;
            // let numOfSelections = document.querySelectorAll("input[type='radio']:checked").length;
            // console.log(numOfSelections);
            // if(timeLeft !== 0 && numOfSelections !== Number(numOfQuestions)) {
                // let examRealSubmit = document.getElementById("exam-real-submit");
                // examRealSubmit.click();
                // document.querySelector("form").submit();
            // } else {
                let selectedQuestionIds = [];
                let selectedOptions = [];
                let selectedOptionsArr = [];
                let optionsMap = {};
                let examId = this.dataset.examId;
                let userId = this.dataset.userId;
                // let optionInputElements = $(".options");
                let questionsElements = $("[name='all_questions_attempted_id[]']");
                // console.log(questionsElements.length);
                // function areArraysEqual(arr1, arr2) {
                //     console.log("Comparing arrays:");
                //     console.log("Array 1:", arr1);
                //     console.log("Array 2:", arr2);
                    
                //     // Check lengths
                //     if (arr1.length !== arr2.length) {
                //         console.log("Arrays have different lengths");
                //         return false;
                //     }
                    
                    
                //     // Convert and sort 
                //     const sortedArr1 = arr1.map(String).sort();
                //     const sortedArr2 = arr2.map(String).sort();
                    
                //     console.log("Sorted Array 1:", sortedArr1);
                //     console.log("Sorted Array 2:", sortedArr2);
                    
                //     let isEqual = sortedArr1.join(',') === sortedArr2.join(',');
                //     console.log("Arrays are equal:", isEqual);
                //     console.log(sortedArr1.join(','));
                //     console.log(sortedArr2.join(','));
                //     // Check for empty arrays
                //     if(sortedArr1.length === 0 || sortedArr2.length === 0) {
                //         isEqual = false;
                //     }
                //     return isEqual;
                // }
                for(let i = 0; i < questionsElements.length; i++) {
                    // debugger;
                    // let correctOptionsArr = [];
                    let optionAsQuestionSpanElements = document.querySelectorAll(".options + label > [data-question-series='" + (1) + "']");
                    // console.log(optionAsQuestionSpanElements[i], i);
                    for (let j = 0; j < optionAsQuestionSpanElements.length; j++) {
                        // if(optionAsQuestionSpanElements[j].dataset.isCorrect === "1") {
                            // correctOptionsArr.push(optionAsQuestionSpanElements[j].dataset.optionId);
                        // }
                        if(optionAsQuestionSpanElements[j].parentElement.previousElementSibling.checked) {
                            selectedOptionsArr.push(optionAsQuestionSpanElements[j].dataset.optionId);
                        }
                        // console.log(optionAsQuestionSpanElements[j]);
                        // console.log(optionAsQuestionSpanElements[j].parentElement.previousElementSibling);
                        // console.log(optionAsQuestionSpanElements[j].dataset.isCorrect);
                        // console.log(j);
                        // if(optionAsQuestionSpanElements[j].parentElement.previousElementSibling.checked && optionAsQuestionSpanElements[j].dataset.isCorrect === "1") {
                            // correctSelected += 1;
                        // }
                        // console.log(optionAsQuestionSpanElements[j]);
                        // areArraysEqual(correctOptionsArr, selectedOptionsArr) ? score += 1 : score += 0;
                        // areArraysEqual(correctOptionsArr, selectedOptionsArr) ? console.log("score += 1") : console.log("score += 0");
                        // console.log(areArraysEqual(correctOptionsArr, selectedOptionsArr));
                        let firstChild = optionAsQuestionSpanElements[i]?.nextElementSibling?.firstElementChild;
                        // console.log(correctSelected);
                        // console.log(optionAsQuestionSpanElements[i])
                        // if(optionAsQuestionSpanElements[i].dataset.numOfCorrectAnswers === correctSelected) {
                        //     score += 1;
                        // }
                        // console.log(score);
                        // console.log(selectedOptionsArr);
                        selectedOptionsArr.length = 0;
                    }
                    // console.log(correctOptionsArr);
                    // console.log(selectedOptionsArr);
                }
                // for (let i = 0; i < questionsElements.length; i++) {
                //     const element = array[i];
                    
                // }
                // Collect data from selected inputs
                $(".options:checked").each(function () {
                    let optionLabel = this.nextElementSibling; // Get the text of the label
                    let optionLabelSpan = optionLabel.firstElementChild;
                    let questionId = optionLabelSpan.dataset.questionId;
                    let optionId = optionLabelSpan.dataset.optionId;
                
                    // Initialize the array for the question ID if it doesn't exist
                    if (!optionsMap[questionId]) {
                        optionsMap[questionId] = [];
                    }
                
                    // Add the option ID to the array for the question ID
                    optionsMap[questionId].push(optionId);
                });
                
                // Convert the map into the desired array format
                for (let questionId in optionsMap) {
                    selectedQuestionIds.push({
                        user_id: userId,
                        exam_id: examId,
                        question_id: questionId,
                        selectedOptions: optionsMap[questionId]
                    });
                }
            
                // console.log("Selected Question IDs:", selectedQuestionIds);
                // console.log("Selected Options:", selectedOptions);
                console.log(selectedQuestionIds);
                // Send data via AJAX
                if(document.querySelector("form[method]").baseURI.includes("Personality") !== true) {
                    $.ajax({
                        url: "Ajax/submit_exam_cpanel.php",
                        type: "POST",
                        contentType: "application/json",
                        data: JSON.stringify({ questions: selectedQuestionIds }),
                        dataType: "json",
                        success: (response) => {
                            console.log("Server Response:", response.message);
                            if (response.message === "All results submitted successfully.") {
                                // Redirect or show success message
                                location.href = "all_quizes.php";
                            } else {
                                // Handle other success messages
                                alert(response.message);
                            }
                        },
                        error: (xhr, status, error) => {
                            console.error("AJAX Error:", xhr.status, xhr.responseText);
                            alert(`Error: ${xhr.responseText || "Unexpected error occurred."}`);
                        },
                    });
                }
    
    
            // }
        });
        
    }
    
    const download_button =
            document.getElementById('download_Btn');
            // download_button.addEventListener("click", downloadAsPDF);
    // function downloadAsPDF() {
    
    if(document.getElementById("user-result-table-pdf")) {
        var content = document.getElementById("user-result-table-pdf");
    }
    if(document.getElementById('user-result-table')) {
        var content = document.getElementById('user-result-table');
    }
        console.log(content);
    
    try {
    download_button.addEventListener
        ('click', async function () {
            const filename = 'exam_data.pdf';
    
            try {
                const opt = {
                    margin: 1,
                    filename: filename,
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2 },
                    jsPDF: {
                        unit: 'in', format: 'letter',
                        orientation: 'portrait'
                    }
                };
                await html2pdf().set(opt).
                    from(content).save();
            } catch (error) {
                console.error('Error:', error.message);
            }
        });
    } catch(error) {
        console.log(error);
    }
    // }
    
    async function generateExcel() {
        const workbook = new ExcelJS.Workbook();
        const worksheet = workbook.addWorksheet('Sheet 1');
    
        // Function to add table data to the worksheet in the same column with gaps
        function addTableToSheet(tableId, startRow, startColumn) {
            const table = document.getElementById(tableId);
            const rows = table.rows;
            let currentRow = startRow;
            console.log(table);
    
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
        if(document.title != "Personality Type Test Result") {
            addTableToSheet('user-result-table', startRow, startColumn);
        }
        // user-result-table
        if(document.title == "Personality Type Test Result") {
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
    
    
    
    
    
    function ctrlShiftKey(e, keyCode) {
      return e.ctrlKey && e.shiftKey && e.keyCode === keyCode.charCodeAt(0);
    }
    
    document.onkeydown = (e) => {
      // Disable F12, Ctrl + Shift + I, Ctrl + Shift + J, Ctrl + U
      if (
        event.keyCode === 123 ||
        ctrlShiftKey(e, 'I') ||
        ctrlShiftKey(e, 'J') ||
        ctrlShiftKey(e, 'C') ||
        (e.ctrlKey && e.keyCode === 'U'.charCodeAt(0))
      )
        return false;
    };
    
    
    
    
    
    
    
    
    
    // Compile and run this code and see if it is easy to use F12 for developer tools or use the context menu to inspect an element
    var addHandler = function (element, type, handler) {
        if (element.addEventListener) {
            element.addEventListener(type, handler, false);
        } else if (element.attachEvent) {
            element.attachEvent("on" + type, handler);
        } else {
            element["on" + type] = handler;
        }
    };
    
    var preventDefault = function (event) {
        if (event.preventDefault) {
            event.preventDefault();
        } else {
            event.returnValue = false;
        }
    };
    
    addHandler(window, "contextmenu", function (event) {
        preventDefault(event);
    });
    document.onkeydown = function (event) {
        if (event.keyCode == 123) { // Prevent F12
            return false;
        }
        else if (event.ctrlKey && event.shiftKey && event.keyCode == 73) { // Prevent Ctrl+Shift+I        
            return false;
        }
    };
    
    window.addEventListener('devtoolschange', function (e) {
        console.log('is DevTools open?', e.detail.open);
    });
    
    
    
    
    
    // function deleteResult() {
    
    // }
    // function deleteFun(formId, deleteId, deleteButton) {
    //     let formIdElement = document.getElementById(formId);
    //     let deleteIdElement = document.getElementById(deleteId);
    //     let deleteButtonElement = document.getElementById(deleteButton);
    // }
    /*
    user_session {
        user_id: id,
        questions:[
            {
                id: question_id,
                selected: [
                    optionA, optionC, optionD
                ]
            },
            {
                question_id: [
                optionA, optionC, optionD
                ]
            },
            
        ]
        }
    }
    */