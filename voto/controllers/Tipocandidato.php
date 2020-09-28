<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tipocandidato extends MX_Controller
{

    public function __construct()
    {

        parent::__construct();

        if (!$this->input->is_ajax_request()) {
            show_404();
            exit();
        } else {

            $this->load->model('Tipocandidato_model', 'Model');
        }
    }

    public function get()//trae los tipos menos blanco y nulo
    {
        $result = $this->Model->get();
        $r = array("data"    => $result,
            "success" => "true");
        echo json_encode($r);
    }

    public function create()
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
        $id = $this->input->post('id');
        unset($data['id']);
        $result = $this->Model->update($id, $data);
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

    public function delete()
    {
        $result = $this->Model->delete($this->input->post('id'));
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

    public function verificar()
    {
        $result = $this->Model->verificar($this->input->post('nombre'));
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

    public function verificaVoto(){
        $result = $this->Model->verificaVoto($this->input->post('id'));
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