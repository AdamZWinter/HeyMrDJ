CREATE TABLE `users` (
                         `id` int(32) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                         `fname` varchar(32) NOT NULL,
                         `lname` varchar(64) DEFAULT NULL,
                         `email` varchar(128) NOT NULL,
                         `phone` varchar(16) DEFAULT NULL,
                         `state` varchar(16) DEFAULT NULL,
                         `photo` varchar(128) DEFAULT NULL,
                         `password` varchar(256) NOT NULL,
                         `isDJ` tinyint(1) DEFAULT NULL
);