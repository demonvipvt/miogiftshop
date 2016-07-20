<?php 
include "connect.php";
include "models/slug.php";

$id  = empty( $_GET["id"] ) ?0:intval($_GET["id"]);
$sql = "SELECT id,title  FROM category WHERE parent_id is null ";
$category = $conn->query($sql);
$errMsg = "";
$sucMsg = "";
$slugEntity = new slugEntity();

$title = "";
$slug = "";
$cat_des = "";
$on_nav = 1;
$order = 0;
$is_active = 1;
$parent = 0;
$seo_title = "";
$seo_des = "";
$seo_tags = "";
$cat_image  = "";
if( $id != 0 ){
    $sql = "SELECT *  FROM category WHERE id = ".$id;
    $getCat = $conn->query($sql);
    if( $getCat->num_rows > 0 ){
        $curCat     = $getCat->fetch_assoc() ;
        $title      = $curCat["title"];
        $slug       = $curCat["slug"];
        $cat_des    = $curCat["description"];
        $on_nav     = $curCat["on_navigation"];
        $order      = $curCat["order"];
        $is_active  = $curCat["is_active"];
        $parent     = $curCat["parent_id"];
        $seo_title  = $curCat["seo_title"];
        $seo_des    = $curCat["seo_description"];
        $seo_tags   = $curCat["seo_tags"];
        $cat_image  = $curCat["image"];
    }else{
        $errMsg = "Invalid category ID.";
    }
}

if (!empty($_POST))
{
    $title          = empty( $_POST["title"] )   ?"":$_POST["title"];
    $slug           = empty( $_POST["slug"] )   ?"":$_POST["slug"];
    $cat_des        = empty( $_POST["description"] ) ?"": htmlspecialchars($_POST["description"]);
    $on_nav         = empty( $_POST["on_nav"] )  ?0:1;
    $order          = empty( $_POST["order"] )   ?0:$_POST["order"];
    $is_active      = empty( $_POST["active"] )  ?0:1;
    $parent         = empty( $_POST["parent"] )  ?"null":$_POST["parent"];
    $seo_title      = empty( $_POST["seo_title"] )          ?"":$_POST["seo_title"];
    $seo_des        = empty( $_POST["seo_description"] )    ?"":$_POST["seo_description"];
    $seo_tags       = empty( $_POST["seo_tags"] )           ?"":$_POST["seo_tags"];
    $cat_image = "";
    if( $title == "" || $slug == "" || $seo_title == "" || $errMsg != ""){
        $errMsg = "Missing required fields.";
    }
    if( $errMsg == "" ){
        $newFile = false;
        $info = pathinfo($_FILES['image']['name']);
        if( !empty($info["filename"]) ){
            $ext = $info['extension']; // get the extension of the file
            $cat_image = time().".".$ext; 

            $target = '../uploads/images/'.$cat_image;
            move_uploaded_file( $_FILES['image']['tmp_name'], $target);
            $newFile = true ;
        }
        if( $id == 0 ){
            $sql = "INSERT INTO category (`title`, `description`, `image`, `slug`, `parent_id`, `is_active`, `on_navigation`, `order`, `seo_title`, `seo_description`, `seo_tags`)
            VALUES ('".$title."'
                , '".$cat_des."'
                , '".$cat_image."'
                , '".$slug."'
                , ".$parent."
                , ".$is_active."
                , ".$on_nav."
                , ".$order."
                , '".$seo_title."'
                , '".$seo_des."'
                , '".$seo_tags."'
                )";
            if ($conn->query($sql) === TRUE) {
                $last_id = $conn->insert_id;
                $sucMsg = "New category created successfully";
                if ( !$slugEntity->saveSlug($conn,$slug,"category",$last_id) ) {
                    $conn->query("DELETE FROM category WHERE `id` = ".$last_id);
                    $errMsg = "Key slug already exist.";
                    if($newFile){
                        unlink('../uploads/images/'.$cat_image);
                    }
                }
            } else {
                $errMsg = "Error: " . $sql . "<br>" . $conn->error;
                if($newFile){
                    unlink('../uploads/images/'.$cat_image);
                }
            }
        }else{
            $sql = "UPDATE category SET `title` = '".$title."'
                        , `description`    = '".$cat_des."'
                        , `image`          = '".$cat_image."'
                        , `slug`           = '".$slug."'
                        , `parent_id`      = ".$parent."
                        , `is_active`      = ".$is_active."
                        , `on_navigation`  = ".$on_nav."
                        , `order`          = ".$order."
                        , `seo_title`      = '".$seo_title."'
                        , `seo_description`= '".$seo_des."'
                        , `seo_tags`       = '".$seo_tags."'
                    WHERE id = ".$id;
            if ($conn->query($sql) === TRUE) {
                $sucMsg = "Update category successfully";
                if ( !$slugEntity->saveSlug($conn,$slug,"category",$id) ) {
                    $errMsg = "Key slug already exist.";
                }
            } else {
                $errMsg = "Error: " . $sql . "<br>" . $conn->error;
                if($newFile){
                    unlink('../uploads/images/'.$cat_image);
                }
            }
        }
    }
}

