SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

INSERT INTO `category` (`id`, `title`) VALUES
(1, 'Men'),
(2, 'Women'),
(3, 'Kids'),
(4, 'Accessories'),
(5, 'Featured'),
(6, 'Hot deals');

CREATE TABLE IF NOT EXISTS `categoryTree` (
  `ancestor` int(11) NOT NULL,
  `descendant` int(11) NOT NULL,
  `nearestAncestor` int(11) NOT NULL DEFAULT '0',
  `level` smallint(6) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `categoryTree` (`ancestor`, `descendant`, `nearestAncestor`, `level`) VALUES
(1, 1, 0, 0),
(2, 2, 0, 0),
(3, 3, 0, 0),
(4, 4, 0, 0),
(5, 5, 0, 0),
(6, 6, 0, 0);

CREATE TABLE IF NOT EXISTS `customer` (
  `id` int(11) NOT NULL,
  `login` varchar(32) NOT NULL,
  `password` varchar(60) DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO `customer` (`id`, `login`, `password`, `is_admin`) VALUES
(1, 'admin', '$2y$10$1QG9hxl0FJ6wbgeONs3z.edDNHpYOE31Vv/sAL4gVKDsIjy/xSBvS', 1),
(3, 'user', '$2y$10$6huXdJ25vjgOQyk26sImM.XQ7lwpqufWSi4NhXWJ/5OVKOb9V48bS', 0);

CREATE TABLE IF NOT EXISTS `order` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `completed` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

INSERT INTO `order` (`id`, `customer_id`, `datetime`, `completed`) VALUES
(2, 1, '2017-08-07 17:04:26', 1),
(4, 3, '2017-08-07 18:15:07', 0),
(5, 1, '2017-08-07 18:59:41', 1),
(6, 1, '2017-08-07 19:02:43', 1),
(7, 1, '2017-08-07 19:03:25', 1),
(8, 1, '2017-08-07 21:18:07', 0);

CREATE TABLE IF NOT EXISTS `order_product` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `number` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

INSERT INTO `order_product` (`id`, `order_id`, `product_id`, `number`) VALUES
(17, 4, 8, 1),
(18, 4, 3, 2),
(19, 2, 8, 2),
(20, 2, 2, 1),
(21, 5, 2, 1),
(22, 5, 3, 1),
(23, 6, 4, 1),
(24, 7, 7, 1);

CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT 'product',
  `description` text,
  `price` float NOT NULL DEFAULT '0',
  `img_large` varchar(255) DEFAULT '/img/product/large/placeholder.png',
  `img_small` varchar(255) DEFAULT '/img/product/small/placeholder.png',
  `alt` varchar(32) DEFAULT 'product'
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

INSERT INTO `product` (`id`, `title`, `description`, `price`, `img_large`, `img_small`, `alt`) VALUES
(1, 'Product 1', 'Product 1 description', 111, '/img/product/large/item1.png', '/img/product/small/item1.png', 'Product 1'),
(2, 'Product 2', 'Product 2 description', 222, '/img/product/large/item2.png', '/img/product/small/item2.png', 'Product 2'),
(3, 'Product 3', 'Product 3 description', 333, '/img/product/large/item3.png', '/img/product/small/item3.png', 'Product 3'),
(4, 'Product 4', 'Product 4 description', 500, '/img/product/large/item4.png', '/img/product/small/item4.png', 'Product 4'),
(5, 'Product 5', 'Product 5 description', 400, '/img/product/large/item5.png', '/img/product/small/item5.png', 'Product 5'),
(6, 'Product 6', 'Product 6 description', 300, '/img/product/large/item6.png', '/img/product/small/item6.png', 'Product 6'),
(7, 'Product 7', 'Product 7 description', 360, '/img/product/large/item7.png', '/img/product/small/item7.png', 'Product 7'),
(8, 'Product 8', 'Product 8 description', 800, '/img/product/large/item8.png', '/img/product/small/item8.png', 'Product 8');

CREATE TABLE IF NOT EXISTS `sessions` (
  `sid` varchar(32) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `last_updated` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `categoryTree`
  ADD PRIMARY KEY (`ancestor`,`descendant`),
  ADD KEY `descendant` (`descendant`),
  ADD KEY `main` (`ancestor`,`descendant`,`nearestAncestor`,`level`),
  ADD KEY `nearestAncestor` (`nearestAncestor`);

ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`);

ALTER TABLE `order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_customer_id_fk` (`customer_id`);

ALTER TABLE `order_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_product_order_id_fk` (`order_id`),
  ADD KEY `order_product_product_id_fk` (`product_id`);

ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `sessions`
  ADD KEY `sessions_sid_index` (`sid`);

ALTER TABLE `categoryTree`
  ADD CONSTRAINT `categoryTree_ibfk_1` FOREIGN KEY (`ancestor`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `categoryTree_ibfk_2` FOREIGN KEY (`descendant`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `order`
  ADD CONSTRAINT `order_customer_id_fk` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `order_product`
  ADD CONSTRAINT `order_product_order_id_fk` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_product_product_id_fk` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
