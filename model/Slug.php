<?php

class Slug {
	public $conn;
	
	public function __construct($conn)  
    {  
        $this->conn = $conn;
    } 
	public function getPageInfomation($slug)
	{
		$result = array("success"=>false,"data"=>array() );
		$sql = "SELECT id, slug, object, object_id FROM slug Where slug = '$slug'";
		$sqlqueryresult = $this->conn->query($sql);
		if ($sqlqueryresult->num_rows == 1) {
			$slugObject = $sqlqueryresult->fetch_assoc() ;
			$result["success"] = true ;
			$result["data"] = $slugObject ;
		}
		return $result;
	}
	
}

?>