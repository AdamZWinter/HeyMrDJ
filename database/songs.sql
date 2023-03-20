
CREATE TABLE `songs` (
                         `id` int(32) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                         `name` varchar(100) NOT NULL,
                         `length` double NOT NULL,
                         `artist` varchar(50) NOT NULL
)


    INSERT INTO `songs` (`id`, `name`, `length`, `artist`) VALUES
(1, '1979', 4.26, 'The Smashing Pumpkins'),
(2, 'Reanimator', 3.03, 'Joji, Yves Tumor'),
(3, 'Overconfidence', 5.07, 'Tallah');
