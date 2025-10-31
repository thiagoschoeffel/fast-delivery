<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pedido extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (!$this->session->has_userdata('usuario')) {
            redirect('login');
        }

        $this->load->model('pedido_model', 'model_pedido');
    }

    public function index()
    {
        $total_linhas = $this->model_pedido->conta_todos();

        $paginacao_configuracao = [
            'base_url' => base_url('pedidos'),
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

        $dados['pedidos'] = $this->model_pedido->lista_todos($paginacao_configuracao['per_page'], $paginacao_offset);
        $dados['paginacao'] = $this->pagination->create_links();

        $this->load->view('template/header_html');
        $this->load->view('template/header');
        $this->load->view('pedidos/listar', $dados);
        $this->load->view('template/footer');
        $this->load->view('template/footer_html');
    }

    public function filtrar()
    {
        if (!$this->input->is_ajax_request()) {
            redirect('pedidos');
        }

        $dados = [
            'pedido_cliente_nome' => strtoupper(filter_var($this->input->post('pedido_cliente_nome'), FILTER_SANITIZE_STRING)),
            'pedido_data_emissao' => strtoupper(filter_var($this->input->post('pedido_data_emissao'), FILTER_SANITIZE_STRING))
        ];

        $this->session->set_userdata('filtro_pedido_cliente_nome', $dados['pedido_cliente_nome']);
        $this->session->set_userdata('filtro_pedido_data_emissao', $dados['pedido_data_emissao']);

        $response['redirect'] = base_url('pedidos');

        echo json_encode($response);
        return;
    }

    public function cadastrar()
    {
        $this->load->model('entregador_model', 'model_entregador');
        $this->load->model('formapagamento_model', 'model_formapagamento');

        $dados['entregadores'] = $this->model_entregador->lista_todos();
        $dados['formaspagamento'] = $this->model_formapagamento->lista_todos();

        $footer['script'] = base_url('assets/js/pedido.js');

        $this->load->view('template/header_html');
        $this->load->view('template/header');
        $this->load->view('pedidos/cadastrar', $dados);
        $this->load->view('template/footer');
        $this->load->view('template/footer_html', $footer);
    }

    public function editar($pedido_id)
    {
        $pedido_id = filter_var($pedido_id, FILTER_SANITIZE_NUMBER_INT);

        $dados['pedido'] = $this->model_pedido->lista_por_id($pedido_id);

        if (!$dados['pedido']) {
            $this->session->set_flashdata('mensagem_sistema', [
                'tipo' => 'warning',
                'conteudo' => 'O pedido que você tentou editar não existe.',
            ]);

            redirect('pedidos');
        }

        $this->load->model('clienteendereco_model', 'model_clienteendereco');
        $this->load->model('entregador_model', 'model_entregador');
        $this->load->model('formapagamento_model', 'model_formapagamento');
        $this->load->model('pedidoitem_model', 'model_pedidoitem');

        $dados['clientesenderecos'] = $this->model_clienteendereco->lista_todos_por_cliente($dados['pedido']['pedido_cliente']);
        $dados['entregadores'] = $this->model_entregador->lista_todos();
        $dados['formaspagamento'] = $this->model_formapagamento->lista_todos();
        $dados['pedidositens'] = $this->model_pedidoitem->lista_todos($dados['pedido']['pedido_id']);

        $footer['script'] = base_url('assets/js/pedido.js');

        $this->load->view('template/header_html');
        $this->load->view('template/header');
        $this->load->view('pedidos/editar', $dados);
        $this->load->view('template/footer');
        $this->load->view('template/footer_html', $footer);
    }

    public function salvar()
    {
        if (!$this->input->is_ajax_request()) {
            redirect('pedidos');
        }

        $dados = [
            'pedido_data_emissao' => filter_var($this->input->post('pedido_data_emissao'), FILTER_SANITIZE_STRING),
            'pedido_cliente' => filter_var($this->input->post('pedido_cliente'), FILTER_SANITIZE_NUMBER_INT),
            'pedido_clienteendereco' => filter_var($this->input->post('pedido_clienteendereco'), FILTER_SANITIZE_NUMBER_INT),
            'pedido_entregador' => filter_var($this->input->post('pedido_entregador'), FILTER_SANITIZE_NUMBER_INT),
            'pedido_formapagamento' => filter_var($this->input->post('pedido_formapagamento'), FILTER_SANITIZE_NUMBER_INT),
            'pedido_observacao' => filter_var($this->input->post('pedido_observacao'), FILTER_SANITIZE_STRING),
            'pedido_status' => 'P',
            'pedido_rascunho' => 'N'
        ];

        $this->form_validation->set_data($dados);

        $this->form_validation->set_rules('pedido_data_emissao', 'data emissão', 'callback_valid_date');
        $this->form_validation->set_rules('pedido_cliente', 'código cliente', 'required|numeric|integer');
        $this->form_validation->set_rules('pedido_clienteendereco', 'endereço cliente', 'required|numeric|integer');
        $this->form_validation->set_rules('pedido_entregador', 'entregador', 'required|numeric|integer');
        $this->form_validation->set_rules('pedido_formapagamento', 'forma pagamento', 'required|numeric|integer');

        if (!$this->form_validation->run()) {
            $response['error'] = [
                'type' => 'danger',
                'message' => validation_errors('<span class="d-block">', '</span>')
            ];

            echo json_encode($response);
            return;
        }

        $pedido = $this->model_pedido->salvar('criar', $dados);

        if (!$pedido) {
            $response['error'] = [
                'type' => 'danger',
                'message' => 'Ocorreu um erro ao cadastrar o pedido.',
            ];

            echo json_encode($response);
            return;
        }

        $this->session->set_flashdata('mensagem_sistema', [
            'tipo' => 'success',
            'conteudo' => 'O pedido foi cadastrado com sucesso.',
        ]);

        $response['redirect'] = base_url('pedidos/editar/' . $pedido . '#itens');

        echo json_encode($response);
        return;
    }

    public function atualizar()
    {
        if (!$this->input->is_ajax_request()) {
            redirect('pedidos');
        }

        $dados = [
            'pedido_id' => filter_var($this->input->post('pedido_id'), FILTER_SANITIZE_NUMBER_INT),
            'pedido_data_emissao' => filter_var($this->input->post('pedido_data_emissao'), FILTER_SANITIZE_STRING),
            'pedido_cliente' => filter_var($this->input->post('pedido_cliente'), FILTER_SANITIZE_NUMBER_INT),
            'pedido_clienteendereco' => filter_var($this->input->post('pedido_clienteendereco'), FILTER_SANITIZE_NUMBER_INT),
            'pedido_entregador' => filter_var($this->input->post('pedido_entregador'), FILTER_SANITIZE_NUMBER_INT),
            'pedido_formapagamento' => filter_var($this->input->post('pedido_formapagamento'), FILTER_SANITIZE_NUMBER_INT),
            'pedido_observacao' => filter_var($this->input->post('pedido_observacao'), FILTER_SANITIZE_STRING)
        ];

        $this->form_validation->set_data($dados);

        $this->form_validation->set_rules('pedido_id', 'código', 'required|numeric|integer');
        $this->form_validation->set_rules('pedido_data_emissao', 'data emissão', 'callback_valid_date');
        $this->form_validation->set_rules('pedido_cliente', 'código cliente', 'required|numeric|integer');
        $this->form_validation->set_rules('pedido_clienteendereco', 'endereço cliente', 'required|numeric|integer');
        $this->form_validation->set_rules('pedido_entregador', 'entregador', 'required|numeric|integer');
        $this->form_validation->set_rules('pedido_formapagamento', 'forma pagamento', 'required|numeric|integer');

        if (!$this->form_validation->run()) {
            $response['error'] = [
                'type' => 'danger',
                'message' => validation_errors('<span class="d-block">', '</span>')
            ];

            echo json_encode($response);
            return;
        }

        $pedido = $this->model_pedido->salvar('alterar', $dados);

        if (!$pedido) {
            $response['error'] = [
                'type' => 'danger',
                'message' => 'Ocorreu um erro ao editar o pedido.',
            ];

            echo json_encode($response);
            return;
        }

        $this->session->set_flashdata('mensagem_sistema', [
            'tipo' => 'success',
            'conteudo' => 'O pedido foi editado com sucesso.',
        ]);

        $response['redirect'] = base_url('pedidos/editar/' . $dados['pedido_id']);

        echo json_encode($response);
        return;
    }

    public function deletar()
    {
        if (!$this->input->is_ajax_request()) {
            redirect('pedido');
        }

        $dados = [
            'id' => filter_var($this->input->post('id'), FILTER_SANITIZE_NUMBER_INT)
        ];

        $this->form_validation->set_data($dados);

        $this->form_validation->set_rules('id', 'pedido', 'required|integer|numeric|is_natural_no_zero');

        if (!$this->form_validation->run()) {
            $response['error'] = [
                'type' => 'danger',
                'message' => validation_errors('<span class="d-block">', '</span>')
            ];

            echo json_encode($response);
            return;
        }

        $pedido = $this->model_pedido->deletar($dados['id']);

        if (!$pedido) {
            $response['error'] = [
                'type' => 'danger',
                'message' => 'Ocorreu um erro ao deletar o pedido.',
            ];

            echo json_encode($response);
            return;
        }

        $this->session->set_flashdata('mensagem_sistema', [
            'tipo' => 'success',
            'conteudo' => 'O pedido foi deletado com sucesso.',
        ]);

        $response['reload'] = true;

        echo json_encode($response);
        return;
    }

    public function atualizar_status()
    {
        if (!$this->input->is_ajax_request()) {
            redirect('pedido');
        }

        $dados = [
            'id' => filter_var($this->input->post('id'), FILTER_SANITIZE_NUMBER_INT),
            'others' => filter_var($this->input->post('others'), FILTER_SANITIZE_STRING),
            'sequence' => 0
        ];

        $this->form_validation->set_data($dados);

        $this->form_validation->set_rules('id', 'pedido', 'required|integer|numeric|is_natural_no_zero');
        $this->form_validation->set_rules('others', 'status', 'required|alpha|exact_length[1]|in_list[A,P,E,D,C]');

        if (!$this->form_validation->run()) {
            $response['error'] = [
                'type' => 'danger',
                'message' => validation_errors('<span class="d-block">', '</span>')
            ];

            echo json_encode($response);
            return;
        }

        if ($dados['others'] === 'D') {
            $dados['sequence'] = $this->model_pedido->lista_por_sequencia_entrega($dados['id'])['pedido_sequencia_entrega'] + 1;
        }

        $pedido_sequencia_entrega = $this->model_pedido->atualiza_sequencia_entrega($dados);

        if (!$pedido_sequencia_entrega) {
            $response['error'] = [
                'type' => 'danger',
                'message' => 'Ocorreu um erro ao atualizar a sequencia de entrega do pedido.',
            ];

            echo json_encode($response);
            return;
        }

        $pedido_status = $this->model_pedido->atualiza_status($dados);

        if (!$pedido_status) {
            $response['error'] = [
                'type' => 'danger',
                'message' => 'Ocorreu um erro ao atualizar o status do pedido.',
            ];

            echo json_encode($response);
            return;
        }

        $this->session->set_flashdata('mensagem_sistema', [
            'tipo' => 'success',
            'conteudo' => 'O status do pedido foi atualizado com sucesso.',
        ]);

        $response['reload'] = true;

        echo json_encode($response);
        return;
    }

    public function atualizar_sequencia_entrega()
    {
        if (!$this->input->is_ajax_request()) {
            redirect('inicio/entrega');
        }

        $dados = [
            'id' => filter_var($this->input->post('id'), FILTER_SANITIZE_NUMBER_INT),
            'sequence' => filter_var($this->input->post('others'), FILTER_SANITIZE_STRING)
        ];

        $this->form_validation->set_data($dados);

        $this->form_validation->set_rules('id', 'pedido', 'required|integer|numeric|is_natural_no_zero');
        $this->form_validation->set_rules('sequence', 'sequência entrega', 'required|integer|numeric|is_natural_no_zero');

        if (!$this->form_validation->run()) {
            $response['error'] = [
                'type' => 'danger',
                'message' => validation_errors('<span class="d-block">', '</span>')
            ];

            echo json_encode($response);
            return;
        }

        $pedido_sequencia_entrega = $this->model_pedido->atualiza_sequencia_entrega($dados);

        if (!$pedido_sequencia_entrega) {
            $response['error'] = [
                'type' => 'danger',
                'message' => 'Ocorreu um erro ao atualizar a sequencia de entrega do pedido.',
            ];

            echo json_encode($response);
            return;
        }

        $this->session->set_flashdata('mensagem_sistema', [
            'tipo' => 'success',
            'conteudo' => 'A sequencia de entrega do pedido foi atualizada com sucesso.',
        ]);

        $response['reload'] = true;

        echo json_encode($response);
        return;
    }

    public function duplicar()
    {
        if (!$this->input->is_ajax_request()) {
            redirect('pedido');
        }

        $dados = [
            'id' => filter_var($this->input->post('id'), FILTER_SANITIZE_NUMBER_INT)
        ];

        $this->form_validation->set_data($dados);

        $this->form_validation->set_rules('id', 'pedido', 'required|integer|numeric|is_natural_no_zero');

        if (!$this->form_validation->run()) {
            $response['error'] = [
                'type' => 'danger',
                'message' => validation_errors('<span class="d-block">', '</span>')
            ];

            echo json_encode($response);
            return;
        }

        $pedido_original = $this->model_pedido->lista_por_id($dados['id']);

        if (!$pedido_original) {
            $response['error'] = [
                'type' => 'warning',
                'message' => 'O pedido que você tentou duplicar não existe.',
            ];

            echo json_encode($response);
            return;
        }

        $dados_novo_pedido = [
            'pedido_data_emissao' => date('d/m/Y'),
            'pedido_cliente' => $pedido_original['pedido_cliente'],
            'pedido_clienteendereco' => $pedido_original['pedido_clienteendereco'],
            'pedido_entregador' => $pedido_original['pedido_entregador'],
            'pedido_formapagamento' => $pedido_original['pedido_formapagamento'],
            'pedido_observacao' => $pedido_original['pedido_observacao'],
            'pedido_valor_total' => $pedido_original['pedido_valor_total'],
            'pedido_status' => 'A',
            'pedido_rascunho' => 'N',
            'pedido_sequencia_entrega' => 0,
        ];

        $pedido_novo = $this->model_pedido->salvar('criar', $dados_novo_pedido);

        if (!$pedido_novo) {
            $response['error'] = [
                'type' => 'danger',
                'message' => 'Ocorreu um erro ao duplicar o pedido.',
            ];

            echo json_encode($response);
            return;
        }

        $this->load->model('pedidoitem_model', 'model_pedidoitem');

        $pedido_original_itens = $this->model_pedidoitem->lista_todos($pedido_original['pedido_id']);

        foreach ($pedido_original_itens as $pedido_item) {
            $dados_item = [
                'pedidoitem_pedido' => $pedido_novo,
                'pedidoitem_produto' => $pedido_item['pedidoitem_produto'],
                'pedidoitem_quantidade' => $pedido_item['pedidoitem_quantidade'],
                'pedidoitem_valor_unitario' => $pedido_item['pedidoitem_valor_unitario'],
                'pedidoitem_valor_total' => $pedido_item['pedidoitem_valor_total']
            ];

            $item = $this->model_pedidoitem->salvar('criar', $dados_item);

            if (!$item) {
                $response['error'] = [
                    'type' => 'danger',
                    'message' => 'Ocorreu um erro ao duplicar o pedido.',
                ];

                echo json_encode($response);
                return;
            }
        }

        $this->session->set_flashdata('mensagem_sistema', [
            'tipo' => 'success',
            'conteudo' => 'O pedido foi duplicado com sucesso.',
        ]);

        $response['redirect'] = base_url('pedidos/editar/' . $pedido_novo);

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
