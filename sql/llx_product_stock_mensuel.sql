 --
 --Structure de la table llx_product_stock_mensuel
 --

CREATE TABLE IF NOT EXISTS `llx_product_stock_mensuel` (
`row_id` int(11) unsigned AUTO_INCREMENT NOT NULL,
  `k_product_id` int(11) NOT NULL,
  `tms` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `pmp` float(6,6) NOT NULL,
  `reel` double DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`row_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

