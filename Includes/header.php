<?php
    session_start();
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo PAGE;?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="CSS/style.css">
    <?php
        if(PAGE == "Personality Type Test Result") {
            ?>
            <link rel="stylesheet" href="CSS/result.css">
            <?php
        }
    ?>
    <!-- <link rel="stylesheet" href="CSS/utils.css"> -->
</head>

<body>
    <nav class="navbar nbg-dark navbar-expand-lg bg-body-tertiary position-sticky top-0 z-1" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php if(PAGE == "Home Page") {echo "#";} else {echo "index.php";}?>"><img src="IMG/logo.png" alt="GEM Engserv" width="34px"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="<?php if(PAGE == "Home Page") {echo "#";} else {echo "index.php";}?>">Home</a>
                    </li>
                    <?php if(PAGE == "MCQ Exam Result" || PAGE == "Personality Type Test Result") {?>
                    <li class="nav-item">
                        <button class="btn btn-danger" id="download_Btn">Download as PDF</button>
                    </li>
                    <?php }?>
                    <?php if(PAGE == "MCQ Exam Result" || PAGE == "Personality Type Test Result") {?>
                    <li class="nav-item">
                    <button class="btn btn-success ms-3" onclick="generateExcel()">Download as Excel</button>
                    </li>
                    <?php }?>
                </ul>
                <form class="d-flex" role="search">
                    <?php if(!isset($_SESSION["is_login"])) {?><a href="Authentication/login.php" class="btn btn-warning">Login</a><?php }?>
                    <?php if(isset($_SESSION["is_login"])) {?><a href="Authentication/logout.php" class="btn btn-warning">Logout</a><?php }?>
                </form>
            </div>
        </div>
    </nav>