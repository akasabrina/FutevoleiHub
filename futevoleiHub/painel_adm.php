<?php
session_start();
include 'php/conexao.php';

// Verifica se o login foi feito e se é um professor
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'administrador') {
  header("Location: index.php");
  exit;
}

// Armazena os dados da pessoa logada
$id = $_SESSION['id'];
$nome = $_SESSION['nome'];
//$tipo = $_SESSION['tipo'];

// Quantidade alunos
$qtd = $conn->query("SELECT COUNT(*) FROM info_geral.alunos")->fetchColumn();
// Quantidade professores
$qtd1 = $conn->query("SELECT COUNT(*) FROM info_geral.professores")->fetchColumn();

// Consulta Alunos
$sql_consulta_alunos = "SELECT a.id_aluno, a.id_arena, a.nome_aluno, ar.nome_arena 
  FROM info_geral.alunos a 
  JOIN info_geral.arena ar ON a.id_arena = ar.id_arena ORDER BY a.nome_aluno";
$alunos = $conn->query($sql_consulta_alunos)->fetchAll(PDO::FETCH_ASSOC);

// Consulta professores
$sql_consulta_prof = "SELECT p.id_professor, p.nome_professor, ar.nome_arena, p.id_arena
  FROM info_geral.professores p 
  JOIN info_geral.arena ar ON p.id_arena = ar.id_arena ORDER BY p.nome_professor";
$professores = $conn->query($sql_consulta_prof)->fetchAll(PDO::FETCH_ASSOC);

// Consulta campeonatos 
$sql_consultar_campeonatos = "SELECT c.*, ar.nome_arena
  FROM info_geral.campeonatos c
  JOIN info_geral.arena ar ON c.id_arena = ar.id_arena
  ORDER BY data_camp DESC";
$campeonatos = $conn->query($sql_consultar_campeonatos)->fetchAll(PDO::FETCH_ASSOC);

// Consulta aulas particulares 
$sql_aula_part = "SELECT a.id_aula, a.id_professor, al.nome_aluno, p.nome_professor, ar.id_arena, ar.nome_arena, a.dias_aula, a.horario_inicio, a.horario_fim
  FROM info_geral.aulas_particulares a
  JOIN info_geral.arena ar ON a.id_arena = ar.id_arena
  JOIN info_geral.alunos al ON a.id_aluno = al.id_aluno
  JOIN info_geral.professores p ON a.id_professor = p.id_professor
  ORDER BY al.nome_aluno";
$aulas_particulares = $conn->query($sql_aula_part)->fetchAll(PDO::FETCH_ASSOC);

// Consultar arenas
$arenas = $conn->query("SELECT * FROM info_geral.arena")->fetchAll(PDO::FETCH_ASSOC);

// Consulta administradores
$adms = $conn->query("SELECT id_adm, nome_adm, email FROM info_geral.adm ORDER BY id_adm")->fetchAll(PDO::FETCH_ASSOC);

// Consulta inscrições de campeonatos
$sql_consulta_insc = "SELECT i.id_inscricao, c.nome_camp, a.nome_aluno, p.nome_professor
  FROM info_geral.inscricao_campeonato i
  JOIN info_geral.campeonatos c ON i.id_camp = c.id_camp
  LEFT JOIN info_geral.alunos a ON i.id_aluno = a.id_aluno
  LEFT JOIN info_geral.professores p ON i.id_professor = p.id_professor
  ORDER BY c.nome_camp";
$inscricoes = $conn->query($sql_consulta_insc)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel do Administrador</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/custom.css">
</head>

