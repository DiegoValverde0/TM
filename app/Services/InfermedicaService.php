<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class InfermedicaService
{
    protected $headers;

    public function __construct()
    {
        $this->headers = [
            'App-Id' => env('INFERMEDICA_APP_ID'),
            'App-Key' => env('INFERMEDICA_APP_KEY'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    public function processSymptoms(Request $request)
    {
        // Validar el input
        $request->validate([
            'symptoms' => 'required|string',
        ]);
    
        // Traducir el texto de los síntomas al inglés (si es necesario)
        $translatedText = GoogleTranslate::trans($request->input('symptoms'), 'en');
    
        // Preparar la carga útil (payload) para la API de Infermedica
        $payload = [
            'text' => $translatedText,
            'include_tokens' => false,
            'language' => 'en',
            'age' => ['value' => 30],
            'sex' => 'female',
        ];
    
        // Llamar a la API de Infermedica
        $response = Http::withHeaders([
            'App-Id' => env('INFERMEDICA_APP_ID'),
            'App-Key' => env('INFERMEDICA_APP_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.infermedica.com/v3/parse', $payload);
    
        // Obtener la respuesta de la API
        $diagnosis = $response->json();
    
        // Verificar si 'mentions' existe en la respuesta
        if (isset($diagnosis['mentions']) && !empty($diagnosis['mentions'])) {
            // Procesar la lista de síntomas
            $symptoms = [];
            foreach ($diagnosis['mentions'] as $mention) {
                // Aquí obtienes información específica de cada síntoma
                $symptoms[] = [
                    'name' => $mention['name'], // Nombre del síntoma
                    'common_name' => $mention['common_name'], // Nombre común del síntoma
                ];
            }
    
            // Traducir los resultados (si es necesario)
            $translatedResult = [];
            foreach ($symptoms as $symptom) {
                $translatedResult[] = [
                    'name' => GoogleTranslate::trans($symptom['name'], 'es'), // Traducir el nombre del síntoma
                    'common_name' => GoogleTranslate::trans($symptom['common_name'], 'es'), // Traducir el nombre común
                ];
            }
    
            // Devolver la vista con los resultados traducidos
            return view('diagnosis.result', ['diagnosis' => $translatedResult]);
        } else {
            // Manejar el caso donde no se reciben menciones
            return back()->with('error', 'No se pudo obtener información de diagnóstico.');
        }
    }
    
    
}
