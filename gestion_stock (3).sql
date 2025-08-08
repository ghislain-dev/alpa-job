-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 08 août 2025 à 22:17
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestion_stock`
--

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE `categorie` (
  `id_categorie` int(11) NOT NULL,
  `nom_categorie` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`id_categorie`, `nom_categorie`, `description`) VALUES
(1, 'juse', 'sans alcol'),
(5, 'Biere', '7%');

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

CREATE TABLE `client` (
  `id_client` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `pwd` varchar(255) DEFAULT NULL,
  `photo` varchar(255) NOT NULL,
  `numero` varchar(20) NOT NULL,
  `genre` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`id_client`, `nom`, `email`, `pwd`, `photo`, `numero`, `genre`) VALUES
(1, 'ghislain', 'ghislainmundeke0@gmail.com', '$2y$10$xs20ClKXZEb.tv5op6W43ukJJyadVS/iRUpsAOY1LwehMZ2uMX/2O', 'dafaut.png', '0976052184', 'homme');

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `id_commande` int(11) NOT NULL,
  `montant_total` double DEFAULT NULL,
  `statut_commande` varchar(50) DEFAULT NULL,
  `datecommande` date DEFAULT NULL,
  `id_client` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`id_commande`, `montant_total`, `statut_commande`, `datecommande`, `id_client`) VALUES
(1, 2474, 'payée', '2025-06-21', 1),
(2, 1237, 'annulée', '2025-06-21', 1),
(3, 2474, 'payée', '2025-06-22', 1),
(4, 25977, 'payée', '2025-06-22', 1),
(5, 4948, 'payée', '2025-06-22', 1),
(6, 29688, 'livrée', '2025-06-24', 1),
(7, 6185, 'livrée', '2025-06-24', 1),
(8, 4948, 'livrée', '2025-06-24', 1),
(9, 1237, 'payée', '2025-06-24', 1),
(10, 1237, 'payée', '2025-06-24', 1),
(11, 1237, 'livrée', '2025-06-24', 1),
(12, 1237, 'livrée', '2025-06-24', 1),
(13, 1237, 'livrée', '2025-06-24', 1),
(14, 2474, 'payée', '2025-06-26', 1),
(15, 1237, 'payée', '2025-06-26', 1),
(16, 86590, 'payée', '2025-06-26', 1),
(17, 3711, 'livrée', '2025-06-27', 1),
(18, 12370, 'payée', '2025-06-27', 1),
(19, 0, 'livrée', '2025-07-06', 1),
(20, 0, 'payée', '2025-07-06', 1),
(21, 0, 'payée', '2025-07-06', 1),
(22, 0, 'payée', '2025-07-06', 1),
(23, 0, 'payée', '2025-07-06', 1),
(24, 0, 'payée', '2025-07-22', 1),
(25, 0, 'en cours', '2025-07-22', 1),
(26, 0, 'annulée', '2025-08-07', 1);

-- --------------------------------------------------------

--
-- Structure de la table `details_commande`
--

CREATE TABLE `details_commande` (
  `id_detail` int(11) NOT NULL,
  `id_commande` int(11) DEFAULT NULL,
  `id_produit` int(11) DEFAULT NULL,
  `id_reapprovisionnement` int(11) DEFAULT NULL,
  `quantite` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `details_commande`
--

INSERT INTO `details_commande` (`id_detail`, `id_commande`, `id_produit`, `id_reapprovisionnement`, `quantite`) VALUES
(1, 1, 6, NULL, 2),
(2, 2, 8, NULL, 1),
(3, 3, 6, NULL, 1),
(4, 3, 8, NULL, 1),
(5, 4, 8, NULL, 21),
(6, 5, 8, NULL, 4),
(7, 6, 6, NULL, 23),
(8, 6, 8, NULL, 1),
(9, 7, 6, NULL, 5),
(10, 8, 6, NULL, 4),
(11, 9, 8, NULL, 1),
(12, 10, 8, NULL, 1),
(13, 11, 8, NULL, 1),
(14, 12, 6, NULL, 1),
(15, 13, 6, NULL, 1),
(16, 14, 6, NULL, 2),
(17, 15, 6, NULL, 1),
(18, 16, 6, NULL, 70),
(19, 17, 6, NULL, 3),
(20, 18, 6, NULL, 10),
(21, 19, 6, NULL, 1),
(22, 20, 6, NULL, 1),
(23, 21, 6, NULL, 1),
(24, 22, 6, NULL, 1),
(25, 23, 6, NULL, 1),
(26, 24, 6, NULL, 1),
(27, 25, 6, NULL, 3),
(28, 26, 10, NULL, 1),
(29, 26, 6, NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `details_commande_stock`
--

CREATE TABLE `details_commande_stock` (
  `id` int(11) NOT NULL,
  `id_detail` int(11) NOT NULL,
  `id_reapprovisionnement` int(11) NOT NULL,
  `quantite_utilisee` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `details_commande_stock`
--

INSERT INTO `details_commande_stock` (`id`, `id_detail`, `id_reapprovisionnement`, `quantite_utilisee`) VALUES
(1, 19, 21, 3),
(2, 20, 21, 10),
(3, 22, 21, 1),
(4, 21, 21, 1),
(5, 23, 21, 1),
(6, 24, 21, 1),
(7, 25, 21, 1),
(8, 26, 21, 1);

-- --------------------------------------------------------

--
-- Structure de la table `fonction`
--

CREATE TABLE `fonction` (
  `id_fonction` int(11) NOT NULL,
  `nom_fonction` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `fonction`
--

INSERT INTO `fonction` (`id_fonction`, `nom_fonction`, `description`) VALUES
(1, 'admin', 'l&#039;administrateur de l&#039;ntrepri'),
(3, 'comptable', 'il gere l&#039;argent'),
(4, 'adminnjnuhuz', 'l&#039;administrateur de l&#039;ntreprise ');

-- --------------------------------------------------------

--
-- Structure de la table `fournisseur`
--

CREATE TABLE `fournisseur` (
  `id_fournisseur` int(11) NOT NULL,
  `noms` varchar(100) DEFAULT NULL,
  `numero` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `fournisseur`
--

INSERT INTO `fournisseur` (`id_fournisseur`, `noms`, `numero`, `email`) VALUES
(1, 'ghislain', '0976052184', 'ghislainmundeke0@gmail.com'),
(2, 'justin ', '097605218', 'justin@gmail.com');

-- --------------------------------------------------------

--
-- Structure de la table `paiement`
--

CREATE TABLE `paiement` (
  `id_paiement` int(11) NOT NULL,
  `montant` double DEFAULT NULL,
  `devise` varchar(50) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `id_commande` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `paiement`
--

INSERT INTO `paiement` (`id_paiement`, `montant`, `devise`, `date`, `id_commande`) VALUES
(1, 2474, 'USD', '2025-06-22', 3),
(2, 25977, 'USD', '2025-06-22', 4),
(3, 4948, 'CDF', '2025-06-24', 5),
(4, 29688, 'CDF', '2025-06-24', 6),
(5, 6185, 'CDF', '2025-06-24', 7),
(6, 4948, 'CDF', '2025-06-24', 8),
(7, 1237, 'CDF', '2025-06-24', 9),
(8, 1237, 'CDF', '2025-06-24', 10),
(9, 1237, 'CDF', '2025-06-24', 11),
(10, 1237, 'CDF', '2025-06-24', 12),
(11, 1237, 'CDF', '2025-06-24', 13),
(12, 2474, 'CDF', '2025-06-26', 14),
(13, 1237, 'CDF', '2025-06-26', 15),
(14, 86590, 'CDF', '2025-06-26', 16),
(15, 3711, 'CDF', '2025-06-27', 17),
(16, 12370, 'USD', '2025-06-27', 18),
(17, 0, 'CDF', '2025-07-06', 20),
(18, 0, 'CDF', '2025-07-06', 19),
(19, 0, 'CDF', '2025-07-06', 21),
(20, 0, 'CDF', '2025-07-06', 22),
(21, 0, 'CDF', '2025-07-06', 23),
(22, 0, 'USD', '2025-07-22', 24);

-- --------------------------------------------------------

--
-- Structure de la table `paiement_reservation`
--

CREATE TABLE `paiement_reservation` (
  `id_paiement` int(11) NOT NULL,
  `id_reservation` int(11) NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  `date_paiement` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `paiement_reservation`
--

INSERT INTO `paiement_reservation` (`id_paiement`, `id_reservation`, `montant`, `date_paiement`) VALUES
(1, 10, 250.00, '2025-06-30 16:05:31');

-- --------------------------------------------------------

--
-- Structure de la table `prix`
--

CREATE TABLE `prix` (
  `id_prix` int(11) NOT NULL,
  `montant` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `prix`
--

INSERT INTO `prix` (`id_prix`, `montant`) VALUES
(2, 1237),
(3, 3500),
(4, 5000);

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `id_produit` int(11) NOT NULL,
  `nom_produit` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `id_categorie` int(11) DEFAULT NULL,
  `id_prix` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id_produit`, `nom_produit`, `image`, `id_categorie`, `id_prix`) VALUES
