<div class="row align-items-center mb-4">
    <div class="col-12 col-md-6 mb-4 mb-md-0">
        <div class="h4 mb-0">
            <i class="fas fa-shopping-bag fa-fw"></i>
            Pedidos
        </div>
    </div>
    <div class="col-12 col-md-6 text-md-right">
        <a href="<?= base_url('pedidos') ?>" class="btn btn-primary">
            <i class="fas fa-arrow-circle-left fa-fw"></i>
            Votlar
        </a>
    </div>
</div>
<?php
if (!empty($this->session->flashdata('mensagem_sistema'))) {
    echo '<div class="alert alert-' . $this->session->flashdata('mensagem_sistema')['tipo'] . ' alert-dismissible fade show mb-4">' . $this->session->flashdata('mensagem_sistema')['conteudo'] . '<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>';
}
?>
<div class="j_message"></div>
<div class="row mb-4">
    <div class="col-12">
        <ul class="nav nav-pills mb-4" id="j_pills">
            <li class="nav-item">
                <a href="#dados_gerais" class="nav-link active" data-toggle="pill">
                    <i class="fas fa-file fa-fw"></i>
                    Dados Gerais
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="dados_gerais" class="tab-pane active">
                <form action="<?= base_url('pedidos/cadastrar/salvar') ?>" method="post" class="j_send_form">
                    <div class="form-row">
                        <div class="form-group col-12 col-md-3">
                            <label for="pedido_data_emissao">Data Emissão</label>
                            <input type="text" name="pedido_data_emissao" id="pedido_data_emissao" class="form-control j_datepicker" value="<?= date('d/m/Y') ?>">
                        </div>
                        <div class="form-group col-12 col-md-9">
                            <label for="pedido_cliente">Cliente</label>
                            <div class="form-row">
                                <div class="col-12 col-md-2 mb-2 mb-md-0">
                                    <input type="text" name="pedido_cliente" id="pedido_cliente" class="form-control" placeholder="Código" value="" data-action="<?= base_url('cliente/buscar_por_codigo') ?>" data-token-name="<?= $this->security->get_csrf_token_name() ?>" data-token-value="<?= $this->security->get_csrf_hash() ?>">
                                </div>
                                <div class="col-12 col-md-10">
                                    <input type="text" name="pedido_cliente_nome" id="pedido_cliente_nome" class="form-control j_text_upper" placeholder="Nome" value="" data-action="<?= base_url('cliente/buscar_por_nome') ?>" data-token-name="<?= $this->security->get_csrf_token_name() ?>" data-token-value="<?= $this->security->get_csrf_hash() ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-12">
                            <div id="pedido_cliente_autocomplete" class="list-group mb-4" style="display: none;" data-action="<?= base_url('cliente/buscar_enderecos') ?>" data-token-name="<?= $this->security->get_csrf_token_name() ?>" data-token-value="<?= $this->security->get_csrf_hash() ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-12">
                            <label for="pedido_clienteendereco">Endereço</label>
                            <select name="pedido_clienteendereco" id="pedido_clienteendereco" class="form-control">
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-12 col-md-6">
                            <label for="pedido_entregador">Entregador</label>
                            <select name="pedido_entregador" id="pedido_entregador" class="form-control">
                                <option value="">SELECIONE...</option>
                                <?php
                                foreach ($entregadores as $entregador) :
                                    echo '<option value="' . $entregador['entregador_id'] . '" ';
                                    echo '>' . $entregador['entregador_nome'] . '</option>';
                                endforeach;
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="pedido_formapagamento">Forma Pagamento</label>
                            <select name="pedido_formapagamento" id="pedido_formapagamento" class="form-control">
                                <option value="">SELECIONE...</option>
                                <?php
                                foreach ($formaspagamento as $formapagamento) :
                                    echo '<option value="' . $formapagamento['formapagamento_id'] . '" ';
                                    echo '>' . $formapagamento['formapagamento_descricao'] . '</option>';
                                endforeach;
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-12">
                            <label for="pedido_observacao">Observações</label>
                            <textarea name="pedido_observacao" id="pedido_observacao" class="form-control j_text_upper" rows="5"></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                    <div class="row align-items-center">
                        <div class="col-12 d-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check-circle fa-fw"></i>
                                Salvar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>