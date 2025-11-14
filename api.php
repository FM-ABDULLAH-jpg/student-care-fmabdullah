<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$api_key = "YOUR_OPENAI_API_KEY"; // ← Apni key yahan lagani hai

$data = json_decode(file_get_contents("php://input"), true);
$userMessage = $data["message"] ?? "";
$imageBase64 = $data["image"] ?? null;

$payload = [
    "model" => "gpt-4o-mini",
    "messages" => [
        ["role" => "system", "content" => "You are a helpful assistant. Reply in the same language as the user."],
        ["role" => "user", "content" => $userMessage]
    ],
    "max_tokens" => 700
];

if ($imageBase64) {
    $payload["messages"][] = [
        "role" => "user",
        "content" => [
            [
                "type" => "input_image",
                "image_url" => "data:image/jpeg;base64," . $imageBase64
            ]
        ]
    ];
}

$ch = curl_init("https://api.openai.com/v1/chat/completions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $api_key"
]);

$response = curl_exec($ch);
curl_close($ch);

echo $response;
?>