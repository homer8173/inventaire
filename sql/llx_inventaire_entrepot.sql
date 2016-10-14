--
-- Structure de la table `llx_inventaire_det_entrepot`
-- stocker les produits correspond a la date T
--

CREATE TABLE IF NOT EXISTS `llx_inventaire_entrepot` (
`row_id` int(11)  unsigned AUTO_INCREMENT NOT NULL,
  `fk_inventaire_line_id` int(11) NOT NULL,
  `fk_entrepot_id` int(11) NOT NULL,
  `pmp` float(24,8) NOT NULL,
  `value` int(11) NOT NULL,
  `origin_value` int(11) NOT NULL,
  `origin_pmp` float(10,6) NOT NULL,
  `fk_user_modified` int(11) NOT NULL,
  `date_modified` datetime NOT NULL,
  PRIMARY KEY (`row_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
