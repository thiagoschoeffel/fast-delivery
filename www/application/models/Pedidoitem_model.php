<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pedidoitem_model extends CI_Model
{

    public function lista_todos($pedido_id)
    {
        $this->db->select('pedidositens.*, produtos.produto_descricao AS pedidoitem_produto_descricao');
        $this->db->from('pedidositens');
        $this->db->join('produtos', 'produtos.produto_id = pedidositens.pedidoitem_produto');
        $this->db->where('pedidoitem_pedido', $pedido_id);
        $this->db->order_by('pedidoitem_id', 'DESC');

        return $this->db->get()->result_array();
    }

    public function lista_por_id($pedidoitem_id)
    {
        $this->db->select('*');
        $this->db->where('pedidositens_id', $pedidoitem_id);
        $this->db->from('pedidositens');

        return $this->db->get()->row_array();
    }

    public function salvar($acao, $dados)
    {
        if ($acao === 'criar') {
            $this->db->insert('pedidositens', $dados);

            return $this->db->insert_id();
        }
    }

    public function deletar($pedidoitem_id)
    {
        $this->db->where('pedidoitem_id', $pedidoitem_id);

        return $this->db->delete('pedidositens');
    }
}
