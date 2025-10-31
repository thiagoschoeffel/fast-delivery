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
        <div class="form-row mb-4">
            <div class="col-12 col-md-4 mb-4 mb-md-0">
                <small class="d-block">Código</small>
                <h3 class="m-0 font-weight-bold"><?= $pedido['pedido_id'] ?></h3>
            </div>
            <div class="col-12 col-md-4 mb-4 mb-md-0">
                <small class="d-block">Valor Total</small>
                <h3 class="m-0 font-weight-bold">R$ <?= number_format($pedido['pedido_valor_total'], 2, ',', '.') ?></h3>
            </div>
            <div class="col-12 col-md-4 mb-0">
                <small class="d-block">Status</small>
                <h3 class="m-0 font-weight-bold text-<?= ($pedido['pedido_status'] === 'A' ? 'primary' : ($pedido['pedido_status'] === 'P' ? 'warning' : ($pedido['pedido_status'] === 'E' ? 'warning' : ($pedido['pedido_status'] === 'D' ? 'danger' : 'success')))) ?>">
                <?= ($pedido['pedido_status'] === 'A' ? 'ABERTO' : ($pedido['pedido_status'] === 'P' ? 'PRODUÇÃO' : ($pedido['pedido_status'] === 'E' ? 'EMBALAGEM' : ($pedido['pedido_status'] === 'D' ? 'ENTREGA' : 'CONCLUÍDO')))) ?>
                </h3>
            </div>
        </div>
        <ul class="nav nav-pills mb-4" id="j_pills">
            <li class="nav-item">
                <a href="#dados_gerais" class="nav-link active" data-toggle="pill">
                    <i class="fas fa-file fa-fw"></i>
                    Dados Gerais
                </a>
            </li>
            <li class="nav-item">
                <a href="#itens" class="nav-link" data-toggle="pill">
                    <i class="fas fa-utensils fa-fw"></i>
                    Itens
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="dados_gerais" class="tab-pane active">
                <form action="<?= base_url('pedidos/editar/salvar') ?>" method="post" class="j_send_form">
                    <div class="form-row">
                        <div class="form-group col-12 col-md-3">
                            <label for="pedido_data_emissao">Data Emissão</label>
                            <input type="text" name="pedido_data_emissao" id="pedido_data_emissao" class="form-control j_datepicker" value="<?= date('d/m/Y', strtotime($pedido['pedido_data_emissao'])) ?>">
                        </div>
                        <div class="form-group col-12 col-md-9">
                            <label for="pedido_cliente">Cliente</label>
                            <div class="form-row">
                                <div class="col-12 col-md-2 mb-2 mb-md-0">
                                    <input type="text" name="pedido_cliente" id="pedido_cliente" class="form-control" placeholder="Código" value="<?= $pedido['pedido_cliente'] ?>" data-action="<?= base_url('cliente/buscar_por_codigo') ?>" data-token-name="<?= $this->security->get_csrf_token_name() ?>" data-token-value="<?= $this->security->get_csrf_hash() ?>">
                                </div>
                                <div class="col-12 col-md-10">
                                    <input type="text" name="pedido_cliente_nome" id="pedido_cliente_nome" class="form-control j_text_upper" placeholder="Nome" value="<?= $pedido['pedido_cliente_nome'] ?>" data-action="<?= base_url('cliente/buscar_por_nome') ?>" data-token-name="<?= $this->security->get_csrf_token_name() ?>" data-token-value="<?= $this->security->get_csrf_hash() ?>">
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
                                <?php
                                foreach ($clientesenderecos as $clienteendereco) :
                                    echo '<option value="' . $clienteendereco['clienteendereco_id'] . '" data-entregador="' . $clienteendereco['clienteendereco_entregador'] . '"';
                                    echo ($clienteendereco['clienteendereco_id'] == $pedido['pedido_clienteendereco']) ? 'selected' : '';
                                    echo '>Endereço: ' . $clienteendereco['clienteendereco_logradouro'] . ', ' . $clienteendereco['clienteendereco_numero'] . ' - ' . $clienteendereco['clienteendereco_complemento'] . ' | Bairro: ' . $clienteendereco['clienteendereco_bairro'] . ' | Cidade: ' . $clienteendereco['clienteendereco_cidade'] . ' | UF: ' . $clienteendereco['clienteendereco_uf'] . '</option>';
                                endforeach;
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-12 col-md-6">
                            <label for="pedido_entregador">Entregador</label>
                            <select name="pedido_entregador" id="pedido_entregador" class="form-control">
                                <?php
                                foreach ($entregadores as $entregador) :
                                    echo '<option value="' . $entregador['entregador_id'] . '" ';
                                    echo ($entregador['entregador_id'] == $pedido['pedido_entregador']) ? 'selected' : '';
                                    echo '>' . $entregador['entregador_nome'] . '</option>';
                                endforeach;
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="pedido_formapagamento">Forma Pagamento</label>
                            <select name="pedido_formapagamento" id="pedido_formapagamento" class="form-control">
                                <?php
                                foreach ($formaspagamento as $formapagamento) :
                                    echo '<option value="' . $formapagamento['formapagamento_id'] . '" ';
                                    echo ($formapagamento['formapagamento_id'] == $pedido['pedido_formapagamento']) ? 'selected' : '';
                                    echo '>' . $formapagamento['formapagamento_descricao'] . '</option>';
                                endforeach;
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-12">
                            <label for="pedido_observacao">Observações</label>
                            <textarea name="pedido_observacao" id="pedido_observacao" class="form-control j_text_upper" rows="5"><?= $pedido['pedido_observacao'] ?></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                    <input type="hidden" name="pedido_id" value="<?= $pedido['pedido_id'] ?>">
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
            <div id="itens" class="tab-pane">
                <div class="row mb-4">
                    <div class="col-12">
                        <form action="<?= base_url('pedidositens/cadastrar/salvar') ?>" method="post" class="j_send_form">
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label for="pedidoitem_produto">Produto</label>
                                    <div class="form-row">
                                        <div class="col-12 col-md-2 mb-2 mb-md-0">
                                            <input type="text" name="pedidoitem_produto" id="pedidoitem_produto" class="form-control" placeholder="Código" value="" data-action="<?= base_url('produto/buscar_por_codigo') ?>" data-token-name="<?= $this->security->get_csrf_token_name() ?>" data-token-value="<?= $this->security->get_csrf_hash() ?>">
                                        </div>
                                        <div class="col-12 col-md-10">
                                            <input type="text" name="pedidoitem_produto_descricao" id="pedidoitem_produto_descricao" class="form-control j_text_upper" placeholder="Nome" value="" data-action="<?= base_url('produto/buscar_por_nome') ?>" data-token-name="<?= $this->security->get_csrf_token_name() ?>" data-token-value="<?= $this->security->get_csrf_hash() ?>">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-12">
                                            <div id="pedidoitem_produto_autocomplete" class="list-group mt-2">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-12 col-md-4">
                                    <label for="pedidoitem_quantidade">Quantidade</label>
                                    <input type="text" name="pedidoitem_quantidade" id="pedidoitem_quantidade" class="form-control j_mask_decimal">
                                </div>
                                <div class="form-group col-12 col-md-4">
                                    <label for="pedidoitem_valor_unitario">Valor Unitário</label>
                                    <input type="text" name="pedidoitem_valor_unitario" id="pedidoitem_valor_unitario" class="form-control j_mask_money" readonly>
                                </div>
                                <div class="form-group col-12 col-md-4">
                                    <label for="pedidoitem_valor_total">Valor Total</label>
                                    <input type="text" name="pedidoitem_valor_total" id="pedidoitem_valor_total" class="form-control j_mask_money" readonly>
                                </div>
                            </div>
                            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                            <input type="hidden" name="pedidoitem_pedido" value="<?= $pedido['pedido_id'] ?>">
                            <input type="hidden" name="action" value="create">
                            <div class="row">
                                <div class="col-12 d-flex justify-content-md-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-plus-circle fa-fw"></i>
                                        Adicionar Produto
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive main_table">
                            <table class="table table-striped">
                                <thead class="bg-light">
                                    <tr>
                                        <th></th>
                                        <th>Código</th>
                                        <th>Produto</th>
                                        <th>Quantidade</th>
                                        <th>Valor Unitário</th>
                                        <th>Valor Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pedidositens as $pedidoitem) : ?>
                                        <tr>
                                            <td>
                                                <div class="dropdown show">
                                                    <a href="#" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                                        <i class="fas fa-cog fa-fw"></i>
                                                        Opções
                                                    </a>
                                                    <div class="dropdown-menu">
                                                        <a href="#" data-message="Confirma a exclusão?" data-action="<?= base_url('pedidositens/deletar') ?>" data-id="<?= $pedidoitem['pedidoitem_id'] ?>" data-others="<?= $pedidoitem['pedidoitem_pedido'] ?>" data-token-name="<?= $this->security->get_csrf_token_name() ?>" data-token-value="<?= $this->security->get_csrf_hash() ?>" class="dropdown-item j_send_action">
                                                            <i class="fas fa-trash-alt fa-fw"></i>
                                                            Excluír
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?= $pedidoitem['pedidoitem_id'] ?></td>
                                            <td><?= $pedidoitem['pedidoitem_produto_descricao'] ?></td>
                                            <td><?= number_format($pedidoitem['pedidoitem_quantidade'], 4, ',', '.'); ?></td>
                                            <td>R$ <?= number_format($pedidoitem['pedidoitem_valor_unitario'], 2, ',', '.'); ?></td>
                                            <td>R$ <?= number_format($pedidoitem['pedidoitem_valor_total'], 2, ',', '.'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>