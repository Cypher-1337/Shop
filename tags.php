<?php 
    // display all items that have similarity with tag 

    session_start();
    $pageTitle = 'Home';
    include('init.php');
?>


<div class="container">

    <?php

        if(isset($_GET['name']) && !empty($_GET['name'])){

            $tag_name = filter_var($_GET['name'], FILTER_SANITIZE_STRING);
            echo "<h1 class='text-center'>" . $tag_name . "</h1>";
            
            echo "<div class='card bg-dark'>";
                echo "<div class='card card-header text-center items-header'>$tag_name</div>";
                
                echo "<div class='card card-body'>";
                
                    echo "<div class='row'>";
                        $items = getAllFrom('*', 'items', 'WHERE Tags like "%' . $tag_name . '%"' , 'Item_ID');
                        if(!empty($items)){
                            foreach($items as $item){
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
                        }

                        // if there is not items with that tag
                        else{
                            echo "</div>";
                            echo "<div class='alert alert-dark text-center'>There is no items with specific Tag.</div>";
                        }

                    echo "</div>";
                echo "</div>";
            echo "</div>";

        }
        
        
        // if there is no get_parameter [name]
        else{
            echo "<div class='alert alert-danger text-center'>Please Specify A tag.</div>";
        }


    ?>

</div>


<?php include $tpl . 'footer.php'; ?>
