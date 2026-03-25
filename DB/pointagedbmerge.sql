-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 25 mars 2026 à 11:55
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

DELIMITER $$
--
-- Fonctions
--
DROP FUNCTION IF EXISTS `clean_string`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `clean_string` (`str` TEXT) RETURNS TEXT CHARSET utf8mb4 DETERMINISTIC BEGIN
    DECLARE result TEXT;
    SET result = LOWER(str);
    -- Remplacement des accents
    SET result = REPLACE(result, 'é', 'e');
    SET result = REPLACE(result, 'è', 'e');
    SET result = REPLACE(result, 'ê', 'e');
    SET result = REPLACE(result, 'ë', 'e');
    SET result = REPLACE(result, 'à', 'a');
    SET result = REPLACE(result, 'â', 'a');
    SET result = REPLACE(result, 'î', 'i');
    SET result = REPLACE(result, 'ï', 'i');
    SET result = REPLACE(result, 'ô', 'o');
    SET result = REPLACE(result, 'û', 'u');
    SET result = REPLACE(result, 'ù', 'u');
    SET result = REPLACE(result, 'ç', 'c');
    -- Suppression des espaces et tirets
    SET result = REPLACE(result, ' ', '');
    SET result = REPLACE(result, '-', '');
    SET result = REPLACE(result, '\'', '');
    
    RETURN result;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `action` varchar(50) NOT NULL,
  `idFiscal` varchar(50) NOT NULL,
  `details` json NOT NULL,
  `mois` int DEFAULT NULL,
  `annee` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `agents`
--

DROP TABLE IF EXISTS `agents`;
CREATE TABLE IF NOT EXISTS `agents` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idFiscal` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `idProx` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nom` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `prenom` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `email` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `password` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `ste` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `campagne` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `token` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isDeleted` tinyint(1) NOT NULL DEFAULT '0',
  `profilePic` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `needReset` double NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_fiscal` (`idFiscal`),
  UNIQUE KEY `idFiscal` (`idFiscal`),
  UNIQUE KEY `idFiscal_2` (`idFiscal`)
) ENGINE=InnoDB AUTO_INCREMENT=2626 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `agents`
--

INSERT INTO `agents` (`id`, `idFiscal`, `idProx`, `nom`, `prenom`, `email`, `password`, `ste`, `campagne`, `role`, `token`, `createdAt`, `isDeleted`, `profilePic`, `needReset`) VALUES
(1, '999999999', '', 'Fathi', 'marcel', 'fathi.marcel@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', '', 'A', 'd66335c622d271b4', '2026-02-24 10:32:33', 0, '', 0);

--
-- Déclencheurs `agents`
--
DROP TRIGGER IF EXISTS `before_insert_agent_email`;
DELIMITER $$
CREATE TRIGGER `before_insert_agent_email` BEFORE INSERT ON `agents` FOR EACH ROW BEGIN
    -- On vérifie si l'email est vide, NULL ou ne contient pas de '@'
    IF (NEW.email IS NULL OR NEW.email = '' OR NEW.email NOT LIKE '%@%') THEN
        SET NEW.email = CONCAT(
            clean_string(NEW.nom), 
            '.', 
            clean_string(NEW.prenom), 
            '@', 
            LOWER(REPLACE(NEW.ste, ' ', '')), 
            '.com'
        );
    END IF;
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `before_update_agent_email`;
DELIMITER $$
CREATE TRIGGER `before_update_agent_email` BEFORE UPDATE ON `agents` FOR EACH ROW /*BEGIN
    -- On ne génère l'email que si le nom, le prénom ou la STE a changé 
    -- ET que l'utilisateur n'a pas saisi manuellement un nouvel email différent
    IF (OLD.nom <> NEW.nom OR OLD.prenom <> NEW.prenom OR OLD.ste <> NEW.ste) 
       AND (NEW.email = OLD.email OR NEW.email IS NULL OR NEW.email = '') THEN
        
        SET NEW.email = CONCAT(
            clean_string(NEW.nom), 
            '.', 
            clean_string(NEW.prenom), 
            '@', 
            LOWER(REPLACE(NEW.ste, ' ', '')), 
            '.com'
        );
    END IF;
END*/

