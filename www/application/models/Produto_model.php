<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Produto_model extends CI_Model
{

    public function lista_todos($limit = null, $offset = null, $considera_filtro = 'S', $produto_status = null)
    {
        $this->db->select('*');
        $this->db->from('produtos');

        if (!empty($this->session->userdata('filtro_produto_descricao')) && $considera_filtro === 'S') {
            $this->db->like('produto_descricao', $this->session->userdata('filtro_produto_descricao'));
        }

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        if ($produto_status) {
            $this->db->where('produto_status', $produto_status);
        }

        $this->db->order_by('produto_id', 'DESC');

        return $this->db->get()->result_array();
    }

    public function conta_todos()
    {
        if (!empty($this->session->userdata('filtro_produto_descricao'))) {
            $this->db->like('produto_descricao', $this->session->userdata('filtro_produto_descricao'));
        }

        return $this->db->count_all_results('produtos');
    }

    public function lista_por_id($produto_id)
    {
        $this->db->select('*');
        $this->db->from('produtos');
        $this->db->where('produto_id', $produto_id);

        return $this->db->get()->row_array();
    }

    public function lista_por_nome($produto_descricao)
    {
        $this->db->select('*');
        $this->db->from('produtos');
        $this->db->like('produto_descricao', $produto_descricao);

        $this->db->order_by('produto_descricao', 'ASC');

        return $this->db->get()->result_array();
    }

    public function salvar($acao, $dados)
    {
        if ($acao === 'criar') {
            $this->db->insert('produtos', $dados);

            return $this->db->insert_id();
        }

        if ($acao === 'alterar') {
            $produto_id = $dados['produto_id'];
            unset($dados['produto_id']);

            $this->db->where('produto_id', $produto_id);

            return $this->db->update('produtos', $dados);
        }
    }

    public function deletar($produto_id)
    {
        $this->db->where('produto_id', $produto_id);

        return $this->db->delete('produtos');
    }
}
