<?php

class ctrPages extends MX_Controller{

	function __construct(){
		parent::__construct();
		$this->load->model('login/mdllogin');
		date_default_timezone_set('America/Mexico_City');
		include FILE_ROUTE_FULL."addons/phpexcel/Classes/PHPExcel.php";
		include FILE_ROUTE_FULL."addons/phpexcel/Classes/PHPExcel/Writer/Excel2007.php";
		include FILE_ROUTE_FULL."addons/phpexcel/Classes/PHPExcel/IOFactory.php";
	}

	public function view_log_history () {
		if (Modules::run('home/ctrtools/check_user_permission', 'allow_log')) {
			$data["controller"] = Modules::run('home/ctrtools/get_self');
			$data["js_files"] = array('js/controllers/log_activity.js');
			print $this->load->view("vwheader", $data, TRUE);
			print $this->load->view("vwaside", $data, TRUE);
			print $this->load->view("actividad/vwmain", $data, TRUE);
			print $this->load->view("vwfooter_js", $data, TRUE);
		} else redirect(base_url());
	}

	public function editar_ingresos(){
		#if($this->check_user_permission("allow_ingresos")){
		if(array_key_exists("rc",$_GET)){
			$this->mdllogin->editData(array("active"=>1),"releases",1,"id");
		}
		$rc=$this->get_data("*","releases","id=1");
		if(intval($rc[0]["active"])>0){
			$data["controller"]=$this;
			print $this->load->view("vwheader",$data,TRUE);
			print $this->load->view("vwaside",$data,TRUE);
			print $this->load->view("pages/vwformulario_main_ingresos",$data,TRUE);
			print $this->load->view("vwfooter",$data,TRUE);
			print $this->load->view("pages/vwsidebar_ingresos",$data,TRUE);
		}else{
			echo "Under construction";
		}
		#}else redirect(base_url());
	}

	public function change_fecha_ingresos(){
		$expedientes_datos_id=$this->security->xss_clean($this->input->post("expedientes_datos_id"));
		$fecha=$this->security->xss_clean($this->input->post("fecha"));
		$result=$this->get_data("id","ingresos","expedientes_datos_id='$expedientes_datos_id'");
		$s=TRUE;
		if($result!==FALSE){
			$this->mdllogin->editData(array("date_start"=>$fecha),"ingresos",intval($expedientes_datos_id),"expedientes_datos_id");
		}else{
			$temp=array(
				"expedientes_datos_id"	=> $expedientes_datos_id,
				"date_start"			=> $fecha
			);
			$id_temp=$this->mdllogin->insertData($temp,"ingresos");
			if(intval($id_temp)>0) $s=TRUE; else $s=FALSE;
		}
		print json_encode(array("success"=>$s));
	}

	public function change_ingresos(){
		$expedientes_datos_id=$this->security->xss_clean($this->input->post("expedientes_datos_id"));
		$estatus=$this->security->xss_clean($this->input->post("estatus"));
		$costo=$this->security->xss_clean($this->input->post("costo"));
		$s=TRUE;
		if(intval($estatus)>0){
			if($this->get_data("id","ingresos","expedientes_datos_id='$expedientes_datos_id'")===FALSE){
				$temp=array(
					"total"					=> $costo,
					"expedientes_datos_id"	=> $expedientes_datos_id,
					"pagado"				=> 1
				);
				$id_temp=$this->mdllogin->insertData($temp,"ingresos");
			}else{
				$this->mdllogin->editData(array("pagado"=>1),"ingresos",intval($expedientes_datos_id),"expedientes_datos_id");
			}
		}else{
			$this->mdllogin->editData(array("pagado"=>0),"ingresos",intval($expedientes_datos_id),"expedientes_datos_id");
		}
		print json_encode(array("success"=>$s));
	}

