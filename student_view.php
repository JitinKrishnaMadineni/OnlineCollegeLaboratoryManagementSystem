<?php

    $conn = mysqli_connect('localhost', 'root', '', 'file_management') or die("Error" . mysqli_error($conn));
    session_start();
    //get student id
    $student_id = $_SESSION['user_id'];
?>

<?php
// connect to the database
//$conn = mysqli_connect('localhost', 'root', '', 'file_management') or die("Error" . mysqli_error($conn));

// Uploads files
if (isset($_POST['upload'])) { // if save button on the form is clicked

    $count = count(array_filter($_FILES['file_assign']['name']));

    //echo $count;
        

    //get task_id
    $task_id = $_POST['id_of_task'];

    // get the task name
    $task_name = $_POST['name_of_task'];

    // get the description
    $task_description = trim($_POST['remarks']);

    if($task_description == ""){
        $task_description = "--no-remarks--";
    }

    //
    

    // update just taskname and description
    $sql = "INSERT INTO submissions (task_name, student_remarks, no_of_files, student_id, task_id) VALUES ('$task_name', '$task_description', '$count', '$student_id', '$task_id')";

    if(mysqli_query($conn, $sql)){
        //success
    }

    //using loop to get multiple files
    for($i=0; $i<$count; $i++){
        //get file name
        $filename = $_FILES['file_assign']['name'][$i];

        // destination of the file on the server
        $destination = 'submissions/' . $filename;

        // the physical file on a temporary uploads directory on the server
        //$file = $_FILES['file']['tmp_name'][$i];

        if ($_FILES['file_assign']['size'][$i] > 10000000) { // file shouldn't be larger than 10Megabyte
            //change the code here to alert the user about failure
            echo "File too large!";
        } else {
            // move the uploaded (temporary) file to the specified destination
            if (move_uploaded_file($_FILES['file_assign']['tmp_name'][$i], $destination)) {
                $sql = "UPDATE submissions SET file_name_$i = '$filename' WHERE task_id = '$task_id' and student_id = '$student_id'";
                if (mysqli_query($conn, $sql)) {
                    //database successfully updated, so we used header to get info
                }
            } 
            else {
                //change the code here to alert the user about failure
                echo "Failed to upload files.";
            }
        }

    }
    unset($_FILES['file_assign']);
} 

?>

