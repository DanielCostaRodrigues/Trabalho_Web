-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 24-Jan-2025 às 13:45
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `grupo106`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `size` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`) VALUES
(1, 'Masculino', '2024-12-13 03:04:56'),
(2, 'Feminino', '2024-12-13 03:04:56');

-- --------------------------------------------------------

--
-- Estrutura da tabela `order_history`
--

CREATE TABLE `order_history` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_method` varchar(50) NOT NULL,
  `delivery_address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `category_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `sizes` varchar(255) DEFAULT NULL,
  `sub_category` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `stock`, `category_id`, `created_at`, `sizes`, `sub_category`, `active`) VALUES
(5, 'Camisa Branca Casual', 'Camisa branca casual perfeita para um dia ensolarado!', 40.00, '../images/camisabrancah.jpg', 10, 1, '2024-12-13 03:05:39', 'XS,S,M,L,XL,XXL', 'Parte Superior', 1),
(6, 'Camisa Azul Formal', 'Camisa azul formal ideal para reuniões e eventos.', 45.00, '../images/camisaazul.jpg', 98, 1, '2024-12-13 03:05:39', 'XS,S,M,L,XL,XXL', 'Parte Superior', 1),
(7, 'Vestido Floral', 'Um lindo vestido floral perfeito para o verão.', 70.00, '../images/vestidofloral.jpg', 100, 2, '2024-12-13 03:05:39', 'XS,S,M,L,XL,XXL', 'Parte Superior', 1),
(8, 'Blusa de Cetim', 'Blusa de cetim elegante e confortável.', 50.00, '../images/blusa.jpg', 100, 2, '2024-12-13 03:05:39', 'XS,S,M,L,XL,XXL', 'Parte Superior', 1),
(10, 'Saia Midi Bege', 'Descrição do produto', 60.00, '../images/saia.jpg', 99, 2, '2024-12-13 21:27:54', 'XS,S,M,L,XL,XXL', 'Parte Inferior', 1),
(11, 'Casaco Feminino Cinza', 'Descrição do produto', 110.00, '../images/casaco.jpg', 98, 2, '2024-12-13 21:27:54', 'XS,S,M,L,XL,XXL', 'Exterior', 1),
(12, 'Calções de Linho', 'Descrição do produto', 45.00, '../images/calcaolinho.jpg', 100, 2, '2024-12-13 21:27:54', 'XS,S,M,L,XL,XXL', 'Parte Inferior', 1),
(13, 'Calças Flare', 'Descrição do produto', 55.00, '../images/calçasflare.jpg', 100, 2, '2024-12-13 21:27:54', 'XS,S,M,L,XL,XXL', 'Parte Inferior', 1),
(14, 'Camisa Branca', 'Descrição do produto', 50.00, '../images/camisabranca.jpg', 100, 2, '2024-12-13 21:27:54', 'XS,S,M,L,XL,XXL', 'Parte Superior', 1),
(15, 'Casaco Ganga Feminina', 'Descrição do produto', 80.00, '../images/casacoganga.jpg', 100, 2, '2024-12-13 21:27:54', 'XS,S,M,L,XL,XXL', 'Exterior', 1),
(16, 'Hoodie Bege', 'Descrição do produto', 50.00, '../images/sweatbege.jpg', 0, 1, '2024-12-13 21:27:54', 'XS,S,M,L,XL,XXL', 'Parte Superior', 1),
(17, 'Casaco Clássico Castanho', 'Descrição do produto', 120.00, '../images/casacocastanho.jpg', 96, 1, '2024-12-13 21:27:54', 'XS,S,M,L,XL,XXL', 'Exterior', 1),
(18, 'Calção Linho', 'Descrição do produto', 45.00, '../images/calcaolinhoh.jpg', 100, 1, '2024-12-13 21:27:54', 'XS,S,M,L,XL,XXL', 'Parte Inferior', 1),
(19, 'Calças Pretas', 'Descrição do produto', 55.00, '../images/calpreta.jpg', 100, 1, '2024-12-13 21:27:54', 'XS,S,M,L,XL,XXL', 'Parte Inferior', 1),
(20, 'Camisa Listrada', 'Descrição do produto', 50.00, '../images/camisalistrada.jpg', 100, 1, '2024-12-13 21:27:54', 'XS,S,M,L,XL,XXL', 'Parte Superior', 1),
(21, 'Camisa Polo Preta', 'Descrição do produto', 60.00, '../images/campolopreta.jpg', 100, 1, '2024-12-13 21:27:54', 'XS,S,M,L,XL,XXL', 'Parte Superior', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `product_reviews`
--

CREATE TABLE `product_reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `address` varchar(255) DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `address`, `is_admin`, `active`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$IlF3YUUffBcNUyjipSUfVeGv2L1POBRNLEqsZhy44csyfYDLbPG6W', '2024-12-16 16:33:09', NULL, 1, 1),
(2, 'user', 'user@gmail.com', '$2y$10$Ic57PxHSi7DU66AHgWmDYeJk77vrSc3H.97NlJDDMsY2pHogHCy7y', '2024-12-22 00:16:55', 'viseu', 0, 1);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Índices para tabela `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `order_history`
--
ALTER TABLE `order_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Índices para tabela `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Índices para tabela `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Índices para tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=158;

--
-- AUTO_INCREMENT de tabela `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `order_history`
--
ALTER TABLE `order_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT de tabela `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de tabela `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `order_history`
--
ALTER TABLE `order_history`
  ADD CONSTRAINT `order_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_history_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD CONSTRAINT `product_reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `product_reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
