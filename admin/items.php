<?php

session_start();
if(isset($_SESSION['user'])){

    $pageTitle='Items';
    include('init.php');

    $page = isset($_GET['page']) ? $_GET['page'] : $page = "manage";

   








/**************************************************************************************
***************************************************************************************
**************************************************************************************/

    if($page == 'manage'){ // Manage Page
    
        $query= '';

        if(isset($_GET['status']) && $_GET['status'] == 'approve'){
            $query = "WHERE Approve = 0";
        }



        $stmt = $con->prepare("SELECT 
                                    items.*,
                                    categories.Name AS Cat_Name,
                                    users.Username
                                FROM
                                    items
                                INNER JOIN 
                                    categories
                                ON 
                                    categories.ID = items.Cat_ID
                                INNER JOIN
                                    users
                                ON
                                    users.UserID = items.Member_ID $query");
        $stmt->execute();
        $items = $stmt->fetchAll();

        if(! empty($items) ){

            ?>

        
    
        <div class="container">
                
        <h1 class="text-center">Manage Items</h1>
            <div class="container">
                <div class="table-responsive">
                    <table class="main-table text-center table table-bordered">
                        <tr>
                            <td>#ID</td>
                            <td>Name</td>
                            <td>Description</td>
                            <td>Price</td>
                            <td>User</td>
                            <td>Category</td>
                            <td>Control</td>
                        </tr>
                        <?php

                            foreach($items as $item){

                                echo "<tr>";
                                    echo "<td>" . $item["Item_ID"]   . "</td>";
                                    echo "<td>" . $item["Name"] . "</td>";
                                    echo "<td>" . $item["Description"]    . "</td>";
                                    echo "<td>" . $item["Price"] . "$</td>";
                                    echo "<td>" . $item["Username"] . "</td>";
                                    echo "<td>" . $item["Cat_Name"] . "</td>";
                                    echo "<td>" .    
                                    '<a href="items.php?page=edit&item_id=' . $item["Item_ID"] . '"class="btn btn-outline-success"><i class="fa fa-edit"></i> Edit</a>
                                    <a href="items.php?page=delete&item_id=' .$item["Item_ID"] . '" class="btn btn-outline-danger confirm"><i class="fa fa-close"></i> Delete</a>';

                                    if($item['Approve'] == 0){

                                        echo '<a href="items.php?page=approve&item_id=' . $item["Item_ID"] . '" class="btn btn-outline-info"><i class="fa fa-check"></i> Approve</a>' . '</td>';

                                    }
               

                                    echo "</td>";
                            }

                        ?>
                    </table>
                </div>

                <a href="items.php?page=add" class='btn btn-primary'><i class='fa fa-plus'></i> Add Item</a>
            </div>

        

        

        
<?php }else{
                echo "<div class='container empty-box'>";
                    echo "<div class='empty-msg'>No <i class='fa fa-tags'></i> items at the moment</div>";
                    echo "<a href='items.php?page=add' class='btn btn-primary'><i class='fa fa-plus'></i> Add Item</a>";
                echo "</div>";

}

}










/**************************************************************************************
***************************************************************************************
**************************************************************************************/
    
    elseif($page == 'add'){ // Add Page ?>
        
        
        <h1 class="text-center">Add New Item</h1>
            <div class="container">
                <form action="?page=insert" class="" method="POST" enctype="multipart/form-data">
                   
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
    
                    
                    <!-- Price -->
                    <div class="form-group row">
                        <label class="col-sm-2 control-label edit-label">Price</label>
                        <div class="col-sm-4">
                            <input type="number" name="price" class="form-control">
                        </div>
                    </div>

    
                    <!-- Country  -->
                    <div class="visible form-group row">
                        <label class="col-sm-2 control-label edit-label">Country</label>
                        <div class="col-sm-4">
                            <!-- list of all countries -->
                            <?php include $tpl . 'countries.php' ?>
                        </div>
                    </div>
    
                    <!-- Status -->
                    <div class="form-group row">
                        <label class="col-sm-2 control-label edit-label">Status</label>
                        <div class="col-sm-4">
                            <select name="status" class='form-control'>
                                <option value="1">New</option>
                                <option value="2">Like New</option>
                                <option value="3">Used</option>
                                <option value="4">Old</option>
                            </select>
                        </div>
                    </div>


                    <!-- Members -->
                    <div class="form-group row">
                        <label class="col-sm-2 control-label edit-label">Publisher</label>
                        <div class="col-sm-4">
                            <select name="member" class='form-control'>
                                <option>...</option>
                                <?php
                                    
                                    
                                    $users = getAllFrom('*', 'users', '', 'UserID');

                                    foreach($users as $user){

                                        echo "<option value='" . $user['UserID'] . "'>" . $user['Username'] . "</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Categories  -->
                    <div class="form-group row">
                        <label class="col-sm-2 control-label edit-label">Category</label>
                        <div class="col-sm-4">
                            <select name="category" class='form-control'>
                                <?php
                            

                                    $cats = getAllFrom('*', 'categories', 'WHERE Parent = 0', 'ID', 'ASC');

                                    foreach($cats as $cat){

                                        echo "<option value='" . $cat['ID'] . "'><b>" . $cat['Name'] . "</b></option>";
                                        $sub_cats = getAllFrom('*', 'categories', 'WHERE Parent = ' . $cat['ID'], 'ID');
                                        foreach($sub_cats as $sub){
                                            echo "<option value='" . $sub['ID'] . "'>-- " . $sub['Name'] . " --</option>";

                                        }
                                        echo "<option disabled><hr></option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- Item image  -->
                    <div class="form-group row">
                        <label class="col-sm-2 control-label edit-label">Image</label>
                        <div class="col-sm-4">
                                <input type="file" name="image" class="form-control">
                        </div>
                    </div>

                    <!-- Tags -->
                    <div class="form-group row">
                        <label class="col-sm-2 control-label edit-label">Tags</label>
                        <div class="col-sm-4">
                            <input type="text" name="tags" class="form-control" placeholder="Item Tags seperated by ,">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Add Item" class="btn btn-add-user btn-outline-dark" >
                        </div>
                    </div>
    
                </form>
            </div>

    <?php }





//*********************************************************************************
elseif($page == 'insert'){ // Insert page

    echo "<div class='container'>";
    echo "<h1 class='text-center'>Insert</h1>";

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $desc = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
        $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
        $country = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
        $status = $_POST['status'];
        $member = $_POST['member'];
        $category = $_POST['category'];
        $tags = $_POST['tags'];


        // uploading image 
        if(!empty($_FILES['image']['name'])){

            $imageName = $_FILES['image']['name'];
            $imageSize = $_FILES['image']['size'];
            $imageTemp = $_FILES['image']['tmp_name'];
            $imageError = $_FILES['image']['error'];

            $allowed_ext = array('png', 'jpg', 'jpeg');

            $img_ext = strtolower(end(explode('.', $imageName)));
            
            $img_name = rand(1, 9999999) . '_' . $imageName ;

            $formErrors = itemValidate($name, $desc, $price, $country, $status, $member, $category, $img_ext, $allowed_ext, $avatarSize);

        }

        else{
            $formErrors = itemValidate($name, $desc, $price, $country, $status, $member, $category);

        }
        
 
        
    if(empty($formErrors)){


        if(!empty($_FILES['image']['name'])){
            $destination = 'uploads/items/' . $img_name;
                
            if (!move_uploaded_file($imageTemp, $destination)) {
                echo "Sorry, there was an error uploading your file.";
            }
        }
        else{
            $img_name = 'default_item.png';
        }

            // Add to db 
            $stmt = $con->prepare("INSERT INTO
                                 items(Name, Description, Price, Country, Status, Approve, Member_ID, Cat_ID, Tags, Image, Add_Date)
                                 VALUES(:name, :description, :price, :country, :status, 1 , :member, :category, :tags, :image, now())");
            

            $stmt->execute(array(

                'name'  =>  $name,
                'description'  =>  $desc,
                'price'  =>  $price,
                'country'  =>  $country,
                'status'  =>  $status,
                'member' => $member,
                'category' => $category,
                'tags' => $tags,
                'image' => $img_name

            ));

            $msg = "<div class='alert alert-dark'>" . $stmt->rowCount() . " record inserted </div>";
            redirectHome($msg, "items.php", 1);
            
        }else{
            foreach($formErrors as $error){
                echo "<div class='alert alert-danger'>$error</div>";
            }
        }

        echo "</div>";
    }

}







/**************************************************************************************
***************************************************************************************
**************************************************************************************/

elseif($page == 'edit'){ // Edit Page

    $itemId = isset($_GET['item_id']) && is_numeric($_GET['item_id'])? intval($_GET['item_id']) : 0;
    $check = checkItem('Item_ID', 'items', $itemId);

    if($check > 0){ 
        
        $stmt = $con->prepare("SELECT * FROM items WHERE Item_ID = $itemId");
        $stmt->execute();
        $row = $stmt->fetch();
        
        ?>

        <h1 class="text-center">Edit Item</h1>
        <div class="container">
            <form action="?page=update" method="POST" enctype="multipart/form-data">
                
                <!-- Item_ID -->
                <input type="hidden" name="itemid" value="<?php  echo $itemId ?>">

                <!-- Name -->
                <div class="form-group row">
                    <label class="col-sm-2 control-label edit-label">Name</label>
                    <div class="col-sm-4">
                        <input type="text" name="name" class="form-control" value="<?php echo $row['Name']?>">
                    </div>
                </div>

                <!-- Description -->
                <div class="form-group row">
                    <label class="col-sm-2 control-label edit-label">Description</label>
                    <div class="col-sm-4">
                        <input type="text" name="description" class="form-control" value="<?php echo $row['Description']?>">
                    </div>
                </div>

                
                <!-- Price -->
                <div class="form-group row">
                    <label class="col-sm-2 control-label edit-label">Price</label>
                    <div class="col-sm-4">
                        <input type="number" name="price" class="form-control" value="<?php echo $row['Price']?>">
                    </div>
                </div>


                <!-- Country  -->
                <div class="visible form-group row">
                    <label class="col-sm-2 control-label edit-label">Country</label>
                    <div class="col-sm-4">
                        <!-- list of all countries -->
                        <input type="text" name="country" class="form-control" value="<?php echo $row['Country']?>">
                    </div>
                </div>

                <!-- Status -->
                <div class="form-group row">
                    <label class="col-sm-2 control-label edit-label">Status</label>
                    <div class="col-sm-4">
                        <select name="status" class='form-control'>
                            <option value="1" <?php if($row['Status'] == 1) {echo "selected";}?>>New</option>
                            <option value="2" <?php if($row['Status'] == 2) {echo "selected";}?>>Like New</option>
                            <option value="3" <?php if($row['Status'] == 3) {echo "selected";}?>>Used</option>
                            <option value="4" <?php if($row['Status'] == 4) {echo "selected";}?>>Old</option>
                        </select>
                    </div>
                </div>


                <!-- Members -->
                <div class="form-group row">
                    <label class="col-sm-2 control-label edit-label">Publisher</label>
                    <div class="col-sm-4">
                        <select name="member" class='form-control'>
                            <option>...</option>
                            <?php
                                $stmt = $con->prepare("SELECT * FROM users");
                                $stmt->execute();
                                $users = $stmt->fetchAll();

                                foreach($users as $user){

                                    echo "<option value='" . $user['UserID'] . "'";
                                    if($row['Member_ID'] == $user['UserID']){ echo "selected";};
                                    echo  ">" . $user['Username'] . "</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
                
                <!-- Categories  -->
                <div class="form-group row">
                    <label class="col-sm-2 control-label edit-label">Category</label>
                    <div class="col-sm-4">
                        <select name="category" class='form-control'>
                            <option>...</option>
                            <?php
                            
                                $cats = getAllFrom('*', 'categories', 'WHERE Parent = 0', 'ID');

                                foreach($cats as $cat){

                                    echo "<option value='" . $cat['ID'] . "'";
                                    if($row['Cat_ID'] == $cat['ID']){echo "selected";}
                                    echo ">" . $cat['Name'] . "</option>";

                                    $sub_cats = getAllFrom('*', 'categories', 'WHERE Parent = ' . $cat['ID'], 'ID');
                                        foreach($sub_cats as $sub){

                                            echo "<option value='" . $sub['ID'] . "'";
                                            if($row['Cat_ID'] == $sub['ID']){echo "selected";}
                                            echo ">-- " . $sub['Name'] . " --</option>";
                                        }
                                        echo "<option disabled><hr></option>";
                         
                                }
                            ?>
                        </select>
                    </div>
                </div>

                <!-- Item image  -->
                <div class="form-group row">
                    <label class="col-sm-2 control-label edit-label">Image</label>
                    <div class="col-sm-4">
                            <input type="file" name="image" class="form-control">
                            <input type="hidden" name="old_image" class="form-control" value="<?php echo $row['Image'] ?>" >

                    </div>
                </div>

                <!-- Tags  -->
                 <div class="form-group row">
                    <label class="col-sm-2 control-label edit-label">Tags</label>
                    <div class="col-sm-4">
                        <input type="text" name="tags" class="form-control" value="<?php echo $row['Tags']?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Update Item" class="btn-add-cat btn btn-dark" >
                    </div>
                </div>

            </form>
        </div>

        <!-- ______________________ -->
<?php


            $stmt = $con->prepare(" SELECT 
                                     comments.*,users.Username AS User
                                    FROM 
                                     comments
                                    INNER JOIN
                                     users
                                    ON 
                                     users.UserID = comments.User_ID
                                    WHERE Item_ID = $itemId");
            $stmt->execute();
            $rows = $stmt->fetchAll();
            
            if(!empty($rows)){
            ?>

                <h1 class="text-center">Comments</h1>
                <div class="container">
                    <div class="table-responsive">
                        <table class="main-table text-center table table-bordered">
                            <tr>
                                <td>Comment</td>
                                <td>User</td>
                                <td>Date</td>
                                <td>Control</td>
                            </tr>
                            <?php

                                foreach($rows as $row){

                        
                                    echo "<tr>";
                                        echo "<td>" . $row["Comment"] . "</td>";
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
    }else{
        $msg = "<div class='alert alert-danger'>Item $itemId doesn't exist.</div>";
        redirectHome($msg, 'items.php', 1);
    }


}



//**************************************************************************************
elseif($page == 'update'){ //Update Page

    echo "<div class='container'>";
    echo "<h1 class='text-center'>Update</h1>";

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $itemId = $_POST['itemid'];
        $name = $_POST['name'];
        $desc = $_POST['description'];
        $price = $_POST['price'];
        $country = $_POST['country'];
        $status = $_POST['status'];
        $member = $_POST['member'];
        $category = $_POST['category'];
        $tags = $_POST['tags'];


        $old_image = $_POST['old_image'];

        // uploading image 
        if(!empty($_FILES['image']['name'])){

            $imageName = $_FILES['image']['name'];
            $imageSize = $_FILES['image']['size'];
            $imageTemp = $_FILES['image']['tmp_name'];
            $imageError = $_FILES['image']['error'];

            $allowed_ext = array('png', 'jpg', 'jpeg');

            $img_ext = strtolower(end(explode('.', $imageName)));
            
            $img_name = rand(1, 9999999) . '_' . $imageName ;

            $formErrors = itemValidate($name, $desc, $price, $country, $status, $member, $category, $img_ext, $allowed_ext, $imageSize);

        }
        else{
            $formErrors = itemValidate($name, $desc, $price, $country, $status, $member, $category);
        }


        if(empty($formErrors)){
            

            if(!empty($_FILES['image']['name'])){
                $destination = 'uploads/items/' . $img_name;
                    
                if (!move_uploaded_file($imageTemp, $destination)) {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
            else{
                $img_name = $old_image;
            }


            $stmt = $con->prepare("UPDATE 
                                    items
                                SET 
                                    Name = ?, Description = ?, Price = ?, Country = ?, Status = ?, Image = ?, Member_ID = ?, Cat_ID = ?, Tags = ? 
                                WHERE Item_ID= ?");

            $stmt->execute(array($name, $desc, $price, $country, $status, $img_name, $member, $category, $tags, $itemId));

            $msg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Updated </div>";
            redirectHome($msg, 'items.php', 1);


        }
        else{
            foreach($formErrors as $error){
                echo "<div class='alert alert-danger'>$error</div>";
            }
        }

       
  
    }else{
        
        $msg = "<div class='alert alert-danger'>You can't Access this page directly</div>";
        redirectHome($msg, 'items.php', 1);
    }

}


/**************************************************************************************
***************************************************************************************
**************************************************************************************/

elseif($page == 'approve'){ // Approve Page

    $itemId = isset($_GET['item_id']) && is_numeric($_GET['item_id'])? intval($_GET['item_id']) : 0;
    
    $check = checkItem('Item_ID', 'items', $itemId);

    if($check > 0){

        $stmt = $con->prepare("UPDATE items SET Approve = ? WHERE Item_ID = ?");
        $stmt->execute(array(1, $itemId));

        $msg = "<div class='alert alert-success'>Item $itemId Approved Successfully.</div>";
        redirectHome($msg, 'items.php', 1);

    }else{
        $msg = "<div class='alert alert-danger'>Item $itemId Not found</div>";
        redirectHome($msg, 'items.php', 1);
    }
}




/**************************************************************************************
***************************************************************************************
**************************************************************************************/

elseif($page == 'delete'){ // Delete Page 

    echo '<h1 class="text-center">DELETE ITEM</h1>';
    
    $itemId = isset($_GET['item_id']) && is_numeric($_GET['item_id'])? intval($_GET['item_id']) : 0;

    $check = checkItem('Item_ID', 'items', $itemId);


    if($check > 0){

        $stmt = $con->prepare("DELETE FROM items WHERE Item_ID = ?");
        $stmt->execute(array($itemId));

        $msg = "<div class='alert alert-success'> Item Deleted Successfully </div>";
        redirectHome($msg, 'items.php', 1);
    
    }else{
        $msg = "<div class='alert alert-danger'> Incorrect item_id. </div>";
        redirectHome($msg, 'items.php', 1);
        
    }

    
    


}


    include $tpl . "footer.php";
}else{

    header("Location: index.php");
    exit();

}