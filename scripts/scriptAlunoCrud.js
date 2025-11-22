window.addEventListener("DOMContentLoaded", () => {
  const container = document.getElementById("container");
  setTimeout(() => container.classList.add("show"), 50);

  document.getElementById("voltar").addEventListener("click", (e) => {
    e.preventDefault();
    container.classList.add("exit");
    setTimeout(() => (window.location.href = "./index.php"), 600);
  });
});
