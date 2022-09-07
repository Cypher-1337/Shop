<?php
    session_start();

    $pageTitle = 'Profile';

    
    include('init.php'); 


// if user logged in show him his profile
    if(isset($_SESSION['shop_user'])){
        
        ?>

<!-- profile card -->
<h1 class='text-center'>My Profile</h1>
<div class="info">
    <div class="container">
            <div class="card edit-card">
                <div class="card-header bg-primary info-head text-center">ACCOUNT INFO</div>
                <div class="card-body bg-light">
                <?php 
                    // display user information 
                    $info = getInfo($_SESSION['shop_user']);
                    echo "<ul class='list-group info-ul'>";

                        echo "<li class='list-group-item'><i class='fa fa-user-group info-icon'> </i> <span>Username: </span>";         
                            echo  $info['Username'];
                        echo "</li>";

                        echo "<li class='list-group-item'><i class='fa fa-envelope info-icon'> </i> <span>Email: </span> ";            
                            echo  $info['Email']   ;
                        echo "</li>";

                        echo "<li class='list-group-item'><i class='fa fa-user-circle info-icon'> </i> <span>Full Name: </span> ";        
                            echo  $info['Fullname'];
                        echo "</li>";

                        echo "<li class='list-group-item'><i class='fa fa-calendar-day info-icon'> </i> <span>Register Date: </span> ";    
                            echo  $info['Date']    ;
                        echo "</li>";


                        echo "<li class='list-group-item'><i class='fa fa-id-card info-icon'> </i> <span>User ID: </span>";          
                            echo $info['UserID']  ;
                        echo "</li>";
                        
                        // if GroupID = 1 that means user is admin else he is user
                        echo "<li class='list-group-item'><i class='fa fa-user info-icon'> </i> <span>Account Privilages: </span>";
                        
                        if($info['GroupID'] == 1){
                            echo " Admin </li>";
                        }else{
                            echo " User </li>";
                        }

                    echo "</ul>";

                ?>

                <button class='btn btn-primary edit-my-profile' onclick='document.location.href = "profile/edit-profile.php"'>Edit Profile</button>
                </div>
            </div>
    </div>
</div>



<!-- ads card  -->
<div class="ads">
    <div class="container">
            <div class="card edit-card">
                <div class="card-header bg-success ads-head text-center">MY ITEMS</div>
                <div class="card-body bg-light">
                <?php
                
                // get user's ads
                    $ads = getAllFrom('*', 'items', ' WHERE Member_ID = ' . $info['UserID'], 'Item_ID', );
                    echo "<div class='row'>";
                        if(!empty($ads)){
                            foreach($ads as $ad){

                                echo "<div class='col-sm-6 col-md-4'>";
                                    echo "<div class='thumbnail item-box'>";
                                    echo "<span class='price-tag'>" . $ad['Price'] . " $</span>";
                                        
                                        echo "<img class='img-thumbnail item-img ' src='admin/uploads/items/";
                                        echo $ad['Image'];
                                        echo "' alt='item' >";

                                        echo "<div class='caption'>";
                                            
                                        echo "<h3><a href='items.php?item_id=" . $ad['Item_ID'] . "'>"  . $ad['Name'] . "</a></h3>";
                                        echo "<p>" . $ad['Description'] . "</p>";
                                            if($ad['Approve'] == 0){
                                                echo "<p class='not-approved'> this ad not approved yet </p>";
                                            }
                                        echo "</div>";
                    
                                    echo "</div>";
                                echo "</div>";
                            }
                        }else{
                            echo "<div class='mx-auto'>There is no items to show. </div>";
                        }
                    echo "</div>"
                ?>

                </div>
            </div>
    </div>
</div>


<!-- items card  -->
<div class="comments">
    <div class="container">
            <div class="card edit-card">
                <div class="card-header bg-warning comment-head text-center">MY COMMENTS</div>
                <div class="card-body bg-light">
                <?php
                    $comments = getAllFrom('*', 'comments', 'WHERE User_ID = ' . $info['UserID'], 'C_id');
                    if(!empty($comments)){
                        foreach($comments as $comment){ ?>
                            <div class="row">
                                <div class="col-md-3"><?php echo $comment['Item_ID'] ?></div>
                                <div class="col-md-9"><?php echo $comment['Comment'] ?></div>
                            </div>
                        
                        <?php }
                    }
                    else{
                        echo "<div class='text-center'>There is no comments to show</div>";
                    }
                ?>

                </div>
            </div>
    </div>
</div>

<?php

}

else
// if user not logged in redirect him to login page 
{
    header("Location: login.php");
    exit();
}

include $tpl . 'footer.php';
 
?>




