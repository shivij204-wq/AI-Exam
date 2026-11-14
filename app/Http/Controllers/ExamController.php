<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\ExamQuestion;

class ExamController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function generate(Request $request)
    {
        // Validate form data
        $data = $request->validate([
            'exam_name' => 'required|string|max:100',
            'subject'   => 'required|string|max:100',
            'topic'     => 'required|string|max:200',
            'count'     => 'required|integer|in:5,10,20',
        ]);

        try {

            // Get Gemini API Key
            $apiKey = config('services.gemini.key');

            if (empty($apiKey)) {
                throw new \Exception('Gemini API Key .env file mein nahi mili.');
            }

            // Create prompt
            $prompt = "
            Generate exactly {$data['count']} multiple choice questions.

            Exam: {$data['exam_name']}
            Subject: {$data['subject']}
            Topic: {$data['topic']}

            Return ONLY a valid JSON array.

            Each question must contain:
            {
                \"question\": \"Question text\",
                \"options\": [
                    \"Option A\",
                    \"Option B\",
                    \"Option C\",
                    \"Option D\"
                ],
                \"correct_answer\": \"Correct option\",
                \"explanation\": \"Explanation\"
            }

            Generate exactly 4 options per question.
            Do not include markdown or extra text.
            ";

            // Call Gemini API
           $response = Http::connectTimeout(20)->timeout(120)->retry(3, 2000)->withHeaders([
        'x-goog-api-key' => $apiKey,
        'Content-Type' => 'application/json',
    ])
    ->post(
        'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash:generateContent',
        [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'responseMimeType' => 'application/json',
                'temperature' => 0.7,
            ],
        ]
    );

            // Check API response
            if (!$response->successful()) {
                throw new \Exception(
                    'Gemini API Error: ' . $response->body()
                );
            }

            // Get generated text
            $text = $response->json(
                'candidates.0.content.parts.0.text'
            );

            if (empty($text)) {
                throw new \Exception(
                    'Gemini ne empty response diya.'
                );
            }

            // Convert JSON into PHP array
            $questions = json_decode($text, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception(
                    'Invalid JSON: ' . json_last_error_msg()
                );
            }

            // Check question count
            if (
                !is_array($questions) ||
                count($questions) !== (int) $data['count']
            ) {
                throw new \Exception(
                    'Generated questions ki count sahi nahi hai.'
                );
            }

            // Save questions in database
            ExamQuestion::create([
                'exam_name' => $data['exam_name'],
                'subject'   => $data['subject'],
                'topic'     => $data['topic'],
                'questions' => $questions,
            ]);

            // Show generated questions
            return view('questions', [
                'questions' => $questions,
                'exam'      => $data['exam_name'],
                'subject'   => $data['subject'],
                'topic'     => $data['topic'],
            ]);

        } catch (\Throwable $e) {

            Log::error('Gemini Question Generation Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return redirect()->route('home') ->withInput()->with('error', $e->getMessage());
        
        }
    }
}