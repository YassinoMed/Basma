-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : dim. 01 fév. 2026 à 19:10
-- Version du serveur : 8.0.35
-- Version de PHP : 8.2.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `nozha`
--

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-5c785c036466adea360111aa28563bfd556b5fba', 'i:1;', 1769970553),
('laravel-cache-5c785c036466adea360111aa28563bfd556b5fba:timer', 'i:1769970553;', 1769970553),
('laravel-cache-theme_card_back_pattern', 's:8:\"mosaicfr\";', 2085250071),
('laravel-cache-theme_icon_setting', 's:9:\"ph-trophy\";', 2085250071),
('laravel-cache-theme_settings_inline_css', 's:2496:\".rami-card,.rami-card-large,.print-rami-card{--rami-card-bg: #faf8f5;--rami-card-border-color: #e6e4e0;--rami-card-border-width: 1px;--rami-card-radius: 12px;--rami-illustration-bg-start: #f0efed;--rami-illustration-bg-end: #e8e6e2;--rami-illustration-border-color: rgba(30, 58, 95, 0.07);--rami-pattern-color: rgba(30, 58, 95, 0.05);--rami-group-1-color: #1e3a5f;--rami-group-2-color: #2d5a3d;--rami-group-3-color: #5a2d5a;--rami-card-shadow: 0 4px 8px rgba(0, 0, 0, 0.1), 0 2px 4px rgba(0, 0, 0, 0.06), inset 0 0 0 1px rgba(255, 255, 255, 0.5);--rami-card-shadow-hover: 0 20px 40px rgba(0, 0, 0, 0.15), 0 8px 16px rgba(0, 0, 0, 0.1), inset 0 0 0 1px rgba(255, 255, 255, 0.6);--rami-noise-opacity: 0.02;--rami-card-border-style: double;--rami-card-radius-tl: 0px;--rami-card-radius-tr: 0px;--rami-card-radius-br: 12px;--rami-card-radius-bl: 12px;--rami-illustration-shadow: 0 8px 32px rgba(31, 38, 135, 0.07);--rami-illustration-size: 120px;--rami-illustration-border-width: 0px;--rami-illustration-inner-inset: 0px;--rami-illustration-inner-bg-start: rgba(255, 255, 255, 0.6);--rami-illustration-inner-bg-end: rgba(255, 255, 255, 0.2);--rami-illustration-radius: 50%;--rami-pattern-inset: 24px;--rami-pattern-inset-large: 36px;--rami-bg-circles-strength: 0%;--rami-bg-rectangles-strength: 0%;--rami-card-hover-lift: 16px;--rami-card-hover-tilt: 12deg;--rami-text-muted-color: #7a7a7a;--rami-index-padding: 6px;--rami-index-pronoun-size: 28px;--rami-index-verb-size: 14px;--rami-verb-size: 23px;--rami-verb-bottom: 62px;--rami-index-padding-large: 28px;--rami-index-pronoun-size-large: 60px;--rami-index-verb-size-large: 14px;--rami-verb-size-large: 32px;--rami-verb-bottom-large: 105px;--rami-badge-bg-color: var(--rami-accent-color);--rami-badge-text-color: #ffffff;--rami-badge-font-size: 6px;--rami-badge-padding-x: 4px;--rami-badge-padding-y: 1px;--rami-badge-radius: 2px;--rami-badge-font-size-large: 18px;--rami-badge-padding-x-large: 22px;--rami-badge-padding-y-large: 14px;--rami-badge-radius-large: 26px;--rami-illustration-clip-path: none;--rami-illustration-icon: ph-trophy;--rami-font-family: \'Inter\', sans-serif;--rami-selected-pattern: arabesque;--rami-card-width: 212px;--rami-card-height: 297px;--rami-card-width-large: 320px;--rami-card-height-large: 396px;--rami-illustration-design: none;--rami-card-back-pattern: mosaicfr;--rami-center-top: 48%;--rami-center-padding: 10px;--rami-verb-letter-spacing: 0.02em;--rami-infinitive-letter-spacing: 0.2em;--rami-suit-size: 1.35em;}\";', 2085250071);

-- --------------------------------------------------------

--
-- Structure de la table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `conjugations`
--

