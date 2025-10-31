<div class="row align-items-center mb-4">
    <div class="col-12 col-md-6 mb-4 mb-md-0">
        <div class="h4 mb-2">
            <i class="fab fa-cloudscale fa-fw"></i>
            Dashboard / Produção
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
        <p class="mb-0">Abaixo estão listados as quantidades que devem ser produzidas hoje <strong class="d-inline-block py-1 px-2 rounded bg-danger text-white"><?= date('d/m/Y') ?></strong></p>
    </div>
</div>
<div class="row j_dashboard_producao" data-action="<?= base_url('inicio/producao/filtrar') ?>" data-token-name="<?= $this->security->get_csrf_token_name() ?>" data-token-value="<?= $this->security->get_csrf_hash() ?>">
</div>