<?php
include 'conexao.php';
include 'utils.php';

session_start();

if (!isset($_SESSION['id']) || !in_array($_SESSION['tipo'], ['aluno', 'professor'])) {
  echo "<script>alert('Acesso não autorizado.'); window.location.href='../index.php';</script>";
  exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id_campeonato'])) {
  echo "<script>alert('Requisição inválida.'); history.back();</script>";
  exit;
}

$id_usuario   = (int) $_SESSION['id'];
$tipo_usuario = $_SESSION['tipo']; // 'aluno' ou 'professor'
$id_campeonato = (int) $_POST['id_campeonato'];

try {
  $campo_usuario = ($tipo_usuario === 'aluno') ? 'id_aluno' : 'id_professor';
  $verifica = $conn->prepare("
        SELECT 1 
        FROM info_geral.inscricao_campeonato 
        WHERE $campo_usuario = :id_usuario AND id_camp = :id_campeonato
    ");
  $verifica->execute([
    ':id_usuario' => $id_usuario,
    ':id_campeonato' => $id_campeonato
  ]);

  if ($verifica->rowCount() > 0) {
    echo "<script>alert('Você já está inscrito neste campeonato.'); history.back();</script>";
    exit;
  }

  $sql = "
        INSERT INTO info_geral.inscricao_campeonato (id_camp, $campo_usuario) 
        VALUES (:id_campeonato, :id_usuario)
    ";
  $stmt = $conn->prepare($sql);
  $stmt->execute([
    ':id_campeonato' => $id_campeonato,
    ':id_usuario' => $id_usuario
  ]);

  echo "<script>alert('Inscrição realizada com sucesso!'); window.location.href='../painel_{$tipo_usuario}.php';</script>";
  exit;
} catch (PDOException $e) {
  registrarErro("Erro ao realizar inscrição: " . $e->getMessage());
  echo "<script>alert('Erro ao realizar inscrição. Tente novamente mais tarde.'); history.back();</script>";
  exit;
}
