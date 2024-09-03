CREATE TABLE IF NOT EXISTS `#__joombridgedle` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `module_id` int(11) NOT NULL,
    `cache_key` varchar(255) NOT NULL,
    `data` text,
    `cache_date` datetime NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `cache_key_module` (`module_id`, `cache_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;