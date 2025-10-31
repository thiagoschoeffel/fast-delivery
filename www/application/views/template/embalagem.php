<div class="row align-items-center mb-4">
    <div class="col-12 col-md-6 mb-4 mb-md-0">
        <div class="h4 mb-2">
            <i class="fab fa-cloudscale fa-fw"></i>
            Dashboard / Embalagem
        </div>
    </div>
    <div class="col-12 col-md-6 text-md-right">
        <p class="mb-0 small"><i class="fas fa-history"></i> Atualiza em <span class="font-weight-bold j_contador_atualizacao"></span><span class="font-weight-bold">s</span></p>
    </div>
</div>
<?php
if (!empty($this->session->flashdata('mensagem_sistema'))) {
    echo '<div class="alert alert-' . $this->session->flashdata('mensagem_sistema')['tipo'] . ' alert-dismissible fade show mb-4">' . $this->session->flashdata('mensagem_sistema')['conteudo'] . '<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>';
}
?>
<div class="j_message"></div>
<div class="row">
    <div class="col-12">
        <p class="mb-0">Abaixo estão listados as quantidades que devem ser embaladas hoje <strong class="d-inline-block py-1 px-2 rounded bg-danger text-white"><?= date('d/m/Y') ?></strong></p>
    </div>
</div>
<div class="row j_dashboard_embalagem" data-action="<?= base_url('inicio/embalagem/filtrar') ?>" data-token-name="<?= $this->security->get_csrf_token_name() ?>" data-token-value="<?= $this->security->get_csrf_hash() ?>">
    <?php foreach ($entregadores as $entregador) : ?>
        <div class="col-12">
            <div class="card mt-4 shadow-sm">
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-street-view fa-2x fa-fw"></i>
                                <div class="ml-2">
                                    <p class="mb-0 small">Entregador</p>
                                    <h5 class="mb-0 font-weight-bold"><?= $entregador['entregador_nome'] ?></h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead class="bg-light">
                                        <tr>
                                            <th></th>
                                            <th>PEDIDO</th>
                                            <th>CLIENTE</th>
                                            <th>PRODUTOS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($entregador['pedidos'] as $pedido) : ?>
                                            <tr>
                                                <td>
                                                    <a href="#" data-message="" data-action="<?= base_url('pedidos/editar/atualizar_status') ?>" data-id="<?= $pedido['pedido_id'] ?>" data-others="D" data-token-name="<?= $this->security->get_csrf_token_name() ?>" data-token-value="<?= $this->security->get_csrf_hash() ?>" class="btn btn-success j_send_action">
                                                        <i class="fas fa-check-circle fa-fw"></i>
                                                        Finalizar Embalagem
                                                    </a>
                                                </td>
                                                <td><?= $pedido['pedido_id'] ?></td>
                                                <td><?= $pedido['cliente_nome'] ?></td>
                                                <td>
                                                    <?php foreach ($pedido['itens'] as $item) : ?>
                                                        <span class="d-inline-block">
                                                            <span class="d-flex align-items-center justify-content-start">
                                                                <span class="py-1 px-2 bg-dark text-white font-weight-bold rounded-left"><?= $item['produto_descricao'] ?></span>
                                                                <span class="py-1 px-2 bg-danger text-white font-weight-bold rounded-right"><?= number_format($item['pedidoitem_quantidade'], 0) ?></span>
                                                            </span>
                                                        </span>
                                                    <?php endforeach; ?>
                                                </td>
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
    <?php endforeach; ?>
</div>