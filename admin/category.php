<?php

session_start();
if(isset($_SESSION['user'])){

    $pageTitle='Category';
    include('init.php');

    
    $page = isset($_GET['page']) ? $_GET['page'] : $page = "manage";



//*********************************************************************************
    if($page == 'manage'){ // manage page

        $sort = 'ASC';

        $sort_array = array('ASC', 'DESC');


        if(isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)){
            $sort = $_GET['sort'];
        }

        

        $cats = getAllFrom('*', 'categories', '', 'Ordering', $sort);

        if(!empty($cats)){
            ?>
            <h1 class='text-center'>Manage Category</h1>
            <div class="container">
                <div class="panel panel-default">

                    <div class="panel-heading category-heading"><i class='fa fa-edit'></i> Categories
                        <div class='option float-right'>   
                            <i class='fa fa-sort'></i> Order:[  
                            <a class='<?php if($_GET["sort"] == "ASC"){echo "active";}?>' href="?sort=ASC">ASC</a> |
                            <a class='<?php if($_GET["sort"] == "DESC"){echo "active";}?>' href="?sort=DESC">DESC </a> ]

                            <i class='fa fa-eye'> </i> View:[
                            <span class='active' data-view='full'>Full</span> |
                            <span data-view='classic'>Classic </span>]
                        </div>

                    </div>
                    <div class="panel-body panel-cat">
                        <div class="cat">
                        <?php
                            foreach($cats as $cat){
                                echo "<div class='category'>";
                                    echo "<div class='cat-buttons float-right'>";
                                        echo "<a class='btn cat-btn btn-outline-primary' href='category.php?page=edit&catid=" . $cat['ID'] . "'><i class='fa fa-edit'></i> Edit</a>";
                                        echo "<a class='btn cat-btn btn-outline-danger confirm_cat' href='category.php?page=delete&catid=" . $cat['ID'] . "'><i class='fa fa-close'></i> Delete</a>";
                                    echo "</div>";

                                    echo "<h4 class='cat-name'>" .$cat['Name'] . "</h4>";
                                    if($cat['Parent'] != 0){
                                        echo "<h6 class='sub-cat-manage'> Sub Category </h6>";
                                    }
                                    echo "<div class='full-view'>";
                                        echo "<div class='cat-desc'>";
                                        if(empty($cat['Description'])){ echo "No Description";}else{echo $cat['Description'];}
                                        echo "</div>";

                                        echo "<div class='cat-alerts'>";
                                            if($cat['Visible'] == 1){echo "<span class='cat-hidden cat-span'> Hidden </span>";}
                                            if($cat['Allow_Comments'] == 1){echo "<span class='cat-comment cat-span'> Comments disabled </span>";}
                                            if($cat['Allow_Ads'] == 1){echo "<span class='cat-ads cat-span'> Ads disabled </span>";}
                                        echo "</div>";
                                    echo "</div>";
                                echo "</div>";
                                echo "<div class='border'></div>";
                            }
                        ?>
                        </div>
                        
                    </div>
                </div>

                <a class='cat-add btn btn-info' href="category.php?page=add"><i class='fa fa-add'></i> New Category</a>
            </div>

        
    <?php   }
        else{
            echo "<div class='container empty-box'>";
                echo "<div class='empty-msg'>No <i class='fa fa-tag'></i> Categories at the moment</div>";
                echo "<a href='category.php?page=add' class='btn btn-primary'><i class='fa fa-plus'></i> Add Category</a>";
            echo "</div>";
    }
    }
    
    
    
