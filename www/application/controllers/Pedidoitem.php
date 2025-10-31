<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pedidoitem extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (!$this->session->has_userdata('usuario')) {
            redirect('login');
        }

        $this->load->model('pedidoitem_model', 'model_pedidoitem');
    }

    public function salvar()
    {
        if (!$this->input->is_ajax_request()) {
            redirect('pedidos');
        }

        $dados = [
            'pedidoitem_pedido' => filter_var($this->input->post('pedidoitem_pedido'), FILTER_SANITIZE_NUMBER_INT),
            'pedidoitem_produto' => filter_var($this->input->post('pedidoitem_produto'), FILTER_SANITIZE_NUMBER_INT),
            'pedidoitem_quantidade' => filter_var(str_replace(',', '.', str_replace('.', '', $this->input->post('pedidoitem_quantidade'))), FILTER_SANITIZE_STRING),
            'pedidoitem_valor_unitario' => filter_var(str_replace(',', '.', str_replace('.', '', $this->input->post('pedidoitem_valor_unitario'))), FILTER_SANITIZE_STRING),
        ];

        $dados['pedidoitem_quantidade'] = floatval($dados['pedidoitem_quantidade']);
        $dados['pedidoitem_valor_unitario'] = floatval($dados['pedidoitem_valor_unitario']);
        $dados['pedidoitem_valor_total'] = $dados['pedidoitem_quantidade'] * $dados['pedidoitem_valor_unitario'];

        $this->form_validation->set_data($dados);

        $this->form_validation->set_rules('pedidoitem_pedido', 'pedido', 'required|integer|numeric|is_natural_no_zero');
        $this->form_validation->set_rules('pedidoitem_produto', 'produto', 'required|integer|numeric|is_natural_no_zero');
        $this->form_validation->set_rules('pedidoitem_quantidade', 'quantidade', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('pedidoitem_valor_unitario', 'valor unitário', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('pedidoitem_valor_total', 'valor total', 'required|numeric|greater_than[0]');

        if (!$this->form_validation->run()) {
            $response['error'] = [
                'type' => 'danger',
                'message' => validation_errors('<span class="d-block">', '</span>')
            ];

            echo json_encode($response);
            return;
        }

        $pedidoitem = $this->model_pedidoitem->salvar('criar', $dados);

        if (!$pedidoitem) {
            $response['error'] = [
                'type' => 'danger',
                'message' => 'Ocorreu um erro ao cadastrar o item.',
            ];

            echo json_encode($response);
            return;
        }



        $this->load->model('pedido_model', 'model_pedido');

        $pedido = [
            'pedido_id' => $dados['pedidoitem_pedido'],
            'pedido_valor_total' => $this->model_pedido->lista_valor_total($dados['pedidoitem_pedido'])['pedido_valor_total']
        ];

        $this->model_pedido->atualiza_valor_total($pedido);



        $this->session->set_flashdata('mensagem_sistema', [
            'tipo' => 'success',
            'conteudo' => 'O item foi cadastrado com sucesso.',
        ]);

        $response['reload'] = true;

        echo json_encode($response);
        return;
    }

    public function deletar()
    {
        if (!$this->input->is_ajax_request()) {
            redirect('pedidos');
        }

        $dados = [
            'id' => filter_var($this->input->post('id'), FILTER_SANITIZE_NUMBER_INT),
            'others' => filter_var($this->input->post('others'), FILTER_SANITIZE_STRING)
        ];

        $this->form_validation->set_data($dados);

        $this->form_validation->set_rules('id', 'item', 'required|integer|numeric|is_natural_no_zero');

        if (!$this->form_validation->run()) {
            $response['error'] = [
                'type' => 'danger',
                'message' => validation_errors('<span class="d-block">', '</span>')
            ];

            echo json_encode($response);
            return;
        }

        $pedidoitem = $this->model_pedidoitem->deletar($dados['id']);

        if (!$pedidoitem) {
            $response['error'] = [
                'type' => 'danger',
                'message' => 'Ocorreu um erro ao deletar o item.',
            ];

            echo json_encode($response);
            return;
        }


        
        $this->load->model('pedido_model', 'model_pedido');

        $pedido = [
            'pedido_id' => $dados['others'],
            'pedido_valor_total' => $this->model_pedido->lista_valor_total($dados['others'])['pedido_valor_total']
        ];

        $this->model_pedido->atualiza_valor_total($pedido);



        $this->session->set_flashdata('mensagem_sistema', [
            'tipo' => 'success',
            'conteudo' => 'O item foi deletado com sucesso.',
        ]);

        $response['reload'] = true;

        echo json_encode($response);
        return;
    }
}
