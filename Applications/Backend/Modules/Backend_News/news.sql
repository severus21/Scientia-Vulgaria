--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user_createur` int(11) NOT NULL,
  `app` varchar(256) NOT NULL,
  `accreditation` int(11) NOT NULL,
  `statut` int(11) NOT NULL,
  `dateCreation` datetime NOT NULL,
  `texthtml` text,
  `textbbcode` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
