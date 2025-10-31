<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Formaspagamento_model extends CI_Model
{

    public function lista_todos($limit = null, $offset = null, $considera_filtro = 'S', $formapagamento_status = null)
    {
        $this->db->select('*');
        $this->db->from('entregadores');

        if (!empty($this->session->userdata('filtro_formapagamento_descricao')) && $considera_filtro === 'S') {
            $this->db->like('formapagamento_descricao', $this->session->userdata('filtro_formapagamento_descricao'));
        }

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        if ($formapagamento_status) {
            $this->db->where('formapagamento_status', $formapagamento_status);
        }

        $this->db->order_by('formapagamento_id', 'DESC');

        return $this->db->get()->result_array();
    }

    public function conta_todos()
    {
        if (!empty($this->session->userdata('filtro_formapagamento_descricao'))) {
            $this->db->like('formapagamento_descricao', $this->session->userdata('filtro_formapagamento_descricao'));
        }

        return $this->db->count_all_results('entregadores');
    }

    public function lista_por_id($formapagamento_id)
    {
        $this->db->select('*');
        $this->db->from('entregadores');
        $this->db->where('formapagamento_id', $formapagamento_id);

        return $this->db->get()->row_array();
    }

    public function salvar($acao, $dados)
    {
        if ($acao === 'criar') {
            $this->db->insert('entregadores', $dados);

            return $this->db->insert_id();
        }

        if ($acao === 'alterar') {
            $formapagamento_id = $dados['formapagamento_id'];
            unset($dados['formapagamento_id']);

            $this->db->where('formapagamento_id', $formapagamento_id);

            return $this->db->update('entregadores', $dados);
        }
    }

    public function deletar($formapagamento_id)
    {
        $this->db->where('formapagamento_id', $formapagamento_id);

        return $this->db->delete('entregadores');
    }
}
