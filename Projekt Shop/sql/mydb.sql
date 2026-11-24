-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Erstellungszeit: 15. Mrz 2026 um 23:17
-- Server-Version: 10.4.32-MariaDB
-- PHP-Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `mydb`
--

-- --------------------------------------------------------
CREATE DATABASE mydb;
use mydb;
--
-- Tabellenstruktur für Tabelle `artikel`
--

CREATE TABLE `artikel` (
  `idArtikel` int(11) NOT NULL,
  `Artikel_name` varchar(255) NOT NULL,
  `Artikel_beschreibung` longtext NOT NULL,
  `kategorie_idkategorie` int(11) NOT NULL,
  `Artikel_bildpfad` varchar(255) DEFAULT NULL,
  `Artikel_preis` double NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Daten für Tabelle `artikel`
--

INSERT INTO `artikel` (`idArtikel`, `Artikel_name`, `Artikel_beschreibung`, `kategorie_idkategorie`, `Artikel_bildpfad`, `Artikel_preis`) VALUES
(1, 'Apfel', 'Knackiger roter Apfel', 1, 'bilder/apfel.jpg', 2.99),
(2, 'Bananen', 'Suesse gelbe Bananen', 1, 'bilder/banane.webp', 1.49),
(3, 'Orangen', 'Saftige Orangen mit viel Vitamin C', 1, 'bilder/orange.jpg', 3.2),
(4, 'Trauben', 'Kernlose helle Weintrauben', 1, 'bilder/trauben.webp', 2.5),
(5, 'Wassermelonen', 'Grosse, erfrischende Wassermelone', 1, 'bilder/wassermelone.webp', 4.95),
(6, 'Erdbeeren', 'Grosse, erfrischende Erdbeeren', 1, 'bilder/erdbeeren.jpg', 1.99);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bestellung`
--

CREATE TABLE `bestellung` (
  `idbestellung` int(11) NOT NULL,
  `Artikel_idArtikel` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `menge` int(11) DEFAULT 1,
  `kategorieid` int(11) NOT NULL,
  `datum` date NOT NULL,
  `name` varchar(50) NOT NULL,
  `preis` double NOT NULL,
  `bildpfad` varchar(200) NOT NULL,
  `lieferrant_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Daten für Tabelle `bestellung`
--

INSERT INTO `bestellung` (`idbestellung`, `Artikel_idArtikel`, `user_id`, `menge`, `kategorieid`, `datum`, `name`, `preis`, `bildpfad`, `lieferrant_id`) VALUES
(37, 3, 1, 1, 1, '2026-03-15', 'Orangen', 3.2, 'bilder/orange.jpg', NULL),
(38, 5, 1, 1, 1, '2026-03-15', 'Wassermelonen', 4.95, 'bilder/wassermelone.webp', NULL),
(39, 2, 1, 2, 1, '2026-03-15', 'Bananen', 1.49, 'bilder/banane.webp', NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kategorie`
--

CREATE TABLE `kategorie` (
  `idkategorie` int(11) NOT NULL,
  `kategorie_name` varchar(255) NOT NULL,
  `kategorie_beschreibung` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Daten für Tabelle `kategorie`
--

INSERT INTO `kategorie` (`idkategorie`, `kategorie_name`, `kategorie_beschreibung`) VALUES
(1, 'Obst', 'Frische Fruechte aus aller Welt'),
(3, 'Obst', 'Frische Fruechte aus aller Welt');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lieferrant`
--

CREATE TABLE `lieferrant` (
  `idLieferrant` int(11) NOT NULL,
  `Lieferrant_name` varchar(255) NOT NULL,
  `Lieferrant_Telefonnummer` varchar(255) NOT NULL,
  `Lieferrant_email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Daten für Tabelle `lieferrant`
--

INSERT INTO `lieferrant` (`idLieferrant`, `Lieferrant_name`, `Lieferrant_Telefonnummer`, `Lieferrant_email`) VALUES
(1, 'DHL', '01234567', 'test@gmail.com');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE `user` (
  `iduser` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `Telefonnummer` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `passwort` varchar(255) NOT NULL,
  `PLZ` int(11) NOT NULL,
  `Ort` varchar(255) NOT NULL,
  `Strasse` varchar(255) NOT NULL,
  `Hausnummer` varchar(45) NOT NULL,
  `rolle` enum('user','admin') NOT NULL DEFAULT 'user',
  `status` enum('abgemeldet','angemeldet') NOT NULL DEFAULT 'abgemeldet'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`iduser`, `name`, `Telefonnummer`, `email`, `passwort`, `PLZ`, `Ort`, `Strasse`, `Hausnummer`, `rolle`, `status`) VALUES
(1, 'user', '01776409602', 'test@gmail.com', '$2y$10$EB.MrJle1BXGdyYXjZMjkuRDPVcgE3cBOkKvhFTdBSA8vnywRUc4S', 45479, 'Muelheim', 'Prinzess-Luise strasse', '113', 'user', 'angemeldet'),
(2, 'admin2', '1', 'admin@gmail.com', '$2y$10$Y9wuIuD4wWbWem/PErehbO96EBIYN6SwOnUgfi7AjNOUQddBG85b6', 1, 'Essen', 'Prinzeß-Luise straße', '113', 'admin', 'abgemeldet');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `artikel`
--
ALTER TABLE `artikel`
  ADD PRIMARY KEY (`idArtikel`,`kategorie_idkategorie`),
  ADD KEY `fk_Artikel_kategorie_idx` (`kategorie_idkategorie`);

--
-- Indizes für die Tabelle `bestellung`
--
ALTER TABLE `bestellung`
  ADD PRIMARY KEY (`idbestellung`),
  ADD KEY `fk_bestellung_Artikel1_idx` (`Artikel_idArtikel`,`kategorieid`),
  ADD KEY `fk_user_bestellung` (`user_id`),
  ADD KEY `fk_lieferrant` (`lieferrant_id`);

--
-- Indizes für die Tabelle `kategorie`
--
ALTER TABLE `kategorie`
  ADD PRIMARY KEY (`idkategorie`);

--
-- Indizes für die Tabelle `lieferrant`
--
ALTER TABLE `lieferrant`
  ADD PRIMARY KEY (`idLieferrant`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`iduser`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `artikel`
--
ALTER TABLE `artikel`
  MODIFY `idArtikel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT für Tabelle `bestellung`
--
ALTER TABLE `bestellung`
  MODIFY `idbestellung` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT für Tabelle `kategorie`
--
ALTER TABLE `kategorie`
  MODIFY `idkategorie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `lieferrant`
--
ALTER TABLE `lieferrant`
  MODIFY `idLieferrant` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `iduser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `artikel`
--
ALTER TABLE `artikel`
  ADD CONSTRAINT `fk_Artikel_kategorie` FOREIGN KEY (`kategorie_idkategorie`) REFERENCES `kategorie` (`idkategorie`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints der Tabelle `bestellung`
--
ALTER TABLE `bestellung`
  ADD CONSTRAINT `fk_bestellung_Artikel1` FOREIGN KEY (`Artikel_idArtikel`,`kategorieid`) REFERENCES `artikel` (`idArtikel`, `kategorie_idkategorie`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_lieferrant` FOREIGN KEY (`lieferrant_id`) REFERENCES `lieferrant` (`idLieferrant`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_user_bestellung` FOREIGN KEY (`user_id`) REFERENCES `user` (`iduser`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
