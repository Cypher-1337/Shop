<?php

    $do = '';

    $do = isset($_GET['do']) ? $_GET['do'] : $do = "Manage";


    if( $do == "Manage"){

        echo "Welcome in Manage Category";
        echo "Add new Category <a href='?do=Add'>+</a>";

    }elseif( $do == "Add"){

        echo "Welcome in Add Category";

    }elseif( $do == "Delete"){

        echo "Welcome in Delete Category";

    }elseif( $do == "Insert"){
        
        echo "Welcome in Insert Category";
    
    }else{

        echo "Welcome in Manage Category";

    }
?>