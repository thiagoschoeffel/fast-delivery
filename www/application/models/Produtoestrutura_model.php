<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Produtoestrutura_model extends CI_Model
{

    public function lista_todos($produto_id)
    {
        $this->db->select('produtosestruturas.*, produto_descricao as produtoestrutura_subproduto_descricao');
        $this->db->from('produtosestruturas');
        $this->db->join('produtos', 'produto_id = produtoestrutura_subproduto');
        $this->db->where('produtoestrutura_produto', $produto_id);
        $this->db->order_by('produtoestrutura_id', 'DESC');

        return $this->db->get()->result_array();
    }

    public function lista_por_id($produtoestrutura_id)
    {
        $this->db->select('*');
        $this->db->where('produtoestrutura_id', $produtoestrutura_id);
        $this->db->from('produtosestruturas');

        return $this->db->get()->row_array();
    }

    public function lista_por_produto_e_subproduto($produtoestrutura_produto, $produtoestrutura_subproduto)
    {
        $this->db->select('*');
        $this->db->from('produtosestruturas');
        $this->db->where('produtoestrutura_produto', $produtoestrutura_produto);
        $this->db->where('produtoestrutura_subproduto', $produtoestrutura_subproduto);

        return $this->db->get()->row_array();
    }

    public function salvar($acao, $dados)
    {
        if ($acao === 'criar') {
            $this->db->insert('produtosestruturas', $dados);

            return $this->db->insert_id();
        }
    }

    public function atualizar_quantidade($produtoestrutura_id, $produtoestrutura_quantidade)
    {
        $this->db->set('produtoestrutura_quantidade', $produtoestrutura_quantidade);
        $this->db->where('produtoestrutura_id', $produtoestrutura_id);

        return $this->db->update('produtosestruturas');
    }

    public function deletar($produtoestrutura_id)
    {
        $this->db->where('produtoestrutura_id', $produtoestrutura_id);

        return $this->db->delete('produtosestruturas');
    }
}
