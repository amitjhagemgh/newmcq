<footer class="footer mt-auto py-3 bg-body-tertiary text-center" id="footer">
    <div class="container">
        <span>
            Copyright © at
            <?php echo date("Y");?> as <a href="https://gemengserv.com" target="_blank">gemengserv.com</a> All rights reserved.
        </span>
        <!-- <a href="<?php echo 'Admin/Authentication/login.php';?>" target="_blank" class="<?php if(isset($_SESSION['is_login'])) {echo " d-none";}?>"><small>Admin Login</small></a> -->
    </div>
</footer>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js" defer></script>
<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous" defer></script>
    <!-- Include html2pdf library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js" defer></script>
    <!-- <script src="https://unpkg.com/jspdf@latest/dist/jspdf.umd.min.js"></script> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/2.3.6/purify.min.js"></script> Add dompurify -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/canvg@4.0.2/+esm" defer></script>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" 
        integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ" 
        crossorigin="anonymous" defer>
    </script>
    <!-- <script src="jQueryPlugins/jQuery-Plugin-Resizable-Table-Columns/jQuery.resizableColumns.js"></script> -->
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js" defer></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css"/>
    <!-- <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script> -->
    <script src="https://kit.fontawesome.com/35f8a6764d.js" crossorigin="anonymous" defer></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/@coreui/coreui@5.3.1/dist/js/coreui.bundle.min.js" integrity="sha384-8QmUFX1sl4cMveCP2+H1tyZlShMi1LeZCJJxTZeXDxOwQexlDdRLQ3O9L78gwBbe" crossorigin="anonymous"></script> -->
    <!-- SELECT2 JavaScript CDN-->
    <!-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.0/dist/jquery.slim.min.js"></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" defer></script>
    <!-- colResize -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/datatables.net-colresize-unofficial@latest/jquery.dataTables.colResize.js"></script> -->
    <!-- Drag and Resize HTML Table Columns - jQuery resizable-columns -->
    <!-- <script src="jQueryPlugins/drag-resize-columns/dist/jquery.resizableColumns.min.js"></script> -->
    <script src="JS/script.js" defer></script>
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
                        console.log(e);
                        console.log(e.dataset.id);
                        console.log(e.dataset.exam_name);
                        console.log(e.dataset.testSeries);
                        e.addEventListener("click", (e) => {
                            deleteEmailIdElement.value = e.target.dataset.id;
                            deleteExamNameElement.value = e.target.dataset.exam_name;
                            deleteTestSeriesElement.value = e.target.dataset.testSeries;
                        });
                    })
                </script>
                <?php
            }
    ?>
</body>

</html>