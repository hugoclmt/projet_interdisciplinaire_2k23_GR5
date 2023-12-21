-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 21, 2023 at 04:08 PM
-- Server version: 8.2.0
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `testinterdisciplinaire`
--

-- --------------------------------------------------------

--
-- Table structure for table `conge`
--

CREATE TABLE `conge` (
  `id_conge` int NOT NULL,
  `date_conge` date DEFAULT NULL,
  `congeconfirm` varchar(50) DEFAULT NULL,
  `justification` varchar(50) DEFAULT NULL,
  `id_employe` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `employes`
--

CREATE TABLE `employes` (
  `id_employe` int NOT NULL,
  `id_type` int NOT NULL,
  `identifiant` varchar(256) NOT NULL,
  `mdp` varchar(512) NOT NULL,
  `admin` tinyint(1) NOT NULL,
  `nbre_conges` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `employes`
--

INSERT INTO `employes` (`id_employe`, `id_type`, `identifiant`, `mdp`, `admin`, `nbre_conges`) VALUES
(1, 1, 'florian.alaert', 'a544f49a44e1b75ef70d54123611651dae4cc59d2575a53670c1a6d691ef5dc1', 0, 0),
(2, 1, 'valentin.barbencon', 'a544f49a44e1b75ef70d54123611651dae4cc59d2575a53670c1a6d691ef5dc1', 0, 0),
(3, 2, 'arnaud.boucaut', 'a544f49a44e1b75ef70d54123611651dae4cc59d2575a53670c1a6d691ef5dc1', 0, 0),
(4, 2, 'sebastien.buze', 'a544f49a44e1b75ef70d54123611651dae4cc59d2575a53670c1a6d691ef5dc1', 0, 0),
(5, 3, 'adrien.debuisson', 'a544f49a44e1b75ef70d54123611651dae4cc59d2575a53670c1a6d691ef5dc1', 0, 0),
(6, 3, 'françois.debay', 'a544f49a44e1b75ef70d54123611651dae4cc59d2575a53670c1a6d691ef5dc1', 0, 0),
(7, 4, 'mathieu.agrillo', 'a544f49a44e1b75ef70d54123611651dae4cc59d2575a53670c1a6d691ef5dc1', 0, 0),
(8, 4, 'thomas.lymans', 'a544f49a44e1b75ef70d54123611651dae4cc59d2575a53670c1a6d691ef5dc1', 0, 0),
(9, 5, 'jules.dubois', 'a544f49a44e1b75ef70d54123611651dae4cc59d2575a53670c1a6d691ef5dc1', 0, 0),
(10, 5, 'martin.moreau', 'a544f49a44e1b75ef70d54123611651dae4cc59d2575a53670c1a6d691ef5dc1', 0, 0),
(11, 6, 'admin', 'a544f49a44e1b75ef70d54123611651dae4cc59d2575a53670c1a6d691ef5dc1', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `jour_horaire`
--

CREATE TABLE `jour_horaire` (
  `id_horaire` int NOT NULL,
  `id_employe` int NOT NULL,
  `date` date NOT NULL,
  `debut` time NOT NULL,
  `fin` time NOT NULL,
  `nbre_heure` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `type`
--

CREATE TABLE `type` (
  `id_type` int NOT NULL,
  `nom_type` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `type`
--

INSERT INTO `type` (`id_type`, `nom_type`) VALUES
(1, 'animateur'),
(2, 'jardinier'),
(3, 'maitre_nageur'),
(4, 'Nettoyage'),
(5, 'ouvrier'),
(6, 'administrateur');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `conge`
--
ALTER TABLE `conge`
  ADD PRIMARY KEY (`id_conge`),
  ADD KEY `id_employe` (`id_employe`);

--
-- Indexes for table `employes`
--
ALTER TABLE `employes`
  ADD PRIMARY KEY (`id_employe`),
  ADD KEY `fk_employes_type` (`id_type`);

--
-- Indexes for table `jour_horaire`
--
ALTER TABLE `jour_horaire`
  ADD PRIMARY KEY (`id_horaire`),
  ADD KEY `fk_horaire_employe` (`id_employe`);

--
-- Indexes for table `type`
--
ALTER TABLE `type`
  ADD PRIMARY KEY (`id_type`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `conge`
--
ALTER TABLE `conge`
  MODIFY `id_conge` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `employes`
--
ALTER TABLE `employes`
  MODIFY `id_employe` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `jour_horaire`
--
ALTER TABLE `jour_horaire`
  MODIFY `id_horaire` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `type`
--
ALTER TABLE `type`
  MODIFY `id_type` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `employes`
--
ALTER TABLE `employes`
  ADD CONSTRAINT `fk_employes_type` FOREIGN KEY (`id_type`) REFERENCES `type` (`id_type`);

--
-- Constraints for table `jour_horaire`
--
ALTER TABLE `jour_horaire`
  ADD CONSTRAINT `fk_horaire_employe` FOREIGN KEY (`id_employe`) REFERENCES `employes` (`id_employe`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
