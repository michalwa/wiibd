-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 11 Lis 2020, 17:41
-- Wersja serwera: 10.4.14-MariaDB
-- Wersja PHP: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `biblioteka`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `autorzy`
--

CREATE TABLE `autorzy` (
  `id` int(11) NOT NULL,
  `imie` varchar(100) NOT NULL,
  `nazwisko` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `autorzy`
--

INSERT INTO `autorzy` (`id`, `imie`, `nazwisko`) VALUES
(1, 'Przemysław', 'Dymek'),
(2, 'Zygmunt', 'Kozak'),
(3, 'Zbigniew', 'Moszumański'),
(4, 'Jacek', 'Szczepański'),
(5, 'Bartłomiej Bernard', 'Kwiatkowski'),
(6, 'Karolina', 'Jaklewicz'),
(7, 'Wallace', 'Carey'),
(8, 'Erin', 'Hunter'),
(9, 'Kathryn', 'Lasky'),
(10, 'Krzysztof', 'Wons'),
(11, 'Arkadiusz', 'Łodziewski'),
(12, 'Jolanta', 'Grabowska-Markowska'),
(14, 'Rafał', 'Pacześ');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `bibliotekarze`
--

CREATE TABLE `bibliotekarze` (
  `id` int(11) NOT NULL,
  `login` varchar(100) NOT NULL,
  `haslo` char(128) NOT NULL,
  `imie` varchar(100) NOT NULL,
  `nazwisko` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `bibliotekarze`
--

INSERT INTO `bibliotekarze` (`id`, `login`, `haslo`, `imie`, `nazwisko`) VALUES
(1, 'admin', 'd404559f602eab6fd602ac7680dacbfaadd13630335e951f097af3900e9de176b6db28512f2e000b9d04fba5133e8b1c6e8df59db3a8ab9d60be4b97cc9e81db', 'Jan', 'Administrator');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `czytelnicy`
--

CREATE TABLE `czytelnicy` (
  `id` int(11) NOT NULL,
  `imie` varchar(100) NOT NULL,
  `nazwisko` varchar(100) NOT NULL,
  `klasa` varchar(4) NOT NULL,
  `aktywny` tinyint(1) NOT NULL DEFAULT 1,
  `login` varchar(100) NOT NULL,
  `haslo` char(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `czytelnicy`
--

INSERT INTO `czytelnicy` (`id`, `imie`, `nazwisko`, `klasa`, `aktywny`, `login`, `haslo`) VALUES
(1, 'Jan', 'Kowalski', '1A', 1, 'jankow123', '65b58a8040c6978d82d5100be261fdb5bca5d804d96d9a842ade4cee99378cbd963941c485d8c9297621ffee316c89537c9b73a0ed31c0fe0d9aeb849a5bc2ac'),
(2, 'Anna', 'Nowak', '1A', 1, 'annnow645', 'd404559f602eab6fd602ac7680dacbfaadd13630335e951f097af3900e9de176b6db28512f2e000b9d04fba5133e8b1c6e8df59db3a8ab9d60be4b97cc9e81db'),
(3, 'Zbigniew', 'Jeleń', '2C', 1, 'zbijel827', 'd404559f602eab6fd602ac7680dacbfaadd13630335e951f097af3900e9de176b6db28512f2e000b9d04fba5133e8b1c6e8df59db3a8ab9d60be4b97cc9e81db'),
(4, 'Alojzy', 'Gruby', '2A', 0, 'alogru747', 'd404559f602eab6fd602ac7680dacbfaadd13630335e951f097af3900e9de176b6db28512f2e000b9d04fba5133e8b1c6e8df59db3a8ab9d60be4b97cc9e81db'),
(5, 'Sebastian', 'Żurek', '2B', 1, 'sebzur289', 'd404559f602eab6fd602ac7680dacbfaadd13630335e951f097af3900e9de176b6db28512f2e000b9d04fba5133e8b1c6e8df59db3a8ab9d60be4b97cc9e81db'),
(6, 'Stanisław', 'Zębak', '1B', 0, 'stazeb277', 'd404559f602eab6fd602ac7680dacbfaadd13630335e951f097af3900e9de176b6db28512f2e000b9d04fba5133e8b1c6e8df59db3a8ab9d60be4b97cc9e81db');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `egzemplarze`
--

CREATE TABLE `egzemplarze` (
  `id` int(11) NOT NULL,
  `identyfikator` varchar(100) NOT NULL,
  `ksiazka` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `egzemplarze`
--

INSERT INTO `egzemplarze` (`id`, `identyfikator`, `ksiazka`) VALUES
(1, 'WULXHA', 1),
(2, 'T14NVP', 2),
(3, 'RXDYJD', 3),
(4, '4C6Z2N', 4),
(5, 'EB0AMN', 5),
(6, '7X2JJ5', 6),
(7, 'Q5VVDE', 7),
(8, 'OB28YY', 8),
(9, 'RC3U1L', 9),
(10, 'UC5FL3', 45),
(11, '0BKKK0', 5),
(12, '75DRDU', 8),
(13, 'QP7R23', 9),
(14, 'BKHYPE', 3);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `gatunki`
--

CREATE TABLE `gatunki` (
  `id` int(11) NOT NULL,
  `etykieta` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `gatunki`
--

INSERT INTO `gatunki` (`id`, `etykieta`) VALUES
(2, 'historyczne'),
(3, 'kryminalne'),
(4, 'religijne'),
(5, 'poradnik'),
(6, 'fantastyka'),
(7, 'baśnie'),
(33, 'biografia'),
(35, 'satyra');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `ksiazki`
--

CREATE TABLE `ksiazki` (
  `id` int(11) NOT NULL,
  `tytul` varchar(200) NOT NULL,
  `wydawnictwo` int(11) NOT NULL,
  `rokWydania` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `ksiazki`
--

INSERT INTO `ksiazki` (`id`, `tytul`, `wydawnictwo`, `rokWydania`) VALUES
(1, '6 pułk ułanów kaniowskich', 1, 2020),
(2, 'Pułkownik Julian Sielewicz (1892-1940)', 1, 2019),
(3, 'Sophie Lahmer i germańskie przekleństwo', 4, 2020),
(4, 'Jaśmina Berezy', 5, 2020),
(5, 'Opowieści o świętych. Inspirujące przygody pełne łaski i odwagi', 6, 2020),
(6, 'Strażnicy GaHoole Tom 5. Rozbicie', 6, 2020),
(7, 'Przyjaciele Jezusa. Maria, Marta i Łazarz', 7, 2019),
(8, 'Boży poradnik relacji z aniołami', 7, 2019),
(9, 'Camino del Norte. Poradnik pielgrzyma', 7, 2019),
(45, 'Grube wióry', 8, 2020);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `ksiazki_autorzy`
--

CREATE TABLE `ksiazki_autorzy` (
  `ksiazka` int(11) NOT NULL,
  `autor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `ksiazki_autorzy`
--

INSERT INTO `ksiazki_autorzy` (`ksiazka`, `autor`) VALUES
(1, 1),
(2, 2),
(2, 3),
(2, 4),
(3, 5),
(4, 6),
(5, 7),
(6, 9),
(7, 10),
(8, 11),
(9, 12),
(45, 14);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `ksiazki_gatunki`
--

CREATE TABLE `ksiazki_gatunki` (
  `ksiazka` int(11) NOT NULL,
  `gatunek` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `ksiazki_gatunki`
--

INSERT INTO `ksiazki_gatunki` (`ksiazka`, `gatunek`) VALUES
(1, 2),
(2, 2),
(4, 3),
(8, 4),
(9, 4),
(7, 4),
(9, 5),
(6, 6),
(6, 7),
(5, 7),
(2, 33),
(3, 2),
(45, 35);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `wydawnictwa`
--

CREATE TABLE `wydawnictwa` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `wydawnictwa`
--

INSERT INTO `wydawnictwa` (`id`, `nazwa`) VALUES
(1, 'Ajaks'),
(4, 'Edytor'),
(5, 'Nisza'),
(6, 'Nowa Baśń'),
(7, 'Salwator'),
(8, 'Agora');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `wypozyczenia`
--

CREATE TABLE `wypozyczenia` (
  `id` int(11) NOT NULL,
  `egzemplarz` int(11) NOT NULL,
  `czytelnik` int(11) NOT NULL,
  `dataRozpoczecia` date NOT NULL,
  `dataZakonczenia` date NOT NULL,
  `aktywne` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `wypozyczenia`
--

INSERT INTO `wypozyczenia` (`id`, `egzemplarz`, `czytelnik`, `dataRozpoczecia`, `dataZakonczenia`, `aktywne`) VALUES
(1, 2, 1, '2020-11-03', '2020-12-03', 1),
(3, 7, 3, '2020-11-02', '2020-12-02', 1),
(4, 8, 1, '2020-10-22', '2020-11-22', 1),
(5, 12, 5, '2020-09-10', '2020-10-10', 0),
(6, 3, 5, '2020-10-23', '2020-11-23', 1);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `autorzy`
--
ALTER TABLE `autorzy`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `bibliotekarze`
--
ALTER TABLE `bibliotekarze`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `czytelnicy`
--
ALTER TABLE `czytelnicy`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `egzemplarze`
--
ALTER TABLE `egzemplarze`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ksiazka` (`ksiazka`);

--
-- Indeksy dla tabeli `gatunki`
--
ALTER TABLE `gatunki`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `ksiazki`
--
ALTER TABLE `ksiazki`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wydawnictwo` (`wydawnictwo`);

--
-- Indeksy dla tabeli `ksiazki_autorzy`
--
ALTER TABLE `ksiazki_autorzy`
  ADD KEY `autor` (`autor`),
  ADD KEY `ksiazka` (`ksiazka`);

--
-- Indeksy dla tabeli `ksiazki_gatunki`
--
ALTER TABLE `ksiazki_gatunki`
  ADD KEY `gatunek` (`gatunek`),
  ADD KEY `ksiazka` (`ksiazka`);

--
-- Indeksy dla tabeli `wydawnictwa`
--
ALTER TABLE `wydawnictwa`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `wypozyczenia`
--
ALTER TABLE `wypozyczenia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `czytelnik` (`czytelnik`),
  ADD KEY `egzemplarz` (`egzemplarz`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `autorzy`
--
ALTER TABLE `autorzy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT dla tabeli `bibliotekarze`
--
ALTER TABLE `bibliotekarze`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT dla tabeli `czytelnicy`
--
ALTER TABLE `czytelnicy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT dla tabeli `egzemplarze`
--
ALTER TABLE `egzemplarze`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT dla tabeli `gatunki`
--
ALTER TABLE `gatunki`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT dla tabeli `ksiazki`
--
ALTER TABLE `ksiazki`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT dla tabeli `wydawnictwa`
--
ALTER TABLE `wydawnictwa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT dla tabeli `wypozyczenia`
--
ALTER TABLE `wypozyczenia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `egzemplarze`
--
ALTER TABLE `egzemplarze`
  ADD CONSTRAINT `egzemplarze_ibfk_1` FOREIGN KEY (`ksiazka`) REFERENCES `ksiazki` (`id`);

--
-- Ograniczenia dla tabeli `ksiazki`
--
ALTER TABLE `ksiazki`
  ADD CONSTRAINT `ksiazki_ibfk_2` FOREIGN KEY (`wydawnictwo`) REFERENCES `wydawnictwa` (`id`);

--
-- Ograniczenia dla tabeli `ksiazki_autorzy`
--
ALTER TABLE `ksiazki_autorzy`
  ADD CONSTRAINT `ksiazki_autorzy_ibfk_1` FOREIGN KEY (`autor`) REFERENCES `autorzy` (`id`),
  ADD CONSTRAINT `ksiazki_autorzy_ibfk_2` FOREIGN KEY (`ksiazka`) REFERENCES `ksiazki` (`id`);

--
-- Ograniczenia dla tabeli `ksiazki_gatunki`
--
ALTER TABLE `ksiazki_gatunki`
  ADD CONSTRAINT `ksiazki_gatunki_ibfk_1` FOREIGN KEY (`gatunek`) REFERENCES `gatunki` (`id`),
  ADD CONSTRAINT `ksiazki_gatunki_ibfk_2` FOREIGN KEY (`ksiazka`) REFERENCES `ksiazki` (`id`);

--
-- Ograniczenia dla tabeli `wypozyczenia`
--
ALTER TABLE `wypozyczenia`
  ADD CONSTRAINT `wypozyczenia_ibfk_1` FOREIGN KEY (`czytelnik`) REFERENCES `czytelnicy` (`id`),
  ADD CONSTRAINT `wypozyczenia_ibfk_2` FOREIGN KEY (`egzemplarz`) REFERENCES `egzemplarze` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
