$(document).ready(function () {
  $(document).on("blur", "#pedido_cliente", function () {
    var data_pedido_cliente = $("#pedido_cliente").val();
    var action = $(this).attr("data-action");
    var token_name = $(this).attr("data-token-name");
    var token_value = $(this).attr("data-token-value");

    if (data_pedido_cliente.length > 0) {
      $.ajax({
        method: "POST",
        url: action,
        dataType: "json",
        data: {
          [token_name]: token_value,
          id: data_pedido_cliente,
        },
        beforeSend: function (xhr) {
          activeLoading();
        },
        success: function (data, textStatus, jqXHR) {
          if (data.error) {
            $(".j_message")
              .hide()
              .html(
                '<div class="alert alert-' +
                data.error.type +
                ' alert-dismissible fade show mb-4">' +
                data.error.message +
                '<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>'
              )
              .fadeIn(300);

            $("#pedido_cliente").val("");
          }

          $("#pedido_cliente_nome").val(data.cliente_nome);

          buscar_enderecos_cliente(data_pedido_cliente);

          disableLoading();
        },
        error: function (jqXHR, textStatus, errorThrown) {
          if (jqXHR.status === 500) {
            $(".j_message")
              .hide()
              .html(
                '<div class="alert alert-danger alert-dismissible fade show mb-4">Ops, parece que nosso servidor está passando por uma instabilidade. Espere uns minutinhos e tente novamente ou então entre em contato pelos nossos outros canais de atendimento. Pedimos desculpas pelo incoveniente.<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>'
              )
              .fadeIn(300);
          }

          disableLoading();
        },
        complete: function (jqXHR, textStatus) {
          disableLoading();
        },
      });
    } else {
      $("#pedido_cliente_nome").val("");
      $("#pedido_clienteendereco").html("");
    }
  });

  $(document).on("keyup", "#pedido_cliente_nome", function () {
    var data_pedido_cliente_nome = $("#pedido_cliente_nome").val();
    var action = $(this).attr("data-action");
    var token_name = $(this).attr("data-token-name");
    var token_value = $(this).attr("data-token-value");

    if (data_pedido_cliente_nome.length > 2) {
      if (
        event.which !== 9 &&
        event.which !== 13 &&
        event.which !== 16 &&
        event.which !== 17 &&
        event.which !== 18 &&
        event.which !== 20 &&
        event.which !== 27
      ) {
        $.ajax({
          method: "POST",
          url: action,
          dataType: "json",
          data: {
            [token_name]: token_value,
            text: data_pedido_cliente_nome,
          },
          beforeSend: function (xhr) {
            $("#pedido_cliente_autocomplete").html("").css("display", "none");
          },
          success: function (data, textStatus, jqXHR) {
            var html_data_list = "";

            $.each(data, function (index, value) {
              html_data_list +=
                '<a href="#" class="list-group-item list-group-item-action px-3 py-2 j_pedido_cliente_autocomplete_item" data-cliente-id="' +
                value.cliente_id +
                '" data-cliente-nome="' +
                value.cliente_nome +
                '">' +
                value.cliente_id +
                " - " +
                value.cliente_nome +
                "</a>";
            });

            $("#pedido_cliente_autocomplete")
              .html(html_data_list)
              .css("display", "block");

            disableLoading();
          },
          error: function (jqXHR, textStatus, errorThrown) {
            if (jqXHR.status === 500) {
              $(".j_message")
                .hide()
                .html(
                  '<div class="alert alert-danger alert-dismissible fade show mb-4">Ops, parece que nosso servidor está passando por uma instabilidade. Espere uns minutinhos e tente novamente ou então entre em contato pelos nossos outros canais de atendimento. Pedimos desculpas pelo incoveniente.<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>'
                )
                .fadeIn(300);
            }

            disableLoading();
          },
          complete: function (jqXHR, textStatus) {
            disableLoading();
          },
        });
      }
    } else {
      $("#pedido_cliente_autocomplete").html("").css("display", "none");
    }
  });

  $(document).on("blur", "#pedido_cliente_nome", function () {
    var data_pedido_cliente_nome = $("#pedido_cliente_nome").val();

    if (data_pedido_cliente_nome.length == 0) {
      $("#pedido_cliente").val("");
      $("#pedido_clienteendereco").html("");
    }
  });

  $(document).on(
    "click",
    ".j_pedido_cliente_autocomplete_item",
    function (event) {
      event.preventDefault();

      $("#pedido_cliente").val($(this).attr("data-cliente-id"));
      $("#pedido_cliente_nome").val($(this).attr("data-cliente-nome"));

      $("#pedido_cliente_autocomplete").html("").css("display", "none");

      buscar_enderecos_cliente($(this).attr("data-cliente-id"));
    }
  );

  function buscar_enderecos_cliente(cliente_id) {
    var data_cliente_id = cliente_id;
    var action = $("#pedido_cliente_autocomplete").attr("data-action");
    var token_name = $("#pedido_cliente_autocomplete").attr("data-token-name");
    var token_value = $("#pedido_cliente_autocomplete").attr(
      "data-token-value"
    );

    $.ajax({
      method: "POST",
      url: action,
      dataType: "json",
      data: {
        [token_name]: token_value,
        id: data_cliente_id,
      },
      beforeSend: function (xhr) {
        activeLoading();

        $("#pedido_clienteendereco").html("");
      },
      success: function (data, textStatus, jqXHR) {
        var html_data_list = '<option value="">SELECIONE...</option>';

        $.each(data, function (index, value) {
          html_data_list +=
            '<option value="' +
            value.clienteendereco_id +
            '" data-entregador="' +
            value.clienteendereco_entregador +
            '">Endereço: ' +
            value.clienteendereco_logradouro +
            ", " +
            value.clienteendereco_numero +
            " - " +
            value.clienteendereco_complemento +
            " | Bairro: " +
            value.clienteendereco_bairro +
            " | Cidade: " +
            value.clienteendereco_cidade +
            " | UF: " +
            value.clienteendereco_uf +
            "</option>";
        });

        $("#pedido_clienteendereco").html(html_data_list);

        disableLoading();
      },
      error: function (jqXHR, textStatus, errorThrown) {
        if (jqXHR.status === 500) {
          $(".j_message")
            .hide()
            .html(
              '<div class="alert alert-danger alert-dismissible fade show mb-4">Ops, parece que nosso servidor está passando por uma instabilidade. Espere uns minutinhos e tente novamente ou então entre em contato pelos nossos outros canais de atendimento. Pedimos desculpas pelo incoveniente.<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>'
            )
            .fadeIn(300);
        }

        disableLoading();
      },
      complete: function (jqXHR, textStatus) {
        disableLoading();
      },
    });
  }

  $(document).on("change", "#pedido_clienteendereco", function () {
    var entregador = $("#pedido_clienteendereco")
      .find(":selected")
      .attr("data-entregador");

    if (entregador) {
      $("#pedido_entregador option[value=" + entregador + "]").prop(
        "selected",
        true
      );
    }
  });

  $(document).on("blur", "#pedidoitem_produto", function () {
    var data_pedidoitem_produto = $("#pedidoitem_produto").val();
    var action = $(this).attr("data-action");
    var token_name = $(this).attr("data-token-name");
    var token_value = $(this).attr("data-token-value");

    if (data_pedidoitem_produto.length > 0) {
      $.ajax({
        method: "POST",
        url: action,
        dataType: "json",
        data: {
          [token_name]: token_value,
          id: data_pedidoitem_produto,
        },
        beforeSend: function (xhr) {
          activeLoading();
        },
        success: function (data, textStatus, jqXHR) {
          if (data.error) {
            $(".j_message")
              .hide()
              .html(
                '<div class="alert alert-' +
                data.error.type +
                ' alert-dismissible fade show mb-4">' +
                data.error.message +
                '<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>'
              )
              .fadeIn(300);

            $("#data_pedidoitem_produto").val("");
            $("#pedidoitem_quantidade").val("");
            $("#pedidoitem_valor_unitario").val("");
            $("#pedidoitem_valor_total").val("");
          }

          $("#pedidoitem_produto_descricao").val(data.produto_descricao);

          var price = parseFloat(data.produto_preco);
          var stringPrice = price.toLocaleString("pt-BR", {
            style: "decimal",
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
          });

          $("#pedidoitem_quantidade").val("");
          $("#pedidoitem_valor_unitario").val(stringPrice);
          $("#pedidoitem_valor_total").val("");

          disableLoading();
        },
        error: function (jqXHR, textStatus, errorThrown) {
          if (jqXHR.status === 500) {
            $(".j_message")
              .hide()
              .html(
                '<div class="alert alert-danger alert-dismissible fade show mb-4">Ops, parece que nosso servidor está passando por uma instabilidade. Espere uns minutinhos e tente novamente ou então entre em contato pelos nossos outros canais de atendimento. Pedimos desculpas pelo incoveniente.<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>'
              )
              .fadeIn(300);
          }

          disableLoading();
        },
        complete: function (jqXHR, textStatus) {
          disableLoading();
        },
      });
    } else {
      $("#pedidoitem_produto_descricao").val("");
      $("#pedidoitem_valor_unitario").val("");
      $("#pedidoitem_valor_total").val("");
    }
  });

  $(document).on("keyup", "#pedidoitem_produto_descricao", function () {
    var data_pedidoitem_produto_descricao = $(
      "#pedidoitem_produto_descricao"
    ).val();
    var action = $(this).attr("data-action");
    var token_name = $(this).attr("data-token-name");
    var token_value = $(this).attr("data-token-value");

    if (data_pedidoitem_produto_descricao.length > 2) {
      if (
        event.which !== 9 &&
        event.which !== 13 &&
        event.which !== 16 &&
        event.which !== 17 &&
        event.which !== 18 &&
        event.which !== 20 &&
        event.which !== 27
      ) {
        $.ajax({
          method: "POST",
          url: action,
          dataType: "json",
          data: {
            [token_name]: token_value,
            text: data_pedidoitem_produto_descricao,
          },
          beforeSend: function (xhr) {
            $("#pedidoitem_produto_autocomplete").html("");
          },
          success: function (data, textStatus, jqXHR) {
            var html_data_list = "";

            $.each(data, function (index, value) {
              html_data_list +=
                '<a href="#" class="list-group-item list-group-item-action px-3 py-2 j_pedidoitem_produto_autocomplete_item" data-produto-id="' +
                value.produto_id +
                '" data-produto-descricao="' +
                value.produto_descricao +
                '" data-produto-preco="' +
                value.produto_preco +
                '">' +
                value.produto_id +
                " - " +
                value.produto_descricao +
                " | " +
                new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value.produto_preco) +
                "</a>";
            });

            $("#pedidoitem_produto_autocomplete").html(html_data_list);

            disableLoading();
          },
          error: function (jqXHR, textStatus, errorThrown) {
            if (jqXHR.status === 500) {
              $(".j_message")
                .hide()
                .html(
                  '<div class="alert alert-danger alert-dismissible fade show mb-4">Ops, parece que nosso servidor está passando por uma instabilidade. Espere uns minutinhos e tente novamente ou então entre em contato pelos nossos outros canais de atendimento. Pedimos desculpas pelo incoveniente.<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>'
                )
                .fadeIn(300);
            }

            disableLoading();
          },
          complete: function (jqXHR, textStatus) {
            disableLoading();
          },
        });
      }
    } else {
      $("#pedidoitem_produto_autocomplete").html("");
    }
  });

  $(document).on("blur", "#pedidoitem_produto_descricao", function () {
    var data_pedidoitem_produto_descricao = $(
      "#pedidoitem_produto_descricao"
    ).val();

    if (data_pedidoitem_produto_descricao.length == 0) {
      $("#pedidoitem_produto").val("");
      $("#pedidoitem_quantidade").val("");
      $("#pedidoitem_valor_unitario").val("");
      $("#pedidoitem_valor_total").val("");
    }
  });

  $(document).on(
    "click",
    ".j_pedidoitem_produto_autocomplete_item",
    function (event) {
      event.preventDefault();

      $("#pedidoitem_produto").val($(this).attr("data-produto-id"));
      $("#pedidoitem_produto_descricao").val(
        $(this).attr("data-produto-descricao")
      );

      var price = parseFloat($(this).attr("data-produto-preco"));
      var stringPrice = price.toLocaleString("pt-BR", {
        style: "decimal",
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
      });

      $("#pedidoitem_quantidade").val("");
      $("#pedidoitem_valor_unitario").val(stringPrice);
      $("#pedidoitem_valor_total").val("");

      $("#pedidoitem_produto_autocomplete").html("");
    }
  );

  $(document).on("blur", "#pedidoitem_quantidade", function () {
    var data_pedidoitem_quatidade = $("#pedidoitem_quantidade").val();
    var data_pedidoitem_valor_unitario = $("#pedidoitem_valor_unitario").val();
    var data_pedidoitem_valor_total = 0;

    if (
      data_pedidoitem_quatidade.length > 0 &&
      data_pedidoitem_valor_unitario.length > 0
    ) {
      data_pedidoitem_valor_total =
        parseFloat(
          data_pedidoitem_quatidade.replace(/\./g, "").replace(",", ".")
        ) *
        parseFloat(
          data_pedidoitem_valor_unitario.replace(/\./g, "").replace(",", ".")
        );

      var price = parseFloat(data_pedidoitem_valor_total);
      var stringPrice = price.toLocaleString("pt-BR", {
        style: "decimal",
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
      });

      $("#pedidoitem_valor_total").val(stringPrice);
    }
  });
});
