-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 29 avr. 2026 à 07:42
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
-- Structure de la table `parametres`
--

DROP TABLE IF EXISTS `parametres`;
CREATE TABLE IF NOT EXISTS `parametres` (
  `id` int NOT NULL AUTO_INCREMENT,
  `keyP` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `valueP` longtext,
  PRIMARY KEY (`id`),
  UNIQUE KEY `keyP` (`keyP`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `parametres`
--

INSERT INTO `parametres` (`id`, `keyP`, `valueP`) VALUES
(1, 'PasswordRegex', '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\\d).{1,}$/'),
(2, 'DefPassword', '7053437511bcb1b878b6b73c8ac80b64'),
(5, 'shiftsColors', '{\n    \"PXS_CHAT_VOICE_BLENDING\": {\"color\": \"#3498db\", \"textColor\": \"#FFFFFF\"},\n    \"BREAK\": {\"color\": \"#1ed760\", \"textColor\": \"#FFFFFF\"},\n    \"TRAINING\": {\"color\": \"#ff9900\", \"textColor\": \"#FFFFFF\"},\n    \"OFF\":{\"color\": \"#da0024\", \"textColor\": \"#FFFFFF\"}\n}'),
(6, 'permissions', '{\r\n    \"U\": {\"Dashboard\": true, \"Pointage\": true},\r\n    \"A\": {\"Dashboard\": true, \"Pointage\": false},\r\n    \"M\": {\"Dashboard\": true, \"Pointage\": true},\r\n}');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
