-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 27, 2023 at 01:54 PM
-- Server version: 5.7.37
-- PHP Version: 7.3.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sunnytailor_hotel`
--

-- --------------------------------------------------------

--
-- Table structure for table `contactus`
--

CREATE TABLE `contactus` (
  `id` int(11) NOT NULL,
  `contactPersonName` varchar(500) NOT NULL DEFAULT 'null',
  `email` varchar(500) NOT NULL DEFAULT 'null',
  `comments` text NOT NULL,
  `feedbacktime` bigint(17) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contactus`
--

INSERT INTO `contactus` (`id`, `contactPersonName`, `email`, `comments`, `feedbacktime`) VALUES
(1, 'prathmesh ravindra karekar', 'prathmeshkarekar11@gmail.com', 'HIIIIiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii', 1588706701307),
(2, 'prathmesh', 'sameer.bayani98@gmail.com', 'jkljjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjj', 1588706931666);

-- --------------------------------------------------------

--
-- Table structure for table `hp_admin`
--

CREATE TABLE `hp_admin` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `fname` varchar(100) DEFAULT NULL,
  `lname` varchar(100) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` text NOT NULL,
  `role` varchar(50) NOT NULL,
  `created` datetime NOT NULL,
  `active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hp_admin`
--

INSERT INTO `hp_admin` (`id`, `full_name`, `fname`, `lname`, `username`, `email`, `password`, `role`, `created`, `active`) VALUES
(1, 'Ajay Prajapati', 'Ajay', 'Prajapati', 'Ajay Prajapati', 'admin@gmail.com', '$2y$10$AyuxgqKh3nQKWApYSTZGHOcyQYA1yTyjKGQkTHJ4tGAwNSLiOJFly', 'admin', '2020-12-05 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `hp_disease_master`
--

CREATE TABLE `hp_disease_master` (
  `id` int(11) NOT NULL,
  `disease_name` varchar(500) DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '0',
  `created_time` bigint(17) NOT NULL DEFAULT '0',
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `updated_time` bigint(17) NOT NULL DEFAULT '0',
  `deleted_by` int(11) NOT NULL DEFAULT '0',
  `deleted_time` bigint(17) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hp_disease_master`
--

