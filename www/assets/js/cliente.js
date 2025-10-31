function limpa_endereco() {
  $("#clienteendereco_logradouro").val("");
  $("#clienteendereco_bairro").val("");
  $("#clienteendereco_cidade").val("");
  $("#clienteendereco_uf").val("");
}

$(function () {
  $("#clienteendereco_cep").blur(function () {
    activeLoading();

    var zipcode = $(this).val().replace(/\D/g, "");

    if (zipcode !== "") {
      var validazipcode = /^[0-9]{8}$/;

      if (validazipcode.test(zipcode)) {
        $.getJSON(
          "https://viacep.com.br/ws/" + zipcode + "/json/?callback=?",
          function (data) {
            if (!("erro" in data)) {
              $("#clienteendereco_logradouro").val(
                data.logradouro.toUpperCase()
              );
              $("#clienteendereco_bairro").val(data.bairro.toUpperCase());
              $("#clienteendereco_cidade").val(data.localidade.toUpperCase());
              $("#clienteendereco_uf").val(data.uf.toUpperCase());
            } else {
              limpa_endereco();
              $(".j_message")
                .hide()
                .html(
                  '<div class="alert alert-warning alert-dismissible fade show mb-4">O CEP informado não foi encontrado na base de dados nacional.<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div></div>'
                )
                .fadeIn(300);
            }

            disableLoading();
          }
        );
      } else {
        limpa_endereco();

        $(".j_message")
          .hide()
          .html(
            '<div class="alert alert-danger alert-dismissible fade show mb-4">O formato do CEP informado não é válido.<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div></div>'
          )
          .fadeIn(300);

        disableLoading();
      }
    } else {
      limpa_endereco();

      disableLoading();
    }
  });
});
