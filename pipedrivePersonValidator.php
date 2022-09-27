<?php
/**
 * Params:
 * string $pipedriveApiKey - required
 * string $pipedrivePipelineId - optional, if subscription related to pipeline
 */

if (empty($pipedriveApiKey)) {
    $validator->addError($key, 'Something wrong: no lists for subscriptions!');
    return false;
}


$email = (string)$value;

if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $validator->addError($key, 'Email is not valid!');
    return false;
}

try {
    require_once('phar://' . MODX_BASE_PATH . 'pipedrive-woodpecker.phar/autoload.php');

    $client = new \Pipedrive\Client(null, null, null, $pipedriveApiKey);

    $persons = $client->getPersons();

    $collect = [
        'term' => $email,
        'fields' => 'email',
        'exactMatch' => true,
    ];

    $person = null;
    $results = $persons->searchPersons($collect);
    if ($results && $results = $results->jsonSerialize()) {
        if ($results->success && !empty($results->data->items)) {
            $person = $results->data->items[0]->item;
        }
    }

    if ($person !== null && $pipedrivePipelineId > 0) {
        $found = $persons->listDealsAssociatedWithAPerson(['id' => $person->id]);
        if ($found && $results = $found->jsonSerialize()) {
            foreach ($results->data as $item) {
                if ($pipedrivePipelineId == $item->pipeline_id) {
                    $validator->addError($key, 'You are already subscribed!');
                    return false;
                }
            }
        }
    } elseif ($person !== null) {
        $validator->addError($key, 'You are already subscribed!');
        return false;
    }
} catch (\Exception $e) {
    $validator->addError($key, 'Something wrong, please try again later.' . $e->getMessage());

    return false;
}

return true;
