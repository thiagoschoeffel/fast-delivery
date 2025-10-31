<?php

defined('BASEPATH') or exit('No direct script access allowed');

class App extends CI_Controller
{

    public function index()
    {
        if ($this->session->has_userdata('usuario')) {
            redirect('inicio');
        } else {
            redirect('login');
        }
    }

    public function login()
    {
        if ($this->session->has_userdata('usuario')) {
            redirect('inicio');
        }

        $this->load->view('template/header_html');
        $this->load->view('template/login');
        $this->load->view('template/footer_html');
    }

    public function dashboard()
    {
        if (!$this->session->has_userdata('usuario')) {
            redirect('login');
        }

        $this->load->model('dashboard_model', 'model_dashboard');
        $this->load->model('cliente_model', 'model_cliente');

        $dados['ticket_medio'] = $this->model_dashboard->lista_ticket_medio()['ticket_medio'];
        $dados['clientes_aniversariantes'] = $this->model_cliente->lista_aniversariantes_hoje();

        $footer['script'] = base_url('assets/js/dashboard.js');

        $this->load->view('template/header_html');
        $this->load->view('template/header');
        $this->load->view('template/dashboard', $dados);
        $this->load->view('template/footer');
        $this->load->view('template/footer_html', $footer);
    }

    public function dashboard_grafico() {
        if (!$this->input->is_ajax_request()) {
            redirect('login');
        }

        $this->load->model('dashboard_model', 'model_dashboard');

        $grafico_quantidade = [];
        $grafico_valor = [];

        $dados = $this->model_dashboard->lista_grafico();

        for($i = 0; $i < count($dados); $i++) {
            $grafico_quantidade['etiquetas'][$i] = data_en_para_br($dados[$i]['pedido_data_emissao']);
            $grafico_quantidade['valores'][$i] = $dados[$i]['pedido_quantidade'];
            $grafico_valor['etiquetas'][$i] = data_en_para_br($dados[$i]['pedido_data_emissao']);
            $grafico_valor['valores'][$i] = $dados[$i]['pedido_valor'];
        }

        $response['grafico_quantidade'] = $grafico_quantidade;
        $response['grafico_valor'] = $grafico_valor;

        echo json_encode($response);
        return;
    }

    public function producao()
    {
        if (!$this->session->has_userdata('usuario')) {
            redirect('login');
        }

        $footer['script'] = base_url('assets/js/producao.js');

        $this->load->view('template/header_html');
        $this->load->view('template/header');
        $this->load->view('template/producao');
        $this->load->view('template/footer');
        $this->load->view('template/footer_html', $footer);
    }

    public function producao_filtrar()
    {
        if (!$this->input->is_ajax_request()) {
            redirect('login');
        }

        $this->load->model('dashboard_model', 'model_dashboard');

        $response = $this->model_dashboard->lista_producao();

        echo json_encode($response);
        return;
    }

    public function embalagem()
    {
        if (!$this->session->has_userdata('usuario')) {
            redirect('login');
        }

        $this->load->model('dashboard_model', 'model_dashboard');

        $dados['entregadores'] = $this->model_dashboard->lista_embalagem_entregador();

        for ($i = 0; $i < count($dados['entregadores']); $i++) {
            $dados['entregadores'][$i]['pedidos'] = $this->model_dashboard->lista_embalagem_entregador_pedido($dados['entregadores'][$i]['entregador_nome']);

            for ($j = 0; $j < count($dados['entregadores'][$i]['pedidos']); $j++) {
                $dados['entregadores'][$i]['pedidos'][$j]['itens'] = $this->model_dashboard->lista_embalagem_entregador_pedido_item($dados['entregadores'][$i]['entregador_nome'], $dados['entregadores'][$i]['pedidos'][$j]['pedido_id']);
            }
        }

        $footer['script'] = base_url('assets/js/embalagem.js');

        $this->load->view('template/header_html');
        $this->load->view('template/header');
        $this->load->view('template/embalagem', $dados);
        $this->load->view('template/footer');
        $this->load->view('template/footer_html', $footer);
    }

