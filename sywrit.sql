-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Dic 28, 2019 alle 17:36
-- Versione del server: 10.1.38-MariaDB
-- Versione PHP: 7.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sywrit`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `account_deletion_request`
--

CREATE TABLE `account_deletion_request` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `expired_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `achievement`
--

CREATE TABLE `achievement` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `points` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `achievement`
--

INSERT INTO `achievement` (`id`, `name`, `description`, `points`, `created_at`, `updated_at`) VALUES
(1, 'achievement.1.0', 'achievement.1.1', 15, NULL, NULL),
(2, 'achievement.2.0', 'achievement.2.1', 7, NULL, NULL),
(3, 'achievement.3.0', 'achievement.3.1', 3, NULL, NULL),
(4, 'achievement.4.0', 'achievement.4.1', 2, NULL, NULL),
(5, 'achievement.5.0', 'achievement.5.1', 2, NULL, NULL),
(6, 'achievement.6.0', 'achievement.6.1', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `achievement_progress`
--

CREATE TABLE `achievement_progress` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `achievement_id` int(11) NOT NULL,
  `progress_value` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `achievement_progress`
--

INSERT INTO `achievement_progress` (`id`, `user_id`, `achievement_id`, `progress_value`, `created_at`, `updated_at`) VALUES
(11, 27, 1, 100, '2019-06-27 13:22:13', '2019-06-27 13:22:13');

-- --------------------------------------------------------

--
-- Struttura della tabella `answer_comments`
--

CREATE TABLE `answer_comments` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `answer_comments`
--

INSERT INTO `answer_comments` (`id`, `user_id`, `text`, `comment_id`, `created_at`, `updated_at`) VALUES
(1, 46, 'ok', 1, '2019-09-05 12:28:12', '2019-09-05 12:28:12');

-- --------------------------------------------------------

--
-- Struttura della tabella `article_category`
--

CREATE TABLE `article_category` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `article_category`
--

INSERT INTO `article_category` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Letteratura', 'literature', NULL, NULL),
(2, 'Spettacolo', 'entertainment', NULL, NULL),
(3, 'Videogames', 'videogames', NULL, NULL),
(4, 'Tecnologia', 'technology', NULL, NULL),
(6, 'Medicina', 'medicine', NULL, NULL),
(7, 'Business', 'business', NULL, NULL),
(8, 'Moda', 'fashion', NULL, NULL),
(9, 'Sport', 'sport', NULL, NULL),
(10, 'Politica', 'policy', NULL, NULL),
(11, 'Scienze', 'science', NULL, NULL),
(13, 'Motori', 'engine', NULL, NULL),
(14, 'Cultura', 'culture', NULL, NULL),
(15, 'Gastronomia', 'gastronomy', NULL, NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `article_comments`
--

CREATE TABLE `article_comments` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `article_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `article_comments`
--

INSERT INTO `article_comments` (`id`, `user_id`, `text`, `article_id`, `created_at`, `updated_at`) VALUES
(11, 46, 'aaaa', 5, '2019-12-23 22:39:05', '2019-12-23 22:39:05'),
(12, 46, 'ssss', 5, '2019-12-24 08:28:39', '2019-12-24 08:28:39');

-- --------------------------------------------------------

--
-- Struttura della tabella `article_history`
--

CREATE TABLE `article_history` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_tags_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `article_likes`
--

CREATE TABLE `article_likes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `article_likes`
--

INSERT INTO `article_likes` (`id`, `user_id`, `article_id`, `created_at`, `updated_at`) VALUES
(70, 36, 8, '2019-08-02 14:49:09', '2019-08-02 14:49:09'),
(72, 36, 9, '2019-08-05 22:13:30', '2019-08-05 22:13:30'),
(73, 36, 11, '2019-08-05 22:13:41', '2019-08-05 22:13:41'),
(78, 36, 14, '2019-08-06 07:35:02', '2019-08-06 07:35:02'),
(80, 37, 20, '2019-08-09 09:47:28', '2019-08-09 09:47:28'),
(82, 46, 39, '2019-08-28 14:14:19', '2019-08-28 14:14:19'),
(84, 46, 43, '2019-09-05 12:48:19', '2019-09-05 12:48:19'),
(86, 46, 42, '2019-09-22 15:55:46', '2019-09-22 15:55:46');

-- --------------------------------------------------------

--
-- Struttura della tabella `article_score`
--

CREATE TABLE `article_score` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `article_score`
--

INSERT INTO `article_score` (`id`, `user_id`, `article_id`, `score`, `created_at`, `updated_at`) VALUES
(30, 27, 7, 4, '2019-06-30 14:17:55', '2019-06-30 14:17:55');

-- --------------------------------------------------------

--
-- Struttura della tabella `articoli`
--

CREATE TABLE `articoli` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `topic_id` int(11) DEFAULT NULL,
  `titolo` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tags` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `testo` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `copertina` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_gruppo` int(11) DEFAULT NULL,
  `id_autore` int(11) DEFAULT NULL,
  `count_view` int(10) DEFAULT '0',
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL,
  `license` enum('1','2') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bot_message` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL,
  `published_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `articoli`
--

INSERT INTO `articoli` (`id`, `topic_id`, `titolo`, `tags`, `slug`, `testo`, `copertina`, `id_gruppo`, `id_autore`, `count_view`, `status`, `license`, `bot_message`, `published_at`, `created_at`, `updated_at`) VALUES
(5, NULL, 'Buon natale', '', '5-b', '<p>asdss</p>', NULL, NULL, 46, 0, '0', '1', '0', '2019-12-28 08:39:55', '2019-12-27 13:43:55', '2019-12-27 13:43:55'),
(7, NULL, 'Buon natale', '', '7-b', '<p>asdss</p>', NULL, NULL, 46, 0, '0', '1', '0', '2019-12-28 08:40:00', '2019-12-27 13:43:55', '2019-12-27 13:43:55'),
(8, NULL, 'Buon natale', '', '8-b', '<p>asdss</p>', NULL, NULL, 46, 0, '0', '1', '0', '2019-12-28 08:40:26', '2019-12-27 13:43:55', '2019-12-27 13:43:55'),
(9, NULL, 'Buon natale', '', '9-b', '<p>asdss</p>', NULL, NULL, 46, 0, '0', '1', '0', '2019-12-28 08:40:26', '2019-12-27 13:43:55', '2019-12-27 13:43:55'),
(10, NULL, 'Buon natale', '', '10-b', '<p>asdss</p>', NULL, NULL, 46, 0, '0', '1', '0', '2019-12-28 08:40:26', '2019-12-27 13:43:55', '2019-12-27 13:43:55'),
(11, NULL, 'Buon natale', '', '11-b', '<p>asdss</p>', NULL, NULL, 46, 0, '0', '1', '0', '2019-12-28 08:40:26', '2019-12-27 13:43:55', '2019-12-27 13:43:55');

-- --------------------------------------------------------

--
-- Struttura della tabella `draft_article`
--

CREATE TABLE `draft_article` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `topic_id` int(11) DEFAULT NULL,
  `titolo` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tags` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `testo` text COLLATE utf8mb4_unicode_ci,
  `copertina` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_gruppo` int(11) DEFAULT NULL,
  `id_autore` int(11) DEFAULT NULL,
  `license` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL,
  `scheduled_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `draft_article`
--

INSERT INTO `draft_article` (`id`, `topic_id`, `titolo`, `tags`, `testo`, `copertina`, `id_gruppo`, `id_autore`, `license`, `scheduled_at`, `created_at`, `updated_at`) VALUES
(1, NULL, 'prova', '', '<p>sadsfscdd</p>', NULL, NULL, 46, '1', '2019-12-31 00:00:00', '2019-12-28 16:30:41', '2019-12-28 16:34:33');

-- --------------------------------------------------------

--
-- Struttura della tabella `editori`
--

CREATE TABLE `editori` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `componenti` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cover` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `biography` text COLLATE utf8mb4_unicode_ci,
  `followers` text COLLATE utf8mb4_unicode_ci,
  `followers_count` int(11) NOT NULL DEFAULT '0',
  `direttore` int(11) NOT NULL,
  `suspended` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `editori`
--

INSERT INTO `editori` (`id`, `name`, `componenti`, `slug`, `avatar`, `cover`, `biography`, `followers`, `followers_count`, `direttore`, `suspended`, `created_at`, `updated_at`) VALUES
(4, 'aaa', '12', 'aaa', NULL, NULL, 'descrizione', NULL, 0, 12, '0', '2019-05-07 09:57:43', '2019-05-10 13:45:35'),
(5, 'Sywrit', '27', 'sywrit', NULL, NULL, NULL, NULL, 0, 27, '0', '2019-06-16 09:38:39', '2019-06-16 09:38:39'),
(6, 'pap', '', 'pap', 'http://www.sywrit.com/storage/groups/342149299.jpg', 'http://www.sywrit.com/storage/groups/__160x1606SfemFFMKmhNhvM6qEuh1iGXL8KCFFErLS14JhUMGEaWF5Wi2EyzL7RNqUkwHzPm.jpg', NULL, NULL, 0, 27, '0', '2019-06-29 16:02:24', '2019-06-30 15:49:27'),
(7, 'asd', '27', 'asd', NULL, NULL, NULL, NULL, 0, 27, '0', '2019-07-02 15:02:47', '2019-07-02 15:02:47');

-- --------------------------------------------------------

--
-- Struttura della tabella `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `avatar` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cover` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `public` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL,
  `suspended` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `groups`
--

INSERT INTO `groups` (`id`, `name`, `description`, `avatar`, `cover`, `public`, `suspended`, `created_at`, `updated_at`) VALUES
(7, 'Test', NULL, 'http://www.neveappennino.it/wp-content/uploads/2017/03/lupo-2.jpg', NULL, '1', '0', '2019-09-04 06:46:21', '2019-09-04 06:46:21');

-- --------------------------------------------------------

--
-- Struttura della tabella `group_article`
--

CREATE TABLE `group_article` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `cover` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `group_article`
--

INSERT INTO `group_article` (`id`, `group_id`, `author_id`, `title`, `text`, `cover`) VALUES
(2, 7, 52, 'aaaa', '<p>s</p>', NULL),
(3, 7, 46, 'ssss', '<p>okkkk</p>', 'http://localhost:8000/sf/ct/__492x3406TzwmNUFVFrI9UH91QyfRzCW2TtHAE55ig319BREw6QBf6ga0VgcT4EFnmZC780l.jpg');

-- --------------------------------------------------------

--
-- Struttura della tabella `group_article_commit`
--

CREATE TABLE `group_article_commit` (
  `id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `old_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `new_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `group_article_commit`
--

INSERT INTO `group_article_commit` (`id`, `article_id`, `user_id`, `old_text`, `new_text`, `note`, `created_at`, `updated_at`) VALUES
(2, 2, 46, '<p>Parola è il mio primissimo post/articolo in questo sito. Non bisogna aspettarsi chissà quale cosa, ma è già un passo avanti rispetto a.</p>\r\n<p>Purtroppo, oggi molte persone, compreso il sottoscritto, hanno paura di scrivere su internet perché non sanno più di cosa parlare, probabilmente dovuto anche a usi scorretti dei social network. Alla domanda: \"Di cosa posso scrivere?\", io rispondo semplicemente: “Scrivi di quello che più ti piace”; racconta di te, quello che fai, anche se magari quello che scrivi non avrà senso. L’importante è iniziare a scrivere. “Non si può migliorare la scrittura se non si scrive”.</p>\r\n<p>Parlando di altre cose, relative sempre all’ambito della scrittura, ho voluto creare questo sito proprio per dare la possibilità alle persone di esprimere ciò che vogliono e come vogliono (eh già…), creando uno spazio privato dove pubblicare le proprie idee: articoli di qualsiasi genere, recensioni di qualsiasi tipo, pensieri, opinioni ecc.</p>\r\n<p>Un consiglio che posso dare su come usare questa piattaforma, è nel modo seguente. Anzitutto, iscriviti (ma va’?), così da crearti uno spazio privato in cui scrivere. In secondo luogo, decidi su cosa verterà il tuo “spazio blog” e parla principalmente di quello, cioè, creati una nicchia.</p>\r\n<p>Rimanendo in tema social, seguitemi su Instagram per rimanere aggiornati.</p>', '<p>Parola è il mio primissimo post/articolo in questo sito. Non bisogna aspettarsi chissà quale cosa, ma è già un passo avanti rispetto a ttt.</p>\r\n<p>Purtroppo, oggi molte persone, compreso il sottoscritto, hanno paura di scrivere su internet perché non sanno più di cosa parlare, probabilmente dovuto anche a usi scorretti dei social network. Alla domanda: \"Di cosa posso scrivere?\", io rispondo semplicemente: “Scrivi di quello che più ti piace”; racconta di te, quello che fai, anche se magari quello che scrivi non avrà senso. L’importante è iniziare a scrivere. “Non si può migliorare la scrittura se non si scrive”.</p>\r\n<p>Parlando di altre cose, relative sempre all’ambito della scrittura, ho voluto creare questo sito proprio per dare la possibilità alle persone di esprimere ciò che vogliono e come vogliono (eh già…), creando uno spazio privato dove pubblicare le proprie idee: articoli di qualsiasi genere, recensioni di qualsiasi tipo, pensieri, opinioni ecc.</p>\r\n<p>Un consiglio che posso dare su come usare questa piattaforma, è nel modo seguente. Anzitutto, iscriviti (ma va’?), così da crearti uno spazio privato in cui scrivere. In secondo luogo, decidi su cosa verterà il tuo “spazio blog” e parla principalmente di quello, cioè, creati una nicchia.</p>\r\n<p>Rimanendo in tema social, seguitemi su Instagram per rimanere aggiornati.</p>', NULL, '2019-09-16 18:02:29', '2019-09-16 18:02:29'),
(3, 2, 46, '<p>Parola è il mio primissimo post/articolo in questo sito. Non bisogna aspettarsi chissà quale cosa, ma è già un passo avanti rispetto a ttt.</p>\r\n<p>Purtroppo, oggi molte persone, compreso il sottoscritto, hanno paura di scrivere su internet perché non sanno più di cosa parlare, probabilmente dovuto anche a usi scorretti dei social network. Alla domanda: \"Di cosa posso scrivere?\", io rispondo semplicemente: “Scrivi di quello che più ti piace”; racconta di te, quello che fai, anche se magari quello che scrivi non avrà senso. L’importante è iniziare a scrivere. “Non si può migliorare la scrittura se non si scrive”.</p>\r\n<p>Parlando di altre cose, relative sempre all’ambito della scrittura, ho voluto creare questo sito proprio per dare la possibilità alle persone di esprimere ciò che vogliono e come vogliono (eh già…), creando uno spazio privato dove pubblicare le proprie idee: articoli di qualsiasi genere, recensioni di qualsiasi tipo, pensieri, opinioni ecc.</p>\r\n<p>Un consiglio che posso dare su come usare questa piattaforma, è nel modo seguente. Anzitutto, iscriviti (ma va’?), così da crearti uno spazio privato in cui scrivere. In secondo luogo, decidi su cosa verterà il tuo “spazio blog” e parla principalmente di quello, cioè, creati una nicchia.</p>\r\n<p>Rimanendo in tema social, seguitemi su Instagram per rimanere aggiornati.</p>', '<p>Parola è il mio primissimo post/articolo in questo sito. Non bisogna aspettarsi chissà quale cosa, ma è già un passo avanti rispetto a ttt.</p>\r\n<p>Purtroppo, oggi molte persone, compreso il sottoscritto, hanno paura di scrivere su internet perché non sanno più di cosa parlare, probabilmente dovuto anche a usi scorretti dei social network. Alla domanda: \"Di cosa posso scrivere?\", io rispondo semplicemente: “Scrivi di quello che più ti piace”; racconta di te, quello che fai, anche se magari quello che scrivi non avrà senso. L’importante è iniziare a scrivere. “Non si può migliorare la scrittura se non si scrive”.</p>\r\n<p>Parlando di altre cose, relative sempre all’ambito della scrittura, ho voluto creare questo sito proprio per dare la possibilità alle persone di esprimere ciò che vogliono e come vogliono (eh già…), creando uno spazio privato dove pubblicare le proprie idee: articoli di qualsiasi genere, recensioni di qualsiasi tipo, pensieri, opinioni ecc.</p>\r\n<p>Un consiglio che posso dare su come usare questa piattaforma, è nel modo seguente. Anzitutto, iscriviti (ma va’?), così da crearti uno spazio privato in cui scrivere. In secondo luogo, decidi su cosa verterà il tuo “spazio blog” e parla principalmente di quello, cioè, creati una nicchia.</p>\r\n<p>Rimanendo in tema social, seguitemi su Instagram per rimanere aggiornati.</p>', NULL, '2019-09-16 18:04:17', '2019-09-16 18:04:17'),
(4, 2, 46, '<p>Parola è il mio primissimo post/articolo in questo sito. Non bisogna aspettarsi chissà quale cosa, ma è già un passo avanti rispetto a ttt.</p>\r\n<p>Purtroppo, oggi molte persone, compreso il sottoscritto, hanno paura di scrivere su internet perché non sanno più di cosa parlare, probabilmente dovuto anche a usi scorretti dei social network. Alla domanda: \"Di cosa posso scrivere?\", io rispondo semplicemente: “Scrivi di quello che più ti piace”; racconta di te, quello che fai, anche se magari quello che scrivi non avrà senso. L’importante è iniziare a scrivere. “Non si può migliorare la scrittura se non si yt”.</p>\r\n<p>Parlando di altre cose, relative sempre all’ambito della scrittura, ho voluto creare questo sito proprio per dare la possibilità alle persone di esprimere ciò che vogliono e come vogliono (eh già…), creando uno spazio privato dove pubblicare le proprie idee: articoli di qualsiasi genere, recensioni di qualsiasi tipo, pensieri, opinioni ecc.</p>\r\n<p>Un consiglio che posso dare su come usare questa piattaforma, è nel modo seguente. Anzitutto, iscriviti (ma va’?), così da crearti uno spazio privato in cui scrivere. In secondo luogo, decidi su cosa verterà il tuo “spazio blog” e parla principalmente di quello, cioè,  una nicchia.</p>', '<p>Parola è il mio primissimo post/articolo in questo sito. Non bisogna aspettarsi chissà quale cosa, ma è già un passo avanti rispetto a.</p>\r\n<p>Purtroppo, oggi molte persone, compreso il sottoscritto, hanno paura di scrivere su internet perché non sanno più di cosa parlare, probabilmente dovuto anche a usi scorretti dei social network. Alla domanda: \"Di cosa posso scrivere?\", io rispondo semplicemente: “Scrivi di quello che più ti piace”; racconta di te, quello che fai, anche se magari quello che scrivi non avrà senso. L’importante è iniziare a scrivere. “Non si può migliorare la scrittura se non si yt”.</p>\r\n<p>Parlando di altre cose, relative sempre all’ambito della scrittura, ho voluto creare questo sito proprio per dare la possibilità alle persone di esprimere ciò che vogliono e come vogliono (eh già…), creando uno spazio privato dove pubblicare le proprie idee: articoli di qualsiasi genere, recensioni di qualsiasi tipo, pensieri, opinioni ecc.</p>\r\n<p>Un consiglio che posso dare su come usare questa piattaforma, è nel modo seguente. Anzitutto, iscriviti (ma va’?), così da crearti uno spazio privato in cui scrivere. In secondo luogo, decidi su cosa verterà il tuo “spazio blog” e parla principalmente di quello, cioè, una nicchia.</p>', NULL, '2019-09-16 19:31:41', '2019-09-16 19:31:41'),
(5, 2, 46, '<p>Parola è il mio primissimo post/articolo in questo sito. Non bisogna aspettarsi chissà quale cosa, ma è già un passo avanti rispetto a.</p>\r\n<p>Purtroppo, oggi molte persone, compreso il sottoscritto, hanno paura di scrivere su internet perché non sanno più di cosa parlare, probabilmente dovuto anche a usi scorretti dei social network. Alla domanda: \"Di cosa posso scrivere?\", io rispondo semplicemente: “Scrivi di quello che più ti piace”; racconta di te, quello che fai, anche se magari quello che scrivi non avrà senso. L’importante è iniziare a scrivere. “Non si può migliorare la scrittura se non si yt”.</p>\r\n<p>Parlando di altre cose, relative sempre all’ambito della scrittura, ho voluto creare questo sito proprio per dare la possibilità alle persone di esprimere ciò che vogliono e come vogliono (eh già…), creando uno spazio privato dove pubblicare le proprie idee: articoli di qualsiasi genere, recensioni di qualsiasi tipo, pensieri, opinioni ecc.</p>\r\n<p>Un consiglio che posso dare su come usare questa piattaforma, è nel modo seguente. Anzitutto, iscriviti (ma va’?), così da crearti uno spazio privato in cui scrivere. In secondo luogo, decidi su cosa verterà il tuo “spazio blog” e parla principalmente di quello, cioè, una nicchia.</p>', '<p>Parola è il mio primissimo post/articolo in questo sito. Non bisogna aspettarsi chissà quale cosa,  è già un passo avanti rispetto a.</p>\r\n<p>Purtroppo, oggi molte persone, compreso il sottoscritto, hanno paura di scrivere su internet perché non sanno più di cosa parlare, probabilmente dovuto anche a usi scorretti dei social network. Alla domanda: \"Di cosa posso scrivere?\", io rispondo semplicemente: “Scrivi di quello che più ti piace”; racconta di te, quello che fai, anche se magari quello che scrivi non avrà senso. L’importante è iniziare a scrivere. “Non si può migliorare la scrittura se non si yt”.</p>\r\n<p>Parlando di altre cose, relative sempre all’ambito della scrittura, ho voluto creare questo sito proprio per dare la possibilità alle persone di esprimere ciò che vogliono e come vogliono (eh già…), creando uno lol privato dove pubblicare le proprie idee: articoli di qualsiasi genere, recensioni di qualsiasi tipo, pensieri, opinioni ecc.</p>\r\n<p>Un consiglio che posso dare su come usare questa piattaforma, è nel modo seguente. Anzitutto, iscriviti (ma va’?), così da crearti uno spazio privato in cui scrivere. In secondo luogo, decidi su cosa verterà il  “spazio blog” e parla principalmente di quello, cioè, una nicchia.</p>', NULL, '2019-09-16 19:32:12', '2019-09-16 19:32:12'),
(6, 2, 46, '<p>Parola è il mio primissimo post/articolo in questo sito. Non bisogna aspettarsi chissà quale cosa,  è già un passo avanti rispetto a.</p>\r\n<p>Purtroppo, oggi molte persone, compreso il sottoscritto, hanno paura di scrivere su internet perché non sanno più di cosa parlare, probabilmente dovuto anche a usi scorretti dei social network. Alla domanda: \"Di cosa posso scrivere?\", io rispondo semplicemente: “Scrivi di quello che più ti piace”; racconta di te, quello che fai, anche se magari quello che scrivi non avrà senso. L’importante è iniziare a scrivere. “Non si può migliorare la scrittura se non si yt”.</p>\r\n<p>Parlando di altre cose, relative sempre all’ambito della scrittura, ho voluto creare questo sito proprio per dare la possibilità alle persone di esprimere ciò che vogliono e come vogliono (eh già…), creando uno lol privato dove pubblicare le proprie idee: articoli di qualsiasi genere, recensioni di qualsiasi tipo, pensieri, opinioni ecc.</p>\r\n<p>Un consiglio che posso dare su come usare questa piattaforma, è nel modo seguente. Anzitutto, iscriviti (ma va’?), così da crearti uno spazio privato in cui scrivere. In secondo luogo, decidi su cosa verterà il  “spazio blog” e parla principalmente di quello, cioè, una nicchia.</p>', '<p>Parola è il mio primissimo post/articolo in questo sito. Non bisogna aspettarsi chissà quale cosa, è già un passo avanti rispetto a.kkk</p>\r\n<p>Purtroppo, oggi molte persone, compreso il sottoscritto, hanno paura di scrivere su internet perché non sanno più di cosa parlare, probabilmente dovuto anche a usi scorretti dei social network. Alla domanda: \"Di cosa posso scrivere?\", io rispondo semplicemente: “Scrivi di quello che più ti piace”; racconta di te, quello che fai, anche se magari quello che scrivi non avrà senso. L’importante è iniziare a scrivere. “Non si può migliorare la scrittura se non si yt”.</p>\r\n<p>Parlando di altre cose, relative sempre all’ambito della scrittura, ho voluto creare questo sito proprio per dare la possibilità alle persone di esprimere ciò che vogliono e come vogliono (eh già…), creando uno lol privato dove pubblicare le proprie idee: articoli di qualsiasi genere, recensioni di qualsiasi tipo, pensieri, opinioni ecc.</p>\r\n<p>Un consiglio che posso dare su come usare questa piattaforma, è nel modo seguente. Anzitutto, iscriviti (ma va’?), così da crearti uno spazio privato in cui scrivere. In secondo luogo, decidi su cosa verterà il “spazio blog” e parla principalmente di quello, cioè, una nicchia.</p>', NULL, '2019-10-27 09:50:00', '2019-10-27 09:50:00'),
(7, 2, 46, '<p>Parola è il mio primissimo post/articolo in questo sito. Non bisogna aspettarsi chissà quale cosa, è già un passo avanti rispetto a.kkk</p>\r\n<p>Purtroppo, oggi molte persone, compreso il sottoscritto, hanno paura di scrivere su internet perché non sanno più di cosa parlare, probabilmente dovuto anche a usi scorretti dei social network. Alla domanda: \"Di cosa posso scrivere?\", io rispondo semplicemente: “Scrivi di quello che più ti piace”; racconta di te, quello che fai, anche se magari quello che scrivi non avrà senso. L’importante è iniziare a scrivere. “Non si può migliorare la scrittura se non si yt”.</p>\r\n<p>Parlando di altre cose, relative sempre all’ambito della scrittura, ho voluto creare questo sito proprio per dare la possibilità alle persone di esprimere ciò che vogliono e come vogliono (eh già…), creando uno lol privato dove pubblicare le proprie idee: articoli di qualsiasi genere, recensioni di qualsiasi tipo, pensieri, opinioni ecc.</p>\r\n<p>Un consiglio che posso dare su come usare questa piattaforma, è nel modo seguente. Anzitutto, iscriviti (ma va’?), così da crearti uno spazio privato in cui scrivere. In secondo luogo, decidi su cosa verterà il “spazio blog” e parla principalmente di quello, cioè, una nicchia.</p>', '<p>s</p>', NULL, '2019-10-27 09:50:16', '2019-10-27 09:50:16');

-- --------------------------------------------------------

--
-- Struttura della tabella `group_conversation`
--

CREATE TABLE `group_conversation` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `article_id` int(11) DEFAULT NULL,
  `text` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `group_conversation`
--

INSERT INTO `group_conversation` (`id`, `group_id`, `user_id`, `article_id`, `text`, `created_at`, `updated_at`) VALUES
(81, 7, 46, 2, NULL, '2019-09-10 13:25:23', '2019-09-10 13:25:23'),
(99, 7, 46, 3, NULL, '2019-10-25 20:16:56', '2019-10-25 20:16:56');

-- --------------------------------------------------------

--
-- Struttura della tabella `group_conversation_answer`
--

CREATE TABLE `group_conversation_answer` (
  `id` int(11) NOT NULL,
  `conversation_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `group_conversation_answer`
--

INSERT INTO `group_conversation_answer` (`id`, `conversation_id`, `user_id`, `text`, `created_at`, `updated_at`) VALUES
(1, 82, 46, 'grazie, eh', '2019-09-18 14:59:55', '2019-09-18 14:59:55'),
(2, 81, 46, 'grazie, eh', '2019-09-18 14:59:55', '2019-09-18 14:59:55'),
(3, 82, 46, 'aa', '2019-09-18 15:08:17', '2019-09-18 15:08:17'),
(4, 81, 46, 'aa', '2019-09-18 15:08:18', '2019-09-18 15:08:18'),
(5, 82, 46, 'we', '2019-09-18 15:09:06', '2019-09-18 15:09:06'),
(6, 81, 46, 'we', '2019-09-18 15:09:07', '2019-09-18 15:09:07');

-- --------------------------------------------------------

--
-- Struttura della tabella `group_join_request`
--

CREATE TABLE `group_join_request` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `text` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `group_member`
--

CREATE TABLE `group_member` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `permission_level` enum('User','Moderator','Administrator') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `group_member`
--

INSERT INTO `group_member` (`id`, `group_id`, `user_id`, `permission_level`, `created_at`, `updated_at`) VALUES
(12, 7, 46, 'Administrator', NULL, NULL),
(23, 7, 52, 'User', '2019-09-10 11:18:04', '2019-09-10 11:18:04');

-- --------------------------------------------------------

--
-- Struttura della tabella `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(4, '2018_12_30_214444_create_publishing_token_table', 2),
(6, '2018_12_30_125343_create_publisher_table', 3),
(24, '2019_02_05_133021_create_invite_message', 12),
(25, '2019_02_06_192504_create_activities_reports', 13),
(27, '2014_10_12_100000_create_password_resets_table', 15),
(28, '2019_03_16_161030_create_article_comments', 16),
(29, '2019_03_16_161319_create_answer_comments', 16),
(30, '2019_03_30_151758_create_publisher_request', 16),
(31, '2019_04_04_231329_create_article_history', 17),
(32, '2019_02_06_192504_create_reported_articles', 18),
(33, '2019_04_06_150205_create_reported_comments', 18),
(34, '2019_04_06_150411_create_reported_users', 18),
(35, '2019_04_09_144040_create_article_category', 18),
(36, '2019_04_09_182026_create_article_score', 19),
(37, '2019_04_20_180049_create_reported_answer', 20),
(38, '2019_04_27_163807_create_account_deletion_request', 21),
(39, '2019_05_07_220742_create_bot_message', 22),
(40, '2019_05_09_134638_create_saved_articles', 23),
(41, '2019_01_01_204941_create_articles_table', 24),
(42, '0000_00_00_000000_create_achievements_tables', 25),
(43, '2019_06_10_205158_create_social_users_table', 25),
(44, '2019_06_23_214140_create_achievement_table', 26),
(46, '2019_03_05_213443_create_notifications_table', 27),
(47, '2019_07_23_172420_create_article_likes', 28);

-- --------------------------------------------------------

--
-- Struttura della tabella `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sender_id` int(11) NOT NULL,
  `target_id` int(11) NOT NULL,
  `content_id` int(11) DEFAULT NULL,
  `class_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL,
  `new` enum('true','false') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'true',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `notifications`
--

INSERT INTO `notifications` (`id`, `sender_id`, `target_id`, `content_id`, `class_name`, `read`, `new`, `created_at`, `updated_at`) VALUES
(1, 28, 27, 7, '\\App\\Push\\ArticleComment', '0', 'true', NULL, NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('pietropaolo.carpentras@outlook.it', '$2y$10$fjiUeZlfJufu6DH3es.sM.c7AQySWUNtD1gkH2o.zjSzw1PRdpOCy', '2019-08-16 15:48:30');

-- --------------------------------------------------------

--
-- Struttura della tabella `publisher_request`
--

CREATE TABLE `publisher_request` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `target_id` int(11) NOT NULL,
  `publisher_id` int(11) NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expired_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `reported_answer`
--

CREATE TABLE `reported_answer` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `answer_id` int(11) NOT NULL,
  `report` enum('0','1','2','3','4') COLLATE utf8mb4_unicode_ci NOT NULL,
  `report_text` text COLLATE utf8mb4_unicode_ci,
  `report_token` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resolved` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `reported_articles`
--

CREATE TABLE `reported_articles` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `report` enum('0','1','2','3') COLLATE utf8mb4_unicode_ci NOT NULL,
  `report_text` text COLLATE utf8mb4_unicode_ci,
  `report_token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `resolved` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `reported_comments`
--

CREATE TABLE `reported_comments` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `report` enum('0','1','2','3','4') COLLATE utf8mb4_unicode_ci NOT NULL,
  `report_text` text COLLATE utf8mb4_unicode_ci,
  `report_token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `resolved` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `reported_users`
--

CREATE TABLE `reported_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `reported_id` int(11) NOT NULL,
  `report` enum('0','1','2','3','4','5') COLLATE utf8mb4_unicode_ci NOT NULL,
  `report_text` text COLLATE utf8mb4_unicode_ci,
  `report_token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `resolved` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `reported_users`
--

INSERT INTO `reported_users` (`id`, `user_id`, `reported_id`, `report`, `report_text`, `report_token`, `resolved`, `created_at`, `updated_at`) VALUES
(1, 46, 48, '3', NULL, 'qs50Xls75xc9XPSv5W3dzBwzivLppElY', '0', '2019-08-25 10:47:11', '2019-08-25 10:47:11'),
(2, 46, 48, '2', NULL, 'zB6y9TPhiP8qSVqqTWMZCH4UHVsKGg5q', '0', '2019-08-25 10:47:23', '2019-08-25 10:47:23'),
(3, 46, 48, '0', NULL, 'BFubumripO4euvfPDqYm78WAOF5q9aYT', '0', '2019-08-25 20:20:38', '2019-08-25 20:20:38');

-- --------------------------------------------------------

--
-- Struttura della tabella `security_code`
--

CREATE TABLE `security_code` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `security_code`
--

INSERT INTO `security_code` (`email`, `token`, `created_at`) VALUES
('pietropaolo.carpentras@outlook.it', 'GHJSL5', '2019-07-31 14:44:55');

-- --------------------------------------------------------

--
-- Struttura della tabella `social_service`
--

CREATE TABLE `social_service` (
  `id` int(11) NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prefix` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `social_service`
--

INSERT INTO `social_service` (`id`, `name`, `prefix`) VALUES
(1, 'facebook', 'https://facebook.com/'),
(2, 'instagram', 'https://instagram.com/'),
(3, 'linkedin', 'https://linkedin.com/in/'),
(4, 'youtube', 'https://youtube.com/');

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `copertina` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `biography` text COLLATE utf8mb4_unicode_ci,
  `followers_count` int(11) DEFAULT '0',
  `notifications_count` int(11) NOT NULL DEFAULT '0',
  `id_gruppo` text COLLATE utf8mb4_unicode_ci,
  `permission` enum('1','2','3','4') COLLATE utf8mb4_unicode_ci NOT NULL,
  `verified` int(11) NOT NULL,
  `suspended` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL,
  `cron` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL,
  `social_auth_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notifications_to_read` enum('true','false') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'false',
  `language` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `users`
--

INSERT INTO `users` (`id`, `name`, `surname`, `email`, `email_verified_at`, `password`, `slug`, `avatar`, `copertina`, `biography`, `followers_count`, `notifications_count`, `id_gruppo`, `permission`, `verified`, `suspended`, `cron`, `social_auth_id`, `remember_token`, `notifications_to_read`, `language`, `created_at`, `updated_at`) VALUES
(46, 'Paolo', 'Carpentras', 'pietropaolo.carpentras@outlook.it', NULL, '$2y$10$M0BwA7U9D/3lSUbmn/ZEjegs0aevqHFlAQ5bUHQnig7yXiygYip/W', '46-pietropaolocarpentras', 'http://localhost:8000/sf/aa/387706484hIGquKp544kvcw.jpg', 'http://localhost:8000/sf/aa/520692305XvNdlpCMUazrS4.jpg', 'Il bene di alcuni è il male di altri.', 0, 0, '7', '4', 1, '0', '0', NULL, '0QKAyK8RBqL3FDkDArUIbqZgaWlfuLvlAifhr065S5mpkEvTz8Z9kRLXLADs', 'false', 'it_IT', '2019-08-16 10:49:27', '2019-12-24 07:51:22'),
(48, 'Test', 'Utente', 'pietropaolo.carpentras@outlookr.it', NULL, '$2y$10$M0BwA7U9D/3lSUbmn/ZEjegs0aevqHFlAQ5bUHQnig7yXiygYip/W', '48-pietropaolocarpentras', 'https://img.freepik.com/vettori-gratuito/progettazione-di-robot-colorato_1148-9.jpg?size=338&ext=jpg', NULL, NULL, 0, 0, NULL, '1', 0, '0', '0', NULL, 'hyW8zSIDu8YNzZQTlTIwbj6dTf7QdJwh8V1DNtdfKF8byncM40LOC08bcQF2', 'false', 'dd', '2019-08-16 10:49:27', '2019-08-25 10:38:57'),
(49, 'Test', 'Utente 2', 'pietropaolo.carpentras@outlooke.it', NULL, '$2y$10$M0BwA7U9D/3lSUbmn/ZEjegs0aevqHFlAQ5bUHQnig7yXiygYip/W', '49-pietropaolocarpentras', 'https://img.freepik.com/vettori-gratuito/progettazione-di-robot-colorato_1148-9.jpg?size=338&ext=jpg', NULL, NULL, 0, 0, NULL, '1', 0, '0', '0', NULL, 'hyW8zSIDu8YNzZQTlTIwbj6dTf7QdJwh8V1DNtdfKF8byncM40LOC08bcQF2', 'false', 'dd', '2019-08-16 10:49:27', '2019-08-25 10:38:57'),
(50, 'Test', 'Utente 3', 'pietropaolo.carpenetras@outlooke.it', NULL, '$2y$10$M0BwA7U9D/3lSUbmn/ZEjegs0aevqHFlAQ5bUHQnig7yXiygYip/W', '50-pietropaolocarpentras', 'https://img.freepik.com/vettori-gratuito/progettazione-di-robot-colorato_1148-9.jpg?size=338&ext=jpg', NULL, NULL, 0, 0, NULL, '1', 0, '0', '0', NULL, 'hyW8zSIDu8YNzZQTlTIwbj6dTf7QdJwh8V1DNtdfKF8byncM40LOC08bcQF2', 'false', 'dd', '2019-08-16 10:49:27', '2019-08-25 10:38:57'),
(51, 'Test', 'Utente 4', 'pietropaolo.carpenetrase@outlooke.it', NULL, '$2y$10$M0BwA7U9D/3lSUbmn/ZEjegs0aevqHFlAQ5bUHQnig7yXiygYip/W', '51-pietropaolocarpentras', 'https://img.freepik.com/vettori-gratuito/progettazione-di-robot-colorato_1148-9.jpg?size=338&ext=jpg', NULL, NULL, 0, 0, NULL, '1', 0, '0', '0', NULL, 'hyW8zSIDu8YNzZQTlTIwbj6dTf7QdJwh8V1DNtdfKF8byncM40LOC08bcQF2', 'false', 'dd', '2019-08-16 10:49:27', '2019-08-25 10:38:57'),
(52, 'Test', 'Utente 5', 'pietropaolo.carpenetraese@outlooke.it', NULL, '$2y$10$M0BwA7U9D/3lSUbmn/ZEjegs0aevqHFlAQ5bUHQnig7yXiygYip/W', '52-pietropaolocarpentras', 'https://img.freepik.com/vettori-gratuito/progettazione-di-robot-colorato_1148-9.jpg?size=338&ext=jpg', NULL, NULL, 0, 0, '7', '1', 0, '0', '0', NULL, 'hyW8zSIDu8YNzZQTlTIwbj6dTf7QdJwh8V1DNtdfKF8byncM40LOC08bcQF2', 'false', 'dd', '2019-08-16 10:49:27', '2019-09-10 11:18:04');

-- --------------------------------------------------------

--
-- Struttura della tabella `user_follower`
--

CREATE TABLE `user_follower` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `follow_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `user_follower`
--

INSERT INTO `user_follower` (`id`, `user_id`, `follow_id`, `created_at`, `updated_at`) VALUES
(1, 48, 46, '2019-09-01 13:11:00', NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `user_links`
--

CREATE TABLE `user_links` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `url` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dump dei dati per la tabella `user_links`
--

INSERT INTO `user_links` (`id`, `user_id`, `service_id`, `url`, `created_at`, `updated_at`) VALUES
(3, 46, 1, 'paolo.carpentras', '2019-08-25 09:16:18', '2019-08-25 09:16:18'),
(4, 46, 2, 'paolocarpentras', '2019-08-25 09:16:18', '2019-08-25 09:16:18');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `account_deletion_request`
--
ALTER TABLE `account_deletion_request`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `achievement`
--
ALTER TABLE `achievement`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `achievement_progress`
--
ALTER TABLE `achievement_progress`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `answer_comments`
--
ALTER TABLE `answer_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `article_category`
--
ALTER TABLE `article_category`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `article_comments`
--
ALTER TABLE `article_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `article_history`
--
ALTER TABLE `article_history`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `article_likes`
--
ALTER TABLE `article_likes`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `article_score`
--
ALTER TABLE `article_score`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `articoli`
--
ALTER TABLE `articoli`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `draft_article`
--
ALTER TABLE `draft_article`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `editori`
--
ALTER TABLE `editori`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `editori_nome_unique` (`name`);

--
-- Indici per le tabelle `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `group_article`
--
ALTER TABLE `group_article`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `group_article_commit`
--
ALTER TABLE `group_article_commit`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `group_conversation`
--
ALTER TABLE `group_conversation`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `group_conversation_answer`
--
ALTER TABLE `group_conversation_answer`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `group_join_request`
--
ALTER TABLE `group_join_request`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `group_member`
--
ALTER TABLE `group_member`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indici per le tabelle `publisher_request`
--
ALTER TABLE `publisher_request`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `reported_answer`
--
ALTER TABLE `reported_answer`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `reported_articles`
--
ALTER TABLE `reported_articles`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `reported_comments`
--
ALTER TABLE `reported_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `reported_users`
--
ALTER TABLE `reported_users`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `social_service`
--
ALTER TABLE `social_service`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `utenti_email_unique` (`email`);

--
-- Indici per le tabelle `user_follower`
--
ALTER TABLE `user_follower`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `user_links`
--
ALTER TABLE `user_links`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `account_deletion_request`
--
ALTER TABLE `account_deletion_request`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `achievement`
--
ALTER TABLE `achievement`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT per la tabella `achievement_progress`
--
ALTER TABLE `achievement_progress`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT per la tabella `answer_comments`
--
ALTER TABLE `answer_comments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la tabella `article_category`
--
ALTER TABLE `article_category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT per la tabella `article_comments`
--
ALTER TABLE `article_comments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT per la tabella `article_history`
--
ALTER TABLE `article_history`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `article_likes`
--
ALTER TABLE `article_likes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT per la tabella `article_score`
--
ALTER TABLE `article_score`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT per la tabella `articoli`
--
ALTER TABLE `articoli`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT per la tabella `draft_article`
--
ALTER TABLE `draft_article`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la tabella `editori`
--
ALTER TABLE `editori`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT per la tabella `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT per la tabella `group_article`
--
ALTER TABLE `group_article`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `group_article_commit`
--
ALTER TABLE `group_article_commit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT per la tabella `group_conversation`
--
ALTER TABLE `group_conversation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT per la tabella `group_conversation_answer`
--
ALTER TABLE `group_conversation_answer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT per la tabella `group_join_request`
--
ALTER TABLE `group_join_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `group_member`
--
ALTER TABLE `group_member`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT per la tabella `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT per la tabella `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la tabella `publisher_request`
--
ALTER TABLE `publisher_request`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `reported_answer`
--
ALTER TABLE `reported_answer`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `reported_articles`
--
ALTER TABLE `reported_articles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `reported_comments`
--
ALTER TABLE `reported_comments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `reported_users`
--
ALTER TABLE `reported_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `social_service`
--
ALTER TABLE `social_service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT per la tabella `user_follower`
--
ALTER TABLE `user_follower`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la tabella `user_links`
--
ALTER TABLE `user_links`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
