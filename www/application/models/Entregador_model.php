<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Entregador_model extends CI_Model
{

    public function lista_todos($limit = null, $offset = null, $considera_filtro = 'S', $entregador_status = null)
    {
        $this->db->select('*');
        $this->db->from('entregadores');

        if (!empty($this->session->userdata('filtro_entregador_nome')) && $considera_filtro === 'S') {
            $this->db->like('entregador_nome', $this->session->userdata('filtro_entregador_nome'));
        }

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        if ($entregador_status) {
            $this->db->where('entregador_status', $entregador_status);
        }

        $this->db->order_by('entregador_id', 'DESC');

        return $this->db->get()->result_array();
    }

    public function conta_todos()
    {
        if (!empty($this->session->userdata('filtro_entregador_nome'))) {
            $this->db->like('entregador_nome', $this->session->userdata('filtro_entregador_nome'));
        }

        return $this->db->count_all_results('entregadores');
    }

    public function lista_por_id($entregador_id)
    {
        $this->db->select('*');
        $this->db->from('entregadores');
        $this->db->where('entregador_id', $entregador_id);

        return $this->db->get()->row_array();
    }

    public function salvar($acao, $dados)
    {
        if ($acao === 'criar') {
            $this->db->insert('entregadores', $dados);

            return $this->db->insert_id();
        }

        if ($acao === 'alterar') {
            $entregador_id = $dados['entregador_id'];
            unset($dados['entregador_id']);

            $this->db->where('entregador_id', $entregador_id);

            return $this->db->update('entregadores', $dados);
        }
    }

    public function deletar($entregador_id)
    {
        $this->db->where('entregador_id', $entregador_id);

        return $this->db->delete('entregadores');
    }
}
