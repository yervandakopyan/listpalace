<?

class admin extends cabstract { 
	
	public $ADMIN=admin_users;
	
	function getAdminUsers(array $args) {
		try {
			$sql="select * from {$this->ADMIN} where ";
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

}


?>