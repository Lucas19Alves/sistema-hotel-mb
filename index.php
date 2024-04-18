<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quartos - Sistema</title>
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
            <a href="index.php" class="btn btn-nav btn-light">Quartos</a>
            <a href="cadastros.php" class="btn btn-nav btn-outline-light">Cadastros</a>
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
              <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" required>
            </div>
            <div class="col">
              <input type="tel" class="form-control" id="celular" placeholder="Celular" name="celular" required>
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
              <input type="text" class="form-control" id="cidade" placeholder="Cidade" name="cidade" required>
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

  <div class="container-fluid ">
    <div class="bg-white rounded p-5 m-5">
      <div class="row">
        <h2 class="text-center border-bottom border-secondary pb-3 mb-4">Gerenciamento de Quartos</h2>
      </div>
      <div class="row" id="quartos">

      </div>
    </div>
  </div>
  </div>

  <!-- Modal Selecionar Hóspede -->
  <div class="modal fade" id="modalSelecionarHospede" tabindex="-1" aria-labelledby="modalSelecionarHospedeLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalSelecionarHospedeLabel">Check-in</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="inputNomeHospede" class="form-label">Nome do Hóspede:</label>
            <input type="text" class="form-control" id="inputNomeHospede" placeholder="Digite o nome do hóspede"
              required>
          </div>
          <div class="mb-3">
            <label for="quantidadePessoas" class="form-label">Quantidade de Pessoas:</label>
            <input type="number" class="form-control" id="quantidadePessoas"
              placeholder="Digite a quantidade de pessoas" required>
          </div>
          <div class="mb-3">
            <label for="valorHospedagem" class="form-label">Valor por pessoa:</label>
            <input type="number" class="form-control" id="valorHospedagem" placeholder="Digite o valor da hospedagem por pessoa"
              required>
          </div>
        </div>


        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          <button type="button" class="btn btn-primary" id="btnConfirmarHospede">Confirmar</button>
        </div>
      </div>
    </div>
  </div>


  <div class="modal fade" id="modalConfirmacaoLiberarQuarto" tabindex="-1"
    aria-labelledby="modalConfirmacaoLiberarQuartoLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalConfirmacaoLiberarQuartoLabel">Confirmar Liberação do Quarto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Você confirma a liberação do quarto?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" id="btnConfirmarLiberacaoQuarto">Confirmar</button>
        </div>
      </div>
    </div>
  </div>


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
  <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
  <script src="script/quartos.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>