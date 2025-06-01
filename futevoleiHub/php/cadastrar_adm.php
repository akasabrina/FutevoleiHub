<?php
include 'conexao.php';
session_start();

// Verifica se o administrador está logado
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'administrador') {
  echo "<script>alert('Acesso não autorizado.'); window.location.href='../index.php';</script>";
  exit;
}

// Verifica se foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // Coleta e sanitiza os dados do formulário
  $nome           = trim($_POST['nome_adm'] ?? '');
  $email          = trim($_POST['email'] ?? '');
  $senha          = $_POST['senha'] ?? '';
  $confirmaSenha  = $_POST['confirma_senha'] ?? '';

  // Verifica se todos os campos foram preenchidos
  if (empty($nome) || empty($email) || empty($senha) || empty($confirmaSenha)) {
    echo "<script>alert('Por favor, preencha todos os campos!'); history.back();</script>";
    exit;
  }

  // Verifica se o e-mail é válido
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('E-mail inválido.'); history.back();</script>";
    exit;
  }

  // Verifica se as senhas coincidem
  if ($senha !== $confirmaSenha) {
    echo "<script>alert('As senhas não coincidem.'); history.back();</script>";
    exit;
  }

  // Criptografa a senha
  $senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);

  try {
    // Verifica se o e-mail já está cadastrado
    $verifica = $conn->prepare("SELECT 1 FROM info_geral.adm WHERE email = :email");
    $verifica->execute([':email' => $email]);

    if ($verifica->rowCount() > 0) {
      echo "<script>alert('Este e-mail já está cadastrado.'); history.back();</script>";
      exit;
    }

    // Prepara e executa o INSERT
    $sql = "INSERT INTO info_geral.adm (nome_adm, email, senha)
            VALUES (:nome, :email, :senha)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
      ':nome'  => $nome,
      ':email' => $email,
      ':senha' => $senhaCriptografada
    ]);

    echo "<script>alert('Administrador cadastrado com sucesso!'); window.location.href='../painel_adm.php';</script>";
    exit;

  } catch (PDOException $e) {
    echo "<script>alert('Erro ao cadastrar: " . $e->getMessage() . "'); history.back();</script>";
    exit;
  }
}

?>
