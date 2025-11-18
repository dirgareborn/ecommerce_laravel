<?php

use App\Http\Controllers\Front\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/ai-chat', function(Request $request) {
    $message = $request->input('message', '');

    // Prompt system bahasa Indonesia
    $system_prompt = "Kamu adalah asisten help desk BPB UNM berbahasa Indonesia.";

    // Payload OpenAI
    $payload = [
        'model' => 'gpt-4.1-mini', // gunakan model stabil
        'input' => [
            ['role' => 'system', 'content' => $system_prompt],
            ['role' => 'user', 'content' => $message]
        ],
        'store' => true
    ];

    // Inisialisasi cURL
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://api.openai.com/v1/responses',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . trim(env('OPENAI_API_KEY')),
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    $reply = 'Maaf, tidak ada jawaban dari AI.';

    if(!$err) {
        $data = json_decode($response, true);

        if(isset($data['output'][0]['content']) && is_array($data['output'][0]['content'])) {
            $parts = [];
            foreach($data['output'][0]['content'] as $c) {
                if(($c['type'] ?? '') === 'output_text' && !empty($c['text'])) {
                    $parts[] = trim($c['text']);
                }
            }
            if(!empty($parts)) {
                // Gabungkan semua output_text dengan line break agar rapi
                $reply = implode("\n\n", $parts);
            }
        }
        // Jika ada error dari API
        elseif(isset($data['error']['message'])) {
            $reply = $data['error']['message'];
        }
    } else {
        $reply = 'Maaf, gagal menghubungi server OpenAI.';
    }

    return response()->json(['reply' => $reply]);
});