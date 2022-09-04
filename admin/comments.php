<?php

    session_start();

    if(isset($_SESSION['user'])){
        $pageTitle= "Comments";
        include("init.php");


        $page = isset($_GET['page']) ? $_GET['page'] : $page = "manage";

        
        
//************************************************************* 
        
        if($page == "manage"){ // Manage page
        


            $stmt = $con->prepare(" SELECT 
                                     comments.*,items.Name
                                    AS Item_Name,
                                     users.Username AS User
                                    FROM 
                                     comments
                                    INNER JOIN
                                     items
                                    ON
                                     items.Item_ID = comments.Item_ID
                                    INNER JOIN
                                     users
                                    ON 
                                     users.UserID = comments.User_ID");
            $stmt->execute();
            $rows = $stmt->fetchAll();
            

            if(!empty($rows)){
            ?>

                <h1 class="text-center">Manage Comments</h1>
                <div class="container">
                    <div class="table-responsive">
                        <table class="main-table text-center table table-bordered">
                            <tr>
                                <td>#ID</td>
                                <td>Comment</td>
                                <td>Item</td>
                                <td>User</td>
                                <td>Date</td>
                                <td>Control</td>
                            </tr>
                            <?php

                                foreach($rows as $row){

                        
                                    echo "<tr>";
                                        echo "<td>" . $row["C_id"]   . "</td>";
                                        echo "<td>" . $row["Comment"] . "</td>";
                                        echo "<td>" . $row["Item_Name"]    . "</td>";
                                        echo "<td>" . $row["User"] . "</td>";
                                        echo "<td>" . $row["Comment_Date"] . "</td>";
                                        echo "<td>" .    
                                        '<a href="comments.php?page=edit&c_id=' . $row["C_id"] . '"class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
                                        <a href="comments.php?page=delete&c_id=' .$row["C_id"] . '" class="btn btn-danger confirm"><i class="fa fa-close"></i> Delete</a>';

                                        if($row['Status'] == 0){

                                            echo '<a href="comments.php?page=approve&c_id=' . $row["C_id"] . '" class="btn btn-info"><i class="fa fa-user-check"></i> Activate</a>' . '</td>';

                                        }

                                    echo "</td>";

                                }

                            ?>
                        </table>
                    </div>

                </div>

            <?php }
            else{
                echo "<div class='container empty-box'>";
                    echo "<div class='empty-msg'>No <i class='fa fa-comments'></i> Comments at the moment</div>";
                echo "</div>";

            }
        }



//************************************************************* 

        elseif ($page == "edit"){  // Edit page
            
            
            // check if the userId exist and numeric 
            $c_id = isset($_GET['c_id']) && is_numeric($_GET['c_id'])? intval($_GET['c_id']) : 0;
                
            // getting the user details that registered with the id
            $stmt = $con->prepare("SELECT * FROM comments Where C_id = ? ");

            $stmt->execute(array($c_id));
            $row = $stmt->fetch();
            $count = $stmt->rowCount();

            // check if the id exists in the db  &  show the form
            if($count > 0){ ?>
                <h1 class="text-center">Edit Comment</h1>
                        <div class="container">
                            <form action="?page=update" class="" method="POST">
                               
                                <input type="hidden" name="c_id" value="<?php echo $c_id ?>" >
                               
                                <!-- comment -->
                                <div class="form-group row">
                                    <label class="col-sm-2 control-label edit-label">Comment</label>
                                    <div class="col-sm-4">
                                        <textarea class='form-control' name='comment' rows='8'><?php echo $row["Comment"] ?></textarea>
                                    </div>
                                </div>
                
                             
                
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="submit" value="Update" class="btn btn-update-cat btn-success btn-sg">
                                    </div>
                                </div>
                
                            </form>
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

        elseif($page == "update"){ // Update Page

            echo "<div class='container'>";
            echo "<h1 class='text-center'>Update</h1>";

            // check if the user came from the edit form
            if($_SERVER['REQUEST_METHOD'] == 'POST'){

                $c_id    =   $_POST['c_id'];
                $comment =   $_POST['comment'];
               



                    // update db
                    $stmt = $con->prepare("UPDATE comments SET Comment = ? WHERE C_id= ?");
                    $stmt->execute(array($comment, $c_id));
                    $count = $stmt->rowCount();
    
                    $msg = "<div class='alert alert-success'>" . $count . " Record updated </div>";
                    redirectHome($msg);
                

            
                
            }else{
                // if the client requested update page directly
                $msg = "You are not authorized to access this page directly";
                redirectHome($msg);

            }

            echo "</div>";
        }




//************************************************************* 

       elseif($page == "delete"){ // Delete Page

            echo "<h1 class='text-center'>Delete Comment</h1>";
            
            $c_id = isset($_GET['c_id']) && is_numeric($_GET['c_id'])? intval($_GET['c_id']) : 0;

            // Check if the userid exist in the database
            $check = checkItem('C_id', 'comments', $c_id);
            
            if($check > 0){

                $stmt = $con->prepare("DELETE FROM comments WHERE C_id = ?");
                $stmt->execute(array($c_id));


                $msg = "<div class='text-center alert alert-success'>" . $count . " Record Deleted </div>";
                redirectHome($msg, "comments.php");


            }else{
                $msg= "<div class='text-center alert alert-danger'> This user doesn't exist. </div>";
                redirectHome($msg);
            }

            

        }   




    
//************************************************************* 
        elseif($page == 'approve'){  // approve page

            $c_id = isset($_GET['c_id']) && is_numeric($_GET['c_id'])? intval($_GET['c_id']) : 0;

            $check = checkItem('C_id', 'comments', $c_id);

            if($check > 0){
                $stmt = $con->prepare("UPDATE comments SET Status = 1 WHERE C_id=? ");
                $stmt->execute(array($c_id)); 

                $msg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Approved </div>";

                redirectHome($msg, "comments.php", 1);

            }else{

                $msg = "<div class='alert alert-danger'> This user doesn't exist </div>";
                redirectHome($msg, 'comments.php', 1);
            }
        }
        
        
        else{

            echo "<div class='alert alert-danger text-center'>This page doesn't exist </div>";
        }


        include $tpl . "footer.php";

    





    }
    else{
        
        header("Location: index.php");
        exit();

    }