(6, 'simba', '8.png', 1, 2),
(7, 'afia', NULL, NULL, NULL),
(8, 'afi', 'index1_carousel.jpg', 1, 2),
(10, 'tembo', 'tembo.PNG', 5, 3);

-- --------------------------------------------------------

--
-- Structure de la table `reapprovisionnement`
--

CREATE TABLE `reapprovisionnement` (
  `id_reapprovisionnement` int(11) NOT NULL,
  `quantite_ajoutee` double DEFAULT NULL,
  `date_exp` date DEFAULT NULL,
  `statut` varchar(20) DEFAULT 'actif',
  `id_produit` int(11) DEFAULT NULL,
  `id_fournisseur` int(11) DEFAULT NULL,
  `date_entre` date DEFAULT NULL,
  `quantite_reste` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reapprovisionnement`
--

INSERT INTO `reapprovisionnement` (`id_reapprovisionnement`, `quantite_ajoutee`, `date_exp`, `statut`, `id_produit`, `id_fournisseur`, `date_entre`, `quantite_reste`) VALUES
(11, 6, '0000-00-00', 'actif', 8, 2, NULL, 0),
(19, 100, '2025-08-09', 'actif', 6, 1, '2025-06-21', 0),
(20, 200, '2025-10-26', 'actif', 6, 1, '2025-06-26', 0),
(21, 200, '2026-01-26', 'actif', 6, 1, '2025-06-26', 108),
(22, 1000, '2026-12-27', 'actif', 10, 1, '2025-08-05', 1000);

-- --------------------------------------------------------

--
-- Structure de la table `reservation`
--

CREATE TABLE `reservation` (
  `id_reservation` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `date` date DEFAULT NULL,
  `date_debut` datetime DEFAULT NULL,
  `date_fin` datetime DEFAULT NULL,
  `id_client` int(11) DEFAULT NULL,
  `id_salle` int(11) DEFAULT NULL,
  `statut` varchar(20) DEFAULT 'en cours',
  `date_reservation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reservation`
--

INSERT INTO `reservation` (`id_reservation`, `description`, `date`, `date_debut`, `date_fin`, `id_client`, `id_salle`, `statut`, `date_reservation`) VALUES
(1, NULL, '2025-06-28', '2025-06-30 16:30:00', '2025-06-30 20:12:00', NULL, NULL, 'en cours', '2025-06-28 20:06:20'),
(2, NULL, '2025-06-28', '2025-07-02 23:09:00', '2025-07-02 23:15:00', NULL, NULL, 'en cours', '2025-06-28 21:09:38'),
(3, 'fete de mariag', '2025-07-03', '2025-07-03 12:40:00', '2025-07-03 15:45:00', NULL, NULL, 'en cours', '2025-06-30 10:41:14'),
(4, 'fete de mariage', '2025-06-30', '2025-07-03 12:44:00', '2025-07-03 12:48:00', NULL, NULL, 'en cours', '2025-06-30 10:44:15'),
(5, 'fete de mariag', '2025-07-03', '2025-07-03 13:20:00', '2025-07-03 18:25:00', NULL, NULL, 'en cours', '2025-06-30 11:21:03'),
(6, 'fete de mariage', '2025-06-30', '2025-07-02 13:29:00', '2025-07-02 18:34:00', NULL, NULL, 'en cours', '2025-06-30 11:29:18'),
(7, 'fete de mariag', '2025-06-30', '2025-07-03 13:33:00', '2025-07-04 13:33:00', NULL, NULL, 'en cours', '2025-06-30 11:33:57'),
(8, 'mariag', '2025-06-30', '2025-06-30 13:49:00', '2025-07-05 20:49:00', NULL, 7, 'en cours', '2025-06-30 11:49:51'),
(10, 'mariage ', '2025-06-30', '2025-07-03 15:04:00', '2025-07-04 15:05:00', 1, 7, 'honoré', '2025-06-30 13:05:25'),
(11, 'batheme', '2025-08-01', '2025-08-01 00:01:00', '2025-08-06 00:01:00', 1, 7, 'honoré', '2025-06-30 22:01:24'),
(12, 'fete de mariage ', '2025-07-21', '2025-07-21 12:26:00', '2025-07-19 12:26:00', 1, 7, 'en cours', '2025-07-22 10:26:32'),
(13, 'ghu_', '2025-07-22', '2025-07-22 12:33:00', '2025-07-23 12:36:00', 1, 7, 'en cours', '2025-07-22 10:30:24'),
(14, 'fete d\'anniversaire ', '2025-07-22', '2025-07-25 14:50:00', '2025-07-23 14:50:00', 1, 7, 'payée', '2025-07-22 12:51:02'),
(15, 'fete de mariage ', '2025-07-22', '2025-07-23 15:40:00', '2025-07-24 15:40:00', 1, 7, 'en cours', '2025-07-22 13:40:25');

-- --------------------------------------------------------

--
-- Structure de la table `salle`
--

CREATE TABLE `salle` (
  `id_salle` int(11) NOT NULL,
  `nom_salle` varchar(100) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `capacite` int(11) DEFAULT NULL,
  `prix` decimal(10,2) DEFAULT 0.00,
  `disponible` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `salle`
--

INSERT INTO `salle` (`id_salle`, `nom_salle`, `photo`, `description`, `capacite`, `prix`, `disponible`) VALUES
(1, ' ghande ', NULL, NULL, NULL, 0.00, 1),
(7, 'salle 1', 'index3_carousel.jpg', 'une tres bonne salle pour les mariage ', 500, 500.00, 1);

-- --------------------------------------------------------

--
-- Structure de la table `stock`
--

CREATE TABLE `stock` (
  `id_stock` int(11) NOT NULL,
  `quantite_disponible` double DEFAULT NULL,
  `date_derniereMiseAJour` date DEFAULT NULL,
  `id_produit` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `stock`
--

INSERT INTO `stock` (`id_stock`, `quantite_disponible`, `date_derniereMiseAJour`, `id_produit`) VALUES
(1, 12, '2025-06-17', 6),
(2, 20, '2025-06-12', 6),
(3, 5, '2025-06-10', 7);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id_utilisateur` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `postnom` varchar(100) DEFAULT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `pwd` varchar(255) DEFAULT NULL,
  `numero` varchar(20) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `id_fonction` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `nom`, `postnom`, `prenom`, `email`, `pwd`, `numero`, `image`, `id_fonction`) VALUES
