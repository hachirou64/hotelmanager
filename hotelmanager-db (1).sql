-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : jeu. 30 avr. 2026 à 07:21
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `hotelmanager-db`
--

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE `clients` (
  `id_client` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `adresse_email` varchar(255) NOT NULL,
  `telephone` varchar(255) NOT NULL,
  `historique_sejours` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`historique_sejours`)),
  `preferences` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`preferences`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `clients`
--

INSERT INTO `clients` (`id_client`, `user_id`, `nom`, `prenom`, `adresse_email`, `telephone`, `historique_sejours`, `preferences`, `created_at`, `updated_at`) VALUES
(4, NULL, 'Bio', '', 'bio1@gmail.com', '01 64 35 74 36', NULL, NULL, '2025-11-26 08:55:46', '2025-11-26 08:55:46'),
(5, NULL, 'hachirou', '', 'hachirou1@gmail.com', '01 69 16 21 07', NULL, NULL, '2025-11-26 09:28:38', '2025-11-26 09:28:38'),
(6, NULL, 'latifou', '', 'latifou@gmail.com', '01 69 16 21 07', NULL, NULL, '2025-11-26 10:24:52', '2025-11-26 10:24:52'),
(7, NULL, 'chawal', '', 'chawal@gmail.com', '01 64 08 44 84', NULL, NULL, '2025-11-26 12:54:58', '2025-11-26 12:54:58'),
(8, NULL, 'igor', '', 'igor1@gmail.com', '0199852029', NULL, NULL, '2025-11-27 16:29:36', '2025-11-27 16:29:36'),
(9, NULL, 'hachirou', '', 'hachirou@gmail.com', '01 69 16 21 07', NULL, NULL, '2025-11-28 06:28:35', '2025-11-28 06:28:35'),
(10, NULL, 'hachirou', '', 'hachirou2@gmail.com', '01 69 16 21 07', NULL, NULL, '2025-11-28 07:28:29', '2025-11-28 07:28:29'),
(11, NULL, 'houzerou', '', 'houzerou1@gmail.com', '01 44 54 84 75', NULL, NULL, '2025-11-29 01:51:21', '2025-11-29 01:51:21'),
(12, NULL, 'User', 'Admin', 'admin@hotel.com', '01 64 35 74 36', NULL, NULL, '2025-11-29 01:56:13', '2025-11-29 01:56:13'),
(13, NULL, 'baparape', '', 'izibath@gmail.com', '01 69 16 21 07', NULL, NULL, '2025-11-29 02:01:22', '2025-11-29 02:01:22'),
(14, 23, 'Ali', '', 'ali@gmail.com', '0199852029', NULL, NULL, '2025-11-29 02:31:23', '2025-11-29 02:31:23'),
(15, NULL, 'baparape', 'hachirou', 'hachiroubaparape@gmail.com', '01 69 16 21 07', NULL, NULL, '2026-04-24 13:51:08', '2026-04-24 13:51:08');

-- --------------------------------------------------------

--
-- Structure de la table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `message`, `created_at`, `updated_at`) VALUES
(1, 'ADAM houzerou', 'houzerou@gmail.com', 'je veux savoir plus sur lapplication', '2025-11-23 16:38:12', '2025-11-23 16:38:12'),
(2, 'Hachirou BAPARAPE', 'hachiroubaparape@gmail.com', 'bonsoir je veux reserver une chambre pour deux personnes', '2025-11-26 12:40:01', '2025-11-26 12:40:01');

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `hotel_parameters`
--