//*********************************************************************************
    elseif($page == 'add'){ // Add page 
    ?>

            <h1 class="text-center">Add New Category</h1>
            <div class="container">
                <form action="?page=insert" class="" method="POST">
                   
                    <!-- Name -->
                    <div class="form-group row">
                        <label class="col-sm-2 control-label edit-label">Name</label>
                        <div class="col-sm-4">
                            <input type="text" name="name" class="form-control">
                        </div>
                    </div>
    
                    <!-- Description -->
                    <div class="form-group row">
                        <label class="col-sm-2 control-label edit-label">Description</label>
                        <div class="col-sm-4">
                            <input type="text" name="description" class="form-control">
                        </div>
                    </div>

                    <!-- Parent  -->
                    <div class="form-group row">
                        <label class="col-sm-2 control-label edit-label">Parent</label>
                        <div class="col-sm-4">
                            <select name="parent" class='form-control'>
                                <option>...</option>
                                <?php
                                
                                    $cats = getAllFrom('*', 'categories', 'WHERE Parent = 0 ', 'ID', 'ASC');

                                    foreach($cats as $cat){

                                        echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- Ordering -->
                    <div class="form-group row">
                        <label class="col-sm-2 control-label edit-label">Ordering</label>
                        <div class="col-sm-4">
                            <input type="text" name="order" class="form-control">
                        </div>
                    </div>

    
                    <!-- Visibility  -->
                    <div class="visible form-group row">
                        <label class="col-sm-2 control-label edit-label">Visible</label>
                        <div class="col-sm-4">
                            <input type="radio" id="visible-yes" name="visible" value='0' checked>
                            <label for="visible-yes">Yes</label>

                            <input type="radio" id="visible-no" name="visible" value='1'>
                            <label for="visible-no">No</label>
                        </div>
                    </div>
    
                    <!-- Allow Comments -->
                    <div class="form-group row">
                        <label class="col-sm-2 control-label edit-label">Allow Comments</label>
                        <div class="col-sm-4">
                            <input type="radio" id="comment-yes" name="comments" value='0' checked>
                            <label for="comment-yes">Yes</label>

                            <input type="radio" id="comment-no" name="comments" value='1'>
                            <label for="comment-no">No</label>
                        </div>
                    </div>
                    
                    <!-- Allow ads -->
                    <div class="form-group row">
                        <label class="col-sm-2 control-label edit-label">Allow Ads</label>
                        <div class="col-sm-4">
                            <input type="radio" id="ads-yes" name="ads" value='0' checked>
                            <label for="ads-yes">Yes</label>

                            <input type="radio" id="ads-no" name="ads" value='1'>
                            <label for="ads-no">No</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Add Category" class="btn-add-cat btn btn-outline-dark" >
                        </div>
                    </div>
    
                </form>
            </div>


    <?php
    }
    
    
//*********************************************************************************
    elseif($page == 'insert'){ // Insert page

        echo "<div class='container'>";
        echo "<h1 class='text-center'>Insert</h1>";

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            
            $name           = $_POST['name'];
            $description    = $_POST['description'];
            $order          = $_POST['order'];
            $visible        = $_POST['visible'];
            $comments       = $_POST['comments'];
            $ads            = $_POST['ads'];
            $parent         = $_POST['parent'];


            // Check if category name exist in the database
            $check = checkItem("Name", "categories", $name);
            if($check == 1){
               
                $msg = "<div class='alert alert-danger'> This Category already exist </div>";
                redirectHome($msg, "back");
            }
            
            elseif(empty($name)){

                $msg = "<div class='alert alert-danger'> Name Can't be empty </div>";
                redirectHome($msg, "back");

            }else{
            
                
                $stmt = $con->prepare("INSERT INTO 
                                            categories(Name, Description, Parent, Ordering, Visible, Allow_Comments, Allow_Ads, Date)
                                            VALUES(:vname, :vdescription, :parent, :vorder, :vvisible, :vcomment, :vads, now() ) ");
                
                
                $stmt->execute(array(

                    'vname'         => $name,
                    'vdescription'  => $description,
                    'vorder'        => $order,
                    'vvisible'      => $visible,
                    'vcomment'      => $comments,
                    'vads'          => $ads,
                    'parent'        => $parent
                    
                ));
                

                $msg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Inserted </div>";
                redirectHome($msg, 'category.php');

            }


        }
        
        else{

            $msg = "<div class='alert alert-danger text-center'> [-] You can't access this page directly. </div>";
            redirectHome($msg, "category.php");
        }

        echo "</div>";

    }
    

    
//*********************************************************************************
    elseif($page == 'edit'){ // Edit page

           // check if the categoryid exist and numeric 
           $catid = isset($_GET['catid']) && is_numeric($_GET['catid'])? intval($_GET['catid']) : 0;
                
           // getting the category details that registered with the id
           $stmt = $con->prepare("SELECT * FROM categories WHERE ID = ? ");

           $stmt->execute(array($catid));
           $row = $stmt->fetch();
           $count = $stmt->rowCount();

           // check if the id exists in the db  &  show the form
           if($count > 0){ ?>

                <h1 class="text-center">Edit Category</h1>
                    <div class="container">
                        <form action="?page=update" method="POST">
                            
                            <input type="hidden" name="catid" value="<?php echo $catid ?>" >
                            <!-- name -->
                            <div class="form-group row">
                                <label class="col-sm-2 control-label edit-label">Category Name</label>
                                <div class="col-sm-4">
                                    <input required type="text" name="name" value="<?php echo $row['Name'];?>" class="form-control">
                                </div>
                            </div>
            
                            <!-- description -->
                            <div class="form-group row">
                                <label class="col-sm-2 control-label edit-label">Description</label>
                                <div class="col-sm-4">
                                    <input required type="text" name="description" value="<?php echo $row['Description'];?>" class="form-control">
                                </div>
                            </div>

                             <!-- Parent  -->
                            <?php if($row['Parent'] != 0){ ?>
                                <div class="form-group row">
                                    <label class="col-sm-2 control-label edit-label">Parent</label>
                                    <div class="col-sm-4">
                                        <select name="parent" class='form-control'>
                                            <?php
                                            
                                                $parent_cats = getAllFrom('*', 'categories', 'WHERE Parent = 0 ', 'ID', 'ASC');

                                                foreach($parent_cats as $cat){

                                                    echo "<option value='" . $cat['ID'] . "'";
                                                    if($row['Parent'] == $cat['ID']){
                                                        echo "selected";
                                                    }
                                                    
                                                    echo ">" . $cat['Name'] . "</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            <?php } ?>            
                            <!-- Ordering  -->
                            <div class="form-group row">
                                <label class="col-sm-2 control-label edit-label">Ordering</label>
                                <div class="col-sm-4">
                                    <input required type="number" name="ordering" value="<?php echo $row['Ordering'];?>" class="form-control">
                                </div>
                            </div>
                
                            <!-- Visibility  -->
                            <div class="visible form-group row">
                                <label class="col-sm-2 control-label edit-label">Visible</label>
                                <div class="col-sm-4">
                                    <input type="radio" id="visible-yes" name="visible" value='0' <?php if($row['Visible'] == 0){echo 'checked';} ?>>
                                    <label for="visible-yes">Yes</label>

                                    <input type="radio" id="visible-no" name="visible" value='1' <?php if($row['Visible'] == 1){echo 'checked';} ?>>
                                    <label for="visible-no">No</label>
                                </div>
                            </div>
            
                            <!-- Allow Comments -->
                            <div class="form-group row">
                                <label class="col-sm-2 control-label edit-label">Allow Comments</label>
                                <div class="col-sm-4">
                                    <input type="radio" id="comment-yes" name="comments" value='0' <?php if($row['Allow_Comments'] == 0){echo 'checked';} ?>>
                                    <label for="comment-yes">Yes</label>

                                    <input type="radio" id="comment-no" name="comments" value='1' <?php if($row['Allow_Comments'] == 1){echo 'checked';} ?>>
                                    <label for="comment-no">No</label>
                                </div>
                            </div>
                            
                            <!-- Allow ads -->
                            <div class="form-group row">
                                <label class="col-sm-2 control-label edit-label">Allow Ads</label>
                                <div class="col-sm-4">
                                    <input type="radio" id="ads-yes" name="ads" value='0' <?php if($row['Allow_Ads'] == 0){echo 'checked';} ?>>
                                    <label for="ads-yes">Yes</label>

                                    <input type="radio" id="ads-no" name="ads" value='1' <?php if($row['Allow_Ads'] == 1){echo 'checked';} ?>>
                                    <label for="ads-no">No</label>
                                </div>
                            </div>                
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <input type="submit" value="Update Category" class="btn-update-cat btn btn-dark">
                                </div>
                            </div>
                        
                        </form>
                    </div>


        <?php }

           else{
            $msg = "<div class='alert alert-danger'>This ID doesn't exist</div>";
            redirectHome($msg, "category.php");
           }

    }
    
    
//*********************************************************************************
    elseif($page == 'update'){ // Update page

        $id = $_POST['catid'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $order = $_POST['ordering'];
        $visible = $_POST['visible'];
        $comments = $_POST['comments'];
        $ads = $_POST['ads'];
        $parent = $_POST['parent'];

        if(empty($parent)){
            $parent = 0;
        }

        $check = checkItem("name", "categories", $name);



        $stmt2 = $con->prepare("SELECT * from categories WHERE Name = ? AND ID != ?");
        $stmt2->execute(array($name, $id));
        $count = $stmt2->rowCount();

        if($count > 0){
            echo "<div class='alert alert-danger'>Category already exists</div>";
        }else{

            $stmt = $con->prepare("UPDATE
                                        categories 
                                    SET 
                                        Name = ?, Description = ?, Parent = ?, Ordering = ?, Visible = ?, Allow_Comments = ?, Allow_Ads =? 
                                        WHERE ID= ?");
            $stmt->execute(array($name, $description, $parent, $order, $visible, $comments, $ads, $id));
            $count = $stmt->rowCount();

            $msg = "<div class='alert alert-success'>Categories Edited successfully</div>";
            redirectHome($msg, 'category.php', 1);
        }

        




    }
    
    
//*********************************************************************************
    elseif($page == 'delete'){ // Delete page

        $catid = isset($_GET['catid']) && is_numeric($_GET['catid'])? intval($_GET['catid']) : 0;
        
        $check = checkItem("ID", "categories", $catid);

        if($check > 0){
            $stmt = $con->prepare("DELETE FROM categories WHERE ID= ?");
            $stmt->execute(array($catid));

            $msg = "<div class='alert alert-success'>Category $catid has been deleted successfully.</div>";
            redirectHome($msg, "category.php", 1);

        }else{

            $msg = "<div class='alert alert-danger'>This user doesn't exist </div>";
            redirectHome($msg, "category.php", 1);
        }

    }






    include $tpl . "footer.php";
}else{

    header("Location: index.php");
    exit();

}