?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>INSPINIA | Basic Form</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="js/plugins/fancybox/jquery.fancybox.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

</head>

<body>

    <div id="wrapper">

        <?php include "layouts/nav.php"; ?>

        <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
        <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
            <form role="search" class="navbar-form-custom" method="post" action="search_results.html">
                <div class="form-group">
                    <input type="text" placeholder="Search for something..." class="form-control" name="top-search" id="top-search">
                </div>
            </form>
        </div>
            <ul class="nav navbar-top-links navbar-right">
                <li>
                    <span class="m-r-sm text-muted welcome-message">Welcome to INSPINIA+ Admin Theme.</span>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                        <i class="fa fa-envelope"></i>  <span class="label label-warning">16</span>
                    </a>
                    <ul class="dropdown-menu dropdown-messages">
                        <li>
                            <div class="dropdown-messages-box">
                                <a href="profile.html" class="pull-left">
                                    <img alt="image" class="img-circle" src="img/a7.jpg">
                                </a>
                                <div class="media-body">
                                    <small class="pull-right">46h ago</small>
                                    <strong>Mike Loreipsum</strong> started following <strong>Monica Smith</strong>. <br>
                                    <small class="text-muted">3 days ago at 7:58 pm - 10.06.2014</small>
                                </div>
                            </div>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <div class="dropdown-messages-box">
                                <a href="profile.html" class="pull-left">
                                    <img alt="image" class="img-circle" src="img/a4.jpg">
                                </a>
                                <div class="media-body ">
                                    <small class="pull-right text-navy">5h ago</small>
                                    <strong>Chris Johnatan Overtunk</strong> started following <strong>Monica Smith</strong>. <br>
                                    <small class="text-muted">Yesterday 1:21 pm - 11.06.2014</small>
                                </div>
                            </div>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <div class="dropdown-messages-box">
                                <a href="profile.html" class="pull-left">
                                    <img alt="image" class="img-circle" src="img/profile.jpg">
                                </a>
                                <div class="media-body ">
                                    <small class="pull-right">23h ago</small>
                                    <strong>Monica Smith</strong> love <strong>Kim Smith</strong>. <br>
                                    <small class="text-muted">2 days ago at 2:30 am - 11.06.2014</small>
                                </div>
                            </div>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <div class="text-center link-block">
                                <a href="mailbox.html">
                                    <i class="fa fa-envelope"></i> <strong>Read All Messages</strong>
                                </a>
                            </div>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell"></i>  <span class="label label-primary">8</span>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts">
                        <li>
                            <a href="mailbox.html">
                                <div>
                                    <i class="fa fa-envelope fa-fw"></i> You have 16 messages
                                    <span class="pull-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="profile.html">
                                <div>
                                    <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                                    <span class="pull-right text-muted small">12 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="grid_options.html">
                                <div>
                                    <i class="fa fa-upload fa-fw"></i> Server Rebooted
                                    <span class="pull-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <div class="text-center link-block">
                                <a href="notifications.html">
                                    <strong>See All Alerts</strong>
                                    <i class="fa fa-angle-right"></i>
                                </a>
                            </div>
                        </li>
                    </ul>
                </li>


                <li>
                    <a href="login.html">
                        <i class="fa fa-sign-out"></i> Log out
                    </a>
                </li>
            </ul>

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
                    <form method="post" id="category_form" class="form-horizontal" enctype='multipart/form-data'>

                        <div class="form-group"><label class="col-sm-2 control-label">Title</label>

                            <div class="col-sm-10"><input type="text" class="form-control" name="title" id="title" value="<?php echo $title; ?>"></div>
                        </div>

                        <div class="hr-line-dashed"></div>
                        <div class="form-group"><label class="col-sm-2 control-label">Slug</label>
                            <div class="col-sm-10"><input type="text" class="form-control" name="slug" id="slug" value="<?php echo $slug; ?>"> <span class="help-block m-b-none">A slug have to unique in this site.</span>
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>
                        <div class="form-group"><label class="col-sm-2 control-label">Thumbnail</label>

                            <div class="col-sm-10">
                                <?php 
                                    $previewImage = $cat_image == ""?"":"/uploads/images/".$cat_image;
                                ?>
                                <a class="fancybox" id="preview_container" href="<?php echo $previewImage;?>" title="Picture 1">
                                    <img alt="image" id="preview" src="<?php echo $previewImage;?>">
                                </a>
                                <button type="button" class="btn btn-default btn-sm" id="reset_image" style="display:none;">Reset image</button>
                                <input type="file" class="file" name="image" id="image" accept="image/*">
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>
                        <div class="form-group"><label class="col-sm-2 control-label">Parent</label>
                            <div class="col-sm-10">
                                <select class="form-control m-b" name="parent">
                                    <option value="">Select parent category</option>
                                    <?php 
                                        while($row = $category->fetch_assoc()) {
                                            $selectedOption = $row['id'] == $parent ?"selected":"";
                                    ?>
                                    <option value="<?php echo $row['id'] ?>" <?php echo $selectedOption ?>><?php echo $row['title']?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>
                        <div class="form-group"><label class="col-sm-2 control-label">On navigation</label>

                            <div class="col-sm-10">
                                <div class="checkbox"><label> <input type="checkbox" value="1" name="on_nav" <?php if($on_nav == 1){echo "checked";}; ?> > The category will be show on navigation if you checked this one .</label></div>
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>
                        <div class="form-group"><label class="col-sm-2 control-label">Order</label>
                            <div class="col-sm-2"><input type="number" class="form-control" name="order" value="<?php echo $order; ?>"> 
                            </div><span class="help-block m-b-none">for sorting category on navigation.</span>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group"><label class="col-sm-2 control-label">Description</label>

                            <div class="col-sm-10">
                                <textarea class="ckeditor" name="description" id="description"> <?php echo $cat_des; ?></textarea>
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>
                        <div class="form-group"><label class="col-sm-2 control-label">Active</label>

                            <div class="col-sm-10">
                                <div class="checkbox"><label> <input type="checkbox" value="1" name="active"  <?php if($is_active == 1){echo "checked";}; ?>></label></div>
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>
                        <div class="form-group"><label class="col-sm-2 control-label">SEO title</label>

                            <div class="col-sm-10"><input type="text" class="form-control" name="seo_title" id = "seo_title" value="<?php echo $seo_title; ?>"></div>
                        </div>

                        <div class="hr-line-dashed"></div>
                        <div class="form-group"><label class="col-sm-2 control-label">SEO description</label>

                            <div class="col-sm-10"><input type="text" class="form-control" name="seo_description" value="<?php echo $seo_des; ?>"></div>
                        </div>

                        <div class="hr-line-dashed"></div>
                        <div class="form-group"><label class="col-sm-2 control-label">SEO tags</label>

                            <div class="col-sm-10"><input type="text" class="form-control" name="seo_tags"  value="<?php echo $seo_tags; ?>"></div>
                        </div>

                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <input type="hidden" id="backlink">
                                <button class="btn btn-white" id="btn_cancel" type="button">Cancel</button>
                                <button class="btn btn-primary" type="submit">Save changes</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php include "layouts/footer.php"; ?>
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

    <!-- iCheck -->
    <script src="js/plugins/iCheck/icheck.min.js"></script>
    <!-- CKeditor -->
    <script src="../ckeditor/ckeditor.js"></script>
    <script src="../cffinder/ckfinder.js"></script>
    <!-- validate form -->
    <script src="jquery-validation-1.15.0/jquery.validate.min.js"></script>
    <!-- fancy box -->
    <script src="js/plugins/fancybox/jquery.fancybox.js"></script>
        <script>
        var previewRootImage = <?php echo json_encode($previewImage) ?>;
        if( previewRootImage == "" ){
            $("#preview_container").hide();
        }
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
            $(document).ready(function () {
                var jbl = document.referrer;
                var curbl = $("#backlink").val();
                if( jbl != "" && curbl == "" ){
                    $("#backlink").val(jbl);
                }else{
                    $("#backlink").val("category.html");
                }
                $("#btn_cancel").click(function(){
                    location.href = $("#backlink").val();
                });

                $("#title").on("change",function(){
                    titleSlug = convertToSlug($(this).val());
                    $("#slug").val(titleSlug);
                    $("#seo_title").val($(this).val());
                })


                CKEDITOR.replace("description");
                <?php $urlID = empty($id)?"":"?id=".$id ?>
                var urlID = <?php echo json_encode($urlID); ?>;
                $("#category_form").validate({
                    rules: {
                        title: {
                            required: true
                        },
                        slug: {
                            required: true,
                            remote: {
                                url: '/admin/validator_slug.php'+urlID,
                                type: 'POST',
                                async: false
                            }
                        },
                        description: {
                            required: true
                        },
                        seo_title: {
                            required: true
                        }
                    },
                    messages: {
                        slug: {
                            remote: 'Slug đã được sử dụng .'
                        }
                    }
                });
                $('.fancybox').fancybox({
                    openEffect  : 'none',
                    closeEffect : 'none'
                });


                $("#image").change(function(){
                    readURL(this);
                });
                $("#reset_image").click(function(){
                    reset($("#image"));
                    $(this).hide();
                    if(previewRootImage != ""){
                        $('#preview').attr('src', previewRootImage);
                        $('#preview_container').attr('href', previewRootImage).show();
                    }
                })

            });

// image
            function readURL(input) {

                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('#preview').attr('src', e.target.result);
                        $('#preview_container').attr('href', e.target.result).show();
                        $("#reset_image").show();
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }

            function convertToSlug(Text)
            {
                return Text
                .toLowerCase()
                .replace(/[^\w ]+/g,'')
                .replace(/ +/g,'-')
                ;
            }
            window.reset = function (e) {
                e.wrap('<form>').closest('form').get(0).reset();
                e.unwrap();
            }
        </script>
</body>

</html>
