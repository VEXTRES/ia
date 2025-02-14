<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use Illuminate\Http\Request;

class AlumnoController extends Controller
{

    public function index(){
        $alumnos=Alumno::all();
        return view('alumnos',compact('alumnos'));
    }

    public function store(Request $request){
        $nombre=$request->name;
        $email=$request->email;
        $fecha_nacimiento=$request->fecha_nacimiento;


        $alumno=Alumno::create([
            'name'=>$nombre,
            'email'=>$email,
            'fecha_nacimiento'=>$fecha_nacimiento
        ]);
        return redirect()->route('alumnos');

    }
    public function destroy($id){
       Alumno::destroy($id);
       return redirect()->route('alumnos');
    }




}
