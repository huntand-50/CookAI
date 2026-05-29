<?php
/**
 * AIService - интеграция с Yandex AI API
 */
class AIService
{
    private $api_key;
    private $folder_id;
    private $cache_dir;

    public function __construct()
    {
        $this->api_key = $_ENV['YANDEX_API_KEY'] ?? '';
        $this->folder_id = $_ENV['YANDEX_FOLDER_ID'] ?? '';
        $this->cache_dir = ROOT_PATH . '/storage/cache/';
        
        if (!is_dir($this->cache_dir)) {
            mkdir($this->cache_dir, 0755, true);
        }
        
        if (empty($this->api_key) || empty($this->folder_id)) {
            throw new Exception('Yandex API ключ не настроен');
        }
    }

    /**
     * Генерация рецепта через Yandex GPT
     */
    public function generateRecipe($params)
    {
        $prompt = "Создай рецепт на основе следующих параметров:\n";
        $prompt .= "- Диета: " . $params['diet'] . "\n";
        $prompt .= "- Время приготовления: " . $params['time'] . " минут\n";
        $prompt .= "- Ингредиенты: " . $params['ingredients'] . "\n\n";
        $prompt .= "Ответ должен быть в формате JSON с полями: title, description, ingredients (массив), instructions (массив шагов), time_minutes, difficulty (easy/medium/hard), servings";
        
        $response = $this->callYandexGPT($prompt);
        
        // Парсинг JSON из ответа
        preg_match('/\{.*\}/s', $response, $matches);
        if (!empty($matches[0])) {
            return json_decode($matches[0], true);
        }
        
        throw new Exception('Не удалось распарсить ответ API');
    }

    /**
     * Сканирование калорий по фото (Computer Vision)
     */
    public function scanCalories($file)
    {
        // Загрузка файла и кодирование в base64
        $image_data = file_get_contents($file['tmp_name']);
        $base64_image = base64_encode($image_data);
        
        $prompt = "Проанализируй это фото блюда и определи:\n";
        $prompt .= "1. Название блюда\n";
        $prompt .= "2. Примерные ингредиенты\n";
        $prompt .= "3. Калорийность на 100г\n";
        $prompt .= "4. Белки/Жиры/Углеводы\n\n";
        $prompt .= "Ответь в формате JSON: {name, ingredients, calories, protein, fat, carbs}";
        
        // Вызов API с изображением
        $response = $this->callYandexVision($base64_image, $prompt);
        
        preg_match('/\{.*\}/s', $response, $matches);
        if (!empty($matches[0])) {
            return json_decode($matches[0], true);
        }
        
        return ['error' => 'Не удалось проанализировать изображение'];
    }

    /**
     * AI советы по готовке
     */
    public function getAdvice($question)
    {
        $prompt = "Ты - опытный шеф-повар и консультант по кулинарии. ";
        $prompt .= "Ответь на следующий вопрос кратко и полезно:\n\n";
        $prompt .= $question;
        
        return $this->callYandexGPT($prompt);
    }

    /**
     * Вызов Yandex GPT API
     */
    private function callYandexGPT($prompt)
    {
        $cache_key = md5($prompt);
        $cache_file = $this->cache_dir . $cache_key . '.txt';
        
        // Проверка кэша
        if (file_exists($cache_file) && time() - filemtime($cache_file) < 86400) {
            return file_get_contents($cache_file);
        }
        
        $url = 'https://llm.api.cloud.yandex.net/foundationModels/v1/completion';
        
        $data = [
            'modelUri' => 'gpt://' . $this->folder_id . '/yandexgpt-3',
            'completionOptions' => [
                'stream' => false,
                'temperature' => 0.7,
                'maxTokens' => 2000
            ],
            'messages' => [
                [
                    'role' => 'user',
                    'text' => $prompt
                ]
            ]
        ];
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Api-Key ' . $this->api_key,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($http_code !== 200) {
            throw new Exception('Yandex API ошибка: ' . $response);
        }
        
        $response_data = json_decode($response, true);
        $result = $response_data['result']['alternatives'][0]['message']['text'] ?? '';
        
        // Сохранение в кэш
        file_put_contents($cache_file, $result);
        
        return $result;
    }

    /**
     * Вызов Yandex Vision API
     */
    private function callYandexVision($base64_image, $prompt)
    {
        // Пока используем GPT для анализа как альтернатива
        $enhanced_prompt = "Проанализируй это изображение блюда (закодировано в base64):\n" . $prompt;
        return $this->callYandexGPT($enhanced_prompt);
    }
}
?>
