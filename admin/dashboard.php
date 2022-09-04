<?php   
    session_start();
    if(isset($_SESSION['user'])){

        $pageTitle = "Admin Dashboard";
        include("init.php");


        
        
        $i_reg = 5;
        $count_items = countItems("Item_ID", "items" );
        $latestItems = getLatest("*", "items", "Item_ID", $i_reg);
        
        $count = countItems("UserID", "users");
        $reg = 5;
        $latestUsers = getLatest("*", "users", "UserID", $reg);


        $stmt = $con->prepare(" SELECT 
                                    comments.*,users.Username AS User
                                FROM 
                                    comments
                                INNER JOIN
                                    users
                                ON 
                                    users.UserID = comments.User_ID
                                ORDER BY C_id DESC
                                ");
        $stmt->execute();
        $comments = $stmt->fetchAll();        

        ?>
        
        <div class="home-stats">
            <div class='container text-center'>
            
                <h1 class='text-center'>Dashboard</h1>
            
                <div class="row">

                    <div class="col-md-3">
                        <div class="stat st-members">
                            <i class='fa fa-users'></i>
                            <div class="info">
                                Total Members
                                <span> <a href='members.php'> <?php echo $count ?> </a> </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="stat st-pending">
                            <i class='fa fa-user-plus'></i>
                            <div class="info">
                                Pending Users
                                <span> <a href='members.php?status=pending'> <?php echo checkItem("RegStatus", "users", 0) ?> </a> </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="stat st-items">
                            <i class='fa fa-tag'></i>
                            <div class="info">
                                Total Items
                                <span> <a href='items.php'><?php echo countItems("Item_ID", 'items'); ?> </a> </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="stat st-comments">
                           <i class="fa fa-comments"></i>
                            <div class="info">
                                Total Comments
                                <span> <a href='comments.php'><?php echo countItems("C_id", 'comments'); ?> </a></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container latest">
            <div class="row">

                <div class="col-sm-6 ">

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-user"></i> Latest <?php echo $reg ?> Registered Users
                            <span class='toggle-info float-right '>
                                <i class='fa fa-plus fa-lg'></i>
                            </span>
                        </div>
                    
                        <div class="panel-body">
                            <?php 
                                echo '<ul class="list-group ">';
                                foreach($latestUsers as $user){

                                    echo '<li class="list-group-item">' . $user['Username'] . '<a href="members.php?do=Edit&userId=' .$user['UserID'] . '"><div class="btn btn-edit btn-success float-right"> <i class="fa fa-edit"></i> Edit</div> </a> ';

                                    if($user['RegStatus'] == 0){
                                        echo "<a href='members.php?do=activate&userId=" . $user['UserID'] . "'><div class='btn btn-info-dashboard btn-info float-right'>Activate</div></a>";
                                    }


                                    echo "</li>";
                                }
                                echo '</ul>';
                            ?>

                        </div>

                    </div>

                </div>

                <div class="col-sm-6 ">
                   
                    <div class="panel panel-default">
                        
                        <div class="panel-heading">
                            <i class="fa fa-tag"></i> Latest <?php echo $i_reg ?> Registered Items
                            <span class='toggle-info float-right '>
                                <i class='fa fa-plus fa-lg'></i>
                            </span>
                        </div>

                        <div class="panel-body">
                        <?php 
                                echo '<ul class="list-group ">';
                                foreach($latestItems as $item){

                                    echo '<li class="list-group-item">' . $item['Name'] . '<a href="items.php?page=edit&item_id=' .$item['Item_ID'] . '"><div class="btn btn-edit btn-success float-right"> <i class="fa fa-edit"></i> Edit</div> </a> ';

                                    if($item['Approve'] == 0){
                                        echo "<a href='items.php?page=approve&item_id=" . $item['Item_ID'] . "'><div class='btn btn-info-dashboard btn-info float-right'>Activate</div></a>";
                                    }


                                    echo "</li>";
                                }
                                echo '</ul>';
                            ?>

                        </div>

                    </div>
                    
                </div>

            </div>


            <div class="row">


                <div class="col-sm-6 ">
                   
                    <div class="panel panel-default">
                        
                        <div class="panel-heading">
                            <i class="fa fa-tag"></i> Latest Comments
                            <span class='toggle-info float-right '>
                                <i class='fa fa-plus fa-lg'></i>
                            </span>
                        </div>

                        <div class="panel-body">
                        <?php 

                                foreach($comments as $comment){
                                    echo "<div class='comment-box'>";
                                        echo "<span class='c-user'>" . $comment['User'] . "</span>";
                                        echo "<span class='c-comment'>" . $comment['Comment'] . "</span>";

                                    echo "</div>";
                                }

                            ?>

                        </div>

                    </div>
                    
                </div>

            </div>

        </div>


        <?php

        include $tpl . "footer.php";

    }else{
        header("Location: index.php");
        exit();
    }

?>