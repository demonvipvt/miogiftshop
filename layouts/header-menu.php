<?php 
    include "config/connect.php";
    $sql = "SELECT *  FROM category WHERE is_active = 1 AND on_navigation = 1 AND parent_id is null ORDER BY `order` ";
    $category = $conn->query($sql);
    if(!isset($slugKey)){
        $slugKey = "home";
    }
    $menuStyle = 2;
?>
<ul class="megamenu skyblue">
    <?php if($menuStyle == 1){ ?>
    <li class="grid <?php if($slugKey == 'home'){echo 'active';} ?>"><a class="color1" href="index.html">Home</a></li>
    <li class="grid <?php if($activeMenu == 'category'){echo 'active';} ?>"><a class="color2" href="#">Category</a>
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
    <?php }else{
        $countCat = 2 ;
        while($row = $category->fetch_assoc()) {
            $active = strtolower($slugKey) == strtolower($row['slug'])?"active":"";
            echo '<li class="'.$active.' grid "><a class="color'.($countCat++).'" href="'.$row['slug'].'.html">'.$row['title'].'</a></li>';
            if($countCat == 10){
                $countCat = 1;
            }
        }
        }
    ?>
</ul> 
<div class="search">
    <form>
        <input type="text" value="" placeholder="Search...">
        <input type="submit" value="">
    </form>
</div>