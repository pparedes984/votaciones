<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Usuariosistema extends MX_Controller
{

    public function __construct()
    {

        parent::__construct();

        if (!$this->input->is_ajax_request()) {
            show_404();
            exit();
        } else {

            $this->load->model('Usuariosistema_model', 'Model');
        }
    }

    public function get()
    {
        $result = $this->Model->get();
        $r = array("data"    => $result,
            "success" => "true");
        echo json_encode($r);
    }

    public function delete()
    {
        $result = $this->Model->delete($this->input->post('usuario'));
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

    public function add()
    {
        $data = json_decode($this->input->post('data'), TRUE);
        $result = $this->Model->add($data);
        if($result <> 'FALSE')
            $r = array("success" => "true");
        else
            $r = array("failure" => "true");
        echo json_encode($r);
    }

    public function update()
    {
        $data = json_decode($this->input->post('data'), TRUE);
        $usuario= $this->input->post('usuario');
        unset($data['id']);
        $result = $this->Model->update($usuario, $data);
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

    public function getRol()//retorna el rol del usuario
    {
        $result = $this->Model->getRol($this->input->post('usuario'));
        if($result) {
            $r = array("data" => $result,
                "success" => "true");
        }
        else
            $r = array("failure" => "true");
        echo json_encode($r);
    }

    public function verificar()
    {
        $result = $this->Model->verificar($this->input->post('usuario'));
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