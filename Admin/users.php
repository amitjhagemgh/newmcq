<?php
    define("PAGE", "Users");
    include "Connection/conn.inc.php";
    include "Includes/header.php";
    include "Includes/functions.inc.php";
    if(!isset($_SESSION["is_login"])) {
        header("location: Authentication/login.php");
    }

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        if(isset($_POST["add-user"])) {
            $name = get_safe_value($conn, $_POST["name"]);
            $email_id = get_safe_value($conn, $_POST["email_id"]);
            $check_query = "SELECT email_id FROM users WHERE email_id = '$email_id'";
            $check_result = mysqli_query($conn, $check_query);
            if(mysqli_num_rows($check_result) > 0) {
                echo "<div class=\"alert alert-warning alert-dismissible fade show\" role=\"alert\">
                <strong>Email ID already exists!</strong> Please choose a different Email ID.
                <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>
                </div>";
            } else {
            
                $query = "INSERT INTO users (name, email_id, status) VALUES ('$name', '$email_id', '1')";
                $result = mysqli_query($conn, $query);
        
                if($result) {
                    echo "<div class=\"alert alert-success alert-dismissible fade show\" role=\"alert\">
        <strong>successfully!</strong> $name is added.
        <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>
        </div>";
                }
            }
        } elseif(isset($_POST["edit-user"])) {
            $id = get_safe_value($conn, $_POST["edit_id"]);
            $name = get_safe_value($conn, $_POST["edit_name"]);
            $email_id = get_safe_value($conn, $_POST["edit_email_id"]);
            $query = "UPDATE users SET name = '$name', email_id = '$email_id' WHERE id = '$id'";
            $result = mysqli_query($conn, $query);
            if($result) {
                echo "<div class=\"alert alert-success alert-dismissible fade show\" role=\"alert\">
        <strong>successfully!</strong> User is updated.
         <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>
        </div>";
            }
        } elseif(isset($_POST["delete-user"])) {
            $id = get_safe_value($conn, $_POST["id"]);
            $select_user_mapping_sql = "SELECT * FROM user_exam_mapping WHERE user_id = '$id'";
            $select_user_mapping_result = mysqli_query($conn, $select_user_mapping_sql);
            if(mysqli_num_rows($select_user_mapping_result) > 0) {
                $delete_user_mapping_sql = "DELETE FROM user_exam_mapping WHERE user_id = '$id'";
                $delete_user_mapping_result = mysqli_query($conn, $delete_user_mapping_sql);
            }
            $select_outcome_sql = "SELECT * FROM result WHERE user_id = '$id'";
            $select_outcome_result = mysqli_query($conn, $select_outcome_sql);
            if(mysqli_num_rows($select_outcome_result) > 0) {
                $delete_outcome_sql = "DELETE FROM result WHERE user_id = '$id'";
                $delete_outcome_result = mysqli_query($conn, $delete_outcome_sql);
            }
            $query = "DELETE FROM users WHERE id = '$id'";
            $result = mysqli_query($conn, $query);
            if($result) {
                echo "<div class=\"alert alert-success alert-dismissible fade show\" role=\"alert\">
        <strong>successfully!</strong> User is deleted.
        <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>
        </div>";
            }
        }
    }
