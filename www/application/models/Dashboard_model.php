<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();

        $this->db->query("SET SESSION sql_mode = (SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
    }

    public function lista_ticket_medio()
    {
        $this->db->select('AVG(pedido_valor_total) AS ticket_medio');
        $this->db->from('pedidos');
        $this->db->where('pedido_rascunho', 'N');

        return $this->db->get()->row_array();
    }

    public function lista_grafico()
    {
        $this->db->select('pedido_data_emissao, COUNT(pedido_id) AS pedido_quantidade, SUM(pedido_valor_total) AS pedido_valor');
        $this->db->from('pedidos');
        $this->db->where('YEAR(pedido_data_emissao)', date('Y'));
        $this->db->where('MONTH(pedido_data_emissao)', date('m'));
        $this->db->where('pedido_rascunho', 'N');
        $this->db->group_by('pedido_data_emissao');

        return $this->db->get()->result_array();
    }

    public function lista_producao()
    {
        $this->db->select('*');
        $this->db->from('vbiproducao');

        $this->db->order_by('produto_quantidade', 'DESC');

        return $this->db->get()->result_array();
    }

    public function lista_embalagem_entregador()
    {
        $this->db->select('entregador_nome');
        $this->db->from('vbiembalagem');

        $this->db->group_by('entregador_nome');

        return $this->db->get()->result_array();
    }

    public function lista_embalagem_entregador_pedido($entregador_nome)
    {
        $this->db->select('pedido_id, cliente_nome, pedido_observacao');
        $this->db->from('vbiembalagem');
        $this->db->where('entregador_nome', $entregador_nome);

        $this->db->group_by('pedido_id, cliente_nome, pedido_observacao');

        return $this->db->get()->result_array();
    }

    public function lista_embalagem_entregador_pedido_item($entregador_nome, $pedido_id)
    {
        $this->db->select('produto_descricao, pedidoitem_quantidade');
        $this->db->from('vbiembalagem');
        $this->db->where('entregador_nome', $entregador_nome);
        $this->db->where('pedido_id', $pedido_id);

        return $this->db->get()->result_array();
    }

    public function lista_entrega_entregador($entregador_id = null)
    {
        $this->db->select('entregador_id, entregador_nome');
        $this->db->from('vbientrega');

        if ($entregador_id) {
            $this->db->where('entregador_id', $entregador_id);
        }

        $this->db->group_by('entregador_id, entregador_nome');
        $this->db->order_by('entregador_nome', 'ASC');

        return $this->db->get()->result_array();
    }

    public function lista_entrega_entregador_pedido($entregador_nome)
    {
        $this->db->select('*');
        $this->db->from('vbientrega');
        $this->db->where('entregador_nome', $entregador_nome);
        $this->db->order_by('pedido_sequencia_entrega', 'ASC');

        return $this->db->get()->result_array();
    }
}
