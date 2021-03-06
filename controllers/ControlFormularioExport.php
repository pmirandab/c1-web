<?php

/**
 * CONTROLADOR de Export Excel
 * Descripción: 
 * Fecha: 2018-03-05
 * @author EDUARDO PACHECO
 */


class ControlFormularioExport extends Control {

  public function excelFactorEstimado($f3) {
        
        //echo $f3->get('SESSION.COD_TEMPORADA');
       
        $filename=$f3->get('SESSION.COD_TEMPORADA')."-"."Factor Estimado.xls";
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Type: application/vnd.ms-excel");
        $f3->set('Lista_FactorEstimado', \temporada\Factor_Estimado::getFactor_Estimado($f3->get('SESSION.COD_TEMPORADA')));

        $f3->set('contenido', 'reporte/visor_factor_estimado.html');
        echo Template::instance()->render('layout_excel.php');
   
    }

  public function excelPlanDeCompra($f3){

require_once '../PHPExcel/PHPExcel.php';

        $tipoArchivo = $_POST["tipos_export"];
        $depto = $f3->get('SESSION.COD_DEPTO');
        $Tempo = $f3->get('SESSION.COD_TEMPORADA');

        if($tipoArchivo == 3) {//BMT
            $nomtempo = \temporada\temporada::getTemporadaCompra($Tempo)->NOM_TEMPORADA_CORTO;
          $grupo_compra = "";
          foreach ($_POST["check"] as $key => $val){
                $grupo_compra =$grupo_compra.$val .",";
          }
          $grupo_compra = substr($grupo_compra, 0, -1);

          include '../ui/reporte/Excel_Export_BMT.php';

         /* $nomtempo = \temporada\temporada::getTemporadaCompra($Tempo)->NOM_TEMPORADA_CORTO;
          $filename="BMT_".$nomtempo."_".$depto.".xls";
          header("Content-Disposition: attachment; filename=\"$filename\"");

          header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
          $f3->set('Lista_bmt', simulador_compra\plan_compra::ExportBMT($Tempo,$depto,$grupo_compra));
          $f3->set('contenido', 'reporte/visor_exportarBMT.html');
          echo Template::instance()->render('layout_excel.php');*/

      } elseif ($tipoArchivo == 2) { //c1
                    $depto_cadena = "";$count1 = 0;

            foreach ($_POST["check_depto"] as $val => $value) {
                $depto_cadena = $depto_cadena . $value . ",";
                $count1++;
            }
            include '../ui/reporte/cabeceraexcel.php';

            /*$dt =  \simulador_compra\cbx_grilla_compra::llenar_tabla_depto($Tempo);
            if (count($dt) == $count1){

                          $file ="C1_Consolidada_".$Tempo.".xls"; // Decode URL-encoded string
                          $filepath = "../archivos/c1_consolidada/" . $file;

                          // Process download
                          if(file_exists($filepath)) {
                              header('Content-Description: File Transfer');
                              header('Content-Type: application/octet-stream');
                              header('Content-Disposition: attachment; filename="'.$file.'"');
                              header('Expires: 0');
                              header('Cache-Control: must-revalidate');
                              header('Pragma: public');
                              header('Content-Length: ' . filesize($filepath));

                              flush(); // Flush system output buffer
                              readfile($filepath);
                              exit;
                          }
            }else{
               if ($count1 > 5){
                   $array5 =  explode("," ,$depto_cadena);;
                   $count =0; $depto_cadena="";
                   foreach ($array5 as $val){$count++;
                       $depto_cadena = $depto_cadena . $val . ",";
                       if ($count == 5){
                           break;
                       }
                   }
                   include '../ui/reporte/cabeceraexcel.php';
               }else{
                   include '../ui/reporte/cabeceraexcel.php';
               }

            }*/

      } elseif ($tipoArchivo == 4) { //c1 por estado

          $depto_cadena ="";
          foreach($_POST["check_depto_estado"] as $val => $value){
              $depto_cadena = $depto_cadena.$value."," ;
        }

          $estado_cadena ="";
          foreach($_POST["check_estado"] as $val => $value){
              $estado_cadena = $estado_cadena.$value."," ;
          }
          include '../ui/reporte/excel_opcion.php';
      }elseif ($tipoArchivo == 1){

            $depto_cadena ="";
            $depto_cadena_sql ="";
            foreach($_POST["check_depto"] as $val => $value){
                $depto_cadena = $depto_cadena.$value."," ; // Le adjunta una ,
                $reemplazo = str_replace ( ",", "'," , $depto_cadena); // Reemplaza , por ',
                $reemplazo2 = str_replace ( "D", "'D" , $reemplazo); // Reemplaza D por 'D
                $reemplazo3 = substr($reemplazo2,0,-1); // Quita el último caracter
            }

            include '../ui/reporte/excel_asorment.php';

      }

  }




      //$f3->set('contenido', 'ui/formulario/plan_compra/simulador_compra4.html');




//-------------------------------------------------------------------------
  public function beforeRoute($f3) {

        if ($f3->exists('SESSION.login') == false) {
            $f3->reroute('/fin-sesion');
        }
    }

    
}
