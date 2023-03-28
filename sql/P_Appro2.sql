-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : db:3306
-- Généré le : mar. 28 mars 2023 à 06:55
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
CREATE DATABASE IF NOT EXISTS `P_Appro2` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `P_Appro2`;

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

INSERT INTO `t_teacher` (`idTeacher`, `teaFirstname`, `teaName`, `teaGender`, `teaNickname`, `teaOrigine`,`teaPhoto`,`fkSection`) VALUES
(1, 'Jean', 'Mi', 'A', 'JM', 'Surnom de Jean Mi','', 2),
(2, 'Jean', 'Mike', 'M', 'JMK', 'Surnom de Jean Mike','', 1),
(3, 'Jeanne', 'Mi', 'F', 'JMT', 'Surnom de Jeanne Mi','', 3),
(47, 'test1', 'test1', 'A', 'test1', 'test1','', 1);

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
(1, 'TestUser', 'User', 0),
(2, 'TestAdmin', 'Admin', 1);

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
  MODIFY `idTeacher` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT pour la table `t_user`
--
ALTER TABLE `t_user`
  MODIFY `idUser` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
