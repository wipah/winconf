-- --------------------------------------------------------
-- Host:                         192.168.1.201
-- Versione server:              10.3.28-MariaDB - Source distribution
-- S.O. server:                  Linux
-- HeidiSQL Versione:            12.5.0.6677
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dump della struttura di tabella winconf.clienti
CREATE TABLE IF NOT EXISTS `clienti` (
                                         `ID` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
                                         `company_ID` mediumint(9) NOT NULL,
                                         `erp_ID` varchar(32) NOT NULL DEFAULT '0',
                                         `linked_user_ID` mediumint(8) unsigned DEFAULT NULL,
                                         `user_ID` int(11) DEFAULT NULL,
                                         `category_ID` mediumint(8) unsigned DEFAULT NULL,
                                         `ragione_sociale` varchar(128) NOT NULL DEFAULT '0',
                                         `nome` varchar(255) DEFAULT NULL,
                                         `cognome` varchar(255) DEFAULT NULL,
                                         `codice_fiscale` varchar(16) NOT NULL DEFAULT '0',
                                         `partita_iva` varchar(16) NOT NULL DEFAULT '0',
                                         `indirizzo_via` varchar(128) NOT NULL DEFAULT '0',
                                         `indirizzo_numero` varchar(12) DEFAULT NULL,
                                         `indirizzo_citta` varchar(128) NOT NULL DEFAULT '0',
                                         `indirizzo_provincia` varchar(128) NOT NULL DEFAULT '0',
                                         `indirizzo_cap` varchar(16) NOT NULL DEFAULT '0',
                                         `indirizzo_nazione` varchar(32) NOT NULL DEFAULT '0',
                                         `fatturazione_elettronica` varchar(8) DEFAULT NULL,
                                         `email` varchar(128) DEFAULT NULL,
                                         `telefono` varchar(128) DEFAULT NULL,
                                         `cellulare` varchar(50) NOT NULL DEFAULT '',
                                         `pec` varchar(128) DEFAULT NULL,
                                         `privacy_data` datetime DEFAULT NULL,
                                         `privacy` tinyint(3) unsigned DEFAULT NULL,
                                         `privacy_firma` mediumblob DEFAULT NULL,
                                         `iban` varchar(60) DEFAULT NULL,
                                         `banca_nome` varchar(128) DEFAULT NULL,
                                         `note` text DEFAULT NULL,
                                         `ultimo_aggiornamento` datetime DEFAULT current_timestamp(),
                                         `is_updated` tinyint(3) unsigned DEFAULT 0,
                                         `ultimo_aggiornamento_data` date DEFAULT NULL,
                                         `origine_aggiornamento` tinyint(3) unsigned DEFAULT 0,
                                         PRIMARY KEY (`ID`) USING BTREE,
                                         KEY `ID` (`ID`) USING BTREE,
                                         KEY `company_ID` (`company_ID`) USING BTREE,
                                         KEY `erp_ID` (`erp_ID`) USING BTREE,
                                         KEY `user_ID` (`user_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=5020 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=49152;

-- L’esportazione dei dati non era selezionata.

-- Dump della struttura di tabella winconf.clienti_destinazioni
CREATE TABLE IF NOT EXISTS `clienti_destinazioni` (
                                                      `ID` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
                                                      `cliente_ID` mediumint(8) unsigned NOT NULL,
                                                      `company_ID` mediumint(8) unsigned NOT NULL,
                                                      `erp_ID` varchar(32) DEFAULT NULL,
                                                      `ragione_sociale` varchar(128) DEFAULT NULL,
                                                      `codice_fiscale` varchar(16) DEFAULT NULL,
                                                      `partita_iva` varchar(16) DEFAULT NULL,
                                                      `indirizzo_via` varchar(64) DEFAULT NULL,
                                                      `indirizzo_numero` varchar(8) DEFAULT NULL,
                                                      `indirizzo_citta` varchar(64) DEFAULT NULL,
                                                      `indirizzo_provincia` varchar(64) DEFAULT NULL,
                                                      `indirizzo_cap` varchar(64) DEFAULT NULL,
                                                      `indirizzo_nazione` varchar(64) DEFAULT NULL,
                                                      `fatturazione_elettronica` varchar(8) DEFAULT NULL,
                                                      `telefono` varchar(64) DEFAULT NULL,
                                                      `cellulare` varchar(64) DEFAULT NULL,
                                                      `email` varchar(128) DEFAULT NULL,
                                                      `pec` varchar(128) DEFAULT NULL,
                                                      `iban` varchar(128) DEFAULT NULL,
                                                      `banca_nome` varchar(128) DEFAULT NULL,
                                                      `notes` text DEFAULT NULL,
                                                      `ultimo_aggiornamento` datetime DEFAULT current_timestamp(),
                                                      `update_source` tinyint(3) unsigned DEFAULT 0 COMMENT '0 = direct; 1 = import',
                                                      PRIMARY KEY (`ID`) USING BTREE,
                                                      KEY `company_ID` (`company_ID`) USING BTREE,
                                                      KEY `customer_ID` (`cliente_ID`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=579 DEFAULT CHARSET=utf8;

-- L’esportazione dei dati non era selezionata.

-- Dump della struttura di tabella winconf.companies
CREATE TABLE IF NOT EXISTS `companies` (
                                           `ID` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
                                           `business_name` varchar(255) NOT NULL,
                                           `orders_email` varchar(50) NOT NULL,
                                           `enabled` tinyint(3) unsigned DEFAULT 0 COMMENT '0 = non attiva; 1 = attiva;',
                                           `send_order_to_customer` tinyint(3) unsigned DEFAULT 0,
                                           `notice` text DEFAULT NULL,
                                           `api_uri` varchar(100) DEFAULT NULL,
                                           `configuratore_hash` varchar(5) DEFAULT NULL,
                                           PRIMARY KEY (`ID`),
                                           KEY `ID` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- L’esportazione dei dati non era selezionata.

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COMMENT='Categorie principali del configuratore. Rappresentano il punto di ingresso per la creazione di un nuovo preventivo.';

-- L’esportazione dei dati non era selezionata.

-- Dump della struttura di tabella winconf.configuratore_formule
CREATE TABLE IF NOT EXISTS `configuratore_formule` (
                                                       `ID` mediumint(9) NOT NULL AUTO_INCREMENT,
                                                       `formula_sigla` varchar(60) CHARACTER SET utf8 NOT NULL DEFAULT '0',
                                                       `formula_descrizione` varchar(60) CHARACTER SET utf8 NOT NULL DEFAULT '0',
                                                       `formula_help` text NOT NULL,
                                                       PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- L’esportazione dei dati non era selezionata.

-- Dump della struttura di tabella winconf.configuratore_opzioni
CREATE TABLE IF NOT EXISTS `configuratore_opzioni` (
                                                       `ID` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
                                                       `categoria_ID` mediumint(8) unsigned DEFAULT NULL,
                                                       `step_ID` mediumint(8) unsigned DEFAULT NULL,
                                                       `sottostep_ID` mediumint(8) unsigned DEFAULT NULL,
                                                       `opzioni_formula_ID` mediumint(8) unsigned DEFAULT NULL,
                                                       `opzione_nome` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
                                                       `opzione_sigla` varchar(60) CHARACTER SET utf8 DEFAULT NULL,
                                                       `opzione_descrizione` text CHARACTER SET utf8 DEFAULT NULL,
                                                       `check_dipendenze` tinyint(4) DEFAULT NULL COMMENT '0 = nessun controllo dipendenze; 1 = dipendenza di tipo "escludi"; 2 = dipendenza di tipo "includi"',
                                                       `check_dimensioni` tinyint(4) DEFAULT NULL COMMENT '0 = nessun controllo; 1 = controlla le dimensioni',
                                                       `opzione_formula_valore` decimal(20,6) DEFAULT NULL,
                                                       `ordine` smallint(6) unsigned DEFAULT NULL,
                                                       `visibile` tinyint(4) unsigned DEFAULT NULL COMMENT '0 = non visibile; 1 = visibile',
                                                       PRIMARY KEY (`ID`),
                                                       KEY `sottostep_ID` (`sottostep_ID`),
                                                       KEY `opzioni_formula_ID` (`opzioni_formula_ID`),
                                                       KEY `categoria_ID` (`categoria_ID`),
                                                       KEY `step_ID` (`step_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

-- L’esportazione dei dati non era selezionata.

-- Dump della struttura di tabella winconf.configuratore_opzioni_check_dimensioni
CREATE TABLE IF NOT EXISTS `configuratore_opzioni_check_dimensioni` (
                                                                        `ID` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
                                                                        `categoria_ID` mediumint(8) unsigned DEFAULT NULL,
                                                                        `step_ID` mediumint(8) unsigned DEFAULT NULL,
                                                                        `sottostep_ID` mediumint(8) unsigned DEFAULT NULL,
                                                                        `opzione_ID` mediumint(8) unsigned DEFAULT NULL,
                                                                        `valore` decimal(20,6) unsigned DEFAULT NULL COMMENT 'Valore da controllare',
                                                                        `dimensione` tinyint(3) unsigned DEFAULT NULL COMMENT '0 = larghezza; 1 = lunghezza; 2 = spessore',
                                                                        `confronto` int(11) DEFAULT NULL COMMENT '0: < (minore); \r\n1: <= minore uguale; \r\n2: = (uguale);\r\n3: >= (maggiore o uguale)\r\n4: > (maggiore)\r\n5: != diverso',
                                                                        `esito` tinyint(3) unsigned DEFAULT NULL COMMENT '0 = escludi; 1 = includi',
                                                                        PRIMARY KEY (`ID`),
                                                                        KEY `categoria_ID` (`categoria_ID`),
                                                                        KEY `step_ID` (`step_ID`),
                                                                        KEY `sottostep_ID` (`sottostep_ID`),
                                                                        KEY `opzione_ID` (`opzione_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- L’esportazione dei dati non era selezionata.

-- Dump della struttura di tabella winconf.configuratore_opzioni_check_dipendenze
CREATE TABLE IF NOT EXISTS `configuratore_opzioni_check_dipendenze` (
                                                                        `ID` mediumint(9) NOT NULL AUTO_INCREMENT,
                                                                        `categoria_ID` mediumint(8) unsigned DEFAULT NULL,
                                                                        `step_ID` mediumint(8) unsigned DEFAULT NULL,
                                                                        `sottostep_ID` mediumint(9) unsigned NOT NULL DEFAULT 0,
                                                                        `opzione_valore_ID` mediumint(9) unsigned NOT NULL DEFAULT 0,
                                                                        `valore` float DEFAULT NULL,
                                                                        `confronto` tinyint(3) unsigned DEFAULT NULL COMMENT '0: < (minore); \r\n1: <= minore uguale; \r\n2: = (uguale);\r\n3: >= (maggiore o uguale)\r\n4: > (maggiore)\r\n5: != diverso',
                                                                        `esito` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 = escludi; 1 = includi;',
                                                                        PRIMARY KEY (`ID`),
                                                                        KEY `opzione_ID` (`sottostep_ID`) USING BTREE,
                                                                        KEY `categoria_ID` (`categoria_ID`),
                                                                        KEY `step_ID` (`step_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- L’esportazione dei dati non era selezionata.

-- Dump della struttura di tabella winconf.configuratore_sottostep
CREATE TABLE IF NOT EXISTS `configuratore_sottostep` (
                                                         `ID` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
                                                         `categoria_ID` mediumint(8) unsigned DEFAULT NULL,
                                                         `step_ID` mediumint(9) DEFAULT NULL,
                                                         `sottostep_nome` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '0',
                                                         `sottostep_sigla` varchar(50) CHARACTER SET utf8 DEFAULT '0',
                                                         `sottostep_descrizione` text CHARACTER SET utf8 DEFAULT NULL,
                                                         `tipo_scelta` smallint(5) unsigned DEFAULT NULL COMMENT '0 = scelta singola; 1 = scelta multipla; 2 = campo libero',
                                                         `check_dipendenze` smallint(6) DEFAULT NULL COMMENT '0 = nessun check sulla dipendenza (il sottostep verrà sempre mostrato); 1 = esegue un ckeck di esclusione se trova nella tabella configuratore_sottostep_check almeno una condizione valida',
                                                         `ordine` smallint(5) unsigned DEFAULT NULL,
                                                         `visibile` smallint(5) unsigned DEFAULT NULL COMMENT '0 = non visibile; 1 = visibile',
                                                         PRIMARY KEY (`ID`),
                                                         KEY `step_ID` (`step_ID`),
                                                         KEY `categoria_ID` (`categoria_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

-- L’esportazione dei dati non era selezionata.

-- Dump della struttura di tabella winconf.configuratore_sottostep_check
CREATE TABLE IF NOT EXISTS `configuratore_sottostep_check` (
                                                               `ID` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
                                                               `categoria_ID` mediumint(8) unsigned DEFAULT NULL,
                                                               `step_ID` mediumint(9) NOT NULL DEFAULT 0,
                                                               `sottostep_ID` mediumint(8) unsigned DEFAULT NULL,
                                                               `opzione_ID` mediumint(9) unsigned DEFAULT NULL,
                                                               `tipo_check` smallint(6) unsigned DEFAULT NULL COMMENT '0 = escludi; 1 = includi',
                                                               `confronto` smallint(6) unsigned DEFAULT NULL COMMENT '0: < (minore); \r\n1: <= minore uguale; \r\n2: = (uguale);\r\n3: >= (maggiore o uguale)\r\n4: > (maggiore)\r\n5: != diverso',
                                                               `valore` decimal(20,6) DEFAULT NULL,
                                                               PRIMARY KEY (`ID`),
                                                               KEY `step_ID` (`step_ID`),
                                                               KEY `opzione_ID` (`opzione_ID`),
                                                               KEY `sottostep_ID` (`sottostep_ID`),
                                                               KEY `categoria_ID` (`categoria_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Questa tabella regola la visualizzazione dei sottostep (configuratore_step) in base alle condizioni imposte';

-- L’esportazione dei dati non era selezionata.

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- L’esportazione dei dati non era selezionata.

-- Dump della struttura di tabella winconf.documenti
CREATE TABLE IF NOT EXISTS `documenti` (
                                           `ID` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
                                           `user_ID` mediumint(8) unsigned DEFAULT NULL,
                                           `customer_ID` mediumint(8) unsigned DEFAULT NULL,
                                           `tipo_ordine_ID` smallint(5) unsigned NOT NULL COMMENT 'ID del tipo del documento (Vedere tabella documenti_tipo). Ad esempio ID 1 = progetto, ID 2 = ordine',
                                           `categoria_ID` int(11) unsigned NOT NULL COMMENT 'ID della categoria del progetto, vedere configuratore_categorie',
                                           `configuratore_versione` varchar(8) NOT NULL DEFAULT '',
                                           `lunghezza` decimal(20,3) unsigned NOT NULL COMMENT 'espressa in mm decimali',
                                           `larghezza` decimal(20,3) unsigned NOT NULL COMMENT 'espressa in mm decimali',
                                           `spessore` decimal(20,6) NOT NULL COMMENT 'espressa in mm decimali',
                                           `metri_quadri` decimal(20,3) unsigned DEFAULT NULL COMMENT 'espressa in metri quadrati decimali',
                                           `data_ordine` date DEFAULT NULL,
                                           `totale` decimal(20,6) DEFAULT NULL,
                                           `note` text CHARACTER SET utf8 DEFAULT NULL,
                                           `stato` tinyint(3) unsigned DEFAULT 0 COMMENT '0 = aperto, 1 = chiuso',
                                           PRIMARY KEY (`ID`),
                                           KEY `user_ID` (`user_ID`),
                                           KEY `customer_ID` (`customer_ID`),
                                           KEY `tipo_ordine_ID` (`tipo_ordine_ID`),
                                           KEY `categoria_ID` (`categoria_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

-- L’esportazione dei dati non era selezionata.

-- Dump della struttura di tabella winconf.documenti_corpo
CREATE TABLE IF NOT EXISTS `documenti_corpo` (
                                                 `ID` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
                                                 `user_ID` mediumint(8) unsigned DEFAULT NULL,
                                                 `documento_ID` mediumint(8) unsigned DEFAULT NULL,
                                                 `categoria_ID` mediumint(9) unsigned DEFAULT NULL,
                                                 `step_ID` mediumint(9) unsigned DEFAULT NULL,
                                                 `sottostep_ID` mediumint(9) unsigned DEFAULT NULL,
                                                 `opzione_ID` mediumint(9) unsigned DEFAULT NULL,
                                                 `formula_ID` mediumint(9) unsigned DEFAULT NULL,
                                                 `sigla` varchar(50) DEFAULT NULL,
                                                 `formula_valore` decimal(20,6) unsigned DEFAULT NULL,
                                                 `importo` decimal(20,6) DEFAULT NULL,
                                                 `qta` smallint(6) DEFAULT NULL,
                                                 `primo_step` tinyint(3) unsigned DEFAULT 0,
                                                 `valorizzata` tinyint(3) unsigned DEFAULT 0 COMMENT 'Determina se l''opzione ha ricevuto un valore',
                                                 `esclusa` tinyint(3) unsigned DEFAULT 0 COMMENT 'Determina se l''opzione è stata esclusa da una dipendenza',
                                                 `visibile` tinyint(4) unsigned DEFAULT 0 COMMENT 'Determina se l''opzione può essere mostrata nel configuratore',
                                                 PRIMARY KEY (`ID`),
                                                 KEY `categoria_ID` (`categoria_ID`),
                                                 KEY `step_ID` (`step_ID`),
                                                 KEY `sottostep_ID` (`sottostep_ID`),
                                                 KEY `opzione_ID` (`opzione_ID`),
                                                 KEY `formula_ID` (`formula_ID`),
                                                 KEY `documento_ID` (`documento_ID`),
                                                 KEY `user_ID` (`user_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;

-- L’esportazione dei dati non era selezionata.

-- Dump della struttura di tabella winconf.documenti_tipo
CREATE TABLE IF NOT EXISTS `documenti_tipo` (
                                                `ID` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
                                                `tipo` varchar(255) NOT NULL DEFAULT 'AUTO_INCREMENT',
                                                `descrizione` text NOT NULL,
                                                `visibile` tinyint(4) unsigned NOT NULL DEFAULT 0 COMMENT '0 = non visibile; 1 = visibile',
                                                PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COMMENT='Valorizza le tipologie di documenti. Potrebbero, ad esempio, essere "ordini", "preventivi", "liste", "progetti".';

-- L’esportazione dei dati non era selezionata.

-- Dump della struttura di tabella winconf.logs
CREATE TABLE IF NOT EXISTS `logs` (
                                      `ID` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
                                      `user_ID` mediumint(8) unsigned NOT NULL DEFAULT 0,
                                      `tipo_ID` tinyint(3) unsigned NOT NULL DEFAULT 0,
                                      `data` datetime NOT NULL DEFAULT current_timestamp(),
                                      `modulo` varchar(128) NOT NULL,
                                      `operazione` varchar(256) NOT NULL DEFAULT '',
                                      `evento` varchar(255) NOT NULL,
                                      `log` text NOT NULL,
                                      PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- L’esportazione dei dati non era selezionata.

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
) ENGINE=InnoDB AUTO_INCREMENT=613 DEFAULT CHARSET=utf8;

-- L’esportazione dei dati non era selezionata.

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
                                       `enabled` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '0 = non abilitato; 1 = abilitato;',
                                       PRIMARY KEY (`ID`),
                                       KEY `ID` (`ID`),
                                       KEY `azienda_ID` (`company_ID`),
                                       KEY `group_ID` (`group_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

-- L’esportazione dei dati non era selezionata.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
