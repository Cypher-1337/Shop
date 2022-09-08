<?php
    session_start();

    $pageTitle = 'Add Item';

    
    include('init.php'); 


    $fail_msgs = [];
    $success_msgs = [];


    if(isset($_SESSION['shop_user'])){


    if($_SERVER['REQUEST_METHOD'] == "POST"){

        $name       = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $desc       = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
        $price      = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
        $country    = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
        $status     = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
        $category   = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
        $tags       = filter_var($_POST['tags'], FILTER_SANITIZE_STRING);
        


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
                $destination = 'admin/uploads/items/' . $img_name;
                    
                if (!move_uploaded_file($imageTemp, $destination)) {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
            else{
                $img_name = 'default_item.png';
                
            }


            $stmt = $con->prepare("INSERT INTO
            items(Name, Description, Price, Country, Status, Image, Member_ID, Cat_ID, Tags, Add_Date)
            VALUES(:name, :description, :price, :country, :status, :image, :member, :category, :tags, now())");

            $stmt->execute(array(

                'name'  =>  $name,
                'description'  =>  $desc,
                'price'  =>  $price,
                'country'  =>  $country,
                'status'  =>  $status,
                'image'   =>  $img_name,
                'member' => $_SESSION['uid'],
                'category' => $category,
                'tags' => $tags

            ));

            $success_msgs[] = 'Item Added Successfully.';
            header("Location: /shop");
        }
        else{

            foreach($formErrors as $error){
                echo "<div class='alert alert-danger'>$error</div>";
            }

        }


    }


?>

<!-- profile card -->
<h1 class='text-center'>Add New Item</h1>
<div class="add-item">
    <div class="container">
    
        <div class="card edit-card">
            <div class="card-header bg-success">Add New Item</div>
            <div class="card-body bg-light add-item-card">
                <div class="row">

                    <div class="col-md-8">
                        <form action="#" class="" method="POST" enctype="multipart/form-data">
                        
                            <!-- Name -->
                            <div class="form-group row">
                                <label class="col-sm-3 control-label edit-label">Name</label>
                                <div class="col-md-8">
                                    <input required type="text" name="name" class="form-control live-name" >
                                </div>
                            </div>
            
                            <!-- Description -->
                            <div class="form-group row">
                                <label class="col-sm-3 control-label edit-label">Description</label>
                                <div class="col-md-8">
                                    <input required type="text" name="description" class="form-control live-desc" >
                                </div>
                            </div>
            
                            
                            <!-- Price -->
                            <div class="form-group row price-input">
                                <label class="col-sm-3 control-label edit-label">Price</label>
                                <div class="col-md-8">
                                    <input required type="number" name="price" class="form-control live-price" >
                                </div>
                            </div>

            
                            <!-- Country  -->
                            <div class="form-group row">
                                <label class="col-sm-3 control-label edit-label">Country</label>
                                <div class="col-md-8">

                                    <input required type="text" name="country" class="form-control" >
                                    
                                </div>
                            </div>
            
                            <!-- Status -->
                            <div class="form-group row">
                                <label class="col-sm-3 control-label edit-label">Status</label>
                                <div class="col-md-8">
                                    <select name="status" class='form-control'>
                                        <option value="1">New</option>
                                        <option value="2">Like New</option>
                                        <option value="3">Used</option>
                                        <option value="4">Old</option>
                                    </select>
                                </div>
                            </div>                          

                            <!-- Categories  -->
                            <div class="form-group row">
                                <label class="col-sm-3 control-label edit-label">Category</label>
                                <div class="col-md-8">
                                    <select name="category" class='form-control'>
                                        <option>...</option>
                                        <?php
                                           

                                            $cats = getAllFrom('*', 'categories', '', 'ID', 'ASC');

                                            foreach($cats as $cat){

                                                echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>


                            <!-- Item image  -->
                            <div class="form-group row">
                                <label class="col-sm-3 control-label edit-label">Image</label>
                                <div class="col-md-8">
                                        <input type="file" name="image" class="form-control">
                                </div>
                            </div>


                            <!-- Tags -->
                            <div class="form-group row">
                                <label class="col-sm-3 control-label edit-label">Tags</label>
                                <div class="col-md-8">
                                    <input type="text" name="tags" class="form-control" placeholder="Item Tags seperated by ,">
                                </div>
                            </div>
                                    
                            <!-- Submit Button  -->
                            <div class="form-group">
                                <div class="col-sm-offset-1 col-sm-11">
                                    <input type="submit" value="Add Item" class="btn btn-add-item btn-outline-success" >
                                </div>
                            </div>
            
                        </form>
                    </div>

                    <div class="col-md-4">


                            <div class='thumbnail item-box live-preview'>
                            <span class='price-tag'>0$</span>
                                <img class='img-thumbnail item-img ' src='includes/imgs/avatar4.jpg' alt='item' >
                                
                                <div class='caption'>
                                    <h3>name</h3>
                                    <p>description</p>
                                </div>
            
                            </div>
                            


                    </div>
                   </div>
                </div>
            </div>
    </div>
    <div class="msgs-box">';
                
            <?php
                if(!empty($form_errors)){
                    foreach($form_errors as $error){
                        echo '<div class="errors-msg">';
                            echo $error;
                        echo '</div>';
                    }
                }
                if(!empty($success_msgs)){
                    foreach($success_msgs as $msg){
                        echo '<div class="success-msg">';
                            echo $msg;
                        echo '</div>';
                    }
                }
            ?>
    
    </div>';
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




