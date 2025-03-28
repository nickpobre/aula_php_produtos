<?php
include 'bd.php'; // Inclui a conexão com o banco de dados

// Inicializa a variável $produtos
$produtos = [];

// Busca de produtos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['busca'])) {
    $busca = $conn->real_escape_string($_POST['busca']);
    $query = "SELECT * FROM produtos WHERE nome LIKE '%$busca%' OR cod LIKE '%$busca%'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        $produtos = $result->fetch_all(MYSQLI_ASSOC);
    }
}

// Venda de produtos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vender'])) {
    $id = (int)$_POST['id'];
    $quantidadeVendida = (int)$_POST['quantidadeVendida'];
    $query = "UPDATE produtos SET quantidade = quantidade - $quantidadeVendida WHERE id = $id AND quantidade >= $quantidadeVendida";
    $conn->query($query);
}

// Exclusão de produtos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir'])) {
    $id = (int)$_POST['id'];
    $query = "DELETE FROM produtos WHERE id = $id";
    $conn->query($query);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ponto de Venda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Ponto de Venda</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="busca" class="form-label">Buscar Produto</label>
                <input type="text" class="form-control" id="busca" name="busca" placeholder="Digite o nome ou código do produto" required>
            </div>
            <button type="submit" class="btn btn-primary">Buscar</button>
        </form>
        <hr>
        <?php if (!empty($produtos)): ?>
            <h2>Resultados da Busca:</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Imagem</th>
                        <th>COD</th>
                        <th>Nome</th>
                        <th>Quantidade</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produtos as $produto): ?>
                        <tr>
                            <td><img src="uploads/<?= $produto['imagem'] ?>" alt="<?= $produto['nome'] ?>" width="50"></td>
                            <td><?= $produto['cod'] ?></td>
                            <td><?= $produto['nome'] ?></td>
                            <td><?= $produto['quantidade'] ?></td>
                            <td>
                                <!-- Formulário de Venda -->
                                <form method="POST" style="display:inline-block;">
                                    <input type="hidden" name="id" value="<?= $produto['id'] ?>">
                                    <input type="number" name="quantidadeVendida" min="1" max="<?= $produto['quantidade'] ?>" placeholder="Qtd">
                                    <button type="submit" name="vender" class="btn btn-success btn-sm">Vender</button>
                                </form>
                                <!-- Formulário de Exclusão -->
                                <form method="POST" style="display:inline-block;">
                                    <input type="hidden" name="id" value="<?= $produto['id'] ?>">
                                    <button type="submit" name="excluir" class="btn btn-danger btn-sm">Excluir</button>
                                </form>
                                <!-- Botão de Editar -->
                                <a href="editar_produto.php?id=<?= $produto['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
