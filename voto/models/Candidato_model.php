<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Candidato_model extends CI_Model{

    public function getAll()
    {
        $this->voto = $this->load->database('voto', TRUE);
        $query = $this->voto->query("SELECT candidato.nombre, tipo_candidato.descripcion id_tipo_candidato, candidato.id, candidato.foto, tipo_candidato.id id_candidato
                                     from candidato
                                     inner join tipo_candidato on candidato.id_tipo_candidato = tipo_candidato.id
                                     where nombre not in ('BLANCO', 'NULO')")->result_array();

        return $query;
    }

    public function get()//trae los candidatos menos nulo y blanco
    {
        $this->voto = $this->load->database('voto', TRUE);
        $query = $this->voto->query("SELECT *
                                        from candidato
                                        where nombre not in ('BLANCO','NULO');")->result_array();

        return $query;
    }

    public function getTipo($tipo)//trae los candidatos por tipo
    {
        $this->voto = $this->load->database('voto', TRUE);
        $query = $this->voto->query("SELECT *
                                          from candidato
                                          where id_tipo_candidato = $tipo" );
        return $query->result_array();
    }

    public function verificar($nombre)
    {
        $this->voto = $this->load->database('voto', TRUE);
        $query = $this->voto->query("SELECT *
                                          from candidato
                                          where nombre = '$nombre'");
        $num = $query->num_rows();
        if ($num == 0) {
            return false;
        }
        else{
            return true;
        }
    }

    public function verificaVoto($id)//verifica si el votante tiene votos
    {
        $this->voto = $this->load->database('voto', TRUE);
        $query= $this->voto->query("SELECT *
                                        FROM voto
                                        where id_candidato = '$id'")->num_rows();

        if ($query == 0) {
            return true;
        }
        else{
            return false;
        }
    }

    public function delete($id)
    {
        $this->voto = $this->load->database('voto', TRUE);
        $this->voto->where('id', $id);
        $query = $this->voto->delete('candidato');
        if($query)
            return 'TRUE';
        else
            return 'FALSE';
    }

    public function add($data,$foto)//inserta un candidato nuevo
    {
        $this->voto = $this->load->database('voto', TRUE);
        $data['foto'] = $foto.'.jpg';
        unset($data['id']);
        $resp = $this->voto->insert('candidato', $data);
        if($resp)
            return 'TRUE';
        else
            return 'FALSE';
    }

    public function update($id, $data)
    {
        $this->voto = $this->load->database('voto', TRUE);
        $this->voto->where('id', $id);
        $resp = $this->voto->update('candidato', $data);
        if($resp)
        {
            return 'TRUE';
        }
        else
        {
            return 'FALSE';
        }
    }

}