BEGIN
    -- STEP 1: Check if the current email is "Invalid" or "Empty"
    -- (If it's missing an '@', it's definitely not a real email)
    IF (OLD.email NOT LIKE '%@%' OR OLD.email IS NULL OR OLD.email = '') THEN
        
        SET NEW.email = CONCAT(
            clean_string(NEW.nom), 
            '.', 
            clean_string(NEW.prenom), 
            '@', 
            LOWER(REPLACE(NEW.ste, ' ', '')), 
            '.com'
        );

    -- STEP 2: Only if the user changed the Nom, Prenom, or STE 
    -- AND the email was already the "Auto-Generated" one (matches the OLD pattern)
   /* ELSEIF (OLD.nom <> NEW.nom OR OLD.prenom <> NEW.prenom OR OLD.ste <> NEW.ste) THEN
        
        -- Logic: If the user didn't manually change the email during this update, 
        -- but changed the STE, we only update the email IF it was the old auto-generated one.
        IF (NEW.email = OLD.email) THEN
             SET NEW.email = CONCAT(
                clean_string(NEW.nom), 
                '.', 
                clean_string(NEW.prenom), 
                '@', 
                LOWER(REPLACE(NEW.ste, ' ', '')), 
                '.com'
            );
        END IF;*/
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `compagne`
--

