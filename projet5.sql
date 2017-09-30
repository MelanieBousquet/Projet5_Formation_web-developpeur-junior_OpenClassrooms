-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  127.0.0.1
-- Généré le :  Sam 30 Septembre 2017 à 22:01
-- Version du serveur :  5.7.14
-- Version de PHP :  7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `projet5`
--

-- --------------------------------------------------------

--
-- Structure de la table `animal`
--

CREATE TABLE `animal` (
  `id` int(11) NOT NULL,
  `sex_id` int(11) DEFAULT NULL,
  `breed_id` int(11) DEFAULT NULL,
  `type_identification_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `birthday` date DEFAULT NULL,
  `sterilised` tinyint(1) DEFAULT NULL,
  `identificationNumber` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` tinytext COLLATE utf8_unicode_ci,
  `user_id` int(11) DEFAULT NULL,
  `main_image_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `animal`
--

INSERT INTO `animal` (`id`, `sex_id`, `breed_id`, `type_identification_id`, `name`, `birthday`, `sterilised`, `identificationNumber`, `description`, `user_id`, `main_image_id`) VALUES
(1, 1, 1, 1, 'Jaffa', '2015-05-15', 1, '346U309', 'Chat sociable et câlin', 19, 1),
(10, 2, 1, 1, 'Bille', '2017-09-01', 1, '0945902', '<p>Chat sociable et c&acirc;lin</p>', 24, 66),
(14, 2, 1, 1, 'Jaffa2', '2015-01-27', 1, 'RQGQRG', 'Gentil, joueur, câin', NULL, 53),
(20, 4, 1, 3, 'test', NULL, 0, NULL, NULL, 43, 70),
(26, 1, 1, 1, 'Mistigri', '2017-02-01', 1, 'non précisé', '<p>Mistigri est un chat tr&egrave;s attachant et c&acirc;lin.</p>', NULL, NULL),
(28, 1, 1, 1, 'Mistigri', '2017-02-01', 1, NULL, '<p>Mistigri est un chat tr&egrave;s attachant et c&acirc;lin.</p>', NULL, 52),
(29, 2, 2, 1, 'Lulu', '2016-09-14', 1, NULL, '<p>Chat c&acirc;lin et joueur</p>', NULL, 58),
(30, 2, 1, 1, 'Titi', '2015-11-12', 1, NULL, '<p>Calme mais apeur&eacute;e</p>', NULL, 60),
(31, 1, 1, 1, 'Riri', '2017-04-19', 1, NULL, '<p>Chaton joueur et vif</p>', NULL, 63),
(32, 1, 1, 1, 'Loulou', '2016-09-01', 1, NULL, '<p>Chaton joueur et tr&egrave;s sociable</p>', NULL, 64),
(33, 1, 1, 4, 'Caramel', '2017-05-12', 0, NULL, '<p>Chaton tr&egrave;s sociable</p>', NULL, 68),
(34, 2, 1, 3, 'nehngoiqG', '2017-10-15', 1, NULL, '<p>VEDVEK?B</p>\r\n<p>FBFWSBN KSWLD</p>\r\n<p>DNDXND</p>\r\n<p>ND</p>\r\n<p>NWD</p>\r\n<p>N</p>\r\n<p>WDN</p>\r\n<p>DWN</p>\r\n<p>DW</p>\r\n<p>NGD</p>\r\n<p>N</p>', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `animal_state`
--

CREATE TABLE `animal_state` (
  `id` int(11) NOT NULL,
  `animal_id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `publication_id` int(11) DEFAULT NULL,
  `current_state` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `animal_state`
--

INSERT INTO `animal_state` (`id`, `animal_id`, `state_id`, `date`, `publication_id`, `current_state`) VALUES
(1, 1, 1, '2017-09-09', 10, 1),
(11, 14, 1, '2015-06-10', NULL, 0),
(12, 14, 2, '2016-01-10', NULL, 0),
(13, 14, 4, '2017-09-26', 23, 1),
(14, 20, 1, '2017-09-29', NULL, 1),
(26, 10, 1, '2017-09-17', NULL, 1),
(28, 28, 1, '2017-09-02', NULL, 1),
(29, 29, 1, '2017-09-12', NULL, 1),
(30, 30, 4, '2017-09-08', NULL, 1),
(31, 31, 4, '2017-09-09', NULL, 1),
(32, 32, 4, '2017-09-01', NULL, 0),
(33, 32, 6, '2017-09-09', NULL, 1),
(34, 33, 5, '2017-09-01', NULL, 0),
(35, 33, 7, '2017-09-07', NULL, 1),
(36, 34, 1, '2017-10-19', NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `breed`
--

CREATE TABLE `breed` (
  `id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `breed`
--

INSERT INTO `breed` (`id`, `type_id`, `name`) VALUES
(1, 1, 'européen'),
(2, 1, 'siamois'),
(3, 1, 'sacré de birmanie'),
(5, 1, 'persan'),
(8, 2, 'sans'),
(9, 2, 'labrador'),
(10, 2, 'golden retriever'),
(12, 2, 'berger allemand'),
(14, 2, 'bouledogue français'),
(15, 2, 'colley'),
(17, 1, 'Inconnue'),
(19, 1, 'Norvégien');

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(3, 'conseils'),
(1, 'lasso'),
(2, 'nousaider');

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `publication_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `message` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `contact`
--

CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phonenumber` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `facebook` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `twitter` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `contact`
--

INSERT INTO `contact` (`id`, `address`, `phonenumber`, `email`, `facebook`, `twitter`) VALUES
(1, 'Adresse de l\'association', 'Numéro de téléphone de l\'association', 'Email de l\'association', '#', '#');

-- --------------------------------------------------------

--
-- Structure de la table `description`
--

CREATE TABLE `description` (
  `id` int(11) NOT NULL,
  `animal_id` int(11) NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `event`
--

CREATE TABLE `event` (
  `id` int(11) NOT NULL,
  `beginning_date_event` datetime DEFAULT NULL,
  `place` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ending_date_event` datetime DEFAULT NULL,
  `lat` float DEFAULT NULL,
  `lng` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `event`
--

INSERT INTO `event` (`id`, `beginning_date_event`, `place`, `ending_date_event`, `lat`, `lng`) VALUES
(3, '2017-09-24 00:00:00', '41 Rue de la Poste, Puyoo, France', '2017-09-26 00:00:00', 43, 0),
(5, '2017-09-29 00:00:00', NULL, NULL, 43, 0),
(6, '2017-09-22 00:00:00', NULL, NULL, NULL, NULL),
(8, '2017-09-28 00:00:00', NULL, '2017-09-29 00:00:00', NULL, NULL),
(9, NULL, NULL, NULL, NULL, NULL),
(10, '2017-09-30 00:00:00', 'Rue des Genêts, Saint-Vincent-de-Tyrosse, France', '2017-10-01 00:00:00', 43.673, -1.28585),
(11, NULL, NULL, NULL, NULL, NULL),
(12, '2017-09-30 00:00:00', NULL, '2017-09-29 00:00:00', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `image`
--

CREATE TABLE `image` (
  `id` int(11) NOT NULL,
  `animal_id` int(11) DEFAULT NULL,
  `extension` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `alt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `image`
--

INSERT INTO `image` (`id`, `animal_id`, `extension`, `alt`, `date`) VALUES
(1, 1, 'jpeg', 'DSC_0636.JPG', '2017-09-12'),
(32, NULL, 'jpeg', 'DSC_0159.JPG', '2017-09-17'),
(36, NULL, 'jpeg', 'P1150290.JPG', '2017-09-25'),
(37, NULL, 'jpeg', 'P1150253.JPG', '2017-09-26'),
(41, NULL, 'jpeg', 'DSC_0046.JPG', '2017-09-28'),
(42, NULL, 'jpeg', 'dons.jpg', '2017-09-29'),
(43, NULL, 'jpeg', 'benevolat.jpg', '2017-09-29'),
(44, NULL, 'jpeg', 'benevolat.jpg', '2017-09-29'),
(45, 1, 'jpeg', 'dons.jpg', '2017-09-29'),
(47, NULL, 'jpeg', 'benevolat.jpg', '2017-09-30'),
(48, NULL, 'jpeg', 'benevolat.jpg', '2017-09-30'),
(49, NULL, 'jpeg', 'dons.jpg', '2017-09-30'),
(50, NULL, 'jpeg', 'benevolat.jpg', '2017-09-30'),
(51, NULL, 'jpeg', 'dons.jpg', '2017-09-30'),
(52, 28, 'jpeg', 'dons.jpg', '2017-09-30'),
(53, 14, 'jpeg', 'dons.jpg', '2017-09-30'),
(54, 14, 'jpeg', 'benevolat.jpg', '2017-09-30'),
(55, NULL, 'jpeg', 'dons.jpg', '2017-09-30'),
(56, NULL, 'jpeg', 'benevolat.jpg', '2017-09-30'),
(57, NULL, 'jpeg', 'benevolat.jpg', '2017-09-30'),
(58, 29, 'jpeg', 'dons.jpg', '2017-09-30'),
(59, 29, 'jpeg', 'benevolat.jpg', '2017-09-30'),
(60, 30, 'jpeg', 'dons.jpg', '2017-09-30'),
(61, 30, 'jpeg', 'benevolat.jpg', '2017-09-30'),
(62, 31, 'jpeg', 'benevolat.jpg', '2017-09-30'),
(63, 31, 'jpeg', 'dons.jpg', '2017-09-30'),
(64, 32, 'jpeg', 'benevolat.jpg', '2017-09-30'),
(65, 32, 'jpeg', 'dons.jpg', '2017-09-30'),
(66, 10, 'jpeg', 'benevolat.jpg', '2017-09-30'),
(67, 10, 'jpeg', 'dons.jpg', '2017-09-30'),
(68, 33, 'jpeg', 'benevolat.jpg', '2017-09-30'),
(69, 33, 'jpeg', 'dons.jpg', '2017-09-30'),
(70, 20, 'jpeg', 'dons.jpg', '2017-09-30'),
(71, 20, 'jpeg', 'benevolat.jpg', '2017-09-30');

-- --------------------------------------------------------

--
-- Structure de la table `image_publication`
--

CREATE TABLE `image_publication` (
  `image_id` int(11) NOT NULL,
  `publication_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `image_publication`
--

INSERT INTO `image_publication` (`image_id`, `publication_id`) VALUES
(1, 10),
(37, 22),
(48, 12),
(49, 12),
(53, 23),
(54, 23),
(55, 1),
(56, 11),
(57, 13);

-- --------------------------------------------------------

--
-- Structure de la table `note`
--

CREATE TABLE `note` (
  `id` int(11) NOT NULL,
  `animal_id` int(11) DEFAULT NULL,
  `date` datetime NOT NULL,
  `message` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `publication_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `note`
--

INSERT INTO `note` (`id`, `animal_id`, `date`, `message`, `user_id`, `publication_id`) VALUES
(10, 1, '2017-09-17 11:21:56', 'Testé FeLV et FIV = neg\r\nPhlegmon base de la queue \r\n=> 5 jours d\'antibio (amox)', 44, NULL),
(14, NULL, '2017-09-17 18:49:08', 'Présente pour le week-end', 44, 1),
(19, NULL, '2017-09-30 21:31:45', '<p>test</p>', 45, 11);

-- --------------------------------------------------------

--
-- Structure de la table `page`
--

CREATE TABLE `page` (
  `id` int(11) NOT NULL,
  `image_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `published` tinyint(1) NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci,
  `title` longtext COLLATE utf8_unicode_ci NOT NULL,
  `name_in_menu` longtext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `page`
--

INSERT INTO `page` (`id`, `image_id`, `category_id`, `published`, `content`, `title`, `name_in_menu`) VALUES
(3, 42, 2, 1, 'Nos protégés ont besoin de vous. Tout don est le bienvenu !\r\nLes dons matériels, comme des bacs à litière, des croquettes, des jouets, coussins..\r\nMais également des dons financiers, pour couvrir par exemple les frais vétérinaires, les stérilisations.. En devenant membre, ou en don spontané.\r\n\r\nVous avez plusieurs moyens de nous faire un don financier : par chèque, à l\'ordre "exemple_ordre_asso", en espèces, ou par Paypal : "lien_Paypal"', 'Aidez-nous en faisant un don', 'Dons'),
(4, 43, 2, 1, 'Vous avez du temps libre et êtes sensible à la cause animale ?  Devenez bénévole..\r\nNous avons besoin de familles d\'accueil pour accueillir, sociabiliser nos animaux dans le besoin, les  emmener chez le vétérinaire, accueillir les futurs adoptants..\r\nVous pouvez aussi nous aider pour des tâches administratives, lors de nos différents événements..', 'Aidez nous : Devenez bénévole !', 'Devenez bénévole'),
(5, 50, 1, 1, '<p>Nous sommes une association de protection animale consitut&eacute;e d\'une &eacute;quipe de b&eacute;n&eacute;voles.</p>\r\n<p>Nous ne disposons pas de refuge, tous nos animaux sont recueillis chez les b&eacute;n&eacute;voles.</p>\r\n<p>Vous pouvez nous rencontrer les des diff&eacute;rentes portes ouvertes de l\'association</p>', 'Qui sommes-nous ?', 'Qui sommes-nous'),
(6, 51, 3, 1, '<p>Un animal est un &ecirc;tre sensible. Son adoption doit &ecirc;tre un acte responsable et refl&eacute;chi&nbsp;car il vous engage sur de nombreuses ann&eacute;es. Certains chats peuvent vivre jusqu\'&agrave; une vingtaine d\'ann&eacute;e.</p>', 'Comment adopter ?', 'Comment adopter ?');

-- --------------------------------------------------------

--
-- Structure de la table `place`
--

CREATE TABLE `place` (
  `id` int(11) NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lat` double DEFAULT NULL,
  `lng` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `place`
--

INSERT INTO `place` (`id`, `address`, `lat`, `lng`) VALUES
(1, '41 Rue de la Poste, Puyoo, France', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `publication`
--

CREATE TABLE `publication` (
  `id` int(11) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `published` tinyint(1) NOT NULL,
  `date` datetime NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` longtext COLLATE utf8_unicode_ci,
  `updated_date` datetime NOT NULL,
  `main_image_id` int(11) DEFAULT NULL,
  `place_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `publication`
--

INSERT INTO `publication` (`id`, `event_id`, `published`, `date`, `title`, `content`, `updated_date`, `main_image_id`, `place_id`) VALUES
(1, 3, 1, '2017-09-17 15:26:34', 'Week-end Portes ouvertes Adoption', 'test', '2017-09-17 15:26:34', 55, NULL),
(10, NULL, 1, '2017-09-21 22:32:26', 'Jaffa', 'Publication sur Jaffa', '2017-09-22 00:00:00', 1, NULL),
(11, NULL, 1, '2017-09-25 08:35:34', 'Publication Test', 'Texte Publication Test', '2017-09-25 08:35:34', 56, NULL),
(12, NULL, 1, '2017-09-25 08:35:34', 'Publication Test', 'Texte Publication Test', '2017-09-25 08:35:34', 49, NULL),
(13, NULL, 1, '2017-09-25 08:35:34', 'Publication Test', 'Texte Publication Test', '2017-09-25 08:35:34', 57, NULL),
(14, NULL, 1, '2017-09-25 08:35:34', 'Publication Test', 'Texte Publication Test', '2017-09-25 08:35:34', NULL, NULL),
(15, NULL, 1, '2017-09-25 08:35:34', 'Publication Test', 'Texte Publication Test', '2017-09-25 08:35:34', NULL, NULL),
(16, NULL, 1, '2017-09-25 08:35:34', 'Publication Test', 'Texte Publication Test', '2017-09-25 08:35:34', NULL, NULL),
(17, NULL, 1, '2017-09-25 08:35:34', 'Publication Test', 'Texte Publication Test', '2017-09-25 08:35:34', NULL, NULL),
(18, NULL, 1, '2017-09-25 08:35:34', 'Publication Test', 'Texte Publication Test', '2017-09-25 08:35:34', NULL, NULL),
(19, NULL, 1, '2017-09-25 08:35:34', 'Publication Test', 'Texte Publication Test', '2017-09-25 08:35:34', NULL, NULL),
(20, NULL, 1, '2017-09-25 08:35:34', 'Publication Test', 'Texte Publication Test', '2017-09-25 08:35:34', NULL, NULL),
(21, NULL, 1, '2017-09-25 08:35:34', 'Publication Test', 'Texte Publication Test', '2017-09-25 08:35:34', NULL, NULL),
(22, 10, 1, '2017-09-26 11:47:30', 'Week-end Portes Ouvertes', 'Venez rencontrer les animaux à l\'adoption', '2017-09-26 11:47:30', 37, NULL),
(23, NULL, 1, '2017-09-27 21:42:21', 'Jaffa2', 'Animal perdu le 26/09 vers 19h. \r\nPorte collier rouge', '2017-09-27 00:00:00', 53, 1);

-- --------------------------------------------------------

--
-- Structure de la table `sex`
--

CREATE TABLE `sex` (
  `id` int(11) NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `sex`
--

INSERT INTO `sex` (`id`, `type`) VALUES
(2, 'femelle'),
(4, 'inconnu'),
(1, 'mâle');

-- --------------------------------------------------------

--
-- Structure de la table `state`
--

CREATE TABLE `state` (
  `id` int(11) NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `state`
--

INSERT INTO `state` (`id`, `type`) VALUES
(1, 'adoptable'),
(2, 'adopté'),
(4, 'perdu'),
(7, 'propriétaireretrouvé'),
(3, 'réservé'),
(6, 'retrouvé'),
(5, 'trouvé');

-- --------------------------------------------------------

--
-- Structure de la table `tag`
--

CREATE TABLE `tag` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `type`
--

CREATE TABLE `type` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `type`
--

INSERT INTO `type` (`id`, `name`) VALUES
(1, 'chat'),
(2, 'chien');

-- --------------------------------------------------------

--
-- Structure de la table `type_identification`
--

CREATE TABLE `type_identification` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `type_identification`
--

INSERT INTO `type_identification` (`id`, `name`) VALUES
(4, 'aucune'),
(3, 'inconnu'),
(1, 'puce électronique'),
(2, 'tatouage');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `password` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_pseudo` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `confirmation_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_already_requested` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`id`, `password`, `salt`, `roles`, `email`, `user_pseudo`, `is_active`, `confirmation_token`, `is_already_requested`) VALUES
(19, '$2y$13$J4D89LAIm1mNkoQN3ZaM0uI..enHK0w4RR937iFya8tN8lvmRjmO2', NULL, 'a:1:{i:0;s:7:"ROLE_FA";}', 'Mel_0634@msn.com', 'Test', 1, NULL, 0),
(20, '$2y$13$4FOxA3.upnFpjGUqjwbPYO8ahKdv374n5iwooDtkgrZ1qLNSUR6/G', NULL, 'a:1:{i:0;s:7:"ROLE_FA";}', 'Mel@msn.com', 'Testj', 1, NULL, 0),
(21, '$2y$13$isJ27QBwkYW9ifkMPxzEfeQxeTVjAD5dFwVr27wu5Z9AaRS6ASZ1G', NULL, 'a:1:{i:0;s:9:"ROLE_USER";}', 'test@test.com', 'segsfle', 1, NULL, 0),
(22, '$2y$13$Bo4cNR55N.d5mZP0LOkfw.QSQSVFdnTopJmU3wgnBRZA1SE1xBgTu', NULL, 'a:1:{i:0;s:9:"ROLE_USER";}', 'tescct@test.com', 'segsflecc', 1, NULL, 0),
(23, '$2y$13$q7LJTS8lOQDbC0XxeN2pNOhWQS3CBNwSOvFow4zu0LWZRa..nWdFC', NULL, 'a:1:{i:0;s:9:"ROLE_USER";}', 'a@a.fr', 'dsfqF', 1, NULL, 0),
(24, '$2y$13$epwWTIDC.j1hrCM9VIu7POwlEN26A7KuDgt3/SAkrKFPaqI.cAPeq', NULL, 'a:1:{i:0;s:7:"ROLE_FA";}', 'ccc@d.com', 'ccc', 1, NULL, 0),
(25, '$2y$13$7YSmFVl.DWSsivFxwjzxdeGmQQp816wY6MEalK/bf.1CK5dUYvWzy', NULL, 'a:1:{i:0;s:9:"ROLE_USER";}', 'dd@dd.dd', 'dssd', 1, NULL, 0),
(26, '$2y$13$6lxClakwMk86Z2mgJjrj4uiG9EMc5xlq8iEHlcPdRe7wZ1S4rXvSG', NULL, 'a:1:{i:0;s:9:"ROLE_USER";}', 'ddc@dd.dd', 'dssdvv', 1, NULL, 0),
(27, '$2y$13$und7t1ULqb/qD/Mx9tbK8.aPo7K0ni8XsS9YRmSz1XbffpoMUXdd2', NULL, 'a:1:{i:0;s:9:"ROLE_USER";}', 'ddnc@dd.dd', 'dssdvvn', 1, NULL, 0),
(28, '$2y$13$g1k2fk6Q05Vti54NyWQ21.AGkSteEmi6aPVbzC3lzOOZuMnhbgIuO', NULL, 'a:1:{i:0;s:9:"ROLE_USER";}', 'kkk@kkk.o', 'kkk', 1, NULL, 0),
(29, '$2y$13$/hBGKPE.nqtbjtAfbcXHAerNtkM4JSW1hRHrFFvOJd0YJgleDR8qy', NULL, 'a:1:{i:0;s:9:"ROLE_USER";}', 'tast@tast.com', 'tast', 1, NULL, 0),
(30, '$2y$13$sbF72ZbhSmwSwHG0LdVDUOWrUgOEfe8QX7HnXhARRST/SI5gRw7L2', NULL, 'a:1:{i:0;s:9:"ROLE_USER";}', 'taste@tast.com', 'taste', 1, NULL, 0),
(31, '$2y$13$T/WWjFVLodnC9rpG55j5ROhosJoKrD7lrjWsilkuY61/klkKyWgT.', NULL, 'a:1:{i:0;s:9:"ROLE_USER";}', 'tastec@tast.com', 'tastec', 1, NULL, 0),
(32, '$2y$13$UGhtd65ZUFdH8grnRvNg5uo.ognudunwBnMFWOTopank/z2BHEHdm', NULL, 'a:1:{i:0;s:9:"ROLE_USER";}', 'tasctec@tast.com', 'tacstec', 1, NULL, 0),
(33, '$2y$13$Xjf9tLIqvIQJUM8XWGLa8.XtpAaNVm8ULfLiRwXscDlLo4BWFTjw2', NULL, 'a:1:{i:0;s:9:"ROLE_USER";}', 'c@tast.com', 'tacscctec', 1, NULL, 0),
(34, '$2y$13$UfM/M.81g6aTAycvOK0DI.LhcMRVrYX7FOZUL6TUhPZlAsvBRW/Hy', NULL, 'a:1:{i:0;s:9:"ROLE_USER";}', 'dc@tast.com', 'tacsdcctec', 1, NULL, 0),
(35, '$2y$13$8f1N7mYJ7MzSHsuMInB8wu/0ry8RseoVDBA2MQYhgymxc8NEraBRG', NULL, 'a:1:{i:0;s:9:"ROLE_USER";}', 'djc@tast.com', 'k', 1, NULL, 0),
(36, '$2y$13$U/zvDuWcZ0bSmTdfVkzK.e7GsZCO8MIGQdZ862MwYfAQ5DjtBl/yK', NULL, 'a:1:{i:0;s:9:"ROLE_USER";}', 'SEG@sefs.com', 'fgzG', 1, NULL, 0),
(37, '$2y$13$SbxrVfmVLDhc2Txk1GzZxOrDaNSCUKfm1.Uy/Uyk3J2KAI/VDPQpm', NULL, 'a:1:{i:0;s:9:"ROLE_USER";}', 'SsEG@sefs.com', 'fgzGs', 1, NULL, 0),
(38, '$2y$13$v4W2tCDoNV8kB2tnfqjC9uD3/RdreB6VtjGgiw0VMVGUayGS/BsOK', NULL, 'a:1:{i:0;s:9:"ROLE_USER";}', 'SsEddG@sefs.com', 'fgzGsd', 1, NULL, 0),
(39, '$2y$13$jFt3KC6soA0bItEpMiSXLOmByssZ59kWMgObhFopwokGOvt3l/5im', NULL, 'a:1:{i:0;s:9:"ROLE_USER";}', 'ddd@ddd.dd', 'ddd', 1, NULL, 0),
(40, '$2y$13$3EOEshRTdXP3FwTmvHCUA.RH47h.oCKSUJ0oyBGdg2L9CDPL6eGY2', NULL, 'a:1:{i:0;s:9:"ROLE_USER";}', 'ddjd@ddd.dd', 'ddd;', 1, NULL, 0),
(41, '$2y$13$5a9ryji.Iyeg2ucVQhGX2.Z4lQMnzA9QPwDaANRyuZgc/2/kxwtWO', NULL, 'a:1:{i:0;s:9:"ROLE_USER";}', 'ddjdn@ddd.dd', 'ddd;k', 1, NULL, 0),
(42, '$2y$13$8fDh6MezQNZy/PM6LL5YkuRNW1HRgAMox3n1vWkK8CyH7SzlmBdeK', NULL, 'a:1:{i:0;s:9:"ROLE_USER";}', 'dddd@ddd.dd', 'dddd', 1, NULL, 0),
(43, '$2y$13$Ui5fy1IVE7swq903C8KIgeEJrPignNRcZuoAwhocypBZ1tJ0RzYAy', NULL, 'a:1:{i:0;s:7:"ROLE_FA";}', 'mel@mel.fr', 'mel', 1, NULL, 0),
(44, '$2y$13$yyEVlwj3U6Y0Ov66g2VVUeodBpcbL3YAFhLxjm38PBpkdktnQPJu2', NULL, 'a:1:{i:0;s:10:"ROLE_ADMIN";}', 'bousquet.melanie@gmail.com', 'Mélanie', 1, NULL, 0),
(45, '$2y$13$qEhjva.M7OyAgzTM3ls1Wee0QK4uUFg7MKd81ZDyZ32l9OT21w61u', NULL, 'a:1:{i:0;s:10:"ROLE_ADMIN";}', 'bousquetmelanie@gmail.com', 'MelanieBousquet', 1, NULL, 0);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `animal`
--
ALTER TABLE `animal`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_6AAB231F9A5375DD` (`identificationNumber`),
  ADD UNIQUE KEY `UNIQ_6AAB231FE4873418` (`main_image_id`),
  ADD KEY `IDX_6AAB231F5A2DB2A0` (`sex_id`),
  ADD KEY `IDX_6AAB231FA8B4A30F` (`breed_id`),
  ADD KEY `IDX_6AAB231FF1CF261E` (`type_identification_id`),
  ADD KEY `IDX_6AAB231FA76ED395` (`user_id`);

--
-- Index pour la table `animal_state`
--
ALTER TABLE `animal_state`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_82600B0F38B217A7` (`publication_id`),
  ADD KEY `IDX_82600B0F8E962C16` (`animal_id`),
  ADD KEY `IDX_82600B0F5D83CC1` (`state_id`);

--
-- Index pour la table `breed`
--
ALTER TABLE `breed`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_F8AF884F5E237E06` (`name`),
  ADD KEY `IDX_F8AF884FC54C8C93` (`type_id`);

--
-- Index pour la table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_64C19C15E237E06` (`name`);

--
-- Index pour la table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_9474526C38B217A7` (`publication_id`);

--
-- Index pour la table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `description`
--
ALTER TABLE `description`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_6DE440268E962C16` (`animal_id`);

--
-- Index pour la table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_C53D045F8E962C16` (`animal_id`);

--
-- Index pour la table `image_publication`
--
ALTER TABLE `image_publication`
  ADD PRIMARY KEY (`image_id`,`publication_id`),
  ADD KEY `IDX_60EA2CF73DA5256D` (`image_id`),
  ADD KEY `IDX_60EA2CF738B217A7` (`publication_id`);

--
-- Index pour la table `note`
--
ALTER TABLE `note`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_CFBDFA148E962C16` (`animal_id`),
  ADD KEY `IDX_CFBDFA14A76ED395` (`user_id`),
  ADD KEY `IDX_CFBDFA1438B217A7` (`publication_id`);

--
-- Index pour la table `page`
--
ALTER TABLE `page`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_140AB6203DA5256D` (`image_id`),
  ADD KEY `IDX_140AB62012469DE2` (`category_id`);

--
-- Index pour la table `place`
--
ALTER TABLE `place`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `publication`
--
ALTER TABLE `publication`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_AF3C677971F7E88B` (`event_id`),
  ADD UNIQUE KEY `UNIQ_AF3C6779DA6A219` (`place_id`),
  ADD KEY `IDX_AF3C6779E4873418` (`main_image_id`);

--
-- Index pour la table `sex`
--
ALTER TABLE `sex`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_EFA269F78CDE5729` (`type`);

--
-- Index pour la table `state`
--
ALTER TABLE `state`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_A393D2FB8CDE5729` (`type`);

--
-- Index pour la table `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_389B7835E237E06` (`name`);

--
-- Index pour la table `type`
--
ALTER TABLE `type`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8CDE57295E237E06` (`name`);

--
-- Index pour la table `type_identification`
--
ALTER TABLE `type_identification`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_1F245B995E237E06` (`name`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E39E52A0` (`user_pseudo`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `animal`
--
ALTER TABLE `animal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
--
-- AUTO_INCREMENT pour la table `animal_state`
--
ALTER TABLE `animal_state`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
--
-- AUTO_INCREMENT pour la table `breed`
--
ALTER TABLE `breed`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT pour la table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `description`
--
ALTER TABLE `description`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT pour la table `event`
--
ALTER TABLE `event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT pour la table `image`
--
ALTER TABLE `image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;
--
-- AUTO_INCREMENT pour la table `note`
--
ALTER TABLE `note`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT pour la table `page`
--
ALTER TABLE `page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT pour la table `place`
--
ALTER TABLE `place`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `publication`
--
ALTER TABLE `publication`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT pour la table `sex`
--
ALTER TABLE `sex`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `state`
--
ALTER TABLE `state`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pour la table `tag`
--
ALTER TABLE `tag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `type`
--
ALTER TABLE `type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `type_identification`
--
ALTER TABLE `type_identification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `animal`
--
ALTER TABLE `animal`
  ADD CONSTRAINT `FK_6AAB231F5A2DB2A0` FOREIGN KEY (`sex_id`) REFERENCES `sex` (`id`),
  ADD CONSTRAINT `FK_6AAB231FA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_6AAB231FA8B4A30F` FOREIGN KEY (`breed_id`) REFERENCES `breed` (`id`),
  ADD CONSTRAINT `FK_6AAB231FE4873418` FOREIGN KEY (`main_image_id`) REFERENCES `image` (`id`),
  ADD CONSTRAINT `FK_6AAB231FF1CF261E` FOREIGN KEY (`type_identification_id`) REFERENCES `type_identification` (`id`);

--
-- Contraintes pour la table `animal_state`
--
ALTER TABLE `animal_state`
  ADD CONSTRAINT `FK_82600B0F38B217A7` FOREIGN KEY (`publication_id`) REFERENCES `publication` (`id`),
  ADD CONSTRAINT `FK_82600B0F5D83CC1` FOREIGN KEY (`state_id`) REFERENCES `state` (`id`),
  ADD CONSTRAINT `FK_82600B0F8E962C16` FOREIGN KEY (`animal_id`) REFERENCES `animal` (`id`);

--
-- Contraintes pour la table `breed`
--
ALTER TABLE `breed`
  ADD CONSTRAINT `FK_F8AF884FC54C8C93` FOREIGN KEY (`type_id`) REFERENCES `type` (`id`);

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `FK_9474526C38B217A7` FOREIGN KEY (`publication_id`) REFERENCES `publication` (`id`);

--
-- Contraintes pour la table `description`
--
ALTER TABLE `description`
  ADD CONSTRAINT `FK_6DE440268E962C16` FOREIGN KEY (`animal_id`) REFERENCES `animal` (`id`);

--
-- Contraintes pour la table `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `FK_C53D045F8E962C16` FOREIGN KEY (`animal_id`) REFERENCES `animal` (`id`);

--
-- Contraintes pour la table `image_publication`
--
ALTER TABLE `image_publication`
  ADD CONSTRAINT `FK_60EA2CF738B217A7` FOREIGN KEY (`publication_id`) REFERENCES `publication` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_60EA2CF73DA5256D` FOREIGN KEY (`image_id`) REFERENCES `image` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `note`
--
ALTER TABLE `note`
  ADD CONSTRAINT `FK_CFBDFA1438B217A7` FOREIGN KEY (`publication_id`) REFERENCES `publication` (`id`),
  ADD CONSTRAINT `FK_CFBDFA148E962C16` FOREIGN KEY (`animal_id`) REFERENCES `animal` (`id`),
  ADD CONSTRAINT `FK_CFBDFA14A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `page`
--
ALTER TABLE `page`
  ADD CONSTRAINT `FK_140AB62012469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `FK_140AB6203DA5256D` FOREIGN KEY (`image_id`) REFERENCES `image` (`id`);

--
-- Contraintes pour la table `publication`
--
ALTER TABLE `publication`
  ADD CONSTRAINT `FK_AF3C677971F7E88B` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
  ADD CONSTRAINT `FK_AF3C6779DA6A219` FOREIGN KEY (`place_id`) REFERENCES `place` (`id`),
  ADD CONSTRAINT `FK_AF3C6779E4873418` FOREIGN KEY (`main_image_id`) REFERENCES `image` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
