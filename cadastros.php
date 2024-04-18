<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta - Sistema</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

    <style>
        body {
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
            background-color: #f2f2f2;
            /* Cor de fundo */
            margin: 0;
            padding: 0;
        }

        .logotipo {
            max-width: 200px;
            height: auto;
        }

        .nome-hotel {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
        }

        .formulario-cliente {
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);

        }

        .caixa-reservas {
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            height: 220px;
            overflow-y: auto;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .caixa-reservas ul {
            list-style-type: none;
            padding: 0;
        }

        .caixa-reservas ul li {
            margin-bottom: 5px;
        }

        .cartao {
            margin-bottom: 20px;
        }

        #mensagemToast {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            background-color: #28a745;
            /* Cor verde sucesso */
            color: #ffffff;
            /* Cor do texto */
            border-radius: 0.25rem;
            padding: 10px 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            /* Sombra suave */
        }

        header {
            background-color: #333;
            padding: 15px;
        }

        .btn-nav {
            text-transform: uppercase;
        }
    </style>
</head>

<body>

    <header class="container-fluid">
        <div class="row">
            <div class="col-md-4 navbar justify-content-center">
                <div class="btn-group">
                    <button data-bs-toggle="modal" data-bs-target="#modalReservar" class="btn btn-primary">FAZER NOVA
                        RESERVA</button>
                </div>
            </div>
            <div class="col-md-4">
                <nav class="navbar justify-content-center">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a href="index.php" class="btn btn-nav btn-outline-light">Quartos</a>
                        <a href="cadastros.php" class="btn btn-nav btn-light">Cadastros</a>
                        <a href="exibir_reservas.php" class="btn btn-nav btn-outline-light">Reservas</a>
                    </div>
                </nav>
            </div>
            <div class="col-md-4 navbar justify-content-center">
                <div class="btn-group">
                    <a href="https://hotel.bitzsoftwares.com.br/sistema/nfe/" class="btn btn-light">NF-e</a>
                    <a href="https://hotel.bitzsoftwares.com.br/sistema/nfce/" class="btn btn-light">NFC-e</a>
                    <a href="https://www.nfservico.com.br/mortugaba/#/home" class="btn btn-light">NFS-e</a>
                </div>
            </div>
        </div>
    </header>


    <section class="container-fluid pb-4">
        <div class="row mt-3 align-items-center">
            <div class="col-md-4">
                <div class="text-center">
                    <img src="images/logomb-fotor-bg-remover-20230713155746.png" alt="Logotipo do Hotel Minas Bahia"
                        class="logotipo">
                    <br>
                    <span class="nome-hotel">Hotel Minas Bahia</span>
                </div>
            </div>
            <div class="col-md-4">
                <form class="formulario-cliente p-2" id="cadastroForm" method="post" action="processa_cadastro.php">
                    <div class="text-center">
                        <h5>Cadastro de cliente</h5>
                    </div>
                    <div class="row mb-1">
                        <div class="col">
                            <input type="text" class="form-control" id="nome" placeholder="Nome" name="nome" required>
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" id="cpf" placeholder="CPF" name="cpf" required>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col">
                            <input type="date" class="form-control" id="data_nascimento" name="data_nascimento"
                                required>
                        </div>
                        <div class="col">
                            <input type="tel" class="form-control" id="celular" placeholder="Celular" name="celular"
                                required>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col">
                            <div class="form-group">
                                <select class="form-control" id="sexo" name="sexo" required>
                                    <option value="" disabled selected>Selecione o sexo</option>
                                    <option value="masculino">Masculino</option>
                                    <option value="feminino">Feminino</option>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" id="cidade" placeholder="Cidade" name="cidade"
                                required>
                        </div>
                    </div>
                    <button type="submit" name="salvar_cadastro" class="btn btn-primary w-100" id="submitBtn">Salvar
                        Cadastro</button>
                </form>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <h5 class="card-header">Reservas do dia</h5>
                    <div class="card-body">
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

                        // Query para buscar as reservas do dia atual
                        $data_atual = date('Y-m-d');
                        $query_reservas = $conexao->prepare("SELECT * FROM reservas WHERE data_entrada <= ? AND data_saida >= ?");
                        $query_reservas->bind_param("ss", $data_atual, $data_atual);
                        $query_reservas->execute();
                        $resultados = $query_reservas->get_result();

                        // Exibir as reservas encontradas
                        if ($resultados->num_rows > 0) {
                            echo "<ul>";
                            while ($row = $resultados->fetch_assoc()) {
                                echo "<li>{$row['nome']} - Quarto {$row['numero_quarto']}</li>";
                            }
                            echo "</ul>";
                        } else {
                            echo "Nenhuma reserva para hoje.";
                        }

                        // Fechar a conexão
                        $conexao->close();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="modalReservar" tabindex="-1" role="dialog" aria-labelledby="modalTitleId"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitleId">
                        Nova reserva
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="processar_reserva.php" method="POST">
                        <div class="form-group">
                            <label for="nome"><b>Nome:</b></label>
                            <input type="text" class="form-control" name="nome" required>
                        </div>

                        <div class="form-group">
                            <label for="quantidade_pessoas"><b>Quantidade de Pessoas:</b></label>
                            <input type="number" class="form-control" name="quantidade_pessoas" required>
                        </div>

                        <div class="form-group">
                            <label for="data_entrada"><b>Data de Entrada:</b></label>
                            <input type="date" class="form-control" name="data_entrada" required>
                        </div>

                        <div class="form-group">
                            <label for="data_saida"><b>Data de Saída:</b></label>
                            <input type="date" class="form-control" name="data_saida" required>
                        </div>

                        <div class="form-group">
                            <label for="valor_por_pessoa"><b>Valor por Pessoa:</b></label>
                            <input type="text" class="form-control" name="valor_por_pessoa" required>
                        </div>

                        <div class="form-group">
                            <label for="numero_quarto"><b>Número do Quarto (101-126):</b></label>
                            <input type="number" class="form-control" name="numero_quarto" min="101" max="126" required>
                        </div>
                        <div class="btn-group mt-2 d-flex align-self-stretch align-content-center">
                            <button type="submit" class="btn btn-success">Registrar Reserva</button>
                            <a href="exibir_reservas.php" class="btn btn-primary ml-auto">Ver Reservas</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Barra de Busca -->
    <div class="container">
        <h2 class="text-center border-bottom mt-5 border-secondary pb-3">Cadastros</h2>
        <div class="form-group mt-3">
            <input type="text" class="form-control" id="busca" placeholder="Buscar por nome ou CPF"
                oninput="filtrarCadastros()">
        </div>
    </div>


    <!-- Tabela de Cadastros -->
    <div class="container mt-3">
        <h3 class="text-center">Cadastros Registrados</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Data de Nascimento</th>
                    <th>Celular</th>
                    <th>Cidade</th>
                    <th>Sexo</th>
                </tr>
            </thead>
            <tbody id="tabelaCadastros">
                <!-- Dados serão inseridos aqui dinamicamente -->
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Faz a requisição Ajax para obter os cadastros do PHP
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'obter_cadastros.php', true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var cadastros = JSON.parse(xhr.responseText);
                    exibirCadastrosNaTabela(cadastros);
                }
            };
            xhr.send();
        });

        function exibirCadastrosNaTabela(cadastros) {
            var tabela = document.getElementById('tabelaCadastros');

            // Certifique-se de que tbody existe, se não, crie-o
            var tbody = tabela.querySelector('tbody');
            if (!tbody) {
                tbody = document.createElement('tbody');
                tabela.appendChild(tbody);
            } else {
                // Limpar o conteúdo existente da tabela antes de atualizar
                tbody.innerHTML = '';
            }

            cadastros.forEach(function (cadastro) {
                adicionarCadastroNaTabela(cadastro, tbody);
            });
        }

        function adicionarCadastroNaTabela(cadastro) {
            // Obtenha a tabela de cadastros
            var tabelaCadastros = document.getElementById('tabelaCadastros');

            // Crie uma nova linha na tabela
            var novaLinha = tabelaCadastros.insertRow();

            // Adicione células à nova linha com os dados do cadastro
            novaLinha.insertCell(0).innerHTML = cadastro.nome;
            novaLinha.insertCell(1).innerHTML = formatarCPF(cadastro.cpf);
            novaLinha.insertCell(2).innerHTML = cadastro.data_nascimento;
            novaLinha.insertCell(3).innerHTML = cadastro.celular;
            novaLinha.insertCell(4).innerHTML = cadastro.cidade;
            novaLinha.insertCell(5).innerHTML = cadastro.sexo;
        }

        function formatarCPF(cpf) {
            // Adiciona a pontuação no CPF
            return cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
        }

        function filtrarCadastros() {
            var termo = document.getElementById('busca').value.toUpperCase();
            var tabela = document.getElementById('tabelaCadastros');
            var linhas = tabela.getElementsByTagName('tr');

            for (var i = 0; i < linhas.length; i++) {
                var dados = linhas[i].getElementsByTagName('td');
                var exibir = false;

                for (var j = 0; j < dados.length; j++) {
                    if (dados[j]) {
                        var texto = dados[j].innerText || dados[j].textContent;

                        if (texto.toUpperCase().indexOf(termo) > -1) {
                            exibir = true;
                            break;
                        }
                    }
                }

                linhas[i].style.display = exibir ? '' : 'none';
            }
        }


    </script>
    <script>
        var modalId = document.getElementById('modalReservar');

        modalId.addEventListener('show.bs.modal', function (event) {
            // Button that triggered the modal
            let button = event.relatedTarget;
            // Extract info from data-bs-* attributes
            let recipient = button.getAttribute('data-bs-whatever');

            // Use above variables to manipulate the DOM
        });
    </script>