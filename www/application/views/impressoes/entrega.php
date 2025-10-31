<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Fast Delivery - Impressão Relatório Entrega - <?= $entregadores[0]['entregador_nome'] ?></title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= base_url('node_modules/@fortawesome/fontawesome-free/css/all.min.css') ?>">

    <link rel="shortcut icon" href="<?= base_url('assets/images/favicon.png') ?>">

    <style>
        * {
            margin: 0;
            padding: 0;
            outline: 0;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        body {
            padding: 0.5cm 0;
            background-color: #fff;
            font-family: 'Noto Sans', sans-serif;
            font-size: 9pt;
            color: #333;
            line-height: 1.5;
        }

        .container {
            width: 27cm;
            margin: 0 auto;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            margin-bottom: 0.5cm;
        }

        .header-left {
            display: flex;
            align-items: center;
        }

        .header-left img {
            width: 80px;
            border-radius: 50%;
        }

        .header-left-title {
            margin-left: 0.25cm;
        }

        .header-left-title h1 {
            font-size: 18pt;
            line-height: 1.5;
        }

        .header-left-title p {
            font-size: 10pt;
            line-height: 1.5;
        }

        .header-center {
            display: flex;
            align-items: center;
        }

        .header-center h2 {
            font-size: 22pt;
            line-height: 1.5;
        }

        .header-right {
            display: flex;
            flex-direction: column;
        }

        .header-right-entregador {
            display: flex;
            align-items: center;
            margin-bottom: 0.25cm;
        }

        .header-right-entregador>div {
            margin-left: 0.25cm;
        }

        .header-right-entregador>div p {
            font-size: 9pt;
            line-height: 1.5;
        }

        .header-right-entregador>div h5 {
            font-size: 11pt;
            line-height: 1.5;
        }

        .header-right-data-entrega {
            display: flex;
            align-items: center;
        }

        .header-right-data-entrega>div {
            margin-left: 0.25cm;
        }

        .header-right-data-entrega>div p {
            font-size: 9pt;
            line-height: 1.5;
        }

        .header-right-data-entrega>div h5 {
            font-size: 11pt;
            line-height: 1.5;
        }

        .content {
            width: 100%;
            margin-bottom: 0.5cm;
        }

        table {
            border-collapse: collapse;
        }

        th,
        td {
            padding: 0.15cm;
            border: 1px solid #ccc;
            text-align: left;
            vertical-align: top;
            font-size: 9pt;
        }

        .footer {
            width: 100%;
        }

        .footer p {
            font-size: 9pt;
            line-height: 1.5;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="header-left">
                <img src="<?= base_url('assets/images/logo.png') ?>">

                <div class="header-left-title">
                    <h1>Fast Delivery</h1>
                </div>
            </div>

            <div class="header-center">
                <h2>Lista de Entregas</h2>
            </div>

            <div class="header-right">
                <div class="header-right-entregador">
                    <i class="fas fa-street-view fa-2x fa-fw"></i>
                    <div>
                        <p>Entregador</p>
                        <h5><?= $entregadores[0]['entregador_nome'] ?></h5>
                    </div>
                </div>

                <div class="header-right-data-entrega">
                    <i class="far fa-calendar-alt fa-2x fa-fw"></i>
                    <div>
                        <p>Data Entrega</p>
                        <h5><?= date('d/m/Y') ?></h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <table>
                <thead>
                    <tr>
                        <th style="width: 1.25cm;">OK</th>
                        <th style="width: 1.75cm;">PEDIDO</th>
                        <th style="width: 6cm;">CLIENTE</th>
                        <th style="width: 4cm;">FORMA PAGAMENTO</th>
                        <th style="width: 3cm;">VALOR TOTAL</th>
                        <th style="width: 11cm;">ENDEREÇO</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($entregadores[0]['pedidos'] as $pedido): ?>
                        <tr>
                            <td></td>
                            <td><?= $pedido['pedido_id'] ?></td>
                            <td><?= $pedido['cliente_nome'] ?></td>
                            <td><?= $pedido['formapagamento_descricao'] ?></td>
                            <td>R$ <?= number_format($pedido['pedido_valor_total'], 2, ',', '.') ?></td>
                            <td><?= $pedido['clienteendereco_logradouro'] ?>, <?= $pedido['clienteendereco_numero'] ?>, <?= $pedido['clienteendereco_complemento'] ?> - <?= $pedido['clienteendereco_bairro'] ?> - <?= $pedido['clienteendereco_cidade'] ?>/<?= $pedido['clienteendereco_uf'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="footer">
            <p><strong>Usuário Emissão:</strong> <?= $this->session->userdata('usuario')['usuario_nome'] ?></p>
            <p><strong>Data/Hora Emissão:</strong> <?= date('d/m/Y H:i:s') ?></p>
        </div>
    </div>
</body>

</html>