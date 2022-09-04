<?php

function getAllFrom($column, $table, $where, $orderby, $ordering = 'DESC'){

    global $con;

    $all = $con->prepare("SELECT $column FROM $table $where ORDER BY $orderby $ordering");
    $all->execute();
    $get_all = $all->fetchAll();

    return $get_all;
}


function checkRegStatus($username){

    global $con;
    
    $stmt = $con->prepare("SELECT * FROM users WHERE Username = ? AND RegStatus = 0");
    $stmt->execute(array($username));
    $count = $stmt->rowCount();
    
    return $count;
}



// get user information 
function getInfo($user){
    
    global $con;

    $stmt = $con->prepare("SELECT * FROM users WHERE Username = ?");
    $stmt->execute(array($user));
    $info = $stmt->fetch();

    return $info;
}


// Add item validate ipnuts function
function insertItemValidate($name, $price, $country, $status, $category){

    $form_errors = [];

    if(empty($name)){
        $form_errors[] = "Name can't be empty.";
    }

    if(empty($price)){
        $form_errors[] = "Price can't be empty.";
    }

    if(empty($country)){
        $form_errors[] = "Country can't be empty.";
    }
    
    if(empty($status)){
        $form_errors[] = "Status can't be empty.";
    }

    if(empty($category)){
        $form_errors[] = "Category can't be empty.";
    }
    

    return $form_errors;
}


//*******************************************************//
//                 back-end functions                    //
//*******************************************************//


// get page title
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


function insertValidateForm($username, $email, $fullname, $password, $password2){
  
    $check = checkItem("Username", "users", $username);
    $check_email = checkItem("Email", "users", $email);

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

    if($check_email == 1){
        $formError[] = "Email already used.";

    }

    if(empty($email)){
        $formError[] = "Email can't be empty.";
    }

    if(empty($password)){
        $formError[] = "Password can't be empty.";

    }

    if(strlen($password) < 6){
        $formError[] = "Password Must be 6 characters long";
    }

    if(empty($fullname)){
        $formError[] = "Name can't be empty.";

    }

    if(empty($password2)){
        $formError[] = "Please. Confirm your password.";

    }

    if($password != $password2){
        $formError[] = "Confirm Password not match.";
    }



    return $formError;
}



// validate edit form
function editValidateForm($username, $email, $fullname){
    
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


    return $formErrors;
    // end validate form
}




/************************************************************************************************ */


function getLatest($column, $table, $order, $limit = 5){

    global $con;
    $stmt = $con->prepare("SELECT $column FROM $table ORDER BY $order DESC LIMIT $limit");
    $stmt->execute();

    return $stmt->fetchAll();

}