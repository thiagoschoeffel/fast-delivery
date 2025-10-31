<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Entregador extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (!$this->session->has_userdata('usuario')) {
            redirect('login');
        }

        $this->load->model('entregador_model', 'model_entregador');
    }

    public function index()
    {
        $total_linhas = $this->model_entregador->conta_todos();

        $paginacao_configuracao = [
            'base_url' => base_url('entregadores'),
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

        $dados['entregadores'] = $this->model_entregador->lista_todos($paginacao_configuracao['per_page'], $paginacao_offset);
        $dados['paginacao'] = $this->pagination->create_links();

        $this->load->view('template/header_html');
        $this->load->view('template/header');
        $this->load->view('entregadores/listar', $dados);
        $this->load->view('template/footer');
        $this->load->view('template/footer_html');
    }

    public function filtrar()
    {
        if (!$this->input->is_ajax_request()) {
            redirect('entregadores');
        }

        $dados = [
            'entregador_nome' => strtoupper(filter_var($this->input->post('entregador_nome'), FILTER_SANITIZE_STRING))
        ];

        $this->session->set_userdata('filtro_entregador_nome', $dados['entregador_nome']);

        $response['redirect'] = base_url('entregadores');

        echo json_encode($response);
        return;
    }

    public function cadastrar()
    {
        $this->load->view('template/header_html');
        $this->load->view('template/header');
        $this->load->view('entregadores/cadastrar');
        $this->load->view('template/footer');
        $this->load->view('template/footer_html');
    }

    public function editar($entregador_id)
    {
        $entregador_id = filter_var($entregador_id, FILTER_SANITIZE_NUMBER_INT);

        $dados['entregador'] = $this->model_entregador->lista_por_id($entregador_id);

        if (!$dados['entregador']) {
            $this->session->set_flashdata('$dados', [
                'tipo' => 'warning',
                'conteudo' => 'O entregador que você tentou editar não existe.',
            ]);

            redirect('entregadores');
        }

        $this->load->view('template/header_html');
        $this->load->view('template/header');
        $this->load->view('entregadores/editar', $dados);
        $this->load->view('template/footer');
        $this->load->view('template/footer_html');
    }

    public function salvar()
    {
        if (!$this->input->is_ajax_request()) {
            redirect('entregadores');
        }

        $dados = [
            'entregador_nome' => strtoupper(filter_var($this->input->post('entregador_nome'), FILTER_SANITIZE_STRING)),
            'entregador_telefone' => filter_var($this->input->post('entregador_telefone'), FILTER_SANITIZE_STRING),
            'entregador_status' => strtoupper(filter_var($this->input->post('entregador_status'), FILTER_SANITIZE_STRING)),
        ];

        $this->form_validation->set_data($dados);

        $this->form_validation->set_rules('entregador_nome', 'nome', 'required|min_length[3]');
        $this->form_validation->set_rules('entregador_telefone', 'telefone', 'required|min_length[13]|max_length[15]|regex_match[/\([0-9]{2}\)\s[0-9]{4,5}\-[0-9]{4}/]');
        $this->form_validation->set_rules('entregador_status', 'status', 'required|alpha|exact_length[1]|in_list[A,I]');

        if (!$this->form_validation->run()) {
            $response['error'] = [
                'type' => 'danger',
                'message' => validation_errors('<span class="d-block">', '</span>')
            ];

            echo json_encode($response);
            return;
        }

        $entregador = $this->model_entregador->salvar('criar', $dados);

        if (!$entregador) {
            $response['error'] = [
                'type' => 'danger',
                'message' => 'Ocorreu um erro ao cadastrar o entregador.',
            ];

            echo json_encode($response);
            return;
        }

        $this->session->set_flashdata('mensagem_sistema', [
            'tipo' => 'success',
            'conteudo' => 'O entregador foi cadastrado com sucesso.',
        ]);

        $response['redirect'] = base_url('entregadores/editar/' . $entregador);

        echo json_encode($response);
        return;
    }

    public function atualizar()
    {
        if (!$this->input->is_ajax_request()) {
            redirect('entregadores');
        }

        $dados = [
            'entregador_id' => filter_var($this->input->post('entregador_id'), FILTER_SANITIZE_NUMBER_INT),
            'entregador_nome' => strtoupper(filter_var($this->input->post('entregador_nome'), FILTER_SANITIZE_STRING)),
            'entregador_telefone' => filter_var($this->input->post('entregador_telefone'), FILTER_SANITIZE_STRING),
            'entregador_status' => strtoupper(filter_var($this->input->post('entregador_status'), FILTER_SANITIZE_STRING)),
        ];

        $this->form_validation->set_data($dados);

        $this->form_validation->set_rules('entregador_id', 'código', 'required|numeric|integer');
        $this->form_validation->set_rules('entregador_nome', 'nome', 'required|min_length[3]');
        $this->form_validation->set_rules('entregador_telefone', 'telefone', 'required|min_length[13]|max_length[15]|regex_match[/\([0-9]{2}\)\s[0-9]{4,5}\-[0-9]{4}/]');
        $this->form_validation->set_rules('entregador_status', 'status', 'required|alpha|exact_length[1]|in_list[A,I]');

        if (!$this->form_validation->run()) {
            $response['error'] = [
                'type' => 'danger',
                'message' => validation_errors('<span class="d-block">', '</span>')
            ];

            echo json_encode($response);
            return;
        }

        $entregador = $this->model_entregador->salvar('alterar', $dados);

        if (!$entregador) {
            $response['error'] = [
                'type' => 'danger',
                'message' => 'Ocorreu um erro ao editar o entregador.',
            ];

            echo json_encode($response);
            return;
        }

        $this->session->set_flashdata('mensagem_sistema', [
            'tipo' => 'success',
            'conteudo' => 'O entregador foi editado com sucesso.',
        ]);

        $response['redirect'] = base_url('entregadores');

        echo json_encode($response);
        return;
    }

    public function deletar()
    {
        if (!$this->input->is_ajax_request()) {
            redirect('entregadores');
        }

        $dados = [
            'id' => filter_var($this->input->post('id'), FILTER_SANITIZE_NUMBER_INT)
        ];

        $this->form_validation->set_data($dados);

        $this->form_validation->set_rules('id', 'entregador', 'required|integer|numeric|is_natural_no_zero');

        if (!$this->form_validation->run()) {
            $response['error'] = [
                'type' => 'danger',
                'message' => validation_errors('<span class="d-block">', '</span>')
            ];

            echo json_encode($response);
            return;
        }

        $entregador = $this->model_entregador->deletar($dados['id']);

        if (!$entregador) {
            $response['error'] = [
                'type' => 'danger',
                'message' => 'Ocorreu um erro ao deletar o entregador.',
            ];

            echo json_encode($response);
            return;
        }

        $this->session->set_flashdata('mensagem_sistema', [
            'tipo' => 'success',
            'conteudo' => 'O entregador foi deletado com sucesso.',
        ]);

        $response['reload'] = true;

        echo json_encode($response);
        return;
    }
}
