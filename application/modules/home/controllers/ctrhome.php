<?php

class ctrHome extends MX_Controller{

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

	public function index(){
		if($this->session->userdata("id")){
			redirect('home');
		}else redirect(base_url());
	}

	public function home(){
		if($this->session->userdata("id")){
			$data["controller"]=$this;
			print $this->load->view("vwheader",$data,TRUE);
			print $this->load->view("vwaside",$data,TRUE);
			print $this->load->view("vwhome",$data,TRUE);
			print $this->load->view("vwfooter",$data,TRUE);
			print $this->load->view("vwsidebar",$data,TRUE);
		}else redirect(base_url());
	}

	public function abrir_formulario($id){
		$data["id_expediente"]=$id;
		$data["controller"]=$this;
		$data["expediente"]=$this->get_data("*","expedientes","id='$id'")[0];
		print $this->load->view("vwheader",$data,TRUE);
		print $this->load->view("vwaside",$data,TRUE);
		print $this->load->view("vwformulario",$data,TRUE);
		print $this->load->view("vwfooter",$data,TRUE);
		print $this->load->view("vwsidebar_formulario",$data,TRUE);
	}

	public function editar_campos_datos($id){
		$data["id_expedientes_datos"]=$id;
		$data["controller"]=$this;
		$temp=$this->get_data("*","expedientes_datos","id='$id'")[0];
		$data["id_expediente"]=$temp["expedientes_id"];
		$data["expediente"]=$this->get_data("*","expedientes","id='".$temp["expedientes_id"]."'")[0];
		print $this->load->view("vwheader",$data,TRUE);
		print $this->load->view("vwaside",$data,TRUE);
		print $this->load->view("vwformulario_editar",$data,TRUE);
		print $this->load->view("vwfooter",$data,TRUE);
		print $this->load->view("vwsidebar_formulario_editar",$data,TRUE);
	}

	public function abrir_datos_formulario($id){
		if(intval($id)===15) $s=$this->check_user_permission("allow_inventario");
		else $s=TRUE;
		if($s){
			$data["id_expediente"]=$id;
			$data["controller"]=$this;
			$data["expediente"]=$this->get_data("*","expedientes","id='$id'")[0];
			print $this->load->view("vwheader",$data,TRUE);
			print $this->load->view("vwaside",$data,TRUE);
			print $this->load->view("vwformulario_datos",$data,TRUE);
			print $this->load->view("vwfooter",$data,TRUE);
			print $this->load->view("vwsidebar_datos",$data,TRUE);
		}else redirect(base_url());
	}

	public function editor_expedientes(){
		$data["controller"]=$this;
		print $this->load->view("vwheader",$data,TRUE);
		print $this->load->view("vwaside",$data,TRUE);
		print $this->load->view("vweditor",$data,TRUE);
		print $this->load->view("vwfooter",$data,TRUE);
		print $this->load->view("vwsidebar",$data,TRUE);
	}

	public function editor_de_bloques(){
		$data["controller"]=$this;
		print $this->load->view("vwheader",$data,TRUE);
		print $this->load->view("vwaside",$data,TRUE);
		print $this->load->view("vweditor_bloques",$data,TRUE);
		print $this->load->view("vwfooter",$data,TRUE);
		print $this->load->view("vwsidebar_bloques",$data,TRUE);
	}

	public function editor_de_opciones(){
		$data["controller"]=$this;
		print $this->load->view("vwheader",$data,TRUE);
		print $this->load->view("vwaside",$data,TRUE);
		print $this->load->view("vweditor_opciones",$data,TRUE);
		print $this->load->view("vwfooter",$data,TRUE);
		print $this->load->view("vwsidebar_opciones",$data,TRUE);
	}

	public function editor_de_paquetes(){
		$data["controller"]=$this;
		print $this->load->view("vwheader",$data,TRUE);
		print $this->load->view("vwaside",$data,TRUE);
		print $this->load->view("vweditor_paquetes",$data,TRUE);
		print $this->load->view("vwfooter",$data,TRUE);
		print $this->load->view("vwsidebar_paquetes",$data,TRUE);
	}

	public function delete_paquete(){
		$id=$this->security->xss_clean($this->input->post("id"));
		$this->mdllogin->deleteData("paquetes_inventario",$id,"id");
		print json_encode(array("success"=>TRUE));
	}

	public function get_new_li(){
		$data['controller']=$this;
		$this->load->view("vwnewli",$data);
	}

	public function get_new_li_child(){
		$id=$this->security->xss_clean($this->input->post("id"));
		$data['controller']=$this;
		$data['id_main']=$id;
		$this->load->view("vwnewli_child",$data);
	}

