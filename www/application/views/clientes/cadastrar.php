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
<div class="row">
    <div class="col-12">
        <form action="<?= base_url('clientes/cadastrar/salvar') ?>" method="post" class="j_send_form">
            <div class="form-row">
                <div class="form-group col-12">
                    <label for="cliente_nome">Nome</label>
                    <input type="text" name="cliente_nome" id="cliente_nome" class="form-control j_text_upper">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-12 col-md-6">
                    <label for="cliente_telefone">Telefone</label>
                    <input type="text" name="cliente_telefone" id="cliente_telefone" class="form-control j_mask_phone">
                </div>
                <div class="form-group col-12 col-md-6">
                    <label for="cliente_data_nascimento">Data Nascimento</label>
                    <input type="text" name="cliente_data_nascimento" id="cliente_data_nascimento"
                        class="form-control j_mask_date">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-12">
                    <label for="cliente_observacao">Observações</label>
                    <textarea name="cliente_observacao" id="cliente_observacao" class="form-control j_text_upper"
                        rows="5"></textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="col-12">
                    <label>Status</label>
                </div>
                <div class="form-group col-12">
                    <div class="custom-control custom-radio">
                        <input type="radio" name="cliente_status" id="status_yes" class="custom-control-input" value="A"
                            checked>
                        <label for="status_yes" class="custom-control-label">Ativo</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input type="radio" name="cliente_status" id="status_no" class="custom-control-input" value="I">
                        <label for="status_no" class="custom-control-label">Inativo</label>
                    </div>
                </div>
            </div>
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>"
                value="<?= $this->security->get_csrf_hash() ?>">
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