<!DOCTYPE html>
<html>
    <head>
    <link rel="stylesheet" href="coolcss.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="node_modules/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="node_modules/bootstrap-social/bootstrap-social.css">
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link href="styles.css" rel="stylesheet">
    </head>
    <body>
    <nav class="navbar navbar-dark navbar-expand-md fixed-top">
        <div class="container">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#Navbar">
                <span class="navbar-toggler-icon"></span>
            </button>
           <a class="navbar-brand" href="index.html"><img src="img/wo.jpg" height="30" width="41"></a>
           <div class="collapse navbar-collapse" id="Navbar">
                <ul class="navbar-nav">
            <li       class="nav-item active"><a class="nav-link" href="./index.html"><span class="fa fa-home fa-lg"></span> Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="./aboutus.html"><span class="fa fa-info fa-lg"></span> About</a></li>
                <!--    <li class="nav-item"><a class="nav-link" href="#"><span class="fa fa-list fa-lg"></span> Menu</a></li> -->
                    <li class="nav-item"><a class="nav-link" href="contactus.html"><span class="fa fa-address-card fa-lg"></span> Contact</a></li>
                  
    

        <!-- this dropdown menu item looks right -->


        <!-- this dropdown menu item (a logout form) does not -->
        <li><form action="x.php" method="post"><button type="submit" class="btn btn-link navbar-btn navbar-link"><i class="fa fa-sign-out" aria-hidden="true"></i>Log Out</button></form></li>

      
    </li>

                                      
            </ul>
            </ul>
            </div>
        </div>
    </nav>
        <header>
            <nav>

                <div>
                    <p><?php /*echo $_SESSION['user_id']*/ ?></p>
                </div>  
            </nav>  
        </header>
        <main id="student_main">
        <div id="display-added-tasks">
            <?php
                //code to dynamically change the html after sql success
                if(true){   
                    $ret_stmt = $conn->prepare("SELECT * FROM tasks") ;
                    $ret_stmt->execute();
                    $result = $ret_stmt->get_result();
                    if($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $check_stmt = $conn->prepare("SELECT * from submissions WHERE task_id=? and student_id=?");
                            $check_stmt->bind_param('ss', $row['id'], $student_id);
                            $check_stmt->execute();
                            $result_check = $check_stmt->get_result();
                            if($result_check->num_rows > 0){
                                //SKIPPING THIS BCZ THIS TASK WAS SUBMITTED
                                continue;
                            }
                            ?>
                            <div class="display_task">
                                <div class="display_task_name"><h3 class="text-back"><?php echo $row['task_name'];?><h3></div>
                                <div class="display_description"><?php echo $row['task_description'];?></div>
                                <div class="upload_button">
                                    <div class="file_list">
                                            <a href="uploads/<?php echo $row['file_name_0'];?>"><?php echo $row['file_name_0'];?></a>
                                            <a href="uploads/<?php echo $row['file_name_1'];?>"><?php echo $row['file_name_1'];?></a>
                                            <a href="uploads/<?php echo $row['file_name_2'];?>"><?php echo $row['file_name_2'];?></a>
                                    </div>
                                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" value="<?php echo $row['id'];?>" name="id_of_task">
                                        <input type="hidden" value="<?php echo $row['task_name'];?>" name="name_of_task">
                                        <div class="desc_div bg-color-override">
                                            <label for="remarks">
                                                Remarks
                                            </label>
                                            <textarea id="remarks" name="remarks" rows="10" cols="100"></textarea>
                                        </div>
                                        <div class="buttons_list">
                                            <label  class="delete_button_color button shadow">Browse
                                                <input type="file" name="file_assign[]"  multiple> 
                                            </label>
                                            <input type="submit" value="Upload" name="upload" class="upload_button_color button shadow">
                                        </div>
                                        <div class="notice-for-student">
                                            <p>Upload not more than three files, Zip them incase of too many files...</p>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?php }
                    }
                    }

                ?>
            </div>
        </main>
        <footer class="footer">
        <div class="container" >
            <div class="row">
                <div class="col-4 offset-1 col-sm-2">
                    <h4>Links</h4>
                    <ul class="list-unstyled">
                        <li><a href="./index.html">Home</a></li>
                        <li><a href="./aboutus.html">About</a></li>

                        <li><a href="./contactus.html">Contact</a></li>
                    </ul>
                </div>
                <div class="col-7 col-sm-5">
                    <h4>Our Address</h4>
                    <address>
                      <h6><i class="fa fa-phone fa-lg"></i>: +91 3456446543<br></h6>
                      <h6><i class="fa fa-fax fa-lg"></i>: +852 8765 4321<br></h6>
                      <h6><i class="fa fa-envelope fa-lg"></i>:
                      <a href="mailto:college@labwork.net">college@labwork.net</a></h6>
		           </address>
                </div>
                <div class="col-12 col-sm-4 align-self-center">
                  <div class="text-center">
                      <a class="btn btn-social-icon btn-google" href="http://google.com/+"><i class="fa fa-google-plus"></i></a>
                      <a class="btn btn-social-icon btn-facebook" href="http://www.facebook.com/profile.php?id="><i class="fa fa-facebook"></i></a>
                      <a class="btn btn-social-icon btn-linkedin" href="http://www.linkedin.com/in/"><i class="fa fa-linkedin"></i></a>
                      <a class="btn btn-social-icon btn-twitter" href="http://twitter.com/"><i class="fa fa-twitter"></i></a>
                      <a class="btn btn-social-icon btn-google" href="http://youtube.com/"><i class="fa fa-youtube"></i></a>
                      <a class="btn btn-social-icon" href="mailto:"><i class="fa fa-envelope-o"></i></a>
                  </div>
                </div>
           </div>
           <div class="row justify-content-center">
                <div class="col-auto">
                    <p>© Copyright 2021 Online College Laboratory Management System </p>
                </div>
           </div>
        </div>
    </footer>

    <script src="node_modules/jquery/dist/jquery.slim.min.js"></script>
    <script src="node_modules/popper.js/dist/umd/popper.min.js"></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    </body>
</html>

<?php $conn->close(); ?>