<?php
    session_start();

    $pageTitle = 'Item';

    
    include('init.php'); 

    $itemId = isset($_GET['item_id']) && is_numeric($_GET['item_id'])? intval($_GET['item_id']) : 0;
    $check = checkItem('Item_ID', 'items', $itemId);
    
    
    
    if ($check > 0 ){
        $stmt = $con->prepare("SELECT items.*, categories.Name AS Cat_Name, users.Username
    
                                FROM
                                    items
                                INNER JOIN 
                                    categories
                                ON 
                                    categories.ID = items.Cat_ID
                                INNER JOIN
                                    users
                                ON
                                    users.UserID = items.Member_ID
                                WHERE 
                                    Item_ID = ?
                                ");
        $stmt->execute(array($itemId));
        $item = $stmt->fetch();

        if($item['Approve'] != 3){

?>



<div class="container">
    <h1 class='text-center'><?php echo $item['Name'] ?></h1>
    <div class="row">
        
        <div class="col-md-3 show-item">

            <div class='thumbnail item-box '>
                <span class='price-tag'> <?php echo $item['Price'] ?>   </span>
                <img class='img-thumbnail item-img ' src='includes/imgs/assassin.jpg' alt='item' >
                
                <div class='caption'>
                    <h3> <?php echo $item['Name'] ?>  </h3>
                    <p> <?php echo $item['Description'] ?>  </p>
                </div>

            </div>

        </div>

        <div class="col-md-9">
            <div class="card bg-secondary">
                <div class="card card-header item-head text-center"> <?php echo $item['Name'] ?></div>
                <div class="card card-body item-card">
                    <h2><?php echo $item['Name'] ?></h2>
                    <span class='desc'><?php echo $item['Description'] ?></span>
                    <ul class='list-group item-ul'>
                        <li class='list-group-item'><i class='fa fa-money-bill edit-icon'> </i> <span> Price: </span><b><?php echo $item['Price'] ?>$</b></li>
                        <li class='list-group-item'><i class='fa fa-calendar-check edit-icon'> </i> <span> Date: </span><?php echo $item['Add_Date'] ?></li>
                        <li class='list-group-item'><i class='fa fa-earth edit-icon'> </i> <span> Country: </span><?php echo $item['Country'] ?></li>
                        <li class='list-group-item'><i class='fa fa-tags edit-icon'> </i> <span> Category: </span><a href="categories.php?cat_id=<?php echo $item['Cat_ID'] ?>&cat_name=<?php echo $item['Cat_Name'] ?>"><?php echo $item['Cat_Name'] ?></a></li>
                        <li class='list-group-item'><i class='fa fa-user edit-icon'> </i> <span>Publisher: </span><a href='#'><?php echo $item['Username'] ?></a></li>
                        <li class='list-group-item'><i class='fa fa-user edit-icon'> </i> <span>Tags: </span>
                        <?php
                            if(!empty($item['Tags'])){
                                $tags = explode(',',$item['Tags']);
                                foreach($tags as $tag){
                                    if(!empty($tag)){
                                        echo "<a href='tags.php?name=$tag' class='edit-tag'>$tag </a> ";
                                    }
                                }
                            }else{
                                echo "There is no tags";
                            }
                        ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>

<hr>
    <div class="row">
        <div class="offset-md-3">
            <h2>Add your comment</h2>
            <form action="#" method="POST">
                <textarea required name="comment" class="form-control comment-text" cols='55' rows='4'></textarea>
                <input class="btn btn-dark add-comment" type="submit" value='Add Comment'>
            </form>
        </div>
    </div>
    <?php

        if($_SERVER['REQUEST_METHOD'] == "POST"){
            if(isset($_SESSION['shop_user'])){
                $comment = $_POST['comment'];

                if(!empty($comment)){
                    $c_stmt = $con->prepare("INSERT INTO comments(Comment, Item_ID, User_ID, Comment_Date)
                                            VALUES(?, ?, ?, now()) ");

                    $c_stmt->execute(array($comment, $itemId, $_SESSION["uid"]));
                

                    if($c_stmt){
                        echo "<div class='alert alert-success text-center'>Your comment added successfully.</div>";
                    }else{
                        echo "<div class='alert alert-danger text-center'>Unexpected Error.</div>";

                    }
                }else{
                    echo "<div class='alert alert-danger text-center'>Comment Section Can't be empty.</div>";
                }
            }else{
                echo "<script>alert('you have to be logged in first')</script>";
            }
        }

    ?>
<hr>

<?php
    $stmt = $con->prepare(" SELECT 
                                comments.*,
                                users.Username AS User
                            FROM 
                                comments
                            INNER JOIN
                                users
                            ON 
                                users.UserID = comments.User_ID
                            WHERE 
                                Item_ID = ?
                            AND
                                Status = 1 ");
    $stmt->execute(array($itemId));
    $rows = $stmt->fetchAll();

?>



    <div class='card bg-dark'>
        <div class="card card-header"></div>
        <div class="card card-body">
        <?php 
                if(!empty($rows)){
                    foreach($rows as $row){ ?>
                        <div class="comment-box">
                            <div class="row">
                                <div class='col-md-3'> 
                                    <img src='includes/imgs/avatar4.jpg' class='img-responsive img-thumbnail rounded-circle user-comment-img'>
                                    <div> <?php echo $row['User'] ?> </div> 
                                </div>
                                <div class='col-md-9 my-auto'> 
                                    <div class="comment-section">
                                        <?php echo $row['Comment'] ?>  
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                    <?php }
                }else{
                    echo "<div class='alert alert-info text-center'>There is no comments on this item</div>";
                }
            ?>
        </div>
          
    </div>

</div>


<?php
        }else{
        echo "<div class='alert alert-info text-center'>Item Waiting for approval</div>";
            
        }
    }
    
    else{
        echo "<div class='alert alert-danger text-center'>Item id isn't valid </div>";
    }

include $tpl . 'footer.php';
 
?>




