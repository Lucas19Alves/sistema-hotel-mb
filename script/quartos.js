let quartos = [
    { numero: 101, hospede: '', tipo: 'SIMPLES' },
    { numero: 102, hospede: '', tipo: 'AR CONDICIONADO' },
    { numero: 103, hospede: '', tipo: 'AR CONDICIONADO' },
    { numero: 104, hospede: '', tipo: 'SIMPLES' },
    { numero: 105, hospede: '', tipo: 'SIMPLES' },
    { numero: 106, hospede: '', tipo: 'AR CONDICIONADO' },
    { numero: 107, hospede: '', tipo: 'VENTILADOR' },
    { numero: 108, hospede: '', tipo: 'SIMPLES' },
    { numero: 109, hospede: '', tipo: 'SIMPLES' },
    { numero: 110, hospede: '', tipo: 'SIMPLES' },
    { numero: 111, hospede: '', tipo: 'AR CONDICIONADO' },
    { numero: 112, hospede: '', tipo: 'AR CONDICIONADO' },
    { numero: 113, hospede: '', tipo: 'VENTILADOR' },
    { numero: 114, hospede: '', tipo: 'VENTILADOR' },
    { numero: 115, hospede: '', tipo: 'VENTILADOR' },
    { numero: 116, hospede: '', tipo: 'SIMPLES' },
    { numero: 117, hospede: '', tipo: 'VENTILADOR' },
    { numero: 118, hospede: '', tipo: 'AR CONDICIONADO' },
    { numero: 119, hospede: '', tipo: 'VENTILADOR' },
    { numero: 120, hospede: '', tipo: 'SIMPLES' },
    { numero: 121, hospede: '', tipo: 'AR CONDICIONADO' },
    { numero: 122, hospede: '', tipo: 'VENTILADOR' },
    { numero: 123, hospede: '', tipo: 'VENTILADOR' },
    { numero: 124, hospede: '', tipo: 'AR CONDICIONADO' },
    { numero: 125, hospede: '', tipo: 'AR CONDICIONADO' },
    { numero: 126, hospede: '', tipo: 'AR CONDICIONADO' },
];

// Função para abrir o modal de seleção de hóspede
function abrirModalSelecionarHospede(numeroQuarto) {
    // Limpa os campos antes de exibir o modal
    $('#inputNomeHospede').val('');
    $('#quantidadePessoas').val('');
    $('#valorHospedagem').val('');

    // Exibe o modal
    $('#modalSelecionarHospede').modal('show');

    // Evento de clique no botão de confirmar no modal
    $('#btnConfirmarHospede').off('click').on('click', function () {
        const nomeHospede = $('#inputNomeHospede').val();
        const quantidadePessoas = parseInt($('#quantidadePessoas').val());
        const valorHospedagem = parseFloat($('#valorHospedagem').val());

        // Calcula o valor total da hospedagem
        const valorTotal = valorHospedagem * quantidadePessoas;

        // Atualiza o atributo 'hospede' e 'valor' do objeto correspondente no array 'quartos'
        const quartoIndex = quartos.findIndex(quarto => quarto.numero === numeroQuarto);
        quartos[quartoIndex].hospede = nomeHospede;
        quartos[quartoIndex].valor = valorTotal;

        // Atualiza o texto do card do quarto com o nome do hóspede selecionado e o valor total da hospedagem
        const card = $(`.quarto[data-numero="${numeroQuarto}"]`);
        card.find('.card-text').html(`Nome: ${nomeHospede ? nomeHospede : 'Disponível'}<br>Valor Total: ${valorTotal.toFixed(2)}`);

        // Altera o botão para 'Ocupado' e sua classe para 'btn-danger'
        card.find('.btn').text('Ocupado').removeClass('btn-primary').addClass('btn-danger');

        // Salva os quartos localmente após a modificação
        salvarQuartosLocalmente();

        // Fecha o modal
        $('#modalSelecionarHospede').modal('hide');
    });
}

// Função para criar os cards dos quartos
function criarCards() {
    const container = $('#quartos');

    quartos.forEach(quarto => {
        const card = $(`
            <div class="quarto disponivel card col-md-4 col-lg-2 m-4" data-numero="${quarto.numero}" data-hospede="${quarto.hospede}" data-tipo="${quarto.tipo}">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title text-center">Quarto ${quarto.numero}</h5>
                    <p class="card-text text-center">${quarto.hospede ? 'Nome: ' + quarto.hospede + '<br>Valor Total: ' + (quarto.valor ? quarto.valor.toFixed(2) : '0.00') : 'Tipo: ' + quarto.tipo}</p>
                    <div class="mt-auto">
                        <button class="btn ${quarto.hospede ? 'btn-danger' : 'btn-primary'} w-100">${quarto.hospede ? 'Ocupado' : 'Disponível'}</button>
                    </div>
                </div>
            </div>
        `);

        // Evento de clique no botão
        card.find('.btn').click(function () {
            if (!quarto.hospede) {
                abrirModalSelecionarHospede(quarto.numero);
            } else {
                // Exibir modal de confirmação para liberar o quarto
                $('#modalConfirmacaoLiberarQuarto').modal('show');

                // Evento de clique no botão de confirmação no modal de confirmação
                $('#btnConfirmarLiberacaoQuarto').off('click').on('click', function () {
                    // Atualiza o card do quarto para o estado disponível
                    quarto.hospede = '';
                    quarto.valor = 0;
                    const card = $(`.quarto[data-numero="${quarto.numero}"]`);
                    card.find('.card-text').html(`Tipo: ${quarto.tipo}`);
                    card.find('.btn').text('Disponível').removeClass('btn-danger').addClass('btn-primary');

                    // Salva os quartos localmente após a modificação
                    salvarQuartosLocalmente();

                    // Fecha o modal de confirmação
                    $('#modalConfirmacaoLiberarQuarto').modal('hide');
                });
            }
        });

        container.append(card);
    });
}

// Função para salvar os dados dos quartos no localStorage
function salvarQuartosLocalmente() {
    localStorage.setItem('quartos', JSON.stringify(quartos));
}

// Função para carregar os dados dos quartos do localStorage
function carregarQuartosLocalmente() {
    const quartosLocalStorage = localStorage.getItem('quartos');
    if (quartosLocalStorage) {
        quartos = JSON.parse(quartosLocalStorage);
    }
}

// Chamada da função para criar os cards dos quartos quando a página é carregada
$(document).ready(function () {
    carregarQuartosLocalmente();
    criarCards();
});