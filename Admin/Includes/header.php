<?php
    session_start();
    if(!isset($_SESSION["is_login"]) && !isset($_SESSION["is_admin"])) {
        header("location: Authentication/login.php");
    }
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo PAGE;?></title>
    <script src="https://unpkg.com/jspdf@latest/dist/jspdf.umd.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- <link href="https://cdn.jsdelivr.net/npm/@coreui/coreui@5.3.1/dist/css/coreui.min.css" rel="stylesheet" integrity="sha384-PDUiPu3vDllMfrUHnurV430Qg8chPZTNhY8RUpq89lq22R3PzypXQifBpcpE1eoB" crossorigin="anonymous"> -->
    <!-- Select2 CSS CDNs -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <!-- Or for RTL support -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />
    <!-- colResize -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/datatables.net-colresize-unofficial@latest/jquery.dataTables.colResize.css"> -->
    <!-- Drag and Resize HTML Table Columns - jQuery resizable-columns -->
    <!-- <link rel="stylesheet" href="jQueryPlugins/drag-resize-columns/dist/jquery.resizableColumns.css" /> -->
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="CSS/utils.css">
    <?php
        if(PAGE == "Result" || PAGE == "Personality Type Test Result") {
    ?>
    <link rel="stylesheet" href="CSS/result.css">
    <link rel="stylesheet" href="CSS/utils.css">
    <?php
        }
    ?>
</head>

<body>
    <nav class="navbar nbg-dark navbar-expand-lg bg-body-tertiary position-sticky top-0 z-2">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php if(PAGE == "Dashboard") {echo "#";} else {echo "index.php";}?>"><img src="IMG/logo.png" alt="GEM Engserv" width="34px" height="24.125px"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?php if(PAGE == "Dashboard") {echo "active";}?>" aria-current="page" href="<?php if(PAGE == "Dashboard") {echo "#";} else {echo "index.php";}?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php if(PAGE == "Question Bank") {echo "active";}?>" href="question_bank.php">Question Bank</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php if(PAGE == "Users") {echo "active";}?>" href="users.php">Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php if(PAGE == "All Results") {echo "active";}?>" href="all_results.php">All Results</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php if(PAGE == "All Personality Type Test Results") {echo "active";}?>" href="all_opinion_results.php">Personality Type Test Results</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php if(PAGE == "All EIT Results") {echo "active";}?>" href="all_eit_results.php">EIT Results</a>
                    </li>
                    <?php if(PAGE == "Result" || PAGE == "All Results" || PAGE == "Personality Type Test Result") {?>
                    <li class="nav-item">
                        <button class="btn btn-danger" id="download_Btn">Download as PDF</button>
                    </li>
                    <?php }?>
                    <?php if(PAGE == "Result" || PAGE == "All Results" || PAGE == "MCQ Questions" || PAGE == "Question Bank" || PAGE == "Personality Type Test Result" || PAGE == "All EIT Results") {?>
                    <li class="nav-item">
                    <button class="btn btn-success ms-3" onclick="generateExcel()">Download as Excel</button>
                    </li>
                    <?php }?>
                </ul>
                <form class="d-flex" role="search">
                <!-- <?php if(PAGE == "MCQ Questions") {?><button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#questionModal">Add Questions</button><?php }?> -->
                    <?php if(!isset($_SESSION["is_login"])) {?><a href="Authentication/login.php" class="btn btn-warning">Login</a><?php }?>
                    <?php if(isset($_SESSION["is_login"])) {?><a href="Authentication/logout.php" class="btn btn-warning">Logout</a><?php }?>
                </form>
            </div>
        </div>
    </nav>