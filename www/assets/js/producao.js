$(document).ready(function () {
  $.when(popular_dashboard()).done(function () {
    var timeNext = 30;
    var timePause = false;

    var timer = setInterval(function () {
      if (!timePause) {
        if (timeNext >= 0) {
          $(".j_contador_atualizacao").text(
            moment.utc(timeNext * 1000).format("ss")
          );
          timeNext--;
        } else {
          $.when(popular_dashboard()).done(function () {
            timeNext = 30;
          });
        }
      }
    }, 1000);
  });
});

function popular_dashboard() {
  var action = $(".j_dashboard_producao").attr("data-action");
  var token_name = $(".j_dashboard_producao").attr("data-token-name");
  var token_value = $(".j_dashboard_producao").attr("data-token-value");

  $.ajax({
    method: "POST",
    url: action,
    dataType: "json",
    data: {
      [token_name]: token_value,
    },
    beforeSend: function (xhr) {
      activeLoading();

      $(".j_dashboard_producao").html("");
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
      }

      var html_data_list = "";

      $.each(data, function (index, value) {
        var price = parseFloat(value.produto_quantidade);
        var stringPrice = price.toLocaleString("pt-BR", {
          style: "decimal",
          minimumFractionDigits: 0,
          maximumFractionDigits: 0,
        });

        html_data_list +=
          '<div class="col-12 col-md-6 col-xl-4 mt-4"><div class="card shadow-sm"><div class="card-body text-center p-5"><h4 class="mb-0">' +
          value.produto_descricao +
          '</h4><h1 class="mb-0 display-1 font-weight-bold">' +
          stringPrice +
          "</h1></div></div></div>";
      });

      $(".j_dashboard_producao").html(html_data_list);

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
