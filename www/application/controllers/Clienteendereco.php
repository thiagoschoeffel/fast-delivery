<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Clienteendereco extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (!$this->session->has_userdata('usuario')) {
            redirect('login');
        }

        $this->load->model('clienteendereco_model', 'model_clienteendereco');
    }

    public function salvar()
    {
        if (!$this->input->is_ajax_request()) {
            redirect('clientes');
        }

        $dados = [
            'clienteendereco_cliente' => filter_var($this->input->post('clienteendereco_cliente'), FILTER_SANITIZE_NUMBER_INT),
            'clienteendereco_cep' => filter_var($this->input->post('clienteendereco_cep'), FILTER_SANITIZE_STRING),
            'clienteendereco_logradouro' => strtoupper(filter_var($this->input->post('clienteendereco_logradouro'), FILTER_SANITIZE_STRING)),
            'clienteendereco_numero' => filter_var($this->input->post('clienteendereco_numero'), FILTER_SANITIZE_NUMBER_INT),
            'clienteendereco_bairro' => strtoupper(filter_var($this->input->post('clienteendereco_bairro'), FILTER_SANITIZE_STRING)),
            'clienteendereco_complemento' => strtoupper(filter_var($this->input->post('clienteendereco_complemento'), FILTER_SANITIZE_STRING)),
            'clienteendereco_entregador' => strtoupper(filter_var($this->input->post('clienteendereco_entregador'), FILTER_SANITIZE_STRING)),
            'clienteendereco_cidade' => strtoupper(filter_var($this->input->post('clienteendereco_cidade'), FILTER_SANITIZE_STRING)),
            'clienteendereco_uf' => strtoupper(filter_var($this->input->post('clienteendereco_uf'), FILTER_SANITIZE_STRING)),
            'clienteendereco_status' => 'A',
        ];

        $this->form_validation->set_data($dados);

        $this->form_validation->set_rules('clienteendereco_cliente', 'cliente', 'required|integer|numeric|is_natural_no_zero');
        $this->form_validation->set_rules('clienteendereco_cep', 'CEP', 'required|exact_length[9]|regex_match[/[0-9]{5}\-[0-9]{3}/]');
        $this->form_validation->set_rules('clienteendereco_logradouro', 'logradouro', 'required|min_length[3]');
        $this->form_validation->set_rules('clienteendereco_numero', 'número', 'numeric|is_natural');
        $this->form_validation->set_rules('clienteendereco_bairro', 'bairro', 'required|min_length[1]');
        $this->form_validation->set_rules('clienteendereco_entregador', 'entregador', 'required|min_length[1]');
        $this->form_validation->set_rules('clienteendereco_cidade', 'cidade', 'required|min_length[1]');
        $this->form_validation->set_rules('clienteendereco_uf', 'estado', 'required|alpha|exact_length[2]');
        $this->form_validation->set_rules('clienteendereco_status', 'status', 'required|alpha|exact_length[1]|in_list[A,I]');

        if (!$this->form_validation->run()) {
            $response['error'] = [
                'type' => 'danger',
                'message' => validation_errors('<span class="d-block">', '</span>')
            ];

            echo json_encode($response);
            return;
        }

        $clienteendereco = $this->model_clienteendereco->salvar('criar', $dados);

        if (!$clienteendereco) {
            $response['error'] = [
                'type' => 'danger',
                'message' => 'Ocorreu um erro ao cadastrar o endereço.',
            ];

            echo json_encode($response);
            return;
        }

        $this->session->set_flashdata('mensagem_sistema', [
            'tipo' => 'success',
            'conteudo' => 'O endereço foi cadastrado com sucesso.',
        ]);

        $response['reload'] = true;

        echo json_encode($response);
        return;
    }

    public function atualizar_status()
    {
        if (!$this->input->is_ajax_request()) {
            redirect('clientes');
        }

        $dados = [
            'id' => filter_var($this->input->post('id'), FILTER_SANITIZE_NUMBER_INT)
        ];

        $this->form_validation->set_data($dados);

        $this->form_validation->set_rules('id', 'endereço', 'required|integer|numeric|is_natural_no_zero');

        if (!$this->form_validation->run()) {
            $response['error'] = [
                'type' => 'danger',
                'message' => validation_errors('<span class="d-block">', '</span>')
            ];

            echo json_encode($response);
            return;
        }

        $clienteendereco = $this->model_clienteendereco->lista_por_id($dados['id']);

        if (!$this->model_clienteendereco->altera_status($clienteendereco['clienteendereco_id'], $clienteendereco['clienteendereco_status'])) {
            $response['error'] = [
                'type' => 'danger',
                'message' => 'Ocorreu um erro ao alterar o status do endereço.',
            ];

            echo json_encode($response);
            return;
        }

        $this->session->set_flashdata('mensagem_sistema', [
            'tipo' => 'success',
            'conteudo' => 'O status do endereço foi alterado com sucesso.',
        ]);

        $response['reload'] = true;

        echo json_encode($response);
        return;
    }

    public function deletar()
    {
        if (!$this->input->is_ajax_request()) {
            redirect('clientes');
        }

        $dados = [
            'id' => filter_var($this->input->post('id'), FILTER_SANITIZE_NUMBER_INT)
        ];

        $this->form_validation->set_data($dados);

        $this->form_validation->set_rules('id', 'endereço', 'required|integer|numeric|is_natural_no_zero');

        if (!$this->form_validation->run()) {
            $response['error'] = [
                'type' => 'danger',
                'message' => validation_errors('<span class="d-block">', '</span>')
            ];

            echo json_encode($response);
            return;
        }

        $clienteendereco = $this->model_clienteendereco->deletar($dados['id']);

        if (!$clienteendereco) {
            $response['error'] = [
                'type' => 'danger',
                'message' => 'Ocorreu um erro ao deletar o endereço.',
            ];

            echo json_encode($response);
            return;
        }

        $this->session->set_flashdata('mensagem_sistema', [
            'tipo' => 'success',
            'conteudo' => 'O endereço foi deletado com sucesso.',
        ]);

        $response['reload'] = true;

        echo json_encode($response);
        return;
    }
}
