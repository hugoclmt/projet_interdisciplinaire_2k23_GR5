-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 20, 2023 at 03:58 PM
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
-- Table structure for table `conges`
--

CREATE TABLE `conges` (
  `id_conge` int NOT NULL,
  `id_employe` int NOT NULL,
  `date` date DEFAULT NULL,
  `conge` tinyint(1) NOT NULL DEFAULT '0',
  `congeconfirm` tinyint(1) DEFAULT NULL,
  `justification` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `conges`
--

INSERT INTO `conges` (`id_conge`, `id_employe`, `date`, `conge`, `congeconfirm`, `justification`) VALUES
(1, 2, '2023-12-19', 1, 1, 'test'),
(6, 2, '2023-12-21', 1, 0, 'test'),
(7, 2, '2023-12-20', 1, NULL, NULL),
(8, 2, '2023-12-24', 1, 1, 'test'),
(10, 2, '2023-12-22', 1, 1, 'test');

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
(2, 1, 'nicolas.quenon', '9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08', 1, 12),
(3, 1, 'root.root', '4813494d137e1631bba301d5acab6e7bb7aa74ce1185d456565ef51d737677b2', 1, 9),
(6, 4, 'test.test', '9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08', 0, 9),
(7, 1, 'user.user', '04f8996da763b7a969b1028ee3007569eaf3a635486ddab211d512c85b9df8fb', 0, 0);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jour_horaire`
--

INSERT INTO `jour_horaire` (`id_horaire`, `id_employe`, `date`, `debut`, `fin`, `nbre_heure`) VALUES
(2, 2, '2023-12-19', '08:00:00', '17:00:00', '09:00:00'),
(3, 2, '2023-12-20', '08:00:45', '15:00:45', '07:00:00'),
(5, 2, '2023-12-20', '16:39:00', '18:39:00', '02:00:00'),
(6, 3, '2023-12-20', '14:11:00', '21:11:00', '07:00:00'),
(8, 3, '2024-01-02', '16:38:00', '20:39:00', '04:01:00'),
(9, 2, '2023-12-25', '15:40:00', '16:40:00', '01:00:00'),
(11, 2, '2023-12-28', '16:49:00', '18:49:00', '02:00:00'),
(12, 2, '2023-12-22', '15:55:00', '18:56:00', '03:01:00'),
(13, 2, '2023-12-27', '16:00:00', '18:03:00', '02:03:00');

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
-- Indexes for table `conges`
--
ALTER TABLE `conges`
  ADD PRIMARY KEY (`id_conge`),
  ADD KEY `fk_conges_employes` (`id_employe`);

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
-- AUTO_INCREMENT for table `conges`
--
ALTER TABLE `conges`
  MODIFY `id_conge` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `employes`
--
ALTER TABLE `employes`
  MODIFY `id_employe` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `jour_horaire`
--
ALTER TABLE `jour_horaire`
  MODIFY `id_horaire` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `type`
--
ALTER TABLE `type`
  MODIFY `id_type` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `conges`
--
ALTER TABLE `conges`
  ADD CONSTRAINT `fk_conges_employes` FOREIGN KEY (`id_employe`) REFERENCES `employes` (`id_employe`) ON DELETE RESTRICT ON UPDATE RESTRICT;

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
