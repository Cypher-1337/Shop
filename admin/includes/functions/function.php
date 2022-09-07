<?php


function getAllFrom($column, $table, $where, $orderby, $ordering = 'DESC'){

    global $con;

    $all = $con->prepare("SELECT $column FROM $table $where ORDER BY $orderby $ordering");
    $all->execute();
    $get_all = $all->fetchAll();

    return $get_all;
}





function getTitle(){

    global $pageTitle;
    
    if(isset($pageTitle)){

        echo $pageTitle;

    }else{

        echo "Default";

    }

}



function redirectHome($msg, $url = null, $time = 3){


    if($url == null){
        $url = "dashboard.php";
    }elseif($url == "back"){
        if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != ''){
            $url = $_SERVER['HTTP_REFERER'];
        }else{
            $url = "dashboard.php";
        }
    }

    echo $msg;
    echo "<div class='text-center alert alert-info'> You will be redirected to <strong>$url</strong> after " . $time . "</div>";

    header("refresh:$time;url=$url");
    exit();

}


// function to check if an item or username exists in the db
// $item = Username  &  $from = users  &  $value = cypher
// if cypher exists in the db it will return 1 if not it will return 0
function checkItem($item, $from, $value){

    global $con;
    $stmt = $con->prepare("SELECT $item FROM $from WHERE $item= ?");
    $stmt->execute(array($value));
    $count = $stmt->rowCount();

    return $count; //return 1 if the user or the item exist in the db
}

function countItems($item, $table){

    global $con;
    $stmt = $con->prepare("SELECT COUNT($item) FROM $table");
    $stmt->execute();

    return $stmt->fetchColumn();
}


function insertValidateForm($username, $email, $password, $fullname, $groupid, $img_ext, $allowed_ext, $img_size){
  
    $check = checkItem("Username", "users", $username);


    $formError = array();

    if(!empty($username) && strlen($username) < 4 ){
        $formError[] = "Username can't be less than 4 character.";
    }

    if(empty($username)){
        $formError[] = "Username can't be empty.";
        
    }

    if($check == 1){
        $formError[] = "Username already exists in the database.";

    }

    if(empty($email)){
        $formError[] = "Email can't be empty.";
    }

    if(empty($password)){
        $formError[] = "Password can't be empty.";

    }

    if(empty($fullname)){
        $formError[] = "Name can't be empty.";

    }

    if(empty($groupid)){
        $formError[] = "Group can't be empty";

    }

    if(($groupid != 'admin') && ($groupid != 'user')){
        $formError[] = "Group Accepts only 2 values admin || user";

    }
    
    if(!in_array($img_ext, $allowed_ext)){
        $formError[] = "Only valid image types allowed<b> png, jpeg, jpg </b>";
    }

    if($img_size > 8 * 1024 * 1024){
        $formError[] = "Image must be Less than <b>8MB</b>";
    }
   
    return $formError;
}



// validate edit form
function editValidateForm($username, $email, $fullname, $groupid, $img_ext = NULL, $allowed_ext = NULL, $img_size = NULL){
    
    $check = checkItem("Username", "users", $username);
    

    $formErrors = array();

    if(!empty($username) && strlen($username) < 4){
        $formErrors[] = "<h5> Username can't be less than 4 char </h5>";
    }

    if(empty($username)){
        $formErrors[] = "<h5> Username can't be empty </h5>";
    }
    
    if($check == 1 && $username != $_POST['username']){
        $formErrors[] = "Username already exists in the database.";

    }

    if(empty($email)){
        $formErrors[] = "<h5> Email can't be empty </h5>";
    }

    if(empty($fullname)){
        $formErrors[] = "<h5> Fullname can't be empty </h5>";
    }

    if(empty($groupid)){
        $formErrors[] = "Group can't be empty";

    }

    if(($groupid != 'admin') && ($groupid != 'user')){
        $formErrors[] = "Group Accepts only 2 values admin || user";

    }

    if(!empty($img_ext) && !empty($allowed_ext)){
        if(!in_array($img_ext, $allowed_ext)){
            $formErrors[] = "Only valid image types allowed<b> png, jpeg, jpg </b>";
        }
    }
    
    if($img_size > 8 * 1024 * 1024){
        $formErrors[] = "Image must be Less than <b>8MB</b>";
    }

    return $formErrors;
    // end validate form
}


// Validate Items
function itemValidate($name, $desc, $price, $country, $status, $member, $category, $img_ext = NULL, $allowed_ext = NULL, $img_size = NULL){

    $formErrors = [];


    if(empty($name)){
        $formErrors[] = "<h5> Item name can't be empty </h5>";
    }
    if(empty($desc)){
        $formErrors[] = "<h5> Description can't be empty </h5>";
    }
    if(empty($price)){
        $formErrors[] = "<h5> Price can't be empty </h5>";
    }
    if(empty($country)){
        $formErrors[] = "<h5> Country can't be empty </h5>";
    }
    if(empty($status)){
        $formErrors[] = "<h5> Status can't be empty </h5>";
    } 
    if(empty($member)){
        $formErrors[] = "<h5> Member can't be empty </h5>";
    }
    if(empty($category)){
        $formErrors[] = "<h5> Category can't be empty </h5>";
    }

    // image validate
    if(!empty($img_ext) && !empty($allowed_ext)){
        if(!in_array($img_ext, $allowed_ext)){
            $formErrors[] = "Only valid image types allowed<b> png, jpeg, jpg </b>";
        }
    }
    
    if($img_size > 8 * 1024 * 1024){
        $formErrors[] = "Image must be Less than <b>8MB</b>";
    }

    return $formErrors;
}



/************************************************************************************************ */


function getLatest($column, $table, $order, $limit = 5){

    global $con;
    $stmt = $con->prepare("SELECT $column FROM $table ORDER BY $order DESC LIMIT $limit");
    $stmt->execute();

    return $stmt->fetchAll();

}