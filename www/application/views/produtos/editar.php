<div class="row align-items-center mb-4">
    <div class="col-12 col-md-6 mb-4 mb-md-0">
        <div class="h4 mb-0">
            <i class="fas fa-utensils fa-fw"></i>
            Produtos
        </div>
    </div>
    <div class="col-12 col-md-6 text-md-right">
        <a href="<?= base_url('produtos') ?>" class="btn btn-primary">
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
            <li class="nav-item">
                <a href="#estrutura" class="nav-link" data-toggle="pill">
                    <i class="fas fa-layer-group fa-fw"></i>
                    Estrutura
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="dados_gerais" class="tab-pane active">
                <form action="<?= base_url('produtos/editar/salvar') ?>" method="post" class="j_send_form">
                    <div class="form-row">
                        <div class="form-group col-12 col-md-9">
                            <label for="produto_descricao">Descrição</label>
                            <input type="text" name="produto_descricao" id="produto_descricao" class="form-control j_text_upper" value="<?= $produto['produto_descricao'] ?>">
                        </div>
                        <div class="form-group col-12 col-md-3">
                            <label for="produto_preco">Preço</label>
                            <input type="text" name="produto_preco" id="produto_preco" class="form-control j_mask_money" value="<?= $produto['produto_preco'] ?>">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-12">
                            <label>Status</label>
                        </div>
                        <div class="form-group col-12">
                            <div class="custom-control custom-radio">
                                <input type="radio" name="produto_status" id="status_yes" class="custom-control-input" value="A" <?= ($produto['produto_status'] === 'A') ? 'checked' : '' ?>>
                                <label for="status_yes" class="custom-control-label">Ativo</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" name="produto_status" id="status_no" class="custom-control-input" value="I" <?= ($produto['produto_status'] === 'I') ? 'checked' : '' ?>>
                                <label for="status_no" class="custom-control-label">Inativo</label>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                    <input type="hidden" name="produto_id" value="<?= $produto['produto_id'] ?>">
                    <div class="row">
                        <div class="col-12 d-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check-circle fa-fw"></i>
                                Salvar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div id="estrutura" class="tab-pane">
                <div class="row mb-4">
                    <div class="col-12">
                        <form action="<?= base_url('produtosestruturas/cadastrar/salvar') ?>" method="post" class="j_send_form">
                            <div class="form-row">
                                <div class="form-group col-12 col-md-9">
                                    <label for="produtoestrutura_subproduto">Produto</label>
                                    <select name="produtoestrutura_subproduto" id="produtoestrutura_subproduto" class="form-control">
                                        <option value="">SELECIONE...</option>
                                        <?php
                                        foreach ($subprodutos as $subproduto):
                                            echo '<option value="' . $subproduto['produto_id'] . '">' . $subproduto['produto_descricao'] . '</option>';
                                        endforeach;
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-12 col-md-3">
                                    <label for="produtoestrutura_quantidade">Quantidade</label>
                                    <input type="text" name="produtoestrutura_quantidade" id="produtoestrutura_quantidade" class="form-control j_mask_decimal">
                                </div>
                            </div>
                            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                            <input type="hidden" name="produtoestrutura_produto" value="<?= $produto['produto_id'] ?>">
                            <input type="hidden" name="action" value="create">
                            <div class="row">
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
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive main_table">
                            <table class="table table-striped">
                                <thead class="bg-light">
                                    <tr>
                                        <th></th>
                                        <th>Código</th>
                                        <th>Descrição</th>
                                        <th>Quantidade</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($produtosestruturas as $produtoestrutura): ?>
                                        <tr>
                                            <td>
                                                <div class="dropdown show">
                                                    <a href="#" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                                        <i class="fas fa-cog fa-fw"></i>
                                                        Opções
                                                    </a>
                                                    <div class="dropdown-menu">
                                                        <a href="#" data-message="Confirma a exclusão?" data-action="<?= base_url('produtosestruturas/deletar') ?>" data-id="<?= $produtoestrutura['produtoestrutura_id'] ?>" data-token-name="<?= $this->security->get_csrf_token_name() ?>" data-token-value="<?= $this->security->get_csrf_hash() ?>" class="dropdown-item j_send_action">
                                                            <i class="fas fa-trash-alt fa-fw"></i>
                                                            Excluír
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?= $produtoestrutura['produtoestrutura_id'] ?></td>
                                            <td><?= $produtoestrutura['produtoestrutura_subproduto_descricao'] ?></td>
                                            <td><?= number_format($produtoestrutura['produtoestrutura_quantidade'], 4, ',', '.'); ?></td>
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