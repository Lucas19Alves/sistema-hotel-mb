// Função para exibir o toast de sucesso
function exibirToast(mensagem) {
    var toast = document.getElementById("toast");
    toast.textContent = mensagem;
    toast.classList.add("mostrar");

    // Oculta o toast após 3 segundos
    setTimeout(function(){
        toast.classList.remove("mostrar");
    }, 3000);
}

// Verifica se o parâmetro de URL "sucesso" está presente e exibe o toast de sucesso
var urlParams = new URLSearchParams(window.location.search);
if (urlParams.has('sucesso')) {
    exibirToast("Cadastro realizado com sucesso!");
}