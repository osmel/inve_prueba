<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php $this->load->view( 'header' ); ?>

<a href="<?php echo base_url(); ?>nuevo_actividad_comercial" id="boton" type="button" class="btn btn-success btn-block">Nueva actividad comercial</a>

 <form action="search.php" method="post">
    <input type="text" id="barcode" name="documentID" >
</form>
	
<?php $this->load->view( 'footer' ); ?>


