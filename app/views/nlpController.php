<?php
class nlpController extends Controller {
    public function index() {
        $data = [
            'title' => 'AI Analysis',
            'isLoggedIn' => isset($_SESSION['user_id']),
            'username' => $_SESSION['username'] ?? null,
            'role' => $_SESSION['role'] ?? null
        ];
        
        $this->view('nlp', $data);
    }

    public function analyze() {
        header('Content-Type: application/json');
        
        try {
            // Check if it's a POST request
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }

            // Get and decode the input
            $rawInput = file_get_contents('php://input');
            if (!$rawInput) {
                throw new Exception('No input received');
            }

            $input = json_decode($rawInput, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Invalid JSON: ' . json_last_error_msg());
            }

            // Check for text
            $text = $input['text'] ?? '';
            if (empty($text)) {
                throw new Exception('No text provided');
            }

            // Call Gemini API
            $response = $this->callGeminiAPI($text);
            
            echo json_encode([
                'success' => true,
                'result' => $response
            ]);

        } catch (Exception $e) {
            error_log('NLP Analysis Error: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    private function callGeminiAPI($text) {
        if (!defined('GOOGLE_API_KEY') || empty(GOOGLE_API_KEY)) {
            throw new Exception('Google API key is not configured');
        }

        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=' . GOOGLE_API_KEY;
        
        $data = [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => "As a medical analysis assistant, analyze this clinical note and provide key insights, potential diagnoses, and recommended follow-ups: \n\n" . $text
                        ]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.7,
                'topK' => 40,
                'topP' => 0.95,
                'maxOutputTokens' => 1024,
            ],
            'safetySettings' => [
                [
                    'category' => 'HARM_CATEGORY_DANGEROUS_CONTENT',
                    'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                ]
            ]
        ];

        $ch = curl_init($url);
        
        if ($ch === false) {
            throw new Exception('Failed to initialize CURL');
        }

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ],
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_VERBOSE => true
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        curl_close($ch);

        if ($error) {
            throw new Exception('CURL Error: ' . $error);
        }

        if ($httpCode !== 200) {
            throw new Exception('API request failed with status ' . $httpCode . ': ' . $response);
        }

        $responseData = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Failed to decode API response: ' . json_last_error_msg());
        }

        // Check for API-specific errors
        if (isset($responseData['error'])) {
            throw new Exception('API Error: ' . ($responseData['error']['message'] ?? 'Unknown error'));
        }

        // Extract the generated text from the response
        if (!isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
            throw new Exception('Unexpected API response format');
        }

        return $responseData['candidates'][0]['content']['parts'][0]['text'];
    }

    // Helper function to handle file uploads if needed
    private function handleFileUpload() {
        if (!isset($_FILES['file'])) {
            return null;
        }

        $file = $_FILES['file'];
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('File upload failed with error code: ' . $file['error']);
        }

        // Read file content
        $content = file_get_contents($file['tmp_name']);
        if ($content === false) {
            throw new Exception('Failed to read uploaded file');
        }

        return $content;
    }

    // Helper function to sanitize input
    private function sanitizeInput($text) {
        // Remove any potentially harmful characters
        $text = strip_tags($text);
        // Remove any null bytes
        $text = str_replace(chr(0), '', $text);
        // Normalize line endings
        $text = str_replace(["\r\n", "\r"], "\n", $text);
        return $text;
    }
}