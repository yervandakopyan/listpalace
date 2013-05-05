<?

class category extends cabstract { 
	
	public $TBL_CAT=main_categories;
	public $TBL_SUB_CAT=sub_categories;
	public $TBL_CAT_ASSOC=categories_association;
	
	public function getCategories() {
		$sql = "select * from " .$this->TBL_CAT . " where is_enabled = '1'";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		
		while ($query = $stmt->fetchAll(PDO::FETCH_ASSOC)){
			return $query;
		}
	
	}
	
	public function getCategoryInfo(array $args) {
		$sql="select * from {$this->TBL_CAT} where ";
		$sql .= $this->sql_select($args);
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		while ($query = $stmt->fetchAll(PDO::FETCH_ASSOC)){
			return $query;
			
		}
    }
	
	public function getSubCategories() {
		$sql = "select * from " .$this->TBL_SUB_CAT . " where is_enabled = '1'";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		
		while ($query = $stmt->fetchAll(PDO::FETCH_ASSOC)){
			return $query;
		}
	}
	
	public function getSubCategoryDetails(array $args) {
		$sql="select * from {$this->TBL_SUB_CAT} where ";
		$sql .= $this->sql_select($args);
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		while ($query = $stmt->fetchAll(PDO::FETCH_ASSOC)){
			return $query;
			
		}
	}
	
	function add_category(array $args) {
		$sql = "INSERT INTO " .$this->TBL_CAT . " ".$this->insert($args).";";
		$prepare = $this->db->prepare($sql);
		$prepare->execute($this->bind($args));
    }
	
	function add_SubCategory(array $args) {
		$sql = "INSERT INTO " .$this->TBL_SUB_CAT . " ".$this->insert($args).";";
		$prepare = $this->db->prepare($sql);
		$prepare->execute($this->bind($args));
    }
	
	function delete_category(array $args) {
		$sql="Delete from {$this->TBL_CAT} where ";
		$sql .= $this->sql_select($args);
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
    }
	
	function delete_subCategory(array $args) {
		$sql="Delete from {$this->TBL_SUB_CAT} where ";
		$sql .= $this->sql_select($args);
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
    }
	
	function add_association(array $args) {
		$sql = "INSERT INTO {$this->TBL_CAT_ASSOC} ".$this->insert($args).";";
		$prepare = $this->db->prepare($sql);
		$prepare->execute($this->bind($args));
	}
	
	function get_CatAssociation(array $args) {
		$sql="select * from {$this->TBL_CAT_ASSOC} where ";
		$sql .= $this->sql_select($args);
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		while ($query = $stmt->fetchAll(PDO::FETCH_ASSOC)){
			return $query;
			
		}
	}
	
	function get_catAssociationDetails ($id_main_category) {
		$sql = "select a.id_sub_category, b.sub_display_name from {$this->TBL_CAT_ASSOC} a 
		join {$this->TBL_SUB_CAT} b on (a.id_sub_category = b.id_sub_category) where a.id_main_category = {$id_main_category} 
			and a.is_enabled and b.is_enabled";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		
		while ($query = $stmt->fetchAll(PDO::FETCH_ASSOC)){
			return $query;
		}
	}
	
	
		
}