	public function get_field($id, $exp=0, $code=0, $added=TRUE){
		if(intval($exp)>0 && $added) $where="AND campos.expedientes_datos_id='$exp'";
		else $where="";
		$query="SELECT ppal.*, det.nombre AS nombre_cat_campos, det.id AS id_cat_campos, det.descripcion AS descripcion_cat_campos,
			COALESCE(campos.dato_var, campos.dato_date, campos.dato_int, campos.dato_text, campos.dato_time) AS respuesta, COALESCE(campos.id) AS id_campos
			FROM campos_expedientes AS ppal INNER JOIN cat_campos AS det ON ppal.cat_campos_id=det.id
			LEFT JOIN campos_datos AS campos ON ppal.id=campos.campos_expedientes_id WHERE det.active=1 AND ppal.id='$id' $where ;";
		#echo $query;
		$data=$this->get_data_from_query($query);
		$result='';
		if($data!==FALSE){
			$data=$data[0];
			$temp_datatag='data-bloque="'.$data["campos_bloques_id"].'" data-id="'.$data["id"].'" data-campos="'.$data["cat_campos_id"].'"';
			if(intval($exp)>0) $temp_datatag.=' data-idcampos="'.$data["id_campos"].'" data-exp="'.$exp.'"';
			if(intval($data["required"])>0) $temp_datatag.=' required ';
			if(intval($code)>0) $data["nombre_cat_campos"]="SECRET";
			switch($data["nombre_cat_campos"]){
				case "SECRET":
					$result = '<input type="hidden" '.$temp_datatag.' data-type="varchar" class="campo_editable form-control" id="field_'.$id.'" value="'.$this->generateRandomString(8).'" placeholder="'.$data["nombre"].'">';
				break;
				case "VARCHAR":#1
					if(intval($exp)>0){
						$value_temp=$data["respuesta"];
						if(intval($data["is_inventario"])>0){
							$query_invent="SELECT COALESCE(cantidad,0) AS num FROM bitacora_detalles WHERE id_inventario = (SELECT id FROM campos_datos WHERE expedientes_datos_id='$exp' AND campos_expedientes_id='".$data["is_inventario"]."'); ";
							//echo $query_invent;
							$data_exist=$this->get_data_from_query($query_invent);
							if($data_exist!==FALSE){
								if(intval($data_exist[0]["num"])<=intval($value_temp))
									$value_temp -= intval($data_exist[0]["num"]);
							}
						}
						$temp_datatag.=' value="'.$value_temp.'" ';
					}
					$result = '<input type="text" '.$temp_datatag.' data-type="varchar" class="campo_editable form-control" id="field_'.$id.'" placeholder="'.$data["nombre"].'">';
				break;
				case "DATE":#2
					if(intval($exp)>0) $temp_datatag.=' value="'.$data["respuesta"].'" ';
					$result = '<input type="date" '.$temp_datatag.' data-type="date" class="campo_editable form-control" id="field_'.$id.'">';
				break;
				case "COMBO":#3
					$result = '<select '.$temp_datatag.' data-type="combo" class="campo_editable form-control" id="field_'.$id.'">';
					$result .= '<option value="0" selected>Elige una opción</option>';
					$temp=$this->get_data("*","cat_opciones","campos_expedientes_id='$id' AND active='1'","orden");
					if($temp!==FALSE){
						foreach($temp AS $e=>$key){
							$data_temp_opts="";
							if(strlen($key["campos_expedientes_opcional_id"])==0){
								if(intval($key["id"])===intval($data["respuesta"]) && intval($exp)>0) $data_temp_opts="selected";
								$result .= '<option '.$data_temp_opts.' value="'.$key["id"].'">'.$key["opcion_nombre"].'</option>';
							}else{
								if(intval($key["campos_expedientes_opcional_id"])===1 && $this->session->userdata("username")=="visitor")
									$data_opc=$this->get_data("id,COALESCE(dato_var, dato_date, dato_int, dato_text, dato_time) AS nombre","campos_datos","campos_expedientes_id='".$key["campos_expedientes_opcional_id"]."' AND expedientes_datos_id='".$this->session->userdata("expedientes_datos_id")."'","nombre");
								else
									$data_opc=$this->get_data("id,COALESCE(dato_var, dato_date, dato_int, dato_text, dato_time) AS nombre","campos_datos","campos_expedientes_id='".$key["campos_expedientes_opcional_id"]."'","nombre");
								if($data_opc!==FALSE){
									foreach($data_opc AS $op=>$opc){
										if(intval($opc["id"])===intval($data["respuesta"]) && intval($exp)>0) $data_temp_opts="selected";
										else $data_temp_opts="";
										$result .= '<option '.$data_temp_opts.' value="'.$opc["id"].'">'.$opc["nombre"].'</option>';
									}
								}
							}
						}
					}
					$result .= "</select>";
				break;
				case "TIME":#4
					if(intval($exp)>0) $temp_datatag.=' value="'.$data["respuesta"].'" ';
					$result = '<input type="time" '.$temp_datatag.' data-type="time" class="campo_editable form-control" id="field_'.$id.'">';
				break;
				case "TEXT":#5
					if(intval($exp)>0) $temp_respuesta=$data["respuesta"]; else $temp_respuesta="";
					$result = '<textarea '.$temp_datatag.' data-type="text" class="campo_editable form-control" rows="3" placeholder="'.$data["nombre"].'" id="field_'.$id.'">'.$temp_respuesta.'</textarea>';
				break;
				case "EMAIL":#6
					if(intval($exp)>0) $temp_datatag.=' value="'.$data["respuesta"].'" ';
					$result = '<input type="email" '.$temp_datatag.' data-type="email" class="campo_editable form-control" id="field_'.$id.'" placeholder="'.$data["nombre"].'">';
				break;
				case "RADIO":#7
					$temp_data=$this->get_data("*","cat_opciones","campos_expedientes_id='$id' AND active='1'");
					$result='<div '.$temp_datatag.' data-type="radio" class="campo_editable" data-id="field_'.$id.'">';
					if($temp_data!==FALSE){
						foreach($temp_data AS $e=>$key){
							$data_temp_opts="";
							if(intval($key["id"])===intval($data["respuesta"])) $data_temp_opts="checked";
							$result .= '<div class="radio"><label><input type="radio" '.$data_temp_opts.' value="'.$key["id"].'" name="field_'.$id.'" id="field_'.$id.'">'.$key["opcion_nombre"].'</label></div>';
						}
					}
					$result.='</div>';
				break;
				case "MULTI COMBO":
					$result = '<select class="form-control select_package" id="paquetes_selector">';
					$result .= '<option value="0">Sin paquete</option>';
					$temp=$this->get_data("*","paquetes_inventario","","nombre");
					if($temp!==FALSE){
						foreach($temp AS $e=>$key){
							$result .= '<option value="'.$key["id"].'">'.trim($key["nombre"]).'</option>';
						}
					}
					$result .= "</select>";

					$result .= '<select '.$temp_datatag.' data-type="multicombo" class="campo_editable multicombo form-control" id="field_'.$id.'">';
					$result .= '<option value="0">Elige una opción</option>';
					$temp=$this->get_data("*","cat_opciones","campos_expedientes_id='$id' AND active='1'","orden");
					if($temp!==FALSE){
						foreach($temp AS $e=>$key){
							$data_temp_opts="";
							if(strlen($key["campos_expedientes_opcional_id"])==0){
								foreach(explode(" ",$data["respuesta"]) AS $ee=>$keey){
									if(intval($key["id"])===intval($keey) && intval($exp)>0) $data_temp_opts="selected";
								}
								$result .= '<option '.$data_temp_opts.' value="'.$key["id"].'">'.trim($key["opcion_nombre"]).'</option>';
							}else{
								$data_opc=$this->get_data("id,COALESCE(dato_var, dato_date, dato_int, dato_text, dato_time) AS nombre","campos_datos","campos_expedientes_id='".$key["campos_expedientes_opcional_id"]."'");
								if($data_opc!==FALSE){
									foreach($data_opc AS $op=>$opc){
										$data_temp_opts="";
										foreach(explode(" ",$data["respuesta"]) AS $ee=>$keey){
											if(intval($opc["id"])===intval($keey) && intval($exp)>0) $data_temp_opts="selected";
										}
										$result .= '<option '.$data_temp_opts.' value="'.$opc["id"].'">'.$opc["nombre"].'</option>';
									}
								}
							}
						}
					}
					$result .= "</select><button type='button' class='btn btn-block btn-default btn-xs' id='agregar_inventario'>Agregar</button><div id='contenedor_inventario'>";
					if(intval($exp)>0){
						$data_temp_multi=$this->get_data_from_query("SELECT campos.dato_var, bit.id_inventario, bit.cantidad FROM bitacora_detalles AS bit INNER JOIN campos_datos AS campos ON bit.id_inventario=campos.id WHERE bit.bitacora_id=(SELECT dato_var FROM campos_datos WHERE expedientes_datos_id='$exp' AND cat_campos_id=8);");
						if($data_temp_multi!==FALSE){
							foreach($data_temp_multi AS $m=>$multi){
								$result .= "<p><label>".$multi["dato_var"]." <input type='text' style='width:40px;' data-id='".$multi["id_inventario"]."' class='cantidad_producto' placeholder='Cantidad' value='".$multi["cantidad"]."'><button type='button' class='btn btn-box-tool eliminar_inventario_elemento'><i class='fa fa-remove'></i></button></label></p>";
							}
						}
						//var temp="<label>"+name+" <input type='text' style='width:40px;' data-id='"+id+"' class='cantidad_producto' placeholder='Cantidad' value='1'></label><br>";
					}
					$result .= "</div>";
				break;
			}
		}else{
			$result = $this->get_field($id, $exp, 0, FALSE);
		}
		return $result;
	}

