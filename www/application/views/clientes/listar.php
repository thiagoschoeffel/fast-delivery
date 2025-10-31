<div class="row align-items-center mb-4">
    <div class="col-12 col-md-6 mb-4 mb-md-0">
        <div class="h4 mb-0">
            <i class="fas fa-address-book fa-fw"></i>
            Clientes
        </div>
    </div>
    <div class="col-12 col-md-6 text-md-right">
        <a href="<?= base_url('clientes/cadastrar') ?>" class="btn btn-primary">
            <i class="fas fa-plus-circle fa-fw"></i>
            Novo Cliente
        </a>
    </div>
</div>
<?php
if (!empty($this->session->flashdata('mensagem_sistema'))) {
    echo '<div class="alert alert-' . $this->session->flashdata('mensagem_sistema')['tipo'] . ' alert-dismissible fade show mb-4">' . $this->session->flashdata('mensagem_sistema')['conteudo'] . '<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>';
}
?>
<div class="j_message"></div>
<form action="<?= base_url('clientes/filtrar') ?>" method="post" class="mb-4 j_send_form">
    <div class="form-row">
        <div class="form-group col-12">
            <label for="cliente_nome">Nome</label>
            <input type="text" name="cliente_nome" id="cliente_nome" class="form-control" value="<?= ($this->session->userdata('filtro_cliente_nome')) ? $this->session->userdata('filtro_cliente_nome') : '' ?>">
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
                        <th>Nome</th>
                        <th>Telefone</th>
                        <th>Data Nascimento</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clientes as $cliente) : ?>
                        <tr>
                            <td class="main_table_action">
                                <div class="dropdown show">
                                    <a href="#" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                        <i class="fas fa-cog fa-fw"></i>
                                        Opções
                                    </a>
                                    <div class="dropdown-menu">
                                        <a href="<?= base_url('clientes/editar/' . $cliente['cliente_id']) ?>" class="dropdown-item">
                                            <i class="fas fa-edit fa-fw"></i>
                                            Editar
                                        </a>
                                        <a href="#" data-message="Confirma a exclusão?" data-action="<?= base_url('clientes/deletar') ?>" data-id="<?= $cliente['cliente_id'] ?>" data-token-name="<?= $this->security->get_csrf_token_name() ?>" data-token-value="<?= $this->security->get_csrf_hash() ?>" class="dropdown-item j_send_action">
                                            <i class="fas fa-trash-alt fa-fw"></i>
                                            Excluír
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td><?= $cliente['cliente_id'] ?></td>
                            <td>
                                <span class="badge badge-<?= ($cliente['cliente_status'] === 'A' ? 'success' : 'danger') ?>">
                                    <?= ($cliente['cliente_status'] === 'A' ? 'ATIVO' : 'INATIVO') ?>
                                </span>
                            </td>
                            <td><?= $cliente['cliente_nome'] ?></td>
                            <td><?= $cliente['cliente_telefone'] ?></td>
                            <td><?= (strtotime($cliente['cliente_data_nascimento']) > 0) ? date('d/m/Y', strtotime($cliente['cliente_data_nascimento'])) : '' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?= $paginacao ?>
    </div>
</div>