<?php
include 'php/conexao.php';

// Consultar arenas
$arenas = $conn->query("SELECT * FROM info_geral.arena")->fetchAll(PDO::FETCH_ASSOC);

// Consulta campeonatos 
$sql_consultar_campeonatos = "SELECT c.*, ar.nome_arena
  FROM info_geral.campeonatos c
  JOIN info_geral.arena ar ON c.id_arena = ar.id_arena
  ORDER BY data_camp DESC";
$campeonatos = $conn->query($sql_consultar_campeonatos)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>FutevôleiHub</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/custom.css">
</head>

<body>

  <!-- Navbar fixa -->
  <nav class="navbar fixed-top shadow-sm bg-white">
    <div class="container d-flex justify-content-between align-items-center">
      <a class="navbar-brand fw-bold" href="#"> FutevôleiHub </a>
      <div class="d-none d-lg-flex gap-3">
        <a class="nav-link" href="#contato"> Contatos</a>
        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#loginModal"> Entrar <i class="bi bi-person-circle"></i></a>
      </div>
      <div class="d-lg-none">
        <div class="dropdown">
          <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"></button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="#contato"> Contatos</a></li>
            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#loginModal"> Entrar <i class="bi bi-person-circle"></i></a></li>
          </ul>
        </div>
      </div>
    </div>
  </nav>

  <div class="content-wrapper">
    <main>
      <!-- Seção da Foto de apresentação -->
      <section class="hero-section position-relative" style="height: 60vh; background: url('img/foto_turma.jpg') center/cover no-repeat;">
        <div class="hero-text">
          <h1 class="fw-bold">Futevôlei é para todos!</h1>
          <p>Treinamento, diversão e comunidade nas nossas arenas</p>
        </div>
      </section>

      <!-- Seção de Horários e Arenas -->
      <section id="arenas_horarios" class="py-5 bg-white bg-opacity-50">
        <div class="container">
          <h2 class="mb-4 text-start">Arenas e Horários</h2>

          <div class="accordion" id="accordionArenas">
            <?php if (!empty($arenas)): ?>
              <?php foreach ($arenas as $arena): ?>
                <?php
                $headingId = 'heading' . $arena['id_arena'];
                $collapseId = 'collapse' . $arena['id_arena'];
                ?>
                <div class="accordion-item mb-3">
                  <h2 class="accordion-header" id="<?= $headingId ?>">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                      data-bs-target="#<?= $collapseId ?>" aria-expanded="false" aria-controls="<?= $collapseId ?>">
                      <?= htmlspecialchars($arena['nome_arena']) ?>
                    </button>
                  </h2>
                  <div id="<?= $collapseId ?>" class="accordion-collapse collapse" aria-labelledby="<?= $headingId ?>" data-bs-parent="#accordionArenas">
                    <div class="accordion-body">
                      <p><strong>Horários:</strong><br><?= nl2br(htmlspecialchars($arena['descricao'])) ?></p>

                      <?php if (!empty($arena['url_maps_iframe'])): ?>
                        <strong>Mapa da arena:</strong>
                        <div class="mapa-wrapper mt-3">
                          <iframe
                            src="<?= htmlspecialchars($arena['url_maps_iframe']) ?>"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                          </iframe>
                        </div>
                      <?php endif; ?>
                      
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <p class="text-muted">Nenhuma arena cadastrada no momento.</p>
            <?php endif; ?>
          </div>
        </div>
      </section>


      <!-- Seção de Campeonatos -->
      <section id="campeonatos" class="py-5 bg-white bg-opacity-50">
        <div class="container">
          <h2 class="mb-4 text-start">Campeonatos</h2>

          <div class="accordion" id="accordionCampeonatos">
            <?php if (count($campeonatos) > 0): ?>
              <?php foreach ($campeonatos as $index => $camp): ?>
                <?php
                // IDs únicos para evitar conflito com a accordion de Arenas
                $campHeadingId = 'campHeading' . $camp['id_camp'];
                $campCollapseId = 'campCollapse' . $camp['id_camp'];
                ?>
                <div class="accordion-item mb-3">
                  <h2 class="accordion-header" id="<?= $campHeadingId ?>">
                    <button class="accordion-button collapsed d-flex justify-content-between align-items-center" type="button"
                      data-bs-toggle="collapse" data-bs-target="#<?= $campCollapseId ?>" aria-expanded="false"
                      aria-controls="<?= $campCollapseId ?>">
                      <div class="me-auto fw-bold"><?= htmlspecialchars($camp['nome_camp']) ?></div>
                      <div class="mx-4 small text-muted">Inscrições até: <?= date('d/m/Y', strtotime($camp['data_fim_inscricao'])) ?></div>
                    </button>
                  </h2>
                  <div id="<?= $campCollapseId ?>" class="accordion-collapse collapse"
                    aria-labelledby="<?= $campHeadingId ?>" data-bs-parent="#accordionCampeonatos">
                    <div class="accordion-body">
                      <p><strong>Categoria:</strong> <?= htmlspecialchars($camp['categoria']) ?></p>
                      <p><strong>Data do Campeonato:</strong> <?= date('d/m/Y', strtotime($camp['data_camp'])) ?></p>
                      <p><strong>Arena:</strong> <?= htmlspecialchars($camp['nome_arena']) ?></p>
                      <p><strong>Descrição:</strong><br><?= nl2br(htmlspecialchars($camp['descricao'])) ?></p>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <p class="text-muted">Nenhum campeonato cadastrado no momento.</p>
            <?php endif; ?>
          </div>
        </div>
      </section>

    </main>

    <!-- Footer -->
    <footer class="bg-light">
      <div class="container d-flex flex-column flex-md-row justify-content-between align-items-start">

        <div class="text-md mt-4 mt-md-0">
          <h5 id="contato">Contatos</h5><br>
          <p><i class="bi bi-instagram"></i> <a href="https://www.instagram.com/futevoleieduba/">@futevoleieduba</a></p>
          <p><i class="bi bi-whatsapp"></i> <a href="https://wa.link/tl4wjx">WhatsApp</a></p>
        </div>

        <div class="text-md-end mt-4 mt-md-0">
          <h5 id="info-geral">Informações gerais</h5><br>
          <p>© 2025 Futevôlei Eduba. Todos os direitos reservados.<br>
            Feito por Sabrina Gaspar — TCC Ciência da Computação</p>
        </div>

      </div>
    </footer>

  </div>

  <?php include 'modal/modais_index.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="js/login.js"></script>
</body>
</html>