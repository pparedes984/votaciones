<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Carga extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form',
            'url'));
        $this->config->load('urls_upload');
    }

    public function index()
    {
        header('location:http://www.puce.edu.ec');
    }

    // Metodo para cargar evidencias
    public function cargarExcel()
    {
        $new_name = isset($_POST['new_name']) ? $_POST['new_name'] : '';
        $new_dir  = isset($_POST['new_dir']) ? $_POST['new_dir'] : '';

        $config['upload_path'] = $new_dir;

        $config['allowed_types'] = 'csv';
        $config['overwrite']     = true;
        $config['max_size']      = 100000;

        if ($new_name) {
            $config['file_name'] = $new_name;
        }

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('userfile')) {
            $error = array('error' => $this->upload->display_errors());
            echo json_encode(array("success" => false,
                "error"                          => $error,
                "path"                           => $config['upload_path']));
        } else {
            $data = array('upload_data' => $this->upload->data());

            echo json_encode(array("success" => true,
                "data"                           => $data));
        }
    }
}