CREATE TABLE `hotel_parameters` (
  `id_parametre` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `valeur` text NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `invoices`
--

CREATE TABLE `invoices` (
  `id_facture` bigint(20) UNSIGNED NOT NULL,
  `id_reservation` bigint(20) UNSIGNED NOT NULL,
  `id_client` bigint(20) UNSIGNED NOT NULL,
  `date_facture` date NOT NULL,
  `montant_total` decimal(10,2) NOT NULL,
  `statut_paiement` enum('payée','impayée') NOT NULL DEFAULT 'impayée',
  `export_format` enum('PDF','Excel') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `invoices`
--

INSERT INTO `invoices` (`id_facture`, `id_reservation`, `id_client`, `date_facture`, `montant_total`, `statut_paiement`, `export_format`, `created_at`, `updated_at`) VALUES
(1, 5, 8, '2025-11-27', 50.00, 'impayée', NULL, '2025-11-27 16:31:24', '2025-11-27 16:31:24'),
(2, 6, 9, '2025-11-28', 150.00, 'impayée', NULL, '2025-11-28 06:28:50', '2025-11-28 06:28:50'),
(3, 7, 10, '2025-11-28', 400.00, 'impayée', NULL, '2025-11-28 07:28:42', '2025-11-28 07:28:42'),
(8, 10, 13, '2025-11-29', 150.00, 'impayée', NULL, '2025-11-29 02:04:38', '2025-11-29 02:04:38'),
(9, 11, 14, '2025-11-29', 150.00, 'impayée', NULL, '2025-11-29 02:32:08', '2025-11-29 02:32:08'),
(10, 12, 9, '2025-12-04', 50.00, 'impayée', NULL, '2025-12-04 17:09:27', '2025-12-04 17:09:27'),
(11, 13, 9, '2025-12-04', 150.00, 'impayée', NULL, '2025-12-04 17:42:43', '2025-12-04 17:42:43'),
(12, 15, 9, '2025-12-04', 150.00, 'impayée', NULL, '2025-12-04 17:49:02', '2025-12-04 17:49:02'),
(13, 16, 9, '2025-12-04', 50.00, 'impayée', NULL, '2025-12-04 18:07:16', '2025-12-04 18:07:16'),
(14, 17, 9, '2025-12-04', 50.00, 'impayée', NULL, '2025-12-04 18:29:10', '2025-12-04 18:29:10'),
(15, 18, 7, '2025-12-15', 150.00, 'impayée', NULL, '2025-12-15 10:07:48', '2025-12-15 10:07:48');

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_10_01_000000_create_roles_table', 1),
(5, '2024_10_01_000001_add_role_id_to_users_table', 1),
(6, '2024_10_01_000002_create_room_types_table', 1),
(7, '2024_10_01_000003_create_rooms_table', 1),
(8, '2024_10_01_000004_create_clients_table', 1),
(9, '2024_10_01_000005_create_reservations_table', 1),
(10, '2024_10_01_000006_create_personnel_table', 1),
(11, '2024_10_01_000007_create_invoices_table', 1),
(12, '2024_10_01_000008_create_payments_table', 1),
(13, '2024_10_01_000009_create_promotions_table', 1),
(14, '2024_10_01_000010_create_hotel_parameters_table', 1),
(15, '2025_11_13_020104_create_personal_access_tokens_table', 1),
(16, '2025_11_18_025032_add_preferences_to_users_table', 1),
(17, '2024_10_01_100000_create_contact_messages_table', 2),
(18, '2025_11_20_190246_add_user_id_to_clients_table', 2),
(19, '2025_11_26_000000_add_reserved_status_to_rooms', 3),
(20, '2025_11_26_000002_update_payments_add_momo_fields', 3),
(21, '2025_12_04_174412_make_id_client_nullable_in_reservations_table', 4);

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `payments`
--

CREATE TABLE `payments` (
  `id_paiement` bigint(20) UNSIGNED NOT NULL,
  `id_facture` bigint(20) UNSIGNED NOT NULL,
  `date_paiement` date NOT NULL,
  `montant_paye` decimal(10,2) NOT NULL,
  `mode_paiement` enum('CB','espèces','virement','MOMO') DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `status` enum('pending','success','failed') NOT NULL DEFAULT 'pending',
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `payments`
--

INSERT INTO `payments` (`id_paiement`, `id_facture`, `date_paiement`, `montant_paye`, `mode_paiement`, `transaction_id`, `provider`, `status`, `metadata`, `created_at`, `updated_at`) VALUES
(1, 1, '2025-11-27', 0.00, 'MOMO', 'MTN-SIM-1764264684-7184', 'momo', 'pending', '{\"simulated\":true,\"provider\":\"mtn\",\"phone\":\"@old(\'telephone\')\",\"amount\":50,\"callback\":\"http:\\/\\/127.0.0.1:8000\\/payments\\/webhook\"}', '2025-11-27 16:31:24', '2025-11-27 16:31:24'),
(2, 1, '2025-11-27', 0.00, 'MOMO', 'MTN-SIM-1764264716-4756', 'momo', 'pending', '{\"simulated\":true,\"provider\":\"mtn\",\"phone\":\"@old(\'telephone\')\",\"amount\":50,\"callback\":\"http:\\/\\/127.0.0.1:8000\\/payments\\/webhook\"}', '2025-11-27 16:31:56', '2025-11-27 16:31:56'),
(3, 1, '2025-11-27', 0.00, 'MOMO', 'MTN-SIM-1764265016-1483', 'momo', 'pending', '{\"simulated\":true,\"provider\":\"mtn\",\"phone\":\"@old(\'telephone\')\",\"amount\":50,\"callback\":\"http:\\/\\/127.0.0.1:8000\\/payments\\/webhook\"}', '2025-11-27 16:36:56', '2025-11-27 16:36:56'),
(4, 2, '2025-11-28', 0.00, 'MOMO', 'MTN-SIM-1764314930-3257', 'momo', 'pending', '{\"simulated\":true,\"provider\":\"mtn\",\"phone\":\"@old(\'telephone\')\",\"amount\":150,\"callback\":\"http:\\/\\/127.0.0.1:8000\\/payments\\/webhook\"}', '2025-11-28 06:28:50', '2025-11-28 06:28:50'),
(5, 2, '2025-11-28', 0.00, 'MOMO', 'MTN-SIM-1764314932-5186', 'momo', 'pending', '{\"simulated\":true,\"provider\":\"mtn\",\"phone\":\"@old(\'telephone\')\",\"amount\":150,\"callback\":\"http:\\/\\/127.0.0.1:8000\\/payments\\/webhook\"}', '2025-11-28 06:28:52', '2025-11-28 06:28:52'),
(6, 2, '2025-11-28', 0.00, 'MOMO', 'MTN-SIM-1764314936-2177', 'momo', 'pending', '{\"simulated\":true,\"provider\":\"mtn\",\"phone\":\"@old(\'telephone\')\",\"amount\":150,\"callback\":\"http:\\/\\/127.0.0.1:8000\\/payments\\/webhook\"}', '2025-11-28 06:28:56', '2025-11-28 06:28:56'),
(7, 2, '2025-11-28', 0.00, 'MOMO', 'MTN-SIM-1764314936-8406', 'momo', 'pending', '{\"simulated\":true,\"provider\":\"mtn\",\"phone\":\"@old(\'telephone\')\",\"amount\":150,\"callback\":\"http:\\/\\/127.0.0.1:8000\\/payments\\/webhook\"}', '2025-11-28 06:28:56', '2025-11-28 06:28:56'),
(8, 2, '2025-11-28', 0.00, 'MOMO', 'MTN-SIM-1764314936-7970', 'momo', 'pending', '{\"simulated\":true,\"provider\":\"mtn\",\"phone\":\"@old(\'telephone\')\",\"amount\":150,\"callback\":\"http:\\/\\/127.0.0.1:8000\\/payments\\/webhook\"}', '2025-11-28 06:28:56', '2025-11-28 06:28:56'),
(9, 2, '2025-11-28', 0.00, 'MOMO', 'MTN-SIM-1764314940-8830', 'momo', 'pending', '{\"simulated\":true,\"provider\":\"mtn\",\"phone\":\"@old(\'telephone\')\",\"amount\":150,\"callback\":\"http:\\/\\/127.0.0.1:8000\\/payments\\/webhook\"}', '2025-11-28 06:29:00', '2025-11-28 06:29:00'),
(10, 2, '2025-11-28', 0.00, 'MOMO', 'MTN-SIM-1764314969-4083', 'momo', 'pending', '{\"simulated\":true,\"provider\":\"mtn\",\"phone\":\"@old(\'telephone\')\",\"amount\":150,\"callback\":\"http:\\/\\/127.0.0.1:8000\\/payments\\/webhook\"}', '2025-11-28 06:29:29', '2025-11-28 06:29:29'),
(11, 3, '2025-11-28', 0.00, 'MOMO', 'MTN-SIM-1764318522-5325', 'momo', 'pending', '{\"simulated\":true,\"provider\":\"mtn\",\"phone\":\"@old(\'telephone\')\",\"amount\":400,\"callback\":\"http:\\/\\/127.0.0.1:8000\\/payments\\/webhook\"}', '2025-11-28 07:28:42', '2025-11-28 07:28:42'),
(16, 8, '2025-11-29', 0.00, 'MOMO', 'MTN-SIM-1764385478-3161', 'momo', 'pending', '{\"simulated\":true,\"provider\":\"mtn\",\"phone\":\"0169162107\",\"amount\":150,\"callback\":\"http:\\/\\/127.0.0.1:8000\\/payments\\/webhook\"}', '2025-11-29 02:04:38', '2025-11-29 02:04:38'),
(17, 8, '2025-11-29', 0.00, 'MOMO', 'MTN-SIM-1764385872-4330', 'momo', 'pending', '{\"simulated\":true,\"provider\":\"mtn\",\"phone\":\"0169162107\",\"amount\":150,\"callback\":\"http:\\/\\/127.0.0.1:8000\\/payments\\/webhook\"}', '2025-11-29 02:11:12', '2025-11-29 02:11:12'),
(18, 9, '2025-11-29', 0.00, 'MOMO', 'MTN-SIM-1764387129-5428', 'momo', 'pending', '{\"simulated\":true,\"provider\":\"mtn\",\"phone\":\"0169162107\",\"amount\":150,\"callback\":\"http:\\/\\/127.0.0.1:8000\\/payments\\/webhook\"}', '2025-11-29 02:32:08', '2025-11-29 02:32:09'),
(19, 10, '2025-12-04', 0.00, 'MOMO', 'CELTIS-SIM-1764871768-5951', 'momo', 'pending', '{\"simulated\":true,\"provider\":\"celtis\",\"phone\":\"@old(\'telephone\')\",\"amount\":50,\"callback\":\"http:\\/\\/127.0.0.1:8000\\/payments\\/webhook\"}', '2025-12-04 17:09:28', '2025-12-04 17:09:28'),
(20, 11, '2025-12-04', 0.00, 'MOMO', 'CELTIS-SIM-1764873763-7847', 'momo', 'pending', '{\"simulated\":true,\"provider\":\"celtis\",\"phone\":\"@old(\'telephone\')\",\"amount\":150,\"callback\":\"http:\\/\\/127.0.0.1:8000\\/payments\\/webhook\"}', '2025-12-04 17:42:43', '2025-12-04 17:42:43'),
(21, 11, '2025-12-04', 0.00, 'MOMO', 'MTN-SIM-1764873782-6342', 'momo', 'pending', '{\"simulated\":true,\"provider\":\"mtn\",\"phone\":\"0169162107\",\"amount\":150,\"callback\":\"http:\\/\\/127.0.0.1:8000\\/payments\\/webhook\"}', '2025-12-04 17:43:02', '2025-12-04 17:43:02'),
(22, 12, '2025-12-04', 0.00, 'MOMO', 'MTN-SIM-1764874142-3914', 'momo', 'pending', '{\"simulated\":true,\"provider\":\"mtn\",\"phone\":\"0169162107\",\"amount\":150,\"callback\":\"http:\\/\\/127.0.0.1:8000\\/payments\\/webhook\"}', '2025-12-04 17:49:02', '2025-12-04 17:49:02'),
(23, 13, '2025-12-04', 0.00, 'MOMO', 'MTN-SIM-1764875237-3753', 'momo', 'pending', '{\"simulated\":true,\"provider\":\"mtn\",\"phone\":\"0169162107\",\"amount\":50,\"callback\":\"http:\\/\\/127.0.0.1:8000\\/payments\\/webhook\"}', '2025-12-04 18:07:16', '2025-12-04 18:07:17'),
(24, 14, '2025-12-04', 0.00, 'MOMO', 'CELTIS-SIM-1764876550-1137', 'momo', 'pending', '{\"simulated\":true,\"provider\":\"celtis\",\"phone\":\"@old(\'telephone\')\",\"amount\":50,\"callback\":\"http:\\/\\/127.0.0.1:8000\\/payments\\/webhook\"}', '2025-12-04 18:29:10', '2025-12-04 18:29:13'),
(25, 15, '2025-12-15', 0.00, 'MOMO', 'MTN-SIM-1765796868-1101', 'momo', 'pending', '{\"simulated\":true,\"provider\":\"mtn\",\"phone\":\"0169162107\",\"amount\":150,\"callback\":\"http:\\/\\/127.0.0.1:8001\\/payments\\/webhook\"}', '2025-12-15 10:07:48', '2025-12-15 10:07:48'),
(26, 15, '2025-12-15', 0.00, 'MOMO', 'CELTIS-SIM-1765796872-2293', 'momo', 'pending', '{\"simulated\":true,\"provider\":\"celtis\",\"phone\":\"@old(\'telephone\')\",\"amount\":150,\"callback\":\"http:\\/\\/127.0.0.1:8001\\/payments\\/webhook\"}', '2025-12-15 10:07:52', '2025-12-15 10:07:52'),
(27, 15, '2025-12-15', 0.00, 'MOMO', 'CELTIS-SIM-1765796874-8085', 'momo', 'pending', '{\"simulated\":true,\"provider\":\"celtis\",\"phone\":\"@old(\'telephone\')\",\"amount\":150,\"callback\":\"http:\\/\\/127.0.0.1:8001\\/payments\\/webhook\"}', '2025-12-15 10:07:54', '2025-12-15 10:07:54'),
(28, 15, '2025-12-15', 0.00, 'MOMO', 'CELTIS-SIM-1765796876-6374', 'momo', 'pending', '{\"simulated\":true,\"provider\":\"celtis\",\"phone\":\"@old(\'telephone\')\",\"amount\":150,\"callback\":\"http:\\/\\/127.0.0.1:8001\\/payments\\/webhook\"}', '2025-12-15 10:07:55', '2025-12-15 10:07:56'),
(29, 15, '2025-12-15', 0.00, 'MOMO', 'CELTIS-SIM-1765796876-7104', 'momo', 'pending', '{\"simulated\":true,\"provider\":\"celtis\",\"phone\":\"@old(\'telephone\')\",\"amount\":150,\"callback\":\"http:\\/\\/127.0.0.1:8001\\/payments\\/webhook\"}', '2025-12-15 10:07:56', '2025-12-15 10:07:56'),
(30, 15, '2025-12-15', 0.00, 'MOMO', 'CELTIS-SIM-1765796876-9522', 'momo', 'pending', '{\"simulated\":true,\"provider\":\"celtis\",\"phone\":\"@old(\'telephone\')\",\"amount\":150,\"callback\":\"http:\\/\\/127.0.0.1:8001\\/payments\\/webhook\"}', '2025-12-15 10:07:56', '2025-12-15 10:07:56'),
(31, 15, '2025-12-15', 0.00, 'MOMO', 'CELTIS-SIM-1765796876-3852', 'momo', 'pending', '{\"simulated\":true,\"provider\":\"celtis\",\"phone\":\"@old(\'telephone\')\",\"amount\":150,\"callback\":\"http:\\/\\/127.0.0.1:8001\\/payments\\/webhook\"}', '2025-12-15 10:07:56', '2025-12-15 10:07:56'),
(32, 15, '2025-12-15', 0.00, 'MOMO', 'CELTIS-SIM-1765796876-5777', 'momo', 'pending', '{\"simulated\":true,\"provider\":\"celtis\",\"phone\":\"@old(\'telephone\')\",\"amount\":150,\"callback\":\"http:\\/\\/127.0.0.1:8001\\/payments\\/webhook\"}', '2025-12-15 10:07:56', '2025-12-15 10:07:56');

-- --------------------------------------------------------

--
-- Structure de la table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `personnel`
--

CREATE TABLE `personnel` (
  `id_employe` bigint(20) UNSIGNED NOT NULL,
  `id_utilisateur` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `service` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `promotions`
--

CREATE TABLE `promotions` (
  `id_promotion` bigint(20) UNSIGNED NOT NULL,
  `code_promotion` varchar(255) NOT NULL,
  `pourcentage_reduction` decimal(5,2) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

CREATE TABLE `reservations` (
  `id_reservation` bigint(20) UNSIGNED NOT NULL,
  `id_client` bigint(20) UNSIGNED DEFAULT NULL,
  `id_chambre` bigint(20) UNSIGNED NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `statut` enum('confirmée','en cours','annulée') NOT NULL DEFAULT 'confirmée',
  `demandes_speciales` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `reservations`
--

INSERT INTO `reservations` (`id_reservation`, `id_client`, `id_chambre`, `date_debut`, `date_fin`, `statut`, `demandes_speciales`, `created_at`, `updated_at`) VALUES
(1, 4, 1, '2025-11-26', '2025-11-27', 'en cours', 'ces urgent', '2025-11-26 08:55:46', '2025-11-26 08:55:46'),
(2, 5, 2, '2025-11-26', '2025-11-27', 'en cours', 'ces urgent', '2025-11-26 09:28:38', '2025-11-26 09:28:38'),
(3, 6, 3, '2025-11-26', '2025-11-27', 'en cours', 'une chambre', '2025-11-26 10:24:52', '2025-11-26 10:24:52'),
(4, 7, 1, '2025-11-28', '2025-11-29', 'en cours', NULL, '2025-11-26 12:54:58', '2025-11-26 12:54:58'),
(5, 8, 1, '2025-11-27', '2025-11-28', 'en cours', NULL, '2025-11-27 16:29:36', '2025-11-27 16:29:36'),
(6, 9, 3, '2025-11-28', '2025-11-29', 'en cours', 'jaimerais prendre spagetti', '2025-11-28 06:28:35', '2025-11-28 06:28:35'),
(7, 10, 1, '2025-11-29', '2025-12-07', 'en cours', NULL, '2025-11-28 07:28:29', '2025-11-28 07:28:29'),
(8, 11, 2, '2025-11-29', '2025-11-30', 'en cours', NULL, '2025-11-29 01:51:21', '2025-11-29 01:51:21'),
(9, 12, 2, '2025-11-29', '2025-11-30', 'en cours', NULL, '2025-11-29 01:56:14', '2025-11-29 01:56:14'),
(10, 13, 3, '2025-11-29', '2025-11-30', 'en cours', NULL, '2025-11-29 02:01:22', '2025-11-29 02:01:22'),
(11, 14, 3, '2025-11-29', '2025-11-30', 'en cours', NULL, '2025-11-29 02:31:23', '2025-11-29 02:31:23'),
(12, 9, 1, '2025-12-05', '2025-12-06', 'en cours', NULL, '2025-12-04 17:09:23', '2025-12-04 17:09:23'),
(13, 9, 3, '2025-12-04', '2025-12-05', 'en cours', NULL, '2025-12-04 17:42:39', '2025-12-04 17:42:39'),
(14, 9, 3, '2025-12-04', '2025-12-05', 'en cours', NULL, '2025-12-04 17:47:38', '2025-12-04 17:47:38'),
(15, 9, 3, '2025-12-05', '2025-12-06', 'en cours', NULL, '2025-12-04 17:48:39', '2025-12-04 17:48:39'),
(16, 9, 1, '2025-12-10', '2025-12-11', 'en cours', NULL, '2025-12-04 18:06:36', '2025-12-04 18:06:36'),
(17, 9, 1, '2025-12-10', '2025-12-11', 'en cours', NULL, '2025-12-04 18:28:49', '2025-12-04 18:28:49'),
(18, 7, 3, '2025-12-15', '2025-12-16', 'en cours', NULL, '2025-12-15 10:07:24', '2025-12-15 10:07:24'),
(19, 9, 1, '2025-12-15', '2025-12-16', 'en cours', NULL, '2025-12-15 10:55:11', '2025-12-15 10:55:11'),
(20, 15, 2, '2026-04-24', '2026-04-25', 'en cours', 'chawama', '2026-04-24 13:51:08', '2026-04-24 13:51:08');

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `id_role` bigint(20) UNSIGNED NOT NULL,
  `nom_role` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id_role`, `nom_role`, `created_at`, `updated_at`) VALUES
(1, 'Admin', '2025-11-18 02:37:55', '2025-11-18 02:37:55'),
(2, 'Manager', '2025-11-18 02:37:55', '2025-11-18 02:37:55'),
(3, 'Staff', '2025-11-18 02:37:56', '2025-11-18 02:37:56'),
(4, 'Admin', '2025-11-29 02:37:01', '2025-11-29 02:37:01'),
(5, 'Manager', '2025-11-29 02:37:01', '2025-11-29 02:37:01'),
(6, 'Staff', '2025-11-29 02:37:01', '2025-11-29 02:37:01'),
(7, 'Client', '2025-11-29 02:37:01', '2025-11-29 02:37:01');

-- --------------------------------------------------------

--
-- Structure de la table `rooms`
--

CREATE TABLE `rooms` (
  `id_chambre` bigint(20) UNSIGNED NOT NULL,
  `numero_chambre` varchar(255) NOT NULL,
  `type_chambre` bigint(20) UNSIGNED NOT NULL,
  `statut` enum('libre','occupée','nettoyage','maintenance','réservée') NOT NULL DEFAULT 'libre',
  `capacite_max` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `rooms`
--

INSERT INTO `rooms` (`id_chambre`, `numero_chambre`, `type_chambre`, `statut`, `capacite_max`, `created_at`, `updated_at`) VALUES
(1, '101', 1, 'libre', 1, '2025-11-18 02:37:57', '2025-12-04 17:09:23'),
(2, '102', 2, 'libre', 2, '2025-11-18 02:37:57', '2025-11-29 01:51:21'),
(3, '201', 3, 'libre', 4, '2025-11-18 02:37:58', '2025-11-29 02:01:22');

-- --------------------------------------------------------

--
-- Structure de la table `room_types`
--

CREATE TABLE `room_types` (
  `id_type` bigint(20) UNSIGNED NOT NULL,
  `nom_type` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `prix_base` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `room_types`
--

INSERT INTO `room_types` (`id_type`, `nom_type`, `description`, `prix_base`, `created_at`, `updated_at`) VALUES
(1, 'Chambre Simple', 'Chambre avec un lit simple', 50.00, '2025-11-18 02:37:56', '2025-11-18 02:37:56'),
(2, 'Chambre Double', 'Chambre avec un lit double', 80.00, '2025-11-18 02:37:57', '2025-11-18 02:37:57'),
(3, 'Suite', 'Suite luxueuse avec salon', 150.00, '2025-11-18 02:37:57', '2025-11-18 02:37:57');

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('0c8pcEVMhUJTT2faXkgZBE08k7P026OWZDVIfLki', NULL, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNWV4bHRoWEQwQ2o1RFhaalAyZzE5UkMwOHhTWEVGVXRJdmFPd2hzdyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9fQ==', 1777525971),
('6rcwjLoz5Tu6IqHSsuOQ8yN3ZexEOIWsyBskjaA0', NULL, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaGNBR3N4RW1hQlhsSGVwTERuU3RrN01ISEM3TENQbko5ektrZHkybCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1777524096);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `preferences` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`preferences`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role_id`, `preferences`) VALUES
(1, 'Admin User', 'admin@hotel.com', NULL, '$2y$12$wSkW1SX5LUJpdLcQg4Loi.UYykKE//1bgXTqjqXuUd95mdf9VhihK', NULL, '2025-11-18 02:37:56', '2025-12-15 10:34:33', 1, '{\"theme\":\"light\",\"language\":\"fr\",\"email_notifications\":true,\"push_notifications\":true}'),
(12, 'latifou', 'latifou@gmail.com', NULL, '$2y$12$6GpW2qduBkq3pFkjfxOKRuwRa46XKVgjz5lPLr2jejio67LysrkQ.', NULL, '2025-11-26 10:23:53', '2025-11-26 10:23:53', NULL, NULL),
(13, 'chawal', 'chawal@gmail.com', NULL, '$2y$12$.yPkRwKxisgh3lI8ycX8zuNsI7dDcsw.L5uwKitW2OAD.FE0vSnly', NULL, '2025-11-26 12:52:48', '2025-11-26 12:52:48', NULL, NULL),
(14, 'igor', 'igor1@gmail.com', NULL, '$2y$12$Zn73DQxvZGC75k8j3kjQFOk6HBerQgCD7zLjBQtv7yDze91nxBmC6', NULL, '2025-11-27 16:24:55', '2025-11-27 16:24:55', NULL, NULL),
(15, 'hachirou', 'hachiroubaparape1@gmail.com', NULL, '$2y$12$OAUvutuat67rNroYcW1W1OKArqcJz0hK.ahoedLElAvX2267INr2i', NULL, '2025-11-27 17:38:40', '2025-11-27 17:38:40', NULL, NULL),
(16, 'moutia', 'moutia@gmail.com', NULL, '$2y$12$0/.sskN17L2ghABZfRhp2u4nIoIUUFl8RmyJVKIBIUIRByi4yA/ia', NULL, '2025-11-27 18:10:07', '2025-11-27 18:10:07', NULL, NULL),
(17, 'hachirou', 'hachirou@gmail.com', NULL, '$2y$12$u0.nN78WZ.8kqrHhyo2dqOKqzibMGt5PPOWgYUsUjpyPcyF/tZrPS', NULL, '2025-11-28 06:25:56', '2025-11-28 06:25:56', NULL, NULL),
(18, 'hachirou', 'hachirou2@gmail.com', NULL, '$2y$12$HoFzVZgu1YQ/bkVPiUftyOiv33jwZ1tH1kFfT0Vv4amvguaPhIY2G', NULL, '2025-11-28 07:25:57', '2025-11-28 07:25:57', NULL, NULL),
(19, 'Ali', 'houzerou@gmail.com', NULL, '$2y$12$F8tp5W8/btCxlG2mKBtcYu4vSO63c/YEdGRc9G4SJNQg2xSRW7kA2', NULL, '2025-11-28 07:39:27', '2025-11-28 07:39:27', NULL, NULL),
(20, 'Bio', 'hachiroubaparape3@gmail.com', NULL, '$2y$12$sw780hos17N2HNPpI5QKC.lWRPsdLjXjbswcCns3dFTTXovT1CWui', NULL, '2025-11-28 07:51:55', '2025-11-28 07:51:55', NULL, NULL),
(21, 'houzerou', 'houzerou1@gmail.com', NULL, '$2y$12$TWWk4vlbVCh74gsRcfjNW.A1ulj8m2e8XdVegrZ4e.QFcmH3sXFyu', NULL, '2025-11-29 01:45:11', '2025-11-29 01:45:11', NULL, NULL),
(22, 'baparape', 'izibath@gmail.com', NULL, '$2y$12$PDE52da6GMbfLJ/ckgEx0./mXfuSU4Xt3V4VioItSWoFObu12BMHK', NULL, '2025-11-29 01:59:10', '2025-11-29 01:59:10', NULL, NULL),
(23, 'Ali', 'ali@gmail.com', NULL, '$2y$12$.2yayUJo2gXBLOK/DJbxvuocRRmRGB974cqAFFNQ3i41Q/Y7R6QTG', NULL, '2025-11-29 02:30:02', '2025-11-29 02:30:02', NULL, NULL),
(25, 'Test Client', 'test@example.com', NULL, '$2y$12$j7fli8NDH/FA2CXB./J34OoL9kAAdat.JEjD3BhpWjAs/8OPEYIkq', NULL, '2025-11-29 02:40:49', '2025-11-29 02:40:49', 7, '\"{\\\"theme\\\":\\\"light\\\"}\"');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id_client`),
  ADD UNIQUE KEY `clients_adresse_email_unique` (`adresse_email`),
  ADD KEY `clients_user_id_foreign` (`user_id`);

--
-- Index pour la table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Index pour la table `hotel_parameters`
--
ALTER TABLE `hotel_parameters`
  ADD PRIMARY KEY (`id_parametre`);

--
-- Index pour la table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id_facture`),
  ADD KEY `invoices_id_reservation_foreign` (`id_reservation`),
  ADD KEY `invoices_id_client_foreign` (`id_client`);

--
-- Index pour la table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Index pour la table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Index pour la table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id_paiement`),
  ADD KEY `payments_id_facture_foreign` (`id_facture`);

--
-- Index pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Index pour la table `personnel`
--
ALTER TABLE `personnel`
  ADD PRIMARY KEY (`id_employe`),
  ADD KEY `personnel_id_utilisateur_foreign` (`id_utilisateur`);

--
-- Index pour la table `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`id_promotion`),
  ADD UNIQUE KEY `promotions_code_promotion_unique` (`code_promotion`);

--
-- Index pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id_reservation`),
  ADD KEY `reservations_id_chambre_foreign` (`id_chambre`),
  ADD KEY `reservations_id_client_foreign` (`id_client`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_role`);

--
-- Index pour la table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id_chambre`),
  ADD UNIQUE KEY `rooms_numero_chambre_unique` (`numero_chambre`),
  ADD KEY `rooms_type_chambre_foreign` (`type_chambre`);

--
-- Index pour la table `room_types`
--
ALTER TABLE `room_types`
  ADD PRIMARY KEY (`id_type`);

--
-- Index pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `clients`
--
ALTER TABLE `clients`
  MODIFY `id_client` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `hotel_parameters`
--
ALTER TABLE `hotel_parameters`
  MODIFY `id_parametre` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id_facture` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `payments`
--
ALTER TABLE `payments`
  MODIFY `id_paiement` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `personnel`
--
ALTER TABLE `personnel`
  MODIFY `id_employe` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `promotions`
--
ALTER TABLE `promotions`
  MODIFY `id_promotion` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id_reservation` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `id_role` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id_chambre` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `room_types`
--
ALTER TABLE `room_types`
  MODIFY `id_type` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `clients_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_id_client_foreign` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id_client`),
  ADD CONSTRAINT `invoices_id_reservation_foreign` FOREIGN KEY (`id_reservation`) REFERENCES `reservations` (`id_reservation`);

--
-- Contraintes pour la table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_id_facture_foreign` FOREIGN KEY (`id_facture`) REFERENCES `invoices` (`id_facture`);

--
-- Contraintes pour la table `personnel`
--
ALTER TABLE `personnel`
  ADD CONSTRAINT `personnel_id_utilisateur_foreign` FOREIGN KEY (`id_utilisateur`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_id_chambre_foreign` FOREIGN KEY (`id_chambre`) REFERENCES `rooms` (`id_chambre`),
  ADD CONSTRAINT `reservations_id_client_foreign` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id_client`);

--
-- Contraintes pour la table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_type_chambre_foreign` FOREIGN KEY (`type_chambre`) REFERENCES `room_types` (`id_type`);

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id_role`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
