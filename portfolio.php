<?php

class portfolio
{
    public  $date;
    public  $startDate;
    public  $endDate;
    public  $acciones;

    public function __construct()
    {
      
        $this->acciones = json_decode(file_get_contents("acciones.json"), true);       
    }

 

    public function getDateRange($startDate, $endDate){    
        $rangArray = [];
        $startDate = strtotime($startDate);
        $endDate = strtotime($endDate);
        for ($currentDate = $startDate; $currentDate <= $endDate; $currentDate += (86400)) {
            $date = date('Y-m-d', $currentDate);
            $rangArray[] = $date;
        }
        return $rangArray;
    }

    private function getUtilidad( $gEmpresa, $aCirculacion, $aCompradas) {
        return $gEmpresa/$aCirculacion*$aCompradas;
    }

  
    public function getAccion($date){
      
        foreach($this->acciones as $key => $acciones) {
            if( array_search($date, $acciones) ){                                
            $accion =  $acciones['empresa'].'- con un valor por accion de '. $acciones['valor'].'USD';                                  
            }
         }  
         return $accion;
    }

    public function getProfit($startDate, $endDate, $isAnual = false){

       $rango = $this->getDateRange($startDate, $endDate);    
       $detalle= array();
 
        if($isAnual === false){
            foreach($rango as $fecha){            
                    foreach($this->acciones as $key => $acciones) {
                        if( array_search($fecha, $acciones) ){                                        
                            $reporte = array( 
                                                    'Empresa' => $acciones['empresa'],
                                                    'FechaCompra' => $acciones['dateCompra'],
                                                    'Ganancias' => $this->getUtilidad( $acciones['gananciasEmpresa'], $acciones['accionesCirculacion'], $acciones['accionesCompradas'])
                            );                              
                            array_push($detalle, $reporte);                        
                        }

                    }  
                                    
            }

             foreach($detalle as $data){
                  
                     echo  'Empresa >'. $data['Empresa'].' <br>Fecha de Compra : '.$data['FechaCompra'].'<br>Utilidades > $'.$data['Ganancias'].' USD<br>';
                 
             };

        }
        else {
            foreach($this->acciones as $key => $acciones) {                                               
                    $reporte = $this->getUtilidad( $acciones['gananciasEmpresa'], $acciones['accionesCirculacion'], $acciones['accionesCompradas']);
                                 
                    array_push($detalle, $reporte);                                    
            }      
          return  'Tu utilidad ANUAL es de '. array_sum($detalle). 'USD';
        }

       

      
     

    }
}


$portfolio = new portfolio();


echo '<table border="1" > <tr> 
<td>Enterprise</td>
<td>E.Profit</td>
<td>C.Supply</td>
<td>Action Value</td>
<td>Bought in</td></tr>';

foreach($portfolio->acciones as $data){
    echo '<tr>
            <td>'.$data['empresa'].'</td>
            <td> $'.$data['gananciasEmpresa'].'</td>
            <td>'.$data['accionesCirculacion'].'</td>
            <td> $'.$data['valor'].' USD</td>
            <td>'.$data['dateCompra'].'</td></tr>';

}


echo '</table>';

/**
 * Get the profit of the portfolio between 2 dates...
 * 3rd Parameter is optional, true if want ANUAL PROFIT report, False to just use BETWEEN DATES
 */
$profit = $portfolio -> getProfit('2020-01-01', '2020-05-05', false);

$profit;


echo '<br>';

/**
 * Get the action price using a date...
 * The dates avaiable in example record json are ... 
 * [ 2020-01-01, 2020-02-01, 2020-03-01, 2020-04-01 ,2020-05-01 ]
*/

$date = '2020-02-01';

$rango = $portfolio -> getAccion($date);
echo 'Se encontro la acción de '. $rango .' con la fecha de compra el '.$date;











 