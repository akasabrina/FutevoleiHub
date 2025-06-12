document.addEventListener("DOMContentLoaded", () => {
  const formLogin = document.getElementById("formLogin");

  formLogin.addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(formLogin);

    fetch("login.php", {
      method: "POST",
      body: formData
    })
      .then(res => res.json())
      .then(data => {
        if (data.status === "administrador") {
          window.location.href = "painel_adm.php";
        } else if (data.status === "professor") {
          window.location.href = "painel_professor.php";
        } else if (data.status === "aluno") {
          window.location.href = "painel_aluno.php";
        } else {
          alert("E-mail ou senha inválidos.");
        }
      })
      .catch(async (err) => {
        const responseText = await err.text?.();
        alert("Erro ao tentar logar: " + err.message + "\n" + responseText);
      });

  });
});
