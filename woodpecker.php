<?php
/**
 * Params:
 * string $woodpeckerApiKey
 * string $woodpeckerCampaignId
 */

if (!$hook) {
    return true;
}

$apiKey = isset($woodpeckerApiKey) ? $woodpeckerApiKey : '';
$campaignId = isset($woodpeckerCampaignId) ? $woodpeckerCampaignId : '';

if (empty($apiKey) || empty($campaignId)) {
    return true;
}

require_once('phar://' . MODX_BASE_PATH . 'pipedrive-woodpecker.phar/autoload.php');

$name = (string)$hook->getValue('name');
$email = (string)$hook->getValue('email');
$company = (string)$hook->getValue('company');

$nameParts = explode(' ', trim($name), 2);
$firstName = $name;
$lastName = '';
if (count($nameParts) > 1) {
    $firstName = $nameParts[0];
    $lastName = $nameParts[1];
}

try {
    $woodpecker = new \Smartsupp\Woodpecker\Woodpecker($apiKey);
    $woodpecker->addProspect($campaignId, $email, $firstName, $lastName, $company);
} catch (\Exception $e) {
    if ($modx) {
        $modx->log(modX::LOG_LEVEL_ERROR, 'Woodpecker: ' . $e->getMessage());
    }

    return false;
}

return true;
