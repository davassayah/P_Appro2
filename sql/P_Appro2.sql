-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : db:3306
-- Généré le : ven. 28 avr. 2023 à 12:12
-- Version du serveur : 8.0.30
-- Version de PHP : 8.0.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `P_Appro2`
--

-- --------------------------------------------------------

--
-- Structure de la table `t_section`
--

CREATE TABLE `t_section` (
  `idSection` int NOT NULL,
  `secName` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `t_section`
--

INSERT INTO `t_section` (`idSection`, `secName`) VALUES
(1, 'Bois'),
(2, 'Mécanique'),
(3, 'Informatique');

-- --------------------------------------------------------

--
-- Structure de la table `t_teacher`
--

CREATE TABLE `t_teacher` (
  `idTeacher` int NOT NULL,
  `teaFirstname` varchar(50) NOT NULL,
  `teaName` varchar(50) NOT NULL,
  `teaGender` char(1) NOT NULL,
  `teaNickname` varchar(50) NOT NULL,
  `teaOrigine` text NOT NULL,
  `teaPhoto` varchar(75) NOT NULL,
  `fkSection` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `t_teacher`
--

INSERT INTO `t_teacher` (`idTeacher`, `teaFirstname`, `teaName`, `teaGender`, `teaNickname`, `teaOrigine`, `teaPhoto`, `fkSection`) VALUES
(77, 'dqwd', 'WQDDWQ', 'M', 'DEWDEEW', 'DDDD', '\\img\\photos\\Img_1.jpg', 2),
(82, 'test2', 'test2', 'M', 'test2', 'dddd', '\\img\\photos\\Img_78.jpg', 3),
(83, 'test3', 'test3', 'M', 'test3', 'test3', '\\img\\photos\\Img_83.png', 3),
(84, 'toto', 'toto', 'A', 'toto', 'toto', '\\img\\photos\\Img_84.png', 3),
(85, 'tutu', 'tutu', 'M', 'tutu', 'utu', '\\img\\photos\\Img_85.png', 2),
(86, 'dddd', 'dddd', 'F', 'lol', 'dddd', '\\img\\photos\\Img_86.jpg', 2),
(87, 'dddd', 'dddd', 'M', 'ddd', 'ddddd', '\\img\\photos\\Img_87.jpg', 3),
(88, 'dddddd', 'dddddd', 'M', 'dddddd', 'ddddd', '\\img\\photos\\Img_88.jpg', 1),
(89, 'dddddddd', 'eeee', 'M', 'eeeeeee', 'eeeeeee', '\\img\\photos\\Img_89.jpg', 2),
(90, 'ddddddddddd', 'ddddddddd', 'M', 'ddddddddd', 'eeeeeeee', '\\img\\photos\\Img_90.jpg', 1),
(91, 'dededeed', 'dededeed', 'M', 'dddd', '3333', '\\img\\photos\\Img_91.jpg', 2);

-- --------------------------------------------------------

--
-- Structure de la table `t_user`
--

CREATE TABLE `t_user` (
  `idUser` int NOT NULL,
  `useLogin` varchar(20) NOT NULL,
  `usePassword` varchar(255) NOT NULL,
  `useAdministrator` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `t_user`
--

INSERT INTO `t_user` (`idUser`, `useLogin`, `usePassword`, `useAdministrator`) VALUES
(3, 'Admin', '$2y$10$xDZ32AMJRIMUXbCgRrToKOP90rCqVBnGHYqfULfqHfZ.fVaAyiukq', 1),
(4, 'User', '$2y$10$fBUdxF/vjd4kZ1QGvbu9B.QqZaHRMJwY7/sSvRX5LDqheWgG0mG2W', 2),
(6, 'Intrus', '$2y$10$T3WSOjtbGLWbb4q8CdCWx.KBLejtmU4yPz3e6HVE5PQ.YzFDBXxdy', 2);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `t_section`
--
ALTER TABLE `t_section`
  ADD PRIMARY KEY (`idSection`);

--
-- Index pour la table `t_teacher`
--
ALTER TABLE `t_teacher`
  ADD PRIMARY KEY (`idTeacher`),
  ADD KEY `fkSection` (`fkSection`);

--
-- Index pour la table `t_user`
--
ALTER TABLE `t_user`
  ADD PRIMARY KEY (`idUser`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `t_section`
--
ALTER TABLE `t_section`
  MODIFY `idSection` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `t_teacher`
--
ALTER TABLE `t_teacher`
  MODIFY `idTeacher` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT pour la table `t_user`
--
ALTER TABLE `t_user`
  MODIFY `idUser` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `t_teacher`
--
ALTER TABLE `t_teacher`
  ADD CONSTRAINT `t_teacher_ibfk_1` FOREIGN KEY (`fkSection`) REFERENCES `t_section` (`idSection`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
