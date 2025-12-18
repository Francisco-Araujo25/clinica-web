<?php
// =====================
// CONEXÃO
// =====================
include '../clinica/config/conexao.php';

$titulo = 'Cadastrar Médicos';
$sucesso = false;
$erros = [];

// Valores antigos (repopular formulário)
$old = [
    'cpf' => '',
    'nome_medico' => '',
    'especialidade' => '',
    'crm' => '',
    'telefone' => '',
];

// =====================
// PROCESSAMENTO
// =====================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Captura dos dados
    $old['cpf']           = trim($_POST['cpf'] ?? '');
    $old['nome_medico']   = trim($_POST['nome_medico'] ?? '');
    $old['especialidade'] = trim($_POST['especialidade'] ?? '');
    $old['crm']           = trim($_POST['crm'] ?? '');
    $old['telefone']      = trim($_POST['telefone'] ?? '');

    // =====================
    // VALIDAÇÕES
    // =====================

    // Nome
    if ($old['nome_medico'] === '') {
        $erros[] = 'Nome do médico é obrigatório.';
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

    // CRM
    if ($old['crm'] === '') {
        $erros[] = 'CRM é obrigatório.';
    }

    // Telefone
    if ($old['telefone'] === '') {
        $erros[] = 'Telefone é obrigatório.';
    } else {
        $tel_num = preg_replace('/\D/', '', $old['telefone']);
        if (strlen($tel_num) < 10) {
            $erros[] = 'Telefone deve conter ao menos 10 dígitos.';
        } else {
            $old['telefone'] = $tel_num;
        }
    }

    // =====================
    // INSERT
    // =====================
    if (empty($erros)) {

        $sql = "INSERT INTO medicos
                (cpf, nome_medico, especialidade, crm, telefone)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $conexao->prepare($sql);

        if (!$stmt) {
            $erros[] = 'Erro no prepare: ' . $conexao->error;
        } else {

            // Variáveis auxiliares
            $cpf           = $old['cpf'];
            $nome          = $old['nome_medico'];
            $especialidade = $old['especialidade'] !== '' ? $old['especialidade'] : null;
            $crm           = $old['crm'];
            $telefone      = $old['telefone'];

            $stmt->bind_param(
                "sssss",
                $cpf,
                $nome,
                $especialidade,
                $crm,
                $telefone
            );

            if ($stmt->execute()) {
                $sucesso = true;
            } else {
                $erros[] = 'Erro ao cadastrar médico: ' . $stmt->error;
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
    <div class="conainer">
        
        <header class="page-header">
        <div class="container">
            <h1 class="page-title"><?= htmlspecialchars($titulo) ?></h1>
            <nav class="breadcrumb">
            <a href="../index.php">Início</a>
            <a href="../medicos/listar_medicos.php">Médicos</a>
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
            <p style="color:#3ddc97;font-weight:bold;">Médico cadastrado com sucesso!</p>
            <script>
                setTimeout(() => {
                    window.location.href = "listar_medicos.php";
                }, 2000);
            </script>
        <?php endif; ?>

        <form class="form" method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">

        
            <label class="label">CPF:</label>
            <input class="input" type="text" name="cpf" required value="<?= htmlspecialchars($old['cpf']) ?>">

            <label class="label">Nome do Médico:</label>
            <input type="text" name="nome_medico" required value="<?= htmlspecialchars($old['nome_medico']) ?>">

            <label class="label">Especialidade:</label>
            <input  class="input" type="text" name="especialidade" value="<?= htmlspecialchars($old['especialidade']) ?>">

            <label class="label">CRM:</label>
            <input type="text" name="crm" required value="<?= htmlspecialchars($old['crm']) ?>">

            <label class="label">Telefone:</label>
            <input type="text" name="telefone" required value="<?= htmlspecialchars($old['telefone']) ?>">

            <br><br>
            <button type="submit" class="btn">Cadastrar</button>
            <button type="reset" class="btn">Limpar</button>

            </form>

        <footer class="footer site-footer">
            <small>&copy; <?= date('Y') ?> Clínica — Sistema de gerenciamento</small>
        </footer>
    </div>
</body>
</html>
