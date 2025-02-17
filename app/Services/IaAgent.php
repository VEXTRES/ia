<?php

namespace App\Services;

use App\Models\Conversacion;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class IaAgent
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'http://127.0.0.1:1234/v1/', // LM Studio
            'timeout'  => 1000.0,
        ]);
    }


    function enviarPreguntaAI($pregunta,$historial=[]) {

        if(count($historial)==0){
            $historial[]=$pregunta;
        }

            $response = $this->client->post('chat/completions', [
                'json' => [
                    'model' => 'deepseek-coder-v2-lite-instruct',
                    'messages' => $historial,
                    'temperature' => 0.7,
                    'max_tokens' => 200
                ]
            ]);

        return json_decode($response->getBody()->getContents(), true);
    }


    // 📌 Guardar mensaje en la base de datos
    function guardarMensaje($usuario, $pregunta, $respuesta) {
        Conversacion::create([
            'usuario_id' => $usuario->id,
            'mensaje' => $pregunta,
            'respuesta' => $respuesta
        ]);
    }

    // 📌 Recuperar el contexto de la conversación
    function recuperarContexto($usuario) {
        return Conversacion::where('usuario_id', $usuario->id)
            ->orderBy('created_at', 'desc') // Tomamos los más recientes primero
            ->take(10) // Últimos 10 mensajes
            ->get()
            ->sortBy('created_at'); // Luego los ordenamos en orden correcto
    }


    // 📌 Verificar si el usuario tiene historial
    function tieneHistorial($usuario) {
        return Conversacion::where('usuario_id', $usuario->id)->exists();
    }

    // 📌 Resumir la conversación si es demasiado grande
    function resumirConversacion($contexto) {
        $resumen = "Aquí está el resumen de la conversación previa:\n";

        foreach ($contexto as $m) {
            $resumen .= "Usuario: {$m->mensaje}\n";
            $resumen .= "IA: {$m->respuesta}\n";
        }

        // Si es muy largo, podemos recortarlo más
        return substr($resumen, 0, 1000); // Recortar a 1000 caracteres máximo
    }


    public function handleResponse($response){
        $prompt = "Eres un asistente inteligente para un sistema en Laravel.
        Si el usuario hace una pregunta referente a usuarios de la base de datos ahora si la pregunta NO está relacionada con la base de datos, responde normal y la respuesta me la das en JSON.

        Ejemplos:

        Usuario: '¿Cuántos usuarios hay?'
        Tú respondes:count_users

        Usuario: '¿Quién es el usuario con el ID más alto?'
        Tú respondes:user_high";

        $question = "\nUsuario: " . $response;

        $response = $this->client->post('chat/completions', [
                    'json' => [
                        'model' => 'deepseek-coder-v2-lite-instruct',
                        'messages' => [
                            ['role' => 'system', 'content' => $prompt],
                            ['role' => 'user', 'content' => $question]
                        ],
                        'temperature' => 0.7,
                        'max_tokens' => 200
                    ]
                ]);



        return json_decode($response->getBody()->getContents(), true);

    }
}
