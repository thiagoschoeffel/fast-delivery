<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Produto extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (!$this->session->has_userdata('usuario')) {
            redirect('login');
        }

        $this->load->model('produto_model', 'model_produto');
        $this->load->model('produtoestrutura_model', 'model_produtoestrutura');
    }

    public function index()
    {
        $total_linhas = $this->model_produto->conta_todos();

        $paginacao_configuracao = [
            'base_url' => base_url('produtos'),
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

        $dados['produtos'] = $this->model_produto->lista_todos($paginacao_configuracao['per_page'], $paginacao_offset);
        $dados['paginacao'] = $this->pagination->create_links();

        $this->load->view('template/header_html');
        $this->load->view('template/header');
        $this->load->view('produtos/listar', $dados);
        $this->load->view('template/footer');
        $this->load->view('template/footer_html');
    }

    public function filtrar()
    {
        if (!$this->input->is_ajax_request()) {
            redirect('produtos');
        }

        $dados = [
            'produto_descricao' => strtoupper(filter_var($this->input->post('produto_descricao'), FILTER_SANITIZE_STRING))
        ];

        $this->session->set_userdata('filtro_produto_descricao', $dados['produto_descricao']);

        $response['redirect'] = base_url('produtos');

        echo json_encode($response);
        return;
    }

    public function buscar_por_codigo()
    {
        if (!$this->input->is_ajax_request()) {
            redirect('produtos');
        }

        $dados = [
            'produto_codigo' => filter_var($this->input->post('id'), FILTER_SANITIZE_NUMBER_INT)
        ];

        $response = $this->model_produto->lista_por_id($dados['produto_codigo']);

        if (!$response) {
            $response['error'] = [
                'type' => 'primary',
                'message' => 'Nenhum produto encontrado com o código informado.',
            ];

            echo json_encode($response);
            return;
        }

        echo json_encode($response);
        return;
    }

    public function buscar_por_nome()
    {
        if (!$this->input->is_ajax_request()) {
            redirect('produtos');
        }

        $dados = [
            'produto_descricao' => filter_var($this->input->post('text'), FILTER_SANITIZE_STRIPPED)
        ];

        $response = $this->model_produto->lista_por_nome($dados['produto_descricao']);

        echo json_encode($response);
        return;
    }

    public function cadastrar()
    {
        $this->load->view('template/header_html');
        $this->load->view('template/header');
        $this->load->view('produtos/cadastrar');
        $this->load->view('template/footer');
        $this->load->view('template/footer_html');
    }

    public function editar($produto_id)
    {
        $produto_id = filter_var($produto_id, FILTER_SANITIZE_NUMBER_INT);

        $dados['produto'] = $this->model_produto->lista_por_id($produto_id);
        $dados['produtosestruturas'] = $this->model_produtoestrutura->lista_todos($produto_id);
        $dados['subprodutos'] = $this->model_produto->lista_todos(null, null, 'N', 'A');

        if (!$dados['produto']) {
            $this->session->set_flashdata('mensagem_sistema', [
                'tipo' => 'warning',
                'conteudo' => 'O produto que você tentou editar não existe.',
            ]);

            redirect('produtos');
        }

        $this->load->view('template/header_html');
        $this->load->view('template/header');
        $this->load->view('produtos/editar', $dados);
        $this->load->view('template/footer');
        $this->load->view('template/footer_html');
    }

    public function salvar()
    {
        if (!$this->input->is_ajax_request()) {
            redirect('produtos');
        }

        $dados = [
            'produto_descricao' => strtoupper(filter_var($this->input->post('produto_descricao'), FILTER_SANITIZE_STRING)),
            'produto_preco' => filter_var(str_replace(',', '.', str_replace('.', '', $this->input->post('produto_preco'))), FILTER_SANITIZE_STRING),
            'produto_status' => strtoupper(filter_var($this->input->post('produto_status'), FILTER_SANITIZE_STRING)),
        ];

        $this->form_validation->set_data($dados);

        $this->form_validation->set_rules('produto_descricao', 'descricao', 'required|min_length[3]');
        $this->form_validation->set_rules('produto_preco', 'preco', 'required|decimal');
        $this->form_validation->set_rules('produto_status', 'status', 'required|alpha|exact_length[1]|in_list[A,I]');

        if (!$this->form_validation->run()) {
            $response['error'] = [
                'type' => 'danger',
                'message' => validation_errors('<span class="d-block">', '</span>')
            ];

            echo json_encode($response);
            return;
        }

        $produto = $this->model_produto->salvar('criar', $dados);

        if (!$produto) {
            $response['error'] = [
                'type' => 'danger',
                'message' => 'Ocorreu um erro ao cadastrar o produto.',
            ];

            echo json_encode($response);
            return;
        }

        $this->session->set_flashdata('mensagem_sistema', [
            'tipo' => 'success',
            'conteudo' => 'O produto foi cadastrado com sucesso.',
        ]);

        $response['redirect'] = base_url('produtos/editar/' . $produto);

        echo json_encode($response);
        return;
    }

    public function atualizar()
    {
        if (!$this->input->is_ajax_request()) {
            redirect('produtos');
        }

        $dados = [
            'produto_id' => filter_var($this->input->post('produto_id'), FILTER_SANITIZE_NUMBER_INT),
            'produto_descricao' => strtoupper(filter_var($this->input->post('produto_descricao'), FILTER_SANITIZE_STRING)),
            'produto_preco' => filter_var(str_replace(',', '.', str_replace('.', '', $this->input->post('produto_preco'))), FILTER_SANITIZE_STRING),
            'produto_status' => strtoupper(filter_var($this->input->post('produto_status'), FILTER_SANITIZE_STRING)),
        ];

        $this->form_validation->set_data($dados);

        $this->form_validation->set_rules('produto_id', 'código', 'required|numeric|integer');
        $this->form_validation->set_rules('produto_descricao', 'descricao', 'required|min_length[3]');
        $this->form_validation->set_rules('produto_preco', 'preco', 'required|decimal');
        $this->form_validation->set_rules('produto_status', 'status', 'required|alpha|exact_length[1]|in_list[A,I]');

        if (!$this->form_validation->run()) {
            $response['error'] = [
                'type' => 'danger',
                'message' => validation_errors('<span class="d-block">', '</span>')
            ];

            echo json_encode($response);
            return;
        }

        $produto = $this->model_produto->salvar('alterar', $dados);

        if (!$produto) {
            $response['error'] = [
                'type' => 'danger',
                'message' => 'Ocorreu um erro ao editar o produto.',
            ];

            echo json_encode($response);
            return;
        }

        $this->session->set_flashdata('mensagem_sistema', [
            'tipo' => 'success',
            'conteudo' => 'O produto foi editado com sucesso.',
        ]);

        $response['redirect'] = base_url('produtos');

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

        $this->form_validation->set_rules('id', 'produto', 'required|integer|numeric|is_natural_no_zero');

        if (!$this->form_validation->run()) {
            $response['error'] = [
                'type' => 'danger',
                'message' => validation_errors('<span class="d-block">', '</span>')
            ];

            echo json_encode($response);
            return;
        }

        $produto = $this->model_produto->deletar($dados['id']);

        if (!$produto) {
            $response['error'] = [
                'type' => 'danger',
                'message' => 'Ocorreu um erro ao deletar o produto.',
            ];

            echo json_encode($response);
            return;
        }

        $this->session->set_flashdata('mensagem_sistema', [
            'tipo' => 'success',
            'conteudo' => 'O produto foi deletado com sucesso.',
        ]);

        $response['reload'] = true;

        echo json_encode($response);
        return;
    }
}
