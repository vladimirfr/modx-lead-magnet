### modx-lead-magnet

Integration with Pipedrive and Woodpecker

#### Generating libraries pack

`composer i && php pack.php`

#### Woodpecker
Fork from https://github.com/smartlook/woodpecker

#### Example of usages

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
