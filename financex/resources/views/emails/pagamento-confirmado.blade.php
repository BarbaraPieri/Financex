<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento Confirmado</title>
</head>
<body>
    <h1>Pagamento Confirmado</h1>

    <p>O seu pagamento foi confirmado com sucesso. Detalhes:</p>

    <ul>
        <li>ID do Pagamento: {{ $pagamento->id }}</li>
        <li>Status do Pagamento: {{ $pagamento->status }}</li>
        <!-- Adicione mais detalhes conforme necessário -->
    </ul>

    <p>Obrigado por escolher nossos serviços.</p>
</body>
</html>
