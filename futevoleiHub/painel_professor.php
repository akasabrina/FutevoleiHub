<?php
session_start();
include 'php/conexao.php';

// Verifica se o login foi feito e se é um professor
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'professor') {
  header("Location: index.php");
  exit;
}

// Armazena o nome da pessoa logada
$id_professor = $_SESSION['id'];
$nome = $_SESSION['nome'];

// Quantidade campeonatos
$qtd = $conn->query("SELECT COUNT(*) FROM info_geral.campeonatos")->fetchColumn();
// Quantidade aulas particulares
$stmt = $conn->prepare("SELECT COUNT(*) FROM info_geral.professores WHERE id_professor = :id_professor");
$stmt->execute([':id_professor' => $id_professor]);
$qtd1 = $stmt->fetchColumn();

// Consulta informações do professor
$sql_info_prof = "SELECT p.id_professor, p.nome_professor, p.email, ar.nome_arena
  FROM info_geral.professores p
  JOIN info_geral.arena ar ON p.id_arena = ar.id_arena
  WHERE p.id_professor = :id_professor";

$stmt = $conn->prepare($sql_info_prof);
$stmt->execute([':id_professor' => $id_professor]);
$info_professor = $stmt->fetch(PDO::FETCH_ASSOC);

// Consulta campeonatos
$sql_consulta_campeonatos = "SELECT c.*, ar.nome_arena, ar.id_arena
  FROM info_geral.campeonatos c
  JOIN info_geral.arena ar ON c.id_arena = ar.id_arena
  ORDER BY data_camp DESC";
$campeonatos = $conn->query($sql_consulta_campeonatos)->fetchAll(PDO::FETCH_ASSOC);

// Consultar arenas
$arenas = $conn->query("SELECT * FROM info_geral.arena")->fetchAll(PDO::FETCH_ASSOC);

// Consulta aulas particulares do professor
$sql_aula_part = "SELECT a.id_aula, al.nome_aluno, p.nome_professor, ar.nome_arena, a.dias_aula, a.horario_inicio, a.horario_fim
  FROM info_geral.aulas_particulares a
  JOIN info_geral.arena ar ON a.id_arena = ar.id_arena
  JOIN info_geral.alunos al ON a.id_aluno = al.id_aluno
  JOIN info_geral.professores p ON a.id_professor = p.id_professor
  WHERE a.id_professor = :id_professor
  ORDER BY al.nome_aluno";

$stmt1 = $conn->prepare($sql_aula_part);
$stmt1->execute([':id_professor' => $id_professor]);
$aulas_particulares = $stmt1->fetchAll(PDO::FETCH_ASSOC);

// Consulta inscrições de campeonatos do professor
$sql_consulta_insc = "SELECT i.id_inscricao, c.nome_camp, c.data_camp, ar.nome_arena
  FROM info_geral.inscricao_campeonato i
  JOIN info_geral.campeonatos c ON c.id_camp = i.id_camp
  JOIN info_geral.professores p ON p.id_professor = i.id_professor
  JOIN info_geral.arena ar ON ar.id_arena = c.id_arena
  WHERE i.id_professor = :id_professor
  ORDER BY c.data_camp DESC";

$stmt2 = $conn->prepare($sql_consulta_insc);
$stmt2->execute([':id_professor' => $id_professor]);
$inscricoes = $stmt2->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel do Professor</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/custom.css">
</head>

<body>

  <!-- Navbar -->
  <nav class="navbar fixed-top shadow-sm bg-white">
    <div class="container d-flex justify-content-between align-items-center">
      <a class="navbar-brand fw-bold" href="#">Painel do Professor</a>
      <div>
        <span>Olá, <?= htmlspecialchars($nome) ?> | <a href="php/logout.php?acao=sair" class="btn btn-danger">Sair</a></span>
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

      <!-- Informações do Professor -->
      <div class="card mt-5">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
          <span>Informações pessoais</span>
          <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#editarProfessorModal">Editar Informações</button>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped">
              <thead class="table-light">
                <tr>
                  <th>Nome</th>
                  <th>Email</th>
                  <th>Arena</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><?= htmlspecialchars($info_professor['nome_professor']) ?></td>
                  <td><?= htmlspecialchars($info_professor['email']) ?></td>
                  <td><?= htmlspecialchars($info_professor['nome_arena']) ?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Tabela de Aulas Particulares -->
      <div class="card mt-5">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center"><span>Aulas Particulares</span></div>
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
                    <th>Aluno</th>
                    <th>Arena</th>
                    <th>Dia(s) de aula</th>
                    <th>Horário</th>
                  </tr>
                </thead>

                <tbody>
                  <?php foreach ($aulas_particulares as $aula): ?>
                    <tr>
                      <td><?= htmlspecialchars($aula['nome_aluno']) ?></td>
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

      <!-- tabela de Campeonatos -->
      <div class="card mt-5">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center"><span>Campeonatos</span></div>
        <div class="card-body">
          <div class="table-responsive">
            <?php if (empty($campeonatos)): ?>
              <div class="alert alert-info text-center m-0">
                Nenhum campeonato cadastrado.
              </div>
            <?php else: ?>
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
            <?php endif; ?>
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

  <?php include 'modal/modais_painel_professor.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>