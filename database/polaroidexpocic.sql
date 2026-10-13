-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 09-Mar-2023 às 16:33
-- Versão do servidor: 10.4.27-MariaDB
-- versão do PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `polaroidexpocic`
--
CREATE DATABASE IF NOT EXISTS `polaroidexpocic` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `polaroidexpocic`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `fotografias`
--

CREATE TABLE IF NOT EXISTS `fotografias` (
  `id` int(11) NOT NULL,
  `categoria` varchar(50) NOT NULL,
  `fotoDefault` varchar(50) DEFAULT NULL,
  `fotoMoldura` varchar(50) DEFAULT NULL,
  `fotoMolduraCharacter` varchar(50) DEFAULT NULL,
  `observacoes` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `fotografias`
--
ALTER TABLE `fotografias`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `fotografias`
--
ALTER TABLE `fotografias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
