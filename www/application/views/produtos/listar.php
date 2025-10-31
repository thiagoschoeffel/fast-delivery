<div class="row align-items-center mb-4">
    <div class="col-12 col-md-6 mb-4 mb-md-0">
        <div class="h4 mb-0">
            <i class="fas fa-utensils fa-fw"></i>
            Produtos
        </div>
    </div>
    <div class="col-12 col-md-6 text-md-right">
        <a href="<?= base_url('produtos/cadastrar') ?>" class="btn btn-primary">
            <i class="fas fa-plus-circle fa-fw"></i>
            Novo Produto
        </a>
    </div>
</div>
<?php
if (!empty($this->session->flashdata('mensagem_sistema'))) {
    echo '<div class="alert alert-' . $this->session->flashdata('mensagem_sistema')['tipo'] . ' alert-dismissible fade show mb-4">' . $this->session->flashdata('mensagem_sistema')['conteudo'] . '<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>';
}
?>
<div class="j_message"></div>
<form action="<?= base_url('produtos/filtrar') ?>" method="post" class="mb-4 j_send_form">
    <div class="form-row">
        <div class="form-group col-12">
            <label for="produto_descricao">Descrição</label>
            <input type="text" name="produto_descricao" id="produto_descricao" class="form-control" value="<?= ($this->session->userdata('filtro_produto_descricao')) ? $this->session->userdata('filtro_produto_descricao') : '' ?>">
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
                        <th>Descrição</th>
                        <th>Preço</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produtos as $produto) : ?>
                        <tr>
                            <td class="main_table_action">
                                <div class="dropdown show">
                                    <a href="#" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                        <i class="fas fa-cog fa-fw"></i>
                                        Opções
                                    </a>
                                    <div class="dropdown-menu">
                                        <a href="<?= base_url('produtos/editar/' . $produto['produto_id']) ?>" class="dropdown-item">
                                            <i class="fas fa-edit fa-fw"></i>
                                            Editar
                                        </a>
                                        <a href="#" data-message="Confirma a exclusão?" data-action="<?= base_url('produtos/deletar') ?>" data-id="<?= $produto['produto_id'] ?>" data-token-name="<?= $this->security->get_csrf_token_name() ?>" data-token-value="<?= $this->security->get_csrf_hash() ?>" class="dropdown-item j_send_action">
                                            <i class="fas fa-trash-alt fa-fw"></i>
                                            Excluír
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td><?= $produto['produto_id'] ?></td>
                            <td>
                                <span class="badge badge-<?= ($produto['produto_status'] === 'A' ? 'success' : 'danger') ?>">
                                    <?= ($produto['produto_status'] === 'A' ? 'ATIVO' : 'INATIVO') ?>
                                </span>
                            </td>
                            <td><?= $produto['produto_descricao'] ?></td>
                            <td>R$ <?= number_format($produto['produto_preco'], 2, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?= $paginacao ?>
    </div>
</div>