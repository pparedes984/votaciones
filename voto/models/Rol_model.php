<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rol_model extends CI_Model{

    public function get()
    {
        $this->voto = $this->load->database('voto', TRUE);
        $query = $this->voto->query("SELECT *
                                     from rol")->result_array();

        return $query;
    }
}