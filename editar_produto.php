<?php
require 'bd.php'; // Inclui o arquivo de conexão com o banco de dados

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $categoria = $_POST['categoria'];
    $validade = $_POST['validade'];
    $quantidade = $_POST['quantidade'];
    $preco_unitario = $_POST['preco_unitario'];
    $preco_pacote = $_POST['preco_pacote'];

    // Atualiza o produto no banco de dados
    $sql = "UPDATE produtos SET 
                nome = ?, 
                categoria = ?, 
                validade = ?, 
                quantidade = ?, 
                preco_unitario = ?, 
                preco_pacote = ? 
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssdddi', $nome, $categoria, $validade, $quantidade, $preco_unitario, $preco_pacote, $id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Produto atualizado com sucesso!</div>";
    } else {
        echo "<div class='alert alert-danger'>Erro ao atualizar o produto: " . $stmt->error . "</div>";
    }
}   

// Obtém os dados do produto para exibição no formulário
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM produtos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $produto = $result->fetch_assoc();

    if (!$produto) {
        echo "<div class='alert alert-danger'>Produto não encontrado!</div>";
        exit;
    }
} else {
    echo "<div class='alert alert-danger'>ID do produto não fornecido!</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Editar Produto</h1>
        <form method="POST" action="/editar_produto.php">
            <input type="hidden" name="id" value="<?= $produto['id'] ?>">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome do Produto</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="categoria" class="form-label">Categoria</label>
                <input type="text" class="form-control" id="categoria" name="categoria" value="<?= htmlspecialchars($produto['categoria']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="validade" class="form-label">Validade</label>
                <input type="date" class="form-control" id="validade" name="validade" value="<?= $produto['validade'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="quantidade" class="form-label">Quantidade</label>
                <input type="number" class="form-control" id="quantidade" name="quantidade" value="<?= $produto['quantidade'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="preco_unitario" class="form-label">Preço Unitário</label>
                <input type="number" step="0.01" class="form-control" id="preco_unitario" name="preco_unitario" value="<?= $produto['preco_unitario'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="preco_pacote" class="form-label">Preço Pacote</label>
                <input type="number" step="0.01" class="form-control" id="preco_pacote" name="preco_pacote" value="<?= $produto['preco_pacote'] ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        </form>
    </div>
</body>
</html>