<body>

  <!-- Navbar -->
  <nav class="navbar fixed-top shadow-sm bg-white">
    <div class="container d-flex justify-content-between align-items-center">
      <a class="navbar-brand fw-bold" href="#">Painel do Administrador</a>
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
            <h5>Total de Alunos</h5>
            <h2 class="fw-bold"><?= $qtd ?></h2>
          </div>
        </div>

        <div class="col-6">
          <div class="bg-info text-white rounded p-4 shadow-sm text-center">
            <h5>Total de Professores</h5>
            <h2 class="fw-bold"><?= $qtd1 ?></h2>
          </div>
        </div>
      </div>

      <!-- Tabela de Alunos -->
      <div class="card">
        <div class="card-header bg-secondary text-white"> Alunos </div>
        <div class="card-body">
          <div class="table-responsive">
            <?php if (empty($alunos)): ?>
              <div class="alert alert-info text-center m-0">
                Nenhum aluno cadastrado.
              </div>
            <?php else: ?>
              <table class="table table-bordered table-striped">

                <thead class="table-light">
                  <tr>
                    <th>Nome</th>
                    <th>Arena</th>
                    <th>Ação</th>
                  </tr>
                </thead>

                <tbody>
                  <?php foreach ($alunos as $aluno): ?>
                    <tr>
                      <td><?= htmlspecialchars($aluno['nome_aluno']) ?></td>
                      <td><?= htmlspecialchars($aluno['nome_arena']) ?></td>
                      <td>
                        <form action="php/excluir_aluno.php" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este aluno? Esta ação não pode ser desfeita.');">
                          <input type="hidden" name="id_aluno" value="<?= $aluno['id_aluno'] ?>">
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

      <!-- Tabela de Professores -->
      <div class="card mt-5">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
          <span>Professores</span>
          <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalNovoProfessor"> + Cadastrar Professor </button>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <?php if (empty($professores)): ?>
              <div class="alert alert-info text-center m-0">
                Nenhum professor cadastrado.
              </div>
            <?php else: ?>
              <table class="table table-bordered table-striped">

                <thead class="table-light">
                  <tr>
                    <th>Nome</th>
                    <th>Arena</th>
                    <th>Ações</th>
                    <th></th>
                  </tr>
                </thead>

                <tbody>
                  <?php foreach ($professores as $prof): ?>
                    <tr>
                      <td><?= htmlspecialchars($prof['nome_professor']) ?></td>
                      <td><?= htmlspecialchars($prof['nome_arena']) ?></td>
                      <td>
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editarProfessorModal<?= $prof['id_professor'] ?>">Editar</button>
                      </td>
                      <td>
                        <form action="php/excluir_professor.php" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este professor? Esta ação não pode ser desfeita.');">
                          <input type="hidden" name="id_professor" value="<?= $prof['id_professor'] ?>">
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

      <!-- Tabela de Campeonatos -->
      <div class="card mt-5">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
          <span>Campeonatos</span>
          <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#novoCampeonatoModal"> + Cadastrar Campeonato </button>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <?php if (empty($campeonatos)): ?>
              <div class="alert alert-info text-center m-0">
                Nenhum campeonato cadastrada.
              </div>
            <?php else: ?>
              <table class="table table-bordered table-striped">

                <thead class="table-light">
                  <tr>
                    <th>Nome</th>
                    <th>Categoria</th>
                    <th>Data do evento</th>
                    <th>Arena</th>
                    <th>Inscrições até</th>
                    <th>Ações</th>
                    <th></th>
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
                      <td>
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editarCampeonatoModal<?= $camp['id_camp'] ?>"> Editar </button>
                      </td>
                      <td>
                        <form action="php/excluir_campeonato.php" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este campeonato? Esta ação não pode ser desfeita.');">
                          <input type="hidden" name="id_camp" value="<?= $camp['id_camp'] ?>">
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

      <!-- Tabela de Aulas Particulares -->
      <div class="card mt-5">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
          <span>Aulas Particulares</span>
          <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalNovaAula"> + Nova Aula Particular </button>
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
                    <th>Aluno</th>
                    <th>Professor</th>
                    <th>Arena</th>
                    <th>Dia(s) de aula</th>
                    <th>Horário</th>
                    <th>Ações</th>
                    <th></th>
                  </tr>
                </thead>

                <tbody>
                  <?php foreach ($aulas_particulares as $aula): ?>
                    <tr>
                      <td><?= htmlspecialchars($aula['nome_aluno']) ?></td>
                      <td><?= htmlspecialchars($aula['nome_professor']) ?></td>
                      <td><?= htmlspecialchars($aula['nome_arena']) ?></td>
                      <td><?= htmlspecialchars($aula['dias_aula']) ?></td>
                      <td><?= date('H:i', strtotime($aula['horario_inicio'])) ?> - <?= date('H:i', strtotime($aula['horario_fim'])) ?></td>
                      <td>
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditarAula<?= $aula['id_aula'] ?>"> Editar </button>
                      </td>
                      <td>
                        <form action="php/excluir_aula.php" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta aula particular? Esta ação não pode ser desfeita.');">
                          <input type="hidden" name="id_aula" value="<?= $aula['id_aula'] ?>">
                          <button type="submit" class="btn btn-sm btn-danger"> Excluir </button>
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

      <!-- Tabela de Arenas -->
      <div class="card mt-5">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
          <span>Arenas</span>
          <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalNovaArena"> + Cadastrar Arena </button>
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
                    <th>Arena</th>
                    <th>Descrição</th>
                    <th>Localização (Maps)</th>
                    <th>Ações</th>
                    <th></th>
                  </tr>
                </thead>

                <tbody>
                  <?php foreach ($arenas as $arena): ?>
                    <tr>
                      <td><?= htmlspecialchars($arena['nome_arena']) ?></td>
                      <td><?= htmlspecialchars($arena['descricao']) ?></td>
                      <td><a href="<?= htmlspecialchars($arena['url_maps_direto']) ?>" target="_blank" class="btn btn-sm btn-info"> Ver no Maps </a></td>
                      <td><button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditarArena<?= $arena['id_arena'] ?>"> Editar </button></td>
                      <td>
                        <form action="php/excluir_arena.php" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta arena? Esta ação não pode ser desfeita.');">
                          <input type="hidden" name="id_arena" value="<?= $arena['id_arena'] ?>">
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

      <!-- Tabela de Administradores -->
      <div class="card mt-5">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
          <span>Administradores</span>
          <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalNovoAdministrador"> + Cadastrar Administrador </button>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <?php if (empty($adms)): ?>
              <div class="alert alert-info text-center m-0">
                Nenhum administrador cadastrado.
              </div>
            <?php else: ?>
              <table class="table table-bordered table-striped">

                <thead class="table-light">
                  <tr>
                    <th>Nome</th>
                    <th>email</th>
                    <th>Ações</th>
                    <th></th>
                  </tr>
                </thead>

                <tbody>
                  <?php foreach ($adms as $adm): ?>
                    <tr>
                      <td><?= htmlspecialchars($adm['nome_adm']) ?></td>
                      <td><?= htmlspecialchars($adm['email']) ?></td>
                      <td><button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditarAdministrador<?= $adm['id_adm'] ?>"> Editar </button></td>
                      <td>
                        <form action="php/excluir_adm.php" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este administrador? Esta ação não pode ser desfeita.');">
                          <input type="hidden" name="id_adm" value="<?= $adm['id_adm'] ?>">
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

      <!-- Tabela de Inscrições de campeonatos -->
      <div class="card mt-5">
        <div class="card-header bg-secondary text-white"> Inscrições em campeonatos </div>
        <div class="card-body">
          <div class="table-responsive">
            <?php if (empty($inscricoes)): ?>
              <div class="alert alert-info text-center m-0">
                Nenhuma inscrição em campeonatos cadastrada.
              </div>
            <?php else: ?>
              <table class="table table-bordered table-striped">
                <thead class="table-light">
                  <tr>
                    <th>Campeonato</th>
                    <th>Nome atleta</th>
                    <th>Ação</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($inscricoes as $insc): ?>
                    <tr>
                      <td><?= htmlspecialchars($insc['nome_camp']) ?></td>
                      <td><?= !empty($insc['nome_aluno']) ? htmlspecialchars($insc['nome_aluno']) : htmlspecialchars($insc['nome_professor']) ?></td>
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
  <?php include 'modal/modais_painel_adm.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>