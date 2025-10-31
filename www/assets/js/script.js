function activeLoading() {
  $(".j_loading").fadeIn(300);
}

function disableLoading() {
  $(".j_loading").fadeOut(300);
}

$(document).ready(function () {
  var PhoneMaskBehavior = function (val) {
      return val.replace(/\D/g, "").length === 11
        ? "(00) 00000-0000"
        : "(00) 0000-00009";
    },
    phoneOptions = {
      onKeyPress: function (val, e, field, options) {
        field.mask(PhoneMaskBehavior.apply({}, arguments), options);
      },
    };

  $(".j_mask_phone").mask(PhoneMaskBehavior, phoneOptions);
  $(".j_mask_cep").mask("00000-000");
  $(".j_mask_date").mask("00/00/0000");
  $(".j_mask_money").mask("#.##0,00", { reverse: true });
  $(".j_mask_decimal").mask("#.##0,0000", { reverse: true });

  $(".j_text_upper").on("blur", function () {
    var input = $(this);

    input.val(input.val().toUpperCase());
  });

  $(".j_text_lower").on("blur", function () {
    var input = $(this);

    input.val(input.val().toLowerCase());
  });

  $(".j_send_form").submit(function (e) {
    e.preventDefault();

    var form = $(this);
    var action = form.attr("action");
    var data = form.serialize();

    $.ajax({
      method: "POST",
      url: action,
      dataType: "json",
      data: data,
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
        }

        if (data.redirect) {
          window.location.href = data.redirect;
        }

        if (data.reload) {
          window.location.reload();
        }

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
  });

  var url = document.location.toString();

  if (url.match("#")) {
    $('.nav-pills a[href="#' + url.split("#")[1] + '"]').tab("show");
  }

  $(".nav-pills a").on("shown.bs.tab", function (e) {
    if (history.pushState) {
      history.pushState(null, null, e.target.hash);
    } else {
      window.location.hash = e.target.hash;
    }
  });

  $(".j_datepicker").datepicker({
    format: "dd/mm/yyyy",
    todayBtn: "linked",
    clearBtn: true,
    language: "pt-BR",
    keyboardNavigation: false,
    autoclose: true,
    todayHighlight: true,
  });
});

$(document).on("click", ".j_send_action", function (e) {
  e.preventDefault();

  var message = $(this).attr("data-message");
  var result = message !== '' ? confirm(message) : true;

  if (result) {
    var action = $(this).attr("data-action");
    var id = $(this).attr("data-id");
    var others = $(this).attr("data-others");
    var token_name = $(this).attr("data-token-name");
    var token_value = $(this).attr("data-token-value");

    $.ajax({
      method: "POST",
      url: action,
      dataType: "json",
      data: {
        [token_name]: token_value,
        id: id,
        others: others,
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
                '<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div></div>'
            )
            .fadeIn(300);
        }

        if (data.redirect) {
          window.location.href = data.redirect;
        }

        if (data.reload) {
          window.location.reload();
        }

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
});
