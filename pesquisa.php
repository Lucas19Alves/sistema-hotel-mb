<?php
include 'conexao.php';

// Verifica se foi enviada uma consulta na URL
if(isset($_GET['pesquisa'])) {
    // Limpa a entrada para evitar injeção de SQL
    $pesquisa = mysqli_real_escape_string($conn, $_GET['pesquisa']);

    // Monta a consulta SQL com a cláusula WHERE para filtrar os resultados
    $query = "SELECT * FROM cadastro_clientes WHERE nome LIKE '%$pesquisa%'";
} else {
    // Consulta SQL padrão para obter todos os registros
    $query = "SELECT * FROM cadastro_clientes";
}

$result = mysqli_query($conn, $query);

header('Content-Type: application/json'); // Indica que o conteúdo é JSON

if ($result) {
    $cadastros = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $cadastros[] = $row;
    }

    mysqli_free_result($result);

    echo json_encode($cadastros);
    die(); // Interrompe a execução após enviar o JSON
} else {
    echo json_encode(['error' => 'Erro ao obter cadastros']);
}

mysqli_close($conn);
?>
