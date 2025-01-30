<footer class="footer mt-auto py-3 bg-dark <?php if(PAGE=='payment success') {echo 'd-none';}?> text-white text-center" id="footer">
    <div class="container">Copyright © at
            <?php echo date("Y");?> as <a href="https://gemengserv.com" target="_blank" class="text-white">gemengserv.com</a> All rights reserved.
        <a href="<?php echo 'Admin/Authentication/login.php';?>" target="_blank" class="<?php if(isset($_SESSION['is_login'])) {echo " d-none";}?>"><small>Admin Login</small></a>
    </div>
</footer>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="JS/script.js"></script>
    <?php
        if(PAGE == "MCQ Exam") { ?>
            <script>
                countdownTimer();
                takeExam();
            </script>
        <?php }
    ?>
    <?php
        if(PAGE == "MCQ Exam") {
            if(null !== TYPE && TYPE == "Take MCQ Exam") { ?>
            <script>
                countdownTimer();
                takeExam();
            </script>
        <?php }
        }
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
    ?>
</body>

</html>