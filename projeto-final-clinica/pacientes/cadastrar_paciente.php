<?php
// =====================
// CONEXÃO
// =====================
include '../clinica/config/conexao.php';

$titulo = 'Cadastrar Pacientes';
$sucesso = false;
$erros = [];

// Valores antigos (para repopular o formulário)
$old = [
    'cpf' => '',
    'nome_paciente' => '',
    'data_nascimento' => '',
    'endereco' => '',
    'telefone' => '',
    'genero' => '',
];

// =====================
// PROCESSAMENTO DO FORM
// =====================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Captura dos dados
    $old['cpf']             = trim($_POST['cpf'] ?? '');
    $old['nome_paciente']   = trim($_POST['nome_paciente'] ?? '');
    $old['data_nascimento'] = trim($_POST['data_nascimento'] ?? '');
    $old['endereco']        = trim($_POST['endereco'] ?? '');
    $old['telefone']        = trim($_POST['telefone'] ?? '');
    $old['genero']          = $_POST['genero'] ?? '';

    // =====================
    // VALIDAÇÕES
    // =====================
    if ($old['nome_paciente'] === '') {
        $erros[] = 'Nome do paciente é obrigatório.';
    }

    // CPF
    if ($old['cpf'] === '') {
        $erros[] = 'CPF é obrigatório.';
    } else {
        $cpf_num = preg_replace('/\D/', '', $old['cpf']);
        if (strlen($cpf_num) !== 11) {
            $erros[] = 'CPF deve conter 11 dígitos.';
        } else {
            $old['cpf'] = $cpf_num;
        }
    }

    // Telefone
    if ($old['telefone'] !== '') {
        $tel_num = preg_replace('/\D/', '', $old['telefone']);
        if (strlen($tel_num) < 10) {
            $erros[] = 'Telefone deve conter ao menos 10 dígitos.';
        } else {
            $old['telefone'] = $tel_num;
        }
    }

    // Gênero
    if ($old['genero'] !== '' && !in_array($old['genero'], ['M', 'F'], true)) {
        $erros[] = 'Gênero inválido.';
    }

    // =====================
    // INSERT
    // =====================
    if (empty($erros)) {

        $sql = "INSERT INTO pacientes 
                (cpf, nome_paciente, data_nascimento, endereco, telefone, genero)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conexao->prepare($sql);

        if (!$stmt) {
            $erros[] = 'Erro no prepare: ' . $conexao->error;
        } else {

            // Variáveis auxiliares (OBRIGATÓRIO para bind_param)
            $cpf       = $old['cpf'];
            $nome      = $old['nome_paciente'];
            $dataNasc  = $old['data_nascimento'] !== '' ? $old['data_nascimento'] : null;
            $endereco  = $old['endereco'] !== '' ? $old['endereco'] : null;
            $telefone  = $old['telefone'] !== '' ? $old['telefone'] : null;
            $genero    = $old['genero'] !== '' ? $old['genero'] : null;

            $stmt->bind_param(
                "ssssss",
                $cpf,
                $nome,
                $dataNasc,
                $endereco,
                $telefone,
                $genero
            );

            if ($stmt->execute()) {
                $sucesso = true;
            } else {
                $erros[] = 'Erro ao cadastrar: ' . $stmt->error;
            }

            $stmt->close();
        }
    }
}
?>

    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title><?= htmlspecialchars($titulo) ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="../assets/css/styles.css">
    </head>
    <body>

      <header class="page-header">
        <div class="container">
            <h1 class="page-title"><?= htmlspecialchars($titulo) ?></h1>
            <nav class="breadcrumb">
            <a href="../index.php">Início</a>
            <a href="../pacientes/listar_pacientes.php">Pacientes</a>
            </nav>
        </div>
        </header>

    <?php if (!empty($erros)): ?>
        <div style="background:#2b1a1a;color:#ffb3b3;padding:12px;border-radius:8px;">
            <ul>
                <?php foreach ($erros as $erro): ?>
                    <li><?= htmlspecialchars($erro) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

<?php if ($sucesso): ?>
    <p style="color:#3ddc97;font-weight:bold;">Paciente cadastrado com sucesso!</p>
    <script>
        setTimeout(() => {
            window.location.href = "listar_pacientes.php";
        }, 2000);
    </script>
<?php endif; ?>

    <form class="form" method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">

    <label class="label">CPF:</label>
    <input class="input" type="text" name="cpf" required value="<?= htmlspecialchars($old['cpf']) ?>">

    <label class="label">Nome:</label>
    <input class="input" type="text" name="nome_paciente" required value="<?= htmlspecialchars($old['nome_paciente']) ?>">

    <label class="label">Data de Nascimento:</label>
    <input class="input" type="date" name="data_nascimento" value="<?= htmlspecialchars($old['data_nascimento']) ?>">

    <label class="label">Endereço:</label>
    <input class="input" type="text" name="endereco" value="<?= htmlspecialchars($old['endereco']) ?>">

    <label class="label">Telefone:</label>
    <input class="input" type="text" name="telefone" value="<?= htmlspecialchars($old['telefone']) ?>">

    <p>Gênero</p>
    <label class="label">
        <input class="input" type="radio" name="genero" value="M" <?= $old['genero'] === 'M' ? 'checked' : '' ?>>
        Masculino
    </label>
    <label class="label">
        <input class="input" type="radio" name="genero" value="F" <?= $old['genero'] === 'F' ? 'checked' : '' ?>>
        Feminino
    </label>

    <br><br>
    <button type="submit" class="btn">Cadastrar</button>
    <button type="reset" class="btn">Limpar</button>

</form>

<footer class="footer site-footer">
    <small>&copy; <?= date('Y') ?> Clínica — Sistema de gerenciamento</small>
</footer>

</body>
</html>
