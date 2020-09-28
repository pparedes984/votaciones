<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Rol extends MX_Controller
{

    public function __construct()
    {

        parent::__construct();

        if (!$this->input->is_ajax_request()) {
            show_404();
            exit();
        } else {

            $this->load->model('Rol_model', 'Model');
        }
    }

    public function get()
    {
        $result = $this->Model->get();
        $r = array("data"    => $result,
            "success" => "true");
        echo json_encode($r);
    }

}