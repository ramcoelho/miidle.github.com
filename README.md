Miidle
======

Projeto para integração Moodle e Sagu, desenvolvido por UEMAnet,

Responsável pelo projeto: Antônio Roberto Coelho Serra

Financiado por: CAPES - Coordenação de Aperfeiçoamento de Pessoal de Nível Superior

Dependências
------------

* Apache 2.2 ou superior com módulo Rewrite.
* PHP5 ou superior com módulos php5-mysql e php5-pgsql.
* Zend Framework versão 1.11 ou superior.

Uso
---

O módulo principal (Miidle) e os módulos de interfaceamento podem ser
instalados em servidores distintos.

iSagu se conecta diretamente ao banco de dados do Sagu.

iMoo se conecta diretamente ao banco de dados do Moodle.

A configuração de conexão a banco dos módulos de interfaceamento podem ser
ajustadas em
	<modulo>/lib/Dao/Generic.php

A configuração das URIs RESTful do Miidle podem ser ajustadas em
	Miidle/application/configs/application.ini

Licença
-------

Este projeto está publicado sob GPL v2. Veja arquivo COPYING para detalhes da licença.

