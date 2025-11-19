//index.php
    window.addEventListener('DOMContentLoaded', () => {
      const container = document.getElementById('container');
      setTimeout(() => container.classList.add('translate-y-0', 'opacity-100'), 50);
    });


document.addEventListener("DOMContentLoaded", () => {
    // Fade-in da página carregando
    document.body.classList.add("page-loaded");

    // Intercepta todos os links para animar o fade-out
    document.querySelectorAll("a").forEach(link => {
        if (link.target === "_blank") return; // evita abrir nova aba
        if (link.href.endsWith("#")) return;

        link.addEventListener("click", e => {
            e.preventDefault();
            const url = link.href;

            document.body.classList.remove("page-loaded");
            document.body.classList.add("fade-out");

            setTimeout(() => {
                window.location.href = url;
            }, 500); // igual ao tempo da animação
        });
    });
});

//login.php
 const form = document.getElementById('login-form');
    const container = document.getElementById('login-container');

    form.addEventListener('submit', function(e) {
      e.preventDefault(); // previne envio imediato

      // Adiciona a classe de saída para animar
      container.classList.add('exit');

      // Após a animação, envia o form de verdade
      setTimeout(() => {
        form.submit();
      }, 600); // igual ao tempo do transition (0.6s)
    });