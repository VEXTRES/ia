<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;
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

    public function handleResponse($response)
    {
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
