<?php 
include "connect.php";

$sql = "SELECT *,(select count(id) from price where from_date <= '".date("Y-m-d")."' AND to_date >= '".date("Y-m-d")."' AND product_id = product.id ) as sale  FROM product ";
if(isset($_GET["category"])){
    $sqlcat = "SELECT id,title  FROM category WHERE id = ".$_GET["category"];
    $getCat = $conn->query($sqlcat);
    if( $getCat->num_rows > 0 ){
        $curCat     = $getCat->fetch_assoc() ;
        $sql = "SELECT *,(select count(id) from price where from_date <= '".date("Y-m-d")."' AND to_date >= '".date("Y-m-d")."' AND product_id = product.id ) as sale  FROM product WHERE category_id = ".$curCat["id"];
    }

}
$products = $conn->query($sql);

$sucMsg = "";
if( isset($_SESSION['sucMsg']) ){
    $sucMsg = $_SESSION['sucMsg'];
    unset($_SESSION['sucMsg']);
}
$errMsg = "";
if( isset($_SESSION['errMsg']) ){
    $errMsg = $_SESSION['errMsg'];
    unset($_SESSION['errMsg']);
}

// get all category for filter product
$sql_getcat = "SELECT id,title,IF(isNull(parent_id),0,parent_id) as parent FROM category WHERE is_active = 1 ORDER BY parent_id,title";

$categories = $conn->query($sql_getcat);
?>


<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>INSPINIA | Data Tables</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">

    <!-- Data Tables -->
    <link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">

    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>

    <div id="wrapper">

        <?php include "layouts/nav.php" ?>

        <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
        <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
            <?php include "layouts/header.php" ; ?>

        </nav>
        </div>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>
                        Products
                        <?php 
                            $catString = '';
                            if(isset($curCat)){
                                $catString = "?category=".$curCat['id'];
                            }
                        ?>
                        <a href="product-form.html<?php echo $catString; ?>" class="btn btn-w-m btn-primary pull-right">Add New</a>
                    </h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="index.html">Dashboard</a>
                        </li>
                        <?php
                            if(isset($curCat)){
                        ?>
                        <li class="btn-group">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="category.html?id=<?php echo $curCat['id'];?>" ><?php echo $curCat["title"]; ?> <span class="caret"></span></a>

                            <ul class="dropdown-menu">
                            <?php 
                                $curCatID = isset($curCat)?$curCat["id"]:0;
                                while($rowCat = $categories->fetch_assoc()) {
                                    $activated = $rowCat["id"] == $curCatID ?"active":"";
                                     echo '<li class="'.$activated.'"><a href="products.html?category='.$rowCat["id"].'">'.$rowCat["title"].'</a></li>';

                                }
                            ?>
                            </ul>
                        </li>
                        <?php } ?>
                        <li class="active">
                            <strong>Products</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2"> 
                </div>
            </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                    <?php
                    if( $products->num_rows == 0 ){
                        if(isset($curCat)){
                            $message = "This category is empty, click the button `Add New` to add products";
                        }else{
                            $message = "The product list is empty, click the button `Add New` to add products";
                        }
                    ?>
                    <div class="alert alert-warning alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <?php echo $message ?>
                    </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <?php
                    if($errMsg != ""){
                    ?>
                    <div class="alert alert-danger alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <?php echo $errMsg ?>
                    </div>
                    <?php
                    }
                    ?>
                    <?php
                    if($sucMsg != ""){
                    ?>
                    <div class="alert alert-success alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <?php echo $sucMsg ?>
                    </div>
                    <?php
                    }
                    ?>
                    <table class="table table-striped table-bordered table-hover dataTables-example" >
                        <thead>
                            <tr>
                                <th>code</th>
                                <th>Slug</th>
                                <th>Title</th>
                                <th>Quantity sold</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php  
                                $catString = '';
                                if(isset($curCat)){
                                    $catString = "&category=".$curCat['id'];
                                }
                                while($row = $products->fetch_assoc()) {
                            ?>
                            <tr class="gradeX">
                                <td><?php echo $row["code"]?></td>
                                <td><?php echo $row["slug"]?></td>
                                <td><?php echo $row["title"]?></td>
                                <td class="center"><?php echo $row["quantity_sold"]?></td>
                                <td class="center"><?php if($row["price"] == 0){ echo "Call";}else{echo number_format($row["price"],2,","," ");}?> VND</td>
                                <td class="center">
                                    <?php if ( $row["is_active"] == 1){
                                        if( $row['sale'] > 0){
                                            echo '<span class="label label-warning"><i class="fa fa-level-down"></i> Sale</span>';
                                        }else{
                                            echo '<span class="label label-primary"><i class="fa fa-check"></i> Active</span>';
                                        }
                                        
                                    }else{
                                        echo '<span class="label label-danger"><i class="fa fa-times"></i> De-active</span>';
                                    }?>
                                </td>
                                <td class="center">
                                    <a class="btn btn-warning btn-bitbucket" href="product-form.html?id=<?php echo $row["id"]; echo $catString; ?>">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <a class="btn btn-primary btn-bitbucket" href="product-detail.html?product=<?php echo $row["id"]; echo $catString; ?>">
                                        <i class="fa fa-rocket"></i>
                                    </a>
                                    <a class="btn btn-danger btn-bitbucket">
                                        <i class="fa fa-trash-o"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>code</th>
                                <th>Slug</th>
                                <th>Title</th>
                                <th>Quantity sold</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="footer">
            <div class="pull-right">
                10GB of <strong>250GB</strong> Free.
            </div>
            <div>
                <strong>Copyright</strong> Example Company &copy; 2014-2015
            </div>
        </div>

        </div>
        </div>


    </div>

    <!-- Mainly scripts -->
    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>

    <!-- Data Tables -->
    <script src="js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="js/plugins/dataTables/dataTables.bootstrap.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="js/inspinia.js"></script>
    <script src="js/plugins/pace/pace.min.js"></script>

    <!-- Page-Level Scripts -->
    <script>
        $(document).ready(function() {
            $('.dataTables-example').dataTable();
            $('#nav_product').addClass("active");
        });
    </script>
</body>

</html>
