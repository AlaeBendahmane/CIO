-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 29 avr. 2026 à 07:36
-- Version du serveur : 8.0.31
-- Version de PHP : 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `pointagedb`
--

-- --------------------------------------------------------

--
-- Structure de la table `shifts`
--

DROP TABLE IF EXISTS `shifts`;
CREATE TABLE IF NOT EXISTS `shifts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `agentId` int NOT NULL,
  `shift_type` varchar(100) DEFAULT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_agent` (`agentId`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `shifts`
--

INSERT INTO `shifts` (`id`, `agentId`, `shift_type`, `start_time`, `end_time`, `created_at`, `updated_at`) VALUES
(2, 1, 'Inbound', '2026-04-22 13:00:00', '2026-04-22 15:00:00', '2026-04-21 15:15:17', '2026-04-22 10:56:00'),
(3, 1, 'BREAK', '2026-04-22 15:00:00', '2026-04-22 15:15:00', '2026-04-21 15:15:17', '2026-04-22 07:49:29'),
(4, 1, 'PXS_CHAT_VOICE_BLENDING', '2026-04-22 15:15:00', '2026-04-22 17:30:00', '2026-04-21 15:15:17', '2026-04-22 07:49:33'),
(5, 1, 'BREAK', '2026-04-22 17:30:00', '2026-04-22 17:45:00', '2026-04-21 15:15:17', '2026-04-22 07:49:38'),
(6, 1, 'PXS_CHAT_VOICE_BLENDING', '2026-04-22 17:45:00', '2026-04-22 19:00:00', '2026-04-21 15:15:17', '2026-04-22 07:49:42'),
(7, 1, 'Inbound', '2026-04-27 13:00:00', '2026-04-27 15:00:00', '2026-04-22 12:31:14', '2026-04-22 12:31:14'),
(8, 1, 'BREAK', '2026-04-27 15:00:00', '2026-04-27 15:15:00', '2026-04-22 12:31:14', '2026-04-22 12:31:14'),
(9, 1, 'Inbound', '2026-04-27 15:15:00', '2026-04-27 17:30:00', '2026-04-22 12:31:14', '2026-04-22 12:31:14'),
(10, 1, 'BREAK', '2026-04-27 17:30:00', '2026-04-27 17:45:00', '2026-04-22 12:31:14', '2026-04-22 12:31:14'),
(11, 1, 'Inbound', '2026-04-27 17:45:00', '2026-04-27 19:00:00', '2026-04-22 12:31:14', '2026-04-22 12:31:14'),
(12, 1, 'Inbound', '2026-04-28 08:00:00', '2026-04-28 10:15:00', '2026-04-22 12:31:14', '2026-04-22 12:31:14'),
(13, 1, 'BREAK', '2026-04-28 10:15:00', '2026-04-28 10:30:00', '2026-04-22 12:31:14', '2026-04-22 12:31:14'),
(14, 1, 'Inbound', '2026-04-28 10:30:00', '2026-04-28 12:00:00', '2026-04-22 12:31:14', '2026-04-22 12:31:14'),
(15, 1, 'BREAK', '2026-04-28 12:00:00', '2026-04-28 12:15:00', '2026-04-22 12:31:14', '2026-04-22 12:31:14'),
(16, 1, 'Inbound', '2026-04-28 12:15:00', '2026-04-28 14:00:00', '2026-04-22 12:31:14', '2026-04-22 12:31:14'),
(17, 1, 'Inbound', '2026-04-29 08:00:00', '2026-04-29 10:00:00', '2026-04-22 12:31:14', '2026-04-22 12:31:14'),
(18, 1, 'BREAK', '2026-04-29 10:00:00', '2026-04-29 10:15:00', '2026-04-22 12:31:14', '2026-04-22 12:31:14'),
(19, 1, 'Inbound', '2026-04-29 10:15:00', '2026-04-29 12:45:00', '2026-04-22 12:31:14', '2026-04-22 12:31:14'),
(20, 1, 'BREAK', '2026-04-29 12:45:00', '2026-04-29 13:00:00', '2026-04-22 12:31:14', '2026-04-22 12:31:14'),
(21, 1, 'Inbound', '2026-04-29 13:00:00', '2026-04-29 14:00:00', '2026-04-22 12:31:14', '2026-04-22 12:31:14'),
(22, 1, 'OFF', '2026-05-15 13:00:00', '2026-05-01 14:00:00', '2026-04-22 12:31:14', '2026-04-22 12:31:14');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `shifts`
--
ALTER TABLE `shifts`
  ADD CONSTRAINT `fk_agent` FOREIGN KEY (`agentId`) REFERENCES `agents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