	public function get_paquete_detalle(){
		$id=$this->security->xss_clean($this->input->post("id"));
		$data=$this->get_data_from_query("SELECT * FROM paquetes_inventario_detalles WHERE paquetes_inventario_id='$id' ORDER BY nombre");
		if($data!==FALSE) print json_encode(array("success"=>TRUE, "result"=>$data));
		else print json_encode(array("success"=>FALSE));
	}

	public function consultar_campo_multiple(){
		$id=$this->security->xss_clean($this->input->post("id"));
		$data=$this->get_data_from_query("SELECT cat.*, exp.expedientes_id FROM cat_opciones AS cat LEFT JOIN campos_expedientes AS exp ON cat.campos_expedientes_opcional_id=exp.id WHERE cat.campos_expedientes_id='$id' ORDER BY orden");
		if($data!==FALSE) print json_encode(array("success"=>TRUE, "result"=>$data));
		else print json_encode(array("success"=>FALSE));
	}

	public function consulta_campos_por_expediente(){
		$id=$this->security->xss_clean($this->input->post("id"));
		$data=$this->get_data("id,nombre,cat_campos_id","campos_expedientes","expedientes_id='$id'","orden");
		if($data!==FALSE) print json_encode(array("success"=>TRUE, "result"=>$data));
		else print json_encode(array("success"=>FALSE));
	}

	public function consultar_bloques_por_expediente(){
		$id=$this->security->xss_clean($this->input->post("id"));
		$data=$this->get_data("*","campos_bloques","expedientes_id='$id'","orden");
		if($data!==FALSE) print json_encode(array("success"=>TRUE, "result"=>$data));
		else print json_encode(array("success"=>FALSE));
	}

	public function consult_bloque_to_delete(){
		$id=$this->security->xss_clean($this->input->post("id"));
		$data=$this->get_data("1","campos_expedientes","campos_bloques_id='$id'","","","1");
		if($data!==FALSE) print json_encode(array("result"=>FALSE));
		else print json_encode(array("result"=>TRUE));
	}

	public function consult_expediente_to_delete(){
		$id=$this->security->xss_clean($this->input->post("id"));
		$data=$this->get_data("1","campos_expedientes","expedientes_id='$id'","","","1");
		if($data!==FALSE) print json_encode(array("result"=>FALSE));
		else print json_encode(array("result"=>TRUE));
	}

	public function consult_campo_to_delete(){
		$id=$this->security->xss_clean($this->input->post("id"));
		$data=$this->get_data("1","cat_opciones","campos_expedientes_id='$id'","","","1");
		if($data!==FALSE) print json_encode(array("result"=>FALSE));
		else print json_encode(array("result"=>TRUE));
	}

	public function delete_opcion_multiple(){
		$id=$this->security->xss_clean($this->input->post("id"));
		$this->mdllogin->deleteData("cat_opciones",$id,"id");
		print json_encode(array("result"=>TRUE));
	}

	public function delete_expediente(){
		$id=$this->security->xss_clean($this->input->post("id"));
		$this->mdllogin->deleteData("campos_expedientes",$id,"expedientes_id");
		$this->mdllogin->deleteData("campos_bloques",$id,"expedientes_id");
		$this->mdllogin->deleteData("expedientes",$id,"id");
		print json_encode(array("result"=>TRUE));
	}

