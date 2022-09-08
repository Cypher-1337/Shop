<?php
    session_start();

    $pageTitle = 'Edit Item';

    include('init.php'); 
    
    if(isset($_SESSION['shop_user'])){

        $username = $_SESSION['shop_user'];
        $uid = $_SESSION['uid'];


        $action = isset($_GET['action']) ? $_GET['action'] : $action = "edit";


        $itemId = isset($_GET['item_id']) && is_numeric($_GET['item_id'])? intval($_GET['item_id']) : 0;


        $checkItem = checkItem('Item_ID', 'items', $itemId);
        

        // get item information
        $items = $con->prepare("SELECT * FROM items WHERE Item_ID = ?");
        $items->execute(array($itemId));

        $item = $items->fetch();

        // check if item belongs to user 
        if($action == 'edit'){ // Edit Page
        
            if(is_array($item) && ($item['Member_ID'] == $uid)){

                ?>
                <div class="container">
                    <div class="card bg-dark edit-profile-card">
    
                        <div class="card card-header">Edit Profile</div>
                        <div class="card card-body">
                            <h1 class='text-center'><?php echo $item['Name']; ?></h1>
                            <div class="row">
                                
                                <div class='col-md-7'> 
                                    <form action="?action=update" method="POST" enctype="multipart/form-data">
                                        
                                        <!-- Item_ID -->
                                        <input type="hidden" name="itemid" value="<?php  echo $itemId ?>">

                                        <!-- Name -->
                                        <div class="form-group row">
                                            <label class="col-sm-3 control-label edit-label">Name</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="name" class="form-control" value="<?php echo $item['Name']?>">
                                            </div>
                                        </div>

                                        <!-- Description -->
                                        <div class="form-group row">
                                            <label class="col-sm-3 control-label edit-label">Description</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="description" class="form-control" value="<?php echo $item['Description']?>">
                                            </div>
                                        </div>

                                        
                                        <!-- Price -->
                                        <div class="form-group row price-input">
                                            <label class="col-sm-3 control-label edit-label">Price</label>
                                            <div class="col-sm-8">
                                                <input type="number" name="price" class="form-control" value="<?php echo $item['Price']?>">
                                            </div>
                                        </div>


                                        <!-- Country  -->
                                        <div class="visible form-group row">
                                            <label class="col-sm-3 control-label edit-label">Country</label>
                                            <div class="col-sm-8">
                                                <!-- list of all countries -->
                                                <input type="text" name="country" class="form-control" value="<?php echo $item['Country']?>">
                                            </div>
                                        </div>

                                        <!-- Status -->
                                        <div class="form-group row">
                                            <label class="col-sm-3 control-label edit-label">Status</label>
                                            <div class="col-sm-8">
                                                <select name="status" class='form-control'>
                                                    <option value="1" <?php if($item['Status'] == 1) {echo "selected";}?>>New</option>
                                                    <option value="2" <?php if($item['Status'] == 2) {echo "selected";}?>>Like New</option>
                                                    <option value="3" <?php if($item['Status'] == 3) {echo "selected";}?>>Used</option>
                                                    <option value="4" <?php if($item['Status'] == 4) {echo "selected";}?>>Old</option>
                                                </select>
                                            </div>
                                        </div>

                                        
                                        <!-- Categories  -->
                                        <div class="form-group row">
                                            <label class="col-sm-3 control-label edit-label">Category</label>
                                            <div class="col-sm-8">
                                                <select name="category" class='form-control'>
                                                    <option>...</option>
                                                    <?php
                                                    
                                                        $cats = getAllFrom('*', 'categories', 'WHERE Parent = 0', 'ID');

                                                        foreach($cats as $cat){

                                                            echo "<option value='" . $cat['ID'] . "'";
                                                            if($item['Cat_ID'] == $cat['ID']){echo "selected";}
                                                            echo ">" . $cat['Name'] . "</option>";

                                                            $sub_cats = getAllFrom('*', 'categories', 'WHERE Parent = ' . $cat['ID'], 'ID');
                                                                foreach($sub_cats as $sub){

                                                                    echo "<option value='" . $sub['ID'] . "'";
                                                                    if($item['Cat_ID'] == $sub['ID']){echo "selected";}
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
                                            <label class="col-sm-3 control-label edit-label">Image</label>
                                            <div class="col-sm-8">
                                                    <input type="file" name="image" class="form-control">
                                                    <input type="hidden" name="old_image" class="form-control" value="<?php echo $item['Image'] ?>" >

                                            </div>
                                        </div>

                                        <!-- Tags  -->
                                        <div class="form-group row">
                                            <label class="col-sm-3 control-label edit-label">Tags</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="tags" class="form-control" value="<?php echo $item['Tags']?>">
                                            </div>
                                        </div>
                                        
                                        <!-- Submit Button  -->
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <input type="submit" value="Update Item" class="btn-add-cat btn btn-dark" >
                                            </div>
                                        </div>

                                    </form>
                                </div>

                                <div class='col-md-4 mx-auto'> 
                                    <div class="user-avatar-box">
                                    <img class='img-thumbnail user-img' src='../admin/uploads/items/<?php echo $item['Image']; ?>' alt='item' >
                                    
                                    <div class='caption'>
                                        <h3> Name: <?php echo $item['Name'] ?>  </h3>
                                        <h3>Description: <?php echo $item['Description'] ?>  </h3>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php 
        }                    // if($item['Member_ID'] == $uid)

            elseif($checkItem > 0 && $item['Member_ID'] != $uid){
                echo "<div class='container'>
                        <div class='alert alert-danger text-center'>
                            <h3> You are not Uthorized to Edit items not belong to you </h3>
                        </div>
                    </div>";
            }

            else{
                echo "<div class='container'>
                <div class='alert alert-danger text-center'>
                    <h3> Please, Enter a valid item_id </h3>
                </div>
            </div>";
            }
    
        }                       // if($action == 'edit')


    //-------------------------------------------------------------------------------

        if($action == 'update'){ // Update Page

            echo "<div class='container'>";
            echo "<h1 class='text-center'>Update</h1>";
        
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
        
                $itemId = $_POST['itemid'];
                $name = $_POST['name'];
                $desc = $_POST['description'];
                $price = $_POST['price'];
                $country = $_POST['country'];
                $status = $_POST['status'];
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

                    $tmp_img = explode('.', $imageName);
                    $img_ext = end($tmp_img);

                    $img_name = rand(1, 9999999) . '_' . $imageName ;
        
                    $formErrors = itemValidate($name, $desc, $price, $country, $status, $category, $img_ext, $allowed_ext, $imageSize);
        
                }
                else{
                    $formErrors = itemValidate($name, $desc, $price, $country, $status, $category);
                }
        
        
                if(empty($formErrors)){
                    
        
                    if(!empty($_FILES['image']['name'])){
                        $destination = '../admin/uploads/items/' . $img_name;
                            
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
                                            Name = ?, Description = ?, Price = ?, Country = ?, Status = ?, Image = ?, Cat_ID = ?, Tags = ? 
                                        WHERE Item_ID= ?");
        
                    $stmt->execute(array($name, $desc, $price, $country, $status, $img_name, $category, $tags, $itemId));
        
                    $msg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Updated </div>";
                    redirectHome($msg, '/shop/profile.php', 1);
        
        
                }                       // if(empty($formErrors))
                else{
                    foreach($formErrors as $error){
                        echo "<div class='alert alert-danger'>$error</div>";
                    }
                }
        
                
            
                }                       // if($_SERVER['REQUEST_METHOD'] == 'POST')

                else{
                    
                    $msg = "<div class='alert alert-danger'>You can't Access this page directly</div>";
                    redirectHome($msg, 'items.php', 1);
                }
            
            }                   //  if($page == 'update')

// ---------------------------- End Update page -----------------------------        
        

// ---------------------------- Start Delete page -----------------------------        

        if($action == 'delete'){ // Delete Page
                    
            if(is_array($item) && ($item['Member_ID'] == $uid)){

                
                $check = checkItem('Item_ID', 'items', $itemId);
                if($check > 0){
            
                    $stmt = $con->prepare("DELETE FROM items WHERE Item_ID = ?");
                    $stmt->execute(array($itemId));
            
                    $msg = "<div class='alert alert-success'> Item Deleted Successfully </div>";
                    redirectHome($msg, '/shop/profile.php', 1);
                
                }else{
                    $msg = "<div class='alert alert-danger'> Incorrect item_id. </div>";
                    redirectHome($msg, '/shop/profile.php', 1);
                    
                }        

            }

            elseif($checkItem > 0 && $item['Member_ID'] != $uid){
                echo "<div class='container'>
                        <div class='alert alert-danger text-center'>
                            <h3> You are not Uthorized to Delete items not belong to you </h3>
                        </div>
                    </div>";
            }

            else{
                echo "<div class='container'>
                <div class='alert alert-danger text-center'>
                    <h3> Please, Enter a valid item_id </h3>
                </div>
            </div>";
            }

        }

// ---------------------------- End Delete page -----------------------------        

       
}   // if(isset($_SESSION['shop_user']))
else{
    header("Location: /shop/login.php");
}
?>

<?php include $tpl . 'footer.php' ?>