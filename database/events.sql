CREATE TABLE `events` (
                          `id` int(32) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                          `name` varchar(64) NOT NULL,
                          `dj` varchar(64) NOT NULL,
                          `state` varchar(32) NOT NULL,
                          `date` int(32) NOT NULL
);