<div class="row align-items-center mb-4">
    <div class="col-12 col-md-6 mb-4 mb-md-0">
        <div class="h4 mb-0">
            <i class="fas fa-chart-bar fa-fw"></i>
            Dashboard
        </div>
    </div>
</div>
<?php
if (!empty($this->session->flashdata('mensagem_sistema'))) {
    echo '<div class="alert alert-' . $this->session->flashdata('mensagem_sistema')['tipo'] . ' alert-dismissible fade show mb-4">' . $this->session->flashdata('mensagem_sistema')['conteudo'] . '<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>';
}
?>
<div class="j_message"></div>
<div class="row mb-4">
    <div class="col-12 col-md-6 mb-4 mb-md-0">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-birthday-cake fa-fw"></i>
                Aniversariantes do Dia
            </div>
            <div class="card-body">
                <h5 class="card-title font-weight-bold"><?= date('d/m/Y') ?></h5>
                <p class="card-text">Abaixo estão listados os clientes que fazem aniverário hoje.</p>
            </div>
            <div class="list-group list-group-flush">
                <?php foreach ($clientes_aniversariantes as $cliente_aniversariante) : ?>
                    <a href="<?= base_url('clientes/editar/' . $cliente_aniversariante['cliente_id']) ?>" class="list-group-item list-group-item-action"><?= $cliente_aniversariante['cliente_nome'] ?></a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-dollar-sign fa-fw"></i>
                Ticket Médio
            </div>
            <div class="card-body">
                <h1 class="mb-0 font-weight-bold">R$ <?= number_format($ticket_medio, 2, ',', '.') ?></h1>
            </div>
        </div>
    </div>
</div>
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-chart-line fa-fw"></i>
                Quantidade Total de Pedidos / Dia
            </div>
            <div class="card-body">
                <canvas id="j_quantidade_pedidos" width="100%"></canvas>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-chart-line fa-fw"></i>
                Valor Total de Pedidos / Dia
            </div>
            <div class="card-body">
                <canvas id="j_valor_pedidos" width="100%"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="j_dashboard_grafico" data-action="<?= base_url('inicio/grafico') ?>" data-token-name="<?= $this->security->get_csrf_token_name() ?>" data-token-value="<?= $this->security->get_csrf_hash() ?>"></div>