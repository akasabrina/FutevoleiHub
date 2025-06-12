<?php
session_start();
include 'php/conexao.php';

if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'aluno') {
  header("Location: index.php");
  exit;
}

// Armazena os dados da pessoa logada
$id_aluno = $_SESSION['id'];
$nome_aluno = $_SESSION['nome'];
//$tipo = $_SESSION['tipo'];

// Quantidade campeonatos
$qtd = $conn->query("SELECT COUNT(*) FROM info_geral.campeonatos")->fetchColumn();
// Quantidade aulas particulares
$stmt = $conn->prepare("SELECT COUNT(*) FROM info_geral.aulas_particulares WHERE id_aluno = :id_aluno");
$stmt->execute([':id_aluno' => $id_aluno]);
$qtd1 = $stmt->fetchColumn();

// Consulta informações do aluno
$sql_info_aluno = "SELECT al.id_aluno, al.nome_aluno, al.email, al.id_arena, ar.nome_arena
  FROM info_geral.alunos al
  JOIN info_geral.arena ar ON al.id_arena = ar.id_arena
  WHERE al.id_aluno = :id_aluno";
$stmt1 = $conn->prepare($sql_info_aluno);
$stmt1->execute([':id_aluno' => $id_aluno]);
$info_aluno = $stmt1->fetch(PDO::FETCH_ASSOC);

// Busca aulas particulares do aluno
$sql_aulas = "SELECT au.*, p.nome_professor, ar.nome_arena
  FROM info_geral.aulas_particulares au
  JOIN info_geral.professores p ON au.id_professor = p.id_professor
  JOIN info_geral.arena ar ON au.id_arena = ar.id_arena
  WHERE au.id_aluno = :id_aluno
  ORDER BY au.horario_inicio";
$stmt_aulas = $conn->prepare($sql_aulas);
$stmt_aulas->execute([':id_aluno' => $id_aluno]);
$aulas_particulares = $stmt_aulas->fetchAll(PDO::FETCH_ASSOC);

// Consultar arenas
$arenas = $conn->query("SELECT * FROM info_geral.arena")->fetchAll(PDO::FETCH_ASSOC);

// Consulta campeonatos
$sql_consulta_campeonatos = "SELECT c.*, ar.nome_arena, ar.id_arena
  FROM info_geral.campeonatos c
  JOIN info_geral.arena ar ON c.id_arena = ar.id_arena
  ORDER BY data_camp DESC";
$campeonatos = $conn->query($sql_consulta_campeonatos)->fetchAll(PDO::FETCH_ASSOC);

// Consulta inscrições de campeonatos do aluno
$sql_consulta_insc = "SELECT i.id_inscricao, c.nome_camp, c.data_camp, ar.nome_arena
  FROM info_geral.inscricao_campeonato i
  JOIN info_geral.campeonatos c ON c.id_camp = i.id_camp
  JOIN info_geral.alunos al ON al.id_aluno = i.id_aluno
  JOIN info_geral.arena ar ON ar.id_arena = c.id_arena
  WHERE i.id_aluno = :id_aluno
  ORDER BY c.data_camp DESC";

$stmt2 = $conn->prepare($sql_consulta_insc);
$stmt2->execute([':id_aluno' => $id_aluno]);
$inscricoes = $stmt2->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel do Aluno</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/custom.css">
</head>

