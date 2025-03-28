<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'bd.php';

    $codProduto = $_POST['codProduto'];
    $nomeProduto = $_POST['nomeProduto'];
    $categoria = $_POST['categoria'];
    $validade = $_POST['validade'];
    $quantidade = $_POST['quantidade'];
    $precoUnitario = $_POST['precoUnitario'];
    $precoPacote = $_POST['precoPacote'];

    // Handle file upload
    $imagemProduto = $_FILES['imagemProduto'];
    $uploadDir = 'uploads/';
    $uploadFile = $uploadDir . basename($imagemProduto['name']);

    if (move_uploaded_file($imagemProduto['tmp_name'], $uploadFile)) {
        $imagemPath = $uploadFile;
    } else {
        die('Erro ao fazer upload da imagem.');
    }

    // Insert into database
    $sql = "INSERT INTO produtos (cod, nome, categoria, validade, quantidade, preco_unitario, preco_pacote, imagem) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssidds", $codProduto, $nomeProduto, $categoria, $validade, $quantidade, $precoUnitario, $precoPacote, $imagemPath);

    if ($stmt->execute()) {
        echo "Produto cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar produto: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Cadastro de Produtos</h1>
        <form action="index.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="codProduto" class="form-label">COD Produto</label>
                <input type="text" class="form-control" id="codProduto" name="codProduto" required>
            </div>
            <div class="mb-3">
                <label for="nomeProduto" class="form-label">Nome Produto</label>
                <input type="text" class="form-control" id="nomeProduto" name="nomeProduto" required>
            </div>
            <div class="mb-3">
                <label for="categoria" class="form-label">Categoria</label>
                <input type="text" class="form-control" id="categoria" name="categoria" required>
            </div>
            <div class="mb-3">
                <label for="validade" class="form-label">Validade</label>
                <input type="date" class="form-control" id="validade" name="validade" required>
            </div>
            <div class="mb-3">
                <label for="quantidade" class="form-label">Quantidade</label>
                <input type="number" class="form-control" id="quantidade" name="quantidade" required>
            </div>
            <div class="mb-3">
                <label for="precoUnitario" class="form-label">Preço Unitário</label>
                <input type="number" step="0.01" class="form-control" id="precoUnitario" name="precoUnitario" required>
            </div>
            <div class="mb-3">
                <label for="precoPacote" class="form-label">Preço Pacote</label>
                <input type="number" step="0.01" class="form-control" id="precoPacote" name="precoPacote" required>
            </div>
            <div class="mb-3">
                <label for="imagemProduto" class="form-label">Imagem do Produto</label>
                <input type="file" class="form-control" id="imagemProduto" name="imagemProduto" required>
            </div>
            <button type="submit" class="btn btn-success">Cadastrar</button>
        </form>
    </div>
</body>
</html>
