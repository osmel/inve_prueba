<?php
 
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
 
class Pdfs extends CI_Controller {
 
    function __construct() {
        parent::__construct();
        $this->load->model('model_pedido', 'modelo_pedido');
        $this->load->model('model_pedido_compra', 'model_pedido_compra'); 
        $this->load->model('pdfs_model','modelo_pdf');
        $this->load->model('catalogo', 'catalogo');  
    }
    
    public function index()  {
        //$data['provincias'] llena el select con las provincias españolas
         $data['movimientos']  = $this->modelo_pdf->listado_movimientos_temporal();
        //cargamos la vista y pasamos el array $data['provincias'] para su uso
        $this->load->view('pdfs/pdfs_view', $data);
    }



public function impresion_etiquetas1($codigo) {
    $data['codigo'] = base64_decode($codigo);

    $data['movimientos'] = $this->modelo_pdf->etiqueta_codigo($data);
    print_r($data['movimientos']);

   } 


  public function impresion_etiquetas($codigo) {
        
        $data['codigo'] = base64_decode($codigo);

        $data['movimientos'] = $this->modelo_pdf->etiqueta_codigo($data);
        //print_r($data['movimientos']);


        //$data['id_movimiento']= base64_decode($id_movimiento);

        /////////////

        set_time_limit(0); 
        ignore_user_abort(1);
        ini_set('memory_limit','512M'); 

        $this->load->library('Pdf');
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('Titulo Generación de Etiqueta');
        $pdf->SetSubject('Subtitulo');
        //$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
 
// datos por defecto de cabecera, se pueden modificar en el archivo tcpdf_config_alt.php de libraries/config
        //$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 001', PDF_HEADER_STRING, array(0, 64, 255), array(0, 64, 128));
        //$pdf->setFooterData($tc = array(0, 64, 0), $lc = array(0, 64, 128));
 
// datos por defecto de cabecera, se pueden modificar en el archivo tcpdf_config.php de libraries/config
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
 
// se pueden modificar en el archivo tcpdf_config.php de libraries/config
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
 

//relación utilizada para ajustar la conversión de los píxeles
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
 
 
// ---------------------------------------------------------
// establecer el modo de fuente por defecto
        $pdf->setFontSubsetting(true);
 
// Establecer el tipo de letra
 
//Si tienes que imprimir carácteres ASCII estándar, puede utilizar las fuentes básicas como
// Helvetica para reducir el tamaño del archivo.
        $pdf->SetFont('freemono', '', 14, '', true);
 
// Añadir una página
// Este método tiene varias opciones, consulta la documentación para más información.
      //  $pdf->AddPage();
 
//fijar efecto de sombra en el texto
        $pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));
 
// Establecemos el contenido para imprimir
        
        //$provincia = $this->input->post('provincia');
        
        //$data['id_movimiento']='29';


        // remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        //$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT, PDF_MARGIN_BOTTOM);
        // set margins
        $pdf->SetMargins(0, 0, 0);

        // set auto page breaks
        $pdf->SetAutoPageBreak(true, -10);


         $pdf->AddPage('P', array( 100.0,  100.0)); //en mm
         //$pdf->AddPage(); //en mm

       



        $html = $this->load->view('pdfs/etiq_new', $data, true);
        

 
// Imprimimos el texto con writeHTMLCell()
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
 
