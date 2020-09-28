<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Candidato extends MX_Controller
{

    public function __construct()
    {

        parent::__construct();

        if (!$this->input->is_ajax_request()) {
            show_404();
            exit();
        } else {

            $this->load->model('Candidato_model', 'Model');
        }
    }
    public function get()
    {
        if($this->input->post('busqueda')==1)
            $result = $this->Model->getAll();
        else
            $result = $this->Model->get();//trae los candidatos
        $r = array("data" => $result,
            "success" => "true");
        echo json_encode($r);
    }

    public function getTipo()//trae los candidatos por el tipo
    {
        $result = $this->Model->getTipo($this->input->post('tipo'));
        $r = array("data" => $result,
            "success" => "true");
        echo json_encode($r);
    }

    public function create()
    {
        $data = json_decode($this->input->post('data'), TRUE);
        unset($data['id']);
        unset($data['id_candidato']);
        $result = $this->Model->add($data, $this->input->post('foto'));
        if($result <> 'FALSE')
            $r = array("success" => "true");
        else
            $r = array("failure" => "true");
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

    public function foto()
    {
        $result = $this->Model->moveFoto($this->input->post('url'),$this->input->post('nombre'));
        if($result <> 'FALSE')
            $r = array("success" => "true");
        else
            $r = array("failure" => "true");
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

    public function verificaVoto(){//verifica si el votante tiene votos
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

    public function update()
    {
        $data = json_decode($this->input->post('data'), TRUE);
        $id = $this->input->post('id');
        unset($data['id']);
        $data['foto'] = $this->input->post('foto').'.jpg';
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
}