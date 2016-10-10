<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="notif-bot-pedidos" data-notify-html="title"></div>
<?php $this->load->view( 'header' ); ?>
		Los siguientes usuarios deberan concluir sus operaciones en las siguientes secciones:
<?php 
	
	$id_perfil=$this->session->userdata('id_perfil');
   //print_r($entradas);
	if ($entradas!=false) {
		echo "<br/>"."<b>Entradas</b>"."<br/>";
		foreach ( $entradas as $entrada ) { 
				print_r($entrada->nombre.'<br/>');
		}
	}	

	if ($pedidos!=false) {	
		echo "<br/>"."<b>Pedidos</b>"."<br/>";
		
		foreach ( $pedidos as $pedido ) { 
				print_r($pedido->nombre.'<br/>');
		}
	}

	if ($devoluciones!=false) {	
		echo "<br/>"."<b>Devoluciones</b>"."<br/>";
		
		foreach ( $devoluciones as $devolucion ) { 
				print_r($devolucion->nombre.'<br/>');
		}
	}	



	if ($traspasos!=false) {	
		echo "<br/>"."<b>Traspasos</b>"."<br/>";
		
		foreach ( $traspasos as $traspaso ) { 
				print_r($traspaso->nombre.'<br/>');
		}
	}	



?>
<?php $this->load->view( 'footer' ); ?>