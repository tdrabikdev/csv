<?php
error_reporting(E_ERROR | E_PARSE);
//assign variables
$input = getopt(null, ["file:"]);
$output = getopt(null, ['unique-combinations:']);

//check if files are provided
if (!$input || !$output) {
    throw new Exception(' Mo input or output file');
}
//read file
$file = new SplFileObject($input['file']);
$file->setFlags(SplFileObject::READ_CSV);
$dataArray = [];
$count = 0;
$dataArray[0] = [
    'make'      => 'make',
    'model'     => 'model',
    'colour'    => 'colour',
    'capacity'  => 'capacity',
    'network'   => 'grade',
    'condition' => 'condition',
    'count'     => 'count'
];
//prepare data
foreach ($file as $row) {
    if ($count >= 1) {
        list($make, $model, $colour, $capacity, $network, $grade, $condition) = $row;

        $key = $make.$model.$colour.$capacity.$network.$grade.$condition;
        $dataArray[$key]['make'] = $make;
        $dataArray[$key]['model'] = $model;
        $dataArray[$key]['capacity'] = $capacity;
        $dataArray[$key]['network'] = $network;
        $dataArray[$key]['grade'] = $grade;
        $dataArray[$key]['condition'] = $condition;
        if (isset($dataArray[$key]['count'])) {
            $dataArray[$key]['count'] += 1;
        } else {
            $dataArray[$key]['count'] = 1;
        }
    }

    $count++;
}

//safe new file
$fileOutput = new SplFileObject($output['unique-combinations'], 'a');

foreach ($dataArray as $fields) {
    $fileOutput->fputcsv($fields);
}
