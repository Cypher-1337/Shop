<?php

    session_start();

    if(isset($_SESSION['user'])){
        $pageTitle= "Members";
        include("init.php");


        $do = isset($_GET['do']) ? $_GET['do'] : $do = "Manage";

        
        
//************************************************************* 
        
        if($do == "Manage"){ // Manage page
        

            $query = '';

            if(isset($_GET['status']) && $_GET['status'] == 'pending'){

                $query = ' WHERE RegStatus = 0';

            }
            

            $stmt = $con->prepare("SELECT * from users $query");
            $stmt->execute();
            $rows = $stmt->fetchAll();
            
            if(!empty($rows)){
            ?>

                <h1 class="text-center">Manage Members</h1>
                <div class="container">
                    <div class="table-responsive">
                        <table class="main-table text-center table table-bordered">
                            <tr>
                                <td>#ID</td>
                                <td>Image</td>
                                <td>Username</td>
                                <td>Email</td>
                                <td>Full Name</td>
                                <td>Privilage</td>
                                <td>Control</td>
                            </tr>
                            <?php

                                foreach($rows as $row){

                                    $privilage = '';
                                    if($row['GroupID'] == 1){
                                        $privilage = 'Admin';
                                    }else{
                                        $privilage = 'User';
                                    }
                                    echo "<tr>";
                                        echo "<td>" . $row["UserID"]   . "</td>";

                                        echo "<td><img class='avatar-img' src='uploads/avatars/";
                                        // if user has image display it if not display default 
                                        if(!empty($row["Avatar"])){
                                            echo $row["Avatar"];
                                        }else{
                                            echo 'default.jpg';
                                        }
                                        echo "' alt='Avatar'></td>";

                                        echo "<td>" . $row["Username"] . "</td>";
                                        echo "<td>" . $row["Email"]    . "</td>";
                                        echo "<td>" . $row["Fullname"] . "</td>";
                                        echo "<td>" . $privilage       . "</td>";
                                        echo "<td>" .    
                                        '<a href="members.php?do=Edit&userId=' . $row["UserID"] . '"class="btn btn-outline-success"><i class="fa fa-edit"></i> Edit</a>
                                        <a href="members.php?do=Delete&userId=' .$row["UserID"] . '" class="btn btn-outline-danger confirm"><i class="fa fa-close"></i> Delete</a>';

                                        if($row['RegStatus'] == 0){

                                            echo '<a href="members.php?do=activate&userId=' . $row["UserID"] . '" class="btn btn-outline-info"><i class="fa fa-user-check"></i> Activate</a>' . '</td>';

                                        }

                                    echo "</td>";

                                }

                            ?>
                        </table>
                    </div>

                    <a href="members.php?do=Add" class='btn btn-primary'><i class='fa fa-plus'></i> Add Member</a>
                </div>


        <?php }
        
        // empty message for pending users manage ( 127.0.0.1/shop/admin/members.php?status=pending )
        elseif(empty($rows) && $_GET['status'] == 'pending'){

            echo "<div class='container empty-box'>";
            echo "<div class='empty-msg'>No <i class='fa fa-users'></i> Pending Users at the moment</div>";
            echo "<a href='members.php?do=Add' class='btn btn-primary'><i class='fa fa-plus'></i> Add User</a>";
            echo "</div>";
        }

        // empty message for users manage
        else{
                echo "<div class='container empty-box'>";
                    echo "<div class='empty-msg'>No <i class='fa fa-users'></i> Users at the moment</div>";
                    echo "<a href='members.php?do=Add' class='btn btn-primary'><i class='fa fa-plus'></i> Add User</a>";
                echo "</div>";
        }
    }



//************************************************************* 

        elseif ($do == "Edit"){  // Edit page
            
            
            // check if the userId exist and numeric 
            $userid = isset($_GET['userId']) && is_numeric($_GET['userId'])? intval($_GET['userId']) : 0;
                
            // getting the user details that registered with the id
            $stmt = $con->prepare("SELECT * FROM users Where UserID = ? ");

            $stmt->execute(array($userid));
            $row = $stmt->fetch();
            $count = $stmt->rowCount();

            // check if the id exists in the db  &  show the form
            if($count > 0){ ?>
                <h1 class="text-center">Edit Profile</h1>
                <div class='row'>
                    <div class="col-md-12">
                        <div class="container">
                            <form action="?do=Update" method="POST" enctype="multipart/form-data">
                            
                                <input type="hidden" name="userid" value="<?php echo $userid ?>" >
                                <!-- username -->
                                <div class="form-group row">
                                    <label class="col-sm-2 control-label edit-label">Username</label>
                                    <div class="col-sm-4">
                                        <input required type="text" name="username" value="<?php echo $row['Username'];?>" class="form-control">
                                    </div>
                                </div>
                
                                <!-- email -->
                                <div class="form-group row">
                                    <label class="col-sm-2 control-label edit-label">Email</label>
                                    <div class="col-sm-4">
                                        <input required type="email" name="email" value="<?php echo $row['Email'];?>" class="form-control">
                                    </div>
                                </div>
                
                                <!-- full name  -->
                                <div class="form-group row">
                                    <label class="col-sm-2 control-label edit-label">Full Name</label>
                                    <div class="col-sm-4">
                                        <input required type="text" name="fullname" value="<?php echo $row['Fullname'];?>" class="form-control">
                                    </div>
                                </div>
                
                                <!-- password -->
                                <div class="form-group row">
                                    <label class="col-sm-2 control-label edit-label">Password</label>
                                    <div class="col-sm-4">

                                        <input type="hidden" name="oldpassword" value="<?php echo $row['Password'] ?>">
                                        <input type="password" name="newpassword" class="form-control" autocomplete="new-password">
                                    </div>
                                </div>

                                <!-- Group ID  -->
                                <div class="form-group row">
                                    <label class="col-sm-2 control-label edit-label">Privilage</label>
                                    <div class="col-sm-4">
                                            <input required type="text" name="groupid" class="form-control" placeholder="admin || user"
                                            value="<?php if($row['GroupID'] == 0){echo 'User';}else{echo 'Admin';}  ?>" >
                                    </div>
                                </div>

                                <!-- User Avatar  -->
                                <div class="form-group row">
                                    <label class="col-sm-2 control-label edit-label">Avatar</label>
                                    <div class="col-sm-4">
                                            <input type="file" name="avatar" class="form-control" >
                                            <!-- old photo  -->
                                            <input type="hidden" name="old_avatar" class="form-control" value="<?php echo $row['Avatar'] ?>" >

                                    </div>
                                </div>
                
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="submit" value="Update" class="btn-add-user btn btn-dark">
                                    </div>
                                </div>
                
                            </form>
                        </div>
                        
                    </div>
                </div>
                

            <?php }

            // if the id wasn't valid 
            else{
                echo "<div class='container'>";
                $msg = "<div class='text-center alert alert-danger'> No such user Id </div>";
                redirectHome($msg);
                echo "</div>";
            }
        }
        
        
        
        
//*************************************************************      

        elseif($do == "Update"){ // Update Page

            echo "<div class='container'>";
            echo "<h1 class='text-center'>Update</h1>";

            // check if the user came from the edit form
            if($_SERVER['REQUEST_METHOD'] == 'POST'){

                $id         = trim($_POST['userid']);
                $username   = trim($_POST['username']);
                $email      = trim($_POST['email']);
                $fullname   = trim($_POST['fullname']);
                $groupid    = strtolower(trim($_POST['groupid']));

                // old avatar if user didn't upload an image
                $old_avatar = $_POST['old_avatar'];
                

                // password
                $pass = empty($_POST['newpassword'])? $pass = $_POST['oldpassword'] : $pass = sha1($_POST['newpassword']);

                // Avatar
                $avatarName = $_FILES['avatar']['name'];
                $avatarSize = $_FILES['avatar']['size'];
                $avatarTemp = $_FILES['avatar']['tmp_name'];
                $avatarError = $_FILES['avatar']['error'];

                $allowed_ext = array('png', 'jpg', 'jpeg');

                $img_ext = strtolower(end(explode('.', $avatarName)));
              
                $img_name = rand(1, 9999999) . '_' . $avatarName ;
                


                // start validate the form
                // if $avatarName is not empty check img extension and size
                if(!empty($avatarName)){
                    $formErrors = editValidateForm($username, $email, $fullname, $groupid, $img_ext, $allowed_ext, $avatarSize);
                }
                // if it is empty ignore file upload restrictions 
                else{
                    $formErrors = editValidateForm($username, $email, $fullname, $groupid);
                    
                }

                 // loops into errors and print them
                foreach($formErrors as $error){
                    echo "<div class='alert alert-danger '>" . $error . "</div>" ;
                }
                // check if there is no error
                if(empty($formErrors)){

                    // if $avatarName not empty means that client uploaded image 
                    if(!empty($avatarName)){
                        $destination = 'uploads/avatars/' . $img_name;
                        
                        if (!move_uploaded_file($avatarTemp, $destination)) {
                            echo "Sorry, there was an error uploading your file.";
                        }
                    // if empty update the db with the old value of Avatar 
                    }else{
                        $img_name = $old_avatar;
                    }
                    // check if username is duplicate
                    $stmt2 = $con->prepare("SELECT * FROM users WHERE Username = ? AND UserID != ?");
                    $stmt2->execute(array($username, $id));
                    $count = $stmt2->rowCount();

                    if($count > 0){
                        echo "<div class='alert alert-danger'>User already exist</div>";
                    }else{

                        // if it was admin the value will be 1 if he is user value will be 0
                        if($groupid == 'admin'){
                            $groupid = 1;
                        }else{
                            $groupid = 0;
                        }

                        // update db
                        $stmt = $con->prepare("UPDATE users SET Username = ?, Email = ?, Fullname = ?, GroupID = ?, Avatar = ?, Password = ? WHERE UserID= ?");
                        $stmt->execute(array($username, $email, $fullname, $groupid, $img_name, $pass, $id));
                        $count = $stmt->rowCount();
        
                        $msg = "<div class='alert alert-success'>" . $count . " Record updated </div>";
                        redirectHome($msg, 'members.php', 1);
                    }
                }

                

                
                
            }else{
                // if the client requested update page directly
                $msg = "You are not authorized to access this page directly";
                redirectHome($msg);

            }

            echo "</div>";
        }



//************************************************************* 
        
        elseif($do == "Add"){ // Add page ?> 
            <h1 class="text-center">Add New Member</h1>
            <div class="container">
                <form action="?do=Insert" class="" method="POST" enctype="multipart/form-data">
                   
                    <!-- username -->
                    <div class="form-group row">
                        <label class="col-sm-2 control-label edit-label">Username</label>
                        <div class="col-sm-4">
                            <input required type="text" name="username" class="form-control">
                        </div>
                    </div>
    
                    <!-- email -->
                    <div class="form-group row">
                        <label class="col-sm-2 control-label edit-label">Email</label>
                        <div class="col-sm-4">
                            <input required type="email" name="email" class="form-control">
                        </div>
                    </div>
    
                    <!-- full name  -->
                    <div class="form-group row">
                        <label class="col-sm-2 control-label edit-label">Full Name</label>
                        <div class="col-sm-4">
                            <input required type="text" name="fullname" class="form-control">
                        </div>
                    </div>
    
                    <!-- password -->
                    <div class="form-group row">
                        <label class="col-sm-2 control-label edit-label">Password</label>
                        <div class="col-sm-4">
                            <input required type="password" name="password" class="password form-control" autocomplete="new-password">
                            <i class="fa fa-eye fa-1x show-pass"></i>
                        </div>
                    </div>

                    <!-- Group ID  -->
                    <div class="form-group row">
                        <label class="col-sm-2 control-label edit-label">Privilage</label>
                        <div class="col-sm-4">
                                <input required type="text" name="groupid" class="form-control"placeholder="admin || user" >
                        </div>
                    </div>


                    <!-- User Avatar  -->
                    <div class="form-group row">
                        <label class="col-sm-2 control-label edit-label">Avatar</label>
                        <div class="col-sm-4">
                                <input type="file" name="avatar" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Add" class="btn btn-add-user btn-outline-dark" >
                        </div>
                    </div>
    
                </form>
            </div>

       <?php }




//************************************************************* 

        elseif($do == 'Insert'){ // Insert page

            echo "<div class='container'>";
            echo "<h1 class='text-center'>Insert</h1>";

            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                
                $username   = trim($_POST['username']);
                $email      = trim($_POST['email']);
                $password   = trim($_POST['password']);
                $fullname   = trim($_POST['fullname']);
                $groupid    = strtolower(trim($_POST['groupid']));

                $hash_password = sha1($password);

                $avatarName = $_FILES['avatar']['name'];
                $avatarSize = $_FILES['avatar']['size'];
                $avatarTemp = $_FILES['avatar']['tmp_name'];
                $avatarError = $_FILES['avatar']['error'];

                $allowed_ext = array('png', 'jpg', 'jpeg');

                $img_ext = strtolower(end(explode('.', $avatarName)));
                
                $img_name = rand(1, 9999999) . '_' . $avatarName ;
                



                // form validation 
               $formError = insertValidateForm($username, $email, $password, $fullname, $groupid, $img_ext, $allowed_ext, $avatarSize);
                
               foreach($formError as $error){
                echo "<div class='alert alert-danger'> " . $error . "</div>";
                }
        

                // if there is no error you can execute what's inside the if 
                if(empty($formError)){
                
                    $destination = 'uploads/avatars/' . $img_name;
                    
                    if (!move_uploaded_file($avatarTemp, $destination)) {
                        echo "Sorry, there was an error uploading your file.";
                    }


                    // if it was admin the value will be 1 if he is user value will be 0
                    if($groupid == 'admin'){
                        $groupid = 1;
                    }else{
                        $groupid = 0;
                    }

                    $stmt = $con->prepare("INSERT INTO 
                                           users(Username, Password, Email, Fullname, RegStatus, GroupID, Date, Avatar)
                                           VALUES(:vuser, :vpass, :vmail, :vname, :vreg, :vgroup, now(), :avatar) 
                                            ");

                    $stmt->execute(array(

                        'vuser' => $username,
                        'vpass' => $hash_password,
                        'vmail' => $email,
                        'vname' => $fullname,
                        'vreg'  => 1,
                        'vgroup' => $groupid,
                        'avatar' => $img_name


                    ));


                    $msg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Inserted </div>";
                    redirectHome($msg, "members.php");
                }else{
                    $msg = "<div class='alert alert-danger text-center'> [-] Error </div";
                    redirectHome($msg, "back");

                }




            }
            
            else{

                $msg = "<div class='alert alert-danger'> [-] You can't access this page directly. </div>";
                redirectHome($msg);
            }

            echo "</div>";
       }




//************************************************************* 

       elseif($do == "Delete"){ // Delete Page

            echo "<h1 class='text-center'>Delete User</h1>";
            
            $userid = isset($_GET['userId']) && is_numeric($_GET['userId'])? intval($_GET['userId']) : 0;

            // Check if the userid exist in the database
            $check = checkItem('UserID', 'users', $userid);
            
            if($check > 0){

                $stmt = $con->prepare("DELETE FROM users WHERE UserID = ?");
                $stmt->execute(array($userid));


                $msg = "<div class='text-center alert alert-success'>" . $count . " Record Deleted </div>";
                redirectHome($msg, "members.php");


            }else{
                $msg= "<div class='text-center alert alert-danger'> This user doesn't exist. </div>";
                redirectHome($msg);
            }

            

        }   




    
//************************************************************* 
        elseif($do == 'activate'){  // activate page

            $userid = isset($_GET['userId']) && is_numeric($_GET['userId'])? intval($_GET['userId']) : 0;

            $check = checkItem('UserID', 'users', $userid);

            if($check > 0){
                $stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE UserID=? ");
                $stmt->execute(array($userid)); 

                $msg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Activated </div>";

                redirectHome($msg, "members.php?status=pending");

            }else{

                $msg = "<div class='alert alert-danger'> This user doesn't exist </div>";
                redirectHome($msg);
            }
        }else{

            echo "<div class='alert alert-danger text-center'>This page doesn't exist </div>";
        }


        include $tpl . "footer.php";

    





    }
    else{
        
        header("Location: index.php");
        exit();

    }