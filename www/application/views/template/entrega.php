<div class="row align-items-center mb-4">
    <div class="col-12 col-md-6 mb-4 mb-md-0">
        <div class="h4 mb-2">
            <i class="fab fa-cloudscale fa-fw"></i>
            Dashboard / Entrega
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
        <p class="mb-0">Abaixo estão listados as entregas de hoje <strong class="d-inline-block py-1 px-2 rounded bg-danger text-white"><?= date('d/m/Y') ?></strong></p>
    </div>
</div>
<div class="row j_dashboard_entrega">
    <?php foreach ($entregadores as $entregador) : ?>
        <div class="col-12">
            <div class="card mt-4 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between mb-4">
                        <div class="col-12 col-md-8 mb-4 mb-md-0">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-street-view fa-2x fa-fw"></i>
                                <div class="ml-2">
                                    <p class="mb-0 small">Entregador</p>
                                    <h5 class="mb-0 font-weight-bold"><?= $entregador['entregador_nome'] ?></h5>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4 d-flex justify-content-md-end">
                            <a href="<?= base_url('inicio/entrega/imprimir/'. $entregador['entregador_id']) ?>" target="_blank" class="btn btn-primary">
                                <i class="fas fa-print fa-fw"></i>
                                Imprimir
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>SEQUÊNCIA</th>
                                            <th>PEDIDO</th>
                                            <th>CLIENTE</th>
                                            <th>FORMA PAGAMENTO</th>
                                            <th>VALOR TOTAL</th>
                                            <th>ENDEREÇO</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($entregador['pedidos'] as $pedido) : ?>
                                            <tr>
                                                <td>
                                                    <input type="text" name="pedido_<?= $pedido['pedido_id'] ?>" class="form-control text-center j_send_input" value="<?= $pedido['pedido_sequencia_entrega'] ?>" data-id="<?= $pedido['pedido_id'] ?>" data-action="<?= base_url('pedidos/editar/atualizar_sequencia_entrega') ?>" data-token-name="<?= $this->security->get_csrf_token_name() ?>" data-token-value="<?= $this->security->get_csrf_hash() ?>">
                                                </td>
                                                <td><?= $pedido['pedido_id'] ?></td>
                                                <td><?= $pedido['cliente_nome'] ?></td>
                                                <td><?= $pedido['formapagamento_descricao'] ?></td>
                                                <td>R$ <?= number_format($pedido['pedido_valor_total'], 2, ',', '.') ?></td>
                                                <td><?= $pedido['clienteendereco_logradouro'] ?>, <?= $pedido['clienteendereco_numero'] ?>, <?= $pedido['clienteendereco_complemento'] ?> - <?= $pedido['clienteendereco_bairro'] ?> - <?= $pedido['clienteendereco_cidade'] ?>/<?= $pedido['clienteendereco_uf'] ?></td>
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