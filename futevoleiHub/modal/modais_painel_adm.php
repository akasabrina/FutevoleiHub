<!-- seção Arena / 100% pronto -->
<!-- Modal: Adicionar Arena -->
<div class="modal fade" id="modalNovaArena" tabindex="-1" aria-labelledby="modalNovaArenaLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="php/cadastrar_arena.php" method="POST" class="modal-content border-0 shadow">
      <div class="modal-header bg-light text-dark">
        <h5 class="modal-title" id="modalNovaArenaLabel">Adicionar Nova Arena</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="nome_arena" class="form-label">Nome da Arena</label>
          <input type="text" class="form-control" name="nome_arena" required>
        </div>
        <div class="mb-3">
          <label for="descricao" class="form-label">Descrição / Horários</label>
          <textarea class="form-control" name="descricao" rows="3" required></textarea>
        </div>
        <div class="mb-3">
          <label for="url_maps" class="form-label">URL do Google Maps - iframe</label>
          <input type="url" class="form-control" name="url_maps_iframe" required>
        </div>
        <div class="mb-3">
          <label for="url_maps" class="form-label">URL do Google Maps - direto</label>
          <input type="url" class="form-control" name="url_maps_direto" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Salvar</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal: Editar Arena -->
<?php foreach ($arenas as $arena): ?>
  <div class="modal fade" id="modalEditarArena<?= $arena['id_arena'] ?>" tabindex="-1" aria-labelledby="modalEditarArenaLabel<?= $arena['id_arena'] ?>" aria-hidden="true">
    <div class="modal-dialog">
      <form action="php/atualizar_arena.php" method="POST" class="modal-content border-0 shadow">
        <div class="modal-header bg-light text-dark">
          <h5 class="modal-title" id="modalEditarArenaLabel<?= $arena['id_arena'] ?>">Editar Arena</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_arena" value="<?= $arena['id_arena'] ?>">

          <div class="mb-3">
            <label for="nome_arena" class="form-label">Nome da Arena</label>
            <input type="text" class="form-control" name="nome_arena" value="<?= htmlspecialchars($arena['nome_arena']) ?>" required>
          </div>

          <div class="mb-3">
            <label for="descricao" class="form-label">Descrição / Horários</label>
            <textarea class="form-control" name="descricao" rows="3" required><?= htmlspecialchars($arena['descricao']) ?></textarea>
          </div>

          <div class="mb-3">
            <label for="url_maps" class="form-label">URL do Google Maps - iframe</label>
            <input type="url" class="form-control" name="url_maps_iframe" value="<?= htmlspecialchars($arena['url_maps_iframe']) ?>" required>
          </div>

          <div class="mb-3">
            <label for="url_maps" class="form-label">URL do Google Maps - direto</label>
            <input type="url" class="form-control" name="url_maps_direto" value="<?= htmlspecialchars($arena['url_maps_direto']) ?>" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        </div>
      </form>
    </div>
  </div>
<?php endforeach; ?>

