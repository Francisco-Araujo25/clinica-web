<?php
include '../projeto-final-clinica/clinica/config/conexao.php';
// Inicia sessão para mensagens se necessário
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clínica Médica - Dashboard</title>
   
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/styles.css">
   
    <style>
        /* Estilos específicos para o dashboard */
        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
       
        .stat-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            text-align: center;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
        }
       
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }
       
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0.5rem 0;
        }
       
        .stat-label {
            color: var(--gray);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
       
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
       
        .module-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--box-shadow);
        }
       
        .module-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }
       
        .module-list {
            list-style: none;
            margin-top: 1rem;
        }
       
        .module-list li {
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--gray-light);
        }
       
        .module-list li:last-child {
            border-bottom: none;
        }
       
        .module-list a {
            color: var(--dark-color);
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 0.5rem;
            border-radius: 4px;
            transition: var(--transition);
        }
       
        .module-list a:hover {
            background-color: var(--gray-light);
            padding-left: 1rem;
        }
       
        .module-list a::before {
            content: "→";
            margin-right: 10px;
            color: var(--primary-color);
            transition: var(--transition);
        }
       
        .module-list a:hover::before {
            margin-right: 15px;
        }
       
        .welcome-section {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 2rem;
            border-radius: var(--border-radius);
            margin-bottom: 2rem;
            box-shadow: var(--box-shadow);
        }
       
        .quick-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-top: 1.5rem;
        }
       
        @media (max-width: 768px) {
            .dashboard-stats {
                grid-template-columns: 1fr;
            }
           
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
           
            .quick-actions {
                flex-direction: column;
            }
           
            .quick-actions .btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>

<header class="page-header">
    <div class="container">
        <h1 class="page-title">Clínica Médica</h1>
        <nav class="breadcrumb">
            <a href="index.php">Dashboard</a> / Início
        </nav>
    </div>
</header>

<main>
    <div class="container">
       
        <!-- Seção de Boas-Vindas -->
        <div class="welcome-section">
            <h2>Bem-vindo ao Sistema da Clínica</h2>
            <p>Gerencie pacientes, médicos, consultas e muito mais em um só lugar.</p>
           
            <div class="quick-actions">
                <a href="pacientes/cadastrar_paciente.php" class="btn">Novo Paciente</a>
                <a href="medicos/cadastrar_medico.php" class="btn">Novo Médico</a>
                <a href="consultas/cadastrar_consulta.php" class="btn">Nova Consulta</a>
            </div>
        </div>
       
        <!-- Estatísticas (pode ser preenchido com dados reais do banco) -->
        <div class="dashboard-stats">
            <div class="stat-card">
                <div class="stat-label">Total de Pacientes</div>
                <div class="stat-number">
                    <?php
                    // Exemplo: Conectar e contar pacientes
                
                    $sql = "SELECT COUNT(*) as total FROM pacientes";
                    $result = $conexao->query($sql);
                    if ($result && $row = $result->fetch_assoc()) {
                        echo $row['total'];
                    } else {
                        echo "0";
                    }
                    ?>
                </div>
                <a href="pacientes/listar_pacientes.php" class="btn btn-secondary mt-2">Ver Todos</a>
            </div>
           
            <div class="stat-card">
                <div class="stat-label">Total de Médicos</div>
                <div class="stat-number">
                    <?php
                    $sql = "SELECT COUNT(*) as total FROM medicos";
                    $result = $conexao->query($sql);
                    if ($result && $row = $result->fetch_assoc()) {
                        echo $row['total'];
                    } else {
                        echo "0";
                    }
                    ?>
                </div>
                <a href="medicos/listar_medicos.php" class="btn btn-secondary mt-2">Ver Todos</a>
            </div>
           
            <div class="stat-card">
                <div class="stat-label">Consultas Hoje</div>
                <div class="stat-number">
                    <?php
                    $sql = "SELECT COUNT(*) as total FROM consultasmedicas
                            WHERE DATE(data_consulta) = CURDATE()";
                    $result = $conexao->query($sql);
                    if ($result && $row = $result->fetch_assoc()) {
                        echo $row['total'];
                    } else {
                        echo "0";
                    }
                    ?>
                </div>
                <a href="consultas/listar_consulta.php" class="btn btn-secondary mt-2">Ver Agenda</a>
            </div>
        </div>
       
        <!-- Módulos do Sistema -->
        <div class="dashboard-grid">
            <!-- Módulo de Pacientes -->
            <div class="module-card">
                <div class="module-icon">👨‍⚕️</div>
                <h3>Pacientes</h3>
                <p>Gerencie o cadastro de pacientes da clínica.</p>
               
                <ul class="module-list">
                    <li><a href="pacientes/listar_pacientes.php">Listar Pacientes</a></li>
                    <li><a href="pacientes/cadastrar_paciente.php">Cadastrar Novo Paciente</a></li>
                    <li><a href="pacientes/editar_paciente.php">Editar Paciente</a></li>
                </ul>
            </div>
           
            <!-- Módulo de Médicos -->
            <div class="module-card">
                <div class="module-icon">👩‍⚕️</div>
                <h3>Médicos</h3>
                <p>Gerencie o cadastro de médicos da clínica.</p>
               
                <ul class="module-list">
                    <li><a href="medicos/listar_medicos.php">Listar Médicos</a></li>
                    <li><a href="medicos/cadastrar_medico.php">Cadastrar Novo Médico</a></li>
                    <li><a href="medicos/editar_medico.php">Editar Médico</a></li>
                </ul>
            </div>
           
            <!-- Módulo de Consultas -->
            <div class="module-card">
                <div class="module-icon">📅</div>
                <h3>Consultas</h3>
                <p>Gerencie as consultas e agendamentos.</p>
               
                <ul class="module-list">
                    <li><a href="consultas/listar_consulta.php">Listar Consultas</a></li>
                    <li><a href="consultas/cadastrar_consulta.php">Agendar Consulta</a></li>
                </ul>
            </div>
        </div>
       
        <!-- Links Rápidos -->
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Acesso Rápido</h3>
                <div class="flex gap-sm flex-wrap">
                    <a href="pacientes/cadastrar_paciente.php" class="btn">Cadastrar Paciente</a>
                    <a href="medicos/cadastrar_medico.php" class="btn">Cadastrar Médico</a>
                    <a href="consultas/cadastrar_consulta.php" class="btn">Agendar Consulta</a>
                    <a href="pacientes/listar_pacientes.php" class="btn btn-secondary">Pacientes</a>
                    <a href="medicos/listar_medicos.php" class="btn btn-secondary">Médicos</a>
                    <a href="consultas/listar_consulta.php" class="btn btn-secondary">Consultas</a>
                </div>
            </div>
        </div>

        <footer class="site-footer">
            <small>&copy; <?= date('Y') ?> Clínica Médica - Sistema de Gerenciamento</small><br>
            <small>Versão 1.0 | Dashboard Principal</small>

            <h2>Créditos</h2>

            <ul class="creditos">
                <li>
                    <span>Francisco</span>
                    <a href="https://github.com/Francisco-Araujo25"
                    target="_blank" rel="noopener">
                        <i class="bi bi-github"></i>
                    </a>
                </li>

                <li>
                    <span>Gustavo</span>
                    <a href="https://github.com/gustavocesarB"
                    target="_blank" rel="noopener">
                        <i class="bi bi-github"></i>
                    </a>
                </li>

                <li>
                    <span>Arthur Rubbo</span>
                </li>
            </ul>
        </footer>

    </div>
</main>

</body>
</html>