?>
    <div class="container my-5">
        <h2 class="text-center">Users</h2>
        <div class="container d-flex justify-content-end p-0">
            <label class="">Search in: 
                <select id="columnSelector">
                    <option value="all">All Columns</option>
                    <option value="1">Name</option>
                    <option value="2">Email ID</option>
                </select>
            </label>
        </div>
        <?php
            $exam_portal_array = array();
            $exam_portal_sql = "SELECT * FROM exam_portal";
            $exam_portal_result = mysqli_query($conn, $exam_portal_sql);
            if(mysqli_num_rows($exam_portal_result) > 0) {
                while($exam_portal_row = mysqli_fetch_assoc($exam_portal_result)) {
                    if($exam_portal_row["status"] == 1) {
                        array_push($exam_portal_array, $exam_portal_row["exam_name"]);
                    }
                }
            }
        ?>

        <!-- User Table -->
        <table class="table table-bordered mt-4" id="all-user-table">
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Name</th>
                    <th>Email ID</th>
                    <th>Assign Quizzes</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody id="userTable">
                <?php
                // Fetch users from the database
                $query = "SELECT * FROM users";
                $result = mysqli_query($conn, $query);
                $i = 0;
                if(mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $user_id = $row["id"];
                        $i++;
                        ?>
                        <tr>
                            <td><?= $i; ?></td>
                            <td><?= $row['name']; ?></td>
                            <td><?= $row['email_id']; ?></td>
                            <td class="assign-quizzes">
                                <?php
                                    // $assigned_exam_array = array();
                                    $assigned_exam_sql = "SELECT 
                                                            ep.exam_name AS exam_name,
                                                            ep.id AS exam_id,
                                                            uep.exam_id AS user_exam_id
                                                        FROM 
                                                            users AS u
                                                        JOIN
                                                            user_exam_mapping AS uep ON u.id = uep.user_id
                                                        JOIN
                                                            exam_portal AS ep ON ep.id = uep.exam_id
                                                        WHERE 
                                                            u.id = '$user_id' AND ep.status = 1;";
                                    // echo $assigned_exam_sql;
                                    $assigned_exam_result = mysqli_query($conn, $assigned_exam_sql);
                                    $exam_id_array = array();
                                    while($assigned_exam_row = mysqli_fetch_assoc($assigned_exam_result)) {
                                        array_push($exam_id_array, $assigned_exam_row["user_exam_id"]);
                                        // $assigned_exam_array = array_merge($assigned_exam_array, explode(",", str_replace(["[", "]"], '', $assigned_exam_row["active_exam"])));
                                        // $assigned_exam_array = json_decode($row['active_exam'], true);
                                        // array_output($assigned_exam_array);
                                            // echo $exam_name . "<br>";
                                            // array_output($exam_portal_array);  
                                        echo "<div class='assigned-exams p-1'>
                                                <button class='btn btn-success toggle-status' data-value='0' data-id='" . $row["id"] . "' data-exam-id='" . $assigned_exam_row["exam_id"] . "' data-exam-name='" . $assigned_exam_row["exam_name"] . "'>" . $assigned_exam_row["exam_name"] . "</button>
                                                <span class='bg-danger rounded-circle text-white remove-exam'>X</span>
                                            </div>";
                                    }
                                    // array_output($assigned_exam_array);
                                    // echo "<pre>";
                                    // print_r($assigned_exam_array);
                                    // echo "</pre>";
                                    // die;
                                    /* foreach($exam_portal_array as $exam_name) { ?>
                                        <th>
                                            <button class="btn <?php if($row["status"] == 0) {?>btn-success<?php } else {?>btn-danger<?php }?> toggle-status" data-value="<?= $row["status"];?>" data-id="<?= $row["id"];?>"><?php if($row["status"] == 0) {?>Activate<?php } else {?>Deactivate<?php }?></button>
                                        </th>
                                    <?php } */
                                    // foreach($exam_portal_array as $exam_name) {
                                    //     if(!in_array($exam_name, $assigned_exam_array)) {
                                    //         echo "<td><button class='btn btn-success toggle-status' data-value='0' data-id='" . $row["id"] . "' data-exam='" . $exam_name . "'>Activate</button></td>";
                                    //     } else {
                                    //         echo "<td><button class='btn btn-danger toggle-status' data-value='1' data-id='" . $row["id"] . "' data-exam='" . $exam_name . "'>Deactivate</button></td>";
                                    //     }
                                    // }
                                ?>
                                <div class='card d-none' style='width: 18rem;'>
                                    <div class='card-body'>
                                        <?php
                                            $exam_portal_sql = "SELECT * FROM exam_portal WHERE status = 1";
                                            $exam_portal_result = mysqli_query($conn, $exam_portal_sql);
                                            if($exam_portal_result) {
                                                while($exam_portal_row = mysqli_fetch_assoc($exam_portal_result)) {
                                                    // if(in_array($row["exam_name"], $assigned_exam_array)) {
                                                        $is_available = in_array($exam_portal_row["id"], $exam_id_array) ? 1 : 0;
                                                        echo "<div><input type='checkbox' class='form-check-input toggle-quizzes-assignment' data-id='" . $row["id"] . "' data-exam-id='" . $exam_portal_row["id"] . "' data-exam-name='" . $exam_portal_row["exam_name"] . "' data-value='0' " . ($is_available ? "checked" : "") . "><p class='card-title d-inline-block mx-2'>" . $exam_portal_row["exam_name"] . "</p></div>";
                                                    // }
                                                }
                                            }
                                        ?>
                                    </div>
                                </div>
                            </td>
                            <td><button class="btn btn-primary edit-user-button" data-bs-toggle="modal" data-bs-target="#editUserModal" data-id="<?= $row['id']; ?>" data-name="<?= $row['name']; ?>" data-email-id="<?= $row['email_id']; ?>" id="edit-user-button">Edit</button></td>
                            <td><button class="btn btn-danger delete-user-button" data-bs-toggle="modal" data-bs-target="#deleteUserModal" data-id="<?= $row['id']; ?>" data-email-id="<?= $row['email_id']; ?>" id="deleteUserButton">Delete</button></td>
                        </tr>
                <?php
                    }
                } else { ?>
                    <tr><td colspan="6" class="text-center">No users found.</td></tr>
                <?php }
                mysqli_close($conn);
            ?>
            </tbody>
        </table>

        <!-- Add User Button -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
            Add User
        </button>

        <!-- Add User Modal -->
        <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="users.php" method="POST" id="addUserForm">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="email_id">Email ID</label>
                                <input type="email" class="form-control" id="email_id" name="email_id" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="add-user">Add User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Edit User Modal -->
        <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="users.php" method="POST" id="editUserForm">
                        <div class="modal-body">
                            <div class="form-group d-none">
                                <label for="edit_id">ID</label>
                                <input type="hidden" class="form-control" id="edit_id" name="edit_id" readonly>
                            </div>
                            <div class="form-group">
                                <label for="edit_name">Name</label>
                                <input type="text" class="form-control" id="edit_name" name="edit_name" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_email_id">Email ID</label>
                                <input type="text" class="form-control" id="edit_email_id" name="edit_email_id" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="edit-user">Edit User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Delete User Modal -->
        <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addUserModalLabel">Delete User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="users.php" method="POST" id="deleteUserForm">
                        <div class="modal-body">
                            <input type="hidden" id="deleteUserId" name="id">
                            <div class="form-group">
                                <p>Are you sure you want to delete?</p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger" name="delete-user">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php
    include "Includes/footer.php";
?>