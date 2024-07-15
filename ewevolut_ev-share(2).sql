-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 26, 2024 at 11:56 AM
-- Server version: 10.6.17-MariaDB-cll-lve
-- PHP Version: 8.1.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ewevolut_ev-share`
--

-- --------------------------------------------------------

--
-- Table structure for table `car`
--

DROP TABLE IF EXISTS `car`;
CREATE TABLE `car` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `car_brand` varchar(255) NOT NULL,
  `license_plate` varchar(255) NOT NULL,
  `model` varchar(255) NOT NULL,
  `charging_type` varchar(255) NOT NULL,
  `year` varchar(255) NOT NULL,
  `battery_capacity` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `car`
--

Sure, here is the updated SQL insert statements with the "kWh" removed from the `battery_capacity` field:

```sql
INSERT INTO `car` (`id`, `user_id`, `car_brand`, `license_plate`, `model`, `charging_type`, `year`, `battery_capacity`) VALUES 
(2, 2, 'TESLA', '1234567', 'Model S', 'Supercharger', 2022, '100'),
(3, 3, 'FIAT', '2345678', '500e', 'CCS', 2021, '23.8'),
(4, 4, 'MG', '3456789', 'ZS EV', 'CCS', 2020, '44.5'),
(5, 5, 'KIA', '4567890', 'Niro EV', 'CCS', 2021, '64'),
(6, 6, 'HYUNDAI', '5678901', 'Kona Electric', 'CCS', 2022, '64'),
(7, 7, 'SKYWELL', '6789012', 'ET5', 'CCS', 2021, '72'),
(8, 8, 'OPEL', '7890123', 'Corsa-e', 'CCS', 2021, '50'),
(9, 9, 'VOLVO', '8901234', 'XC40 Recharge', 'CCS', 2022, '78'),
(10, 10, 'AUDI', '9012345', 'e-tron', 'CCS', 2021, '95'),
(11, 11, 'SKODA', '0123456', 'Enyaq iV', 'CCS', 2021, '82'),
(12, 12, 'DS3', '11234567', 'Crossback E-Tense', 'CCS', 2022, '50'),
(13, 13, 'PORSCHE', '22345678', 'Taycan', 'CCS', 2022, '93.4'),
(14, 14, 'JAGUAR', '33456789', 'I-Pace', 'CCS', 2021, '90'),
(15, 15, 'MAXUS', '44567890', 'Euniq 5', 'CCS', 2022, '52.5'),
(16, 16, 'CITROEN', '55678901', 'e-C4', 'CCS', 2021, '50'),
(17, 17, 'MINI', '66789012', 'Electric', 'CCS', 2021, '32.6'),
(18, 18, 'AIWAYS', '77890123', 'U5', 'CCS', 2022, '63'),
(19, 19, 'BMW', '88901234', 'i3', 'CCS', 2020, '42.2'),
(20, 20, 'GENESIS', '99012345', 'GV60', 'CCS', 2022, '77.4'),
(21, 21, 'MERCEDES', '10123456', 'EQC', 'CCS', 2021, '80'),
(22, 22, 'SERES', '21234567', 'SF5', 'CCS', 2022, '90'),
(23, 23, 'BYD', '32345678', 'Tang EV', 'CCS', 2021, '86.4'),
(24, 24, 'GEELY', '43456789', 'Geometry A', 'CCS', 2020, '61.9'),
(25, 25, 'VOLKSWAGEN', '54567890', 'ID.4', 'CCS', 2022, '82'),
(26, 26, 'CHEVROLET', '65678901', 'Bolt EV', 'CCS', 2021, '66'),
(27, 27, 'GMC', '76789012', 'Hummer EV', 'CCS', 2022, '200'),
(28, 28, 'CADILLAC', '87890123', 'Lyriq', 'CCS', 2022, '100'),
(29, 29, 'PEUGEOT', '98901234', 'e-208', 'CCS', 2021, '50'),
(30, 30, 'JEEP', '10134567', 'Wrangler 4xe', 'CCS', 2021, '17.3'),
(31, 31, 'NISSAN', '21245678', 'Leaf', 'CHAdeMO', 2021, '62'),
(32, 32, 'RIVIAN', '32356789', 'R1T', 'CCS', 2022, '135'),
(33, 33, 'NIO', '43467890', 'ES8', 'CCS', 2022, '100'),
(34, 34, 'ORA', '54578901', 'Good Cat', 'CCS', 2021, '63'),
(35, 35, 'XPENG', '65689012', 'P7', 'CCS', 2021, '80.9'),
(36, 36, 'Eveasy', '76790123', 'EV300', 'CCS', 2022, '50'),
(37, 37, 'WEY', '87801234', 'VV7 PHEV', 'CCS', 2022, '84.3'),
(38, 38, 'HONGQI', '98912345', 'E-HS9', 'CCS', 2021, '99'),
(39, 39, 'WM', '10123456', 'EX5-Z', 'CCS', 2021, '69'),
(40, 40, 'VOYAH', '21234567', 'Free', 'CCS', 2022, '88'),
(41, 41, 'ZEEKER', '32345678', '001', 'CCS', 2022, '100'),
(42, 42, 'LYNK & CO', '43456789', '01 PHEV', 'CCS', 2021, '17.6'),
(43, 43, 'LEAP MOTOR', '54567890', 'C11', 'CCS', 2022, '90'),
(44, 44, 'NETA', '65678901', 'U Pro', 'CCS', 2021, '61.2'),
(45, 45, 'CHERY', '76789012', 'eQ5', 'CCS', 2021, '70'),
(46, 46, 'FORTHING', '87890123', 'FRIDAY', 'CCS', 2022, '85.9');
```

These entries now have the battery capacities without the "kWh" suffix.

-- --------------------------------------------------------

--
-- Table structure for table `station`
--

DROP TABLE IF EXISTS `station`;
CREATE TABLE `station` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `station_name` varchar(255) NOT NULL,
  `station_model` varchar(255) NOT NULL,
  `station_year` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `charging_type` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `charging_capacity` varchar(255) NOT NULL,
  `how_to_find` varchar(255) NOT NULL,
  `asking_price` varchar(255) NOT NULL,
  `algo_price` varchar(255) NOT NULL,
  `latitude` varchar(255) NOT NULL,
  `longitude` varchar(255) NOT NULL,
  `image1` varchar(255) DEFAULT NULL,
  `image2` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `station`
--

INSERT INTO `station` (`id`, `user_id`, `station_name`, `station_model`, `station_year`, `address`, `charging_type`, `city`, `charging_capacity`, `how_to_find`, `asking_price`, `algo_price`, `latitude`, `longitude`, `image1`, `image2`) VALUES
(2, 2, 'Mivtsa Kadesh Charger', 'Gen 3', '2020', '32 Mivtsa Kadesh', 'CCS', 'Tel Aviv', '25', 'Near the dog park entrance', '4', '3.5', '32.113563', '34.815640',NULL, NULL),
(4, 2, 'Yeshiva EV', 'Gen 3', '2023', '44 Mivtsa Kadesh', 'CCS', 'Tel Aviv', '25', 'Next to the Yeshiva entrance', '4', '3.6', '32.113270', '34.820007',NULL, NULL),
(5, 2, 'Dizi Charge', 'Gen 2', '2019', '123 Dizengoff St', 'CHAdeMO', 'Tel Aviv', '20', 'Across from Central Park', '3', '2.5', '32.081248', '34.773634',NULL, NULL),
(6, 2, 'Rothschild EV Station', 'Gen 1', '2018', '456 Rothschild Blvd', 'CCS', 'Tel Aviv', '15', 'Next to the grocery store', '2.5', '2.2', '32.065369', '34.776622',NULL, NULL),
(7, 7, 'EV Gabirol Station', 'Gen 4', '2022', '789 Ibn Gabirol St', 'TESLA 3', 'Tel Aviv', '30', 'Parking lot level 3', '5', '4.8', '32.097233', '34.783638',NULL, NULL),
(8, 2, 'AFEKA EV STATION', 'Gen 2', '2021', '36 Mivtsa Kadesh', 'CCS', 'Tel Aviv', '25', 'Near the dog park entrance', '4', '3.5', '32.113563', '34.817499',NULL, NULL),
(9, 2, 'Neot Afeka Charge', 'Gen 3', '2020', '40 Mivtsa Kadesh', 'TESLA 2', 'Tel Aviv', '35', 'Dirt parking lot', '6', '5.5', '32.113436', '34.818929',NULL, NULL),
(10, 2, 'Beachside Charge', 'Gen 2', '2019', 'HaYarkon St 114', 'CHAdeMO', 'Tel Aviv', '18', 'Near Avis', '4', '3.8', '32.080858', '34.768473',NULL, NULL),
(11, 7, 'Kikar Hamedina Station', 'Gen 1', '2017', '29 Moshe Sharet St', 'CCS', 'Tel Aviv', '12', 'Building 5', '2', '1.9', '32.086894', '34.787561',NULL, NULL),
(12, 7, 'Campus Charge', 'Gen 4', '2023', '8 Torczyner', 'TESLA 3', 'Tel Aviv', '40', 'Gate 7A', '7', '6.5', '32.108696', '34.800228',NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` enum('Station_Owner','Car_Owner','','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `phone`, `email`, `address`, `password`, `user_type`) VALUES
(1, 'Test Car Owner', '03244202186', 'CO@gmail.com', '123 Street', '$2y$10$vI3bp5inBciVZOUxpq/XjuZDEIXOu3F/U7JRtBKjhMI7JgFIXC3Le', 'Car_Owner'),
(2, 'Station Owner', '03244202188', 'so@gmail.com', '123', '2', 'Station_Owner'),
(3, 'Ali Safdar', '033341678489', 'test@gmail.com', 'Addres', '$2y$10$eYhsh34BCjvWliR5nbNS7ejhFyTgcjhd9GFx91GBUs8Y4A1LiXJp6', 'Station_Owner'),
(4, 'New Station Owner', '03244202185', 'tester@gmail.com', 'Address', '$2y$10$GS2Mof1Qbyva53xUaIHP2OzV1KM9SoJcY0wmrzXqXJeUv40eMNYrK', 'Station_Owner'),
(5, 'Nir Srour', '0501234562', 'email@email.com', 'Hayarkon 2 , tel aviv', '$2y$10$S3pvxFX8grCvhG9rMfGh0.9cGb/tglx1MGN6ruk6n8ENEniVYTE.q', 'Station_Owner'),
(6, 'Efi Leder', '0543007148', 'Efik88@gmail.com', '×”×§×•× ×’×¨×¡ 27 ×ª×œ ××‘×™×‘', '$2y$10$rB8CMhECm2PFrxDRPsf0DupzKWbykNQVs5vTkRuyL4WlSrGH5PIA.', 'Station_Owner'),
(7, 'FS', '03242415898', 'fs@gmail.com', 'abc', '$2y$10$htE5Nl6yZfev8XaZ5QJn2uSpamcDO64.PGXIENnz0PkGRuZbWWCiS', 'Station_Owner');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `car`
--
ALTER TABLE `car`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `station`
--
ALTER TABLE `station`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `car`
--
ALTER TABLE `car`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `station`
--
ALTER TABLE `station`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
