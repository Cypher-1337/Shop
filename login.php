<?php
    session_start();
    
    if(isset($_SESSION['shop_user'])){
        header("Location: index.php");
    }

    $pageTitle = "Login";
    include('init.php');
    
    $fail_msgs = [];
    $success_msgs = [];

    ?>

<div class='container'>

<?php
    // login function 
    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        // Login function 
        if(isset($_POST['login'])){

            $name  = $_POST['username'];
            $password = $_POST['password'];
            
            $hash_pass = sha1($password);

            $stmt = $con->prepare("SELECT * FROM users WHERE Username = ? AND Password = ?");
            $stmt->execute(array($name, $hash_pass));
            $fetch = $stmt->fetch();

            // if username & password correct create session with shop_user
            if($stmt->rowCount() > 0){
                $_SESSION['shop_user'] = $name;         // create session with username
                $_SESSION['uid'] = $fetch['UserID'];    // create session with user id


                header("Location: index.php");
            }
            // if username | password is incorrect display the same message for security resons 
            else{
                $fail_msgs[] = "Incorrect <b>Username</b> or <b>Password</b>";
            }
        }


        // Signup function
        elseif(isset($_POST['signup'])){

            $username   =   filter_var($_POST['username'], FILTER_SANITIZE_STRING);
            $email      =   $_POST['email'];
            $fullname   =   filter_var($_POST['fullname'], FILTER_SANITIZE_STRING);
            $password   =   $_POST['password'];
            $password2  =   $_POST['password2'];

            $hash_pass = sha1($password);


            // User Avatar
            $avatarName = $_FILES['avatar']['name'];
            $avatarSize = $_FILES['avatar']['size'];
            $avatarTemp = $_FILES['avatar']['tmp_name'];
            $avatarError = $_FILES['avatar']['error'];

            $allowed_ext = array('png', 'jpg', 'jpeg');

            $tmp_img = explode('.', $avatarName);
            $img_ext = end($tmp_img);

            $img_name = rand(1, 9999999) . '_' . $avatarName ;
    
    
            $form_errors = insertValidateForm($username, $email, $fullname, $password, $password2, $img_ext, $allowed_ext, $avatarSize);
         
            
            if(!empty($form_errors)){               // Display Errors if any 
                foreach($form_errors as $error){
                    $fail_msgs[] = $error;
                }
            }
            else{                                   // insert to the database


                // uploading img to upload/avatars folder
                $destination = 'admin/uploads/avatars/' . $img_name;
                    
                if (!move_uploaded_file($avatarTemp, $destination)) {
                    echo "Sorry, there was an error uploading your file.";
                }


                $stmt = $con->prepare("INSERT INTO users(Username, Email, Fullname, Avatar, Password, Date)
                                       VALUES(?,?,?,?,?,now())");
                $stmt->execute(array($username, $email, $fullname, $img_name, $hash_pass));
                $count = $stmt->rowCount();

                $success_msgs[] = "You Signed Up Successfully";
            }
        }

    }
    




?>
</div>


<div class="container sign-page">
    <h1 class='text-center'>
        <span class='active' data-class='login'>Login</span> | <span class='signup-color' data-class='signup'>Sign-up</span>
    </h1>

    <!-- Login Form -->
    <form class='login' action="login.php" method="POST">

        <input required class='form-control input-control' type="text" name="username" placeholder="Username">
        <input required class='form-control input-control' type="password" name="password" placeholder="Password">
        <input type="submit" class='btn btn-primary btn-block login-submit' name='login' value='Login'>
        

    </form>

    <!-- Signup Form  -->
    <form class='signup' action="login.php" method="POST" enctype="multipart/form-data">
        
        <input pattern=".{4,}" title="Username can't be less than 4 chars" class='form-control input-control' type="text" name="username" placeholder="Username" required >
        <input class='form-control input-control' type="email" name="email" placeholder="Email" required >
        <input class='form-control input-control' type="text" name="fullname" placeholder="Full Name" required >
        <input minlength='6' class='form-control input-control' type="password"  name="password" placeholder="Password" required >
        <input minlength='6' class='form-control input-control' type="password"  name="password2" placeholder="Confirm Password" required >
        <input type="file" name="avatar" class="form-control input-control" required>
        <input type="submit" class='btn btn-success btn-block login-submit' name='signup' value='Sign up'>
        

    </form>

<?php

    // display error msgs if any
    if(!empty($fail_msgs)){ 
        
        echo '<div class="msgs-box">';
        foreach($fail_msgs as $msg){
            echo '<div class="errors-msg">';
                echo $msg;
            echo '</div>';
        }
        echo '</div>';
    }

    // display successful msgs if any
    if(!empty($success_msgs)){
        echo '<div class="msgs-box">';
        foreach($success_msgs as $msg){
                echo '<div class="success-msg">';
                    echo $msg;
                echo '</div>';
            }
        echo '</div>';
    } 
    
?>

</div>


<?php include $tpl . 'footer.php'; ?>
