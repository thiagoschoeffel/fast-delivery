<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Cliente_model extends CI_Model
{

    public function lista_todos($limit = null, $offset = null)
    {
        $this->db->select('*');
        $this->db->from('clientes');

        if (!empty($this->session->userdata('filtro_cliente_nome'))) {
            $this->db->like('cliente_nome', $this->session->userdata('filtro_cliente_nome'));
        }

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        $this->db->order_by('cliente_id', 'DESC');

        return $this->db->get()->result_array();
    }

    public function conta_todos()
    {
        if (!empty($this->session->userdata('filtro_cliente_nome'))) {
            $this->db->like('cliente_nome', $this->session->userdata('filtro_cliente_nome'));
        }

        return $this->db->count_all_results('clientes');
    }

    public function lista_por_id($cliente_id)
    {
        $this->db->select('*');
        $this->db->from('clientes');
        $this->db->where('cliente_id', $cliente_id);

        return $this->db->get()->row_array();
    }

    public function lista_por_nome($cliente_nome)
    {
        $this->db->select('*');
        $this->db->from('clientes');
        $this->db->like('cliente_nome', $cliente_nome);

        $this->db->order_by('cliente_nome', 'ASC');

        return $this->db->get()->result_array();
    }

    public function lista_aniversariantes_hoje()
    {
        $this->db->select('*');
        $this->db->from('clientes');
        $this->db->where('day(cliente_data_nascimento) = day(NOW())');
        $this->db->where('month(cliente_data_nascimento) = month(NOW())');

        return $this->db->get()->result_array();
    }

    public function salvar($acao, $dados)
    {
        $dados['cliente_data_nascimento'] = data_br_para_en($dados['cliente_data_nascimento']);

        if ($acao === 'criar') {
            $this->db->insert('clientes', $dados);

            return $this->db->insert_id();
        }

        if ($acao === 'alterar') {
            $cliente_id = $dados['cliente_id'];
            unset($dados['cliente_id']);

            $this->db->where('cliente_id', $cliente_id);

            return $this->db->update('clientes', $dados);
        }
    }

    public function deletar($cliente_id)
    {
        $this->db->where('cliente_id', $cliente_id);

        return $this->db->delete('clientes');
    }
}
