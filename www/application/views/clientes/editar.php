<div class="row align-items-center mb-4">
    <div class="col-12 col-md-6 mb-4 mb-md-0">
        <div class="h4 mb-0">
            <i class="fas fa-address-book fa-fw"></i>
            Clientes
        </div>
    </div>
    <div class="col-12 col-md-6 text-md-right">
        <a href="<?= base_url('clientes') ?>" class="btn btn-primary">
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
                <a href="#enderecos" class="nav-link" data-toggle="pill">
                    <i class="fas fa-map-marker-alt fa-fw"></i>
                    Endereços
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="dados_gerais" class="tab-pane active">
                <form action="<?= base_url('clientes/editar/salvar') ?>" method="post" class="j_send_form">
                    <div class="form-row">
                        <div class="form-group col-12">
                            <label for="cliente_nome">Nome</label>
                            <input type="text" name="cliente_nome" id="cliente_nome" class="form-control j_text_upper" value="<?= $cliente['cliente_nome'] ?>">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-12 col-md-6">
                            <label for="cliente_telefone">Telefone</label>
                            <input type="text" name="cliente_telefone" id="cliente_telefone" class="form-control j_mask_phone" value="<?= $cliente['cliente_telefone'] ?>">
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label for="cliente_data_nascimento">Data Nascimento</label>
                            <input type="text" name="cliente_data_nascimento" id="cliente_data_nascimento" class="form-control j_mask_date" value="<?= (strtotime($cliente['cliente_data_nascimento']) > 0) ? date('d/m/Y', strtotime($cliente['cliente_data_nascimento'])) : '' ?>">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-12">
                            <label for="cliente_observacao">Observações</label>
                            <textarea name="cliente_observacao" id="cliente_observacao" class="form-control j_text_upper" rows="5"><?= $cliente['cliente_observacao'] ?></textarea>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-12">
                            <label>Status</label>
                        </div>
                        <div class="form-group col-12">
                            <div class="custom-control custom-radio">
                                <input type="radio" name="cliente_status" id="status_yes" class="custom-control-input" value="A" <?= ($cliente['cliente_status'] === 'A') ? 'checked' : '' ?>>
                                <label for="status_yes" class="custom-control-label">Ativo</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" name="cliente_status" id="status_no" class="custom-control-input" value="I" <?= ($cliente['cliente_status'] === 'I') ? 'checked' : '' ?>>
                                <label for="status_no" class="custom-control-label">Inativo</label>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                    <input type="hidden" name="cliente_id" value="<?= $cliente['cliente_id'] ?>">
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
            <div id="enderecos" class="tab-pane">
                <div class="row mb-4">
                    <div class="col-12">
                        <form action="<?= base_url('clientesenderecos/cadastrar/salvar') ?>" method="post" class="j_send_form">
                            <div class="form-row">
                                <div class="form-group col-12 col-md-3">
                                    <label for="clienteendereco_cep">CEP</label>
                                    <input type="text" name="clienteendereco_cep" id="clienteendereco_cep" class="form-control j_mask_cep">
                                </div>
                                <div class="form-group col-12 col-md-7">
                                    <label for="clienteendereco_logradouro">Logradouro</label>
                                    <input type="text" name="clienteendereco_logradouro" id=clienteendereco_logradouro" class="form-control j_text_upper">
                                </div>
                                <div class="form-group col-12 col-md-2">
                                    <label for="clienteendereco_numero">Número</label>
                                    <input type="text" name="clienteendereco_numero" id="clienteendereco_numero" class="form-control">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-12 col-md-6">
                                    <label for="clienteendereco_bairro">Bairro</label>
                                    <input type="text" name="clienteendereco_bairro" id="clienteendereco_bairro" class="form-control j_text_upper">
                                </div>
                                <div class="form-group col-12 col-md-3">
                                    <label for="clienteendereco_complemento">Complemento</label>
                                    <input type="text" name="clienteendereco_complemento" id="clienteendereco_complemento" class="form-control j_text_upper">
                                </div>
                                <div class="form-group col-12 col-md-3">
                                    <label for="clienteendereco_entregador">Entregador</label>
                                    <select name="clienteendereco_entregador" id="clienteendereco_entregador" class="form-control">
                                        <option value="">SELECIONE...</option>
                                        <?php
                                        foreach ($entregadores as $entregador):
                                            echo '<option value="' . $entregador['entregador_id'] . '">' . $entregador['entregador_nome'] . '</option>';
                                        endforeach;
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-12 col-md-10">
                                    <label for="clienteendereco_cidade">Cidade</label>
                                    <input type="text" name="clienteendereco_cidade" id="clienteendereco_cidade" class="form-control j_text_upper" readonly>
                                </div>
                                <div class="form-group col-12 col-md-2">
                                    <label for="clienteendereco_uf">UF</label>
                                    <input type="text" name="clienteendereco_uf" id="clienteendereco_uf" class="form-control j_text_upper" readonly>
                                </div>
                            </div>
                            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                            <input type="hidden" name="clienteendereco_cliente" value="<?= $cliente['cliente_id'] ?>">
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
                                        <th>Status</th>
                                        <th>CEP</th>
                                        <th>Logradouro</th>
                                        <th>Número</th>
                                        <th>Bairro</th>
                                        <th>Complemento</th>
                                        <th>Entregador</th>
                                        <th>Cidade</th>
                                        <th>UF</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($clientesenderecos as $clienteendereco): ?>
                                        <tr>
                                            <td>
                                                <div class="dropdown show">
                                                    <a href="#" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                                        <i class="fas fa-cog fa-fw"></i>
                                                        Opções
                                                    </a>
                                                    <div class="dropdown-menu">
                                                        <a href="#" data-message="Confirma a alteração?" data-action="<?= base_url('clientesenderecos/editar_status/salvar') ?>" data-id="<?= $clienteendereco['clienteendereco_id'] ?>" data-token-name="<?= $this->security->get_csrf_token_name() ?>" data-token-value="<?= $this->security->get_csrf_hash() ?>" class="dropdown-item j_send_action">
                                                            <?= ($clienteendereco['clienteendereco_status'] === 'A' ? '<i class="fas fa-thumbs-down fa-fw"></i> Inativar' : '<i class="fas fa-thumbs-up fa-fw"></i> Ativar') ?>
                                                        </a>
                                                        <a href="#" data-message="Confirma a exclusão?" data-action="<?= base_url('clientesenderecos/deletar') ?>" data-id="<?= $clienteendereco['clienteendereco_id'] ?>" data-token-name="<?= $this->security->get_csrf_token_name() ?>" data-token-value="<?= $this->security->get_csrf_hash() ?>" class="dropdown-item j_send_action">
                                                            <i class="fas fa-trash-alt fa-fw"></i>
                                                            Excluír
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?= $clienteendereco['clienteendereco_id'] ?></td>
                                            <td>
                                                <span class="badge badge-<?= ($clienteendereco['clienteendereco_status'] === 'A' ? 'success' : 'danger') ?>">
                                                    <?= ($clienteendereco['clienteendereco_status'] === 'A' ? 'ATIVO' : 'INATIVO') ?>
                                                </span>
                                            </td>
                                            <td><?= $clienteendereco['clienteendereco_cep'] ?></td>
                                            <td><?= $clienteendereco['clienteendereco_logradouro'] ?></td>
                                            <td><?= $clienteendereco['clienteendereco_numero'] ?></td>
                                            <td><?= $clienteendereco['clienteendereco_bairro'] ?></td>
                                            <td><?= $clienteendereco['clienteendereco_complemento'] ?></td>
                                            <td><?= $clienteendereco['entregador_nome'] ?></td>
                                            <td><?= $clienteendereco['clienteendereco_cidade'] ?></td>
                                            <td><?= $clienteendereco['clienteendereco_uf'] ?></td>
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