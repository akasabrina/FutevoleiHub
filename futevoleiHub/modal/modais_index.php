<!-- Modal de Login Estilizado -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="loginModalLabel">Entrar na Conta</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <form id="formLogin">
          <div class="mb-3">
            <label for="emailInput" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" id="emailInput" placeholder="nome@exemplo.com" required>
          </div>
          <div class="mb-3">
            <label for="senhaInput" class="form-label">Senha</label>
            <input type="password" name="senha" class="form-control" id="senhaInput" placeholder="Digite sua senha" required>
          </div>
          <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">Entrar</button>
            <button type="button" class="btn btn-outline-secondary w-100" data-bs-target="#cadastroModal" data-bs-toggle="modal" data-bs-dismiss="modal">Cadastrar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal de Cadastro de Aluno -->
<div class="modal fade" id="cadastroModal" tabindex="-1" aria-labelledby="cadastroModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="cadastroModalLabel">Cadastrar Novo Aluno</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <form action="php/cadastrar_aluno.php" method="POST">
          <div class="mb-3">
            <label for="nome" class="form-label">Nome completo</label>
            <input type="text" class="form-control" name="nome" required>
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

          <!-- email -->
          <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" class="form-control" name="email" required>
          </div>

          <!-- senha e confirmação -->
          <div class="mb-3">
            <label for="senha" class="form-label">Senha</label>
            <input type="password" class="form-control" name="senha" required>
          </div>
          <div class="mb-3">
            <label for="confirma_senha" class="form-label">Confirmar Senha</label>
            <input type="password" class="form-control" name="confirma_senha" required>
          </div>

          <button type="submit" class="btn btn-success w-100">Cadastrar</button>
        </form>
      </div>
    </div>
  </div>
</div>