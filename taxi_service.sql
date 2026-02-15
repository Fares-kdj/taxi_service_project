-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 14 fév. 2026 à 19:27
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
-- Base de données : `taxi_service`
--

-- --------------------------------------------------------

--
-- Structure de la table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `pickup_location` varchar(255) NOT NULL,
  `destination` varchar(255) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `booking_date` date DEFAULT NULL,
  `booking_time` time DEFAULT NULL,
  `passengers` int(11) DEFAULT 1,
  `notes` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `price_euro` decimal(10,2) DEFAULT NULL,
  `status` enum('new','contacted','confirmed','completed','cancelled') DEFAULT 'new',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `status` enum('unread','read','replied') DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `gallery`
--

CREATE TABLE `gallery` (
  `id` int(11) NOT NULL,
  `title_ar` varchar(200) DEFAULT NULL,
  `title_fr` varchar(200) DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `category` enum('fleet','service','team','other') DEFAULT 'other',
  `display_order` int(11) DEFAULT 0,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `newsletter_subscribers`
--

CREATE TABLE `newsletter_subscribers` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `active` tinyint(1) DEFAULT 1,
  `subscribed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `pricing`
--

CREATE TABLE `pricing` (
  `id` int(11) NOT NULL,
  `from_location_ar` varchar(200) NOT NULL,
  `from_location_fr` varchar(200) NOT NULL,
  `to_location_ar` varchar(200) NOT NULL,
  `to_location_fr` varchar(200) NOT NULL,
  `service_id` int(11) DEFAULT NULL,
  `distance_km` decimal(10,2) DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `price_euro` decimal(10,2) DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `pricing`
--

INSERT INTO `pricing` (`id`, `from_location_ar`, `from_location_fr`, `to_location_ar`, `to_location_fr`, `service_id`, `distance_km`, `duration`, `price`, `price_euro`, `active`, `created_at`, `updated_at`) VALUES
(8, 'الجزائر', 'Alger', 'وهران', 'Oran', NULL, 50.00, '4h', 250.00, 66.00, 1, '2026-02-13 20:15:00', '2026-02-13 21:12:32');

-- --------------------------------------------------------

--
-- Structure de la table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `name_ar` varchar(200) NOT NULL,
  `name_fr` varchar(200) NOT NULL,
  `description_ar` text DEFAULT NULL,
  `description_fr` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `price_euro` decimal(10,2) DEFAULT NULL,
  `price_type` enum('fixed','starting_from','on_request') DEFAULT 'starting_from',
  `image` varchar(255) DEFAULT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `services`
--

INSERT INTO `services` (`id`, `name_ar`, `name_fr`, `description_ar`, `description_fr`, `price`, `price_euro`, `price_type`, `image`, `icon`, `display_order`, `active`, `created_at`, `updated_at`) VALUES
(16, 'فارس', 'Aéropot', ' xsxx', 'xcx', 2600.00, 25.00, 'fixed', 'uploads/services/service_698f97f51e145.jpg', 'fa-taxi', 0, 1, '2026-02-13 21:30:29', '2026-02-13 21:35:29');

-- --------------------------------------------------------

--
-- Structure de la table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_type` enum('text','textarea','image','url','number') DEFAULT 'text',
  `category` varchar(50) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `site_settings`
--

INSERT INTO `site_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `category`, `updated_at`) VALUES
(1, 'company_name_ar', 'فرسان الطريق', 'text', 'company', '2026-02-06 22:36:13'),
(2, 'company_name_fr', 'Chevaliers de la Route', 'text', 'company', '2026-02-12 23:56:34'),
(3, 'company_phone', '0784279410 / 0662811112', 'text', 'contact', '2026-02-13 17:52:03'),
(4, 'company_whatsapp', '0553928620', 'text', 'contact', '2026-02-07 02:41:49'),
(5, 'company_email', 'adel@gmail.com', 'text', 'contact', '2026-02-13 00:00:46'),
(6, 'company_address_ar', 'وهران', 'text', 'contact', '2026-02-07 02:41:49'),
(7, 'company_address_fr', 'oran', 'text', 'contact', '2026-02-07 02:41:49'),
(8, 'hero_title_ar', 'خدمة تاكسي احترافية وموثوقة', 'text', 'homepage', '2026-02-06 22:36:13'),
(9, 'hero_title_fr', 'Service de Taxi Professionnel et Fiable', 'text', 'homepage', '2026-02-06 22:36:13'),
(10, 'hero_subtitle_ar', 'نوفر لك تنقل آمن ومريح في جميع أنحاء المدينة على مدار الساعة', 'textarea', 'homepage', '2026-02-06 22:36:13'),
(11, 'hero_subtitle_fr', 'Nous vous offrons un transport sûr et confortable partout dans la ville 24/7', 'textarea', 'homepage', '2026-02-06 22:36:13'),
(12, 'about_title_ar', 'عن شركة فرسان الطريق', 'text', 'homepage', '2026-02-06 22:36:13'),
(13, 'about_title_fr', 'À propos de Les Chevaliers de Route', 'text', 'homepage', '2026-02-06 22:36:13'),
(14, 'about_text_ar', 'نحن شركة رائدة في مجال خدمات النقل والتاكسي، نفخر بتقديم خدمات عالية الجودة منذ أكثر من 10 سنوات. فريقنا من السائقين المحترفين مدربون على أعلى مستوى لضمان راحتك وأمانك.', 'textarea', 'homepage', '2026-02-06 22:36:13'),
(15, 'about_text_fr', 'Nous sommes une entreprise leader dans le domaine des services de transport et de taxi, fiers d\'offrir des services de haute qualité depuis plus de 10 ans. Notre équipe de chauffeurs professionnels est formée au plus haut niveau pour assurer votre confort et votre sécurité.', 'textarea', 'homepage', '2026-02-06 22:36:13'),
(16, 'why_us_1_title_ar', 'سائقون محترفون', 'text', 'features', '2026-02-06 22:36:13'),
(17, 'why_us_1_title_fr', 'Chauffeurs Professionnels', 'text', 'features', '2026-02-06 22:36:13'),
(18, 'why_us_1_desc_ar', 'جميع سائقينا مرخصون ومدربون', 'text', 'features', '2026-02-06 22:36:13'),
(19, 'why_us_1_desc_fr', 'Tous nos chauffeurs sont licenciés et formés', 'text', 'features', '2026-02-06 22:36:13'),
(20, 'why_us_2_title_ar', 'خدمة 24/7', 'text', 'features', '2026-02-06 22:36:13'),
(21, 'why_us_2_title_fr', 'Service 24/7', 'text', 'features', '2026-02-06 22:36:13'),
(22, 'why_us_2_desc_ar', 'متاحون في أي وقت تحتاجنا', 'text', 'features', '2026-02-06 22:36:13'),
(23, 'why_us_2_desc_fr', 'Disponibles à tout moment', 'text', 'features', '2026-02-06 22:36:13'),
(24, 'why_us_3_title_ar', 'أسعار تنافسية', 'text', 'features', '2026-02-06 22:36:13'),
(25, 'why_us_3_title_fr', 'Prix Compétitifs', 'text', 'features', '2026-02-06 22:36:13'),
(26, 'why_us_3_desc_ar', 'أفضل الأسعار في السوق', 'text', 'features', '2026-02-06 22:36:13'),
(27, 'why_us_3_desc_fr', 'Les meilleurs prix du marché', 'text', 'features', '2026-02-06 22:36:13'),
(28, 'why_us_4_title_ar', 'سيارات حديثة', 'text', 'features', '2026-02-06 22:36:13'),
(29, 'why_us_4_title_fr', 'Véhicules Modernes', 'text', 'features', '2026-02-06 22:36:13'),
(30, 'why_us_4_desc_ar', 'أسطول من السيارات المكيفة والمريحة', 'text', 'features', '2026-02-06 22:36:13'),
(31, 'why_us_4_desc_fr', 'Flotte de véhicules climatisés et confortables', 'text', 'features', '2026-02-06 22:36:13'),
(32, 'seo_title_ar', 'فرسان الطريق - خدمة تاكسي احترافية', 'text', 'seo', '2026-02-06 22:36:13'),
(33, 'seo_title_fr', 'Les Chevaliers de Route - Service de Taxi Professionnel', 'text', 'seo', '2026-02-06 22:36:13'),
(34, 'seo_description_ar', 'خدمة تاكسي موثوقة ومريحة على مدار الساعة. احجز الآن!', 'textarea', 'seo', '2026-02-06 22:36:13'),
(35, 'seo_description_fr', 'Service de taxi fiable et confortable disponible 24/7. Réservez maintenant!', 'textarea', 'seo', '2026-02-06 22:36:13'),
(36, 'seo_keywords', 'taxi, تاكسي, نقل, transport, الجزائر, Algérie', 'text', 'seo', '2026-02-06 22:36:13'),
(37, 'facebook_url', 'https://facebook.com', 'url', 'social', '2026-02-06 22:36:13'),
(38, 'instagram_url', 'https://instagram.com', 'url', 'social', '2026-02-06 22:36:13'),
(39, 'twitter_url', 'https://twitter.com', 'url', 'social', '2026-02-06 22:36:13'),
(40, 'google_maps_api_key', '', 'text', 'integrations', '2026-02-06 22:36:13'),
(41, 'google_maps_embed', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d102604.6074697395!2d-0.7196328639207038!3d35.70529683691681!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd7e8854841f4d8d%3A0x6c6e1d6d45f4961b!2sOran%2C%20Algeria!5e0!3m2!1sen!2s!4v1707500000000!5m2!1sen!2s', 'url', 'integrations', '2026-02-09 17:32:43');

-- --------------------------------------------------------

--
-- Structure de la table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `rating` int(11) DEFAULT 5 CHECK (`rating` >= 1 and `rating` <= 5),
  `review_ar` text NOT NULL,
  `review_fr` text NOT NULL,
  `customer_image` varchar(255) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `content` text DEFAULT NULL,
  `content_ar` text DEFAULT NULL,
  `content_fr` text DEFAULT NULL,
  `name_ar` varchar(100) DEFAULT NULL,
  `name_fr` varchar(100) DEFAULT NULL,
  `role_ar` varchar(100) DEFAULT NULL,
  `role_fr` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `testimonials`
--

INSERT INTO `testimonials` (`id`, `name`, `rating`, `review_ar`, `review_fr`, `customer_image`, `role`, `active`, `display_order`, `created_at`, `content`, `content_ar`, `content_fr`, `name_ar`, `name_fr`, `role_ar`, `role_fr`) VALUES
(7, NULL, 4, '', '', NULL, NULL, 1, 0, '2026-02-12 23:38:16', NULL, 'انا هو الافضل', 'je suis le méilleure', 'فارس', 'Fares', 'رئيس', 'président'),
(9, NULL, 5, '', '', NULL, NULL, 1, 0, '2026-02-13 17:49:43', NULL, 'فريق موهوب', 'une équipe professionelle', 'عادل', 'Adel', 'سائق تاكسي', 'taxieur');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) DEFAULT 'customer',
  `is_admin` tinyint(1) DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `name`, `email`, `password`, `role`, `is_admin`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'Adel', 'Adel', 'adel@gmail.com', '$2y$10$6..3ZUMTNjylEwXtw2EbEeT3pwu/.fxxI2V/2rjitULtC.c9wbU3y', 'admin', 1, '2026-02-07 00:30:22', '2026-02-06 22:36:13', '2026-02-09 20:16:37');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_id` (`service_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_phone` (`phone`),
  ADD KEY `idx_date` (`booking_date`),
  ADD KEY `idx_created` (`created_at`);

--
-- Index pour la table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created` (`created_at`);

--
-- Index pour la table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `idx_active` (`active`);

--
-- Index pour la table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`);

--
-- Index pour la table `pricing`
--
ALTER TABLE `pricing`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_active` (`active`),
  ADD KEY `fk_pricing_service` (`service_id`);

--
-- Index pour la table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_active` (`active`),
  ADD KEY `idx_order` (`display_order`);

--
-- Index pour la table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`),
  ADD KEY `idx_key` (`setting_key`),
  ADD KEY `idx_category` (`category`);

--
-- Index pour la table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_active` (`active`),
  ADD KEY `idx_rating` (`rating`),
  ADD KEY `idx_order` (`display_order`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT pour la table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `pricing`
--
ALTER TABLE `pricing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT pour la table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `pricing`
--
ALTER TABLE `pricing`
  ADD CONSTRAINT `fk_pricing_service` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
