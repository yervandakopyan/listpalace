<?

abstract class cabstract  {


	public function __construct() {
        try {
			$dbh = new PDO($this->host, $this->user, $this->password);
			$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
			$this->db=$dbh;
		} catch (PDOException $e) {
			echo 'Connection failed: ' . $e->getMessage();
		}
	
	}
	
	function insert($keyValue){ 
		if(is_array($keyValue)){
			foreach($keyValue as $key => $value){
				$fields[] = '`'.$key.'`';
				$values[] = ':'.$key;
			}

			return '('.implode(' , ',$fields).') VALUES '.'('.implode(' , ',$values).')';
		}
		return '';
	}
	
	
	// Change the key to be :key to stop injections
	function bind($keyValue){
		if(is_array($keyValue)){
			foreach($keyValue as $key => $value){
				if(is_array($value)){ // if the value is array, lets assume I want an OR statement.
					$count = -1;
					foreach($value as $sValue){
						$count++;
						$where[':'.$key.$count] = $sValue;
					}
				} else {
					$where[':'.$key] = $value;
				}
			}
			return $where;
		}
		return array();
		
	}
	
	/**
	 * @param array for WHERE clause
	 * @return string
	 */
	function sql_select(array $pair){
	  //just init cond array:
		$condition = array(); 
		
		//added to have any values that are empty to be removed
		foreach($pair as $k=>$v) {
			if(empty($v)) {
				unset($pair[$k]);
			}
		}
			
		foreach ( $pair as $key => $value){
			//oh yeah, you can also automatically prevent SQL injection  
			$value=$this->db->quote($value);
			$condition[] = $key  . " = " . $value;
		} 
		
		 //Prepare for WHERE clause: 
		 $condition = join(' AND ', $condition);
		 //Return prepared string:
		 return $condition;
	}


}



?>