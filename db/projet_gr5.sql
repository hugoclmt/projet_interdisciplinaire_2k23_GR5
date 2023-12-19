-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 19, 2023 at 09:17 AM
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
-- Database: `projet_gr5`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `employes`
--

INSERT INTO `employes` (`id_employe`, `id_type`, `identifiant`, `mdp`, `admin`, `nbre_conges`) VALUES
(2, 3, 'quenon.nicolas', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 1, 1),
(3, 1, 'root.root', 'cf2e875d70c402e4aaf32ceb64b1fa6f7396af59', 1, 0),
(6, 4, 'test.test', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 0, 0);

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
  `nbre_heure` time NOT NULL,
  `conge` tinyint(1) NOT NULL,
  `congeconfirm` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `justification` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jour_horaire`
--

INSERT INTO `jour_horaire` (`id_horaire`, `id_employe`, `date`, `debut`, `fin`, `nbre_heure`, `conge`, `congeconfirm`, `justification`) VALUES
(1, 2, '2023-12-18', '08:15:00', '17:30:00', '09:15:00', 0, NULL, NULL),
(2, 2, '2023-12-19', '08:00:00', '17:00:00', '09:00:00', 1, NULL, 'J\'aimerai un congé ce jour là'),
(3, 2, '2023-12-20', '08:00:45', '15:00:45', '07:00:00', 1, NULL, 'J\'aimerai un congé aussi');

-- --------------------------------------------------------

--
-- Table structure for table `type`
--

CREATE TABLE `type` (
  `id_type` int NOT NULL,
  `nom_type` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `type`
--

INSERT INTO `type` (`id_type`, `nom_type`) VALUES
(1, 'ouvrier'),
(2, 'personnel_nettoyage'),
(3, 'jardinier'),
(4, 'maitre-nageur'),
(5, 'animateur');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `employes`
--
ALTER TABLE `employes`
  MODIFY `id_employe` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `jour_horaire`
--
ALTER TABLE `jour_horaire`
  MODIFY `id_horaire` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `type`
--
ALTER TABLE `type`
  MODIFY `id_type` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `employes`
--
ALTER TABLE `employes`
  ADD CONSTRAINT `fk_employes_type` FOREIGN KEY (`id_type`) REFERENCES `type` (`id_type`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `jour_horaire`
--
ALTER TABLE `jour_horaire`
  ADD CONSTRAINT `fk_horaire_employe` FOREIGN KEY (`id_employe`) REFERENCES `employes` (`id_employe`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
