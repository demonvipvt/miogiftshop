<?php 
	$slugKey = "home";
	if(isset($_GET['slug'])){
		$slugKey = $_GET['slug'] ;
	}
	include("config/connect.php");
	include("model/Slug.php");

	$slugObj = new Slug($conn);
	$getpage = $slugObj->getPageInfomation($slugKey);
	if( $getpage["success"] ){
		include_once("model/category.php");
		$includeController = "controller/".$getpage["data"]["object"].".php";
		$id = $getpage["data"]["object_id"] ;
		include($includeController);
	}else{
		include("403.php");
	}

	$conn->close();
?>