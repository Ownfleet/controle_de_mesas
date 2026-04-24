<?php
header('Content-Type: application/json; charset=utf-8');

$csvUrl = "https://docs.google.com/spreadsheets/d/18kD9Hz3tIKQxJXg-rp5RyKI3iWkx8UBRUh9Uy7XRN5w/export?format=csv&gid=0";

$csv = @file_get_contents($csvUrl);

if (!$csv) {
    echo json_encode([
        "erro" => true,
        "mensagem" => "Não foi possível acessar a planilha"
    ]);
    exit;
}

$linhas = array_map('str_getcsv', explode("\n", $csv));

$mesas = [
    ['numero'=>1,  'l'=>0,  'c'=>3],
    ['numero'=>2,  'l'=>5,  'c'=>3],
    ['numero'=>3,  'l'=>10, 'c'=>3],
    ['numero'=>4,  'l'=>15, 'c'=>3],
    ['numero'=>5,  'l'=>20, 'c'=>3],
    ['numero'=>6,  'l'=>25, 'c'=>3],
    ['numero'=>7,  'l'=>30, 'c'=>3],

    ['numero'=>8,  'l'=>0,  'c'=>10],
    ['numero'=>9,  'l'=>5,  'c'=>10],
    ['numero'=>10, 'l'=>10, 'c'=>10],
    ['numero'=>11, 'l'=>15, 'c'=>10],
    ['numero'=>12, 'l'=>20, 'c'=>10],
    ['numero'=>13, 'l'=>25, 'c'=>10],
    ['numero'=>14, 'l'=>30, 'c'=>10],
];

$resultado = [];

foreach ($mesas as $m) {
    $status = trim($linhas[$m['l']][$m['c']] ?? '');

    $tipo = "ocupada";

    if (stripos($status, "DISPON") !== false) $tipo = "disponivel";
    if (stripos($status, "FECHADA") !== false) $tipo = "fechada";

    $resultado[] = [
        "numero" => $m['numero'],
        "status" => $status,
        "tipo" => $tipo
    ];
}

echo json_encode([
    "erro" => false,
    "mesas" => $resultado
]);