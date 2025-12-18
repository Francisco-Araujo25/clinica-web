Sistema de Gerenciamento de Clínica
Descrição
Sistema web para gerenciamento de clínica médica desenvolvido em PHP e MySQL. Permite cadastrar e gerenciar pacientes, médicos e consultas médicas.

Funcionalidades
Gestão de Pacientes: Cadastro, listagem, edição e exclusão de pacientes

Gestão de Médicos: Cadastro e gerenciamento de médicos com CRM

Gestão de Consultas: Agendamento de consultas entre médicos e pacientes

Dashboard: Página inicial com estatísticas e acesso rápido

Tecnologias
PHP 7.4+

MySQL

HTML5, CSS3, JavaScript

Font Awesome para ícones

Google Fonts (Poppins)

Características
Interface moderna e responsiva

Navegação por navbar

Formulários com validação

Tabelas responsivas

Segurança com prepared statements

Feedback visual para ações do usuário

Estrutura do Projeto
text
clinica/
├── index.php                 (Dashboard)
├── config/conexao.php        (Conexão com banco)
├── includes/header.php       (Header com navbar)
├── assets/css/styles.css     (Estilos)
├── pacientes/                (Gestão de pacientes)
├── medicos/                  (Gestão de médicos)
└── consultas/                (Gestão de consultas)
Instalação
Clone o repositório

Configure o banco de dados MySQL

Ajuste as credenciais em config/conexao.php

Coloque os arquivos no servidor web

Acesse http://localhost/clinica/

Banco de Dados
O sistema utiliza 3 tabelas principais:

pacientes - Informações dos pacientes

medicos - Informações dos médicos

consultasmedicas - Registro de consultas

Objetivo
Este projeto foi desenvolvido para demonstrar habilidades em desenvolvimento web com PHP, criação de interfaces modernas e implementação de sistemas de gerenciamento.

Licença
MIT License

Autores
[Francisco Araujo] - GitHub: @Francisco-Araujo25
[Gustavo Bianchi] - GitHub: @gustavocesarB
[Arthur Rubbo] 
