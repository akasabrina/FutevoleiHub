<?php
include 'conexao.php';
session_start();

// Verifica se o administrador está logado
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'administrador') {
  echo "<script>alert('Acesso não autorizado.'); window.location.href='../index.php';</script>";
  exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Coleta os dados do formulário
  $nome      = $_POST["nome_camp"] ?? '';
  $desc      = $_POST["descricao"] ?? '';
  $categoria = $_POST["categoria"] ?? '';
  $data_camp = $_POST["data_camp"] ?? '';
  $fim       = $_POST["data_fim_inscricao"] ?? '';
  $arena     = $_POST["id_arena"] ?? '';

  // Verifica se todos os campos estão preenchidos
  if (empty($nome) || empty($desc) || empty($categoria) || empty($data_camp) || empty($fim) || empty($arena)) {
    echo "<script>alert('Por favor, preencha todos os campos!'); history.back();</script>";
    exit;
  }

  try {
    // Verifica se já existe campeonato com o mesmo nome e data
    $verifica = $conn->prepare("SELECT 1 FROM info_geral.campeonatos WHERE nome_camp = :nome AND data_camp = :data_camp");
    $verifica->execute([
      ':nome' => $nome,
      ':data_camp' => $data_camp
    ]);

    if ($verifica->rowCount() > 0) {
      echo "<script>alert('Já existe um campeonato com esse nome e/ou data.'); history.back();</script>";
      exit;
    }

    if ($data_camp < $fim) {
      echo "<script>alert('O fim das inscrições tem que ser antes do dia do campeonato'); history.back();</script>";
      exit;
    }

    // Prepara o INSERT
    $sql = "INSERT INTO info_geral.campeonatos (nome_camp, descricao, categoria, data_camp, data_fim_inscricao, id_arena)
            VALUES (:nome, :descricao, :categoria, :data_camp, :fim, :arena)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
      ':nome'      => $nome,
      ':descricao' => $desc,
      ':categoria' => $categoria,
      ':data_camp' => $data_camp,
      ':fim'       => $fim,
      ':arena'     => $arena
    ]);

    echo "<script>alert('Campeonato cadastrado com sucesso!'); window.location.href='../painel_adm.php';</script>";
    exit;
  } catch (PDOException $e) {
    echo "<script>alert('Erro ao cadastrar: " . $e->getMessage() . "'); history.back();</script>";
    exit;
  }
}
