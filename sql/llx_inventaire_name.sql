--
-- Structure de la table `llx_inventaire_name`
--

CREATE TABLE IF NOT EXISTS `llx_inventaire_name` (
`row_id` int(11)  unsigned AUTO_INCREMENT NOT NULL,
  `name` varchar(250) NOT NULL,
  `entrepots` varchar(20) NOT NULL,
  `statut` int(1) NOT NULL,
  `date_created` datetime NOT NULL,
  `fk_user_created` int(11) NOT NULL,
  `date_modified` datetime NOT NULL,
  `fk_user_modified` int(11) NOT NULL,
  `date_applied` datetime NOT NULL,
  PRIMARY KEY (`row_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

