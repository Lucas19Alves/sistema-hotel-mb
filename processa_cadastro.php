<?php
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $cpf = $_POST["cpf"];
    $data_nascimento = $_POST["data_nascimento"];
    $celular = $_POST["celular"];
    $cidade = $_POST["cidade"];
    $sexo = $_POST["sexo"];
    $stmt = $conn->prepare("INSERT INTO cadastro_clientes (nome, cpf, data_nascimento, celular, cidade, sexo) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nome, $cpf, $data_nascimento, $celular, $cidade, $sexo);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit(); 
    } else {
        echo "Erro ao cadastrar: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Método de requisição inválido";
}

$conn->close();
?>
