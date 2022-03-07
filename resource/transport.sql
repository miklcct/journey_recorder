-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: miklcct.com
-- Generation Time: Mar 02, 2022 at 05:16 PM
-- Server version: 10.5.12-MariaDB-0+deb11u1
-- PHP Version: 8.1.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `transport`
--

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` char(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `digits` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`name`, `code`, `digits`) VALUES
('UAE Dirham', 'AED', 2),
('Afghani', 'AFN', 2),
('Lek', 'ALL', 2),
('Armenian Dram', 'AMD', 2),
('Netherlands Antillean Guilder', 'ANG', 2),
('Kwanza', 'AOA', 2),
('Argentine Peso', 'ARS', 2),
('Australian Dollar', 'AUD', 2),
('Aruban Florin', 'AWG', 2),
('Azerbaijan Manat', 'AZN', 2),
('Convertible Mark', 'BAM', 2),
('Barbados Dollar', 'BBD', 2),
('Taka', 'BDT', 2),
('Bulgarian Lev', 'BGN', 2),
('Bahraini Dinar', 'BHD', 3),
('Burundi Franc', 'BIF', 0),
('Bermudian Dollar', 'BMD', 2),
('Brunei Dollar', 'BND', 2),
('Boliviano', 'BOB', 2),
('Mvdol', 'BOV', 2),
('Brazilian Real', 'BRL', 2),
('Bahamian Dollar', 'BSD', 2),
('Ngultrum', 'BTN', 2),
('Pula', 'BWP', 2),
('Belarusian Ruble', 'BYN', 2),
('Belize Dollar', 'BZD', 2),
('Canadian Dollar', 'CAD', 2),
('Congolese Franc', 'CDF', 2),
('WIR Euro', 'CHE', 2),
('Swiss Franc', 'CHF', 2),
('WIR Franc', 'CHW', 2),
('Unidad de Fomento', 'CLF', 4),
('Chilean Peso', 'CLP', 0),
('Yuan Renminbi', 'CNY', 2),
('Colombian Peso', 'COP', 2),
('Unidad de Valor Real', 'COU', 2),
('Costa Rican Colon', 'CRC', 2),
('Peso Convertible', 'CUC', 2),
('Cuban Peso', 'CUP', 2),
('Cabo Verde Escudo', 'CVE', 2),
('Czech Koruna', 'CZK', 2),
('Djibouti Franc', 'DJF', 0),
('Danish Krone', 'DKK', 2),
('Dominican Peso', 'DOP', 2),
('Algerian Dinar', 'DZD', 2),
('Egyptian Pound', 'EGP', 2),
('Nakfa', 'ERN', 2),
('Ethiopian Birr', 'ETB', 2),
('Euro', 'EUR', 2),
('Fiji Dollar', 'FJD', 2),
('Falkland Islands Pound', 'FKP', 2),
('Pound Sterling', 'GBP', 2),
('Lari', 'GEL', 2),
('Ghana Cedi', 'GHS', 2),
('Gibraltar Pound', 'GIP', 2),
('Dalasi', 'GMD', 2),
('Guinean Franc', 'GNF', 0),
('Quetzal', 'GTQ', 2),
('Guyana Dollar', 'GYD', 2),
('Hong Kong Dollar', 'HKD', 2),
('Lempira', 'HNL', 2),
('Kuna', 'HRK', 2),
('Gourde', 'HTG', 2),
('Forint', 'HUF', 2),
('Rupiah', 'IDR', 2),
('New Israeli Sheqel', 'ILS', 2),
('Indian Rupee', 'INR', 2),
('Iraqi Dinar', 'IQD', 3),
('Iranian Rial', 'IRR', 2),
('Iceland Krona', 'ISK', 0),
('Jamaican Dollar', 'JMD', 2),
('Jordanian Dinar', 'JOD', 3),
('Yen', 'JPY', 0),
('Kenyan Shilling', 'KES', 2),
('Som', 'KGS', 2),
('Riel', 'KHR', 2),
('Comorian Franc ', 'KMF', 0),
('North Korean Won', 'KPW', 2),
('Won', 'KRW', 0),
('Kuwaiti Dinar', 'KWD', 3),
('Cayman Islands Dollar', 'KYD', 2),
('Tenge', 'KZT', 2),
('Lao Kip', 'LAK', 2),
('Lebanese Pound', 'LBP', 2),
('Sri Lanka Rupee', 'LKR', 2),
('Liberian Dollar', 'LRD', 2),
('Loti', 'LSL', 2),
('Libyan Dinar', 'LYD', 3),
('Moroccan Dirham', 'MAD', 2),
('Moldovan Leu', 'MDL', 2),
('Malagasy Ariary', 'MGA', 2),
('Denar', 'MKD', 2),
('Kyat', 'MMK', 2),
('Tugrik', 'MNT', 2),
('Pataca', 'MOP', 2),
('Ouguiya', 'MRU', 2),
('Mauritius Rupee', 'MUR', 2),
('Rufiyaa', 'MVR', 2),
('Malawi Kwacha', 'MWK', 2),
('Mexican Peso', 'MXN', 2),
('Mexican Unidad de Inversion (UDI)', 'MXV', 2),
('Malaysian Ringgit', 'MYR', 2),
('Mozambique Metical', 'MZN', 2),
('Namibia Dollar', 'NAD', 2),
('Naira', 'NGN', 2),
('Cordoba Oro', 'NIO', 2),
('Norwegian Krone', 'NOK', 2),
('Nepalese Rupee', 'NPR', 2),
('New Zealand Dollar', 'NZD', 2),
('Rial Omani', 'OMR', 3),
('Balboa', 'PAB', 2),
('Sol', 'PEN', 2),
('Kina', 'PGK', 2),
('Philippine Peso', 'PHP', 2),
('Pakistan Rupee', 'PKR', 2),
('Zloty', 'PLN', 2),
('Guarani', 'PYG', 0),
('Qatari Rial', 'QAR', 2),
('Romanian Leu', 'RON', 2),
('Serbian Dinar', 'RSD', 2),
('Russian Ruble', 'RUB', 2),
('Rwanda Franc', 'RWF', 0),
('Saudi Riyal', 'SAR', 2),
('Solomon Islands Dollar', 'SBD', 2),
('Seychelles Rupee', 'SCR', 2),
('Sudanese Pound', 'SDG', 2),
('Swedish Krona', 'SEK', 2),
('Singapore Dollar', 'SGD', 2),
('Saint Helena Pound', 'SHP', 2),
('Leone', 'SLL', 2),
('Somali Shilling', 'SOS', 2),
('Surinam Dollar', 'SRD', 2),
('South Sudanese Pound', 'SSP', 2),
('Dobra', 'STN', 2),
('El Salvador Colon', 'SVC', 2),
('Syrian Pound', 'SYP', 2),
('Lilangeni', 'SZL', 2),
('Baht', 'THB', 2),
('Somoni', 'TJS', 2),
('Turkmenistan New Manat', 'TMT', 2),
('Tunisian Dinar', 'TND', 3),
('Pa’anga', 'TOP', 2),
('Turkish Lira', 'TRY', 2),
('Trinidad and Tobago Dollar', 'TTD', 2),
('New Taiwan Dollar', 'TWD', 2),
('Tanzanian Shilling', 'TZS', 2),
('Hryvnia', 'UAH', 2),
('Uganda Shilling', 'UGX', 0),
('US Dollar', 'USD', 2),
('US Dollar (Next day)', 'USN', 2),
('Uruguay Peso en Unidades Indexadas (UI)', 'UYI', 0),
('Peso Uruguayo', 'UYU', 2),
('Unidad Previsional', 'UYW', 4),
('Uzbekistan Sum', 'UZS', 2),
('Bolívar Soberano', 'VED', 2),
('Bolívar Soberano', 'VES', 2),
('Dong', 'VND', 0),
('Vatu', 'VUV', 0),
('Tala', 'WST', 2),
('CFA Franc BEAC', 'XAF', 0),
('Silver', 'XAG', NULL),
('Gold', 'XAU', NULL),
('Bond Markets Unit European Composite Unit (EURCO)', 'XBA', NULL),
('Bond Markets Unit European Monetary Unit (E.M.U.-6)', 'XBB', NULL),
('Bond Markets Unit European Unit of Account 9 (E.U.A.-9)', 'XBC', NULL),
('Bond Markets Unit European Unit of Account 17 (E.U.A.-17)', 'XBD', NULL),
('East Caribbean Dollar', 'XCD', 2),
('SDR (Special Drawing Right)', 'XDR', NULL),
('CFA Franc BCEAO', 'XOF', 0),
('Palladium', 'XPD', NULL),
('CFP Franc', 'XPF', 0),
('Platinum', 'XPT', NULL),
('Sucre', 'XSU', NULL),
('Test currency', 'XTS', NULL),
('ADB Unit of Account', 'XUA', NULL),
('No currency', 'XXX', NULL),
('Yemeni Rial', 'YER', 2),
('Rand', 'ZAR', 2),
('Zambian Kwacha', 'ZMW', 2),
('Zimbabwe Dollar', 'ZWL', 2);

-- --------------------------------------------------------

--
-- Table structure for table `journeys`
--

CREATE TABLE `journeys` (
  `serial` bigint(20) NOT NULL auto_increment primary key,
  `type` enum('Aeroplane','Helicopter','Train','Metro','Tram','Funicular','BRT','Bus','Trolleybus','Share taxi','Ferry','Cable Car') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `network` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `route` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `destination` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `boarding place` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alighting place` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cabin number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `boarding time stamp` timestamp NOT NULL,
  `boarding time offset minutes` smallint(6) NOT NULL DEFAULT 0,
  `alighting time stamp` timestamp NOT NULL,
  `alighting time offset minutes` smallint(6) NOT NULL DEFAULT 0,
  `time taken` time GENERATED ALWAYS AS (timediff(`alighting time stamp`,`boarding time stamp`)) STORED,
  `distance` decimal(5,2) DEFAULT NULL COMMENT 'in km',
  `speed` double GENERATED ALWAYS AS (`distance` / (unix_timestamp(`alighting time stamp`) - unix_timestamp(`boarding time stamp`)) * 60 * 60) STORED COMMENT 'in km/h'
) ;

