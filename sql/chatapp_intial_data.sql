INSERT INTO `users` (`id`, `usName`, `usSurname`, `username`, `email`, `age`, `telephone`, `passwd`, `pfp`, `isActive`, `usRole`) VALUES
(1, 'root', 'root', 'root', 'root', 99, '999999999', 'root', './assets/files/img/default/pfp_default.jpg', 1, 'admin'),
(2, 'Alfredo', 'Puerta Gallego', 'alfreeznx', 'apgalle03@gmail.com', 19, '', '$2y$10$248v48J91md/tAU8jxuY5Oe4yH1Q1YnU405JSG099srT1fo.PCozy', './assets/files/uploads/kakashi.jpg', 1, 'client'),
(3, 'Luis', 'Monzon', 'Luis4609', 'luis@gmail.com', 19, '', '$2y$10$HvEQtynQniW2w2HDJyv8humTUzTAAiSfmarlhB4iWidolm6RO1p/e', './assets/files/uploads/Naruto_-Mira-este-impresionante-cosplay-que-le-da-vida-a-Minato.jpg', 0, 'client');

INSERT INTO `chats` (`id`, `alias`, `pfp`) VALUES
(1, 'test group', './assets/files/img/default/pfp_group_default.jpg'),
(2, 'luis', './assets/files/img/default/pfp_group_default.jpg');

INSERT INTO `messages` (`id`, `senderID`, `chatID`, `content`, `msgTime`, `msgFile`, `isRead`) VALUES
(1, 3, 1, 'Hey guys! How are you?', '2021-11-25 15:41:08', '', 0),
(2, 2, 1, 'Hi Luis! I am good, finishing my PHP project at the moment :)', '2021-11-25 15:42:45', './assets/files/uploads/code.png', 0),
(3, 1, 1, 'SHUT UP! I CONTROL EVERYTHING!', '2021-11-25 15:44:07', '', 0),
(4, 2, 2, 'Luis, that root user is a bit weird...', '2021-11-25 15:46:57', '', 0),
(5, 3, 2, 'HAHAHAHAHAHAHA', '2021-11-25 15:49:01', '', 0);

INSERT INTO `participate_users_chats` (`userID`, `chatID`) VALUES
(1, 1),
(2, 1),
(2, 2),
(3, 1),
(3, 2);