	public function delete_bloque(){
		$id=$this->security->xss_clean($this->input->post("id"));
		$this->mdllogin->deleteData("cat_opciones",$id,"campos_bloques_id");
		$this->mdllogin->deleteData("campos_expedientes",$id,"campos_bloques_id");
		$this->mdllogin->deleteData("campos_bloques",$id,"id");
		print json_encode(array("result"=>TRUE));
	}

	public function delete_campo(){
		$id=$this->security->xss_clean($this->input->post("id"));
		$this->mdllogin->deleteData("cat_opciones",$id,"campos_expedientes_id");
		$this->mdllogin->deleteData("campos_expedientes",$id,"id");
		print json_encode(array("result"=>TRUE));
	}

	public function eliminar_expedientes_datos(){
		$id=$this->security->xss_clean($this->input->post("id"));
		$this->mdllogin->deleteData("expedientes_datos",$id,"id");
		print json_encode(array("result"=>TRUE));
	}

	public function save_campos_multiples(){
		$data=$this->security->xss_clean($this->input->post("data"));
		$id=$this->security->xss_clean($this->input->post("id"));
		$s=TRUE;
		$ids=array();
		if(!empty($data)){
			foreach($data AS $e=>$key){
				if(intval($key[1])>0){
					$temp=array(
						"opcion_nombre"	=> $key[0],
						"orden"			=> ($e+1)
					);
					$this->mdllogin->editData($temp,"cat_opciones",intval($key[1]),"id");
					$ids[$e]=intval($key[1]);
				}else{
					$temp=array(
						"campos_expedientes_id"	=> $id,
						"opcion_nombre"			=> $key[0],
						"active"				=> 1,
						"orden"					=> ($e+1)
					);
					$id_temp=$this->mdllogin->insertData($temp,"cat_opciones");
					if($id_temp === FALSE) $id_temp=FALSE;
					$ids[$e]=intval($id_temp);
				}
			}
		}
		print json_encode(array("success"=>$s, "result"=>$ids));
	}

	public function save_campos_multiples_opc(){
		$id_opc=$this->security->xss_clean($this->input->post("id_opc"));
		$id=$this->security->xss_clean($this->input->post("id"));
		$id_campos=$this->security->xss_clean($this->input->post("id_campos"));
		$ids=0;
		$s=TRUE;
		if(intval($id_campos)>0){
			$temp=array(
				"campos_expedientes_opcional_id"=> $id_opc,
				"orden"							=> 1
			);
			$this->mdllogin->editData($temp,"cat_opciones",intval($id),"campos_expedientes_id");
			$ids=$id_campos;
		}else{
			$temp=array(
				"campos_expedientes_id"			=> $id,
				"campos_expedientes_opcional_id"=> $id_opc,
				"active"						=> 1,
				"orden"							=> 1
			);
			$id_temp=$this->mdllogin->insertData($temp,"cat_opciones");
			if($id_temp!==FALSE) $ids=$id_temp;
			else $s=FALSE;
		}
		print json_encode(array("success"=>$s, "result"=>$ids));
	}

	public function save_paquetes(){
		$data=$this->security->xss_clean($this->input->post("data"));
		$nombre=$this->security->xss_clean($this->input->post("nombre"));
		$s=FALSE;
		$id_inv=$this->mdllogin->insertData(array("nombre"=>$nombre,"date_start"=>date('Y-m-d H:i:s')),"paquetes_inventario");
		if($id_inv!==FALSE){
			$s=TRUE;
			foreach($data AS $e=>$key){
				$temp=array(
					"paquetes_inventario_id"	=> $id_inv,
					"id_inventario"				=> $key[1],
					"nombre"					=> $key[0]
				);
				$id_temp=$this->mdllogin->insertData($temp,"paquetes_inventario_detalles");
				if($id_temp===FALSE)  $s=FALSE;
			}
		}
		print json_encode(array("success"=>$s));
	}

	public function save_bloques_por_expediente(){
		$data=$this->security->xss_clean($this->input->post("data"));
		$id=$this->security->xss_clean($this->input->post("id"));
		$s=TRUE;
		$ids=array();
		if(!empty($data)){
			foreach($data AS $e=>$key){
				if(intval($key[1])>0){
					$temp=array(
						"nombre"		=> $key[0],
						"orden"			=> ($e+1)
					);
					$this->mdllogin->editData($temp,"campos_bloques",intval($key[1]),"id");
					$ids[$e]=intval($key[1]);
				}else{
					$temp=array(
						"expedientes_id"=> $id,
						"nombre"		=> $key[0],
						"color"			=> "primary",
						"orden"			=> ($e+1)
					);
					$id_temp=$this->mdllogin->insertData($temp,"campos_bloques");
					if($id_temp === FALSE) $s=FALSE;
					$ids[$e]=intval($id_temp);
				}
			}
		}
		print json_encode(array("success"=>$s, "result"=>$ids));
	}

	public function save_expedientes(){
		$data=$this->security->xss_clean($this->input->post("data"));
		$s=TRUE;
		$ids=array();
		if(!empty($data)){
			foreach($data AS $e=>$key){
				if(intval($key[1])>0){
					$temp=array(
						"titulo"		=> $key[0],
						"subtitulo"		=> $key[2],
						"color"			=> $key[3],
						"icon"			=> $key[4],
						"orden"			=> ($e+1)
					);
					$this->mdllogin->editData($temp,"expedientes",intval($key[1]),"id");
					$ids[$e]=intval($key[1]);
				}else{
					$temp=array(
						"titulo"	=> $key[0],
						"subtitulo"	=> $key[2],
						"active"	=> 1,
						"color"		=> $key[3],
						"icon"		=> $key[4],
						"orden"		=> ($e+1)
					);
					$id_temp=$this->mdllogin->insertData($temp,"expedientes");
					if($id_temp === FALSE) $id_temp=FALSE;
					$ids[$e]=intval($id_temp);
				}
			}
		}
		print json_encode(array("success"=>$s, "result"=>$ids));
	}

