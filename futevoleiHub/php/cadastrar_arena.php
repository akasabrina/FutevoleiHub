<?php
include 'conexao.php';
session_start();

// Verifica se o administrador está logado
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'administrador') {
  echo "<script>alert('Acesso não autorizado.'); window.location.href='../index.php';</script>";
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Coleta os dados do formulário
  $nome_arena = $_POST['nome_arena'] ?? '';
  $descricao  = $_POST['descricao'] ?? '';
  $url_iframe = $_POST['url_maps_iframe'] ?? '';
  $url_direto = $_POST['url_maps_direto'] ?? '';

  // Verifica se todos os campos foram preenchidos
  if (empty($nome_arena) || empty($descricao) || empty($url_iframe) || empty($url_direto)) {
    echo "<script>alert('Por favor, preencha todos os campos!'); history.back();</script>";
    exit;
  }

  try {
    // Verifica se o nome da arena já está cadastrado
    $verifica = $conn->prepare("SELECT 1 FROM info_geral.arena WHERE nome_arena = :nome");
    $verifica->execute([':nome' => $nome_arena]);

    if ($verifica->rowCount() > 0) {
      echo "<script>alert('Já existe uma arena com esse nome.'); history.back();</script>";
      exit;
    }

    // Prepara o INSERT
    $sql = "INSERT INTO info_geral.arena (nome_arena, descricao, url_maps_iframe, url_maps_direto)
            VALUES (:nome_arena, :descricao, :url_iframe, :url_direto)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
      ':nome_arena' => $nome_arena,
      ':descricao'  => $descricao,
      ':url_iframe' => $url_iframe,
      ':url_direto' => $url_direto
    ]);

    echo "<script>alert('Arena cadastrada com sucesso!'); window.location.href='../painel_adm.php';</script>";
    exit;
  } catch (PDOException $e) {
    echo "<script>alert('Erro ao cadastrar: " . $e->getMessage() . "'); history.back();</script>";
    exit;
  }
}
?>
