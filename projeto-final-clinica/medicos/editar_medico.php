<?php


// a partir de /projeto-final-clinica/medicos
require_once __DIR__ . '/../clinica/config/conexao.php';


$sucesso = false;
$erros = [];

$old = [
  'cpf' => '',
  'nome_medico' => '',
  'crm' => '',
  'especialidade' => '',
  'telefone' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $old['cpf']           = trim($_POST['cpf'] ?? '');
    $old['nome_medico']   = trim($_POST['nome_medico'] ?? '');
    $old['crm']           = trim($_POST['crm'] ?? '');
    $old['especialidade'] = trim($_POST['especialidade'] ?? '');
    $old['telefone']      = trim($_POST['telefone'] ?? '');

    /* ===== Validações ===== */

    if ($old['nome_medico'] === '') {
        $erros[] = 'Nome do médico é obrigatório.';
    }

    if ($old['cpf'] !== '') {
        $cpf = preg_replace('/\D/', '', $old['cpf']);
        if (strlen($cpf) !== 11) {
            $erros[] = 'CPF deve conter 11 dígitos.';
        } else {
            $old['cpf'] = $cpf;
        }
    } else {
        $erros[] = 'CPF é obrigatório.';
    }

    if ($old['crm'] === '') {
        $erros[] = 'CRM é obrigatório.';
    }

    if ($old['especialidade'] === '') {
        $erros[] = 'Especialidade é obrigatória.';
    }

    if ($old['telefone'] !== '') {
        $tel = preg_replace('/\D/', '', $old['telefone']);
        if (strlen($tel) < 10) {
            $erros[] = 'Telefone inválido.';
        } else {
            $old['telefone'] = $tel;
        }
    }

    /* ===== Inserção ===== */

    if (empty($erros)) {
        $sql = "
          INSERT INTO medicos
            (cpf, nome_medico, crm, especialidade, telefone, ativo)
          VALUES (?, ?, ?, ?, ?, 1)
        ";

        $stmt = $conexao->prepare($sql);

        if ($stmt) {
            $stmt->bind_param(
                "sssss",
                $old['cpf'],
                $old['nome_medico'],
                $old['crm'],
                $old['especialidade'],
                $old['telefone']
            );

            if ($stmt->execute()) {
                $sucesso = true;
            } else {
                $erros[] = 'Erro ao cadastrar médico.';
            }

            $stmt->close();
        } else {
            $erros[] = 'Erro ao preparar consulta.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Cadastrar Médico</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>

<header class="page-header">
  <div class="container">
    <h1>Cadastrar Médico</h1>
  </div>
</header>

<main class="container">

<?php if ($erros): ?>
  <div class="alert alert-danger">
    <ul>
      <?php foreach ($erros as $e): ?>
        <li><?= htmlspecialchars($e) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<?php if ($sucesso): ?>
  <div class="alert alert-success">
    Médico cadastrado com sucesso.
  </div>
  <script>
    setTimeout(() => location.href = 'listar_medicos.php', 1500);
  </script>
<?php endif; ?>

<form method="post" data-validate class="form">

  <label>CPF</label>
  <input type="text" name="cpf" value="<?= htmlspecialchars($old['cpf']) ?>" required>

  <label>Nome do Médico</label>
  <input type="text" name="nome_medico" value="<?= htmlspecialchars($old['nome_medico']) ?>" required>

  <label>CRM</label>
  <input type="text" name="crm" value="<?= htmlspecialchars($old['crm']) ?>" required>

  <label>Especialidade</label>
  <input type="text" name="especialidade" value="<?= htmlspecialchars($old['especialidade']) ?>" required>

  <label>Telefone</label>
  <input type="text" name="telefone" value="<?= htmlspecialchars($old['telefone']) ?>">

  <div class="flex justify-end mt-4">
    <a href="listar_medicos.php" class="btn btn-secondary">Cancelar</a>
    <button type="submit" class="btn btn-primary">Cadastrar</button>
  </div>

</form>

</main>

<footer class="site-footer">
  <small>&copy; <?= date('Y') ?> Clínica</small>
</footer>

</body>
<script src="../assets/js/scripts.js" defer></script>
</html>