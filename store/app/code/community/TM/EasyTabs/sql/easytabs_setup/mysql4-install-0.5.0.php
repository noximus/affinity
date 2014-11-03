<?php
$installer = $this;

$installer->startSetup();

$installer->setConfigData('easy_tabs/general/enabled', 1);
$installer->setConfigData('easy_tabs/general/descriptiontabbed', 1);
$installer->setConfigData('easy_tabs/general/additionaltabbed', 1);
$installer->setConfigData('easy_tabs/general/upsellproductstabbed', 1);
$installer->setConfigData('easy_tabs/general/relatedtabbed', 1);
$installer->setConfigData('easy_tabs/general/tagstabbed', 1);
$installer->setConfigData('easy_tabs/general/reviewtabbed', 1);

$installer->endSetup();
