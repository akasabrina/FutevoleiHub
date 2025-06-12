<!-- Seção aluno / 100% pronto -->
<!-- Modal: Editar informações do aluno -->
<div class="modal fade" id="editarAlunoModal" tabindex="-1" aria-labelledby="editarAlunoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content border-0 shadow">
      <form action="php/atualizar_aluno.php" method="POST">
        <div class="modal-header bg-light text-dark">
          <h5 class="modal-title" id="editarAlunoModalLabel">Editar Informações</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>

        <input type="hidden" name="id_aluno" value="<?= $info_aluno['id_aluno'] ?>">

        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" class="form-control" name="nome_aluno" value="<?= htmlspecialchars($info_aluno['nome_aluno']) ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($info_aluno['email']) ?>" required>
          </div>

          <!-- Arena -->
          <div class="mb-3">
            <label class="form-label">Arena</label>
            <select class="form-select" name="id_arena" required>
              <option value="">Selecione uma arena</option>
              <?php foreach ($arenas as $arena): ?>
                <option value="<?= $arena['id_arena'] ?>" <?= $arena['id_arena'] == $info_aluno['id_arena'] ? 'selected' : '' ?>>
                  <?= $arena['nome_arena'] ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <hr>
          <p class="text-muted">Se desejar alterar sua senha, preencha os campos abaixo:</p>

          <div class="mb-3">
            <label class="form-label">Senha Atual</label>
            <input type="password" class="form-control" name="senha_atual">
          </div>

          <div class="mb-3">
            <label class="form-label">Nova Senha</label>
            <input type="password" class="form-control" name="nova_senha">
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

<!-- Seção inscrição campeonato / 100% pronto -->
<!-- Modal: Cadastrar inscrição em campeonato -->
<div class="modal fade" id="modalNovaInscricao" tabindex="-1" aria-labelledby="modalNovaInscricaoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="php/cadastrar_inscricao.php" method="POST" class="modal-content border-0 shadow">
      <div class="modal-header bg-light text-dark">
        <h5 class="modal-title" id="modalNovaInscricaoLabel">Inscrever-se em um Campeonato</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">

        <!-- Seleção de campeonato -->
        <div class="mb-3">
          <label for="id_campeonato" class="form-label">Campeonato</label>
          <select class="form-select" name="id_campeonato" required>
            <option value="" disabled selected>Selecione um campeonato</option>
            <?php foreach ($campeonatos as $camp): ?>
              <option value="<?= $camp['id_camp'] ?>"><?= htmlspecialchars($camp['nome_camp']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Confirmar Inscrição</button>
      </div>
    </form>
  </div>
</div>