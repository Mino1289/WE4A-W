-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 14 mars 2024 à 22:00
-- Version du serveur : 10.4.24-MariaDB
-- Version de PHP : 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `w`
--

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

CREATE TABLE `comment` (
  `ID` int(11) NOT NULL,
  `ID_post` int(11) NOT NULL,
  `ID_user` int(11) NOT NULL,
  `content` varchar(300) NOT NULL,
  `date` date NOT NULL,
  `isSensible` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `dislikecomment`
--

CREATE TABLE `dislikecomment` (
  `ID` int(11) NOT NULL,
  `ID_user` int(11) NOT NULL,
  `ID_comment` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `dislikepost`
--

CREATE TABLE `dislikepost` (
  `ID` int(11) NOT NULL,
  `ID_user` int(11) NOT NULL,
  `ID_post` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `follow`
--

CREATE TABLE `follow` (
  `ID` int(11) NOT NULL,
  `ID_user` int(11) NOT NULL,
  `ID_followed` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `likecomment`
--

CREATE TABLE `likecomment` (
  `ID` int(11) NOT NULL,
  `ID_user` int(11) NOT NULL,
  `ID_comment` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `likepost`
--

CREATE TABLE `likepost` (
  `ID` int(11) NOT NULL,
  `ID_user` int(11) NOT NULL,
  `ID_post` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `post`
--

CREATE TABLE `post` (
  `ID` int(11) NOT NULL,
  `ID_user` int(11) NOT NULL,
  `content` varchar(300) NOT NULL,
  `date` date NOT NULL,
  `isSensible` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `ID` int(11) NOT NULL,
  `username` varchar(25) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `birth_date` date NOT NULL,
  `profile_picture` mediumblob NOT NULL,
  `isWarn` tinyint(1) NOT NULL,
  `isAdmin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_post` (`ID_post`,`ID_user`),
  ADD KEY `ID_user` (`ID_user`);

--
-- Index pour la table `dislikecomment`
--
ALTER TABLE `dislikecomment`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_user` (`ID_user`,`ID_comment`),
  ADD KEY `ID_comment` (`ID_comment`);

--
-- Index pour la table `dislikepost`
--
ALTER TABLE `dislikepost`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_user` (`ID_user`,`ID_post`),
  ADD KEY `ID_post` (`ID_post`);

--
-- Index pour la table `follow`
--
ALTER TABLE `follow`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_user` (`ID_user`,`ID_followed`),
  ADD KEY `ID_followed` (`ID_followed`);

--
-- Index pour la table `likecomment`
--
ALTER TABLE `likecomment`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_user` (`ID_user`,`ID_comment`),
  ADD KEY `ID_comment` (`ID_comment`);

--
-- Index pour la table `likepost`
--
ALTER TABLE `likepost`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_user` (`ID_user`,`ID_post`),
  ADD KEY `ID_post` (`ID_post`);

--
-- Index pour la table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_user` (`ID_user`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `comment`
--
ALTER TABLE `comment`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `dislikecomment`
--
ALTER TABLE `dislikecomment`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `dislikepost`
--
ALTER TABLE `dislikepost`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `follow`
--
ALTER TABLE `follow`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `likecomment`
--
ALTER TABLE `likecomment`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `likepost`
--
ALTER TABLE `likepost`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `post`
--
ALTER TABLE `post`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`ID_user`) REFERENCES `user` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`ID_post`) REFERENCES `post` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `dislikecomment`
--
ALTER TABLE `dislikecomment`
  ADD CONSTRAINT `dislikecomment_ibfk_1` FOREIGN KEY (`ID_user`) REFERENCES `user` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `dislikecomment_ibfk_2` FOREIGN KEY (`ID_comment`) REFERENCES `comment` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `dislikepost`
--
ALTER TABLE `dislikepost`
  ADD CONSTRAINT `dislikepost_ibfk_1` FOREIGN KEY (`ID_user`) REFERENCES `user` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `dislikepost_ibfk_2` FOREIGN KEY (`ID_post`) REFERENCES `post` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `follow`
--
ALTER TABLE `follow`
  ADD CONSTRAINT `follow_ibfk_1` FOREIGN KEY (`ID_user`) REFERENCES `user` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `follow_ibfk_2` FOREIGN KEY (`ID_followed`) REFERENCES `user` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `likecomment`
--
ALTER TABLE `likecomment`
  ADD CONSTRAINT `likecomment_ibfk_1` FOREIGN KEY (`ID_user`) REFERENCES `user` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `likecomment_ibfk_2` FOREIGN KEY (`ID_comment`) REFERENCES `comment` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `likepost`
--
ALTER TABLE `likepost`
  ADD CONSTRAINT `likepost_ibfk_1` FOREIGN KEY (`ID_user`) REFERENCES `user` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `likepost_ibfk_2` FOREIGN KEY (`ID_post`) REFERENCES `post` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`ID_user`) REFERENCES `user` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