	public function get_reporte_ingresos(){
		$id=14;
		$fecha_inicio=$this->security->xss_clean($this->input->post("fecha_inicio"));
		$fecha_fin=$this->security->xss_clean($this->input->post("fecha_fin"));

		$w="";
		if(strlen($fecha_inicio)>0 && strlen($fecha_fin)===0)
			$w=" AND id IN (SELECT DISTINCT expedientes_datos_id FROM ingresos WHERE date_start >= '$fecha_inicio') ";
		elseif(strlen($fecha_inicio)===0 && strlen($fecha_fin)>0)
			$w=" AND id IN (SELECT DISTINCT expedientes_datos_id FROM ingresos WHERE date_start <= '$fecha_fin') ";
		elseif(strlen($fecha_inicio)>0 && strlen($fecha_fin)>0)
			$w=" AND id IN (SELECT DISTINCT expedientes_datos_id FROM ingresos WHERE date_start BETWEEN '$fecha_inicio' AND '$fecha_fin') ";

		$data=$this->get_data_from_query("SELECT ppal.* FROM expedientes AS ppal WHERE ppal.id='$id'; ")[0];
		$data_titulos=$this->get_data("*","campos_expedientes","expedientes_id='14' AND is_secretcode='0' AND (id=170 OR id=188)","campos_bloques_id, orden");
		$query="SELECT * FROM expedientes_datos WHERE expedientes_id='".$data["id"]."' AND id IN (SELECT DISTINCT expedientes_datos_id FROM campos_datos) $w ORDER BY date_time;";
		$data_campos=$this->get_data_from_query($query);
		$objPHPExcel = PHPExcel_IOFactory::load(FILE_ROUTE_FULL."files/reporte_completo.xlsx");
		$name="Reporte_Ingresos.xlsx";
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->SetCellValue("A4", "INGRESOS");
		$s=6;
		$a='A';
		if($data_titulos!==FALSE){
			foreach($data_titulos AS $t=>$tit){
				if(intval($tit["id"])!==188){
					$objPHPExcel->getActiveSheet()->getColumnDimension($a)->setAutoSize(true);
					$objPHPExcel->getActiveSheet()->SetCellValue(strval($a++).strval($s), $tit["nombre"]);
				}
			}
			$objPHPExcel->getActiveSheet()->getColumnDimension($a)->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->SetCellValue(strval($a++).strval($s), "Costo total");
			$objPHPExcel->getActiveSheet()->getColumnDimension($a)->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->SetCellValue(strval($a++).strval($s), "Método de Pago");
			$objPHPExcel->getActiveSheet()->getColumnDimension($a)->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->SetCellValue(strval($a++).strval($s), "Terminal");
			$objPHPExcel->getActiveSheet()->getColumnDimension($a)->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->SetCellValue(strval($a++).strval($s), "¿Facturado?");
			$objPHPExcel->getActiveSheet()->getColumnDimension($a)->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->SetCellValue(strval($a++).strval($s), "Seguro");
			$objPHPExcel->getActiveSheet()->getColumnDimension($a)->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->SetCellValue(strval($a++).strval($s), "Fecha de Pago");
			$objPHPExcel->getActiveSheet()->getColumnDimension($a)->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->SetCellValue(strval($a++).strval($s), "Estatus");
			$styleArray=array('font' => array('bold' => true,'color' => array('rgb'=>'3c3c3c'),'size' => 12,'name' => 'Arial'));
			$objPHPExcel->getActiveSheet()->getStyle("A6:".$a."6")->applyFromArray($styleArray);
			$s++;
			if($data_campos!==FALSE) foreach($data_campos AS $campo){
				$a='A';
				$costo_total=0;
				if($data_titulos!==FALSE) foreach($data_titulos AS $key){
					$data_temp=$this->get_data("COALESCE(COALESCE((SELECT opcion_nombre FROM cat_opciones WHERE id=ppal.dato_int), ppal.dato_var, ppal.dato_text, ppal.dato_date, ppal.dato_time),(SELECT COALESCE(det.dato_int, det.dato_var, det.dato_text, det.dato_date, det.dato_time) FROM campos_datos AS det WHERE det.id=ppal.dato_int)) AS campo_dato","campos_datos AS ppal","ppal.expedientes_datos_id='".$campo["id"]."' AND ppal.campos_expedientes_id='".$key["id"]."'");
					if(intval($key["cat_campos_id"])===8){
						$data_bitacora=$this->get_data_from_query("SELECT ppal.*, det.dato_var, (SELECT dato_var FROM campos_datos WHERE expedientes_datos_id=det.expedientes_datos_id AND campos_expedientes_id=180) AS precio FROM bitacora_detalles AS ppal INNER JOIN campos_datos AS det ON ppal.id_inventario=det.id WHERE ppal.bitacora_id='".$data_temp[0]["campo_dato"]."';");
						if($data_bitacora!==FALSE){
							$r="";
							foreach($data_bitacora AS $b => $bit){
								if($r!="")$r.=" || ";
								$r.=trim($bit["cantidad"]."x".$bit["dato_var"]);
								$costo_total+=(floatval($bit["precio"])*intval($bit["cantidad"]));
							}
						}
						#$objPHPExcel->getActiveSheet()->SetCellValue(strval($a++).strval($s), $r);
					}else{
						if($data_temp!==FALSE) $objPHPExcel->getActiveSheet()->SetCellValue(strval($a++).strval($s), $data_temp[0]["campo_dato"]);
						else $a++;
					}
				}

				$objPHPExcel->getActiveSheet()->SetCellValue(strval($a).strval($s), $costo_total);
				$objPHPExcel->getActiveSheet()->getStyle(strval($a++).strval($s))->getNumberFormat()->setFormatCode("$#,##0.00");

				$query="SELECT COALESCE(
						COALESCE((SELECT opcion_nombre FROM cat_opciones WHERE id=ppal.dato_int), ppal.dato_var, ppal.dato_text, ppal.dato_date, ppal.dato_time),
						(SELECT COALESCE(det.dato_int, det.dato_var, det.dato_text, det.dato_date, det.dato_time) FROM campos_datos AS det WHERE det.id=ppal.dato_int)) AS campo_dato
						FROM campos_datos AS ppal
						WHERE ppal.expedientes_datos_id=(SELECT expedientes_datos_id FROM campos_datos WHERE expedientes_id='13' AND campos_expedientes_id='168' AND dato_int=(SELECT dato_int FROM campos_datos WHERE expedientes_datos_id='".$campo["id"]."' AND campos_expedientes_id='170') LIMIT 1) AND ppal.campos_expedientes_id='169';";
				$data_metodo=$this->get_data_from_query($query);
				if($data_metodo!==FALSE) $objPHPExcel->getActiveSheet()->SetCellValue(strval($a++).strval($s), $data_metodo[0]["campo_dato"]);
				else $objPHPExcel->getActiveSheet()->SetCellValue(strval($a++).strval($s), "");

				$query="SELECT COALESCE(
						COALESCE((SELECT opcion_nombre FROM cat_opciones WHERE id=ppal.dato_int), ppal.dato_var, ppal.dato_text, ppal.dato_date, ppal.dato_time),
						(SELECT COALESCE(det.dato_int, det.dato_var, det.dato_text, det.dato_date, det.dato_time) FROM campos_datos AS det WHERE det.id=ppal.dato_int)) AS campo_dato
						FROM campos_datos AS ppal
						WHERE ppal.expedientes_datos_id=(SELECT expedientes_datos_id FROM campos_datos WHERE expedientes_id='13' AND campos_expedientes_id='168' AND dato_int=(SELECT dato_int FROM campos_datos WHERE expedientes_datos_id='".$campo["id"]."' AND campos_expedientes_id='170') LIMIT 1) AND ppal.campos_expedientes_id='194';";
				$data_metodo=$this->get_data_from_query($query);
				if($data_metodo!==FALSE) $objPHPExcel->getActiveSheet()->SetCellValue(strval($a++).strval($s), $data_metodo[0]["campo_dato"]);
				else $objPHPExcel->getActiveSheet()->SetCellValue(strval($a++).strval($s), "");

				$query="SELECT COALESCE(
						COALESCE((SELECT opcion_nombre FROM cat_opciones WHERE id=ppal.dato_int), ppal.dato_var, ppal.dato_text, ppal.dato_date, ppal.dato_time),
						(SELECT COALESCE(det.dato_int, det.dato_var, det.dato_text, det.dato_date, det.dato_time) FROM campos_datos AS det WHERE det.id=ppal.dato_int)) AS campo_dato
						FROM campos_datos AS ppal
						WHERE ppal.expedientes_datos_id=(SELECT expedientes_datos_id FROM campos_datos WHERE expedientes_id='14' AND campos_expedientes_id='170' AND dato_int=(SELECT dato_int FROM campos_datos WHERE expedientes_datos_id='".$campo["id"]."' AND campos_expedientes_id='170') LIMIT 1) AND ppal.campos_expedientes_id='196';";
				$data_metodo=$this->get_data_from_query($query);
				if($data_metodo!==FALSE) $objPHPExcel->getActiveSheet()->SetCellValue(strval($a++).strval($s), $data_metodo[0]["campo_dato"]);
				else $objPHPExcel->getActiveSheet()->SetCellValue(strval($a++).strval($s), "");

