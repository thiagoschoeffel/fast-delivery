<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pedido_model extends CI_Model
{

    public function lista_todos($limit = null, $offset = null)
    {
        $this->db->select('pedidos.*, clientes.cliente_nome AS pedido_cliente_nome, formaspagamento.formapagamento_descricao AS pedido_formapagamento_descricao');
        $this->db->from('pedidos');
        $this->db->join('clientes', 'clientes.cliente_id = pedidos.pedido_cliente');
        $this->db->join('formaspagamento', 'formaspagamento.formapagamento_id = pedidos.pedido_formapagamento');

        if (!empty($this->session->userdata('filtro_pedido_cliente_nome'))) {
            $this->db->like('clientes.cliente_nome', $this->session->userdata('filtro_pedido_cliente_nome'));
        }

        if (!empty($this->session->userdata('filtro_pedido_data_emissao'))) {
            $pedido_data_emissao = data_br_para_en($this->session->userdata('filtro_pedido_data_emissao'));

            $this->db->where('pedidos.pedido_data_emissao', $pedido_data_emissao);
        }

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        $this->db->where('pedido_rascunho', 'N');
        $this->db->order_by('pedido_id', 'DESC');

        return $this->db->get()->result_array();
    }

    public function conta_todos()
    {
        if (!empty($this->session->userdata('filtro_pedido_cliente'))) {
            $this->db->like('pedido_cliente', $this->session->userdata('filtro_pedido_cliente'));
        }

        if (!empty($this->session->userdata('filtro_pedido_data_emissao'))) {
            $pedido_data_emissao = data_br_para_en($this->session->userdata('filtro_pedido_data_emissao'));

            $this->db->where('pedidos.pedido_data_emissao', $pedido_data_emissao);
        }

        $this->db->where('pedido_rascunho', 'N');

        return $this->db->count_all_results('pedidos');
    }

    public function lista_por_sequencia_entrega($pedido_id) {
        $pedido_entregador = $this->lista_por_id($pedido_id)['pedido_entregador'];
        
        $this->db->select('MAX(pedido_sequencia_entrega) AS pedido_sequencia_entrega');
        $this->db->from('pedidos');
        $this->db->where('pedido_data_emissao', date('Y-m-d'));
        $this->db->where('pedido_entregador', $pedido_entregador);
        
        return $this->db->get()->row_array();
    }

    public function lista_por_id($pedido_id)
    {
        $this->db->select('pedidos.*, clientes.cliente_nome AS pedido_cliente_nome');
        $this->db->from('pedidos');
        $this->db->join('clientes', 'clientes.cliente_id = pedidos.pedido_cliente', 'left outer');
        $this->db->where('pedido_id', $pedido_id);

        return $this->db->get()->row_array();
    }

    public function lista_valor_total($pedido_id)
    {
        $this->db->select('SUM(pedidoitem_valor_total) AS pedido_valor_total');
        $this->db->from('pedidositens');
        $this->db->where('pedidoitem_pedido', $pedido_id);

        return $this->db->get()->row_array();
    }

    public function salvar($acao, $dados)
    {
        $dados['pedido_data_emissao'] = data_br_para_en($dados['pedido_data_emissao']);

        if ($acao === 'criar') {
            $this->db->insert('pedidos', $dados);

            return $this->db->insert_id();
        }

        if ($acao === 'alterar') {
            $pedido_id = $dados['pedido_id'];
            unset($dados['pedido_id']);

            $this->db->where('pedido_id', $pedido_id);

            return $this->db->update('pedidos', $dados);
        }
    }

    public function deletar($pedido_id)
    {
        $this->db->where('pedido_id', $pedido_id);

        return $this->db->delete('pedidos');
    }

    public function atualiza_valor_total($dados)
    {
        $pedido_id = $dados['pedido_id'];
        unset($dados['pedido_id']);

        $this->db->set('pedido_valor_total', $dados['pedido_valor_total']);

        $this->db->where('pedido_id', $pedido_id);

        return $this->db->update('pedidos');
    }

    public function atualiza_status($dados)
    {
        $pedido_id = $dados['id'];
        unset($dados['id']);
        
        $this->db->set('pedido_status', $dados['others']);
        
        $this->db->where('pedido_id', $pedido_id);

        return $this->db->update('pedidos');
    }

    public function atualiza_sequencia_entrega($dados)
    {
        $pedido_id = $dados['id'];
        unset($dados['id']);
        
        $this->db->set('pedido_sequencia_entrega', $dados['sequence']);
        $this->db->where('pedido_id', $pedido_id);
        
        return $this->db->update('pedidos');
    }
}
