<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="/shop/index.php">My Shop</a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto">
        <?php
            $cats = getAllFrom('*', 'categories', 'Where Parent = 0 ', 'ID');

            foreach($cats as $cat){ 
                
        
                echo "<li class='nav-item active'>";
                    echo "<a class='nav-link dropdown-toggle' href='/shop/categories.php?cat_id=" . $cat['ID'] . "&cat_name=" . str_replace('%20', ' ', $cat['Name']) . "'>";
                        echo  $cat['Name'];
                    echo  "</a>";
                    echo '<div class="dropdown-menu main-navbar bg-dark" aria-labelledby="navbarDropdown">';

                echo "</li>";

            }
        ?>
       
        </ul>

        <!-- <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown ml-auto">
                <a class="nav-link dropdown-toggle " href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Categories
                </a>
                <div class="dropdown-menu main-navbar bg-dark" aria-labelledby="navbarDropdown">
                <?php
                    // $cats = getAllFrom('*', 'categories', '', 'ID', 'asc');

                    // foreach($cats as $cat){ 
                        
                    //     echo "<a class='dropdown-item bg-dark text-light' href='categories.php?cat_id=" . $cat['ID'] . "&cat_name=" . str_replace('%20', ' ', $cat['Name']) . "'>";
                    //     echo  $cat['Name'];
                    //     echo  "</a>";

                    // }



                ?>
            
                <div class="dropdown-divider"></div>
                </div>
            </li>
        
        </ul> -->
    </div>
</nav>