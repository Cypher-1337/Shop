<?php
    // you will get this page after you click on specific category
    // it has all approved items that belong to specific category
    // it accepts 2 parameters [cat_id] & [cat_name]  cat_name will be displayed as h1

    session_start();

    $pageTitle = 'Categories';

    include('init.php'); 
    
?>
<div class='container'>


<?php    

    
    echo "<h1 class='text-center'>";
     if(isset($_GET['cat_name'])){echo $_GET['cat_name'];} 
    echo "</h1>";

    $catid = isset($_GET['cat_id']) && is_numeric($_GET['cat_id'])? intval($_GET['cat_id']) : 0;

    // get all items in the main page of categories.php
  

    

    $items = getAllFrom('*', 'items', 'WHERE Cat_ID = ' . $catid  . ' AND Approve = 1 ', 'Item_ID');
    if(!empty($items) && $catid != 0){
        
        echo "<div class='row'>";
            foreach($items as $item){

                echo "<div class='col-sm-6 col-md-3'>";
                    echo "<div class='thumbnail item-box'>";
                        echo "<span class='price-tag'>" . $item['Price'] . " $</span>";
                        echo "<img class='img-thumbnail item-img ' src='admin/uploads/items/";
                        echo $item['Image'];
                        echo "' alt='item' >";
                        
                        echo "<div class='caption'>";
                            echo "<h3><a href='items.php?item_id=" . $item['Item_ID'] . "'>"  . $item['Name'] . "</a></h3>";
                            echo "<p>" . $item['Description'] . "</p>";

                            // if item not approved display msg to tell them 
                            if($item['Approve'] == 0){
                                echo "<p class='not-approved'> this ad not approved yet </p>";
                            }
                        echo "</div>";

                    echo "</div>";
                echo "</div>";
            }
        echo "</div>";

    }
        
        
        else{

            echo "<div class='alert alert-info'>There is no items in this category</div>";
        
        }

    

?>

</div>
<?php include $tpl . 'footer.php'; ?>