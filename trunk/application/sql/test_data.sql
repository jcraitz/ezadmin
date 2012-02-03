USE <dbname>;

--
-- Dumping data for table `resource`
--

-- --------------------------------------------------------

INSERT INTO `resource` (`id`, `title`, `custom_config`, `resource_type`, `use_custom`, `restricted`) VALUES
(555, 'Some Institution of Washington- Yearbook', '', 'Journal', 'F', 'T'),
(556, 'International Atomic Soda Agency', '', 'Journal', 'F', 'F'),
(557, 'Dusty Volume of Chemistry and Physics', '', 'Database', 'F', 'F'),
(558, 'Table of Contents Index', '', 'Database', 'F', 'F'),
(559, 'ASDFGHJKL Project', 'SOME CUSTOM CONFIG', 'Database', 'T', 'F');

--
-- Dumping data for table `config`
--

-- --------------------------------------------------------

INSERT INTO `config` (`id`, `resource`, `config_type`, `config_value`) VALUES
(111, 555, 'H', 'someinstitution.org'),
(112, 555, 'H', 'someinstitution2.org'),
(113, 556, 'H', 'pepsi.com'),
(114, 557, 'DJ', 'yahoo.com'),
(115, 558, 'DJ', 'tableindex.org'),
(116, 558, 'DJ', 'pbs.com');

-- --------------------------------------------------------

--
-- Dumping data for table `auth`
--

INSERT INTO `auth` (`user`) VALUES
('jthurtea'),
('ejlynema'),
('krdoerr'),
('tsmori'),
('jpsample');