	public function save_formulario(){
		$data=$this->security->xss_clean($this->input->post("data"));
		$id_exp=$this->security->xss_clean($this->input->post("id_exp"));
		$s=TRUE;
		if(!empty($data)){
			$data_exp=array("expedientes_id"=>$id_exp,"date_time"=>date('Y-m-d H:i:s'));
			$id_exp_data=$this->mdllogin->insertData($data_exp,"expedientes_datos");
			#$id_exp_data=1;
			if(intval($id_exp_data)>0){
				$tipos=array("varchar"=>"dato_var", "date"=>"dato_date", "combo"=>"dato_int", "multicombo"=>"dato_var", "time"=>"dato_time", "text"=>"dato_text", "email"=>"dato_var", "radio"=>"dato_int");
				foreach($data AS $e => $key){
					if($key[1]!="multicombo"){
						if(($tipos[$key[1]]=="dato_date" || $tipos[$key[1]]=="dato_time") && strlen($key[0])==0) $temp_data=NULL;
						else $temp_data=$key[0];
						if(is_array($temp_data) && $key[1]!="multicombo") $temp_data=implode(" ",$temp_data);
					}else{
						if(is_array($key[0])){
							$temp_data=$this->mdllogin->insertData(array("date_start"=>date('Y-m-d H:i:s')),"bitacora");
							foreach($key[0] AS $i => $item){
								$this->mdllogin->insertData(array("bitacora_id"=>$temp_data,"id_inventario"=>$item[0],"cantidad"=>$item[1]),"bitacora_detalles");
							}
						}else $temp_data=0;
					}

					$temp=array(
						"campos_expedientes_id"	=> $key[3],
						"cat_campos_id"			=> $key[4],
						"expedientes_id"		=> $id_exp,
						"campos_bloques_id"		=> $key[2],
						"expedientes_datos_id"	=> $id_exp_data,
						$tipos[$key[1]]			=> $temp_data,
						"date_start"			=> date('Y-m-d H:i:s')
					);
					$id_temp=$this->mdllogin->insertData($temp,"campos_datos");
					if($id_temp===FALSE) $s=FALSE;
				}
			}else $s=FALSE;
		}else $s=FALSE;
		print json_encode(array("success"=>$s));
	}

	public function editar_formulario(){
		$data=$this->security->xss_clean($this->input->post("data"));
		$id_exp=$this->security->xss_clean($this->input->post("id_exp"));
		$s=TRUE;
		if(!empty($data)){
			$tipos=array("varchar"=>"dato_var", "date"=>"dato_date", "combo"=>"dato_int", "multicombo"=>"dato_var", "time"=>"dato_time", "text"=>"dato_text", "email"=>"dato_var", "radio"=>"dato_int");
			foreach($data AS $e => $key){
				if($key[1]!="multicombo"){
					if(($tipos[$key[1]]=="dato_date" || $tipos[$key[1]]=="dato_time") && strlen($key[0])==0){
						if(intval($key[5])>0) $temp_data="NULL";
						else $temp_data=NULL;
					}else $temp_data=$key[0];
				}else{
					if(is_array($key[0])){
						$temp_data=$this->mdllogin->insertData(array("date_start"=>date('Y-m-d H:i:s')),"bitacora");
						foreach($key[0] AS $i => $item){
							$this->mdllogin->insertData(array("bitacora_id"=>$temp_data,"id_inventario"=>$item[0],"cantidad"=>$item[1]),"bitacora_detalles");
						}
					}else $temp_data=0;
				}
				
				if(is_array($temp_data)) $temp_data=implode(" ",$temp_data);
				$temp=array(
					"campos_expedientes_id"	=> $key[3],
					"cat_campos_id"			=> $key[4],
					"expedientes_id"		=> $id_exp,
					"campos_bloques_id"		=> $key[2],
					"expedientes_datos_id"	=> intval($key[6]),
					$tipos[$key[1]]			=> $temp_data,
					"date_start"			=> date('Y-m-d H:i:s')
				);
				if(intval($key[5])>0){
					$this->mdllogin->editData($temp,"campos_datos",$key[5],"id",$where=";");
				}else{
					$id_temp=$this->mdllogin->insertData($temp,"campos_datos");
					if($id_temp===FALSE) $s=FALSE;
				}
			}
		}else $s=FALSE;
		print json_encode(array("success"=>$s));
	}

	public function save_expedientes_campos(){
		$data=$this->security->xss_clean($this->input->post("data"));
		$s=TRUE;
		$ids=array();
		$actual=0;
		if(!empty($data)){
			foreach($data AS $e=>$key){
				if(intval($key[1])>0){
					$bloque=intval($key[4]);
					if($actual===0){
						$actual=$bloque;
						$orden=1;
					}else{
						if($bloque===$actual) $orden++;
						else{
							$actual=$bloque;
							$orden=1;
						}
					}
					$temp=array(
						"cat_campos_id"		=> $key[2],
						"campos_bloques_id"	=> $bloque,
						"nombre"			=> $key[0],
						"orden"				=> $orden,
						"required"			=> $key[5]
					);
					$this->mdllogin->editData($temp,"campos_expedientes",intval($key[1]),"id");
					$ids[$e]=intval($key[1]);
				}else{
					$bloque=intval($key[4]);
					if($actual===0){
						$actual=$bloque;
						$orden=1;
					}else{
						if($bloque===$actual) $orden++;
						else{
							$actual=$bloque;
							$orden=1;
						}
					}
					if($bloque===0){
						$data_temp=$this->get_data("*","campos_bloques","expedientes_id='".$key[3]."'","orden","","1");
						if($data_temp!==FALSE) $bloque=intval($data_temp[0]["id"]);
						else{
							$data_exp=$this->get_data("titulo","expedientes","id='".$key[3]."'")[0];
							$bloque=$this->mdllogin->insertData(array("expedientes_id"=>$key[3], "nombre"=>$data_exp["titulo"], "color"=>"primary", "orden"=>1),"campos_bloques");
						}
					}
					$temp=array(
						"cat_campos_id"		=> $key[2],
						"expedientes_id"	=> $key[3],
						"campos_bloques_id"	=> $bloque,
						"nombre"			=> $key[0],
						"orden"				=> $orden,
						"required"			=> $key[5]
					);
					$id_temp=$this->mdllogin->insertData($temp,"campos_expedientes");
					if($id_temp === FALSE) $id_temp=FALSE;
					$ids[$e]=intval($id_temp);
				}
			}
		}
		print json_encode(array("success"=>$s, "result"=>$ids));
	}

