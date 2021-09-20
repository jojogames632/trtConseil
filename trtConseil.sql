-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : lun. 20 sep. 2021 à 17:07
-- Version du serveur :  10.4.17-MariaDB
-- Version de PHP : 8.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `trtConseil`
--

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20210909121510', '2021-09-09 12:15:18', 362),
('DoctrineMigrations\\Version20210909122217', '2021-09-09 12:22:25', 632);

-- --------------------------------------------------------

--
-- Structure de la table `job`
--

CREATE TABLE `job` (
  `id` int(11) NOT NULL,
  `recruiter_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `place` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `schedule_start` time NOT NULL,
  `schedule_end` time NOT NULL,
  `salary` int(11) NOT NULL,
  `year_experience_required` int(11) NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_valid` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `job`
--

INSERT INTO `job` (`id`, `recruiter_id`, `title`, `place`, `schedule_start`, `schedule_end`, `salary`, `year_experience_required`, `description`, `is_valid`) VALUES
(4, 7, 'Developpeur full stack junior', 'Poitiers', '08:00:00', '18:00:00', 30000, 0, 'Exploiter les langages de programmation afin de répondre aux besoin de l\'entreprise. tout en assurant la sécurité et les règles de bonnes pratiques. En collaboration avec le chef de projet, vous monterez en compétences afin de devenir un meilleur développeur.', 1),
(5, 7, 'developpeur javascript / react js full time', 'Chattellerault', '06:00:00', '17:00:00', 2100, 0, 'Exploiter les langages de programmation afin de répondre aux besoin de l\'entreprise. tout en assurant la sécurité et les règles de bonnes pratiques. En collaboration avec le chef de projet, vous monterez en compétences afin de devenir un meilleur développeur.', 0),
(6, 7, 'Développeur web full stack junior', 'a', '00:00:00', '00:00:00', 1, 0, 'a', 1),
(7, 7, 'développeur php/symfony', 'poitiers', '08:00:00', '18:00:00', 2000, 0, 'comme à la maison', 1),
(23, 7, 'test', 'test', '00:00:00', '00:00:00', 0, 0, 'test', 0),
(24, 7, 'a', 'a', '00:00:00', '00:00:00', 0, 1, 'a', 0),
(25, 7, 'aaa', 'aaa', '00:00:00', '00:00:00', 0, 0, 'aaa', 0),
(26, 7, 'aaa', 'aaa', '00:00:00', '00:00:00', 0, 0, 'aaa', 0),
(27, 7, 'aaaa', 'a', '00:00:00', '00:00:00', 0, 0, 'aaa', 0),
(28, 7, 'a', 'a', '00:00:00', '00:00:00', 0, 0, 'a', 0);

-- --------------------------------------------------------

--
-- Structure de la table `pending_job_request`
--

CREATE TABLE `pending_job_request` (
  `id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `candidate_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `pending_job_request`
--

INSERT INTO `pending_job_request` (`id`, `job_id`, `candidate_id`) VALUES
(60, 6, 5);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `company` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cv_filename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `is_active`, `company`, `address`, `first_name`, `last_name`, `cv_filename`) VALUES
(3, 'jojogames@free.fr', '[\"ROLE_ADMIN\"]', '$2y$13$Tv2h1oLBgk512ax/a/9MP.ZAD1qKau4D3HXdRpTueNpwLy1rL/OSm', 1, NULL, NULL, NULL, NULL, NULL),
(4, 'consultant@gmail.com', '[\"ROLE_CONSULTANT\"]', '$2y$13$jg7KkoDjHPflFbprMsGUgu5f8KjPhq3ewzy0AnopGLuG2dEMeyJp6', 1, NULL, NULL, NULL, NULL, NULL),
(5, 'candidat@gmail.com', '[\"ROLE_CANDIDATE\"]', '$2y$13$SyDRJEeiJ.r6w5UbRL25Zu8rAHFVDkkr4TOqO/nAD/RfCV47.4Gke', 1, NULL, NULL, 'Jonathan', 'Viault', 'Evaluation-1-GDWFSRMDAWREXAIII1A-6148461312064.pdf'),
(7, 'recruteur@gmail.com', '[\"ROLE_RECRUITER\"]', '$2y$13$MD94CU5MS0zDWed1Wn06ze4z0NOT.4znnu3xp4eR./YCtTbxCWj.6', 1, 'farming is life', 'blaaaaaaa', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `valid_job_request`
--

CREATE TABLE `valid_job_request` (
  `id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `candidate_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `valid_job_request`
--

INSERT INTO `valid_job_request` (`id`, `job_id`, `candidate_id`) VALUES
(14, 4, 5);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `job`
--
ALTER TABLE `job`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_FBD8E0F8156BE243` (`recruiter_id`);

--
-- Index pour la table `pending_job_request`
--
ALTER TABLE `pending_job_request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_FADCB7C0BE04EA9` (`job_id`),
  ADD KEY `IDX_FADCB7C091BD8781` (`candidate_id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- Index pour la table `valid_job_request`
--
ALTER TABLE `valid_job_request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_F25CF4E3BE04EA9` (`job_id`),
  ADD KEY `IDX_F25CF4E391BD8781` (`candidate_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `job`
--
ALTER TABLE `job`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT pour la table `pending_job_request`
--
ALTER TABLE `pending_job_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT pour la table `valid_job_request`
--
ALTER TABLE `valid_job_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `job`
--
ALTER TABLE `job`
  ADD CONSTRAINT `FK_FBD8E0F8156BE243` FOREIGN KEY (`recruiter_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `pending_job_request`
--
ALTER TABLE `pending_job_request`
  ADD CONSTRAINT `FK_FADCB7C091BD8781` FOREIGN KEY (`candidate_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_FADCB7C0BE04EA9` FOREIGN KEY (`job_id`) REFERENCES `job` (`id`);

--
-- Contraintes pour la table `valid_job_request`
--
ALTER TABLE `valid_job_request`
  ADD CONSTRAINT `FK_F25CF4E391BD8781` FOREIGN KEY (`candidate_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_F25CF4E3BE04EA9` FOREIGN KEY (`job_id`) REFERENCES `job` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