DROP TABLE IF EXISTS `compagne`;
CREATE TABLE IF NOT EXISTS `compagne` (
  `id` int NOT NULL AUTO_INCREMENT,
  `abreviation` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nomCompagne` text,
  `isDeleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`abreviation`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `compagne`
--

INSERT INTO `compagne` (`id`, `abreviation`, `nomCompagne`, `isDeleted`) VALUES
(1, 'Coach', 'Coach', 0),
(2, 'Luminus CMO FR', 'Luminus CMO FR', 0),
(3, 'Luminus CMO NL', 'Luminus CMO NL', 0),
(4, 'Luminus LHS', 'Luminus LHS', 0),
(5, 'P3', 'P3', 0),
(6, 'P3 EXPERT', 'P3 EXPERT', 0),
(7, 'P3 FOST', 'P3 FOST', 0),
(8, 'P3/CHAT', 'P3/CHAT', 0),
(9, 'P3/CHAT FOST', 'P3/CHAT FOST', 0),
(10, 'P3/NEWBIE', 'P3/NEWBIE', 0),
(11, 'P3/Part time', 'P3/Part time', 0);

-- --------------------------------------------------------

--
-- Structure de la table `logs`
--

DROP TABLE IF EXISTS `logs`;
CREATE TABLE IF NOT EXISTS `logs` (
  `id_log` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_agent` varchar(50) DEFAULT NULL,
  `date_concerne` date DEFAULT NULL,
  `colonne_modifiee` varchar(50) DEFAULT NULL,
  `ancienne_valeur` text,
  `nouvelle_valeur` text,
  `modifie_par` varchar(100) DEFAULT NULL,
  `date_modification` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_log`),
  UNIQUE KEY `id_log` (`id_log`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `logs_auth`
--

DROP TABLE IF EXISTS `logs_auth`;
CREATE TABLE IF NOT EXISTS `logs_auth` (
  `id` int NOT NULL AUTO_INCREMENT,
  `admin_id` int DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `content` longtext NOT NULL,
  `fromAdmin` varchar(100) NOT NULL,
  `toUser` varchar(100) NOT NULL,
  `isSent` double NOT NULL DEFAULT '0',
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `parametres`
--

INSERT INTO `parametres` (`id`, `keyP`, `valueP`) VALUES
(1, 'PasswordRegex', '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\\d).{1,}$/'),
(2, 'DefPassword', '7053437511bcb1b878b6b73c8ac80b64');

-- --------------------------------------------------------

--
-- Structure de la table `pointage`
--

DROP TABLE IF EXISTS `pointage`;
CREATE TABLE IF NOT EXISTS `pointage` (
  `id` int NOT NULL AUTO_INCREMENT,
  `agent_id_fiscal` varchar(50) DEFAULT NULL,
  `jour_index` int DEFAULT NULL,
  `valeur` varchar(10) DEFAULT NULL,
  `mois` int DEFAULT NULL,
  `annee` int DEFAULT NULL,
  `commentaire` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_daily_pointage` (`agent_id_fiscal`,`jour_index`,`mois`,`annee`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `abreviation` varchar(1) NOT NULL,
  `nomRole` text,
  `isDeleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`abreviation`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `abreviation`, `nomRole`, `isDeleted`) VALUES
(1, 'A', 'Administrateur', 0),
(2, 'C', 'Coach', 0),
(3, 'I', 'IT', 0),
(4, 'M', 'Mex', 0),
(5, 'U', 'Utilisateur', 0);

-- --------------------------------------------------------

--
-- Structure de la table `ste`
--

DROP TABLE IF EXISTS `ste`;
CREATE TABLE IF NOT EXISTS `ste` (
  `id` int NOT NULL AUTO_INCREMENT,
  `abreviation` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nomSte` text,
  `isDeleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`abreviation`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `ste`
--

INSERT INTO `ste` (`id`, `abreviation`, `nomSte`, `isDeleted`) VALUES
(1, 'CIO', 'Call in Out', 0),
(2, 'DC', 'Deal call', 0);

-- --------------------------------------------------------

--
-- Structure de la table `user_performance`
--

DROP TABLE IF EXISTS `user_performance`;
CREATE TABLE IF NOT EXISTS `user_performance` (
  `id` int NOT NULL AUTO_INCREMENT,
  `agent_id` varchar(50) NOT NULL,
  `totalJours` decimal(10,2) DEFAULT '0.00',
  `assiduite` varchar(100) DEFAULT NULL,
  `avance` varchar(100) DEFAULT NULL,
  `prime` varchar(100) DEFAULT NULL,
  `cdp` varchar(100) DEFAULT NULL,
  `remarque` text,
  `mois` int DEFAULT NULL,
  `annee` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_perf_period` (`agent_id`,`mois`,`annee`),
  UNIQUE KEY `unique_agent_performance_period` (`agent_id`,`mois`,`annee`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `logs_auth`
--
ALTER TABLE `logs_auth`
  ADD CONSTRAINT `logs_auth_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`);

--
-- Contraintes pour la table `pointage`
--
ALTER TABLE `pointage`
  ADD CONSTRAINT `pointage_agent_fk` FOREIGN KEY (`agent_id_fiscal`) REFERENCES `agents` (`idFiscal`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `user_performance`
--
ALTER TABLE `user_performance`
  ADD CONSTRAINT `perf_agent_fk` FOREIGN KEY (`agent_id`) REFERENCES `agents` (`idFiscal`) ON DELETE CASCADE ON UPDATE CASCADE;

DELIMITER $$
--
-- Évènements
--
DROP EVENT IF EXISTS `clean_duplicate_logs`$$
CREATE DEFINER=`root`@`localhost` EVENT `clean_duplicate_logs` ON SCHEDULE EVERY 5 DAY STARTS '2026-02-12 12:32:32' ON COMPLETION NOT PRESERVE ENABLE DO DELETE l1 
  FROM activity_logs l1
  INNER JOIN activity_logs l2 
  ON l1.action = l2.action 
     AND l1.idFiscal = l2.idFiscal 
     AND l1.details = l2.details 
     AND l1.mois = l2.mois 
     AND l1.annee = l2.annee
  WHERE l1.id > l2.id$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
