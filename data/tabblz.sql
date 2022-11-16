-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 09 nov. 2022 à 09:02
-- Version du serveur : 5.7.36
-- Version de PHP : 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `tabblz`
--

-- --------------------------------------------------------

--
-- Structure de la table `account`
--

DROP TABLE IF EXISTS `account`;
CREATE TABLE IF NOT EXISTS `account` (
  `Id_account` varchar(255) NOT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `adress` varchar(255) DEFAULT NULL,
  `cp` int(11) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `login` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT NULL,
  `Id_appUser` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Id_account`),
  UNIQUE KEY `Id_appUser` (`Id_appUser`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `appuser`
--

DROP TABLE IF EXISTS `appuser`;
CREATE TABLE IF NOT EXISTS `appuser` (
  `Id_appUser` varchar(255) NOT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT NULL,
  `Id_role` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Id_appUser`),
  KEY `Id_role` (`Id_role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `command`
--

DROP TABLE IF EXISTS `command`;
CREATE TABLE IF NOT EXISTS `command` (
  `Id_command` varchar(255) NOT NULL,
  `products` varchar(255) DEFAULT NULL,
  `price` decimal(19,4) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT NULL,
  `Id_appUser` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Id_command`),
  KEY `Id_appUser` (`Id_appUser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

DROP TABLE IF EXISTS `comment`;
CREATE TABLE IF NOT EXISTS `comment` (
  `Id_comment` varchar(255) NOT NULL,
  `content` varchar(255) DEFAULT NULL,
  `release_date` datetime DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `is_approved` tinyint(1) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT NULL,
  `Id_meal` varchar(255) DEFAULT NULL,
  `Id_appUser` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Id_comment`),
  KEY `Id_meal` (`Id_meal`),
  KEY `Id_appUser` (`Id_appUser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `meal`
--

DROP TABLE IF EXISTS `meal`;
CREATE TABLE IF NOT EXISTS `meal` (
  `Id_meal` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `number` int(11) DEFAULT NULL,
  `price` decimal(15,2) DEFAULT NULL,
  `release_date` datetime DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT NULL,
  `Id_command` varchar(255) DEFAULT NULL,
  `Id_appUser` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Id_meal`),
  UNIQUE KEY `Id_command` (`Id_command`),
  KEY `Id_appUser` (`Id_appUser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

DROP TABLE IF EXISTS `role`;
CREATE TABLE IF NOT EXISTS `role` (
  `Id_role` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `is__deleted` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`Id_role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `account`
--
ALTER TABLE `account`
  ADD CONSTRAINT `account_ibfk_1` FOREIGN KEY (`Id_appUser`) REFERENCES `appuser` (`Id_appUser`);

--
-- Contraintes pour la table `appuser`
--
ALTER TABLE `appuser`
  ADD CONSTRAINT `appuser_ibfk_1` FOREIGN KEY (`Id_role`) REFERENCES `role` (`Id_role`);

--
-- Contraintes pour la table `command`
--
ALTER TABLE `command`
  ADD CONSTRAINT `command_ibfk_1` FOREIGN KEY (`Id_appUser`) REFERENCES `appuser` (`Id_appUser`);

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`Id_meal`) REFERENCES `meal` (`Id_meal`),
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`Id_appUser`) REFERENCES `appuser` (`Id_appUser`);

--
-- Contraintes pour la table `meal`
--
ALTER TABLE `meal`
  ADD CONSTRAINT `meal_ibfk_1` FOREIGN KEY (`Id_command`) REFERENCES `command` (`Id_command`),
  ADD CONSTRAINT `meal_ibfk_2` FOREIGN KEY (`Id_appUser`) REFERENCES `appuser` (`Id_appUser`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
