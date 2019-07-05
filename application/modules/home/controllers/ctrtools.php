<?php

class ctrTools extends MX_Controller{

	function __construct(){
		parent::__construct();
		$this->load->model('login/mdllogin');
		date_default_timezone_set('America/Mexico_City');
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
  }

  public function check_user_permission ($permission) {
		$data = $this->get_data($permission, "users", "id='".$this->session->userdata('id')."'");
		if ($data !== FALSE) {
			$data = $data[0];
			$data = $data[$permission];
			return intval($data) > 0 ? TRUE : FALSE;
    }
    return FALSE;
  }

  public function get_self () {
    return $this;
  }
  
  public function insert_data ($data,$table) {
		return $this->mdllogin->insertData($data, $table);
	}

	public function get_data ($select = "", $from = "", $where = "", $order = "", $group = "", $limit = "") {
		return $this->mdllogin->getData($select, $from, $where, $order, $group, $limit);
	}

	public function get_data_from_query ($query) {
		return $this->mdllogin->getDataFromQuery($query);
	}

	public function closesession () {
		$this->session->sess_destroy();
		redirect(base_url());
	}

}

?>