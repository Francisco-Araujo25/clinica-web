
<?php
// Conexão com o banco
include '../clinica/config/conexao.php';

$titulo = 'Cadastrar Médicos';

// Consulta completa — ajuste os nomes conforme seu schema real
$sql = "
  SELECT
    id_paciente,
    cpf,
    nome_paciente,
    data_nascimento,
    genero,
    endereco,
    telefone
  FROM pacientes
  ORDER BY id_paciente ASC
";

$resultado = $conexao->query($sql);
if ($resultado === false) {
  $erro = 'Erro ao consultar pacientes: ' . $conexao->error;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $titulo ?? 'Admin' ?></title>

  <!-- Fonte Poppins e CSS do design system -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/styles.css">

</head>
<body>

  <!-- Cabeçalho da página -->
  <header class="page-header">
    <div class="container">
      <h1 class="page-title"><?= $titulo ?? 'Admin' ?></h1>
      <nav class="breadcrumb">
        <a href="../index.php">Início</a>
        <a href="./cadastrar_paciente.php">Cadastrar novo Paciente</a>
      </nav>
    </div>
  </header>

  <main>
    <div class="container">

      <!-- Barra de ações -->
      <div class="flex justify-between items-center mb-4">
        <div class="stack-sm">
          <h2 class="card-title" style="margin:0;">Listagem</h2>
          <p class="form-help" style="margin:0;">Veja todos os pacientes cadastrados e gerencie suas informações.</p>
        </div>
        <div class="flex">
          <a href="cadastrar_paciente.php" class="btn"> + Novo Paciente</a>
        </div>
      </div>

      <!-- Feedback de erro, se houver -->
      <?php if (!empty($erro)): ?>
        <div class="alert alert-danger mb-4">
          <h3 class="alert-title">Erro na consulta</h3>
          <p><?php echo htmlspecialchars($erro); ?></p>
        </div>
      <?php endif; ?>

      <!-- Tabela -->
      <?php if (empty($erro) && $resultado && $resultado->num_rows > 0): ?>
        <div class="card">
          <div class="card-body">
            <table class="table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>CPF</th>
                  <th>Nome</th>
                  <th>Data de Nascimento</th>
                  <th>Gênero</th>
                  <th>Endereço</th>
                  <th>Telefone</th>
                  <th style="width: 110px;">Editar</th>
                  <th style="width: 110px;">Excluir</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($linha = $resultado->fetch_assoc()): ?>
                  <?php
                    // Sanitização
                    $id         = (int) $linha['id_paciente'];
                    $cpf        = htmlspecialchars($linha['cpf'] ?? '');
                    $nome       = htmlspecialchars($linha['nome_paciente'] ?? '');
                    $nascRaw    = $linha['data_nascimento'] ?? null;
                    $genero     = htmlspecialchars($linha['genero'] ?? '');
                    $endereco   = htmlspecialchars($linha['endereco'] ?? '');
                    $telefone   = htmlspecialchars($linha['telefone'] ?? '');

                    // Formata a data (YYYY-MM-DD -> DD/MM/YYYY)
                    $nasc = '';
                    if (!empty($nascRaw) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $nascRaw)) {
                      $partes = explode('-', $nascRaw);
                      $nasc = $partes[2] . '/' . $partes[1] . '/' . $partes[0];
                    }
                  ?>
                  <tr>
                    <td><?php echo $id; ?></td>
                    <td><?php echo $cpf; ?></td>
                    <td><?php echo $nome; ?></td>
                    <td><?php echo htmlspecialchars($nasc); ?></td>
                    <td><?php echo $genero; ?></td>
                    <td><?php echo $endereco; ?></td>
                    <td><?php echo $telefone; ?></td>
                    <td>
                     <a href="editar_paciente.php?id=<?= $id ?>" class="btn btn-success">
                        Editar
                    </a>

                    </td>
                    <td>
                      <a href="excluir_paciente.php?id=<?= $linha['id_paciente'] ?>"
                        class="btn btn-danger"
                        onclick="return confirm('Deseja excluir este paciente?')">
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
          <h3 class="alert-title">Nenhum paciente encontrado</h3>
          <p>Não há registros no momento. Você pode cadastrar um novo paciente.</p>
          <div class="mt-4">
            <a href="/cadastrar_paciente.php" class="btn">Cadastrar Paciente</a>
          </div>
        </div>
      <?php endif; ?>

      <footer class="site-footer">
        <small>&copy; <?php echo date('Y'); ?> Clínica — Sistema de gerenciamento</small>
      </footer>
    </div>
  </main>
</body>
<script src="../assets/js/scripts.js" defer></script>
</html>
<?php
// Encerra conexão
if ($resultado instanceof mysqli_result) {
  $resultado->free();
}
$conexao->close();
?>