--
-- Triggers `journeys`
--
DELIMITER $$
CREATE TRIGGER `check time does not overlap before insert` BEFORE INSERT ON `journeys` FOR EACH ROW if exists (
    select serial
    from journeys
    where new.`boarding time stamp` < journeys.`alighting time stamp` 
     	and new.`alighting time stamp` > journeys.`boarding time stamp`
)
then
    signal sqlstate '23000' set message_text = 'The time given overlaps with an existing record.';
end if
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `check time does not overlap before update` BEFORE UPDATE ON `journeys` FOR EACH ROW if exists (
    select serial
    from journeys
    where journeys.serial <> new.serial
    	and new.`boarding time stamp` < journeys.`alighting time stamp` 
     	and new.`alighting time stamp` > journeys.`boarding time stamp`
)
then
    signal sqlstate '23000' set message_text = 'The time given overlaps with an existing record.';
end if
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `check time is not in the future before insert` BEFORE INSERT ON `journeys` FOR EACH ROW if
	new.`boarding time stamp` > now()
    or new.`alighting time stamp` > now()
then
    signal sqlstate '23000' set message_text = 'The time given is in the future.';
end if
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `check time is not in the future before update` BEFORE UPDATE ON `journeys` FOR EACH ROW if
	new.`boarding time stamp` > now()
    or new.`alighting time stamp` > now()
