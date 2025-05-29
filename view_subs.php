<?php

    $conn = mysqli_connect('localhost', 'root', '', 'file_management') or die("Error" . mysqli_error($conn));
    
?>

<?php
if(isset($_POST['delete'])) { //if delete of task occurs
    //get the id of the task
    $id_deleted = $_POST['id_of_deleted'];

    //delete the record from database
    $delete_stmt = $conn->prepare("DELETE FROM submissions WHERE student_id = ? and task_id = ?");
    $delete_stmt->bind_param("ss", $id_deleted, $_POST['id_of_task']);
    $delete_stmt->execute();
    $delete_stmt->close();

}
?>

<!DOCTYPE html>
<html>
    <head>
    <link rel="stylesheet" href="coolcss.css">
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
        <header>
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
                    
                   
                    <li class="nav-item"><form action="add_tasks.php" method="post"><button type="submit" class="btn btn-link navbar-btn navbar-link"><i class="fa fa-tasks" aria-hidden="true"></i>Add Tasks</button></form></li>
                    <li class="nav-item"><form action="view_subs.php" method="post"><button type="submit" class="btn btn-link navbar-btn navbar-link"><i class="fa fa-upload" aria-hidden="true"></i>Submissions</button></form></li>
                    <li class="nav-item"><form action="assess.php" method="post"><button type="submit" class="btn btn-link navbar-btn navbar-link"><i class="fa fa-graduation-cap" aria-hidden="true"></i>Assign Marks</button></form></li>





                    <li><form action="x.php" method="post"><button type="submit" class="btn btn-link navbar-btn navbar-link"><i class="fa fa-sign-out" aria-hidden="true"></i>Log Out</button></form></li>
                  
            </ul>
            </ul>
            </div>
        </div>
    </nav>  
        </header>
        <main id="submissions_main">
        <div id="display-added-submissions">
            <?php
                //code to dynamically change the html after sql success
                if(true){   
                    $ret_stmt = $conn->prepare("SELECT * FROM submissions") ;
                    $ret_stmt->execute();
                    $result = $ret_stmt->get_result();
                    if($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            ?>
                            <div class="display_task">
                                <div class="display_task_name">
                                    <h3 class="text-back"><?php echo $row['task_name'];?><h3>
                                    <h3 id="roll_no" class="text-back"><?php echo $row['student_id'];?><h3>
                                </div>
                                <div class="display_description"><?php echo $row['student_remarks'];?></div>
                                <?php 
                                if($row['no_of_files'] > 0){?>
                                    <div class="notice-for-student">
                                            <p>The files contain the submissions and assignments</p> 
                                    </div>
                                <?php
                                }
                                ?>

                                <div class="upload_button">
                                    <div class="file_list">
                                            <a href="submissions/<?php echo $row['file_name_0'];?>"><?php echo $row['file_name_0'];?></a>
                                            <a href="submissions/<?php echo $row['file_name_1'];?>"><?php echo $row['file_name_1'];?></a>
                                            <a href="submissions/<?php echo $row['file_name_2'];?>"><?php echo $row['file_name_2'];?></a>
                                    </div>
                                    <form action="" method="POST">
                                        <input type="hidden" value="<?php echo $row['task_id'];?>" name="id_of_task">
                                        <input type="hidden" value="<?php echo $row['student_id'];?>" name="id_of_deleted">
                                        <input type="submit" value="Clear" name="delete" class="delete_button_color button shadow delete_button">
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