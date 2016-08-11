<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Has recibido una alerta del Sistema de control de inventario</title>
</head>
<body style="background:#e9e9e9">


	<table width="590" border="0" cellspacing="0" cellpadding="0" style="margin:0 auto; background-color:white; padding:20px">
	  <tr style="background-color:#2E509A;">
	   	 <td scope="row" style="border-bottom:5px solid #ad0132; height:45px;"></td>
	  </tr>
	  <tr>
	    <td scope="row" style="padding:20px">
	    <p style="font-family:'Myriad Pro', 'Myriad Pro Bold', Verdana, Arial; color:#5e6d81; font-size:23px; font-weight:bold">Estimado administrador:</b></p>
	    <p style="font-family:'Myriad Pro', 'Myriad Pro Bold', Verdana, Arial; font-size:20px; color:#5e6d81">
	    	    Te recordamos tu usuario y contraseña para entrar al Sistema de control de inventario:  
				

			<br><br>
					    <b>E-mail: </b> <?php echo $email; ?>
			<br><br>					    
						<b>Contraseña: </b> <?php echo $pass; ?>
			
			
			<div class="col-sm-4 col-md-4">
				<a href="<?php echo base_url(); ?>" name="boton" style="background:#ad0132; font-family:'Myriad Pro', 'Myriad Pro Bold', Verdana, Arial; font-size:18px; color:white; padding:10px; border:none; text-decoration:none;">
						IR AL SISTEMA
				</a>
			</div>				  


		</p>
	   </td>
	  </tr>
	  <tr style="background-color:#2E509A;">
	    <td scope="row" style="border-bottom:5px solid #ad0132; height:25px;"></td>
	  </tr>	  
	  
	</table>
	

</body>
</html>
