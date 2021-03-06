<?php
/**
 * Created by PhpStorm.
 * User: Nomaan
 * Date: 13-Apr-19
 * Time: 10:36 PM
 */

$login_role = 10;
$login_user = NAN;

require_once "../includes/db.php";
include "../includes/session.php";

$semester = [
        1 => "Level 1, Term I",
        2 => "Level 1, Term II",
        3 => "Level 2, Term I",
        4 => "Level 2, Term II",
        5 => "Level 3, Term I",
        6 => "Level 3, Term II",
        7 => "Level 4, Term I",
        8 => "Level 4, Term II"
];

$message_count = NAN;

if ($login_role <= 3){  //Owner or Admin
    $sql = "SELECT COUNT(*) AS message FROM message WHERE msg_to = '$login_user' AND unread = 1";

    if($result = mysqli_query($db_conn, $sql)){
        $row = mysqli_fetch_assoc($result);
        $message_count = $row['message'];
    } else {
        echo mysqli_error($db_conn) . " SQL: " . $sql;
    }

    mysqli_free_result($result);
} else {   //Unauthorized
    die("<title>Unauthorized | BAUST Online</title>
        <h1>Unauthorized</h1><hr>
        <h2>You don't have permission to view this page.</h2>");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>BAUST Online - Dashboard</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="../css/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/styles.css" rel="stylesheet" type="text/css">
</head>

<body>

    <!--Header-->
    <?php include "includes/header.php" ?>

    <!-- Sidebar -->
    <?php $active_page = "students"; include "includes/sidebar.php"; ?>

    <?php include "includes/header.php" ?>

    <div class="content">
        <ul class="breadcrumb">
            <li><a href="index.php">Dashboard</a></li>
            <li>Students</li>
        </ul>

        <?php
        if(isset($_SESSION['message'])){
            if($_SESSION['message'][0] == 'success'){
                echo '<div class="alert success">
                        <span class="closebtn">&times;</span>
                        ' . $_SESSION['message'][1] . '
                      </div>';
            }

            if($_SESSION['message'][0] == 'info'){
                echo '<div class="alert info">
                        <span class="closebtn">&times;</span>
                        ' . $_SESSION['message'][1] . '
                      </div>';
            }

            if($_SESSION['message'][0] == 'warning'){
                echo '<div class="alert warning">
                        <span class="closebtn">&times;</span>
                        ' . $_SESSION['message'][1] . '
                      </div>';
            }

            if($_SESSION['message'][0] == 'error'){
                echo '<div class="alert danger">
                        <span class="closebtn">&times;</span>
                        ' . $_SESSION['message'][1] . '
                      </div>';
            }

            unset($_SESSION['message']);
        }
        ?>

        <!-- Students Table -->
        <div class="card">
            <div class="card-header"><i class="fas fa-table"></i> Students</div>
            <div class="card-body">
                <div class="data-table-head">
                    <div class="row">
                        <div class="col-25">
                            <a href="?p=add_student"><button type='button' class='btn btn-primary'>Add Student</button><br></a>
                        </div>
                        <div class="col-75">
                            <div class="f-right">
                                <form name="SearchStudent" action="" method="get">
                                    <input type="hidden" name="p" value="students">
                                    <select name="dept" id="dept">
                                        <option value="" <?php if(isset($_GET['dept']) && $_GET['dept'] == "") echo 'selected' ?>>All Department</option>
                                        <?php
                                        $sql = "SELECT id, name FROM department";

                                        if($result = mysqli_query($db_conn, $sql)){
                                            if(mysqli_num_rows($result) > 0){
                                                while($row = mysqli_fetch_assoc($result)){
                                                    if(isset($_GET['dept']) && $_GET['dept'] == $row['id']){
                                                        echo "<option value='" . $row['id'] . "' selected>" . $row['name'] . "</option>";
                                                    } else {
                                                        echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                                                    }

                                                }
                                            } else {

                                            }
                                        } else {
                                            die("Error: " . mysqli_connect_error($db_conn). " SQL: " . $sql);
                                        }
                                        ?>
                                    </select>

                                    <select name="semester" id="semester">
                                        <option <?php if(isset($_GET['semester']) && $_GET['semester'] == "") echo "selected" ?>  value="">All Semester</option>
                                        <option <?php if(isset($_GET['semester']) && $_GET['semester'] == "1") echo "selected" ?> value="1">Level 1, Term I</option>
                                        <option <?php if(isset($_GET['semester']) && $_GET['semester'] == "2") echo "selected" ?> value="2">Level 1, Term II</option>
                                        <option <?php if(isset($_GET['semester']) && $_GET['semester'] == "3") echo "selected" ?> value="3">Level 2, Term I</option>
                                        <option <?php if(isset($_GET['semester']) && $_GET['semester'] == "4") echo "selected" ?> value="4">Level 2, Term II</option>
                                        <option <?php if(isset($_GET['semester']) && $_GET['semester'] == "5") echo "selected" ?> value="5">Level 3, Term I</option>
                                        <option <?php if(isset($_GET['semester']) && $_GET['semester'] == "6") echo "selected" ?> value="6">Level 3, Term II</option>
                                        <option <?php if(isset($_GET['semester']) && $_GET['semester'] == "7") echo "selected" ?> value="7">Level 4, Term I</option>
                                        <option <?php if(isset($_GET['semester']) && $_GET['semester'] == "8") echo "selected" ?> value="8">Level 4, Term II</option>
                                    </select>

                                    <input type="text" style="min-width: 120px; width: 20%" name="search" placeholder="Search For..." value="<?php if(isset($_GET['search'])) echo $_GET['search'] ?>">

                                    <button type='submit' class='btn btn-primary'>Search</button><br>
                                    <div id="invalid-dept" class="invalid-feedback">
                                        * Please enter Search Text.
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <?php if(isset($_GET['search'])) echo "<p>Showing search result for: <strong>" . $_GET['search'] . "</strong></p>"; ?>
                    </div>
                </div>

                <?php if($login_role < 2){ ?>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <!--                                <th width='15px'>#</th>-->
                        <th>ID</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Semester</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Options</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if(isset($_GET['search'])){
                        $search = $_GET['search'];
                        $dept = isset($_GET['dept'])?$_GET['dept']:"";
                        $semester_p = isset($_GET['semester'])?$_GET['semester']:"";
//                        if($dept == ""){
//                            $sql = "SELECT student.student_id, student.name,
//                                    student.semester, student.email,
//                                    student.phone, student.department,
//                                    department.name AS dept_name
//                                    FROM student LEFT JOIN department ON department.id = student.department
//                                    WHERE (active = 1)
//                                    AND (student.name LIKE '%$search%'
//                                    OR student.student_id LIKE '%$search%'
//                                    OR student.phone LIKE '%$search%')";
//                        } else {
//                            $sql = "SELECT student.student_id, student.name,
//                                    student.semester, student.email,
//                                    student.phone, student.department,
//                                    department.name AS dept_name
//                                    FROM student LEFT JOIN department ON department.id = student.department
//                                    WHERE (active = 1) AND (student.department = '$dept') AND (student.name LIKE '%$search%' OR student.student_id LIKE '%$search%' OR student.phone LIKE '%$search%')";
//                        }

                        $semester_search = $semester_p == ""?"":"AND (student.semester = '" .$semester_p. "')";
                        $dept_search = $dept == ""?"":"AND (student.department = " .$dept. ")";

                        $sql = "SELECT student.student_id, student.name,
                                    student.semester, student.email,
                                    student.phone, student.department,
                                    department.name AS dept_name
                                    FROM student LEFT JOIN department ON department.id = student.department
                                    WHERE (active = 1)
                                    ". $dept_search . "
                                    ". $semester_search . "
                                    AND (student.name LIKE '%$search%'
                                    OR student.student_id LIKE '%$search%'
                                    OR student.phone LIKE '%$search%')";

                        if($result = mysqli_query($db_conn, $sql)){
                            if(mysqli_num_rows($result)){
                                while ($row = mysqli_fetch_assoc($result)){
                                    echo "<tr>
                                            <td>" . $row['student_id'] . "</td>
                                            <td>" . $row['name'] . "</td>
                                            <td>" . $row['dept_name'] . "</td>
                                            <td>" . $semester[$row['semester']] . "</td>
                                            <td>" . $row['email'] . "</td>
                                            <td>" . $row['phone'] . "</td>
                                            
                                            <td> <a href='?p=edit_student&id=". $row['student_id'] ."'><button type='button' class='btn btn-success btn-sm'>Edit</button></a>
                                            <button type='button' class='btn btn-danger btn-sm' onclick='deleteStudent(\"" . $row['student_id'] . "\", \"" . $row['name'] . "\")'>Delete</button>" . "</td>
                                        </tr>";
                                }
                            } else{
                                echo "<tr><td colspan='7'><center>No Students Found<center></td></tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'><center>Database Error! ". mysqli_error($db_conn) ."<center></td></tr>";
                        }
                    } else {
                        $sql = "SELECT student.student_id,
                                student.name, student.semester,
                                student.email, student.phone,
                                student.department,
                                department.name AS dept_name
                                FROM student
                                LEFT JOIN department ON department.id = student.department
                                WHERE active = 1";
                        if($result = mysqli_query($db_conn, $sql)){
                            if(mysqli_num_rows($result)){
                                while ($row = mysqli_fetch_assoc($result)){
                                    echo "<tr>
                                            <td>" . $row['student_id'] . "</td>
                                            <td>" . $row['name'] . "</td>
                                            <td>" . $row['dept_name'] . "</td>
                                            <td>" . $semester[$row['semester']] . "</td>
                                            <td>" . $row['email'] . "</td>
                                            <td>" . $row['phone'] . "</td>
                                            
                                            <td> <a href='?p=edit_student&id=". $row['student_id'] ."'><button type='button' class='btn btn-success btn-sm'>Edit</button></a>
                                            <button type='button' class='btn btn-danger btn-sm' onclick='deleteStudent(\"" . $row['student_id'] . "\", \"" . $row['name'] . "\")'>Delete</button>" . "</td>
                                        </tr>";
                                }
                            }else {
                                echo "<tr><td colspan='7'><center>No Students<center></td></tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'><center>Database Error! ". mysqli_error($db_conn) . "<center></td></tr>";
                        }
                    }
                    ?>

                    </tbody>
                </table>
                <?php } else {?>
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <!--                                <th width='15px'>#</th>-->
                            <th>ID</th>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Semester</th>
                            <th>Email</th>
                            <th>Phone</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if(isset($_GET['search'])){
                            $search = $_GET['search'];
                            $dept = isset($_GET['dept'])?$_GET['dept']:"";
                            $semester_p = isset($_GET['semester'])?$_GET['semester']:"";
//                        if($dept == ""){
//                            $sql = "SELECT student.student_id, student.name,
//                                    student.semester, student.email,
//                                    student.phone, student.department,
//                                    department.name AS dept_name
//                                    FROM student LEFT JOIN department ON department.id = student.department
//                                    WHERE (active = 1)
//                                    AND (student.name LIKE '%$search%'
//                                    OR student.student_id LIKE '%$search%'
//                                    OR student.phone LIKE '%$search%')";
//                        } else {
//                            $sql = "SELECT student.student_id, student.name,
//                                    student.semester, student.email,
//                                    student.phone, student.department,
//                                    department.name AS dept_name
//                                    FROM student LEFT JOIN department ON department.id = student.department
//                                    WHERE (active = 1) AND (student.department = '$dept') AND (student.name LIKE '%$search%' OR student.student_id LIKE '%$search%' OR student.phone LIKE '%$search%')";
//                        }

                            $semester_search = $semester_p == ""?"":"AND (student.semester = '" .$semester_p. "')";
                            $dept_search = $dept == ""?"":"AND (student.department = " .$dept. ")";

                            $sql = "SELECT student.student_id, student.name,
                                    student.semester, student.email,
                                    student.phone, student.department,
                                    department.name AS dept_name
                                    FROM student LEFT JOIN department ON department.id = student.department
                                    WHERE (active = 1)
                                    ". $dept_search . "
                                    ". $semester_search . "
                                    AND (student.name LIKE '%$search%'
                                    OR student.student_id LIKE '%$search%'
                                    OR student.phone LIKE '%$search%')";

                            if($result = mysqli_query($db_conn, $sql)){
                                if(mysqli_num_rows($result)){
                                    while ($row = mysqli_fetch_assoc($result)){
                                        echo "<tr>
                                            <td>" . $row['student_id'] . "</td>
                                            <td>" . $row['name'] . "</td>
                                            <td>" . $row['dept_name'] . "</td>
                                            <td>" . $semester[$row['semester']] . "</td>
                                            <td>" . $row['email'] . "</td>
                                            <td>" . $row['phone'] . "</td>
                                             </tr>";
                                    }
                                } else{
                                    echo "<tr><td colspan='7'><center>No Students Found<center></td></tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7'><center>Database Error! ". mysqli_error($db_conn) ."<center></td></tr>";
                            }
                        } else {
                            $sql = "SELECT student.student_id,
                                student.name, student.semester,
                                student.email, student.phone,
                                student.department,
                                department.name AS dept_name
                                FROM student
                                LEFT JOIN department ON department.id = student.department
                                WHERE active = 1";
                            if($result = mysqli_query($db_conn, $sql)){
                                if(mysqli_num_rows($result)){
                                    while ($row = mysqli_fetch_assoc($result)){
                                        echo "<tr>
                                            <td>" . $row['student_id'] . "</td>
                                            <td>" . $row['name'] . "</td>
                                            <td>" . $row['dept_name'] . "</td>
                                            <td>" . $semester[$row['semester']] . "</td>
                                            <td>" . $row['email'] . "</td>
                                            <td>" . $row['phone'] . "</td>
                                            </tr>";
                                    }
                                }else {
                                    echo "<tr><td colspan='7'><center>No Students<center></td></tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7'><center>Database Error! ". mysqli_error($db_conn) . "<center></td></tr>";
                            }
                        }
                        ?>

                        </tbody>
                    </table>
                <?php } ?>
            </div>
        </div>



        <!-- Footer -->
        <?php include "includes/footer.php" ?>
    </div>

    <!-- Delete Student Modal -->
    <div id="deleteStudentModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close" onclick="modalDisplay('deleteStudentModal', 'none')">&times;</span>
                <h2>Delete Student</h2>
            </div>
            <div class="modal-body">
                <p>Select "Delete" below if you want to delete <strong id="name_text">the selected</strong> student.</p>
            </div>
            <div class="modal-footer">
                <form name="DeleteStudent" action="action/delete_student.php" method="post">
                    <input name="student_id" type="hidden">
                    <input name="student_name" type="hidden">
                    <button class="btn btn-danger" type="submit">Delete</button>
                </form>
                <button class="btn btn-secondary" type="button" onclick="modalDisplay('deleteStudentModal', 'none')">Cancel</button>
            </div>
        </div>
    </div>

    <script src="js/scripts.js" type="text/javascript"></script>
    <script src="js/validation.js" type="text/javascript"></script>

</body>

</html>
