-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 20 déc. 2023 à 19:42
-- Version du serveur :  5.7.31
-- Version de PHP : 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `testinterdisciplinaire`
--

-- --------------------------------------------------------

--
-- Structure de la table `conge`
--

DROP TABLE IF EXISTS `conge`;
CREATE TABLE IF NOT EXISTS `conge` (
  `id_conge` int(50) NOT NULL AUTO_INCREMENT,
  `date_conge` date DEFAULT NULL,
  `congeconfirm` varchar(50) DEFAULT NULL,
  `justification` varchar(50) DEFAULT NULL,
  `id_employe` int(50) NOT NULL,
  PRIMARY KEY (`id_conge`),
  KEY `id_employe` (`id_employe`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `conge`
--

INSERT INTO `conge` (`id_conge`, `date_conge`, `congeconfirm`, `justification`, `id_employe`) VALUES
(2, '2023-12-23', 'Refusé', 'test', 3),
(3, '2023-12-22', 'Refusé', 'test2', 3),
(4, '2023-12-23', 'Refusé', 'testbehxhzc', 3),
(5, '2023-12-30', 'Refusé', 'cdcd', 3);

-- --------------------------------------------------------

--
-- Structure de la table `employes`
--

DROP TABLE IF EXISTS `employes`;
CREATE TABLE IF NOT EXISTS `employes` (
  `id_employe` int(11) NOT NULL AUTO_INCREMENT,
  `id_type` int(11) NOT NULL,
  `identifiant` varchar(256) NOT NULL,
  `mdp` varchar(512) NOT NULL,
  `admin` tinyint(1) NOT NULL,
  `nbre_conges` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_employe`),
  KEY `fk_employes_type` (`id_type`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `employes`
--

INSERT INTO `employes` (`id_employe`, `id_type`, `identifiant`, `mdp`, `admin`, `nbre_conges`) VALUES
(2, 3, 'quenon.nicolas', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 1, 14),
(3, 1, 'root.root', 'cf2e875d70c402e4aaf32ceb64b1fa6f7396af59', 1, 13),
(6, 4, 'test.test', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 0, 13);

-- --------------------------------------------------------

--
-- Structure de la table `jour_horaire`
--

DROP TABLE IF EXISTS `jour_horaire`;
CREATE TABLE IF NOT EXISTS `jour_horaire` (
  `id_horaire` int(11) NOT NULL AUTO_INCREMENT,
  `id_employe` int(11) NOT NULL,
  `date` date NOT NULL,
  `debut` time NOT NULL,
  `fin` time NOT NULL,
  `nbre_heure` time NOT NULL,
  PRIMARY KEY (`id_horaire`),
  KEY `fk_horaire_employe` (`id_employe`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `jour_horaire`
--

INSERT INTO `jour_horaire` (`id_horaire`, `id_employe`, `date`, `debut`, `fin`, `nbre_heure`) VALUES
(1, 2, '2023-12-18', '08:15:00', '17:30:00', '09:15:00'),
(2, 2, '2023-12-19', '08:00:00', '17:00:00', '09:00:00'),
(3, 2, '2023-12-20', '08:00:45', '15:00:45', '07:00:00'),
(4, 3, '2023-12-21', '12:21:49', '19:21:49', '07:21:49'),
(5, 3, '2023-12-21', '21:04:00', '22:04:00', '00:00:01');

-- --------------------------------------------------------

--
-- Structure de la table `type`
--

DROP TABLE IF EXISTS `type`;
CREATE TABLE IF NOT EXISTS `type` (
  `id_type` int(11) NOT NULL AUTO_INCREMENT,
  `nom_type` varchar(256) NOT NULL,
  PRIMARY KEY (`id_type`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `type`
--

INSERT INTO `type` (`id_type`, `nom_type`) VALUES
(1, 'ouvrier'),
(2, 'personnel_nettoyage'),
(3, 'jardinier'),
(4, 'maitre-nageur'),
(5, 'animateur');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `employes`
--
ALTER TABLE `employes`
  ADD CONSTRAINT `fk_employes_type` FOREIGN KEY (`id_type`) REFERENCES `type` (`id_type`);

--
-- Contraintes pour la table `jour_horaire`
--
ALTER TABLE `jour_horaire`
  ADD CONSTRAINT `fk_horaire_employe` FOREIGN KEY (`id_employe`) REFERENCES `employes` (`id_employe`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
