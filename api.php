<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$api_key = "YOUR_OPENAI_API_KEY"; // ← Yahan apni real key lagao

$input = json_decode(file_get_contents("php://input"), true);
$userMessage = $input["message"] ?? "";
$imageBase64 = $input["image"] ?? null;

$messages = [
    ["role" => "system", "content" => "Reply in the same language as the user. Be a friendly study tutor."]
];

if ($userMessage) {
    $messages[] = ["role" => "user", "content" => $userMessage];
}

if ($imageBase64) {
    $messages[] = [
        "role" => "user",
        "content" => [
            [
                "type"  => "input_image",
                "image_url" => "data:image/jpeg;base64,".$imageBase64
            ]
        ]
    ];
}

$payload = [
    "model" => "gpt-4o-mini",
    "messages" => $messages,
    "max_tokens" => 700
];

$ch = curl_init("https://api.openai.com/v1/chat/completions");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $api_key"
]);

$response = curl_exec($ch);
curl_close($ch);

echo $response;
?>
