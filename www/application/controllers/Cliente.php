<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Cliente extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (!$this->session->has_userdata('usuario')) {
            redirect('login');
        }

        $this->load->model('cliente_model', 'model_cliente');
        $this->load->model('clienteendereco_model', 'model_clienteendereco');
        $this->load->model('entregador_model', 'model_entregador');
    }

    public function index()
    {
        $total_linhas = $this->model_cliente->conta_todos();

        $paginacao_configuracao = [
            'base_url' => base_url('clientes'),
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

        $dados['clientes'] = $this->model_cliente->lista_todos($paginacao_configuracao['per_page'], $paginacao_offset);
        $dados['paginacao'] = $this->pagination->create_links();

        $this->load->view('template/header_html');
        $this->load->view('template/header');
        $this->load->view('clientes/listar', $dados);
        $this->load->view('template/footer');
        $this->load->view('template/footer_html');
    }

    public function filtrar()
    {
        if (!$this->input->is_ajax_request()) {
            redirect('clientes');
        }

        $dados = [
            'cliente_nome' => strtoupper(filter_var($this->input->post('cliente_nome'), FILTER_SANITIZE_STRING))
        ];

        $this->session->set_userdata('filtro_cliente_nome', $dados['cliente_nome']);

        $response['redirect'] = base_url('clientes');

        echo json_encode($response);
        return;
    }

    public function buscar_por_codigo()
    {
        if (!$this->input->is_ajax_request()) {
            redirect('clientes');
        }

        $dados = [
            'cliente_codigo' => filter_var($this->input->post('id'), FILTER_SANITIZE_NUMBER_INT)
        ];

        $response = $this->model_cliente->lista_por_id($dados['cliente_codigo']);

        if (!$response) {
            $response['error'] = [
                'type' => 'primary',
                'message' => 'Nenhum cliente encontrado com o código informado.',
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
            redirect('clientes');
        }

        $dados = [
            'cliente_nome' => filter_var($this->input->post('text'), FILTER_SANITIZE_STRIPPED)
        ];

        $response = $this->model_cliente->lista_por_nome($dados['cliente_nome']);

        echo json_encode($response);
        return;
    }

    public function buscar_enderecos()
    {
        if (!$this->input->is_ajax_request()) {
            redirect('clientes');
        }

        $dados = [
            'cliente_id' => filter_var($this->input->post('id'), FILTER_SANITIZE_NUMBER_INT)
        ];

        $this->load->model('clienteendereco_model', 'model_clienteendereco');

        $response = $this->model_clienteendereco->lista_todos_por_cliente($dados['cliente_id']);

        echo json_encode($response);
        return;
    }

    public function cadastrar()
    {
        $this->load->view('template/header_html');
        $this->load->view('template/header');
        $this->load->view('clientes/cadastrar');
        $this->load->view('template/footer');
        $this->load->view('template/footer_html');
    }

    public function editar($cliente_id)
    {
        $cliente_id = filter_var($cliente_id, FILTER_SANITIZE_NUMBER_INT);

        $dados['cliente'] = $this->model_cliente->lista_por_id($cliente_id);
        $dados['clientesenderecos'] = $this->model_clienteendereco->lista_todos($cliente_id);
        $dados['entregadores'] = $this->model_entregador->lista_todos(null, null, 'N', 'A');

        if (!$dados['cliente']) {
            $this->session->set_flashdata('mensagem_sistema', [
                'tipo' => 'warning',
                'conteudo' => 'O cliente que você tentou editar não existe.',
            ]);

            redirect('clientes');
        }

        $footer['script'] = base_url('assets/js/cliente.js');

        $this->load->view('template/header_html');
        $this->load->view('template/header');
        $this->load->view('clientes/editar', $dados);
        $this->load->view('template/footer');
        $this->load->view('template/footer_html', $footer);
    }

    public function salvar()
    {
        if (!$this->input->is_ajax_request()) {
            redirect('clientes');
        }

        $dados = [
            'cliente_nome' => strtoupper(filter_var($this->input->post('cliente_nome'), FILTER_SANITIZE_STRING)),
            'cliente_telefone' => filter_var($this->input->post('cliente_telefone'), FILTER_SANITIZE_STRING),
            'cliente_data_nascimento' => filter_var($this->input->post('cliente_data_nascimento'), FILTER_SANITIZE_STRING),
            'cliente_observacao' => strtoupper(filter_var($this->input->post('cliente_observacao'), FILTER_SANITIZE_STRING)),
            'cliente_status' => strtoupper(filter_var($this->input->post('cliente_status'), FILTER_SANITIZE_STRING)),
        ];

        $this->form_validation->set_data($dados);

        $this->form_validation->set_rules('cliente_nome', 'nome', 'required|min_length[3]');
        $this->form_validation->set_rules('cliente_telefone', 'telefone', 'required|min_length[13]|max_length[15]|regex_match[/\([0-9]{2}\)\s[0-9]{4,5}\-[0-9]{4}/]');
        $this->form_validation->set_rules('cliente_data_nascimento', 'data nascimento', 'callback_valid_date');
        $this->form_validation->set_rules('cliente_status', 'status', 'required|alpha|exact_length[1]|in_list[A,I]');

        if (!$this->form_validation->run()) {
            $response['error'] = [
                'type' => 'danger',
                'message' => validation_errors('<span class="d-block">', '</span>')
            ];

            echo json_encode($response);
            return;
        }

        $cliente = $this->model_cliente->salvar('criar', $dados);

        if (!$cliente) {
            $response['error'] = [
                'type' => 'danger',
                'message' => 'Ocorreu um erro ao cadastrar o cliente.',
            ];

            echo json_encode($response);
            return;
        }

        $this->session->set_flashdata('mensagem_sistema', [
            'tipo' => 'success',
            'conteudo' => 'O cliente foi cadastrado com sucesso.',
        ]);

        $response['redirect'] = base_url('clientes/editar/' . $cliente);

        echo json_encode($response);
        return;
    }

    public function atualizar()
    {
        if (!$this->input->is_ajax_request()) {
            redirect('clientes');
        }

        $dados = [
            'cliente_id' => filter_var($this->input->post('cliente_id'), FILTER_SANITIZE_NUMBER_INT),
            'cliente_nome' => strtoupper(filter_var($this->input->post('cliente_nome'), FILTER_SANITIZE_STRING)),
            'cliente_telefone' => filter_var($this->input->post('cliente_telefone'), FILTER_SANITIZE_STRING),
            'cliente_data_nascimento' => filter_var($this->input->post('cliente_data_nascimento'), FILTER_SANITIZE_STRING),
            'cliente_observacao' => strtoupper(filter_var($this->input->post('cliente_observacao'), FILTER_SANITIZE_STRING)),
            'cliente_status' => strtoupper(filter_var($this->input->post('cliente_status'), FILTER_SANITIZE_STRING)),
        ];

        $this->form_validation->set_data($dados);

        $this->form_validation->set_rules('cliente_id', 'código', 'required|numeric|integer');
        $this->form_validation->set_rules('cliente_nome', 'nome', 'required|min_length[3]');
        $this->form_validation->set_rules('cliente_telefone', 'telefone', 'required|min_length[13]|max_length[15]|regex_match[/\([0-9]{2}\)\s[0-9]{4,5}\-[0-9]{4}/]');
        $this->form_validation->set_rules('cliente_data_nascimento', 'data nascimento', 'callback_valid_date');
        $this->form_validation->set_rules('cliente_status', 'status', 'required|alpha|exact_length[1]|in_list[A,I]');

        if (!$this->form_validation->run()) {
            $response['error'] = [
                'type' => 'danger',
                'message' => validation_errors('<span class="d-block">', '</span>')
            ];

            echo json_encode($response);
            return;
        }

        $cliente = $this->model_cliente->salvar('alterar', $dados);

        if (!$cliente) {
            $response['error'] = [
                'type' => 'danger',
                'message' => 'Ocorreu um erro ao editar o cliente.',
            ];

            echo json_encode($response);
            return;
        }

        $this->session->set_flashdata('mensagem_sistema', [
            'tipo' => 'success',
            'conteudo' => 'O cliente foi editado com sucesso.',
        ]);

        $response['redirect'] = base_url('clientes');

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

        $this->form_validation->set_rules('id', 'cliente', 'required|integer|numeric|is_natural_no_zero');

        if (!$this->form_validation->run()) {
            $response['error'] = [
                'type' => 'danger',
                'message' => validation_errors('<span class="d-block">', '</span>')
            ];

            echo json_encode($response);
            return;
        }

        $cliente = $this->model_cliente->deletar($dados['id']);

        if (!$cliente) {
            $response['error'] = [
                'type' => 'danger',
                'message' => 'Ocorreu um erro ao deletar o cliente.',
            ];

            echo json_encode($response);
            return;
        }

        $this->session->set_flashdata('mensagem_sistema', [
            'tipo' => 'success',
            'conteudo' => 'O cliente foi deletado com sucesso.',
        ]);

        $response['reload'] = true;

        echo json_encode($response);
        return;
    }

    public function valid_date($date)
    {
        if (empty($date)) {
            $this->form_validation->set_message('valid_date', 'O campo {field} é obrigatório.');
            return false;
        }

        $d = explode('/', $date);

        if (!checkdate($d[1], $d[0], $d[2])) {
            $this->form_validation->set_message('valid_date', 'O campo {field} deve conter uma data válida.');
            return false;
        } else {
            return true;
        }
    }
}
