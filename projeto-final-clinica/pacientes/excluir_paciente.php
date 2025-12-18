<?php
include '../clinica/config/conexao.php';

/**
 * Exclusão de paciente
 */
session_start();

$id_paciente = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id_paciente || $id_paciente <= 0) {
    $_SESSION['status'] = 'erro_id';
    $_SESSION['message'] = 'ID inválido.';
    header('Location: listar_pacientes.php');
    exit;
}

/* Verifica se o paciente existe */
$sql_busca = "SELECT id_paciente, nome_paciente FROM pacientes WHERE id_paciente = ?";
$stmt_busca = $conexao->prepare($sql_busca);

if (!$stmt_busca) {
    $_SESSION['status'] = 'erro_prepare';
    $_SESSION['message'] = 'Erro no sistema.';
    header('Location: listar_pacientes.php');
    exit;
}

$stmt_busca->bind_param("i", $id_paciente);
$stmt_busca->execute();
$resultado = $stmt_busca->get_result();

if ($resultado->num_rows !== 1) {
    $stmt_busca->close();
    $_SESSION['status'] = 'nao_encontrado';
    $_SESSION['message'] = 'Paciente não encontrado.';
    header('Location: listar_pacientes.php');
    exit;
}

$paciente = $resultado->fetch_assoc();
$stmt_busca->close();

/* Exclui o paciente */
$sql_delete = "DELETE FROM pacientes WHERE id_paciente = ?";
$stmt_delete = $conexao->prepare($sql_delete);

if (!$stmt_delete) {
    $_SESSION['status'] = 'erro_prepare';
    $_SESSION['message'] = 'Erro no sistema.';
    header('Location: listar_pacientes.php');
    exit;
}

$stmt_delete->bind_param("i", $id_paciente);

if ($stmt_delete->execute()) {
    $_SESSION['status'] = 'excluido';
    $_SESSION['message'] = 'Paciente "' . htmlspecialchars($paciente['nome_paciente']) . '" excluído com sucesso!';
    $stmt_delete->close();
    $conexao->close();
    header('Location: listar_pacientes.php');
    exit;
} else {
    // Verifica se é erro de FK
    if ($conexao->errno == 1451) { // Código de erro para violação de chave estrangeira
        $_SESSION['status'] = 'erro_fk';
        $_SESSION['message'] = 'Não é possível excluir o paciente. Existem consultas relacionadas.';
    } else {
        $_SESSION['status'] = 'erro';
        $_SESSION['message'] = 'Erro ao excluir: ' . $conexao->error;
    }
    
    $stmt_delete->close();
    $conexao->close();
    header('Location: listar_pacientes.php');
    exit;
}
?>