SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

USE <dbname>;

--
-- Database: `ezproxy`
--

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `id` int(11) NOT NULL auto_increment,
  `resource` int(11) NOT NULL,
  `config_type` enum('H','HJ','D','DJ') NOT NULL,
  `config_value` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `resource` (`resource`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Table structure for table `resource`
--

CREATE TABLE IF NOT EXISTS `resource` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `custom_config` text,
  `resource_type` enum('Journal','Database') NOT NULL,
  `use_custom` enum('T','F') NOT NULL default 'F',
  `restricted` enum('T','F') NOT NULL default 'F',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Constraints for table `config`
--
ALTER TABLE `config`
  ADD CONSTRAINT `config_ibfk_1` FOREIGN KEY (`resource`) REFERENCES `resource` (`id`);

-- --------------------------------------------------------

--
-- Table structure for table `auth`
--  
  CREATE TABLE IF NOT EXISTS `auth` (
  `user` char(8) NOT NULL,
  PRIMARY KEY  (`user`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;