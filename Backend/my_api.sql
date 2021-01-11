SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `produtos`
--

-- --------------------------------------------------------

--
-- Table structure for table `produtos`
--

CREATE TABLE `produtos` (
  `id` INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `product_name` varchar(50) NOT NULL,
  `cod_bar` varchar(50) NOT NULL,
  `price_buy` FLOAT(10,4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


INSERT INTO `produtos` (`product_name`, `cod_bar`, `price_buy`) VALUES
('Caracu', '0001', 5.0),
('Blumenau', '0002', 4.9),
('SuperBock', '0003', 6.0);