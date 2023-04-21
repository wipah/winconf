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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dump dei dati della tabella winconf.configuratore_opzioni: ~2 rows (circa)
INSERT INTO `configuratore_opzioni` (`ID`, `sottostep_ID`, `opzioni_formula_ID`, `opzione_nome`, `opzione_sigla`, `opzione_descrizione`, `check_dipendenze`, `check_dimensioni`, `opzione_formula_valore`, `ordine`, `visibile`) VALUES
                                                                                                                                                                                                                                     (1, 1, 1, 'A scomparsa', 'scomparsa', 'Telaio a scomparsa. Etc.', 0, 0, 4, NULL, 1),
                                                                                                                                                                                                                                     (2, 1, 1, 'Visibile', 'vis', 'Visibile', 1, 1, 1, NULL, 1);

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
                                                                        `sottostep_ID` mediumint(9) NOT NULL DEFAULT 0,
                                                                        `opzione_valore_ID` mediumint(9) NOT NULL DEFAULT 0,
                                                                        `confronto` tinyint(3) unsigned DEFAULT NULL COMMENT '0: < (minore); \r\n1: <= minore uguale; \r\n2: = (uguale);\r\n3: >= (maggiore o uguale)\r\n4: > (maggiore)\r\n5: != diverso',
                                                                        `esito` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 = escludi; 1 = includi;',
                                                                        PRIMARY KEY (`ID`),
                                                                        KEY `opzione_ID` (`sottostep_ID`) USING BTREE
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
