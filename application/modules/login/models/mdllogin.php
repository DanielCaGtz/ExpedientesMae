<?php

class MdlLogin extends CI_Model{
	
	function __construct(){
		parent::__construct();
	}

	public function check_login($email,$pwd){
		$pwd=hash('sha512',$pwd);
		$result=$this->db->query("SELECT id, username, date_start from users where username='$email' and pwd='$pwd';");
		return $result->num_rows()>0?$result->result_array():FALSE;
	}

	public function check_login_with_code($pwd){
		$result=$this->db->query("SELECT id, expedientes_datos_id, dato_var, date_start, 'visitor' AS username from campos_datos where campos_expedientes_id=191 AND dato_var='$pwd';");
		return $result->num_rows()>0?$result->result_array():FALSE;
	}

	public function check_login_by_id($id){
		$result=$this->db->query("SELECT * from users where id='$id';");
		return $result->num_rows()>0?$result->result_array():FALSE;
	}

	public function check_email_existence($email){
		$result=$this->db->query("SELECT id from users where email='$email';");
		return $result->num_rows()>0?$result->result_array():FALSE;
	}

	public function check_if_name_exists($name){
		$query="SELECT ppal.id, ppal.expedientes_datos_id FROM campos_datos AS ppal WHERE ppal.expedientes_id=1 AND ppal.campos_expedientes_id=1 AND LOWER(dato_var)=LOWER('$name'); ";
		$result=$this->db->query($query);
		return $result->num_rows()>0?$result->result_array():FALSE;
	}

	public function get_code_from_usr($expedientes_datos_id){
		$result=$this->db->query("SELECT dato_var FROM campos_datos WHERE campos_expedientes_id=191 AND expedientes_datos_id='$expedientes_datos_id';");
		return $result->num_rows()>0?$result->result_array():FALSE;
	}

	public function getData($select,$from,$where,$order,$group,$limit){
		$query="SELECT $select FROM $from ";
		if($where!=="") $query.="WHERE $where ";
		if($group!=="") $query.="GROUP BY $group ";
		if($order!=="") $query.="ORDER BY $order ";
		if($limit!=="") $query.="LIMIT $limit ";
		$result=$this->db->query($query);
		return $result->num_rows()>0?$result->result_array():FALSE;
	}

	public function getDataFromQuery($query){
		$result=$this->db->query($query);
		return $result->num_rows()>0?$result->result_array():FALSE;
	}

	public function insertDataBatch($datos,$tabla){
		$this->db->insert_batch($tabla,$datos);
		return $this->db->insert_id()>0 ? $this->db->insert_id() : FALSE;
	}

	public function insertData($datos,$tabla){
		$this->db->insert($tabla,$datos);
		return $this->db->insert_id()>0 ? $this->db->insert_id() : FALSE;
	}

	public function editData($data,$table,$id,$idName,$where=";"){
		$consulta="UPDATE $table ";
		$set=FALSE;
		foreach($data AS $e=>$key){
			if($key!=="false"){
				if(!$set){
					$consulta.=" SET ";
					$set=TRUE;
				}else{
					$consulta.=" , ";
				}
				if($key!=="NULL") $consulta.="`$e` = '$key' ";
				else $consulta.="`$e` = NULL ";
			}
		}
		$consulta.=" WHERE $idName = $id ".$where;
		$resultado=$this->db->query($consulta);
		if(strpos((string)$this->db->conn_id->info,"Rows matched: 0")===FALSE)
			return TRUE;
		else
			return FALSE;
	}

	public function deleteData($table,$id,$idName){
		$this->db->where("$idName",$id);
		$this->db->delete($table);
		return $this->db->affected_rows()>0 ? TRUE : FALSE;
	}

	public function deleteDataIfExists($table,$id,$idName){
		$existence=$this->db->query("SELECT 1 FROM $table WHERE $idName = '$id' LIMIT 1;");
		if($existence->num_rows()>0){
			$this->db->where($idName,$id);
			$this->db->delete($table);
			return $this->db->affected_rows()>0 ? TRUE : FALSE;
		} else return TRUE;
	}

}

?>