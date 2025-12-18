<?php
include '../clinica/config/conexao.php';

$mensagem = '';
$classe_alerta = '';
$paciente = null;

/* ===== GET: carregar paciente ===== */
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id_paciente = isset($_GET['id']) ? (int) $_GET['id'] : 0;

    if ($id_paciente > 0) {
        $sql = "SELECT id_paciente, nome_paciente FROM pacientes WHERE id_paciente = ?";
        $stmt = $conexao->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $id_paciente);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($resultado && $resultado->num_rows === 1) {
                $paciente = $resultado->fetch_assoc();
            } else {
                $mensagem = 'Paciente não encontrado.';
                $classe_alerta = 'error';
            }
            $stmt->close();
        } else {
            $mensagem = 'Erro ao preparar consulta.';
            $classe_alerta = 'error';
        }
    } else {
        $mensagem = 'ID inválido.';
        $classe_alerta = 'error';
    }
}

/* ===== POST: atualizar paciente ===== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_paciente = (int) ($_POST['id_paciente'] ?? 0);
    $nome = trim($_POST['nome_paciente'] ?? '');

    if ($id_paciente <= 0 || $nome === '') {
        $mensagem = 'Dados inválidos.';
        $classe_alerta = 'error';
    } else {
        $sql = "UPDATE pacientes SET nome_paciente = ? WHERE id_paciente = ?";
        $stmt = $conexao->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("si", $nome, $id_paciente);
            if ($stmt->execute()) {
                $mensagem = 'Paciente atualizado com sucesso.';
                $classe_alerta = 'success';
                $paciente = ['id_paciente' => $id_paciente, 'nome_paciente' => $nome];
            } else {
                $mensagem = 'Erro ao atualizar.';
                $classe_alerta = 'error';
            }
            $stmt->close();
        }
    }
}

$conexao->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Editar Paciente</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>

<header class="page-header">
  <div class="container">
    <h1>Editar Paciente</h1>
    <nav class="breadcrumb">
      <a href="/index.php">Início</a> / Pacientes / Editar
    </nav>
  </div>
</header>

<main class="container">

<?php if ($mensagem): ?>
  <div class="alert <?= $classe_alerta === 'success' ? 'alert-success' : 'alert-danger' ?>">
    <?= htmlspecialchars($mensagem) ?>
  </div>
<?php endif; ?>

<?php if ($paciente): ?>
  <div class="card">
    <div class="card-body">

      <form method="post">
        <input type="hidden" name="id_paciente" value="<?= $paciente['id_paciente'] ?>">

        <div class="form-group">
          <label>ID</label>
          <input class="input" type="text" value="<?= $paciente['id_paciente'] ?>" disabled>
        </div>

        <div class="form-group">
          <label>Nome</label>
          <input class="input" type="text" name="nome_paciente"
                 value="<?= htmlspecialchars($paciente['nome_paciente']) ?>" required>
        </div>

        <div class="flex justify-end gap-sm mt-4">
          <a href="listar_pacientes.php" class="btn btn-secondary">Cancelar</a>
          <button class="btn btn-primary" type="submit">Salvar</button>
          <a href="listar_pacientes.php" class="btn">Voltar</a>
        </div>
      </form>

    </div>
  </div>
<?php else: ?>
  <div class="alert alert-info">
    Nenhum paciente carregado.
    <br><br>
    <a href="listar_pacientes.php" class="btn">Voltar</a>
  </div>
<?php endif; ?>

</main>

<footer class="site-footer">
  <small>&copy; <?= date('Y') ?> Clínica</small>
</footer>

</body>
<script src="../assets/js/scripts.js" defer></script>
</html>