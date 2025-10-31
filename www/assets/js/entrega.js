$(document).ready(function () {
    var timeNext = 59;
    var timePause = false;

    var timer = setInterval(function () {
        if (!timePause) {
            if (timeNext >= 0) {
                $(".j_contador_atualizacao").text(
                    moment.utc(timeNext * 1000).format("ss")
                );
                timeNext--;
            } else {
                window.location.reload();
            }
        }
    }, 1000);
});

$('.j_send_input').on('change', function(e) {
    e.preventDefault();

    var data_pedido_id = $(this).attr("data-id");
    var data_pedido_sequencia_entrega = $(this).val();
    var action = $(this).attr("data-action");
    var token_name = $(this).attr("data-token-name");
    var token_value = $(this).attr("data-token-value");

    $.ajax({
        method: "POST",
        url: action,
        dataType: "json",
        data: {
            [token_name]: token_value,
            id: data_pedido_id,
            others: data_pedido_sequencia_entrega
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