(2, 'katembo', 'gh', 'kkA', 'GH@gmainl.com', '$2y$10$elV9H28yr5AnhWhHKXexF.z9yGcPJDbK/UHnCtc9tK4AX9uCxvJka', '09584838', 'diagramme de cas d\'utilisation .png', 1),
(6, 'ghislain', 'mundeke', 'kasereaka', 'ghislaindev243@gmail.com', '$2y$10$ZCOhrbCAzEWGgbAjd338v.EgAWtn/tc76LFQgnDL12T8QxWNSQGR6', '243976052184', 'default.png', 1),
(7, 'justin', 'kambale', 'machozi', 'muyisa2003@gmail.com', '$2y$10$tZ7e667HD/JFeVUY2ieWAOFVVrjbq2y/7jxmG0LumgPeGLPk96a7i', '243976052184', 'user_687fc518083a4_20220516_210443.jpg', 3);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `vue_stock_fifo`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `vue_stock_fifo` (
`id_produit` int(11)
,`nom_produit` varchar(100)
,`image` varchar(255)
,`nom_categorie` varchar(100)
,`prix` double
,`total_entree` double
,`stock_expire` decimal(32,0)
,`stock_valide` decimal(32,0)
,`total_sortie` decimal(32,0)
,`stock_restant` decimal(32,0)
,`premiere_entree` date
,`derniere_entree` date
,`produit_epuise` int(1)
,`bientot_expire` int(1)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `vue_stock_fifoo`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `vue_stock_fifoo` (
`id_produit` int(11)
,`nom_produit` varchar(100)
,`image` varchar(255)
,`nom_categorie` varchar(100)
,`prix` double
,`stock_restant` double
,`premiere_entree` date
,`derniere_entree` date
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `vue_stock_produit`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `vue_stock_produit` (
`id_produit` int(11)
,`nom_produit` varchar(100)
,`image` varchar(255)
,`nom_categorie` varchar(100)
,`prix` double
,`quantite_disponible` double
,`date_derniereMiseAJour` date
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `vue_stock_reel`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `vue_stock_reel` (
`id_produit` int(11)
,`nom_produit` varchar(100)
,`image` varchar(255)
,`nom_categorie` varchar(100)
,`prix` double
,`quantite_totale` double
,`derniere_reception` date
);

-- --------------------------------------------------------

--
-- Structure de la vue `vue_stock_fifo`
--
DROP TABLE IF EXISTS `vue_stock_fifo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vue_stock_fifo`  AS SELECT `p`.`id_produit` AS `id_produit`, `p`.`nom_produit` AS `nom_produit`, `p`.`image` AS `image`, `c`.`nom_categorie` AS `nom_categorie`, `pr`.`montant` AS `prix`, ifnull(sum(`r`.`quantite_ajoutee`),0) AS `total_entree`, ifnull(sum(case when `r`.`date_exp` is not null and to_days(`r`.`date_exp`) - to_days(curdate()) < 0 then `r`.`quantite_reste` else 0 end),0) AS `stock_expire`, ifnull(sum(case when `r`.`date_exp` is null or to_days(`r`.`date_exp`) - to_days(curdate()) >= 0 then `r`.`quantite_reste` else 0 end),0) AS `stock_valide`, (select ifnull(sum(`d`.`quantite`),0) from `details_commande` `d` where `d`.`id_produit` = `p`.`id_produit`) AS `total_sortie`, ifnull(sum(`r`.`quantite_reste`),0) AS `stock_restant`, (select min(`r2`.`date_entre`) from `reapprovisionnement` `r2` where `r2`.`id_produit` = `p`.`id_produit`) AS `premiere_entree`, (select max(`r3`.`date_entre`) from `reapprovisionnement` `r3` where `r3`.`id_produit` = `p`.`id_produit`) AS `derniere_entree`, CASE WHEN ifnull(sum(`r`.`quantite_reste`),0) = 0 THEN 1 ELSE 0 END AS `produit_epuise`, CASE WHEN sum(case when `r`.`date_exp` is not null AND to_days(`r`.`date_exp`) - to_days(curdate()) between 0 and 7 then 1 else 0 end) > 0 THEN 1 ELSE 0 END AS `bientot_expire` FROM (((`produit` `p` left join `reapprovisionnement` `r` on(`r`.`id_produit` = `p`.`id_produit`)) left join `categorie` `c` on(`p`.`id_categorie` = `c`.`id_categorie`)) left join `prix` `pr` on(`p`.`id_produit` = `pr`.`id_prix` and `r`.`statut` = 1)) GROUP BY `p`.`id_produit`, `p`.`nom_produit`, `p`.`image`, `c`.`nom_categorie`, `pr`.`montant` ;

-- --------------------------------------------------------

--
-- Structure de la vue `vue_stock_fifoo`
--
DROP TABLE IF EXISTS `vue_stock_fifoo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vue_stock_fifoo`  AS SELECT `p`.`id_produit` AS `id_produit`, `p`.`nom_produit` AS `nom_produit`, `p`.`image` AS `image`, `c`.`nom_categorie` AS `nom_categorie`, `pr`.`montant` AS `prix`, sum(`r`.`quantite_ajoutee`) - ifnull(`v`.`total_sortie`,0) AS `stock_restant`, min(`r`.`date_entre`) AS `premiere_entree`, max(`r`.`date_entre`) AS `derniere_entree` FROM ((((`produit` `p` join `reapprovisionnement` `r` on(`p`.`id_produit` = `r`.`id_produit`)) join `categorie` `c` on(`p`.`id_categorie` = `c`.`id_categorie`)) join `prix` `pr` on(`p`.`id_prix` = `pr`.`id_prix`)) left join (select `d`.`id_produit` AS `id_produit`,sum(`d`.`quantite`) AS `total_sortie` from (`commande` `c` join `details_commande` `d` on(`c`.`id_commande` = `d`.`id_commande`)) where `c`.`statut_commande` = 'payée' group by `d`.`id_produit`) `v` on(`p`.`id_produit` = `v`.`id_produit`)) GROUP BY `p`.`id_produit`, `p`.`nom_produit`, `p`.`image`, `c`.`nom_categorie`, `pr`.`montant` ;

-- --------------------------------------------------------

--
-- Structure de la vue `vue_stock_produit`
--
DROP TABLE IF EXISTS `vue_stock_produit`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vue_stock_produit`  AS SELECT `p`.`id_produit` AS `id_produit`, `p`.`nom_produit` AS `nom_produit`, `p`.`image` AS `image`, `c`.`nom_categorie` AS `nom_categorie`, `pr`.`montant` AS `prix`, `s`.`quantite_disponible` AS `quantite_disponible`, `s`.`date_derniereMiseAJour` AS `date_derniereMiseAJour` FROM (((`produit` `p` join `stock` `s` on(`p`.`id_produit` = `s`.`id_produit`)) join `categorie` `c` on(`p`.`id_categorie` = `c`.`id_categorie`)) join `prix` `pr` on(`p`.`id_prix` = `pr`.`id_prix`)) ;

-- --------------------------------------------------------

--
-- Structure de la vue `vue_stock_reel`
--
DROP TABLE IF EXISTS `vue_stock_reel`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vue_stock_reel`  AS SELECT `p`.`id_produit` AS `id_produit`, `p`.`nom_produit` AS `nom_produit`, `p`.`image` AS `image`, `c`.`nom_categorie` AS `nom_categorie`, `pr`.`montant` AS `prix`, sum(`r`.`quantite_ajoutee`) AS `quantite_totale`, max(`r`.`date_entre`) AS `derniere_reception` FROM (((`produit` `p` join `reapprovisionnement` `r` on(`p`.`id_produit` = `r`.`id_produit`)) join `categorie` `c` on(`p`.`id_categorie` = `c`.`id_categorie`)) join `prix` `pr` on(`p`.`id_prix` = `pr`.`id_prix`)) GROUP BY `p`.`id_produit`, `p`.`nom_produit`, `p`.`image`, `c`.`nom_categorie`, `pr`.`montant` ;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id_categorie`);

--
-- Index pour la table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id_client`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id_commande`),
  ADD KEY `id_client` (`id_client`);

--
-- Index pour la table `details_commande`
--
ALTER TABLE `details_commande`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_commande` (`id_commande`),
  ADD KEY `id_produit` (`id_produit`),
  ADD KEY `fk_detail_reappro` (`id_reapprovisionnement`);

--
-- Index pour la table `details_commande_stock`
--
ALTER TABLE `details_commande_stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_detail` (`id_detail`),
  ADD KEY `id_reapprovisionnement` (`id_reapprovisionnement`);

--
-- Index pour la table `fonction`
--
ALTER TABLE `fonction`
  ADD PRIMARY KEY (`id_fonction`);

--
-- Index pour la table `fournisseur`
--
ALTER TABLE `fournisseur`
  ADD PRIMARY KEY (`id_fournisseur`);

--
-- Index pour la table `paiement`
--
ALTER TABLE `paiement`
  ADD PRIMARY KEY (`id_paiement`),
  ADD KEY `id_commande` (`id_commande`);

--
-- Index pour la table `paiement_reservation`
--
ALTER TABLE `paiement_reservation`
  ADD PRIMARY KEY (`id_paiement`),
  ADD KEY `id_reservation` (`id_reservation`);

--
-- Index pour la table `prix`
--
ALTER TABLE `prix`
  ADD PRIMARY KEY (`id_prix`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id_produit`),
  ADD KEY `id_categorie` (`id_categorie`),
  ADD KEY `fk_id_prix` (`id_prix`);

--
-- Index pour la table `reapprovisionnement`
--
ALTER TABLE `reapprovisionnement`
  ADD PRIMARY KEY (`id_reapprovisionnement`),
  ADD KEY `id_produit` (`id_produit`),
  ADD KEY `id_fournisseur` (`id_fournisseur`);

--
-- Index pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`id_reservation`),
  ADD KEY `id_client` (`id_client`),
  ADD KEY `id_salle` (`id_salle`);

--
-- Index pour la table `salle`
--
ALTER TABLE `salle`
  ADD PRIMARY KEY (`id_salle`);

--
-- Index pour la table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`id_stock`),
  ADD KEY `id_produit` (`id_produit`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_utilisateur`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `id_categorie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `client`
--
ALTER TABLE `client`
  MODIFY `id_client` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `id_commande` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT pour la table `details_commande`
--
ALTER TABLE `details_commande`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT pour la table `details_commande_stock`
--
ALTER TABLE `details_commande_stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `fonction`
--
ALTER TABLE `fonction`
  MODIFY `id_fonction` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `fournisseur`
--
ALTER TABLE `fournisseur`
  MODIFY `id_fournisseur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `paiement`
--
ALTER TABLE `paiement`
  MODIFY `id_paiement` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pour la table `paiement_reservation`
--
ALTER TABLE `paiement_reservation`
  MODIFY `id_paiement` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `prix`
--
ALTER TABLE `prix`
  MODIFY `id_prix` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `id_produit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `reapprovisionnement`
--
ALTER TABLE `reapprovisionnement`
  MODIFY `id_reapprovisionnement` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pour la table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id_reservation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `salle`
--
ALTER TABLE `salle`
  MODIFY `id_salle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `stock`
--
ALTER TABLE `stock`
  MODIFY `id_stock` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `commande_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `client` (`id_client`);

--
-- Contraintes pour la table `details_commande`
--
ALTER TABLE `details_commande`
  ADD CONSTRAINT `details_commande_ibfk_1` FOREIGN KEY (`id_commande`) REFERENCES `commande` (`id_commande`),
  ADD CONSTRAINT `details_commande_ibfk_2` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id_produit`),
  ADD CONSTRAINT `fk_detail_reappro` FOREIGN KEY (`id_reapprovisionnement`) REFERENCES `reapprovisionnement` (`id_reapprovisionnement`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `details_commande_stock`
--
ALTER TABLE `details_commande_stock`
  ADD CONSTRAINT `details_commande_stock_ibfk_1` FOREIGN KEY (`id_detail`) REFERENCES `details_commande` (`id_detail`) ON DELETE CASCADE,
  ADD CONSTRAINT `details_commande_stock_ibfk_2` FOREIGN KEY (`id_reapprovisionnement`) REFERENCES `reapprovisionnement` (`id_reapprovisionnement`) ON DELETE CASCADE;

--
-- Contraintes pour la table `paiement`
--
ALTER TABLE `paiement`
  ADD CONSTRAINT `paiement_ibfk_1` FOREIGN KEY (`id_commande`) REFERENCES `commande` (`id_commande`);

--
-- Contraintes pour la table `paiement_reservation`
--
ALTER TABLE `paiement_reservation`
  ADD CONSTRAINT `paiement_reservation_ibfk_1` FOREIGN KEY (`id_reservation`) REFERENCES `reservation` (`id_reservation`) ON DELETE CASCADE;

--
-- Contraintes pour la table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `fk_id_prix` FOREIGN KEY (`id_prix`) REFERENCES `prix` (`id_prix`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `produit_ibfk_1` FOREIGN KEY (`id_categorie`) REFERENCES `categorie` (`id_categorie`);

--
-- Contraintes pour la table `reapprovisionnement`
--
ALTER TABLE `reapprovisionnement`
  ADD CONSTRAINT `reapprovisionnement_ibfk_1` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id_produit`),
  ADD CONSTRAINT `reapprovisionnement_ibfk_2` FOREIGN KEY (`id_fournisseur`) REFERENCES `fournisseur` (`id_fournisseur`);

--
-- Contraintes pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `client` (`id_client`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservation_ibfk_2` FOREIGN KEY (`id_salle`) REFERENCES `salle` (`id_salle`) ON DELETE CASCADE;

--
-- Contraintes pour la table `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `stock_ibfk_1` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id_produit`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
