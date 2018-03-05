<?php

if (!defined('PIMCORE_PROJECT_ROOT')) {
    define(
        'PIMCORE_PROJECT_ROOT',
        getenv('PIMCORE_PROJECT_ROOT')
            ?: getenv('REDIRECT_PIMCORE_PROJECT_ROOT')
            ?: realpath(getcwd())
    );
}

if (!defined('TESTS_PATH')) {
    define('TESTS_PATH', __DIR__);
}

define('PIMCORE_CLASS_DIRECTORY', __DIR__ . '/tmp/var/classes');

define('PIMCORE_TEST', true);

require_once PIMCORE_PROJECT_ROOT . '/pimcore/config/bootstrap.php';

/**
 * @var $loader \Composer\Autoload\ClassLoader
 */
$loader->add('CoreShop\Test', [__DIR__.'/lib']);
$loader->add('Pimcore\Model\DataObject', [__DIR__ . '/tmp/var/classes/DataObject']);

$loader->addPsr4('Pimcore\\Model\\DataObject\\', PIMCORE_CLASS_DIRECTORY.'/DataObject', true);
//Actually, only needed
foreach (['CoreShopAddress',
    'CoreShopCart',
    'CoreShopCartItem',
    'CoreShopCategory',
    'CoreShopCustomer',
    'CoreShopCustomerGroup',
    'CoreShopOrder',
    'CoreShopOrderItem',
    'CoreShopOrderInvoice',
    'CoreShopOrderInvoiceItem',
    'CoreShopOrderShipment',
    'CoreShopOrderShipmentItem',
    'CoreShopProduct',
    'CoreShopQuote',
    'CoreShopQuoteItem'] as $class) {
    $loader->addClassMap([sprintf('Pimcore\Model\DataObject\%s', $class) => sprintf('%s/DataObject/%s.php', PIMCORE_CLASS_DIRECTORY, $class)]);
    $loader->addClassMap([sprintf('Pimcore\Model\DataObject\%s\Listing', $class) => sprintf('%s/DataObject/%s/Listing.php', PIMCORE_CLASS_DIRECTORY, $class)]);
}
foreach (['CoreShopProposalCartPriceRuleItem', 'CoreShopTaxItem'] as $fc) {
    $loader->addClassMap([sprintf('Pimcore\Model\DataObject\Fieldcollection\Data\%s', $fc) => sprintf('%s/DataObject/Fieldcollection/Data/%s.php', PIMCORE_CLASS_DIRECTORY, $fc)]);
}