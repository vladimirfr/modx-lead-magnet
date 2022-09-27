### modx-lead-magnet

Integration with Pipedrive and Woodpecker

#### Generating libraries pack

`composer i && php pack.php`

`pipedrive-woodpecker.phar` - should be placed in root directory.

#### Snippets:

 - pipedrive.php - add people to pipedrive
 - woodpecker.php - add people to woodpecker
 - pipedrivePersonValidator.php - check if person already subscribed to specific deal and stage

#### Woodpecker
Fork from https://github.com/smartlook/woodpecker

#### Example of usages for simple forms

```
[[!FormIt?
    &hooks=`...,pipedrive,woodpecker`
    &woodpeckerApiKey=``
    &woodpeckerCampaignId=`1457168`
    &pipedriveApiKey=``
    &pipedrivePipelineId=`9` # CW - Inbound - Ebook pipeline
    &pipedriveStageId=`47` # Downloaded ebook
    &pipedriveCompanyKey=`d6b1c5c53f262bdc1925a78c224122bed18295c3`
    &pipedriveStaticData=`e36557534ea092f51d65845a9c008c92a96c152a==2086` #  Downloaded ebook => Yes
    ...
]]
```

```
[!AjaxForm?
    &hooks=`...,pipedrive,woodpecker`
    &snippet=`FormIt`
    
    &woodpeckerApiKey=`114776.e3857342f25b0fb0298f253abc1f5a6e0cc7a9d6515a0b4140257e9c2a5de171`
    &woodpeckerCampaignId=`1457168`
    &pipedriveApiKey=`8ff9123c5dc55c33382ae07e81aebf3486dc5120`
    &pipedrivePipelineId=`9`
    &pipedriveStageId=`47`
    ...
]]
```

#### Example of usages for subscription form

pipedrivePipelineId - should be set empty or required pipeline
pipedriveStageId - should be set empty or required stage. Shouldn't be empty if pipedrivePipelineId is filled.

```
[[!AjaxForm?
    &hooks=`...,pipedrive`
    &snippet=`FormIt`
    &customValidators=`pipedrivePersonValidator`
    
    &pipedriveApiKey=`8ff9123c5dc55c33382ae07e81aebf3486dc5120`
    &pipedrivePipelineId=``
    &pipedriveStageId=``

    &validate=`email:email:required:pipedrivePersonValidator`
]]
```
