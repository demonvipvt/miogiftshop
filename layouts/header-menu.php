<?php 
    include "config/connect.php";
    $sql = "SELECT *  FROM category WHERE is_active = 1 AND on_navigation = 1 AND parent_id is null ORDER BY `order` ";
    $category = $conn->query($sql);
?>
<ul class="megamenu skyblue">
    <li class="grid"><a class="color1" href="index.html">Home</a></li>
    <li class="active grid"><a class="color2" href="#">Category</a>
        <div class="megapanel">
            <div class="row">
        <?php 
            $countCat = 0 ;
            while($row = $category->fetch_assoc()) {
                $countCurCat = 1;
                $defaultCol = "col1";
                if( strlen(trim($row['title'])) > 20 ){
                    $countCurCat = 2;
                    $defaultCol = "col2";
                }
                $sqlsub = "SELECT *  FROM category WHERE is_active = 1 AND on_navigation = 1 AND parent_id = ".$row['id']." ORDER BY `order` ";
                $subcategory = $conn->query($sqlsub);
                if( ($countCat+$countCurCat) > 5 ){
                    echo '</div><div class="row">';
                    $countCat = $countCurCat ;
                }else{
                    $countCat += $countCurCat ;
                }
        ?>
                <div class="<?php echo $defaultCol;?>">
                    <div class="h_nav">
                        <h4><?php echo '<a href="'.$row['slug'].'.html">'.$row['title'].'</a>'; ?></h4>
                        <?php
                            if ($subcategory->num_rows > 0) {
                                echo "<ul>";
                                while($subrow = $subcategory->fetch_assoc()) {
                                    echo '<li><a href="'.$subrow['slug'].'.html">'.$subrow['title'].'</a></li>';
                                }
                                echo "</ul>";
                            }
                        ?>
                    </div>                          
                </div>
        <?php 
            }
        ?>
                <!-- <div class="col1">
                    <div class="h_nav">
                        <h4>Tables</h4>
                        <ul>
                            <li><a href="products.html">Coffee Tables</a></li>
                            <li><a href="products.html">Dinning Tables</a></li>
                            <li><a href="products.html">Study Tables</a></li>
                            <li><a href="products.html">Wooden Tables</a></li>
                            <li><a href="products.html">Study Tables</a></li>
                            <li><a href="products.html">Bar & Unit Stools</a></li>
                        </ul>   
                    </div>                          
                </div>
                <div class="col1">
                    <div class="h_nav">
                        <h4>Beds</h4>
                        <ul>
                            <li><a href="products.html">Single Bed</a></li>
                            <li><a href="products.html">Poster Bed</a></li>
                            <li><a href="products.html">Sofa Cum Bed</a></li>
                            <li><a href="products.html">Bunk Bed</a></li>
                            <li><a href="products.html">King Size Bed</a></li>
                            <li><a href="products.html">Metal Bed</a></li>
                        </ul>   
                    </div>                                              
                </div>
                <div class="col1">
                    <div class="h_nav">
                        <h4>Seating</h4>
                        <ul>
                            <li><a href="products.html">Wing Chair</a></li>
                            <li><a href="products.html">Accent Chair</a></li>
                            <li><a href="products.html">Arm Chair</a></li>
                            <li><a href="products.html">Mettal Chair</a></li>
                            <li><a href="products.html">Folding Chair</a></li>
                            <li><a href="products.html">Bean Bags</a></li>
                        </ul>   
                    </div>                      
                </div>
                <div class="col1">
                    <div class="h_nav">
                        <h4>Solid Woods</h4>
                        <ul>
                            <li><a href="products.html">Side Tables</a></li>
                            <li><a href="products.html">T.v Units</a></li>
                            <li><a href="products.html">Dressing Tables</a></li>
                            <li><a href="products.html">Wardrobes</a></li>
                            <li><a href="products.html">Shoe Racks</a></li>
                            <li><a href="products.html">Console Tables</a></li>
                        </ul>   
                    </div>
                </div> -->
            </div>
            <div class="row">
                <div class="col2"></div>
                <div class="col1"></div>
                <div class="col1"></div>
                <div class="col1"></div>
                <div class="col1"></div>
            </div>
        </div>
    </li>
    <li><a class="color3" href="#">Page 1</a></li>  
    <li><a class="color4" href="#">Page 2</a></li>               
    <li><a class="color5" href="#">Page 3</a></li>
    <li><a class="color6" href="#">Page 4</a></li>               
    <li><a class="color7" href="#">About</a></li>               
    <li><a class="color8" href="#">Maps</a></li>               
</ul> 
<div class="search">
    <form>
        <input type="text" value="" placeholder="Search...">
        <input type="submit" value="">
    </form>
</div>