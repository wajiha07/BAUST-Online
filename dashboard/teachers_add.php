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

$message_count = NAN;
$dept_row = NAN;

if ($login_role == 0 || $login_role == 1){  //Owner or Admin
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
    <?php $active_page = "teachers"; include "includes/sidebar.php"; ?>

    <?php include "includes/header.php" ?>

    <div class="content">
        <ul class="breadcrumb">
            <li><a href="index.php">Dashboard</a></li>
            <li><a href="?p=teachers">Teachers</a></li>
            <li>Add Teacher</li>
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

        <!-- Teacher Add -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-edit"></i> Add Teacher</div>
            <div class="card-body">
                <p>
                <form class="form-group" name="AddTeacher" action="action/add_teacher.php" method="post" onsubmit="return validateTeacherForm()">
                    <div class="row">
                        <div class="col-25">
                            <label for="teacherUsername">Username</label>

                        </div>
                        <div class="col-75">
                            <input type="text" name="teacherUsername" id="teacherUsername" placeholder="Enter Teacher Username">
                            <div class="invalid-feedback">
                                * Please enter username.
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-25">
                            <label for="teacherName">Name</label>

                        </div>
                        <div class="col-75">
                            <input type="text" name="teacherName" id="teacherName" placeholder="Enter Teacher Name">
                            <div class="invalid-feedback">
                                * Please enter name.
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-25">
                            <label for="teacherDesignation">Designation</label>

                        </div>
                        <div class="col-75">
                            <select name="teacherDesignation" id="teacherDesignation">
                                <option value="" selected>Choose...</option>
                                <option value="Professor">Professor</option>
                                <option value="Associate Professor">Associate Professor</option>
                                <option value="Assistant Professor">Assistant Professor</option>
                                <option value="Lecturer">Lecturer</option>
                            </select>
                            <div class="invalid-feedback">
                                * Please select a designation.
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-25">
                            <label for="teacherDepartment">Department</label>

                        </div>
                        <div class="col-75">
                            <select name="teacherDepartment" id="teacherDepartment">
                                <option value="" selected>Choose...</option>

                                <?php
                                $sql = "SELECT id, name FROM department";
                                //                            $sql = "SELECT username, name FROM teacher";

                                if($result = mysqli_query($db_conn, $sql)){
                                    if(mysqli_num_rows($result) > 0){
                                        while($row = mysqli_fetch_assoc($result)){
                                            echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                                        }
                                    } else {

                                    }
                                } else {
                                    die("Error: " . mysqli_connect_error($db_conn). " SQL: " . $sql);
                                }
                                ?>
                            </select>
                            <div class="invalid-feedback">
                                * Please select a department.
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-25">
                            <label for="teacherGender">Gender</label>

                        </div>
                        <div class="col-75">
                            <select name="teacherGender" id="teacherGender">
                                <option value="" selected>Choose...</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                            <div class="invalid-feedback">
                                * Please select a gender.
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-25">
                            <label for="teacherBirthdate">Birthdate</label>

                        </div>
                        <div class="col-75">
                            <input type="date" name="teacherBirthdate" id="teacherBirthdate" placeholder="DD-MM-YYYY">
                            <div class="invalid-feedback">
                                * Please enter birthdate.
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-25">
                            <label for="teacherEmail">Email</label>

                        </div>
                        <div class="col-75">
                            <input type="text" name="teacherEmail" id="teacherEmail" placeholder="Enter Email Address">
                            <div class="invalid-feedback">
                                * Please enter email address.
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-25">
                            <label for="teacherPhone">Phone</label>

                        </div>
                        <div class="col-75">
                            <input type="text" name="teacherPhone" id="teacherPhone" placeholder="Enter Phone Number">
                            <div class="invalid-feedback">
                                * Please enter phone number.
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-25">
                            <label for="teacherAddress">Address</label>

                        </div>
                        <div class="col-75">
                            <input type="text" name="teacherAddress" id="teacherAddress" placeholder="Enter Address">
                            <div class="invalid-feedback">
                                * Please enter address.
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-25">
                            <label for="teacherDistrict">District</label>

                        </div>
                        <div class="col-75">
                            <select name="teacherDistrict" id="teacherDistrict">
                                <option value="" selected>Choose...</option>
                                <option value="Bagerhat">Bagerhat</option>
                                <option value="Bandarban">Bandarban</option>
                                <option value="Barguna">Barguna</option>
                                <option value="Barisal">Barisal</option>
                                <option value="Bhola">Bhola</option>
                                <option value="Bogra">Bogra</option>
                                <option value="Brahmanbaria">Brahmanbaria</option>
                                <option value="Chandpur">Chandpur</option>
                                <option value="Chittagong">Chittagong</option>
                                <option value="Chuadanga">Chuadanga</option>
                                <option value="Comilla">Comilla</option>
                                <option value="Cox's Bazar">Cox's Bazar</option>
                                <option value="Dhaka">Dhaka</option>
                                <option value="Dinajpur">Dinajpur</option>
                                <option value="Faridpur">Faridpur</option>
                                <option value="Feni">Feni</option>
                                <option value="Gaibandha">Gaibandha</option>
                                <option value="Gazipur">Gazipur</option>
                                <option value="Gopalganj">Gopalganj</option>
                                <option value="Habiganj">Habiganj</option>
                                <option value="Jaipurhat">Jaipurhat</option>
                                <option value="Jamalpur">Jamalpur</option>
                                <option value="Jessore">Jessore</option>
                                <option value="Jhalakati">Jhalakati</option>
                                <option value="Jhenaidah">Jhenaidah</option>
                                <option value="Khagrachari">Khagrachari</option>
                                <option value="Khulna">Khulna</option>
                                <option value="Kishoreganj">Kishoreganj</option>
                                <option value="Kurigram">Kurigram</option>
                                <option value="Kushtia">Kushtia</option>
                                <option value="Lakshmipur">Lakshmipur</option>
                                <option value="Lalmonirhat">Lalmonirhat</option>
                                <option value="Madaripur">Madaripur</option>
                                <option value="Magura">Magura</option>
                                <option value="Manikganj">Manikganj</option>
                                <option value="Meherpur">Meherpur</option>
                                <option value="Moulvibazar">Moulvibazar</option>
                                <option value="Munshiganj">Munshiganj</option>
                                <option value="Mymensingh">Mymensingh</option>
                                <option value="Narail">Narail</option>
                                <option value="Naogaon">Naogaon</option>
                                <option value="Narayanganj">Narayanganj</option>
                                <option value="Narsingdi">Narsingdi</option>
                                <option value="Natore">Natore</option>
                                <option value="Nawabganj">Nawabganj</option>
                                <option value="Netrakona">Netrakona</option>
                                <option value="Nilphamari">Nilphamari</option>
                                <option value="Noakhali">Noakhali</option>
                                <option value="Pabna">Pabna</option>
                                <option value="Panchagarh">Panchagarh</option>
                                <option value="Parbattya Chattagram">Parbattya Chattagram</option>
                                <option value="Patuakhali">Patuakhali</option>
                                <option value="Pirojpur">Pirojpur</option>
                                <option value="Rangpur">Rangpur</option>
                                <option value="Rajshahi">Rajshahi</option>
                                <option value="Rajbari">Rajbari</option>
                                <option value="Satkhira">Satkhira</option>
                                <option value="Shariatpur">Shariatpur</option>
                                <option value="Sherpur">Sherpur</option>
                                <option value="Sirajganj">Sirajganj</option>
                                <option value="Sunamganj">Sunamganj</option>
                                <option value="Sylhet">Sylhet</option>
                                <option value="Tangail">Tangail</option>
                                <option value="Thakurgaon">Thakurgaon</option>

                            </select>
                            <div class="invalid-feedback">
                                * Please select a district.
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-25">
                            <label for="teacherPhoto"></label>

                        </div>
                        <div class="col-75">
                            <input type="hidden" name="teacherPhoto" id="teacherPhoto" placeholder="Browse.." value="12">
                            <div class="invalid-feedback">
                                * Please choose a photo.
                            </div>
                        </div>
                    </div>

                    <input name="deptID" type="hidden" value="<?php echo $dept_row['id'] ?>">
                    <div class="row">
                        <button type='submit' class='btn btn-primary' style="float: right; margin-top: 15px">Add</button><br>
                    </div>
                </form>
                </p>

            </div>
        </div>



        <!-- Footer -->
        <?php include "includes/footer.php" ?>
    </div>


    <script src="js/scripts.js" type="text/javascript"></script>
    <script src="js/validation.js" type="text/javascript"></script>

</body>

</html>
