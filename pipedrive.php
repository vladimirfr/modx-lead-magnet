<?php
/**
 * Params:
 * string $pipedriveApiKey
 * string $pipedrivePipelineId
 * string $pipedriveStageId
 * string $pipedriveCompanyKey
 * string $pipedriveStaticData - format: key==value||key==value...
 */

if (!$hook) {
    return true;
}

$apiToken = isset($pipedriveApiKey) ? $pipedriveApiKey : '';
if (empty($apiToken)) {
    return true;
}

require_once('phar://' . MODX_BASE_PATH . 'pipedrive-woodpecker.phar/autoload.php');

try {
    $client = new \Pipedrive\Client(null, null, null, $apiToken);

    $persons = $client->getPersons();

    $name = (string)$hook->getValue('name');
    $email = $hook->getValue('email');
    $company = (string)$hook->getValue('company');

    $person = null;

    $collect = [
        'term' => $email,
        'fields' => 'email',
        'exactMatch' => true,
    ];
    $results = $persons->searchPersons($collect);
    if ($results && $results = $results->jsonSerialize()) {
        if ($results->success && !empty($results->data->items)) {
            $person = $results->data->items[0]->item;
        }
    }

    $companyKey = (isset($pipedriveCompanyKey) && !empty($pipedriveCompanyKey)) ? $pipedriveCompanyKey : 'd6b1c5c53f262bdc1925a78c224122bed18295c3';
    if (isset($pipedriveStaticData)) {
        $staticData = [];
        foreach (explode('||', (string)$pipedriveStaticData) as $item) {
            $parts = explode('==', $item, 2);
            $staticData[$parts[0]] = $parts[1] ?? '';
        }
    } else {
        $staticData = [
            'e36557534ea092f51d65845a9c008c92a96c152a' => 2086, // Downloaded ebook => Yes
        ];
    }

    if ($person === null) {
        $body = [
            'name' => $name,
            'first_name' => $name,
            'email' => $email,
        ];

        if ($company) {
            $body[$companyKey] = $company; //Associated company
        }

        $body = array_merge($body, $staticData);

        $nameParts = explode(' ', trim($name), 2);
        if (count($nameParts) > 1) {
            $body['first_name'] = $nameParts[0];
            $body['last_name'] = $nameParts[1];
        }

        $response = $persons->addAPerson($body);
        if ($response && $response = $response->jsonSerialize()) {
            if ($response->success) {
                $person = $response->data;
            }
        }

        if ($person === null) {
            return true;
        }
    } elseif ($company || !empty($staticData)) {
        $body = [
            'id' => $person->id,
        ];

        if ($company) {
            $body[$companyKey] = $company; //Associated company
        }

        $body = array_merge($body, $staticData);
        $response = $persons->updateAPerson($body);
    }

    $pipeline_id = $pipedrivePipelineId ?? 9; //CW - Inbound - Ebook pipeline
    $stage_id = $pipedriveStageId ?? 47; //Downloaded ebook

    if (is_numeric($pipeline_id) && is_numeric($stage_id)) {
        $deals = $client->getDeals();
        $title = $email;
        if (!empty($name)) {
            $title = sprintf('%s - %s', $name, $email);
        }

        $body = [
            'title' => $title,
            'pipeline_id' => $pipeline_id,
            'stage_id' => $stage_id,
            'person_id' => $person->id,
        ];

        $response = $deals->addADeal($body);
    }
} catch (\Exception $e) {
    if ($modx) {
        $modx->log(modX::LOG_LEVEL_ERROR, 'Pipedrive: ' . $e->getMessage());
    }

    return false;
}

return true;
