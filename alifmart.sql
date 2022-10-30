-- MySQL dump 10.13  Distrib 5.5.45, for Linux (x86_64)
--
-- Host: localhost    Database: alifmart
-- ------------------------------------------------------
-- Server version	5.5.45-cll-lve

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `banner_mast`
--

DROP TABLE IF EXISTS `banner_mast`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `banner_mast` (
  `banner_id` int(11) NOT NULL AUTO_INCREMENT,
  `banner_alt_text` varchar(50) DEFAULT NULL,
  `banner_img` longblob,
  `banner_sort` float DEFAULT NULL,
  `banner_status` tinyint(4) DEFAULT NULL,
  `banner_target_url` varchar(100) DEFAULT NULL,
  `banner_target_window` varchar(10) DEFAULT NULL,
  `banner_position` varchar(2) DEFAULT NULL,
  `banner_img_cp` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`banner_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banner_mast`
--

LOCK TABLES `banner_mast` WRITE;
/*!40000 ALTER TABLE `banner_mast` DISABLE KEYS */;
/*!40000 ALTER TABLE `banner_mast` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `brand_mast`
--

DROP TABLE IF EXISTS `brand_mast`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brand_mast` (
  `brand_id` int(11) NOT NULL AUTO_INCREMENT,
  `brand_name` varchar(100) DEFAULT NULL,
  `brand_heading` varchar(100) DEFAULT NULL,
  `page_title` varchar(100) DEFAULT NULL,
  `meta_key` varchar(500) DEFAULT NULL,
  `meta_desc` varchar(2000) DEFAULT NULL,
  `brand_desc` varchar(4000) DEFAULT NULL,
  `brand_img` varchar(100) DEFAULT NULL,
  `brand_status` tinyint(4) DEFAULT NULL,
  `brand_sort` float DEFAULT NULL,
  PRIMARY KEY (`brand_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brand_mast`
--

LOCK TABLES `brand_mast` WRITE;
/*!40000 ALTER TABLE `brand_mast` DISABLE KEYS */;
/*!40000 ALTER TABLE `brand_mast` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart_details`
--

DROP TABLE IF EXISTS `cart_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cart_details` (
  `session_id` varchar(50) DEFAULT NULL,
  `bill_name` varchar(30) DEFAULT NULL,
  `bill_state` varchar(30) DEFAULT NULL,
  `bill_email` varchar(100) DEFAULT NULL,
  `bill_add1` varchar(50) DEFAULT NULL,
  `bill_add2` varchar(50) DEFAULT NULL,
  `bill_city` varchar(30) DEFAULT NULL,
  `bill_postcode` varchar(10) DEFAULT NULL,
  `bill_country` varchar(30) DEFAULT NULL,
  `bill_tel` varchar(30) DEFAULT NULL,
  `bill_mob` varchar(30) DEFAULT NULL,
  `ship_name` varchar(30) DEFAULT NULL,
  `ship_state` varchar(30) DEFAULT NULL,
  `ship_email` varchar(100) DEFAULT NULL,
  `ship_add1` varchar(50) DEFAULT NULL,
  `ship_add2` varchar(50) DEFAULT NULL,
  `ship_city` varchar(30) DEFAULT NULL,
  `ship_postcode` varchar(10) DEFAULT NULL,
  `ship_country` varchar(30) DEFAULT NULL,
  `ship_tel` varchar(30) DEFAULT NULL,
  `ship_mob` varchar(30) DEFAULT NULL,
  `ord_instruct` varchar(1000) DEFAULT NULL,
  `cart_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_details`
--

LOCK TABLES `cart_details` WRITE;
/*!40000 ALTER TABLE `cart_details` DISABLE KEYS */;
/*!40000 ALTER TABLE `cart_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart_items`
--

DROP TABLE IF EXISTS `cart_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cart_items` (
  `cart_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `prod_id` int(11) DEFAULT NULL,
  `session_id` varchar(50) DEFAULT NULL,
  `cart_datetime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `item_name` varchar(200) DEFAULT NULL,
  `item_stock_no` varchar(100) DEFAULT NULL,
  `item_thumb` longblob,
  `cart_qty` int(11) DEFAULT NULL,
  `cart_price` decimal(6,2) DEFAULT NULL,
  `sup_id` int(11) DEFAULT NULL,
  `sup_name` varchar(50) DEFAULT NULL,
  `tax_id` int(11) NOT NULL,
  `tax_name` varchar(30) DEFAULT NULL,
  `tax_percent` decimal(6,2) NOT NULL,
  `tax_value` decimal(10,2) NOT NULL,
  `cart_price_tax` decimal(10,2) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `item_wish` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`cart_item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_items`
--

LOCK TABLES `cart_items` WRITE;
/*!40000 ALTER TABLE `cart_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `cart_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart_summary`
--

DROP TABLE IF EXISTS `cart_summary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cart_summary` (
  `session_id` varchar(50) DEFAULT NULL,
  `way_billl_no` varchar(50) DEFAULT NULL,
  `cart_datetime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `item_total` decimal(10,2) DEFAULT NULL,
  `shipping_charges` decimal(6,2) DEFAULT NULL,
  `ord_total` decimal(10,2) DEFAULT NULL,
  `vat_percent` decimal(6,2) DEFAULT NULL,
  `vat_value` decimal(6,2) DEFAULT NULL,
  `item_count` int(11) DEFAULT NULL,
  `pay_method` varchar(5) DEFAULT NULL,
  `pay_method_name` varchar(100) DEFAULT NULL,
  `ord_id` int(11) DEFAULT NULL,
  `ord_date` datetime DEFAULT NULL,
  `pay_status` varchar(20) DEFAULT NULL,
  `delivery_status` varchar(20) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `cart_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_type` varchar(2) DEFAULT NULL,
  `ord_no` varchar(15) DEFAULT NULL,
  `pg_status` varchar(30) DEFAULT NULL,
  `pg_txnid` varchar(30) DEFAULT NULL,
  `pkg_weight` float DEFAULT NULL,
  `pkg_height` float DEFAULT NULL,
  `pkg_width` float DEFAULT NULL,
  `pkg_depth` float DEFAULT NULL,
  PRIMARY KEY (`cart_id`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_summary`
--

LOCK TABLES `cart_summary` WRITE;
/*!40000 ALTER TABLE `cart_summary` DISABLE KEYS */;
/*!40000 ALTER TABLE `cart_summary` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `city_mast`
--

DROP TABLE IF EXISTS `city_mast`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `city_mast` (
  `city_id` int(11) NOT NULL,
  `city_name` varchar(50) NOT NULL,
  `state_name` varchar(30) NOT NULL,
  `state_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='List of all Indian Cities';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `city_mast`
--

LOCK TABLES `city_mast` WRITE;
/*!40000 ALTER TABLE `city_mast` DISABLE KEYS */;
INSERT INTO `city_mast` (`city_id`, `city_name`, `state_name`, `state_id`) VALUES (1,'Port Blair','Andaman and Nicobar Islands',33),(2,'Adilabad','Andhra Pradesh',1),(3,'Adoni','Andhra Pradesh',1),(4,'Amadalavalasa','Andhra Pradesh',1),(5,'Amalapuram','Andhra Pradesh',1),(6,'Anakapalle','Andhra Pradesh',1),(7,'Anantapur','Andhra Pradesh',1),(8,'Badepalle','Andhra Pradesh',1),(9,'Banganapalle','Andhra Pradesh',1),(10,'Bapatla','Andhra Pradesh',1),(11,'Bellampalle','Andhra Pradesh',1),(12,'Bethamcherla','Andhra Pradesh',1),(13,'Bhadrachalam','Andhra Pradesh',1),(14,'Bhainsa','Andhra Pradesh',1),(15,'Bheemunipatnam','Andhra Pradesh',1),(16,'Bhimavaram','Andhra Pradesh',1),(17,'Bhongir','Andhra Pradesh',1),(18,'Bobbili','Andhra Pradesh',1),(19,'Bodhan','Andhra Pradesh',1),(20,'Chapirevula','Andhra Pradesh',1),(21,'Chilakaluripet','Andhra Pradesh',1),(22,'Chirala','Andhra Pradesh',1),(23,'Chittoor','Andhra Pradesh',1),(24,'Cuddapah','Andhra Pradesh',1),(25,'Devarakonda','Andhra Pradesh',1),(26,'Dharmavaram','Andhra Pradesh',1),(27,'Eluru','Andhra Pradesh',1),(28,'Farooqnagar','Andhra Pradesh',1),(29,'Gadwal','Andhra Pradesh',1),(30,'Gooty','Andhra Pradesh',1),(31,'Gudivada','Andhra Pradesh',1),(32,'Gudur','Andhra Pradesh',1),(33,'Guntakal','Andhra Pradesh',1),(34,'Guntur','Andhra Pradesh',1),(35,'Hanuman Junction','Andhra Pradesh',1),(36,'Hindupur','Andhra Pradesh',1),(37,'Hyderabad*','Andhra Pradesh',1),(38,'Ichchapuram','Andhra Pradesh',1),(39,'Jaggaiahpet','Andhra Pradesh',1),(40,'Jagtial','Andhra Pradesh',1),(41,'Jammalamadugu','Andhra Pradesh',1),(42,'Jangaon','Andhra Pradesh',1),(43,'Kadapa','Andhra Pradesh',1),(44,'Kadiri','Andhra Pradesh',1),(45,'Kagaznagar','Andhra Pradesh',1),(46,'Kakinada','Andhra Pradesh',1),(47,'Kalyandurg','Andhra Pradesh',1),(48,'Kamareddy','Andhra Pradesh',1),(49,'Kandukur','Andhra Pradesh',1),(50,'Karimnagar','Andhra Pradesh',1),(51,'Kavali','Andhra Pradesh',1),(52,'Khammam','Andhra Pradesh',1),(53,'Kodad','Andhra Pradesh',1),(54,'Koratla','Andhra Pradesh',1),(55,'Kothagudem','Andhra Pradesh',1),(56,'Kothapeta','Andhra Pradesh',1),(57,'Kovvur','Andhra Pradesh',1),(58,'Kurnool','Andhra Pradesh',1),(59,'Kyathampalle','Andhra Pradesh',1),(60,'Macherla','Andhra Pradesh',1),(61,'Machilipatnam','Andhra Pradesh',1),(62,'Madanapalle','Andhra Pradesh',1),(63,'Mahbubnagar','Andhra Pradesh',1),(64,'Mancherial','Andhra Pradesh',1),(65,'Mandamarri','Andhra Pradesh',1),(66,'Mandapeta','Andhra Pradesh',1),(67,'Mangalagiri','Andhra Pradesh',1),(68,'Manuguru','Andhra Pradesh',1),(69,'Markapur','Andhra Pradesh',1),(70,'Medak','Andhra Pradesh',1),(71,'Miryalaguda','Andhra Pradesh',1),(72,'Mogalthur','Andhra Pradesh',1),(73,'Nagari','Andhra Pradesh',1),(74,'Nagarkurnool','Andhra Pradesh',1),(75,'Nandyal','Andhra Pradesh',1),(76,'Narasapur','Andhra Pradesh',1),(77,'Narasaraopet','Andhra Pradesh',1),(78,'Narayanpet','Andhra Pradesh',1),(79,'Narsipatnam','Andhra Pradesh',1),(80,'Nellore','Andhra Pradesh',1),(81,'Nidadavole','Andhra Pradesh',1),(82,'Nirmal','Andhra Pradesh',1),(83,'Nizamabad','Andhra Pradesh',1),(84,'Nuzvid','Andhra Pradesh',1),(85,'Ongole','Andhra Pradesh',1),(86,'Palacole','Andhra Pradesh',1),(87,'Palasa Kasibugga','Andhra Pradesh',1),(88,'Palwancha','Andhra Pradesh',1),(89,'Parvathipuram','Andhra Pradesh',1),(90,'Pedana','Andhra Pradesh',1),(91,'Peddapuram','Andhra Pradesh',1),(92,'Pithapuram','Andhra Pradesh',1),(93,'Pondur','Andhra pradesh',1),(94,'Ponnur','Andhra Pradesh',1),(95,'Proddatur','Andhra Pradesh',1),(96,'Punganur','Andhra Pradesh',1),(97,'Puttur','Andhra Pradesh',1),(98,'Rajahmundry','Andhra Pradesh',1),(99,'Rajam','Andhra Pradesh',1),(100,'Rajampet','Andhra Pradesh',1),(101,'Ramachandrapuram','Andhra Pradesh',1),(102,'Ramagundam','Andhra Pradesh',1),(103,'Rayachoti','Andhra Pradesh',1),(104,'Rayadurg','Andhra Pradesh',1),(105,'Renigunta','Andhra Pradesh',1),(106,'Repalle','Andhra Pradesh',1),(107,'Sadasivpet','Andhra Pradesh',1),(108,'Salur','Andhra Pradesh',1),(109,'Samalkot','Andhra Pradesh',1),(110,'Sangareddy','Andhra Pradesh',1),(111,'Sattenapalle','Andhra Pradesh',1),(112,'Siddipet','Andhra Pradesh',1),(113,'Singapur','Andhra Pradesh',1),(114,'Sircilla','Andhra Pradesh',1),(115,'Srikakulam','Andhra Pradesh',1),(116,'Srikalahasti','Andhra Pradesh',1),(117,'Srisailam Township','Andhra Pradesh',1),(118,'Suryapet','Andhra Pradesh',1),(119,'Tadepalligudem','Andhra Pradesh',1),(120,'Tadpatri','Andhra Pradesh',1),(121,'Tandur','Andhra Pradesh',1),(122,'Tanuku','Andhra Pradesh',1),(123,'Tenali','Andhra Pradesh',1),(124,'Tirupati','Andhra Pradesh',1),(125,'Tiruvuru','Andhra Pradesh',1),(126,'Tuni','Andhra Pradesh',1),(127,'Uravakonda','Andhra Pradesh',1),(128,'Venkatagiri','Andhra Pradesh',1),(129,'Vicarabad','Andhra Pradesh',1),(130,'Vijayawada','Andhra Pradesh',1),(131,'Vinukonda','Andhra Pradesh',1),(132,'Visakhapatnam','Andhra Pradesh',1),(133,'Vizianagaram','Andhra Pradesh',1),(134,'Wanaparthy','Andhra Pradesh',1),(135,'Warangal','Andhra Pradesh',1),(136,'Yellandu','Andhra Pradesh',1),(137,'Yemmiganur','Andhra Pradesh',1),(138,'Yerraguntla','Andhra Pradesh',1),(139,'Zahirabad','Andhra Pradesh',1),(140,'Along','Arunachal Pradesh',2),(141,'Bomdila','Arunachal Pradesh',2),(142,'Itanagar*','Arunachal Pradesh',2),(143,'Naharlagun','Arunachal Pradesh',2),(144,'Pasighat','Arunachal Pradesh',2),(145,'Abhayapuri','Assam',3),(146,'Amguri','Assam',3),(147,'Anandnagaar','Assam',3),(148,'Barpeta','Assam',3),(149,'Barpeta Road','Assam',3),(150,'Bilasipara','Assam',3),(151,'Bongaigaon','Assam',3),(152,'Dhekiajuli','Assam',3),(153,'Dhubri','Assam',3),(154,'Dibrugarh','Assam',3),(155,'Digboi','Assam',3),(156,'Diphu','Assam',3),(157,'Dispur*','Assam',3),(158,'Duliajan Oil Town','Assam',3),(159,'Gauripur','Assam',3),(160,'Goalpara','Assam',3),(161,'Golaghat','Assam',3),(162,'Guwahati','Assam',3),(163,'Haflong','Assam',3),(164,'Hailakandi','Assam',3),(165,'Hojai','Assam',3),(166,'Jorhat','Assam',3),(167,'Karimganj','Assam',3),(168,'Kokrajhar','Assam',3),(169,'Lanka','Assam',3),(170,'Lumding','Assam',3),(171,'Mangaldoi','Assam',3),(172,'Mankachar','Assam',3),(173,'Margherita','Assam',3),(174,'Mariani','Assam',3),(175,'Marigaon','Assam',3),(176,'Nagaon','Assam',3),(177,'Nalbari','Assam',3),(178,'North Lakhimpur','Assam',3),(179,'Rangia','Assam',3),(180,'Sibsagar','Assam',3),(181,'Silapathar','Assam',3),(182,'Silchar','Assam',3),(183,'Tezpur','Assam',3),(184,'Tinsukia','Assam',3),(185,'Amarpur','Bihar',4),(186,'Araria','Bihar',4),(187,'Areraj','Bihar',4),(188,'Arrah','Bihar',4),(189,'Asarganj','Bihar',4),(190,'Aurangabad','Bihar',4),(191,'Bagaha','Bihar',4),(192,'Bahadurganj','Bihar',4),(193,'Bairgania','Bihar',4),(194,'Bakhtiarpur','Bihar',4),(195,'Banka','Bihar',4),(196,'Banmankhi Bazar','Bihar',4),(197,'Barahiya','Bihar',4),(198,'Barauli','Bihar',4),(199,'Barbigha','Bihar',4),(200,'Barh','Bihar',4),(201,'Begusarai','Bihar',4),(202,'Behea','Bihar',4),(203,'Bettiah','Bihar',4),(204,'Bhabua','Bihar',4),(205,'Bhagalpur','Bihar',4),(206,'Bihar Sharif','Bihar',4),(207,'Bikramganj','Bihar',4),(208,'Bodh Gaya','Bihar',4),(209,'Buxar','Bihar',4),(210,'Chandan Bara','Bihar',4),(211,'Chanpatia','Bihar',4),(212,'Chhapra','Bihar',4),(213,'Colgong','Bihar',4),(214,'Dalsinghsarai','Bihar',4),(215,'Darbhanga','Bihar',4),(216,'Daudnagar','Bihar',4),(217,'Dehri-on-Sone','Bihar',4),(218,'Dhaka','Bihar',4),(219,'Dighwara','Bihar',4),(220,'Dumraon','Bihar',4),(221,'Fatwah','Bihar',4),(222,'Forbesganj','Bihar',4),(223,'Gaya','Bihar',4),(224,'Gogri Jamalpur','Bihar',4),(225,'Gopalganj','Bihar',4),(226,'Hajipur','Bihar',4),(227,'Hilsa','Bihar',4),(228,'Hisua','Bihar',4),(229,'Islampur','Bihar',4),(230,'Jagdispur','Bihar',4),(231,'Jamalpur','Bihar',4),(232,'Jamui','Bihar',4),(233,'Jehanabad','Bihar',4),(234,'Jhajha','Bihar',4),(235,'Jhanjharpur','Bihar',4),(236,'Jogabani','Bihar',4),(237,'Kanti','Bihar',4),(238,'Katihar','Bihar',4),(239,'Khagaria','Bihar',4),(240,'Kharagpur','Bihar',4),(241,'Kishanganj','Bihar',4),(242,'Lakhisarai','Bihar',4),(243,'Lalganj','Bihar',4),(244,'Madhepura','Bihar',4),(245,'Madhubani','Bihar',4),(246,'Maharajganj','Bihar',4),(247,'Mahnar Bazar','Bihar',4),(248,'Makhdumpur','Bihar',4),(249,'Maner','Bihar',4),(250,'Manihari','Bihar',4),(251,'Marhaura','Bihar',4),(252,'Masaurhi','Bihar',4),(253,'Mirganj','Bihar',4),(254,'Mohania','Bihar',4),(255,'Mokama','Bihar',4),(256,'Mokameh','Bihar',4),(257,'Motihari','Bihar',4),(258,'Motipur','Bihar',4),(259,'Munger','Bihar',4),(260,'Murliganj','Bihar',4),(261,'Muzaffarpur','Bihar',4),(262,'Narkatiaganj','Bihar',4),(263,'Naugachhia','Bihar',4),(264,'Nawada','Bihar',4),(265,'Nokha','Bihar',4),(266,'Patna*','Bihar',4),(267,'Piro','Bihar',4),(268,'Purnia','Bihar',4),(269,'Rafiganj','Bihar',4),(270,'Rajgir','Bihar',4),(271,'Ramnagar','Bihar',4),(272,'Raxaul Bazar','Bihar',4),(273,'Revelganj','Bihar',4),(274,'Rosera','Bihar',4),(275,'Saharsa','Bihar',4),(276,'Samastipur','Bihar',4),(277,'Sasaram','Bihar',4),(278,'Sheikhpura','Bihar',4),(279,'Sheohar','Bihar',4),(280,'Sherghati','Bihar',4),(281,'Silao','Bihar',4),(282,'Sitamarhi','Bihar',4),(283,'Siwan','Bihar',4),(284,'Sonepur','Bihar',4),(285,'Sugauli','Bihar',4),(286,'Sultanganj','Bihar',4),(287,'Supaul','Bihar',4),(288,'Warisaliganj','Bihar',4),(289,'Ahiwara','Chhattisgarh',6),(290,'Akaltara','Chhattisgarh',6),(291,'Ambagarh Chowki','Chhattisgarh',6),(292,'Ambikapur','Chhattisgarh',6),(293,'Arang','Chhattisgarh',6),(294,'Bade Bacheli','Chhattisgarh',6),(295,'Balod','Chhattisgarh',6),(296,'Baloda Bazar','Chhattisgarh',6),(297,'Basna','Chhattisgarh',6),(298,'Bemetra','Chhattisgarh',6),(299,'Bhatapara','Chhattisgarh',6),(300,'Bhilai','Chhattisgarh',6),(301,'Bilaspur','Chhattisgarh',6),(302,'Birgaon','Chhattisgarh',6),(303,'Champa','Chhattisgarh',6),(304,'Chirmiri','Chhattisgarh',6),(305,'Dalli-Rajhara','Chhattisgarh',6),(306,'Dhamtari','Chhattisgarh',6),(307,'Dipka','Chhattisgarh',6),(308,'Dongargarh','Chhattisgarh',6),(309,'Durg-Bhilai Nagar','Chhattisgarh',6),(310,'Gobranawapara','Chhattisgarh',6),(311,'Jagdalpur','Chhattisgarh',6),(312,'Janjgir','Chhattisgarh',6),(313,'Jashpurnagar','Chhattisgarh',6),(314,'Kanker','Chhattisgarh',6),(315,'Kawardha','Chhattisgarh',6),(316,'Kondagaon','Chhattisgarh',6),(317,'Korba','Chhattisgarh',6),(318,'Mahasamund','Chhattisgarh',6),(319,'Mahendragarh','Chhattisgarh',6),(320,'Mungeli','Chhattisgarh',6),(321,'Naila Janjgir','Chhattisgarh',6),(322,'Raigarh','Chhattisgarh',6),(323,'Raipur*','Chhattisgarh',6),(324,'Rajnandgaon','Chhattisgarh',6),(325,'Sakti','Chhattisgarh',6),(326,'Tilda Newra','Chhattisgarh',6),(327,'Amli','Dadra and Nagar Haveli',6),(328,'Silvassa*','Dadra and Nagar Haveli',6),(329,'Daman and Diu','Daman and Diu',6),(330,'Asola','Delhi',7),(331,'Bhajanpura','Delhi',7),(332,'New Delhi*','Delhi',7),(333,'Aldona','Goa',8),(334,'Curchorem Cacora','Goa',8),(335,'Goa Velha','Goa',8),(336,'Madgaon','Goa',8),(337,'Mapusa','Goa',8),(338,'Margao','Goa',8),(339,'Marmagao','Goa',8),(340,'Panaji*','Goa',8),(341,'Ahmedabad','Gujarat',9),(342,'Amreli','Gujarat',9),(343,'Anand','Gujarat',9),(344,'Ankleshwar','Gujarat',9),(345,'Bharuch','Gujarat',9),(346,'Bhavnagar','Gujarat',9),(347,'Bhuj','Gujarat',9),(348,'Cambay','Gujarat',9),(349,'Dahod','Gujarat',9),(350,'Deesa','Gujarat',9),(351,'Dehgam','Gujarat',9),(352,'Dharampur','Gujarat',9),(353,'Dholka','Gujarat',9),(354,'Dwarka','Gujarat',9),(355,'Gandhidham','Gujarat',9),(356,'Gandhinagar*','Gujarat',9),(357,'Godhra','Gujarat',9),(358,'Himatnagar','Gujarat',9),(359,'Idar','Gujarat',9),(360,'Jamnagar','Gujarat',9),(361,'Junagadh','Gujarat',9),(362,'Kadi','Gujarat',9),(363,'Kalavad','Gujarat',9),(364,'Kalol','Gujarat',9),(365,'Kapadvanj','Gujarat',9),(366,'Karjan','Gujarat',9),(367,'Keshod','Gujarat',9),(368,'Khambhalia','Gujarat',9),(369,'Khambhat','Gujarat',9),(370,'Kheda','Gujarat',9),(371,'Khedbrahma','Gujarat',9),(372,'Kheralu','Gujarat',9),(373,'Kodinar','Gujarat',9),(374,'Lathi','Gujarat',9),(375,'Limbdi','Gujarat',9),(376,'Lunawada','Gujarat',9),(377,'Mahesana','Gujarat',9),(378,'Mahuva','Gujarat',9),(379,'Manavadar','Gujarat',9),(380,'Mandvi','Gujarat',9),(381,'Mangrol','Gujarat',9),(382,'Mansa','Gujarat',9),(383,'Mehmedabad','Gujarat',9),(384,'Mithapur','Gujarat',9),(385,'Modasa','Gujarat',9),(386,'Morvi','Gujarat',9),(387,'Nadiad','Gujarat',9),(388,'Navsari','Gujarat',9),(389,'Padra','Gujarat',9),(390,'Palanpur','Gujarat',9),(391,'Palitana','Gujarat',9),(392,'Pardi','Gujarat',9),(393,'Patan','Gujarat',9),(394,'Petlad','Gujarat',9),(395,'Porbandar','Gujarat',9),(396,'Radhanpur','Gujarat',9),(397,'Rajkot','Gujarat',9),(398,'Rajpipla','Gujarat',9),(399,'Rajula','Gujarat',9),(400,'Ranavav','Gujarat',9),(401,'Rapar','Gujarat',9),(402,'Salaya','Gujarat',9),(403,'Sanand','Gujarat',9),(404,'Savarkundla','Gujarat',9),(405,'Sidhpur','Gujarat',9),(406,'Sihor','Gujarat',9),(407,'Songadh','Gujarat',9),(408,'Surat','Gujarat',9),(409,'Talaja','Gujarat',9),(410,'Thangadh','Gujarat',9),(411,'Tharad','Gujarat',9),(412,'Umbergaon','Gujarat',9),(413,'Umreth','Gujarat',9),(414,'Una','Gujarat',9),(415,'Unjha','Gujarat',9),(416,'Upleta','Gujarat',9),(417,'Vadnagar','Gujarat',9),(418,'Vadodara','Gujarat',9),(419,'Valsad','Gujarat',9),(420,'Vapi','Gujarat',9),(421,'Vapi','Gujarat',9),(422,'Veraval','Gujarat',9),(423,'Vijapur','Gujarat',9),(424,'Viramgam','Gujarat',9),(425,'Visnagar','Gujarat',9),(426,'Vyara','Gujarat',9),(427,'Wadhwan','Gujarat',9),(428,'Wankaner','Gujarat',9),(429,'Adalaj','Gujrat',9),(430,'Adityana','Gujrat',9),(431,'Alang','Gujrat',9),(432,'Ambaji','Gujrat',9),(433,'Ambaliyasan','Gujrat',9),(434,'Andada','Gujrat',9),(435,'Anjar','Gujrat',9),(436,'Anklav','Gujrat',9),(437,'Antaliya','Gujrat',9),(438,'Arambhada','Gujrat',9),(439,'Atul','Gujrat',9),(440,'Asankhurd','Haryana',10),(441,'Assandh','Haryana',10),(442,'Ateli','Haryana',10),(443,'Babiyal','Haryana',10),(444,'Bahadurgarh','Haryana',10),(445,'Ballabhgarh','Haryana',10),(446,'Barwala','Haryana',10),(447,'Bawal','Haryana',10),(448,'Bhiwani','Haryana',10),(449,'Charkhi Dadri','Haryana',10),(450,'Cheeka','Haryana',10),(451,'Ellenabad','Haryana',10),(452,'Faridabad','Haryana',10),(453,'Fatehabad','Haryana',10),(454,'Ganaur','Haryana',10),(455,'Gharaunda','Haryana',10),(456,'Gohana','Haryana',10),(457,'Gurgaon','Haryana',10),(458,'Haibat(Yamuna Nagar)','Haryana',10),(459,'Hansi','Haryana',10),(460,'Hisar','Haryana',10),(461,'Hodal','Haryana',10),(462,'Jagadhri','Haryana',10),(463,'Jhajjar','Haryana',10),(464,'Jind','Haryana',10),(465,'Kaithal','Haryana',10),(466,'Kalan Wali','Haryana',10),(467,'Kalka','Haryana',10),(468,'Karnal','Haryana',10),(469,'Ladwa','Haryana',10),(470,'Mahendragarh','Haryana',10),(471,'Mandi Dabwali','Haryana',10),(472,'Manesar','Haryana',10),(473,'Narnaul','Haryana',10),(474,'Narwana','Haryana',10),(475,'Palwal','Haryana',10),(476,'Panchkula','Haryana',10),(477,'Panipat','Haryana',10),(478,'Pehowa','Haryana',10),(479,'Pinjore','Haryana',10),(480,'Rania','Haryana',10),(481,'Ratia','Haryana',10),(482,'Rewari','Haryana',10),(483,'Rohtak','Haryana',10),(484,'Safidon','Haryana',10),(485,'Samalkha','Haryana',10),(486,'Shahbad','Haryana',10),(487,'Sirsa','Haryana',10),(488,'Sohna','Haryana',10),(489,'Sonipat','Haryana',10),(490,'Taraori','Haryana',10),(491,'Thanesar','Haryana',10),(492,'Tohana','Haryana',10),(493,'Yamunanagar','Haryana',10),(494,'Arki','Himachal Pradesh',11),(495,'Baddi','Himachal Pradesh',11),(496,'Bilaspur','Himachal Pradesh',11),(497,'Chamba','Himachal Pradesh',11),(498,'Dalhousie','Himachal Pradesh',11),(499,'Dharamsala','Himachal Pradesh',11),(500,'Hamirpur','Himachal Pradesh',11),(501,'Keylong','Himachal Pradesh',11),(502,'Mandi','Himachal Pradesh',11),(503,'Nahan','Himachal Pradesh',11),(504,'Shimla*','Himachal Pradesh',11),(505,'Solan','Himachal Pradesh',11),(506,'Sundarnagar','Himachal Pradesh',11),(507,'Achabbal','Jammu and Kashmir',12),(508,'Akhnoor','Jammu and Kashmir',12),(509,'Anantnag','Jammu and Kashmir',12),(510,'Arnia','Jammu and Kashmir',12),(511,'Awantipora','Jammu and Kashmir',12),(512,'Bandipore','Jammu and Kashmir',12),(513,'Baramula','Jammu and Kashmir',12),(514,'Jammu','Jammu and Kashmir',12),(515,'Kathua','Jammu and Kashmir',12),(516,'Leh','Jammu and Kashmir',12),(517,'Punch','Jammu and Kashmir',12),(518,'Rajauri','Jammu and Kashmir',12),(519,'Sopore','Jammu and Kashmir',12),(520,'Srinagar*','Jammu and Kashmir',12),(521,'Udhampur','Jammu and Kashmir',12),(522,'Amlabad','Jharkhand',13),(523,'Ara','Jharkhand',13),(524,'Barughutu','Jharkhand',13),(525,'Bokaro Steel City','Jharkhand',13),(526,'Chaibasa','Jharkhand',13),(527,'Chakradharpur','Jharkhand',13),(528,'Chandil','Jharkhand',13),(529,'Chandrapura','Jharkhand',13),(530,'Chatra','Jharkhand',13),(531,'Chirkunda','Jharkhand',13),(532,'Churi','Jharkhand',13),(533,'Daltonganj','Jharkhand',13),(534,'Deoghar','Jharkhand',13),(535,'Dhanbad','Jharkhand',13),(536,'Dumka','Jharkhand',13),(537,'Garhwa','Jharkhand',13),(538,'Ghatshila','Jharkhand',13),(539,'Giridih','Jharkhand',13),(540,'Godda','Jharkhand',13),(541,'Gomoh','Jharkhand',13),(542,'Gumia','Jharkhand',13),(543,'Gumla','Jharkhand',13),(544,'Hazaribag','Jharkhand',13),(545,'Hussainabad','Jharkhand',13),(546,'Jamshedpur','Jharkhand',13),(547,'Jamtara','Jharkhand',13),(548,'Jhumri Tilaiya','Jharkhand',13),(549,'Khunti','Jharkhand',13),(550,'Lohardaga','Jharkhand',13),(551,'Madhupur','Jharkhand',13),(552,'Mihijam','Jharkhand',13),(553,'Musabani','Jharkhand',13),(554,'Pakaur','Jharkhand',13),(555,'Patratu','Jharkhand',13),(556,'Phusro','Jharkhand',13),(557,'Ramngarh','Jharkhand',13),(558,'Ranchi*','Jharkhand',13),(559,'Sahibganj','Jharkhand',13),(560,'Saunda','Jharkhand',13),(561,'Simdega','Jharkhand',13),(562,'Tenu Dam-cum- Kathhara','Jharkhand',13),(563,'Aalanavara','Karnataka',14),(564,'Adyar','Karnataka',14),(565,'Afzalpura','Karnataka',14),(566,'Alandha','Karnataka',14),(567,'Alur','Karnataka',14),(568,'Ambikaanagara','Karnataka',14),(569,'Anekal','Karnataka',14),(570,'Ankola','Karnataka',14),(571,'Annigeri','Karnataka',14),(572,'Arkalgud','Karnataka',14),(573,'Arsikere','Karnataka',14),(574,'Athni','Karnataka',14),(575,'Aurad','Karnataka',14),(576,'Ballary','Karnataka',14),(577,'Belagaavi','Karnataka',14),(578,'Bengalooru','Karnataka',14),(579,'Bidar','Karnataka',14),(580,'Chamarajanagara','Karnataka',14),(581,'Chikkaballapura','Karnataka',14),(582,'Chikkamagalur','Karnataka',14),(583,'Chikkodi','Karnataka',14),(584,'Chinthaamani','Karnataka',14),(585,'Chithradurga','Karnataka',14),(586,'Davanageray','Karnataka',14),(587,'Dharwad','Karnataka',14),(588,'Gadhaga','Karnataka',14),(589,'Gokak','Karnataka',14),(590,'Gulbarga','Karnataka',14),(591,'Gundlupet','Karnataka',14),(592,'Haasana','Karnataka',14),(593,'Hosapet','Karnataka',14),(594,'Hubbali','Karnataka',14),(595,'Kaarawaara','Karnataka',14),(596,'Kaarkala','Karnataka',14),(597,'Kalburgi','Karnataka',14),(598,'Kolaara','Karnataka',14),(599,'Kota','Karnataka',14),(600,'Lakshmishawara','Karnataka',14),(601,'Lingsuguru','Karnataka',14),(602,'Maagadi','Karnataka',14),(603,'Maaluru','Karnataka',14),(604,'Maanvi','Karnataka',14),(605,'Maddhuru','Karnataka',14),(606,'Madhugiri','Karnataka',14),(607,'Madikeri','Karnataka',14),(608,'Mahalingapura','Karnataka',14),(609,'Malavalli','Karnataka',14),(610,'Mandya','Karnataka',14),(611,'Mangalooru','Karnataka',14),(612,'Mudabidri','Karnataka',14),(613,'Mudalagi','Karnataka',14),(614,'Muddebihala','Karnataka',14),(615,'Mudhola','Karnataka',14),(616,'Mulabaagilu','Karnataka',14),(617,'Mundaragi','Karnataka',14),(618,'Mysooru','Karnataka',14),(619,'Nanjanagoodu','Karnataka',14),(620,'Nippani','Karnataka',14),(621,'Paavagada','Karnataka',14),(622,'pereyaapatna','Karnataka',14),(623,'Puthooru','Karnataka',14),(624,'Raamadurga','Karnataka',14),(625,'Raamanagara','Karnataka',14),(626,'Raayachuru','Karnataka',14),(627,'Rabakavi Banahatti','Karnataka',14),(628,'Ranibennur','Karnataka',14),(629,'Robertson Pet','Karnataka',14),(630,'Ron','Karnataka',14),(631,'Saagara','Karnataka',14),(632,'Sadalaga','Karnataka',14),(633,'Sakaleshapura','Karnataka',14),(634,'Sandur','Karnataka',14),(635,'Sankeshwara','Karnataka',14),(636,'Savanur','Karnataka',14),(637,'Sedam','Karnataka',14),(638,'Shahabad','Karnataka',14),(639,'Shahapura','Karnataka',14),(640,'Sheraguppa','Karnataka',14),(641,'Shiggaavi','Karnataka',14),(642,'Shikapur','Karnataka',14),(643,'Shivamogga','Karnataka',14),(644,'Shree Rangapattana','Karnataka',14),(645,'Shreenivaasapura','Karnataka',14),(646,'Sidhalaghatta','Karnataka',14),(647,'Sindhagi','Karnataka',14),(648,'Sindhanooru','Karnataka',14),(649,'Sira','Karnataka',14),(650,'Sirsi','Karnataka',14),(651,'Soudaththi-Yellamma','Karnataka',14),(652,'Surapura','Karnataka',14),(653,'Tarikere','Karnataka',14),(654,'Tekkalakote','Karnataka',14),(655,'Thaalikote','Karnataka',14),(656,'Thergallu','Karnataka',14),(657,'Thipatooru','Karnataka',14),(658,'Thumakooru','Karnataka',14),(659,'Udupi','Karnataka',14),(660,'Vijayapura','Karnataka',14),(661,'Wadi','Karnataka',14),(662,'Yaadhagiri','Karnataka',14),(663,'Adoor','Kerala',15),(664,'Akathiyoor','Kerala',15),(665,'Alappuzha','Kerala',15),(666,'Ancharakandy','Kerala',15),(667,'Aroor','Kerala',15),(668,'Ashtamichira','Kerala',15),(669,'Attingal','Kerala',15),(670,'Avinissery','Kerala',15),(671,'Chalakudy','Kerala',15),(672,'Changanassery','Kerala',15),(673,'Chendamangalam','Kerala',15),(674,'Chengannur','Kerala',15),(675,'Cherthala','Kerala',15),(676,'Cheruthazham','Kerala',15),(677,'Chittur-Thathamangalam','Kerala',15),(678,'Chockli','Kerala',15),(679,'Erattupetta','Kerala',15),(680,'Guruvayoor','Kerala',15),(681,'Irinjalakuda','Kerala',15),(682,'Kadirur','Kerala',15),(683,'Kalliasseri','Kerala',15),(684,'Kalpetta','Kerala',15),(685,'Kanhangad','Kerala',15),(686,'Kanjikkuzhi','Kerala',15),(687,'Kannur','Kerala',15),(688,'Kasaragod','Kerala',15),(689,'Kayamkulam','Kerala',15),(690,'Kochi','Kerala',15),(691,'Kodungallur','Kerala',15),(692,'Kollam','Kerala',15),(693,'Koothuparamba','Kerala',15),(694,'Kothamangalam','Kerala',15),(695,'Kottayam','Kerala',15),(696,'Kozhikode','Kerala',15),(697,'Kunnamkulam','Kerala',15),(698,'Malappuram','Kerala',15),(699,'Mattannur','Kerala',15),(700,'Mavelikkara','Kerala',15),(701,'Mavoor','Kerala',15),(702,'Muvattupuzha','Kerala',15),(703,'Nedumangad','Kerala',15),(704,'Neyyattinkara','Kerala',15),(705,'Ottappalam','Kerala',15),(706,'Palai','Kerala',15),(707,'Palakkad','Kerala',15),(708,'Panniyannur','Kerala',15),(709,'Pappinisseri','Kerala',15),(710,'Paravoor','Kerala',15),(711,'Pathanamthitta','Kerala',15),(712,'Payyannur','Kerala',15),(713,'Peringathur','Kerala',15),(714,'Perinthalmanna','Kerala',15),(715,'Perumbavoor','Kerala',15),(716,'Ponnani','Kerala',15),(717,'Punalur','Kerala',15),(718,'Quilandy','Kerala',15),(719,'Shoranur','Kerala',15),(720,'Taliparamba','Kerala',15),(721,'Thiruvalla','Kerala',15),(722,'Thiruvananthapuram','Kerala',15),(723,'Thodupuzha','Kerala',15),(724,'Thrissur','Kerala',15),(725,'Tirur','Kerala',15),(726,'Vadakara','Kerala',15),(727,'Vaikom','Kerala',15),(728,'Varkala','Kerala',15),(729,'Amini','Lakshadweep',15),(730,'Kavaratti*','Lakshadweep',15),(731,'Ashok Nagar','Madhya Pradesh',16),(732,'Balaghat','Madhya Pradesh',16),(733,'Barwani','Madhya Pradesh',16),(734,'Betul','Madhya Pradesh',16),(735,'Bhopal*','Madhya Pradesh',16),(736,'Burhanpur','Madhya Pradesh',16),(737,'Chhatarpur','Madhya Pradesh',16),(738,'Chhindwara','Madhya Pradesh',16),(739,'Chitrakoot','Madhya Pradesh',16),(740,'Dabra','Madhya Pradesh',16),(741,'Damoh','Madhya Pradesh',16),(742,'Datia','Madhya Pradesh',16),(743,'Dewas','Madhya Pradesh',16),(744,'Dhar','Madhya Pradesh',16),(745,'Fatehabad','Madhya Pradesh',16),(746,'Guna','Madhya Pradesh',16),(747,'Gwalior','Madhya pradesh',16),(748,'Harda','Madhya Pradesh',16),(749,'Indore','Madhya Pradesh',16),(750,'Itarsi','Madhya Pradesh',16),(751,'Jabalpur','Madhya Pradesh',16),(752,'Jhabua','Madhya Pradesh',16),(753,'Kailaras','Madhya Pradesh',16),(754,'Katni','Madhya Pradesh',16),(755,'Khurai','Madhya Pradesh',16),(756,'Kotma','Madhya Pradesh',16),(757,'Lahar','Madhya Pradesh',16),(758,'Lundi','Madhya Pradesh',16),(759,'Maharajpur','Madhya Pradesh',16),(760,'Mahidpur','Madhya Pradesh',16),(761,'Maihar','Madhya Pradesh',16),(762,'Malajkhand','Madhya Pradesh',16),(763,'Manasa','Madhya Pradesh',16),(764,'Manawar','Madhya Pradesh',16),(765,'Mandideep','Madhya Pradesh',16),(766,'Mandla','Madhya Pradesh',16),(767,'Mandsaur','Madhya Pradesh',16),(768,'Mauganj','Madhya Pradesh',16),(769,'Mhow Cantonment','Madhya Pradesh',16),(770,'Mhowgaon','Madhya Pradesh',16),(771,'Morena','Madhya Pradesh',16),(772,'Multai','Madhya Pradesh',16),(773,'Murwara','Madhya Pradesh',16),(774,'Nagda','Madhya Pradesh',16),(775,'Nainpur','Madhya Pradesh',16),(776,'Narsinghgarh','Madhya Pradesh',16),(777,'Narsinghgarh','Madhya Pradesh',16),(778,'Neemuch','Madhya Pradesh',16),(779,'Nepanagar','Madhya Pradesh',16),(780,'Niwari','Madhya Pradesh',16),(781,'Nowgong','Madhya Pradesh',16),(782,'Nowrozabad','Madhya Pradesh',16),(783,'Pachore','Madhya Pradesh',16),(784,'Pali','Madhya Pradesh',16),(785,'Panagar','Madhya Pradesh',16),(786,'Pandhurna','Madhya Pradesh',16),(787,'Panna','Madhya Pradesh',16),(788,'Pasan','Madhya Pradesh',16),(789,'Pipariya','Madhya Pradesh',16),(790,'Pithampur','Madhya Pradesh',16),(791,'Porsa','Madhya Pradesh',16),(792,'Prithvipur','Madhya Pradesh',16),(793,'Raghogarh-Vijaypur','Madhya Pradesh',16),(794,'Rahatgarh','Madhya Pradesh',16),(795,'Raisen','Madhya Pradesh',16),(796,'Rajgarh','Madhya Pradesh',16),(797,'Ratlam','Madhya Pradesh',16),(798,'Rau','Madhya Pradesh',16),(799,'Rehli','Madhya Pradesh',16),(800,'Rewa','Madhya Pradesh',16),(801,'Sabalgarh','Madhya Pradesh',16),(802,'Sagar','Madhya Pradesh',16),(803,'Sanawad','Madhya Pradesh',16),(804,'Sarangpur','Madhya Pradesh',16),(805,'Sarni','Madhya Pradesh',16),(806,'Satna','Madhya Pradesh',16),(807,'Sausar','Madhya Pradesh',16),(808,'Sehore','Madhya Pradesh',16),(809,'Sendhwa','Madhya Pradesh',16),(810,'Seoni','Madhya Pradesh',16),(811,'Seoni-Malwa','Madhya Pradesh',16),(812,'Shahdol','Madhya Pradesh',16),(813,'Shajapur','Madhya Pradesh',16),(814,'Shamgarh','Madhya Pradesh',16),(815,'Sheopur','Madhya Pradesh',16),(816,'Shivpuri','Madhya Pradesh',16),(817,'Shujalpur','Madhya Pradesh',16),(818,'Sidhi','Madhya Pradesh',16),(819,'Sihora','Madhya Pradesh',16),(820,'Singrauli','Madhya Pradesh',16),(821,'Sironj','Madhya Pradesh',16),(822,'Sohagpur','Madhya Pradesh',16),(823,'Tarana','Madhya Pradesh',16),(824,'Tikamgarh','Madhya Pradesh',16),(825,'Ujhani','Madhya Pradesh',16),(826,'Ujjain','Madhya Pradesh',16),(827,'Umaria','Madhya Pradesh',16),(828,'Vidisha','Madhya Pradesh',16),(829,'Wara Seoni','Madhya Pradesh',16),(830,'Achalpur','Maharashtra',17),(831,'Ahmednagar','Maharashtra',17),(832,'Ahmedpur','Maharashtra',17),(833,'Ajra','Maharashtra',17),(834,'Akkalkot','Maharashtra',17),(835,'Akola','Maharashtra',17),(836,'Akot','Maharashtra',17),(837,'Alandi','Maharashtra',17),(838,'Alibag','Maharashtra',17),(839,'Amalner','Maharashtra',17),(840,'Ambad','Maharashtra',17),(841,'Ambejogai','Maharashtra',17),(842,'Ambivali Tarf Wankhal','Maharashtra',17),(843,'Amravati','Maharashtra',17),(844,'Arvi','Maharashtra',17),(845,'Ashta','Maharashtra',17),(846,'Aurangabad','Maharashtra',17),(847,'Ausa','Maharashtra',17),(848,'Baramati','Maharashtra',17),(849,'Bhandara','Maharashtra',17),(850,'Bhiwandi','Maharashtra',17),(851,'Bhusawal','Maharashtra',17),(852,'Chalisgaon','Maharashtra',17),(853,'Chandrapur','Maharashtra',17),(854,'Chinchani','Maharashtra',17),(855,'Chiplun','Maharashtra',17),(856,'Daund','Maharashtra',17),(857,'Devgarh','Maharashtra',17),(858,'Dhule','Maharashtra',17),(859,'Dombivli','Maharashtra',17),(860,'Durgapur','Maharashtra',17),(861,'Gadchiroli','Maharashtra',17),(862,'Ghatanji','Maharashtra',17),(863,'Gondiya','Maharashtra',17),(864,'Ichalkaranji','Maharashtra',17),(865,'Jalgaon','Maharashtra',17),(866,'Jalna','Maharashtra',17),(867,'Junnar','Maharashtra',17),(868,'Kalyan','Maharashtra',17),(869,'Kamthi','Maharashtra',17),(870,'Karad','Maharashtra',17),(871,'karjat','maharashtra',17),(872,'Kolhapur','Maharashtra',17),(873,'Latur','Maharashtra',17),(874,'Loha','Maharashtra',17),(875,'Lonar','Maharashtra',17),(876,'Lonavla','Maharashtra',17),(877,'Mahabaleswar','Maharashtra',17),(878,'Mahad','Maharashtra',17),(879,'Mahuli','Maharashtra',17),(880,'Malegaon','Maharashtra',17),(881,'Malkapur','Maharashtra',17),(882,'Manchar','Maharashtra',17),(883,'Mangalvedhe','Maharashtra',17),(884,'Mangrulpir','Maharashtra',17),(885,'Manjlegaon','Maharashtra',17),(886,'Manmad','Maharashtra',17),(887,'Manwath','Maharashtra',17),(888,'Mehkar','Maharashtra',17),(889,'Mhaswad','Maharashtra',17),(890,'Mira-Bhayandar','Maharashtra',17),(891,'Miraj','Maharashtra',17),(892,'Morshi','Maharashtra',17),(893,'Mukhed','Maharashtra',17),(894,'Mul','Maharashtra',17),(895,'Mumbai','Maharashtra',17),(896,'Murtijapur','Maharashtra',17),(897,'Nagpur','Maharashtra',17),(898,'Nalasopara','Maharashtra',17),(899,'Nanded-Waghala','Maharashtra',17),(900,'Nandgaon','Maharashtra',17),(901,'Nandura','Maharashtra',17),(902,'Nandurbar','Maharashtra',17),(903,'Narkhed','Maharashtra',17),(904,'Nashik','Maharashtra',17),(905,'Navi Mumbai','Maharashtra',17),(906,'Nawapur','Maharashtra',17),(907,'Nilanga','Maharashtra',17),(908,'Osmanabad','Maharashtra',17),(909,'Ozar','Maharashtra',17),(910,'Pachora','Maharashtra',17),(911,'Paithan','Maharashtra',17),(912,'Palghar','Maharashtra',17),(913,'Pandharkaoda','Maharashtra',17),(914,'Pandharpur','Maharashtra',17),(915,'Panvel','Maharashtra',17),(916,'Parbhani','Maharashtra',17),(917,'Parli','Maharashtra',17),(918,'Parola','Maharashtra',17),(919,'Partur','Maharashtra',17),(920,'Pathardi','Maharashtra',17),(921,'Pathri','Maharashtra',17),(922,'Patur','Maharashtra',17),(923,'Pauni','Maharashtra',17),(924,'Pen','Maharashtra',17),(925,'Phaltan','Maharashtra',17),(926,'Pulgaon','Maharashtra',17),(927,'Pune','Maharashtra',17),(928,'Purna','Maharashtra',17),(929,'Pusad','Maharashtra',17),(930,'Raichuri','Maharashtra',17),(931,'Rajura','Maharashtra',17),(932,'Ramtek','Maharashtra',17),(933,'Ratnagiri','Maharashtra',17),(934,'Raver','Maharashtra',17),(935,'Risod','Maharashtra',17),(936,'Sailu','Maharashtra',17),(937,'Sangamner','Maharashtra',17),(938,'Sangli','Maharashtra',17),(939,'Sangole','Maharashtra',17),(940,'Sasvad','Maharashtra',17),(941,'Satana','Maharashtra',17),(942,'Satara','Maharashtra',17),(943,'Savner','Maharashtra',17),(944,'Sawantwadi','Maharashtra',17),(945,'Shahade','Maharashtra',17),(946,'Shegaon','Maharashtra',17),(947,'Shendurjana','Maharashtra',17),(948,'Shirdi','Maharashtra',17),(949,'Shirpur-Warwade','Maharashtra',17),(950,'Shirur','Maharashtra',17),(951,'Shrigonda','Maharashtra',17),(952,'Shrirampur','Maharashtra',17),(953,'Sillod','Maharashtra',17),(954,'Sinnar','Maharashtra',17),(955,'Solapur','Maharashtra',17),(956,'Soyagaon','Maharashtra',17),(957,'Talegaon Dabhade','Maharashtra',17),(958,'Talode','Maharashtra',17),(959,'Tasgaon','Maharashtra',17),(960,'Tirora','Maharashtra',17),(961,'Tuljapur','Maharashtra',17),(962,'Tumsar','Maharashtra',17),(963,'Uchgaon','Maharashtra',17),(964,'Udgir','Maharashtra',17),(965,'Umarga','Maharashtra',17),(966,'Umarkhed','Maharashtra',17),(967,'Umred','Maharashtra',17),(968,'Uran','Maharashtra',17),(969,'Uran Islampur','Maharashtra',17),(970,'Vadgaon Kasba','Maharashtra',17),(971,'Vaijapur','Maharashtra',17),(972,'Vasai','Maharashtra',17),(973,'Virar','Maharashtra',17),(974,'Vita','Maharashtra',17),(975,'Wadgaon Road','Maharashtra',17),(976,'Wai','Maharashtra',17),(977,'Wani','Maharashtra',17),(978,'Wardha','Maharashtra',17),(979,'Warora','Maharashtra',17),(980,'Warud','Maharashtra',17),(981,'Washim','Maharashtra',17),(982,'Yavatmal','Maharashtra',17),(983,'Yawal','Maharashtra',17),(984,'Yevla','Maharashtra',17),(985,'Anjangaon','Maharashtra.Cl',17),(986,'Imphal*','Manipur',18),(987,'Kakching','Manipur',18),(988,'Lilong','Manipur',18),(989,'Mayang Imphal','Manipur',18),(990,'Thoubal','Manipur',18),(991,'Jowai','Meghalaya',19),(992,'Nongstoin','Meghalaya',19),(993,'Shillong*','Meghalaya',19),(994,'Tura','Meghalaya',19),(995,'Aizawl','Mizoram',20),(996,'Champhai','Mizoram',20),(997,'Lunglei','Mizoram',20),(998,'Saiha','Mizoram',20),(999,'Dimapur','Nagaland',21),(1000,'Kohima*','Nagaland',21),(1001,'Mokokchung','Nagaland',21),(1002,'Tuensang','Nagaland',21),(1003,'Wokha','Nagaland',21),(1004,'Zunheboto','Nagaland',21),(1005,'Anandapur','Orissa',22),(1006,'Anugul','Orissa',22),(1007,'Asika','Orissa',22),(1008,'Balangir','Orissa',22),(1009,'Balasore','Orissa',22),(1010,'Baleshwar','Orissa',22),(1011,'Bamra','Orissa',22),(1012,'Barbil','Orissa',22),(1013,'Bargarh','Orissa',22),(1014,'Bargarh','Orissa',22),(1015,'Baripada','Orissa',22),(1016,'Basudebpur','Orissa',22),(1017,'Belpahar','Orissa',22),(1018,'Berhampur','Orissa',22),(1019,'Bhadrak','Orissa',22),(1020,'Bhawanipatna','Orissa',22),(1021,'Bhuban','Orissa',22),(1022,'Bhubaneswar*','Orissa',22),(1023,'Biramitrapur','Orissa',22),(1024,'Brahmapur','Orissa',22),(1025,'Brajrajnagar','Orissa',22),(1026,'Burla','Orissa',22),(1027,'Byasanagar','Orissa',22),(1028,'Cuttack','Orissa',22),(1029,'Debagarh','Orissa',22),(1030,'Dhenkanal','Orissa',22),(1031,'Ganjam','Orissa',22),(1032,'Gunupur','Orissa',22),(1033,'Hinjilicut','Orissa',22),(1034,'Jagatsinghapur','Orissa',22),(1035,'Jajapur','Orissa',22),(1036,'Jaleswar','Orissa',22),(1037,'Jatani','Orissa',22),(1038,'Jeypur','Orissa',22),(1039,'Jharsuguda','Orissa',22),(1040,'Joda','Orissa',22),(1041,'Kantabanji','Orissa',22),(1042,'Karanjia','Orissa',22),(1043,'Kendrapara','Orissa',22),(1044,'Kendujhar','Orissa',22),(1045,'Khordha','Orissa',22),(1046,'Koraput','Orissa',22),(1047,'Kuchinda','Orissa',22),(1048,'Madhyamgram','Orissa',22),(1049,'Malkangiri','Orissa',22),(1050,'Nabarangapur','Orissa',22),(1051,'Paradip','Orissa',22),(1052,'Parlakhemundi','Orissa',22),(1053,'Pattamundai','Orissa',22),(1054,'Phulabani','Orissa',22),(1055,'Puri','Orissa',22),(1056,'Rairangpur','Orissa',22),(1057,'Rajagangapur','Orissa',22),(1058,'Raurkela','Orissa',22),(1059,'Rayagada','Orissa',22),(1060,'Sambalpur','Orissa',22),(1061,'Soro','Orissa',22),(1062,'Sunabeda','Orissa',22),(1063,'Sundargarh','Orissa',22),(1064,'Talcher','Orissa',22),(1065,'Titlagarh','Orissa',22),(1066,'Umarkote','Orissa',22),(1067,'Karaikal','Pondicherry',22),(1068,'Mahe','Pondicherry',22),(1069,'Pondicherry*','Pondicherry',22),(1070,'Yanam','Pondicherry',22),(1071,'Ahmedgarh','Punjab',24),(1072,'Amritsar','Punjab',24),(1073,'Barnala','Punjab',24),(1074,'Batala','Punjab',24),(1075,'Bathinda','Punjab',24),(1076,'Bhagha Purana','Punjab',24),(1077,'Budhlada','Punjab',24),(1078,'Chandigarh*','Punjab',24),(1079,'Dasua','Punjab',24),(1080,'Dhuri','Punjab',24),(1081,'Dinanagar','Punjab',24),(1082,'Faridkot','Punjab',24),(1083,'Fazilka','Punjab',24),(1084,'Firozpur','Punjab',24),(1085,'Firozpur Cantt.','Punjab',24),(1086,'Giddarbaha','Punjab',24),(1087,'Gobindgarh','Punjab',24),(1088,'Gurdaspur','Punjab',24),(1089,'Hoshiarpur','Punjab',24),(1090,'Jagraon','Punjab',24),(1091,'Jaitu','Punjab',24),(1092,'Jalalabad','Punjab',24),(1093,'Jalandhar','Punjab',24),(1094,'Jalandhar Cantt.','Punjab',24),(1095,'Jandiala','Punjab',24),(1096,'Kapurthala','Punjab',24),(1097,'Karoran','Punjab',24),(1098,'Kartarpur','Punjab',24),(1099,'Khanna','Punjab',24),(1100,'Kharar','Punjab',24),(1101,'Kot Kapura','Punjab',24),(1102,'Kurali','Punjab',24),(1103,'Longowal','Punjab',24),(1104,'Ludhiana','Punjab',24),(1105,'Malerkotla','Punjab',24),(1106,'Malout','Punjab',24),(1107,'Mansa','Punjab',24),(1108,'Maur','Punjab',24),(1109,'Moga','Punjab',24),(1110,'Mohali','Punjab',24),(1111,'Morinda','Punjab',24),(1112,'Mukatsar','Punjab',24),(1113,'Mukerian','Punjab',24),(1114,'Muktsar','Punjab',24),(1115,'Nabha','Punjab',24),(1116,'Nakodar','Punjab',24),(1117,'Nangal','Punjab',24),(1118,'Nawanshahr','Punjab',24),(1119,'Pathankot','Punjab',24),(1120,'Patiala','Punjab',24),(1121,'Patran','Punjab',24),(1122,'Patti','Punjab',24),(1123,'Phagwara','Punjab',24),(1124,'Phillaur','Punjab',24),(1125,'Qadian','Punjab',24),(1126,'Raikot','Punjab',24),(1127,'Rajpura','Punjab',24),(1128,'Rampura Phul','Punjab',24),(1129,'Rupnagar','Punjab',24),(1130,'Samana','Punjab',24),(1131,'Sangrur','Punjab',24),(1132,'Sirhind Fatehgarh Sahib','Punjab',24),(1133,'Sujanpur','Punjab',24),(1134,'Sunam','Punjab',24),(1135,'Talwara','Punjab',24),(1136,'Tarn Taran','Punjab',24),(1137,'Urmar Tanda','Punjab',24),(1138,'Zira','Punjab',24),(1139,'Zirakpur','Punjab',24),(1140,'Ajmer','Rajasthan',25),(1141,'Alwar','Rajasthan',25),(1142,'Bali','Rajasthan',25),(1143,'Bandikui','Rajasthan',25),(1144,'Banswara','Rajasthan',25),(1145,'Baran','Rajasthan',25),(1146,'Barmer','Rajasthan',25),(1147,'Beawar','Rajasthan',25),(1148,'Bharatpur','Rajasthan',25),(1149,'Bhilwara','Rajasthan',25),(1150,'Bhinmal','Rajasthan',25),(1151,'Bikaner','Rajasthan',25),(1152,'Bilara','Rajasthan',25),(1153,'Churu','Rajasthan',25),(1154,'Devgarh','Rajasthan',25),(1155,'Falna','Rajasthan',25),(1156,'Fatehpur','Rajasthan',25),(1157,'Hanumangarh','Rajasthan',25),(1158,'Harsawa','Rajasthan',25),(1159,'Jaipur*','Rajasthan',25),(1160,'Jaisalmer','Rajasthan',25),(1161,'Jaitaran','Rajasthan',25),(1162,'Jalore','Rajasthan',25),(1163,'Jhalawar','Rajasthan',25),(1164,'Jhunjhunu','Rajasthan',25),(1165,'Jodhpur','Rajasthan',25),(1166,'Kota','Rajasthan',25),(1167,'Lachhmangarh','Rajasthan',25),(1168,'Ladnu','Rajasthan',25),(1169,'Lakheri','Rajasthan',25),(1170,'Lalsot','Rajasthan',25),(1171,'Losal','Rajasthan',25),(1172,'Mahwa','Rajasthan',25),(1173,'Makrana','Rajasthan',25),(1174,'Malpura','Rajasthan',25),(1175,'Mandalgarh','Rajasthan',25),(1176,'Mandawa','Rajasthan',25),(1177,'Mangrol','Rajasthan',25),(1178,'Merta City','Rajasthan',25),(1179,'Mount Abu','Rajasthan',25),(1180,'Nadbai','Rajasthan',25),(1181,'Nagar','Rajasthan',25),(1182,'Nagaur','Rajasthan',25),(1183,'Nargund','Rajasthan',25),(1184,'Nasirabad','Rajasthan',25),(1185,'Nathdwara','Rajasthan',25),(1186,'Navalgund','Rajasthan',25),(1187,'Nawalgarh','Rajasthan',25),(1188,'Neem-Ka-Thana','Rajasthan',25),(1189,'Nelamangala','Rajasthan',25),(1190,'Nimbahera','Rajasthan',25),(1191,'Niwai','Rajasthan',25),(1192,'Nohar','Rajasthan',25),(1193,'Nokha','Rajasthan',25),(1194,'Pali','Rajasthan',25),(1195,'Phalodi','Rajasthan',25),(1196,'Phulera','Rajasthan',25),(1197,'Pilani','Rajasthan',25),(1198,'Pilibanga','Rajasthan',25),(1199,'Pindwara','Rajasthan',25),(1200,'Pipar City','Rajasthan',25),(1201,'Prantij','Rajasthan',25),(1202,'Pratapgarh','Rajasthan',25),(1203,'Raisinghnagar','Rajasthan',25),(1204,'Rajakhera','Rajasthan',25),(1205,'Rajaldesar','Rajasthan',25),(1206,'Rajgarh (Alwar)','Rajasthan',25),(1207,'Rajgarh (Churu)','Rajasthan',25),(1208,'Rajsamand','Rajasthan',25),(1209,'Ramganj Mandi','Rajasthan',25),(1210,'Ramngarh','Rajasthan',25),(1211,'Ratangarh','Rajasthan',25),(1212,'Rawatbhata','Rajasthan',25),(1213,'Rawatsar','Rajasthan',25),(1214,'Reengus','Rajasthan',25),(1215,'Sadri','Rajasthan',25),(1216,'Sadulshahar','Rajasthan',25),(1217,'Sagwara','Rajasthan',25),(1218,'Sambhar','Rajasthan',25),(1219,'Sanchore','Rajasthan',25),(1220,'Sangaria','Rajasthan',25),(1221,'Sardarshahar','Rajasthan',25),(1222,'Sawai Madhopur','Rajasthan',25),(1223,'Shahpura','Rajasthan',25),(1224,'Shahpura','Rajasthan',25),(1225,'Sheoganj','Rajasthan',25),(1226,'Sikar','Rajasthan',25),(1227,'Sirohi','Rajasthan',25),(1228,'Sojat','Rajasthan',25),(1229,'Sri Madhopur','Rajasthan',25),(1230,'Sujangarh','Rajasthan',25),(1231,'Sumerpur','Rajasthan',25),(1232,'Suratgarh','Rajasthan',25),(1233,'Taranagar','Rajasthan',25),(1234,'Todabhim','Rajasthan',25),(1235,'Todaraisingh','Rajasthan',25),(1236,'Tonk','Rajasthan',25),(1237,'Udaipur','Rajasthan',25),(1238,'Udaipurwati','Rajasthan',25),(1239,'Vijainagar','Rajasthan',25),(1240,'Gangtok*','Sikkim',26),(1241,'Arakkonam','Tamil Nadu',27),(1242,'Arcot','Tamil Nadu',27),(1243,'Aruppukkottai','Tamil Nadu',27),(1244,'Bhavani','Tamil Nadu',27),(1245,'Chengalpattu','Tamil Nadu',27),(1246,'Chennai*','Tamil Nadu',27),(1247,'Chinna salem','Tamil nadu',27),(1248,'Coimbatore','Tamil Nadu',27),(1249,'Coonoor','Tamil Nadu',27),(1250,'Cuddalore','Tamil Nadu',27),(1251,'Dharmapuri','Tamil Nadu',27),(1252,'Dindigul','Tamil Nadu',27),(1253,'Erode','Tamil Nadu',27),(1254,'Gudalur','Tamil Nadu',27),(1255,'Gudalur','Tamil Nadu',27),(1256,'Gudalur','Tamil Nadu',27),(1257,'Kanchipuram','Tamil Nadu',27),(1258,'Karaikudi','Tamil Nadu',27),(1259,'Karungal','Tamil Nadu',27),(1260,'Karur','Tamil Nadu',27),(1261,'Kollankodu','Tamil Nadu',27),(1262,'Lalgudi','Tamil Nadu',27),(1263,'Madurai','Tamil Nadu',27),(1264,'Nagapattinam','Tamil Nadu',27),(1265,'Nagercoil','Tamil Nadu',27),(1266,'Namagiripettai','Tamil Nadu',27),(1267,'Namakkal','Tamil Nadu',27),(1268,'Nandivaram-Guduvancheri','Tamil Nadu',27),(1269,'Nanjikottai','Tamil Nadu',27),(1270,'Natham','Tamil Nadu',27),(1271,'Nellikuppam','Tamil Nadu',27),(1272,'Neyveli','Tamil Nadu',27),(1273,'O Valley','Tamil Nadu',27),(1274,'Oddanchatram','Tamil Nadu',27),(1275,'P.N.Patti','Tamil Nadu',27),(1276,'Pacode','Tamil Nadu',27),(1277,'Padmanabhapuram','Tamil Nadu',27),(1278,'Palani','Tamil Nadu',27),(1279,'Palladam','Tamil Nadu',27),(1280,'Pallapatti','Tamil Nadu',27),(1281,'Pallikonda','Tamil Nadu',27),(1282,'Panagudi','Tamil Nadu',27),(1283,'Panruti','Tamil Nadu',27),(1284,'Paramakudi','Tamil Nadu',27),(1285,'Parangipettai','Tamil Nadu',27),(1286,'Pattukkottai','Tamil Nadu',27),(1287,'Perambalur','Tamil Nadu',27),(1288,'Peravurani','Tamil Nadu',27),(1289,'Periyakulam','Tamil Nadu',27),(1290,'Periyasemur','Tamil Nadu',27),(1291,'Pernampattu','Tamil Nadu',27),(1292,'Pollachi','Tamil Nadu',27),(1293,'Polur','Tamil Nadu',27),(1294,'Ponneri','Tamil Nadu',27),(1295,'Pudukkottai','Tamil Nadu',27),(1296,'Pudupattinam','Tamil Nadu',27),(1297,'Puliyankudi','Tamil Nadu',27),(1298,'Punjaipugalur','Tamil Nadu',27),(1299,'Rajapalayam','Tamil Nadu',27),(1300,'Ramanathapuram','Tamil Nadu',27),(1301,'Rameshwaram','Tamil Nadu',27),(1302,'Rasipuram','Tamil Nadu',27),(1303,'Salem','Tamil Nadu',27),(1304,'Sankarankoil','Tamil Nadu',27),(1305,'Sankari','Tamil Nadu',27),(1306,'Sathyamangalam','Tamil Nadu',27),(1307,'Sattur','Tamil Nadu',27),(1308,'Shenkottai','Tamil Nadu',27),(1309,'Sholavandan','Tamil Nadu',27),(1310,'Sholingur','Tamil Nadu',27),(1311,'Sirkali','Tamil Nadu',27),(1312,'Sivaganga','Tamil Nadu',27),(1313,'Sivagiri','Tamil Nadu',27),(1314,'Sivakasi','Tamil Nadu',27),(1315,'Srivilliputhur','Tamil Nadu',27),(1316,'Surandai','Tamil Nadu',27),(1317,'Suriyampalayam','Tamil Nadu',27),(1318,'Tenkasi','Tamil Nadu',27),(1319,'Thammampatti','Tamil Nadu',27),(1320,'Thanjavur','Tamil Nadu',27),(1321,'Tharamangalam','Tamil Nadu',27),(1322,'Tharangambadi','Tamil Nadu',27),(1323,'Theni Allinagaram','Tamil Nadu',27),(1324,'Thirumangalam','Tamil Nadu',27),(1325,'Thirunindravur','Tamil Nadu',27),(1326,'Thiruparappu','Tamil Nadu',27),(1327,'Thirupuvanam','Tamil Nadu',27),(1328,'Thiruthuraipoondi','Tamil Nadu',27),(1329,'Thiruvallur','Tamil Nadu',27),(1330,'Thiruvarur','Tamil Nadu',27),(1331,'Thoothukudi','Tamil Nadu',27),(1332,'Thuraiyur','Tamil Nadu',27),(1333,'Tindivanam','Tamil Nadu',27),(1334,'Tiruchendur','Tamil Nadu',27),(1335,'Tiruchengode','Tamil Nadu',27),(1336,'Tiruchirappalli','Tamil Nadu',27),(1337,'Tirukalukundram','Tamil Nadu',27),(1338,'Tirukkoyilur','Tamil Nadu',27),(1339,'Tirunelveli','Tamil Nadu',27),(1340,'Tirupathur','Tamil Nadu',27),(1341,'Tirupathur','Tamil Nadu',27),(1342,'Tiruppur','Tamil Nadu',27),(1343,'Tiruttani','Tamil Nadu',27),(1344,'Tiruvannamalai','Tamil Nadu',27),(1345,'Tiruvethipuram','Tamil Nadu',27),(1346,'Tittakudi','Tamil Nadu',27),(1347,'Udhagamandalam','Tamil Nadu',27),(1348,'Udumalaipettai','Tamil Nadu',27),(1349,'Unnamalaikadai','Tamil Nadu',27),(1350,'Usilampatti','Tamil Nadu',27),(1351,'Uthamapalayam','Tamil Nadu',27),(1352,'Uthiramerur','Tamil Nadu',27),(1353,'Vadakkuvalliyur','Tamil Nadu',27),(1354,'Vadalur','Tamil Nadu',27),(1355,'Vadipatti','Tamil Nadu',27),(1356,'Valparai','Tamil Nadu',27),(1357,'Vandavasi','Tamil Nadu',27),(1358,'Vaniyambadi','Tamil Nadu',27),(1359,'Vedaranyam','Tamil Nadu',27),(1360,'Vellakoil','Tamil Nadu',27),(1361,'Vellore','Tamil Nadu',27),(1362,'Vikramasingapuram','Tamil Nadu',27),(1363,'Viluppuram','Tamil Nadu',27),(1364,'Virudhachalam','Tamil Nadu',27),(1365,'Virudhunagar','Tamil Nadu',27),(1366,'Viswanatham','Tamil Nadu',27),(1367,'Agartala','Tripura',29),(1368,'Badharghat','Tripura',29),(1369,'Dharmanagar','Tripura',29),(1370,'Indranagar','Tripura',29),(1371,'Jogendranagar','Tripura',29),(1372,'Kailasahar','Tripura',29),(1373,'Khowai','Tripura',29),(1374,'Pratapgarh','Tripura',29),(1375,'Udaipur','Tripura',29),(1376,'Achhnera','Uttar Pradesh',30),(1377,'Adari','Uttar Pradesh',30),(1378,'Agra','Uttar Pradesh',30),(1379,'Aligarh','Uttar Pradesh',30),(1380,'Allahabad','Uttar Pradesh',30),(1381,'Amroha','Uttar Pradesh',30),(1382,'Azamgarh','Uttar Pradesh',30),(1383,'Badaun','Uttar Pradesh',30),(1384,'Bahraich','Uttar Pradesh',30),(1385,'Ballia','Uttar Pradesh',30),(1386,'Balrampur','Uttar Pradesh',30),(1387,'Banda','Uttar Pradesh',30),(1388,'Bareilly','Uttar Pradesh',30),(1389,'Bharthana','Uttar Pradesh',30),(1390,'Bijnaur','Uttar Pradesh',30),(1391,'Budaun','Uttar Pradesh',30),(1392,'Bulandshahr','Uttar Pradesh',30),(1393,'Chakeri','Uttar Pradesh',30),(1394,'Chandausi','Uttar Pradesh',30),(1395,'Charkhari','Uttar Pradesh',30),(1396,'Dadri','Uttar Pradesh',30),(1397,'Deoria','Uttar Pradesh',30),(1398,'Etah','Uttar Pradesh',30),(1399,'Etawah','Uttar Pradesh',30),(1400,'Faizabad','Uttar Pradesh',30),(1401,'Farrukhabad','Uttar Pradesh',30),(1402,'Fatehabad','Uttar Pradesh',30),(1403,'Fatehgarh','Uttar Pradesh',30),(1404,'Fatehpur','Uttar Pradesh',30),(1405,'Fatehpur','Uttar Pradesh',30),(1406,'Fatehpur Chaurasi','Uttar Pradesh',30),(1407,'Fatehpur Sikri','Uttar Pradesh',30),(1408,'Firozabad','Uttar Pradesh',30),(1409,'Ghatampur','Uttar Pradesh',30),(1410,'Ghaziabad','Uttar Pradesh',30),(1411,'Ghazipur','Uttar Pradesh',30),(1412,'Gorakhpur','Uttar Pradesh',30),(1413,'Greater Noida','Uttar Pradesh',30),(1414,'Hamirpur','Uttar Pradesh',30),(1415,'Hardoi','Uttar Pradesh',30),(1416,'Hastinapur','Uttar Pradesh',30),(1417,'Hathras','Uttar Pradesh',30),(1418,'Jais','Uttar Pradesh',30),(1419,'Jajmau','Uttar Pradesh',30),(1420,'Jaunpur','Uttar Pradesh',30),(1421,'Jhansi','Uttar Pradesh',30),(1422,'Kalpi','Uttar Pradesh',30),(1423,'Kanpur','Uttar Pradesh',30),(1424,'Kheri','Uttar Pradesh',30),(1425,'Kota','Uttar Pradesh',30),(1426,'Laharpur','Uttar Pradesh',30),(1427,'Lakhimpur','Uttar Pradesh',30),(1428,'Lal Gopalganj Nindaura','Uttar Pradesh',30),(1429,'Lalganj','Uttar Pradesh',30),(1430,'Lalitpur','Uttar Pradesh',30),(1431,'Lar','Uttar Pradesh',30),(1432,'Loni','Uttar Pradesh',30),(1433,'Lucknow*','Uttar Pradesh',30),(1434,'Mahoba','Uttar Pradesh',30),(1435,'Mathura','Uttar Pradesh',30),(1436,'Meerut','Uttar Pradesh',30),(1437,'Mirzapur','Uttar Pradesh',30),(1438,'Modinagar','Uttar Pradesh',30),(1439,'Moradabad','Uttar Pradesh',30),(1440,'Muradnagar','Uttar Pradesh',30),(1441,'Muzaffarnagar','Uttar Pradesh',30),(1442,'Nagina','Uttar Pradesh',30),(1443,'Najibabad','Uttar Pradesh',30),(1444,'Nakur','Uttar Pradesh',30),(1445,'Nanpara','Uttar Pradesh',30),(1446,'Naraura','Uttar Pradesh',30),(1447,'Naugawan Sadat','Uttar Pradesh',30),(1448,'Nautanwa','Uttar Pradesh',30),(1449,'Nawabganj','Uttar Pradesh',30),(1450,'Nehtaur','Uttar Pradesh',30),(1451,'Noida','Uttar Pradesh',30),(1452,'Noorpur','Uttar Pradesh',30),(1453,'Obra','Uttar Pradesh',30),(1454,'Orai','Uttar Pradesh',30),(1455,'Padrauna','Uttar Pradesh',30),(1456,'Palia Kalan','Uttar Pradesh',30),(1457,'Parasi','Uttar Pradesh',30),(1458,'Phulpur','Uttar Pradesh',30),(1459,'Pihani','Uttar Pradesh',30),(1460,'Pilibhit','Uttar Pradesh',30),(1461,'Pilkhuwa','Uttar Pradesh',30),(1462,'Powayan','Uttar Pradesh',30),(1463,'Pukhrayan','Uttar Pradesh',30),(1464,'Puranpur','Uttar Pradesh',30),(1465,'Purquazi','Uttar Pradesh',30),(1466,'Purwa','Uttar Pradesh',30),(1467,'Rae Bareli','Uttar Pradesh',30),(1468,'Rampur','Uttar Pradesh',30),(1469,'Rampur Maniharan','Uttar Pradesh',30),(1470,'Rasra','Uttar Pradesh',30),(1471,'Rath','Uttar Pradesh',30),(1472,'Renukoot','Uttar Pradesh',30),(1473,'Reoti','Uttar Pradesh',30),(1474,'Robertsganj','Uttar Pradesh',30),(1475,'Rudauli','Uttar Pradesh',30),(1476,'Rudrapur','Uttar Pradesh',30),(1477,'Sadabad','Uttar Pradesh',30),(1478,'Safipur','Uttar Pradesh',30),(1479,'Saharanpur','Uttar Pradesh',30),(1480,'Sahaspur','Uttar Pradesh',30),(1481,'Sahaswan','Uttar Pradesh',30),(1482,'Sahawar','Uttar Pradesh',30),(1483,'Sahjanwa','Uttar Pradesh',30),(1484,'Saidpur','Uttar Pradesh',30),(1485,'Sambhal','Uttar Pradesh',30),(1486,'Samdhan','Uttar Pradesh',30),(1487,'Samthar','Uttar Pradesh',30),(1488,'Sandi','Uttar Pradesh',30),(1489,'Sandila','Uttar Pradesh',30),(1490,'Sardhana','Uttar Pradesh',30),(1491,'Seohara','Uttar Pradesh',30),(1492,'Shahabad, Hardoi','Uttar Pradesh',30),(1493,'Shahabad, Rampur','Uttar Pradesh',30),(1494,'Shahganj','Uttar Pradesh',30),(1495,'Shahjahanpur','Uttar Pradesh',30),(1496,'Shamli','Uttar Pradesh',30),(1497,'Shamsabad, Agra','Uttar Pradesh',30),(1498,'Shamsabad, Farrukhabad','Uttar Pradesh',30),(1499,'Sherkot','Uttar Pradesh',30),(1500,'Shikarpur, Bulandshahr','Uttar Pradesh',30),(1501,'Shikohabad','Uttar Pradesh',30),(1502,'Shishgarh','Uttar Pradesh',30),(1503,'Siana','Uttar Pradesh',30),(1504,'Sikanderpur','Uttar Pradesh',30),(1505,'Sikandra Rao','Uttar Pradesh',30),(1506,'Sikandrabad','Uttar Pradesh',30),(1507,'Sirsaganj','Uttar Pradesh',30),(1508,'Sirsi','Uttar Pradesh',30),(1509,'Sitapur','Uttar Pradesh',30),(1510,'Soron','Uttar Pradesh',30),(1511,'Suar','Uttar Pradesh',30),(1512,'Sultanpur','Uttar Pradesh',30),(1513,'Sumerpur','Uttar Pradesh',30),(1514,'Tanda','Uttar Pradesh',30),(1515,'Tanda','Uttar Pradesh',30),(1516,'Tetri Bazar','Uttar Pradesh',30),(1517,'Thakurdwara','Uttar Pradesh',30),(1518,'Thana Bhawan','Uttar Pradesh',30),(1519,'Tilhar','Uttar Pradesh',30),(1520,'Tirwaganj','Uttar Pradesh',30),(1521,'Tulsipur','Uttar Pradesh',30),(1522,'Tundla','Uttar Pradesh',30),(1523,'Unnao','Uttar Pradesh',30),(1524,'Utraula','Uttar Pradesh',30),(1525,'Varanasi','Uttar Pradesh',30),(1526,'Vrindavan','Uttar Pradesh',30),(1527,'Warhapur','Uttar Pradesh',30),(1528,'Zaidpur','Uttar Pradesh',30),(1529,'Zamania','Uttar Pradesh',30),(1530,'Almora','Uttarakhand',31),(1531,'Bazpur','Uttarakhand',31),(1532,'Chamba','Uttarakhand',31),(1533,'Champawat','Uttarakhand',31),(1534,'Dehradun','Uttarakhand',31),(1535,'Haldwani','Uttarakhand',31),(1536,'Haridwar','Uttarakhand',31),(1537,'Jaspur','Uttarakhand',31),(1538,'Kashipur','Uttarakhand',31),(1539,'kichha','Uttarakhand',31),(1540,'Kotdwara','Uttarakhand',31),(1541,'Manglaur','Uttarakhand',31),(1542,'Mussoorie','Uttarakhand',31),(1543,'Nagla','Uttarakhand',31),(1544,'Nainital','Uttarakhand',31),(1545,'Pauri','Uttarakhand',31),(1546,'Pithoragarh','Uttarakhand',31),(1547,'Ramnagar','Uttarakhand',31),(1548,'Rishikesh','Uttarakhand',31),(1549,'Roorkee','Uttarakhand',31),(1550,'Rudrapur','Uttarakhand',31),(1551,'Sitarganj','Uttarakhand',31),(1552,'Tehri','Uttarakhand',31),(1553,'Adra','West Bengal',32),(1554,'Alipurduar','West Bengal',32),(1555,'Arambagh','West Bengal',32),(1556,'Asansol','West Bengal',32),(1557,'Baharampur','West Bengal',32),(1558,'Bally','West Bengal',32),(1559,'Balurghat','West Bengal',32),(1560,'Bankura','West Bengal',32),(1561,'Barakar','West Bengal',32),(1562,'Barasat','West Bengal',32),(1563,'Bardhaman','West Bengal',32),(1564,'Barrackpur','West Bengal',32),(1565,'Bidhan Nagar','West Bengal',32),(1566,'Chinsura','West Bengal',32),(1567,'Contai','West Bengal',32),(1568,'Cooch Behar','West Bengal',32),(1569,'Dalkhola','West Bengal',32),(1570,'Darjeeling','West Bengal',32),(1571,'Dhulian','West Bengal',32),(1572,'Dumdum','West Bengal',32),(1573,'Durgapur','West Bengal',32),(1574,'Haldia','West Bengal',32),(1575,'Howrah','West Bengal',32),(1576,'Hugli-Chuchura','West Bengal',32),(1577,'Islampur','West Bengal',32),(1578,'Jhargram','West Bengal',32),(1579,'Kalimpong','West Bengal',32),(1580,'Kharagpur','West Bengal',32),(1581,'Kolkata','West Bengal',32),(1582,'Konnagar','West Bengal',32),(1583,'Krishnanagar','West Bengal',32),(1584,'Mainaguri','West Bengal',32),(1585,'Mal','West Bengal',32),(1586,'Mathabhanga','West Bengal',32),(1587,'Medinipur','West Bengal',32),(1588,'Memari','West Bengal',32),(1589,'Monoharpur','West Bengal',32),(1590,'Murshidabad','West Bengal',32),(1591,'Nabadwip','West Bengal',32),(1592,'Naihati','West Bengal',32),(1593,'Panchla','West Bengal',32),(1594,'Pandua','West Bengal',32),(1595,'Paschim Punropara','West Bengal',32),(1596,'Purulia','West Bengal',32),(1597,'Raghunathpur','West Bengal',32),(1598,'Raiganj','West Bengal',32),(1599,'Rampurhat','West Bengal',32),(1600,'Ranaghat','West Bengal',32),(1601,'Sainthia','West Bengal',32),(1602,'Santipur','West Bengal',32),(1603,'Siliguri','West Bengal',32),(1604,'Sonamukhi','West Bengal',32),(1605,'Srirampore','West Bengal',32),(1606,'Suri','West Bengal',32),(1607,'Taki','West Bengal',32),(1608,'Tamluk','West Bengal',32),(1609,'Tarakeswar','West Bengal',32);
/*!40000 ALTER TABLE `city_mast` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_pages`
--

DROP TABLE IF EXISTS `cms_pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cms_pages` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT,
  `page_type` varchar(3) DEFAULT NULL,
  `page_name` varchar(45) DEFAULT NULL,
  `page_heading` varchar(200) DEFAULT NULL,
  `page_title` varchar(200) DEFAULT NULL,
  `meta_key` varchar(1000) DEFAULT NULL,
  `meta_desc` varchar(2000) DEFAULT NULL,
  `middle_panel` text,
  `page_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`page_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms_pages`
--

LOCK TABLES `cms_pages` WRITE;
/*!40000 ALTER TABLE `cms_pages` DISABLE KEYS */;
INSERT INTO `cms_pages` (`page_id`, `page_type`, `page_name`, `page_heading`, `page_title`, `meta_key`, `meta_desc`, `middle_panel`, `page_updated`) VALUES (1,'hp','index','Home Page','Home','AlifMart Home','AlifMart Home','<p style=\"text-align: justify;\"><span style=\"font-family: verdana, geneva, sans-serif; font-size: 12pt;\">Welcome to <strong>AlifMart.com</strong>, a platform where Buyers meet Sellers of Industrial Hardware, Tools, Machinery &amp; Equipment. If you are a Industrial Buyer, register yourself to get a completely new experience, we\'ve completely redefined the Industrial Shopping experience. If you are a Brick-n-Mortar Establishment selling Industrial Goods, then you will get the benefit of a fresh experience &amp; an Online Shop where you can showcase your unique offerings. Whether you are a Manufacturer, a Wholesaler/Distributor/Dealer or a Retailer, there is oppurtunity for each one of you to succeed using the Power of Internet.</span></p>\r\n<p style=\"text-align: justify;\"><span style=\"font-family: verdana, geneva, sans-serif; font-size: 12pt;\">On this page, you can navigate to the Top Right Corner to Register/Login as a Seller/Buyer. You can search for Products by simply typing a keyword in the Search box or you can also navigate to your required item by browsing the Category &amp; Sub-Category, where the Industrial items are categorized based on their use &amp; function.</span></p>\r\n<p style=\"text-align: justify;\"><span style=\"font-family: verdana, geneva, sans-serif; font-size: 12pt;\">On the footer of this page, you can read our Website use Terms &amp; Conditions, our Privacy Policy, Copyright information, and other relevant details.</span></p>\r\n<p style=\"text-align: justify;\"><span style=\"font-family: verdana, geneva, sans-serif; font-size: 12pt;\">Feel free to write us about your experience to feedback@alifmart.com or you can also leave a message in the form of SMS or call us on our Support line. Alternately, you may also find us on Facebook, Twitter &amp; Linkedin and add us, like us, comment us. If you are happy about our services feel free to update others and if you want to be our critic or give us constructive feedback, TELL US and we will be more happy to listen to your feedback &amp; improve wherever we have not matched your expectation.</span></p>\r\n<p style=\"text-align: justify;\"><span style=\"font-family: verdana, geneva, sans-serif; font-size: 12pt;\">We thank you for your patronage &amp; we hope you enjoy using this eCommerce Platform where opportunity is waiting for everyone. Let us together redefine the way we do Industrial Shopping!</span></p>','2015-11-09 06:37:46'),(2,'sr','seller_registration','Seller Registration','Seller Registration','AlifMart Seller','AlifMart Seller page',NULL,'2015-11-01 06:26:27'),(3,'mr','register','Buyer Registration',NULL,NULL,NULL,NULL,'2015-11-01 06:26:34'),(4,'cu','contact_us','Contact Us','Contact Us','AlifMart Contact Us','AlifMart Contact Us','<p style=\"text-align: center;\"><span style=\"font-size: 14pt; color: #000000;\"><strong>Contact Us</strong></span></p>\r\n<p>&nbsp;</p>\r\n<p><span style=\"font-size: 12pt;\"><strong>Registered Office:</strong></span></p>\r\n<p><span style=\"font-size: 12pt;\"><span style=\"font-size: 14pt;\">AlifMart</span>.com,</span></p>\r\n<p><span style=\"font-size: 12pt;\"><span lang=\"EN-US\" style=\"font-size: 9.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-ansi-language: EN-US; mso-fareast-language: EN-US; mso-bidi-language: AR-SA;\">PAP S-12, &nbsp;Near Indrayani Nagar Corner, </span></span></p>\r\n<p><span style=\"font-size: 12pt;\"><span lang=\"EN-US\" style=\"font-size: 9.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-ansi-language: EN-US; mso-fareast-language: EN-US; mso-bidi-language: AR-SA;\">Telco Road, MIDC, Bhosari, PUNE - 411026</span></span></p>\r\n<p>&nbsp;</p>\r\n<p><span style=\"font-size: 12pt;\"><span lang=\"EN-US\" style=\"font-size: 9.0pt; font-family: \'Arial\',\'sans-serif\'; mso-fareast-font-family: \'Times New Roman\'; mso-ansi-language: EN-US; mso-fareast-language: EN-US; mso-bidi-language: AR-SA;\">Phone Number: <strong>+91</strong> <strong>20</strong></span></span><strong><span lang=\"EN-US\" style=\"font-size: 9.0pt; font-family: \'Arial\',\'sans-serif\';\"> 4003 5353</span></strong></p>\r\n<p>Email: feedback<span style=\"font-size: 10pt;\">@alifmart.com</span></p>\r\n<p class=\"MsoNormal\">&nbsp;</p>\r\n<p>&nbsp;</p>','2015-11-10 03:31:08'),(5,'au','about_us','About Us','About Us','AboutUs','About AlifMart.com','<h1 class=\"c3\" style=\"text-align: center;\"><span class=\"c2\" style=\"font-family: verdana, geneva, sans-serif; color: #000000;\">About Us</span></h1>\r\n<p class=\"c1\" style=\"text-align: justify;\"><span style=\"font-family: verdana,geneva,sans-serif;\"><a name=\"h.gjdgxs\"></a>We are an eCommerce Business Process Management (BPM) partnership firm involved in promoting &amp; redefining the way Business can be done online. We&rsquo;re placed in the twin-township of Pune&rsquo;s Pimpri-Chinchwad region which is the heart of Industrial Development Corporation of Maharashtra (MIDC). We get the advantage of being where the action happens, the industrial belt where all sort of Industries are flourishing including but not limited to Automobile, Engineering, Chemical &amp; Pharmaceutical plants, Real Estate and Construction and as well as vast farming that takes place in &amp; around Pune region, specially the Sugarcane farming in the central &amp; southern parts of Maharashtra. </span></p>\r\n<p class=\"c1\" style=\"text-align: justify;\"><span style=\"font-family: verdana,geneva,sans-serif;\">We offer our portal services to all the Sellers who are involved in selling Industrial Hardware, Tools, Equipment as well as those that are in selling Manufacturing machineries, tools &amp; equipment, Farming equipment, Building Construction hardware, Plumbing material/tools, CP fittings &amp; Sanitary ware as well as Household tools needed in daily lives, &nbsp;and any such products that fall in these categories.</span></p>\r\n<p class=\"c1\" style=\"text-align: justify;\"><span style=\"font-family: verdana,geneva,sans-serif;\">We have created an automation platform for performing brick-and-mortar business using eCommerce model catering to B-2-C as well as B-2-B segments of the market. Using our unique offerings, sellers will be able to reach not just the local market, but can expand their reach to Pan India using our partner logistic services. The Users or Buyers will be able to get a 1-stop solution for their buying needs in the categories listed above &amp; get competitive rates &amp; flexible payment options through their preferred vendors (Sellers) using our BPM engine.</span></p>\r\n<p class=\"c1\" style=\"text-align: justify;\"><span style=\"font-family: verdana,geneva,sans-serif;\">This is an exciting phase of India&rsquo;s growth in the eCommerce sector &amp; best chance to grow using the power of Internet &amp; Online Marketing. We welcome all to be a part of this growth story..</span></p>','2015-11-01 07:10:22'),(6,'cs','customer_service','Customer Service',NULL,NULL,NULL,NULL,'2015-11-01 06:29:18'),(7,'ss','secure_shopping','Secure Shopping',NULL,NULL,NULL,NULL,'2015-11-01 06:29:26'),(8,'tc','terms','Terms & Condition','Terms of Use','T&C TermsAndConditions','Terms of Use of AlifMart.com','<h1 class=\"c0 c9\" style=\"text-align: justify;\"><span style=\"color: #000000;\">T<span class=\"c10\" style=\"font-family: verdana,geneva,sans-serif;\">erms of Use</span></span></h1>\r\n<h1 class=\"c0\" style=\"text-align: center;\"><span class=\"c11 c12\" style=\"font-family: verdana,geneva,sans-serif;\"><span style=\"font-size: 12pt;\">ALIFMART</span><sup><span style=\"font-size: 8pt;\">TM</span></sup> <span style=\"font-size: 12pt;\">User Agreement</span></span></h1>\r\n<p class=\"c0\" style=\"text-align: justify;\"><strong><span class=\"c5\" style=\"font-family: verdana,geneva,sans-serif;\">PLEASE READ THE FOLLOWING USER AGREEMENT CAREFULLY</span></strong></p>\r\n<p class=\"c0\" style=\"text-align: justify;\"><span class=\"c1\" style=\"font-family: verdana,geneva,sans-serif;\">The following article demonstrates User Agreement (Here-in-after referred to as an \"Agreement\") between AlifMart.com (Here-in-after referred to as \"ALIFMART\") and the users of the Website (\"You\", \"Your\", \"User/Users\").</span></p>\r\n<p class=\"c0\" style=\"text-align: justify;\"><span class=\"c1\" style=\"font-family: verdana,geneva,sans-serif;\">Before you subscribe to begin participating in using our web site,&nbsp;ALIFMART&nbsp;believes that user must have fully read, understood and accept the Agreement.</span></p>\r\n<p class=\"c0\" style=\"text-align: justify;\"><span class=\"c1\" style=\"font-family: verdana,geneva,sans-serif;\">If you do not agree to wish to be bound by Agreement, you may not access to or otherwise use the website.</span></p>\r\n<h2 class=\"c0 c6\" style=\"text-align: justify;\"><span style=\"color: #000000;\"><strong><span style=\"font-family: verdana,geneva,sans-serif;\">US<span class=\"c2\">ER AGREEMENT</span></span></strong></span></h2>\r\n<p class=\"c0\" style=\"text-align: justify;\"><span class=\"c1\" style=\"font-family: verdana,geneva,sans-serif;\">Your use of a&nbsp;ALIFMART&nbsp;website - www.alifmart.com (Here-in-after referred to as \"the website\") and services available on a website is governed by the following terms and&nbsp;conditions. This User Agreement for the website shall come into effect on June 1, 2015.</span></p>\r\n<h2 class=\"c0 c6\" style=\"text-align: justify;\"><strong><span style=\"font-family: verdana, geneva, sans-serif; color: #000000;\">AM<span class=\"c2\">ENDMENT TO USER(S) AGREEMENT</span></span></strong></h2>\r\n<p class=\"c0\" style=\"text-align: justify;\"><span style=\"font-family: verdana,geneva,sans-serif;\"><span class=\"c1\">ALIFMART&nbsp;may change, modify, amend, or update this agreement from time to time without any prior notification to user(s) and the amended and restated terms and conditions of use shall be effective immediately on posting. You are advised to regularly check for any amendments or updates to the terms and conditions contained in this User Agreement. If you do not adhere to the changes, you must stop using the service. Your continuous use of the service will signify your acceptance of the changed terms.</span></span></p>\r\n<h2 class=\"c0 c6\" style=\"text-align: justify;\"><strong><span style=\"font-family: verdana, geneva, sans-serif; color: #000000;\">U<span class=\"c2\">SER(S) ELIGIBILITY</span></span></strong></h2>\r\n<p class=\"c0\" style=\"text-align: justify;\"><span class=\"c1\" style=\"font-family: verdana,geneva,sans-serif;\">User(s) means any individual or business entity/organization that legally operates in India or in other countries, uses and has the right to use the services provided by&nbsp;ALIFMART. Our services are available only to those individuals or companies who can form legally binding contracts under the applicable law i.e. Indian Contract Act, 1872.&nbsp;As a minor if you wish to purchase or sell an item on the Website such purchase or sale may be made by your legal guardian or parents who have registered as users of the Website.&nbsp;ALIFMART&nbsp;advises its users that while accessing the website, they must follow/abide by the related laws.&nbsp;ALIFMART&nbsp;is not&nbsp;responsible&nbsp;for the possible consequences caused by your behavior during use of website.&nbsp;ALIFMART&nbsp;may, in its sole discretion, reserve the right to terminate your&nbsp;membership and refuse to provide you with access to the Website at any time.</span></p>\r\n<p class=\"c0\" style=\"text-align: justify;\"><span class=\"c1\" style=\"font-family: verdana,geneva,sans-serif;\">If you are registering as a business entity, you represent that you are duly authorized by the business entity to accept this User Agreement and you have the authority to bind that business entity to this User Agreement.</span></p>\r\n<h2 class=\"c0 c6\" style=\"text-align: justify;\"><strong><span style=\"font-family: verdana, geneva, sans-serif; color: #000000;\">E<span class=\"c2\">LECTRONIC COMMUNICATIONS</span></span></strong></h2>\r\n<p class=\"c0\" style=\"text-align: justify;\"><span class=\"c1\" style=\"font-family: verdana,geneva,sans-serif;\">When You use the Website or send emails or other data, information or communication to&nbsp;ALIFMART, You agree and understand that You are communicating with&nbsp;ALIFMART&nbsp;through electronic records and You consent to receive communications via electronic records from&nbsp;ALIFMART&nbsp;periodically and as and when required.</span></p>\r\n<h2 class=\"c0 c6\" style=\"text-align: justify;\"><strong><span style=\"font-family: verdana, geneva, sans-serif; color: #000000;\">R<span class=\"c2\">EGISTRATION AND YOUR ACCOUNT</span></span></strong></h2>\r\n<p class=\"c0\" style=\"text-align: justify;\"><span class=\"c1\" style=\"font-family: verdana,geneva,sans-serif;\">To become a Registered User(s) there is a proper procedure which is for the convenience of user(s) so that they can easily login and logout.</span></p>\r\n<p class=\"c0\" style=\"text-align: justify;\"><span style=\"font-family: verdana,geneva,sans-serif;\"><span class=\"c1\">User(s) can become a Registered User(s) by filling an on-line registration form on the website by giving desired information (name, contact information, details of its business, etc.).&nbsp;ALIFMART&nbsp;will establish an account (\"Account\") for the user(s) upon registration and assign a user alias (\"User ID\") and password (\"Password\") for login&nbsp;access to your Account. If you use the Website as Registered User, you are re</span><span class=\"c1\">sponsible for maintaining t</span><span class=\"c1\">he confidentiality of your User ID and Password. You are responsible for maintaining the confidentiality of your personal and non-personal information and for restricting access to your computer, computer system and computer network, and you are responsible for all activities that occur under your User ID and Password , email id and cell number as the case may be.</span></span></p>\r\n<p class=\"c0\" style=\"text-align: justify;\"><span class=\"c1\" style=\"font-family: verdana,geneva,sans-serif;\">If you register on behalf of a business entity, you represent that business entity and (a) you have the authority to bind the entity to terms and condition of use and/or this Agreement; (b) the address you use when registering is the principal place of business of such business entity; and (c) all other information submitted to&nbsp;ALIFMART&nbsp;during the registration process is true, accurate, current and complete.</span></p>\r\n<p class=\"c0\" style=\"text-align: justify;\"><span class=\"c1\" style=\"font-family: verdana,geneva,sans-serif;\">By becoming a Registered User, you consent to the inclusion of your personal and non-personal data in our online database and authorize&nbsp;ALIFMART&nbsp;to share such&nbsp;information with other user(s).&nbsp;ALIFMART&nbsp;may refuse registration and deny the membership and associated User ID and Password to any user for whatever reason. Website&nbsp;may suspend or terminate a registered membership at any time without any prior notification in interest of ALIFMART or general interest of its visitors/other members without giving any reason thereof.</span></p>\r\n<p class=\"c0\" style=\"text-align: justify;\"><span class=\"c1\" style=\"font-family: verdana,geneva,sans-serif;\">Despite my and organization\'s contact number(s) are on Do Not Call Registry, I consent to be contacted by&nbsp;ALIFMART&nbsp;through phone calls, SMS notifications or any other&nbsp;means of communication, in respect to the services provided by&nbsp;ALIFMART.</span></p>\r\n<p class=\"c0\" style=\"text-align: justify;\"><span class=\"c1\" style=\"font-family: verdana,geneva,sans-serif;\">You agree, inter alia, to: 1. Provide true, accurate, current and complete information about yourself as prompted by&nbsp;ALIFMART\'s registration form or provided by You 2.&nbsp;Maintain and promptly update the personal and non-personal Data to keep it true, accurate, current and complete. If you provide any information that is untrue, inaccurate, not current or incomplete or&nbsp;ALIFMART&nbsp;has reasonable grounds to suspect that such information is untrue, inaccurate, not current or incomplete, or not in&nbsp;accordance with the User Agreement,&nbsp;ALIFMART&nbsp;has the right to indefinitely suspend or terminate or block access of your membership with the Website&nbsp;and refuse to provide you with access to the Website.</span></p>\r\n<h2 class=\"c0 c6\" style=\"text-align: justify;\"><strong><span style=\"font-family: verdana,geneva,sans-serif;\">U<span class=\"c2\">SE OF www.alifmart.com</span></span></strong></h2>\r\n<p class=\"c0\" style=\"text-align: justify;\"><span class=\"c1\" style=\"font-family: verdana,geneva,sans-serif;\">You understand and agree that&nbsp;ALIFMART&nbsp;and the Website merely provide hosting services to its Registered Users and persons browsing/visiting the Website. All items&nbsp;advertised/listed and the contents therein are advertised and listed by Registered Users and are third party user generated contents.&nbsp;ALIFMART&nbsp;has no control over the&nbsp;third party user generated contents.</span></p>\r\n<p class=\"c0\" style=\"text-align: justify;\"><span class=\"c1\" style=\"font-family: verdana,geneva,sans-serif;\">Please note that in accordance with the Information Technology (Intermediaries guidelines) Rules 2011 in case of non-compliance with user agreement and privacy policy for access or usage of intermediary computer resource, the Intermediary has the right to immediately terminate the access or usage rights of the users to the computer resource of Intermediary and remove non-compliant information.</span></p>\r\n<p class=\"c0 c4\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"c0\" style=\"text-align: justify;\"><span class=\"c1 c11\" style=\"font-family: verdana,geneva,sans-serif;\">You shall not host, display, upload, modify, publish, transmit, update or share any information or share/list(s) any information or item that:</span></p>\r\n<ul class=\"c8 lst-kix_8u0m4vsm6hbi-0 start\">\r\n<li class=\"c0 c3\" style=\"text-align: justify;\"><span class=\"c1\" style=\"font-family: verdana,geneva,sans-serif;\">Belongs to another person and to which You do not have any right to;</span></li>\r\n<li class=\"c0 c3\" style=\"text-align: justify;\"><span class=\"c1\" style=\"font-family: verdana,geneva,sans-serif;\">Is grossly harmful, harassing, blasphemous, defamatory, obscene, pornographic, pedophilic, libelous, invasive of other\'s privacy, hateful, or racially, ethnically objectionable, disparaging, relating or encouraging money laundering or gambling, or otherwise unlawful in any manner whatever; or unlawfully threatening or unlawfully harassing including but not limited to \"indecent representation of women\" within the meaning of the Indecent Representation of Women (Prohibition) Act, 1986;</span></li>\r\n<li class=\"c0 c3\" style=\"text-align: justify;\"><span class=\"c1\" style=\"font-family: verdana,geneva,sans-serif;\">Harm minors in any way;</span></li>\r\n<li class=\"c0 c3\" style=\"text-align: justify;\"><span class=\"c1\" style=\"font-family: verdana,geneva,sans-serif;\">Infringes any patent, trademark, copyright or other proprietary rights or third party&rsquo;s trade secrets or rights of publicity or privacy or shall not be fraudulent or involve the sale of counterfeit or stolen items;</span></li>\r\n<li class=\"c0 c3\" style=\"text-align: justify;\"><span class=\"c1\" style=\"font-family: verdana,geneva,sans-serif;\">Violates any law for the time being in force;</span></li>\r\n<li class=\"c0 c3\" style=\"text-align: justify;\"><span class=\"c1\" style=\"font-family: verdana,geneva,sans-serif;\">Deceives or misleads the addressee/ users about the origin of such messages or communicates any information which is grossly offensive or menacing in nature;</span></li>\r\n<li class=\"c0 c3\" style=\"text-align: justify;\"><span class=\"c1\" style=\"font-family: verdana,geneva,sans-serif;\">Impersonate another person;</span></li>\r\n<li class=\"c0 c3\" style=\"text-align: justify;\"><span class=\"c1\" style=\"font-family: verdana,geneva,sans-serif;\">Contains software viruses or any other computer code, files or programs designed to interrupt, destroy or limit the functionality of any computer resource; or contains any Trojan horses, worms, time bombs, or other computer programming routines that may damage, detrimentally interfere with, diminish value of, surreptitiously intercept or expropriate any system, data or personal information;</span></li>\r\n<li class=\"c0 c3\" style=\"text-align: justify;\"><span class=\"c1\" style=\"font-family: verdana,geneva,sans-serif;\">Threatens the unity, integrity, defense, security or sovereignty of India, friendly relations with foreign states, or public order or causes incitement to the commission of any cognizable offence or prevents investigation of any offence or is insulting any other nation.</span></li>\r\n<li class=\"c0 c3\" style=\"text-align: justify;\"><span class=\"c1\" style=\"font-family: verdana,geneva,sans-serif;\">Shall not be false, inaccurate or misleading;</span></li>\r\n<li class=\"c0 c3\" style=\"text-align: justify;\"><span class=\"c1\" style=\"font-family: verdana,geneva,sans-serif;\">Shall not, directly or indirectly, offer, attempt to offer, trade or attempt to trade in any item, the dealing of which is prohibited or restricted in any manner under the provisions of any applicable law, rule, regulation or guideline for the time being in force.</span></li>\r\n<li class=\"c0 c3\" style=\"text-align: justify;\"><span class=\"c1\" style=\"font-family: verdana,geneva,sans-serif;\">Shall not create liability for us or cause us to lose (in whole or in part) the services of our ISPs or other suppliers; and</span></li>\r\n<li class=\"c0 c3\" style=\"text-align: justify;\"><span class=\"c1\" style=\"font-family: verdana,geneva,sans-serif;\">Shall not link directly or indirectly to or include descriptions of items, goods or services that are prohibited under the User Agreement or any other applicable law for the time being in force including but not limited to the Drugs and Cosmetics Act, 1940, the Drugs And Magic Remedies (Objectionable Advertisements) Act, 1954, the Indian Penal Code, 1860, Information Technology Act 2000 as amended time to time and rules there under.</span></li>\r\n<li class=\"c0 c3\" style=\"text-align: justify;\"><span class=\"c1\" style=\"font-family: verdana,geneva,sans-serif;\">You shall at all times ensure full compliance with the applicable provisions of the Information Technology Act, 2000 and rules thereunder as applicable and as amended from time to time and also all applicable Domestic laws, rules and regulations (including the provisions of any applicable Exchange Control Laws or Regulations in Force) and International Laws, Foreign Exchange Laws, Statutes, Ordinances and Regulations (including, but not limited to Sales Tax/ VAT, Income Tax, Service Tax, Central Excise, Custom Duty, Local Levies) regarding your use of our services. You shall not engage in any transaction in an item or service, which is prohibited by the provisions of any applicable law including exchange control laws or regulations for the time being in force. In particular you shall ensure that if any of your items listed on the Website qualifies as an \"Antiquity\" or \"Art treasure\" as defined in the Act (\"Artwork\"), you shall indicate that such Artwork is \"non-exportable\" and sold subject to the provisions of the Arts and Antiquities Act.</span></li>\r\n<li class=\"c0 c3\" style=\"text-align: justify;\"><span class=\"c1\" style=\"font-family: verdana,geneva,sans-serif;\">User(s) hereby grant an irrevocable, perpetual, worldwide and royalty-free, sub-licensable (through multiple tiers) license to ALIFMART to display and use all information provided by them in accordance with the purposes set forth in agreement and to exercise the copyright, publicity, and database rights you have in such material or information, in any form of media, third party copyrights, trademarks, trade secret rights, patents and other personal or proprietary rights affecting or relating to material or information displayed on the web site, including but not limited to rights of personality and rights of privacy, or affecting or relating to products that are offered or displayed on the website. ALIFMART will only use Your Information in accordance with the User Agreement and&nbsp;ALIFMART&nbsp;Privacy Policy. You represent and confirm that you shall have absolute right, title and authority to deal in and offer for sale such items,&nbsp;goods or products.</span></li>\r\n<li class=\"c0 c3\" style=\"text-align: justify;\"><span class=\"c1\" style=\"font-family: verdana,geneva,sans-serif;\">From time to time, you shall be responsible for providing information relating to the items or services proposed to be sold by you. In this connection, you undertake that all such information shall be accurate in all respects. You shall not exaggerate or over emphasize the item-specifics of such items or services so as to mislead other Users in any manner.</span></li>\r\n</ul>','2015-11-04 07:36:17'),(9,'pv','privacy','Privacy Policy','Privacy Policy','Privacy Policy','Privacy Policy document','<h1 class=\"c0 c5\" style=\"text-align: center;\"><a name=\"h.xctap0l4kbqe\"></a><span class=\"c3 c4\">Privacy Policy</span></h1>\r\n<p class=\"c0\" style=\"text-align: justify;\"><span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>.com&nbsp;(Here-in-after referred to as \"<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>\") is committed to safeguarding its user\'s privacy (Here-in-after referred to as &ldquo;Your&rdquo;,\"You\") accessing its website (i.e.alifmart.com)&nbsp;and has provided this Privacy Policy &nbsp;to familiarize You with the manner in which&nbsp;<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>&nbsp;collects, uses and discloses Your information collected&nbsp;through the Website.</p>\r\n<p class=\"c0\" style=\"text-align: justify;\">Before you subscribe to and/or begin participating in or using website,&nbsp;<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>&nbsp;believes that You have fully read, understood and accept the terms and conditions of this&nbsp;privacy policy.</p>\r\n<p class=\"c0\" style=\"text-align: justify;\">The Policy applies to all information that&nbsp;<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>&nbsp;has about You and Your account.</p>\r\n<p class=\"c0\" style=\"text-align: justify;\">If you do not agree to or wish to be bound by the terms and conditions of this privacy policy, please do not proceed further to use this website.</p>\r\n<p class=\"c0\" style=\"text-align: justify;\">This Policy should be at all times read along with the <a class=\"c2\" href=\"http://www.google.com/url?q=http%3A%2F%2Fwww.alifmart.com%2FTerms.html&amp;sa=D&amp;sntz=1&amp;usg=AFQjCNFqat1bNdJdMd5FCCKYTMfZKlWnNg\">Terms of Use</a> of the Website.</p>\r\n<p class=\"c0\" style=\"text-align: justify;\"><span class=\"c3\">&nbsp;</span></p>\r\n<p class=\"c0\" style=\"text-align: justify;\"><span style=\"color: #000000;\"><strong><span class=\"c3\">COLLECTION OF INFORMATION</span></strong></span></p>\r\n<p class=\"c0\" style=\"text-align: justify;\"><span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>&nbsp;collects personal and non-personal information when they register to gain access to the services provided by the&nbsp;<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>&nbsp;or at other specific instances when&nbsp;they are requested to provide us with their personal &amp; non-personal information (&ldquo;Information&rdquo;). Personal Information shall include, but not limited to, information regarding Your name, date of birth, gender, address, telephone number, e-mail address, etc. You can always choose not to provide information and in such cases, if the information required is classified as mandatory, You will not be able to avail of that part of the Services, features or content. You can add or update Your Personal Information on a regular basis. When You update information,&nbsp;<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>&nbsp;will keep a copy of the prior version for its records.</p>\r\n<p class=\"c0\" style=\"text-align: justify;\"><span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>\'s primary goal in collecting information is to provide the user with a customized experience on our websites. This includes personalized services, interactive communication and other services.&nbsp;<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>&nbsp;collects Your Personal Information in order to record, support and facilitate Your participation in the activities You&nbsp;select, track Your preferences, to notify You of any updated information and new activities and other related functions offered by&nbsp;<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>, keep You informed&nbsp;about latest content available on the Website, special offers, and other products and services of&nbsp;<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>, to provide You with a customized Website&nbsp;experience, to assist You with customer service or technical support issues, to follow up with You after Your visit, to otherwise support Your relationship with&nbsp;<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>&nbsp;or to prevent fraud and unlawful use.</p>\r\n<p class=\"c0\" style=\"text-align: justify;\">Certain information may be collected when you visit the Website and such information may be stored in server logs in the form of data. Through this data&nbsp;<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>&nbsp;understand the use and number of user visiting the Website. Some or all data collected may be shared with the sponsors, investors, advertisers, developer,&nbsp;strategic business partners of&nbsp;<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>. While using the Website, ALIFMART\'s servers (hosted by a third party service provider) may collect information indirectly&nbsp;and automatically about Your activities on the Website; for instance by way of cookies.</p>\r\n<p class=\"c0\" style=\"text-align: justify;\">Promotions that run on our website may be sponsored by companies other than&nbsp;<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>&nbsp;or may be co-sponsored by&nbsp;<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>&nbsp;and another company. We use third-party&nbsp;service providers to serve ads on our behalf across the internet and sometimes on the Website. They may&nbsp;collect information about Your&nbsp;visits to our Website.&nbsp;<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>&nbsp;uses the log file which includes but not limited to internet protocol (IP) addresses, browser software, number of clicks, number of unique visitors,&nbsp;internet service provider, exit/referring pages, type of platform, date/time stamp, screen resolution etc. for analysis that helps us provide you improved user experience and services.</p>\r\n<p class=\"c0\" style=\"text-align: justify;\">We record buying and browsing activities of our users including but not limited to YOUR contact details and profiles and uses the same to provide value-added services to our users.</p>\r\n<p class=\"c0\" style=\"text-align: justify;\">We use them to administer the site, track a user\'s movement and gather broad demographic information for aggregate use. Once a user registers, he/she is no longer anonymous to&nbsp;<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>&nbsp;and it is deemed that the user has given&nbsp;<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span><sup style=\"font-family: verdana, geneva, sans-serif;\">TM</sup>&nbsp;the right to use the personal &amp; non personal information.</p>\r\n<p class=\"c0\" style=\"text-align: justify;\">Please note our website is also not a risks proof website.</p>\r\n<p class=\"c0\" style=\"text-align: justify;\"><span class=\"c3\">&nbsp;</span></p>\r\n<p class=\"c0\" style=\"text-align: justify;\"><strong><span class=\"c3\">USE OF INFORMATION</span></strong></p>\r\n<p class=\"c0\" style=\"text-align: justify;\">Business information is used to display the user\'s business listing or product offerings across our website to fetch maximum business opportunities for the user. If You upload any content on the Website and the same may be available to the other users of the Website.&nbsp;<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>&nbsp;will not be liable for the disclosure and dissemination of&nbsp;such content to any third parties. Once the user\'s content is displayed on our website, the user may start receiving business enquiries through email, phone calls or SMS notifications, from third parties that might or might not be of their interest,&nbsp;<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>&nbsp;does not exercise any control over it.</p>\r\n<p class=\"c0\" style=\"text-align: justify;\"><span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>&nbsp;may, if You so choose, send direct advertisement mailers to You at the address given by You which could contain details of the products or services displayed on&nbsp;<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>&nbsp;or of any third party not associated with&nbsp;<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>. You have the option to opt-out of this direct or third party mailer by clicking at the unsubscribe&nbsp;link&nbsp;option attached to e-mail.&nbsp;<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>&nbsp;respects Your privacy and if You do not want to receive e-mail or other communications from&nbsp;<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>.</p>\r\n<p class=\"c0\" style=\"text-align: justify;\"><span class=\"c3\">&nbsp;</span></p>\r\n<p class=\"c0\" style=\"text-align: justify;\"><span style=\"color: #000000;\"><strong><span class=\"c3\">DISCLOSURE</span></strong></span></p>\r\n<p class=\"c0\" style=\"text-align: justify;\">In situations when&nbsp;<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>&nbsp;is legally obligated to disclose information to the government or other third parties,&nbsp;<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>&nbsp;will do so.</p>\r\n<p class=\"c0\" style=\"text-align: justify;\"><span class=\"c3\">&nbsp;</span></p>\r\n<p class=\"c0\" style=\"text-align: justify;\"><strong><span class=\"c3\">SHARING OF INFORMATION</span></strong></p>\r\n<p class=\"c0\" style=\"text-align: justify;\">As a general rule,&nbsp;<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span><sup style=\"font-family: verdana, geneva, sans-serif;\">TM</sup>&nbsp;will not disclose or share any of the user\'s personally identifiable information except when&nbsp;<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>&nbsp;has the user\'s permission or under special&nbsp;circumstances, such as when&nbsp;<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>&nbsp;believes in good faith that the law requires it or as permitted in terms of this policy.</p>\r\n<p class=\"c0 c1\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"c0\" style=\"text-align: justify;\"><span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>&nbsp;may also disclose account information in special cases when&nbsp;<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>&nbsp;has reasons to believe that disclosing this information is necessary to identify, contact or&nbsp;bring legal action against someone who may be violating&nbsp;<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>\'s Terms of Services or may be causing injury to or interference with (either&nbsp;intentionally or unintentionally)&nbsp;<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>\'s rights or property, other&nbsp;<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>&nbsp;website users, or if&nbsp;<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>&nbsp;deems it necessary to maintain, service, and&nbsp;improve&nbsp;its&nbsp;products and services. Personal information collected may be transferred and shared in the event of a sale.</p>\r\n<p class=\"c0\" style=\"text-align: justify;\">You are required to submit Your information at the time of making an online purchase on the Website.&nbsp;<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>&nbsp;uses an online payment gateway that is operated by a third&nbsp;party and the information that You share with&nbsp;<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>&nbsp;is transferred and shared with such third party payment gateway operator. The said operator&nbsp;may also have access to Your online purchase history/details that You make from the Website. Extremely sensitive information like Your credit-card details are transacted upon secure sites of approved payment gateways which are digitally under encryption, thereby providing the highest possible degree of care as per current technology. You are advised, however, that internet technology is not 100% safe and You should exercise discretion on using the same.</p>\r\n<p class=\"c0\" style=\"text-align: justify;\"><span class=\"c3\">&nbsp;</span></p>\r\n<p class=\"c0\" style=\"text-align: justify;\"><span style=\"color: #000000;\"><strong><span class=\"c3\">LINKS TO THIRD PARTY SITES</span></strong></span></p>\r\n<p class=\"c0\" style=\"text-align: justify;\">Links to third party sites are provided by website as a convenience to user(s) and&nbsp;<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>&nbsp;does not have any control over such sites i.e. content and resources provided by&nbsp;them.&nbsp;<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>&nbsp;may allow user(s) access to content, products or services offered by third parties through hyperlinks (in the form of word link, banners,&nbsp;channels or otherwise) to such Third Party\'s web site. You are cautioned to read such sites\' terms and conditions and/or privacy policies before using such sites in order to be aware of the terms and conditions of your use of such sites.&nbsp;<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>&nbsp;believes that user(s) acknowledge that&nbsp;<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>&nbsp;has no control over such&nbsp;third&nbsp;party\'s site, does not monitor such sites, and&nbsp;<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>&nbsp;shall not be responsible or liable to anyone for such third party site, or any content, products or&nbsp;services made available on such a site.</p>\r\n<p class=\"c0\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"c0\" style=\"text-align: justify;\"><strong><span class=\"c3\">PROTECTION OF INFORMATION</span></strong></p>\r\n<p class=\"c0\" style=\"text-align: justify;\"><span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>&nbsp;takes necessary steps, within its limits of commercial viability and necessity, to ensure that the user\'s information is treated securely.</p>\r\n<p class=\"c0\" style=\"text-align: justify;\">We request our users to sign out of their&nbsp;<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>&nbsp;account and close their browser window when they have finished their work. This is to ensure that others cannot access&nbsp;their personal or business information and correspondence, if the user shares the computer with someone else or is using a computer in a public place.</p>\r\n<p class=\"c0\" style=\"text-align: justify;\">Unfortunately, no data transmission over the Internet can be guaranteed to be 100% secure. As a result, while&nbsp;<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>&nbsp;strives to protect the user\'s personal &amp; business&nbsp;information, it cannot ensure the security of any information transmitted to&nbsp;<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>&nbsp;and you do so at your own risk. Once&nbsp;<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>&nbsp;receives your transmission,&nbsp;it&nbsp;makes best efforts to ensure its security in its systems. Please keep in mind that whenever you post personal &amp; business information online, that is accessible to the public, you may receive unsolicited messages from other parties.</p>\r\n<p class=\"c0\" style=\"text-align: justify;\"><span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>&nbsp;is not responsible for any breach of security or for any actions of any third parties that receive Your Information. The Website also linked to many other sites and we are not/shall be not responsible for their privacy policies or practices as it is beyond our control.</p>\r\n<p class=\"c0\" style=\"text-align: justify;\">Notwithstanding anything contained in this Policy or elsewhere,&nbsp;<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>&nbsp;shall not be held responsible for any loss, damage or misuse of Your Information, if such loss,&nbsp;damage or misuse is attributable to a Force Majeure Event (as defined in Terms of Use).</p>\r\n<p class=\"c0\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"c0\" style=\"text-align: justify;\"><strong><span class=\"c3\">DISTRIBUTION OF INFORMATION</span></strong></p>\r\n<p class=\"c0\" style=\"text-align: justify;\"><span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>&nbsp;may, from time to time, send its users emails regarding its products and services. &nbsp;<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>&nbsp;constantly tries and improves the website for better efficiency, more relevancy, innovative business matchmaking and better personal preferences.&nbsp;<span style=\"font-family: verdana, geneva, sans-serif;\">ALIFMART</span>&nbsp;may allow direct mails using its own scripts (without disclosing the&nbsp;email address) with respect/pertaining to the products and services of third parties that it feels may be of interest to the user or if the user has shown interest in the same.</p>\r\n<p class=\"c0\" style=\"text-align: justify;\"><span class=\"c3\">&nbsp;</span></p>\r\n<p class=\"c0\" style=\"text-align: justify;\"><strong><span class=\"c3\">CHANGES IN PRIVACY POLICY</span></strong></p>\r\n<p class=\"c0\" style=\"text-align: justify;\">The policy may change from time to time so please check periodically. The revised Policy shall be made available on the Website. Your continued use of the Website, following changes to the Policy, will constitute your acceptance of those changes. Any disputes arising under this Policy shall be governed by the laws of India.</p>','2015-11-04 07:41:58'),(10,'rp','returns','Returns Policy','Cancellation and Return Policy','Cancellation Return Moneyback','Cancel order at AlifMart.com, return item to AlifMart.com, get the money back','<h1 class=\"c2 c6\" style=\"text-align: center;\"><a name=\"h.h03jaqvvp2gg\"></a><span class=\"c1 c4\" style=\"font-family: verdana,geneva,sans-serif;\">CANCELLATION AND RETURN POLICY</span></h1>\r\n<p class=\"c2\" style=\"text-align: justify;\"><span style=\"font-family: verdana, geneva, sans-serif; font-size: 10pt;\">100% MoneyBack if issue not resolved in maximum 30 days via same mode.</span></p>\r\n<p style=\"text-align: justify;\"><span style=\"font-family: verdana, geneva, sans-serif; font-size: 10pt; color: #333333;\">AlifMart will allow return of products in following cases:</span></p>\r\n<ul class=\"c3 lst-kix_ob2m79fo58gs-0 start\" style=\"text-align: justify;\">\r\n<li><span style=\"font-family: verdana, geneva, sans-serif; font-size: 10pt;\">Wrong order (wrong size, colour, quantity or material related issues)</span></li>\r\n<li class=\"c0\"><span style=\"font-family: verdana, geneva, sans-serif; font-size: 10pt;\">Defective product,</span></li>\r\n<li class=\"c0\"><span style=\"font-family: verdana, geneva, sans-serif; font-size: 10pt;\">Damaged product, or</span></li>\r\n<li class=\"c0\"><span style=\"font-family: verdana, geneva, sans-serif; font-size: 10pt;\">Significantly different from the description given by the merchant</span></li>\r\n</ul>\r\n<p class=\"c2\" style=\"text-align: justify;\"><span style=\"font-family: verdana, geneva, sans-serif; font-size: 10pt;\">But we are not able to accept returns in following cases:</span></p>\r\n<ul class=\"c3 lst-kix_a085wmith9ij-0 start\" style=\"text-align: justify;\">\r\n<li><span style=\"font-family: verdana, geneva, sans-serif; font-size: 10pt;\">Product is used/worn or altered</span></li>\r\n<li class=\"c0\"><span style=\"font-family: verdana, geneva, sans-serif; font-size: 10pt;\">Physical damage to the box or to the product</span></li>\r\n<li class=\"c0\"><span style=\"font-family: verdana, geneva, sans-serif; font-size: 10pt;\">If returned without original packaging and accessories</span></li>\r\n<li class=\"c0\"><span style=\"font-family: verdana, geneva, sans-serif; font-size: 10pt;\">If sealed package of branded products is open</span></li>\r\n</ul>\r\n<p class=\"c2\" style=\"text-align: justify;\"><span style=\"font-family: verdana, geneva, sans-serif; font-size: 10pt;\">In case of Return/Replacement, following conditions should satisfy:</span></p>\r\n<ul class=\"c3 lst-kix_z67mficc23r9-0 start\" style=\"text-align: justify;\">\r\n<li class=\"c0\"><span style=\"font-family: verdana, geneva, sans-serif; font-size: 10pt;\">Product must be in its original condition</span></li>\r\n<li class=\"c0\"><span style=\"font-family: verdana, geneva, sans-serif; font-size: 10pt;\">Only unused, unaltered, unsoiled products will be accepted</span></li>\r\n<li class=\"c0\"><span style=\"font-family: verdana, geneva, sans-serif; font-size: 10pt;\">Brand packaging should be intact</span></li>\r\n<li class=\"c0\"><span style=\"font-family: verdana, geneva, sans-serif; font-size: 10pt;\">Replacements will be made if product is available on website</span></li>\r\n</ul>\r\n<p class=\"c2\" style=\"text-align: justify;\"><span style=\"font-family: verdana, geneva, sans-serif; font-size: 10pt;\">If you have received a damaged product or empty parcel or any item is missing or for any other reason you want to return product, please contact us within 15 days of receiving the order at our helpline number or email at <span class=\"c1\">returns@alifmart.com</span>. You can raise a return request by:</span></p>\r\n<ul class=\"c3 lst-kix_xs6o51gqticy-0 start\" style=\"text-align: justify;\">\r\n<li><span style=\"font-family: verdana, geneva, sans-serif; font-size: 10pt;\">Contact us at our helpline number and provide Order ID details.</span></li>\r\n<li class=\"c0\"><span style=\"font-family: verdana, geneva, sans-serif; font-size: 10pt;\">Customer Support will confirm the return request and will inform you about the pickup process.</span></li>\r\n<li class=\"c0\"><span style=\"font-family: verdana, geneva, sans-serif; font-size: 10pt;\">Please ensure that product is in unused and original condition. Include all labels, original packing and invoice along with the product.</span></li>\r\n</ul>\r\n<p class=\"c2\" style=\"text-align: justify;\"><span style=\"font-family: verdana, geneva, sans-serif; font-size: 10pt;\">In case you want to cancel your order, you need to call at our help line number or email at <span class=\"c1\">support@alifmart.com</span>&nbsp;at any point of time before order delivery. AlifMart may also want to cancel order under following scenarios:</span></p>\r\n<ul class=\"c3 lst-kix_cg0d4bligshh-0 start\" style=\"text-align: justify;\">\r\n<li class=\"c0\"><span style=\"font-family: verdana, geneva, sans-serif; font-size: 10pt;\">Product no longer available or temporarily out of stock</span></li>\r\n<li class=\"c0\"><span style=\"font-family: verdana, geneva, sans-serif; font-size: 10pt;\">Non-serviceable locations</span></li>\r\n<li class=\"c0\"><span style=\"font-family: verdana, geneva, sans-serif; font-size: 10pt;\">Restriction on quantity ordered</span></li>\r\n<li class=\"c0\"><span style=\"font-family: verdana, geneva, sans-serif; font-size: 10pt;\">Problems identified by our credit and fraud avoidance department</span></li>\r\n<li class=\"c0\" style=\"text-align: left;\"><span style=\"font-size: 10pt;\">Incomplete or inaccurate address</span></li>\r\n</ul>\r\n<p class=\"c2\" style=\"text-align: justify;\"><span style=\"font-family: verdana, geneva, sans-serif; font-size: 10pt;\">In case, customer opted for COD options, then before accepting order we require additional information from customer for verification. After verification, AlifMart can cancel order if there is any discrepancy in the information provided by customer.</span></p>','2015-11-01 07:12:57'),(11,'sh','shipping','Shipping',NULL,NULL,NULL,NULL,'2015-11-01 06:54:58'),(12,'fq','faq','FAQ','FAQs','AlifMart FAQs XBA Execution by AlifMart','AlifMart FAQs, Execution by AlifMart','<p class=\"MsoNormal\"><span style=\"font-size: 8.5pt; font-family: Verdana, sans-serif;\">&nbsp;</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 5pt 0cm; text-align: center;\" align=\"center\"><strong><span style=\"font-size: 14pt; font-family: Verdana, sans-serif;\">General FAQs</span></strong></p>\r\n<p class=\"MsoNormal\" style=\"margin: 5pt 0cm; text-align: center;\" align=\"center\"><strong><span style=\"font-size: 14pt; font-family: Verdana, sans-serif;\">________________________________</span></strong></p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify; text-indent: 36pt;\"><strong><span style=\"font-size: 18pt; font-family: Verdana, sans-serif;\">&nbsp;</span></strong><strong><span style=\"font-size: 16pt; font-family: Verdana, sans-serif;\">Who can become a seller on AlifMart.com?</span></strong></p>\r\n<p class=\"MsoNormal\" style=\"margin: 5pt 0cm 5pt 36pt; text-align: justify;\"><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">Any vendor who has existing brick-n-mortar establishment &amp; has all necessary documentation in place for doing business in India can become a seller &amp; list their products with AlifMart.com eCommerce Portal. Manufacturers, Distributors &amp; Dealers are preferred.</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 5pt 0cm 5pt 36pt; text-align: justify;\"><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">We support you at every step of your Online business journey right from Order receipt to product Delivery; covering product pick-up, packaging, shipping, shipment tracking and payment collection. AlifMart helps you expand your business and reach customers throughout India, which you couldn&rsquo;t have otherwise achieved from your Brick-n-Mortar establishment.</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 5pt 0cm 5pt 36pt; text-align: justify;\"><strong><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">Do I have to make any upfront payment for selling with AlifMart.com?</span></strong></p>\r\n<p class=\"MsoNormal\" style=\"margin: 5pt 0cm 5pt 36pt; text-align: justify;\"><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">No, you do not have to make any upfront payment to us for selling your products using our eCommerce portal. </span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 5pt 0cm 5pt 36pt; text-align: justify;\"><strong><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">What documents are needed to register as seller?</span></strong></p>\r\n<p class=\"MsoNormal\" style=\"margin: 5pt 0cm 5pt 36pt; text-align: justify;\"><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">To start selling, you need to have the following:</span><span style=\"font-size: 8.5pt; font-family: Verdana, sans-serif;\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 5pt 0cm 5pt 36pt; text-align: justify; text-indent: -18pt; vertical-align: baseline;\"><!-- [if !supportLists]--><span style=\"font-size: 10pt; font-family: Symbol;\">&middot;<span style=\"font-stretch: normal; font-size: 7pt; font-family: \'Times New Roman\';\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><!--[endif]--><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">Individual &amp; Establishment PAN Card</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 5pt 0cm 5pt 36pt; text-align: justify; text-indent: -18pt; vertical-align: baseline;\"><!-- [if !supportLists]--><span style=\"font-size: 10pt; font-family: Symbol;\">&middot;<span style=\"font-stretch: normal; font-size: 7pt; font-family: \'Times New Roman\';\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><!--[endif]--><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">VAT/TIN Number, CST to sell across India</span></p>\r\n<ul style=\"margin-top: 0cm;\" type=\"disc\">\r\n<li class=\"MsoNormal\" style=\"margin-top: 5pt; margin-bottom: 5pt; text-align: justify; vertical-align: baseline;\"><span style=\"font-size: 12.0pt; font-family: \'Verdana\',sans-serif; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: Arial; mso-fareast-language: EN-IN;\">Bank account and supporting KYC documents (ID Proof, Address Proof, and Cancelled cheque)</span></li>\r\n</ul>\r\n<p class=\"MsoNormal\"><span style=\"font-size: 8.5pt; font-family: Verdana, sans-serif;\">&nbsp;</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 5pt 0cm 5pt 36pt; text-align: justify;\"><strong><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">Do I need to have a Bank Account for doing business with AlifMart.com?</span></strong></p>\r\n<p class=\"MsoNormal\" style=\"margin: 5pt 0cm 5pt 36pt; text-align: justify;\"><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">Yes, you need to have a Current account with a Nationalized, Private or Cooperative Bank to enable us make payments to you using NEFT/RTGS services offered by the Banks.</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 5pt 0cm 5pt 36pt; text-align: justify;\"><strong><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">&nbsp;</span></strong></p>\r\n<p class=\"MsoNormal\" style=\"margin: 5pt 0cm 5pt 36pt; text-align: justify;\"><strong><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">How do I sell on AlifMart?</span></strong></p>\r\n<ul style=\"margin-top: 0cm;\" type=\"disc\">\r\n<li class=\"MsoNormal\" style=\"margin-top: 5pt; margin-bottom: 5pt; text-align: justify; vertical-align: baseline;\"><span style=\"font-size: 12.0pt; font-family: \'Verdana\',sans-serif; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: Arial; mso-fareast-language: EN-IN;\">Register yourself at <span style=\"font-size: 10pt;\"><a href=\"http://sellerzone.alifmart.com/\">sellerzone.alifmart.com</a> </span></span></li>\r\n<li class=\"MsoNormal\" style=\"margin-top: 5pt; margin-bottom: 5pt; text-align: justify; vertical-align: baseline;\"><span style=\"font-size: 12.0pt; font-family: \'Verdana\',sans-serif; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: Arial; mso-fareast-language: EN-IN;\">List your products under specific product categories.</span></li>\r\n<li class=\"MsoNormal\" style=\"margin-top: 5pt; margin-bottom: 5pt; text-align: justify; vertical-align: baseline;\"><span style=\"font-size: 12.0pt; font-family: \'Verdana\',sans-serif; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: Arial; mso-fareast-language: EN-IN;\">Once we receive an order for items sold by you, an intimation will be done to you on your registered email &amp; phone number, you pack the product and mark it as &lsquo;Shipment Ready&rsquo; in our system using your seller account. Our logistics partner will pick up the product and deliver it to the customer.</span></li>\r\n<li class=\"MsoNormal\" style=\"margin-top: 5pt; margin-bottom: 5pt; text-align: justify; vertical-align: baseline;\"><span style=\"font-size: 12.0pt; font-family: \'Verdana\',sans-serif; mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: Arial; mso-fareast-language: EN-IN;\">Once an order is successfully dispatched, we will settle your payment within the next payment cycle. &nbsp;</span></li>\r\n</ul>\r\n<p class=\"MsoNormal\" style=\"margin: 5pt 0cm 5pt 36pt; text-align: justify; vertical-align: baseline;\"><span style=\"font-size: 12pt; font-family: Arial, sans-serif;\">&nbsp;</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 5pt 0cm 5pt 36pt; text-align: justify;\"><strong><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">Do I need to courier my products to AlifMart?</span></strong></p>\r\n<p class=\"MsoNormal\" style=\"margin: 5pt 0cm 5pt 36pt; text-align: justify;\"><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">No, AlifMart will handle shipping of your products. All that you need is to pack the product as per our guidelines and keep it ready for dispatch. Our logistics partner will pick up the product from you from your registered Business/Warehouse address and deliver it to the customer.</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 5pt 0cm 5pt 36pt; text-align: justify;\"><strong><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">&nbsp;</span></strong></p>\r\n<p class=\"MsoNormal\" style=\"margin: 5pt 0cm 5pt 36pt; text-align: justify;\"><strong><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">When can I start getting traction for my products listed with AlifMart.com?</span></strong></p>\r\n<p class=\"MsoNormal\" style=\"margin: 5pt 0cm 5pt 36pt; text-align: justify;\"><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">As soon as we&rsquo;ve jointly completed all formalities &amp; have validated your establishment &amp; necessary documentation and once you&rsquo;ve provided the list of products along with its brochures &amp; competitive price-list, you will be ready to go online &amp; you can start seeing how our online marketing makes wonder for your business &amp; its revenues.</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 5pt 0cm 5pt 36pt; text-align: justify;\"><strong><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">&nbsp;</span></strong></p>\r\n<p class=\"MsoNormal\" style=\"margin: 5pt 0cm 5pt 36pt; text-align: justify;\"><strong><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">Who are potential buyers for my product?</span></strong></p>\r\n<p class=\"MsoNormal\" style=\"margin: 5pt 0cm 5pt 36pt; text-align: justify;\"><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">Any user, be it a household end-user, a small-time Entrepreneur/Trader or a business house such as Hardware retailer, Small to Medium Scale Industrial Manufacturer or even a large conglomerate.</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 14pt 0cm 14pt 36pt; text-align: justify;\"><strong><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">Who decides the price of the products?</span></strong></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 14pt 36pt; text-align: justify;\"><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">As a seller, you will set the competitive price of your products. Remember, there are other sellers who may be selling the same items at aggressive price, buyer will obviously want the best deal.</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 14pt 36pt; text-align: justify;\"><strong><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">What products can I sell through AlifMart.com?</span></strong></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 14pt 36pt; text-align: justify;\"><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">Sellers in the following categories can use our eCommerce services for doing business online:</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 5pt 0cm 5pt 72pt; text-align: justify; text-indent: -18pt; vertical-align: baseline;\"><!-- [if !supportLists]--><span style=\"font-size: 10pt; font-family: Symbol;\">&middot;<span style=\"font-stretch: normal; font-size: 7pt; font-family: \'Times New Roman\';\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><!--[endif]--><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">General Hardware</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 5pt 0cm 5pt 72pt; text-align: justify; text-indent: -18pt; vertical-align: baseline;\"><!-- [if !supportLists]--><span style=\"font-size: 10pt; font-family: Symbol;\">&middot;<span style=\"font-stretch: normal; font-size: 7pt; font-family: \'Times New Roman\';\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><!--[endif]--><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">Construction Hardware</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 5pt 0cm 5pt 72pt; text-align: justify; text-indent: -18pt; vertical-align: baseline;\"><!-- [if !supportLists]--><span style=\"font-size: 10pt; font-family: Symbol;\">&middot;<span style=\"font-stretch: normal; font-size: 7pt; font-family: \'Times New Roman\';\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><!--[endif]--><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">Adhesives Domestic &amp; Industrial Use</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 5pt 0cm 5pt 72pt; text-align: justify; text-indent: -18pt; vertical-align: baseline;\"><!-- [if !supportLists]--><span style=\"font-size: 10pt; font-family: Symbol;\">&middot;<span style=\"font-stretch: normal; font-size: 7pt; font-family: \'Times New Roman\';\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><!--[endif]--><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">Grinders</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 5pt 0cm 5pt 72pt; text-align: justify; text-indent: -18pt; vertical-align: baseline;\"><!-- [if !supportLists]--><span style=\"font-size: 10pt; font-family: Symbol;\">&middot;<span style=\"font-stretch: normal; font-size: 7pt; font-family: \'Times New Roman\';\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><!--[endif]--><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">Drilling equipment</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 5pt 0cm 5pt 72pt; text-align: justify; text-indent: -18pt; vertical-align: baseline;\"><!-- [if !supportLists]--><span style=\"font-size: 10pt; font-family: Symbol;\">&middot;<span style=\"font-stretch: normal; font-size: 7pt; font-family: \'Times New Roman\';\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><!--[endif]--><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">Architectural Hardware</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 5pt 0cm 5pt 72pt; text-align: justify; text-indent: -18pt; vertical-align: baseline;\"><!-- [if !supportLists]--><span style=\"font-size: 10pt; font-family: Symbol;\">&middot;<span style=\"font-stretch: normal; font-size: 7pt; font-family: \'Times New Roman\';\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><!--[endif]--><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">Bearing </span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 5pt 0cm 5pt 72pt; text-align: justify; text-indent: -18pt; vertical-align: baseline;\"><!-- [if !supportLists]--><span style=\"font-size: 10pt; font-family: Symbol;\">&middot;<span style=\"font-stretch: normal; font-size: 7pt; font-family: \'Times New Roman\';\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><!--[endif]--><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">Metal &amp; Machine Cutting Tools</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 5pt 0cm 5pt 72pt; text-align: justify; text-indent: -18pt; vertical-align: baseline;\"><!-- [if !supportLists]--><span style=\"font-size: 10pt; font-family: Symbol;\">&middot;<span style=\"font-stretch: normal; font-size: 7pt; font-family: \'Times New Roman\';\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><!--[endif]--><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">Automobile Tools</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 5pt 0cm 5pt 72pt; text-align: justify; text-indent: -18pt; vertical-align: baseline;\"><!-- [if !supportLists]--><span style=\"font-size: 10pt; font-family: Symbol;\">&middot;<span style=\"font-stretch: normal; font-size: 7pt; font-family: \'Times New Roman\';\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><!--[endif]--><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">Hand Tools &amp; Power Tools</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 5pt 0cm 5pt 72pt; text-align: justify; text-indent: -18pt; vertical-align: baseline;\"><!-- [if !supportLists]--><span style=\"font-size: 10pt; font-family: Symbol;\">&middot;<span style=\"font-stretch: normal; font-size: 7pt; font-family: \'Times New Roman\';\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><!--[endif]--><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">Sanitary Ware &amp; CP Fittings</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 5pt 0cm 5pt 72pt; text-align: justify; text-indent: -18pt; vertical-align: baseline;\"><!-- [if !supportLists]--><span style=\"font-size: 10pt; font-family: Symbol;\">&middot;<span style=\"font-stretch: normal; font-size: 7pt; font-family: \'Times New Roman\';\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><!--[endif]--><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">Gardening and Plumbing equipment &amp; fittings</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 5pt 0cm 5pt 72pt; text-align: justify; text-indent: -18pt; vertical-align: baseline;\"><!-- [if !supportLists]--><span style=\"font-size: 10pt; font-family: Symbol;\">&middot;<span style=\"font-stretch: normal; font-size: 7pt; font-family: \'Times New Roman\';\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><!--[endif]--><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">Lubricants, Oils</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 5pt 0cm 5pt 72pt; text-align: justify; text-indent: -18pt; vertical-align: baseline;\"><!-- [if !supportLists]--><span style=\"font-size: 10pt; font-family: Symbol;\">&middot;<span style=\"font-stretch: normal; font-size: 7pt; font-family: \'Times New Roman\';\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><!--[endif]--><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">Compressors, Pneumatic Tools &amp; Pressure Gauges</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 5pt 0cm 5pt 72pt; text-align: justify; text-indent: -18pt; vertical-align: baseline;\"><!-- [if !supportLists]--><span style=\"font-size: 10pt; font-family: Symbol;\">&middot;<span style=\"font-stretch: normal; font-size: 7pt; font-family: \'Times New Roman\';\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><!--[endif]--><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">Hydraulic Tools &amp; Pressure Gauges</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 5pt 0cm 5pt 72pt; text-align: justify; text-indent: -18pt; vertical-align: baseline;\"><!-- [if !supportLists]--><span style=\"font-size: 10pt; font-family: Symbol;\">&middot;<span style=\"font-stretch: normal; font-size: 7pt; font-family: \'Times New Roman\';\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><!--[endif]--><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">Lathe Machines, Cutting Machines, Punching Machines, etc.</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 5pt 0cm 5pt 72pt; text-align: justify; text-indent: -18pt; vertical-align: baseline;\"><!-- [if !supportLists]--><span style=\"font-size: 10pt; font-family: Symbol;\">&middot;<span style=\"font-stretch: normal; font-size: 7pt; font-family: \'Times New Roman\';\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><!--[endif]--><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">Farming equipment</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 5pt 0cm 5pt 72pt; text-align: justify; text-indent: -18pt; vertical-align: baseline;\"><!-- [if !supportLists]--><span style=\"font-size: 10pt; font-family: Symbol;\">&middot;<span style=\"font-stretch: normal; font-size: 7pt; font-family: \'Times New Roman\';\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><!--[endif]--><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">Pumps &amp; Motors</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 5pt 0cm 5pt 72pt; text-align: justify; text-indent: -18pt; vertical-align: baseline;\"><!-- [if !supportLists]--><span style=\"font-size: 10pt; font-family: Symbol;\">&middot;<span style=\"font-stretch: normal; font-size: 7pt; font-family: \'Times New Roman\';\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><!--[endif]--><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">Paints, Enamels, painting equipment &amp; accessories</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 5pt 0cm 0.0001pt 72pt; text-align: justify; text-indent: -18pt; vertical-align: baseline;\"><!-- [if !supportLists]--><span style=\"font-size: 10pt; font-family: Symbol;\">&middot;<span style=\"font-stretch: normal; font-size: 7pt; font-family: \'Times New Roman\';\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><!--[endif]--><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">Testing tools &amp; equipment</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 14pt 0cm 14pt 36pt; text-align: justify;\"><strong><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">How does AlifMart get benefitted from the sale?</span></strong></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 14pt 36pt; text-align: justify;\"><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">Listing of products on AlifMart.com is absolutely free. AlifMart get a share of commission after paying for Logistics &amp; Payment Gateway charges on the deal value &amp; shipped item.</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 14pt 36pt; text-align: justify;\"><strong><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">How many listings are required to start selling?</span></strong></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 14pt 36pt; text-align: justify;\"><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">You can even start with just one listing and gradually increase your product listings.</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 5pt 0cm; text-align: justify;\"><strong><span style=\"font-size: 14pt; font-family: Verdana, sans-serif;\">&nbsp;</span></strong></p>\r\n<p class=\"MsoNormal\" style=\"margin: 5pt 0cm; text-align: center;\" align=\"center\"><strong><span style=\"font-size: 14pt; font-family: Verdana, sans-serif;\">Execution by AlifMart FAQs</span></strong></p>\r\n<p class=\"MsoNormal\" style=\"margin: 5pt 0cm; text-align: center;\" align=\"center\"><strong><span style=\"font-size: 14pt; font-family: Verdana, sans-serif;\">________________________________</span></strong></p>\r\n<p class=\"MsoNormal\" style=\"margin: 14pt 0cm 14pt 36pt; text-align: justify;\"><strong><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">What is Execution by AlifMart (XBA) Service?</span></strong></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 14pt 36pt; text-align: justify;\"><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">Execution by AlifMart (XBA) is a service provided by AlifMart to deliver orders to customers from our Warehouses, provide customer service and handle returns.</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 14pt 36pt; text-align: justify;\"><strong><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">Can I sell my products in India only or outside India too?</span></strong></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 14pt 36pt; text-align: justify;\"><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">You can currently sell products in India only. Currently, AlifMart does not have a provision to sell internationally.</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 14pt 36pt; text-align: justify;\"><strong><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">Is there any minimum number of items required to use XBA?</span></strong></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 14pt 36pt; text-align: justify;\"><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">There are no minimum requirements on the number of products to use XBA.</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 14pt 36pt; text-align: justify;\"><strong><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">How will you charge me for Execution by AlifMart?</span></strong></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 14pt 36pt; text-align: justify;\"><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">Charges for execution services are included in the AlifMart fees. No additional charges are applicable.</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 14pt 36pt; text-align: justify;\"><strong><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">Who will handle customer service?</span></strong></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 14pt 36pt; text-align: justify;\"><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">AlifMart&rsquo;s dedicated support team will handle customer service for all sellers.</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 14pt 36pt; text-align: justify;\"><strong><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">Who will handle the installation, maintenance and repairs (all after-sales services)?</span></strong></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 14pt 36pt; text-align: justify;\"><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">AlifMart is not responsible for any installation, maintenance and repairs of the products sold via AlifMart.com. AlifMart does not provide any after-sales services.</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 14pt 36pt; text-align: justify;\"><strong><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">Who will handle customer returns?</span></strong></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 14pt 36pt; text-align: justify;\"><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">AlifMart will handle customer returns. Please refer to the </span><span style=\"font-size: 8.5pt; font-family: Verdana, sans-serif;\"><a href=\"../returns.php\"><span style=\"font-size: 12.0pt; text-decoration: none; text-underline: none;\">Cancellation &amp; Returns Policy</span></a></span><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\"> for more information.</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 14pt 36pt; text-align: justify;\"><strong><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">How will customer refunds happen?</span></strong></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 14pt 36pt; text-align: justify;\"><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">We refund to the customer and charge the same to your account.</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 14pt 36pt; text-align: justify;\"><strong><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">What will happen to my unsold inventory at your execution centers?</span></strong></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 14pt 36pt; text-align: justify;\"><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">Goods not sold within duration of 60 days since receipt of goods will be returned back to the seller.</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 14pt 36pt; text-align: justify;\"><strong><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">How do we decide which products to be stored at your warehouse?</span></strong></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 14pt 36pt; text-align: justify;\"><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">Sellers generally prefer to keep fast moving products at our warehouse and slow-moving products at their own premises.</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 14pt 36pt; text-align: justify;\"><strong><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">How should I package the items for shipment by you?</span></strong></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 14pt 36pt; text-align: justify;\"><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">Products should be packed as per AlifMart&rsquo;s packaging guidelines that are shared with you via email after successful on boarding process.</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 14pt 36pt; text-align: justify;\"><strong><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">Can I pass Credit to my preferred customers?</span></strong></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 14pt 36pt; text-align: justify;\"><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">Yes, once you have authorized a buyer as a preferred buyer, buyer will be allowed to use the allocated Credit value &amp; period as per your mutual agreement. Our system will honor your agreement &amp; will allow customer to place order without making any upfront payment.</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 14pt 36pt; text-align: justify;\"><strong><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">Who can become my preferred buyer?</span></strong></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 14pt 36pt; text-align: justify;\"><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">Your long term customer relationship with your brick-n-mortar buyer can be brought in purview of AlifMart&rsquo;s XBA system &amp; we will create an account for your preferred buyer based on the agreed terms between AlifMart &amp; You and between You &amp; your preferred buyer.</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 14pt 36pt; text-align: justify;\"><strong><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">How will I get the payments from my preferred buyers?</span></strong></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 14pt 36pt; text-align: justify;\"><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">We will follow-up on email &amp; SMS with your preferred seller to make payment as per the credit period allowed by you &amp; will send the payment link to execute the Payment using our Payment Gateway System. </span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 14pt 36pt; text-align: justify;\"><strong><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">What happens when my preferred buyer fails to make a payment?</span></strong></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 14pt 36pt; text-align: justify;\"><span style=\"font-size: 12pt; font-family: Verdana, sans-serif;\">We inform you of the failure to make the payment due by the end of credit period &amp; we expect you to resolve the payment &amp; terms with your preferred buyer. You may increase or decrease his credit limits &amp; credit period based on your relationship with the preferred buyer &amp; also update the same directly using your Seller Dashboard or authorize us to do the same.</span></p>\r\n<p>&nbsp;</p>','2015-11-04 08:14:54'),(13,'ac','accessibility','Accessibility',NULL,NULL,NULL,NULL,'2015-11-01 07:09:42'),(14,'cr','copyright','Copyright','Copyright','AlifMart Copyright','AlifMart Copyright details','<h1 id=\"docs-internal-guid-fa7088dd-d179-2f59-e974-61ece909514d\" dir=\"ltr\" style=\"line-height: 1.38; margin-top: 24pt; margin-bottom: 6pt; text-align: center;\"><span style=\"font-size: 14pt; font-family: Arial; vertical-align: baseline; background-color: transparent;\">Copyright</span></h1>\r\n<p dir=\"ltr\" style=\"line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt; text-align: justify;\"><span style=\"font-size: 14.6667px; font-family: Arial; vertical-align: baseline; background-color: transparent;\">ALIFMART.com (&ldquo;ALIFMART&rdquo;) is the sole owner of all the rights to</span><a style=\"text-decoration: none;\" href=\"http://www.alifmart.com/\"> <span style=\"font-size: 14.6667px; font-family: Arial; color: #1155cc; text-decoration: underline; vertical-align: baseline; background-color: transparent;\">www.alifmart.com</span></a><span style=\"font-size: 14.6667px; font-family: Arial; vertical-align: baseline; background-color: transparent;\"> &nbsp;(&ldquo;Website&rdquo;) and it&rsquo;s content. Website content means its design, layout, text, images, graphics, sound, video etc. All Title, Ownership and Intellectual Property Rights in the Website and its content shall remain with ALIFMART. All rights not otherwise claimed under this agreement or by ALIFMART, are hereby reserved. The information contained in this Website is intended, solely to provide general information for the personal use of the USER, who accepts full responsibility for its use. ALIFMART does not represent or endorse the accuracy or reliability of any information, or advertisements (collectively, the \"content\") contained on, distributed through, or linked, downloaded or accessed from any of the services contained on this Website, or the quality of any products, information or other materials displayed, or obtained by USER as a result of an advertisement or any other information or offer in or in connection with the service. We accept no responsibility for any errors or omissions, or for the results obtained from the use of the information contained in this Website. All information in this Website is provided \"as is\" with no guarantee of completeness, accuracy, timeliness or of the results obtained from the use of the information contained in this Website, and without warranty of any kind, express or implied, including, but not limited to warranties of performance, merchantability and fitness for a particular purpose. In no event shall ALIFMART be liable for any direct, indirect, incidental, punitive, or consequential damages of any kind whatsoever with respect to the service, the materials and the products available on the Website. User(s) of this site must hereby acknowledge that any reliance upon any content shall be at their sole risk. The information presented here has been compiled from publicly aired and published sources. ALIFMART respects these sources and is in no way trying to infringe on the respective copyrights or businesses of these entities. ALIFMART reserves the right, in its sole discretion and without any obligation, to make improvements to, or correct any error or omissions in any portion of the service or the materials.</span></p>\r\n<p>&nbsp;</p>\r\n<p dir=\"ltr\" style=\"line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt;\"><span style=\"font-size: 14.6667px; font-family: Arial; font-weight: bold; vertical-align: baseline; background-color: transparent;\">COPYRIGHT</span></p>\r\n<p dir=\"ltr\" style=\"line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt; text-align: justify;\"><span style=\"font-size: 14.6667px; font-family: Arial; vertical-align: baseline; background-color: transparent;\">All content on this Website is the copyright of ALIFMART except the third party content and link to third party website on our Website.</span></p>\r\n<p dir=\"ltr\" style=\"line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt; text-align: justify;\"><span style=\"font-size: 14.6667px; font-family: Arial; vertical-align: baseline; background-color: transparent;\">ALIFMART is not an expert in Intellectual Property Rights of third parties, and ALIFMART cannot verify that the users of Website - who post goods on the Website have the right to sell the goods offered. We will appreciate your assistance in identifying content which may not appear on their face to infringe your rights but which you believe are infringing. ALIFMART is also not an arbiter or judge of disputes about Intellectual Property Rights. By taking down content in question, as a prudential matter, ALIFMART is not endorsing a claim of infringement. Neither, in those instances in which ALIFMART declines to take down content, is ALIFMART determining that the listing is not infringing, nor is ALIFMART endorsing the sale of goods in such cases.</span></p>\r\n<p dir=\"ltr\" style=\"line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt; text-align: justify;\"><span style=\"font-size: 14.6667px; font-family: Arial; vertical-align: baseline; background-color: transparent;\">The unauthorized copying, modification, use or publication of the mark - ALIFMART is strictly prohibited.</span></p>\r\n<p dir=\"ltr\" style=\"line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt; text-align: justify;\"><span style=\"font-size: 14.6667px; font-family: Arial; vertical-align: baseline; background-color: transparent;\">ALIFMART respects the intellectual property rights of others, and we expect our user(s) to do the same. ALIFMART believes that user(s) agree that they will not copy, download &amp; reproduce any information, text, images, video clips, directories, files, databases or listings available on or through the Website (the \"ALIFMART content\") for the purpose of re-selling or re-distributing, mass mailing (via email, wireless text messages, physical mail or otherwise), operating a business competing with ALIFMART, or otherwise commercially exploiting the ALIFMART content. Systematic retrieval of ALIFMART content to create or compile, directly or indirectly, a collection, compilation, database or directory (whether automatically or manual processes) without written permission from ALIFMART is prohibited.</span></p>\r\n<p dir=\"ltr\" style=\"line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt; text-align: justify;\"><span style=\"font-size: 14.6667px; font-family: Arial; vertical-align: baseline; background-color: transparent;\">In addition, use of the content for any purpose not expressly permitted in this policy is prohibited and may invite legal action. As a condition of your access to and use of ALIFMART\'s services, you agree that you will not use the Website service to infringe the Intellectual Property Rights of others in any way. ALIFMART reserves the right to terminate the account of a user(s) upon any infringement of the rights of others in conjunction with use of the ALIFMART service, or if ALIFMART believes that user(s) conduct is harmful to the interests of ALIFMART, its affiliates, or other users, or for any other reason in ALIFMART\'s sole discretion, with or without cause.&nbsp;</span></p>','2015-11-04 07:51:29');
/*!40000 ALTER TABLE `cms_pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `configuration`
--

DROP TABLE IF EXISTS `configuration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `configuration` (
  `c_id` decimal(10,0) NOT NULL DEFAULT '0',
  `admin_user` varchar(20) DEFAULT NULL,
  `admin_pwd` varchar(20) DEFAULT NULL,
  `smtp_server` varchar(100) DEFAULT NULL,
  `vat` decimal(5,2) NOT NULL,
  `Admin_email` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`c_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `configuration`
--

LOCK TABLES `configuration` WRITE;
/*!40000 ALTER TABLE `configuration` DISABLE KEYS */;
INSERT INTO `configuration` (`c_id`, `admin_user`, `admin_pwd`, `smtp_server`, `vat`, `Admin_email`) VALUES (1,'AlifAdmin','Alif@Pass123',NULL,0.00,'orders@alifmart.com');
/*!40000 ALTER TABLE `configuration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `levels`
--

DROP TABLE IF EXISTS `levels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `levels` (
  `level_id` int(11) NOT NULL AUTO_INCREMENT,
  `level_parent` int(11) DEFAULT NULL,
  `level_name` varchar(50) DEFAULT NULL,
  `page_title` varchar(100) DEFAULT NULL,
  `meta_key` text,
  `meta_desc` text,
  `level_desc` tinytext,
  `level_sort` float DEFAULT NULL,
  `level_status` tinyint(4) DEFAULT NULL,
  `level_image` longblob,
  PRIMARY KEY (`level_id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `levels`
--

LOCK TABLES `levels` WRITE;
/*!40000 ALTER TABLE `levels` DISABLE KEYS */;
/*!40000 ALTER TABLE `levels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `levels_props`
--

DROP TABLE IF EXISTS `levels_props`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `levels_props` (
  `level_id` int(11) NOT NULL,
  `prop_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Category-wise property';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `levels_props`
--

LOCK TABLES `levels_props` WRITE;
/*!40000 ALTER TABLE `levels_props` DISABLE KEYS */;
/*!40000 ALTER TABLE `levels_props` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logging`
--

DROP TABLE IF EXISTS `logging`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logging` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `log_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `log_page` varchar(50) DEFAULT NULL,
  `session_id` varchar(30) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `log_activity` varchar(500) DEFAULT NULL,
  `log_event` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logging`
--

LOCK TABLES `logging` WRITE;
/*!40000 ALTER TABLE `logging` DISABLE KEYS */;
/*!40000 ALTER TABLE `logging` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `member_mast`
--

DROP TABLE IF EXISTS `member_mast`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `member_mast` (
  `memb_id` int(11) NOT NULL,
  `memb_email` varchar(50) NOT NULL,
  `memb_pwd` varchar(10) NOT NULL,
  `memb_title` varchar(5) DEFAULT NULL,
  `memb_fname` varchar(20) NOT NULL,
  `memb_sname` varchar(20) NOT NULL,
  `memb_tel` varchar(50) DEFAULT NULL,
  `memb_fax` varchar(15) DEFAULT NULL,
  `memb_add1` varchar(45) NOT NULL,
  `memb_add2` varchar(45) DEFAULT NULL,
  `memb_city` varchar(45) DEFAULT NULL,
  `memb_county` varchar(45) DEFAULT NULL,
  `memb_postcode` varchar(10) NOT NULL,
  `memb_state` varchar(50) NOT NULL,
  `memb_country` varchar(30) DEFAULT NULL,
  `memb_status` tinyint(4) DEFAULT NULL,
  `memb_contact` varchar(30) DEFAULT NULL,
  `memb_allowonaccpymt` tinyint(4) DEFAULT NULL,
  `memb_act_id` varchar(100) DEFAULT NULL,
  `memb_act_status` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`memb_id`),
  UNIQUE KEY `memb_email_UNIQUE` (`memb_email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `member_mast`
--

LOCK TABLES `member_mast` WRITE;
/*!40000 ALTER TABLE `member_mast` DISABLE KEYS */;
/*!40000 ALTER TABLE `member_mast` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ord_details`
--

DROP TABLE IF EXISTS `ord_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ord_details` (
  `session_id` varchar(50) DEFAULT NULL,
  `bill_name` varchar(30) DEFAULT NULL,
  `bill_state` varchar(30) DEFAULT NULL,
  `bill_email` varchar(100) DEFAULT NULL,
  `bill_add1` varchar(50) DEFAULT NULL,
  `bill_add2` varchar(50) DEFAULT NULL,
  `bill_city` varchar(30) DEFAULT NULL,
  `bill_postcode` varchar(10) DEFAULT NULL,
  `bill_country` varchar(30) DEFAULT NULL,
  `bill_tel` varchar(30) DEFAULT NULL,
  `bill_mob` varchar(30) DEFAULT NULL,
  `ship_name` varchar(30) DEFAULT NULL,
  `ship_state` varchar(30) DEFAULT NULL,
  `ship_email` varchar(100) DEFAULT NULL,
  `ship_add1` varchar(50) DEFAULT NULL,
  `ship_add2` varchar(50) DEFAULT NULL,
  `ship_city` varchar(30) DEFAULT NULL,
  `ship_postcode` varchar(10) DEFAULT NULL,
  `ship_country` varchar(30) DEFAULT NULL,
  `ship_tel` varchar(30) DEFAULT NULL,
  `ship_mob` varchar(30) DEFAULT NULL,
  `ord_instruct` varchar(1000) DEFAULT NULL,
  `cart_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ord_details`
--

LOCK TABLES `ord_details` WRITE;
/*!40000 ALTER TABLE `ord_details` DISABLE KEYS */;
/*!40000 ALTER TABLE `ord_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ord_items`
--

DROP TABLE IF EXISTS `ord_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ord_items` (
  `cart_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `prod_id` int(11) DEFAULT NULL,
  `session_id` varchar(50) DEFAULT NULL,
  `cart_datetime` datetime DEFAULT NULL,
  `item_name` varchar(200) DEFAULT NULL,
  `item_stock_no` varchar(100) DEFAULT NULL,
  `item_thumb` longblob,
  `cart_qty` int(11) DEFAULT NULL,
  `cart_price` decimal(6,2) DEFAULT NULL,
  `sup_id` int(11) DEFAULT NULL,
  `sup_name` varchar(50) DEFAULT NULL,
  `tax_id` int(11) NOT NULL,
  `tax_name` varchar(30) DEFAULT NULL,
  `tax_percent` decimal(6,2) NOT NULL,
  `tax_value` decimal(10,2) NOT NULL,
  `cart_price_tax` decimal(10,2) NOT NULL,
  `cart_id` int(11) NOT NULL,
  PRIMARY KEY (`cart_item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ord_items`
--

LOCK TABLES `ord_items` WRITE;
/*!40000 ALTER TABLE `ord_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `ord_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ord_summary`
--

DROP TABLE IF EXISTS `ord_summary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ord_summary` (
  `session_id` varchar(50) DEFAULT NULL,
  `way_billl_no` varchar(50) DEFAULT NULL COMMENT 'Way bill number of shipment',
  `cart_datetime` datetime DEFAULT NULL,
  `item_total` decimal(10,2) DEFAULT NULL,
  `shipping_charges` decimal(6,2) DEFAULT NULL,
  `ord_total` decimal(10,2) DEFAULT NULL,
  `vat_percent` decimal(6,2) DEFAULT NULL,
  `vat_value` decimal(6,2) DEFAULT NULL,
  `item_count` int(11) DEFAULT NULL,
  `pay_method` varchar(5) DEFAULT NULL,
  `pay_method_name` varchar(100) DEFAULT NULL,
  `ord_id` int(11) DEFAULT NULL,
  `ord_date` datetime DEFAULT NULL,
  `pay_status` varchar(20) DEFAULT NULL,
  `delivery_status` varchar(20) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `cart_id` int(11) NOT NULL,
  `user_type` varchar(2) DEFAULT NULL,
  `ord_no` varchar(15) DEFAULT NULL,
  `pg_status` varchar(30) DEFAULT NULL,
  `pg_txnid` varchar(30) DEFAULT NULL,
  `pkg_weight` float DEFAULT NULL,
  `pkg_height` float DEFAULT NULL,
  `pkg_width` float DEFAULT NULL,
  `pkg_depth` float DEFAULT NULL,
  PRIMARY KEY (`cart_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ord_summary`
--

LOCK TABLES `ord_summary` WRITE;
/*!40000 ALTER TABLE `ord_summary` DISABLE KEYS */;
/*!40000 ALTER TABLE `ord_summary` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ord_waybill`
--

DROP TABLE IF EXISTS `ord_waybill`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ord_waybill` (
  `way_bill_no` int(11) NOT NULL,
  `ord_id` int(11) DEFAULT NULL,
  `in_use` tinyint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ord_waybill`
--

LOCK TABLES `ord_waybill` WRITE;
/*!40000 ALTER TABLE `ord_waybill` DISABLE KEYS */;
INSERT INTO `ord_waybill` (`way_bill_no`, `ord_id`, `in_use`) VALUES (1,NULL,0),(2,NULL,0),(3,NULL,0),(4,NULL,0),(5,NULL,0),(6,NULL,0),(7,NULL,0),(8,NULL,0),(9,NULL,0),(10,NULL,0),(11,NULL,0),(12,NULL,0),(13,NULL,0),(14,NULL,0),(15,NULL,0),(16,NULL,0),(17,NULL,0),(18,NULL,0),(19,NULL,0),(20,NULL,0),(21,NULL,0),(22,NULL,0),(23,NULL,0),(24,NULL,0),(25,NULL,0),(26,NULL,0),(27,NULL,0),(28,NULL,0),(29,NULL,0),(30,NULL,0),(31,NULL,0),(32,NULL,0),(33,NULL,0),(34,NULL,0),(35,NULL,0),(36,NULL,0),(37,NULL,0),(38,NULL,0),(39,NULL,0),(40,NULL,0),(41,NULL,0),(42,NULL,0),(43,NULL,0),(44,NULL,0),(45,NULL,0),(46,NULL,0),(47,NULL,0),(48,NULL,0),(49,NULL,0),(50,NULL,0),(51,NULL,0),(52,NULL,0),(53,NULL,0),(54,NULL,0),(55,NULL,0),(56,NULL,0),(57,NULL,0),(58,NULL,0),(59,NULL,0),(60,NULL,0),(61,NULL,0),(62,NULL,0),(63,NULL,0),(64,NULL,0),(65,NULL,0),(66,NULL,0),(67,NULL,0),(68,NULL,0),(69,NULL,0),(70,NULL,0),(71,NULL,0),(72,NULL,0),(73,NULL,0),(74,NULL,0),(75,NULL,0),(76,NULL,0),(77,NULL,0),(78,NULL,0),(79,NULL,0),(80,NULL,0),(81,NULL,0),(82,NULL,0),(83,NULL,0),(84,NULL,0),(85,NULL,0),(86,NULL,0),(87,NULL,0),(88,NULL,0),(89,NULL,0),(90,NULL,0),(91,NULL,0),(92,NULL,0),(93,NULL,0),(94,NULL,0),(95,NULL,0),(96,NULL,0),(97,NULL,0),(98,NULL,0),(99,NULL,0),(100,NULL,0),(101,NULL,0),(102,NULL,0),(103,NULL,0),(104,NULL,0),(105,NULL,0),(106,NULL,0),(107,NULL,0),(108,NULL,0),(109,NULL,0),(110,NULL,0),(111,NULL,0),(112,NULL,0),(113,NULL,0),(114,NULL,0),(115,NULL,0),(116,NULL,0),(117,NULL,0),(118,NULL,0),(119,NULL,0),(120,NULL,0),(121,NULL,0),(122,NULL,0),(123,NULL,0),(124,NULL,0),(125,NULL,0),(126,NULL,0),(127,NULL,0),(128,NULL,0),(129,NULL,0),(130,NULL,0),(131,NULL,0),(132,NULL,0),(133,NULL,0),(134,NULL,0),(135,NULL,0),(136,NULL,0),(137,NULL,0),(138,NULL,0),(139,NULL,0),(140,NULL,0),(141,NULL,0),(142,NULL,0),(143,NULL,0),(144,NULL,0),(145,NULL,0),(146,NULL,0),(147,NULL,0),(148,NULL,0),(149,NULL,0),(150,NULL,0),(151,NULL,0),(152,NULL,0),(153,NULL,0),(154,NULL,0),(155,NULL,0),(156,NULL,0),(157,NULL,0),(158,NULL,0),(159,NULL,0),(160,NULL,0),(161,NULL,0),(162,NULL,0),(163,NULL,0),(164,NULL,0),(165,NULL,0),(166,NULL,0),(167,NULL,0),(168,NULL,0),(169,NULL,0),(170,NULL,0),(171,NULL,0),(172,NULL,0),(173,NULL,0),(174,NULL,0),(175,NULL,0),(176,NULL,0),(177,NULL,0),(178,NULL,0),(179,NULL,0),(180,NULL,0),(181,NULL,0),(182,NULL,0),(183,NULL,0),(184,NULL,0),(185,NULL,0),(186,NULL,0),(187,NULL,0),(188,NULL,0),(189,NULL,0),(190,NULL,0),(191,NULL,0),(192,NULL,0),(193,NULL,0),(194,NULL,0),(195,NULL,0),(196,NULL,0),(197,NULL,0),(198,NULL,0),(199,NULL,0),(200,NULL,0),(201,NULL,0),(202,NULL,0),(203,NULL,0),(204,NULL,0),(205,NULL,0),(206,NULL,0),(207,NULL,0),(208,NULL,0),(209,NULL,0),(210,NULL,0),(211,NULL,0),(212,NULL,0),(213,NULL,0),(214,NULL,0),(215,NULL,0),(216,NULL,0),(217,NULL,0),(218,NULL,0),(219,NULL,0),(220,NULL,0),(221,NULL,0),(222,NULL,0),(223,NULL,0),(224,NULL,0),(225,NULL,0),(226,NULL,0),(227,NULL,0),(228,NULL,0),(229,NULL,0),(230,NULL,0),(231,NULL,0),(232,NULL,0),(233,NULL,0),(234,NULL,0),(235,NULL,0),(236,NULL,0),(237,NULL,0),(238,NULL,0),(239,NULL,0),(240,NULL,0),(241,NULL,0),(242,NULL,0),(243,NULL,0),(244,NULL,0),(245,NULL,0),(246,NULL,0),(247,NULL,0),(248,NULL,0),(249,NULL,0),(250,NULL,0),(251,NULL,0),(252,NULL,0),(253,NULL,0),(254,NULL,0),(255,NULL,0),(256,NULL,0),(257,NULL,0),(258,NULL,0),(259,NULL,0),(260,NULL,0),(261,NULL,0),(262,NULL,0),(263,NULL,0),(264,NULL,0),(265,NULL,0),(266,NULL,0),(267,NULL,0),(268,NULL,0),(269,NULL,0),(270,NULL,0),(271,NULL,0),(272,NULL,0),(273,NULL,0),(274,NULL,0),(275,NULL,0),(276,NULL,0),(277,NULL,0),(278,NULL,0),(279,NULL,0),(280,NULL,0),(281,NULL,0),(282,NULL,0),(283,NULL,0),(284,NULL,0),(285,NULL,0),(286,NULL,0),(287,NULL,0),(288,NULL,0),(289,NULL,0),(290,NULL,0),(291,NULL,0),(292,NULL,0),(293,NULL,0),(294,NULL,0),(295,NULL,0),(296,NULL,0),(297,NULL,0),(298,NULL,0),(299,NULL,0),(300,NULL,0),(301,NULL,0),(302,NULL,0),(303,NULL,0),(304,NULL,0),(305,NULL,0),(306,NULL,0),(307,NULL,0),(308,NULL,0),(309,NULL,0),(310,NULL,0),(311,NULL,0),(312,NULL,0),(313,NULL,0),(314,NULL,0),(315,NULL,0),(316,NULL,0),(317,NULL,0),(318,NULL,0),(319,NULL,0),(320,NULL,0),(321,NULL,0),(322,NULL,0),(323,NULL,0),(324,NULL,0),(325,NULL,0),(326,NULL,0),(327,NULL,0),(328,NULL,0),(329,NULL,0),(330,NULL,0),(331,NULL,0),(332,NULL,0),(333,NULL,0),(334,NULL,0),(335,NULL,0),(336,NULL,0),(337,NULL,0),(338,NULL,0),(339,NULL,0),(340,NULL,0),(341,NULL,0),(342,NULL,0),(343,NULL,0),(344,NULL,0),(345,NULL,0),(346,NULL,0),(347,NULL,0),(348,NULL,0),(349,NULL,0),(350,NULL,0),(351,NULL,0),(352,NULL,0),(353,NULL,0),(354,NULL,0),(355,NULL,0),(356,NULL,0),(357,NULL,0),(358,NULL,0),(359,NULL,0),(360,NULL,0),(361,NULL,0),(362,NULL,0),(363,NULL,0),(364,NULL,0),(365,NULL,0),(366,NULL,0),(367,NULL,0),(368,NULL,0),(369,NULL,0),(370,NULL,0),(371,NULL,0),(372,NULL,0),(373,NULL,0),(374,NULL,0),(375,NULL,0),(376,NULL,0),(377,NULL,0),(378,NULL,0),(379,NULL,0),(380,NULL,0),(381,NULL,0),(382,NULL,0),(383,NULL,0),(384,NULL,0),(385,NULL,0),(386,NULL,0),(387,NULL,0),(388,NULL,0),(389,NULL,0),(390,NULL,0),(391,NULL,0),(392,NULL,0),(393,NULL,0),(394,NULL,0),(395,NULL,0),(396,NULL,0),(397,NULL,0),(398,NULL,0),(399,NULL,0),(400,NULL,0),(401,NULL,0),(402,NULL,0),(403,NULL,0),(404,NULL,0),(405,NULL,0),(406,NULL,0),(407,NULL,0),(408,NULL,0),(409,NULL,0),(410,NULL,0),(411,NULL,0),(412,NULL,0),(413,NULL,0),(414,NULL,0),(415,NULL,0),(416,NULL,0),(417,NULL,0),(418,NULL,0),(419,NULL,0),(420,NULL,0),(421,NULL,0),(422,NULL,0),(423,NULL,0),(424,NULL,0),(425,NULL,0),(426,NULL,0),(427,NULL,0),(428,NULL,0),(429,NULL,0),(430,NULL,0),(431,NULL,0),(432,NULL,0),(433,NULL,0),(434,NULL,0),(435,NULL,0),(436,NULL,0),(437,NULL,0),(438,NULL,0),(439,NULL,0),(440,NULL,0),(441,NULL,0),(442,NULL,0),(443,NULL,0),(444,NULL,0),(445,NULL,0),(446,NULL,0),(447,NULL,0),(448,NULL,0),(449,NULL,0),(450,NULL,0),(451,NULL,0),(452,NULL,0),(453,NULL,0),(454,NULL,0),(455,NULL,0),(456,NULL,0),(457,NULL,0),(458,NULL,0),(459,NULL,0),(460,NULL,0),(461,NULL,0),(462,NULL,0),(463,NULL,0),(464,NULL,0),(465,NULL,0),(466,NULL,0),(467,NULL,0),(468,NULL,0),(469,NULL,0),(470,NULL,0),(471,NULL,0),(472,NULL,0),(473,NULL,0),(474,NULL,0),(475,NULL,0),(476,NULL,0),(477,NULL,0),(478,NULL,0),(479,NULL,0),(480,NULL,0),(481,NULL,0),(482,NULL,0),(483,NULL,0),(484,NULL,0),(485,NULL,0),(486,NULL,0),(487,NULL,0),(488,NULL,0),(489,NULL,0),(490,NULL,0),(491,NULL,0),(492,NULL,0),(493,NULL,0),(494,NULL,0),(495,NULL,0),(496,NULL,0),(497,NULL,0),(498,NULL,0),(499,NULL,0),(500,NULL,0),(501,NULL,0),(502,NULL,0),(503,NULL,0),(504,NULL,0),(505,NULL,0),(506,NULL,0),(507,NULL,0),(508,NULL,0),(509,NULL,0),(510,NULL,0),(511,NULL,0),(512,NULL,0),(513,NULL,0),(514,NULL,0),(515,NULL,0),(516,NULL,0),(517,NULL,0),(518,NULL,0),(519,NULL,0),(520,NULL,0),(521,NULL,0),(522,NULL,0),(523,NULL,0),(524,NULL,0),(525,NULL,0),(526,NULL,0),(527,NULL,0),(528,NULL,0),(529,NULL,0),(530,NULL,0),(531,NULL,0),(532,NULL,0),(533,NULL,0),(534,NULL,0),(535,NULL,0),(536,NULL,0),(537,NULL,0),(538,NULL,0),(539,NULL,0),(540,NULL,0),(541,NULL,0),(542,NULL,0),(543,NULL,0),(544,NULL,0),(545,NULL,0),(546,NULL,0),(547,NULL,0),(548,NULL,0),(549,NULL,0),(550,NULL,0),(551,NULL,0),(552,NULL,0),(553,NULL,0),(554,NULL,0),(555,NULL,0),(556,NULL,0),(557,NULL,0),(558,NULL,0),(559,NULL,0),(560,NULL,0),(561,NULL,0),(562,NULL,0),(563,NULL,0),(564,NULL,0),(565,NULL,0),(566,NULL,0),(567,NULL,0),(568,NULL,0),(569,NULL,0),(570,NULL,0),(571,NULL,0),(572,NULL,0),(573,NULL,0),(574,NULL,0),(575,NULL,0),(576,NULL,0),(577,NULL,0),(578,NULL,0),(579,NULL,0),(580,NULL,0),(581,NULL,0),(582,NULL,0),(583,NULL,0),(584,NULL,0),(585,NULL,0),(586,NULL,0),(587,NULL,0),(588,NULL,0),(589,NULL,0),(590,NULL,0),(591,NULL,0),(592,NULL,0),(593,NULL,0),(594,NULL,0),(595,NULL,0),(596,NULL,0),(597,NULL,0),(598,NULL,0),(599,NULL,0),(600,NULL,0),(601,NULL,0),(602,NULL,0),(603,NULL,0),(604,NULL,0),(605,NULL,0),(606,NULL,0),(607,NULL,0),(608,NULL,0),(609,NULL,0),(610,NULL,0),(611,NULL,0),(612,NULL,0),(613,NULL,0),(614,NULL,0),(615,NULL,0),(616,NULL,0),(617,NULL,0),(618,NULL,0),(619,NULL,0),(620,NULL,0),(621,NULL,0),(622,NULL,0),(623,NULL,0),(624,NULL,0),(625,NULL,0),(626,NULL,0),(627,NULL,0),(628,NULL,0),(629,NULL,0),(630,NULL,0),(631,NULL,0),(632,NULL,0),(633,NULL,0),(634,NULL,0),(635,NULL,0),(636,NULL,0),(637,NULL,0),(638,NULL,0),(639,NULL,0),(640,NULL,0),(641,NULL,0),(642,NULL,0),(643,NULL,0),(644,NULL,0),(645,NULL,0),(646,NULL,0),(647,NULL,0),(648,NULL,0),(649,NULL,0),(650,NULL,0),(651,NULL,0),(652,NULL,0),(653,NULL,0),(654,NULL,0),(655,NULL,0),(656,NULL,0),(657,NULL,0),(658,NULL,0),(659,NULL,0),(660,NULL,0),(661,NULL,0),(662,NULL,0),(663,NULL,0),(664,NULL,0),(665,NULL,0),(666,NULL,0),(667,NULL,0),(668,NULL,0),(669,NULL,0),(670,NULL,0),(671,NULL,0),(672,NULL,0),(673,NULL,0),(674,NULL,0),(675,NULL,0),(676,NULL,0),(677,NULL,0),(678,NULL,0),(679,NULL,0),(680,NULL,0),(681,NULL,0),(682,NULL,0),(683,NULL,0),(684,NULL,0),(685,NULL,0),(686,NULL,0),(687,NULL,0),(688,NULL,0),(689,NULL,0),(690,NULL,0),(691,NULL,0),(692,NULL,0),(693,NULL,0),(694,NULL,0),(695,NULL,0),(696,NULL,0),(697,NULL,0),(698,NULL,0),(699,NULL,0),(700,NULL,0),(701,NULL,0),(702,NULL,0),(703,NULL,0),(704,NULL,0),(705,NULL,0),(706,NULL,0),(707,NULL,0),(708,NULL,0),(709,NULL,0),(710,NULL,0),(711,NULL,0),(712,NULL,0),(713,NULL,0),(714,NULL,0),(715,NULL,0),(716,NULL,0),(717,NULL,0),(718,NULL,0),(719,NULL,0),(720,NULL,0),(721,NULL,0),(722,NULL,0),(723,NULL,0),(724,NULL,0),(725,NULL,0),(726,NULL,0),(727,NULL,0),(728,NULL,0),(729,NULL,0),(730,NULL,0),(731,NULL,0),(732,NULL,0),(733,NULL,0),(734,NULL,0),(735,NULL,0),(736,NULL,0),(737,NULL,0),(738,NULL,0),(739,NULL,0),(740,NULL,0),(741,NULL,0),(742,NULL,0),(743,NULL,0),(744,NULL,0),(745,NULL,0),(746,NULL,0),(747,NULL,0),(748,NULL,0),(749,NULL,0),(750,NULL,0),(751,NULL,0),(752,NULL,0),(753,NULL,0),(754,NULL,0),(755,NULL,0),(756,NULL,0),(757,NULL,0),(758,NULL,0),(759,NULL,0),(760,NULL,0),(761,NULL,0),(762,NULL,0),(763,NULL,0),(764,NULL,0),(765,NULL,0),(766,NULL,0),(767,NULL,0),(768,NULL,0),(769,NULL,0),(770,NULL,0),(771,NULL,0),(772,NULL,0),(773,NULL,0),(774,NULL,0),(775,NULL,0),(776,NULL,0),(777,NULL,0),(778,NULL,0),(779,NULL,0),(780,NULL,0),(781,NULL,0),(782,NULL,0),(783,NULL,0),(784,NULL,0),(785,NULL,0),(786,NULL,0),(787,NULL,0),(788,NULL,0),(789,NULL,0),(790,NULL,0),(791,NULL,0),(792,NULL,0),(793,NULL,0),(794,NULL,0),(795,NULL,0),(796,NULL,0),(797,NULL,0),(798,NULL,0),(799,NULL,0),(800,NULL,0),(801,NULL,0),(802,NULL,0),(803,NULL,0),(804,NULL,0),(805,NULL,0),(806,NULL,0),(807,NULL,0),(808,NULL,0),(809,NULL,0),(810,NULL,0),(811,NULL,0),(812,NULL,0),(813,NULL,0),(814,NULL,0),(815,NULL,0),(816,NULL,0),(817,NULL,0),(818,NULL,0),(819,NULL,0),(820,NULL,0),(821,NULL,0),(822,NULL,0),(823,NULL,0),(824,NULL,0),(825,NULL,0),(826,NULL,0),(827,NULL,0),(828,NULL,0),(829,NULL,0),(830,NULL,0),(831,NULL,0),(832,NULL,0),(833,NULL,0),(834,NULL,0),(835,NULL,0),(836,NULL,0),(837,NULL,0),(838,NULL,0),(839,NULL,0),(840,NULL,0),(841,NULL,0),(842,NULL,0),(843,NULL,0),(844,NULL,0),(845,NULL,0),(846,NULL,0),(847,NULL,0),(848,NULL,0),(849,NULL,0),(850,NULL,0),(851,NULL,0),(852,NULL,0),(853,NULL,0),(854,NULL,0),(855,NULL,0),(856,NULL,0),(857,NULL,0),(858,NULL,0),(859,NULL,0),(860,NULL,0),(861,NULL,0),(862,NULL,0),(863,NULL,0),(864,NULL,0),(865,NULL,0),(866,NULL,0),(867,NULL,0),(868,NULL,0),(869,NULL,0),(870,NULL,0),(871,NULL,0),(872,NULL,0),(873,NULL,0),(874,NULL,0),(875,NULL,0),(876,NULL,0),(877,NULL,0),(878,NULL,0),(879,NULL,0),(880,NULL,0),(881,NULL,0),(882,NULL,0),(883,NULL,0),(884,NULL,0),(885,NULL,0),(886,NULL,0),(887,NULL,0),(888,NULL,0),(889,NULL,0),(890,NULL,0),(891,NULL,0),(892,NULL,0),(893,NULL,0),(894,NULL,0),(895,NULL,0),(896,NULL,0),(897,NULL,0),(898,NULL,0),(899,NULL,0),(900,NULL,0),(901,NULL,0),(902,NULL,0),(903,NULL,0),(904,NULL,0),(905,NULL,0),(906,NULL,0),(907,NULL,0),(908,NULL,0),(909,NULL,0),(910,NULL,0),(911,NULL,0),(912,NULL,0),(913,NULL,0),(914,NULL,0),(915,NULL,0),(916,NULL,0),(917,NULL,0),(918,NULL,0),(919,NULL,0),(920,NULL,0),(921,NULL,0),(922,NULL,0),(923,NULL,0),(924,NULL,0),(925,NULL,0),(926,NULL,0),(927,NULL,0),(928,NULL,0),(929,NULL,0),(930,NULL,0),(931,NULL,0),(932,NULL,0),(933,NULL,0),(934,NULL,0),(935,NULL,0),(936,NULL,0),(937,NULL,0),(938,NULL,0),(939,NULL,0),(940,NULL,0),(941,NULL,0),(942,NULL,0),(943,NULL,0),(944,NULL,0),(945,NULL,0),(946,NULL,0),(947,NULL,0),(948,NULL,0),(949,NULL,0),(950,NULL,0),(951,NULL,0),(952,NULL,0),(953,NULL,0),(954,NULL,0),(955,NULL,0),(956,NULL,0),(957,NULL,0),(958,NULL,0),(959,NULL,0),(960,NULL,0),(961,NULL,0),(962,NULL,0),(963,NULL,0),(964,NULL,0),(965,NULL,0),(966,NULL,0),(967,NULL,0),(968,NULL,0),(969,NULL,0),(970,NULL,0),(971,NULL,0),(972,NULL,0),(973,NULL,0),(974,NULL,0),(975,NULL,0),(976,NULL,0),(977,NULL,0),(978,NULL,0),(979,NULL,0),(980,NULL,0),(981,NULL,0),(982,NULL,0),(983,NULL,0),(984,NULL,0),(985,NULL,0),(986,NULL,0),(987,NULL,0),(988,NULL,0),(989,NULL,0),(990,NULL,0),(991,NULL,0),(992,NULL,0),(993,NULL,0),(994,NULL,0),(995,NULL,0),(996,NULL,0),(997,NULL,0),(998,NULL,0),(999,NULL,0),(1000,NULL,0);
/*!40000 ALTER TABLE `ord_waybill` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pay_method`
--

DROP TABLE IF EXISTS `pay_method`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pay_method` (
  `pay_id` int(11) NOT NULL AUTO_INCREMENT,
  `pay_code` varchar(5) NOT NULL,
  `pay_name` varchar(100) NOT NULL,
  `pay_sort` float NOT NULL,
  `pay_status` tinyint(4) NOT NULL,
  PRIMARY KEY (`pay_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pay_method`
--

LOCK TABLES `pay_method` WRITE;
/*!40000 ALTER TABLE `pay_method` DISABLE KEYS */;
INSERT INTO `pay_method` (`pay_id`, `pay_code`, `pay_name`, `pay_sort`, `pay_status`) VALUES (1,'CC','Credit/Debit Card/Paypal/Net Banking (Powered by PayU Money)',1,1),(2,'OC','On credit',2,2),(3,'COD','Cash on delivery',3,2),(4,'BT','Bank Transfer',4,1),(5,'CCZ','Credit/Debit Card/Paypal/Net Banking (Powered by ZaakPay)',1.5,1);
/*!40000 ALTER TABLE `pay_method` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prod_cats`
--

DROP TABLE IF EXISTS `prod_cats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prod_cats` (
  `prod_id` int(11) NOT NULL,
  `level_id` int(11) NOT NULL,
  UNIQUE KEY `unq_prod_level` (`prod_id`,`level_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prod_cats`
--

LOCK TABLES `prod_cats` WRITE;
/*!40000 ALTER TABLE `prod_cats` DISABLE KEYS */;
/*!40000 ALTER TABLE `prod_cats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prod_mast`
--

DROP TABLE IF EXISTS `prod_mast`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prod_mast` (
  `prod_id` int(11) NOT NULL AUTO_INCREMENT,
  `page_title` varchar(200) DEFAULT NULL,
  `meta_key` text,
  `meta_desc` text,
  `prod_stockno` varchar(25) DEFAULT NULL,
  `prod_name` varchar(200) NOT NULL,
  `prod_briefdesc` text,
  `prod_detaildesc` text,
  `prod_ourprice` float NOT NULL,
  `prod_offerprice` float DEFAULT NULL,
  `prod_effectiveprice` float DEFAULT NULL,
  `prod_pricestatus` char(10) DEFAULT NULL,
  `prod_thumb1` longblob,
  `prod_large1` longblob,
  `prod_thumb2` longblob,
  `prod_large2` longblob,
  `prod_thumb3` longblob,
  `prod_large3` longblob,
  `prod_thumb4` longblob,
  `prod_large4` longblob,
  `prod_sort` float DEFAULT NULL,
  `prod_status` tinyint(4) DEFAULT NULL,
  `prod_bestseller` tinyint(4) DEFAULT NULL,
  `prod_bsort` float DEFAULT NULL,
  `prod_new` tinyint(4) DEFAULT NULL,
  `prod_nsort` float DEFAULT NULL,
  `level1_id` int(11) DEFAULT '0',
  `level2_id` int(11) DEFAULT '0',
  `level3_id` int(11) DEFAULT '0',
  `is_vat` tinyint(4) DEFAULT '0',
  `cross_cell_item1` varchar(30) DEFAULT NULL,
  `cross_cell_item2` varchar(30) DEFAULT NULL,
  `cross_cell_item3` varchar(30) DEFAULT NULL,
  `prod_free_text` text,
  `prod_osort` int(11) DEFAULT NULL,
  `prod_weight` float DEFAULT NULL,
  `sell_online` tinyint(4) DEFAULT '0',
  `prod_date` datetime DEFAULT NULL,
  `prod_thumb_display` tinyint(4) DEFAULT NULL,
  `prod_brand_id` int(11) DEFAULT NULL,
  `prod_url` varchar(100) DEFAULT NULL,
  `level_parent` int(11) DEFAULT NULL,
  `prod_sup_id` int(11) DEFAULT NULL,
  `prod_tax_id` int(11) NOT NULL,
  `prod_tax_name` varchar(30) NOT NULL,
  `prod_tax_percent` decimal(5,2) NOT NULL,
  `inserted_date` datetime NOT NULL,
  `prod_effective_price` decimal(10,2) NOT NULL,
  `prod_viewed` bigint(20) NOT NULL,
  `prod_purchased` bigint(20) NOT NULL,
  `prod_outofstock` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`prod_id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prod_mast`
--

LOCK TABLES `prod_mast` WRITE;
/*!40000 ALTER TABLE `prod_mast` DISABLE KEYS */;
/*!40000 ALTER TABLE `prod_mast` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prod_props`
--

DROP TABLE IF EXISTS `prod_props`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prod_props` (
  `prod_id` int(11) NOT NULL,
  `prop_id` int(11) NOT NULL,
  `opt_value` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prod_props`
--

LOCK TABLES `prod_props` WRITE;
/*!40000 ALTER TABLE `prod_props` DISABLE KEYS */;
/*!40000 ALTER TABLE `prod_props` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prod_sup`
--

DROP TABLE IF EXISTS `prod_sup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prod_sup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prod_id` int(11) DEFAULT NULL,
  `sup_id` int(11) DEFAULT NULL,
  `price` decimal(6,2) DEFAULT NULL,
  `offer_price` decimal(6,2) DEFAULT NULL,
  `offer_disc` decimal(6,2) DEFAULT NULL,
  `sup_status` int(11) DEFAULT NULL,
  `final_price` decimal(6,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unq` (`prod_id`,`sup_id`)
) ENGINE=MyISAM AUTO_INCREMENT=203 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prod_sup`
--

LOCK TABLES `prod_sup` WRITE;
/*!40000 ALTER TABLE `prod_sup` DISABLE KEYS */;
/*!40000 ALTER TABLE `prod_sup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prod_viewed`
--

DROP TABLE IF EXISTS `prod_viewed`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prod_viewed` (
  `view_id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(30) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `prod_id` int(11) DEFAULT NULL,
  `view_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `view_count` int(11) DEFAULT NULL,
  PRIMARY KEY (`view_id`)
) ENGINE=MyISAM AUTO_INCREMENT=809 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prod_viewed`
--

LOCK TABLES `prod_viewed` WRITE;
/*!40000 ALTER TABLE `prod_viewed` DISABLE KEYS */;
/*!40000 ALTER TABLE `prod_viewed` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prop_mast`
--

DROP TABLE IF EXISTS `prop_mast`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prop_mast` (
  `prop_id` int(11) NOT NULL AUTO_INCREMENT,
  `prop_name` varchar(40) NOT NULL,
  `prop_status` tinyint(4) NOT NULL,
  PRIMARY KEY (`prop_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prop_mast`
--

LOCK TABLES `prop_mast` WRITE;
/*!40000 ALTER TABLE `prop_mast` DISABLE KEYS */;
/*!40000 ALTER TABLE `prop_mast` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prop_options`
--

DROP TABLE IF EXISTS `prop_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prop_options` (
  `prop_id` int(11) NOT NULL,
  `opt_value` varchar(50) NOT NULL,
  `opt_id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`opt_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prop_options`
--

LOCK TABLES `prop_options` WRITE;
/*!40000 ALTER TABLE `prop_options` DISABLE KEYS */;
/*!40000 ALTER TABLE `prop_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rotating_img_mast`
--

DROP TABLE IF EXISTS `rotating_img_mast`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rotating_img_mast` (
  `rotating_img_id` int(11) NOT NULL,
  `rotating_img_alt_text` varchar(50) DEFAULT NULL,
  `rotating_img` varchar(100) DEFAULT NULL,
  `rotating_img_sort` decimal(4,0) DEFAULT NULL,
  `rotating_img_status` tinyint(4) DEFAULT NULL,
  `rotating_img_target_url` varchar(100) DEFAULT NULL,
  `rotating_img_target_window` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`rotating_img_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rotating_img_mast`
--

LOCK TABLES `rotating_img_mast` WRITE;
/*!40000 ALTER TABLE `rotating_img_mast` DISABLE KEYS */;
/*!40000 ALTER TABLE `rotating_img_mast` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ship_zone`
--

DROP TABLE IF EXISTS `ship_zone`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ship_zone` (
  `ship_id` int(11) NOT NULL AUTO_INCREMENT,
  `zone_name` varchar(50) NOT NULL,
  `ord_upto` decimal(10,0) NOT NULL,
  `ship_charge` decimal(10,0) NOT NULL,
  PRIMARY KEY (`ship_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ship_zone`
--

LOCK TABLES `ship_zone` WRITE;
/*!40000 ALTER TABLE `ship_zone` DISABLE KEYS */;
INSERT INTO `ship_zone` (`ship_id`, `zone_name`, `ord_upto`, `ship_charge`) VALUES (1,'Western',100,19),(2,'Western',290,50);
/*!40000 ALTER TABLE `ship_zone` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `state_mast`
--

DROP TABLE IF EXISTS `state_mast`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `state_mast` (
  `state_id` int(11) NOT NULL AUTO_INCREMENT,
  `state_name` varchar(50) NOT NULL,
  `state_zone` varchar(50) NOT NULL,
  PRIMARY KEY (`state_id`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `state_mast`
--

LOCK TABLES `state_mast` WRITE;
/*!40000 ALTER TABLE `state_mast` DISABLE KEYS */;
INSERT INTO `state_mast` (`state_id`, `state_name`, `state_zone`) VALUES (1,'Andhra Pradesh','Southern'),(2,'Arunachal Pradesh','Northeastern'),(3,'Assam','Northeastern'),(4,'Bihar','Eastern'),(5,'Chandigarh','Northern'),(6,'Chhattisgarh','Central'),(7,'Delhi','Northern'),(8,'Goa','Western'),(9,'Gujarat','Western'),(10,'Haryana','Northern'),(11,'Himachal Pradesh','Northern'),(12,'Jammu and Kashmir','Northern'),(13,'Jharkhand','Eastern'),(14,'Karnataka','Southern'),(15,'Kerala','Southern'),(16,'Madhya Pradesh','Central'),(17,'Maharashtra','Western'),(18,'Manipur','Eastern'),(19,'Meghalaya','Northeastern'),(20,'Mizoram','Eastern'),(21,'Nagaland','Eastern'),(22,'Odisha','Eastern'),(23,'Puducherry','Southern'),(24,'Punjab','Northern'),(25,'Rajasthan','Western'),(26,'Sikkim','Northeastern'),(27,'Tamil Nadu','Southern'),(28,'Telangana','Southern'),(29,'Tripura','Eastern'),(30,'Uttar Pradesh','Northern'),(31,'Uttarakhand','Northern'),(32,'West Bengal','Eastern'),(33,'Andaman and Nicobar Islands','Southern');
/*!40000 ALTER TABLE `state_mast` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sup_ext_addr`
--

DROP TABLE IF EXISTS `sup_ext_addr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sup_ext_addr` (
  `addr_id` int(11) NOT NULL AUTO_INCREMENT,
  `sup_id` int(11) NOT NULL COMMENT 'Seller ID',
  `sup_ext_name` varchar(50) DEFAULT NULL COMMENT 'Establishment Name',
  `sup_ext_address` varchar(100) DEFAULT NULL COMMENT 'Seller''s additional  address',
  `sup_ext_state` varchar(50) DEFAULT NULL COMMENT 'State',
  `sup_ext_city` varchar(50) DEFAULT NULL COMMENT 'City',
  `sup_ext_address_type` varchar(50) DEFAULT NULL COMMENT 'Address type',
  `sup_ext_contact_no` varchar(50) DEFAULT NULL COMMENT 'contact number',
  `sup_ext_pincode` varchar(50) DEFAULT NULL COMMENT 'Pincode',
  PRIMARY KEY (`addr_id`),
  KEY `sup_id` (`sup_id`),
  CONSTRAINT `Supplier id foriegn key` FOREIGN KEY (`sup_id`) REFERENCES `sup_mast` (`sup_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sup_ext_addr`
--

LOCK TABLES `sup_ext_addr` WRITE;
/*!40000 ALTER TABLE `sup_ext_addr` DISABLE KEYS */;
/*!40000 ALTER TABLE `sup_ext_addr` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sup_mast`
--

DROP TABLE IF EXISTS `sup_mast`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sup_mast` (
  `sup_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Seller ID',
  `sup_seller_token` varchar(7) DEFAULT NULL COMMENT 'An alpha numeric code of length 6, unique to every seller',
  `sup_company` varchar(50) DEFAULT NULL COMMENT 'Company Name',
  `sup_business_type` varchar(50) DEFAULT NULL COMMENT 'Type of Business',
  `sup_email` varchar(50) NOT NULL COMMENT 'Email address',
  `sup_contact_no` varchar(50) NOT NULL COMMENT 'seller conntact',
  `sup_alt_contact_no` int(11) DEFAULT NULL COMMENT 'Alternate contact number',
  `sup_logo` varchar(100) DEFAULT NULL COMMENT 'Seller Logo',
  `sup_pwd` varchar(50) NOT NULL COMMENT 'Seller password',
  `sup_active_status` int(11) NOT NULL DEFAULT '0' COMMENT 'Activation status',
  `sup_activation` varchar(50) DEFAULT NULL COMMENT 'Activtion code',
  `sup_admin_approval` int(1) NOT NULL DEFAULT '0' COMMENT 'Admin''s approval after verification of seller''s documents.',
  `sup_shop_act_license` varchar(50) DEFAULT NULL COMMENT 'Shop act license document',
  `sup_pan` varchar(20) DEFAULT NULL COMMENT 'Establishment PAN',
  `sup_vat` varchar(20) DEFAULT NULL COMMENT 'Central Excise & Service Tax number',
  `sup_cstn` varchar(20) DEFAULT NULL COMMENT 'Central Sales Tax Account number',
  `sup_bk_acc` varchar(20) DEFAULT NULL COMMENT 'Bank account number',
  `sup_bk_ifsc` varchar(20) DEFAULT NULL COMMENT 'IFSC Code',
  `sup_bk_name` varchar(50) DEFAULT NULL COMMENT 'Bank Name',
  `sup_bk_brname` varchar(50) DEFAULT NULL COMMENT 'Branch Name',
  `sup_bk_can_chk` varchar(50) DEFAULT NULL COMMENT 'Cancelled check for the bank account',
  `sup_contact_name` varchar(50) DEFAULT NULL COMMENT 'Seller Name',
  `sup_reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sup_vat_doc` varchar(100) DEFAULT NULL COMMENT 'VAT ceertificate',
  `sup_pan_doc` varchar(100) DEFAULT NULL COMMENT 'PAN document',
  `sup_cst_doc` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`sup_id`),
  UNIQUE KEY `sup_id_2` (`sup_id`),
  UNIQUE KEY `sup_email` (`sup_email`),
  KEY `sup_id` (`sup_id`),
  KEY `sup_id_3` (`sup_id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sup_mast`
--

LOCK TABLES `sup_mast` WRITE;
/*!40000 ALTER TABLE `sup_mast` DISABLE KEYS */;
/*!40000 ALTER TABLE `sup_mast` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sup_type_mast`
--

DROP TABLE IF EXISTS `sup_type_mast`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sup_type_mast` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(30) NOT NULL,
  `type_sort` int(11) NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sup_type_mast`
--

LOCK TABLES `sup_type_mast` WRITE;
/*!40000 ALTER TABLE `sup_type_mast` DISABLE KEYS */;
INSERT INTO `sup_type_mast` (`type_id`, `type_name`, `type_sort`) VALUES (1,'Reseller',1),(2,'Dealer',2),(3,'Distributor',3),(4,'Manufacturer',4);
/*!40000 ALTER TABLE `sup_type_mast` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tax_mast`
--

DROP TABLE IF EXISTS `tax_mast`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tax_mast` (
  `tax_id` int(11) NOT NULL AUTO_INCREMENT,
  `tax_name` varchar(30) NOT NULL,
  `tax_percent` decimal(6,2) NOT NULL,
  `tax_status` tinyint(4) NOT NULL,
  PRIMARY KEY (`tax_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COMMENT='To store Tax types based on product types';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tax_mast`
--

LOCK TABLES `tax_mast` WRITE;
/*!40000 ALTER TABLE `tax_mast` DISABLE KEYS */;
INSERT INTO `tax_mast` (`tax_id`, `tax_name`, `tax_percent`, `tax_status`) VALUES (1,'VAT',5.00,1);
/*!40000 ALTER TABLE `tax_mast` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_mast`
--

DROP TABLE IF EXISTS `user_mast`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_mast` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_email` varchar(100) NOT NULL,
  `user_pwd` varchar(20) NOT NULL,
  `user_type` varchar(10) NOT NULL,
  `user_status` int(11) NOT NULL DEFAULT '0',
  `user_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_name` varchar(120) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_mast`
--

LOCK TABLES `user_mast` WRITE;
/*!40000 ALTER TABLE `user_mast` DISABLE KEYS */;
INSERT INTO `user_mast` (`user_id`, `user_email`, `user_pwd`, `user_type`, `user_status`, `user_created`, `user_name`) VALUES (1,'ammart_786@live.in','yahusain','AM',1,'2015-09-22 03:03:18','ammar'),(2,'taizun@alifmart.com','qwerty','FM',1,'2015-10-27 06:42:10','Taizun Kachwala'),(3,'info@alifmart.com','alifinfo@123','LP',1,'2015-10-30 12:57:51','Delhivery');
/*!40000 ALTER TABLE `user_mast` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `vw_user`
--

DROP TABLE IF EXISTS `vw_user`;
/*!50001 DROP VIEW IF EXISTS `vw_user`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `vw_user` (
  `user_type` tinyint NOT NULL,
  `memb_id` tinyint NOT NULL,
  `memb_email` tinyint NOT NULL,
  `memb_pwd` tinyint NOT NULL,
  `memb_fname` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Dumping events for database 'alifmart'
--

--
-- Dumping routines for database 'alifmart'
--

--
-- Final view structure for view `vw_user`
--

/*!50001 DROP TABLE IF EXISTS `vw_user`*/;
/*!50001 DROP VIEW IF EXISTS `vw_user`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`alifmartdba`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_user` AS select 'M' AS `user_type`,`member_mast`.`memb_id` AS `memb_id`,`member_mast`.`memb_email` AS `memb_email`,`member_mast`.`memb_pwd` AS `memb_pwd`,`member_mast`.`memb_fname` AS `memb_fname` from `member_mast` where ((`member_mast`.`memb_act_status` = 1) and (`member_mast`.`memb_status` = 1)) union select 'S' AS `S`,`sup_mast`.`sup_id` AS `sup_id`,`sup_mast`.`sup_email` AS `sup_email`,`sup_mast`.`sup_pwd` AS `sup_pwd`,`sup_mast`.`sup_contact_name` AS `sup_contact_name` from `sup_mast` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-11-16  6:49:17
