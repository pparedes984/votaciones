<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Votante extends MX_Controller
{

    public function __construct()
    {

        parent::__construct();

        if (!$this->input->is_ajax_request()) {
            show_404();
            exit();
        } else {

            $this->load->model('Votante_model', 'Model');
        }
    }

    public function get()
    {
        if($this->input->post('busqueda')==1)
        {
            $result = $this->Model->getCedula($this->input->post('cedula'));//retorna el votante por la cedula
        }
        else
            $result = $this->Model->get();
        $r = array("data" => $result,
            "success" => "true");
        echo json_encode($r);
    }

    public function create(){
        $data = json_decode($this->input->post('data'), TRUE);
        date_default_timezone_set('America/Bogota');
        $fecha = date("Y-m-d");
        $data['fecha_creado'] = $fecha;
        $result = $this->Model->create($data);
        if($result <> 'FALSE')
            $r = array("success" => "true");
        else
            $r = array("failure" => "true");
        echo json_encode($r);
    }

    public function verificar(){
        $result = $this->Model->verificar($this->input->post('cedula'));
        if($result)
        {
            $r = array("success" => "true");
        }
        else
        {
            $r = array("failure" => "true");
        }
        echo json_encode($r);
    }

    public function revisa()
    {
        $result = $this->Model->votoUsuario($this->input->post('cedula'));//Veririca si el usuario ya votó
        if($result) {
            $r = array("failure" => "true",
                "msn"=>"El usuario ingresado ya realizó la votación");
        }
        else {
            $result = $this->Model->existeUsuario($this->input->post('cedula'), $this->input->post('clave'));//Verifica cedula y Clave
            if($result)
                $r = array("data" => $result,
                    "success" => "true");
            else
                $r = array("failure" => "true",
                    "msn"=>"Usuario inactivo o credenciales incorrectas");
        }

        echo json_encode($r);
    }

    public function clavespendientes()
    {
        $result = $this->Model->getnumClaves();
        $r = array("data"    => $result,
            "success" => "true");
        echo json_encode($r);
    }

    public function generarClave()
    {

        $result = $this->Model->generar();
        if($result)
            $r = array("success" => "true");
        else
            $r = array("failure" => "true");
        echo json_encode($r);
    }

    public function correospendientes()
    {
        $result = $this->Model->getnumCorreos();
        $r = array("data"    => $result,
            "success" => "true");
        echo json_encode($r);
    }

    public function enviar()
    {
        $resp= $this->Model->getCorreos();
        $result='';
        foreach ($resp as $email) {
            $result = $this->correo($email['email'], $email['clave']);
            if($result){
                $this->Model->marcarenviado($email['cedula']);
                $r = array("success" => "true");
            }
            else {
                $r = array("failure" => "true");
                break;
            }
        }
        echo json_encode($r);
    }

    public function correo($bcc, $contrasena)
    {
        $datos['data'] = $contrasena;
        $mensaje = $this->load->view('correovotacion_view', $datos, TRUE);
        $result['mensaje institucional'] = $this->Model->correo('votacion@puce.edu.ec',$bcc, $mensaje, 'Clave para las elecciones');
        if($result['mensaje institucional']==true)
            return true;
        else
            return false;
    }

    public function correoestu()
    {
        $datos['data'] = $this->input->post('clave');
        $mensaje = $this->load->view('correovotacion_view', $datos, TRUE);
        $result['mensaje institucional'] = $this->Model->correo('votacion@puce.edu.ec',$this->input->post('email'), $mensaje, 'Clave para las elecciones');
        if($result['mensaje institucional']==true)
            return 'TRUE';
        else
            return 'FALSE';
    }

    public function revisaVoto()
    {
        $result = $this->Model->votoUsuario($this->input->post('cedula'));//Veririca si el usuario ya votó
        if($result) {
            $r = array("failure" => "true",
                "msn"=>"El usuario ingresado ya realizó la votación");
        }
        else {
            $r = array("success" => "true");
        }

        echo json_encode($r);
    }

    public function loadExcel()
    {
        $result = $this->Model->loadExcel($this->input->post('nombre'));
        if($result <> 'FALSE')
            $r = array("success" => "true");
        else
            $r = array("failure" => "true");
        echo json_encode($r);
    }

    public function update()
    {
        $data = json_decode($this->input->post('data'), TRUE);
        $cedula = $this->input->post('cedula');
        unset($data['id']);
        $result = $this->Model->update($cedula, $data);
        if($result)
        {
            $r = array("success" => "true");
        }
        else
        {
            $r = array("failure" => "true");
        }
        echo json_encode($r);
    }
}
