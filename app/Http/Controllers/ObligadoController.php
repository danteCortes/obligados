<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use App\UsuarioSunat;
use DB;

class ObligadoController extends Controller{

  public $obligados = [];

  public function inicio(){
    return view('subirExcel');
  }

  public function buscar(Request $request){

    $archivo = $request->file('obligados')->storeAs('obligados', 'obligados.xlsx', 'archivos');

    \Excel::load('public/archivos/obligados/obligados.xlsx', function($reader) {

      $results = $reader->get();
      foreach ($results as $clave => $valor) {
        $ruc = $valor['ruc'];
        $usuarioSunat = UsuarioSunat::where('ruc', $ruc)->first();
        if($usuarioSunat){
          if ($usuarioSunat->ubigeo != "-" && $usuarioSunat->ubigeo != "--" && $usuarioSunat->ubigeo != "---" && $usuarioSunat->ubigeo != "----") {
            $dpto = substr($usuarioSunat->ubigeo, '0', 2);
            if($dpto == '10'){
              $razon_social = $usuarioSunat->razon;
              $direccion = $this->direccion($usuarioSunat);
              $array = ['ruc'=>$ruc, 'razon'=>$razon_social, 'direccion'=>$direccion];
              $this->agregarUsuario($array);
            }
          }
        }
      }
    });
    
    \Excel::create('obligados-huanuco', function($excel) {
      $excel->setTitle('Obligados de la ciudad de Huánuco');
  
      $excel->setCreator('Sipromsa')
        ->setCompany('Siprom sac');

      $excel->setDescription('A demonstration to change the file properties');

      $excel->sheet('Hoja 1', function($sheet){
        $sheet->fromArray($this->obligados);
      });
    })->download('xlsx');
  }

  private function direccion($usuarioSunat){
    $direccion = "";
    if ($usuarioSunat->tipo_via != "-" && $usuarioSunat->tipo_via != "--" && $usuarioSunat->tipo_via != "---" && $usuarioSunat->tipo_via != "----") {
      $direccion .= $usuarioSunat->tipo_via." ";
    }
    if ($usuarioSunat['nombre_via'] != "-" && $usuarioSunat['nombre_via'] != "--" && $usuarioSunat['nombre_via'] != "---" && $usuarioSunat['nombre_via'] != "----") {
      $direccion .= $usuarioSunat['nombre_via']." ";
    }
    if ($usuarioSunat['numero'] != "-" && $usuarioSunat['numero'] != "--" && $usuarioSunat['numero'] != "---" && $usuarioSunat['numero'] != "----") {
      $direccion .= "NRO. ".$usuarioSunat['numero']." ";
    }
    if ($usuarioSunat['interior'] != "-" && $usuarioSunat['interior'] != "--" && $usuarioSunat['interior'] != "---" && $usuarioSunat['interior'] != "----") {
      $direccion .= "INT. ".$usuarioSunat['interior']." ";
    }
    if ($usuarioSunat['zona'] != "-" && $usuarioSunat['zona'] != "--" && $usuarioSunat['zona'] != "---" && $usuarioSunat['zona'] != "----") {
      $direccion .= $usuarioSunat['zona']." ";
    }
    if ($usuarioSunat['tipo_zona'] != "-" && $usuarioSunat['tipo_zona'] != "--" && $usuarioSunat['tipo_zona'] != "---" && $usuarioSunat['tipo_zona'] != "----") {
      $direccion .= $usuarioSunat['tipo_zona']." ";
    }
    if ($usuarioSunat['manzana'] != "-" && $usuarioSunat['manzana'] != "--" && $usuarioSunat['manzana'] != "---" && $usuarioSunat['manzana'] != "----") {
      $direccion .= "MZ. ".$usuarioSunat['manzana']." ";
    }
    if ($usuarioSunat['lote'] != "-" && $usuarioSunat['lote'] != "--" && $usuarioSunat['lote'] != "---" && $usuarioSunat['lote'] != "----") {
      $direccion .= "LTE. ".$usuarioSunat['lote']." ";
    }
    if (trim($usuarioSunat['departamento']) != "-" && $usuarioSunat['departamento'] != "--" && $usuarioSunat['departamento'] != "---" && $usuarioSunat['departamento'] != "----") {
      $direccion .= "DPTO. ".$usuarioSunat['departamento']." ";
    }
    if ($usuarioSunat['kilometro'] != "-" && $usuarioSunat['kilometro'] != "--" && $usuarioSunat['kilometro'] != "---" && $usuarioSunat['kilometro'] != "----") {
      $direccion .= "KM. ".$usuarioSunat['kilometro']." ";
    }return $direccion;
    if ($usuarioSunat['ubigeo'] != "-" && $usuarioSunat['ubigeo'] != "--" && $usuarioSunat['ubigeo'] != "---" && $usuarioSunat['ubigeo'] != "----") {
      $dpto = substr($usuarioSunat['ubigeo'], '0', 2);
      $prov = substr($usuarioSunat['ubigeo'], '2', 2);
      $dist = substr($usuarioSunat['ubigeo'], '4', 2);
      $dist = DB::table('webs_ubigeo')->where('CodDpto', '=', $dpto)->where('CodProv', '=', $prov)->where('CodDist', '=', $dist)->first();
      $prov = DB::table('webs_ubigeo')->where('CodDpto', '=', $dpto)->where('CodProv', '=', $prov)->where('CodDist', '=', '0')->first();
      $dpto = DB::table('webs_ubigeo')->where('CodDpto', '=', $dpto)->where('CodProv', '=', '0')->where('CodDist', '=', '0')->first();
      $direccion .= $dist->Nombre." - ".$prov->Nombre." - ".$dpto->Nombre;
    }
    return $direccion;
  }

  private function agregarUsuario(array $array){
    array_push($this->obligados, $array);
  }
  


}
