<?php

class ctrLogin extends MX_Controller{

	function __construct(){
		parent::__construct();
		$this->load->model('mdllogin');
		date_default_timezone_set('America/Mexico_City');
	}

	public function index(){
		$data["controller"]=Modules::run("home/ctrhome/get_self");
		if($this->session->userdata("id")){
			redirect("home");
		}else{
			print $this->load->view("vwlogin",$data,TRUE);
		}
	}

	private function test_view(){
		$data["code"]="XXXXXX";
		print $this->load->view("vwemail_code",$data,TRUE);
	}

	private function send_mail($email,$data){
		$code=$this->mdllogin->get_code_from_usr($data["expedientes_datos_id"]);
		if($code!==FALSE){
			$code=$code[0];
			$code=$code["dato_var"];
			$datos["code"]=$code;
			$body=$this->load->view("vwemail_code",$datos,TRUE);
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$headers .= 'From: <noreply@softandgo.com>' . "\r\n";
			mail($email,"Código de acceso",$body,$headers);
			/*
			require FILE_ROUTE_FULL.'addons/mail/PHPMailerAutoload.php';
			$mail = new PHPMailer;
			$mail->isSMTP();
			$mail->SMTPDebug = 1;
			$mail->Debugoutput = 'html';
			$mail->Host = "smtp.gmail.com";
			$mail->Port = 465;
			$mail->SMTPAuth = true;
			$mail->SMTPSecure = 'ssl';
			$mail->Username = "dboonny@gmail.com";
			$mail->Password = "holacontrasenaddc1234";
			$mail->setFrom('noreply@softandgo.com', 'Clinica MAE');
			$mail->addReplyTo('noreply@softandgo.com', 'Clinica MAE');
			$mail->addAddress($email, "Usuario");
			$mail->Subject = 'Codigo de acceso';
			$mail->Body=$body;
			$mail->AltBody = $body;
			$mail->IsHTML(true);
			if (!$mail->send()) return FALSE; else return TRUE;
			#return $mail->send();
			*/
			return TRUE;
		}else return FALSE;
	}

	public function check_usr_for_code(){
		$data=$this->security->xss_clean($this->input->post("data"));
		$params = array();
		parse_str($data, $params);
		$result=$this->mdllogin->check_if_name_exists($params["name"]);
		if($result!==FALSE){
			if($this->send_mail($params["email"],$result[0]))
				print json_encode(array("success"=>TRUE, "type_msg"=>"success", "title"=>"Éxito", "msg"=>"Revisa tu bandeja de correo, así como tu bandeja de spam. Puede tardar unos minutos en llegar."));
			else
				print json_encode(array("success"=>FALSE, "type_msg"=>"danger", "title"=>"Alerta", "msg"=>"Hubo un problema al enviar tu código. Intenta más tarde."));
		}else print json_encode(array("success"=>FALSE, "type_msg"=>"danger", "title"=>"Alerta", "msg"=>"El nombre ingresado no existe en el sistema."));
	}

	public function login_guest(){
		$id=$this->mdllogin->insertData(array("date_start"=>date('Y-m-d H:i:s')),"users");
		if(intval($id)>0){
			$temp=array("id"=>$id, "admin"=>0);
			$this->session->set_userdata($temp);
			print json_encode(array("success"=>TRUE, "type_msg"=>"success", "title"=>"Éxito", "msg"=>"Sesión iniciada correctamente como paciente."));
		}else print json_encode(array("success"=>FALSE, "type_msg"=>"danger", "title"=>"Alerta", "msg"=>"Hubo un error. Por favor intente más tarde."));
	}

