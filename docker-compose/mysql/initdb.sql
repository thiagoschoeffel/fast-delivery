-- MySQL dump 10.13  Distrib 5.5.62, for Win64 (AMD64)
--
-- Host: 162.241.2.123    Database: fast_delivery
-- ------------------------------------------------------
-- Server version	5.7.21-21

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
-- Table structure for table `ci_sessions`
--

DROP TABLE IF EXISTS `ci_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clientes` (
  `cliente_id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_nome` varchar(255) NOT NULL,
  `cliente_telefone` varchar(255) NOT NULL,
  `cliente_data_nascimento` date NOT NULL,
  `cliente_observacao` longtext,
  `cliente_status` char(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`cliente_id`)
) ENGINE=InnoDB AUTO_INCREMENT=344 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `clientesenderecos`
--

DROP TABLE IF EXISTS `clientesenderecos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clientesenderecos` (
  `clienteendereco_id` int(11) NOT NULL AUTO_INCREMENT,
  `clienteendereco_cliente` int(11) NOT NULL,
  `clienteendereco_cep` varchar(255) NOT NULL,
  `clienteendereco_logradouro` varchar(255) NOT NULL,
  `clienteendereco_numero` int(11) DEFAULT '0',
  `clienteendereco_bairro` varchar(255) NOT NULL,
  `clienteendereco_complemento` varchar(255) DEFAULT NULL,
  `clienteendereco_entregador` int(11) NOT NULL,
  `clienteendereco_cidade` varchar(255) NOT NULL,
  `clienteendereco_uf` char(2) NOT NULL,
  `clienteendereco_status` char(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`clienteendereco_id`),
  KEY `addresses_fk` (`clienteendereco_cliente`),
  KEY `addresses_fk_1_idx` (`clienteendereco_entregador`),
  CONSTRAINT `addresses_FK` FOREIGN KEY (`clienteendereco_cliente`) REFERENCES `clientes` (`cliente_id`),
  CONSTRAINT `addresses_FK_1` FOREIGN KEY (`clienteendereco_entregador`) REFERENCES `entregadores` (`entregador_id`)
) ENGINE=InnoDB AUTO_INCREMENT=344 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `entregadores`
--

