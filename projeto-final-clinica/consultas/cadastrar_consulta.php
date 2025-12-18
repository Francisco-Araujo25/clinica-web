<?php
include '../clinica/config/conexao.php';

$titulo = 'Cadastrar Consulta';
$mensagem = '';
$classe_alerta = '';
$sucesso = false;

$pacientes = [];
$medicos   = [];

/* ===== Carregar pacientes ===== */
$sqlPac = "SELECT id_paciente, nome_paciente FROM pacientes ORDER BY nome_paciente ASC";
$resPac = $conexao->query($sqlPac);
if ($resPac) {
    while ($row = $resPac->fetch_assoc()) {
        $pacientes[] = $row;
    }
    $resPac->free();
} else {
    $mensagem = 'Erro ao carregar pacientes.';
    $classe_alerta = 'danger';
}

/* ===== Carregar médicos ===== */
$sqlMed = "SELECT id_medico, nome_medico FROM medicos ORDER BY nome_medico ASC";
$resMed = $conexao->query($sqlMed);
if ($resMed) {
    while ($row = $resMed->fetch_assoc()) {
        $medicos[] = $row;
    }
    $resMed->free();
} else {
    $mensagem = 'Erro ao carregar médicos.';
    $classe_alerta = 'danger';
}

/* ===== POST ===== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $paciente_id = (int) ($_POST['paciente_id'] ?? 0);
    $medico_id   = (int) ($_POST['medico_id'] ?? 0);
    $data_input  = trim($_POST['data_consulta'] ?? '');
    $diagnostico = trim($_POST['diagnostico'] ?? '');
    $tratamento  = trim($_POST['tratamento'] ?? '');
    $prescricao  = trim($_POST['prescricao_medica'] ?? '');

    $erros = [];

    if ($paciente_id <= 0) $erros[] = 'Selecione um paciente.';
    if ($medico_id <= 0)   $erros[] = 'Selecione um médico.';
    if ($data_input === '') $erros[] = 'Informe data e hora.';

    $data_mysql = null;
    if ($data_input) {
        $data_mysql = str_replace('T', ' ', $data_input) . ':00';
    }

    if (empty($erros)) {
        $sql = "
            INSERT INTO consultasmedicas
            (data_consulta, diagnostico, tratamento, prescricao_medica, paciente_id, medico_id)
            VALUES (?, ?, ?, ?, ?, ?)
        ";
        $diagnostico_param = !empty($diagnostico) ? $diagnostico : null;
        $tratamento_param  = !empty($tratamento)  ? $tratamento  : null;
        $prescricao_param  = !empty($prescricao)  ? $prescricao  : null;

        $stmt = $conexao->prepare($sql);
        if ($stmt) {
            $stmt->bind_param(
                "ssssii",
                $data_mysql,
                $diagnostico_param,
                $tratamento_param,
                $prescricao_param,
                $paciente_id,
                $medico_id
    );

            if ($stmt->execute()) {
                $sucesso = true;
                $mensagem = 'Consulta cadastrada com sucesso.';
                $classe_alerta = 'success';

                echo "<script>
                        setTimeout(() => {
                          window.location.href = 'listar_consulta.php';
                        }, 2000);
                      </script>";
            } else {
                $mensagem = 'Erro ao salvar consulta.';
                $classe_alerta = 'danger';
            }
            $stmt->close();
        }
    } else {
        $mensagem = implode(' ', $erros);
        $classe_alerta = 'danger';
    }
}
?>
  <!DOCTYPE html>
  <html lang="pt-br">
  <head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($titulo) ?></title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/styles.css">
  </head>
  <body>

        <header class="page-header">
        <div class="container">
            <h1 class="page-title"><?= htmlspecialchars($titulo) ?></h1>
            <nav class="breadcrumb">
            <a href="../index.php">Início</a>
            <a href="./listar_consulta.php">Consultas</a>
            </nav>
        </div>
        </header>

  <main class="container">

  <?php if ($mensagem): ?>
    <div class="alert <?= $classe_alerta === 'success' ? 'alert-success' : 'alert-danger' ?>">
      <?= htmlspecialchars($mensagem) ?>
    </div>
  <?php endif; ?>

<div class="card">
  <div class="card-body">

    <form action="./cadastrar_consulta.php" method="post" data-validate>

      <div class="form-group">
        <label>Paciente</label>
        <select name="paciente_id" class="select" required>
          <option value="">Selecione...</option>
          <?php foreach ($pacientes as $p): ?>
            <option value="<?= (int)$p['id_paciente'] ?>">
              <?= htmlspecialchars($p['nome_paciente']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label>Médico</label>
        <select name="medico_id" class="select" required>
          <option value="">Selecione...</option>
          <?php foreach ($medicos as $m): ?>
            <option value="<?= (int)$m['id_medico'] ?>">
              <?= htmlspecialchars($m['nome_medico']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label>Data e Hora</label>
        <input type="datetime-local" name="data_consulta" class="input" required>
      </div>

      <div class="form-group">
        <label>Diagnóstico</label>
        <textarea name="diagnostico" class="textarea"></textarea>
      </div>

      <div class="form-group">
        <label>Tratamento</label>
        <textarea name="tratamento" class="textarea"></textarea>
      </div>

      <div class="form-group">
        <label>Prescrição Médica</label>
        <textarea name="prescricao_medica" class="textarea"></textarea>
      </div>

      <div class="flex justify-end gap-sm mt-4">
        <a href="listar_consulta.php" class="btn btn-secondary">Cancelar</a>
        <button class="btn btn-primary">Registrar</button>
      </div>

    </form>

  </div>
</div>

</main>

<footer class="site-footer">
  <small>&copy; <?= date('Y') ?> Clínica</small>
</footer>

</body>
<script src="../assets/js/scripts.js" defer></script>
</html>