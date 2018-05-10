<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use App\UsuarioSunat;

class ObligadoController extends Controller{

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
          echo "ruc=>".$valor['ruc']." Razón Social=>".$usuarioSunat->razon."<br>";
        }
      }
      
  
    });
    
  

    

  }
  


}
