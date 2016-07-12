<?php 
    $category = new Category();

	$categories = $category->getCategoryOnNavigation();
	$pagecontent = 'view/index.php';
	$seo_title = "QUÀ TẶNG GỖ: Nhà tăm tre,danbo gỗ,thiệp sinh nhật,album ảnh,scrapbook";
	$seo_description = "CÔNG TY CUNG CẤP nhà tăm tre đẹp tphcm,  phụ kiện làm nhà tăm, danbo gỗ, thiệp sinh nhật, album ảnh đẹp, scrapbook đẹp, khung ảnh đẹp, khung ảnh tphcm, quà sinh nhật, quà lưu niệm, quà valentine, quà tặng bạn trai, quà tặng bạn gái, quà noel, quà giáng sinh, quà 20/10 , quà 8/3, quà tết, ";
	$seo_tags = "nhà tăm tre, nhà tăm tre tphcm, phụ kiện làm nhà tăm, danbo gỗ, danbo gỗ tphcm thiệp sinh nhật, album ảnh đẹp, scrapbook đẹp, khung ảnh đẹp, khung ảnh tphcm, quà sinh nhật, quà lưu niệm, quà valentine, quà tặng bạn trai, quà tặng bạn gái, quà noel, quà giáng sinh, quà 20/10 , quà 8/3, quà tết, ";
	include('layouts/main.php');

?>