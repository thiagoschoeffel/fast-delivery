<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Produtoestrutura extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (!$this->session->has_userdata('usuario')) {
            redirect('login');
        }

        $this->load->model('produtoestrutura_model', 'model_produtoestrutura');
    }

    public function salvar()
    {
        if (!$this->input->is_ajax_request()) {
            redirect('produtos');
        }

        $dados = [
            'produtoestrutura_produto' => filter_var($this->input->post('produtoestrutura_produto'), FILTER_SANITIZE_NUMBER_INT),
            'produtoestrutura_subproduto' => filter_var($this->input->post('produtoestrutura_subproduto'), FILTER_SANITIZE_STRING),
            'produtoestrutura_quantidade' => filter_var(str_replace(',', '.', str_replace('.', '', $this->input->post('produtoestrutura_quantidade'))), FILTER_SANITIZE_STRING)
        ];

        $this->form_validation->set_data($dados);

        $this->form_validation->set_rules('produtoestrutura_produto', 'produto', 'required|integer|numeric|is_natural_no_zero');
        $this->form_validation->set_rules('produtoestrutura_subproduto', 'subproduto', 'required|integer|numeric|is_natural_no_zero');
        $this->form_validation->set_rules('produtoestrutura_quantidade', 'quantidade', 'required|decimal|greater_than[0]');

        if (!$this->form_validation->run()) {
            $response['error'] = [
                'type' => 'danger',
                'message' => validation_errors('<span class="d-block">', '</span>')
            ];

            echo json_encode($response);
            return;
        }

        $produtoestrutura = $this->model_produtoestrutura->lista_por_produto_e_subproduto($dados['produtoestrutura_produto'], $dados['produtoestrutura_subproduto']);

        if ($produtoestrutura) {
            $nova_quantidade = $produtoestrutura['produtoestrutura_quantidade'] + $dados['produtoestrutura_quantidade'];

            $atualizar = $this->model_produtoestrutura->atualizar_quantidade($produtoestrutura['produtoestrutura_id'], $nova_quantidade);

            if (!$atualizar) {
                $response['error'] = [
                    'type' => 'danger',
                    'message' => 'Ocorreu um erro ao atualizar a quantidade do subproduto.',
                ];

                echo json_encode($response);
                return;
            }

            $this->session->set_flashdata('mensagem_sistema', [
                'tipo' => 'success',
                'conteudo' => 'A quantiade do subproduto foi atualizada com sucesso.',
            ]);

            $response['reload'] = true;

            echo json_encode($response);
            return;
        }

        $salvar = $this->model_produtoestrutura->salvar('criar', $dados);

        if (!$salvar) {
            $response['error'] = [
                'type' => 'danger',
                'message' => 'Ocorreu um erro ao cadastrar o subproduto.',
            ];

            echo json_encode($response);
            return;
        }

        $this->session->set_flashdata('mensagem_sistema', [
            'tipo' => 'success',
            'conteudo' => 'O subproduto foi cadastrado com sucesso.',
        ]);

        $response['reload'] = true;

        echo json_encode($response);
        return;
    }

    public function deletar()
    {
        if (!$this->input->is_ajax_request()) {
            redirect('produtos');
        }

        $dados = [
            'id' => filter_var($this->input->post('id'), FILTER_SANITIZE_NUMBER_INT)
        ];

        $this->form_validation->set_data($dados);

        $this->form_validation->set_rules('id', 'subproduto', 'required|integer|numeric|is_natural_no_zero');

        if (!$this->form_validation->run()) {
            $response['error'] = [
                'type' => 'danger',
                'message' => validation_errors('<span class="d-block">', '</span>')
            ];

            echo json_encode($response);
            return;
        }

        $produtoestrutura = $this->model_produtoestrutura->deletar($dados['id']);

        if (!$produtoestrutura) {
            $response['error'] = [
                'type' => 'danger',
                'message' => 'Ocorreu um erro ao deletar o subproduto.',
            ];

            echo json_encode($response);
            return;
        }

        $this->session->set_flashdata('mensagem_sistema', [
            'tipo' => 'success',
            'conteudo' => 'O subproduto foi deletado com sucesso.',
        ]);

        $response['reload'] = true;

        echo json_encode($response);
        return;
    }
}
