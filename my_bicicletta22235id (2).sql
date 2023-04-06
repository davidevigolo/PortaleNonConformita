-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Apr 06, 2023 alle 12:50
-- Versione del server: 8.0.30
-- Versione PHP: 8.0.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `my_bicicletta22235id`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `ACCOUNT`
--

CREATE TABLE `ACCOUNT` (
  `USERNAME` varchar(50) NOT NULL,
  `PASSWORD` varchar(64) NOT NULL,
  `IDSEGNALANTE` int DEFAULT NULL,
  `RUOLO` varchar(35) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `ACCOUNT`
--

INSERT INTO `ACCOUNT` (`USERNAME`, `PASSWORD`, `IDSEGNALANTE`, `RUOLO`) VALUES
('test', '$2y$10$/GPSx0O/oPAbZGmP8kgDLujvi3XvK6AAzTYxHhid4BW2vXAekNaSW', 1, 'Admin');

-- --------------------------------------------------------

--
-- Struttura della tabella `AZIONECORRETTIVA`
--

CREATE TABLE `AZIONECORRETTIVA` (
  `DATAINIZIO` date NOT NULL,
  `DATAFINE` date DEFAULT NULL,
  `DESCRIZIONE` varchar(255) NOT NULL,
  `IDSEGNALAZIONE` int NOT NULL,
  `NUMERO` int NOT NULL,
  `STATO` varchar(20) NOT NULL,
  `ESEGUENTE` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `CLIENTE`
--

CREATE TABLE `CLIENTE` (
  `DATAN` date NOT NULL,
  `COGNOME` varchar(50) NOT NULL,
  `NOME` varchar(50) NOT NULL,
  `CODF` varchar(16) NOT NULL,
  `IDSEGNALANTE` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `FORNITORE`
--

CREATE TABLE `FORNITORE` (
  `IDSEGNALANTE` int NOT NULL,
  `PIVA` varchar(11) NOT NULL,
  `CAP` int NOT NULL,
  `DENOMINAZIONE` varchar(100) NOT NULL,
  `VIA` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `FORNITURE`
--

CREATE TABLE `FORNITURE` (
  `SKU` int NOT NULL,
  `IDSEGNALANTE` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `GESTIONENC`
--

CREATE TABLE `GESTIONENC` (
  `IDSEGNALANTE` int NOT NULL,
  `IDSEGNALAZIONE` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `IMPIEGATO`
--

CREATE TABLE `IMPIEGATO` (
  `IDSEGNALANTE` int NOT NULL,
  `TIPO` char(1) NOT NULL,
  `COGNOME` varchar(50) NOT NULL,
  `NOME` varchar(50) NOT NULL,
  `CODF` varchar(16) NOT NULL,
  `DATAN` date NOT NULL,
  `DATASSUNZIONE` date NOT NULL,
  `DATALICENZIAMENTO` date DEFAULT NULL,
  `REPARTO` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `IMPIEGATO`
--

INSERT INTO `IMPIEGATO` (`IDSEGNALANTE`, `TIPO`, `COGNOME`, `NOME`, `CODF`, `DATAN`, `DATASSUNZIONE`, `DATALICENZIAMENTO`, `REPARTO`) VALUES
(2, 'I', 'Vigolo', 'Davide', 'VGLDVD04S07G225Z', '2023-04-06', '2023-04-05', NULL, 'Reparto 1');

-- --------------------------------------------------------

--
-- Struttura della tabella `NONCONFORMITA`
--

CREATE TABLE `NONCONFORMITA` (
  `GRADOMINIMO` int NOT NULL,
  `DESCRIZIONE` varchar(255) NOT NULL,
  `NOME` varchar(50) NOT NULL,
  `ID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `NONCONFORMITA`
--

INSERT INTO `NONCONFORMITA` (`GRADOMINIMO`, `DESCRIZIONE`, `NOME`, `ID`) VALUES
(5, 'Componente difettosa', 'Componente difettosa', 1),
(10, 'Macchinario rotto', 'Macchinario rotto', 2),
(3, 'Prodotto difettoso', 'Prodotto difettoso', 3);

-- --------------------------------------------------------

--
-- Struttura della tabella `PRODOTTO`
--

CREATE TABLE `PRODOTTO` (
  `ID` int NOT NULL,
  `LOTTO` int NOT NULL,
  `TIPO` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `REPARTO`
--

CREATE TABLE `REPARTO` (
  `NOME` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `REPARTO`
--

INSERT INTO `REPARTO` (`NOME`) VALUES
('Reparto 1'),
('Reparto 2'),
('Reparto 3');

-- --------------------------------------------------------

--
-- Struttura della tabella `RUOLO`
--

CREATE TABLE `RUOLO` (
  `NOME` varchar(35) NOT NULL,
  `GRADOGESTIONE` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `RUOLO`
--

INSERT INTO `RUOLO` (`NOME`, `GRADOGESTIONE`) VALUES
('Admin', 10),
('Caporeparto', 10),
('Dirigente', 10),
('Utente', 5);

-- --------------------------------------------------------

--
-- Struttura della tabella `SEGNALANTE`
--

CREATE TABLE `SEGNALANTE` (
  `ID` int NOT NULL,
  `EMAIL` varchar(100) NOT NULL,
  `TIPO` char(1) NOT NULL,
  `TELEFONO` varchar(13) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `SEGNALANTE`
--

INSERT INTO `SEGNALANTE` (`ID`, `EMAIL`, `TIPO`, `TELEFONO`) VALUES
(1, 'esempio@es.es', 'F', '3920720761'),
(2, 'vidavid04@gmail.com', 'I', '392-072-0761');

-- --------------------------------------------------------

--
-- Struttura della tabella `SEGNALAZIONE`
--

CREATE TABLE `SEGNALAZIONE` (
  `ID` int NOT NULL,
  `TIPO` int NOT NULL,
  `DATACREAZIONE` date NOT NULL,
  `DATACHIUSURA` date DEFAULT NULL,
  `AUTORE` int DEFAULT NULL,
  `STATO` varchar(20) NOT NULL,
  `NCREPARTO` varchar(50) DEFAULT NULL,
  `NCFORNITORE` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `SEGNALAZIONEPROD`
--

CREATE TABLE `SEGNALAZIONEPROD` (
  `IDSEGNALAZIONE` int NOT NULL,
  `IDPROD` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `TIPOPRODOTTO`
--

CREATE TABLE `TIPOPRODOTTO` (
  `SKU` int NOT NULL,
  `TIPO` varchar(50) NOT NULL,
  `DESCRIZIONE` varchar(255) NOT NULL,
  `PREZZO` decimal(15,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `ACCOUNT`
--
ALTER TABLE `ACCOUNT`
  ADD PRIMARY KEY (`USERNAME`),
  ADD KEY `RUOLO` (`RUOLO`),
  ADD KEY `IDSEGNALANTE` (`IDSEGNALANTE`);

--
-- Indici per le tabelle `AZIONECORRETTIVA`
--
ALTER TABLE `AZIONECORRETTIVA`
  ADD PRIMARY KEY (`NUMERO`,`IDSEGNALAZIONE`),
  ADD KEY `IDSEGNALAZIONE` (`IDSEGNALAZIONE`),
  ADD KEY `ESEGUENTE` (`ESEGUENTE`);

--
-- Indici per le tabelle `CLIENTE`
--
ALTER TABLE `CLIENTE`
  ADD PRIMARY KEY (`IDSEGNALANTE`);

--
-- Indici per le tabelle `FORNITORE`
--
ALTER TABLE `FORNITORE`
  ADD PRIMARY KEY (`IDSEGNALANTE`);

--
-- Indici per le tabelle `FORNITURE`
--
ALTER TABLE `FORNITURE`
  ADD PRIMARY KEY (`SKU`,`IDSEGNALANTE`),
  ADD KEY `FORNITURE_ibfk_1` (`IDSEGNALANTE`);

--
-- Indici per le tabelle `GESTIONENC`
--
ALTER TABLE `GESTIONENC`
  ADD PRIMARY KEY (`IDSEGNALANTE`,`IDSEGNALAZIONE`),
  ADD KEY `IDSEGNALAZIONE` (`IDSEGNALAZIONE`);

--
-- Indici per le tabelle `IMPIEGATO`
--
ALTER TABLE `IMPIEGATO`
  ADD PRIMARY KEY (`IDSEGNALANTE`),
  ADD KEY `IMPIEGATO_ibfk_3` (`REPARTO`);

--
-- Indici per le tabelle `NONCONFORMITA`
--
ALTER TABLE `NONCONFORMITA`
  ADD PRIMARY KEY (`ID`);

--
-- Indici per le tabelle `PRODOTTO`
--
ALTER TABLE `PRODOTTO`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `TIPO` (`TIPO`);

--
-- Indici per le tabelle `REPARTO`
--
ALTER TABLE `REPARTO`
  ADD PRIMARY KEY (`NOME`);

--
-- Indici per le tabelle `RUOLO`
--
ALTER TABLE `RUOLO`
  ADD PRIMARY KEY (`NOME`);

--
-- Indici per le tabelle `SEGNALANTE`
--
ALTER TABLE `SEGNALANTE`
  ADD PRIMARY KEY (`ID`);

--
-- Indici per le tabelle `SEGNALAZIONE`
--
ALTER TABLE `SEGNALAZIONE`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `TIPO` (`TIPO`),
  ADD KEY `SEGNALAZIONE_ibfk_1` (`NCREPARTO`),
  ADD KEY `SEGNALAZIONE_ibfk_2` (`NCFORNITORE`),
  ADD KEY `SEGNALAZIONE_ibfk_3` (`AUTORE`);

--
-- Indici per le tabelle `SEGNALAZIONEPROD`
--
ALTER TABLE `SEGNALAZIONEPROD`
  ADD PRIMARY KEY (`IDSEGNALAZIONE`,`IDPROD`),
  ADD KEY `IDPROD` (`IDPROD`);

--
-- Indici per le tabelle `TIPOPRODOTTO`
--
ALTER TABLE `TIPOPRODOTTO`
  ADD PRIMARY KEY (`SKU`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `NONCONFORMITA`
--
ALTER TABLE `NONCONFORMITA`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `PRODOTTO`
--
ALTER TABLE `PRODOTTO`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `SEGNALANTE`
--
ALTER TABLE `SEGNALANTE`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT per la tabella `SEGNALAZIONE`
--
ALTER TABLE `SEGNALAZIONE`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `TIPOPRODOTTO`
--
ALTER TABLE `TIPOPRODOTTO`
  MODIFY `SKU` int NOT NULL AUTO_INCREMENT;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `ACCOUNT`
--
ALTER TABLE `ACCOUNT`
  ADD CONSTRAINT `ACCOUNT_ibfk_1` FOREIGN KEY (`RUOLO`) REFERENCES `RUOLO` (`NOME`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ACCOUNT_ibfk_2` FOREIGN KEY (`IDSEGNALANTE`) REFERENCES `SEGNALANTE` (`ID`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `AZIONECORRETTIVA`
--
ALTER TABLE `AZIONECORRETTIVA`
  ADD CONSTRAINT `AZIONECORRETTIVA_ibfk_1` FOREIGN KEY (`IDSEGNALAZIONE`) REFERENCES `SEGNALAZIONE` (`ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `AZIONECORRETTIVA_ibfk_2` FOREIGN KEY (`ESEGUENTE`) REFERENCES `IMPIEGATO` (`IDSEGNALANTE`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `CLIENTE`
--
ALTER TABLE `CLIENTE`
  ADD CONSTRAINT `CLIENTE_ibfk_1` FOREIGN KEY (`IDSEGNALANTE`) REFERENCES `SEGNALANTE` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `FORNITORE`
--
ALTER TABLE `FORNITORE`
  ADD CONSTRAINT `FORNITORE_ibfk_1` FOREIGN KEY (`IDSEGNALANTE`) REFERENCES `SEGNALANTE` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `FORNITURE`
--
ALTER TABLE `FORNITURE`
  ADD CONSTRAINT `FORNITURE_ibfk_1` FOREIGN KEY (`IDSEGNALANTE`) REFERENCES `FORNITORE` (`IDSEGNALANTE`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FORNITURE_ibfk_2` FOREIGN KEY (`SKU`) REFERENCES `TIPOPRODOTTO` (`SKU`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `GESTIONENC`
--
ALTER TABLE `GESTIONENC`
  ADD CONSTRAINT `GESTIONENC_ibfk_1` FOREIGN KEY (`IDSEGNALANTE`) REFERENCES `SEGNALANTE` (`ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `GESTIONENC_ibfk_2` FOREIGN KEY (`IDSEGNALAZIONE`) REFERENCES `SEGNALAZIONE` (`ID`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `IMPIEGATO`
--
ALTER TABLE `IMPIEGATO`
  ADD CONSTRAINT `IMPIEGATO_ibfk_2` FOREIGN KEY (`IDSEGNALANTE`) REFERENCES `SEGNALANTE` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `IMPIEGATO_ibfk_3` FOREIGN KEY (`REPARTO`) REFERENCES `REPARTO` (`NOME`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Limiti per la tabella `PRODOTTO`
--
ALTER TABLE `PRODOTTO`
  ADD CONSTRAINT `PRODOTTO_ibfk_1` FOREIGN KEY (`TIPO`) REFERENCES `TIPOPRODOTTO` (`SKU`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `SEGNALAZIONE`
--
ALTER TABLE `SEGNALAZIONE`
  ADD CONSTRAINT `SEGNALAZIONE_ibfk_1` FOREIGN KEY (`NCREPARTO`) REFERENCES `REPARTO` (`NOME`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `SEGNALAZIONE_ibfk_2` FOREIGN KEY (`NCFORNITORE`) REFERENCES `FORNITORE` (`IDSEGNALANTE`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `SEGNALAZIONE_ibfk_3` FOREIGN KEY (`AUTORE`) REFERENCES `SEGNALANTE` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `SEGNALAZIONE_ibfk_4` FOREIGN KEY (`TIPO`) REFERENCES `NONCONFORMITA` (`ID`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `SEGNALAZIONEPROD`
--
ALTER TABLE `SEGNALAZIONEPROD`
  ADD CONSTRAINT `SEGNALAZIONEPROD_ibfk_1` FOREIGN KEY (`IDSEGNALAZIONE`) REFERENCES `SEGNALAZIONE` (`ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `SEGNALAZIONEPROD_ibfk_2` FOREIGN KEY (`IDPROD`) REFERENCES `PRODOTTO` (`ID`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
