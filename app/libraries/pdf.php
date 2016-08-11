<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* 
 * http://uno-de-piera.com/creacion-de-pdf-con-codeigniter-la-libreria-tcpdf/
 * http://uno-de-piera.com/la-libreria-dompdf-para-codeigniter/
 *
 * http://sourceforge.net/projects/tcpdf/files/
 * http://sourceforge.net/projects/tcpdf/?source=typ_redirect
 *  
 *  ======================================= 
 */  

require_once __DIR__."/tcpdf/tcpdf.php"; 

class Pdf extends TCPDF {
    public function __construct() { 
        parent::__construct(); 
    } 
}