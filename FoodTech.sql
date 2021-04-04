-- -------------------------------------------------------------
-- TablePlus 3.1.0(290)
--
-- https://tableplus.com/
--
-- Database: FoodTech
-- Generation Time: 2020-03-14 22:07:44.5950
-- -------------------------------------------------------------


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


DROP TABLE IF EXISTS `Address`;
CREATE TABLE `Address` (
  `AddressID` bigint(20) NOT NULL AUTO_INCREMENT,
  `Street1` varchar(250) DEFAULT NULL,
  `Street2` varchar(250) DEFAULT NULL,
  `City` varchar(150) DEFAULT NULL,
  `State` varchar(150) DEFAULT NULL,
  `ZipCode` varchar(150) DEFAULT NULL,
  `Latitude` bigint(20) DEFAULT NULL,
  `Longitude` bigint(20) DEFAULT NULL,
  `PlaceID` varchar(500) DEFAULT NULL,
  `CreationDate` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`AddressID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `ETLLoad`;
CREATE TABLE `ETLLoad` (
  `EtlLoadID` bigint(20) NOT NULL AUTO_INCREMENT,
  `ChainID` bigint(20) DEFAULT NULL,
  `CreatedDate` datetime DEFAULT CURRENT_TIMESTAMP,
  `FileLoadTypeID` int(11) DEFAULT NULL,
  `FileName` varchar(500) DEFAULT NULL,
  `Status` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`EtlLoadID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `ItemImage`;
CREATE TABLE `ItemImage` (
  `ItemImageID` bigint(20) NOT NULL AUTO_INCREMENT,
  `ItemBarcode` bigint(20) DEFAULT NULL,
  `CreatedDate` datetime DEFAULT CURRENT_TIMESTAMP,
  `ItemImageSRC` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`ItemImageID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `Items`;
CREATE TABLE `Items` (
  `ItemID` bigint(20) NOT NULL AUTO_INCREMENT,
  `ItemCode` bigint(20) DEFAULT NULL,
  `PriceUpdateDate` datetime DEFAULT NULL,
  `ItemName` varchar(50) DEFAULT NULL,
  `ItemPrice` double(9,2) unsigned zerofill DEFAULT NULL,
  `CurrencyCode` tinyint(4) DEFAULT NULL,
  `SourceTypeID` int(11) DEFAULT NULL,
  `ETLLoadID` bigint(20) DEFAULT NULL,
  `RefStoreID` bigint(20) DEFAULT NULL,
  `ItemImageID` bigint(20) DEFAULT NULL,
  `ManufacturerName` varchar(250) DEFAULT NULL,
  `ManufactureCountry` varchar(250) DEFAULT NULL,
  `ManufacturerItemDescription` varchar(250) DEFAULT NULL,
  `UnitQty` varchar(50) DEFAULT NULL,
  `Quantity` varchar(50) DEFAULT NULL,
  `UnitOfMeasure` varchar(50) DEFAULT NULL,
  `PromotionDescription` varchar(500) DEFAULT NULL,
  `PromotionStartDate` date DEFAULT NULL,
  `PromotionEndDate` date DEFAULT NULL,
  `PromotionStartHour` time DEFAULT NULL,
  `PromotionEndHour` time DEFAULT NULL,
  PRIMARY KEY (`ItemID`),
  KEY `RefStoreID` (`RefStoreID`),
  KEY `ItemImageID` (`ItemImageID`),
  KEY `CurrencyCode` (`CurrencyCode`),
  KEY `SourceTypeID` (`SourceTypeID`),
  KEY `ETLLoadID` (`ETLLoadID`)
  -- CONSTRAINT `items_ibfk_1` FOREIGN KEY (`RefStoreID`) REFERENCES `RefStore` (`RefStoreID`) ON DELETE CASCADE ON UPDATE CASCADE,
  -- CONSTRAINT `items_ibfk_2` FOREIGN KEY (`ItemImageID`) REFERENCES `ItemImage` (`ItemImageID`) ON DELETE SET NULL ON UPDATE CASCADE,
  -- CONSTRAINT `items_ibfk_3` FOREIGN KEY (`CurrencyCode`) REFERENCES `RefCurrency` (`CurrencyCode`) ON DELETE SET NULL ON UPDATE CASCADE,
  -- CONSTRAINT `items_ibfk_4` FOREIGN KEY (`SourceTypeID`) REFERENCES `RefSourceType` (`SourceTypeID`) ON DELETE SET NULL ON UPDATE CASCADE,
  -- CONSTRAINT `items_ibfk_5` FOREIGN KEY (`SourceTypeID`) REFERENCES `RefSourceType` (`SourceTypeID`) ON DELETE SET NULL ON UPDATE CASCADE,
  -- CONSTRAINT `items_ibfk_6` FOREIGN KEY (`SourceTypeID`) REFERENCES `RefSourceType` (`SourceTypeID`) ON DELETE SET NULL ON UPDATE CASCADE,
  -- CONSTRAINT `items_ibfk_7` FOREIGN KEY (`ETLLoadID`) REFERENCES `ETLLoad` (`EtlLoadID`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `Phone`;
CREATE TABLE `Phone` (
  `PhoneID` bigint(20) NOT NULL AUTO_INCREMENT,
  `PhoneType` int(11) DEFAULT NULL,
  `PhoneNumber` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`PhoneID`),
  KEY `PhoneType` (`PhoneType`)
  -- CONSTRAINT `phone_ibfk_1` FOREIGN KEY (`PhoneType`) REFERENCES `RefPhoneType` (`PhoneTypeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `RefChain`;
CREATE TABLE `RefChain` (
  `ChainID` bigint(20) NOT NULL AUTO_INCREMENT,
  `ChainName` varchar(250) DEFAULT NULL,
  `ChainHQAddressID` bigint(20) DEFAULT NULL,
  `ChainHQPhoneID` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`ChainID`),
  KEY `ChainHQAddressID` (`ChainHQAddressID`),
  KEY `ChainHQPhoneID` (`ChainHQPhoneID`)
  -- CONSTRAINT `refchain_ibfk_1` FOREIGN KEY (`ChainHQAddressID`) REFERENCES `Address` (`AddressID`) ON DELETE SET NULL ON UPDATE CASCADE,
  -- CONSTRAINT `refchain_ibfk_2` FOREIGN KEY (`ChainHQPhoneID`) REFERENCES `Phone` (`PhoneID`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `RefCurrency`;
CREATE TABLE `RefCurrency` (
  `CurrencyCode` tinyint(4) NOT NULL,
  `CurrencyName` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`CurrencyCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `RefLoadType`;
CREATE TABLE `RefLoadType` (
  `FileLoadTypeID` int(11) NOT NULL AUTO_INCREMENT,
  `LoadTypeDescription` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`FileLoadTypeID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `RefPhoneType`;
CREATE TABLE `RefPhoneType` (
  `PhoneTypeID` int(11) NOT NULL AUTO_INCREMENT,
  `PhoneTypeName` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`PhoneTypeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `RefSourceType`;
CREATE TABLE `RefSourceType` (
  `SourceTypeID` int(11) NOT NULL AUTO_INCREMENT,
  `SourceTypeName` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`SourceTypeID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `RefStore`;
CREATE TABLE `RefStore` (
  `RefStoreID` bigint(20) NOT NULL AUTO_INCREMENT,
  `StoreID` bigint(20) DEFAULT NULL,
  `ChainID` bigint(20) DEFAULT NULL,
  `StoreName` varchar(250) DEFAULT NULL,
  `StoreAddressID` bigint(20) DEFAULT NULL,
  `StorePhoneID` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`RefStoreID`),
  KEY `ChainID` (`ChainID`),
  KEY `StoreAddressID` (`StoreAddressID`),
  KEY `StorePhoneID` (`StorePhoneID`)
  -- CONSTRAINT `refstore_ibfk_1` FOREIGN KEY (`ChainID`) REFERENCES `RefChain` (`ChainID`) ON DELETE SET NULL ON UPDATE CASCADE,
  -- CONSTRAINT `refstore_ibfk_2` FOREIGN KEY (`StoreAddressID`) REFERENCES `Address` (`AddressID`) ON DELETE SET NULL ON UPDATE CASCADE,
  -- CONSTRAINT `refstore_ibfk_3` FOREIGN KEY (`StorePhoneID`) REFERENCES `Phone` (`PhoneID`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `RefCurrency` (`CurrencyCode`, `CurrencyName`) VALUES ('1', 'Shekel');
INSERT INTO `RefCurrency` (`CurrencyCode`, `CurrencyName`) VALUES ('2', 'Dollar');
INSERT INTO `RefCurrency` (`CurrencyCode`, `CurrencyName`) VALUES ('3', 'Euro');

INSERT INTO `RefLoadType` (`FileLoadTypeID`, `LoadTypeDescription`) VALUES ('1', 'Store');
INSERT INTO `RefLoadType` (`FileLoadTypeID`, `LoadTypeDescription`) VALUES ('2', 'Price');
INSERT INTO `RefLoadType` (`FileLoadTypeID`, `LoadTypeDescription`) VALUES ('3', 'PriceFull');
INSERT INTO `RefLoadType` (`FileLoadTypeID`, `LoadTypeDescription`) VALUES ('4', 'Promo');
INSERT INTO `RefLoadType` (`FileLoadTypeID`, `LoadTypeDescription`) VALUES ('5', 'PromoFull');

INSERT INTO `RefSourceType` (`SourceTypeID`, `SourceTypeName`) VALUES ('1', 'xml');
INSERT INTO `RefSourceType` (`SourceTypeID`, `SourceTypeName`) VALUES ('2', 'user');




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;