DROP TABLE IF EXISTS `entregadores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `entregadores` (
  `entregador_id` int(11) NOT NULL AUTO_INCREMENT,
  `entregador_nome` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `entregador_telefone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `entregador_status` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'A',
  PRIMARY KEY (`entregador_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `formaspagamento`
--

DROP TABLE IF EXISTS `formaspagamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `formaspagamento` (
  `formapagamento_id` int(11) NOT NULL AUTO_INCREMENT,
  `formapagamento_descricao` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `formapagamento_status` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'A',
  PRIMARY KEY (`formapagamento_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `pedidos`
--

DROP TABLE IF EXISTS `pedidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pedidos` (
  `pedido_id` int(11) NOT NULL AUTO_INCREMENT,
  `pedido_data_emissao` date NOT NULL,
  `pedido_cliente` int(11) DEFAULT NULL,
  `pedido_clienteendereco` int(11) DEFAULT NULL,
  `pedido_entregador` int(11) DEFAULT NULL,
  `pedido_formapagamento` int(11) DEFAULT NULL,
  `pedido_observacao` longtext COLLATE utf8_unicode_ci,
  `pedido_valor_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `pedido_status` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'A',
  `pedido_rascunho` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'S',
  `pedido_sequencia_entrega` int(11) DEFAULT '0',
  PRIMARY KEY (`pedido_id`),
  KEY `pedidos_FK` (`pedido_cliente`),
  KEY `pedidos_FK_1` (`pedido_clienteendereco`),
  KEY `pedidos_FK_2` (`pedido_entregador`),
  KEY `pedidos_FK_4` (`pedido_formapagamento`),
  CONSTRAINT `pedidos_FK` FOREIGN KEY (`pedido_cliente`) REFERENCES `clientes` (`cliente_id`),
  CONSTRAINT `pedidos_FK_1` FOREIGN KEY (`pedido_clienteendereco`) REFERENCES `clientesenderecos` (`clienteendereco_id`),
  CONSTRAINT `pedidos_FK_2` FOREIGN KEY (`pedido_entregador`) REFERENCES `entregadores` (`entregador_id`),
  CONSTRAINT `pedidos_FK_4` FOREIGN KEY (`pedido_formapagamento`) REFERENCES `formaspagamento` (`formapagamento_id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `pedidositens`
--

DROP TABLE IF EXISTS `pedidositens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pedidositens` (
  `pedidoitem_id` int(11) NOT NULL AUTO_INCREMENT,
  `pedidoitem_pedido` int(11) NOT NULL,
  `pedidoitem_produto` int(11) NOT NULL,
  `pedidoitem_quantidade` decimal(10,4) NOT NULL DEFAULT '0.0000',
  `pedidoitem_valor_unitario` decimal(10,2) NOT NULL DEFAULT '0.00',
  `pedidoitem_valor_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`pedidoitem_id`),
  KEY `pedidositens_FK` (`pedidoitem_pedido`),
  KEY `pedidositens_FK_1` (`pedidoitem_produto`),
  CONSTRAINT `pedidositens_FK` FOREIGN KEY (`pedidoitem_pedido`) REFERENCES `pedidos` (`pedido_id`),
  CONSTRAINT `pedidositens_FK_1` FOREIGN KEY (`pedidoitem_produto`) REFERENCES `produtos` (`produto_id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `produtos`
--

DROP TABLE IF EXISTS `produtos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `produtos` (
  `produto_id` int(11) NOT NULL AUTO_INCREMENT,
  `produto_descricao` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `produto_preco` decimal(10,2) NOT NULL,
  `produto_status` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'A',
  PRIMARY KEY (`produto_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `produtosestruturas`
--

DROP TABLE IF EXISTS `produtosestruturas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `produtosestruturas` (
  `produtoestrutura_id` int(11) NOT NULL AUTO_INCREMENT,
  `produtoestrutura_produto` int(11) NOT NULL,
  `produtoestrutura_subproduto` int(11) NOT NULL,
  `produtoestrutura_quantidade` decimal(10,4) NOT NULL,
  PRIMARY KEY (`produtoestrutura_id`),
  KEY `produtosestruturas_FK` (`produtoestrutura_produto`),
  KEY `produtosestruturas_FK_1` (`produtoestrutura_subproduto`),
  CONSTRAINT `produtosestruturas_FK` FOREIGN KEY (`produtoestrutura_produto`) REFERENCES `produtos` (`produto_id`),
  CONSTRAINT `produtosestruturas_FK_1` FOREIGN KEY (`produtoestrutura_subproduto`) REFERENCES `produtos` (`produto_id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `usuario_id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_nome` varchar(255) NOT NULL,
  `usuario_email` varchar(255) NOT NULL,
  `usuario_senha` varchar(255) NOT NULL,
  `usuario_data_ultimo_login` datetime DEFAULT NULL,
  PRIMARY KEY (`usuario_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'ADMIN','admin@example.com','$2a$12$8b8XuXk.FfNMDVME7f7SsOSpnogB.nZNBeKC.cAdNuhpdb9pMKhgO','2025-10-31 19:04:00');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `vbiembalagem`
--

DROP TABLE IF EXISTS `vbiembalagem`;
/*!50001 DROP VIEW IF EXISTS `vbiembalagem`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `vbiembalagem` (
  `pedido_id` tinyint NOT NULL,
  `cliente_nome` tinyint NOT NULL,
  `entregador_nome` tinyint NOT NULL,
  `pedido_observacao` tinyint NOT NULL,
  `produto_descricao` tinyint NOT NULL,
  `pedidoitem_quantidade` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vbientrega`
--

DROP TABLE IF EXISTS `vbientrega`;
/*!50001 DROP VIEW IF EXISTS `vbientrega`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `vbientrega` (
  `pedido_id` tinyint NOT NULL,
  `cliente_nome` tinyint NOT NULL,
  `formapagamento_descricao` tinyint NOT NULL,
  `pedido_valor_total` tinyint NOT NULL,
  `clienteendereco_logradouro` tinyint NOT NULL,
  `clienteendereco_numero` tinyint NOT NULL,
  `clienteendereco_complemento` tinyint NOT NULL,
  `clienteendereco_bairro` tinyint NOT NULL,
  `clienteendereco_cidade` tinyint NOT NULL,
  `clienteendereco_uf` tinyint NOT NULL,
  `entregador_id` tinyint NOT NULL,
  `entregador_nome` tinyint NOT NULL,
  `pedido_observacao` tinyint NOT NULL,
  `pedido_sequencia_entrega` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vbiproducao`
--

DROP TABLE IF EXISTS `vbiproducao`;
/*!50001 DROP VIEW IF EXISTS `vbiproducao`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `vbiproducao` (
  `produto_descricao` tinyint NOT NULL,
  `produto_quantidade` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `veiculos`
--

DROP TABLE IF EXISTS `veiculos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `veiculos` (
  `veiculo_id` int(11) NOT NULL AUTO_INCREMENT,
  `veiculo_nome` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `veiculo_status` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'A',
  PRIMARY KEY (`veiculo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `veiculos`
--

LOCK TABLES `veiculos` WRITE;
/*!40000 ALTER TABLE `veiculos` DISABLE KEYS */;
/*!40000 ALTER TABLE `veiculos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'fast_delivery'
--

--
-- Final view structure for view `vbiembalagem`
--

/*!50001 DROP TABLE IF EXISTS `vbiembalagem`*/;
/*!50001 DROP VIEW IF EXISTS `vbiembalagem`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50001 VIEW `vbiembalagem` AS select `p`.`pedido_id` AS `pedido_id`,`c`.`cliente_nome` AS `cliente_nome`,`e`.`entregador_nome` AS `entregador_nome`,`p`.`pedido_observacao` AS `pedido_observacao`,`p3`.`produto_descricao` AS `produto_descricao`,`p2`.`pedidoitem_quantidade` AS `pedidoitem_quantidade` from ((((`pedidos` `p` left join `pedidositens` `p2` on((`p2`.`pedidoitem_pedido` = `p`.`pedido_id`))) left join `clientes` `c` on((`c`.`cliente_id` = `p`.`pedido_cliente`))) left join `entregadores` `e` on((`e`.`entregador_id` = `p`.`pedido_entregador`))) left join `produtos` `p3` on((`p3`.`produto_id` = `p2`.`pedidoitem_produto`))) where ((`p`.`pedido_data_emissao` = curdate()) and (`p`.`pedido_status` = 'P')) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vbientrega`
--

/*!50001 DROP TABLE IF EXISTS `vbientrega`*/;
/*!50001 DROP VIEW IF EXISTS `vbientrega`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50001 VIEW `vbientrega` AS select `p`.`pedido_id` AS `pedido_id`,`c`.`cliente_nome` AS `cliente_nome`,`f`.`formapagamento_descricao` AS `formapagamento_descricao`,`p`.`pedido_valor_total` AS `pedido_valor_total`,`c2`.`clienteendereco_logradouro` AS `clienteendereco_logradouro`,`c2`.`clienteendereco_numero` AS `clienteendereco_numero`,`c2`.`clienteendereco_complemento` AS `clienteendereco_complemento`,`c2`.`clienteendereco_bairro` AS `clienteendereco_bairro`,`c2`.`clienteendereco_cidade` AS `clienteendereco_cidade`,`c2`.`clienteendereco_uf` AS `clienteendereco_uf`,`e`.`entregador_id` AS `entregador_id`,`e`.`entregador_nome` AS `entregador_nome`,`p`.`pedido_observacao` AS `pedido_observacao`,`p`.`pedido_sequencia_entrega` AS `pedido_sequencia_entrega` from ((((`pedidos` `p` left join `clientes` `c` on((`c`.`cliente_id` = `p`.`pedido_cliente`))) left join `entregadores` `e` on((`e`.`entregador_id` = `p`.`pedido_entregador`))) left join `formaspagamento` `f` on((`f`.`formapagamento_id` = `p`.`pedido_formapagamento`))) left join `clientesenderecos` `c2` on((`c2`.`clienteendereco_id` = `p`.`pedido_clienteendereco`))) where ((`p`.`pedido_data_emissao` = curdate()) and (`p`.`pedido_status` = 'D')) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vbiproducao`
--

/*!50001 DROP TABLE IF EXISTS `vbiproducao`*/;
/*!50001 DROP VIEW IF EXISTS `vbiproducao`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50001 VIEW `vbiproducao` AS select `p4`.`produto_descricao` AS `produto_descricao`,sum((`p3`.`produtoestrutura_quantidade` * `p`.`pedidoitem_quantidade`)) AS `produto_quantidade` from ((((`pedidositens` `p` left join `produtos` `p2` on((`p2`.`produto_id` = `p`.`pedidoitem_produto`))) left join `produtosestruturas` `p3` on((`p3`.`produtoestrutura_produto` = `p2`.`produto_id`))) left join `produtos` `p4` on((`p4`.`produto_id` = `p3`.`produtoestrutura_subproduto`))) left join `pedidos` `p5` on((`p5`.`pedido_id` = `p`.`pedidoitem_pedido`))) where ((`p5`.`pedido_data_emissao` = curdate()) and (`p5`.`pedido_status` in ('P','D','C'))) group by `p4`.`produto_descricao` order by `p5`.`pedido_id` */;
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

-- Dump completed on 2021-10-21 15:57:01