				$query="SELECT COALESCE(
						COALESCE((SELECT opcion_nombre FROM cat_opciones WHERE id=ppal.dato_int), ppal.dato_var, ppal.dato_text, ppal.dato_date, ppal.dato_time),
						(SELECT COALESCE(det.dato_int, det.dato_var, det.dato_text, det.dato_date, det.dato_time) FROM campos_datos AS det WHERE det.id=ppal.dato_int)) AS campo_dato
						FROM campos_datos AS ppal
						WHERE ppal.expedientes_datos_id=(SELECT expedientes_datos_id FROM campos_datos WHERE expedientes_id='14' AND campos_expedientes_id='170' AND dato_int=(SELECT dato_int FROM campos_datos WHERE expedientes_datos_id='".$campo["id"]."' AND campos_expedientes_id='170') LIMIT 1) AND ppal.campos_expedientes_id='193';";
				$data_metodo=$this->get_data_from_query($query);
				if($data_metodo!==FALSE) $objPHPExcel->getActiveSheet()->SetCellValue(strval($a++).strval($s), $data_metodo[0]["campo_dato"]);
				else $objPHPExcel->getActiveSheet()->SetCellValue(strval($a++).strval($s), "");

				$ingreso=$this->get_data("*","ingresos","expedientes_datos_id='".$campo["id"]."'");
				if($ingreso!==FALSE){
					$objPHPExcel->getActiveSheet()->SetCellValue(strval($a++).strval($s), $ingreso[0]["date_start"]);

					if(intval($ingreso[0]["pagado"])>0) $objPHPExcel->getActiveSheet()->SetCellValue(strval($a++).strval($s++), "PAGADO");
					else $objPHPExcel->getActiveSheet()->SetCellValue(strval($a++).strval($s++), "NO PAGADO");
				}else{
					$objPHPExcel->getActiveSheet()->SetCellValue(strval($a++).strval($s), "");
					$objPHPExcel->getActiveSheet()->SetCellValue(strval($a++).strval($s++), "NO PAGADO");
				}
			}
		}
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		$objWriter->save(str_replace(__FILE__,FILE_ROUTE_FULL.'files/'.$name,__FILE__));
		print json_encode(array("success"=>TRUE,"ruta"=>$name));
	}

	public function index(){
		redirect('home');
	}

	public function get_self(){return $this;}

	public function insert_data($data,$table){
		return $this->mdllogin->insertData($data,$table);
	}

	public function get_data($select="",$from="",$where="",$order="",$group="",$limit=""){
		return $this->mdllogin->getData($select,$from,$where,$order,$group,$limit);
	}

	public function get_data_from_query($query){
		return $this->mdllogin->getDataFromQuery($query);
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

}

?>