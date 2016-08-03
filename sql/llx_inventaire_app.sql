 --
 --Structure de la table llx_inventaire_app
 --

CREATE TABLE IF NOT EXISTS `llx_inventaire_app` (
`row_id` int(11) unsigned AUTO_INCREMENT NOT NULL,
  `k_inventaire_id` int(11) NOT NULL,
  `k_product_id` int(11) NOT NULL,
  `pmp` float(6,6) NOT NULL,
  `reel` double DEFAULT NULL,
  `applied` int(1) NOT NULL DEFAULT '0',
  `date_modified` datetime DEFAULT NULL,
  `fk_user_modified` int(11) DEFAULT NULL,
  PRIMARY KEY (`row_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

