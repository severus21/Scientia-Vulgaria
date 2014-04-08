--
-- Table structure for table `post`
--

DROP TABLE IF EXISTS `post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user_createur` int(11) NOT NULL,
  `dateCreation` datetime NOT NULL,
  `topicid` int(11) DEFAULT NULL,
  `forumid` int(11) DEFAULT NULL,
  `texthtml` text,
  `textbbcode` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8;
