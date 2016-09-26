<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
 
class Pdf_reportes extends CI_Controller {
 
    function __construct() {
        parent::__construct();
        $this->load->model('informes_model','informes_model');
        $this->load->model('catalogo', 'catalogo');  
    }
    

    public function imprimir_totales() {

        $data=$_POST;

        $data['movimientos'] = $this->informes_model->buscador_consulta_totales($data);


        $data['totales'] = $this->informes_model->totales_consulta_totales($data);        


        $html = $this->load->view('pdfs/informes/totales', $data, true);


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

        //http://www.tcpdf.org/fonts.php
        //$pdf->SetFont('freemono', '', 14, '', true);
        $pdf->SetFont('freemono', '', 11, '', 'true');

 
        $pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));
 
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10, 10, 10,true);
        
        $pdf->SetAutoPageBreak(true, 10);

        $pdf->AddPage('P', array( 215.9,  279.4)); //en mm 21.59cm por 27.94cm



        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
        $nombre_archivo = utf8_decode("informe".".pdf");
        $pdf->Output($nombre_archivo, 'I');
        
    }


    public function imprimir_reportes() {
        
        $extra_search = ($this->input->post('extra_search'));

        $data=$_POST;

       $dato['id'] = 7;
       $data['configuracion'] = $this->catalogo->coger_configuracion($dato); 

        switch($extra_search) {

            case "reportes_costo":
                $data['movimientos'] = $this->informes_model->informe_reportes_costo($data);
                $data['totales'] = $this->informes_model->total_reportes_costo($data);        
                $html = $this->load->view('pdfs/informes/costos', $data, true);
                 
                break;

            case "entrada":
                $data['movimientos'] = $this->informes_model->buscador_entrada_devolucion($data);
                $data['totales'] = $this->informes_model->totales_entrada_devolucion($data);        
                $html = $this->load->view('pdfs/informes/entrada', $data, true);
                 
                break;

            case "devolucion":
                $data['movimientos'] = $this->informes_model->buscador_entrada_devolucion($data);
                $data['totales'] = $this->informes_model->totales_entrada_devolucion($data);        
                $html = $this->load->view('pdfs/informes/devolucion', $data, true);
                 
                break;

                /////

            case "salida":
                $data['movimientos'] = $this->informes_model->salida_home($data);
                $data['totales'] = $this->informes_model->totales_salidas($data);        

                $html = $this->load->view('pdfs/informes/salida', $data, true);
                 
                break;

            case "existencia":
                $data['movimientos'] = $this->informes_model->entrada_home($data);
                $data['totales'] = $this->informes_model->totales_entradas($data);        

                $html = $this->load->view('pdfs/informes/existencia', $data, true);
                break;
            case "apartado":
                $data['movimientos'] = $this->informes_model->entrada_home($data);
                $data['totales'] = $this->informes_model->totales_entradas($data);        

                $html = $this->load->view('pdfs/informes/apartado', $data, true);
                break;


            case 'baja':
                $data['movimientos']= $this->informes_model->buscador_cero_baja($data);
                $html = $this->load->view('pdfs/informes/baja', $data, true);
               break;

            case 'cero':
                $data['movimientos']= $this->informes_model->buscador_cero_baja($data);
                $html = $this->load->view('pdfs/informes/cero', $data, true);
               break;

            case 'top':
               $data['movimientos'] = $this->informes_model->buscador_top($data);
                $html = $this->load->view('pdfs/informes/top', $data, true);
               break;

            default:
        }

        /////////////



        set_time_limit(0); 
        ignore_user_abort(1);
        ini_set('memory_limit','1024M'); 


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

        //http://www.tcpdf.org/fonts.php
        //$pdf->SetFont('freemono', '', 14, '', true);
        //$pdf->SetFont('freemono', '', 11, '', 'true');
        $pdf->SetFont('Times', '', 8,'','true');

 
        $pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));
 
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10, 10, 10,true);
        
        $pdf->SetAutoPageBreak(true, 10);

        $pdf->AddPage('P', array( 215.9,  279.4)); //en mm 21.59cm por 27.94cm



        
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
        $nombre_archivo = utf8_decode("informe".".pdf");
        $pdf->Output($nombre_archivo, 'I');
    }








}
