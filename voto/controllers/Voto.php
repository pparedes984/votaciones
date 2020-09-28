<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Voto extends MX_Controller
{

    public function __construct()
    {

        parent::__construct();

        if (!$this->input->is_ajax_request()) {
            show_404();
            exit();
        } else {

            $this->load->model('Voto_model', 'Model');
        }
    }

    public function get()
    {
        $result = $this->Model->get();
        $r = array("data" => $result,
            "success" => "true");
        echo json_encode($r);
    }

    public function getResultado()
    {
        $result = $this->Model->getResultado();
        $r = array("data" => $result,
            "success" => "true");
        echo json_encode($r);
    }

    public function saveVoto()//guarda los votos
    {
        $usuario = $datos['usuario_apps'] = $this->session->session_usuario_apps;
        if($usuario == ''){//verifica si la sesion esta activa
            $r = array("failure" => "true");
        }
        else{
            $result = $this->Model->add($this->input->post('cedula'), json_decode($this->input->post('votos')));//guarda los votos
            if($result == 'TRUE') {
                $resp = $this->papeleta($this->input->post('cedula'));//envia el correo de la papeleta
                if ($resp) {
                    $this->Model->marcarenviado($this->input->post('cedula'));//marca como enviada la papeleta
                    $r = array("success" => "true");
                } else {
                    $r = array("failure" => "true");
                }
                $r = array("success" => "true");
            }
            else
                $r = array("failure" => "true");
        }

        echo json_encode($r);
    }

    public function papeleta($cedula)
    {
        $bcc = $this->Model->envioPapeleta($cedula);//trae el email del votante
        $datos = $this->Model->getData($cedula);//trae los datos del votante
        $datos['tribunal'] = $this->session->session_nombre_apps;
        $aux = $datos[0]['cedula'];
        $datos['cedula'] = $aux;
        $aux = $datos[0]['nombres'];
        $datos['nombre'] = $aux;
        $mensaje = $this->load->view('correopapeleta_view', $datos, TRUE);
        $result['mensaje institucional'] = $this->Model->correo('votacion@puce.edu.ec',$bcc, $mensaje, 'Certificado de votación No contestar');
        $result['copia mensaje institucional'] = $this->Model->correo('votacion@puce.edu.ec','palmeida319@puce.edu.ec', $mensaje, 'Certificado de votación');
        if($result['mensaje institucional']==true){
            return true;
        }else{
            return false;
        }
    }

    public function eliminar()
    {
        $result = $this->Model->proceso();
        $r = array("data"    => $result,
            "success" => "true");
        echo json_encode($r);
    }
}