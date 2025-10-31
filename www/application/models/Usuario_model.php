<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Usuario_model extends CI_Model
{

    public function lista_por_email($email)
    {
        $this->db->select('*');
        $this->db->from('usuarios');
        $this->db->where('usuario_email', $email);

        return $this->db->get()->row_array();
    }

    public function atualiza_data_ultimo_login($usuario_id)
    {
        $data_atual = date('Y-m-d H:i:s');

        $this->db->set('usuario_data_ultimo_login', $data_atual);
        $this->db->where('usuario_id', $usuario_id);

        return $this->db->update('usuarios');
    }
}
