$(document).ready(function () {
    var action = $(".j_dashboard_grafico").attr("data-action");
    var token_name = $(".j_dashboard_grafico").attr("data-token-name");
    var token_value = $(".j_dashboard_grafico").attr("data-token-value");

    $.ajax({
        method: "POST",
        url: action,
        dataType: "json",
        data: {
            [token_name]: token_value,
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

            var ctx1 = document.getElementById("j_quantidade_pedidos");
            var ctx2 = document.getElementById("j_valor_pedidos");

            new Chart(ctx1, {
                type: "line",
                data: {
                    labels: data.grafico_quantidade.etiquetas,
                    datasets: [
                        {
                            label: "Quantidade",
                            data: data.grafico_quantidade.valores,
                            borderWidth: 3,
                            backgroundColor: "#29F017",
                            borderColor: "#29F017",
                        },
                    ],
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value, index, values) {
                                    return new Intl.NumberFormat("pt-BR", {
                                        style: "decimal",
                                        maximumFractionDigits: 0,
                                    }).format(value);
                                },
                            },
                        },
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    var label = context.dataset.label || "";

                                    if (label) {
                                        label += ": ";
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat(
                                            "pt-BR",
                                            {
                                                style: "decimal",
                                                maximumFractionDigits: 0,
                                            }
                                        ).format(context.parsed.y);
                                    }
                                    return label;
                                },
                            },
                        },
                    },
                },
            });

            new Chart(ctx2, {
                type: "line",
                data: {
                    labels: data.grafico_valor.etiquetas,
                    datasets: [
                        {
                            label: "Valor",
                            data: data.grafico_valor.valores,
                            borderWidth: 3,
                            backgroundColor: "#29F017",
                            borderColor: "#29F017",
                        },
                    ],
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value, index, values) {
                                    return new Intl.NumberFormat("pt-BR", {
                                        style: "currency",
                                        currency: "BRL",
                                    }).format(value);
                                },
                            },
                        },
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    var label = context.dataset.label || "";

                                    if (label) {
                                        label += ": ";
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat(
                                            "pt-BR",
                                            {
                                                style: "currency",
                                                currency: "BRL",
                                            }
                                        ).format(context.parsed.y);
                                    }
                                    return label;
                                },
                            },
                        },
                    },
                },
            });
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
