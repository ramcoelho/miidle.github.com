CREATE TABLE IF NOT EXISTS `imoo_grade_grades_esp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_fk` int(11) NOT NULL,
  `operacao` varchar(1) NOT NULL,
  `confirmado` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `imoo_alteracao_nota_curso` (
  `id_curso` int(11) NOT NULL,
  `ultima_atualizacao` bigint(10) NOT NULL,
  PRIMARY KEY (`id_curso`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

