-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 18/12/2025 às 19:29
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `projeto_clinica`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `consultasmedicas`
--

CREATE TABLE `consultasmedicas` (
  `id_consulta` int(11) NOT NULL,
  `data_consulta` datetime NOT NULL,
  `diagnostico` text DEFAULT NULL,
  `tratamento` text DEFAULT NULL,
  `prescricao_medica` text DEFAULT NULL,
  `paciente_id` int(11) NOT NULL,
  `medico_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `consultasmedicas`
--

INSERT INTO `consultasmedicas` (`id_consulta`, `data_consulta`, `diagnostico`, `tratamento`, `prescricao_medica`, `paciente_id`, `medico_id`) VALUES
(1, '2025-12-23 15:04:00', 'DWA', 'AQRF3QW4RFE', 'WEQFEWQFGWQEF', 6, 8);

-- --------------------------------------------------------

--
-- Estrutura para tabela `medicos`
--

CREATE TABLE `medicos` (
  `id_medico` int(11) NOT NULL,
  `cpf` char(11) NOT NULL,
  `nome_medico` varchar(100) NOT NULL,
  `especialidade` varchar(50) DEFAULT NULL,
  `crm` varchar(20) NOT NULL,
  `telefone` char(14) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `medicos`
--

INSERT INTO `medicos` (`id_medico`, `cpf`, `nome_medico`, `especialidade`, `crm`, `telefone`) VALUES
(5, '61113972904', 'Fran', 'podologias', '885768', '4399870989'),
(8, '61113972905', 'Fran', 'podologiass', '5435345', '43433242342');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pacientes`
--

CREATE TABLE `pacientes` (
  `id_paciente` int(11) NOT NULL,
  `nome_paciente` varchar(250) NOT NULL,
  `cpf` char(11) NOT NULL,
  `data_nascimento` date NOT NULL,
  `genero` enum('M','F') DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pacientes`
--

INSERT INTO `pacientes` (`id_paciente`, `nome_paciente`, `cpf`, `data_nascimento`, `genero`, `endereco`, `telefone`) VALUES
(4, 'gustavo', '12433455451', '2008-02-02', 'M', 'ra', '232323232323'),
(6, 'gustavoo', '12878354945', '2222-02-02', 'M', 'gggggggg', '1212121211212'),
(9, 'gustavo', '12878254945', '2222-02-02', 'M', 'gggggggg', '12121212112');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `consultasmedicas`
--
ALTER TABLE `consultasmedicas`
  ADD PRIMARY KEY (`id_consulta`),
  ADD KEY `paciente_id` (`paciente_id`),
  ADD KEY `medico_id` (`medico_id`),
  ADD KEY `idx_consultas_data` (`data_consulta`),
  ADD KEY `idx_consultas_medico` (`medico_id`),
  ADD KEY `idx_consultas_paciente` (`paciente_id`);

--
-- Índices de tabela `medicos`
--
ALTER TABLE `medicos`
  ADD PRIMARY KEY (`id_medico`),
  ADD UNIQUE KEY `numeroregistromedico` (`crm`),
  ADD UNIQUE KEY `uk_medicos_cpf` (`cpf`),
  ADD KEY `idx_medicos_nome` (`nome_medico`);

--
-- Índices de tabela `pacientes`
--
ALTER TABLE `pacientes`
  ADD PRIMARY KEY (`id_paciente`),
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD KEY `idx_pacientes_nome` (`nome_paciente`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `consultasmedicas`
--
ALTER TABLE `consultasmedicas`
  MODIFY `id_consulta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `medicos`
--
ALTER TABLE `medicos`
  MODIFY `id_medico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `pacientes`
--
ALTER TABLE `pacientes`
  MODIFY `id_paciente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `consultasmedicas`
--
ALTER TABLE `consultasmedicas`
  ADD CONSTRAINT `fk_consulta_medico` FOREIGN KEY (`medico_id`) REFERENCES `medicos` (`id_medico`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_consulta_paciente` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id_paciente`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