then
    signal sqlstate '23000' set message_text = 'The time given is in the future.';
end if
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `journeys fare`
-- (See below for the actual view)
--
CREATE TABLE `journeys fare` (
`boarding time stamp` timestamp
,`type` enum('Aeroplane','Helicopter','Train','Metro','Tram','Funicular','BRT','Bus','Trolleybus','Share taxi','Ferry','Cable Car')
,`network` varchar(255)
,`route` varchar(255)
,`destination` varchar(255)
,`boarding place` varchar(255)
,`alighting place` varchar(255)
,`distance` decimal(5,2)
,`time taken` time
,`speed` double
,`fully ticketed` tinyint(1)
,`tickets count` int(10) unsigned
,`currency` varchar(3)
,`fare` decimal(58,10)
,`fare per km` decimal(64,14)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `ticket apportion`
-- (See below for the actual view)
--
CREATE TABLE `ticket apportion` (
`ticket serial` int(10) unsigned
,`journey serial` bigint(20)
,`currency` char(3)
,`fare` decimal(36,10)
);

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `serial` int(10) UNSIGNED NOT NULL auto_increment primary key,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency` char(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` bigint(20) NOT NULL COMMENT 'in smallest unit',
  `carnets` smallint(10) UNSIGNED NOT NULL DEFAULT 1,
  `expired` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `tickets view`
-- (See below for the actual view)
--
CREATE TABLE `tickets view` (
`serial` int(10) unsigned
,`description` varchar(255)
,`currency` char(3)
,`price` bigint(20)
,`carnets` smallint(10) unsigned
,`expired` tinyint(1)
,`first use` timestamp /* mariadb-5.3 */
,`last use` timestamp /* mariadb-5.3 */
,`carnets used` bigint(21)
,`segments travelled` bigint(21)
,`distance travelled` decimal(29,2)
,`price used` decimal(43,4)
,`price per km` decimal(49,8)
);

-- --------------------------------------------------------

--
-- Table structure for table `ticket uses`
--

CREATE TABLE `ticket uses` (
  `journey serial` bigint(20) NOT NULL,
  `ticket serial` int(10) UNSIGNED NOT NULL,
  `carnet sequence` smallint(6) NOT NULL DEFAULT 0 COMMENT '0-based',
  `cover from` decimal(7,2) DEFAULT NULL COMMENT 'in km',
  `cover to` decimal(7,2) DEFAULT NULL COMMENT 'in km',
  `distance covered` decimal(7,2) GENERATED ALWAYS AS (`cover to` - `cover from`) STORED
) ;

--
-- Triggers `ticket uses`
--
DELIMITER $$
CREATE TRIGGER `validate ticket use before insert` BEFORE INSERT ON `ticket uses` FOR EACH ROW begin
    declare distance decimal(7, 2);
    select journeys.distance into distance from journeys where serial = new.`journey serial`;

    if new.`cover from` >= ifnull(distance, 0) or new.`cover to` > ifnull(distance, 0)
    then
        signal sqlstate '23000' set message_text = 'Covered distance is out of bounds.';
    end if;

    if new.`carnet sequence` >= (select tickets.carnets from tickets where serial = new.`ticket serial`)
    then
        signal sqlstate '23000' set message_text = 'Carnet sequence is out of bounds.';
    end if;
end
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `validate ticket use before update` BEFORE UPDATE ON `ticket uses` FOR EACH ROW begin
    declare distance decimal(7, 2);
    select journeys.distance into distance from journeys where serial = new.`journey serial`;

    if new.`cover from` >= ifnull(distance, 0) or new.`cover to` > ifnull(distance, 0)
    then
        signal sqlstate '23000' set message_text = 'Covered distance is out of bounds.';
    end if;

    if new.`carnet sequence` >= (select tickets.carnets from tickets where serial = new.`ticket serial`)
    then
        signal sqlstate '23000' set message_text = 'Carnet sequence is out of bounds.';
    end if;
end
$$
DELIMITER ;

DELIMITER $$
--
-- Functions
--
CREATE DEFINER=`michael`@`%` FUNCTION `canonicalise route` (`route` VARCHAR(255)) RETURNS VARCHAR(255) CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci  return regexp_replace(regexp_replace(route, '(^|[^0-9])([0-9]{1,2})([^0-9]|$)', '\\10\\2\\3'), '(^|[^0-9])([0-9]{1,2})([^0-9]|$)', '\\10\\2\\3')$$

CREATE DEFINER=`michael`@`%` FUNCTION `get tickets count` (`journey serial` BIGINT UNSIGNED) RETURNS INT(10) UNSIGNED DETERMINISTIC return (select count(0) from `ticket uses` where `ticket uses`.`journey serial` = `journey serial`)$$

CREATE DEFINER=`michael`@`%` FUNCTION `is fully ticketed` (`journey serial` BIGINT UNSIGNED) RETURNS TINYINT(1)  return (
    select min(exists(select 0 from `ticket uses` where `ticket uses`.`journey serial` = `journey serial` and (`distance covered` is null or `split point` >= `cover from` and `split point` < `cover to`)))
    from (select 0 as `split point` union select `cover to` from `ticket uses` where `ticket uses`.`journey serial` = `journey serial` and `cover to` < (select distance from journeys where journeys.serial = `journey serial`)) distances
)$$

DELIMITER ;


-- --------------------------------------------------------

--
-- Structure for view `journeys fare`
--
DROP TABLE IF EXISTS `journeys fare`;

CREATE ALGORITHM=UNDEFINED DEFINER=`michael`@`%` SQL SECURITY DEFINER VIEW `journeys fare`  AS SELECT `journeys`.`boarding time stamp` AS `boarding time stamp`, `journeys`.`type` AS `type`, `journeys`.`network` AS `network`, `journeys`.`route` AS `route`, `journeys`.`destination` AS `destination`, `journeys`.`boarding place` AS `boarding place`, `journeys`.`alighting place` AS `alighting place`, `journeys`.`distance` AS `distance`, `journeys`.`time taken` AS `time taken`, `journeys`.`speed` AS `speed`, `is fully ticketed`(`journeys`.`serial`) AS `fully ticketed`, `get tickets count`(`journeys`.`serial`) AS `tickets count`, (select `ticket apportion`.`currency` from `ticket apportion` where `ticket apportion`.`journey serial` = `journeys`.`serial` limit 1) AS `currency`, (select sum(`ticket apportion`.`fare`) from `ticket apportion` where `ticket apportion`.`journey serial` = `journeys`.`serial`) AS `fare`, (select sum(`ticket apportion`.`fare`) from `ticket apportion` where `ticket apportion`.`journey serial` = `journeys`.`serial`) / `journeys`.`distance` AS `fare per km` FROM `journeys` WHERE `is fully ticketed`(`journeys`.`serial`) AND (select count(distinct ifnull(`ticket apportion`.`currency`,'XXX')) from `ticket apportion` where `ticket apportion`.`journey serial` = `journeys`.`serial`) = 1 ORDER BY `journeys`.`boarding time stamp` desc  ;

-- --------------------------------------------------------

--
-- Structure for view `ticket apportion`
--
DROP TABLE IF EXISTS `ticket apportion`;

CREATE ALGORITHM=UNDEFINED DEFINER=`michael`@`%` SQL SECURITY DEFINER VIEW `ticket apportion`  AS SELECT `ticket uses`.`ticket serial` AS `ticket serial`, `ticket uses`.`journey serial` AS `journey serial`, `tickets`.`currency` AS `currency`, ifnull(`ticket uses`.`distance covered`,(select `journeys`.`distance` from `journeys` where `journeys`.`serial` = `ticket uses`.`journey serial`)) / `summary`.`total distance covered` * `summary`.`price per carnet` AS `fare` FROM (((`ticket uses` left join `tickets` on(`ticket uses`.`ticket serial` = `tickets`.`serial`)) left join `journeys` on(`ticket uses`.`journey serial` = `journeys`.`serial`)) left join (select `ticket uses`.`ticket serial` AS `ticket serial`,`ticket uses`.`carnet sequence` AS `carnet sequence`,`tickets`.`price` / `tickets`.`carnets` AS `price per carnet`,sum(ifnull(`ticket uses`.`distance covered`,(select `journeys`.`distance` from `journeys` where `journeys`.`serial` = `ticket uses`.`journey serial`))) AS `total distance covered` from (`ticket uses` left join `tickets` on(`ticket uses`.`ticket serial` = `tickets`.`serial`)) group by `ticket uses`.`ticket serial`,`ticket uses`.`carnet sequence`) `summary` on(`ticket uses`.`ticket serial` = `summary`.`ticket serial` and `ticket uses`.`carnet sequence` = `summary`.`carnet sequence`))  ;

-- --------------------------------------------------------

--
-- Structure for view `tickets view`
--
DROP TABLE IF EXISTS `tickets view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`michael`@`%` SQL SECURITY DEFINER VIEW `tickets view`  AS SELECT `tickets`.`serial` AS `serial`, `tickets`.`description` AS `description`, `tickets`.`currency` AS `currency`, `tickets`.`price` AS `price`, `tickets`.`carnets` AS `carnets`, `tickets`.`expired` AS `expired`, `tickets info`.`first use` AS `first use`, `tickets info`.`last use` AS `last use`, `tickets info`.`carnets used` AS `carnets used`, `tickets info`.`segments travelled` AS `segments travelled`, `tickets info`.`distance travelled` AS `distance travelled`, `tickets`.`price`/ `tickets`.`carnets` * `tickets info`.`carnets used` AS `price used`, `tickets`.`price`/ `tickets`.`carnets` * `tickets info`.`carnets used` / `tickets info`.`distance travelled` AS `price per km` FROM (`tickets` join (select `tickets`.`serial` AS `_serial`,(select min(`journeys`.`boarding time stamp`) from (`ticket uses` join `journeys` on(`ticket uses`.`journey serial` = `journeys`.`serial`)) where `ticket uses`.`ticket serial` = `tickets`.`serial`) AS `first use`,(select max(`journeys`.`alighting time stamp`) from (`ticket uses` join `journeys` on(`ticket uses`.`journey serial` = `journeys`.`serial`)) where `ticket uses`.`ticket serial` = `tickets`.`serial`) AS `last use`,(select count(distinct `ticket uses`.`carnet sequence`) from `ticket uses` where `ticket uses`.`ticket serial` = `tickets`.`serial`) AS `carnets used`,(select count(distinct `ticket uses`.`journey serial`) from `ticket uses` where `ticket uses`.`ticket serial` = `tickets`.`serial`) AS `segments travelled`,(select ifnull(sum(ifnull(`ticket uses`.`distance covered`,`journeys`.`distance`)),0) from (`ticket uses` join `journeys` on(`ticket uses`.`journey serial` = `journeys`.`serial`)) where `ticket uses`.`ticket serial` = `tickets`.`serial`) AS `distance travelled` from `tickets`) `tickets info` on(`tickets`.`serial` = `tickets info`.`_serial`)) ORDER BY `tickets info`.`last use` desc  ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`code`);

--
-- Indexes for table `journeys`
--
ALTER TABLE `journeys`
  ADD KEY `boarding time stamp` (`boarding time stamp`),
  ADD KEY `alighting time stamp` (`alighting time stamp`),
  ADD KEY `speed` (`speed`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD KEY `currency` (`currency`);

--
-- Indexes for table `ticket uses`
--
ALTER TABLE `ticket uses`
  ADD PRIMARY KEY (`journey serial`,`ticket serial`),
  ADD KEY `ticket uses_ibfk_1` (`ticket serial`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `journeys`
--
ALTER TABLE `journeys`
  MODIFY `serial` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `serial` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`currency`) REFERENCES `currencies` (`code`) ON UPDATE CASCADE;

--
-- Constraints for table `ticket uses`
--
ALTER TABLE `ticket uses`
  ADD CONSTRAINT `ticket uses_ibfk_1` FOREIGN KEY (`ticket serial`) REFERENCES `tickets` (`serial`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ticket uses_ibfk_2` FOREIGN KEY (`journey serial`) REFERENCES `journeys` (`serial`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
