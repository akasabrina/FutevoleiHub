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

$nome      = trim($_POST["nome_camp"] ?? '');
$desc      = trim($_POST["descricao"] ?? '');
$categoria = trim($_POST["categoria"] ?? '');
$data_camp = trim($_POST["data_camp"] ?? '');
$fim       = trim($_POST["data_fim_inscricao"] ?? '');
$arena     = isset($_POST["id_arena"]) ? (int)$_POST["id_arena"] : 0;

if (empty($nome) || empty($desc) || empty($categoria) || empty($data_camp) || empty($fim) || $arena <= 0) {
  echo "<script>alert('Por favor, preencha todos os campos corretamente!'); history.back();</script>";
  exit;
}

// Validação básica das datas (formato e lógica)
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data_camp) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fim)) {
  echo "<script>alert('Datas inválidas. Use o formato AAAA-MM-DD.'); history.back();</script>";
  exit;
}

if ($fim > $data_camp) {
  echo "<script>alert('O encerramento das inscrições deve ser antes da data do campeonato.'); history.back();</script>";
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
    echo "<script>alert('Já existe um campeonato com esse nome e data.'); history.back();</script>";
    exit;
  }

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
  registrarErro("Erro ao cadastrar aula: " . $e->getMessage());
  echo "<script>alert('Erro ao cadastrar campeonato. Por favor, tente novamente mais tarde.'); history.back();</script>";
  exit;
}
