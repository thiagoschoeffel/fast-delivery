<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12 col-lg-3 mb-4 mb-lg-0">
            <div class="mb-4 p-3 text-center">
                <img src="<?= base_url('assets/images/logo.png') ?>" class="rounded-circle mb-2" height="116">
                <p class="mb-2"><?= $this->session->userdata('usuario')['usuario_nome'] ?></p>
                <a href="#" data-message="Deseja realmente sair?" data-action="<?= base_url('logout') ?>" data-id="" data-token-name="<?= $this->security->get_csrf_token_name() ?>" data-token-value="<?= $this->security->get_csrf_hash() ?>" class="btn btn-danger j_send_action">
                    <i class="fas fa-power-off fa-fw"></i>
                    Sair
                </a>
            </div>
            <div class="list-group">
                <?php
                $uri = $this->uri->segment(1);
                ?>
                <a href="<?= base_url('inicio') ?>" class="list-group-item list-group-item-action <?= ($uri === 'inicio') ? 'active' : '' ?>">
                    <i class="fas fa-chart-bar fa-fw"></i>
                    Dashboard
                </a>
                <a href="<?= base_url('entregadores') ?>" class="list-group-item list-group-item-action <?= ($uri === 'entregadores') ? 'active' : '' ?>">
                    <i class="fas fa-street-view fa-fw"></i>
                    Entregadores
                </a>
                <a href="<?= base_url('formaspagamento') ?>" class="list-group-item list-group-item-action <?= ($uri === 'formaspagamento') ? 'active' : '' ?>">
                    <i class="fas fa-wallet fa-fw"></i>
                    Formas de Pagamento
                </a>
                <a href="<?= base_url('clientes') ?>" class="list-group-item list-group-item-action <?= ($uri === 'clientes') ? 'active' : '' ?>">
                    <i class="fas fa-address-book fa-fw"></i>
                    Clientes
                </a>
                <a href="<?= base_url('produtos') ?>" class="list-group-item list-group-item-action <?= ($uri === 'produtos') ? 'active' : '' ?>">
                    <i class="fas fa-utensils fa-fw"></i>
                    Produtos
                </a>
                <a href="<?= base_url('pedidos') ?>" class="list-group-item list-group-item-action <?= ($uri === 'pedidos') ? 'active' : '' ?>">
                    <i class="fas fa-shopping-bag fa-fw"></i>
                    Pedidos
                </a>
            </div>
            <?php if ($uri === 'inicio') { ?>
                <div class="list-group mt-4">
                    <?php
                    $uri_1 = $this->uri->segment(2);
                    ?>
                    <a href="<?= base_url('inicio/producao') ?>" class="list-group-item list-group-item-action <?= ($uri === 'inicio' && $uri_1 === 'producao') ? 'active' : '' ?>">
                        <i class="fab fa-cloudscale fa-fw"></i>
                        Produção
                    </a>
                    <a href="<?= base_url('inicio/embalagem') ?>" class="list-group-item list-group-item-action <?= ($uri === 'inicio' && $uri_1 === 'embalagem') ? 'active' : '' ?>">
                        <i class="fab fa-cloudscale fa-fw"></i>
                        Embalagem
                    </a>
                    <a href="<?= base_url('inicio/entrega') ?>" class="list-group-item list-group-item-action <?= ($uri === 'inicio' && $uri_1 === 'entrega') ? 'active' : '' ?>">
                        <i class="fab fa-cloudscale fa-fw"></i>
                        Entrega
                    </a>
                </div>
            <?php } ?>
        </div>
        <div class="col-12 col-lg-9">
            <div class="card">
                <div class="card-body p-3 p-lg-5">