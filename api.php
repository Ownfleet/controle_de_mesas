<?php
header('Content-Type: application/json; charset=utf-8');

$csvUrl = "https://docs.google.com/spreadsheets/d/1Xc9CrVDJRC11ZpR2uXPf9EVBZZF-_OUHCbuHNnjcVMk/export?format=csv&gid=573517713";

$csv = file_get_contents($csvUrl);
$linhas = array_map('str_getcsv', explode("\n", $csv));

$mesas = [
    ['numero'=>1, 'l'=>0,  'c'=>3],  // D1
    ['numero'=>2, 'l'=>5,  'c'=>3],  // D6
    ['numero'=>3, 'l'=>10, 'c'=>3],  // D11
    ['numero'=>4, 'l'=>15, 'c'=>3],  // D16
    ['numero'=>5, 'l'=>20, 'c'=>3],  // D21
    ['numero'=>6, 'l'=>25, 'c'=>3],  // D26
    ['numero'=>7, 'l'=>30, 'c'=>3],  // D31

    ['numero'=>8,  'l'=>0,  'c'=>10], // K1
    ['numero'=>9,  'l'=>5,  'c'=>10], // K6
    ['numero'=>10, 'l'=>10, 'c'=>10], // K11
    ['numero'=>11, 'l'=>15, 'c'=>10], // K16
    ['numero'=>12, 'l'=>20, 'c'=>10], // K21
    ['numero'=>13, 'l'=>25, 'c'=>10], // K26
    ['numero'=>14, 'l'=>30, 'c'=>10], // K31
];

$resultado = [];

foreach ($mesas as $m) {
    $status = trim($linhas[$m['l']][$m['c']] ?? '');

    $tipo = "ocupada";

    if (stripos($status, "DISPON") !== false) {
        $tipo = "disponivel";
    }

    if (stripos($status, "FECHADA") !== false) {
        $tipo = "fechada";
    }

    $resultado[] = [
        "mesa" => $m['numero'],
        "status" => $status,
        "tipo" => $tipo
    ];
}

echo json_encode($resultado);