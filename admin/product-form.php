<?php 
session_start();
include "connect.php";
include "models/slug.php";

$id  = empty( $_GET["id"] ) ?0:intval($_GET["id"]);
$sql = "SELECT id,title  FROM category ";
$categoryList = $conn->query($sql);
$errMsg = "";
$sucMsg = "";
$slugEntity = new slugEntity();

$title      = "";
$code       = "";
$slug       = "";
$p_des      = "";
$p_sort_des = "";
$p_image    = "";
$price      = 0;
$is_active  = 1;
$category   = 0;
$seo_title  = "";
$seo_des    = "";
$seo_tags   = "";
if( $id != 0 ){
    $sql_get_product = "SELECT *  FROM product WHERE id = ".$id;
    $getProduct = $conn->query($sql_get_product);
    if( $getProduct->num_rows > 0 ){
        $curProduct     = $getProduct->fetch_assoc() ;
        $title      = $curProduct["title"];
        $code       = $curProduct["code"];
        $slug       = $curProduct["slug"];
        $p_des      = $curProduct["description"];
        $p_sort_des = $curProduct["sort_description"];
        $p_image    = $curProduct["image"];
        if($p_image != ""){
            $p_old_image= $curProduct["image"];
        }
        $price      = $curProduct["price"];
        $is_active  = $curProduct["is_active"];
        $category   = $curProduct["category_id"];
        $seo_title  = $curProduct["seo_title"];
        $seo_des    = $curProduct["seo_description"];
        $seo_tags   = $curProduct["seo_tags"];
    }else{
        $errMsg = "Invalid product ID.";
    }
}
$backlink = "";
if (!empty($_POST))
{
    $title          = empty( $_POST["title"] )   ?"":$_POST["title"];
    $slug           = empty( $_POST["slug"] )   ?"":$_POST["slug"];
    $code           = empty( $_POST["code"] )   ?"":$_POST["code"];
    $p_des          = empty( $_POST["description"] ) ?"": htmlspecialchars($_POST["description"]);
    $p_sort_des     = empty( $_POST["sort_description"] )   ?"":$_POST["sort_description"];
    $price          = empty( $_POST["price"] )  ?0:$_POST["price"];
    $is_active      = empty( $_POST["active"] )  ?0:1;
    $category       = empty( $_POST["category"] )  ?"null":$_POST["category"];
    $seo_title      = empty( $_POST["seo_title"] )          ?"":$_POST["seo_title"];
    $seo_des        = empty( $_POST["seo_description"] )    ?"":$_POST["seo_description"];
    $seo_tags       = empty( $_POST["seo_tags"] )           ?"":$_POST["seo_tags"];
    $backlink       = empty( $_POST["backlink"] )?"products.html":$_POST["backlink"];
    if( $title == "" || $slug == "" || $seo_title == "" || $errMsg != "" || $p_sort_des == "" || empty($category)){
        $errMsg = "Missing required fields.";
    }
    if( $errMsg == "" ){
        $newFile = false;
        $info = pathinfo($_FILES['image']['name']);
        if( $id == 0 ){
            if( !empty($info["filename"]) ){
                $ext = $info['extension']; // get the extension of the file
                $p_image = "Thumbnail-".$slug.".".$ext; 

                $target = '../uploads/images/'.$p_image;
                if(file_exists($target)) {
                    chmod($target,0755); //Change the file permissions if allowed
                    unlink($target); //remove the file
                }
                move_uploaded_file( $_FILES['image']['tmp_name'], $target);
                $newFile = true ;
            }
            $sql = "INSERT INTO product (`title`, `description`, `sort_description`, `code`
                                        , `slug`, `category_id`, `is_active`
                                        , `price`, `image`, `seo_title`
                                        , `seo_description`, `seo_tags`)
            VALUES ('".$title."'
                , '".$p_des."'
                , '".$p_sort_des."'
                , '".$code."'
                , '".$slug."'
                , ".$category."
                , ".$is_active."
                , ".$price."
                , '".$p_image."'
                , '".$seo_title."'
                , '".$seo_des."'
                , '".$seo_tags."'
                )";
            if ($conn->query($sql) === TRUE) {
                $last_id = $conn->insert_id;
                $sucMsg = "New product created successfully";
                if ( !$slugEntity->saveSlug($conn,$slug,"product",$last_id) ) {
                    $conn->query("DELETE FROM product WHERE `id` = ".$last_id);
                    $errMsg = "Key slug already exist.";
                    if($newFile){
                        unlink('../uploads/images/'.$p_image);
                    }
                }else{
                    $_SESSION["sucMsg"] = $sucMsg;
                    header('Location: '.$backlink);
                    session_write_close();
                }
            } else {
                $errMsg = "Error: " . $sql . "<br>" . $conn->error;
                if($newFile){
                    unlink('../uploads/images/'.$p_image);
                }
            }
        }else{
            if ( !$slugEntity->saveSlug($conn,$slug,"product",$id) ) {
                $errMsg = "Key slug already exist.";
            }else{
                if( !empty($info["filename"]) ){
                    $ext = $info['extension']; // get the extension of the file
                    $p_image = "Thumbnail-".$slug.".".$ext; 

                    $target = '../uploads/images/'.$p_image;
                    if(isset($p_old_image)){
                        $old = '../uploads/images/'.$p_old_image;
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
                $sql = "UPDATE product SET `title` = '".$title."'
                            , `slug`            = '".$slug."'
                            , `code`            = '".$code."'
                            , `description`     = '".$p_des."'
                            , `sort_description`= '".$p_sort_des."'
                            , `image`           = '".$p_image."'
                            , `category_id`     = ".$category."
                            , `is_active`       = ".$is_active."
                            , `price`           = ".$price."
                            , `seo_title`       = '".$seo_title."'
                            , `seo_description` = '".$seo_des."'
                            , `seo_tags`        = '".$seo_tags."'
                        WHERE id = ".$id;
                if ($conn->query($sql) === TRUE) {
                    $sucMsg = "Update product successfully";
                    $_SESSION["sucMsg"] = $sucMsg;
                    header('Location: '.$backlink);
                    session_write_close();
                } else {
                    $errMsg = "Error: " . $sql . "<br>" . $conn->error;
                    if($newFile){
                        unlink('../uploads/images/'.$p_image);
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
                            <a href="products.html">Products</a>
                        </li>
                        <?php 
                        if(isset($curProduct)){
                            echo '<li>'.$title."</li>";
                            echo '<li class="active"><strong>Edit</strong></li>';
                        }else{
                            echo '<li class="active"><strong>Add New</strong></li>';
                        }
                        ?>
                        
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

                        <div class="form-group"><label class="col-sm-2 control-label">Code</label>

                            <div class="col-sm-10"><input type="text" class="form-control" name="code" id="code" value="<?php echo $code; ?>"></div>
                        </div>

                        <div class="hr-line-dashed"></div>

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
                                    $previewImage = $p_image == ""?"":"/uploads/images/".$p_image;
                                ?>
                                <a class="fancybox" id="preview_container" href="<?php echo $previewImage;?>" title="Picture 1">
                                    <img alt="image" id="preview" src="<?php echo $previewImage;?>">
                                </a>
                                <button type="button" class="btn btn-default btn-sm" id="reset_image" style="display:none;">Reset image</button>
                                <input type="file" class="file" name="image" id="image" accept="image/*">
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>
                        <div class="form-group"><label class="col-sm-2 control-label">Category</label>
                            <div class="col-sm-10">
                                <select class="form-control m-b" name="category">
                                    <?php 
                                        while($row = $categoryList->fetch_assoc()) {
                                            $selectedOption = $row['id'] == $category ?"selected":"";
                                    ?>
                                    <option value="<?php echo $row['id'] ?>" <?php echo $selectedOption ?>><?php echo $row['title']?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>
                        <div class="form-group"><label class="col-sm-2 control-label">Price</label>

                            <div class="col-sm-10"><input type="text" class="form-control" name="price" id="price" value="<?php echo $price; ?>"></div>
                        </div>

                        <div class="hr-line-dashed"></div>
                        <div class="form-group"><label class="col-sm-2 control-label">Sort description</label>
                            <div class="col-sm-10">
                                <div class="">
                                    <textarea class="" name="sort_description" id="sort_description" style="width:100%;"> <?php echo $p_sort_des; ?></textarea>
                                    <span class="help-block m-b-none">will show when hover in thumbnail.</span>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group"><label class="col-sm-2 control-label">Description</label>

                            <div class="col-sm-10">
                                <textarea class="ckeditor" name="description" id="description"> <?php echo $p_des; ?></textarea>
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>
                        <div class="form-group"><label class="col-sm-2 control-label">Active</label>

                            <div class="col-sm-10">
                                <div class="checkbox"><label> <input type="checkbox" value="1" name="active"  <?php if($is_active == 1){echo "checked";}; ?>> Turn on or off the product.</label></div>
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
                                <input type="hidden" id="backlink" name="backlink" value="<?php echo $backlink;?>">
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
                <?php $urlID = empty($id)?"":"?obj=product&id=".$id ?>
                var urlID = <?php echo json_encode($urlID); ?>;
                $("#category_form").validate({
                    rules: {
                        title: {
                            required: true
                        },
                        code: {
                            required: true,
                            remote: {
                                url: '/admin/validator_code.php'+urlID,
                                type: 'POST',
                                async: false
                            }
                        },
                        slug: {
                            required: true,
                            remote: {
                                url: '/admin/validator_slug.php'+urlID,
                                type: 'POST',
                                async: false
                            }
                        },
                        sort_description: {
                            required: true
                        },
                        description: {
                            required: true
                        },
                        price: {
                            number: true
                        },
                        seo_title: {
                            required: true
                        }
                    },
                    messages: {
                        slug: {
                            remote: 'Slug đã được sử dụng .'
                        },
                        code: {
                            remote: 'Code đã được sử dụng .'
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
