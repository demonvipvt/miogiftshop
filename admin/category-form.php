<?php 
session_start();
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
        if( $cat_image != ""){
            $cat_old_image  = $curCat["image"];
        }
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
    if( $title == "" || $slug == "" || $seo_title == "" || $errMsg != ""){
        $errMsg = "Missing required fields.";
    }
    if( $errMsg == "" ){
        $newFile = false;
        $info = pathinfo($_FILES['image']['name']);
        if( $id == 0 ){
            if( !empty($info["filename"]) ){
                $ext = $info['extension']; // get the extension of the file
                $cat_image = "Thumbnail-".$slug.".".$ext; 

                $target = '../uploads/images/'.$cat_image;
                if(file_exists($target)) {
                    chmod($target,0755); //Change the file permissions if allowed
                    unlink($target); //remove the file
                }
                move_uploaded_file( $_FILES['image']['tmp_name'], $target);
                $newFile = true ;
            }
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
                    $_SESSION["sucMsg"] = $sucMsg;
                    header('Location: category.html');
                    session_write_close();
                }
            } else {
                $errMsg = "Error: " . $sql . "<br>" . $conn->error;
                if($newFile){
                    unlink('../uploads/images/'.$cat_image);
                }
            }
        }else{
            if ( !$slugEntity->saveSlug($conn,$slug,"category",$id) ) {
                $errMsg = "Key slug already exist.";
            }else{
                if( !empty($info["filename"]) ){
                    $ext = $info['extension']; // get the extension of the file
                    $cat_image = "Thumbnail-".$slug.".".$ext; 

                    $target = '../uploads/images/'.$cat_image;
                    if(isset($cat_old_image)){
                        $old = '../uploads/images/'.$cat_old_image;
                        if(file_exists($old)) {
                            chmod($old,0755); //Change the file permissions if allowed
                            unlink($old); //remove the file
                        }
                    }
                    if(file_exists($target)) {
                        chmod($target,0755); //Change the file permissions if allowed
                        unlink($target); //remove the file
                    }
                    move_uploaded_file( $_FILES['image']['tmp_name'], $target);
                    $newFile = true ;
                }
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
                    header('Location: category.html');
                    $_SESSION["sucMsg"] = $sucMsg;
                    session_write_close();
                } else {
                    $errMsg = "Error: " . $sql . "<br>" . $conn->error;
                    if($newFile){
                        unlink('../uploads/images/'.$cat_image);
                    }
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
                            <a href="index.html">Dashboard</a>
                        </li>
                        <li>
                            <a href="category.html">Category</a>
                        </li>
                        <?php 
                        if(isset($curCat)){
                        ?>
                        <li>
                            <?php echo $title ; ?>
                        </li>
                        <li class="active">
                            <strong>Edit</strong>
                        </li>
                        <?php
                        }else{
                        ?>
                        <li class="active">
                            <strong>Add New</strong>
                        </li>
                        <?php } ?>
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
                                <div class="checkbox"><label> <input type="checkbox" value="1" name="active"  <?php if($is_active == 1){echo "checked";}; ?>> Turn on or off the category.</label></div>
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
    <script src="../ckfinder/ckfinder.js"></script>
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
                <?php $urlID = empty($id)?"":"?obj=category&id=".$id ?>
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
