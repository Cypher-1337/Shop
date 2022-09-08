<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href= "<?php echo $css ?>bootstrap.min.css" />
        <link rel="stylesheet" href= "<?php echo $css ?>all.min.css" />
        <link rel="stylesheet" href= "<?php echo $css ?>frontend.css" />
        <link rel="stylesheet" href= "<?php echo $css ?>fontawesome.min.css" />

        <title><?php getTitle() ?></title>
    </head>
    <body>
        <div class='upper-nav'>

            <div class="navbar">
                    <!-- check if user signed in -->
                    <?php
                        if(isset($_SESSION['shop_user'])){ 
                        
                            $username = $_SESSION['shop_user'];

                            $user = $con->prepare("SELECT * FROM users WHERE Username = ?");
                            $user->execute(array($username));

                            $get_user = $user->fetch();

                    ?>
                        <div class='btn-group info-dropdown '>

                            <!-- my avatar  -->
                            <?php
                                echo "<img src='/shop/admin/uploads/avatars/";
                                if(!empty($get_user['Avatar'])){
                                    echo $get_user['Avatar'];
                                }else{
                                    echo 'default.jpg';
                                }
                                echo "' class='img-responsive rounded-circle user-uppernav-img'>";
                            ?>

                            <!-- dropdown menu  -->
                            <span class="my-name btn dropdown-toggle my-auto" data-toggle='dropdown'>
                                <?php echo $_SESSION['shop_user']; ?>
                                <span class='caret'></span>
                            </span>

                            <!-- dropdown items  -->
                            <ul class='dropdown-menu bg-secondary'>
                                <li><a href="/shop/profile.php" class='dropdown-item bg-secondary'>My Profile</a></li>
                                <li><a href="/shop/additem.php" class='dropdown-item bg-secondary'>New Item</a></li>
                                <li><a href="/shop/logout.php" class='dropdown-item bg-secondary'>Logout</a></li>
                            </ul>

                        </div>
                        
                    <?php } else{
                ?>

                <div class="sign mr-auto">
                        <a class='' href="/shop/login.php">Login</a>
                       
                <?php } ?>
            </div>
        </div>


    </div>
