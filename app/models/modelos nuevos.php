<?php 
CREATE TABLE IF NOT EXISTS `inven_historico_ctasxpagar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `movimiento` int(11) DEFAULT NULL, 
  `id_tipo_pago` int(11) NOT NULL,  
  `id_almacen` int(11) NOT NULL,
  `id_empresa` int(11) DEFAULT NULL, //proveedor
  `fecha_entrada` datetime DEFAULT NULL,
  `factura` varchar(30) DEFAULT NULL,
  `subtotal` float(10,2) NOT NULL,
  `iva` float(10,2) NOT NULL,
  `total` float(10,2) NOT NULL,
  `id_factura` int(11) NOT NULL, //tipo_factura
  `id_cargador` int(11) NOT NULL, //no se registra en la factura
  `id_usuario` varchar(36) NOT NULL, //el que realizo la factura
  `fecha_mac` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, //esta es una fecha de cambios
  `fecha_vencimiento` datetime NOT NULL, //posible fecha de vencimiento
  `comentario` text,
  `id_operacion` int(2) NOT NULL,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14;


CREATE TABLE IF NOT EXISTS `inven_historico_pagos_realizados` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `movimiento` int(11) DEFAULT NULL, 
  `id_documento_pago` int(11) DEFAULT NULL, 
  `instrumento_pago` varchar(25) DEFAULT NULL,
  `fecha_pago` datetime NOT NULL, 
  `importe` float(10,2) NOT NULL,
  `comentario` text,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;


CREATE TABLE IF NOT EXISTS `inven_catalogo_documentos_pagos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `documento_pago` varchar(45) DEFAULT NULL,
  `id_usuario` varchar(36) NOT NULL,
  `fecha_mac` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `inven_catalogo_tipos_pagos`
--

INSERT INTO `inven_catalogo_documentos_pagos` (`id`, `documento_pago`, `id_usuario`, `fecha_mac`) VALUES
(1, 'Transferencia', '', '2016-09-05 21:13:49'),
(2, 'Cheque', '', '2016-09-05 21:13:49'),
(3, 'Deposito', '', '2016-09-05 21:13:49');


