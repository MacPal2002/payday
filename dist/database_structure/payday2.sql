-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 06 Lis 2023, 18:35
-- Wersja serwera: 10.4.27-MariaDB
-- Wersja PHP: 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `payday2`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `balances`
--

CREATE TABLE `balances` (
  `id` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `balance` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Zrzut danych tabeli `balances`
--

INSERT INTO `balances` (`id`, `idUser`, `balance`) VALUES
(6, 38, '-19.00'),
(7, 68, '-12.00');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `bills`
--

CREATE TABLE `bills` (
  `id` int(11) NOT NULL,
  `idSubscription` int(11) NOT NULL,
  `paymentDate` date NOT NULL,
  `billingDate` date NOT NULL,
  `nextBillingDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Zrzut danych tabeli `bills`
--

INSERT INTO `bills` (`id`, `idSubscription`, `paymentDate`, `billingDate`, `nextBillingDate`) VALUES
(18, 3, '2023-11-06', '2023-09-05', '2023-10-05'),
(19, 4, '2023-11-06', '2023-10-03', '2023-11-03'),
(21, 4, '2023-11-06', '2023-11-03', '2023-12-03');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `vodType` varchar(15) NOT NULL,
  `subscribeDate` date NOT NULL,
  `lastCancelDate` date DEFAULT NULL,
  `status` enum('active','inactive','suspend','') NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Zrzut danych tabeli `subscriptions`
--

INSERT INTO `subscriptions` (`id`, `idUser`, `vodType`, `subscribeDate`, `lastCancelDate`, `status`, `price`) VALUES
(3, 38, 'netflix', '2023-09-05', '2023-11-06', 'suspend', '23.00'),
(4, 68, 'prime', '2023-10-03', NULL, 'active', '4.00');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `top_ups`
--

CREATE TABLE `top_ups` (
  `id` int(11) NOT NULL,
  `idBalance` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `topUpDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(15) NOT NULL,
  `password` text NOT NULL,
  `userRole` enum('admin','user','','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `userRole`) VALUES
(38, 'mackal', '$2y$10$4NBohG3rciV9lMYs4NZzReFS32OOsTiMjmvNDaBgrdpFi02.yZt1u', 'admin'),
(40, 'macka1l', '$2y$10$kHQtcxS0nZLrsyhFOmnhVeqfZX2w6ZNDt18v9ZH76K0x1NRpAwch6', 'admin'),
(68, 'jan', '$2y$10$rxCMMYxLye4nSBWGiBtkje93dljgdfeF1FYP.m58vdPwv8n2KJIqe', 'user');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `vod_types`
--

CREATE TABLE `vod_types` (
  `vodType` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Zrzut danych tabeli `vod_types`
--

INSERT INTO `vod_types` (`vodType`) VALUES
('disney'),
('hbo'),
('netflix'),
('prime');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `balances`
--
ALTER TABLE `balances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_idUser_balances` (`idUser`);

--
-- Indeksy dla tabeli `bills`
--
ALTER TABLE `bills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_idSubscription_bills` (`idSubscription`);

--
-- Indeksy dla tabeli `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_vod_type` (`vodType`),
  ADD KEY `fk_idUser_subscription` (`idUser`);

--
-- Indeksy dla tabeli `top_ups`
--
ALTER TABLE `top_ups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `top_ups_idUser` (`idBalance`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uc_username` (`username`);

--
-- Indeksy dla tabeli `vod_types`
--
ALTER TABLE `vod_types`
  ADD PRIMARY KEY (`vodType`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `balances`
--
ALTER TABLE `balances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT dla tabeli `bills`
--
ALTER TABLE `bills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT dla tabeli `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT dla tabeli `top_ups`
--
ALTER TABLE `top_ups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `balances`
--
ALTER TABLE `balances`
  ADD CONSTRAINT `fk_idUser_balances` FOREIGN KEY (`idUser`) REFERENCES `users` (`id`);

--
-- Ograniczenia dla tabeli `bills`
--
ALTER TABLE `bills`
  ADD CONSTRAINT `fk_idSubscription_bills` FOREIGN KEY (`idSubscription`) REFERENCES `subscriptions` (`id`);

--
-- Ograniczenia dla tabeli `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `fk_idUser_subscription` FOREIGN KEY (`idUser`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_vodType_subscription` FOREIGN KEY (`vodType`) REFERENCES `vod_types` (`vodType`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