// ---------------------------------------------------------
// Cerrar el documento PDF y preparamos la salida
// Este método tiene varias opciones, consulte la documentación para más información.
        $nombre_archivo = utf8_decode("Etiqueta_".$data['codigo'].".pdf");
        $pdf->Output($nombre_archivo, 'I');
    }


  public function generar_salida_rapida($id_movimiento,$id_tipo_pedido,$id_tipo_factura,$id_estatus){

        $data['id_movimiento']       = base64_decode($id_movimiento);
        $data['id_tipo_pedido']      = base64_decode($id_tipo_pedido);
        $data['id_tipo_factura']     = base64_decode($id_tipo_factura);
        $data['id_estatus']     = base64_decode($id_estatus);
        /////////////

        $data['movimientos'] = $this->modelo_pdf->listado_salida($data);

        $data['totales'] = $this->modelo_pdf->totales_salidas($data);      

        $data['etiq_mov'] ="Salida";  

       $dato['id'] = 10; //solo para salida
       $data['configuracion'] = $this->catalogo->coger_configuracion($dato); 

        $html = $this->load->view('pdfs/salidas/notas_rapida', $data, true);
        

        echo $html;


    }


  public function generar_salida($id_movimiento,$id_tipo_pedido,$id_tipo_factura,$id_estatus){

        $data['id_movimiento']       = base64_decode($id_movimiento);
        $data['id_tipo_pedido']      = base64_decode($id_tipo_pedido);
        $data['id_tipo_factura']     = base64_decode($id_tipo_factura);
        $data['id_estatus']     = base64_decode($id_estatus);
        /////////////

        set_time_limit(0); 
        ignore_user_abort(1);
        ini_set('memory_limit','512M'); 

        $this->load->library('Pdf');
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('Titulo Generación de Etiqueta');
        $pdf->SetSubject('Subtitulo');
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
 
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
 

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
 
        $pdf->setFontSubsetting(true);

        $pdf->SetFont('Times', '', 8,'','true');

 
        $pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));
 
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10, 10, 10,true);
        
        $pdf->SetAutoPageBreak(true, 10);

        $pdf->AddPage('P', array( 215.9,  279.4)); //en mm 21.59cm por 27.94cm



        $data['movimientos'] = $this->modelo_pdf->listado_salida($data);

        $data['totales'] = $this->modelo_pdf->totales_salidas($data);      

        $data['etiq_mov'] ="Salida";  

       $dato['id'] = 10; //solo para salida
       $data['configuracion'] = $this->catalogo->coger_configuracion($dato); 

        $html = $this->load->view('pdfs/salidas/notas', $data, true);
        

        // Imprimimos el texto con writeHTMLCell()
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
         
        // ---------------------------------------------------------
        // Cerrar el documento PDF y preparamos la salida
        // Este método tiene varias opciones, consulte la documentación para más información.
        $nombre_archivo = utf8_decode("Salidas_".$data['id_movimiento'].".pdf");
        $pdf->Output($nombre_archivo, 'I');



    }



  public function generar_notas_rapida($id_movimiento,$dev,$id_factura,$id_estatus) {

        $data['dev']= base64_decode($dev);    
        $data['id_movimiento']= base64_decode($id_movimiento);
        $data['id_factura']= base64_decode($id_factura);
        $data['id_estatus']= base64_decode($id_estatus);




        $data['movimientos'] = $this->modelo_pdf->listado_registros($data);

        $data['totales'] = $this->modelo_pdf->totales_entradas($data);


        


          if  ($data['dev'] == 0) {
                $data['etiq_mov'] ="Entrada";
          }
          
          if  ($data['dev'] == 1) {
                $data['etiq_mov'] ="Devolución";
          }

        $dato['id'] = 7; //solo para salida
        $data['configuracion'] = $this->catalogo->coger_configuracion($dato); 
        $html = $this->load->view('pdfs/notas_rapida', $data, true);
        
        echo $html;
    }


  public function generar_notas($id_movimiento,$dev,$id_factura,$id_estatus) {

        $data['dev']= base64_decode($dev);    
        $data['id_movimiento']= base64_decode($id_movimiento);
        $data['id_factura']= base64_decode($id_factura);
        $data['id_estatus']= base64_decode($id_estatus);

           
        /////////////

        set_time_limit(0); 
        ignore_user_abort(1);
        ini_set('memory_limit','512M'); 

        $this->load->library('Pdf');
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('Titulo Generación de Etiqueta');
        $pdf->SetSubject('Subtitulo');
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
 
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
 

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
 
        $pdf->setFontSubsetting(true);
        //$pdf->SetFont('freemono', '', 14, '', true);
        $pdf->SetFont('Times', '', 8,'','true');
 
        $pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));
 
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10, 10, 10,true);
       
        $pdf->SetAutoPageBreak(true, 10);



        $pdf->AddPage('P', array( 215.9,  279.4)); //en mm 21.59cm por 27.94cm


        $data['movimientos'] = $this->modelo_pdf->listado_registros($data);

        $data['totales'] = $this->modelo_pdf->totales_entradas($data);


        


          if  ($data['dev'] == 0) {
                $data['etiq_mov'] ="Entrada";
          }
          
          if  ($data['dev'] == 1) {
                $data['etiq_mov'] ="Devolución";
          }

        $dato['id'] = 7; //solo para salida
        $data['configuracion'] = $this->catalogo->coger_configuracion($dato); 
        $html = $this->load->view('pdfs/notas', $data, true);
        


