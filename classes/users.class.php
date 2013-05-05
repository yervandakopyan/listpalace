<?

class users extends cabstract { 
	
	public $TBL_TYPE=user_types;
	public $TBL_USERS=users;
	public $TBL_USER_CON=user_contractor;
	public $TBL_USER_HOM=user_homeowner;
	


	function getUsersType($user_args) {
		$sql="select * from {$this->TBL_TYPE} where is_enabled='1' ";
		if(!empty($user_args)) {
			$sql .= " and " . $this->sql_select($user_args);
		}
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
		while ($query = $stmt->fetchAll(PDO::FETCH_ASSOC)){
			return $query;
		}
 
    }
	
	function add_user(array $user) {
		$sql = "INSERT INTO " .$this->TBL_USERS . " ".$this->insert($user).";";
		$prepare = $this->db->prepare($sql);
		$prepare->execute($this->bind($user));
		return $this->db->lastInsertId();	
	
    }
	
	function add_user_contractor(array $user_ext) {
		$sql = "INSERT INTO " .$this->TBL_USER_CON . " ".$this->insert($user_ext).";";
		$prepare = $this->db->prepare($sql);
		$prepare->execute($this->bind($user_ext));
		return $this->db->lastInsertId();
	}
	
	function add_user_homeowner(array $user_ext_home) {
		$sql = "INSERT INTO " .$this->TBL_USER_HOM . " ".$this->insert($user_ext_home).";";
		$prepare = $this->db->prepare($sql);
		$prepare->execute($this->bind($user_ext_home));
		return $this->db->lastInsertId();
	}
	
	function getUsers(array $args) {
		try {
			$sql="select * from {$this->TBL_USERS} where ";
			$sql .= $this->sql_select($args);
			$stmt = $this->db->prepare($sql);
			$stmt->execute();
			while ($query = $stmt->fetchAll(PDO::FETCH_ASSOC)){
				return $query;
				
			}
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
		
	}
	
	function updateUsers (array $args, $id_user) {
		$sql = "update {$this->TBL_USERS} set ";
		$sql.=$this->sql_select($args);
		$sql.=" where id_user = '{$id_user}'";
		$stmt = $this->db->prepare($sql);
		$stmt->execute();
	}
	
}

?>