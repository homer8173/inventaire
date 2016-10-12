--
-- Structure de la table `llx_product_pmp`
--  nom inventaire a la T (date_applied)
--

CREATE TABLE IF NOT EXISTS `llx_product_pmp` (
`rowid` int(11)  unsigned AUTO_INCREMENT NOT NULL,
  `fk_product` int(11) NOT NULL,
  `pmp` double(24,8) NOT NULL DEFAULT 0.0000000,
  `stock` int(11)  NULL,
  `date_created` datetime NOT NULL,
  `tms` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`rowid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

