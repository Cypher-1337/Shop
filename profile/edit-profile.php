<?php
    session_start();

    $pageTitle = 'Edit Profile';

    $action = isset($_GET['action']) ? $_GET['action'] : $action = "edit";
    
    include('init.php'); 
    
    if(isset($_SESSION['shop_user'])){
        $username = $_SESSION['shop_user'];
    

        $user_profile = getInfo($username);  
        $id = $user_profile['UserID']; 


        if($action == 'edit'){ // Edit Page
        ?>
            <div class="container">
                <div class="card bg-dark edit-profile-card">

                    <div class="card card-header">Edit Profile</div>
                    <div class="card card-body">
                        <h1 class='text-center'><?php echo $username; ?></h1>
                        <div class="row">
                            
                            <div class='col-md-7'> 
                                <form action="?action=update" class="" method="POST" enctype="multipart/form-data">
                                    
                                    <!-- username -->
                                    <div class="form-group row">
                                        <label class="col-sm-3 control-label edit-label">Username</label>
                                        <div class="col-sm-8">
                                            <input  type="text" name="username" class="form-control" value="<?php echo $user_profile['Username'] ?>" >
                                        </div>
                                    </div>
                    
                                    <!-- email -->
                                    <div class="form-group row">
                                        <label class="col-sm-3 control-label edit-label">Email</label>
                                        <div class="col-sm-8">
                                            <input  type="email" name="email" class="form-control" value="<?php echo $user_profile['Email'] ?>" >
                                        </div>
                                    </div>
                    
                                    <!-- full name  -->
                                    <div class="form-group row">
                                        <label class="col-sm-3 control-label edit-label">Full Name</label>
                                        <div class="col-sm-8">
                                            <input  type="text" name="fullname" class="form-control" value="<?php echo $user_profile['Fullname'] ?>" >
                                        </div>
                                    </div>
                    
                                    <!-- password -->
                                    <div class="form-group row">
                                        <label class="col-sm-3 control-label edit-label">Password</label>
                                        <div class="col-sm-8">
                                            <input type="hidden" name="old-password" value="<?php echo $user_profile['Password'] ?>">
                                            <input type="password" name="password" class="password form-control" autocomplete="new-password">

                                            <i class="fa fa-eye fa-1x show-pass"></i>
                                        </div>
                                    </div>

                                    <!-- User Avatar  -->
                                    <div class="form-group row">
                                        <label class="col-sm-3 control-label edit-label">Avatar</label>
                                        <div class="col-sm-8">
                                            <input type="file" name="avatar" class="form-control">
                                            <!-- old photo  -->
                                            <input type="hidden" name="old_avatar" class="form-control" value="<?php echo $user_profile['Avatar'] ?>" >

                                        </div>
                                    </div>

                                    <!-- Submit Button  -->
                                    <div class="form-group">
                                        <div>
                                            <input type="submit" name='edit' value="Edit Profile" class="btn btn-edit-profile btn-dark" >
                                        </div>
                                    </div>
                    
                                </form>
                            </div>
                            
                            <div class='col-md-4 mx-auto'> 
                                <div class="user-avatar-box">
                                    <img class='img-thumbnail user-img' src='../admin/uploads/avatars/<?php echo $user_profile['Avatar']; ?>' alt='item' >
                                    
                                    <div class='caption'>
                                        <h3> ID: <?php echo $user_profile['UserID'] ?>  </h3>
                                        <h3>Name: <?php echo $user_profile['Username'] ?>  </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<?php
        }
        
        if($action == "update"){

            if(isset($_POST['edit'])){
                
                $username = trim(filter_var($_POST['username'], FILTER_SANITIZE_STRING));
                $email = trim(filter_var($_POST['email'], FILTER_SANITIZE_STRING));
                $full_name = trim(filter_var($_POST['fullname'], FILTER_SANITIZE_STRING));


                // old avatar if user didn't upload an image
                $old_avatar = $_POST['old_avatar'];

                $password = empty($_POST['password'])? $password = $_POST['old-password'] : $password = sha1($_POST['password']);
                
                // get Avatar details if user upload image
                $avatarName = $_FILES['avatar']['name'];
                $avatarSize = $_FILES['avatar']['size'];
                $avatarTemp = $_FILES['avatar']['tmp_name'];
                $avatarError = $_FILES['avatar']['error'];


                
                $allowed_ext = array('png', 'jpg', 'jpeg');

                $tmp_img = explode('.', $avatarName);
                $img_ext = end($tmp_img);
                
                $img_name = rand(1, 9999999) . '_' . $avatarName ;


                // start validate the form
                // if $avatarName is not empty check img extension and size
                if(!empty($avatarName)){
                    $formErrors = editValidateForm($username, $email, $full_name, $img_ext, $allowed_ext, $avatarSize);
                }
                // if it is empty ignore file upload restrictions 
                else{
                    $formErrors = editValidateForm($username, $email, $full_name);
                }

                // loops into errors and print them
                foreach($formErrors as $error){
                    echo "<div class='alert alert-danger '>" . $error . "</div>" ;
                }


                // check if there is no error
                if(empty($formErrors)){

                    // if $avatarName not empty means that client uploaded image 
                    if(!empty($avatarName)){
                        $destination = '../admin/uploads/avatars/' . $img_name;
                        
                        if (!move_uploaded_file($avatarTemp, $destination)) {
                            echo "Sorry, there was an error uploading your file.";
                        }
                    // if empty update the db with the old value of Avatar 
                    }else{
                        $img_name = $old_avatar;
                    }



                    $stmt2 = $con->prepare("SELECT * FROM users WHERE Username = ? AND UserID != ?");
                    $stmt2->execute(array($username, $id));
                    $count = $stmt2->rowCount();

                    if($count > 0){
                        echo "<div class='alert alert-danger text-center'>User already exist</div>";
                    }else{
                        // update db
                        $stmt = $con->prepare("UPDATE users SET Username = ?, Email = ?, Fullname = ?, Avatar = ?, Password = ? WHERE UserID= ?");
                        $stmt->execute(array($username, $email, $full_name,  $img_name, $password, $id));
                        $count = $stmt->rowCount();
                        
                        
                        
                        if($count > 0){
                            // create new session with new Username
                            $stmt4 = $con->prepare("SELECT * FROM users WHERE Username = ? ");
                            $stmt4->execute(array($username));
                            $fetch = $stmt4->fetch();

                            $_SESSION['shop_user'] = $fetch['Username'];         // create session with username
                            $_SESSION['uid'] = $fetch['UserID'];                // create session with user id
            
                            header("Location: /shop/profile/edit-profile.php");
                        }
                    }

                }

            }
            
            // if client access edit-profile.php?action=update directly execute below code
            else{
                $msg = "<div class='alert alert-danger text-center'>you can't access this page directly.</div>";
                redirectHome($msg, '/shop/profile/edit-profile.php', 1);
            }

        }

        
?>




<?php }
    else{
        header("Location: /shop/login.php");
    }
?>

<?php include $tpl . 'footer.php' ?>