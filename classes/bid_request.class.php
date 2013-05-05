<?

class bid_request extends cabstract { 

	public $TBL_IMG = bid_request_images;
	public $TBL_BID = bid_request;
	
	function add_images(array $args) {
		$sql = "INSERT INTO " .$this->TBL_IMG . " ".$this->insert($args).";";
		$prepare = $this->db->prepare($sql);
		$prepare->execute($this->bind($args));
		$image_id =  array($this->db->lastInsertId());
		return $image_id;
    }
	
	function add_bid(array $args) {
		$sql = "INSERT INTO " .$this->TBL_BID . " ".$this->insert($args).";";
		$prepare = $this->db->prepare($sql);
		$prepare->execute($this->bind($args));
		return $this->db->lastInsertId();
	
    }
	
	public function getBids(array $args) {
		$sql="select * from {$this->TBL_BID} where ";
		$sql .= $this->sql_select($args);
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		while ($query = $stmt->fetchAll(PDO::FETCH_ASSOC)){
			return $query;
			
		}
    }	
	
	public function getBidImages(array $args) {
		$sql="select * from {$this->TBL_IMG} where ";
		$sql .= $this->sql_select($args);
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		while ($query = $stmt->fetchAll(PDO::FETCH_ASSOC)){
			return $query;
			
		}
    }

	public function getBidInfoWithImage($bid_args) {
		$sql = "select a.*, b.job_image_1, b.job_image_2, b.job_image_3, b.job_image_4, b.job_image_5, b.job_image_6,
		 		b.video_link
				from {$this->TBL_BID} a
				join {$this->TBL_IMG} b on (b.id_bid_request = a.id_bid_request) where a.is_enabled='1'";
		if(!empty($bid_args)) {
			$sql .= " and " . $this->sql_select($bid_args);
		}

		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		
		while ($query = $stmt->fetchAll(PDO::FETCH_ASSOC)){
			return $query;
		}
	
	}
	
	public function getBidsInfo(array $args) {
	    $sql = "select a.id_bid_request,a.city,a.state,a.zipcode,a.job_sq_ft, a.description, a.cdate, a.is_enabled, b.display_name as main_cat, c.sub_display_name as sub_cat
	    from {$this->TBL_BID} a 
	    join main_categories b on (a.id_main_category = b.id_main_category) 
	    join sub_categories c on (a.id_sub_category = c.id_sub_category) 
	    where ";
	    $sql .= $this->sql_select($args);
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		while ($query = $stmt->fetchAll(PDO::FETCH_ASSOC)){
			return $query;
			
		}
	}
}