<!-- seção Professor / 100% pronto -->
<!-- Modal: Adicionar Professor -->
<div class="modal fade" id="modalNovoProfessor" tabindex="-1" aria-labelledby="modalNovoProfessorLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="php/cadastrar_professor.php" method="POST" class="modal-content border-0 shadow">
      <div class="modal-header bg-light text-dark">
        <h5 class="modal-title" id="modalNovoProfessorLabel">Cadastrar Novo Professor</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">

        <div class="mb-3">
          <label for="nome_professor" class="form-label">Nome do Professor</label>
          <input type="text" class="form-control" name="nome_professor" required>
        </div>

        <div class="mb-3">
          <label for="id_arena" class="form-label">Arena Locada</label>
          <select name="id_arena" class="form-select" required>
            <option value="">Selecione uma arena</option>
            <?php foreach ($arenas as $arena): ?>
              <option value="<?= $arena['id_arena'] ?>"><?= htmlspecialchars($arena['nome_arena']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">E-mail</label>
          <input type="email" class="form-control" name="email" required>
        </div>

        <div class="mb-3">
          <label for="senha" class="form-label">Senha</label>
          <input type="password" class="form-control" name="senha" required>
        </div>

        <div class="mb-3">
          <label for="confirma_senha" class="form-label">Confirmar Senha</label>
          <input type="password" class="form-control" name="confirma_senha" required>
        </div>

      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Salvar</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal: Editar professor -->
<?php foreach ($professores as $prof): ?>
  <div class="modal fade" id="editarProfessorModal<?= $prof['id_professor'] ?>" tabindex="-1" aria-labelledby="editarProfessorModalLabel<?= $prof['id_professor'] ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content border-0 shadow">
        <form action="php/atualizar_professor.php" method="POST">
          <div class="modal-header bg-light text-dark">
            <h5 class="modal-title" id="editarProfessorModalLabel<?= $prof['id_professor'] ?>">Editar Professor</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
          </div>

          <input type="hidden" name="id_professor" value="<?= $prof['id_professor'] ?>">

          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Nome do Professor</label>
              <input type="text" class="form-control" name="nome_professor" value="<?= htmlspecialchars($prof['nome_professor']) ?>" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Arena Locada</label>
              <select class="form-select" name="id_arena" required>
                <option value="">Selecione uma arena</option>
                <?php foreach ($arenas as $arena): ?>
                  <option value="<?= $arena['id_arena'] ?>" <?= $arena['id_arena'] == $prof['id_arena'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($arena['nome_arena']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <hr>
            <p class="text-muted">Se desejar mudar a senha, preencha os campos abaixo:</p>

            <div class="mb-3">
              <label class="form-label">Senha Atual</label>
              <input type="password" name="senha_atual" class="form-control">
            </div>

            <div class="mb-3">
              <label class="form-label">Nova Senha</label>
              <input type="password" name="nova_senha" class="form-control">
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-success">Salvar Alterações</button>
          </div>
        </form>
      </div>
    </div>
  </div>
<?php endforeach; ?>

<!-- seção Campeonato / 100% pronto -->
<!-- Modal: Adicionar Campeonato -->
<div class="modal fade" id="novoCampeonatoModal" tabindex="-1" aria-labelledby="novoCampeonatoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-light text-dark">
        <h5 class="modal-title fw-bold" id="novoCampeonatoModalLabel">Novo Campeonato</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <form action="php/cadastrar_campeonato.php" method="POST">
        <div class="modal-body">
          <div class="mb-3">
            <label for="nome_camp" class="form-label">Nome do Campeonato</label>
            <input type="text" class="form-control" name="nome_camp" required>
          </div>
          <div class="mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <textarea class="form-control" name="descricao" rows="3"></textarea>
          </div>
          <div class="mb-3">
            <label for="categoria" class="form-label">Categoria</label>
            <select class="form-select" name="categoria" required>
              <option value="">Selecione</option>
              <option value="Iniciante">Iniciante</option>
              <option value="Intermediário">Intermediário</option>
              <option value="Avançado">Avançado</option>
            </select>
          </div>
          <div class="row g-3">
            <div class="col-md-6">
              <label for="data_camp" class="form-label">Data do Campeonato</label>
              <input type="date" class="form-control" name="data_camp" required>
            </div>
            <div class="col-md-6">
              <label for="data_fim_inscricao" class="form-label">Encerramento das Inscrições</label>
              <input type="date" class="form-control" name="data_fim_inscricao" required>
            </div>
          </div>
          <div class="mb-3">
            <label for="id_arena" class="form-label">Arena</label>
            <select name="id_arena" class="form-select" required>
              <option value="">Selecione uma arena</option>
              <?php foreach ($arenas as $arena): ?>
                <option value="<?= $arena['id_arena'] ?>"><?= htmlspecialchars($arena['nome_arena']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Criar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal: Editar Campeonato -->
<?php foreach ($campeonatos as $camp): ?>
  <div class="modal fade" id="editarCampeonatoModal<?= $camp['id_camp'] ?>" tabindex="-1" aria-labelledby="editarCampeonatoModalLabel<?= $camp['id_camp'] ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content border-0 shadow">
        <div class="modal-header bg-light text-dark">
          <h5 class="modal-title" id="editarCampeonatoModalLabel<?= $camp['id_camp'] ?>">Editar Campeonato</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <form action="php/atualizar_campeonato.php" method="POST">
          <input type="hidden" name="id_camp" value="<?= $camp['id_camp'] ?>">
          <div class="modal-body">

            <!-- nome campeonato -->
            <div class="mb-3">
              <label class="form-label">Nome do Campeonato</label>
              <input type="text" class="form-control" name="nome_camp" value="<?= htmlspecialchars($camp['nome_camp']) ?>" required>
            </div>

            <!-- descrição -->
            <div class="mb-3">
              <label class="form-label">Descrição</label>
              <textarea class="form-control" name="descricao" rows="3"><?= htmlspecialchars($camp['descricao']) ?></textarea>
            </div>

            <!-- categoria -->
            <div class="mb-3">
              <label class="form-label">Categoria</label>
              <select class="form-select" name="categoria" required>
                <option value="Iniciante" <?= $camp['categoria'] === 'Iniciante' ? 'selected' : '' ?>>Iniciante</option>
                <option value="Intermediário" <?= $camp['categoria'] === 'Intermediário' ? 'selected' : '' ?>>Intermediário</option>
                <option value="Avançado" <?= $camp['categoria'] === 'Avançado' ? 'selected' : '' ?>>Avançado</option>
              </select>
            </div>

            <!-- data campeonato e encerramento inscrições -->
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Data do Campeonato</label>
                <input type="date" class="form-control" name="data_camp" value="<?= date('Y-m-d', strtotime($camp['data_camp'])) ?>" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Encerramento das Inscrições</label>
                <input type="date" class="form-control" name="data_fim_inscricao" value="<?= date('Y-m-d', strtotime($camp['data_fim_inscricao'])) ?>" required>
              </div>
            </div>

            <!-- arena -->
            <div class="mb-3">
              <label class="form-label">Arena</label>
              <select class="form-select" name="id_arena" required>
                <option value="">Selecione uma arena</option>
                <?php foreach ($arenas as $arena): ?>
                  <option value="<?= $arena['id_arena'] ?>" <?= $arena['id_arena'] == $camp['id_arena'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($arena['nome_arena']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Salvar Alterações</button>
          </div>
        </form>
      </div>
    </div>
  </div>
<?php endforeach; ?>

<!-- seção Aula particular / 100% pronto -->
<!-- Modal: Adicionar Aula Particular -->
<div class="modal fade" id="modalNovaAula" tabindex="-1" aria-labelledby="modalNovaAulaLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form action="php/cadastrar_aula.php" method="POST" class="modal-content border-0 shadow">

      <div class="modal-header bg-light text-dark">
        <h5 class="modal-title" id="modalNovaAulaLabel">Cadastrar Nova Aula Particular</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>

      <div class="modal-body">

        <!-- nome aluno -->
        <div class="mb-3">
          <label for="id_aluno" class="form-label">Aluno</label>
          <select name="id_aluno" class="form-select" required>
            <option value="">Selecione um aluno</option>
            <?php foreach ($alunos as $aluno): ?>
              <option value="<?= $aluno['id_aluno'] ?>"><?= htmlspecialchars($aluno['nome_aluno']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- nome professor -->
        <div class="mb-3">
          <label for="id_professor" class="form-label">Professor</label>
          <select name="id_professor" class="form-select" required>
            <option value="">Selecione um professor</option>
            <?php foreach ($professores as $prof): ?>
              <option value="<?= $prof['id_professor'] ?>"><?= htmlspecialchars($prof['nome_professor']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- nome arena -->
        <div class="mb-3">
          <label for="id_arena" class="form-label">Arena</label>
          <select name="id_arena" class="form-select" required>
            <option value="">Selecione uma arena</option>
            <?php foreach ($arenas as $arena): ?>
              <option value="<?= $arena['id_arena'] ?>"><?= htmlspecialchars($arena['nome_arena']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- dias da aula -->
        <div class="mb-3">
          <label class="form-label d-block">Dia(s) da Aula</label>
          <div class="row">
            <?php
            $dias = ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'];
            foreach ($dias as $dia):
              $id_dia = strtolower($dia);
            ?>
              <div class="col-md-4">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="dias_aula[]" value="<?= $dia ?>" id="dia_<?= $id_dia ?>">
                  <label class="form-check-label" for="dia_<?= $id_dia ?>"><?= $dia ?></label>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- horários -->
        <div class="row g-3">
          <div class="col-md-6">
            <label for="horario_inicio" class="form-label">Horário Início</label>
            <input type="time" class="form-control" name="horario_inicio" id="horario_inicio" required>
          </div>
          <div class="col-md-6">
            <label for="horario_fim" class="form-label">Horário Fim</label>
            <input type="time" class="form-control" name="horario_fim" id="horario_fim" required>
          </div>
        </div>

      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Cadastrar Aula</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal: Editar Aula Particular -->
<?php foreach ($aulas_particulares as $aula): ?>
  <div class="modal fade" id="modalEditarAula<?= $aula['id_aula'] ?>" tabindex="-1" aria-labelledby="modalEditarAulaLabel<?= $aula['id_aula'] ?>" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content border-0 shadow">
        <form action="php/atualizar_aula.php" method="POST">
          <div class="modal-header">
            <h5 class="modal-title" id="modalEditarAulaLabel<?= $aula['id_aula'] ?>">Editar Aula Particular</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
          </div>

          <div class="modal-body">
            <input type="hidden" name="id_aula" value="<?= $aula['id_aula'] ?>">

            <!-- nome professor -->
            <div class="mb-3">
              <label class="form-label">Professor</label>
              <select name="id_professor" class="form-select" required>
                <option value="">Selecione um professor</option>
                <?php foreach ($professores as $prof): ?>
                  <option value="<?= $prof['id_professor'] ?>" <?= $prof['id_professor'] == $aula['id_professor'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($prof['nome_professor']) ?>
                  <?php endforeach; ?>
              </select>
            </div>

            <!-- arena -->
            <div class="mb-3">
              <label class="form-label">Arena</label>
              <select class="form-select" name="id_arena" required>
                <option value="">Selecione uma arena</option>
                <?php foreach ($arenas as $arena): ?>
                  <option value="<?= $arena['id_arena'] ?>" <?= $arena['id_arena'] == $aula['id_arena'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($arena['nome_arena']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- Dias da semana -->
            <?php
            $dias_da_semana = ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'];
            $dias_selecionados = array_map('trim', explode(',', $aula['dias_aula']));
            ?>
            <div class="mb-3">
              <label class="form-label d-block">Dia(s) da Aula</label>
              <?php foreach ($dias_da_semana as $dia): ?>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" name="dias_aula[]" value="<?= $dia ?>" id="dia_<?= $dia . '_' . $aula['id_aula'] ?>" <?= in_array($dia, $dias_selecionados) ? 'checked' : '' ?>>
                  <label class="form-check-label" for="dia_<?= $dia . '_' . $aula['id_aula'] ?>"><?= $dia ?></label>
                </div>
              <?php endforeach; ?>
            </div>

            <!-- Horário de início -->
            <div class="mb-3">
              <label for="inicio_<?= $aula['id_aula'] ?>" class="form-label">Horário Início</label>
              <input type="time" class="form-control" name="horario_inicio" id="inicio_<?= $aula['id_aula'] ?>" value="<?= date('H:i', strtotime($aula['horario_inicio'])) ?>" required>
            </div>

            <!-- Horário de fim -->
            <div class="mb-3">
              <label for="fim_<?= $aula['id_aula'] ?>" class="form-label">Horário Fim</label>
              <input type="time" class="form-control" name="horario_fim" id="fim_<?= $aula['id_aula'] ?>" value="<?= date('H:i', strtotime($aula['horario_fim'])) ?>" required>
            </div>

          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
          </div>
        </form>
      </div>
    </div>
  </div>
<?php endforeach; ?>

<!-- Seção Administrador / 100% pronto -->
<!-- Modal: Adicionar Administrador -->
<div class="modal fade" id="modalNovoAdministrador" tabindex="-1" aria-labelledby="modalNovoAdministradorLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="php/cadastrar_adm.php" method="POST" class="modal-content border-0 shadow">
      <div class="modal-header bg-light text-dark">
        <h5 class="modal-title" id="modalNovoAdministradorLabel">Adicionar Novo Administrador</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">

        <!-- nome adm -->
        <div class="mb-3">
          <label for="nome_adm" class="form-label">Nome do Administrador</label>
          <input type="text" class="form-control" name="nome_adm" required>
        </div>

        <!-- email adm -->
        <div class="mb-3">
          <label for="email" class="form-label">E-mail</label>
          <input type="email" class="form-control" name="email" required>
        </div>

        <!-- senha -->
        <div class="mb-3">
          <label for="senha" class="form-label">Senha</label>
          <input type="password" class="form-control" name="senha" required>
        </div>

        <!-- confirmação senha -->
        <div class="mb-3">
          <label for="senha" class="form-label">Confirmar Senha</label>
          <input type="password" class="form-control" name="confirma_senha" required>
        </div>

      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Salvar</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal: Editar Administrador -->
<?php foreach ($adms as $adm): ?>
  <div class="modal fade" id="modalEditarAdministrador<?= $adm['id_adm'] ?>" tabindex="-1" aria-labelledby="modalEditarAdministradorLabel<?= $adm['id_adm'] ?>" aria-hidden="true">
    <div class="modal-dialog">
      <form action="php/atualizar_adm.php" method="POST" class="modal-content border-0 shadow">
        <div class="modal-header bg-light text-dark">
          <h5 class="modal-title" id="modalEditarAdministradorLabel<?= $adm['id_adm'] ?>">Editar Administrador</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          <!-- ID oculto -->
          <input type="hidden" name="id_adm" value="<?= $adm['id_adm'] ?>">

          <!-- Nome -->
          <div class="mb-3">
            <label for="nome_adm" class="form-label">Nome</label>
            <input type="text" class="form-control" name="nome_adm" value="<?= htmlspecialchars($adm['nome_adm']) ?>" required>
          </div>

          <!-- Email -->
          <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($adm['email']) ?>" required>
          </div>

          <hr>
          <p class="text-muted">Se desejar mudar a senha, preencha os campos abaixo:</p>

          <!-- Senha atual -->
          <div class="mb-3">
            <label for="senha_atual" class="form-label">Senha Atual</label>
            <input type="password" class="form-control" name="senha_atual">
          </div>

          <!-- Nova senha -->
          <div class="mb-3">
            <label for="nova_senha" class="form-label">Nova Senha</label>
            <input type="password" class="form-control" name="nova_senha">
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        </div>
      </form>
    </div>
  </div>
<?php endforeach; ?>

