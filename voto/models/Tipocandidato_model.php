<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tipocandidato_model extends CI_Model
{


    public function get()//trae los tipos ordenamos or el orden, menos blanco y nulo
    {
        $this->voto = $this->load->database('voto', TRUE);
        $query = $this->voto->query("SELECT id, descripcion, votos, orden
                                    from tipo_candidato
                                    where descripcion not in ('BLANCO','NULO') 
                                    order by orden;" );

        return $query->result_array();
    }

    PUBLIC function add($data)//inserta un TIPOCANDIDATO del sistema nuevo
    {
        $this->votacion = $this->load->database('voto', TRUE);
        $resp = $this->votacion->insert('tipo_candidato', $data);
        if($resp)
            return 'TRUE';
        else
            return 'FALSE';
    }

    public function verificar($descripcion)
    {
        $this->voto = $this->load->database('voto', TRUE);
        $query = $this->voto->query("SELECT *
                                          from tipo_candidato
                                          where descripcion = '$descripcion'");
        $num = $query->num_rows();
        if ($num == 0) {
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
        $query = $this->voto->delete('tipo_candidato');
        if($query)
            return 'TRUE';
        else
            return 'FALSE';
    }

    public function verificaVoto($id)
    {
        $this->voto = $this->load->database('voto', TRUE);
        $query= $this->voto->query("SELECT *
                                        FROM tipo_candidato
                                        inner join candidato on candidato.id_tipo_candidato = tipo_candidato.id
                                        where tipo_candidato.id = '$id'")->num_rows();

        if ($query == 0) {
            return true;
        }
        else{
            return false;
        }
    }

    function update($id, $data)
    {
        $this->voto = $this->load->database('voto', TRUE);
        $this->voto->where('id', $id);
        $resp = $this->voto->update('tipo_candidato', $data);
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