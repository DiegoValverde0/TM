<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Support\Facades\Http;
use App\Models\TriageEntry;
use App\Models\Patient;



class DiagnosisController extends Controller
{
    public function showForm()
    {
        return view('diagnosis.form');
    }

    public function requestMedicalAttention(Request $request)
    {
        $request->validate([
            'symptoms' => 'required|string',
        ]);
    
        $user = $request->user();
        $patient = $user->patient;
    
        if (!$patient) {
            return redirect()->route('diagnosis')->with('error', 'Paciente no encontrado.');
        }
    
        try {
            $triage = new TriageEntry();
            $triage->patient_id = $patient->id;
            $triage->nurse_id = $user->id;
            $triage->symptoms = $request->input('symptoms'); // Este debe ser un string limpio
            $triage->priority = 'blue';
            $triage->save();
    
            return redirect()->route('triage_entries.index')->with('success', 'Triage creado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('diagnosis')->with('error', 'Error al crear el triage: ' . $e->getMessage());
        }
    }


    public function processSymptoms(Request $request)
    {
        $request->validate([
            'symptoms' => 'required|string',
        ]);

        // Traducir los síntomas al inglés
        $translatedText = GoogleTranslate::trans($request->input('symptoms'), 'en');

        // Preparar payload para /parse
        $payload = [
            'text' => $translatedText,
            'include_tokens' => false,
            'language' => 'en',
            'age' => ['value' => 30],
            'sex' => 'female',
        ];

        $headers = [
            'App-Id' => env('INFERMEDICA_APP_ID'),
            'App-Key' => env('INFERMEDICA_APP_KEY'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        // Llamar a /parse para extraer menciones
        $parseResponse = Http::withHeaders($headers)
            ->post('https://api.infermedica.com/v3/parse', $payload);

        $parseData = $parseResponse->json();

        if (empty($parseData['mentions'])) {
            return back()->with('error', 'No se identificaron síntomas.');
        }

        // Construir evidencia para /recommend_specialist
        $evidence = [];
        foreach ($parseData['mentions'] as $mention) {
            $evidence[] = [
                'id' => $mention['id'],
                'choice_id' => 'present',
            ];
        }

        // Llamar a /recommend_specialist
        $specialistResponse = Http::withHeaders($headers)
            ->post('https://api.infermedica.com/v3/recommend_specialist', [
                'sex' => 'female',
                'age' => ['value' => 30],
                'evidence' => $evidence,
            ]);

        $specialistData = $specialistResponse->json();

        // Traducir los síntomas identificados
        $translatedSymptoms = [];
        foreach ($parseData['mentions'] as $mention) {
            $translatedSymptoms[] = [
                'name' => GoogleTranslate::trans($mention['name'], 'es'),
                'common_name' => GoogleTranslate::trans($mention['common_name'], 'es'),
            ];
        }

        $specialtySymptomsMap = [
            // Cardiólogo (problemas cardiovasculares)
            'cardiólogo' => [
                'dolor en el pecho', 
                'taquicardia', 
                'palpitaciones', 
                'falta de aire', 
                'presión en el pecho', 
                'hipertensión'
            ],
            
            // Ginecólogo (problemas ginecológicos)
            'ginecólogo' => [
                'dolor pélvico', 
                'sangrado menstrual irregular', 
                'flujo vaginal', 
                'dolor durante el sexo', 
                'síntomas premenstruales', 
                'ardor vulvovaginal', 
                'sangrado uterino anormal'
            ],
        
            // Gastroenterólogo (problemas digestivos)
            'gastroenterólogo' => [
                'dolor abdominal', 
                'diarrea', 
                'estreñimiento', 
                'náuseas', 
                'reflujo ácido', 
                'vómito', 
                'heces con sangre'
            ],
        
            // Neurólogo (problemas neurológicos)
            'neurólogo' => [
                'mareo', 
                'desmayo', 
                'pérdida de equilibrio', 
                'dolor de cabeza', 
                'convulsiones', 
                'temblores', 
                'visión doble'
            ],
        
            // Dermatólogo (problemas de piel)
            'dermatólogo' => [
                'picazón en la piel', 
                'erupción cutánea', 
                'acné', 
                'manchas en la piel', 
                'enrojecimiento', 
                'eczema'
            ],
        
            // Endocrinólogo (problemas hormonales)
            'endocrinólogo' => [
                'aumento de peso', 
                'pérdida de peso', 
                'fatiga', 
                'diabetes', 
                'hipotiroidismo', 
                'hipertiroidismo', 
                'sed excesiva'
            ],
        
            // Hematólogo (problemas sanguíneos)
            'hematólogo' => [
                'anemia', 
                'sangrado excesivo', 
                'hematomas frecuentes', 
                'fatiga persistente', 
                'plaquetas bajas'
            ],
        
            // Oncólogo (problemas oncológicos)
            'oncólogo' => [
                'masa en el cuerpo', 
                'pérdida de peso inexplicada', 
                'cansancio crónico', 
                'dolor persistente', 
                'sudoración nocturna'
            ],
        
            // Reumatólogo (problemas articulares)
            'reumatólogo' => [
                'dolor articular', 
                'rigidez matutina', 
                'hinchazón articular', 
                'fatiga', 
                'lupus', 
                'artritis'
            ],
        
            // Otorrinolaringólogo (problemas de oído, nariz y garganta)
            'otorrinolaringólogo' => [
                'dolor de oído', 
                'zumbido', 
                'dolor de garganta', 
                'dificultad para tragar', 
                'sinusitis', 
                'sordera parcial'
            ],
        
            // Oftalmólogo (problemas visuales)
            'oftalmólogo' => [
                'visión borrosa', 
                'dolor ocular', 
                'ojo seco', 
                'pérdida de visión', 
                'ojos rojos', 
                'sensibilidad a la luz'
            ],
        
            // Psiquiatra (problemas mentales graves)
            'psiquiatra' => [
                'ansiedad', 
                'depresión', 
                'insomnio', 
                'pensamientos suicidas', 
                'alucinaciones', 
                'trastornos del estado de ánimo'
            ],
        
            // Psicólogo (problemas emocionales y psicológicos leves)
            'psicólogo' => [
                'estrés', 
                'ansiedad leve', 
                'problemas de concentración', 
                'problemas familiares', 
                'baja autoestima'
            ],
        
            // Infectólogo (infecciones)
            'infectólogo' => [
                'fiebre', 
                'infección recurrente', 
                'infección de transmisión sexual', 
                'dolor de garganta persistente'
            ],
        
            // Nefrólogo (problemas renales)
            'nefrólogo' => [
                'dolor en los riñones', 
                'orina espumosa', 
                'edema', 
                'presión alta', 
                'fallo renal', 
                'infección urinaria recurrente'
            ],
        
            // Urólogo (problemas urinarios y prostáticos)
            'urólogo' => [
                'dolor al orinar', 
                'sangre en la orina', 
                'micción frecuente', 
                'incontinencia', 
                'problemas prostáticos'
            ],
        
            // Proctólogo (problemas anales)
            'proctólogo' => [
                'dolor anal', 
                'sangrado rectal', 
                'hemorroides', 
                'fisuras anales'
            ],
        
            // Neonatólogo (problemas en recién nacidos)
            'neonatólogo' => [
                'ictericia en recién nacidos', 
                'bajo peso al nacer', 
                'problemas respiratorios neonatales'
            ],
        
            // Pediatra (problemas infantiles generales)
            'pediatra' => [
                'fiebre infantil', 
                'tos en niños', 
                'diarrea infantil', 
                'dolor de oídos en niños'
            ],
        
            // Geriatra (problemas geriátricos)
            'geriatra' => [
                'pérdida de memoria', 
                'caídas frecuentes', 
                'debilidad generalizada', 
                'incontinencia', 
                'polifarmacia'
            ],
        
            // Neurocirujano (cirugía cerebral y neurológica)
            'neurocirujano' => [
                'tumor cerebral', 
                'hernia discal', 
                'trauma craneal', 
                'presión intracraneal elevada'
            ],
        
            // Angiólogo (problemas circulatorios)
            'angiológo' => [
                'dolor en piernas', 
                'varices', 
                'trombosis venosa profunda', 
                'edema unilateral'
            ],
        
            // Fisiatra (rehabilitación física)
            'fisiatra' => [
                'rehabilitación física', 
                'dolor post operatorio', 
                'discapacidad física'
            ],
        
            // Nutriólogo (problemas alimenticios y nutricionales)
            'nutriólogo' => [
                'sobrepeso', 
                'bajo peso', 
                'trastornos alimenticios', 
                'nutrición deportiva', 
                'obesidad'
            ],
        
            // Medicina interna (problemas generales y crónicos)
            'medicina interna' => [
                'síntomas múltiples', 
                'diagnóstico complejo', 
                'enfermedades crónicas'
            ],
        
            // Medicina general (consultas generales y rutinarias)
            'medicina general' => [
                'síntomas inespecíficos', 
                'malestar general', 
                'control general', 
                'chequeo de rutina'
            ],
        
            // Medicina familiar (atención médica integral)
            'medicina familiar' => [
                'seguimiento de enfermedades', 
                'vacunas', 
                'atención familiar completa'
            ]
        ];
        

        // Buscar las especialidades coincidentes con los síntomas
        $matchedSpecialties = [];

        // Comprobamos todos los síntomas mapeados y los comparamos con los síntomas identificados
        foreach ($specialtySymptomsMap as $specialty => $symptomsList) {
            foreach ($translatedSymptoms as $translatedSymptom) {
                foreach ($symptomsList as $mappedSymptom) {
                    if (str_contains(strtolower($translatedSymptom['name']), strtolower($mappedSymptom))) {
                        if (!isset($matchedSpecialties[$specialty])) {
                            $matchedSpecialties[$specialty] = 0;
                        }
                        $matchedSpecialties[$specialty]++;
                    }
                }
            }
        }

        // Si encontramos especialidades coincidentes, las ordenamos
        arsort($matchedSpecialties);

        // Recolectar todas las especialidades que tengan coincidencias
        $priorityRecommendations = [];
        foreach ($matchedSpecialties as $specialty => $matches) {
            if ($matches > 0) {
                $priorityRecommendations[] = $specialty;
            }
        }

        // Si no se encontró ninguna especialidad relevante, recomendar médico general
        if (empty($priorityRecommendations)) {
            $priorityRecommendations[] = 'Médico general'; 
        }

        // Devolver las recomendaciones finales
        return view('diagnosis.result', [
            'diagnosis' => $translatedSymptoms,
            'specialist' => implode(', ', $priorityRecommendations),
        ]);
    }
}
