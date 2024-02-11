-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Creato il: Gen 31, 2024 alle 22:36
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `edubay`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `amministratore`
--

CREATE TABLE `amministratore` (
  `ID` int(11) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `dettaglio_ordine`
--

CREATE TABLE `dettaglio_ordine` (
  `Cod_Ordine` int(11) NOT NULL,
  `Num_Linea` int(11) NOT NULL,
  `Cod_Reso` int(11) DEFAULT NULL,
  `ID_Inserzione` int(11) DEFAULT NULL,
  `Recensione` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `indirizzo`
--

CREATE TABLE `indirizzo` (
  `ID` int(11) NOT NULL,
  `NumCivico` smallint(6) NOT NULL,
  `Via` varchar(255) NOT NULL,
  `CAP` smallint(6) NOT NULL,
  `Attivo` tinyint(1) NOT NULL,
  `ID_Utente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `inserzione`
--

CREATE TABLE `inserzione` (
  `ID` int(11) NOT NULL,
  `Descrizione` varchar(255) NOT NULL,
  `TotCosto` float(5,2) NOT NULL,
  `IDUtente` int(11) NOT NULL,
  `Attivo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `modera`
--

CREATE TABLE `modera` (
  `data` date NOT NULL,
  `descrizione` varchar(255) NOT NULL,
  `ID_Amministratore` int(11) NOT NULL,
  `ID_Utente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `oggetto`
--

CREATE TABLE `oggetto` (
  `ID` int(11) NOT NULL,
  `Prezzo_unitario` float(5,2) NOT NULL,
  `Nome` varchar(255) NOT NULL,
  `Usura` char(1) NOT NULL,
  `IDInserzione` int(11) NOT NULL,
  `Categoria` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `ordine`
--

CREATE TABLE `ordine` (
  `Cod_Ordine` int(11) NOT NULL,
  `IDUtente` int(11) NOT NULL,
  `data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `reso`
--

CREATE TABLE `reso` (
  `CodReso` int(11) NOT NULL,
  `Descrizione` varchar(255) NOT NULL,
  `Data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `utente`
--

CREATE TABLE `utente` (
  `ID` int(11) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `bloccato` tinyint(1) NOT NULL,
  `AvgStella` float DEFAULT NULL,
  `Saldo` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `amministratore`
--
ALTER TABLE `amministratore`
  ADD PRIMARY KEY (`ID`);

--
-- Indici per le tabelle `dettaglio_ordine`
--
ALTER TABLE `dettaglio_ordine`
  ADD PRIMARY KEY (`Num_Linea`) USING BTREE,
  ADD KEY `Cod_Ordine` (`Cod_Ordine`),
  ADD KEY `FKcomposto_ID` (`ID_Inserzione`) USING BTREE,
  ADD KEY `FKrimando_ID` (`Cod_Reso`) USING BTREE;

--
-- Indici per le tabelle `indirizzo`
--
ALTER TABLE `indirizzo`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `FKregistra_FK` (`ID_Utente`);

--
-- Indici per le tabelle `inserzione`
--
ALTER TABLE `inserzione`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `FKcreazione_FK` (`IDUtente`);

--
-- Indici per le tabelle `modera`
--
ALTER TABLE `modera`
  ADD PRIMARY KEY (`ID_Amministratore`,`ID_Utente`),
  ADD KEY `ID_Utente` (`ID_Utente`);

--
-- Indici per le tabelle `oggetto`
--
ALTER TABLE `oggetto`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `FKriferimento_FK` (`IDInserzione`);

--
-- Indici per le tabelle `ordine`
--
ALTER TABLE `ordine`
  ADD PRIMARY KEY (`Cod_Ordine`),
  ADD KEY `FKeffettua_FK` (`IDUtente`);

--
-- Indici per le tabelle `reso`
--
ALTER TABLE `reso`
  ADD PRIMARY KEY (`CodReso`);

--
-- Indici per le tabelle `utente`
--
ALTER TABLE `utente`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `amministratore`
--
ALTER TABLE `amministratore`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `dettaglio_ordine`
--
ALTER TABLE `dettaglio_ordine`
  MODIFY `Num_Linea` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT per la tabella `indirizzo`
--
ALTER TABLE `indirizzo`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT per la tabella `inserzione`
--
ALTER TABLE `inserzione`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT per la tabella `oggetto`
--
ALTER TABLE `oggetto`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT per la tabella `ordine`
--
ALTER TABLE `ordine`
  MODIFY `Cod_Ordine` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT per la tabella `reso`
--
ALTER TABLE `reso`
  MODIFY `CodReso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `utente`
--
ALTER TABLE `utente`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `dettaglio_ordine`
--
ALTER TABLE `dettaglio_ordine`
  ADD CONSTRAINT `FKcomposto_FK` FOREIGN KEY (`ID_Inserzione`) REFERENCES `inserzione` (`ID`),
  ADD CONSTRAINT `FKrimando_FK` FOREIGN KEY (`Cod_Reso`) REFERENCES `reso` (`CodReso`),
  ADD CONSTRAINT `dettaglio_ordine_ibfk_1` FOREIGN KEY (`Cod_Ordine`) REFERENCES `ordine` (`Cod_Ordine`);

--
-- Limiti per la tabella `indirizzo`
--
ALTER TABLE `indirizzo`
  ADD CONSTRAINT `FKregistra_FK` FOREIGN KEY (`ID_Utente`) REFERENCES `utente` (`ID`);

--
-- Limiti per la tabella `inserzione`
--
ALTER TABLE `inserzione`
  ADD CONSTRAINT `FKcreazione_FK` FOREIGN KEY (`IDUtente`) REFERENCES `utente` (`ID`);

--
-- Limiti per la tabella `modera`
--
ALTER TABLE `modera`
  ADD CONSTRAINT `modera_ibfk_1` FOREIGN KEY (`ID_Amministratore`) REFERENCES `amministratore` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `modera_ibfk_2` FOREIGN KEY (`ID_Utente`) REFERENCES `utente` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Limiti per la tabella `oggetto`
--
ALTER TABLE `oggetto`
  ADD CONSTRAINT `FKriferimento_FK` FOREIGN KEY (`IDInserzione`) REFERENCES `inserzione` (`ID`);

--
-- Limiti per la tabella `ordine`
--
ALTER TABLE `ordine`
  ADD CONSTRAINT `FKeffettua_FK` FOREIGN KEY (`IDUtente`) REFERENCES `utente` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
