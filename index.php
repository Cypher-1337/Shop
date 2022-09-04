<?php 
    // this is the main page user will get after he signed in 
    // it has all items from categories

    session_start();
    $pageTitle = 'Home';
    include('init.php');
?>

<div class='container'>

<?php
$all_items = getAllFrom('*', 'items', 'WHERE Approve = 1', 'Item_ID');

    echo "<h1 class='text-center'>All Items</h1>";
        echo "<div class='card bg-dark'>";
            echo "<div class='card card-header text-center items-header'>All Items</div>";
            
            echo "<div class='card card-body'>";
                
                echo "<div class='row'>";

                    foreach($all_items as $item){

                        echo "<div class='col-sm-6 col-md-3'>";
                            echo "<div class='thumbnail item-box'>";
                                echo "<span class='price-tag'>" . $item['Price'] . " $</span>";
                                echo "<img class='img-thumbnail item-img ' src='includes/imgs/avatar4.jpg' alt='item' >";
                                
                                echo "<div class='caption'>";
                                    echo "<h3><a href='items.php?item_id=" . $item['Item_ID'] . "'>"  . $item['Name'] . "</a></h3>";
                                    echo "<p>" . $item['Description'] . "</p>";
                                    echo "<p class='date'>" . $item['Add_Date'] . "</p>";

                                    // if item not approved display msg to tell them 
                                    if($item['Approve'] == 0){
                                        echo "<p class='not-approved'> this ad not approved yet </p>";
                                    }
                                echo "</div>";
                            echo "</div>";
                        echo "</div>";
                    }

                echo "</div>";
            echo "</div>";
        echo "</div>";
            
    
?>
</div>



<?php include $tpl . 'footer.php'; ?>