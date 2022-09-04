<?php   

    // if the user already logged in and requested the login page redirect him to the admin panel
    session_start();
    if(isset($_SESSION['user'])){
        header("Location: dashboard.php");
    }

    $noNavBar = '';
    $pageTitle = "Admin Login";
    include("init.php");


    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $username = $_POST['user'];
        $password = $_POST['pass'];
        $hash_pass = sha1($password);

        $stmt = $con->prepare("SELECT UserID, Username, Password
                                FROM users  
                                Where Username = ? 
                                AND Password = ? 
                                AND GroupID = 1
                                LIMIT 1");

        $stmt->execute(array($username, $hash_pass));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();

        if($count > 0){
            $_SESSION['user'] = $username;
            $_SESSION['id'] = $row["UserID"];
            header("Location: dashboard.php");
            exit();
        }

    }
?>


<form action="<?php echo $_SERVER['PHP_SELF'] ?>" METHOD="POST" class="login" >
    <h3 class="text-center">Admin Login</h3>
    <input class="form-control" type="text" name="user" placeholder="Username">
    <input class="form-control" type="password" name="pass" placeholder="Password">
    <input class="btn btn-primary btn-block" type="submit" value="Login">

</form>



<?php include($tpl . "footer.php"); ?>