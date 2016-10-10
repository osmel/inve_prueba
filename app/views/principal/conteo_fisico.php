<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="notif-bot-pedidos" data-notify-html="title"></div>
<?php $this->load->view( 'header' ); ?>
		Conteo físico (inventario)
<?php $id_perfil=$this->session->userdata('id_perfil');

print_r($id_perfil);

?>
<?php $this->load->view( 'footer' ); ?>