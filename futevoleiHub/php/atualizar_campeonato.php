<?php
include 'conexao.php';
include 'utils.php';

session_start();

if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'administrador') {
  echo "<script>alert('Acesso não autorizado.'); window.location.href='../index.php';</script>";
  exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  echo "<script>alert('Requisição inválida.'); window.location.href='../painel_adm.php';</script>";
  exit;
}

$id        = isset($_POST["id_camp"]) ? (int) $_POST["id_camp"] : 0;
$nome_camp = trim($_POST["nome_camp"] ?? '');
$descricao = trim($_POST["descricao"] ?? '');
$categoria = trim($_POST["categoria"] ?? '');
$data_camp = $_POST["data_camp"] ?? '';
$fim       = $_POST["data_fim_inscricao"] ?? '';
$arena     = isset($_POST["id_arena"]) ? (int) $_POST["id_arena"] : 0;

if ($id <= 0 || $arena <= 0 || $nome_camp === '' || $descricao === '' || $categoria === '' || $data_camp === '' || $fim === '') {
  echo "<script>alert('Preencha todos os campos obrigatórios.'); window.history.back();</script>";
  exit;
}

if ($data_camp <= $fim) {
  echo "<script>alert('O encerramento das inscrições deve ser antes da data do campeonato.'); window.history.back();</script>";
  exit;
}

try {
  $sql = "UPDATE info_geral.campeonatos 
          SET nome_camp = :nome, 
              descricao = :descricao, 
              categoria = :categoria, 
              data_camp = :data_camp, 
              data_fim_inscricao = :fim, 
              id_arena = :arena 
          WHERE id_camp = :id";

  $stmt = $conn->prepare($sql);
  $stmt->execute([
    ':nome'      => $nome_camp,
    ':descricao' => $descricao,
    ':categoria' => $categoria,
    ':data_camp' => $data_camp,
    ':fim'       => $fim,
    ':arena'     => $arena,
    ':id'        => $id
  ]);

  echo "<script>alert('Informações do campeonato atualizadas com sucesso.'); window.location.href='../painel_adm.php';</script>";
  exit;
} catch (PDOException $e) {
  registrarErro("Erro ao atualizar campeonato: " . $e->getMessage());
  echo "<script>alert('Erro ao atualizar campeonato. Por favor, tente novamente mais tarde.'); window.history.back();</script>";
  exit;
}
