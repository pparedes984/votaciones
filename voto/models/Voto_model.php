<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Voto_model extends CI_Model{

    public function get()
    {
        $this->voto = $this->load->database('voto', TRUE);
        $query = $this->voto->query("SELECT *
                                     from voto")->result_array();

        return $query;
    }

    public function getResultado()
    {
        $this->voto = $this->load->database('voto', TRUE);
        $query = $this->voto->query("select  count(voto.id_candidato) voto, candidato.nombre nombre, tipo_candidato.descripcion
                                        from voto
                                        inner join candidato on candidato.id = voto.id_candidato
                                        inner join tipo_candidato on voto.id_tipo_candidato = tipo_candidato.id
                                        group by  tipo_candidato.orden,tipo_candidato.descripcion, candidato.nombre
                                        order by tipo_candidato.orden")->result_array();

        return $query;
    }

    public function add($cedula, $votos)//guarda los votos
    {
        $this->voto = $this->load->database('voto', TRUE);
        $usuariosistema = $this->session->session_usuario_apps;//toma el usuario de la sesion
        $myIp = $_SERVER['REMOTE_ADDR'];//toma la ip desde donde se vota
        $resp = false;
        $tipos = $this->voto->query("SELECT *
                                    from tipo_candidato
                                    where descripcion not in ('BLANCO','NULO')
                                    order by orden;");//trae los tipos ordenados por la columna orden a excepcion de blanco y nulo
        $numtipos = $tipos->num_rows();//cuenta cuantos tipos
        $tipos = $tipos->result_array();//guarda la infoemacion de los tipos
        for ($i = 0; $i < $numtipos; $i++)//bucle para cada tipo
        {
            if($tipos[$i]['votos'] >= count($votos[$i]))//verifica si los votos realizados para este tipo son menores o iguales a los que permite el tipo
            {
                for ($j = 0; $j < count($votos[$i]); $j++) {//buble de votos realizados para cada tipo
                    $voto = $votos[$i][$j];//se asigna el valor del voto
                    if($votos[$i][$j] == 'BLANCO' || $votos[$i][$j] == 'NULO'){//si el voto es blanco o nulo
                        $voto = $this->voto->query("SELECT id
                                    from candidato
                                    where nombre = '$voto';")->result_array();//se trae el id del blanco o nulo
                        $voto = $voto[0]['id'];//se añade el id del blanco o nulo
                    }
                    $tipo = $tipos[$i]['id'];//asigna el id del tipo en el que se encuentra
                    $votos_realizados = $this->voto->query("select * from voto
                                                            where cedula_votante = '$cedula'
                                                            and id_tipo_candidato = $tipo;")->num_rows();//cuenta los votos realizados
                    if($votos_realizados<count($votos[$i])) {//verifica si los votos realizados son menos a los que inserta
                        $this->voto->query("insert into voto
                                              (id_candidato,cedula_votante,id_usuario_sistema, ip,id_tipo_candidato)
                                              values ($voto,'$cedula','$usuariosistema','$myIp',$tipo);");//inserta el voto
                    }
                    else{
                        break;
                    }
                }
                $resp = true;
            }
            else{
                $resp = false;
                break;
            }
        }
        if($resp == true)
            return 'TRUE';
        else
            return 'FALSE';
    }

    public function marcarenviado($cedula)//marca como enviada la papeleta
    {
        $this->voto = $this->load->database('voto', TRUE);
        $query = $this->voto->query("update votante 
                                          set papeleta = 1
                                          where cedula = '$cedula'");
        if($query)
        {
            return 'TRUE';
        }
        else
        {
            return 'FALSE';
        }
    }

    public function envioPapeleta ($cedula)//trae el email del votante
    {
        $this->voto = $this->load->database('voto', TRUE);
        $query = $this->voto->query("SELECT email
                                          from votante
                                          where cedula  = '$cedula'");
        $query = $query->result_array();
        return $query[0]['email'];
    }

    public function correo($from, $bcc, $mensaje, $subject)
    {
        $this->email->from($from);
        $this->email->bcc($bcc);
        $this->email->subject($subject);
        $this->email->set_mailtype("html");
        $this->email->message($mensaje);
        $envio = $this->email->send();
        if ($envio)
            return true;
        else
            return false;
    }

    public function getData ($cedula)//trae la informacion del votante
    {
        $this->voto = $this->load->database('voto', TRUE);
        $query = $this->voto->query("SELECT *
                                          from votante
                                          where cedula  = '$cedula'");
        return $query->result_array();
    }

    public function proceso()
    {
        $this->voto = $this->load->database('voto', TRUE);
        $query1 = $this->voto->query("delete from voto");
        $query2 = $this->voto->query("delete from votante");
        $query3 = $this->voto->query("delete from candidato");
        $query4 = $this->voto->query("delete from tipo_candidato");
        $query5 = $this->voto->query("insert into tipo_candidato values ('BLANCO',0, 0)");
        $query6 = $this->voto->query("insert into tipo_candidato values ('NULO',0, 0)");
        $dato1 = $this->voto->query("SELECT id from tipo_candidato where descripcion = 'BLANCO'")->result_array();
        $dato2 = $this->voto->query("SELECT id from tipo_candidato where descripcion = 'NULO'")->result_array();
        $aux1 = $dato1[0]['id'];
        $aux2 = $dato2[0]['id'];

        $query7 = $this->voto->query("insert into candidato (nombre, id_tipo_candidato) values ('BLANCO', $aux1)");
        $query8 = $this->voto->query("insert into candidato (nombre, id_tipo_candidato) values ('NULO',$aux2)");

        if($query1 && $query2 && $query3 && $query4 &&  $query5 && $query6 && $query7 && $query8)
            return 'TRUE';
        else
            return 'FALSE';
    }

}