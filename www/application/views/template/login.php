<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-8 col-md-6 col-lg-4">
            <div class="mb-2 p-2 text-center">
                <img src="<?= base_url('assets/images/logo.png') ?>" class="rounded-circle mb-3" height="116">
                <p class="mb-0 small"><i class="fas fa-lock"></i> Esta é uma <strong>área restrita</strong> e qualquer tentativa de acesso é gravada, informe suas credenciais para entrar no sistema.</p>
            </div>
            <?php
            if (!empty($this->session->flashdata('mensagem_sistema'))) {
                echo '<div class="alert alert-' . $this->session->flashdata('mensagem_sistema')['tipo'] . ' alert-dismissible fade show mb-4">' . $this->session->flashdata('mensagem_sistema')['conteudo'] . '<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>';
            }
            ?>
            <div class="j_message"></div>
            <div class="card">
                <div class="card-body p-5">                    
                    <form action="<?= base_url('login/processar') ?>" method="post" class="j_send_form">
                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="usuario_email">E-Mail</label>
                                <input type="email" name="usuario_email" id="usuario_email" class="form-control j_text_lower">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="usuario_senha">Senha</label>
                                <input type="password" name="usuario_senha" id="usuario_senha" class="form-control">
                            </div>
                        </div>
                        <!-- <script src="https://www.google.com/recaptcha/api.js" async defer></script> -->
                        <!-- <div class="g-recaptcha" data-sitekey="6LdpmQAVAAAAAGSmtbY-5jqICV5sQYoWpeig0ERk"></div> -->
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                        <div class="row">
                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-sign-in-alt fa-fw"></i>
                                    Entrar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>