	public function get_reporte_completo(){
		$id=$this->security->xss_clean($this->input->post("id"));
		$data=$this->get_data_from_query("SELECT ppal.* FROM expedientes AS ppal WHERE ppal.id='$id'; ")[0];
		$data_titulos=$this->get_data("*","campos_expedientes","expedientes_id='".$data["id"]."'","orden");
		$data_campos=$this->get_data("*","expedientes_datos","expedientes_id='".$data["id"]."' AND id IN (SELECT DISTINCT expedientes_datos_id FROM campos_datos)");
		$objPHPExcel = PHPExcel_IOFactory::load(FILE_ROUTE_FULL."files/reporte_completo.xlsx");
		$name="Reporte_".$data["titulo"]."-".date("Ymd_His").".xlsx";
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->SetCellValue("A4", $data["titulo"]);
		$s=6;
		$a='A';
		if($data_titulos!==FALSE){
			foreach($data_titulos AS $t=>$tit){
				$objPHPExcel->getActiveSheet()->getColumnDimension($a)->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->SetCellValue(strval($a++).strval($s), $tit["nombre"]);
			}
			if(intval($id)===14){
				$objPHPExcel->getActiveSheet()->getColumnDimension($a)->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->SetCellValue(strval($a++).strval($s), "Costo total");
			}
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
						$objPHPExcel->getActiveSheet()->SetCellValue(strval($a++).strval($s), $r);
					}else{
						if($data_temp!==FALSE) $objPHPExcel->getActiveSheet()->SetCellValue(strval($a++).strval($s), $data_temp[0]["campo_dato"]);
						else $a++;
					}
				}
				if(intval($id)===14){
					$objPHPExcel->getActiveSheet()->SetCellValue(strval($a++).strval($s), $costo_total);
				}
				$s++;
			}
		}
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		$objWriter->save(str_replace(__FILE__,FILE_ROUTE_FULL.'files/'.$name,__FILE__));
		print json_encode(array("success"=>TRUE,"ruta"=>$name));
	}

	public function get_formato_adicional_by_expediente(){
		$id=$this->security->xss_clean($this->input->post("id"));
		$id_exp=$this->security->xss_clean($this->input->post("exp"));
		$expedientes_datos_id=$this->security->xss_clean($this->input->post("campo"));
		$exp=$this->get_data("*","expedientes","id='$id_exp'")[0];
		$adicional=$this->get_data("*","formatos_adicionales","formatos_id='$id'")[0];
		$data=$this->get_data("*","formatos","id_formato='$id' AND is_adicional=1");
		if($data!==FALSE){
			$objPHPExcel = PHPExcel_IOFactory::load(FILE_ROUTE_FULL."files/".$adicional["ruta"]);
			$name=$adicional["nombre"].".xlsx";
			$objPHPExcel->setActiveSheetIndex(0);
			foreach($data AS $e=>$key){
				if(intval($key["campos_expedientes_id_relacionado"])>0){
					if(intval($key["is_id"])>0) $data_temp=$this->get_data("ppal.expedientes_datos_id AS campo_dato","campos_datos AS ppal","ppal.expedientes_datos_id=(SELECT expedientes_datos_id FROM campos_datos WHERE id=(SELECT dato_int FROM campos_datos WHERE expedientes_id='$id' AND campos_expedientes_id IN (SELECT campos_expedientes_id FROM cat_opciones WHERE campos_expedientes_opcional_id IS NOT NULL) LIMIT 1)) AND ppal.campos_expedientes_id='".$key["campos_expedientes_id_relacionado"]."'; ");
					else $data_temp=$this->get_data("COALESCE(COALESCE((SELECT opcion_nombre FROM cat_opciones WHERE id=ppal.dato_int), ppal.dato_var, ppal.dato_text, ppal.dato_date, ppal.dato_time),(SELECT COALESCE(det.dato_int, det.dato_var, det.dato_text, det.dato_date, det.dato_time) FROM campos_datos AS det WHERE det.id=ppal.dato_int)) AS campo_dato","campos_datos AS ppal","ppal.expedientes_datos_id=(SELECT expedientes_datos_id FROM campos_datos WHERE id=(SELECT dato_int FROM campos_datos WHERE expedientes_id='$id' AND campos_expedientes_id IN (SELECT campos_expedientes_id FROM cat_opciones WHERE campos_expedientes_opcional_id IS NOT NULL) LIMIT 1)) AND ppal.campos_expedientes_id='".$key["campos_expedientes_id_relacionado"]."'; ");
				}else{
					if(intval($key["is_id"])>0) $data_temp=$this->get_data("ppal.expedientes_datos_id AS campo_dato","campos_datos AS ppal","ppal.expedientes_datos_id='$expedientes_datos_id' AND ppal.campos_expedientes_id='".$key["campos_expedientes_id"]."'; ");
					else $data_temp=$this->get_data("COALESCE(COALESCE((SELECT opcion_nombre FROM cat_opciones WHERE id=ppal.dato_int), ppal.dato_var, ppal.dato_text, ppal.dato_date, ppal.dato_time),(SELECT COALESCE(det.dato_int, det.dato_var, det.dato_text, det.dato_date, det.dato_time) FROM campos_datos AS det WHERE det.id=ppal.dato_int)) AS campo_dato","campos_datos AS ppal","ppal.expedientes_datos_id='$expedientes_datos_id' AND ppal.campos_expedientes_id='".$key["campos_expedientes_id"]."'; ");
				}
				if($data_temp!==FALSE){
					if(intval($key["first_letter"])>0) $temp=substr($data_temp[0]["campo_dato"],0,1);
					else $temp=$data_temp[0]["campo_dato"];
					if(strlen($key["if_campo"])>0){
						if($key["if_campo"]==$temp) $objPHPExcel->getActiveSheet()->SetCellValue($key["celda"], $key["then_campo"]);
					}else{
						$objPHPExcel->getActiveSheet()->SetCellValue($key["celda"], $temp);
					}
				}
			}
			$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
			$objWriter->save(str_replace(__FILE__,FILE_ROUTE_FULL.'files/'.$name,__FILE__));
			print json_encode(array("success"=>TRUE,"ruta"=>$name));
		}else print json_encode(array("success"=>FALSE));
	}

	public function get_formato_bitacora(){
		$expedientes_id=$this->security->xss_clean($this->input->post("id"));
		$expedientes_datos_id=$this->security->xss_clean($this->input->post("campo"));
		$data_temp=$this->get_data("COALESCE(COALESCE((SELECT opcion_nombre FROM cat_opciones WHERE id=ppal.dato_int), ppal.dato_var, ppal.dato_text, ppal.dato_date, ppal.dato_time),(SELECT COALESCE(det.dato_int, det.dato_var, det.dato_text, det.dato_date, det.dato_time) FROM campos_datos AS det WHERE det.id=ppal.dato_int)) AS campo_dato","campos_datos AS ppal","ppal.expedientes_datos_id='$expedientes_datos_id'");
		if($data_temp!==FALSE){
			$objPHPExcel = PHPExcel_IOFactory::load(FILE_ROUTE_FULL."files/reporte_bitacora.xlsx");
			$name="Bitacora-".date("Ymd_His").".xlsx";
			$objPHPExcel->setActiveSheetIndex(0);
			$objPHPExcel->getActiveSheet()->SetCellValue("D5", $data_temp[0]["campo_dato"]);
			$s=7;
			$tot_main=0;
			$sub_main=0;
			$clasif=array(40,41,42);
			foreach($clasif AS $clas){
				$data_bit=$this->get_data_from_query("SELECT ppal.*, det.dato_var, (SELECT dato_var FROM campos_datos WHERE expedientes_datos_id=det.expedientes_datos_id AND campos_expedientes_id=180) AS precio FROM bitacora_detalles AS ppal INNER JOIN campos_datos AS det ON ppal.id_inventario=det.id WHERE ppal.bitacora_id='".$data_temp[2]["campo_dato"]."' AND (SELECT dato_int FROM campos_datos WHERE expedientes_datos_id=det.expedientes_datos_id AND campos_expedientes_id=192 LIMIT 1)='$clas';");
				if($data_bit!==FALSE){
					$tot=0;
					foreach($data_bit AS $b=>$bit){
						$objPHPExcel->getActiveSheet()->mergeCells('B'.$s.':E'.$s);
						$objPHPExcel->getActiveSheet()->mergeCells('F'.$s.':G'.$s);
						$objPHPExcel->getActiveSheet()->mergeCells('H'.$s.':I'.$s);
						$objPHPExcel->getActiveSheet()->SetCellValue("A".$s, $bit["cantidad"]);
						$objPHPExcel->getActiveSheet()->SetCellValue("B".$s, $bit["dato_var"]);
						$objPHPExcel->getActiveSheet()->SetCellValue("F".$s, floatval($bit["precio"]));
						$objPHPExcel->getActiveSheet()->SetCellValue("H".$s, (floatval($bit["precio"])*intval($bit["cantidad"])));
						$tot+=floatval(floatval($bit["precio"])*intval($bit["cantidad"]));
						$s++;
					}
					$objPHPExcel->getActiveSheet()->getStyle('F7:H'.$s)->getNumberFormat()->setFormatCode("#,##0.00");
					if($tot>0){
						if($clas!=40){
							$iva=$tot*0.16;
							$objPHPExcel->getActiveSheet()->mergeCells('F'.$s.':G'.$s);
							$objPHPExcel->getActiveSheet()->mergeCells('H'.$s.':I'.$s);
							$objPHPExcel->getActiveSheet()->SetCellValue("F".$s, "IVA");
							$objPHPExcel->getActiveSheet()->SetCellValue("H".$s, $iva);
							$objPHPExcel->getActiveSheet()->getStyle('H'.$s++)->getNumberFormat()->setFormatCode("#,##0.00");
						}else $iva=0;
						$objPHPExcel->getActiveSheet()->mergeCells('F'.$s.':G'.$s);
						$objPHPExcel->getActiveSheet()->mergeCells('H'.$s.':I'.$s);
						$objPHPExcel->getActiveSheet()->SetCellValue("F".$s, "SUBTOTAL");
						$objPHPExcel->getActiveSheet()->SetCellValue("H".$s, $tot+$iva);
						$tot_main+=$tot+$iva;
						$sub_main+=$tot;
						$objPHPExcel->getActiveSheet()->getStyle('H'.$s)->getNumberFormat()->setFormatCode("#,##0.00");
						$s++;$s++;
					}
				}
			}
			$objPHPExcel->getActiveSheet()->mergeCells('F'.$s.':G'.$s);
			$objPHPExcel->getActiveSheet()->mergeCells('H'.$s.':I'.$s);
			$objPHPExcel->getActiveSheet()->SetCellValue("F".$s, "SUBTOTAL");
			$objPHPExcel->getActiveSheet()->SetCellValue("H".$s, $sub_main);
			$objPHPExcel->getActiveSheet()->getStyle('H'.$s++)->getNumberFormat()->setFormatCode("#,##0.00");
			$objPHPExcel->getActiveSheet()->mergeCells('F'.$s.':G'.$s);
			$objPHPExcel->getActiveSheet()->mergeCells('H'.$s.':I'.$s);
			$objPHPExcel->getActiveSheet()->SetCellValue("F".$s, "TOTAL");
			$objPHPExcel->getActiveSheet()->SetCellValue("H".$s, $tot_main);
			$objPHPExcel->getActiveSheet()->getStyle('H'.$s++)->getNumberFormat()->setFormatCode("#,##0.00");
			$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
			$objWriter->save(str_replace(__FILE__,FILE_ROUTE_FULL.'files/'.$name,__FILE__));
			print json_encode(array("success"=>TRUE,"ruta"=>$name));
		}else print json_encode(array("success"=>FALSE));
	}

	public function get_formato_by_expediente(){
		$id=$this->security->xss_clean($this->input->post("id"));
		$expedientes_datos_id=$this->security->xss_clean($this->input->post("campo"));
		$exp=$this->get_data("*","expedientes","id='$id'")[0];
		$data=$this->get_data("*","formatos","expedientes_id='$id' AND is_adicional=0");
		if($data!==FALSE){
			$objPHPExcel = PHPExcel_IOFactory::load(FILE_ROUTE_FULL."files/".$exp["ruta_formato"]);
			$name=$exp["titulo"]."-".date("Ymd_His").".xlsx";
			$objPHPExcel->setActiveSheetIndex(0);
			foreach($data AS $e=>$key){
				if(intval($key["campos_expedientes_id_relacionado"])>0){
					if(intval($key["is_id"])>0) $data_temp=$this->get_data("ppal.expedientes_datos_id AS campo_dato","campos_datos AS ppal","ppal.expedientes_datos_id=(SELECT expedientes_datos_id FROM campos_datos WHERE id=(SELECT dato_int FROM campos_datos WHERE expedientes_id='$id' AND campos_expedientes_id IN (SELECT campos_expedientes_id FROM cat_opciones WHERE campos_expedientes_opcional_id IS NOT NULL) LIMIT 1)) AND ppal.campos_expedientes_id='".$key["campos_expedientes_id_relacionado"]."'; ");
					else $data_temp=$this->get_data("COALESCE(COALESCE((SELECT opcion_nombre FROM cat_opciones WHERE id=ppal.dato_int), ppal.dato_var, ppal.dato_text, ppal.dato_date, ppal.dato_time),(SELECT COALESCE(det.dato_int, det.dato_var, det.dato_text, det.dato_date, det.dato_time) FROM campos_datos AS det WHERE det.id=ppal.dato_int)) AS campo_dato","campos_datos AS ppal","ppal.expedientes_datos_id=(SELECT expedientes_datos_id FROM campos_datos WHERE id=(SELECT dato_int FROM campos_datos WHERE expedientes_id='$id' AND campos_expedientes_id IN (SELECT campos_expedientes_id FROM cat_opciones WHERE campos_expedientes_opcional_id IS NOT NULL) LIMIT 1)) AND ppal.campos_expedientes_id='".$key["campos_expedientes_id_relacionado"]."'; ");
				}else{
					if(intval($key["is_id"])>0) $data_temp=$this->get_data("ppal.expedientes_datos_id AS campo_dato","campos_datos AS ppal","ppal.expedientes_datos_id='$expedientes_datos_id' AND ppal.campos_expedientes_id='".$key["campos_expedientes_id"]."'; ");
					else $data_temp=$this->get_data("COALESCE(COALESCE((SELECT opcion_nombre FROM cat_opciones WHERE id=ppal.dato_int), ppal.dato_var, ppal.dato_text, ppal.dato_date, ppal.dato_time),(SELECT COALESCE(det.dato_int, det.dato_var, det.dato_text, det.dato_date, det.dato_time) FROM campos_datos AS det WHERE det.id=ppal.dato_int)) AS campo_dato","campos_datos AS ppal","ppal.expedientes_datos_id='$expedientes_datos_id' AND ppal.campos_expedientes_id='".$key["campos_expedientes_id"]."'; ");
				}
				if($data_temp!==FALSE){
					if(intval($key["first_letter"])>0) $temp=substr($data_temp[0]["campo_dato"],0,1);
					else $temp=$data_temp[0]["campo_dato"];
					if(strlen($key["if_campo"])>0){
						if($key["if_campo"]==$temp) $objPHPExcel->getActiveSheet()->SetCellValue($key["celda"], $key["then_campo"]);
					}else{
						$objPHPExcel->getActiveSheet()->SetCellValue($key["celda"], $temp);
					}
				}
			}
			$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
			$objWriter->save(str_replace(__FILE__,FILE_ROUTE_FULL.'files/'.$name,__FILE__));
			print json_encode(array("success"=>TRUE,"ruta"=>$name));
		}else print json_encode(array("success"=>FALSE));
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

	public function closesession(){
		$this->session->sess_destroy();
		redirect(base_url());
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

	private function generateRandomString($length = 10) {
		$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

	private function getInicials($string){
		$special_chars_table = array('Š'=>'S', 'š'=>'s', 'Ð'=>'Dj', 'Ž'=>'Z', 'ž'=>'z', 'C'=>'C', 'c'=>'c', 'C'=>'C', 'c'=>'c','À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E','Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O','Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e','ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o','ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b','ÿ'=>'y', 'R'=>'R', 'r'=>'r', "'"=>'', " "=>"");
		$string=strtr($string, $special_chars_table);
		$words=explode(" ", $string);
		$acronym="";
		foreach($words AS $w){
			if(strlen($w)>0) $acronym.= $w[0];
		}
		if($acronym==="") $acronym=$string;
		return $string;
	}

}

?>