	public function login(){
		$data=$this->security->xss_clean($this->input->post("data"));
		$params = array();
		parse_str($data, $params);
		$data_login=$this->mdllogin->check_login($params["email"],$params["pwd"]);
		if($data_login!==FALSE){
			if(strlen($data_login[0]["date_start"])>0){
				if(array_key_exists("remember",$params)){
					$sess['new_expiration'] = 60*60*24*30;//30 day(s)
        			$this->session->sess_expiration = $sess['new_expiration'];
        			$this->session->set_userdata($sess);
				}
				$this->session->set_userdata($data_login[0]);
				print json_encode(array("success"=>TRUE,"type_msg"=>"success","title"=>"Éxito","msg"=>"Sesión iniciada correctamente."));
			}else print json_encode(array("success"=>FALSE,"type_msg"=>"warning","title"=>"Alerta","msg"=>"El usuario se encuentra desactivado. Consultar activación con el administrador del sitio."));
		}else print json_encode(array("success"=>FALSE,"type_msg"=>"danger","title"=>"Error","msg"=>"Contraseña y/o nombre de usuario incorrectos."));
	}

	public function login_with_code(){
		$data=$this->security->xss_clean($this->input->post("data"));
		$params = array();
		parse_str($data, $params);
		$data_login=$this->mdllogin->check_login_with_code($params["pwd_second"]);
		if($data_login!==FALSE){
			#if(strlen($data_login[0]["date_start"])>0){
				if(array_key_exists("remember",$params)){
					$sess['new_expiration'] = 60*60*24*30;//30 day(s)
        			$this->session->sess_expiration = $sess['new_expiration'];
        			$this->session->set_userdata($sess);
				}
				$this->session->set_userdata($data_login[0]);
				print json_encode(array("success"=>TRUE,"type_msg"=>"success","title"=>"Éxito","msg"=>"Sesión iniciada correctamente."));
			#}else print json_encode(array("success"=>FALSE,"type_msg"=>"warning","title"=>"Alerta","msg"=>"El usuario se encuentra desactivado. Consultar activación con el administrador del sitio."));
		}else print json_encode(array("success"=>FALSE,"type_msg"=>"danger","title"=>"Error","msg"=>"Contraseña y/o nombre de usuario incorrectos."));
	}

	public function signup(){
		$data=$this->security->xss_clean($this->input->post("data"));
		$params = array();
		parse_str($data, $params);
		$data_login=$this->mdllogin->check_email_existence($params["email"]);
		if($data_login===FALSE){
			$params["pwd"]=hash('sha512',$params["pwd"]);
			$params["date_start"]=date('Y-m-d H:i:s');
			unset($params["curso"]);
			unset($params["despacho"]);
			$params["idInstructor"]=$params["instructor"];
			unset($params["instructor"]);
			if(array_key_exists("remember",$params)){ $rem=TRUE; unset($params["remember"]); } else $rem=FALSE;
			$insert_id=$this->mdllogin->insertData($params,"usuarios");
			if(intval($insert_id)>0){
				$data_login_id=$this->mdllogin->check_login_by_id($insert_id);
				if($data_login_id!==FALSE){
					if($rem){
						$sess['new_expiration'] = 60*60*24*30;//30 day(s)
        				$this->session->sess_expiration = $sess['new_expiration'];
        				$this->session->set_userdata($sess);
					}
					$this->session->set_userdata($data_login_id[0]);
					print json_encode(array("success"=>TRUE,"type_msg"=>"success","title"=>"Éxito","msg"=>"Usuario registrado correctamente."));
				}else print json_encode(array("success"=>FALSE,"type_msg"=>"danger","title"=>"Error","msg"=>"Hubo un error con el usuario. Favor de intentar más tarde."));
			}else print json_encode(array("success"=>FALSE,"type_msg"=>"danger","title"=>"Error","msg"=>"Hubo un error. Favor de intentar más tarde."));
		}else print json_encode(array("success"=>FALSE,"type_msg"=>"warning","title"=>"Alerta","msg"=>"El correo ingresado ya se encuentra registrado. Favor de iniciar sesión."));
	}

	public function forgot_password(){
		$data=$this->security->xss_clean($this->input->post("data"));
		$data_login=$this->mdllogin->check_email_existence($params["email"]);
	}
}

?>