// Imprimimos el texto con writeHTMLCell()
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
 
// ---------------------------------------------------------
// Cerrar el documento PDF y preparamos la salida
// Este método tiene varias opciones, consulte la documentación para más información.
        $nombre_archivo = utf8_decode("Entrada_".$data['id_movimiento'].".pdf");
        $pdf->Output($nombre_archivo, 'I');
    }




   public function generar_etiquetas_rapida($id_movimiento,$dev,$id_factura,$id_estatus) {
        $data['dev']= base64_decode($dev);    
        $data['id_movimiento']= base64_decode($id_movimiento);
        $data['id_factura']= base64_decode($id_factura);
        $data['id_estatus']= base64_decode($id_estatus);

        $data['movimientos'] = $this->modelo_pdf->listado_registros($data);



        $html = $this->load->view('pdfs/etiq_new_rapida', $data, true);
        echo $html;       
    }

 
    public function generar_etiquetas($id_movimiento,$dev,$id_factura,$id_estatus) {
        $data['dev']= base64_decode($dev);    
        $data['id_movimiento']= base64_decode($id_movimiento);
        $data['id_factura']= base64_decode($id_factura);
        $data['id_estatus']= base64_decode($id_estatus);

        /////////////

        set_time_limit(0); 
        ignore_user_abort(1);
        ini_set('memory_limit','512M'); 
        
        $this->load->library('Pdf');
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        //$pdf->SetAuthor('Osmel Calderón');
        $pdf->SetTitle('Titulo Generación de Etiqueta');
        $pdf->SetSubject('Subtitulo');
        //$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
 
// datos por defecto de cabecera, se pueden modificar en el archivo tcpdf_config_alt.php de libraries/config
        //$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 001', PDF_HEADER_STRING, array(0, 64, 255), array(0, 64, 128));
        //$pdf->setFooterData($tc = array(0, 64, 0), $lc = array(0, 64, 128));
 
// datos por defecto de cabecera, se pueden modificar en el archivo tcpdf_config.php de libraries/config
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
 
// se pueden modificar en el archivo tcpdf_config.php de libraries/config
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
 

//relación utilizada para ajustar la conversión de los píxeles
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
 
 
// ---------------------------------------------------------
// establecer el modo de fuente por defecto
        $pdf->setFontSubsetting(true);
 
// Establecer el tipo de letra
 
//Si tienes que imprimir carácteres ASCII estándar, puede utilizar las fuentes básicas como
// Helvetica para reducir el tamaño del archivo.
        $pdf->SetFont('freemono', '', 14, '', true);
 
// Añadir una página
// Este método tiene varias opciones, consulta la documentación para más información.
      //  $pdf->AddPage();
 
//fijar efecto de sombra en el texto
        $pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));
 
