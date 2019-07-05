<?php

class ctrReportes extends MX_Controller{

	function __construct(){
		parent::__construct();
		$this->load->model('login/mdllogin');
		date_default_timezone_set('America/Mexico_City');
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		include FILE_ROUTE_FULL."addons/phpexcel/Classes/PHPExcel.php";
		include FILE_ROUTE_FULL."addons/phpexcel/Classes/PHPExcel/Writer/Excel2007.php";
		include FILE_ROUTE_FULL."addons/phpexcel/Classes/PHPExcel/IOFactory.php";
	}

	public function cargar_reportes(){
		if($this->session->userdata("id")){
			$data["controller"]=$this;
			print $this->load->view("vwheader",$data,TRUE);
			print $this->load->view("vwaside",$data,TRUE);
			print $this->load->view("reportes/vwcarga",$data,TRUE);
			print $this->load->view("vwfooter",$data,TRUE);
			print $this->load->view("reportes/vwsidebar",$data,TRUE);
		}else redirect(base_url());
	}

	public function save_new_reporte(){
		$ruta=$this->security->xss_clean($this->input->post("ruta"));
		$expedientes_id=$this->security->xss_clean($this->input->post("expedientes_id"));
		$nombre=$this->security->xss_clean($this->input->post("nombre"));
		$titulo=$this->security->xss_clean($this->input->post("titulo"));
		$lis=$this->security->xss_clean($this->input->post("lis"));
		$max_id=$this->get_data("MAX(formatos_id) AS max_id","formatos_adicionales");
		$max_id=$max_id[0];$max_id=intval($max_id["max_id"])+1;
		$temp=array(
			"expedientes_id"	=> $expedientes_id,
			"formatos_id"		=> $max_id,
			"ruta"				=> $ruta,
			"nombre"			=> $nombre,
			"titulo"			=> $titulo
		);
		$insert_id=$this->mdllogin->insertData($temp,"formatos_adicionales");
		foreach($lis AS $e => $key){
			$temp=array(
				"id_formato"			=> $max_id,
				"expedientes_id"		=> $expedientes_id,
				"campos_expedientes_id"	=> $key[1],
				"celda"					=> $key[0],
				"is_id"					=> $key[2]=="true" ? 1 : 0,
				"first_letter"			=> $key[3]=="true" ? 1 : 0,
				"is_adicional"			=> 1
			);
			$temp=$this->mdllogin->insertData($temp,"formatos");
		}
		print json_encode(array("success"=>TRUE));
	}

	public function get_rows_fields(){
		$exp=$this->security->xss_clean($this->input->post("expediente"));
		$data=$this->get_data("id,nombre","campos_expedientes","expedientes_id='$exp'");
		$regresa='	<br clear="all"><div class="rows_container">
						<div style="float:left;margin-bottom: 30px;">
							<div class="box-body">
								<div class="form-group">
									<label>Celda</label>
									<input type="text" class="form-control get_this_cell" style="width:90px !important;">
								</div>
							</div>
						</div>
						<div style="float:right;margin-bottom: 30px;">
							<div class="box-body">
								<div class="form-group">
									<label>Celda</label>
									<select class="form-control get_this_field" style="width:220px !important;">';
									if($data!==FALSE){
										foreach($data AS $e => $key){
											$regresa.='<option value="'.$key["id"].'">'.$key["nombre"].'</option>';
										}
									}
		$regresa.='					</select>
								</div>
							</div>
						</div>
						<div style="float:right;margin-bottom: 30px;">
							<div class="box-body">
								<div class="form-group">
									<div class="checkbox">
										<label>
											<input type="checkbox" class="if_is_id"> ¿Usa ID?
										</label>
									</div>
									<div class="checkbox">
										<label>
											<input type="checkbox" class="if_is_first_letter"> ¿Solo primera letra?
										</label>
									</div>
								</div>
							</div>
						</div>
					</div>';
		print $regresa;
	}

	public function check_user_permission($permission){
		$data=$this->get_data($permission,"users","id='".$this->session->userdata('id')."'");
		if($data!==FALSE){
			$data=$data[0];
			$data=$data[$permission];
			if(intval($data)>0) return TRUE;
			else return FALSE;
		}else return FALSE;
	}

	public function get_data_from_query($query){return $this->mdllogin->getDataFromQuery($query);}
	public function get_data($select="",$from="",$where="",$order="",$group="",$limit=""){return $this->mdllogin->getData($select,$from,$where,$order,$group,$limit);}

}

?>