-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 13 avr. 2026 à 09:14
-- Version du serveur : 8.4.7
-- Version de PHP : 8.3.28

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
) ENGINE=InnoDB AUTO_INCREMENT=3267 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `agents`
--

INSERT INTO `agents` (`id`, `idFiscal`, `idProx`, `nom`, `prenom`, `email`, `password`, `ste`, `campagne`, `role`, `token`, `createdAt`, `isDeleted`, `profilePic`, `needReset`) VALUES
(1, '999999999', '69', 'Fathi', 'Radaoune', 'fathi.marcel@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3', 'A', 'c7269c10d9a11945', '2026-02-24 10:32:33', 0, NULL, 0),
(2631, '560', '510', 'EL ABDALAOUI', 'Hicham ', 'hicham.elabdalaoui@cio.com', '7053437511bcb1b878b6b73c8ac80b64', 'CIO', 'P3', 'A', '5bc5500d90230705', '2026-03-26 15:07:26', 0, NULL, 0),
(2637, '273', '', 'AARAB', 'Dounia', 'aarab.dounia@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/Part time', 'U', '613cc069189a0f73', '2026-03-30 09:25:28', 0, NULL, 0),
(2642, '702', '', 'ABDOUNI', ' samir', 'abdouni.samir@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/NEWBIE', 'U', 'e7380080f36355d0', '2026-03-30 09:25:28', 0, NULL, 0),
(2647, '61', '', 'ABOU OSMANE', 'Diomonde', 'abouosmane.diomonde@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3 EXPERT', 'U', '76ac26ca5ca68b10', '2026-03-30 09:25:28', 0, NULL, 0),
(2652, '422', '', 'ABOUDOU HASSANI', 'Abasse', 'aboudouhassani.abasse@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3 FOST', 'U', NULL, '2026-03-30 09:25:28', 0, NULL, 1),
(2657, '598', '', 'ADJOBIGNON BILEMBA', 'Lucricia', 'adjobignonbilemba.lucricia@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3', 'U', NULL, '2026-03-30 09:25:28', 0, NULL, 1),
(2662, '674', '', 'AFI SYLVIE', 'Agbokpin', 'afisylvie.agbokpin@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/NEWBIE', 'U', NULL, '2026-03-30 09:25:29', 0, NULL, 1),
(2667, '585', '', 'AGOUA INGRID ', 'Vanessa', 'agouaingrid.vanessa@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/CHAT', 'U', NULL, '2026-03-30 09:25:29', 0, NULL, 1),
(2672, '647', '', 'AHIRI', ' khalid', 'ahiri.khalid@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/NEWBIE', 'U', NULL, '2026-03-30 09:25:29', 0, NULL, 1),
(2677, '93', '', 'AISSAOUI', 'Soukaina', 'aissaoui.soukaina@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3', 'U', NULL, '2026-03-30 09:25:29', 0, NULL, 1),
(2682, '701', '', 'AKANDE', ' franck thierry', 'akande.franckthierry@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/NEWBIE', 'U', NULL, '2026-03-30 09:25:30', 0, NULL, 1),
(2687, '710', '', 'ALAMI', 'Amine', 'alami.amine@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', '', 'U', NULL, '2026-03-30 09:25:30', 0, NULL, 1),
(2692, '2', '', 'CHAFIK', 'Allal', 'chafik.allal@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3 FOST', 'U', NULL, '2026-03-30 09:25:30', 0, NULL, 1),
(2697, '295', '', 'AQIQI', 'Zineb', 'aqiqi.zineb@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/CHAT', 'U', NULL, '2026-03-30 09:25:30', 0, NULL, 1),
(2702, '707', '', 'LACINA', 'Assande moro ', 'lacina.assandemoro@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', '', 'U', NULL, '2026-03-30 09:25:31', 0, NULL, 1),
(2707, '651', '', 'CHEICK AWEISSOU', 'Karaneni toure', 'cheickaweissou.karanenitoure@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/NEWBIE', 'U', NULL, '2026-03-30 09:25:31', 0, NULL, 1),
(2712, '528', '', 'BALLOUK', 'El hassan ', 'ballouk.elhassan@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3', 'U', NULL, '2026-03-30 09:25:31', 0, NULL, 1),
(2717, '105', '', 'BARDALLOU', 'Yousra', 'bardallou.yousra@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3', 'U', NULL, '2026-03-30 09:25:31', 0, NULL, 1),
(2722, '593', '', 'BASSALA MALEMODO', 'Cedric', 'bassalamalemodo.cedric@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3', 'U', NULL, '2026-03-30 09:25:32', 0, NULL, 1),
(2727, '681', '', 'BENAISSA', 'Faissal', 'benaissa.faissal@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/NEWBIE', 'U', NULL, '2026-03-30 09:25:32', 0, NULL, 1),
(2732, '29', '', 'BENHAID', 'Nouria', 'benhaid.nouria@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/Part time', 'U', NULL, '2026-03-30 09:25:32', 0, NULL, 1),
(2737, '569', '', 'BENNANI', 'Amine', 'bennani.amine@cio.com', '7053437511bcb1b878b6b73c8ac80b64', 'CIO', 'P3', 'U', NULL, '2026-03-30 09:25:32', 0, NULL, 1),
(2742, '18', '', 'BENYECHOU', 'Maryeme', 'benyechou.maryeme@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3 FOST', 'U', NULL, '2026-03-30 09:25:32', 0, NULL, 1),
(2747, '660', '', 'CHARLES KONAN', ' bergson koffi', 'charleskonan.bergsonkoffi@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/NEWBIE', 'U', NULL, '2026-03-30 09:25:33', 0, NULL, 1),
(2752, '501', '', 'BOUAKRI ', 'Bouakri ', 'bouakri.bouakri@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3', 'U', NULL, '2026-03-30 09:25:33', 0, NULL, 1),
(2757, '646', '', 'BOUBACAR ', 'Lalya bah', 'boubacar.lalyabah@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/NEWBIE', 'U', NULL, '2026-03-30 09:25:33', 0, NULL, 1),
(2762, '683', '', 'BOULAMANE', 'Aimad ', 'boulamane.aimad@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/NEWBIE', 'U', NULL, '2026-03-30 09:25:33', 0, NULL, 1),
(2767, '135', '', 'CHAHID', 'Laila', 'chahid.laila@cio.com', '7053437511bcb1b878b6b73c8ac80b64', 'CIO', 'P3/Part time', 'U', NULL, '2026-03-30 09:25:34', 0, NULL, 1),
(2772, '293', '', 'CHMANTI HOUARI', 'Sanae', 'chmantihouari.sanae@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/CHAT', 'U', NULL, '2026-03-30 09:25:34', 0, NULL, 1),
(2777, '653', '', 'CORINSIO', ' werner tanoh', 'corinsio.wernertanoh@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/NEWBIE', 'U', NULL, '2026-03-30 09:25:34', 0, NULL, 1),
(2782, '616', '', 'COULIBALY', ' fatoumata', 'coulibaly.fatoumata@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/CHAT', 'U', NULL, '2026-03-30 09:25:34', 0, NULL, 1),
(2787, '607', '', 'DEBBARH', ' abderrazzak', 'debbarh.abderrazzak@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/CHAT', 'U', NULL, '2026-03-30 09:25:35', 0, NULL, 1),
(2792, '23', '', 'DERMAJ', 'Latifa', 'dermaj.latifa@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3', 'U', NULL, '2026-03-30 09:25:35', 0, NULL, 1),
(2797, '365', '', 'DIALLO OUMOU', 'Habibata', 'diallooumou.habibata@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/CHAT', 'U', NULL, '2026-03-30 09:25:35', 0, NULL, 1),
(2802, '668', '', 'DIDI', 'Asmae', 'didi.asmae@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/NEWBIE', 'U', NULL, '2026-03-30 09:25:35', 0, NULL, 1),
(2807, '690', '', 'DIRABOU ', 'Lucrece ophelie ', 'dirabou.lucreceophelie@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/NEWBIE', 'U', NULL, '2026-03-30 09:25:35', 0, NULL, 1),
(2812, '686', '', 'DJOLO TRA', ' lorraine ', 'djolotra.lorraine@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/NEWBIE', 'U', NULL, '2026-03-30 09:25:36', 0, NULL, 1),
(2817, '709', '', 'ECH-CHIBI', 'Abdelkader ', 'echchibi.abdelkader@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/NEWBIE', 'U', NULL, '2026-03-30 09:25:36', 0, NULL, 1),
(2822, '675', '', 'EL ADIBE', 'Hanane', 'eladibe.hanane@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/NEWBIE', 'U', NULL, '2026-03-30 09:25:36', 0, NULL, 1),
(2827, '627', '', 'EL ALAMI ELHASSANI', 'Saad', 'elalamielhassani.saad@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3', 'U', NULL, '2026-03-30 09:25:36', 0, NULL, 1),
(2832, '638', '', ' EL BAKKALI ', 'Oumaima', 'elbakkali.oumaima@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3', 'U', NULL, '2026-03-30 09:25:37', 0, NULL, 1),
(2837, '708', '', 'EL HADIRI', ' saida', 'elhadiri.saida@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', '', 'U', NULL, '2026-03-30 09:25:37', 0, NULL, 1),
(2842, '605', '', 'EL HIHI', 'Youssef', 'elhihi.youssef@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/CHAT', 'U', NULL, '2026-03-30 09:25:37', 0, NULL, 1),
(2847, '677', '', 'EL IRAR', 'Mehdi', 'elirar.mehdi@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/NEWBIE', 'U', NULL, '2026-03-30 09:25:37', 0, NULL, 1),
(2852, '481', '', 'EL JAOUHARI', 'Ghita', 'eljaouhari.ghita@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3', 'U', NULL, '2026-03-30 09:25:38', 0, NULL, 1),
(2857, '149', '', 'EL KANDOUSSI', ' houda', 'elkandoussi.houda@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/Part time', 'U', NULL, '2026-03-30 09:25:38', 0, NULL, 1),
(2862, '632', '', 'ELAREBI', 'Safae', 'elarebi.safae@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/CHAT', 'U', NULL, '2026-03-30 09:25:38', 0, NULL, 1),
(2867, '565', '', 'TAYBI', 'Hamid', 'elkhamlichi.youssef@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'CIO', 'P3 EXPERT', 'U', NULL, '2026-03-30 09:25:38', 0, NULL, 1),
(2872, '296', '', 'EL OUARDY', 'Ghizlane', 'elouardy.ghizlane@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/CHAT', 'U', NULL, '2026-03-30 09:25:38', 0, NULL, 1),
(2877, '165', '', 'ELYADRI', 'Sahar', 'elyadri.sahar@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3 FOST', 'U', NULL, '2026-03-30 09:25:39', 0, NULL, 1),
(2882, '479', '', 'ERRAJI', 'Zineb', 'erraji.zineb@cio.com', '7053437511bcb1b878b6b73c8ac80b64', 'CIO', 'P3/Part time', 'U', NULL, '2026-03-30 09:25:39', 0, NULL, 1),
(2887, '546', '', 'ETBER ', 'Hajar ', 'etber.hajar@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3', 'U', NULL, '2026-03-30 09:25:39', 0, NULL, 1),
(2892, '290', '', 'FALAHI', 'Zakia', 'falahi.zakia@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'Coach', 'U', NULL, '2026-03-30 09:25:39', 0, NULL, 1),
(2897, '657', '', ' FILALI HANINE', 'Hatim', 'filalihanine.hatim@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/NEWBIE', 'U', '294c712751e6b51c', '2026-03-30 09:25:40', 0, NULL, 0),
(2902, '684', '', 'FILALI MIKOU ', 'Fouad ', 'filalimikou.fouad@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/NEWBIE', 'U', NULL, '2026-03-30 09:25:40', 0, NULL, 1),
(2907, '692', '', 'GAADA', 'Houssam', 'gaada.houssam@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/NEWBIE', 'U', NULL, '2026-03-30 09:25:40', 0, NULL, 1),
(2912, '663', '', 'GBAGBA DE NGATA ', 'Eddy innocent', 'gbagbadengata.eddyinnocent@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/NEWBIE', 'U', NULL, '2026-03-30 09:25:40', 0, NULL, 1),
(2917, '623', '', 'GEORNYASSEMBEBEKALE', 'Evan', 'geornyassembebekale.evan@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P4', 'U', NULL, '2026-03-30 09:25:40', 0, NULL, 1),
(2922, '513', '', 'FETTAH', 'Ghizlane', 'fettah.ghizlane@cio.com', '7053437511bcb1b878b6b73c8ac80b64', 'CIO', 'P3', 'U', NULL, '2026-03-30 09:25:41', 0, NULL, 1),
(2927, '654', '', 'GHOUDANE', 'Adil', 'ghoudane.adil@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/NEWBIE', 'U', NULL, '2026-03-30 09:25:41', 0, NULL, 1),
(2932, '574', '', 'GNABA ', 'Audrey', 'gnaba.audrey@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'p3', 'U', NULL, '2026-03-30 09:25:41', 0, NULL, 1),
(2937, '139', '', 'HADDANE', 'Hamza', 'haddane.hamza@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3 EXPERT', 'U', NULL, '2026-03-30 09:25:41', 0, NULL, 1),
(2942, '695', '', 'HARMAK ', 'Chaymae ', 'harmak.chaymae@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/NEWBIE', 'U', NULL, '2026-03-30 09:25:41', 0, NULL, 1),
(2947, '515', '', 'HAZZAZ ', 'Chafik', 'hazzaz.chafik@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3', 'U', NULL, '2026-03-30 09:25:42', 0, NULL, 1),
(2952, '92', '', 'HEZI', 'Otman', 'hezi.otman@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3 EXPERT', 'U', NULL, '2026-03-30 09:25:42', 0, NULL, 1),
(2957, '649', '', 'HRIMACH', 'Rim', 'hrimach.rim@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/NEWBIE', 'U', NULL, '2026-03-30 09:25:42', 0, NULL, 1),
(2962, '471', '', 'JAAD', 'Abderrahim', 'jaad.abderrahim@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3 ', 'U', NULL, '2026-03-30 09:25:42', 0, NULL, 1),
(2967, '578', '', 'KANDAR', 'Imane', 'kandar.imane@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3', 'U', NULL, '2026-03-30 09:25:42', 0, NULL, 1),
(2972, '67', '', 'KEITA', 'Mohamed', 'keita.mohamed@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3 FOST', 'U', NULL, '2026-03-30 09:25:42', 0, NULL, 1),
(2977, '136', '', 'BENHAYOUNE', 'Khaoula', 'benhayoune.khaoula@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3 FOST', 'U', NULL, '2026-03-30 09:25:43', 0, NULL, 1),
(2982, '540', '', 'KHAYATI ', 'Abdel ali', 'khayati.abdelali@cio.com', '7053437511bcb1b878b6b73c8ac80b64', 'CIO', 'P3 FOST', 'U', NULL, '2026-03-30 09:25:43', 0, NULL, 1),
(2987, '659', '', 'KOBENAN APPAH', 'Koffi ', 'kobenanappah.koffi@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/NEWBIE', 'U', NULL, '2026-03-30 09:25:43', 0, NULL, 1),
(2992, '466', '', 'KOUADIO HULICE', 'Franck olivier', 'kouadiohulice.franckolivier@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/CHAT', 'U', NULL, '2026-03-30 09:25:43', 0, NULL, 1),
(2997, '468', '', 'KOUADIO', 'Nguessan benedicte', 'kouadio.nguessanbenedicte@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3 FOST', 'U', NULL, '2026-03-30 09:25:43', 0, NULL, 1),
(3002, '465', '', 'KOUAKOU ADJIA DONGO', 'Adeline', 'kouakouadjiadongo.adeline@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3', 'U', NULL, '2026-03-30 09:25:44', 0, NULL, 1),
(3007, '689', '', 'KPANTE CHERIF ', 'Boris', 'kpantecherif.boris@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/NEWBIE', 'U', NULL, '2026-03-30 09:25:44', 0, NULL, 1),
(3012, '703', '', 'LAAGUIDI ', 'Souhayla', 'laaguidi.souhayla@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/NEWBIE', 'U', NULL, '2026-03-30 09:25:44', 0, NULL, 1),
(3017, '639', '', 'LACHHAB ', 'Walid', 'lachhab.walid@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3', 'U', NULL, '2026-03-30 09:25:44', 0, NULL, 1),
(3022, '562', '', 'LAZRAK', 'Khalil', 'lazrak.khalil@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3', 'U', NULL, '2026-03-30 09:25:44', 0, NULL, 1),
(3027, '699', '', 'MAAMOUR', ' aouatif', 'maamour.aouatif@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/NEWBIE', 'U', NULL, '2026-03-30 09:25:44', 0, NULL, 1),
(3032, '658', '', 'MALEKOUDOU ', 'Clarence', 'malekoudou.clarence@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/NEWBIE', 'U', NULL, '2026-03-30 09:25:45', 0, NULL, 1),
(3037, '571', '', 'MALIGA', 'Franklin', 'maliga.franklin@cio.com', '7053437511bcb1b878b6b73c8ac80b64', 'CIO', 'P3 FOST', 'U', NULL, '2026-03-30 09:25:45', 0, NULL, 1),
(3042, '393', '', 'MALLOUKI', 'Soukaina', 'mallouki.soukaina@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3', 'U', NULL, '2026-03-30 09:25:45', 0, NULL, 1),
(3047, '526', '', 'MANGRE ', 'Yann cedric', 'mangre.yanncedric@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3', 'U', NULL, '2026-03-30 09:25:45', 0, NULL, 1),
(3052, '655', '', 'MBENGA TSIA', 'Mauviane prudence', 'mbengatsia.mauvianeprudence@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/NEWBIE', 'U', NULL, '2026-03-30 09:25:45', 0, NULL, 1),
(3057, '641', '', 'MOKABA BIKINDOU ', 'Jeanchel wenlove', 'mokababikindou.jeanchelwenlove@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/NEWBIE', 'U', NULL, '2026-03-30 09:25:45', 0, NULL, 1),
(3062, '495', '', 'MAYALA CELESTE', 'Molomunzama', 'mayalaceleste.molomunzama@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/CHAT', 'U', NULL, '2026-03-30 09:25:46', 0, NULL, 1),
(3067, '299', '', 'MRABET', 'Fatima zahra', 'mrabet.fatimazahra@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'Coach', 'U', NULL, '2026-03-30 09:25:46', 0, NULL, 1),
(3072, '504', '', 'MVUMBI KIBINGWA ', 'Nino feraud', 'mvumbikibingwa.ninoferaud@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3', 'U', NULL, '2026-03-30 09:25:46', 0, NULL, 1),
(3077, '645', '', 'NADOU ASSIA ', 'Jacquelin', 'nadouassia.jacquelin@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/NEWBIE', 'U', NULL, '2026-03-30 09:25:46', 0, NULL, 1),
(3082, '594', '', 'NAJIBI', 'Nisrine', 'najibi.nisrine@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3', 'U', NULL, '2026-03-30 09:25:46', 0, NULL, 1),
(3087, '576', '', 'NAJM', 'Ouijdane ', 'najm.ouijdane@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3', 'U', NULL, '2026-03-30 09:25:46', 0, NULL, 1),
(3092, '696', '', 'BOUBACAR  NDIAYE', 'Pierre', 'boubacarndiaye.pierre@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/NEWBIE', 'U', NULL, '2026-03-30 09:25:47', 0, NULL, 1),
(3097, '53', '', 'NDIN', 'Déborah', 'ndin.deborah@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3', 'U', NULL, '2026-03-30 09:25:47', 0, NULL, 1),
(3102, '391', '', 'N\'DIN', 'Inchaud', 'ndin.inchaud@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3 FOST', 'U', NULL, '2026-03-30 09:25:47', 0, NULL, 1),
(3107, '652', '', 'ASSE SUZANNE', 'Natacha doli', 'assesuzanne.natachadoli@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/NEWBIE', 'U', NULL, '2026-03-30 09:25:47', 0, NULL, 1),
(3112, '640', '', 'NDOUKA NKOMBE ', 'Leslie', 'ndoukankombe.leslie@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3', 'U', NULL, '2026-03-30 09:25:47', 0, NULL, 1),
(3117, '147', '', 'N\'GORAN', 'Cyrielle joelle', 'ngoran.cyriellejoelle@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/CHAT', 'U', NULL, '2026-03-30 09:25:47', 0, NULL, 1),
(3122, '694', '', 'NGOUGNON ', 'Abdoul hamid', 'ngougnon.abdoulhamid@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/NEWBIE', 'U', NULL, '2026-03-30 09:25:48', 0, NULL, 1),
(3127, '671', '', 'YANNICK', 'Noe akheyan', 'yannick.noeakheyan@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/NEWBIE', 'U', NULL, '2026-03-30 09:25:48', 0, NULL, 1),
(3132, '673', '', 'DJIBRILLA', 'Oumarou', 'djibrilla.oumarou@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/NEWBIE', 'U', NULL, '2026-03-30 09:25:48', 0, NULL, 1),
(3137, '171', '', 'GNA', 'Phares', 'gna.phares@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3 FOST', 'U', NULL, '2026-03-30 09:25:48', 0, NULL, 1),
(3142, '670', '', 'RAQIQ', 'Hiba', 'raqiq.hiba@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/NEWBIE', 'U', NULL, '2026-03-30 09:25:48', 0, NULL, 1),
(3147, '553', '', 'RAZAFINDRAIB', 'Edith ', 'razafindraib.edith@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3', 'U', NULL, '2026-03-30 09:25:48', 0, NULL, 1),
(3152, '339', '', 'RAJAE ', 'Rozi', 'rajae.rozi@cio.com', '7053437511bcb1b878b6b73c8ac80b64', 'CIO', 'Coach', 'U', NULL, '2026-03-30 09:25:48', 0, NULL, 1),
(3157, '550', '', 'SAHAL ', ' safae ', 'sahal.safae@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3', 'U', NULL, '2026-03-30 09:25:49', 0, NULL, 1),
(3162, '589', '', 'SAYYOUR', ' jalal', 'sayyour.jalal@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3 FOST', 'U', NULL, '2026-03-30 09:25:49', 0, NULL, 1),
(3167, '556', '', 'SIDOUNA ', 'Abdelali', 'sidouna.abdelali@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3', 'U', NULL, '2026-03-30 09:25:49', 0, NULL, 1),
(3172, '698', '', 'SLIMI ', 'Imane', 'slimi.imane@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/NEWBIE', 'U', NULL, '2026-03-30 09:25:49', 0, NULL, 1),
(3177, '700', '', 'SOUMAORO', ' mohamed', 'soumaoro.mohamed@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/NEWBIE', 'U', NULL, '2026-03-30 09:25:49', 0, NULL, 1),
(3182, '705', '', 'TAHIROU ', ' yameogo', 'tahirou.yameogo@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/NEWBIE', 'U', NULL, '2026-03-30 09:25:49', 0, NULL, 1),
(3192, '494', '', 'TOKPESSI GBENAN', 'Jean-junior ', 'tokpessigbenan.jeanjunior@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3 FOST', 'U', NULL, '2026-03-30 09:25:50', 0, NULL, 1),
(3197, '404', '', 'TOKPESSI', 'Seda harold', 'tokpessi.sedaharold@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3', 'U', NULL, '2026-03-30 09:25:50', 0, NULL, 1),
(3202, '56', '', 'BLAISE ', 'Wawa', 'blaise.wawa@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3', 'U', NULL, '2026-03-30 09:25:50', 0, NULL, 1),
(3207, '603', '', 'MOUKALA ADHAM', 'William', 'moukalaadham.william@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/CHAT', 'U', NULL, '2026-03-30 09:25:50', 0, NULL, 1),
(3212, '662', '', 'YAO', 'Jefferson', 'yao.jefferson@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/NEWBIE', 'U', NULL, '2026-03-30 09:25:50', 0, NULL, 1),
(3217, '469', '', 'YEO APSE ADJEI', 'Massogolo', 'yeoapseadjei.massogolo@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3', 'U', NULL, '2026-03-30 09:25:51', 0, NULL, 1),
(3222, '38', '', 'ZAIDOUN ', 'Khaoula', 'zaidoun.khaoula@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3', 'U', NULL, '2026-03-30 09:25:51', 0, NULL, 1),
(3227, '394', '', 'ZEKRAOUI', 'Niema', 'zekraoui.niema@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3', 'U', NULL, '2026-03-30 09:25:51', 0, NULL, 1),
(3232, '693', '', 'ZIBO', 'Morelle', 'zibo.morelle@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/NEWBIE', 'U', NULL, '2026-03-30 09:25:51', 0, NULL, 1),
(3237, '13', '', 'GHESSAL', 'Imane', 'ghessal.imane@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/Part time', 'U', NULL, '2026-03-30 09:25:51', 0, NULL, 1),
(3242, '270', '', 'DERDAK', 'Meryem', 'derdak.meryem@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3', 'U', NULL, '2026-03-30 09:25:51', 0, NULL, 1),
(3247, '330', '', 'BENDIDI', 'Adnane', 'bendidi.adnane@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3 FOST', 'U', NULL, '2026-03-30 09:25:51', 0, NULL, 1),
(3252, '484', '', 'CHERKAOUI OMARI', 'Yousra', 'cherkaouiomari.yousra@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3', 'U', NULL, '2026-03-30 09:25:52', 0, NULL, 1),
(3257, '538', '', 'ISMAELI', 'Lotfi', 'ismaeli.lotfi@cio.com', '7053437511bcb1b878b6b73c8ac80b64', 'CIO', 'P3', 'U', NULL, '2026-03-30 09:25:52', 0, NULL, 1),
(3262, '2790', '', 'DOUNIA', 'Aarab', 'dounia.aarab@dc.com', '7053437511bcb1b878b6b73c8ac80b64', 'DC', 'P3/Part time', 'U', NULL, '2026-04-11 16:07:28', 0, NULL, 1);

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
-- Structure de la table `agent_info`
--

DROP TABLE IF EXISTS `agent_info`;
CREATE TABLE IF NOT EXISTS `agent_info` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idFiscal` int NOT NULL,
  `teams` int NOT NULL,
  `outlook` int NOT NULL,
  `whatsapp` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Structure de la table `documents`
--

DROP TABLE IF EXISTS `documents`;
CREATE TABLE IF NOT EXISTS `documents` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `contentType` text NOT NULL,
  `type_document` text NOT NULL,
  `account_doc_indice` int DEFAULT NULL,
  `base64` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `creationDate` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `creationHeure` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `isShown` tinyint(1) NOT NULL DEFAULT '1',
  `isDeleted` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  `isSeen` double NOT NULL DEFAULT '0',
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