// Establecemos el contenido para imprimir
        
        //$provincia = $this->input->post('provincia');
        
        //$data['id_movimiento']='29';


        // remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        //$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT, PDF_MARGIN_BOTTOM);
        // set margins
        $pdf->SetMargins(0, 0, 0);

        // set auto page breaks
        $pdf->SetAutoPageBreak(true, -10);



         $pdf->AddPage('P', array( 100.0,  100.0)); //en mm
         //$pdf->AddPage(); //en mm

        $data['movimientos'] = $this->modelo_pdf->listado_registros($data);



        $html = $this->load->view('pdfs/etiq_new', $data, true);
        

 
// Imprimimos el texto con writeHTMLCell()
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
 
// ---------------------------------------------------------
// Cerrar el documento PDF y preparamos la salida
// Este método tiene varias opciones, consulte la documentación para más información.
        $nombre_archivo = utf8_decode("Entrada_".$data['id_movimiento'].".pdf");
        $pdf->Output($nombre_archivo, 'I');
    }




/////////////////////////Pedidos/////////////////////////////////////


 public function generar_pedido_especifico($num_mov,$id_generar,$id_cliente,$id_almacen,$consecutivo_venta,$id_tipo_pedido,$id_tipo_factura){



        $data['num_mov']= base64_decode($num_mov);
        $data['id_generar']= base64_decode($id_generar);
        $data['id_cliente']= base64_decode($id_cliente);
        $data['id_almacen']= base64_decode($id_almacen);
        $data['consecutivo_venta']= base64_decode($consecutivo_venta);
        $data['id_tipo_pedido'] = base64_decode($id_tipo_pedido);
        $data['id_tipo_factura'] = base64_decode($id_tipo_factura);             

        

        
        $data['id']=$data['id_almacen'];
        if ($data['id']==0){
            $data['almacen'] = 'Todos'; 
        } else {
            $data['almacen'] = $this->catalogo->coger_almacen($data)->almacen;
        }


        
        /////////////

        set_time_limit(0); 
        ignore_user_abort(1);
        ini_set('memory_limit','512M');         
       
        $this->load->library('Pdf');
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('Titulo Generación de Etiqueta');
        $pdf->SetSubject('Subtitulo');
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
 
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
 

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
 
        $pdf->setFontSubsetting(true);
        $pdf->SetFont('freemono', '', 14, '', true);
 
        $pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));
 
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10, 10, 10,true);
        
        $pdf->SetAutoPageBreak(true, 10);


        $pdf->AddPage('P', array( 215.9,  279.4)); //en mm 21.59cm por 27.94cm

        $dato['id'] = 4;
        $data['configuracion'] = $this->catalogo->coger_configuracion($dato);
        //print_r($data['configuracion']);

        switch ($data['id_generar']) { //m.consecutivo m.cantidad_um
            case 1:
                    $data['movimientos'] = $this->modelo_pdf->pedido_especifico_vendedor($data);        
                    $html = $this->load->view('pdfs/pedidos/notas_vendedor', $data, true);

                break;
            case 2:
                    //este es de los vendedores
                    $data['movimientos'] = $this->modelo_pdf->pedido_especifico_tienda($data);        
                     $html = $this->load->view('pdfs/pedidos/notas_tienda', $data, true);
                      
                break;
            case 3:
                    $data['movimientos'] = $this->modelo_pdf->pedido_especifico_completo($data);        
                    $html = $this->load->view('pdfs/pedidos/notas_completo', $data, true);
                break;


                
            default:
                    $data['movimientos'] = $this->modelo_pdf->pedido_especifico_vendedor($data);        
                break;
        }
        

        
        


// Imprimimos el texto con writeHTMLCell()
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
 
// ---------------------------------------------------------
// Cerrar el documento PDF y preparamos la salida
// Este método tiene varias opciones, consulte la documentación para más información.
        $nombre_archivo = utf8_decode("Pedido_".$data['num_mov'].".pdf");
        $pdf->Output($nombre_archivo, 'I');
    }    







}
