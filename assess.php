<?php

    // connect to the database
    $conn = mysqli_connect('localhost', 'root', '', 'file_management') or die("Error" . mysqli_error($conn));

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
        <header>

        </header>
        <main>
            <div id="form_div">
                <form id="assess_form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST" >
                    <div class="assess_fields">
                        <label for="roll">
                            Rollno
                        </label>
                        <input type="text" id="roll" name="roll" required>
                    </div>
                    <div class="assess_fields">
                        <label for="obs">
                            Observation
                        </label>
                        <input type="number" id="obs" name="obs" value="0" required>
                    </div>
                    <div class="assess_fields">
                        <label for="CoE">
                            Completion of Experiment
                        </label>
                        <input type="number" id="CoE" name="CoE" value="0" required>
                    </div>
                    <div class="assess_fields">
                        <label for="viva">
                            Viva
                        </label>
                        <input type="number" id="viva" name="viva" value="0" required>
                    </div>
                    <div class="assess_fields">
                        <label for="rec">
                            Record
                        </label>
                        <input type="text" id="rec" name="rec" value="0" required>
                    </div>
                    <div id="upload_buttons">
                    <input type="submit" name="submit" value="Submit" class="button_color button shadow" >
                    </div>
                </form>
            </div>
            <div>
                <?php

                    //update data

                    if(isset($_POST['submit'])){
                        //check if roll no is right?
                        $req_stmt = $conn->prepare("SELECT * from assessment WHERE hallticket=?");
                        $req_stmt->bind_param("s",$_POST['roll']);
                        $req_stmt->execute();
                        $res = $req_stmt->get_result();
                        $row = $res->fetch_assoc();
                        $marks = $row['Marks'];
                        $weeks_done = $row['no_of_assess'];
                        $total = $marks * $weeks_done;
                        $this_week = $_POST['obs'] + $_POST['CoE'] + $_POST['viva'] + $_POST['rec'];
                        $total = $total + $this_week;
                        $weeks_done++;
                        $total = $total/$weeks_done;
                        $req_stmt->close();

                        $upd_stmt = $conn->prepare("UPDATE assessment SET Marks = ?, no_of_assess = ?, this_week = ? WHERE hallticket=?");
                        $upd_stmt->bind_param('ssss',$total, $weeks_done, $this_week, $_POST['roll']);
                        $upd_stmt->execute();
                        $upd_stmt->close();

                        ?>
                        <div class="fade-out">
                            <h3>Data submitted succesfully</h3>
                        </div>
                        <?php

                    }

                ?>
            </div>
            <div class="table_div">
                <table>
                    <tr>
                        <th>Rollno</th>
                        <th>Cummulative Marks</th>
                    </tr>
                <?php
                    $dis_stmt = $conn->prepare("SELECT * from assessment");
                    $dis_stmt->execute();
                    $dis = $dis_stmt->get_result();
                    while($erow = $dis->fetch_assoc()){ ?>
                        <tr>
                            <td><?php echo $erow['hallticket'];?></td>
                            <td><?php echo $erow['Marks'];?></td>
                        </tr>
                    <?php }
                ?>
                </table>
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