<body>
  <!-- Navbar -->
  <nav class="navbar fixed-top shadow-sm bg-white">
    <div class="container d-flex justify-content-between align-items-center">
      <a class="navbar-brand fw-bold" href="#">Painel do Aluno</a>
      <div>
        <span>Olá, <?= htmlspecialchars($nome_aluno) ?> | <a href="php/logout.php?acao=sair" class="btn btn-danger">Sair</a></span>
      </div>
    </div>
  </nav>

  <div class="content-wrapper">
    <main>
      <!-- Contadores -->
      <div class="row mb-4">
        <div class="col-6">
          <div class="bg-primary text-white rounded p-4 shadow-sm text-center">
            <h5>Total de Campeonatos</h5>
            <h2 class="fw-bold"><?= $qtd ?></h2>
          </div>
        </div>

        <div class="col-6">
          <div class="bg-info text-white rounded p-4 shadow-sm text-center">
            <h5>Total de Aulas Particulares</h5>
            <h2 class="fw-bold"><?= $qtd1 ?></h2>
          </div>
        </div>
      </div>

      <!-- Informações do Aluno -->
      <div class="card mt-5">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
          <span>Informações pessoais</span>
          <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#editarAlunoModal">Editar Informações</button>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped">

              <thead class="table-light">
                <tr>
                  <th>Nome</th>
                  <th>Email</th>
                  <th>Arena</th>
                  <th>Ação</th>
                </tr>
              </thead>

              <tbody>
                <tr>
                  <td><?= htmlspecialchars($info_aluno['nome_aluno']) ?></td>
                  <td><?= htmlspecialchars($info_aluno['email']) ?></td>
                  <td><?= htmlspecialchars($info_aluno['nome_arena']) ?></td>
                  <td>
                    <form action="php/excluir_aluno.php" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este aluno? Esta ação não pode ser desfeita.');">
                      <input type="hidden" name="id_aluno" value="<?= $info_aluno['id_aluno'] ?>">
                      <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                    </form>
                  </td>
                </tr>
              </tbody>

            </table>
          </div>
        </div>
      </div>

      <!-- Tabela de Aulas Particulares -->
      <div class="card mt-5">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
          <span>Aulas Particulares</span>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <?php if (empty($aulas_particulares)): ?>
              <div class="alert alert-info text-center m-0">
                Nenhuma aula particular cadastrada.
              </div>
            <?php else: ?>
              <table class="table table-bordered table-striped">
                <thead class="table-light">
                  <tr>
                    <th>Professor</th>
                    <th>Arena</th>
                    <th>Dia(s)</th>
                    <th>Horário</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($aulas_particulares as $aula): ?>
                    <tr>
                      <td><?= htmlspecialchars($aula['nome_professor']) ?></td>
                      <td><?= htmlspecialchars($aula['nome_arena']) ?></td>
                      <td><?= htmlspecialchars($aula['dias_aula']) ?></td>
                      <td><?= date('H:i', strtotime($aula['horario_inicio'])) ?> - <?= date('H:i', strtotime($aula['horario_fim'])) ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Tabela de Arenas -->
      <div class="card mt-5">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
          <span>Arenas</span>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <?php if (empty($arenas)): ?>
              <div class="alert alert-info text-center m-0">
                Nenhuma arena cadastrada.
              </div>
            <?php else: ?>
              <table class="table table-bordered table-striped">

                <thead class="table-light">
                  <tr>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Localização (Maps)</th>
                  </tr>
                </thead>

                <tbody>
                  <?php foreach ($arenas as $arena): ?>
                    <tr>
                      <td><?= htmlspecialchars($arena['nome_arena']) ?></td>
                      <td><?= htmlspecialchars($arena['descricao']) ?></td>
                      <td><a href="<?= htmlspecialchars($arena['url_maps_direto']) ?>" target="_blank" class="btn btn-sm btn-info"> Ver no Maps </a></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>

              </table>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Tabela de Campeonatos -->
      <div class="card mt-5">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center"><span>Campeonatos</span></div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped">

              <thead class="table-light">
                <tr>
                  <th>Nome</th>
                  <th>Categoria</th>
                  <th>Data do campeonato</th>
                  <th>Arena</th>
                  <th>Inscrições até</th>
                </tr>
              </thead>

              <tbody>
                <?php foreach ($campeonatos as $camp): ?>
                  <tr>
                    <td><?= htmlspecialchars($camp['nome_camp']) ?></td>
                    <td><?= htmlspecialchars($camp['categoria']) ?></td>
                    <td><?= date('d/m/Y', strtotime($camp['data_camp'])) ?></td>
                    <td><?= htmlspecialchars($camp['nome_arena']) ?></td>
                    <td><?= date('d/m/Y', strtotime($camp['data_fim_inscricao'])) ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>

            </table>
          </div>
        </div>
      </div>

      <!-- Tabela de inscrições em campeonatos -->
      <div class="card mt-5">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
          <span>Inscrições em campeonatos</span>
          <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalNovaInscricao"> + Cadastrar Inscrição </button>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <?php if (empty($inscricoes)): ?>
              <div class="alert alert-info text-center m-0">
                Nenhuma inscrição em campeonato cadastrada.
              </div>
            <?php else: ?>
              <table class="table table-bordered table-striped">

                <thead class="table-light">
                  <tr>
                    <th>Campeonato</th>
                    <th>Data do campeonato</th>
                    <th>Arena</th>
                    <th>Ação</th>
                  </tr>
                </thead>

                <tbody>
                  <?php foreach ($inscricoes as $insc): ?>
                    <tr>
                      <td><?= htmlspecialchars($insc['nome_camp']) ?></td>
                      <td><?= date('d/m/Y', strtotime($insc['data_camp'])) ?></td>
                      <td><?= htmlspecialchars($insc['nome_arena']) ?></td>
                      <td>
                        <form action="php/excluir_inscricao.php" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta inscrição?');">
                          <input type="hidden" name="id_inscricao" value="<?= $insc['id_inscricao'] ?>">
                          <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                        </form>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>

              </table>
            <?php endif; ?>
          </div>
        </div>
      </div>

    </main>
  </div>

  <?php include 'modal/modais_painel_aluno.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>