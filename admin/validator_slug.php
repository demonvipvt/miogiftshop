<?php 
include "connect.php";

$id  = empty( $_GET["id"] ) ?"":$_GET["id"];
$slug  = empty( $_POST["slug"] ) ?"":$_POST["slug"];
if($slug == ""){
	echo json_encode(false);
}else{
	if( $id == "" ){
		$sql = "SELECT id FROM slug WHERE slug = '".$slug."' ";
		$checking = $conn->query($sql);
		if($checking->num_rows > 0){
			echo json_encode(false);
		}else{
			echo json_encode(true);
		}
	}else{
		$sql = "SELECT id FROM slug WHERE `slug` = '".$slug."' AND NOT (`object` = 'category' AND `object_id` = ".$id.") ";
		$checking = $conn->query($sql);
		if($checking->num_rows > 0){
			echo json_encode(false);
		}else{
			echo json_encode(true);
		}
	}
}
?>