<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\IaAgent;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class IA extends Controller
{
    protected $agent;


    public function index(){
        return view('ia');
    }

    public function __construct(IaAgent $agent){
        $this->agent = $agent;
    }


    public function queryIA(Request $request)
    {
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

// $response = $this->agent->ask($request->message);
}
