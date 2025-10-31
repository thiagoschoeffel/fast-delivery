<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Formapagamento extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (!$this->session->has_userdata('usuario')) {
            redirect('login');
        }

        $this->load->model('formapagamento_model', 'model_formapagamento');
    }

    public function index()
    {
        $total_linhas = $this->model_formapagamento->conta_todos();

        $paginacao_configuracao = [
            'base_url' => base_url('formaspagamento'),
            'per_page' => 10,
            'num_links' => 3,
            'uri_segment' => 2,
            'total_rows' => $total_linhas,
            'full_tag_open' => '<ul class="pagination mb-0 mt-4">',
            'full_tag_close' => '</ul>',
            'first_link' => '<i class="fas fa-angle-double-left"></i>',
            'last_link' => '<i class="fas fa-angle-double-right"></i>',
            'first_tag_open' => '<li class="page-item">',
            'first_tag_close' => '</li>',
            'prev_link' => '<i class="fas fa-angle-left"></i>',
            'prev_tag_open' => '<li class="page-item prev">',
            'prev_tag_close' => '</li>',
            'next_link' => '<i class="fas fa-angle-right"></i>',
            'next_tag_open' => '<li class="page-item">',
            'next_tag_close' => '</li>',
            'last_tag_open' => '<li class="page-item">',
            'last_tag_close' => '</li>',
            'cur_tag_open' => '<li class="page-item active"><a href="#" class="page-link">',
            'cur_tag_close' => '</a></li>',
            'num_tag_open' => '<li class="page-item">',
            'num_tag_close' => '</li>',
            'attributes' => ['class' => 'page-link']
        ];

        $this->load->library('pagination');

        $this->pagination->initialize($paginacao_configuracao);

        $paginacao_offset = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;

        $dados['formaspagamento'] = $this->model_formapagamento->lista_todos($paginacao_configuracao['per_page'], $paginacao_offset);
        $dados['paginacao'] = $this->pagination->create_links();

        $this->load->view('template/header_html');
        $this->load->view('template/header');
        $this->load->view('formaspagamento/listar', $dados);
        $this->load->view('template/footer');
        $this->load->view('template/footer_html');
    }

    public function filtrar()
    {
        if (!$this->input->is_ajax_request()) {
            redirect('formaspagamento');
        }

        $dados = [
            'formapagamento_descricao' => strtoupper(filter_var($this->input->post('formapagamento_descricao'), FILTER_SANITIZE_STRING))
        ];

        $this->session->set_userdata('filtro_formapagamento_descricao', $dados['formapagamento_descricao']);

        $response['redirect'] = base_url('formaspagamento');

        echo json_encode($response);
        return;
    }

    public function cadastrar()
    {
        $this->load->view('template/header_html');
        $this->load->view('template/header');
        $this->load->view('formaspagamento/cadastrar');
        $this->load->view('template/footer');
        $this->load->view('template/footer_html');
    }

    public function editar($formapagamento_id)
    {
        $formapagamento_id = filter_var($formapagamento_id, FILTER_SANITIZE_NUMBER_INT);

        $dados['formapagamento'] = $this->model_formapagamento->lista_por_id($formapagamento_id);

        if (!$dados['formapagamento']) {
            $this->session->set_flashdata('$dados', [
                'tipo' => 'warning',
                'conteudo' => 'O forma de pagamento que você tentou editar não existe.',
            ]);

            redirect('formaspagamento');
        }

        $this->load->view('template/header_html');
        $this->load->view('template/header');
        $this->load->view('formaspagamento/editar', $dados);
        $this->load->view('template/footer');
        $this->load->view('template/footer_html');
    }

    public function salvar()
    {
        if (!$this->input->is_ajax_request()) {
            redirect('formaspagamento');
        }

        $dados = [
            'formapagamento_descricao' => strtoupper(filter_var($this->input->post('formapagamento_descricao'), FILTER_SANITIZE_STRING)),
            'formapagamento_status' => strtoupper(filter_var($this->input->post('formapagamento_status'), FILTER_SANITIZE_STRING)),
        ];

        $this->form_validation->set_data($dados);

        $this->form_validation->set_rules('formapagamento_descricao', 'nome', 'required|min_length[3]');
        $this->form_validation->set_rules('formapagamento_status', 'status', 'required|alpha|exact_length[1]|in_list[A,I]');

        if (!$this->form_validation->run()) {
            $response['error'] = [
                'type' => 'danger',
                'message' => validation_errors('<span class="d-block">', '</span>')
            ];

            echo json_encode($response);
            return;
        }

        $formapagamento = $this->model_formapagamento->salvar('criar', $dados);

        if (!$formapagamento) {
            $response['error'] = [
                'type' => 'danger',
                'message' => 'Ocorreu um erro ao cadastrar o forma de pagamento.',
            ];

            echo json_encode($response);
            return;
        }

        $this->session->set_flashdata('mensagem_sistema', [
            'tipo' => 'success',
            'conteudo' => 'O forma de pagamento foi cadastrado com sucesso.',
        ]);

        $response['redirect'] = base_url('formaspagamento/editar/' . $formapagamento);

        echo json_encode($response);
        return;
    }

    public function atualizar()
    {
        if (!$this->input->is_ajax_request()) {
            redirect('formaspagamento');
        }

        $dados = [
            'formapagamento_id' => filter_var($this->input->post('id'), FILTER_SANITIZE_NUMBER_INT),
            'formapagamento_descricao' => strtoupper(filter_var($this->input->post('formapagamento_descricao'), FILTER_SANITIZE_STRING)),
            'formapagamento_status' => strtoupper(filter_var($this->input->post('formapagamento_status'), FILTER_SANITIZE_STRING)),
        ];

        $this->form_validation->set_data($dados);

        $this->form_validation->set_rules('formapagamento_id', 'código', 'required|numeric|integer');
        $this->form_validation->set_rules('formapagamento_descricao', 'nome', 'required|min_length[3]');
        $this->form_validation->set_rules('formapagamento_status', 'status', 'required|alpha|exact_length[1]|in_list[A,I]');

        if (!$this->form_validation->run()) {
            $response['error'] = [
                'type' => 'danger',
                'message' => validation_errors('<span class="d-block">', '</span>')
            ];

            echo json_encode($response);
            return;
        }

        $formapagamento = $this->model_formapagamento->salvar('alterar', $dados);

        if (!$formapagamento) {
            $response['error'] = [
                'type' => 'danger',
                'message' => 'Ocorreu um erro ao editar o forma de pagamento.',
            ];

            echo json_encode($response);
            return;
        }

        $this->session->set_flashdata('mensagem_sistema', [
            'tipo' => 'success',
            'conteudo' => 'O forma de pagamento foi editado com sucesso.',
        ]);

        $response['redirect'] = base_url('formaspagamento');

        echo json_encode($response);
        return;
    }

    public function deletar()
    {
        if (!$this->input->is_ajax_request()) {
            redirect('formaspagamento');
        }

        $dados = [
            'id' => filter_var($this->input->post('id'), FILTER_SANITIZE_NUMBER_INT)
        ];

        $this->form_validation->set_data($dados);

        $this->form_validation->set_rules('id', 'forma de pagamento', 'required|integer|numeric|is_natural_no_zero');

        if (!$this->form_validation->run()) {
            $response['error'] = [
                'type' => 'danger',
                'message' => validation_errors('<span class="d-block">', '</span>')
            ];

            echo json_encode($response);
            return;
        }

        $formapagamento = $this->model_formapagamento->deletar($dados['id']);

        if (!$formapagamento) {
            $response['error'] = [
                'type' => 'danger',
                'message' => 'Ocorreu um erro ao deletar o forma de pagamento.',
            ];

            echo json_encode($response);
            return;
        }

        $this->session->set_flashdata('mensagem_sistema', [
            'tipo' => 'success',
            'conteudo' => 'O forma de pagamento foi deletado com sucesso.',
        ]);

        $response['reload'] = true;

        echo json_encode($response);
        return;
    }
}
