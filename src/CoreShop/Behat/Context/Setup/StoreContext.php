<?php
/**
 * CoreShop.
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2015-2017 Dominik Pfaffenbauer (https://www.pfaffenbauer.at)
 * @license    https://www.coreshop.org/license     GNU General Public License version 3 (GPLv3)
*/

namespace CoreShop\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use CoreShop\Behat\Service\SharedStorageInterface;
use CoreShop\Component\Core\Model\CountryInterface;
use CoreShop\Component\Core\Model\CurrencyInterface;
use CoreShop\Component\Core\Model\ProductInterface;
use CoreShop\Component\Core\Model\StoreInterface;
use CoreShop\Component\Core\Repository\ProductRepositoryInterface;
use CoreShop\Component\Product\Calculator\ProductPriceCalculatorInterface;
use CoreShop\Component\Resource\Factory\FactoryInterface;
use CoreShop\Component\Store\Context\FixedStoreContext;
use CoreShop\Component\Store\Repository\StoreRepositoryInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Pimcore\Model\DataObject\Folder;
use Webmozart\Assert\Assert;

final class StoreContext implements Context
{
    /**
     * @var SharedStorageInterface
     */
    private $sharedStorage;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var FactoryInterface
     */
    private $storeFactory;

    /**
     * @var FactoryInterface
     */
    private $currencyFactory;

    /**
     * @var FactoryInterface
     */
    private $countryFactory;

    /**
     * @var StoreRepositoryInterface
     */
    private $storeRepository;

    /**
     * @var FixedStoreContext
     */
    private $fixedStoreContext;

    /**
     * StoreContext constructor.
     * @param SharedStorageInterface $sharedStorage
     * @param ObjectManager $objectManager,
     * @param FactoryInterface $storeFactory
     * @param StoreRepositoryInterface $storeRepository
     * @param FactoryInterface $currencyFactory
     * @param FactoryInterface $countryFactory
     * @param FixedStoreContext $fixedStoreContext
     */
    public function __construct(
        SharedStorageInterface $sharedStorage,
        ObjectManager $objectManager,
        FactoryInterface $storeFactory,
        StoreRepositoryInterface $storeRepository,
        FactoryInterface $currencyFactory,
        FactoryInterface $countryFactory,
        FixedStoreContext $fixedStoreContext
    )
    {
        $this->sharedStorage = $sharedStorage;
        $this->objectManager = $objectManager;
        $this->storeFactory = $storeFactory;
        $this->storeRepository = $storeRepository;
        $this->currencyFactory = $currencyFactory;
        $this->countryFactory = $countryFactory;
        $this->fixedStoreContext = $fixedStoreContext;
    }

    /**
     * @Given the site operates on a store in "Austria"
     */
    public function storeOperatesOnASingleChannelInAustria()
    {
        $store = $this->createStore('Austria');

        $this->fixedStoreContext->setStore($store);

        $this->saveStore($store);
    }

    /**
     * @Given /^the site has a store "([^"]+)" with (country "[^"]+") and (currency "[^"]+")$/
     */
    public function siteHasAStoreWithCountryAndCurrency($name, CountryInterface $country, CurrencyInterface $currency)
    {
        $store = $this->createStore($name, $currency, $country);

        $this->saveStore($store);
    }

    /**
     * @param $name
     * @param CurrencyInterface|null $currency
     * @param CountryInterface|null $country
     * @return StoreInterface
     */
    private function createStore($name, CurrencyInterface $currency = null, CountryInterface $country = null)
    {
        /**
         * @var $store StoreInterface
         */
        $store = $this->storeFactory->createNew();

        if (null === $currency) {
            /**
             * @var $currency CurrencyInterface
             */
            $currency = $this->currencyFactory->createNew();
            $currency->setIsoCode('EUR');
            $currency->setName('EURO');
            $currency->setSymbol('€');

            $this->objectManager->persist($currency);
        }

        if (null === $country) {
            /**
             * @var $country CountryInterface
             */
            $country = $this->countryFactory->createNew();
            $country->setName('Austria');
            $country->setIsoCode('AT');
            $country->setCurrency($currency);
            $country->setActive(true);

            $this->objectManager->persist($country);
        }

        $store->setName($name);
        $store->setCurrency($currency);
        $store->setBaseCountry($country);

        return $store;
    }

    /**
     * @param StoreInterface $store
     */
    private function saveStore(StoreInterface $store)
    {
        $this->objectManager->persist($store);
        $this->objectManager->flush();

        $this->sharedStorage->set('store', $store);
    }
}
