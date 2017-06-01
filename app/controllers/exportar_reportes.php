<?php
 
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
 
class Exportar_reportes extends CI_Controller {
 
    function __construct() {
        parent::__construct();
        $this->load->model('exportar_model','exportar_model');
        $this->load->model('catalogo', 'catalogo');  

    }
    


  public function exportar_totales()  {
        $this->load->library('export');

        $data=$_POST;

        $nombre_completo = $this->session->userdata('nombre_completo');

        $data['movimientos'] = $this->exportar_model->buscador_consulta_totales($data);
        if ($data['movimientos']) {
            $this->export->to_excel($data['movimientos'], 'reporte_totales_ALM'.$data['id_almacen'].'_'.date("Y-m-d_H-i-s").'-'.$nombre_completo);
        }    

    }





    public function exportar()  {
        $this->load->library('export');

        $extra_search = ($this->input->post('extra_search'));

        $data=$_POST;
        $dato['id'] = 7; //ok porq es para entrada y devolucion
        $data['configuracion'] = $this->catalogo->coger_configuracion($dato); 


        $nombre_completo=$this->session->userdata('nombre_completo');

        ////1- verificar si   $data['movimientos'] no esta vacio
        // 2- ordenar por factura
        switch($extra_search) {



            case "reportes_costo":
                $data['movimientos'] = $this->exportar_model->exportar_reportes_costo($data);
                if ($data['movimientos']) {
                    $this->export->to_excel($data['movimientos'], 'reporte_costo_'.date("Y-m-d_H-i-s").'-'.$nombre_completo);
                }    
                break;

            case "entrada": //
                $data['movimientos'] = $this->exportar_model->exportar_entrada_devolucion($data);
                if ($data['movimientos']) {
                    $this->export->to_excel($data['movimientos'], 'reporte_entrada_'.date("Y-m-d_H-i-s").'-'.$nombre_completo);
                }    
                break;


            case "devolucion": //
                $data['movimientos'] = $this->exportar_model->exportar_entrada_devolucion($data);
                if ($data['movimientos']) {
                    $this->export->to_excel($data['movimientos'], 'reporte_devolucion_'.date("Y-m-d_H-i-s").'-'.$nombre_completo);
                }    
                break;

              ////////////////    
                        


            case "salida": //

                $dato['id'] = 10; //ok porq es solo para salida
                $data['configuracion'] = $this->catalogo->coger_configuracion($dato); 

                $data['movimientos'] = $this->exportar_model->salida_home($data);
                if ($data['movimientos']) {
                    $this->export->to_excel($data['movimientos'], 'reporte_salida_'.date("Y-m-d_H-i-s").'-'.$nombre_completo);
                }    
                 
                break;

            case "existencia": //
                $data['movimientos'] = $this->exportar_model->entrada_home($data);
                if ($data['movimientos']) {
                    $this->export->to_excel($data['movimientos'], 'reporte_existencia_'.date("Y-m-d_H-i-s").'-'.$nombre_completo);
                }    
                break;
            case "apartado": //
                $data['movimientos'] = $this->exportar_model->entrada_home($data);

                if ($data['movimientos']) {
                    $this->export->to_excel($data['movimientos'], 'reporte_apartado_'.date("Y-m-d_H-i-s").'-'.$nombre_completo);
                }

                break;


            case 'baja': //*precio
                $data['movimientos']= $this->exportar_model->buscador_cero_baja($data);
                if ($data['movimientos']) {
                    $this->export->to_excel($data['movimientos'], 'reporte_baja_'.date("Y-m-d_H-i-s").'-'.$nombre_completo);
                }    
               break;

            case 'cero': //*precio
                $data['movimientos']= $this->exportar_model->buscador_cero_baja($data);
                if ($data['movimientos']) {
                    $this->export->to_excel($data['movimientos'], 'reporte_cero_'.date("Y-m-d_H-i-s").'-'.$nombre_completo);
                }    
               break;

            case 'top': //*precio
               $data['movimientos'] = $this->exportar_model->buscador_top($data);
                if ($data['movimientos']) {
                    $this->export->to_excel($data['movimientos'], 'reporte_top_'.date("Y-m-d_H-i-s").'-'.$nombre_completo);
                }    
               break;

            default:
        }

    }

}