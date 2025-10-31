<div class="row align-items-center mb-4">
    <div class="col-12 col-md-6 mb-4 mb-md-0">
        <div class="h4 mb-0">
            <i class="fas fa-wallet fa-fw"></i>
            Formas de Pagamento
        </div>
    </div>
    <div class="col-12 col-md-6 text-md-right">
        <a href="<?= base_url('formaspagamento') ?>" class="btn btn-primary">
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
        </ul>
        <div class="tab-content">
            <div id="dados_gerais" class="tab-pane active">
                <form action="<?= base_url('formaspagamento/editar/salvar') ?>" method="post" class="j_send_form">
                    <div class="form-row">
                        <div class="form-group col-12">
                            <label for="formapagamento_descricao">Descrição</label>
                            <input type="text" name="formapagamento_descricao" id="formapagamento_descricao" class="form-control j_text_upper" value="<?= $formapagamento['formapagamento_descricao'] ?>">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-12">
                            <label>Status</label>
                        </div>
                        <div class="form-group col-12">
                            <div class="custom-control custom-radio">
                                <input type="radio" name="entregador_status" id="status_yes" class="custom-control-input" value="A" <?= ($formapagamento['formapagamento_status'] === 'A') ? 'checked' : '' ?>>
                                <label for="status_yes" class="custom-control-label">Ativo</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" name="entregador_status" id="status_no" class="custom-control-input" value="I" <?= ($formapagamento['formapagamento_status'] === 'I') ? 'checked' : '' ?>>
                                <label for="status_no" class="custom-control-label">Inativo</label>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                    <input type="hidden" name="id" value="<?= $formapagamento['formapagamento_id'] ?>">
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
    </div>
</div>