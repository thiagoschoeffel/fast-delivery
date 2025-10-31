<div class="row align-items-center mb-4">
    <div class="col-12 col-md-6 mb-4 mb-md-0">
        <div class="h4 mb-0">
            <i class="fas fa-shopping-bag fa-fw"></i>
            Pedidos
        </div>
    </div>
    <div class="col-12 col-md-6 text-md-right">
        <a href="<?= base_url('pedidos/cadastrar') ?>" class="btn btn-primary">
            <i class="fas fa-plus-circle fa-fw"></i>
            Novo Pedido
        </a>
    </div>
</div>
<?php
if (!empty($this->session->flashdata('mensagem_sistema'))) {
    echo '<div class="alert alert-' . $this->session->flashdata('mensagem_sistema')['tipo'] . ' alert-dismissible fade show mb-4">' . $this->session->flashdata('mensagem_sistema')['conteudo'] . '<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>';
}
?>
<div class="j_message"></div>
<form action="<?= base_url('pedidos/filtrar') ?>" method="post" class="mb-4 j_send_form">
    <div class="form-row">
        <div class="form-group col-12 col-md-8">
            <label for="pedido_cliente_nome">Cliente</label>
            <input type="text" name="pedido_cliente_nome" id="pedido_cliente_nome" class="form-control" value="<?= ($this->session->userdata('filtro_pedido_cliente')) ? $this->session->userdata('filtro_pedido_cliente') : '' ?>">
        </div>
        <div class="form-group col-12 col-md-4">
            <label for="pedido_data_emissao">Data Emissão</label>
            <input type="text" name="pedido_data_emissao" id="pedido_data_emissao" class="form-control j_datepicker" value="<?= ($this->session->userdata('filtro_pedido_data_emissao')) ? $this->session->userdata('filtro_pedido_data_emissao') : '' ?>">
        </div>
    </div>
    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
    <div class="row">
        <div class="col-12 d-flex justify-content-md-end">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search fa-fw"></i>
                Buscar
            </button>
        </div>
    </div>
</form>
<div class="row">
    <div class="col-12">
        <div class="table-responsive main_table">
            <table class="table table-striped">
                <thead class="bg-light">
                    <tr>
                        <th></th>
                        <th>Código</th>
                        <th>Status</th>
                        <th>Data Emissão</th>
                        <th>Cliente</th>
                        <th>Forma Pagamento</th>
                        <th>Valor Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pedidos as $pedido) : ?>
                        <tr>
                            <td class="main_table_action">
                                <div class="dropdown show">
                                    <a href="#" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                        <i class="fas fa-cog fa-fw"></i>
                                        Opções
                                    </a>
                                    <div class="dropdown-menu">
                                        <?php if ($pedido['pedido_status'] != 'C') { ?>
                                            <a href="#" data-message="Deseja realmente concluír o pedido?" data-action="<?= base_url('pedidos/editar/atualizar_status') ?>" data-id="<?= $pedido['pedido_id'] ?>" data-others="C" data-token-name="<?= $this->security->get_csrf_token_name() ?>" data-token-value="<?= $this->security->get_csrf_hash() ?>" class="dropdown-item j_send_action">
                                                <i class="fas fa-thumbs-up fa-fw"></i>
                                                Concluír
                                            </a>
                                        <?php } ?>
                                        <?php if ($pedido['pedido_status'] != 'P') { ?>
                                            <a href="#" data-message="Deseja realmente produzir o pedido?" data-action="<?= base_url('pedidos/editar/atualizar_status') ?>" data-id="<?= $pedido['pedido_id'] ?>" data-others="P" data-token-name="<?= $this->security->get_csrf_token_name() ?>" data-token-value="<?= $this->security->get_csrf_hash() ?>" class="dropdown-item j_send_action">
                                                <i class="fas fa-utensils fa-fw"></i>
                                                Produzir
                                            </a>
                                        <?php } ?>
                                        <?php if ($pedido['pedido_status'] != 'A') { ?>
                                            <a href="#" data-message="Deseja realmente reabir o pedido?" data-action="<?= base_url('pedidos/editar/atualizar_status') ?>" data-id="<?= $pedido['pedido_id'] ?>" data-others="A" data-token-name="<?= $this->security->get_csrf_token_name() ?>" data-token-value="<?= $this->security->get_csrf_hash() ?>" class="dropdown-item j_send_action">
                                                <i class="fas fa-thumbs-down fa-fw"></i>
                                                Reabrir
                                            </a>
                                        <?php } ?>
                                        <a href="<?= base_url('pedidos/editar/' . $pedido['pedido_id']) ?>" class="dropdown-item">
                                            <i class="fas fa-edit fa-fw"></i>
                                            Editar
                                        </a>
                                        <a href="#" data-message="Deseja realmente duplicar o pedido?" data-action="<?= base_url('pedidos/duplicar') ?>" data-id="<?= $pedido['pedido_id'] ?>" data-token-name="<?= $this->security->get_csrf_token_name() ?>" data-token-value="<?= $this->security->get_csrf_hash() ?>" class="dropdown-item j_send_action">
                                            <i class="fas fa-clone fa-fw"></i>
                                            Duplicar
                                        </a>
                                        <a href="#" data-message="Confirma a exclusão?" data-action="<?= base_url('pedidos/deletar') ?>" data-id="<?= $pedido['pedido_id'] ?>" data-token-name="<?= $this->security->get_csrf_token_name() ?>" data-token-value="<?= $this->security->get_csrf_hash() ?>" class="dropdown-item j_send_action">
                                            <i class="fas fa-trash-alt fa-fw"></i>
                                            Excluír
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td><?= $pedido['pedido_id'] ?></td>
                            <td>
                                <span class="badge badge-<?= ($pedido['pedido_status'] === 'A' ? 'primary' : ($pedido['pedido_status'] === 'P' ? 'warning' : ($pedido['pedido_status'] === 'E' ? 'secondary' : ($pedido['pedido_status'] === 'D' ? 'danger' : 'success')))) ?>">
                                    <?= ($pedido['pedido_status'] === 'A' ? 'ABERTO' : ($pedido['pedido_status'] === 'P' ? 'PRODUÇÃO' : ($pedido['pedido_status'] === 'E' ? 'EMBALAGEM' : ($pedido['pedido_status'] === 'D' ? 'ENTREGA' : 'CONCLUÍDO')))) ?>
                                </span>
                            </td>
                            <td><?= (strtotime($pedido['pedido_data_emissao']) > 0) ? date('d/m/Y', strtotime($pedido['pedido_data_emissao'])) : '' ?></td>
                            <td><?= $pedido['pedido_cliente_nome'] ?></td>
                            <td><?= $pedido['pedido_formapagamento_descricao'] ?></td>
                            <td>R$ <?= number_format($pedido['pedido_valor_total'], 2, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?= $paginacao ?>
    </div>
</div>