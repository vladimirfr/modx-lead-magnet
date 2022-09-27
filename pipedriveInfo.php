<?php

$apiToken = '???';

require_once('phar://' . MODX_BASE_PATH . 'pipedrive-woodpecker.phar/autoload.php');

try {
    $client = new \Pipedrive\Client(null, null, null, $apiToken);

    print '<h4>Person fields:</h4>';
    print '<pre>';
    $personFields = $client->getPersonFields();
    $fields = $personFields->getAllPersonFields([]);
    $result = [];
    foreach($fields->jsonSerialize()->data as $item) {
        $result[$item->key] = $item->name;
    }
    print_r($result);
    print '</pre>';


    print '<h4>Pipelines:</h4>';
    print '<pre>';
    $pipelines = $client->getPipelines();
    $stages = $client->getStages();

    $pipelinesList = $pipelines->getAllPipelines();

    $result = [];
    foreach($pipelinesList->jsonSerialize()->data as $pipeline) {
        $result[$pipeline->id] = [
            'name' => $pipeline->name,
            'stages' => [],
        ];

        $stagesList = $stages->getAllStages($pipeline->id);
        foreach($stagesList->jsonSerialize()->data as $stage) {
            $result[$pipeline->id]['stages'][$stage->id] = $stage->name;
        }
    }

    print_r($result);

    print '</pre>';


} catch (\Exception $e) {
    if ($modx) {
        $modx->log(modX::LOG_LEVEL_ERROR, 'Pipedrive: ' . $e->getMessage());
    }

    return false;
}

return true;
