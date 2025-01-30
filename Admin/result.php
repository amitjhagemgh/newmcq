<?php
    define("PAGE", "Result");
    include "Connection/conn.inc.php";
    include "Includes/header.php";
    if(!isset($_SESSION["is_login"])) {
        header("location: Authentication/login.php");
    }
    $e = 0;
    $i = 0;
    $s = 0;
    $n = 0;
    $t = 0;
    $f = 0;
    $j = 0;
    $p = 0;
?>
    <div class="container">
        <table class="table-content" id="result-calculation-table">
            <tr>
                <td class="invisible"></td>
                <td>a</td>
                <td>b</td>
                <td class="invisible"></td>
                <td>a</td>
                <td>b</td>
                <td class="invisible"></td>
                <td>a</td>
                <td>b</td>
                <td class="invisible"></td>
                <td>a</td>
                <td>b</td>
                <td class="invisible"></td>
                <td>a</td>
                <td>b</td>
                <td class="invisible"></td>
                <td>a</td>
                <td>b</td>
                <td class="invisible"></td>
                <td>a</td>
                <td>b</td>
            </tr>
            <?php
                $username = $_GET['username'];
                $exam_name = $_GET['exam_name'];
                $sql_count = "SELECT COUNT(*) AS total FROM questions WHERE exam_name = '$exam_name'";
                $result_count = mysqli_query($conn, $sql_count);
                $row_count = mysqli_fetch_assoc($result_count);
                $count = $row_count["total"];
                $sql = "SELECT * FROM result WHERE username = '$username' AND exam_name = '$exam_name'";
                $result = mysqli_query($conn, $sql);
                $sr_no = 1;
                while($sr_no <= $count) {
                    $row = mysqli_fetch_assoc($result);
                    // Start a new row every 7 cells
                    if ($sr_no % 7 == 1) {
                        echo "<tr>";
                    }
                    echo '<td class="sr_no"></td>'; // Placeholder for serial number or other data
            
                    // Option A cell
                    echo "<td>";
                    if (isset($row["answer"]) && $row["answer"] == "Option A") {
                        echo "&#10004;";
                    } else {
                        echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                    }
                    echo "</td>";
            
                    // Option B cell
                    echo "<td>";
                    if (isset($row["answer"]) && $row["answer"] == "Option B") {
                        echo "&#10004;";
                    } else {
                        echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                    }
                    echo "</td>";
            
                    // Check if row should end after 7 cells and close it
                    if ($sr_no % 7 == 0) {
                        echo "</tr>";
                    }

                    if($sr_no%7==1 && (isset($row["answer"]) && $row["answer"] == "Option A")) {
                        $e++;
                    }
                    if($sr_no%7==1 && (isset($row["answer"]) && $row["answer"] == "Option B")) {
                        $i++;
                    }
                    if(($sr_no%7==2 || $sr_no%7==3) && (isset($row["answer"]) && $row["answer"] == "Option A")) {
                        $s++;
                    }
                    if(($sr_no%7==2 || $sr_no%7==3) && (isset($row["answer"]) && $row["answer"] == "Option B")) {
                        $n++;
                    }
                    if(($sr_no%7==4 || $sr_no%7==5) && (isset($row["answer"]) && $row["answer"] == "Option A")) {
                        $t++;
                    }
                    if(($sr_no%7==4 || $sr_no%7==5) && (isset($row["answer"]) && $row["answer"] == "Option B")) {
                        $f++;
                    }
                    if(($sr_no%7==6 || $sr_no%7==0) && (isset($row["answer"]) && $row["answer"] == "Option A")) {
                        $j++;
                    }
                    if(($sr_no%7==6 || $sr_no%7==0) && (isset($row["answer"]) && $row["answer"] == "Option B")) {
                        $p++;
                    }
            
                    $sr_no++;
                }
            
                // Close any remaining open row (if rows are not a multiple of 7)
                if (($sr_no - 1) % 7 != 0) {
                    echo "</tr>";
                }
                ?>
        </table>
    
        <div id="result-values-container">
            <table id="result-values-1">
                <tbody>
                    <tr>
                        <td>E</td>
                        <td>I</td>
                    </tr>
                    <tr>
                        <td><?= $e;?></td>
                        <td><?= $i;?></td>
                    </tr>
                </tbody>
            </table>
            <table  id="result-values-2">
                <tbody>
                    <tr>
                        <td>S</td>
                        <td>N</td>
                    </tr>
                    <tr>
                        <td><?= $s;?></td>
                        <td><?= $n;?></td>
                    </tr>
                </tbody>
            </table>
            <table  id="result-values-3">
                <tbody>
                    <tr>
                        <td>T</td>
                        <td>F</td>
                    </tr>
                    <tr>
                        <td><?= $t;?></td>
                        <td><?= $f?></td>
                    </tr>
                </tbody>
            </table>
            <table  id="result-values-4">
                <tbody>
                    <tr>
                        <td>J</td>
                        <td>P</td>
                    </tr>
                    <tr>
                        <td><?= $j;?></td>
                        <td><?= $p;?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <?php
        include "Includes/footer.php";
    ?>