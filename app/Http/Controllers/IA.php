<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\IaAgent;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;


class IA extends Controller
{
    protected $agent;


    public function index(){
        return view('ia');
    }

    public function __construct(IaAgent $agent){
        $this->agent = $agent;
    }
    public function js(){
        return view('js');
    }


    function preguntarIA(Request $request) {
        $usuario = User::first();
        $pregunta = $request->message;

        if (!$this->agent->tieneHistorial($usuario)) {
            $respuesta = $this->agent->enviarPreguntaAI(["role" => "user", "content" => $pregunta]);
            $respuesta = $respuesta['choices'][0]['message']['content'];

            $this->agent->guardarMensaje($usuario, $pregunta, $respuesta);
            return $respuesta;
        }

        $contexto = $this->agent->recuperarContexto($usuario);
        if ($contexto->count() > 10) {
            $resumen = $this->agent->resumirConversacion($contexto);
            $historial = [
                ["role" => "system", "content" => "Resumen de la conversación: " . $resumen],
                ["role" => "user", "content" => $pregunta]
            ];
        } else {
            // Construir el historial correctamente
            $historial = [];
            foreach ($contexto as $mensaje) {
                $historial[] = ["role" => "user", "content" => $mensaje->mensaje];
                $historial[] = ["role" => "assistant", "content" => $mensaje->respuesta];
            }
        }

        // Agregamos la pregunta actual
        $historial[] = ["role" => "user", "content" => $pregunta];

        // Enviamos la pregunta junto con todo el historial
        $respuesta = $this->agent->enviarPreguntaAI($historial[count($historial)-1], $historial);
        $respuesta = $respuesta['choices'][0]['message']['content'];

        // Guardamos el nuevo mensaje y respuesta
        $this->agent->guardarMensaje($usuario, $pregunta, $respuesta);


        return $respuesta;
    }






    public function queryIA(Request $request){
        $message = $request->input('message');

        // 1️⃣ Llamar a la IA para analizar el mensaje
        $response = $this->agent->handleResponse($message);

        $jsonContent=$response['choices'][0]['message']['content'];
        $jsonContent = trim($jsonContent, "```json\n ");
        $data = json_decode($jsonContent, true);

        // 2️⃣ Si la IA sugiere una acción, ejecutamos la consulta en Laravel
        if (isset($data['response'])) {
            switch ($data['response']) {
                case 'count_users':
                    return response()->json([
                        'message' => "Actualmente hay " . User::count() . " usuarios en el sistema."
                    ]);

                case 'user_high':
                    $highestUser = User::orderBy('id', 'desc')->first();
                    return response()->json([
                        'message' => "El usuario con el ID más alto es {$highestUser->name} (ID: {$highestUser->id})."
                    ]);
            }
        }
        return response()->json([
            'message' => $data['message']
        ]);
    }


}