    public function entrega()
    {
        if (!$this->session->has_userdata('usuario')) {
            redirect('login');
        }

        $this->load->model('dashboard_model', 'model_dashboard');

        $dados['entregadores'] = $this->model_dashboard->lista_entrega_entregador();

        for ($i = 0; $i < count($dados['entregadores']); $i++) {
            $dados['entregadores'][$i]['pedidos'] = $this->model_dashboard->lista_entrega_entregador_pedido($dados['entregadores'][$i]['entregador_nome']);
        }

        $footer['script'] = base_url('assets/js/entrega.js');

        $this->load->view('template/header_html');
        $this->load->view('template/header');
        $this->load->view('template/entrega', $dados);
        $this->load->view('template/footer');
        $this->load->view('template/footer_html', $footer);
    }

    public function entrega_imprimir($entregador_id)
    {
        if (!$this->session->has_userdata('usuario')) {
            redirect('login');
        }

        $this->load->model('dashboard_model', 'model_dashboard');

        $dados['entregadores'] = $this->model_dashboard->lista_entrega_entregador($entregador_id);

        for ($i = 0; $i < count($dados['entregadores']); $i++) {
            $dados['entregadores'][$i]['pedidos'] = $this->model_dashboard->lista_entrega_entregador_pedido($dados['entregadores'][$i]['entregador_nome']);
        }

        $this->load->view('impressoes/entrega', $dados);
    }

    public function processar_login()
    {
        if (!$this->input->is_ajax_request()) {
            redirect('login');
        }

        $dados = [
            'usuario_email' => strtolower(filter_var($this->input->post('usuario_email'), FILTER_SANITIZE_EMAIL, FILTER_SANITIZE_SPECIAL_CHARS)),
            'usuario_senha' => filter_var($this->input->post('usuario_senha'), FILTER_SANITIZE_SPECIAL_CHARS),
            'recaptchaResponse' => filter_var($this->input->post('g-recaptcha-response'), FILTER_SANITIZE_STRING),
        ];

        $this->form_validation->set_data($dados);

        $this->form_validation->set_rules('usuario_email', 'e-mail', 'required|valid_email');
        $this->form_validation->set_rules('usuario_senha', 'senha', 'required');


        if (!$this->form_validation->run()) {
            $response['error'] = [
                'type' => 'danger',
                'message' => validation_errors('<span class="d-block">', '</span>')
            ];

            echo json_encode($response);
            return;
        }

        $this->load->model('usuario_model', 'model_usuario');

        $usuario = $this->model_usuario->lista_por_email($dados['usuario_email']);

        if (!$usuario) {
            $response['error'] = [
                'type' => 'danger',
                'message' => 'E-Mail e/ou Senha inválidos.',
            ];

            echo json_encode($response);
            return;
        }

        if (!password_verify($dados['usuario_senha'], $usuario['usuario_senha'])) {
            $response['error'] = [
                'type' => 'danger',
                'message' => 'E-Mail e/ou Senha inválidos.',
            ];

            echo json_encode($response);
            return;
        }

        unset($usuario['senha']);

        $this->model_usuario->atualiza_data_ultimo_login($usuario['usuario_id']);

        $this->session->set_userdata('usuario', $usuario);
        $this->session->set_userdata('filtro_pedido_data_emissao', date('d/m/Y'));

        $this->session->set_flashdata('mensagem_sistema', [
            'tipo' => 'success',
            'conteudo' => 'Login efetuado com sucesso, bem-vindo!',
        ]);

        $response['redirect'] = base_url('inicio');

        echo json_encode($response);
        return;
    }

    public function logout()
    {
        if (!$this->input->is_ajax_request()) {
            redirect('login');
        }

        $this->session->unset_userdata('usuario');

        $this->session->set_flashdata('mensagem_sistema', [
            'tipo' => 'success',
            'conteudo' => 'Logout efetuado com sucesso, até mais!',
        ]);

        $response['redirect'] = base_url('login');

        echo json_encode($response);
        return;
    }
}