CREATE TABLE `conjugations` (
  `id` bigint UNSIGNED NOT NULL,
  `verb_id` bigint UNSIGNED NOT NULL,
  `tense` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `je` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tu` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `il_elle_on` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nous` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vous` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ils_elles` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `conjugations`
--

INSERT INTO `conjugations` (`id`, `verb_id`, `tense`, `je`, `tu`, `il_elle_on`, `nous`, `vous`, `ils_elles`, `created_at`, `updated_at`) VALUES
(1, 1, 'présent', 'mange', 'manges', 'mange', 'mangeons', 'mangez', 'mangent', '2026-01-23 11:05:54', '2026-01-23 11:05:54'),
(2, 2, 'présent', 'parle', 'parles', 'parle', 'parlons', 'parlez', 'parlent', '2026-01-23 11:05:54', '2026-01-23 11:05:54'),
(3, 3, 'présent', 'chante', 'chantes', 'chante', 'chantons', 'chantez', 'chantent', '2026-01-23 11:05:54', '2026-01-23 11:05:54'),
(4, 4, 'présent', 'danse', 'danses', 'danse', 'dansons', 'dansez', 'dansent', '2026-01-23 11:05:54', '2026-01-23 11:05:54'),
(5, 5, 'présent', 'joue', 'joues', 'joue', 'jouons', 'jouez', 'jouent', '2026-01-23 11:05:54', '2026-01-23 11:05:54'),
(6, 6, 'présent', 'travaille', 'travailles', 'travaille', 'travaillons', 'travaillez', 'travaillent', '2026-01-23 11:05:54', '2026-01-23 11:05:54'),
(7, 7, 'présent', 'écoute', 'écoutes', 'écoute', 'écoutons', 'écoutez', 'écoutent', '2026-01-23 11:05:54', '2026-01-23 11:05:54'),
(8, 8, 'présent', 'regarde', 'regardes', 'regarde', 'regardons', 'regardez', 'regardent', '2026-01-23 11:05:54', '2026-01-23 11:05:54'),
(9, 9, 'présent', 'aime', 'aimes', 'aime', 'aimons', 'aimez', 'aiment', '2026-01-23 11:05:54', '2026-01-23 11:05:54'),
(10, 10, 'présent', 'marche', 'marches', 'marche', 'marchons', 'marchez', 'marchent', '2026-01-23 11:05:54', '2026-01-23 11:05:54'),
(11, 11, 'présent', 'finis', 'finis', 'finit', 'finissons', 'finissez', 'finissent', '2026-01-23 11:05:54', '2026-01-23 11:05:54'),
(12, 12, 'présent', 'choisis', 'choisis', 'choisit', 'choisissons', 'choisissez', 'choisissent', '2026-01-23 11:05:54', '2026-01-23 11:05:54'),
(13, 13, 'présent', 'réussis', 'réussis', 'réussit', 'réussissons', 'réussissez', 'réussissent', '2026-01-23 11:05:54', '2026-01-23 11:05:54'),
(14, 14, 'présent', 'grandis', 'grandis', 'grandit', 'grandissons', 'grandissez', 'grandissent', '2026-01-23 11:05:54', '2026-01-23 11:05:54'),
(15, 15, 'présent', 'suis', 'es', 'est', 'sommes', 'êtes', 'sont', '2026-01-23 11:05:54', '2026-01-23 11:05:54'),
(16, 16, 'présent', 'ai', 'as', 'a', 'avons', 'avez', 'ont', '2026-01-23 11:05:54', '2026-01-23 11:05:54'),
(17, 17, 'présent', 'vais', 'vas', 'va', 'allons', 'allez', 'vont', '2026-01-23 11:05:54', '2026-01-23 11:05:54'),
(18, 18, 'présent', 'fais', 'fais', 'fait', 'faisons', 'faites', 'font', '2026-01-23 11:05:54', '2026-01-23 11:05:54'),
(19, 19, 'présent', 'prends', 'prends', 'prend', 'prenons', 'prenez', 'prennent', '2026-01-23 11:05:54', '2026-01-23 11:05:54'),
(20, 20, 'présent', 'viens', 'viens', 'vient', 'venons', 'venez', 'viennent', '2026-01-23 11:05:54', '2026-01-23 11:05:54'),
(21, 21, 'présent', 'commence', 'commences', 'commence', 'commencons', 'commencez', 'commencent', '2026-01-25 16:07:38', '2026-01-25 16:07:38'),
(22, 22, 'présent', 'rougis', 'rougis', 'rougit', 'rougissons', 'rougissez', 'rougissent', '2026-01-25 16:22:20', '2026-01-25 16:22:20'),
(23, 27, 'présent', 'rougis', 'rougis', 'rougit', 'rougissons', 'rougissez', 'rougissent', '2026-01-28 06:56:35', '2026-01-28 06:56:35'),
(24, 28, 'présent', 'écoute', 'écoutes', 'écoute', 'écoutons', 'écoutez', 'écoutent', '2026-01-28 07:02:51', '2026-01-28 07:02:51'),
(25, 29, 'présent', 'nourris', 'nourris', 'nourrit', 'nourrissons', 'nourrissez', 'nourrissent', '2026-01-28 07:19:30', '2026-01-28 07:19:30'),
(26, 30, 'présent', 'mange', 'manges', 'mange', 'mangeons', 'mangez', 'mangent', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(27, 31, 'présent', 'mange', 'manges', 'mange', 'mangeons', 'mangez', 'mangent', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(28, 32, 'présent', 'mange', 'manges', 'mange', 'mangeons', 'mangez', 'mangent', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(29, 33, 'présent', 'parle', 'parles', 'parle', 'parlons', 'parlez', 'parlent', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(30, 34, 'présent', 'parle', 'parles', 'parle', 'parlons', 'parlez', 'parlent', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(31, 35, 'présent', 'parle', 'parles', 'parle', 'parlons', 'parlez', 'parlent', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(32, 36, 'présent', 'chante', 'chantes', 'chante', 'chantons', 'chantez', 'chantent', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(33, 37, 'présent', 'chante', 'chantes', 'chante', 'chantons', 'chantez', 'chantent', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(34, 38, 'présent', 'chante', 'chantes', 'chante', 'chantons', 'chantez', 'chantent', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(35, 39, 'présent', 'danse', 'danses', 'danse', 'dansons', 'dansez', 'dansent', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(36, 40, 'présent', 'danse', 'danses', 'danse', 'dansons', 'dansez', 'dansent', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(37, 41, 'présent', 'danse', 'danses', 'danse', 'dansons', 'dansez', 'dansent', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(38, 42, 'présent', 'joue', 'joues', 'joue', 'jouons', 'jouez', 'jouent', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(39, 43, 'présent', 'joue', 'joues', 'joue', 'jouons', 'jouez', 'jouent', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(40, 44, 'présent', 'joue', 'joues', 'joue', 'jouons', 'jouez', 'jouent', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(41, 45, 'présent', 'travaille', 'travailles', 'travaille', 'travaillons', 'travaillez', 'travaillent', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(42, 46, 'présent', 'travaille', 'travailles', 'travaille', 'travaillons', 'travaillez', 'travaillent', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(43, 47, 'présent', 'travaille', 'travailles', 'travaille', 'travaillons', 'travaillez', 'travaillent', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(44, 48, 'présent', 'écoute', 'écoutes', 'écoute', 'écoutons', 'écoutez', 'écoutent', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(45, 49, 'présent', 'écoute', 'écoutes', 'écoute', 'écoutons', 'écoutez', 'écoutent', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(46, 50, 'présent', 'regarde', 'regardes', 'regarde', 'regardons', 'regardez', 'regardent', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(47, 51, 'présent', 'regarde', 'regardes', 'regarde', 'regardons', 'regardez', 'regardent', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(48, 52, 'présent', 'regarde', 'regardes', 'regarde', 'regardons', 'regardez', 'regardent', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(49, 53, 'présent', 'aime', 'aimes', 'aime', 'aimons', 'aimez', 'aiment', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(50, 54, 'présent', 'aime', 'aimes', 'aime', 'aimons', 'aimez', 'aiment', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(51, 55, 'présent', 'aime', 'aimes', 'aime', 'aimons', 'aimez', 'aiment', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(52, 56, 'présent', 'marche', 'marches', 'marche', 'marchons', 'marchez', 'marchent', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(53, 57, 'présent', 'marche', 'marches', 'marche', 'marchons', 'marchez', 'marchent', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(54, 58, 'présent', 'marche', 'marches', 'marche', 'marchons', 'marchez', 'marchent', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(55, 59, 'présent', 'finis', 'finis', 'finit', 'finissons', 'finissez', 'finissent', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(56, 60, 'présent', 'finis', 'finis', 'finit', 'finissons', 'finissez', 'finissent', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(57, 61, 'présent', 'finis', 'finis', 'finit', 'finissons', 'finissez', 'finissent', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(58, 62, 'présent', 'choisis', 'choisis', 'choisit', 'choisissons', 'choisissez', 'choisissent', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(59, 63, 'présent', 'choisis', 'choisis', 'choisit', 'choisissons', 'choisissez', 'choisissent', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(60, 64, 'présent', 'choisis', 'choisis', 'choisit', 'choisissons', 'choisissez', 'choisissent', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(61, 65, 'présent', 'réussis', 'réussis', 'réussit', 'réussissons', 'réussissez', 'réussissent', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(62, 66, 'présent', 'réussis', 'réussis', 'réussit', 'réussissons', 'réussissez', 'réussissent', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(63, 67, 'présent', 'réussis', 'réussis', 'réussit', 'réussissons', 'réussissez', 'réussissent', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(64, 68, 'présent', 'grandis', 'grandis', 'grandit', 'grandissons', 'grandissez', 'grandissent', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(65, 69, 'présent', 'grandis', 'grandis', 'grandit', 'grandissons', 'grandissez', 'grandissent', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(66, 70, 'présent', 'grandis', 'grandis', 'grandit', 'grandissons', 'grandissez', 'grandissent', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(67, 71, 'présent', 'suis', 'es', 'est', 'sommes', 'êtes', 'sont', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(68, 72, 'présent', 'suis', 'es', 'est', 'sommes', 'êtes', 'sont', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(69, 73, 'présent', 'suis', 'es', 'est', 'sommes', 'êtes', 'sont', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(70, 74, 'présent', 'ai', 'as', 'a', 'avons', 'avez', 'ont', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(71, 75, 'présent', 'ai', 'as', 'a', 'avons', 'avez', 'ont', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(72, 76, 'présent', 'ai', 'as', 'a', 'avons', 'avez', 'ont', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(73, 77, 'présent', 'vais', 'vas', 'va', 'allons', 'allez', 'vont', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(74, 78, 'présent', 'vais', 'vas', 'va', 'allons', 'allez', 'vont', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(75, 79, 'présent', 'vais', 'vas', 'va', 'allons', 'allez', 'vont', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(76, 80, 'présent', 'fais', 'fais', 'fait', 'faisons', 'faites', 'font', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(77, 81, 'présent', 'fais', 'fais', 'fait', 'faisons', 'faites', 'font', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(78, 82, 'présent', 'fais', 'fais', 'fait', 'faisons', 'faites', 'font', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(79, 83, 'présent', 'prends', 'prends', 'prend', 'prenons', 'prenez', 'prennent', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(80, 84, 'présent', 'prends', 'prends', 'prend', 'prenons', 'prenez', 'prennent', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(81, 85, 'présent', 'prends', 'prends', 'prend', 'prenons', 'prenez', 'prennent', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(82, 86, 'présent', 'viens', 'viens', 'vient', 'venons', 'venez', 'viennent', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(83, 87, 'présent', 'viens', 'viens', 'vient', 'venons', 'venez', 'viennent', '2026-01-28 07:26:02', '2026-01-28 07:26:02'),
(84, 88, 'présent', 'viens', 'viens', 'vient', 'venons', 'venez', 'viennent', '2026-01-28 07:26:02', '2026-01-28 07:26:02');

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `favorites`
--

CREATE TABLE `favorites` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `verb_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_01_23_115745_create_verbs_table', 1),
(5, '2026_01_23_115746_create_conjugations_table', 1),
(6, '2026_01_23_124343_create_theme_settings_table', 2),
(7, '2026_01_25_000001_add_unique_index_to_verbs_infinitive', 3),
(8, '2026_01_25_171640_add_pattern_to_verbs_table', 3),
(9, '2026_01_25_200000_add_is_admin_to_users_table', 4),
(10, '2026_01_26_000001_add_example_sentence_to_verbs_table', 5),
(11, '2026_01_26_000002_create_favorites_table', 5),
(12, '2026_01_27_123742_add_suit_to_verbs_table', 5),
(13, '2026_01_27_123753_add_suit_to_verbs_table', 5);

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('1aBP6lALjPgVeQ5mJBJJSLi5blqGgvzMii8hRiRd', 1, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibU0zVXZwUU9QMzV2SmJqN1REaTVYbHByVTRXbG9MNEF0MXRWZjBRUCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi90aGVtZS12MiI7czo1OiJyb3V0ZSI7czoxODoiYWRtaW4udGhlbWUuZWRpdFYyIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1769955353),
('9yuVkdLcmk5UaxalbdyBIK3kGgi8AzARxTindl2C', 1, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVTEzNjJvZ0txajJUNFZOd1VnUmM3NEhwSFFYN09lY09CMjc2cjNkQyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jYXJkcy9wcmludC9kZWNrIjtzOjU6InJvdXRlIjtzOjE2OiJjYXJkcy5wcmludF9kZWNrIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1769890081),
('X8OwTqpE1kp7yFFIftzJDeiMJIzAUQYUQDSy97Mv', NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOTVSUGp4clJmS01uZHg4bWNvZVBNWVE4RWpxaENzaXRXRXp5bHU4RiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1769972930);

-- --------------------------------------------------------

--
-- Structure de la table `theme_settings`
--

CREATE TABLE `theme_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `theme_settings`
--

INSERT INTO `theme_settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, '--color-primary', '#1e3a5f', NULL, NULL),
(2, '--color-primary-light', '#2d5480', NULL, NULL),
(3, '--color-accent', '#5b9bd5', NULL, NULL),
(4, '--color-bg-primary', '#faf9f7', NULL, NULL),
(5, '--color-group-1', '#1e3a5f', NULL, NULL),
(6, '--color-group-2', '#2d5a3d', NULL, NULL),
(7, '--color-group-3', '#5a2d5a', NULL, NULL),
(8, '--radius-xl', '24px', NULL, '2026-01-23 12:35:22'),
(9, '--color-primary-dark', '#132840', '2026-01-23 12:30:00', '2026-01-23 12:30:00'),
(10, '--color-accent-light', '#89bce8', '2026-01-23 12:30:00', '2026-01-23 12:30:00'),
(11, '--color-accent-dark', '#3d7ab8', '2026-01-23 12:30:00', '2026-01-23 12:30:00'),
(12, '--color-bg-secondary', '#f5f3ef', '2026-01-23 12:30:00', '2026-01-23 12:30:00'),
(13, '--color-bg-card', '#ffffff', '2026-01-23 12:30:00', '2026-01-23 12:30:00'),
(14, '--color-bg-elevated', '#ffffff', '2026-01-23 12:30:00', '2026-01-23 12:30:00'),
(15, '--color-text-primary', '#1a1a1a', '2026-01-23 12:30:00', '2026-01-23 12:30:00'),
(16, '--color-text-secondary', '#4a4a4a', '2026-01-23 12:30:00', '2026-01-23 12:30:00'),
(17, '--color-text-muted', '#7a7a7a', '2026-01-23 12:30:00', '2026-01-23 12:30:00'),
(18, '--color-border', 'rgba(30, 58, 95, 0.12)', '2026-01-23 12:30:00', '2026-01-23 12:30:00'),
(19, '--color-border-light', 'rgba(30, 58, 95, 0.06)', '2026-01-23 12:30:00', '2026-01-23 12:30:00'),
(20, '--shadow-card', '0 2px 12px rgba(30, 58, 95, 0.08)', '2026-01-23 12:30:00', '2026-01-23 12:30:00'),
(21, '--shadow-card-hover', '0 8px 32px rgba(30, 58, 95, 0.15)', '2026-01-23 12:30:00', '2026-01-23 12:30:00'),
(22, '--radius-md', '10px', '2026-01-23 12:30:00', '2026-01-23 12:35:22'),
(23, '--radius-lg', '16px', '2026-01-23 12:30:00', '2026-01-23 12:30:00'),
(24, '--navbar-bg-opacity', '0.85', '2026-01-23 12:30:00', '2026-01-23 12:30:00'),
(25, '--navbar-blur', '20px', '2026-01-23 12:30:00', '2026-01-23 12:35:22'),
(26, '--paper-noise-opacity', '0.015', '2026-01-23 12:30:00', '2026-01-23 12:30:00'),
(27, '--font-display', '\'Montserrat\', -apple-system, BlinkMacSystemFont, sans-serif', '2026-01-23 12:30:00', '2026-01-23 12:35:22'),
(28, '--font-body', '\'Inter\', -apple-system, BlinkMacSystemFont, sans-serif', '2026-01-23 12:30:00', '2026-01-23 12:30:00'),
(29, '--dark-color-bg-primary', '#0f1419', '2026-01-23 12:30:00', '2026-01-23 12:30:00'),
(30, '--dark-color-bg-secondary', '#1a2028', '2026-01-23 12:30:00', '2026-01-23 12:30:00'),
(31, '--dark-color-bg-card', '#1e252e', '2026-01-23 12:30:00', '2026-01-23 12:30:00'),
(32, '--dark-color-bg-elevated', '#252d38', '2026-01-23 12:30:00', '2026-01-23 12:30:00'),
(33, '--dark-color-text-primary', '#f5f5f5', '2026-01-23 12:30:00', '2026-01-23 12:30:00'),
(34, '--dark-color-text-secondary', '#c8c8c8', '2026-01-23 12:30:00', '2026-01-23 12:30:00'),
(35, '--dark-color-text-muted', '#8a8a8a', '2026-01-23 12:30:00', '2026-01-23 12:30:00'),
(36, '--dark-color-text-light', '#6a6a6a', '2026-01-23 12:30:00', '2026-01-23 12:30:00'),
(37, '--dark-color-border', 'rgba(255, 255, 255, 0.08)', '2026-01-23 12:30:00', '2026-01-23 12:30:00'),
(38, '--dark-color-border-light', 'rgba(255, 255, 255, 0.04)', '2026-01-23 12:30:00', '2026-01-23 12:30:00'),
(39, '--dark-shadow-card', '0 2px 12px rgba(0, 0, 0, 0.3)', '2026-01-23 12:30:00', '2026-01-23 12:30:00'),
(40, '--dark-shadow-card-hover', '0 8px 32px rgba(0, 0, 0, 0.4)', '2026-01-23 12:30:00', '2026-01-23 12:30:00'),
(41, '--dark-paper-noise-opacity', '0.015', '2026-01-23 12:30:00', '2026-01-23 12:35:22'),
(42, '--rami-card-bg', '#faf8f5', '2026-01-23 12:53:47', '2026-01-31 19:07:49'),
(43, '--rami-card-border-color', '#e6e4e0', '2026-01-23 12:53:47', '2026-01-31 19:07:49'),
(44, '--rami-card-border-width', '1px', '2026-01-23 12:53:47', '2026-01-31 19:07:49'),
(45, '--rami-card-radius', '12px', '2026-01-23 12:53:47', '2026-01-30 21:25:38'),
(46, '--rami-illustration-bg-start', '#f0efed', '2026-01-23 12:53:47', '2026-01-31 19:07:49'),
(47, '--rami-illustration-bg-end', '#e8e6e2', '2026-01-23 12:53:47', '2026-01-31 19:07:49'),
(48, '--rami-illustration-border-color', 'rgba(30, 58, 95, 0.07)', '2026-01-23 12:53:47', '2026-01-25 15:05:37'),
(49, '--rami-pattern-color', 'rgba(30, 58, 95, 0.05)', '2026-01-23 12:53:47', '2026-01-31 19:07:49'),
(50, '--rami-group-1-color', '#1e3a5f', '2026-01-23 12:53:47', '2026-01-31 19:07:49'),
(51, '--rami-group-2-color', '#2d5a3d', '2026-01-23 12:53:47', '2026-01-31 19:07:49'),
(52, '--rami-group-3-color', '#5a2d5a', '2026-01-23 12:53:47', '2026-01-31 19:07:49'),
(53, '--rami-card-shadow', '0 4px 8px rgba(0, 0, 0, 0.1), 0 2px 4px rgba(0, 0, 0, 0.06), inset 0 0 0 1px rgba(255, 255, 255, 0.5)', '2026-01-23 12:53:47', '2026-01-31 19:07:49'),
(54, '--rami-card-shadow-hover', '0 20px 40px rgba(0, 0, 0, 0.15), 0 8px 16px rgba(0, 0, 0, 0.1), inset 0 0 0 1px rgba(255, 255, 255, 0.6)', '2026-01-23 12:53:47', '2026-01-31 19:07:49'),
(55, '--rami-noise-opacity', '0.02', '2026-01-23 12:53:47', '2026-01-30 21:25:38'),
(56, '--rami-card-border-style', 'double', '2026-01-24 10:14:51', '2026-01-30 21:25:38'),
(57, '--rami-card-radius-tl', '0px', '2026-01-24 10:14:51', '2026-01-30 21:25:38'),
(58, '--rami-card-radius-tr', '0px', '2026-01-24 10:14:51', '2026-01-30 21:25:38'),
(59, '--rami-card-radius-br', '12px', '2026-01-24 10:14:51', '2026-01-26 12:07:11'),
(60, '--rami-card-radius-bl', '12px', '2026-01-24 10:14:51', '2026-01-26 12:07:11'),
(61, '--rami-illustration-shadow', '0 8px 32px rgba(31, 38, 135, 0.07)', '2026-01-24 10:14:51', '2026-01-31 19:07:49'),
(62, '--rami-illustration-size', '120px', '2026-01-24 10:14:51', '2026-01-30 21:25:38'),
(63, '--rami-illustration-border-width', '0px', '2026-01-24 10:14:51', '2026-01-31 19:07:49'),
(64, '--rami-illustration-inner-inset', '0px', '2026-01-24 10:14:51', '2026-01-31 19:07:49'),
(65, '--rami-illustration-inner-bg-start', 'rgba(255, 255, 255, 0.6)', '2026-01-24 10:14:51', '2026-01-31 19:07:49'),
(66, '--rami-illustration-inner-bg-end', 'rgba(255, 255, 255, 0.2)', '2026-01-24 10:14:51', '2026-01-31 19:07:49'),
(67, '--rami-illustration-radius', '50%', '2026-01-24 10:14:51', '2026-01-24 10:14:51'),
(68, '--rami-pattern-inset', '24px', '2026-01-24 10:14:51', '2026-01-27 09:57:51'),
(69, '--rami-pattern-inset-large', '36px', '2026-01-24 10:14:51', '2026-01-27 09:57:51'),
(70, '--rami-bg-circles-strength', '0%', '2026-01-24 10:14:51', '2026-01-28 08:47:33'),
(71, '--rami-bg-rectangles-strength', '0%', '2026-01-24 10:14:51', '2026-01-28 08:47:33'),
(72, '--rami-card-hover-lift', '16px', '2026-01-24 10:14:51', '2026-01-28 07:24:26'),
(73, '--rami-card-hover-tilt', '12deg', '2026-01-24 10:14:51', '2026-01-27 09:57:51'),
(74, '--rami-text-muted-color', '#7a7a7a', '2026-01-24 10:14:51', '2026-01-24 10:14:51'),
(75, '--rami-index-padding', '6px', '2026-01-24 10:14:51', '2026-01-27 06:15:51'),
(76, '--rami-index-pronoun-size', '28px', '2026-01-24 10:14:51', '2026-01-31 19:07:49'),
(77, '--rami-index-verb-size', '14px', '2026-01-24 10:14:51', '2026-01-28 07:24:26'),
(78, '--rami-verb-size', '23px', '2026-01-24 10:14:51', '2026-01-31 19:07:49'),
(79, '--rami-verb-bottom', '62px', '2026-01-24 10:14:51', '2026-01-27 09:57:51'),
(80, '--rami-index-padding-large', '28px', '2026-01-24 10:14:51', '2026-01-27 09:57:51'),
(81, '--rami-index-pronoun-size-large', '60px', '2026-01-24 10:14:51', '2026-01-27 09:57:51'),
(82, '--rami-index-verb-size-large', '14px', '2026-01-24 10:14:51', '2026-01-27 09:57:51'),
(83, '--rami-verb-size-large', '32px', '2026-01-24 10:14:51', '2026-01-27 09:57:51'),
(84, '--rami-verb-bottom-large', '105px', '2026-01-24 10:14:51', '2026-01-28 07:24:26'),
(85, '--rami-badge-bg-color', 'var(--rami-accent-color)', '2026-01-24 10:14:51', '2026-01-24 10:14:51'),
(86, '--rami-badge-text-color', '#ffffff', '2026-01-24 10:14:51', '2026-01-24 10:14:51'),
(87, '--rami-badge-font-size', '6px', '2026-01-24 10:14:51', '2026-01-27 09:57:51'),
(88, '--rami-badge-padding-x', '4px', '2026-01-24 10:14:51', '2026-01-27 06:15:51'),
(89, '--rami-badge-padding-y', '1px', '2026-01-24 10:14:51', '2026-01-27 06:15:51'),
(90, '--rami-badge-radius', '2px', '2026-01-24 10:14:51', '2026-01-27 09:57:51'),
(91, '--rami-badge-font-size-large', '18px', '2026-01-24 10:14:51', '2026-01-27 09:57:51'),
(92, '--rami-badge-padding-x-large', '22px', '2026-01-24 10:14:51', '2026-01-27 09:57:51'),
(93, '--rami-badge-padding-y-large', '14px', '2026-01-24 10:14:51', '2026-01-27 09:57:51'),
(94, '--rami-badge-radius-large', '26px', '2026-01-24 10:14:51', '2026-01-27 09:57:51'),
(95, '--rami-illustration-clip-path', 'none', '2026-01-25 11:46:33', '2026-01-25 11:46:33'),
(96, '--rami-illustration-icon', 'ph-trophy', '2026-01-25 11:46:33', '2026-01-31 19:07:49'),
(97, '--rami-font-family', '\'Inter\', sans-serif', '2026-01-25 15:05:33', '2026-01-31 19:07:49'),
(98, '--rami-selected-pattern', 'arabesque', '2026-01-25 16:43:31', '2026-01-31 19:07:49'),
(99, '--rami-card-width', '212px', '2026-01-26 08:01:31', '2026-01-30 21:25:38'),
(100, '--rami-card-height', '297px', '2026-01-26 08:01:31', '2026-01-30 21:25:38'),
(101, '--rami-card-width-large', '320px', '2026-01-26 08:01:31', '2026-01-26 08:01:31'),
(102, '--rami-card-height-large', '396px', '2026-01-26 08:01:31', '2026-01-28 07:24:26'),
(103, '--rami-illustration-design', 'none', '2026-01-26 10:54:00', '2026-01-31 19:07:49'),
(104, '--rami-card-back-pattern', 'mosaicfr', '2026-01-27 06:03:27', '2026-01-30 21:25:38'),
(105, '--rami-center-top', '48%', '2026-01-31 19:07:49', '2026-01-31 19:07:49'),
(106, '--rami-center-padding', '10px', '2026-01-31 19:07:49', '2026-01-31 19:07:49'),
(107, '--rami-verb-letter-spacing', '0.02em', '2026-01-31 19:07:49', '2026-01-31 19:07:49'),
(108, '--rami-infinitive-letter-spacing', '0.2em', '2026-01-31 19:07:49', '2026-01-31 19:07:49'),
(109, '--rami-suit-size', '1.35em', '2026-01-31 19:07:49', '2026-01-31 19:07:49');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `is_admin`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Test User', 'admin@yassino.com', '2026-01-23 11:05:54', '$2y$12$RNMY4FlwIVTvuwNU6EtNQ.8.SozIyDdtIDyE17zUXRp4O6zbXdiMO', 1, 'ewhuWkQkOatInUVbotYJLL6hG70Isco6zXPciGK4BxoikMK8cwX1gpXiUBiD', '2026-01-23 11:05:54', '2026-01-23 11:05:54'),
(2, 'Mohamed Yassine', 'admin@yassino.fr', NULL, '$2y$12$RNMY4FlwIVTvuwNU6EtNQ.8.SozIyDdtIDyE17zUXRp4O6zbXdiMO', 0, NULL, '2026-01-25 16:31:18', '2026-01-25 16:31:18'),
(3, 'Admin', 'admin@example.com', NULL, '$2y$12$ycVCpMvB.pBxk0InpMFMOuDZy2s6yyS1pJWntrAAmCj7xsgqFTscm', 1, NULL, '2026-01-25 16:40:06', '2026-01-25 16:40:06'),
(4, 'Test User', 'test@example.com', NULL, '$2y$12$AV0L7HfaG6Hpyxkeiil37OkJzmi69OfzK1X6puZ95Gpb396sCslra', 0, NULL, '2026-01-25 16:40:06', '2026-01-25 16:40:06');

-- --------------------------------------------------------

--
-- Structure de la table `verbs`
--

CREATE TABLE `verbs` (
  `id` bigint UNSIGNED NOT NULL,
  `infinitive` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `infinitive_translation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `example_sentence` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1er',
  `illustration_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `illustration_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `theme_color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#1e3a5f',
  `accent_color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#5b9bd5',
  `pattern` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'plain',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `suit` enum('heart','spade','diamond','club') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'spade'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `verbs`
--

INSERT INTO `verbs` (`id`, `infinitive`, `infinitive_translation`, `example_sentence`, `group`, `illustration_path`, `illustration_description`, `theme_color`, `accent_color`, `pattern`, `is_active`, `created_at`, `updated_at`, `suit`) VALUES
(1, 'manger', 'to eat', NULL, '1er', NULL, 'personne mangeant une pomme', '#1e3a5f', '#5b9bd5', 'plain', 1, '2026-01-23 11:05:54', '2026-01-23 11:05:54', 'spade'),
(2, 'parler', 'to speak', NULL, '1er', NULL, 'personne en train de parler', '#1e3a5f', '#5b9bd5', 'plain', 1, '2026-01-23 11:05:54', '2026-01-23 11:05:54', 'spade'),
(3, 'chanter', 'to sing', NULL, '1er', NULL, 'personne chantant avec micro', '#1e3a5f', '#5b9bd5', 'plain', 1, '2026-01-23 11:05:54', '2026-01-23 11:05:54', 'spade'),
(4, 'danser', 'to dance', NULL, '1er', NULL, 'personne en train de danser', '#1e3a5f', '#5b9bd5', 'plain', 1, '2026-01-23 11:05:54', '2026-01-23 11:05:54', 'spade'),
(5, 'jouer', 'to play', NULL, '1er', NULL, 'personne jouant au ballon', '#1e3a5f', '#5b9bd5', 'plain', 1, '2026-01-23 11:05:54', '2026-01-23 11:05:54', 'spade'),
(6, 'travailler', 'to work', NULL, '1er', NULL, 'personne travaillant sur ordinateur', '#1e3a5f', '#5b9bd5', 'plain', 1, '2026-01-23 11:05:54', '2026-01-23 11:05:54', 'spade'),
(7, 'écouter', 'to listen', NULL, '1er', NULL, 'personne écoutant de la musique', '#1e3a5f', '#5b9bd5', 'plain', 1, '2026-01-23 11:05:54', '2026-01-23 11:05:54', 'spade'),
(8, 'regarder', 'to watch/look', NULL, '1er', NULL, 'personne regardant la télévision', '#1e3a5f', '#5b9bd5', 'plain', 1, '2026-01-23 11:05:54', '2026-01-23 11:05:54', 'spade'),
(9, 'aimer', 'to love/like', NULL, '1er', NULL, 'personne formant un coeur avec ses mains', '#1e3a5f', '#5b9bd5', 'plain', 1, '2026-01-23 11:05:54', '2026-01-23 11:05:54', 'spade'),
(10, 'marcher', 'to walk', NULL, '1er', NULL, 'personne marchant dans un parc', '#1e3a5f', '#5b9bd5', 'plain', 1, '2026-01-23 11:05:54', '2026-01-23 11:05:54', 'spade'),
(11, 'finir', 'to finish', NULL, '2ème', NULL, 'personne terminant un travail', '#2d5a3d', '#6d9e7a', 'plain', 1, '2026-01-23 11:05:54', '2026-01-23 11:05:54', 'spade'),
(12, 'choisir', 'to choose', NULL, '2ème', NULL, 'personne choisissant entre options', '#2d5a3d', '#6d9e7a', 'plain', 1, '2026-01-23 11:05:54', '2026-01-23 11:05:54', 'spade'),
(13, 'réussir', 'to succeed', NULL, '2ème', NULL, 'personne célébrant un succès', '#2d5a3d', '#6d9e7a', 'plain', 1, '2026-01-23 11:05:54', '2026-01-23 11:05:54', 'spade'),
(14, 'grandir', 'to grow', NULL, '2ème', NULL, 'plant growing', '#2d5a3d', '#6d9e7a', 'plain', 1, '2026-01-23 11:05:54', '2026-01-23 11:05:54', 'spade'),
(15, 'être', 'to be', NULL, '3ème', NULL, 'personne existant', '#5a2d5a', '#9a6d9a', 'plain', 1, '2026-01-23 11:05:54', '2026-01-23 11:05:54', 'spade'),
(16, 'avoir', 'to have', NULL, '3ème', NULL, 'personne tenant quelque chose', '#5a2d5a', '#9a6d9a', 'plain', 1, '2026-01-23 11:05:54', '2026-01-23 11:05:54', 'spade'),
(17, 'aller', 'to go', NULL, '3ème', NULL, 'personne marchant avec direction', '#5a2d5a', '#9a6d9a', 'plain', 1, '2026-01-23 11:05:54', '2026-01-23 11:05:54', 'spade'),
(18, 'faire', 'to do/make', NULL, '3ème', NULL, 'personne faisant quelque chose', '#5a2d5a', '#9a6d9a', 'plain', 1, '2026-01-23 11:05:54', '2026-01-23 11:05:54', 'spade'),
(19, 'prendre', 'to take', NULL, '3ème', NULL, 'personne prenant un objet', '#5a2d5a', '#9a6d9a', 'plain', 1, '2026-01-23 11:05:54', '2026-01-23 11:05:54', 'spade'),
(20, 'venir', 'to come', NULL, '3ème', NULL, 'personne arrivant', '#5a2d5a', '#9a6d9a', 'plain', 1, '2026-01-23 11:05:54', '2026-01-23 11:05:54', 'spade'),
(21, 'commencer', NULL, NULL, '1er', NULL, NULL, '#1e3a5f', '#5b9bd5', 'plain', 1, '2026-01-25 16:07:38', '2026-01-25 16:07:38', 'spade'),
(22, 'rougir', NULL, NULL, '2ème', NULL, NULL, '#2d5a3d', '#4fb286', 'plain', 1, '2026-01-25 16:22:20', '2026-01-25 16:22:20', 'spade'),
(27, 'rougir', NULL, NULL, '2ème', NULL, NULL, '#2d5a3d', '#4fb286', NULL, 1, '2026-01-28 06:56:35', '2026-01-28 06:56:35', 'heart'),
(28, 'écouter', 'to listen', NULL, '1er', NULL, 'personne écoutant de la musique', '#1e3a5f', '#5b9bd5', NULL, 1, '2026-01-28 07:02:51', '2026-01-28 07:26:02', 'heart'),
(29, 'nourrir', NULL, 'vouloir', '2ème', NULL, NULL, '#2d5a3d', '#4fb286', 'triangles', 1, '2026-01-28 07:19:30', '2026-01-28 07:19:30', 'spade'),
(30, 'manger', 'to eat', NULL, '1er', NULL, 'personne mangeant une pomme', '#1e3a5f', '#5b9bd5', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'diamond'),
(31, 'manger', 'to eat', NULL, '1er', NULL, 'personne mangeant une pomme', '#1e3a5f', '#5b9bd5', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'club'),
(32, 'manger', 'to eat', NULL, '1er', NULL, 'personne mangeant une pomme', '#1e3a5f', '#5b9bd5', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'heart'),
(33, 'parler', 'to speak', NULL, '1er', NULL, 'personne en train de parler', '#1e3a5f', '#5b9bd5', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'diamond'),
(34, 'parler', 'to speak', NULL, '1er', NULL, 'personne en train de parler', '#1e3a5f', '#5b9bd5', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'club'),
(35, 'parler', 'to speak', NULL, '1er', NULL, 'personne en train de parler', '#1e3a5f', '#5b9bd5', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'heart'),
(36, 'chanter', 'to sing', NULL, '1er', NULL, 'personne chantant avec micro', '#1e3a5f', '#5b9bd5', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'diamond'),
(37, 'chanter', 'to sing', NULL, '1er', NULL, 'personne chantant avec micro', '#1e3a5f', '#5b9bd5', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'club'),
(38, 'chanter', 'to sing', NULL, '1er', NULL, 'personne chantant avec micro', '#1e3a5f', '#5b9bd5', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'heart'),
(39, 'danser', 'to dance', NULL, '1er', NULL, 'personne en train de danser', '#1e3a5f', '#5b9bd5', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'diamond'),
(40, 'danser', 'to dance', NULL, '1er', NULL, 'personne en train de danser', '#1e3a5f', '#5b9bd5', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'club'),
(41, 'danser', 'to dance', NULL, '1er', NULL, 'personne en train de danser', '#1e3a5f', '#5b9bd5', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'heart'),
(42, 'jouer', 'to play', NULL, '1er', NULL, 'personne jouant au ballon', '#1e3a5f', '#5b9bd5', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'diamond'),
(43, 'jouer', 'to play', NULL, '1er', NULL, 'personne jouant au ballon', '#1e3a5f', '#5b9bd5', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'club'),
(44, 'jouer', 'to play', NULL, '1er', NULL, 'personne jouant au ballon', '#1e3a5f', '#5b9bd5', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'heart'),
(45, 'travailler', 'to work', NULL, '1er', NULL, 'personne travaillant sur ordinateur', '#1e3a5f', '#5b9bd5', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'diamond'),
(46, 'travailler', 'to work', NULL, '1er', NULL, 'personne travaillant sur ordinateur', '#1e3a5f', '#5b9bd5', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'club'),
(47, 'travailler', 'to work', NULL, '1er', NULL, 'personne travaillant sur ordinateur', '#1e3a5f', '#5b9bd5', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'heart'),
(48, 'écouter', 'to listen', NULL, '1er', NULL, 'personne écoutant de la musique', '#1e3a5f', '#5b9bd5', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'diamond'),
(49, 'écouter', 'to listen', NULL, '1er', NULL, 'personne écoutant de la musique', '#1e3a5f', '#5b9bd5', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'club'),
(50, 'regarder', 'to watch/look', NULL, '1er', NULL, 'personne regardant la télévision', '#1e3a5f', '#5b9bd5', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'diamond'),
(51, 'regarder', 'to watch/look', NULL, '1er', NULL, 'personne regardant la télévision', '#1e3a5f', '#5b9bd5', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'club'),
(52, 'regarder', 'to watch/look', NULL, '1er', NULL, 'personne regardant la télévision', '#1e3a5f', '#5b9bd5', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'heart'),
(53, 'aimer', 'to love/like', NULL, '1er', NULL, 'personne formant un coeur avec ses mains', '#1e3a5f', '#5b9bd5', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'diamond'),
(54, 'aimer', 'to love/like', NULL, '1er', NULL, 'personne formant un coeur avec ses mains', '#1e3a5f', '#5b9bd5', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'club'),
(55, 'aimer', 'to love/like', NULL, '1er', NULL, 'personne formant un coeur avec ses mains', '#1e3a5f', '#5b9bd5', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'heart'),
(56, 'marcher', 'to walk', NULL, '1er', NULL, 'personne marchant dans un parc', '#1e3a5f', '#5b9bd5', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'diamond'),
(57, 'marcher', 'to walk', NULL, '1er', NULL, 'personne marchant dans un parc', '#1e3a5f', '#5b9bd5', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'club'),
(58, 'marcher', 'to walk', NULL, '1er', NULL, 'personne marchant dans un parc', '#1e3a5f', '#5b9bd5', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'heart'),
(59, 'finir', 'to finish', NULL, '2ème', NULL, 'personne terminant un travail', '#2d5a3d', '#6d9e7a', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'diamond'),
(60, 'finir', 'to finish', NULL, '2ème', NULL, 'personne terminant un travail', '#2d5a3d', '#6d9e7a', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'club'),
(61, 'finir', 'to finish', NULL, '2ème', NULL, 'personne terminant un travail', '#2d5a3d', '#6d9e7a', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'heart'),
(62, 'choisir', 'to choose', NULL, '2ème', NULL, 'personne choisissant entre options', '#2d5a3d', '#6d9e7a', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'diamond'),
(63, 'choisir', 'to choose', NULL, '2ème', NULL, 'personne choisissant entre options', '#2d5a3d', '#6d9e7a', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'club'),
(64, 'choisir', 'to choose', NULL, '2ème', NULL, 'personne choisissant entre options', '#2d5a3d', '#6d9e7a', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'heart'),
(65, 'réussir', 'to succeed', NULL, '2ème', NULL, 'personne célébrant un succès', '#2d5a3d', '#6d9e7a', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'diamond'),
(66, 'réussir', 'to succeed', NULL, '2ème', NULL, 'personne célébrant un succès', '#2d5a3d', '#6d9e7a', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'club'),
(67, 'réussir', 'to succeed', NULL, '2ème', NULL, 'personne célébrant un succès', '#2d5a3d', '#6d9e7a', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'heart'),
(68, 'grandir', 'to grow', NULL, '2ème', NULL, 'plant growing', '#2d5a3d', '#6d9e7a', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'diamond'),
(69, 'grandir', 'to grow', NULL, '2ème', NULL, 'plant growing', '#2d5a3d', '#6d9e7a', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'club'),
(70, 'grandir', 'to grow', NULL, '2ème', NULL, 'plant growing', '#2d5a3d', '#6d9e7a', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'heart'),
(71, 'être', 'to be', NULL, '3ème', NULL, 'personne existant', '#5a2d5a', '#9a6d9a', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'diamond'),
(72, 'être', 'to be', NULL, '3ème', NULL, 'personne existant', '#5a2d5a', '#9a6d9a', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'club'),
(73, 'être', 'to be', NULL, '3ème', NULL, 'personne existant', '#5a2d5a', '#9a6d9a', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'heart'),
(74, 'avoir', 'to have', NULL, '3ème', NULL, 'personne tenant quelque chose', '#5a2d5a', '#9a6d9a', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'diamond'),
(75, 'avoir', 'to have', NULL, '3ème', NULL, 'personne tenant quelque chose', '#5a2d5a', '#9a6d9a', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'club'),
(76, 'avoir', 'to have', NULL, '3ème', NULL, 'personne tenant quelque chose', '#5a2d5a', '#9a6d9a', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'heart'),
(77, 'aller', 'to go', NULL, '3ème', NULL, 'personne marchant avec direction', '#5a2d5a', '#9a6d9a', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'diamond'),
(78, 'aller', 'to go', NULL, '3ème', NULL, 'personne marchant avec direction', '#5a2d5a', '#9a6d9a', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'club'),
(79, 'aller', 'to go', NULL, '3ème', NULL, 'personne marchant avec direction', '#5a2d5a', '#9a6d9a', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'heart'),
(80, 'faire', 'to do/make', NULL, '3ème', NULL, 'personne faisant quelque chose', '#5a2d5a', '#9a6d9a', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'diamond'),
(81, 'faire', 'to do/make', NULL, '3ème', NULL, 'personne faisant quelque chose', '#5a2d5a', '#9a6d9a', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'club'),
(82, 'faire', 'to do/make', NULL, '3ème', NULL, 'personne faisant quelque chose', '#5a2d5a', '#9a6d9a', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'heart'),
(83, 'prendre', 'to take', NULL, '3ème', NULL, 'personne prenant un objet', '#5a2d5a', '#9a6d9a', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'diamond'),
(84, 'prendre', 'to take', NULL, '3ème', NULL, 'personne prenant un objet', '#5a2d5a', '#9a6d9a', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'club'),
(85, 'prendre', 'to take', NULL, '3ème', NULL, 'personne prenant un objet', '#5a2d5a', '#9a6d9a', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'heart'),
(86, 'venir', 'to come', NULL, '3ème', NULL, 'personne arrivant', '#5a2d5a', '#9a6d9a', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'diamond'),
(87, 'venir', 'to come', NULL, '3ème', NULL, 'personne arrivant', '#5a2d5a', '#9a6d9a', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'club'),
(88, 'venir', 'to come', NULL, '3ème', NULL, 'personne arrivant', '#5a2d5a', '#9a6d9a', 'plain', 1, '2026-01-28 07:26:02', '2026-01-28 07:26:02', 'heart');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Index pour la table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Index pour la table `conjugations`
--
ALTER TABLE `conjugations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conjugations_verb_id_foreign` (`verb_id`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Index pour la table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `favorites_user_id_verb_id_unique` (`user_id`,`verb_id`),
  ADD KEY `favorites_verb_id_foreign` (`verb_id`);

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
-- Index pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Index pour la table `theme_settings`
--
ALTER TABLE `theme_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `theme_settings_key_unique` (`key`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Index pour la table `verbs`
--
ALTER TABLE `verbs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `verbs_infinitive_suit_unique` (`infinitive`,`suit`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `conjugations`
--
ALTER TABLE `conjugations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `theme_settings`
--
ALTER TABLE `theme_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `verbs`
--
ALTER TABLE `verbs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `conjugations`
--
ALTER TABLE `conjugations`
  ADD CONSTRAINT `conjugations_verb_id_foreign` FOREIGN KEY (`verb_id`) REFERENCES `verbs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorites_verb_id_foreign` FOREIGN KEY (`verb_id`) REFERENCES `verbs` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
