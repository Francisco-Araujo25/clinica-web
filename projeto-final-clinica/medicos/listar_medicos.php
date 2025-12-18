<?php
// Conexão com o banco
include '../clinica/config/conexao.php';

$titulo = 'Médicos';

$sql = "
  SELECT
    id_medico,
    crm,
    nome_medico,
    especialidade,
    telefone
  FROM medicos
  ORDER BY id_medico ASC
";

$resultado = $conexao->query($sql);
if ($resultado === false) {
  $erro = 'Erro ao consultar médicos: ' . $conexao->error;
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
      <a href="../medicos/cadastrar_medico.php">Cadastrar Médicos</a>
    </nav>
  </div>
</header>

<main>
  <div class="container">

    <div class="flex justify-between items-center mb-4">
      <div class="stack-sm">
        <h2 class="card-title" style="margin:0;">Listagem</h2>
        <p class="form-help" style="margin:0;">
          Veja todos os médicos cadastrados e gerencie suas informações.
        </p>
      </div>
      <div class="flex">
        <a href="cadastrar_medico.php" class="btn">Novo Médico</a>
      </div>
    </div>

    <?php if (!empty($erro)): ?>
      <div class="alert alert-danger mb-4">
        <h3 class="alert-title">Erro na consulta</h3>
        <p><?= htmlspecialchars($erro) ?></p>
      </div>
    <?php endif; ?>

    <?php if (empty($erro) && $resultado && $resultado->num_rows > 0): ?>
      <div class="card">
        <div class="card-body">
          <table class="table">
            <thead>
              <tr>
                <th>ID</th>
                <th>CRM</th>
                <th>Nome</th>
                <th>Especialidade</th>
                <th>Telefone</th>
                <th style="width:110px;">Editar</th>
                <th style="width:110px;">Excluir</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($linha = $resultado->fetch_assoc()): ?>
                <?php
                  $id           = (int) $linha['id_medico'];
                  $crm          = htmlspecialchars($linha['crm'] ?? '');
                  $nome         = htmlspecialchars($linha['nome_medico'] ?? '');
                  $especialidade= htmlspecialchars($linha['especialidade'] ?? '');
                  $telefone     = htmlspecialchars($linha['telefone'] ?? '');
                ?>
                <tr>
                  <td><?= $id ?></td>
                  <td><?= $crm ?></td>
                  <td><?= $nome ?></td>
                  <td><?= $especialidade ?></td>
                  <td><?= $telefone ?></td>
                  <td>
                    <a href="editar_medico.php?id_medico=<?= $id ?>" class="btn btn-secondary">
                      Editar
                    </a>
                  </td>
                  <td>
                    <a
                      href="excluir_medico.php?id_medico=<?= $id ?>"
                      class="btn btn-danger"
                      onclick="return confirm('Deseja excluir o médico <?= addslashes($nome) ?>?');"
                    >
                      Excluir
                    </a>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php else: ?>
      <div class="alert alert-info">
        <h3 class="alert-title">Nenhum médico encontrado</h3>
        <p>Não há registros no momento.</p>
        <div class="mt-4">
          <a href="cadastrar_medico.php" class="btn">Cadastrar Médico</a>
        </div>
      </div>
    <?php endif; ?>

    <footer class="site-footer">
      <small>&copy; <?= date('Y') ?> Clínica — Sistema de gerenciamento</small>
    </footer>

  </div>
</main>

</body>
<script src="../assets/js/scripts.js" defer></script>
</html>

<?php
if ($resultado instanceof mysqli_result) {
  $resultado->free();
}
$conexao->close();
?>