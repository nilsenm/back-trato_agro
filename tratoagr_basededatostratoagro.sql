-- MariaDB dump 10.19  Distrib 10.6.11-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: tratoagr_basededatostratoagro
-- ------------------------------------------------------
-- Server version	10.6.11-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `tratoagr_basededatostratoagro`
--


--
-- Table structure for table `categoria`
--

DROP TABLE IF EXISTS `categoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categoria` (
  `id_categoria` bigint(20) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(300) DEFAULT NULL,
  `icono` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id_categoria`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoria`
--

LOCK TABLES `categoria` WRITE;
/*!40000 ALTER TABLE `categoria` DISABLE KEYS */;
INSERT INTO `categoria` (`id_categoria`, `nombre`, `icono`) VALUES (1,'Ganadería','https://tratoagro.com/TratoAgroNuevaCarpeta/tratoagro/img/ganaderia.png'),(2,'Maquinaria','https://tratoagro.com/TratoAgroNuevaCarpeta/tratoagro/img/maquinaria.png'),(3,'Tubérculos','https://tratoagro.com/TratoAgroNuevaCarpeta/tratoagro/img/insumos.png'),(4,'Pesticidas','https://tratoagro.com/TratoAgroNuevaCarpeta/tratoagro/img/pesticidas.png'),(5,'Fertilizantes','https://tratoagro.com/TratoAgroNuevaCarpeta/tratoagro/img/fertilizantes.png'),(6,'Pescados','https://tratoagro.com/TratoAgroNuevaCarpeta/tratoagro/img/pesca.png');
/*!40000 ALTER TABLE `categoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departamento`
--

DROP TABLE IF EXISTS `departamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `departamento` (
  `id_departamento` bigint(20) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departamento`
--

LOCK TABLES `departamento` WRITE;
/*!40000 ALTER TABLE `departamento` DISABLE KEYS */;
INSERT INTO `departamento` (`id_departamento`, `nombre`) VALUES (1,'Amazonas'),(2,'Áncash'),(3,'Apurímac'),(4,'Arequipa'),(5,'Ayacucho'),(6,'Cajamarca'),(7,'Callao'),(8,'Cusco'),(9,'Huancavelica'),(10,'Huánuco'),(11,'Ica'),(12,'Junín'),(13,'La Libertad'),(14,'Lambayeque'),(15,'Lima'),(16,'Loreto'),(17,'Madre de Dios'),(18,'Moquegua'),(19,'Pasco'),(20,'Piura'),(21,'Puno'),(22,'San Martín'),(23,'Tacna'),(24,'Tumbes'),(25,'Ucayali');
/*!40000 ALTER TABLE `departamento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_venta`
--

DROP TABLE IF EXISTS `detalle_venta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detalle_venta` (
  `id_detalle_venta` bigint(20) NOT NULL AUTO_INCREMENT,
  `cantidad` int(11) DEFAULT NULL,
  `id_stock` bigint(20) DEFAULT NULL,
  `id_venta` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id_detalle_venta`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_venta`
--

LOCK TABLES `detalle_venta` WRITE;
/*!40000 ALTER TABLE `detalle_venta` DISABLE KEYS */;
INSERT INTO `detalle_venta` (`id_detalle_venta`, `cantidad`, `id_stock`, `id_venta`) VALUES (1,3,1,1),(2,1,4,2),(3,2,5,2),(4,2,8,2),(5,1,13,3);
/*!40000 ALTER TABLE `detalle_venta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `distrito`
--

DROP TABLE IF EXISTS `distrito`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `distrito` (
  `id_distrito` bigint(20) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `id_provincia` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `distrito`
--

LOCK TABLES `distrito` WRITE;
/*!40000 ALTER TABLE `distrito` DISABLE KEYS */;
INSERT INTO `distrito` (`id_distrito`, `nombre`, `id_provincia`) VALUES (1,'Chachapoyas',1),(2,'Asuncion',1),(3,'Balsas',1),(4,'Cheto',1),(5,'Chiliquin',1),(6,'Chuquibamba',1),(7,'Granada',1),(8,'Huancas',1),(9,'La Jalca',1),(10,'Leimebamba',1),(11,'Levanto',1),(12,'Magdalena',1),(13,'Mariscal Castilla',1),(14,'Molinopampa',1),(15,'Montevideo',1),(16,'Olleros',1),(17,'Quinjalca',1),(18,'San Francisco de Daguas',1),(19,'San Isidro de Maino',1),(20,'Soloco',1),(21,'Sonche',1),(22,'Bagua',2),(23,'Aramango',2),(24,'Copallin',2),(25,'El Parco',2),(26,'Imaza',2),(27,'La Peca',2),(28,'Jumbilla',3),(29,'Chisquilla',3),(30,'Churuja',3),(31,'Corosha',3),(32,'Cuispes',3),(33,'Florida',3),(34,'Jazan',3),(35,'Recta',3),(36,'San Carlos',3),(37,'Shipasbamba',3),(38,'Valera',3),(39,'Yambrasbamba',3),(40,'Nieva',4),(41,'El Cenepa',4),(42,'Rio Santiago',4),(43,'Lamud',5),(44,'Camporredondo',5),(45,'Cocabamba',5),(46,'Colcamar',5),(47,'Conila',5),(48,'Inguilpata',5),(49,'Longuita',5),(50,'Lonya Chico',5),(51,'Luya',5),(52,'Luya Viejo',5),(53,'Maria',5),(54,'Ocalli',5),(55,'Ocumal',5),(56,'Pisuquia',5),(57,'Providencia',5),(58,'San Cristobal',5),(59,'San Francisco del Yeso',5),(60,'San Jeronimo',5),(61,'San Juan de Lopecancha',5),(62,'Santa Catalina',5),(63,'Santo Tomas',5),(64,'Tingo',5),(65,'Trita',5),(66,'San Nicolas',6),(67,'Chirimoto',6),(68,'Cochamal',6),(69,'Huambo',6),(70,'Limabamba',6),(71,'Longar',6),(72,'Mariscal Benavides',6),(73,'Milpuc',6),(74,'Omia',6),(75,'Santa Rosa',6),(76,'Totora',6),(77,'Vista Alegre',6),(78,'Bagua Grande',7),(79,'Cajaruro',7),(80,'Cumba',7),(81,'El Milagro',7),(82,'Jamalca',7),(83,'Lonya Grande',7),(84,'Yamon',7),(85,'Huaraz',8),(86,'Cochabamba',8),(87,'Colcabamba',8),(88,'Huanchay',8),(89,'Independencia',8),(90,'Jangas',8),(91,'La Libertad',8),(92,'Olleros',8),(93,'Pampas',8),(94,'Pariacoto',8),(95,'Pira',8),(96,'Tarica',8),(97,'Aija',9),(98,'Coris',9),(99,'Huacllan',9),(100,'La Merced',9),(101,'Succha',9),(102,'Llamellin',10),(103,'Aczo',10),(104,'Chaccho',10),(105,'Chingas',10),(106,'Mirgas',10),(107,'San Juan de Rontoy',10),(108,'Chacas',11),(109,'Acochaca',11),(110,'Chiquian',12),(111,'Abelardo Pardo Lezameta',12),(112,'Antonio Raymondi',12),(113,'Aquia',12),(114,'Cajacay',12),(115,'Canis',12),(116,'Colquioc',12),(117,'Huallanca',12),(118,'Huasta',12),(119,'Huayllacayan',12),(120,'La Primavera',12),(121,'Mangas',12),(122,'Pacllon',12),(123,'San Miguel de Corpanqui',12),(124,'Ticllos',12),(125,'Carhuaz',13),(126,'Acopampa',13),(127,'Amashca',13),(128,'Anta',13),(129,'Ataquero',13),(130,'Marcara',13),(131,'Pariahuanca',13),(132,'San Miguel de Aco',13),(133,'Shilla',13),(134,'Tinco',13),(135,'Yungar',13),(136,'San Luis',14),(137,'San Nicolas',14),(138,'Yauya',14),(139,'Casma',15),(140,'Buena Vista Alta',15),(141,'Comandante Noel',15),(142,'Yautan',15),(143,'Corongo',16),(144,'Aco',16),(145,'Bambas',16),(146,'Cusca',16),(147,'La Pampa',16),(148,'Yanac',16),(149,'Yupan',16),(150,'Huari',17),(151,'Anra',17),(152,'Cajay',17),(153,'Chavin de Huantar',17),(154,'Huacachi',17),(155,'Huacchis',17),(156,'Huachis',17),(157,'Huantar',17),(158,'Masin',17),(159,'Paucas',17),(160,'Ponto',17),(161,'Rahuapampa',17),(162,'Rapayan',17),(163,'San Marcos',17),(164,'San Pedro de Chana',17),(165,'Uco',17),(166,'Huarmey',18),(167,'Cochapeti',18),(168,'Culebras',18),(169,'Huayan',18),(170,'Malvas',18),(171,'Caraz',19),(172,'Huallanca',19),(173,'Huata',19),(174,'Huaylas',19),(175,'Mato',19),(176,'Pamparomas',19),(177,'Pueblo Libre',19),(178,'Santa Cruz',19),(179,'Santo Toribio',19),(180,'Yuracmarca',19),(181,'Piscobamba',20),(182,'Casca',20),(183,'Eleazar Guzman Barron',20),(184,'Fidel Olivas Escudero',20),(185,'Llama',20),(186,'Llumpa',20),(187,'Lucma',20),(188,'Musga',20),(189,'Ocros',21),(190,'Acas',21),(191,'Cajamarquilla',21),(192,'Carhuapampa',21),(193,'Cochas',21),(194,'Congas',21),(195,'Llipa',21),(196,'San Cristobal de Rajan',21),(197,'San Pedro',21),(198,'Santiago de Chilcas',21),(199,'Cabana',22),(200,'Bolognesi',22),(201,'Conchucos',22),(202,'Huacaschuque',22),(203,'Huandoval',22),(204,'Lacabamba',22),(205,'Llapo',22),(206,'Pallasca',22),(207,'Pampas',22),(208,'Santa Rosa',22),(209,'Tauca',22),(210,'Pomabamba',23),(211,'Huayllan',23),(212,'Parobamba',23),(213,'Quinuabamba',23),(214,'Recuay',24),(215,'Catac',24),(216,'Cotaparaco',24),(217,'Huayllapampa',24),(218,'Llacllin',24),(219,'Marca',24),(220,'Pampas Chico',24),(221,'Pararin',24),(222,'Tapacocha',24),(223,'Ticapampa',24),(224,'Chimbote',25),(225,'Caceres del Peru',25),(226,'Coishco',25),(227,'Macate',25),(228,'Moro',25),(229,'Nepeña',25),(230,'Samanco',25),(231,'Santa',25),(232,'Nuevo Chimbote',25),(233,'Sihuas',26),(234,'Acobamba',26),(235,'Alfonso Ugarte',26),(236,'Cashapampa',26),(237,'Chingalpo',26),(238,'Huayllabamba',26),(239,'Quiches',26),(240,'Ragash',26),(241,'San Juan',26),(242,'Sicsibamba',26),(243,'Yungay',27),(244,'Cascapara',27),(245,'Mancos',27),(246,'Matacoto',27),(247,'Quillo',27),(248,'Ranrahirca',27),(249,'Shupluy',27),(250,'Yanama',27),(251,'Abancay',28),(252,'Chacoche',28),(253,'Circa',28),(254,'Curahuasi',28),(255,'Huanipaca',28),(256,'Lambrama',28),(257,'Pichirhua',28),(258,'San Pedro de Cachora',28),(259,'Tamburco',28),(260,'Andahuaylas',29),(261,'Andarapa',29),(262,'Chiara',29),(263,'Huancarama',29),(264,'Huancaray',29),(265,'Huayana',29),(266,'Kishuara',29),(267,'Pacobamba',29),(268,'Pacucha',29),(269,'Pampachiri',29),(270,'Pomacocha',29),(271,'San Antonio de Cachi',29),(272,'San Jeronimo',29),(273,'San Miguel de Chaccrampa',29),(274,'Santa Maria de Chicmo',29),(275,'Talavera',29),(276,'Tumay Huaraca',29),(277,'Turpo',29),(278,'Kaquiabamba',29),(279,'Antabamba',30),(280,'El Oro',30),(281,'Huaquirca',30),(282,'Juan Espinoza Medrano',30),(283,'Oropesa',30),(284,'Pachaconas',30),(285,'Sabaino',30),(286,'Chalhuanca',31),(287,'Capaya',31),(288,'Caraybamba',31),(289,'Chapimarca',31),(290,'Colcabamba',31),(291,'Cotaruse',31),(292,'Huayllo',31),(293,'Justo Apu Sahuaraura',31),(294,'Lucre',31),(295,'Pocohuanca',31),(296,'San Juan de Chacña',31),(297,'Sañayca',31),(298,'Soraya',31),(299,'Tapairihua',31),(300,'Tintay',31),(301,'Toraya',31),(302,'Yanaca',31),(303,'Tambobamba',32),(304,'Cotabambas',32),(305,'Coyllurqui',32),(306,'Haquira',32),(307,'Mara',32),(308,'Challhuahuacho',32),(309,'Chincheros',33),(310,'Anco_Huallo',33),(311,'Cocharcas',33),(312,'Huaccana',33),(313,'Ocobamba',33),(314,'Ongoy',33),(315,'Uranmarca',33),(316,'Ranracancha',33),(317,'Chuquibambilla',34),(318,'Curpahuasi',34),(319,'Gamarra',34),(320,'Huayllati',34),(321,'Mamara',34),(322,'Micaela Bastidas',34),(323,'Pataypampa',34),(324,'Progreso',34),(325,'San Antonio',34),(326,'Santa Rosa',34),(327,'Turpay',34),(328,'Vilcabamba',34),(329,'Virundo',34),(330,'Curasco',34),(331,'Arequipa',35),(332,'Alto Selva Alegre',35),(333,'Cayma',35),(334,'Cerro Colorado',35),(335,'Characato',35),(336,'Chiguata',35),(337,'Jacobo Hunter',35),(338,'La Joya',35),(339,'Mariano Melgar',35),(340,'Miraflores',35),(341,'Mollebaya',35),(342,'Paucarpata',35),(343,'Pocsi',35),(344,'Polobaya',35),(345,'Quequeña',35),(346,'Sabandia',35),(347,'Sachaca',35),(348,'San Juan de Siguas',35),(349,'San Juan de Tarucani',35),(350,'Santa Isabel de Siguas',35),(351,'Santa Rita de Siguas',35),(352,'Socabaya',35),(353,'Tiabaya',35),(354,'Uchumayo',35),(355,'Vitor',35),(356,'Yanahuara',35),(357,'Yarabamba',35),(358,'Yura',35),(359,'Jose Luis Bustamante y Rivero',35),(360,'Camana',36),(361,'Jose Maria Quimper',36),(362,'Mariano Nicolas Valcarcel',36),(363,'Mariscal Caceres',36),(364,'Nicolas de Pierola',36),(365,'Ocoña',36),(366,'Quilca',36),(367,'Samuel Pastor',36),(368,'Caraveli',37),(369,'Acari',37),(370,'Atico',37),(371,'Atiquipa',37),(372,'Bella Union',37),(373,'Cahuacho',37),(374,'Chala',37),(375,'Chaparra',37),(376,'Huanuhuanu',37),(377,'Jaqui',37),(378,'Lomas',37),(379,'Quicacha',37),(380,'Yauca',37),(381,'Aplao',38),(382,'Andagua',38),(383,'Ayo',38),(384,'Chachas',38),(385,'Chilcaymarca',38),(386,'Choco',38),(387,'Huancarqui',38),(388,'Machaguay',38),(389,'Orcopampa',38),(390,'Pampacolca',38),(391,'Tipan',38),(392,'Uñon',38),(393,'Uraca',38),(394,'Viraco',38),(395,'Chivay',39),(396,'Achoma',39),(397,'Cabanaconde',39),(398,'Callalli',39),(399,'Caylloma',39),(400,'Coporaque',39),(401,'Huambo',39),(402,'Huanca',39),(403,'Ichupampa',39),(404,'Lari',39),(405,'Lluta',39),(406,'Maca',39),(407,'Madrigal',39),(408,'San Antonio de Chuca',39),(409,'Sibayo',39),(410,'Tapay',39),(411,'Tisco',39),(412,'Tuti',39),(413,'Yanque',39),(414,'Majes',39),(415,'Chuquibamba',40),(416,'Andaray',40),(417,'Cayarani',40),(418,'Chichas',40),(419,'Iray',40),(420,'Rio Grande',40),(421,'Salamanca',40),(422,'Yanaquihua',40),(423,'Mollendo',41),(424,'Cocachacra',41),(425,'Dean Valdivia',41),(426,'Islay',41),(427,'Mejia',41),(428,'Punta de Bombon',41),(429,'Cotahuasi',42),(430,'Alca',42),(431,'Charcana',42),(432,'Huaynacotas',42),(433,'Pampamarca',42),(434,'Puyca',42),(435,'Quechualla',42),(436,'Sayla',42),(437,'Tauria',42),(438,'Tomepampa',42),(439,'Toro',42),(440,'Ayacucho',43),(441,'Acocro',43),(442,'Acos Vinchos',43),(443,'Carmen Alto',43),(444,'Chiara',43),(445,'Ocros',43),(446,'Pacaycasa',43),(447,'Quinua',43),(448,'San Jose de Ticllas',43),(449,'San Juan Bautista',43),(450,'Santiago de Pischa',43),(451,'Socos',43),(452,'Tambillo',43),(453,'Vinchos',43),(454,'Jesus Nazareno',43),(455,'Cangallo',44),(456,'Chuschi',44),(457,'Los Morochucos',44),(458,'Maria Parado de Bellido',44),(459,'Paras',44),(460,'Totos',44),(461,'Sancos',45),(462,'Carapo',45),(463,'Sacsamarca',45),(464,'Santiago de Lucanamarca',45),(465,'Huanta',46),(466,'Ayahuanco',46),(467,'Huamanguilla',46),(468,'Iguain',46),(469,'Luricocha',46),(470,'Santillana',46),(471,'Sivia',46),(472,'Llochegua',46),(473,'San Miguel',47),(474,'Anco',47),(475,'Ayna',47),(476,'Chilcas',47),(477,'Chungui',47),(478,'Luis Carranza',47),(479,'Santa Rosa',47),(480,'Tambo',47),(481,'Samugari',47),(482,'Puquio',48),(483,'Aucara',48),(484,'Cabana',48),(485,'Carmen Salcedo',48),(486,'Chaviña',48),(487,'Chipao',48),(488,'Huac-Huas',48),(489,'Laramate',48),(490,'Leoncio Prado',48),(491,'Llauta',48),(492,'Lucanas',48),(493,'Ocaña',48),(494,'Otoca',48),(495,'Saisa',48),(496,'San Cristobal',48),(497,'San Juan',48),(498,'San Pedro',48),(499,'San Pedro de Palco',48),(500,'Sancos',48),(501,'Santa Ana de Huaycahuacho',48),(502,'Santa Lucia',48),(503,'Coracora',49),(504,'Chumpi',49),(505,'Coronel Castañeda',49),(506,'Pacapausa',49),(507,'Pullo',49),(508,'Puyusca',49),(509,'San Francisco de Ravacayco',49),(510,'Upahuacho',49),(511,'Pausa',50),(512,'Colta',50),(513,'Corculla',50),(514,'Lampa',50),(515,'Marcabamba',50),(516,'Oyolo',50),(517,'Pararca',50),(518,'San Javier de Alpabamba',50),(519,'San Jose de Ushua',50),(520,'Sara Sara',50),(521,'Querobamba',51),(522,'Belen',51),(523,'Chalcos',51),(524,'Chilcayoc',51),(525,'Huacaña',51),(526,'Morcolla',51),(527,'Paico',51),(528,'San Pedro de Larcay',51),(529,'San Salvador de Quije',51),(530,'Santiago de Paucaray',51),(531,'Soras',51),(532,'Huancapi',52),(533,'Alcamenca',52),(534,'Apongo',52),(535,'Asquipata',52),(536,'Canaria',52),(537,'Cayara',52),(538,'Colca',52),(539,'Huamanquiquia',52),(540,'Huancaraylla',52),(541,'Huaya',52),(542,'Sarhua',52),(543,'Vilcanchos',52),(544,'Vilcas Huaman',53),(545,'Accomarca',53),(546,'Carhuanca',53),(547,'Concepcion',53),(548,'Huambalpa',53),(549,'Independencia',53),(550,'Saurama',53),(551,'Vischongo',53),(552,'Cajamarca',54),(553,'Asuncion',54),(554,'Chetilla',54),(555,'Cospan',54),(556,'Encañada',54),(557,'Jesus',54),(558,'Llacanora',54),(559,'Los Baños del Inca',54),(560,'Magdalena',54),(561,'Matara',54),(562,'Namora',54),(563,'San Juan',54),(564,'Cajabamba',55),(565,'Cachachi',55),(566,'Condebamba',55),(567,'Sitacocha',55),(568,'Celendin',56),(569,'Chumuch',56),(570,'Cortegana',56),(571,'Huasmin',56),(572,'Jorge Chavez',56),(573,'Jose Galvez',56),(574,'Miguel Iglesias',56),(575,'Oxamarca',56),(576,'Sorochuco',56),(577,'Sucre',56),(578,'Utco',56),(579,'La Libertad de Pallan',56),(580,'Chota',57),(581,'Anguia',57),(582,'Chadin',57),(583,'Chiguirip',57),(584,'Chimban',57),(585,'Choropampa',57),(586,'Cochabamba',57),(587,'Conchan',57),(588,'Huambos',57),(589,'Lajas',57),(590,'Llama',57),(591,'Miracosta',57),(592,'Paccha',57),(593,'Pion',57),(594,'Querocoto',57),(595,'San Juan de Licupis',57),(596,'Tacabamba',57),(597,'Tocmoche',57),(598,'Chalamarca',57),(599,'Contumaza',58),(600,'Chilete',58),(601,'Cupisnique',58),(602,'Guzmango',58),(603,'San Benito',58),(604,'Santa Cruz de Toled',58),(605,'Tantarica',58),(606,'Yonan',58),(607,'Cutervo',59),(608,'Callayuc',59),(609,'Choros',59),(610,'Cujillo',59),(611,'La Ramada',59),(612,'Pimpingos',59),(613,'Querocotillo',59),(614,'San Andres de Cutervo',59),(615,'San Juan de Cutervo',59),(616,'San Luis de Lucma',59),(617,'Santa Cruz',59),(618,'Santo Domingo de La Capilla',59),(619,'Santo Tomas',59),(620,'Socota',59),(621,'Toribio Casanova',59),(622,'Bambamarca',60),(623,'Chugur',60),(624,'Hualgayoc',60),(625,'Jaen',61),(626,'Bellavista',61),(627,'Chontali',61),(628,'Colasay',61),(629,'Huabal',61),(630,'Las Pirias',61),(631,'Pomahuaca',61),(632,'Pucara',61),(633,'Sallique',61),(634,'San Felipe',61),(635,'San Jose del Alto',61),(636,'Santa Rosa',61),(637,'San Ignacio',62),(638,'Chirinos',62),(639,'Huarango',62),(640,'La Coipa',62),(641,'Namballe',62),(642,'San Jose de Lourdes',62),(643,'Tabaconas',62),(644,'Pedro Galvez',63),(645,'Chancay',63),(646,'Eduardo Villanueva',63),(647,'Gregorio Pita',63),(648,'Ichocan',63),(649,'Jose Manuel Quiroz',63),(650,'Jose Sabogal',63),(651,'San Miguel',64),(652,'Bolivar',64),(653,'Calquis',64),(654,'Catilluc',64),(655,'El Prado',64),(656,'La Florida',64),(657,'Llapa',64),(658,'Nanchoc',64),(659,'Niepos',64),(660,'San Gregorio',64),(661,'San Silvestre de Cochan',64),(662,'Tongod',64),(663,'Union Agua Blanca',64),(664,'San Pablo',65),(665,'San Bernardino',65),(666,'San Luis',65),(667,'Tumbaden',65),(668,'Santa Cruz',66),(669,'Andabamba',66),(670,'Catache',66),(671,'Chancaybaños',66),(672,'La Esperanza',66),(673,'Ninabamba',66),(674,'Pulan',66),(675,'Saucepampa',66),(676,'Sexi',66),(677,'Uticyacu',66),(678,'Yauyucan',66),(679,'Callao',67),(680,'Bellavista',67),(681,'Carmen de La Legua',67),(682,'La Perla',67),(683,'La Punta',67),(684,'Ventanilla',67),(685,'Cusco',68),(686,'Ccorca',68),(687,'Poroy',68),(688,'San Jeronimo',68),(689,'San Sebastian',68),(690,'Santiago',68),(691,'Saylla',68),(692,'Wanchaq',68),(693,'Acomayo',69),(694,'Acopia',69),(695,'Acos',69),(696,'Mosoc Llacta',69),(697,'Pomacanchi',69),(698,'Rondocan',69),(699,'Sangarara',69),(700,'Anta',70),(701,'Ancahuasi',70),(702,'Cachimayo',70),(703,'Chinchaypujio',70),(704,'Huarocondo',70),(705,'Limatambo',70),(706,'Mollepata',70),(707,'Pucyura',70),(708,'Zurite',70),(709,'Calca',71),(710,'Coya',71),(711,'Lamay',71),(712,'Lares',71),(713,'Pisac',71),(714,'San Salvador',71),(715,'Taray',71),(716,'Yanatile',71),(717,'Yanaoca',72),(718,'Checca',72),(719,'Kunturkanki',72),(720,'Langui',72),(721,'Layo',72),(722,'Pampamarca',72),(723,'Quehue',72),(724,'Tupac Amaru',72),(725,'Sicuani',73),(726,'Checacupe',73),(727,'Combapata',73),(728,'Marangani',73),(729,'Pitumarca',73),(730,'San Pablo',73),(731,'San Pedro',73),(732,'Tinta',73),(733,'Santo Tomas',74),(734,'Capacmarca',74),(735,'Chamaca',74),(736,'Colquemarca',74),(737,'Livitaca',74),(738,'Llusco',74),(739,'Quiñota',74),(740,'Velille',74),(741,'Espinar',75),(742,'Condoroma',75),(743,'Coporaque',75),(744,'Ocoruro',75),(745,'Pallpata',75),(746,'Pichigua',75),(747,'Suyckutambo',75),(748,'Alto Pichigua',75),(749,'Santa Ana',76),(750,'Echarate',76),(751,'Huayopata',76),(752,'Maranura',76),(753,'Ocobamba',76),(754,'Quellouno',76),(755,'Kimbiri',76),(756,'Santa Teresa',76),(757,'Vilcabamba',76),(758,'Pichari',76),(759,'Paruro',77),(760,'Accha',77),(761,'Ccapi',77),(762,'Colcha',77),(763,'Huanoquite',77),(764,'Omacha',77),(765,'Paccaritambo',77),(766,'Pillpinto',77),(767,'Yaurisque',77),(768,'Paucartambo',78),(769,'Caicay',78),(770,'Challabamba',78),(771,'Colquepata',78),(772,'Huancarani',78),(773,'Kosñipata',78),(774,'Urcos',79),(775,'Andahuaylillas',79),(776,'Camanti',79),(777,'Ccarhuayo',79),(778,'Ccatca',79),(779,'Cusipata',79),(780,'Huaro',79),(781,'Lucre',79),(782,'Marcapata',79),(783,'Ocongate',79),(784,'Oropesa',79),(785,'Quiquijana',79),(786,'Urubamba',80),(787,'Chinchero',80),(788,'Huayllabamba',80),(789,'Machupicchu',80),(790,'Maras',80),(791,'Ollantaytambo',80),(792,'Yucay',80),(793,'Huancavelica',81),(794,'Acobambilla',81),(795,'Acoria',81),(796,'Conayca',81),(797,'Cuenca',81),(798,'Huachocolpa',81),(799,'Huayllahuara',81),(800,'Izcuchaca',81),(801,'Laria',81),(802,'Manta',81),(803,'Mariscal Caceres',81),(804,'Moya',81),(805,'Nuevo Occoro',81),(806,'Palca',81),(807,'Pilchaca',81),(808,'Vilca',81),(809,'Yauli',81),(810,'Ascension',81),(811,'Huando',81),(812,'Acobamba',82),(813,'Andabamba',82),(814,'Anta',82),(815,'Caja',82),(816,'Marcas',82),(817,'Paucara',82),(818,'Pomacocha',82),(819,'Rosario',82),(820,'Lircay',83),(821,'Anchonga',83),(822,'Callanmarca',83),(823,'Ccochaccasa',83),(824,'Chincho',83),(825,'Congalla',83),(826,'Huanca-Huanca',83),(827,'Huayllay Grande',83),(828,'Julcamarca',83),(829,'San Antonio de Antaparco',83),(830,'Santo Tomas de Pata',83),(831,'Secclla',83),(832,'Castrovirreyna',84),(833,'Arma',84),(834,'Aurahua',84),(835,'Capillas',84),(836,'Chupamarca',84),(837,'Cocas',84),(838,'Huachos',84),(839,'Huamatambo',84),(840,'Mollepampa',84),(841,'San Juan',84),(842,'Santa Ana',84),(843,'Tantara',84),(844,'Ticrapo',84),(845,'Churcampa',85),(846,'Anco',85),(847,'Chinchihuasi',85),(848,'El Carmen',85),(849,'La Merced',85),(850,'Locroja',85),(851,'Paucarbamba',85),(852,'San Miguel de Mayocc',85),(853,'San Pedro de Coris',85),(854,'Pachamarca',85),(855,'Cosme',85),(856,'Huaytara',86),(857,'Ayavi',86),(858,'Cordova',86),(859,'Huayacundo Arma',86),(860,'Laramarca',86),(861,'Ocoyo',86),(862,'Pilpichaca',86),(863,'Querco',86),(864,'Quito-Arma',86),(865,'San Antonio de Cusicancha',86),(866,'San Francisco de Sangayaico',86),(867,'San Isidro',86),(868,'Santiago de Chocorvos',86),(869,'Santiago de Quirahuara',86),(870,'Santo Domingo de Capillas',86),(871,'Tambo',86),(872,'Pampas',87),(873,'Acostambo',87),(874,'Acraquia',87),(875,'Ahuaycha',87),(876,'Colcabamba',87),(877,'Daniel Hernandez',87),(878,'Huachocolpa',87),(879,'Huaribamba',87),(880,'Ñahuimpuquio',87),(881,'Pazos',87),(882,'Quishuar',87),(883,'Salcabamba',87),(884,'Salcahuasi',87),(885,'San Marcos de Rocchac',87),(886,'Surcubamba',87),(887,'Tintay Puncu',87),(888,'Huanuco',88),(889,'Amarilis',88),(890,'Chinchao',88),(891,'Churubamba',88),(892,'Margos',88),(893,'Quisqui',88),(894,'San Francisco de Cayran',88),(895,'San Pedro de Chaulan',88),(896,'Santa Maria del Valle',88),(897,'Yarumayo',88),(898,'Pillco Marca',88),(899,'Yacus',88),(900,'Ambo',89),(901,'Cayna',89),(902,'Colpas',89),(903,'Conchamarca',89),(904,'Huacar',89),(905,'San Francisco',89),(906,'San Rafael',89),(907,'Tomay Kichwa',89),(908,'La Union',90),(909,'Chuquis',90),(910,'Marias',90),(911,'Pachas',90),(912,'Quivilla',90),(913,'Ripan',90),(914,'Shunqui',90),(915,'Sillapata',90),(916,'Yanas',90),(917,'Huacaybamba',91),(918,'Canchabamba',91),(919,'Cochabamba',91),(920,'Pinra',91),(921,'Llata',92),(922,'Arancay',92),(923,'Chavin de Pariarca',92),(924,'Jacas Grande',92),(925,'Jircan',92),(926,'Miraflores',92),(927,'Monzon',92),(928,'Punchao',92),(929,'Puños',92),(930,'Singa',92),(931,'Tantamayo',92),(932,'Rupa-Rupa',93),(933,'Daniel Alomias Robles',93),(934,'Hermilio Valdizan',93),(935,'Jose Crespo y Castillo',93),(936,'Luyando',93),(937,'Mariano Damaso Beraun',93),(938,'Huacrachuco',94),(939,'Cholon',94),(940,'San Buenaventura',94),(941,'Panao',95),(942,'Chaglla',95),(943,'Molino',95),(944,'Umari',95),(945,'Puerto Inca',96),(946,'Codo del Pozuzo',96),(947,'Honoria',96),(948,'Tournavista',96),(949,'Yuyapichis',96),(950,'Jesus',97),(951,'Baños',97),(952,'Jivia',97),(953,'Queropalca',97),(954,'Rondos',97),(955,'San Francisco de Asis',97),(956,'San Miguel de Cauri',97),(957,'Chavinillo',98),(958,'Cahuac',98),(959,'Chacabamba',98),(960,'Aparicio Pomares',98),(961,'Jacas Chico',98),(962,'Obas',98),(963,'Pampamarca',98),(964,'Choras',98),(965,'Ica',99),(966,'La Tinguiña',99),(967,'Los Aquijes',99),(968,'Ocucaje',99),(969,'Pachacutec',99),(970,'Parcona',99),(971,'Pueblo Nuevo',99),(972,'Salas',99),(973,'San Jose de los Molinos',99),(974,'San Juan Bautista',99),(975,'Santiago',99),(976,'Subtanjalla',99),(977,'Tate',99),(978,'Yauca del Rosario',99),(979,'Chincha Alta',100),(980,'Alto Laran',100),(981,'Chavin',100),(982,'Chincha Baja',100),(983,'El Carmen',100),(984,'Grocio Prado',100),(985,'Pueblo Nuevo',100),(986,'San Juan de Yanac',100),(987,'San Pedro de Huacarpana',100),(988,'Sunampe',100),(989,'Tambo de Mora',100),(990,'Nazca',101),(991,'Changuillo',101),(992,'El Ingenio',101),(993,'Marcona',101),(994,'Vista Alegre',101),(995,'Palpa',102),(996,'Llipata',102),(997,'Rio Grande',102),(998,'Santa Cruz',102),(999,'Tibillo',102),(1000,'Pisco',103),(1001,'Huancano',103),(1002,'Humay',103),(1003,'Independencia',103),(1004,'Paracas',103),(1005,'San Andres',103),(1006,'San Clemente',103),(1007,'Tupac Amaru Inca',103),(1008,'Huancayo',104),(1009,'Carhuacallanga',104),(1010,'Chacapampa',104),(1011,'Chicche',104),(1012,'Chilca',104),(1013,'Chongos Alto',104),(1014,'Chupuro',104),(1015,'Colca',104),(1016,'Cullhuas',104),(1017,'El Tambo',104),(1018,'Huacrapuquio',104),(1019,'Hualhuas',104),(1020,'Huancan',104),(1021,'Huasicancha',104),(1022,'Huayucachi',104),(1023,'Ingenio',104),(1024,'Pariahuanca',104),(1025,'Pilcomayo',104),(1026,'Pucara',104),(1027,'Quichuay',104),(1028,'Quilcas',104),(1029,'San Agustin',104),(1030,'San Jeronimo de Tunan',104),(1031,'Saño',104),(1032,'Sapallanga',104),(1033,'Sicaya',104),(1034,'Santo Domingo de Acobamba',104),(1035,'Viques',104),(1036,'Concepcion',105),(1037,'Aco',105),(1038,'Andamarca',105),(1039,'Chambara',105),(1040,'Cochas',105),(1041,'Comas',105),(1042,'Heroinas Toledo',105),(1043,'Manzanares',105),(1044,'Mariscal Castilla',105),(1045,'Matahuasi',105),(1046,'Mito',105),(1047,'Nueve de Julio',105),(1048,'Orcotuna',105),(1049,'San Jose de Quero',105),(1050,'Santa Rosa de Ocopa',105),(1051,'Chanchamayo',106),(1052,'Perene',106),(1053,'Pichanaqui',106),(1054,'San Luis de Shuaro',106),(1055,'San Ramon',106),(1056,'Vitoc',106),(1057,'Jauja',107),(1058,'Acolla',107),(1059,'Apata',107),(1060,'Ataura',107),(1061,'Canchayllo',107),(1062,'Curicaca',107),(1063,'El Mantaro',107),(1064,'Huamali',107),(1065,'Huaripampa',107),(1066,'Huertas',107),(1067,'Janjaillo',107),(1068,'Julcan',107),(1069,'Leonor Ordoñez',107),(1070,'Llocllapampa',107),(1071,'Marco',107),(1072,'Masma',107),(1073,'Masma Chicche',107),(1074,'Molinos',107),(1075,'Monobamba',107),(1076,'Muqui',107),(1077,'Muquiyauyo',107),(1078,'Paca',107),(1079,'Paccha',107),(1080,'Pancan',107),(1081,'Parco',107),(1082,'Pomacancha',107),(1083,'Ricran',107),(1084,'San Lorenzo',107),(1085,'San Pedro de Chunan',107),(1086,'Sausa',107),(1087,'Sincos',107),(1088,'Tunan Marca',107),(1089,'Yauli',107),(1090,'Yauyos',107),(1091,'Junin',108),(1092,'Carhuamayo',108),(1093,'Ondores',108),(1094,'Ulcumayo',108),(1095,'Satipo',109),(1096,'Coviriali',109),(1097,'Llaylla',109),(1098,'Mazamari',109),(1099,'Pampa Hermosa',109),(1100,'Pangoa',109),(1101,'Rio Negro',109),(1102,'Rio Tambo',109),(1103,'Tarma',110),(1104,'Acobamba',110),(1105,'Huaricolca',110),(1106,'Huasahuasi',110),(1107,'La Union',110),(1108,'Palca',110),(1109,'Palcamayo',110),(1110,'San Pedro de Cajas',110),(1111,'Tapo',110),(1112,'La Oroya',111),(1113,'Chacapalpa',111),(1114,'Huay-Huay',111),(1115,'Marcapomacocha',111),(1116,'Morococha',111),(1117,'Paccha',111),(1118,'Santa Barbara de Carhuacayan',111),(1119,'Santa Rosa de Sacco',111),(1120,'Suitucancha',111),(1121,'Yauli',111),(1122,'Chupaca',112),(1123,'Ahuac',112),(1124,'Chongos Bajo',112),(1125,'Huachac',112),(1126,'Huamancaca Chico',112),(1127,'San Juan de Yscos',112),(1128,'San Juan de Jarpa',112),(1129,'Tres de Diciembre',112),(1130,'Yanacancha',112),(1131,'Trujillo',113),(1132,'El Porvenir',113),(1133,'Florencia de Mora',113),(1134,'Huanchaco',113),(1135,'La Esperanza',113),(1136,'Laredo',113),(1137,'Moche',113),(1138,'Poroto',113),(1139,'Salaverry',113),(1140,'Simbal',113),(1141,'Victor Larco Herrera',113),(1142,'Ascope',114),(1143,'Chicama',114),(1144,'Chocope',114),(1145,'Magdalena de Cao',114),(1146,'Paijan',114),(1147,'Razuri',114),(1148,'Santiago de Cao',114),(1149,'Casa Grande',114),(1150,'Bolivar',115),(1151,'Bambamarca',115),(1152,'Condormarca',115),(1153,'Longotea',115),(1154,'Uchumarca',115),(1155,'Ucuncha',115),(1156,'Chepen',116),(1157,'Pacanga',116),(1158,'Pueblo Nuevo',116),(1159,'Julcan',117),(1160,'Calamarca',117),(1161,'Carabamba',117),(1162,'Huaso',117),(1163,'Otuzco',118),(1164,'Agallpampa',118),(1165,'Charat',118),(1166,'Huaranchal',118),(1167,'La Cuesta',118),(1168,'Mache',118),(1169,'Paranday',118),(1170,'Salpo',118),(1171,'Sinsicap',118),(1172,'Usquil',118),(1173,'San Pedro de Lloc',119),(1174,'Guadalupe',119),(1175,'Jequetepeque',119),(1176,'Pacasmayo',119),(1177,'San Jose',119),(1178,'Tayabamba',120),(1179,'Buldibuyo',120),(1180,'Chillia',120),(1181,'Huancaspata',120),(1182,'Huaylillas',120),(1183,'Huayo',120),(1184,'Ongon',120),(1185,'Parcoy',120),(1186,'Pataz',120),(1187,'Pias',120),(1188,'Santiago de Challas',120),(1189,'Taurija',120),(1190,'Urpay',120),(1191,'Huamachuco',121),(1192,'Chugay',121),(1193,'Cochorco',121),(1194,'Curgos',121),(1195,'Marcabal',121),(1196,'Sanagoran',121),(1197,'Sarin',121),(1198,'Sartimbamba',121),(1199,'Santiago de Chuco',122),(1200,'Angasmarca',122),(1201,'Cachicadan',122),(1202,'Mollebamba',122),(1203,'Mollepata',122),(1204,'Quiruvilca',122),(1205,'Santa Cruz de Chuca',122),(1206,'Sitabamba',122),(1207,'Cascas',123),(1208,'Lucma',123),(1209,'Marmot',123),(1210,'Sayapullo',123),(1211,'Viru',124),(1212,'Chao',124),(1213,'Guadalupito',124),(1214,'Chiclayo',125),(1215,'Chongoyape',125),(1216,'Eten',125),(1217,'Eten Puerto',125),(1218,'Jose Leonardo Ortiz',125),(1219,'La Victoria',125),(1220,'Lagunas',125),(1221,'Monsefu',125),(1222,'Nueva Arica',125),(1223,'Oyotun',125),(1224,'Picsi',125),(1225,'Pimentel',125),(1226,'Reque',125),(1227,'Santa Rosa',125),(1228,'Saña',125),(1229,'Cayalti',125),(1230,'Patapo',125),(1231,'Pomalca',125),(1232,'Pucala',125),(1233,'Tuman',125),(1234,'Ferreñafe',126),(1235,'Cañaris',126),(1236,'Incahuasi',126),(1237,'Manuel Antonio Mesones Muro',126),(1238,'Pitipo',126),(1239,'Pueblo Nuevo',126),(1240,'Lambayeque',127),(1241,'Chochope',127),(1242,'Illimo',127),(1243,'Jayanca',127),(1244,'Mochumi',127),(1245,'Morrope',127),(1246,'Motupe',127),(1247,'Olmos',127),(1248,'Pacora',127),(1249,'Salas',127),(1250,'San Jose',127),(1251,'Tucume',127),(1252,'Lima',128),(1253,'Ancon',128),(1254,'Ate',128),(1255,'Barranco',128),(1256,'Breña',128),(1257,'Carabayllo',128),(1258,'Chaclacayo',128),(1259,'Chorrillos',128),(1260,'Cieneguilla',128),(1261,'Comas',128),(1262,'El Agustino',128),(1263,'Independencia',128),(1264,'Jesus Maria',128),(1265,'La Molina',128),(1266,'La Victoria',128),(1267,'Lince',128),(1268,'Los Olivos',128),(1269,'Lurigancho',128),(1270,'Lurin',128),(1271,'Magdalena del Mar',128),(1272,'Pueblo Libre',128),(1273,'Miraflores',128),(1274,'Pachacamac',128),(1275,'Pucusana',128),(1276,'Puente Piedra',128),(1277,'Punta Hermosa',128),(1278,'Punta Negra',128),(1279,'Rimac',128),(1280,'San Bartolo',128),(1281,'San Borja',128),(1282,'San Isidro',128),(1283,'San Juan de Lurigancho',128),(1284,'San Juan de Miraflores',128),(1285,'San Luis',128),(1286,'San Martin de Porres',128),(1287,'San Miguel',128),(1288,'Santa Anita',128),(1289,'Santa Maria del Mar',128),(1290,'Santa Rosa',128),(1291,'Santiago de Surco',128),(1292,'Surquillo',128),(1293,'Villa El Salvador',128),(1294,'Villa Maria del Triunfo',128),(1295,'Barranca',129),(1296,'Paramonga',129),(1297,'Pativilca',129),(1298,'Supe',129),(1299,'Supe Puerto',129),(1300,'Cajatambo',130),(1301,'Copa',130),(1302,'Gorgor',130),(1303,'Huancapon',130),(1304,'Manas',130),(1305,'Canta',131),(1306,'Arahuay',131),(1307,'Huamantanga',131),(1308,'Huaros',131),(1309,'Lachaqui',131),(1310,'San Buenaventura',131),(1311,'Santa Rosa de Quives',131),(1312,'San Vicente de Cañete',132),(1313,'Asia',132),(1314,'Calango',132),(1315,'Cerro Azul',132),(1316,'Chilca',132),(1317,'Coayllo',132),(1318,'Imperial',132),(1319,'Lunahuana',132),(1320,'Mala',132),(1321,'Nuevo Imperial',132),(1322,'Pacaran',132),(1323,'Quilmana',132),(1324,'San Antonio',132),(1325,'San Luis',132),(1326,'Santa Cruz de Flores',132),(1327,'Zuñiga',132),(1328,'Huaral',133),(1329,'Atavillos Alto',133),(1330,'Atavillos Bajo',133),(1331,'Aucallama',133),(1332,'Chancay',133),(1333,'Ihuari',133),(1334,'Lampian',133),(1335,'Pacaraos',133),(1336,'San Miguel de Acos',133),(1337,'Santa Cruz de Andamarca',133),(1338,'Sumbilca',133),(1339,'Veintisiete de Noviembre',133),(1340,'Matucana',134),(1341,'Antioquia',134),(1342,'Callahuanca',134),(1343,'Carampoma',134),(1344,'Chicla',134),(1345,'Cuenca',134),(1346,'Huachupampa',134),(1347,'Huanza',134),(1348,'Huarochiri',134),(1349,'Lahuaytambo',134),(1350,'Langa',134),(1351,'Laraos',134),(1352,'Mariatana',134),(1353,'Ricardo Palma',134),(1354,'San Andres de Tupicocha',134),(1355,'San Antonio',134),(1356,'San Bartolome',134),(1357,'San Damian',134),(1358,'San Juan de Iris',134),(1359,'San Juan de Tantaranche',134),(1360,'San Lorenzo de Quinti',134),(1361,'San Mateo',134),(1362,'San Mateo de Otao',134),(1363,'San Pedro de Casta',134),(1364,'San Pedro de Huancayre',134),(1365,'Sangallaya',134),(1366,'Santa Cruz de Cocachacra',134),(1367,'Santa Eulalia',134),(1368,'Santiago de Anchucaya',134),(1369,'Santiago de Tuna',134),(1370,'Santo Domingo de los Olleros',134),(1371,'Surco',134),(1372,'Huacho',135),(1373,'Ambar',135),(1374,'Caleta de Carquin',135),(1375,'Checras',135),(1376,'Hualmay',135),(1377,'Huaura',135),(1378,'Leoncio Prado',135),(1379,'Paccho',135),(1380,'Santa Leonor',135),(1381,'Santa Maria',135),(1382,'Sayan',135),(1383,'Vegueta',135),(1384,'Oyon',136),(1385,'Andajes',136),(1386,'Caujul',136),(1387,'Cochamarca',136),(1388,'Navan',136),(1389,'Pachangara',136),(1390,'Yauyos',137),(1391,'Alis',137),(1392,'Ayauca',137),(1393,'Ayaviri',137),(1394,'Azangaro',137),(1395,'Cacra',137),(1396,'Carania',137),(1397,'Catahuasi',137),(1398,'Chocos',137),(1399,'Cochas',137),(1400,'Colonia',137),(1401,'Hongos',137),(1402,'Huampara',137),(1403,'Huancaya',137),(1404,'Huangascar',137),(1405,'Huantan',137),(1406,'Huañec',137),(1407,'Laraos',137),(1408,'Lincha',137),(1409,'Madean',137),(1410,'Miraflores',137),(1411,'Omas',137),(1412,'Putinza',137),(1413,'Quinches',137),(1414,'Quinocay',137),(1415,'San Joaquin',137),(1416,'San Pedro de Pilas',137),(1417,'Tanta',137),(1418,'Tauripampa',137),(1419,'Tomas',137),(1420,'Tupe',137),(1421,'Viñac',137),(1422,'Vitis',137),(1423,'Iquitos',138),(1424,'Alto Nanay',138),(1425,'Fernando Lores',138),(1426,'Indiana',138),(1427,'Las Amazonas',138),(1428,'Mazan',138),(1429,'Napo',138),(1430,'Punchana',138),(1431,'Torres Causana',138),(1432,'Belen',138),(1433,'San Juan Bautista',138),(1434,'Yurimaguas',139),(1435,'Balsapuerto',139),(1436,'Jeberos',139),(1437,'Lagunas',139),(1438,'Santa Cruz',139),(1439,'Teniente Cesar Lopez Rojas',139),(1440,'Nauta',140),(1441,'Parinari',140),(1442,'Tigre',140),(1443,'Trompeteros',140),(1444,'Urarinas',140),(1445,'Ramon Castilla',141),(1446,'Pebas',141),(1447,'Yavari',141),(1448,'San Pablo',141),(1449,'Requena',142),(1450,'Alto Tapiche',142),(1451,'Capelo',142),(1452,'Emilio San Martin',142),(1453,'Maquia',142),(1454,'Puinahua',142),(1455,'Saquena',142),(1456,'Soplin',142),(1457,'Tapiche',142),(1458,'Jenaro Herrera',142),(1459,'Yaquerana',142),(1460,'Contamana',143),(1461,'Inahuaya',143),(1462,'Padre Marquez',143),(1463,'Pampa Hermosa',143),(1464,'Sarayacu',143),(1465,'Vargas Guerra',143),(1466,'Barranca',144),(1467,'Cahuapanas',144),(1468,'Manseriche',144),(1469,'Morona',144),(1470,'Pastaza',144),(1471,'Andoas',144),(1472,'Putumayo',138),(1473,'Teniente Manuel Clavero',138),(1474,'Tambopata',146),(1475,'Inambari',146),(1476,'Las Piedras',146),(1477,'Laberinto',146),(1478,'Manu',147),(1479,'Fitzcarrald',147),(1480,'Madre de Dios',147),(1481,'Huepetuhe',147),(1482,'Iñapari',148),(1483,'Iberia',148),(1484,'Tahuamanu',148),(1485,'Moquegua',149),(1486,'Carumas',149),(1487,'Cuchumbaya',149),(1488,'Samegua',149),(1489,'San Cristobal',149),(1490,'Torata',149),(1491,'Omate',150),(1492,'Chojata',150),(1493,'Coalaque',150),(1494,'Ichuña',150),(1495,'La Capilla',150),(1496,'Lloque',150),(1497,'Matalaque',150),(1498,'Puquina',150),(1499,'Quinistaquillas',150),(1500,'Ubinas',150),(1501,'Yunga',150),(1502,'Ilo',151),(1503,'El Algarrobal',151),(1504,'Pacocha',151),(1505,'Chaupimarca',152),(1506,'Huachon',152),(1507,'Huariaca',152),(1508,'Huayllay',152),(1509,'Ninacaca',152),(1510,'Pallanchacra',152),(1511,'Paucartambo',152),(1512,'San Francisco de Asis de Yarusyacan',152),(1513,'Simon Bolivar',152),(1514,'Ticlacayan',152),(1515,'Tinyahuarco',152),(1516,'Vicco',152),(1517,'Yanacancha',152),(1518,'Yanahuanca',153),(1519,'Chacayan',153),(1520,'Goyllarisquizga',153),(1521,'Paucar',153),(1522,'San Pedro de Pillao',153),(1523,'Santa Ana de Tusi',153),(1524,'Tapuc',153),(1525,'Vilcabamba',153),(1526,'Oxapampa',154),(1527,'Chontabamba',154),(1528,'Huancabamba',154),(1529,'Palcazu',154),(1530,'Pozuzo',154),(1531,'Puerto Bermudez',154),(1532,'Villa Rica',154),(1533,'Constitucion',154),(1534,'Piura',155),(1535,'Castilla',155),(1536,'Catacaos',155),(1537,'Cura Mori',155),(1538,'El Tallan',155),(1539,'La Arena',155),(1540,'La Union',155),(1541,'Las Lomas',155),(1542,'Tambo Grande',155),(1543,'Ayabaca',156),(1544,'Frias',156),(1545,'Jilili',156),(1546,'Lagunas',156),(1547,'Montero',156),(1548,'Pacaipampa',156),(1549,'Paimas',156),(1550,'Sapillica',156),(1551,'Sicchez',156),(1552,'Suyo',156),(1553,'Huancabamba',157),(1554,'Canchaque',157),(1555,'El Carmen de La Frontera',157),(1556,'Huarmaca',157),(1557,'Lalaquiz',157),(1558,'San Miguel de El Faique',157),(1559,'Sondor',157),(1560,'Sondorillo',157),(1561,'Chulucanas',158),(1562,'Buenos Aires',158),(1563,'Chalaco',158),(1564,'La Matanza',158),(1565,'Morropon',158),(1566,'Salitral',158),(1567,'San Juan de Bigote',158),(1568,'Santa Catalina de Mossa',158),(1569,'Santo Domingo',158),(1570,'Yamango',158),(1571,'Paita',159),(1572,'Amotape',159),(1573,'Arenal',159),(1574,'Colan',159),(1575,'La Huaca',159),(1576,'Tamarindo',159),(1577,'Vichayal',159),(1578,'Sullana',160),(1579,'Bellavista',160),(1580,'Ignacio Escudero',160),(1581,'Lancones',160),(1582,'Marcavelica',160),(1583,'Miguel Checa',160),(1584,'Querecotillo',160),(1585,'Salitral',160),(1586,'Pariñas',161),(1587,'El Alto',161),(1588,'La Brea',161),(1589,'Lobitos',161),(1590,'Los Organos',161),(1591,'Mancora',161),(1592,'Sechura',162),(1593,'Bellavista de La Union',162),(1594,'Bernal',162),(1595,'Cristo Nos Valga',162),(1596,'Vice',162),(1597,'Rinconada Llicuar',162),(1598,'Puno',163),(1599,'Acora',163),(1600,'Amantani',163),(1601,'Atuncolla',163),(1602,'Capachica',163),(1603,'Chucuito',163),(1604,'Coata',163),(1605,'Huata',163),(1606,'Mañazo',163),(1607,'Paucarcolla',163),(1608,'Pichacani',163),(1609,'Plateria',163),(1610,'San Antonio',163),(1611,'Tiquillaca',163),(1612,'Vilque',163),(1613,'Azangaro',164),(1614,'Achaya',164),(1615,'Arapa',164),(1616,'Asillo',164),(1617,'Caminaca',164),(1618,'Chupa',164),(1619,'Jose Domingo Choquehuanca',164),(1620,'Muñani',164),(1621,'Potoni',164),(1622,'Saman',164),(1623,'San Anton',164),(1624,'San Jose',164),(1625,'San Juan de Salinas',164),(1626,'Santiago de Pupuja',164),(1627,'Tirapata',164),(1628,'Macusani',165),(1629,'Ajoyani',165),(1630,'Ayapata',165),(1631,'Coasa',165),(1632,'Corani',165),(1633,'Crucero',165),(1634,'Ituata',165),(1635,'Ollachea',165),(1636,'San Gaban',165),(1637,'Usicayos',165),(1638,'Juli',166),(1639,'Desaguadero',166),(1640,'Huacullani',166),(1641,'Kelluyo',166),(1642,'Pisacoma',166),(1643,'Pomata',166),(1644,'Zepita',166),(1645,'Ilave',167),(1646,'Capazo',167),(1647,'Pilcuyo',167),(1648,'Santa Rosa',167),(1649,'Conduriri',167),(1650,'Huancane',168),(1651,'Cojata',168),(1652,'Huatasani',168),(1653,'Inchupalla',168),(1654,'Pusi',168),(1655,'Rosaspata',168),(1656,'Taraco',168),(1657,'Vilque Chico',168),(1658,'Lampa',169),(1659,'Cabanilla',169),(1660,'Calapuja',169),(1661,'Nicasio',169),(1662,'Ocuviri',169),(1663,'Palca',169),(1664,'Paratia',169),(1665,'Pucara',169),(1666,'Santa Lucia',169),(1667,'Vilavila',169),(1668,'Ayaviri',170),(1669,'Antauta',170),(1670,'Cupi',170),(1671,'Llalli',170),(1672,'Macari',170),(1673,'Nuñoa',170),(1674,'Orurillo',170),(1675,'Santa Rosa',170),(1676,'Umachiri',170),(1677,'Moho',171),(1678,'Conima',171),(1679,'Huayrapata',171),(1680,'Tilali',171),(1681,'Putina',172),(1682,'Ananea',172),(1683,'Pedro Vilca Apaza',172),(1684,'Quilcapuncu',172),(1685,'Sina',172),(1686,'Juliaca',173),(1687,'Cabana',173),(1688,'Cabanillas',173),(1689,'Caracoto',173),(1690,'Sandia',174),(1691,'Cuyocuyo',174),(1692,'Limbani',174),(1693,'Patambuco',174),(1694,'Phara',174),(1695,'Quiaca',174),(1696,'San Juan del Oro',174),(1697,'Yanahuaya',174),(1698,'Alto Inambari',174),(1699,'San Pedro de Putina Punco',174),(1700,'Yunguyo',175),(1701,'Anapia',175),(1702,'Copani',175),(1703,'Cuturapi',175),(1704,'Ollaraya',175),(1705,'Tinicachi',175),(1706,'Unicachi',175),(1707,'Moyobamba',176),(1708,'Calzada',176),(1709,'Habana',176),(1710,'Jepelacio',176),(1711,'Soritor',176),(1712,'Yantalo',176),(1713,'Bellavista',177),(1714,'Alto Biavo',177),(1715,'Bajo Biavo',177),(1716,'Huallaga',177),(1717,'San Pablo',177),(1718,'San Rafael',177),(1719,'San Jose de Sisa',178),(1720,'Agua Blanca',178),(1721,'San Martin',178),(1722,'Santa Rosa',178),(1723,'Shatoja',178),(1724,'Saposoa',179),(1725,'Alto Saposoa',179),(1726,'El Eslabon',179),(1727,'Piscoyacu',179),(1728,'Sacanche',179),(1729,'Tingo de Saposoa',179),(1730,'Lamas',180),(1731,'Alonso de Alvarado',180),(1732,'Barranquita',180),(1733,'Caynarachi',180),(1734,'Cuñumbuqui',180),(1735,'Pinto Recodo',180),(1736,'Rumisapa',180),(1737,'San Roque de Cumbaza',180),(1738,'Shanao',180),(1739,'Tabalosos',180),(1740,'Zapatero',180),(1741,'Juanjui',181),(1742,'Campanilla',181),(1743,'Huicungo',181),(1744,'Pachiza',181),(1745,'Pajarillo',181),(1746,'Picota',182),(1747,'Buenos Aires',182),(1748,'Caspisapa',182),(1749,'Pilluana',182),(1750,'Pucacaca',182),(1751,'San Cristobal',182),(1752,'San Hilarion',182),(1753,'Shamboyacu',182),(1754,'Tingo de Ponasa',182),(1755,'Tres Unidos',182),(1756,'Rioja',183),(1757,'Awajun',183),(1758,'Elias Soplin Vargas',183),(1759,'Nueva Cajamarca',183),(1760,'Pardo Miguel',183),(1761,'Posic',183),(1762,'San Fernando',183),(1763,'Yorongos',183),(1764,'Yuracyacu',183),(1765,'Tarapoto',184),(1766,'Alberto Leveau',184),(1767,'Cacatachi',184),(1768,'Chazuta',184),(1769,'Chipurana',184),(1770,'El Porvenir',184),(1771,'Huimbayoc',184),(1772,'Juan Guerra',184),(1773,'La Banda de Shilcayo',184),(1774,'Morales',184),(1775,'Papaplaya',184),(1776,'San Antonio',184),(1777,'Sauce',184),(1778,'Shapaja',184),(1779,'Tocache',185),(1780,'Nuevo Progreso',185),(1781,'Polvora',185),(1782,'Shunte',185),(1783,'Uchiza',185),(1784,'Tacna',186),(1785,'Alto de La Alianza',186),(1786,'Calana',186),(1787,'Ciudad Nueva',186),(1788,'Inclan',186),(1789,'Pachia',186),(1790,'Palca',186),(1791,'Pocollay',186),(1792,'Sama',186),(1793,'Coronel Gregorio Albarracin Lanchipa',186),(1794,'Candarave',187),(1795,'Cairani',187),(1796,'Camilaca',187),(1797,'Curibaya',187),(1798,'Huanuara',187),(1799,'Quilahuani',187),(1800,'Locumba',188),(1801,'Ilabaya',188),(1802,'Ite',188),(1803,'Tarata',189),(1804,'Heroes Albarracin',189),(1805,'Estique',189),(1806,'Estique-Pampa',189),(1807,'Sitajara',189),(1808,'Susapaya',189),(1809,'Tarucachi',189),(1810,'Ticaco',189),(1811,'Tumbes',190),(1812,'Corrales',190),(1813,'La Cruz',190),(1814,'Pampas de Hospital',190),(1815,'San Jacinto',190),(1816,'San Juan de La Virgen',190),(1817,'Zorritos',191),(1818,'Casitas',191),(1819,'Canoas de Punta Sal',191),(1820,'Zarumilla',192),(1821,'Aguas Verdes',192),(1822,'Matapalo',192),(1823,'Papayal',192),(1824,'Calleria',193),(1825,'Campoverde',193),(1826,'Iparia',193),(1827,'Masisea',193),(1828,'Yarinacocha',193),(1829,'Nueva Requena',193),(1830,'Manantay',193),(1831,'Raymondi',194),(1832,'Sepahua',194),(1833,'Tahuania',194),(1834,'Yurua',194),(1835,'Padre Abad',195),(1836,'Irazola',195),(1837,'Curimana',195),(1838,'Purus',196);
/*!40000 ALTER TABLE `distrito` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `persona_juridica`
--

DROP TABLE IF EXISTS `persona_juridica`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `persona_juridica` (
  `ruc` char(11) NOT NULL,
  `razon_social` varchar(300) DEFAULT NULL,
  `domicilio_fiscal` varchar(500) DEFAULT NULL,
  `nombre_representante_legal` varchar(300) DEFAULT NULL,
  `celular` varchar(15) DEFAULT NULL,
  `pais` varchar(300) DEFAULT NULL,
  `departamento` int(11) DEFAULT NULL,
  `provincia` int(11) DEFAULT NULL,
  `distrito` int(11) DEFAULT NULL,
  `id_usuario` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`ruc`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `persona_juridica`
--

LOCK TABLES `persona_juridica` WRITE;
/*!40000 ALTER TABLE `persona_juridica` DISABLE KEYS */;
INSERT INTO `persona_juridica` (`ruc`, `razon_social`, `domicilio_fiscal`, `nombre_representante_legal`, `celular`, `pais`, `departamento`, `provincia`, `distrito`, `id_usuario`) VALUES ('10714153167','Renzo Alejandro Alcántara Lizares','987654321','Renzo Alejandro Alcántara Lizares','',NULL,1,1,1,16);
/*!40000 ALTER TABLE `persona_juridica` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `persona_natural`
--

DROP TABLE IF EXISTS `persona_natural`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `persona_natural` (
  `dni` char(8) NOT NULL,
  `nombres` varchar(300) DEFAULT NULL,
  `apellidos` varchar(300) DEFAULT NULL,
  `direccion` varchar(500) DEFAULT NULL,
  `celular` varchar(15) DEFAULT NULL,
  `pais` varchar(300) DEFAULT NULL,
  `departamento` int(11) DEFAULT NULL,
  `provincia` int(11) DEFAULT NULL,
  `distrito` int(11) DEFAULT NULL,
  `id_usuario` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`dni`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `persona_natural`
--

LOCK TABLES `persona_natural` WRITE;
/*!40000 ALTER TABLE `persona_natural` DISABLE KEYS */;
INSERT INTO `persona_natural` (`dni`, `nombres`, `apellidos`, `direccion`, `celular`, `pais`, `departamento`, `provincia`, `distrito`, `id_usuario`) VALUES ('','','','Maynas 265, Chiclayo 14009, Perú','',NULL,NULL,NULL,NULL,1),('16682287','Zoila ','Lizares Arce','Juan Buendía 280, Chiclayo 14009, Perú','947595204',NULL,NULL,NULL,NULL,29),('71415316','','','Cruce de Calle Loreto con Calle, Juan Buendía, Chiclayo 14009, Perú','',NULL,NULL,NULL,NULL,NULL),('71415319','Renzo Alejandro','Alcantara Lizares','Urb.Patazca - calle BuenDia 280','931884330',NULL,1,NULL,NULL,20),('72608801','Bryant Alejandro','Yacila Valenzuela','Cristóbal Colón 139, Chiclayo 14001, Perú','936794594',NULL,NULL,NULL,NULL,19);
/*!40000 ALTER TABLE `persona_natural` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `producto`
--

DROP TABLE IF EXISTS `producto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `producto` (
  `id_producto` bigint(20) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(300) DEFAULT NULL,
  `imagen` varchar(250) NOT NULL DEFAULT '-',
  `id_subcategoria` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id_producto`)
) ENGINE=InnoDB AUTO_INCREMENT=222 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `producto`
--

LOCK TABLES `producto` WRITE;
/*!40000 ALTER TABLE `producto` DISABLE KEYS */;
INSERT INTO `producto` (`id_producto`, `nombre`, `imagen`, `id_subcategoria`) VALUES (1,'Merino Rambouillet','https://upload.wikimedia.org/wikipedia/commons/8/84/Jielbeaumadier_belier_merinos_bn_rambouillet_2011.jpeg',1),(2,'Hampshire','-',1),(3,'Suffolk ','-',1),(4,'Africana','-',1),(5,'Doble Utilidad','-',1),(6,'Corriedale','-',1),(7,'Romney','-',1),(8,'Marsh','-',1),(9,'Cheviot','-',1),(10,'Black Face ','-',1),(11,'Criolla','-',1),(12,'Aberdeen Angus','-',2),(13,'Charolais','-',2),(14,'Wagyu','-',2),(15,'Rubia gallega','-',2),(16,'Frisona','-',2),(17,'Avileña-Negra ibérica.','-',2),(18,'Angus','-',2),(19,'Holstein','-',2),(20,'Hereford','-',2),(21,'Simmental','-',2),(22,'Limousin ','-',2),(23,'Pardo Suizo','-',2),(24,'Gelbvieh ','-',2),(25,'Chianina','-',2),(26,'Brangus','-',2),(27,'Angus rojo','-',2),(28,'Gallowey','-',2),(29,'DUROC','-',3),(30,'LARGE WHITE','-',3),(31,'BERKSHIRE','-',3),(32,'CERDO MINIATURA','-',3),(33,'TAMWORTH','-',3),(34,'BOER','-',4),(35,'ANGLONUBIANA','-',4),(36,'CABRA ENANA AFRICANA','-',4),(37,'CABALLO ÁRABE','-',5),(38,'FRISÓN','-',5),(39,'PURA SANGRE INGLÉS','-',5),(40,'CUARTO DE MILLA','-',5),(41,'CABALLO PERUANO DE PASO','-',5),(42,'CONEJO HOLANDÉS ','-',6),(43,'BELIER HOLANDÉS ','-',6),(44,'CONEJO CABEZA DE LEÓN ','-',6),(45,'CONEJO CALIFORNIANO','-',6),(46,'LEGHRON BLANCA','-',7),(47,'RHODE ISLAND ROJA','-',7),(48,'NEW HAMPSHIRE ','-',7),(49,'PLAYMOUNTH ROCK BLANCA','-',7),(50,'CORNISH ','-',7),(51,'PLYMOUNTH ROCK BARRADA','-',7),(52,'SUSSEX CLARA','-',7),(53,'Abonadoras por gravedad ','-',8),(54,'Abonadoras centrífugas','-',8),(55,'Abonadoras neumáticas ','-',8),(56,'Cosechadoras arrastradas ','-',9),(57,'Cosechadoras autopropulsadas ','-',9),(58,'Desbrozadoras para cortar hierba','-',10),(59,'Desbrozadoras para cortar zarzas','-',10),(60,'Desbrozadoras para cortar pequeños arbustos','-',10),(61,'Desbrozadoras para corte de monte\r\n','-',10),(62,'Desbrozadoras tipo mochila','-',10),(63,'Desbrozadoras multifuncionales','-',10),(64,'Desgranadora manual ','-',11),(65,'Desgranadora mecánica','-',11),(70,'Macroempacadoras','-',12),(71,'Rotoempacadoras','-',12),(72,'Empacadoras de grandes pacas rectangulares','-',12),(73,'Empacadoras de baja presión','-',12),(74,'Empacadora de media presión','-',12),(75,'Fumigadoras de motor','-',13),(76,'Mochila para fumigar','-',13),(77,'Fumigadora manual','-',13),(78,'Fumigadora de gasolina','-',13),(79,'Fumigadora de compresión','-',13),(80,'Fumigadora Doméstica','-',13),(81,'Motocultor a gasolina','-',14),(82,'Motocultor a diesel','-',14),(83,'Motocultor eléctrico','-',14),(84,'Motocultor con remolque','-',14),(85,'Riego por superficie o por gravedad','-',15),(86,'Riego por aspersión','-',15),(87,'Riego localizado','-',15),(88,'Riego subterráneo','-',15),(89,'Rodillos lisos o rodillos de tubo','-',16),(90,'Rodillos de anillos o discos (Cambridge)','-',16),(91,'Sistema hidráulico','-',16),(92,'Sembradora a chorrillo','-',17),(93,'Sembradora a monograno','-',17),(94,'Para siembra directa','-',17),(95,'A voleo','-',17),(96,'Sembradora para líneas','-',17),(97,'Sembradoras para hileras','-',17),(98,'Sembradoras para implantaciones sin laboreo previo del suelo','-',17),(99,'Con cajón para semilla','-',17),(100,'Sin cajón','-',17),(101,'Con cajón para fertilizante','-',17),(102,'Pasturas','-',17),(103,'Segadoras manuales','-',18),(104,'Segadoras de hierbas','-',18),(105,'Segadoras-trilladoras','-',18),(106,'Segadoras-repicadoras','-',18),(107,'Tractores Utilitarios','-',19),(108,'Tractores Compactos ','-',19),(109,'Tractores para cultivos en hileras','-',19),(110,'Tractores Industriales ','-',19),(111,'Tractores de Jardín','-',19),(112,'Tractores de Portadores de Implementos','-',19),(113,'TRACTORES PARA MOVIMIENTO DE TIERRA(BULLDOZER)','-',19),(114,'TRACTORES PARA MOVIMIENTO DE TIERRA(EXCAVADORAS)','-',19),(115,'TRACTORES PARA MOVIMIENTO DE TIERRA(RETROEXCAVADORAS)','-',19),(116,'Tractores Autonómos ','-',19),(117,'Tractores de dos ruedas','-',19),(118,'Carbamatos','-',24),(119,'Organofosfatos','-',24),(120,'Ciclodieno organoclorados','-',24),(121,'Fenilpirazoles (Fiproles)','-',24),(122,'Organoclorados','-',24),(123,'Piretroides','-',24),(124,'Piretrinas','-',24),(125,'Neonicotinoides','-',24),(126,'Nicotina','-',24),(127,'Spinocin','-',24),(128,'Avermectin','-',24),(129,'Hormona juvenil análoga e\r\nimitadora ','-',24),(130,'Cryolite','-',24),(131,'Pimetrozina','-',24),(132,'Especies de Bacillus','-',24),(133,'Diafentiuron','-',24),(134,'Clorfenapir','-',24),(135,'Benzoilúreas','-',24),(136,'Buprofezin','-',24),(137,'Diacilhidrazinas','-',24),(138,'Azadiractin','-',24),(139,'Rotenona','-',24),(140,'Indoxacarb','-',24),(141,'Amitraz','-',25),(142,'Azufre','-',25),(143,'Dicofol','-',25),(144,'Propargita','-',25),(145,'Tetradifon','-',25),(146,'Etion','-',25),(147,'Piridafention','-',25),(148,'Hexitiazox','-',25),(149,'Fenbutestan','-',25),(150,'Fungicidas protectores','-',26),(151,'Fungicidas penetrantes','-',26),(152,'Fungicidas translaminares','-',26),(153,'Fungicidas de movimiento por el xilema','-',26),(154,'Fungicidas sistémicos','-',26),(155,'Fumigantes','-',27),(156,'No fumigantes','-',27),(157,'Cloropicrina','-',28),(158,'Dicloropropeno','-',28),(159,'Metam-sodio','-',28),(160,'Metam-potasio','-',28),(161,'Metil tioisocianato','-',28),(162,'Agrocelhone','-',28),(163,'Fumigación de gas','-',29),(164,'Fumigación sólida','-',29),(165,'Fumigación líquida','-',29),(166,'Fumigación estructural','-',29),(168,'Herbicidas de acción total','-',30),(169,'Herbicidas de acción selectiva','-',30),(170,'Herbicidas residuales','-',30),(171,'Herbicidas foliares','-',30),(172,'Herbicidas de presiembra','-',30),(173,'Herbicidas de postsiembra','-',30),(174,'Herbicidas de preemergencia','-',30),(175,'herbicidas de postemergencia','-',30),(176,'Auxinas','-',31),(177,'Giberelinas','-',31),(178,'Citoquininas','-',31),(179,'Etileno','-',31),(180,'Abscisina','-',31),(181,'Agrol','-',32),(182,'Rodenticidas de acción rápida','-',33),(183,'Rodenticidas de acción lenta o anticoagulantes','-',33),(184,'Abono verde','-',34),(185,'Compost','-',34),(186,'Estiércol','-',34),(187,'Guano','-',34),(188,'Turba','-',34),(189,'Fertilizantes nitrogenados','-',35),(190,'Fertilizantes fosforados','-',35),(191,'Fertilizantes a base de potasio','-',35),(192,'Fijadores de nitrógeno','-',36),(193,'Solubilizadores de fósforo','-',36),(194,'Captadores de fósforo','-',36),(195,'Promotores de crecimiento vegetal','-',36),(196,'Fitohormonas ','-',37),(197,'Ácidos húmicos ','-',37),(198,'Ácidos fúlvicos','-',37),(199,'Extractos de algas ','-',37),(200,'Extractos de plantas ','-',37),(201,'Quintosanos ','-',37),(202,'Biopolímeros ','-',37),(203,'Hongos beneficiosos ','-',37),(204,'Aminoácidos y mezclas de péptidos','-',37),(205,'Tricontanol','-',37),(206,'Betaínas ','-',37),(207,'Poliaminas ','-',37),(208,'Estrigolactonas ','-',37),(209,'Brasinoesteroides ','-',37),(211,'Fertich','-',38),(212,'Cosmo-R','-',38),(213,'Terrano','-',38),(214,'Mixtura ','-',38),(215,'Mixmenores','-',38),(216,'Deccoscreen','-',39),(217,'Deccoshield ','-',39),(218,'Deccoguard Zn','-',39),(219,'Por goteo','-',40),(220,'Por aspersión','-',40),(221,'Por microaspersión','-',40);
/*!40000 ALTER TABLE `producto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `provincia`
--

DROP TABLE IF EXISTS `provincia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `provincia` (
  `id_provincia` bigint(20) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `id_departamento` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `provincia`
--

LOCK TABLES `provincia` WRITE;
/*!40000 ALTER TABLE `provincia` DISABLE KEYS */;
INSERT INTO `provincia` (`id_provincia`, `nombre`, `id_departamento`) VALUES (1,'BAGUA',1),(2,'BONGARA',1),(3,'CHACHAPOYAS',1),(4,'CONDORCANQUI',1),(5,'LUYA',1),(6,'RODRIGUEZ DE MENDOZA',1),(7,'UTCUBAMBA',1),(8,'AIJA',2),(9,'ANTONIO RAYMONDI',2),(10,'ASUNCION',2),(11,'BOLOGNESI',2),(12,'CARHUAZ',2),(13,'CARLOS FERMIN FITZCARRALD',2),(14,'CASMA',2),(15,'CORONGO',2),(16,'HUARAZ',2),(17,'HUARI',2),(18,'HUARMEY',2),(19,'HUAYLAS',2),(20,'MARISCAL LUZURIAGA',2),(21,'OCROS',2),(22,'PALLASCA',2),(23,'POMABAMBA',2),(24,'RECUAY',2),(25,'SANTA',2),(26,'SIHUAS',2),(27,'YUNGAY',2),(28,'ABANCAY',3),(29,'ANDAHUAYLAS',3),(30,'ANTABAMBA',3),(31,'AYMARAES',3),(32,'CHINCHEROS',3),(33,'COTABAMBAS',3),(34,'GRAU',3),(35,'AREQUIPA',4),(36,'CAMANA',4),(37,'CARAVELI',4),(38,'CASTILLA',4),(39,'CAYLLOMA',4),(40,'CONDESUYOS',4),(41,'ISLAY',4),(42,'LA UNION',4),(43,'CANGALLO',5),(44,'HUAMANGA',5),(45,'HUANCA SANCOS',5),(46,'HUANTA',5),(47,'LA MAR',5),(48,'LUCANAS',5),(49,'PARINACOCHAS',5),(50,'PAUCAR DEL SARA SARA',5),(51,'SUCRE',5),(52,'VICTOR FAJARDO',5),(53,'VILCAS HUAMAN',5),(54,'CAJABAMBA',6),(55,'CAJAMARCA',6),(56,'CELENDIN',6),(57,'CHOTA',6),(58,'CONTUMAZA',6),(59,'CUTERVO',6),(60,'HUALGAYOC',6),(61,'JAEN',6),(62,'SAN IGNACIO',6),(63,'SAN MARCOS',6),(64,'SAN MIGUEL',6),(65,'SAN PABLO',6),(66,'SANTA CRUZ',6),(67,'CALLAO',7),(68,'ACOMAYO',8),(69,'ANTA',8),(70,'CALCA',8),(71,'CANAS',8),(72,'CANCHIS',8),(73,'CHUMBIVILCAS',8),(74,'CUSCO',8),(75,'ESPINAR',8),(76,'LA CONVENCION',8),(77,'PARURO',8),(78,'PAUCARTAMBO',8),(79,'QUISPICANCHI',8),(80,'URUBAMBA',8),(81,'ACOBAMBA',9),(82,'ANGARAES',9),(83,'CASTROVIRREYNA',9),(84,'CHURCAMPA',9),(85,'HUANCAVELICA',9),(86,'HUAYTARA',9),(87,'TAYACAJA',9),(88,'AMBO',10),(89,'DOS DE MAYO',10),(90,'HUACAYBAMBA',10),(91,'HUAMALIES',10),(92,'HUANUCO',10),(93,'LAURICOCHA',10),(94,'LEONCIO PRADO',10),(95,'MARAÑON',10),(96,'PACHITEA',10),(97,'PUERTO INCA',10),(98,'YAROWILCA',10),(99,'CHINCHA',11),(100,'ICA',11),(101,'NAZCA',11),(102,'PALPA',11),(103,'PISCO',11),(104,'CHANCHAMAYO',12),(105,'CHUPACA',12),(106,'CONCEPCION',12),(107,'HUANCAYO',12),(108,'JAUJA',12),(109,'JUNIN',12),(110,'SATIPO',12),(111,'TARMA',12),(112,'YAULI',12),(113,'ASCOPE',13),(114,'BOLIVAR',13),(115,'CHEPEN',13),(116,'GRAN CHIMU',13),(117,'JULCAN',13),(118,'OTUZCO',13),(119,'PACASMAYO',13),(120,'PATAZ',13),(121,'SANCHEZ CARRION',13),(122,'SANTIAGO DE CHUCO',13),(123,'TRUJILLO',13),(124,'VIRU',13),(125,'CHICLAYO',14),(126,'FERREÑAFE',14),(127,'LAMBAYEQUE',14),(128,'BARRANCA',15),(129,'CAJATAMBO',15),(130,'CANTA',15),(131,'CAÑETE',15),(132,'HUARAL',15),(133,'HUAROCHIRI',15),(134,'HUAURA',15),(135,'LIMA',15),(136,'OYON',15),(137,'YAUYOS',15),(138,'ALTO AMAZONAS',16),(139,'DATEM DEL MARAÑON',16),(140,'LORETO',16),(141,'MARISCAL RAMON CASTILLA',16),(142,'MAYNAS',16),(143,'PUTUMAYO',16),(144,'REQUENA',16),(145,'UCAYALI',16),(146,'MANU',17),(147,'TAHUAMANU',17),(148,'TAMBOPATA',17),(149,'GENERAL SANCHEZ CERRO',18),(150,'ILO',18),(151,'MARISCAL NIETO',18),(152,'DANIEL ALCIDES CARRION',19),(153,'OXAPAMPA',19),(154,'PASCO',19),(155,'AYABACA',20),(156,'HUANCABAMBA',20),(157,'MORROPON',20),(158,'PAITA',20),(159,'PIURA',20),(160,'SECHURA',20),(161,'SULLANA',20),(162,'TALARA',20),(163,'AZANGARO',21),(164,'CARABAYA',21),(165,'CHUCUITO',21),(166,'EL COLLAO',21),(167,'HUANCANE',21),(168,'LAMPA',21),(169,'MELGAR',21),(170,'MOHO',21),(171,'PUNO',21),(172,'SAN ANTONIO DE PUTINA',21),(173,'SAN ROMAN',21),(174,'SANDIA',21),(175,'YUNGUYO',21),(176,'BELLAVISTA',22),(177,'EL DORADO',22),(178,'HUALLAGA',22),(179,'LAMAS',22),(180,'MARISCAL CACERES',22),(181,'MOYOBAMBA',22),(182,'PICOTA',22),(183,'RIOJA',22),(184,'SAN MARTIN',22),(185,'TOCACHE',22),(186,'CANDARAVE',23),(187,'JORGE BASADRE',23),(188,'TACNA',23),(189,'TARATA',23),(190,'CONTRALMIRANTE VILLAR',24),(191,'TUMBES',24),(192,'ZARUMILLA',24),(193,'ATALAYA',25),(194,'CORONEL PORTILLO',25),(195,'PADRE ABAD',25),(196,'PURUS',25);
/*!40000 ALTER TABLE `provincia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock`
--

DROP TABLE IF EXISTS `stock`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock` (
  `id_stock` bigint(20) NOT NULL AUTO_INCREMENT,
  `precio` decimal(10,2) DEFAULT NULL,
  `imagen` varchar(500) DEFAULT NULL,
  `id_usuario` bigint(20) DEFAULT NULL,
  `id_producto` bigint(20) DEFAULT NULL,
  `cantidad` int(11) NOT NULL,
  PRIMARY KEY (`id_stock`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock`
--

LOCK TABLES `stock` WRITE;
/*!40000 ALTER TABLE `stock` DISABLE KEYS */;
INSERT INTO `stock` (`id_stock`, `precio`, `imagen`, `id_usuario`, `id_producto`, `cantidad`) VALUES (1,25.50,'https://upload.wikimedia.org/wikipedia/commons/8/84/Jielbeaumadier_belier_merinos_bn_rambouillet_2011.jpeg',16,1,150),(2,30.30,'https://zoovetesmipasion.com/wp-content/uploads/2017/09/La-raza-ovina-Rambouillet.jpg',16,1,24),(3,10.40,'',16,2,9),(4,6.70,'',16,2,420),(5,8.90,'',16,2,600),(6,9.60,'',16,3,100),(7,6.40,'',16,3,50),(8,5.30,'',16,3,300),(9,3.20,'',16,4,4),(10,4.10,'',16,4,40),(11,10.10,'',16,4,70),(12,3.50,'',16,4,60),(13,25.00,NULL,16,45,230),(14,150.00,'https://www.researchgate.net/profile/Francisco-Ramos-29/publication/28276266/figure/fig5/AS:339488812355586@1457951848036/Figura-6-Macroempacadora-Documentacion-New-Holland.png',16,70,200);
/*!40000 ALTER TABLE `stock` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subcategoria`
--

DROP TABLE IF EXISTS `subcategoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subcategoria` (
  `id_subcategoria` bigint(20) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(300) DEFAULT NULL,
  `id_categoria` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id_subcategoria`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subcategoria`
--

LOCK TABLES `subcategoria` WRITE;
/*!40000 ALTER TABLE `subcategoria` DISABLE KEYS */;
INSERT INTO `subcategoria` (`id_subcategoria`, `nombre`, `id_categoria`) VALUES (1,'Ovino: Ovejas',1),(2,'Bovino o vacuno: Bueyes, toros y vacas',1),(3,'Porcino: Cerdo',1),(4,'Caprino: Cabras',1),(5,'Equino: Caballos y yeguas',1),(6,'Cunicultura: Conejos',1),(7,'Avicultura: Aves de corral',1),(8,'Abonadora',2),(9,'Cosechadora',2),(10,'Desbrozadora',2),(11,'Desgranadora',2),(12,'Empacadora',2),(13,'Fumigadora',2),(14,'Motocultor',2),(15,'Motor para riego',2),(16,'Rodillo agrícola',2),(17,'Sembradora',2),(18,'Segadora',2),(19,'Tractor',2),(20,'Tubérculos radicales',3),(21,'Tubérculos hidropónicos',3),(22,'Tubérculos tropicales',3),(23,'Tubérculos comestibles',3),(24,'Insecticidas',4),(25,'Acaricidas',4),(26,'Fungicidas',4),(27,'Nematocidas',4),(28,'Desinfectantes de suelo',4),(29,'Fumigantes',4),(30,'Herbicidas',4),(31,'Fitorreguladores',4),(32,'Molusquicidas',4),(33,'Rodenticidas',4),(34,'Fertilizantes orgánicos',5),(35,'Fertilizantes químicos',5),(36,'Biofertilizantes',5),(37,'Bioestimulantes',5),(38,'Fertilizante radicular o al suelo',5),(39,'Fertilizante foliar',5),(40,'Fertirrigación',5),(41,'Peces pelágicos',6),(42,'Pescado blanco',6),(43,'Cefalópodos',6),(44,'Crustáceos',6),(45,'Mariscos',6),(46,'Algas',6),(47,'Equinodermos',6);
/*!40000 ALTER TABLE `subcategoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unidad`
--

DROP TABLE IF EXISTS `unidad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `unidad` (
  `id_unidad` bigint(20) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_unidad`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unidad`
--

LOCK TABLES `unidad` WRITE;
/*!40000 ALTER TABLE `unidad` DISABLE KEYS */;
INSERT INTO `unidad` (`id_unidad`, `nombre`) VALUES (1,'Kilogramo (kg)'),(2,'Gramo (gr)'),(3,'Miligramo (mg)'),(4,'Hectogramo (hg)'),(5,'Decagramo (dag)'),(6,'Decigramo (dg)'),(7,'Centigramo (cg)'),(8,'Metro (m)'),(9,'Joule/segundo (J/s)');
/*!40000 ALTER TABLE `unidad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario` (
  `id_usuario` bigint(20) NOT NULL AUTO_INCREMENT,
  `documento` varchar(11) NOT NULL,
  `nombre` varchar(300) DEFAULT NULL,
  `correo` varchar(300) DEFAULT NULL,
  `clave` varchar(16) DEFAULT NULL,
  `estado` char(1) DEFAULT NULL,
  `tipo_vendedor` varchar(3) NOT NULL,
  `tipo_persona` char(1) DEFAULT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` (`id_usuario`, `documento`, `nombre`, `correo`, `clave`, `estado`, `tipo_vendedor`, `tipo_persona`) VALUES (16,'12345678','Renzo Alejandro Alcántara Lizares','renzo123','1234','D','MAY','N'),(19,'','Bryant Alejandro','bryant','0000','D','',NULL),(20,'','Renzo Alejandro','RAAL','tyga2020..+','D','',NULL),(21,'','','renzo123','1234','D','',NULL),(22,'','','renzo123','1234','D','',NULL),(23,'','','renzo123','1234','D','',NULL),(24,'','','renzo123','1234','D','',NULL),(25,'','','renzo123','1234','D','',NULL),(26,'','','renzo123','1234','D','',NULL),(27,'','','renzo123','1234','D','',NULL),(28,'','','renzo123','1234','D','',NULL),(29,'','Zoila ','zlizares','123456','D','',NULL),(30,'','','rrtrt','12345','D','',NULL),(31,'','','renzo123','1234','D','',NULL);
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `venta`
--

DROP TABLE IF EXISTS `venta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `venta` (
  `id_venta` bigint(20) NOT NULL AUTO_INCREMENT,
  `fecha` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `id_usuario_compra` bigint(20) DEFAULT NULL,
  `id_distrito` int(11) NOT NULL,
  PRIMARY KEY (`id_venta`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `venta`
--

LOCK TABLES `venta` WRITE;
/*!40000 ALTER TABLE `venta` DISABLE KEYS */;
INSERT INTO `venta` (`id_venta`, `fecha`, `hora`, `id_usuario_compra`, `id_distrito`) VALUES (1,'2022-01-15','18:02:14',1,1214),(2,'2022-01-15','18:03:24',1,1214),(3,'2022-01-15','18:27:16',1,1214);
/*!40000 ALTER TABLE `venta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'tratoagr_basededatostratoagro'
--

--
-- Dumping routines for database 'tratoagr_basededatostratoagro'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-11-30 20:54:38
