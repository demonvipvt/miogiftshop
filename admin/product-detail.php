<?php 
session_start();
include "connect.php";


if(!isset($_GET["product"])){
    header('Location: products.html');
}

$sql_get_product = "SELECT p.*,c.id as cid, c.title as ctitle   FROM product p left join category c on p.category_id = c.id WHERE p.id = ".$_GET["product"];
$get_product = $conn->query($sql_get_product);
$is_sale = false;

if( $get_product->num_rows > 0 ){
    $cur_product     = $get_product->fetch_assoc() ;
    $sale_price = $conn->query("SELECT * from price where from_date <= '".date("Y-m-d")."' AND to_date >= '".date("Y-m-d")."' AND product_id = ".$cur_product['id']." order by from_date desc, to_date, sale_percent desc");
    while ($sp = $sale_price->fetch_assoc()) {
        if($sp['price']<$cur_product['price'] && $sp['sale_percent'] > 0){
            $is_sale = true;
            $new_price = $sp['price'];
            $sale_percent = $sp['sale_percent'];
        }
    }

}else{
    header('Location: products.html');
}

$sql = "SELECT *  FROM price WHERE product_id = ".$cur_product["id"]." order by to_date desc";
$prices = $conn->query($sql);
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

    <link href="css/plugins/iCheck/custom.css" rel="stylesheet">

    <!-- <link href="css/plugins/chosen/chosen.css" rel="stylesheet"> -->

    <link href="css/plugins/jasny/jasny-bootstrap.min.css" rel="stylesheet">

    <link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">


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
                        <a href="product-form.html" class="btn btn-w-m btn-primary pull-right">Add New</a>
                    </h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="index.html">Dashboard</a>
                        </li>
                        <li>
                            <a href="category.html"><?php echo $cur_product["ctitle"] ?></a>
                        </li>
                        <li>
                            <a href="products.html?category=<?php echo $cur_product['cid'] ?>"><?php echo $cur_product["title"] ?></a>
                        </li>
                        <li class="active">
                            <strong>Detail</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2"> 
                </div>
            </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="wrapper wrapper-content animated fadeInUp">
                    <div class="ibox">
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="m-b-md">
                                        <a href="product-form.html?id=<?php echo $cur_product['id']; ?>" class="btn btn-white btn-xs pull-right">Edit product</a>
                                        <h2><?php echo $cur_product['title']; ?> </h2>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-6">
                                    <img src="../uploads/images/<?php echo $cur_product['image']; ?>" width="100%"/>
                                </div>
                                <div class="col-lg-10 col-md-10 col-sm-6">
                                    <dl class="dl-horizontal">
                                        <dt>Status:</dt> 
                                        <dd>
                                        <?php 
                                        if($cur_product['is_active']){
                                            if($is_sale){
                                                echo '<span class="label label-warning">Sale '.$sale_percent.'%</span>';
                                            }else{
                                                    echo '<span class="label label-primary">Active</span>';
                                            }
                                        }else{
                                            echo '<span class="label label-danger">De-active</span>';
                                        }
                                        ?></dd>
                                        <dt>Price:</dt> 
                                        <dd>
                                            <?php
                                            if($is_sale){
                                                echo "<strong>".number_format($new_price,2,","," ")."VND</strong>/<small class='old-price'>".number_format($cur_product['price'],2,","," ")."VND</small>";
                                            }else{
                                                echo number_format($cur_product['price'],2,","," ")."VND";
                                            }?>
                                        </dd>
                                        <dt>Quantity sold:</dt> <dd>  <?php echo $cur_product['quantity_sold'];?></dd>
                                        <dt>Link:</dt> <dd><a target="_blank" href="../<?php echo $cur_product['slug'];?>.html" class="text-navy"><?php $actual_link = "http://$_SERVER[HTTP_HOST]/".$cur_product['slug'].".html" ; echo $actual_link; ?></a> </dd>
                                        <dt>Last Updated:</dt> <dd><?php echo date_format(date_create($cur_product['updated']),"d.m.Y");?></dd> 
                                    </dl>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <dl class="dl-horizontal">
                                        <dt>Sort description:</dt>
                                        <dd>
                                            <?php echo $cur_product['sort_description']; ?>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                            <div class="row m-t-sm">
                                <div class="col-lg-12">
                                <div class="panel blank-panel">
                                <div class="panel-heading">
                                    <div class="panel-options">
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a href="#tab-1" data-toggle="tab">Users messages</a></li>
                                            <li class=""><a href="#tab-2" data-toggle="tab">Price list</a></li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="panel-body">

                                <div class="tab-content">
                                <div class="tab-pane active" id="tab-1">
                                    <div class="feed-activity-list">
                                        <div class="feed-element">
                                            <a href="#" class="pull-left">
                                                <img alt="image" class="img-circle" src="img/a2.jpg">
                                            </a>
                                            <div class="media-body ">
                                                <small class="pull-right">2h ago</small>
                                                <strong>Mark Johnson</strong> posted message on <strong>Monica Smith</strong> site. <br>
                                                <small class="text-muted">Today 2:10 pm - 12.06.2014</small>
                                                <div class="well">
                                                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
                                                    Over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
                                                </div>
                                            </div>
                                        </div>
                                        <div class="feed-element">
                                            <a href="#" class="pull-left">
                                                <img alt="image" class="img-circle" src="img/a3.jpg">
                                            </a>
                                            <div class="media-body ">
                                                <small class="pull-right">2h ago</small>
                                                <strong>Janet Rosowski</strong> add 1 photo on <strong>Monica Smith</strong>. <br>
                                                <small class="text-muted">2 days ago at 8:30am</small>
                                            </div>
                                        </div>
                                        <div class="feed-element">
                                            <a href="#" class="pull-left">
                                                <img alt="image" class="img-circle" src="img/a4.jpg">
                                            </a>
                                            <div class="media-body ">
                                                <small class="pull-right text-navy">5h ago</small>
                                                <strong>Chris Johnatan Overtunk</strong> started following <strong>Monica Smith</strong>. <br>
                                                <small class="text-muted">Yesterday 1:21 pm - 11.06.2014</small>
                                                <div class="actions">
                                                    <a class="btn btn-xs btn-white"><i class="fa fa-thumbs-up"></i> Like </a>
                                                    <a class="btn btn-xs btn-white"><i class="fa fa-heart"></i> Love</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="feed-element">
                                            <a href="#" class="pull-left">
                                                <img alt="image" class="img-circle" src="img/a5.jpg">
                                            </a>
                                            <div class="media-body ">
                                                <small class="pull-right">2h ago</small>
                                                <strong>Kim Smith</strong> posted message on <strong>Monica Smith</strong> site. <br>
                                                <small class="text-muted">Yesterday 5:20 pm - 12.06.2014</small>
                                                <div class="well">
                                                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
                                                    Over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
                                                </div>
                                            </div>
                                        </div>
                                        <div class="feed-element">
                                            <a href="#" class="pull-left">
                                                <img alt="image" class="img-circle" src="img/profile.jpg">
                                            </a>
                                            <div class="media-body ">
                                                <small class="pull-right">23h ago</small>
                                                <strong>Monica Smith</strong> love <strong>Kim Smith</strong>. <br>
                                                <small class="text-muted">2 days ago at 2:30 am - 11.06.2014</small>
                                            </div>
                                        </div>
                                        <div class="feed-element">
                                            <a href="#" class="pull-left">
                                                <img alt="image" class="img-circle" src="img/a7.jpg">
                                            </a>
                                            <div class="media-body ">
                                                <small class="pull-right">46h ago</small>
                                                <strong>Mike Loreipsum</strong> started following <strong>Monica Smith</strong>. <br>
                                                <small class="text-muted">3 days ago at 7:58 pm - 10.06.2014</small>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="tab-pane" id="tab-2">
                                    <a data-toggle="modal" href="#modal-add-price" class="btn btn-primary pull-right">Add Price</a>
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>Price</th>
                                            <th>Sale Percent</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Comments</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php while($row = $prices->fetch_assoc()) { ?>
                                        <tr>
                                            <td>
                                               <?php echo number_format($row['price'],2,","," ");?> VND
                                            </td>
                                            <td>
                                            <?php echo $row['sale_percent'];?> %
                                            </td>
                                            <td>
                                               <?php echo date_format(date_create($row['from_date']),"d.m.Y");?>
                                            </td>
                                            <td>
                                                <?php echo date_format(date_create($row['to_date']),"d.m.Y");?>
                                            </td>
                                            <td>
                                            <p class="small">
                                                <?php echo $row['comment'];?>
                                            </p>
                                            </td>
                                            <td><a data-toggle="modal" href="#modal-edit-price" class="text-navy edit-price"
                                            style="cursor:pointer;"
                                            data-id="<?php echo $row['id'];?>" 
                                            data-price="<?php echo $row['price'];?>" 
                                            data-sale-percent="<?php echo $row['sale_percent'];?>"
                                            data-start="<?php echo date_format(date_create($row['from_date']),"d.m.Y");?>"
                                            data-end="<?php echo date_format(date_create($row['to_date']),"d.m.Y");?>"
                                            data-cmt="<?php echo $row['comment'];?>"  >
                                                <i class="fa fa-wrench"></i>
                                            </a></td>

                                        </tr>
                                        <?php }?>
                                        </tbody>
                                    </table>

                                </div>
                                </div>

                                </div>

                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
        <?php include "layouts/footer.php";?>

        </div>
        </div>


    </div>
    <!-- modal -->
    <div id="modal-add-price" class="modal fade" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                <form role="form" method="POST" action="save-price.php" id="new_price_form">
                <input type="hidden" name="product_id" value="<?php echo $cur_product['id'];?>" />
                    <div class="row">
                        <div class="col-lg-12"><h3 style="margin-top:0px;">Add new a price</h3></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group"><label>Price</label> <input id="n_price" type="text" name="price" placeholder="Enter new price" class="form-control" value="<?php echo $cur_product['price']; ?>" style="text-align: right;"></div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group"><label>Sale percent</label> 
                            <div class="input-group m-b"><input id="n_sale_percent" class="form-control" name="sale_percent" placeholder="Enter sale percent" type="text" value="0" style="text-align: right;"> <span class="input-group-addon">%</span></div></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group"><label>Date</label> 
                                <div class="input-daterange input-group" id="datepicker" style="width:100%;">
                                    <input type="text" class="input-sm form-control" name="start" value="<?php echo date("d.m.Y");?>"/>
                                    <span class="input-group-addon">to</span>
                                    <input type="text" class="input-sm form-control" name="end" value="<?php echo date("d.m.Y");?>" />
                                </div>
                            </div>
                            
                            <div class="form-group"><label>Comment</label> <input type="text" name="comment" placeholder="Enter comment." class="form-control"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div>
                                <button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit"><strong>Save</strong></button>
                            </div>
                            
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
    <div id="modal-edit-price" class="modal fade" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                <form role="form" method="POST" action="save-price.php" id="edit_price_form">
                <input type="hidden" name="product_id" value="<?php echo $cur_product['id'];?>" />
                <input type="hidden" name="price_id" value="<?php echo $cur_product['id'];?>" id="e_id" />
                    <div class="row">
                        <div class="col-lg-12"><h3 style="margin-top:0px;">Edit a price</h3></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group"><label>Price</label> <input id="e_price" type="text" name="price" placeholder="Enter new price" class="form-control" value="<?php echo $cur_product['price']; ?>" style="text-align: right;"></div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group"><label>Sale percent</label> 
                            <div class="input-group m-b"><input id="e_sale" class="form-control" name="sale_percent" placeholder="Enter sale percent" type="text" value="0" style="text-align: right;"> <span class="input-group-addon">%</span></div></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group"><label>Date</label> 
                                <div class="input-daterange input-group" id="datepicker" style="width:100%;">
                                    <input id="e_start" type="text" class="input-sm form-control" name="start" value=""/>
                                    <span class="input-group-addon">to</span>
                                    <input id="e_end" type="text" class="input-sm form-control" name="end" value="" />
                                </div>
                            </div>
                            
                            <div class="form-group"><label>Comment</label> <input id="e_cmt" type="text" name="comment" placeholder="Enter comment." class="form-control"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div>
                                <button class="btn btn-sm btn-primary pull-right m-t-n-xs" type="submit"><strong>Save</strong></button>
                            </div>
                            
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="js/inspinia.js"></script>
    <script src="js/plugins/pace/pace.min.js"></script>


    <!-- Data Tables -->
    <script src="js/plugins/dataTables/jquery.dataTables.js"></script>

   <!-- Input Mask-->
    <script src="js/plugins/jasny/jasny-bootstrap.min.js"></script>

   <!-- Data picker -->
   <script src="js/plugins/datapicker/bootstrap-datepicker.js"></script>
    <!-- validate form -->
    <script src="jquery-validation-1.15.0/jquery.validate.min.js"></script>


    <!-- Page-Level Scripts -->
    <script>
    var root_price = <?php echo json_encode($cur_product['price']); ?>;
        $.extend( $.validator.messages, {
            required: "Hãy nhập.",
            remote: "Hãy sửa cho đúng.",
            email: "Hãy nhập email.",
            url: "Hãy nhập URL.",
            date: "Hãy nhập ngày.",
            dateISO: "Hãy nhập ngày (ISO).",
            number: "Hãy nhập số.",
            digits: "Hãy nhập chữ số.",
            creditcard: "Hãy nhập số thẻ tín dụng.",
            equalTo: "Hãy nhập thêm lần nữa.",
            extension: "Phần mở rộng không đúng.",
            maxlength: $.validator.format( "Hãy nhập từ {0} kí tự trở xuống." ),
            minlength: $.validator.format( "Hãy nhập từ {0} kí tự trở lên." ),
            rangelength: $.validator.format( "Hãy nhập từ {0} đến {1} kí tự." ),
            range: $.validator.format( "Hãy nhập từ {0} đến {1}." ),
            max: $.validator.format( "Hãy nhập từ {0} trở xuống." ),
            min: $.validator.format( "Hãy nhập từ {1} trở lên." )
        } );
        $(document).ready(function() {
            $('.dataTables-example').dataTable();
            $('#nav_product').addClass("active");
            $('.input-daterange').datepicker({
                format: 'dd.mm.yyyy',
                keyboardNavigation: false,
                forceParse: false,
                autoclose: true
            });
            $("#new_price_form").validate({
                rules: {
                    price: {
                        required: true,
                        number: true,
                        min: <?php echo json_encode($cur_product['price']);?>
                    },
                    sale_percent: {
                        required: true,
                        number: true,
                        min: 0,
                        max: 100
                    },
                    start: {
                        required: true
                    },
                    end: {
                        required: true
                    },
                    comment: {
                        required: true
                    }
                }
            });
            $("#n_price").change(function(){
                var new_price = parseInt($(this).val());
                var div_price = parseInt(((root_price - new_price) / root_price) *100);
                $("#n_sale_percent").val(div_price.toString());
            })
            // edit price
            $(".edit-price").click(function(){
                var id = $(this).attr("data-id");
                var price = $(this).attr("data-price");
                var sale_percent = $(this).attr("data-sale-percent");
                var start = $(this).attr("data-start");
                var end = $(this).attr("data-end");
                var cmt = $(this).attr("data-cmt");
                $("#e_id").val(id);
                $("#e_price").val(price);
                $("#e_sale").val(sale_percent);
                $("#e_start").val(start);
                $("#e_end").val(end);
                $("#e_cmt").val(cmt);
                // $("#edit_price_form").modal("show");
            })
            $("#edit_price_form").validate({
                rules: {
                    price: {
                        required: true,
                        number: true,
                        min: <?php echo json_encode($cur_product['price']);?>
                    },
                    sale_percent: {
                        required: true,
                        number: true,
                        min: 0,
                        max: 100
                    },
                    start: {
                        required: true
                    },
                    end: {
                        required: true
                    },
                    comment: {
                        required: true
                    }
                }
            });
            $("#e_price").change(function(){
                var new_price = parseInt($(this).val());
                var div_price = parseInt(((root_price - new_price) / root_price) *100);
                $("#e_sale").val(div_price.toString());
            })
        });
    </script>
</body>

</html>
