-- phpMyAdmin SQL Dump
-- version 3.3.7deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 24, 2012 at 06:02 PM
-- Server version: 5.1.49
-- PHP Version: 5.3.3-1ubuntu9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `gradingsystem`
--
CREATE DATABASE `gradingsystem` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `gradingsystem`;

-- --------------------------------------------------------

--
-- Table structure for table `answer`
--

CREATE TABLE IF NOT EXISTS `answer` (
  `an_id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `p_id` int(10) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `user_comments` text,
  `instruction` text,
  `upload_time` datetime NOT NULL,
  `no_attempts` int(10) NOT NULL,
  `isLatest` enum('true','false') NOT NULL,
  `ex_status` enum('Waiting','Compiling','Running','Interrupted','Complete') NOT NULL,
  `status` enum('Yes','No - Compilation Error','No - Runtime Error','No - Wrong Result','No - Time Limit Exceeded','No - Presentation Error') DEFAULT NULL,
  PRIMARY KEY (`an_id`),
  KEY `username` (`username`),
  KEY `p_id` (`p_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `answer`
--

INSERT INTO `answer` (`an_id`, `username`, `p_id`, `filename`, `user_comments`, `instruction`, `upload_time`, `no_attempts`, `isLatest`, `ex_status`, `status`) VALUES
(1, 'adam', 1, 'Hello1.java', 'First endless loop problem.', NULL, '2012-02-24 16:51:37', 1, 'false', 'Complete', 'No - Time Limit Exceeded'),
(2, 'adam', 4, 'SumAverage1.java', 'Java problems.', NULL, '2012-02-24 16:52:00', 1, 'false', 'Complete', 'No - Wrong Result'),
(3, 'adam', 2, 'Hello.cpp', '', NULL, '2012-02-24 16:53:01', 1, 'true', 'Complete', 'Yes'),
(4, 'adam', 3, 'PrintStar.java', 'Print a simple star', NULL, '2012-02-24 16:53:19', 1, 'true', 'Complete', 'Yes'),
(5, 'adam', 4, 'SumAverage2.java', '', NULL, '2012-02-24 16:53:30', 2, 'false', 'Complete', 'No - Wrong Result'),
(6, 'adam', 4, 'SumAverage3.java', 'This time is the final one that is always correct.', NULL, '2012-02-24 16:54:46', 3, 'false', 'Complete', 'No - Wrong Result'),
(7, 'adam', 4, 'SumAverage4.java', 'OK, I gave up....\\r\\n\\r\\nHaha. ', NULL, '2012-02-24 16:56:02', 4, 'false', 'Complete', 'No - Wrong Result'),
(8, 'adam', 4, 'SumAverage5.java', 'OK, this is heavy load one.', NULL, '2012-02-24 17:36:32', 5, 'false', 'Complete', 'No - Wrong Result'),
(9, 'adam', 4, 'SumAverage.java', 'Sorry for the first wrong result.', 'Pleaslfjsl\r\nsfslfjslf\r\nsfjslfjsl\r\nsjflsjfsl\r\nfjksl;fks', '2012-02-24 17:37:44', 6, 'true', 'Complete', 'Yes'),
(10, 'adam', 1, 'HelloWorld.java', 'IJK\\r\\njsflsfj\\r\\nsfjsl;fjk', 'g\r\n\r\n\r\n\r\nsfs', '2012-02-24 17:38:43', 2, 'false', 'Complete', 'No - Time Limit Exceeded'),
(11, 'adam', 1, 'HelloWorld1.java', '\\\\sfs\\r\\nsfsfs\\r\\nsfsf', NULL, '2012-02-24 17:48:17', 3, 'false', 'Complete', 'Yes'),
(12, 'adam', 1, 'HelloWorld.java', 'THis is first\r\nadn second\r\n\\\\abc\r\nslfjslf \\r\\n\r\nsfjslzhang\r\nhaha', 'OK.', '2012-02-24 17:56:54', 4, 'true', 'Complete', 'Yes');

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE IF NOT EXISTS `course` (
  `course_code` varchar(10) NOT NULL,
  `course_name` varchar(100) NOT NULL,
  PRIMARY KEY (`course_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`course_code`, `course_name`) VALUES
('EE0001', 'Introduction to Java'),
('EE1234', 'Introductory Computer Science');

-- --------------------------------------------------------

--
-- Table structure for table `enrollment`
--

CREATE TABLE IF NOT EXISTS `enrollment` (
  `en_id` int(10) NOT NULL AUTO_INCREMENT,
  `course_code` varchar(10) NOT NULL,
  `username` varchar(20) NOT NULL,
  PRIMARY KEY (`en_id`),
  KEY `course_code` (`course_code`),
  KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `enrollment`
--

INSERT INTO `enrollment` (`en_id`, `course_code`, `username`) VALUES
(3, 'EE0001', 'tutor'),
(5, 'EE1234', 'myinst'),
(6, 'EE1234', 'tutor'),
(8, 'EE0001', 'newuser'),
(10, 'EE1234', 'newuser'),
(11, 'EE0001', 'adam'),
(12, 'EE0001', 'eva'),
(13, 'EE1234', 'adam');

-- --------------------------------------------------------

--
-- Table structure for table `problem`
--

CREATE TABLE IF NOT EXISTS `problem` (
  `p_id` int(10) NOT NULL AUTO_INCREMENT,
  `p_num` int(10) DEFAULT NULL,
  `p_name` varchar(100) NOT NULL,
  `course_code` varchar(10) NOT NULL,
  `tutorial` int(5) NOT NULL,
  `lang` enum('Java','C++') NOT NULL,
  `explanation` varchar(500) NOT NULL,
  `description` text,
  `input` text,
  `output` text,
  `sample_input` text,
  `sample_output` text,
  `hint` text,
  PRIMARY KEY (`p_id`),
  KEY `course_code` (`course_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `problem`
--

INSERT INTO `problem` (`p_id`, `p_num`, `p_name`, `course_code`, `tutorial`, `lang`, `explanation`, `description`, `input`, `output`, `sample_input`, `sample_output`, `hint`) VALUES
(1, NULL, 'Hello, World', 'EE0001', 1, 'Java', 'Print a Hello World Statment', 'First it print a Hello World\r\nThen the program terminates.\r\n', 'No input needed', 'Hello World statement', 'No exact input needed.', 'Hello world\r\n', 'For such a simple problem, do you really need a hint?'),
(2, NULL, 'C++ hello World', 'EE0001', 1, 'C++', 'It''s an a+b+c+d+e problem', 'a+b+c+d+e', 'int a, b', 'a + b', '\n3\n5\n6\n7\n8', '\n				\n', 'No hint'),
(3, NULL, 'Print Start Figure', 'EE0001', 3, 'Java', 'Print a list of stars', '', '', '', '\n3\n5\n6\n7\n8', '\n29\n5.8\n', 'No hint'),
(4, NULL, 'Number Sum and Average', 'EE0001', 2, 'Java', 'It''s an Sum and Average problem', 'a+b+c+d+e', 'int a, b', 'a + b', '\n3\n5\n6\n7\n8', '\n29\n5.8\n', 'No hint'),
(5, NULL, 'Number Sum and Average', 'EE1234', 2, 'Java', 'It''s an Sum and Average problem', 'a+b+c+d+e', 'int a, b', 'a + b', '\n3\n5\n6\n7\n8', '\n29\n5.8\n', 'No hint');

-- --------------------------------------------------------

--
-- Table structure for table `testcase`
--

CREATE TABLE IF NOT EXISTS `testcase` (
  `io_id` int(10) NOT NULL AUTO_INCREMENT,
  `p_id` int(10) NOT NULL,
  `inputs` text NOT NULL,
  `outputs` text NOT NULL,
  PRIMARY KEY (`io_id`),
  KEY `p_id` (`p_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `testcase`
--

INSERT INTO `testcase` (`io_id`, `p_id`, `inputs`, `outputs`) VALUES
(1, 1, '\n\n', 'Hello World'),
(2, 2, '\n', 'Hello World'),
(3, 3, '\n5\n', '*\n**\n***\n****\n*****\n****\n***\n**\n*'),
(4, 3, '\n7\n', '*\n**\n***\n****\n*****\n******\n*******\n******\n*****\n****\n***\n**\n*'),
(8, 5, '\n3\n2 3 4\n', '9\n3.0'),
(9, 5, '\n5\n1\n2\n-2\n0\n6\n', '7\n1.4'),
(10, 5, '\n7\n-2\n-3\n-4\n-5\n5\n6\n4\n', '1\n0.142857142857'),
(11, 4, '4\r\n2 3 4 5', '14\r\n3.5'),
(12, 4, '10\r\n1 2 3 4 5 6 7 8 9 10', '55\r\n5.50'),
(13, 4, '1\r\n23', '23\r\n23.0'),
(14, 4, '4\r\n12 34 56 65\r\n', '167\r\n41.75');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `username` varchar(20) NOT NULL,
  `password` char(40) NOT NULL,
  `email` varchar(100) NOT NULL,
  `privilege` enum('student','instructor','administrator') NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`username`, `password`, `email`, `privilege`) VALUES
('adam', '0e18f44c1fec03ec4083422cb58ba6a09ac4fb2a', 'adam@ntu.edu.sg', 'student'),
('admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'admin@ntu.edu.sg', 'administrator'),
('batman', '5c6d9edc3a951cda763f650235cfc41a3fc23fe8', 'batman@ntu.edu.sg', 'administrator'),
('eva', '3da1befff5b5c75e2e99948ffb230642c19fac6a', 'eva@ntu.edu.sg', 'student'),
('myadmin', 'bc750014cb3cf5a86b4851b9ca33027537b3fb85', 'myadmin@ntu.edu.sg', 'administrator'),
('myinst', '506bba0617f676f0354d4af45e7b199d0cea943a', 'myinst@ntu.edu.sg', 'instructor'),
('newuser', 'cd1bfe55cbb2b0b989cac86391e5100c933ffe7e', 'newuser@ntu.edu.sg', 'student'),
('ntuadmin', '39f5ee61d1713de46dfd763ac95e8d4139d3bd57', 'ntuadmin@ntu.edu.sg', 'administrator'),
('superuser', '8e67bb26b358e2ed20fe552ed6fb832f397a507d', 'superuser@ntu.edu.sg', 'administrator'),
('testadmin', '743139240ff612253817440d98acb2ce7939fbb4', 'testadmin@ntu.edu.sg', 'administrator'),
('tutor', 'a9bd7a5b583cbe082e2c850595c71a6818626f10', 'tutor@ntu.edu.sg', 'instructor');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `answer`
--
ALTER TABLE `answer`
  ADD CONSTRAINT `answer_ibfk_1` FOREIGN KEY (`username`) REFERENCES `user` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `answer_ibfk_2` FOREIGN KEY (`p_id`) REFERENCES `problem` (`p_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `enrollment`
--
ALTER TABLE `enrollment`
  ADD CONSTRAINT `enrollment_ibfk_1` FOREIGN KEY (`course_code`) REFERENCES `course` (`course_code`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `enrollment_ibfk_2` FOREIGN KEY (`username`) REFERENCES `user` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `problem`
--
ALTER TABLE `problem`
  ADD CONSTRAINT `problem_ibfk_1` FOREIGN KEY (`course_code`) REFERENCES `course` (`course_code`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `testcase`
--
ALTER TABLE `testcase`
  ADD CONSTRAINT `testcase_ibfk_1` FOREIGN KEY (`p_id`) REFERENCES `problem` (`p_id`) ON DELETE CASCADE ON UPDATE CASCADE;