INSERT INTO `hp_disease_master` (`id`, `disease_name`, `created_by`, `created_time`, `updated_by`, `updated_time`, `deleted_by`, `deleted_time`) VALUES
(1, 'fever', 1, 1661347035620, 1, 1661347373692, 0, 0),
(2, 'fever with chills', 1, 1661347053316, 0, 0, 0, 0),
(3, 'vomiting', 1, 1661347131170, 0, 0, 0, 0),
(4, 'headache', 1, 1661347150930, 0, 0, 0, 0),
(5, 'nausea', 1, 1667057428225, 1, 1672666879655, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `hp_medicine_master`
--

CREATE TABLE `hp_medicine_master` (
  `id` int(11) NOT NULL,
  `medicine_name` varchar(500) CHARACTER SET latin1 DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '0',
  `created_time` bigint(17) NOT NULL DEFAULT '0',
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `updated_time` bigint(17) NOT NULL DEFAULT '0',
  `deleted_by` int(11) NOT NULL DEFAULT '0',
  `deleted_time` bigint(17) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hp_medicine_master`
--

INSERT INTO `hp_medicine_master` (`id`, `medicine_name`, `created_by`, `created_time`, `updated_by`, `updated_time`, `deleted_by`, `deleted_time`) VALUES
(1, 'Dolo', 1, 1687677519568, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `hp_patient_info`
--

CREATE TABLE `hp_patient_info` (
  `id` int(11) NOT NULL,
  `patient_name` varchar(500) NOT NULL,
  `patient_age` varchar(100) NOT NULL,
  `sex` varchar(20) NOT NULL,
  `contact_no` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `fees` int(11) NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `created_time` bigint(17) NOT NULL DEFAULT '0',
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `updated_time` bigint(17) NOT NULL DEFAULT '0',
  `deleted_by` int(11) NOT NULL DEFAULT '0',
  `deleted_time` bigint(17) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hp_patient_info`
--

INSERT INTO `hp_patient_info` (`id`, `patient_name`, `patient_age`, `sex`, `contact_no`, `address`, `fees`, `created_by`, `created_time`, `updated_by`, `updated_time`, `deleted_by`, `deleted_time`) VALUES
(1, 'Prathmesh Karekar', '29 Years', 'male', '9920121080', 'Bhayander (East)', 0, 1, 1614008292850, 1, 1613979045054, 0, 0),
(2, 'rishi', '22 year', 'male', '7021364965', 'dhanjiwadi malad east', 0, 1, 1614008292850, 1, 1631974452877, 1, 1661003372823),
(3, 'rishi', '26', 'male', '000000000', 'east', 0, 1, 1631974390897, 1, 1631974442751, 1, 1661003382493),
(4, 'mr jay singh', '35', 'male', '9699500008', 'laxamn nagar', 0, 1, 1633621054757, 1, 1633621094700, 1, 1661003390089),
(5, 'mr ashish tiwari', '29', 'male', '8291044713', 'kandivali east\r\n', 0, 1, 1633759445089, 0, 0, 1, 1661003397778),
(6, 'mr naresh chavda', '40', 'male', '7096050440', 'laxman nagar malad east', 0, 1, 1637589777551, 0, 0, 0, 0),
(7, 'mr abzar ansari', '16', 'male', '8691894708', 'laxman nagar malad east', 0, 1, 1637590761083, 0, 0, 0, 0),
(8, 'mrs deepika kadas', '45', 'female', '8149195523', 'virar', 0, 1, 1637592408921, 0, 0, 0, 0),
(9, 'mr sahnawaz rahman', '19', 'male', '9608642972', 'laxman nagar', 0, 1, 1637595388864, 0, 0, 0, 0),
(10, 'ms gauravi bandevkar', '14', 'female', '9664906518', 'laxman nagar', 0, 1, 1637596346060, 0, 0, 0, 0),
(11, 'mr yashraj kshirsagar', '13', 'male', '9322774917', 'laxman nagar', 0, 1, 1637596902495, 0, 0, 0, 0),
(12, 'mr piyush  kate', '17', 'male', '9867277518', 'laxman nagar', 0, 1, 1637597195951, 0, 0, 0, 0),
(13, 'mr harsh deshmukh', '10', 'male', '9594523361', 'laxman nagar', 0, 1, 1637597716702, 0, 0, 0, 0),
(14, 'mrs arti kamble', '29', 'female', '7045788719', 'laxmqan nagar', 0, 1, 1637598645454, 0, 0, 0, 0),
(15, 'mrs sonu patel', '40', 'female', '7977507713', 'laxman nagar', 0, 1, 1637600218131, 0, 0, 0, 0),
(16, 'mr vikas vishwkarma', '25', 'male', '8108233428', 'laxman nagar', 0, 1, 1637644164969, 0, 0, 0, 0),
(17, 'mrs sharmila sawant', '34', 'female', '9768007065', 'laxman nagar', 0, 1, 1637649894479, 0, 0, 0, 0),
(18, 'mr unish', '35', 'male', '9967123262', 'laxman nagar', 0, 1, 1637732422117, 0, 0, 0, 0),
(19, 'mrs pooja rathod', '32', 'female', '885027072', 'adharsh nagar', 0, 1, 1659533490279, 1, 1659533588785, 0, 0),
(20, 'rahul gupta', '28', 'male', '8108986439', 'pal nagar nr punjab dairy', 120, 1, 1659963962560, 1, 1659966304111, 1, 1661003452996),
(21, 'sunny tailor', '28', 'male', '8450914175', 'dhanjiwadi', 0, 1, 1660107327577, 0, 0, 1, 1661003466717),
(22, 'prathmesh 1 ', '29', 'male', '9985858585', 'bhayander', 0, 1, 1660138981235, 0, 0, 1, 1660664079678),
(23, 'mr jaydash', '56', 'male', '9167458693', 'laxman nagar', 0, 1, 1660198804084, 0, 0, 0, 0),
(24, 'Chirag R pokiya', '29', 'male', '8291253574', 'Malad(E)', 30, 1, 1660578729135, 0, 0, 0, 0),
(25, 'mast ansh gaikwad', '8', 'male', '7083555689', 'laxman nagar', 0, 1, 1660629836990, 0, 0, 0, 0),
(26, 'demosss kkkkkkkkkk', '22', 'male', '8965741257', 'demosss kkkkkkk', 0, 1, 1660630712177, 1, 1660654299897, 1, 1660654326752),
(27, 'mrs meghana jhadav', '38', 'female', '8454833101', 'laxman nagr', 50, 1, 1660632740666, 1, 1660633163938, 0, 0),
(28, 'mrs aditi kobnak', '32', 'female', '916267987', 'laxman nagar', 50, 1, 1660654376315, 1, 1660655051487, 0, 0),
(29, 'Kabir kumar', '12', 'male', '9702529035', 'malad west', 0, 1, 1660663648006, 1, 1660663673834, 0, 0),
(30, 'MR MOOL SINGH', '21', 'male', '9351655317', 'LAXMAN NAGAR', -50, 1, 1660885306614, 1, 1660885733841, 0, 0),
(31, 'MRS MAMTA MALIK', '26', 'female', '9699989903', 'LAXMAN NAGAR', 0, 1, 1660885722358, 0, 0, 0, 0),
(32, 'MRS MEENA DEEPAK SAKPAL', '36', 'female', '8291494946', 'LAXMAN NAGAR', 0, 1, 1660912644744, 1, 1660912683521, 0, 0),
(33, 'vansh manoj sakhre', '2 years', 'male', '9326547183', 'laxman nagar', 50, 1, 1660916376126, 0, 0, 0, 0),
(34, 'karishma sitaram khamkar', '17', 'female', '9167544247', 'laxman nagar', 50, 1, 1660916580885, 0, 0, 0, 0),
(35, 'Shanatanu Sanjay Patil', '25', 'male', '9082813747', 'laxman nagar', 50, 1, 1660917206611, 0, 0, 0, 0),
(36, 'Anil krishna kharade', '43', 'male', '9619974663', 'shivrang co op soc , laxman nagar', 50, 1, 1660917512827, 0, 0, 0, 0),
(37, 'sangeeta deepak patel', '31', 'female', '8657154820', 'datta mandir', 50, 1, 1660919778108, 0, 0, 0, 0),
(38, 'mrs bachidevi avadesh jaiswal', '24', 'female', '7738308795', 'jagu sohanji chawl vaisht  pada np 02', 0, 1, 1660920280742, 0, 0, 0, 0),
(39, 'ms jyoti dhadwe', '22', 'female', '9004817737', 'laxman nagar', 0, 1, 1660921201385, 0, 0, 0, 0),
(40, 'mrs chanda jagjeevan vishwakarma', '55', 'female', '7208953410', 'laxman nagare', 0, 1, 1660923682097, 0, 0, 0, 0),
(41, 'ms suman bairagi', '27', 'female', '9082194614', 'goregaon west', 0, 1, 1660924201004, 0, 0, 0, 0),
(42, 'mrs vaisali godawari', '24', 'female', '8850119799', 'omkar ', 0, 1, 1660925331724, 0, 0, 0, 0),
(43, 'ms kiran suresh upadhye', '21', 'female', '7039624701', 'laxman nagar', 0, 1, 1660925567292, 0, 0, 0, 0),
(44, 'ms rahil shaikh', '17', 'female', '9930156450', 'laxman nagar', 0, 1, 1660925736737, 0, 0, 0, 0),
(45, 'mrs asha rajesh gupta', '33 years', 'female', '9136535501', 'laxman nagar', 0, 1, 1660925934697, 0, 0, 0, 0),
(46, 'ms sadaf yosouf shaikh', '21', 'female', '9676094235', 'laxman nagar', 0, 1, 1660926305511, 0, 0, 0, 0),
(47, 'mrs parida syyad shaikh', '38 years', 'female', '9867105399', 'laxman nagar', 0, 1, 1660926830940, 0, 0, 0, 0),
(48, 'mr vrushabh vijay thombre', '21 years', 'male', '8691891012', 'laxman nagar', 0, 1, 1660928026926, 0, 0, 0, 0),
(49, 'mr shiva shree vrindeshwari prasad gautam', '18 years', 'male', '9565862472', 'laxman nagar', 0, 1, 1660929577404, 0, 0, 0, 0),
(50, 'mr arun lahu kasbe', '40y', 'male', '9322071850', 'laxman nagar', 0, 1, 1660970728038, 0, 0, 0, 0),
(51, 'mrs vimala jaiswal', '72y', 'female', '0000000000', 'laxman nagar', 0, 1, 1660972586244, 0, 0, 0, 0),
(52, 'ms sabina ibarat shaikh', '10y', 'female', '9619627040', 'laxman nagar', 0, 1, 1660973582039, 0, 0, 0, 0),
(53, 'mr sankar dalaram devashi', '10y', 'male', '9819643230', 'laxman nagar', 0, 1, 1660974438134, 0, 0, 0, 0),
(54, 'mrs sheetal kotian', '60y', 'female', '8879365295', 'laxman nagar', 0, 1, 1660976712562, 0, 0, 0, 0),
(55, 'mr prasanjeet bag', '27y', 'male', '9967568525', 'laxman nagar', 0, 1, 1660977310850, 0, 0, 0, 0),
(56, 'mr sachin sambhaji mahadik', '43y', 'male', '9769488408', 'laxman nagar', 0, 1, 1660977527422, 0, 0, 0, 0),
(57, 'mrs sangeeta pravin mistry', '39y', 'female', '9619555796', 'laxman nagar\r\n', -50, 1, 1660978112668, 1, 1660978371309, 0, 0),
(58, 'ms manali laxman dhamal', '19y', 'female', '9619643556', 'usman chwl,laxman nagar', 0, 1, 1660982823985, 0, 0, 0, 0),
(59, 'mr satyajit satish payra', '25y', 'male', '9064532318', 'laxman nagar', 0, 1, 1660983208698, 0, 0, 0, 0),
(60, 'mrs farzana abdul shaikh ', '35y', 'female', '00000000', 'laxman nagar', 0, 1, 1660984524979, 0, 0, 0, 0),
(61, 'mrs sumaiya salman khan', '25y', 'female', '8424829286', 'laxman nagar', 0, 1, 1660984811524, 0, 0, 0, 0),
(62, 'mrs rajeshree sanjit das', '18y', 'female', '9152712949', 'laxman nagar', 0, 1, 1660985071931, 0, 0, 0, 0),
(63, 'mr babulal gupta', '40y', 'male', '7738426639', 'laxman nagar', 0, 1, 1661000213394, 0, 0, 0, 0),
(64, 'baby pari jitendra panchal', '4y', 'female', '9769351944', 'vasai west', 0, 1, 1661001151188, 0, 0, 0, 0),
(65, 'ms bhakti', '18y', 'female', '000000000', 'laxman nagar', 0, 1, 1661060121804, 0, 0, 1, 1661060128086),
(66, 'mr siddhesh sunil pawar', '23y', 'male', '7718956598', 'laxman nagar', 0, 1, 1661143204516, 0, 0, 0, 0),
(67, 'mrs sanjivani nilesh gaikwad', '36y', 'female', '7710068048', 'laxman nagar', 0, 1, 1661144600613, 0, 0, 0, 0),
(68, 'mr prashant eknath jadhav', '25y', 'male', '8459108366', 'laxman nagar', 0, 1, 1661145908342, 0, 0, 0, 0),
(69, 'mrs suman ganesh kasbe', '45y', 'female', '9224133355', 'laxman nagar', 0, 1, 1661149070344, 0, 0, 0, 0),
(70, 'mast vansh manoj sakre', '2y', 'male', '9326547183', 'laxman nagar', 0, 1, 1661149605836, 0, 0, 0, 0),
(71, 'mr mukesh pramod sharma', '21y', 'male', '9372195203', 'laxman nagar', 0, 1, 1661149978085, 0, 0, 0, 0),
(72, 'mr subhash shankar jaybhaye', '49y', 'male', '9987219264', 'laxman nagar subedar chwl', 0, 1, 1661152711522, 0, 0, 0, 0),
(73, 'ms divyanshi ketharam prajapati', '13y', 'female', '9819387720', 'laxman nagar', 0, 1, 1661153275221, 0, 0, 0, 0),
(74, 'ms shrutika sanjay bodke', '19y', 'female', '8879013858', 'laxman nagar', 0, 1, 1661172565809, 0, 0, 0, 0),
(75, 'mrs manisha ravi kasbe', '29y', 'female', '9930863118', 'laxman nagar', 0, 1, 1661174700234, 1, 1661174733742, 0, 0),
(76, 'ms samiksha shankar rakshe ', '9y', 'female', '882838325', 'laxman nagar', 0, 1, 1661176761339, 0, 0, 0, 0),
(77, 'mr dharmendra lalbhadur yadav', '28y', 'male', '7990199038', 'laxman nagar', 0, 1, 1661177049021, 0, 0, 0, 0),
(78, 'mr ibarat ali', '42y', 'female', '9892663061', 'laxman nagar', 0, 1, 1661177429384, 0, 0, 0, 0),
(79, 'ms kavya vivek kamble', '7y', 'female', '8291134449', 'laxman nagar', 0, 1, 1661177729343, 0, 0, 0, 0),
(80, 'mr vivek kamble', '35y', 'male', '8291134449', 'laxman nagar', 0, 1, 1661178091107, 0, 0, 0, 0),
(81, 'mr ajay kumar ', '38y', 'male', '8356982817', 'laxman nagar', 0, 1, 1661178491689, 0, 0, 0, 0),
(82, 'mast asad majeed khan', '2y', 'male', '9326925977', 'laxman nagar', 0, 1, 1661178898102, 0, 0, 0, 0),
(83, 'ms dhruvi bipin parmar', '5y', 'female', '9867144774', 'laxman nagar', 0, 1, 1661180233837, 0, 0, 0, 0),
(84, 'mrs raksha ganesh dangar', '34y', 'female', '7302584009', 'laxman nagar', 0, 1, 1661231068035, 0, 0, 0, 0),
(85, 'ms urmi solaram prajapati', '5y', 'female', '9892602146', 'laxman nagar', 0, 1, 1661231257436, 0, 0, 0, 0),
(86, 'ms nidhi shelar', '6y', 'female', '8692903709', 'laxman nagar', 0, 1, 1661268786101, 0, 0, 0, 0),
(87, 'ms nandini nilesh vishwakarma', '15y', 'female', '7304850547', 'laxman nagar\r\n', 0, 1, 1661316232837, 0, 0, 0, 0),
(88, 'mr karan narayan karde', '19y', 'male', '7738464738', 'laxman nagar', 0, 1, 1661316940905, 0, 0, 0, 0),
(89, 'mr mahadev yashwant mhadaye', '55y', 'male', '9768674528', 'laxman nagar shirangan soc.', 0, 1, 1661345141742, 0, 0, 0, 0),
(90, 'mr yatharth raju nagpurkar', '13y', 'male', '7039209081', 'laxman nagar', 0, 1, 1661345336687, 0, 0, 0, 0),
(91, 'mr sushim malgaonkar', '52y', 'male', '9702673805', 'laxman nagar', 0, 1, 1661490891537, 0, 0, 0, 0),
(92, 'mrs dipali machindra khot', '32y', 'female', '8623837365', 'laxman nagar', 0, 1, 1661491114547, 0, 0, 0, 0),
(93, 'ms netra arun kasbe', '4y', 'female', '9322071850', 'laxman nagar', 0, 1, 1661493061213, 1, 1683383122973, 0, 0),
(94, 'xxx', '28y', 'male', '0000000000', 'xxx', 0, 1, 1688557129519, 0, 0, 1, 1688557134626),
(95, 'ms aditi raju nagpurkar ', '16', 'female', '9594443248', 'laxman nagar', 0, 1, 1688971137026, 1, 1688971338752, 0, 0),
(96, 'mrs namita raju nagpurkar', '45y', 'female', '9594443248', 'laxman nagar', 0, 1, 1688971300076, 0, 0, 0, 0),
(97, 'mrs dhanu syadri', '30y', 'female', '0000000000', 'laxman nagar', 0, 1, 1688972379740, 0, 0, 0, 0),
(98, 'baby rashika vishawkarma ', '1y wt 10kg', 'female', '9702360704', 'kandivali east', 0, 1, 1688973719756, 0, 0, 0, 0),
(99, 'mr manoj jadhav ', '38', 'male', '9702531635', 'mourya chawl , guru krupa society , laxman nagar kurar village \r\nmalad east mumbai - 400 097', 0, 1, 1688993554379, 1, 1688994421279, 0, 0),
(100, '`mrs asha vivek jadhav', '30y', 'female', '7506748480', 'laxman nagar', 0, 1, 1688993806743, 0, 0, 1, 1688993929944),
(101, 'mrs kusuma pujari', '48y', 'female', '0000000000', 'laxman nagar', 0, 1, 1688994092426, 0, 0, 0, 0),
(102, 'mrs chanda vishwakarma', '57y', 'female', '9769701245', 'laxman nagar', 0, 1, 1688995103646, 0, 0, 0, 0),
(103, 'ms anavi kamble', '6y 18kg', 'female', '9870163424', 'balmohan singh chwl laxman nagar', 0, 1, 1688995401343, 1, 1688995467894, 0, 0),
(104, 'mast jogesh rohit harigaonkar', '1.5y 11.45kg', 'male', '7039526792', 'trimutri chwl laxman nagar', 0, 1, 1688995702548, 0, 0, 0, 0),
(105, 'ms priyanka markal', '28y', 'female', '7045893206', 'malang chwl laxman nagar', 0, 1, 1688996086263, 0, 0, 0, 0),
(106, 'ms payal kharat', '13y', 'female', '7045561876', 'amebdkar chwl laxman nagar', 0, 1, 1688996252960, 0, 0, 0, 0),
(107, 'mr manoj singh', '46y', 'male', '9970687582', 'maurya chwl laxman nagar', 0, 1, 1688996459759, 0, 0, 0, 0),
(108, 'mrs chandrakala ameen', '43y', 'female', '7498877455', 'jabir chwl laxman nagr', 0, 1, 1688996819795, 0, 0, 1, 1688997311801),
(109, 'mrs chandrakala ameen', '43y', 'female', '7498877455', 'jabir chwl laxman nagr', 0, 1, 1688996819909, 0, 0, 0, 0),
(110, 'mr tanish sakpal', '15y', 'male', '8291494946', 'sukhdev pandit chwl laxman nagr', 0, 1, 1688997108581, 0, 0, 0, 0),
(111, 'mrs sobha chaugule', '50y', 'female', '9220617804', 'ganesh wadi laxman nagar', 0, 1, 1688997523047, 0, 0, 0, 0),
(112, 'ms pooja kamble ', '21y', 'female', '7021371862', 'sai darshan chwl laxman nagr', 0, 1, 1688997720235, 0, 0, 0, 0),
(113, 'mr chetan rangle', '38y', 'male', '7039403844', 'shivrangan co so laxman ngr', 0, 1, 1688997966808, 0, 0, 0, 0),
(114, 'mrs jaya bambhaniya', '40y', 'female', '8652601892', 'datta wadi ', 0, 1, 1688998555947, 0, 0, 0, 0),
(115, 'mast om bare', '5y 10kg', 'male', '9967374465', 'utakarsh chwl laxman ngr', 0, 1, 1688998792576, 0, 0, 0, 0),
(116, 'mast ekansh rakshe', '7m 7kg', 'male', '8356044305', 'laxman ngr', 0, 1, 1688999126914, 0, 0, 0, 0),
(117, 'mast taimur shaikh', '1y 7kg', 'male', '8424811858', 'pn gupta chwl laxman ngr', 0, 1, 1688999814963, 0, 0, 0, 0),
(118, 'mrs heena shaikh', '26y', 'female', '8424811858', 'pn gupta chwl laxman ngr', 0, 1, 1688999915468, 0, 0, 0, 0),
(119, 'ms maithil patel', '10y', 'female', '7977507713', 'kanta jhadav chwl laxman ngr', 0, 1, 1689000762974, 0, 0, 0, 0),
(120, 'mrs nagma zahir khan', '38y', 'female', '9967510508', 'stove chwl kurar village', 0, 1, 1689002758602, 0, 0, 0, 0),
(121, 'mr dhruv katkar', '13y', 'male', '7738428525', 'sardar mast chawl,laxxman nagr', 0, 1, 1689009341149, 0, 0, 0, 0),
(122, 'ms piyanshi suthar', '8y 14kg', 'female', '9321018147', 'ramesh hotel lucky dairy', 0, 1, 1689049837792, 0, 0, 0, 0),
(123, 'mr ganesh bhanage', '21y', 'male', '9769994539', 'bst dindoshi', 0, 1, 1689050965106, 0, 0, 0, 0),
(124, 'mr ganesh bhanage', '21y', 'male', '9769994539', 'bst dindoshi', 0, 1, 1689050965204, 0, 0, 1, 1689051266039),
(125, 'mr muzfar sayyad ', '42y', 'male', '7208984540', 'ganesh wadi laxman nagar', 0, 1, 1689053733743, 0, 0, 0, 0),
(126, 'ms bhumi vijay bhodke', '10y', 'female', '9769099290', 'devkar chwl laxman nagar', 0, 1, 1689054385619, 0, 0, 0, 0),
(127, 'ms aparna sawant', '40y', 'female', '8850181912', 'master chwl laxman nagar', 0, 1, 1689055556692, 1, 1689055579404, 0, 0),
(128, 'mr kuldeep kumar', '19y', 'male', '9886235481', 'laxman nagar', 0, 1, 1689056242518, 0, 0, 0, 0),
(129, 'mrs bina dave', '51y', 'female', '9076325244', 'master chawl laxman nagar', 0, 1, 1689056522483, 0, 0, 0, 0),
(130, 'mr arnav nazre', '8y 25.8kg', 'male', '7208713754', 'anad wadi chwl nr jija mata ', 0, 1, 1689059002525, 0, 0, 0, 0),
(131, 'mrs subhangi katkar', '56y', 'female', '0000000000', 'laxman nagar', 0, 1, 1689080819508, 0, 0, 0, 0),
(132, 'ms kajol tiwari', '23y', 'female', '8268427385', 'dubej chwl laxman nagar', 0, 1, 1689084340357, 0, 0, 0, 0),
(133, 'mr anand zingade ', '39y', 'male', '9664875729', 'anand chawl', 0, 1, 1689091937152, 0, 0, 0, 0),
(134, 'mr surykant bhayje', '39y', 'male', '9594682750', 'laxman nagar', 0, 1, 1689599669716, 0, 0, 0, 0),
(135, 'ms almira shaikh ', '9y wt 24kg', 'female', '0000000000', 'laxman nagar', 0, 1, 1690292865160, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `hp_patient_info_case_paper`
--

CREATE TABLE `hp_patient_info_case_paper` (
  `id` int(11) NOT NULL,
  `patient_registration_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `disease_name` varchar(500) NOT NULL,
  `disease_days` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hp_patient_info_case_paper`
--

INSERT INTO `hp_patient_info_case_paper` (`id`, `patient_registration_id`, `patient_id`, `disease_name`, `disease_days`) VALUES
(6, 1, 1, 'fever', '1 Days'),
(7, 1, 1, 'headche', '1 Days'),
(8, 1, 1, 'shivering Fever', '1 Days'),
(9, 2, 4, 'fever with chills', '3'),
(10, 2, 4, 'throat pain', '3'),
(11, 2, 4, 'runnig nose', '3'),
(12, 3, 6, 'lumbar pain ', '2 days'),
(13, 3, 6, 'upper back region pain', '2 days'),
(14, 4, 7, 'giddiness', 'evng'),
(15, 4, 7, 'running nose', 'evng'),
(16, 5, 8, 'itching on redness area on and off', '5 month'),
(17, 6, 9, 'itching all over body sp.finger and toe', '15-20d'),
(18, 7, 10, ' drycough ', '2 d'),
(19, 8, 11, 'fever ', '2d'),
(20, 9, 12, 'throat pain ', '2 d'),
(21, 9, 12, 'dry cough', '2 d'),
(22, 10, 13, 'running nose', '1 d'),
(23, 11, 14, 'throat pain', '2 d'),
(24, 11, 14, 'h/o fever ', '2 d'),
(25, 12, 15, 'running nose ', '1 d'),
(26, 12, 15, 'headache', '1 d'),
(27, 12, 15, 'eye pain', '1 d'),
(28, 13, 16, 'burning micturation', '2 d'),
(29, 13, 16, 'mild pain in body', '2 d'),
(39, 14, 17, 'abd pain', '2 d'),
(40, 14, 17, 'vomiting', ' 2 d'),
(41, 14, 17, 'h/o losse motion', '2 d'),
(44, 15, 24, 'Headach', '2'),
(48, 16, 25, 'fever with chills', '2'),
(49, 16, 25, 'throat pain', '2'),
(50, 16, 25, 'dry cough', '2'),
(51, 17, 27, 'bodypain', '1'),
(52, 17, 27, 'headache', '1'),
(53, 17, 27, 'nausea', '1'),
(54, 18, 28, 'fever with chills', '1'),
(55, 18, 28, 'bodyache', '1'),
(56, 18, 28, 'throat pain', '1'),
(57, 19, 30, 'FEVER WITH CHILLS', '1'),
(58, 19, 30, 'BODYACHE', '1'),
(59, 19, 30, 'WEAKNESS', '1'),
(62, 20, 32, 'NAUSEA', '5DAYS ON AND OFF'),
(63, 20, 32, 'HEADACHE', '1 DAY'),
(64, 21, 6, 'FEVER EITH CHILLS', '1'),
(65, 21, 6, 'LUMBER PAIN ON ADN OFF', '1MONTH'),
(66, 21, 6, 'RUNNING NOSE', '1'),
(67, 22, 37, 'fever ', '1'),
(68, 22, 37, 'bodyache ', '1'),
(69, 22, 37, 'throat pain', '1'),
(70, 23, 38, 'fever eith chills', '4 '),
(71, 23, 38, 'bodyache ', '4'),
(72, 23, 38, 'vomiting', '1'),
(73, 24, 39, 'fever with chills', '1'),
(74, 24, 39, 'running nose ', '1'),
(75, 24, 39, 'throat pain', '1'),
(76, 25, 40, 'dry cough', '4-5'),
(77, 25, 40, 'weakness', '4'),
(78, 26, 46, 'fever with chills', '1'),
(79, 26, 46, 'bodyache ', '1'),
(80, 26, 46, 'weakness', '1'),
(81, 27, 47, 'fever ', '1'),
(82, 27, 47, 'running nose ', '1'),
(83, 27, 47, 'throat pain', '1'),
(84, 28, 45, 'giddiness', '4'),
(85, 28, 45, 'nausea', '1'),
(86, 29, 44, 'abdomen pain lt hypogastric region', 'today evng'),
(87, 30, 48, 'throat pain', '1'),
(88, 30, 48, 'running nose', ''),
(89, 31, 43, 'bodyache ', '1'),
(90, 31, 43, 'headache', '1'),
(91, 31, 43, 'nausea', '1'),
(92, 32, 42, 'abdomen pain rt hypochondria while breathing,laughing', '4'),
(93, 33, 41, 'anxity attack', '1'),
(94, 34, 49, 'wound@ rt lower leg  ', '4'),
(95, 34, 49, 'mild pain @ wound site', '2'),
(96, 35, 50, 'fever ', '1'),
(97, 35, 50, 'bodyache', '1'),
(98, 35, 50, 'running nose', '1'),
(99, 36, 51, 'fever ', '1'),
(100, 36, 51, 'cough', '1'),
(101, 37, 52, 'fever ', 'today night'),
(102, 37, 52, 'running nose', 'today night'),
(103, 38, 53, 'fever with chills', '1'),
(104, 39, 54, 'rt side upper back pain', '1'),
(105, 40, 55, 'weakness', '2'),
(106, 41, 56, 'coughing', '2'),
(107, 42, 57, 'tr knee jt pain on and off', '2-3y'),
(108, 42, 57, 'lt wrist jt pain on and off', '2-3y'),
(109, 43, 58, 'throat pain', '1'),
(110, 44, 59, 'epistrium pain', '4-5'),
(111, 44, 59, 'hyperacidity', '4-5'),
(112, 44, 59, 'dry cough', '2-3'),
(113, 45, 60, 'fever ', '1'),
(114, 45, 60, 'weakness', '1'),
(115, 46, 61, 'fever with chills', '2'),
(116, 46, 61, 'bodyache', '2'),
(117, 47, 62, 'headache', '2-3'),
(118, 47, 62, 'giddiness', '1'),
(119, 47, 62, 'constipation on&off', '2-4month'),
(120, 48, 63, 'giddinesss ', 'mrng'),
(121, 48, 63, 'headache', 'mrng'),
(122, 49, 64, 'fever', '1'),
(123, 49, 64, 'throat pain', '1'),
(124, 50, 66, 'fever ', '1'),
(125, 50, 66, 'running nose ', '1'),
(126, 50, 66, 'throat pain', '1'),
(127, 51, 68, 'fever ', '2'),
(128, 51, 68, 'running nose', '1'),
(129, 52, 71, 'fever', '2'),
(130, 52, 71, 'bodyache', '2'),
(131, 52, 71, 'abdomen pain', '1'),
(132, 53, 72, 'fever', '1'),
(133, 53, 72, 'throat pain', '1'),
(134, 54, 73, 'leg pain on and off', ' 1m'),
(135, 55, 74, 'abnormal menses', '3m'),
(136, 56, 75, 'fever with chills', '1'),
(137, 56, 75, 'headache', '1'),
(138, 57, 76, 'fever', '1'),
(139, 57, 76, 'running nose', '1'),
(140, 57, 76, 'coughing', '1'),
(141, 58, 77, 'fever ', '1'),
(142, 58, 77, 'headache', '1'),
(143, 59, 78, 'somotitis', '10'),
(144, 60, 79, 'fever ', '1'),
(145, 60, 79, 'running nose', '1'),
(146, 61, 80, 'fever with chills', '1'),
(147, 61, 80, 'weakness', '1'),
(148, 62, 81, 'fever with chills', '2'),
(149, 62, 81, 'weakness', '2'),
(150, 62, 81, 'cough', '2'),
(151, 63, 82, 'fever ', '1'),
(152, 63, 82, 'running nose', '1'),
(153, 64, 83, 'fever with chills', '1'),
(154, 64, 83, 'running nose', '1'),
(155, 65, 84, 'headache ', '1'),
(156, 65, 84, 'dry cough', '1'),
(157, 66, 85, 'swelling and pain in rt eye', '1'),
(158, 67, 87, 'loose motion watery', '1'),
(159, 67, 87, 'abd pain', '1'),
(160, 67, 87, 'fever ', '1'),
(161, 68, 88, 'fever with chills', '1'),
(162, 68, 88, 'throat pain', '1'),
(163, 69, 89, 'cough eith exp', '1'),
(164, 69, 89, 'weakness', '1'),
(165, 70, 90, 'fever ', '1'),
(166, 70, 90, 'running nose', '1'),
(167, 70, 90, 'throat pain', '1'),
(169, 72, 92, 'lt leg pain', '2-3m'),
(170, 71, 91, 'fungal ', '3m'),
(172, 73, 93, 'running nose', '1'),
(173, 74, 1, 'Test', '1'),
(174, 75, 95, 'loose motion', '1'),
(175, 75, 95, 'abd pain', '1'),
(176, 75, 95, 'vomiting', '1'),
(177, 76, 96, 'fever', 'today night'),
(178, 76, 96, 'headache', 'today mrng'),
(179, 77, 97, 'fever ', '1'),
(180, 77, 97, 'running nose', '1'),
(181, 77, 97, 'throat pain', '1'),
(182, 78, 98, 'fever', '2'),
(183, 79, 99, 'fever with chills', '1'),
(184, 79, 99, 'headache++', '1'),
(185, 79, 99, 'cough+', '1'),
(186, 80, 101, 'headche', '1'),
(187, 81, 120, 'itching all over body', '6 month in and off'),
(188, 82, 119, 'abd pain', '2'),
(189, 83, 118, 'fever', '2'),
(190, 83, 118, 'running nose', '2'),
(191, 83, 118, 'throat pain', '2'),
(192, 84, 117, 'fever', '2'),
(193, 84, 117, 'running nose', '2'),
(194, 85, 116, 'loose motion 2times', '2'),
(195, 86, 121, 'running nose ', '2'),
(196, 86, 121, 'cough', '2'),
(197, 87, 122, 'fever ', '2'),
(198, 87, 122, 'cough with exp', '2'),
(199, 88, 123, 'fever ', '2'),
(200, 88, 123, 'throat pain', '2'),
(201, 88, 123, 'cough with exp', '2'),
(202, 89, 125, 'fever', '2'),
(203, 89, 125, 'running nose', '2'),
(204, 90, 127, 'abd pain', '2'),
(205, 90, 127, 'gaseous', '2'),
(206, 91, 129, 'abd pain', '2'),
(207, 91, 129, 'vomiting', '2'),
(208, 92, 126, 'ear pain', '2-3'),
(209, 93, 134, 'itching and rash in all over body', '2'),
(210, 94, 134, 'itching and rash in all over body', '2'),
(211, 95, 134, 'itching and rash in all over body', '2'),
(212, 96, 135, 'fever ', '3d'),
(213, 96, 135, 'throat pain', '3d'),
(214, 96, 135, 'running', '3d'),
(215, 97, 135, 'fever ', '3d'),
(216, 97, 135, 'throat pain', '3d'),
(217, 97, 135, 'running', '3d');

-- --------------------------------------------------------

--
-- Table structure for table `hp_patient_info_medicine_info`
--

CREATE TABLE `hp_patient_info_medicine_info` (
  `id` int(11) NOT NULL,
  `patient_registration_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `medicine_name` varchar(500) NOT NULL,
  `medicine_taken_process` varchar(100) NOT NULL,
  `medicine_af_bf` varchar(500) NOT NULL,
  `medicine_days` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hp_patient_info_medicine_info`
--

INSERT INTO `hp_patient_info_medicine_info` (`id`, `patient_registration_id`, `patient_id`, `medicine_name`, `medicine_taken_process`, `medicine_af_bf`, `medicine_days`) VALUES
(6, 1, 1, 'Adderall', '1-1-1', 'After Lunch', '5 Days'),
(7, 1, 1, 'Kevzara', '1-0-1', 'Before Lunch', '2 Days'),
(8, 1, 1, 'Otezla', '0-0-1', 'After Lunch', '1 Days'),
(9, 2, 4, 'tab azee 500', '0-1-0', 'before lunch', '5'),
(10, 2, 4, 'tab dolo 650', '1-1-1', 'after lunch', '5'),
(11, 2, 4, 'tab levocet m', '0-0-1', 'after lunch', '5'),
(12, 3, 6, 'p1/2 d f', '1-1-1', 'after food', '1'),
(13, 4, 7, 'pcm 650', '1-1-1', 'after food', '1'),
(14, 4, 7, 'cetrizine', '1-1-1', 'after food', '1'),
(15, 4, 7, 'stemetil', '1-1-1', 'after food', '1'),
(16, 4, 7, 'femo', '1-1-1', 'after food', '1'),
(17, 5, 8, 'troken 200mg', '0-0-1', 'after food', '10'),
(18, 5, 8, 'cpm 4', '0-0-1', 'after food', '10'),
(19, 5, 8, 'lulikent ointment for la', '1-0-1', 'LA', '10'),
(20, 6, 9, 'tab bandy plus', '0-0-1', 'after food', '1'),
(21, 6, 9, 'permate lotion fir la', '0-0-1', 'LA', '3'),
(22, 7, 10, 'syp tusken d 5ml', '1-1-1', 'after food', '5'),
(23, 7, 10, 'cet pre fm', '1-1-1', 'after food', '1'),
(24, 8, 11, 'pcm 400mg cet1/2 ', '1-1-1', 'after food', '1'),
(25, 9, 12, 'p650 cet pred fm', '1-1-1', 'after food', '1'),
(26, 9, 12, 'syp tusken d 5ml', '1-1-1', 'after food', '5'),
(27, 10, 13, 'p 375 crt 10 pre2', '1-1-1', 'after food', '1'),
(28, 11, 14, 'p650 cet pred fm', '1-1-1', 'after food', '1'),
(29, 12, 15, 'p650 cet pred fm', '1-1-1', 'after food', '1'),
(30, 13, 16, 'syp cital 10ml', '1-1-1', 'after food', '5'),
(31, 13, 16, 'norflox 400', '1-0-1', 'after food', '2'),
(38, 14, 17, 'norflox 400', '1-0-1', 'after food', '2'),
(39, 14, 17, 'rantac 150', '1-0-1', 'before food', '2'),
(42, 15, 24, 'Dolo 350', '1-1-1', 'Before/After', '2'),
(43, 15, 24, 'CPM', '1-1-1', 'Before/After', '2'),
(47, 16, 25, 'para+mef 250/100 6ml', '1-1-1', 'after food', '3'),
(48, 16, 25, 'syp augmentin 400 5ml', '1-0-1', 'after food', '5'),
(49, 16, 25, 'cet 5', '1-0-1', 'after food', '3'),
(50, 16, 25, 'syp alex d jr 5ml', '1-1-1', 'after food', '5'),
(51, 17, 27, 'pcm650,cpm,fm,dom', '1-1-1', 'after food', '2'),
(52, 18, 28, 'pcm650,cpm,fm,d', '1-1-1', 'after food', '1'),
(53, 18, 28, 'macro 250', '1-0-1', 'after food', '3'),
(54, 19, 30, 'P+M.CPM,FM,D', '1-1-1', 'AFTER FOOD', '1'),
(55, 19, 30, 'MACRO 250', '1-0-1', 'AFTER FOOD', '3'),
(57, 20, 32, 'PCM650,DOM,FM,EGEM', '1-1-1', 'AFTER FOOD', '1'),
(58, 20, 32, 'TAB VOMINEC DSR', '1-0-0', 'BEFORE FOOD', '10'),
(59, 21, 6, 'PM,CPM,FM,D', '1-1-1', 'ATFER FOOD', '1'),
(60, 21, 6, 'TAB MACRO 250', '1-0-1', 'ATFER FOOD', '3'),
(61, 22, 37, 'pcm650,cpm,fm,d', '1-1-1', 'after food', '1'),
(62, 22, 37, 'macro 250', '1-0-1', 'after food', '3'),
(63, 23, 38, 'pm,cpm,fm,d', '1-1-1', 'after food', '1'),
(64, 23, 38, 'doxy lb', '1-0-1', 'after food', '3'),
(65, 24, 39, '650,cpm,fm,d', '1-1-1', 'after food', '1'),
(66, 24, 39, 'macro 250', '1-0-1', 'after food', '3'),
(67, 25, 40, '650,cpm,fm,d', '1-1-1', 'after food', '1'),
(68, 25, 40, 'syp lupitoss 5ml', '1-1-1', 'after food', '3'),
(69, 25, 40, 'macro 250', '1-0-1', 'after food', '3'),
(70, 26, 46, 'pm,cpm,fm,d', '1-1-1', 'after food', '1'),
(71, 26, 46, 'doxy', '1-0-1', 'after food', '3'),
(72, 27, 47, '650,cpm,fm,d', '1-1-1', 'after food', '1'),
(73, 27, 47, 'macro250', '1-0-1', 'after food', '3'),
(74, 28, 45, 'dom,stem,fm', '1-1-1', 'after food', '1'),
(75, 28, 45, 'vertin 16', '1-0-1', 'after food', '2'),
(76, 29, 44, 'cyl,fm,dic', '1-1-1', 'after food', '1'),
(77, 30, 48, '650,cpm,fm,d', '1-1-1', 'after food', '1'),
(78, 30, 48, 'macro250', '1-0-1', 'after food', '3'),
(79, 31, 43, 'pd,dom,fm', '1-1-1', 'after food', '1'),
(80, 32, 42, 'cyl,fm,dom,gem', '1-1-1', 'after food', '1'),
(81, 33, 41, '.', '0-0-1', '.', '.'),
(82, 34, 49, 'pd,bc,fm', '1-1-1', 'after food', '1'),
(83, 35, 50, '650,cpm,fm,bc', '1-1-1', 'after food', '1'),
(84, 35, 50, 'macro250', '1-0-1', 'after food', '3'),
(85, 36, 51, '650,bc,fm', '1-1-1', 'after food', '1'),
(86, 37, 52, 'pm1/2,cet1/2,p1/2', '1-1-1', 'after food', '1'),
(87, 37, 52, 'tab fexinec 100', '1-0-1', 'after food', '5'),
(88, 38, 53, 'pm1/2,cet1/2,p1/2', '1-1-1', 'after food', '1'),
(89, 38, 53, 'tab fexinec 100', '1-0-1', 'after food', '5'),
(90, 39, 54, 'pdc,fm,soda', '1-1-1', 'after food', '1'),
(91, 40, 55, '650,bc,fm', '1-1-1', 'after food', '1'),
(92, 41, 56, 'bro,cpm,fm,d', '1-1-1', 'after food', '1'),
(93, 42, 57, 'pd,fm,soda,d', '1-1-1', 'after food', '1'),
(94, 43, 58, '650,cpm,fm,d', '1-1-1', 'after food', '1'),
(95, 44, 59, 'cyl,fm,cpm,gem', '1-1-1', 'after food', '1'),
(96, 44, 59, 'syp tusken d 5ml', '1-1-1', 'after food', '5'),
(97, 44, 59, 'tab vominec dsr', '1-0-1', 'before food', '5'),
(98, 45, 60, '650,cpm,fm,d', '1-1-1', 'after food', '1'),
(99, 45, 60, 'macro250', '1-0-1', 'after food', '3'),
(100, 46, 61, '650,cpm,fm,d', '1-1-1', 'after food', '1'),
(101, 46, 61, 'macro250', '1-0-1', 'after food', '3'),
(102, 47, 62, '650,dom,fm,gem', '1-1-1', 'after food', '1'),
(103, 47, 62, 'tab vominec dsr', '1-0-1', 'before food', '5'),
(104, 47, 62, 'syp laxikent', '0-0-1', 'hs 30ml', '30ml'),
(105, 48, 63, '650,stem,fm,dom', '1-1-1', 'after food', '1'),
(106, 49, 64, 'ib cet+p3 6ml', '1-1-1', 'after food', '2'),
(107, 49, 64, 'syp clavitoss200 5ml', '1-0-1', 'after food', '5'),
(108, 50, 66, '650,cpm,fm,d', '1-1-1', 'after food', '1'),
(109, 50, 66, 'tab macro 250', '1-0-1', 'after food', '3'),
(110, 51, 68, '650,cpm,fm,d', '1-1-1', 'after food', '1'),
(111, 52, 71, 'pm,cpm,cyl,fm', '1-1-1', 'after food', '1'),
(112, 53, 72, '650,cpm,fm,d', '1-1-1', 'after food', '1'),
(113, 54, 73, '500,bc,fm', '1-1-1', 'after food', '1'),
(114, 54, 73, 'tab frutivit', '1-0-0', 'after food', '3m'),
(115, 55, 74, 'tab primulte n ', '1-0-1', 'after food', '5'),
(116, 56, 75, '650,cpm,fm,d', '1-1-1', 'after food', '1'),
(117, 56, 75, 'tab fexinec 200', '1-0-1', 'after food', '5'),
(118, 57, 76, 'syp pm 5ml', '1-1-1', 'afte food', '3'),
(119, 58, 77, '650,dom,fm,cpm', '1-1-1', 'ahter food', '1'),
(120, 58, 77, 'tab macro 250', '1-0-1', 'after food', '3'),
(121, 59, 78, 'pd,bd,fm,soda', '1-1-1', 'after food', '1'),
(122, 59, 78, 'kenacort mouth gel', '1-1-1', 'la ', '5'),
(123, 60, 79, 'syp pm ', '1-1-1', '5ml', '2'),
(124, 60, 79, 'syp clavitoss ds', '1-0-1', '5ml', '3'),
(125, 61, 80, 'pm,cpm,fm,d', '1-1-1', 'after food', '1'),
(126, 61, 80, 'tab macro 250', '1-0-1', 'after food', '3'),
(127, 62, 81, 'pm,cet,deri,fm', '1-1-1', 'after food', '1'),
(128, 62, 81, 'tab fexinec 200', '1-0-1', 'after food', '5'),
(129, 62, 81, 'syp macbery', '1-1-1', '5ml', '5'),
(130, 63, 82, 'syp ib ', '1-1-1', '5ml', '2'),
(131, 63, 82, 'syp clavitoss 200', '1-0-1', '5ml', '5'),
(132, 63, 82, 'syp ascoril ls jr', '1-1-1', '3ml', '3'),
(133, 64, 83, 'syp pm', '1-1-1', '4ml', 'sos'),
(134, 64, 83, 'syp clavitoss200', '1-0-1', '6ml', '5'),
(135, 65, 84, '650,dom,fm,cpm', '1-1-1', 'after food', '1'),
(136, 65, 84, 'syp tusken d ', '1-1-1', '5ml', '3'),
(137, 66, 85, 'syp ib', '1-1-1', '7.5ml', '1'),
(138, 66, 85, 'syp clavitoss200', '1-0-1', '7.5ml', '5'),
(139, 66, 85, 'genta e/d', '1-1-1', '2 drop', '5'),
(140, 67, 87, 'nor,lo,cyl,fm', '1-0-1', 'after food', '1'),
(141, 68, 88, 'pm,cpm,fm,d', '1-1-1', 'after food', '1'),
(142, 68, 88, 'tab macro 250', '1-0-1', 'after food', '3'),
(143, 69, 89, '650,cet,bro,fm', '1-1-1', 'after food', '1'),
(144, 69, 89, 'syp tusken am ', '1-1-1', '5ml', '5'),
(145, 70, 90, '650,cet,p,fm', '1-1-1', 'after food', '1'),
(148, 72, 92, 'pd,cpm,fm', '1-1-1', 'after food', '1'),
(149, 72, 92, 'karb d3', '0-1-0', 'after food', '3m'),
(150, 71, 91, 'troken 200', '0-0-1', 'after food', '10'),
(151, 71, 91, 'lullikebt', '1-0-1', 'la', '10'),
(153, 73, 93, 'syp ib+cet+p', '1-1-1', '7ml', '2'),
(154, 74, 1, 'dolo', '1-1-1', '1', '1'),
(155, 75, 95, 'nor 1/2,cyl,fm,ond', '1-1-1', 'after food', '1'),
(156, 76, 96, '', '', '', ''),
(157, 77, 97, '650,cpm,fm,d', '1-1-1', 'after food', '1'),
(158, 77, 97, 'doxy lb', '1-0-1', 'after food', '5'),
(159, 78, 98, 'syp calpol 120 ', '1-1-1', '5ml after food', '3'),
(160, 78, 98, 'syp s doxim 50', '1-0-1', '5ml after food', '5'),
(161, 79, 99, 'p+m,cpm,fm,d', '1-1-1', 'after food', '1'),
(162, 79, 99, 'doxy lb', '1-0-1', 'after food', '5'),
(163, 80, 101, '650,dom,fm', '1-1-1', 'after food', '1'),
(164, 81, 120, 'cet,pred,fm', '1-0-1', 'after food', '2d'),
(165, 82, 119, 'cyl dom', '1-0-1', 'after food', '2'),
(166, 83, 118, '650,cpm,fm,d', '1-1-1', 'after food', '1'),
(167, 83, 118, 'macro 250', '1-0-1', 'after food', '3'),
(168, 84, 117, 'ib 2 cet', '1-1-1', ' 3.5ml ', '2 days'),
(169, 84, 117, 'clavitoss 228', '1-0-1', '3.5ml', '5 days'),
(170, 85, 116, 'immunogermina repsule', '1-0-1', '1/2', '2'),
(171, 85, 116, 'enuff', '1-0-1', 'stop after lotion stop', '2'),
(172, 86, 121, 'cet fm,p', '1-0-1', 'after food', '2'),
(173, 87, 122, 'Ib 2 cet', '1-1-1', '7.5 ml', '2'),
(174, 87, 122, 'syp clavitoss 228.5', '1-0-1', '7.5 ml', '5'),
(175, 87, 122, 'macberry jr', '1-1-1', '5ml', '5'),
(176, 88, 123, '650,cpm,fm,d', '1-1-1', 'after food', '1'),
(177, 88, 123, 'tab azee 500', '0-1-0', 'after food', '3'),
(178, 88, 123, 'syp ascoril ls 5ml', '1-1-1', 'after food', '3'),
(179, 89, 125, 'p+mcpm,fm,d', '1-1-1', 'after food', '1'),
(180, 90, 127, 'cyl,fm,engem', '1-1-1', 'after food', '1'),
(181, 91, 129, 'nor200,cyl,fm,ond', '1-1-1', 'after food', '2'),
(182, 92, 126, 'Ib 200,cet5,fm', '1-0-1', 'after food', '2'),
(183, 92, 126, 'drep ear drop 2 drop', '1-1-1', 'after food', '3'),
(184, 93, 134, 'cet,pred,fm', '1-0-1', 'after food', '2'),
(185, 94, 134, 'cet,pred,fm', '1-0-1', 'after food', '2'),
(186, 95, 134, 'cet,pred,fm', '1-0-1', 'after food', '2'),
(187, 96, 135, 'p+m,cet1/2,p1/2', '1-1-1', 'after food', '2d'),
(188, 97, 135, 'p+m,cet1/2,p1/2', '1-1-1', 'after food', '2d');

-- --------------------------------------------------------

--
-- Table structure for table `hp_patient_registration`
--

CREATE TABLE `hp_patient_registration` (
  `id` int(11) NOT NULL,
  `patient_name` varchar(500) NOT NULL,
  `patient_age` varchar(200) NOT NULL,
  `sex` varchar(20) NOT NULL,
  `contact_no` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `lmp` varchar(500) NOT NULL,
  `registration_date` date NOT NULL,
  `registration_time` time NOT NULL,
  `temperature` varchar(100) NOT NULL,
  `p` varchar(100) NOT NULL,
  `bp` varchar(100) NOT NULL,
  `sp02` varchar(100) NOT NULL,
  `rs` varchar(100) NOT NULL,
  `cvs` varchar(100) NOT NULL,
  `cns` varchar(100) NOT NULL,
  `pa` varchar(100) NOT NULL,
  `htn` varchar(100) NOT NULL,
  `dm` varchar(100) NOT NULL,
  `thyroad` varchar(100) NOT NULL,
  `other_description` text NOT NULL,
  `created_by` int(11) NOT NULL DEFAULT '0',
  `created_time` bigint(17) NOT NULL DEFAULT '0',
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `updated_time` varchar(50) NOT NULL DEFAULT '0',
  `deleted_by` int(11) NOT NULL DEFAULT '0',
  `deleted_time` bigint(17) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hp_patient_registration`
--

INSERT INTO `hp_patient_registration` (`id`, `patient_name`, `patient_age`, `sex`, `contact_no`, `address`, `lmp`, `registration_date`, `registration_time`, `temperature`, `p`, `bp`, `sp02`, `rs`, `cvs`, `cns`, `pa`, `htn`, `dm`, `thyroad`, `other_description`, `created_by`, `created_time`, `updated_by`, `updated_time`, `deleted_by`, `deleted_time`) VALUES
(1, '1', '29 Years', '', '9920121080', '  Bhayander (East)', 'none', '2021-03-17', '16:15:00', '98.6F', '88/min', '88', '98%', 'AB', 'SIS2', 'Alert', 'Soft', 'AB', 'SIS2', 'Alert', 'None', 1, 1615977929261, 1, '1615978698337', 0, 0),
(2, '4', '35', '', '9699500008', 'laxamn nagar', '', '2021-10-07', '00:00:00', '100.8F', '120/min', '120\\80mmhg', '98%', 'clear', 's1s2 n', 'Alert', 'Soft', 'nill', 'nill', 'nill', '', 1, 1633621361466, 0, '0', 0, 0),
(3, '6', '40', '', '7096050440', 'laxman nagar malad east', '', '2021-11-22', '00:00:00', 'afeb', '88/min', '150/80', '98%', 'AB clear', 'SIS2 n', 'Alert', 'Soft', 'nill', 'nill', 'nill', 'nill', 1, 1637589981834, 0, '0', 0, 0),
(4, '7', '16', '', '8691894708', 'laxman nagar malad east', '', '2021-11-22', '00:00:00', '98.6F', '76/min', '120/82', '98%', 'AB clear', 'SIS2 n', 'Alert', 'Soft', 'nill', 'nill', 'nill', 'nill', 1, 1637590948194, 0, '0', 0, 0),
(5, '8', '45', '', '8149195523', 'virar', '', '2021-11-22', '00:00:00', '98.6F', '88/min', '126/80', '98%', 'AB clear', 'SIS2 n', 'Alert', 'Soft', 'AB', 'SIS2', 'Alert', '?peacemaker', 1, 1637592598753, 0, '0', 0, 0),
(6, '9', '19', '', '9608642972', 'laxman nagar', '', '2021-11-22', '00:00:00', '98.6F', '88/min', '120/80', '98%', 'AB claer', 'SIS2 n', 'Alert', 'Soft', 'n', 'n', 'n', '', 1, 1637595502300, 0, '0', 0, 0),
(7, '10', '14', '', '9664906518', 'laxman nagar', '18/11/2021', '2021-11-22', '00:00:00', '98.6F', '80/min', '120/80', '98%', 'AEBE clear', 'SIS2 n', 'Alert', 'Soft', 'n', 'n', 'n', '', 1, 1637596570707, 0, '0', 0, 0),
(8, '11', '13', '', '9322774917', 'laxman nagar', '', '2021-11-22', '00:00:00', '97.7F', '82/min', '', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', 'n', 'n', 'n', '', 1, 1637596982461, 0, '0', 0, 0),
(9, '12', '17', '', '9867277518', 'laxman nagar', '', '2021-11-22', '00:00:00', '98.6F', '78/min', '120/80', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '', '', '', '', 1, 1637597433212, 0, '0', 0, 0),
(10, '13', '10', '', '9594523361', 'laxman nagar', '', '2021-11-22', '00:00:00', '98.6F', '80/min', '', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', 'n', 'n', 'n', '', 1, 1637597840686, 0, '0', 0, 0),
(11, '14', '29', '', '7045788719', 'laxmqan nagar', '1/11/21', '2021-11-22', '00:00:00', '98.6F', '82/min', '122/80', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', 'n', 'n', 'n', '', 1, 1637598759399, 0, '0', 0, 0),
(12, '15', '40', '', '7977507713', 'laxman nagar', '07/11/21', '2021-11-22', '00:00:00', '98.6F', '80/min', '120/80', '98%', 'clear ', 'SIS2 n', 'Alert', 'Soft', 'n', 'n', 'n', 'hot water fomentatiobn', 1, 1637600345047, 0, '0', 0, 0),
(13, '16', '25', '', '8108233428', 'laxman nagar', '', '2021-11-23', '00:00:00', '98.6F', '80/min', '120/80', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', 'n', 'n', 'n', '', 1, 1637644268190, 0, '0', 0, 0),
(14, '17', '34', 'female', '9768007065', '   laxman nagar', '05/11/21', '2021-11-23', '00:00:00', '100.6F', '110/min', '90/66 mmhg', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', 'n', 'n', 'n', 's/h/o famliy planing', 1, 1637650191336, 1, '1660132543780', 0, 0),
(15, '24', '29', 'male', '8291253574', 'Kasam Bag', '', '2022-08-15', '21:22:00', '98.8F', '88/min', '88', '98%', 'AB', 'SIS2', 'Alert', 'Soft', 'AB', 'SIS2', 'Alert', '', 1, 1660578976853, 1, '1660579036913', 0, 0),
(16, '25', '8', 'male', '7083555689', ' laxman nagar', '', '2022-08-16', '00:00:00', '98.6F', '88/min', '-', '98%', 'Aebe clear', 'SIS2 n ', 'Alert', 'Soft', '-', '-', '-', 'enlarge tonsil', 1, 1660630270315, 1, '1660630505928', 0, 0),
(17, '27', '38', 'female', '8454833101', 'laxman nagr', '25/07/2022', '2022-08-16', '00:00:00', '98.6F', '86/min', '130/80mmhg', '98%', 'Aebe clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', '', 1, 1660633152619, 0, '0', 0, 0),
(18, '28', '32', 'female', '916267987', 'laxman nagar', '28/07/2022', '2022-08-16', '00:00:00', '99.8F', '102/min', '110/70mmhg', '98%', 'AEBE clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', 'enlarge tonsil', 1, 1660655033749, 0, '0', 0, 0),
(19, '30', '21', 'male', '9351655317', 'LAXMAN NAGAR', '', '2022-08-19', '00:00:00', '102.0F', '110/min', '110/80MMHG', '98%', 'AEBE CLEAR', 'SIS2 N', 'Alert', 'Soft', '-', '-', '-', 'CONGESTED TONSIL', 1, 1660885656527, 0, '0', 0, 0),
(20, '32', '36', 'female', '8291494946', ' LAXMAN NAGAR', '3/08/2022', '2022-08-19', '00:00:00', '98.6F', '88/min', '100/70', '98%', 'AEBE CLEAR', 'SIS2 N', 'Alert', 'Soft', '-', '-', 'IRREGULAR T/T', '', 1, 1660912828842, 1, '1660912918207', 0, 0),
(21, '6', '40', 'male', '7096050440', 'laxman nagar malad east', '', '2022-08-19', '00:00:00', '100.6F', '110/min', '110/70MMHG', '98%', 'AEBE CLEAR', 'SIS2 N ', 'Alert', 'Soft', '-', '-', '-', 'INJ PCM 150MG 0.5ML IM STAT @ RT VG SITE', 1, 1660914259997, 0, '0', 0, 0),
(22, '37', '31', 'female', '8657154820', 'datta mandir', '10/08/2022', '2022-08-19', '00:00:00', '98.6F', '88/min', '110/80mmhg', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', 'congested tonsil', 1, 1660920117812, 0, '0', 0, 0),
(23, '38', '24', 'female', '7738308795', 'jagu sohanji chawl vaisht  pada np 02', '26/07/2022', '2022-08-19', '00:00:00', '99.6F', '98/min', '98/66mmhg', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', 'dengue positive cbc 12.7/3960/154000', 1, 1660921118624, 0, '0', 0, 0),
(24, '39', '22', 'female', '9004817737', 'laxman nagar', '20/07/2022', '2022-08-19', '00:00:00', '98.6F', '82/min', '110/70mmhg', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', 'enlarge tonsil', 1, 1660921867817, 0, '0', 0, 0),
(25, '40', '55', 'female', '7208953410', 'laxman nagare', '-', '2022-08-19', '00:00:00', '98.6F', '102/min', '176/90mmhg', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', 'on rx', '-', '-', 'adv take htn medicine', 1, 1660924153170, 0, '0', 0, 0),
(26, '46', '21', 'female', '9676094235', 'laxman nagar', '20/07/2022', '2022-08-19', '00:00:00', '99.4F', '98/min', '110/70mmhg', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', '', 1, 1660926780297, 0, '0', 0, 0),
(27, '47', '38 years', 'female', '9867105399', 'laxman nagar', '27/07/2022', '2022-08-19', '00:00:00', '98.6F', '86/min', '110/70mmhg', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', 'congested pharynx', 1, 1660927300556, 0, '0', 0, 0),
(28, '45', '33 years', 'female', '9136535501', 'laxman nagar', '27/07/2022', '2022-08-19', '00:00:00', '98.6F', '88/min', '96/66mmhg', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', 'no neck stiffness\r\nphysicine ref', 1, 1660927630500, 0, '0', 0, 0),
(29, '44', '17', 'female', '9930156450', 'laxman nagar', '18/08/2022', '2022-08-19', '00:00:00', '98.6F', '88/min', '88', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', 'h/o heavy object lifting', 1, 1660927903775, 0, '0', 0, 0),
(30, '48', '21 years', 'male', '8691891012', 'laxman nagar', '', '2022-08-19', '00:00:00', '98.6F', '88/min', '120/80mmhg', '98%', 'clear ', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', 'pharynx congestion', 1, 1660928368508, 0, '0', 0, 0),
(31, '43', '21', 'female', '7039624701', 'laxman nagar', '28/07/2022', '2022-08-19', '00:00:00', '98.6F', '72/min', '100/68mmhg', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', '', 1, 1660928482455, 0, '0', 0, 0),
(32, '42', '24', 'female', '8850119799', 'omkar ', '15/08/2022', '2022-08-19', '00:00:00', '98.6F', '88/min', '120/70mmhg', '98%', 'clear', 'SIS2 n', 'Alert', 'rt hypochondria tender+', '-', '-', '-', 'adv usg abd+pelvis', 1, 1660928800361, 0, '0', 0, 0),
(33, '41', '27', 'female', '9082194614', 'goregaon west', '22/07/2022', '2022-08-19', '00:00:00', '98.6F', '88/min', '96/68mmhg', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', 'advice rest for 5 days\r\nct previous day', 1, 1660928939831, 0, '0', 0, 0),
(34, '49', '18 years', 'male', '9565862472', 'laxman nagar', '', '2022-08-19', '00:00:00', '98.6F', '88/min', '120/80mmhg', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', 'wound is healing', 1, 1660929690736, 0, '0', 0, 0),
(35, '50', '40y', 'male', '9322071850', 'laxman nagar', '', '2022-08-20', '00:00:00', '101.4F', '104/min', '120/70mmhg', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', 'on rx', '-', 'phaynx congestion', 1, 1660971155846, 0, '0', 0, 0),
(36, '51', '72y', 'female', '0000000000', 'laxman nagar', 'stop', '2022-08-20', '00:00:00', '98.6F', '88/min', '120/70mmhg', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', 'on rx', 'on rx', '-', '', 1, 1660972685505, 0, '0', 0, 0),
(37, '52', '10y', 'female', '9619627040', 'laxman nagar', '-', '2022-08-20', '00:00:00', '98.6F', '88/min', '-', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', 'congested tonsil', 1, 1660973939681, 0, '0', 0, 0),
(38, '53', '10y', 'male', '9819643230', 'laxman nagar', '-', '2022-08-20', '00:00:00', '101.9F', '118/min', '110/70mmhg', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', 'adv blood test \r\n?dengue fever', 1, 1660974993461, 0, '0', 0, 0),
(39, '54', '60y', 'female', '8879365295', 'laxman nagar', 'stop', '2022-08-20', '00:00:00', '98.6F', '88/min', '120/80mmhg', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', 'muscle spasm', 1, 1660977015595, 0, '0', 0, 0),
(40, '55', '27y', 'male', '9967568525', 'laxman nagar', '-', '2022-08-20', '00:00:00', '98.6F', '88/min', '100/70mmhg', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', 'adv cbc\r\ndengue fever', 1, 1660977393644, 0, '0', 0, 0),
(41, '56', '43y', 'male', '9769488408', 'laxman nagar', '', '2022-08-20', '00:00:00', '98.6F', '88/min', '110/70mmhg', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', '', 1, 1660977695703, 0, '0', 0, 0),
(42, '57', '39y', 'female', '9619555796', 'laxman nagar\r\n', '14/06/2022', '2022-08-20', '00:00:00', '98.6F', '88/min', '120/70mmhg', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', 'on rx ', 'h/o d&c on 14/06/2022\r\nadv ortho ref', 1, 1660978349263, 0, '0', 0, 0),
(43, '58', '19y', 'female', '9619643556', 'usman chwl,laxman nagar', '28/07/2022', '2022-08-20', '13:37:00', '98.6F', '88/min', '110/70mmhg', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', 'congested throat', 1, 1660983076892, 0, '0', 0, 0),
(44, '59', '25y', 'male', '9064532318', 'laxman nagar', '-', '2022-08-20', '00:00:00', '98.6F', '88/min', '120/76mmhg', '98%', 'clear', 'SIS2', 'Alert', 'Soft,gaseous', '-', '-', '-', 'adv usg abd pelvis', 1, 1660984133836, 0, '0', 0, 0),
(45, '60', '35y', 'female', '00000000', 'laxman nagar', '15/08/2022', '2022-08-20', '00:00:00', '99.6F', '102/min', '110/70mmhg', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', '', 1, 1661000344574, 0, '0', 0, 0),
(46, '61', '25y', 'female', '8424829286', 'laxman nagar', '16/08/2022', '2022-08-20', '00:00:00', '98.6F', '88/min', '110/70mmhg', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', 'congested tonsil', 1, 1661000472408, 0, '0', 0, 0),
(47, '62', '18y', 'female', '9152712949', 'laxman nagar', '15/08/2022', '2022-08-20', '00:00:00', '98.6F', '88/min', '100/90mmhg', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', 'postural hypotension', 1, 1661000676914, 0, '0', 0, 0),
(48, '63', '40y', 'male', '7738426639', 'laxman nagar', '-', '2022-08-20', '00:00:00', '98.6F', '88/min', '120/80mmhg', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft,gaseous', '-', '-', '-', 'no neck stiffness\r\npupil reactive to light', 1, 1661000841973, 0, '0', 0, 0),
(49, '64', '4y', 'female', '9769351944', 'vasai west', '-', '2022-08-20', '00:00:00', '100.0F', '88/min', '-', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', 'enlarge tonsil', 1, 1661001705525, 0, '0', 0, 0),
(50, '66', '23y', 'male', '7718956598', 'laxman nagar', '-', '2022-08-22', '00:00:00', '99.0F', '98/min', '120/80mmhg', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', 'congested throat', 1, 1661143385825, 0, '0', 0, 0),
(51, '68', '25y', 'male', '8459108366', 'laxman nagar', '-', '2022-08-22', '00:00:00', '98.6F', '88/min', '110/70mmhg', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', 'congested throat', 1, 1661146149731, 0, '0', 0, 0),
(52, '71', '21y', 'male', '9372195203', 'laxman nagar', '-', '2022-08-22', '00:00:00', '100.6F', '108/min', '120/80mmhg', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', 'adv blood test', 1, 1661150106599, 0, '0', 0, 0),
(53, '72', '49y', 'male', '9987219264', 'laxman nagar subedar chwl', '-', '2022-08-22', '00:00:00', '98.6F', '88/min', '120/80mmhg', '98%', 'clear', 'SIS2', 'Alert', 'Soft', '-', '-', '-', 'congested throat', 1, 1661152872837, 0, '0', 0, 0),
(54, '73', '13y', 'female', '9819387720', 'laxman nagar', '-', '2022-08-22', '00:00:00', '98.6F', '88/min', '120/70mmhg', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', '', 1, 1661153402853, 0, '0', 0, 0),
(55, '74', '19y', 'female', '8879013858', 'laxman nagar', '07/06/2022', '2022-08-22', '00:00:00', '98.6F', '88/min', '110/70mmhg', '98%', 'clear', 'n', 'conc', 'Soft', '-', '-', '-', '', 1, 1661172764669, 0, '0', 0, 0),
(56, '75', '29y', 'female', '9930863118', 'laxman nagar', '13/08/2022', '2022-08-22', '00:00:00', '98.6F', '88/min', '110/70mmhg', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', '', 1, 1661175063975, 0, '0', 0, 0),
(57, '76', '9y', 'female', '882838325', 'laxman nagar', '-', '2022-08-22', '00:00:00', '98.6F', '88/min', '-', '98%', 'clear', 'SIS2', 'Alert', 'Soft', '-', '-', '-', '', 1, 1661176995249, 0, '0', 0, 0),
(58, '77', '28y', 'male', '7990199038', 'laxman nagar', '-', '2022-08-22', '00:00:00', '99.6F', '98/min', '110/70mmhg', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', 'congested throat', 1, 1661177384160, 0, '0', 0, 0),
(59, '78', '42y', 'female', '9892663061', 'laxman nagar', '', '2022-08-22', '00:00:00', '98.6F', '88/min', '110/70mmh', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', '', 1, 1661177689077, 0, '0', 0, 0),
(60, '79', '7y', 'female', '8291134449', 'laxman nagar', '-', '2022-08-22', '00:00:00', '98.6F', '88/min', '-', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', 'congested throat', 1, 1661178049896, 0, '0', 0, 0),
(61, '80', '35y', 'male', '8291134449', 'laxman nagar', '-', '2022-08-22', '00:00:00', '100.6F', '102/min', '98/66mmhg', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', '', 1, 1661178434896, 0, '0', 0, 0),
(62, '81', '38y', 'male', '8356982817', 'laxman nagar', '-', '2022-08-22', '00:00:00', '99.6F', '98/min', '110/70mmhg', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', '', 1, 1661178824844, 0, '0', 0, 0),
(63, '82', '2y', 'male', '9326925977', 'laxman nagar', '-', '2022-08-22', '00:00:00', '99.9F', '112/min', '-', '98%', 'clear ', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', '', 1, 1661179289677, 0, '0', 0, 0),
(64, '83', '5y', 'female', '9867144774', 'laxman nagar', '-', '2022-08-22', '00:00:00', '101.1F', '118/min', '-', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', '', 1, 1661180384424, 0, '0', 0, 0),
(65, '84', '34y', 'female', '7302584009', 'laxman nagar', '16/08/2022', '2022-08-23', '00:00:00', '98.6F', '88/min', '140/80mmhg', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', 'adv alt day bp check up', 1, 1661231197204, 0, '0', 0, 0),
(66, '85', '5y', 'female', '9892602146', 'laxman nagar', '-', '2022-08-23', '00:00:00', '98.6F', '88/min', '-', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', 'hot fomentation', 1, 1661231514402, 0, '0', 0, 0),
(67, '87', '15y', 'female', '7304850547', 'laxman nagar\r\n', '22/08/2022', '2022-08-24', '00:00:00', '98.6F', '88/min', '110/70mmhg', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', '', 1, 1661316564537, 0, '0', 0, 0),
(68, '88', '19y', 'male', '7738464738', 'laxman nagar', '-', '2022-08-24', '00:00:00', '100.6F', '18/min', '120/70mmhg', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', 'congested throat', 1, 1661317179363, 0, '0', 0, 0),
(69, '89', '55y', 'male', '9768674528', 'laxman nagar shirangan soc.', '-', '2022-08-24', '00:00:00', '98.6F', '88/min', '110/70mmhg', '98%', 'congestion', 'SIS2 n', 'Alert', 'Soft', 'on rx', '-', '-', '', 1, 1661345251821, 0, '0', 0, 0),
(70, '90', '13y', 'male', '7039209081', 'laxman nagar', '-', '2022-08-24', '00:00:00', '100.6F', '112bpm', '110/70mmhg', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', 'enlarge tonsil', 1, 1661346931772, 0, '0', 0, 0),
(71, '91', '52y', 'male', '9702673805', ' laxman nagar', '-', '2022-08-26', '00:00:00', '98.6F', '88/min', '130/80mmhg', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', 'on rx ', 'on rx', '-', 'cabg done 17/06/2022', 1, 1661491058586, 1, '1661491935340', 0, 0),
(72, '92', '32y', 'female', '8623837365', 'laxman nagar', '25/07/2022', '2022-08-26', '00:00:00', '98.6F', '88/min', '120/80mmhg', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', 'varicose vien?\r\nadv stocking', 1, 1661491800061, 0, '0', 0, 0),
(73, '93', '4y', 'female', '9322071850', ' laxman nagar', '-', '2022-08-26', '00:00:00', '98.6F', '88/min', '-', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', '', 1, 1661493341021, 1, '1687677637478', 0, 0),
(74, '1', '29 Years', 'male', '9920121080', 'Bhayander (East)', 'Test', '2023-06-25', '13:54:00', '98.6', '88/min', '140/90mmhg', '98%', 'AB', 'SIS2', 'Alert', 'Soft', 'AB', 'SIS2', 'Alert', 'Testst', 1, 1687678012407, 0, '0', 0, 0),
(75, '95', '16', 'female', '9594443248', 'laxman nagar', '', '2023-07-10', '12:12:00', '98.6', '88/min', '110/70mmhg', '98%', 'AB clear', 'SIS2 n', 'Alert', 'Soft nt', '-', '-', '-', '', 1, 1688971480785, 0, '0', 0, 0),
(76, '96', '45y', 'female', '9594443248', 'laxman nagar', '', '2023-07-10', '12:14:00', '98.6', '88/min', '100/70mmhg', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', '', 1, 1688971581944, 0, '0', 0, 0),
(77, '97', '30y', 'female', '0000000000', 'laxman nagar', '08/07', '2023-07-10', '12:29:00', '98.6', '88/min', '110/80mmhg', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '--', '-', 'throat congested', 1, 1688972552692, 0, '0', 0, 0),
(78, '98', '1y wt 10kg', 'female', '9702360704', 'kandivali east', '', '2023-07-10', '12:54:00', '98.6', '99/min', '-', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', '', 1, 1688974148685, 0, '0', 0, 0),
(79, '99', '38', 'male', '9702531635', 'mourya chawl , guru krupa society , laxman nagar kurar village \r\nmalad east mumbai - 400 097', '', '2023-07-10', '18:37:00', '100.5f', '98/min', '110/70mmhg', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', 'inj pcm 0.5 im with aap @ rt vg site\r\n?viral fever\r\n? malaria', 1, 1688994658656, 0, '0', 0, 0),
(80, '101', '48y', 'female', '0000000000', 'laxman nagar', '', '2023-07-10', '19:02:00', '98.6', '88/min', '130/90mmhg', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', 'on rx', '-', '-', '', 1, 1688996032772, 0, '0', 0, 0),
(81, '120', '38y', 'female', '9967510508', 'stove chwl kurar village', '', '2023-07-10', '21:41:00', '98.6', '88/min', '120/80mmhg', '98%', 'Clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', 'adv sr ige', 1, 1689005777669, 0, '0', 0, 0),
(82, '119', '10y', 'female', '7977507713', 'kanta jhadav chwl laxman ngr', '', '2023-07-10', '21:46:00', '98.6', '88/min', '-', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', '', 1, 1689007844751, 0, '0', 0, 0),
(83, '118', '26y', 'female', '8424811858', 'pn gupta chwl laxman ngr', '', '2023-07-10', '22:28:00', '98.6', '88/min', '120/70mmhg', '98%', 'clear', 'SIS2n', 'Alert', 'Soft', '-', '-', '-', 'throat congested', 1, 1689008548518, 0, '0', 0, 0),
(84, '117', '1y 7kg', 'male', '8424811858', 'pn gupta chwl laxman ngr', '', '2023-07-10', '00:00:00', '98.6', '98/min', '-', '98%', 'congested', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', '', 1, 1689008988471, 0, '0', 0, 0),
(85, '116', '7m 7kg', 'male', '8356044305', 'laxman ngr', '', '2023-07-10', '22:40:00', '98.6', '-', '-', '-', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', 'no dehydration', 1, 1689009161255, 0, '0', 0, 0),
(86, '121', '13y', 'male', '7738428525', 'sardar mast chawl,laxxman nagr', '', '2023-07-10', '22:45:00', '98.6', '88/min', '110/80mmhg', '98%', 'clear', 'SIS2', 'Alert', 'Soft', '-', '-', '-', 'congested throat', 1, 1689009506641, 0, '0', 0, 0),
(87, '122', '8y 14kg', 'female', '9321018147', 'ramesh hotel lucky dairy', '', '2023-07-11', '10:09:00', '98.6', '88/min', '-', '98%', 'congestion', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', 'inflammed tonsil', 1, 1689050480162, 0, '0', 0, 0),
(88, '123', '21y', 'male', '9769994539', 'bst dindoshi', '', '2023-07-11', '10:24:00', '98.6', '88/min', '120/80mmhg', '98%', 'congestion', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', 'throat congesetd', 1, 1689051435140, 0, '0', 0, 0),
(89, '125', '42y', 'male', '7208984540', 'ganesh wadi laxman nagar', '', '2023-07-11', '11:12:00', '98.6', '88/min', '110/80mmhg', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', 'congested throat', 1, 1689054336423, 0, '0', 0, 0),
(90, '127', '40y', 'female', '8850181912', 'master chwl laxman nagar', '21/06', '2023-07-11', '11:41:00', '98.6', '88/min', '110/80mmhg', '98%', 'clear', 'SIS2', 'Alert', 'Soft', '-', '-', '-', '', 1, 1689055992028, 0, '0', 0, 0),
(91, '129', '51y', 'female', '9076325244', 'master chawl laxman nagar', 'menopause', '2023-07-11', '11:55:00', '98.6', '88/min', '110/70mmhg', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft,tender', '-', '-', '-', '', 1, 1689056855822, 0, '0', 0, 0),
(92, '126', '10y', 'female', '9769099290', 'devkar chwl laxman nagar', '-', '2023-07-11', '11:58:00', '98.6', '88/min', '-', '98%', 'clear', 'SIS2 n', 'Alert', 'Soft', '-', '-', '-', 'ear- ?perforated tm\r\nadv ent ref', 1, 1689057042955, 0, '0', 0, 0),
(93, '134', '39y', 'male', '9594682750', 'laxman nagar', '', '2023-07-17', '18:44:00', 'afeb', '88', '110/70', '98%', '', 'SIS1 normal', 'concious', 'soft', '', '', '', 'avoid oily and non veg', 1, 1689599796730, 0, '0', 0, 0),
(94, '134', '39y', 'male', '9594682750', 'laxman nagar', '', '2023-07-17', '18:44:00', 'afeb', '88', '110/70', '98%', '', 'SIS1 normal', 'concious', 'soft', '', '', '', 'avoid oily and non veg', 1, 1689599808975, 0, '0', 0, 0),
(95, '134', '39y', 'male', '9594682750', 'laxman nagar', '', '2023-07-17', '18:44:00', 'afeb', '88', '110/70', '98%', '', 'SIS1 normal', 'concious', 'soft', '', '', '', 'avoid oily and non veg', 1, 1689599814530, 0, '0', 0, 0),
(96, '135', '9y wt 24kg', 'female', '0000000000', 'laxman nagar', '', '2023-07-25', '19:18:00', '101', '112', '-', '98%', '', 'SIS1 normal', 'concious', 'soft', '', '', '', '', 1, 1690293007723, 0, '0', 0, 0),
(97, '135', '9y wt 24kg', 'female', '0000000000', 'laxman nagar', '', '2023-07-25', '19:18:00', '101', '112', '-', '98%', '', 'SIS1 normal', 'concious', 'soft', '', '', '', '', 1, 1690293314160, 0, '0', 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contactus`
--
ALTER TABLE `contactus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hp_admin`
--
ALTER TABLE `hp_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hp_disease_master`
--
ALTER TABLE `hp_disease_master`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hp_medicine_master`
--
ALTER TABLE `hp_medicine_master`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hp_patient_info`
--
ALTER TABLE `hp_patient_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hp_patient_info_case_paper`
--
ALTER TABLE `hp_patient_info_case_paper`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hp_patient_info_medicine_info`
--
ALTER TABLE `hp_patient_info_medicine_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hp_patient_registration`
--
ALTER TABLE `hp_patient_registration`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contactus`
--
ALTER TABLE `contactus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hp_admin`
--
ALTER TABLE `hp_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hp_disease_master`
--
ALTER TABLE `hp_disease_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `hp_medicine_master`
--
ALTER TABLE `hp_medicine_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hp_patient_info`
--
ALTER TABLE `hp_patient_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

--
-- AUTO_INCREMENT for table `hp_patient_info_case_paper`
--
ALTER TABLE `hp_patient_info_case_paper`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=218;

--
-- AUTO_INCREMENT for table `hp_patient_info_medicine_info`
--
ALTER TABLE `hp_patient_info_medicine_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=189;

--
-- AUTO_INCREMENT for table `hp_patient_registration`
--
ALTER TABLE `hp_patient_registration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
