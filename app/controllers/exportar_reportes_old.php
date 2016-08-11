<?php
 
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
 
class Exportar_reportes extends CI_Controller {
 
    function __construct() {
        parent::__construct();
        $this->load->model('exportar_model','exportar_model');
    }
    


  public function exportar_totales()  {
        $this->load->library('export');

        $data=$_POST;


        $data['movimientos'] = $this->exportar_model->buscador_consulta_totales($data);
        if ($data['movimientos']) {
            $this->export->to_excel($data['movimientos'], 'reporte_totales_'.date("Y-m-d_H-i-s")); 
        }    




    }





    public function exportar()  {
        $this->load->library('export');



        $extra_search = ($this->input->post('extra_search'));

        $data=$_POST;

        ////1- verificar si   $data['movimientos'] no esta vacio
        // 2- ordenar por factura
        switch($extra_search) {

            case "entrada":
                $data['movimientos'] = $this->exportar_model->exportar_entrada_devolucion($data);
                if ($data['movimientos']) {
                    $this->export->to_excel($data['movimientos'], 'reporte_entrada_'.date("Y-m-d_H-i-s")); 
                }    
                break;


            case "devolucion":
                $data['movimientos'] = $this->exportar_model->exportar_entrada_devolucion($data);
                if ($data['movimientos']) {
                    $this->export->to_excel($data['movimientos'], 'reporte_devolucion_'.date("Y-m-d_H-i-s")); 
                }    
                break;

              ////////////////                  


            case "salida":
                $data['movimientos'] = $this->exportar_model->salida_home($data);
                if ($data['movimientos']) {
                    $this->export->to_excel($data['movimientos'], 'reporte_salida_'.date("Y-m-d_H-i-s")); 
                }    
                 
                break;

            case "existencia":
                $data['movimientos'] = $this->exportar_model->entrada_home($data);
                if ($data['movimientos']) {
                    $this->export->to_excel($data['movimientos'], 'reporte_existencia_'.date("Y-m-d_H-i-s")); 
                }    
                break;
            case "apartado":
                $data['movimientos'] = $this->exportar_model->entrada_home($data);

                if ($data['movimientos']) {
                    $this->export->to_excel($data['movimientos'], 'reporte_apartado_'.date("Y-m-d_H-i-s")); 
                }

                break;


            case 'baja':
                $data['movimientos']= $this->exportar_model->buscador_cero_baja($data);
                if ($data['movimientos']) {
                    $this->export->to_excel($data['movimientos'], 'reporte_baja_'.date("Y-m-d_H-i-s")); 
                }    
               break;

            case 'cero':
                $data['movimientos']= $this->exportar_model->buscador_cero_baja($data);
                if ($data['movimientos']) {
                    $this->export->to_excel($data['movimientos'], 'reporte_cero_'.date("Y-m-d_H-i-s")); 
                }    
               break;

            case 'top':
               $data['movimientos'] = $this->exportar_model->buscador_top($data);
                if ($data['movimientos']) {
                    $this->export->to_excel($data['movimientos'], 'reporte_top_'.date("Y-m-d_H-i-s")); 
                }    
               break;

            default:
        }




    }






}