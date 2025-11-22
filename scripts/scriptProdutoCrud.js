window.addEventListener("DOMContentLoaded", () => {
  const container = document.getElementById("container");
  setTimeout(() => container.classList.add("show"), 50);

  document.getElementById("voltar").addEventListener("click", (e) => {
    e.preventDefault();
    container.classList.add("exit");
    setTimeout(() => (window.location.href = "./index.php"), 600);
  });
});

function validarPreco(preco) {
  // Converte para número
  const valor = parseFloat(preco);

  // Verifica se é número e maior que zero
  return !isNaN(valor) && valor > 0;
}

function validarQuantidade(quantidade) {
  // Converte para número inteiro
  const valor = parseInt(quantidade, 10);

  // Verifica se é número e maior ou igual a 1
  return !isNaN(valor) && valor > 0;
}

document.getElementById("cadastro").addEventListener("submit", function (e) {
  const preco = document.getElementById("preco").value;
  const quantidade = document.getElementById("quantidade").value;
  if (!validarPreco(preco)) {
    alert("Preço inválido! Deve ser um número maior que 0.");
    e.preventDefault(); // 🚫 bloqueia envio
    return;
  }

  if (!validarQuantidade(quantidade)) {
    alert("Quantidade inválida! Deve ser um número inteiro maior que 0.");
    e.preventDefault(); // 🚫 bloqueia envio
    return;
  }
  // ... aqui entram suas outras validações (telefone, CPF, idade etc.)
});
