window.addEventListener("DOMContentLoaded", () => {
  const container = document.getElementById("container");
  setTimeout(() => container.classList.add("show"), 50);

  document.getElementById("voltar").addEventListener("click", (e) => {
    e.preventDefault();
    container.classList.add("exit");
    setTimeout(() => (window.location.href = "./index.php"), 600);
  });

  // Função para formatar nome
  function validarNome(nome_aluno) {
    nome_aluno = nome_aluno.trim();
    let partes = nome_aluno.split(/\s+/);

    if (partes.length < 2) {
      return false;
    }

    partes = partes.map(
      (p) => p.charAt(0).toUpperCase() + p.slice(1).toLowerCase()
    );

    return partes.join(" ");
  }

  // Máscara de telefone
  const telefoneInput = document.getElementById("telefone");
  telefoneInput.addEventListener("input", () => {
    let valor = telefoneInput.value.replace(/\D/g, ""); // só dígitos
    if (valor.length > 11) valor = valor.slice(0, 11);

    if (valor.length <= 10) {
      // Formato fixo (XX) XXXX-XXXX
      telefoneInput.value = valor.replace(
        /(\d{2})(\d{4})(\d{0,4})/,
        "($1) $2-$3"
      );
    } else {
      // Formato celular (XX) XXXXX-XXXX
      telefoneInput.value = valor.replace(
        /(\d{2})(\d{5})(\d{0,4})/,
        "($1) $2-$3"
      );
    }
  });

  // Máscara de CPF
  const cpfInput = document.getElementById("cpf");
  cpfInput.addEventListener("input", () => {
    let valor = cpfInput.value.replace(/\D/g, ""); // só dígitos
    if (valor.length > 11) valor = valor.slice(0, 11);

    cpfInput.value = valor.replace(
      /(\d{3})(\d{3})(\d{3})(\d{0,2})/,
      "$1.$2.$3-$4"
    );
  });

  // Validações
  function validarTelefone(telefone) {
    const regexTelefone = /^\(\d{2}\)\s?\d{4,5}-\d{4}$/;
    return regexTelefone.test(telefone);
  }

  function validarCPF(cpf) {
    const regexCPF = /^\d{3}\.\d{3}\.\d{3}-\d{2}$/;
    return regexCPF.test(cpf);
  }

  function validarIdade(idade) {
    const regexIdade = /^\d+$/;
    return regexIdade.test(idade) && idade >= 14 && idade <= 120;
  }

  document.getElementById("cadastro").addEventListener("submit", function (e) {
    const telefone = telefoneInput.value;
    const cpf = cpfInput.value;
    const idade = document.getElementById("idade").value;
    const nomeInput = document.getElementById("nome_aluno");
    const nomeFormatado = validarNome(nomeInput.value);
    const dataInput = document.getElementById("Data_matricula");

    const hoje = new Date().toISOString().split("T")[0];
    dataInput.setAttribute("max", hoje);

    // Validação da data
    if (dataInput.value > hoje) {
      alert("A data da matrícula não pode ser futura.");
      e.preventDefault();
      return;
    }

    if (!validarTelefone(telefone)) {
      alert("Telefone inválido!");
      e.preventDefault();
      return;
    }

    if (!validarCPF(cpf)) {
      alert("CPF inválido!");
      e.preventDefault();
      return;
    }

    if (!validarIdade(idade)) {
      alert("Idade inválida! Deve ser entre 14 e 120 anos.");
      e.preventDefault();
      return;
    }

    if (!nomeFormatado) {
      alert("Digite pelo menos nome e sobrenome.");
      e.preventDefault();
      return;
    }

    // Atualiza os valores para enviar ao PHP (só dígitos)
    telefoneInput.value = telefone.replace(/\D/g, "");
    cpfInput.value = cpf.replace(/\D/g, "");
    nomeInput.value = nomeFormatado;
  });
});
