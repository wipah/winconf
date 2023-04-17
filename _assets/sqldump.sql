-- --------------------------------------------------------
-- Host:                         192.168.1.201
-- Versione server:              10.3.28-MariaDB - Source distribution
-- S.O. server:                  Linux
-- HeidiSQL Versione:            12.4.0.6659
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dump della struttura di tabella winconf.companies
CREATE TABLE IF NOT EXISTS `companies` (
                                           `ID` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
                                           `business_name` varchar(255) NOT NULL,
                                           `orders_email` varchar(50) NOT NULL,
                                           `semaphore_line_policy` tinyint(3) unsigned DEFAULT 1 COMMENT '0 = admins and users, 1 = only admins',
                                           `semaphore_ko` decimal(5,2) unsigned DEFAULT NULL,
                                           `semaphore_warning` decimal(5,2) unsigned DEFAULT NULL,
                                           `semaphore_ok` decimal(5,2) unsigned DEFAULT NULL,
                                           `semaphore_order_ko` decimal(5,2) unsigned DEFAULT NULL,
                                           `semaphore_order_warning` decimal(5,2) unsigned DEFAULT NULL,
                                           `semaphore_order_ok` decimal(5,2) unsigned DEFAULT NULL,
                                           `semaphore_drug_ko` decimal(5,2) unsigned DEFAULT NULL,
                                           `semaphore_drug_warning` decimal(5,2) unsigned DEFAULT NULL,
                                           `semaphore_drug_ok` decimal(5,2) unsigned DEFAULT NULL,
                                           `semaphore_drug_order_ko` decimal(5,2) unsigned DEFAULT NULL,
                                           `semaphore_drug_order_warning` decimal(5,2) unsigned DEFAULT NULL,
                                           `semaphore_drug_order_ok` decimal(5,2) unsigned DEFAULT NULL,
                                           `red_semaphore_policy` tinyint(3) unsigned DEFAULT 0 COMMENT '0 = block; 1 = send order with warning;',
                                           `enabled` tinyint(3) unsigned DEFAULT 0 COMMENT '0 = non attiva; 1 = attiva;',
                                           `send_order_to_customer` tinyint(3) unsigned DEFAULT 0,
                                           `notice` text DEFAULT NULL,
                                           `api_uri` varchar(100) DEFAULT NULL,
                                           `commission_drug_limit` decimal(5,2) DEFAULT NULL,
                                           `commission_drug_low_value` decimal(5,2) DEFAULT NULL,
                                           `commission_drug_high_value` decimal(5,2) DEFAULT NULL,
                                           `drug_mktg_coefficient` decimal(3,2) DEFAULT 1.00,
                                           PRIMARY KEY (`ID`),
                                           KEY `ID` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dump dei dati della tabella winconf.companies: ~0 rows (circa)
INSERT INTO `companies` (`ID`, `business_name`, `orders_email`, `semaphore_line_policy`, `semaphore_ko`, `semaphore_warning`, `semaphore_ok`, `semaphore_order_ko`, `semaphore_order_warning`, `semaphore_order_ok`, `semaphore_drug_ko`, `semaphore_drug_warning`, `semaphore_drug_ok`, `semaphore_drug_order_ko`, `semaphore_drug_order_warning`, `semaphore_drug_order_ok`, `red_semaphore_policy`, `enabled`, `send_order_to_customer`, `notice`, `api_uri`, `commission_drug_limit`, `commission_drug_low_value`, `commission_drug_high_value`, `drug_mktg_coefficient`) VALUES
    (1, 'Difar Distribuzione', 'f.crisafulli@difar.it', 1, 0.00, 25.00, 30.00, NULL, 25.00, 30.00, 0.00, 11.95, 15.95, NULL, 11.90, 15.90, 1, 1, 0, '<h2>Aggiornamento di canvass</h2>\n<p>Gentili Agenti, troverete gli aggiornamenti di Canvass (I Canvass 2023) distribuiti nel programma. Potete trovare le marche a voi assegnate e le composizioni a voi assegnate nel profilo (click in alto sul vostro nome). Qualora mancasse qualcosa vi prego di contattarmi a f.crisafulli@difar.it.\n</p>\n<p>Vi ricordo che non è necessario inserire articolo su articolo una composizione. <a href="https://dccsales.difar.it/help/doku.php/ordini">Leggi la guida</a></p>', 'https://tools.difar.it:4443/api/', 15.95, 2.00, 3.00, 0.30);

-- Dump della struttura di tabella winconf.configuratore_categorie
CREATE TABLE IF NOT EXISTS `configuratore_categorie` (
                                                         `ID` mediumint(9) NOT NULL AUTO_INCREMENT,
                                                         `categoria_formula_ID` mediumint(9) DEFAULT NULL,
                                                         `categoria_nome` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
                                                         `categoria_sigla` varchar(16) DEFAULT NULL,
                                                         `categoria_descrizione` text CHARACTER SET utf8 DEFAULT NULL,
                                                         `immagine` varchar(128) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Nome del file che contiene l''immagine associata alla categoria.',
                                                         `categoria_formula_valore` decimal(20,6) DEFAULT NULL,
                                                         `ordine` smallint(5) unsigned DEFAULT NULL,
                                                         `visibile` mediumint(8) unsigned DEFAULT 1,
                                                         PRIMARY KEY (`ID`),
                                                         KEY `formula_ID` (`categoria_formula_ID`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COMMENT='Categorie principali del configuratore. Rappresentano il punto di ingresso per la creazione di un nuovo preventivo.';

-- Dump dei dati della tabella winconf.configuratore_categorie: ~4 rows (circa)
INSERT INTO `configuratore_categorie` (`ID`, `categoria_formula_ID`, `categoria_nome`, `categoria_sigla`, `categoria_descrizione`, `immagine`, `categoria_formula_valore`, `ordine`, `visibile`) VALUES
                                                                                                                                                                                                     (1, 0, 'materialix', 'materialiy', 'abce', NULL, 0.000000, 1, 1),
                                                                                                                                                                                                     (2, NULL, 'persiane', '2', '3', NULL, NULL, NULL, 1),
                                                                                                                                                                                                     (3, NULL, '1', '2', '3', NULL, NULL, NULL, 1),
                                                                                                                                                                                                     (4, NULL, 'abc', 'dee', 'rgfwrgrw', NULL, NULL, NULL, 1);

-- Dump della struttura di tabella winconf.configuratore_formule
CREATE TABLE IF NOT EXISTS `configuratore_formule` (
                                                       `ID` mediumint(9) NOT NULL AUTO_INCREMENT,
                                                       `formula_sigla` varchar(60) CHARACTER SET utf8 NOT NULL DEFAULT '0',
                                                       `formula_descrizione` varchar(60) CHARACTER SET utf8 NOT NULL DEFAULT '0',
                                                       `formula_help` text NOT NULL,
                                                       PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Dump dei dati della tabella winconf.configuratore_formule: ~4 rows (circa)
INSERT INTO `configuratore_formule` (`ID`, `formula_sigla`, `formula_descrizione`, `formula_help`) VALUES
                                                                                                       (1, 'coeff-k', 'Moltiplica l\'importo per un coefficiente K.', ''),
                                                                                                       (2, 'coeff-percentuale', 'Moltiplica l\'importo per un valore percentuale', ''),
                                                                                                       (3, 'somma-valore', 'Somma all\'importo il valore', ''),
                                                                                                       (4, 'coeff-k-mq', 'Somma all\'importo il valore in mc2 per un coefficiente.', '');

-- Dump della struttura di tabella winconf.configuratore_opzioni
CREATE TABLE IF NOT EXISTS `configuratore_opzioni` (
                                                       `ID` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
                                                       `sottostep_ID` int(11) DEFAULT NULL,
                                                       `opzioni_formula_ID` int(11) DEFAULT NULL,
                                                       `opzione_nome` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
                                                       `opzione_sigla` varchar(60) CHARACTER SET utf8 DEFAULT NULL,
                                                       `opzione_descrizione` text CHARACTER SET utf8 DEFAULT NULL,
                                                       `check_dipendenze` tinyint(4) DEFAULT NULL COMMENT '0 = nessun controllo dipendenze; 1 = dipendenza di tipo "escludi"; 2 = dipendenza di tipo "includi"',
                                                       `check_dimensioni` tinyint(4) DEFAULT NULL COMMENT '0 = nessun controllo; 1 = controlla le dimensioni',
                                                       `opzione_formula_valore` int(11) DEFAULT NULL,
                                                       `ordine` smallint(6) DEFAULT NULL,
                                                       `visibile` tinyint(4) DEFAULT NULL COMMENT '0 = non visibile; 1 = visibile',
                                                       PRIMARY KEY (`ID`),
                                                       KEY `sottostep_ID` (`sottostep_ID`),
                                                       KEY `opzioni_formula_ID` (`opzioni_formula_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dump dei dati della tabella winconf.configuratore_opzioni: ~0 rows (circa)

-- Dump della struttura di tabella winconf.configuratore_opzioni_check_dimensioni
CREATE TABLE IF NOT EXISTS `configuratore_opzioni_check_dimensioni` (
                                                                        `ID` int(11) NOT NULL AUTO_INCREMENT,
                                                                        `opzione_ID` mediumint(8) unsigned DEFAULT NULL,
                                                                        `valore` decimal(20,6) unsigned DEFAULT NULL COMMENT 'Valore da controllare',
                                                                        `dimensione` tinyint(3) unsigned DEFAULT NULL COMMENT '0 = larghezza; 1 = lunghezza; 2 = spessore',
                                                                        `confronto` int(11) DEFAULT NULL COMMENT '0: < (minore); \r\n1: <= minore uguale; \r\n2: = (uguale);\r\n3: >= (maggiore o uguale)\r\n4: > (maggiore)\r\n5: != diverso',
                                                                        PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dump dei dati della tabella winconf.configuratore_opzioni_check_dimensioni: ~0 rows (circa)

-- Dump della struttura di tabella winconf.configuratore_opzioni_check_dipendenze
CREATE TABLE IF NOT EXISTS `configuratore_opzioni_check_dipendenze` (
                                                                        `ID` mediumint(9) NOT NULL AUTO_INCREMENT,
                                                                        `opzione_ID` mediumint(9) NOT NULL DEFAULT 0,
                                                                        `opzione_valore_ID` mediumint(9) NOT NULL DEFAULT 0,
                                                                        `esito` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 = escludi; 1 = includi;',
                                                                        PRIMARY KEY (`ID`),
                                                                        KEY `opzione_ID` (`opzione_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dump dei dati della tabella winconf.configuratore_opzioni_check_dipendenze: ~0 rows (circa)

-- Dump della struttura di tabella winconf.configuratore_sottostep
CREATE TABLE IF NOT EXISTS `configuratore_sottostep` (
                                                         `ID` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
                                                         `step_ID` mediumint(9) DEFAULT NULL,
                                                         `sottostep_nome` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '0',
                                                         `sottostep_sigla` varchar(50) CHARACTER SET utf8 DEFAULT '0',
                                                         `sottostep_descrizione` text CHARACTER SET utf8 DEFAULT NULL,
                                                         `tipo_scelta` smallint(5) unsigned DEFAULT NULL COMMENT '0 = scelta singola; 1 = scelta multipla; 2 = campo libero',
                                                         `check_dipendenze` smallint(6) DEFAULT NULL COMMENT '0 = nessun check sulla dipendenza (il sottostep verrà sempre mostrato); 1 = esegue un ckeck di esclusione se trova nella tabella configuratore_sottostep_check almeno una condizione valida',
                                                         `ordine` smallint(5) unsigned DEFAULT NULL,
                                                         `visibile` smallint(5) unsigned DEFAULT NULL COMMENT '0 = non visibile; 1 = visibile',
                                                         PRIMARY KEY (`ID`),
                                                         KEY `step_ID` (`step_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dump dei dati della tabella winconf.configuratore_sottostep: ~1 rows (circa)
INSERT INTO `configuratore_sottostep` (`ID`, `step_ID`, `sottostep_nome`, `sottostep_sigla`, `sottostep_descrizione`, `tipo_scelta`, `check_dipendenze`, `ordine`, `visibile`) VALUES
                                                                                                                                                                                   (1, 1, 'Telaio', 'telaio', 'Scelta del telaio', 0, NULL, 1, 1),
                                                                                                                                                                                   (2, 1, 'Materiale', 'doppia anta', 'Scelta del materiale', 0, NULL, 2, 1);

-- Dump della struttura di tabella winconf.configuratore_sottostep_check
CREATE TABLE IF NOT EXISTS `configuratore_sottostep_check` (
                                                               `ID` mediumint(9) NOT NULL DEFAULT 0,
                                                               `step_ID` mediumint(9) NOT NULL DEFAULT 0,
                                                               `opzione_ID` mediumint(9) DEFAULT NULL,
                                                               `tipo_check` smallint(6) DEFAULT NULL COMMENT '0 = escludi; 1 = includi',
                                                               `confronto` smallint(6) DEFAULT NULL COMMENT '0: < (minore); \r\n1: <= minore uguale; \r\n2: = (uguale);\r\n3: >= (maggiore o uguale)\r\n4: > (maggiore)\r\n5: != diverso',
                                                               `valore` decimal(20,6) DEFAULT NULL,
                                                               PRIMARY KEY (`ID`),
                                                               KEY `step_ID` (`step_ID`),
                                                               KEY `opzione_ID` (`opzione_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Questa tabella regola la visualizzazione dei sottostep (configuratore_step) in base alle condizioni imposte';

-- Dump dei dati della tabella winconf.configuratore_sottostep_check: ~0 rows (circa)

-- Dump della struttura di tabella winconf.configuratore_step
CREATE TABLE IF NOT EXISTS `configuratore_step` (
                                                    `ID` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
                                                    `categoria_ID` mediumint(9) DEFAULT NULL COMMENT 'ID della categoria del configuratore. Vedere tabella configuratore_categorie',
                                                    `step_sigla` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '0' COMMENT 'Sigla dello step',
                                                    `step_nome` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
                                                    `step_descrizione` text CHARACTER SET utf8 DEFAULT NULL COMMENT 'Descrizione dello step',
                                                    `ordine` mediumint(8) unsigned DEFAULT NULL COMMENT 'Ordine dello step',
                                                    `visibile` tinyint(3) unsigned DEFAULT 1,
                                                    PRIMARY KEY (`ID`),
                                                    KEY `categoria_ID` (`categoria_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dump dei dati della tabella winconf.configuratore_step: ~2 rows (circa)
INSERT INTO `configuratore_step` (`ID`, `categoria_ID`, `step_sigla`, `step_nome`, `step_descrizione`, `ordine`, `visibile`) VALUES
                                                                                                                                 (1, 1, 'materialiy', 'materialix', 'abce', 1, 1),
                                                                                                                                 (2, 1, 'finestre', 'finestre', 'Finestre', 2, 1);

-- Dump della struttura di tabella winconf.documenti
CREATE TABLE IF NOT EXISTS `documenti` (
                                           `ID` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
                                           `user_ID` mediumint(8) unsigned DEFAULT NULL,
                                           `customer_ID` mediumint(8) unsigned DEFAULT NULL,
                                           `tipo_ordine_ID` smallint(5) unsigned NOT NULL COMMENT 'ID del tipo del documento (Vedere tabella documenti_tipo). Ad esempio ID 1 = progetto, ID 2 = ordine',
                                           `categoria_ID` int(11) NOT NULL COMMENT 'ID della categoria del progetto, vedere configuratore_categorie',
                                           `lunghezza` decimal(20,3) unsigned NOT NULL COMMENT 'espressa in cm decimali',
                                           `larghezza` decimal(20,3) unsigned NOT NULL COMMENT 'espressa in cm decimali',
                                           `spessore` decimal(20,6) NOT NULL COMMENT 'espressa in cm decimali',
                                           `metri_quadri` decimal(20,3) unsigned DEFAULT NULL COMMENT 'espressa in metri quadrati decimali',
                                           `data_ordine` date DEFAULT NULL,
                                           `totale` decimal(20,6) DEFAULT NULL,
                                           PRIMARY KEY (`ID`),
                                           KEY `user_ID` (`user_ID`),
                                           KEY `customer_ID` (`customer_ID`),
                                           KEY `tipo_ordine_ID` (`tipo_ordine_ID`),
                                           KEY `categoria_ID` (`categoria_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dump dei dati della tabella winconf.documenti: ~0 rows (circa)

-- Dump della struttura di tabella winconf.documenti_corpo
CREATE TABLE IF NOT EXISTS `documenti_corpo` (
                                                 `ID` mediumint(9) NOT NULL AUTO_INCREMENT,
                                                 `categoria_ID` mediumint(9) DEFAULT NULL,
                                                 `step_ID` mediumint(9) DEFAULT NULL,
                                                 `sottostep_ID` mediumint(9) DEFAULT NULL,
                                                 `opzione_ID` mediumint(9) DEFAULT NULL,
                                                 `formula_ID` mediumint(9) DEFAULT NULL,
                                                 `formula_valore` decimal(20,6) DEFAULT NULL,
                                                 `importo` decimal(20,6) DEFAULT NULL,
                                                 `qta` smallint(6) DEFAULT NULL,
                                                 PRIMARY KEY (`ID`),
                                                 KEY `categoria_ID` (`categoria_ID`),
                                                 KEY `step_ID` (`step_ID`),
                                                 KEY `sottostep_ID` (`sottostep_ID`),
                                                 KEY `opzione_ID` (`opzione_ID`),
                                                 KEY `formula_ID` (`formula_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dump dei dati della tabella winconf.documenti_corpo: ~0 rows (circa)

-- Dump della struttura di tabella winconf.documenti_tipo
CREATE TABLE IF NOT EXISTS `documenti_tipo` (
                                                `ID` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
                                                `tipo` varchar(255) NOT NULL DEFAULT 'AUTO_INCREMENT',
                                                `descrizione` text NOT NULL,
                                                `visibile` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 = non visibile; 1 = visibile',
                                                PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COMMENT='Valorizza le tipologie di documenti. Potrebbero, ad esempio, essere "ordini", "preventivi", "liste", "progetti".';

-- Dump dei dati della tabella winconf.documenti_tipo: ~1 rows (circa)
INSERT INTO `documenti_tipo` (`ID`, `tipo`, `descrizione`, `visibile`) VALUES
    (1, 'preventivo', 'Preventivo di Vendita', 1);

-- Dump della struttura di tabella winconf.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
                                          `ID` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
                                          `user_ID` mediumint(8) unsigned NOT NULL DEFAULT 0,
                                          `start` datetime NOT NULL,
                                          `end` datetime NOT NULL,
                                          `security_hash` char(32) NOT NULL,
                                          `session` char(32) NOT NULL,
                                          `IP` char(16) NOT NULL,
                                          PRIMARY KEY (`ID`),
                                          KEY `user_ID` (`user_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=609 DEFAULT CHARSET=utf8;

-- Dump dei dati della tabella winconf.sessions: ~248 rows (circa)
INSERT INTO `sessions` (`ID`, `user_ID`, `start`, `end`, `security_hash`, `session`, `IP`) VALUES
                                                                                               (3, 2, '2021-09-22 11:42:08', '2021-10-22 11:42:08', 'c0f493588f0422699bb74ec6b852e6ab', 'abe6db1870abb99c7a37496473189b4d', '82.84.84.192'),
                                                                                               (4, 1, '2021-09-22 12:04:56', '2021-10-22 12:04:56', '0e8e8eb19d6df6da82c1e3177b92517c', '373255e6e9551b077b399e7d908e2cfa', '188.12.92.13'),
                                                                                               (5, 1, '2021-10-12 08:54:19', '2021-11-12 08:54:19', 'ecead70f67d4b5cda57577ff07c5e335', '218bb57f723c860d32cda5ed35ace82f', '188.12.92.13'),
                                                                                               (7, 2, '2021-11-18 11:40:29', '2021-12-18 11:40:29', '76ba7fc7e79a228bbaf89871579f3bee', '7ff999b9e6d235bd51407197e19ba749', '94.36.91.48'),
                                                                                               (8, 1, '2021-11-18 11:42:24', '2021-12-18 11:42:24', '5a508a39205e305ea53e6a1fd04bcc25', '0b0351d845cb6e0034ac35d7cb3f990c', '188.12.92.13'),
                                                                                               (9, 2, '2021-11-25 11:27:03', '2021-12-25 11:27:03', '0b90cbd15a656854708804120bf58bcf', '3c01c58db8011afd2ad3353d9d431637', '93.45.0.26'),
                                                                                               (10, 2, '2021-11-29 16:39:04', '2021-12-29 16:39:04', '3dccf66d3539ba9d2421d15e64d9df17', 'f512663b7e004afe826aac1cf37ee004', '94.37.53.147'),
                                                                                               (11, 1, '2021-12-09 17:13:24', '2022-01-09 17:13:24', '5f9b6eba435393863e5ac8b27fd87d84', '44954b2ee9300d994fc29751094a104f', '188.12.92.13'),
                                                                                               (12, 2, '2021-12-15 15:38:59', '2022-01-15 15:38:59', 'ab7ee066284987abccad304132a7d916', '5841142f90a6f25ff13e575038ebf8a7', '37.160.150.45'),
                                                                                               (13, 1, '2022-02-04 10:40:01', '2022-03-04 10:40:01', '74fa4208915deff46e5a73b2768d801c', '9ae3d3e68cf1b96302dd7e977d94b0b5', '188.12.92.13'),
                                                                                               (14, 2, '2022-02-07 11:56:23', '2022-03-07 11:56:23', '127b8ea7d006665dc88580a57d3a9480', '1a0a363c61fedab2e8a6002821a31029', '93.67.53.210'),
                                                                                               (15, 2, '2022-02-28 12:55:32', '2022-03-28 12:55:32', 'bd427e32b49444453d4b3a9ad184374a', 'a8191abaafb520eabf2fa2cfa5450eea', '188.12.92.13'),
                                                                                               (16, 1, '2022-03-04 09:57:19', '2022-04-04 09:57:19', 'd214972f8277d5a80ea45a3fd5862e35', 'd50d7e71a86ae8ace39fcb79ba4326e4', '188.12.92.13'),
                                                                                               (17, 2, '2022-03-07 09:31:56', '2022-04-07 09:31:56', '3f5fe3515f2536a85a47fe08f56e7e50', '8eb90ce830fbe857680495597085d445', '93.67.53.210'),
                                                                                               (19, 4, '2022-04-06 10:50:19', '2022-05-06 10:50:19', '43b54cc5359e2532554b9c7f5182e5a0', '4787e8abbfa0709b36f36b4673efa302', '188.12.92.13'),
                                                                                               (20, 2, '2022-04-06 15:54:15', '2022-05-06 15:54:15', '936335854e2fcd9d6fe23e8b571337b4', '73517f74e7adccd07d582776b66e9026', '37.160.130.1'),
                                                                                               (21, 1, '2022-04-12 08:39:12', '2022-05-12 08:39:12', 'b9d5a20650a146ef8ca5384895daf60c', 'bec93bf5ae299202221a19aa163dceac', '82.50.81.243'),
                                                                                               (24, 5, '2022-05-26 10:57:26', '2022-06-26 10:57:26', '7ddcfadfa9141459c04f6b1b2aaa3bfc', '25a2e2ff9f3db8233033643771212c72', '188.12.92.13'),
                                                                                               (25, 6, '2022-05-26 14:52:11', '2022-06-26 14:52:11', '373e06d6ce3235ff21079b7786192964', '06adb7411f95518cc48509e7a27aafa9', '151.57.14.101'),
                                                                                               (27, 1, '2022-05-26 17:28:00', '2022-06-26 17:28:00', '8ad30c806a46393c518ceffbb3dc7c08', '25b7dc7e5d0061d309a7e67c747f3da2', '188.12.92.13'),
                                                                                               (28, 5, '2022-05-27 07:35:16', '2022-06-27 07:35:16', '70de04f5516cdc4e8cb2af251de69bb6', '3e5fdbcb9859efe4ca54d93f3b445c9a', '79.17.73.2'),
                                                                                               (29, 5, '2022-05-27 07:36:25', '2022-06-27 07:36:25', 'a7c7fb9f68bcb3139bb9b7de952b78e1', '5c7082b9acf722aa44ab0bfd25d8c8c8', '79.17.73.2'),
                                                                                               (30, 5, '2022-05-27 07:38:08', '2022-06-27 07:38:08', 'fb9f7fac277a2db0ed06f40be4aaad22', 'd4720cd081bc0e54962a674a3a72db82', '79.17.73.2'),
                                                                                               (31, 5, '2022-05-27 09:06:53', '2022-06-27 09:06:53', 'e16769f55b16c93811121aa1d7c59e49', 'c03698eaf85af84192e15d29b5f1f06b', '5.90.1.143'),
                                                                                               (32, 6, '2022-05-30 20:01:26', '2022-06-30 20:01:26', 'eb3136e633852b2e27707b6773793abd', '2b0edc2c72d51507137953b361f8bd45', '151.37.225.3'),
                                                                                               (33, 8, '2022-06-09 11:57:43', '2022-07-09 11:57:43', 'f1fcf151b7d2055aae1f105fbe71e0ad', '8a43d6008d3e552eeeae2513d519a718', '151.37.143.186'),
                                                                                               (36, 7, '2022-06-15 12:52:31', '2022-07-15 12:52:31', '4512da684a481869f9d670cb34713244', '667c8d6084cbb3a56f56c867f9bee76c', '151.19.31.80'),
                                                                                               (38, 10, '2022-06-27 16:04:07', '2022-07-27 16:04:07', 'f29c09c2abf486261bf425158bbc284a', '32bcc1c5f261ec011c3cf92e657e2b7b', '87.17.169.55'),
                                                                                               (39, 10, '2022-06-27 16:26:14', '2022-07-27 16:26:14', '24d4bd6a6e5d102388a06f1c9e3b1ee3', '8798f6740fbde2d860d08effa05ef41e', '87.17.169.55'),
                                                                                               (40, 11, '2022-07-04 16:56:10', '2022-08-04 16:56:10', '7d56ad11f82c255a3eb6cecdc111bd1c', 'c966ef79842ce22bde7f276c81e84c8a', '151.42.39.80'),
                                                                                               (41, 11, '2022-07-05 14:50:18', '2022-08-05 14:50:18', 'be1932a54a0ceaadfb005705dab0bc3a', '67e2d6f4a2fa6e8784467c5a95aa40b0', '151.42.66.162'),
                                                                                               (42, 11, '2022-07-06 07:09:48', '2022-08-06 07:09:48', '5b3f15d1e6209ee52513e53473aae485', '3271fee07b98a955c1f8a6ecacff36b0', '151.42.66.162'),
                                                                                               (43, 12, '2022-07-06 09:31:24', '2022-08-06 09:31:24', '092e5ab1d34aef32e4fd592b795660fe', 'c6d94b9e73edceb0755f98463ee1f4f7', '188.12.92.13'),
                                                                                               (46, 11, '2022-07-06 10:16:11', '2022-08-06 10:16:11', '566897037deb052a1eb935a17da064c2', '54d4534adaa08f8928213140127b457b', '188.12.92.13'),
                                                                                               (47, 12, '2022-07-06 14:35:27', '2022-08-06 14:35:27', 'a6d29448af4ea5ae2a2f7616cefb8f34', '966b3e27d99aebf8c199996f6d9c4cf8', '95.252.198.193'),
                                                                                               (50, 13, '2022-07-07 11:04:03', '2022-08-07 11:04:03', 'cbd0b018458bb68b38338171794d6137', '802bf4ccd787af85b661a704e54ccba5', '93.33.147.41'),
                                                                                               (51, 1, '2022-07-07 11:09:21', '2022-08-07 11:09:21', '197bdcae478e49bcbd061ba72ae062c2', '0dfda7d5b5dd733fcc42718e660bb151', '188.12.92.13'),
                                                                                               (58, 1, '2022-07-11 11:02:38', '2022-08-11 11:02:38', '7a7af9489b30ea7513453efe997df8fa', '9a2af05efd52d68d45cf57c4afd45bbd', '188.12.92.13'),
                                                                                               (59, 1, '2022-07-11 15:11:06', '2022-08-11 15:11:06', '4f88096c6f0f237ea74bd2d1dcad5eb1', 'c1b82b25108d679d59aa1716567f376a', '188.12.92.13'),
                                                                                               (60, 13, '2022-07-11 15:18:05', '2022-08-11 15:18:05', '71b70c02bd763f369ab21a3936dd2452', '0a85ebb179a8aaba07518257872a567c', '93.144.192.181'),
                                                                                               (61, 1, '2022-07-14 11:10:12', '2022-08-14 11:10:12', '54714db78244cd616909a7974ab69b99', '9266ccbf4bf89cd07f9bbfefb4bfedd0', '188.12.92.13'),
                                                                                               (64, 16, '2022-07-20 17:55:32', '2022-08-20 17:55:32', '2d3c6f9da7ef6dfd3331dfee4c08e778', '18c041abeb84f13d01867d6fc3813137', '188.12.92.13'),
                                                                                               (65, 14, '2022-07-20 18:01:51', '2022-08-20 18:01:51', '4b48fa734adb0505a36e7bc17d230524', '26130f0f6b2c90d22d542d1afa2ca6cd', '87.26.107.229'),
                                                                                               (66, 11, '2022-07-21 12:54:33', '2022-08-21 12:54:33', 'c3030701edb7ec9cb230a7f573570c94', '628dabed19ad1511fdeb8760acfd1604', '151.36.229.131'),
                                                                                               (67, 12, '2022-07-26 10:09:01', '2022-08-26 10:09:01', '8265a6f8a463bdb102bac10c939f9692', '7d56b70716d8239ebd5234c26f5caa44', '188.12.92.13'),
                                                                                               (68, 11, '2022-07-27 19:16:40', '2022-08-27 19:16:40', 'a63bc4fa2b28653c7ad03339a20a2051', '465bc9db0e7daaccfe94d009e6c99ab8', '151.42.62.248'),
                                                                                               (74, 11, '2022-08-22 08:46:44', '2022-09-22 08:46:44', 'd2202a89401364eb3c9b34ec9ab9bb18', 'fc46cc8fca8ce92a14f68f30bfd1fd3e', '151.42.71.125'),
                                                                                               (75, 12, '2022-08-22 09:00:41', '2022-09-22 09:00:41', '74203b168d5d76d8093c526ffc06c0b8', '75bfd5b8198cb98c5d47ecfc45546fe6', '188.12.92.13'),
                                                                                               (77, 18, '2022-08-22 14:42:07', '2022-09-22 14:42:07', '17245a5c79dbb6c6110af4c26ab8d04e', 'c9876ba611e7ab432a95f018effd9f1b', '104.28.98.58'),
                                                                                               (83, 18, '2022-08-23 21:43:10', '2022-09-23 21:43:10', '9fe41d87a96ddff72471ccb194297bc6', '654f904c50d430f56feeaf208ed5e20c', '87.21.72.92'),
                                                                                               (84, 12, '2022-08-24 14:42:31', '2022-09-24 14:42:31', 'a934a2f83cd1da4aea2c8496ee4a4cf9', 'ae61315429ca491262d31e686f32e093', '188.217.171.87'),
                                                                                               (86, 20, '2022-08-24 21:23:39', '2022-09-24 21:23:39', '394e3e91c205e3b2804e11ed0fac7ada', 'fa138fc6390a8f45e21c56de8f439381', '79.13.14.214'),
                                                                                               (109, 13, '2022-09-12 20:25:17', '2022-10-12 20:25:17', '09c87ae398cacbee7b1f636e0d45453c', '52cdf83a43213f7959225f1d1cddf829', '93.67.66.132'),
                                                                                               (110, 17, '2022-09-12 22:03:11', '2022-10-12 22:03:11', '66a945ed3e74ad742d0c9437c58939a8', 'e9c230a3e41b44bcfbd6cda72222064d', '188.116.11.56'),
                                                                                               (111, 11, '2022-09-12 23:41:03', '2022-10-12 23:41:03', 'fe37735c5d6660847189a5983f61e309', '0796a11d7bd976e045affb48b64bdcc5', '151.42.37.146'),
                                                                                               (114, 11, '2022-09-13 18:10:40', '2022-10-13 18:10:40', '4479e833b67df1310d0b7456d72511f9', 'caf62af75034f12dc4fcf08ba2d99cc3', '151.42.37.146'),
                                                                                               (115, 14, '2022-09-13 22:13:05', '2022-10-13 22:13:05', 'ab2602767c52cdd31d793b017e62ef52', 'ca9d1eb3ad7ab303e7787d608e7e13e1', '87.26.107.229'),
                                                                                               (116, 11, '2022-09-14 19:41:09', '2022-10-14 19:41:09', 'bb74c4ae2849776cc67881e43b470bb0', 'd6018511cc3c396760426fc8c92164ff', '151.42.37.146'),
                                                                                               (117, 14, '2022-09-14 19:51:07', '2022-10-14 19:51:07', '3549a204943b0cf9f09fcc606d26ac4f', '1af7db53b75126e55ebd1391f2dc9e7d', '87.26.107.229'),
                                                                                               (118, 14, '2022-09-14 20:12:38', '2022-10-14 20:12:38', 'a62d7c6745e4fd8f9216bbf629c43d85', 'd9040fded73f065c4060fe196e40cc71', '87.26.107.229'),
                                                                                               (120, 20, '2022-09-15 09:47:15', '2022-10-15 09:47:15', 'd9ad1549212d0a4a8e122b4d9b3cd049', '6549f9d6620c6866c26f28ef8bf35dcb', '93.38.187.107'),
                                                                                               (125, 11, '2022-09-17 07:58:19', '2022-10-17 07:58:19', '7d3dd6dff20bedda658574044ed9694b', 'a0be97bc174fc6babb10a71229be30aa', '151.42.37.146'),
                                                                                               (126, 17, '2022-09-17 15:40:18', '2022-10-17 15:40:18', 'e4700cc0636822d2e34cef0cf9748f99', '4d9da21671ff57728857f83f60fae320', '45.10.74.62'),
                                                                                               (128, 11, '2022-09-19 17:27:28', '2022-10-19 17:27:28', 'b2d62847e0324548bf41e8b1464f3587', '16267f6840916ded01dfa1a783a2f382', '151.42.37.146'),
                                                                                               (131, 16, '2022-09-20 15:21:33', '2022-10-20 15:21:33', '9364965d37536e4fe5e4ec499386926d', '076d63cf0b0d4f86f06b34a5373b2daf', '95.235.69.166'),
                                                                                               (133, 11, '2022-09-20 20:40:42', '2022-10-20 20:40:42', '681bfa8af40240afc7b6d8d7d2cd3e00', '94305d09d66b12016f2abad937019c34', '151.42.37.146'),
                                                                                               (134, 11, '2022-09-20 20:53:02', '2022-10-20 20:53:02', '42c30bd6a5c9d3d3339429d310025a4c', 'd5d712dee0ac4d4b897932ece4953b3d', '151.42.37.146'),
                                                                                               (136, 12, '2022-09-21 10:39:40', '2022-10-21 10:39:40', '8225c1a7e0fbe9c1a27ad632dbb0200d', '037bab642c797bbb981290ae5ce25012', '188.12.92.13'),
                                                                                               (137, 11, '2022-09-21 18:14:07', '2022-10-21 18:14:07', '61c76a86f1267b5db6739f9938386f5c', '3f338b58ae8de88c286920d6aa564cb8', '151.42.37.146'),
                                                                                               (139, 11, '2022-09-22 20:06:28', '2022-10-22 20:06:28', 'f0ca6c4f3ebd9af71e23ab6e931d1811', '3660d673af60928c8d93e2cf015b6467', '151.42.37.146'),
                                                                                               (143, 11, '2022-09-23 17:48:57', '2022-10-23 17:48:57', '8d060e6e2eb15daf9d6704b185995cf0', 'c3c64d809d3ebc768d2bd581ce7ace46', '151.42.80.145'),
                                                                                               (147, 17, '2022-09-25 18:27:56', '2022-10-25 18:27:56', 'f66a7abe883cb564d00fff283754e1a0', '0c03e721d0d6ff34d507f8b7e607ba9d', '185.39.26.40'),
                                                                                               (150, 11, '2022-09-26 16:01:43', '2022-10-26 16:01:43', '6442e1298a7227e49bb2a890cae32191', '4ed004939aeb280f63e7fc76ad4a4561', '151.42.80.145'),
                                                                                               (153, 12, '2022-09-26 16:44:03', '2022-10-26 16:44:03', '2a52f52e89d6a37b73a3d520b845e916', '2f18030179f30ca43783a5f5e3b34907', '188.217.171.87'),
                                                                                               (155, 11, '2022-09-27 14:50:22', '2022-10-27 14:50:22', '7b2661044ab1698575ece85b51650245', '639fb8d6daae18ed0497d2b592789079', '151.42.80.145'),
                                                                                               (156, 17, '2022-09-27 16:11:16', '2022-10-27 16:11:16', '12b2fa38d99e5a39a1da0a862bc88e1d', '45045b0864a8648dc90bd9ea78779271', '185.39.26.40'),
                                                                                               (157, 20, '2022-09-27 18:05:49', '2022-10-27 18:05:49', '05c42916b4759df2201a1f51a14416d0', 'd425d37cc357e3cc7b4642e606719b3a', '82.59.136.236'),
                                                                                               (161, 11, '2022-09-28 14:03:22', '2022-10-28 14:03:22', 'f0c41de11b3ea7f4a000f0fa1a51af64', 'aa83d354608e4b04c903ef2762f400e1', '151.44.77.179'),
                                                                                               (162, 25, '2022-09-28 19:17:01', '2022-10-28 19:17:01', '3432c3eb649aa19f4b14e3cedb1fbfed', 'bb8521e04fe2912ab0c228ef85db0101', '79.18.212.218'),
                                                                                               (163, 11, '2022-09-28 20:38:08', '2022-10-28 20:38:08', 'd12fec83b5e30251406c2d7f0aa5e0dc', '12d78650db7479446c7401ed5f923902', '151.42.80.145'),
                                                                                               (166, 14, '2022-09-29 10:48:06', '2022-10-29 10:48:06', 'aa38e5bf79a41ed170a6d5702cbdd851', '7f361311d18c418a5d43ff0988396191', '109.52.19.138'),
                                                                                               (167, 11, '2022-09-29 15:54:02', '2022-10-29 15:54:02', 'd109a3d1b5746441438925d56a35435c', '8de3f5edcbd231b3acb6db28eea3c5d4', '151.42.80.145'),
                                                                                               (170, 17, '2022-10-03 08:46:42', '2022-11-03 08:46:42', '0a72797d4ee75ec878ba001f33202dcf', 'd1eab82f0c44841d58c880d379caa1c9', '188.116.11.32'),
                                                                                               (178, 11, '2022-10-05 20:00:46', '2022-11-05 20:00:46', '274c19eb6365654c4a58c94b5504534e', '9dc318f25b5a706f4e172774b8d122b1', '151.42.80.145'),
                                                                                               (182, 11, '2022-10-06 20:44:03', '2022-11-06 20:44:03', '2b1181edf8dc60fa43a5dbefe4090f48', 'cd90d74217d84d5c5b54c7128a3d1949', '151.42.80.145'),
                                                                                               (183, 22, '2022-10-06 20:45:12', '2022-11-06 20:45:12', 'e29db412250ba63ce2ce1f667c3aa29b', '8f5af39ae2e6e0815fcb0133a4eec723', '2.37.3.30'),
                                                                                               (184, 11, '2022-10-06 21:28:37', '2022-11-06 21:28:37', '561380d8ab4bd525764f63ce07686832', '7cef434c241c8d37d5ab4a4a41808c20', '151.42.80.145'),
                                                                                               (190, 11, '2022-10-07 17:51:36', '2022-11-07 17:51:36', 'f8de16974cc301506346d3e53e6f2648', 'b7d0c7499b8d1a9c152f7f7ef84e9941', '151.42.80.145'),
                                                                                               (192, 11, '2022-10-10 18:22:47', '2022-11-10 18:22:47', '294bc8f7cbd08b90130359a6c8359273', 'c7e8463c868f02cc31955a0ee12b09e2', '151.42.80.145'),
                                                                                               (195, 11, '2022-10-11 11:39:20', '2022-11-11 11:39:20', '4823c15787aff40626fbd34bdae86bb6', '3e88f1f7ddd9b01f685bcfa2b5e9a655', '151.38.214.175'),
                                                                                               (196, 11, '2022-10-11 13:15:50', '2022-11-11 13:15:50', '75c324d02927a69953499896c99aa3d0', 'c1240453ba87d555374dde777f76d557', '151.36.101.55'),
                                                                                               (198, 17, '2022-10-12 12:18:07', '2022-11-12 12:18:07', 'b047b59f446f6f8aa59d0af5ff4a8f59', '93c9554d56aadd3757f5a182a91d892d', '37.162.140.73'),
                                                                                               (199, 11, '2022-10-12 18:46:49', '2022-11-12 18:46:49', 'f1a99de91d4eb7324c860b1e611727e0', '5e2cbef9377493d5cfa769dfa9e0ee55', '151.42.80.145'),
                                                                                               (201, 13, '2022-10-13 09:29:02', '2022-11-13 09:29:02', 'e588dd2be442670ce97ee2ef678c30b7', 'e5901b9f3def4a89d044b07e8cc00d08', '83.225.38.84'),
                                                                                               (202, 11, '2022-10-13 13:15:50', '2022-11-13 13:15:50', '9d4d4a9aa7bff1c98ea70b1ebad0becb', '8c3e8670ea67ef478e38344949ef98c2', '151.36.207.40'),
                                                                                               (209, 11, '2022-10-14 11:35:41', '2022-11-14 11:35:41', 'b813e941ad6ad9dfb97f45679e921c0e', 'd1d0d5656a9555a5aa007d3c447510e1', '151.36.87.48'),
                                                                                               (210, 11, '2022-10-15 10:06:32', '2022-11-15 10:06:32', 'ee55b186b31c12aa55f2b1ec4f615552', 'cd076aa985be7389e5188dcafb670a9d', '151.42.80.145'),
                                                                                               (211, 20, '2022-10-17 09:58:04', '2022-11-17 09:58:04', 'e7704397ce6e3d8cda0e762da7964cad', '620e8fa9e80edaf35b869bbc7b02c770', '93.40.43.22'),
                                                                                               (214, 1, '2022-10-17 17:10:49', '2022-11-17 17:10:49', 'a3ffdad0df7bc51c2469c0cfac77f51b', '97e24049a9cc31d47d2dba3feb0027a2', '188.12.92.13'),
                                                                                               (219, 14, '2022-10-17 18:49:45', '2022-11-17 18:49:45', 'a10f29ad96df5849a38586119563fd4b', 'cd8aabd956b4e76a31de261e374d3f9f', '87.26.107.229'),
                                                                                               (222, 14, '2022-10-18 12:36:02', '2022-11-18 12:36:02', '4945bdb533e3b785f1e866ee67489c47', '765b138ba60599442dfd89aea7fa0b5c', '93.44.46.124'),
                                                                                               (223, 11, '2022-10-18 14:51:11', '2022-11-18 14:51:11', 'aba3e00dc968ad42cfee2bef531e9db9', '57978f437264a5372d8091da91dd336d', '151.42.80.145'),
                                                                                               (225, 11, '2022-10-19 12:07:40', '2022-11-19 12:07:40', 'dc0e29802a1ed9d7bbb32d6a35b50628', 'f3d53f9fc603d2ed8275fd22475cfffd', '151.82.124.21'),
                                                                                               (230, 11, '2022-10-20 14:53:30', '2022-11-20 14:53:30', '6cdf73990861355434b87726b0c75d98', '304273f0a3a89d74fd4175bf835b4ad0', '151.42.37.239'),
                                                                                               (232, 12, '2022-10-21 11:24:39', '2022-11-21 11:24:39', 'd7e0fdba265bab1c4a047d22bf5b7fde', 'a96e7843ce9e280cd348158c462c3e96', '188.12.92.13'),
                                                                                               (235, 11, '2022-10-21 18:34:34', '2022-11-21 18:34:34', 'ef5d7ba61201830c9abd700f9e83cc8e', '6602ec97ae6c553abc9f808592100a64', '151.42.37.239'),
                                                                                               (238, 11, '2022-10-24 14:41:55', '2022-11-24 14:41:55', 'bf8a6fdcc32c89d1290cee4aa99b4b68', '67ee5699bbae54cb4d58ae143d4d77ad', '151.42.71.244'),
                                                                                               (240, 11, '2022-10-24 18:36:41', '2022-11-24 18:36:41', '1fd2bb6775d8fd69b9fec3b6cffefed8', 'ca11e5ce17cedea7f4e8a715113370bb', '151.42.71.244'),
                                                                                               (244, 11, '2022-10-25 20:38:50', '2022-11-25 20:38:50', '98abcdc98fff9ef23016ccb00b9775e2', '2923f7bd1f2d936a0d06894b508af5da', '151.42.87.117'),
                                                                                               (246, 11, '2022-10-26 12:20:53', '2022-11-26 12:20:53', '395ca7d5430db61b5f5f1a96d2cac3da', '1cfca2c39d08971b37ef84ac722e92b7', '151.68.150.171'),
                                                                                               (247, 11, '2022-10-26 20:36:07', '2022-11-26 20:36:07', 'ab2d4b74eea2a1628e37a34d351359da', '3fa3dd8e9ddda48043e063c7d84d4f5e', '151.42.87.117'),
                                                                                               (250, 11, '2022-10-27 20:32:59', '2022-11-27 20:32:59', '9dea8595bdb8f5e7af8036867ea6885a', 'ab4ff775dbebb3b01f801e3dd62cb7af', '151.42.87.117'),
                                                                                               (254, 11, '2022-10-28 15:55:36', '2022-11-28 15:55:36', '20d0b7df16d8c51ef17db3f59525762d', 'dbc22496c213ce997c2039480501afa7', '151.38.129.82'),
                                                                                               (256, 11, '2022-10-28 19:01:38', '2022-11-28 19:01:38', 'fddeb4b76c9c78d1e01b7daa91308180', 'a901ff3c1bb8e4a7bb3221bb533eba49', '151.42.87.117'),
                                                                                               (258, 17, '2022-10-31 09:29:55', '2022-11-30 09:29:55', '0f04796311754639b7302de5167517ed', 'a0b8608d60dc03cd829d0ac858fa7789', '188.116.9.183'),
                                                                                               (259, 12, '2022-11-02 16:46:01', '2022-12-02 16:46:01', '58196a9f83ae1a10060702069748134c', 'f764e72e323ae82ac1c15f9a56b4b936', '93.65.242.168'),
                                                                                               (260, 11, '2022-11-02 17:54:17', '2022-12-02 17:54:17', '614610ca9b3c6f2dbda68209ae52a893', '91e587cf9fe394d72bd73da49a091de5', '151.42.87.117'),
                                                                                               (261, 20, '2022-11-02 17:58:52', '2022-12-02 17:58:52', 'f06a71afcf669e8a50d6a1c755b1ea46', '04f4bd4e7906374bacb4a80c6795e2da', '82.49.129.55'),
                                                                                               (263, 25, '2022-11-03 13:23:25', '2022-12-03 13:23:25', '7f07a5e4d83dc75b48e913bb5f651d98', 'e3a98af5cae2fba60fdc335892da5fe9', '82.55.235.82'),
                                                                                               (267, 11, '2022-11-03 17:29:37', '2022-12-03 17:29:37', '14854a6a6293a3bdf98386aa36d3854c', 'c7e5cabb178296dd81d73d743add6db2', '151.42.87.117'),
                                                                                               (268, 18, '2022-11-03 21:33:43', '2022-12-03 21:33:43', '0f062fef236f4ef92943b10b1889b216', '7110d85f1b92a3a31b884816aaf8fd4f', '79.12.122.210'),
                                                                                               (273, 17, '2022-11-06 18:36:00', '2022-12-06 18:36:00', '59c4fdb3707f6221a5f66c1205b585f1', 'd9dc8095bb95afa4f0c2db8cd6c39a7b', '79.143.123.123'),
                                                                                               (274, 11, '2022-11-07 12:07:13', '2022-12-07 12:07:13', '7557eac77f0961ccd6eba356e9365a5b', '1e3e7cc734dfc24ad421c5dfefc49a53', '151.34.135.221'),
                                                                                               (275, 11, '2022-11-07 17:39:24', '2022-12-07 17:39:24', '20fc49e3d95955fdfc3313bcba3ae9f3', '782e5519a0d90b7d6db5b650f59f773a', '151.42.87.117'),
                                                                                               (277, 11, '2022-11-08 15:30:42', '2022-12-08 15:30:42', 'b3348036b81f30f4748d5b98e4af2675', '17ba4ebef7a8d0fff1083190b203fb16', '151.42.87.117'),
                                                                                               (280, 11, '2022-11-09 06:51:24', '2022-12-09 06:51:24', 'da8a0d44d379eeb18d613557902d6ded', '20b468e76d3af5d0248dbe9049180336', '151.42.87.117'),
                                                                                               (281, 11, '2022-11-09 13:50:12', '2022-12-09 13:50:12', '8cf7aa9c35808327202c35c1059f4dae', '2d55135221dbb0fad2da5cba4603da44', '151.38.229.208'),
                                                                                               (283, 6, '2022-11-09 16:13:44', '2022-12-09 16:13:44', 'f135a022d5474c717660e67df93bbee8', '57da368a1da9a3fd1fca2864dc58ded3', '151.57.0.22'),
                                                                                               (287, 14, '2022-11-10 11:19:06', '2022-12-10 11:19:06', 'f633ef97ec8f0778ad0d098d41785726', 'a5546ec99e2a82dc48a8dafd1a6e6edf', '158.148.139.236'),
                                                                                               (288, 11, '2022-11-10 16:23:05', '2022-12-10 16:23:05', '370b1b3914c4b5867284eb75c67097f6', '85c4ce338f17908cebb83a856189996d', '151.42.53.135'),
                                                                                               (290, 11, '2022-11-12 14:20:53', '2022-12-12 14:20:53', '3dcdb28ee2cf87773d77b71b743afe92', '149fa14e2a81b881616aa8c01344c22d', '151.42.53.135'),
                                                                                               (291, 26, '2022-11-12 17:10:39', '2022-12-12 17:10:39', '7d62524bcb02db571f964bc4b7296003', 'c0bb55fb7ca937bd9e026ef79b6f5569', '93.47.32.103'),
                                                                                               (293, 13, '2022-11-12 19:57:19', '2022-12-12 19:57:19', 'a1efdd12bd8d91271cf5736e48af4fc9', '456bda87b4ff8f0672b0cbae5eccbe43', '2.38.49.166'),
                                                                                               (295, 11, '2022-11-15 20:52:27', '2022-12-15 20:52:27', 'b68947eee8c98892b3f840e77e81d8ac', 'b1506cd4bcd212bf9fb438e46e994705', '151.42.53.135'),
                                                                                               (299, 11, '2022-11-16 07:27:01', '2022-12-16 07:27:01', 'f12b361cf122f24c40c1e974d06ce7bc', '06b9dedc5cbedab16b4c00dc83e8c6fb', '151.42.53.135'),
                                                                                               (305, 11, '2022-11-17 12:45:57', '2022-12-17 12:45:57', 'c7cc52032db2d4cf0c68e3d614f07cde', 'cbfd55837845fceb2a6bc857deb4aa35', '151.34.151.207'),
                                                                                               (306, 1, '2022-11-17 16:18:21', '2022-12-17 16:18:21', 'e7569762a3cd93ba63721fa393ddfa1f', 'f72819f2d7671ac7e0d5000b029ae869', '188.12.92.13'),
                                                                                               (309, 14, '2022-11-17 18:19:13', '2022-12-17 18:19:13', 'ddd47f2c09a34dbe604d5993bb83ba12', 'dd1dba8265452cb64f3c46a636daccb1', '87.26.107.229'),
                                                                                               (312, 11, '2022-11-18 14:36:53', '2022-12-18 14:36:53', '99015a0b9979ce112b0fe82a9300c712', '6065504c6ce44e2457bb07cc1baaf95a', '151.42.53.135'),
                                                                                               (313, 26, '2022-11-18 15:03:45', '2022-12-18 15:03:45', '0f5d088e83689cb4cfa72f63f51e6079', 'a01217fd3da4f3ba4432e2d3407f6b18', '93.44.6.196'),
                                                                                               (315, 12, '2022-11-21 09:58:42', '2022-12-21 09:58:42', '71c1b3b26554b11f20056a205ba99329', 'e360f9920276c60377752feded92f0a3', '37.180.68.71'),
                                                                                               (317, 11, '2022-11-21 18:34:27', '2022-12-21 18:34:27', '5ed09e282590a4109263888253f02df0', '5668282d69b6f7f4b5cb250528b77013', '151.42.53.135'),
                                                                                               (318, 11, '2022-11-22 09:01:39', '2022-12-22 09:01:39', '769995f42e16349d488a7344bb384e73', '1179912090a5196b47f0c35de6812f26', '151.42.40.241'),
                                                                                               (319, 26, '2022-11-23 07:18:55', '2022-12-23 07:18:55', 'e5381b2207e359518b5564415f92ea2b', '57f4467544c8427cd28923e74431ac73', '93.47.33.55'),
                                                                                               (320, 14, '2022-11-23 09:50:32', '2022-12-23 09:50:32', 'fab79c191fcce56c68e7aee9ab641fae', 'b7b4ce5a22b8506e60196c8d30b5bff3', '93.33.37.251'),
                                                                                               (322, 9, '2022-11-23 22:38:50', '2022-12-23 22:38:50', 'b6acade5d8323d861c4eec09ebe6ae5f', 'e4fc154c731033abfcc26cf4bc19d2f0', '79.52.198.9'),
                                                                                               (325, 11, '2022-11-24 22:52:40', '2022-12-24 22:52:40', '9753d85d691443f590168e19f0dbaf23', 'd919011f204fe8e160f0bc105ecd7315', '151.42.40.241'),
                                                                                               (327, 1, '2022-11-27 16:52:05', '2022-12-27 16:52:05', '1eb297a52fb4c7667b4e882d32a2af4b', 'a9ca88415239c88ff29fd5e649adafc5', '95.233.133.121'),
                                                                                               (331, 11, '2022-11-29 18:18:32', '2022-12-29 18:18:32', 'ef7295f4a383ca3e96c4b1c9c05e117b', '8e4d1228cb45fe02e244ccd6cdf695de', '151.42.53.57'),
                                                                                               (333, 11, '2022-11-30 07:19:45', '2022-12-30 07:19:45', '4295a066d08b27f37562723eb94988c3', '2542e9cbaef5227433da69fe003e508d', '151.42.53.57'),
                                                                                               (334, 11, '2022-12-01 13:50:42', '2023-01-01 13:50:42', '5ca6ecb31ac7dd5b22c902c5d8442e4f', 'b8fbf907ca7147b9c29c97fc71494593', '151.42.53.57'),
                                                                                               (335, 9, '2022-12-01 22:38:33', '2023-01-01 22:38:33', 'a3be3d30fe4bf67db8161eae931106f3', 'e4b52cf7e6a1c8d23914123f34973e90', '79.52.198.9'),
                                                                                               (336, 1, '2022-12-02 14:22:44', '2023-01-02 14:22:44', 'd0bf9994af147ea24c33cc1d55249526', 'e5f3bf42476c21c52bb5fd2be41d7d9c', '79.35.145.104'),
                                                                                               (338, 11, '2022-12-05 20:21:56', '2023-01-05 20:21:56', 'b54c00c307b912e979d6fe94c6740303', '47c3e26106ad2c3dc3f8ad52d6a0942d', '176.207.132.131'),
                                                                                               (340, 25, '2022-12-06 14:04:41', '2023-01-06 14:04:41', 'e8bc9a04eb16121beeb6f228c74f567c', '68ab822a149cc90543ecd04ab74163cc', '79.27.111.30'),
                                                                                               (342, 11, '2022-12-06 16:32:22', '2023-01-06 16:32:22', 'bc34864c4df494b237cfc05fd48515ea', '2c345c74befbfdc774cb05d5f4ab6f13', '176.207.132.131'),
                                                                                               (345, 20, '2022-12-06 17:48:52', '2023-01-06 17:48:52', '9b3ae44413a9a90dd292bdee9ad697e4', '1073ab19d726dc6d2455e34f1b68c231', '79.26.76.190'),
                                                                                               (346, 20, '2022-12-07 17:11:06', '2023-01-07 17:11:06', '321c4d1f317c1b3bd39d1f7c400d6924', 'b24299e250a01db00f47e58c8b95a1ed', '79.26.76.190'),
                                                                                               (348, 11, '2022-12-07 19:20:18', '2023-01-07 19:20:18', '9a4912597573c1546489d4e98ff3bf8e', 'fb6db0432816501eb98581f6199c6cea', '176.207.132.131'),
                                                                                               (351, 12, '2022-12-09 11:15:53', '2023-01-09 11:15:53', 'fc2d93de4af9384ce608d2544e57fd99', 'f7d5f4840c97084c2c5ba49f1ccd8f8d', '37.102.16.89'),
                                                                                               (355, 17, '2022-12-10 10:43:24', '2023-01-10 10:43:24', 'f35414d3e365cc74cdd0825dcb444722', 'd284b120e1ddc8ac63c322b9a96d32da', '194.145.250.180'),
                                                                                               (356, 11, '2022-12-10 11:00:30', '2023-01-10 11:00:30', '4982406440cb2729764e592c52bf4fd5', 'e86cdbfe1d6044384086192aa8c757bf', '151.61.214.239'),
                                                                                               (357, 11, '2022-12-12 19:15:12', '2023-01-12 19:15:12', '0b0a04a146037fc0ba23892cbdc093fe', 'c73c230d8352b5926a8966e2e673ed33', '151.61.214.239'),
                                                                                               (358, 13, '2022-12-13 18:37:51', '2023-01-13 18:37:51', 'f62e2824fa1adefa0b5aace6f2964acf', 'a3838a429ae881958857ce1ff399dbcb', '83.225.85.168'),
                                                                                               (362, 12, '2022-12-15 14:47:14', '2023-01-15 14:47:14', '3bbf8f38001c4bc3bcf677e231f7a745', 'e22d4e321711336ec07312f2479a0732', '188.12.92.13'),
                                                                                               (374, 12, '2022-12-22 12:27:35', '2023-01-22 12:27:35', '8b83c4317d0ea747b8cab48704a168f7', '02eba39be9f87f3c288faba4e8fb305e', '213.99.16.171'),
                                                                                               (375, 11, '2022-12-24 15:04:41', '2023-01-24 15:04:41', '02377a7c47a6c041fe693fa8fcc97e4b', 'b6eac27043694a356b9ae85d51bfe340', '176.207.133.233'),
                                                                                               (376, 1, '2022-12-24 21:54:27', '2023-01-24 21:54:27', '36f40be5d3057d87715bbd2865b0a017', '1c64ba98c7d7a2dabdccfdefb1c5fee9', '151.37.173.137'),
                                                                                               (377, 11, '2022-12-31 09:43:39', '2023-01-31 09:43:39', '2cb09736e57924d915c8d97ae7eff1f0', '8e1a2379d4b861abe40732a3179ed4cf', '151.42.56.136'),
                                                                                               (378, 27, '2023-01-03 16:01:40', '2023-02-03 16:01:40', '77bf5c12e7f0c980c6bb22f06c3cd817', '4c713e226edc752e377ce46aa771654f', '188.217.54.241'),
                                                                                               (379, 11, '2023-01-03 20:55:15', '2023-02-03 20:55:15', 'e01ced8c681534e86b1b872a484326f6', '36adf478f02f4b49a76c97ba83d78966', '151.42.56.136'),
                                                                                               (381, 11, '2023-01-09 17:13:00', '2023-02-09 17:13:00', 'bd8e99c658dd5e6a0db388acad056e2b', '6ec1315a54916956176eff9d1423d06a', '151.18.177.253'),
                                                                                               (382, 12, '2023-01-10 10:08:45', '2023-02-10 10:08:45', '84cd2e4c35f7a2e339fa35eb3f9858d0', 'b7f54332fc55a089a971432201f5ade6', '188.12.92.13'),
                                                                                               (383, 12, '2023-01-10 15:47:51', '2023-02-10 15:47:51', '9fce18f4123096ae4ad72c7374a7ad87', 'e1658eb52bcdd3271850734eeafe96d8', '93.65.242.5'),
                                                                                               (387, 20, '2023-01-10 20:08:41', '2023-02-10 20:08:41', 'e681aefed377ce11141055e867ab506e', '0cdd803b79462b1a9aeee17414d7b598', '87.20.179.123'),
                                                                                               (388, 11, '2023-01-11 07:52:30', '2023-02-11 07:52:30', '192f92b7117452b3429d5ec3cbffa73a', '69dd560f4a995ed4e5ed49798fbd27b7', '151.42.56.136'),
                                                                                               (389, 25, '2023-01-11 12:44:19', '2023-02-11 12:44:19', 'cd4dc22204fccec350ae0a1478de32a7', 'b7a66435a96e57677ac670de3ba98ec4', '79.55.217.201'),
                                                                                               (390, 6, '2023-01-11 15:13:52', '2023-02-11 15:13:52', '5d2ba5a53a8a37511cae31dc884de04e', '01c39d3865b41a70d66b2ac84b558400', '151.47.109.65'),
                                                                                               (392, 22, '2023-01-11 15:37:13', '2023-02-11 15:37:13', 'f9840a11debb3fe2f50c58f3056277b1', 'f42fabbeec491c4d7e207d90365ee830', '2.37.3.30'),
                                                                                               (395, 26, '2023-01-11 18:57:26', '2023-02-11 18:57:26', '7999379c73d235e812acd05dd6200d7f', '74a298e757ac388d0cdbeb3996b34d5a', '93.47.33.55'),
                                                                                               (396, 28, '2023-01-11 20:13:15', '2023-02-11 20:13:15', 'eccc4b96e7c1b731d2c3dfd0475580c8', '0b3510e479898454a3e223664a0ae8e9', '95.238.20.83'),
                                                                                               (397, 29, '2023-01-12 10:01:38', '2023-02-12 10:01:38', '7328f8c8d315dff36c8112e0638d7e9a', '0cbe11947c519b11ba1c3fba90924e4d', '2.198.196.209'),
                                                                                               (400, 13, '2023-01-13 07:56:11', '2023-02-13 07:56:11', '3e2a3ee6c408c60dac03ffc56ff98987', '98e599059d58018864a3dd972604eb53', '83.225.100.178'),
                                                                                               (401, 20, '2023-01-13 10:11:46', '2023-02-13 10:11:46', 'a1d3b9769258f68410acb57b5b782dad', 'adc50f8f19dac4ee4d924e3d3b1ac8b9', '93.36.20.166'),
                                                                                               (402, 11, '2023-01-13 11:08:47', '2023-02-13 11:08:47', '8f4771d92dd172227f6cf96c2a474239', 'e4333970e68f87c8521c9250143b77ee', '151.68.223.102'),
                                                                                               (404, 1, '2023-01-13 13:20:03', '2023-02-13 13:20:03', '920063f2646d9820d839c0a87627bd6a', '6de183a35aacea7921639fde13d66581', '188.12.92.13'),
                                                                                               (406, 6, '2023-01-13 16:38:46', '2023-02-13 16:38:46', '39d9c6776fd0dc72fed596e2305fe561', '3cb2720133d4adf594baf611bc34709b', '151.47.101.75'),
                                                                                               (407, 14, '2023-01-13 18:08:34', '2023-02-13 18:08:34', 'f063c14b9ef4224ed4ad5ddc1846c1fb', '1ad08451e6e7255fef5fdad26f59c215', '87.26.107.229'),
                                                                                               (409, 11, '2023-01-16 11:15:51', '2023-02-16 11:15:51', '66f797d5ab7da77cabd101025d609617', 'f1e2313f52e3a2ee8365345691f48b3f', '151.34.140.50'),
                                                                                               (415, 17, '2023-01-16 14:15:54', '2023-02-16 14:15:54', '9b209f1640394058025c2b6f785ab968', 'abb4dd6fd1e2fce72b0bb578f04dd3eb', '188.12.92.13'),
                                                                                               (416, 26, '2023-01-16 17:28:28', '2023-02-16 17:28:28', '441d2c37f7de896627b503988616e993', 'a769237b8c17ed2b291d3d1b9b6f4eb3', '93.47.33.55'),
                                                                                               (417, 11, '2023-01-16 18:32:57', '2023-02-16 18:32:57', '35e3987b8c2b169d993c46bc79e2996a', '8afd9acaf9473f9b5f324567b0f6c40e', '176.207.134.156'),
                                                                                               (418, 9, '2023-01-16 21:26:16', '2023-02-16 21:26:16', 'a270589d910cdf1e8a272ed26ee62958', '91bfafada0cc1b6c6dd36dda22e848f5', '79.32.216.252'),
                                                                                               (419, 24, '2023-01-17 08:09:21', '2023-02-17 08:09:21', 'f1a2ea9a46ceb8414470f6c849056f4e', '1caf0c9c6bbb7f4bebb7c4b8993156b3', '188.12.92.13'),
                                                                                               (421, 14, '2023-01-17 12:43:41', '2023-02-17 12:43:41', '5dabf3c13decb42743dd3f1995616425', 'af3602523451458ed1c20bcc61271f33', '93.32.140.167'),
                                                                                               (428, 11, '2023-01-18 13:03:52', '2023-02-18 13:03:52', '6716ebd4569b54cf585679c085d04c1b', 'bd38d1c57e4e9bb316bbe2ea4b20a5cf', '151.36.186.122'),
                                                                                               (441, 1, '2023-01-20 08:32:28', '2023-02-20 08:32:28', 'd51c19a0c48998abccf0234a853333cd', 'e7be5642abbd8ab05fb619f6e2deca4f', '188.12.92.13'),
                                                                                               (442, 26, '2023-01-20 12:27:18', '2023-02-20 12:27:18', '1ff5c2493ba33952ad6252a1a2acef58', '076245dd6e36daf7c69ab58f701ce86a', '93.40.37.56'),
                                                                                               (449, 11, '2023-01-21 10:02:01', '2023-02-21 10:02:01', '67c87d3d3af0688aa604b045ec63fa2c', 'cdeddc94786cce2f504991a47d41773d', '151.42.53.159'),
                                                                                               (452, 9, '2023-01-22 18:14:57', '2023-02-22 18:14:57', 'c1b133b1aad48e7a556e118aeff7c653', 'be29ae2ad2f476379d15c7fce3bf10d3', '87.13.187.41'),
                                                                                               (454, 9, '2023-01-23 13:16:40', '2023-02-23 13:16:40', '2d3355ab99e482cc8d5b23343bc98746', 'f435058204092a26741b4a74449a70ba', '87.13.187.41'),
                                                                                               (459, 24, '2023-01-24 09:24:49', '2023-02-24 09:24:49', '540eff3a10d0ff10bb6a2d75c38c5f39', '9b2550975dd130f2b68bf79605df8b76', '79.21.240.58'),
                                                                                               (466, 11, '2023-01-25 18:16:14', '2023-02-25 18:16:14', '136c490e0725581924358094754d6832', '2db92f0cfb0788fbd2062ab4428c38d0', '151.42.53.159'),
                                                                                               (473, 11, '2023-01-30 13:20:51', '2023-02-28 13:20:51', 'd44bedb020a3bc8de359f651d633bedd', 'cbaccad40ea345df87bb93a07113877d', '151.42.35.239'),
                                                                                               (478, 12, '2023-01-31 13:43:17', '2023-02-28 13:43:17', 'c902469a35a7e7cc7d38fab41120d16a', 'ea04b45cdf5f32ea27f996483d140a22', '109.116.207.153'),
                                                                                               (481, 11, '2023-01-31 21:41:36', '2023-02-28 21:41:36', '223f0984046cc8c328f95ef6d0dc1ab9', '3b0ed9da1c453652e15d1958237c2153', '151.42.35.239'),
                                                                                               (486, 11, '2023-02-02 11:44:21', '2023-03-02 11:44:21', '9f3548c6b95352f53e1452ddb0d8e2b7', '1613cb21c7896d71d2a7a939ec87a75f', '151.38.26.109'),
                                                                                               (490, 11, '2023-02-02 20:06:35', '2023-03-02 20:06:35', 'b4f4d601b5c6b84f02efbd636abf9488', 'e80f3292a100dafdea7380e2db1b8afc', '151.42.35.239'),
                                                                                               (496, 11, '2023-02-03 17:45:10', '2023-03-03 17:45:10', 'b2e3b6c4c53920470887199d813ca5c3', '4eb73199be2fcfc9029200e13a77295e', '151.42.35.239'),
                                                                                               (498, 11, '2023-02-06 15:37:06', '2023-03-06 15:37:06', '078e96de543f6e2194d1e529e40fe72e', '9cccee025b3ab9a4086708a93ee6e05b', '151.42.35.239'),
                                                                                               (502, 17, '2023-02-07 20:30:58', '2023-03-07 20:30:58', 'fca9d62a237b85c05a466839646b76de', '764fde3b18a2a6e06906af623c4a478e', '212.69.142.233'),
                                                                                               (504, 1, '2023-02-08 09:12:17', '2023-03-08 09:12:17', '0ab5d4d98bf547f1163544f808545bf4', '52d37b2ba8a68f7d4def9d6e14cf03a1', '188.12.92.13'),
                                                                                               (511, 12, '2023-02-09 11:54:23', '2023-03-09 11:54:23', '703bef548a7ba2770db9cafcbe3bdf32', '9689c3b231ba9dcc99abf46acfa0b8d6', '188.12.92.13'),
                                                                                               (517, 11, '2023-02-10 06:50:58', '2023-03-10 06:50:58', '5a0332aac83ecb27228ef6813a88d240', '5ed1e245d5ab9c92a12aa04352f6c606', '151.42.35.239'),
                                                                                               (519, 20, '2023-02-10 15:03:27', '2023-03-10 15:03:27', '36e4c31042d9d0322a12414d18846a57', '04b06d0a88bfeb26c3e25104a034e373', '80.117.73.48'),
                                                                                               (520, 22, '2023-02-10 15:39:38', '2023-03-10 15:39:38', '110da188254663c0ea54bb2cf26bf163', '0548aa3edf46ad6249207b549d6348bd', '2.37.3.30'),
                                                                                               (524, 25, '2023-02-13 13:39:59', '2023-03-13 13:39:59', 'd4473b84aaffb93a995dc28f68852722', 'ea5467af8c96f33adb655654f5ea22b1', '82.61.156.85'),
                                                                                               (525, 6, '2023-02-13 17:06:06', '2023-03-13 17:06:06', '916e8b0bd1ba5cd036efea79fc52285f', 'dfb683b85ee35ad0943f6031d1a9a8f1', '151.19.47.60'),
                                                                                               (526, 28, '2023-02-13 17:42:15', '2023-03-13 17:42:15', 'b1ff9d37e303df0ad320713e1bc0a339', '6ad3b0f4074b2143d1171b21039f2447', '82.49.107.174'),
                                                                                               (527, 14, '2023-02-13 18:05:04', '2023-03-13 18:05:04', '68a7e41edea2f1cca5acaa6b83a2e354', '64a5bf416148ff80ae2e14be3633f53b', '87.26.107.229'),
                                                                                               (530, 13, '2023-02-13 19:55:27', '2023-03-13 19:55:27', '92af5492c97e8679951a56fbf357454f', 'c6c284c32dd6df6db42fc78f6e623011', '83.225.75.135'),
                                                                                               (533, 20, '2023-02-14 14:02:53', '2023-03-14 14:02:53', '1e6ad91c0bfe55e18ad96d06a6310a36', 'fedc9508e0b8880be37417a4540fe182', '93.43.147.228'),
                                                                                               (536, 27, '2023-02-14 22:27:45', '2023-03-14 22:27:45', '253d115c57cd5150ac23126cb9a52aa0', '92281a5405b341a15b4ed8d435f613e3', '2.39.143.33'),
                                                                                               (539, 11, '2023-02-16 16:37:04', '2023-03-16 16:37:04', '1f18cf94f51cbaaef1c774edd8fc2c26', 'f4e3717989049a939365dc00a7837945', '151.42.35.108'),
                                                                                               (540, 9, '2023-02-16 21:02:13', '2023-03-16 21:02:13', '0eff8916e1e4ee3cfb98ec4056bdb940', 'd5dc495d7c452d1aa8add8697ba5e7b7', '109.52.72.44'),
                                                                                               (544, 14, '2023-02-20 12:49:41', '2023-03-20 12:49:41', 'ad20a83dc204bca2917c461d1a6fcbed', 'b124397a3d1a20a03b1c03d34e8cbbcd', '93.33.115.46'),
                                                                                               (545, 11, '2023-02-20 17:26:26', '2023-03-20 17:26:26', 'fb8a8ae6dd6f13139ffdccd53ffb1f48', 'd5a0387bd5ea444c745d2e273b6314a4', '151.42.35.108'),
                                                                                               (554, 11, '2023-02-22 18:42:01', '2023-03-22 18:42:01', '2404f07e570df0a5d469f9f672df9162', 'e7735561a22365a806d62aa3da9c8939', '151.42.35.108'),
                                                                                               (558, 17, '2023-02-23 15:26:34', '2023-03-23 15:26:34', '342cad521c6b005a8731b35d1dbab4ed', '406370dc9ee6b9722592fd561df66a03', '45.10.75.44'),
                                                                                               (559, 23, '2023-02-23 15:35:30', '2023-03-23 15:35:30', '4db7e8ae3dcdbb2f420f5d86dabed2ff', '39168bb5e160cd1cbe81ddd1da304286', '5.102.11.126'),
                                                                                               (560, 24, '2023-02-23 16:44:18', '2023-03-23 16:44:18', 'ccec13d15a6586ea7a91d933e8cbf623', '3673d8b4be3bb1d702b23a14e7922b19', '79.40.239.47'),
                                                                                               (562, 11, '2023-02-24 11:12:37', '2023-03-24 11:12:37', '302371a6d330a5bcdca0cca5a7fd26b1', '81181cd8b8e909011d1d73d566d383b9', '151.18.162.180'),
                                                                                               (563, 11, '2023-02-24 15:01:50', '2023-03-24 15:01:50', 'fc487053c413f3890989e31f538ff139', '3a4932c16614944e0d8a16bc040c85b6', '151.42.35.108'),
                                                                                               (570, 26, '2023-02-27 15:40:52', '2023-03-27 15:40:52', 'a42cfbdb35839cbb07a9bcba3e8eea53', '404a493e512d25ec3196d4b0145b7593', '93.47.32.191'),
                                                                                               (573, 11, '2023-02-27 18:55:25', '2023-03-27 18:55:25', '56a045ffbe961744e11b56c40f7ee164', 'f97c51764fc4ac1c44655c5de3ca4066', '151.42.35.108'),
                                                                                               (575, 11, '2023-02-28 11:00:36', '2023-03-28 11:00:36', 'f8d43a9238742a00c67483dd13cf6095', '7df6c06919a233d5bb76df2a98623a51', '151.82.90.130'),
                                                                                               (581, 12, '2023-03-02 13:47:10', '2023-04-02 13:47:10', 'fd0a950d7c345784d187d4ea00c5b9bc', 'ed48734f1a87e6a4a94e05dea5598e6b', '109.116.207.153'),
                                                                                               (583, 11, '2023-03-02 23:12:51', '2023-04-02 23:12:51', '6f32cca44c34d964785510afc569ee1d', '5524ce42f03481169f0a3e78c3192b6c', '151.42.35.108'),
                                                                                               (585, 11, '2023-03-04 07:52:53', '2023-04-04 07:52:53', '707a2c7278bbbd24f157f74ba1ebcc05', '2fbabcf88bef16035b36326d2091b470', '151.42.35.108'),
                                                                                               (586, 11, '2023-03-04 08:00:47', '2023-04-04 08:00:47', 'f8ef834fa5596a45cd1fa668015d1f75', 'fd72bc6d63566d6d3140c55ece4a52b8', '151.42.35.108'),
                                                                                               (587, 11, '2023-03-04 08:06:42', '2023-04-04 08:06:42', '789d9c565ff042ff8e58e576d4c25131', 'ce0e2300bab8bba504d18e0fe5582ee3', '151.42.35.108'),
                                                                                               (591, 11, '2023-03-06 16:53:26', '2023-04-06 16:53:26', 'e1a1153022b269092cd9e435af87fb88', '645a40ebb9fa427515517489a46f7315', '151.42.35.108'),
                                                                                               (594, 2, '2023-03-09 10:12:57', '2023-04-09 10:12:57', '8ce4de86306b3bae85399fa68918f9db', '525bb0e392996f38333c2ac012d31b20', '151.43.35.12'),
                                                                                               (597, 32, '2023-03-31 07:14:35', '2023-04-30 07:14:35', '0dd328b0bb4c4ee1f7f5353d6df94ea6', 'f7578e93fa71cc108ad7035ce6c51084', '80.181.15.235'),
                                                                                               (602, 1, '2023-04-05 08:56:02', '2023-05-05 08:56:02', '35f6176e11404a2f23878d64523206c1', '47082288cd0ab9da644a0ad63606e718', '188.12.92.13'),
                                                                                               (604, 12, '2023-04-06 08:45:13', '2023-05-06 08:45:13', '548e0525bd59e9ac7ce40ed4b4198ccc', '585158eefbd6b85c08ec48426ed3b27e', '188.12.92.13'),
                                                                                               (606, 2, '2023-04-07 07:24:41', '2023-05-07 07:24:41', 'ff909c59a5f610398c318bce8c0510dc', '85cf03f9b9c4b5befb8f756bf6c0abbb', '82.84.67.48'),
                                                                                               (608, 1, '2023-04-14 01:39:36', '2023-05-14 01:39:36', 'be44c265b7b84da89072bc06dcb06e75', '896a010544110902504ead9f089ab316', '::1');

-- Dump della struttura di tabella winconf.users
CREATE TABLE IF NOT EXISTS `users` (
                                       `ID` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
                                       `company_ID` smallint(5) unsigned NOT NULL DEFAULT 0,
                                       `group_ID` tinyint(4) NOT NULL DEFAULT 3,
                                       `name` varchar(50) NOT NULL DEFAULT '0',
                                       `surname` varchar(50) NOT NULL DEFAULT '0',
                                       `email` varchar(60) NOT NULL DEFAULT '0',
                                       `password` varchar(32) DEFAULT NULL,
                                       `last_login` datetime DEFAULT NULL,
                                       `is_admin` tinyint(4) DEFAULT 0 COMMENT '0 = no root; 1 = accesso root',
                                       `zones` varchar(255) DEFAULT NULL COMMENT 'ME|PA',
                                       `enabled` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '0 = non abilitato; 1 = abilitato;',
                                       PRIMARY KEY (`ID`),
                                       KEY `ID` (`ID`),
                                       KEY `azienda_ID` (`company_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

-- Dump dei dati della tabella winconf.users: ~32 rows (circa)
INSERT INTO `users` (`ID`, `company_ID`, `group_ID`, `name`, `surname`, `email`, `password`, `last_login`, `is_admin`, `zones`, `enabled`) VALUES
                                                                                                                                               (1, 1, 1, 'Fabrizio', 'Crisafulli', 'f.crisafulli@difar.it', 'd63f4d283f9e00e48ab9668b5374aef2', '2021-09-10 09:22:41', 1, 'ME|CT', 1),
                                                                                                                                               (2, 1, 1, 'Salvatore', 'Di Giovanni', 'marketing@difar.it', '21f617de2635ac3289200fcdd8cfedf7', '2021-09-10 16:48:52', 3, 'ME', 1),
                                                                                                                                               (3, 1, 3, 'Agente', 'Generico', 'generico@difar.it', '1012803c850faae505a225d34b772445', NULL, 0, 'ME', 1),
                                                                                                                                               (4, 1, 2, 'Cettina', 'Merlo', 'vendite@difar.it', 'ab0afec92fba7615223b0814e56bf748', NULL, 0, '*', 1),
                                                                                                                                               (5, 1, 3, 'Stefano', 'Scacciapiche', 'stefano.scacciapiche@gmail.com', '56ac62ffbf40b6499dd6e71c5c7d1d92', NULL, 0, 'RM', 1),
                                                                                                                                               (6, 1, 3, 'Tommaso', 'Perna', 'pernatommaso77@gmail.com', '4a872e26917f1f34a82041c42208c8b1', NULL, 0, 'RM', 1),
                                                                                                                                               (7, 1, 3, 'Paolo', 'Pirozzi', 'pirozzipaolo77@gmail.com', '8287b3d58eaa6661d8684463c2ace5ea', NULL, 0, 'RM', 1),
                                                                                                                                               (8, 1, 3, 'Carlo', 'Carobbio', 'carlo.carobbio@gmail.com', '62be67b76633cd25b801ca5ba0adc9bd', NULL, 0, 'RM', 1),
                                                                                                                                               (9, 1, 3, 'Fabio', 'Del Vecchio', 'fabio1963.fdv@gmail.com', '656da4df94965394bf1604b85e991a90', NULL, 0, 'RM|VT', 1),
                                                                                                                                               (10, 1, 3, 'Ivan', 'Giuffrida', 'ivan.giuffrida.difar@gmail.com', 'd2fa0633973cb70c944c6e0a4bbe7756', NULL, 0, 'TP', 1),
                                                                                                                                               (11, 1, 3, 'Andrea', 'Degli Esposti', 'andreadegliesposti5@gmail.com', 'f3b217c9d29942b3227b4d6cc457752a', NULL, 0, 'BO|MO|FE', 1),
                                                                                                                                               (12, 1, 0, 'Stefania', 'Colosi', 'info@difar.it', '2136414fb82248c85957b93cd5ad26f9', NULL, 0, 'XX', 1),
                                                                                                                                               (13, 1, 3, 'Diletta', 'Rimini', 'diletta_rimini@yahoo.it', '15494d9af490183ae6919372bfe0124b', NULL, 0, 'FI|PO|PT', 1),
                                                                                                                                               (14, 1, 3, 'Terzo', 'Casadei', 'terzocasadei@gmail.com', '721b2f59ac0490c54d2a86fb6d9efffb', NULL, 0, 'FC|RA|RN|RSM', 1),
                                                                                                                                               (15, 1, 3, 'Enrico', 'Merlini', 'enrico.merlini@email.it', '1773d2516ae3f16ecf129d1ae36a14a9', NULL, 0, 'PC|PR|RE', 1),
                                                                                                                                               (16, 1, 3, 'Sandro', 'Pincini', 'sandropincini65@gmail.com', '169831b3e523e2deb59923ec44e5da2c', NULL, 0, 'AN|AP|FM|MC|PU', 1),
                                                                                                                                               (17, 1, 3, 'Federica', 'Castellani', 'f.castellani1975@gmail.com', '1daeb5f24da754fd1d039debe2fd8c0d', NULL, 0, 'PG|TR', 1),
                                                                                                                                               (18, 1, 3, 'SAHEL', 'KOMIN', 'ksahel@hotmail.it', 'XXX', NULL, 0, 'SI|AR|GR', 0),
                                                                                                                                               (19, 1, 3, 'Roberto', 'Vanni', 'vanni_roberto@yahoo.it', '4d9e50b1544e7b0bdae4632ff35336f4', NULL, 0, 'LU|PI|LI|MS', 1),
                                                                                                                                               (20, 1, 3, 'Gianluca', 'Labrozzi', 'gianlulab@gmail.com', '2571eccd1779685fc66b99184cc8c2b2', NULL, 0, 'CH|AQ|PE|TE|CB|IS', 1),
                                                                                                                                               (21, 1, 3, 'Giovanni', 'Ebolito | VENETO ZONA 02', 'giovanni.ebolito@gmail.com', 'ad928e80d9c410056aaa4baa5d505dc5', NULL, 0, 'PD|VI|RO', 1),
                                                                                                                                               (22, 1, 3, 'Monica', 'Agostini | VENETO ZONA 3', 'monica.agostini12@libero.it', '889b031d577ee9871da38812f4c1e4fa', NULL, 0, 'VE|BL|TV', 1),
                                                                                                                                               (23, 1, 3, 'Cinzia', 'Marchiori | VENETO T.A. ZONA 1', 'cinziamarchiori@virgilio.it', 'b12d88e21ab5afa59f84c283425f8de9', NULL, 0, 'BZ|TN|VR', 1),
                                                                                                                                               (24, 1, 3, 'Federica', 'Pravisani | FRIULI V.G. ZONA 1', 'federica.pravisani@gmail.com', 'fb9dd53d265fa8b52d3c739b34c8c110', NULL, 0, 'GO|PN|TS|UD', 1),
                                                                                                                                               (25, 1, 3, 'Diego', 'Guglielmetti', 'guglielmetti.agente@gmail.com', '7570f9f5206852766780db73b86a0322', NULL, 0, 'LT|FR|RM', 1),
                                                                                                                                               (26, 1, 3, 'Michele', 'Di Fino', 'difosuperstar@hotmail.it', '5e0a07af1cdaf6aae5d840a4b5092bb6', NULL, 0, 'AN|AP|FM|MC|PU', 1),
                                                                                                                                               (27, 1, 3, 'Paolo', 'Pantani', 'paolopantani3@hotmail.it', '79d3f3d4e97324feddc651a9df924556', NULL, 0, 'AR|SI|GR', 1),
                                                                                                                                               (28, 1, 3, 'Enza', 'Cosentino', 'enzacos85@hotmail.it', 'dce9e623cb1fdd2e17d54a2165275ce3', NULL, 0, 'CT', 1),
                                                                                                                                               (29, 1, 3, 'Ilenia', 'Cusumano', 'ileniacusimano@libero.it', '51b31e1030db6d336b6ade4f26408525', NULL, 0, 'PA', 1),
                                                                                                                                               (30, 1, 3, 'Giuseppe', 'Toscano', 'peppe.toscano@hotmail.it', '501489f840aad9b58febe18ac58609ce', NULL, 0, 'AG|CL|EN', 1),
                                                                                                                                               (31, 1, 3, 'Simone', 'Segneri', 'segnerisimone.agente@gmail.com', '42d1af3cdc3c8cea35282e9511151f7c', NULL, 0, 'LT|FR|RM', 1),
                                                                                                                                               (32, 1, 3, 'Federica', 'Mantenuto', 'f.mantenuto@difar.it', 'fb8fd5ce749b4737dceb0eb1befdb023', NULL, 0, 'ME', 1);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
