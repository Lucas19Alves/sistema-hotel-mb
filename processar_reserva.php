<?php
// Conectar ao banco de dados (ajuste as credenciais conforme necessário)
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "hotel_reservas";

$conexao = new mysqli($host, $usuario, $senha, $banco);

// Verificar a conexão
if ($conexao->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conexao->connect_error);
}

// Receber dados do formulário
$nome = $_POST['nome'];
$quantidade_pessoas = $_POST['quantidade_pessoas'];
$data_entrada = $_POST['data_entrada'];
$data_saida = $_POST['data_saida'];
$valor_por_pessoa = $_POST['valor_por_pessoa'];
$numero_quarto = $_POST['numero_quarto'];

// Inserir dados no banco de dados usando prepared statements
$query = $conexao->prepare("INSERT INTO reservas (nome, quantidade_pessoas, data_entrada, data_saida, valor_por_pessoa, numero_quarto) VALUES (?, ?, ?, ?, ?, ?)");
$query->bind_param("sissii", $nome, $quantidade_pessoas, $data_entrada, $data_saida, $valor_por_pessoa, $numero_quarto);

if ($query->execute()) {
    // Redirecionar de volta para o formulário após a inserção bem-sucedida
    header("Location: index.php");
    exit();
} else {
    echo "Erro ao registrar reserva: " . $query->error;
}

// Fechar a conexão
$conexao->close();
?>
