<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Clienteendereco_model extends CI_Model
{

    public function lista_todos($cliente_id)
    {
        $this->db->select('clientesenderecos.*, entregador_nome');
        $this->db->from('clientesenderecos');
        $this->db->join('entregadores', 'entregador_id = clienteendereco_entregador');
        $this->db->where('clienteendereco_cliente', $cliente_id);
        $this->db->order_by('clienteendereco_id', 'DESC');

        return $this->db->get()->result_array();
    }

    public function lista_todos_por_cliente($clienteendereco_cliente)
    {
        $this->db->select('*');
        $this->db->where('clienteendereco_cliente', $clienteendereco_cliente);
        $this->db->from('clientesenderecos');

        return $this->db->get()->result_array();
    }

    public function lista_por_id($clienteendereco_id)
    {
        $this->db->select('*');
        $this->db->where('clienteendereco_id', $clienteendereco_id);
        $this->db->from('clientesenderecos');

        return $this->db->get()->row_array();
    }

    public function salvar($acao, $dados)
    {
        if ($acao === 'criar') {
            $this->db->insert('clientesenderecos', $dados);

            return $this->db->insert_id();
        }
    }

    public function deletar($clienteendereco_id)
    {
        $this->db->where('clienteendereco_id', $clienteendereco_id);

        return $this->db->delete('clientesenderecos');
    }

    public function altera_status($clienteendereco_id, $clienteendereco_status)
    {
        if ($clienteendereco_status === 'A') {
            $this->db->set('clienteendereco_status', 'I');
        } else {
            $this->db->set('clienteendereco_status', 'A');
        }

        $this->db->where('clienteendereco_id', $clienteendereco_id);

        return $this->db->update('clientesenderecos');
    }
}
