<?php
include '../clinica/config/conexao.php';

$titulo = 'Cadastrar Consulta';
$erro = '';

$sql = "
  SELECT
    c.id_consulta,
    c.data_consulta,
    c.diagnostico,
    c.tratamento,
    c.prescricao_medica,
    p.nome_paciente,
    m.nome_medico
  FROM consultasmedicas c
  INNER JOIN pacientes p ON p.id_paciente = c.paciente_id
  INNER JOIN medicos   m ON m.id_medico   = c.medico_id
  ORDER BY c.data_consulta DESC
";

$resultado = $conexao->query($sql);
if ($resultado === false) {
    $erro = 'Erro ao consultar consultas: ' . $conexao->error;
}
?>
  <!DOCTYPE html>
  <html lang="pt-br">
  <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

  <main>
    <div class="container">

      <div class="flex justify-between items-center mb-4">
        <div>
          <h2 class="card-title">Consultas Cadastradas</h2>
          <p class="form-help">Histórico completo de atendimentos.</p>
        </div>
        <a href="cadastrar_consulta.php" class="btn">Nova Consulta</a>
      </div>

    <?php if ($erro): ?>
      <div class="alert alert-danger">
        <?= htmlspecialchars($erro) ?>
      </div>
    <?php endif; ?>

    <?php if (!$erro && $resultado && $resultado->num_rows > 0): ?>
      <div class="card">
        <div class="card-body">
          <table class="table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Data</th>
                <th>Médico</th>
                <th>Paciente</th>
                <th>Diagnóstico</th>
                <th>Tratamento</th>
                <th>Prescrição</th>

              </tr>
            </thead>
            <tbody>
              <?php while ($row = $resultado->fetch_assoc()): ?>
                <?php
                  $data = date('d/m/Y H:i', strtotime($row['data_consulta']));
                ?>
                <tr>
                  <td><?= (int)$row['id_consulta'] ?></td>
                  <td><?= $data ?></td>
                  <td><?= htmlspecialchars($row['nome_medico']) ?></td>
                  <td><?= htmlspecialchars($row['nome_paciente']) ?></td>
                  <td><?= htmlspecialchars($row['diagnostico'] ?: '-') ?></td>
                  <td><?= htmlspecialchars($row['tratamento'] ?: '-') ?></td>
                  <td><?= htmlspecialchars($row['prescricao_medica'] ?: '-') ?></td>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php else: ?>
      <div class="alert alert-info">
        Nenhuma consulta cadastrada.
        <br><br>
        <a href="cadastrar_consulta.php" class="btn">Cadastrar Consulta</a>
      </div>
    <?php endif; ?>

  </div>
</main>

<footer class="site-footer">
  <small>&copy; <?= date('Y') ?> Clínica — Sistema de gerenciamento</small>
</footer>

</body>
<script src="../assets/js/scripts.js" defer></script>
</html>
<?php
if ($resultado instanceof mysqli_result) {
    $resultado->free();
}
$conexao->close();
?>