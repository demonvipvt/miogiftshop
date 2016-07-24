<?php 
session_start();
include "connect.php";

$sql = "SELECT * , (select count(id) FROM product WHERE product.category_id = category.id) as pnum  FROM category ";
$category = $conn->query($sql);

$errMsg = "";
$sucMsg = "";
if(isset($_SESSION["sucMsg"])){
    $sucMsg = $_SESSION["sucMsg"];
    unset($_SESSION["sucMsg"]);
}


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
    <style type="text/css">
    .addnew{
        margin-top: 20px;
        margin-bottom: 10px;
    }
    </style>
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
                        Category
                        <a href="category-form.html" class="btn btn-w-m btn-primary pull-right">Add New</a>
                    </h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="index.html">Admin</a>
                        </li>
                        <li class="active">
                            <strong>Category</strong>
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
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <table class="table table-striped table-bordered table-hover dataTables-example" >
                        <thead>
                            <tr>
                                <th>Slug</th>
                                <th>Title</th>
                                <th>Product number</th>
                                <th>On Navigation</th>
                                <th>Active</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php  
                                while($row = $category->fetch_assoc()) {
                            ?>
                            <tr class="gradeX">
                                <td><?php echo $row["slug"]?></td>
                                <td><?php echo $row["title"]?></td>
                                <td class="center"><?php echo $row["pnum"]?></td>
                                <td class="center"><?php if ( $row["on_navigation"] == 1){echo '<i class="fa fa-check text-navy"></i>';}else{echo '<i class="fa fa-times text-danger"></i>';}?></td>
                                <td class="center"><?php if ( $row["is_active"] == 1){echo '<i class="fa fa-check text-navy"></i>';}else{echo '<i class="fa fa-times text-danger"></i>';}?></td>
                                <td class="center">
                                    <a class="btn btn-warning btn-bitbucket" href="category-form.html?id=<?php echo $row["id"]; ?>">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <a class="btn btn-info btn-bitbucket" href="products.html?category=<?php echo $row["id"]; ?>">
                                        <i class="fa fa-cubes"></i>
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
                                <th>Slug</th>
                                <th>Title</th>
                                <th>Product number</th>
                                <th>On Navigation</th>
                                <th>Active</th>
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
            $('#nav_category').addClass("active");
        });
    </script>
</body>

</html>
