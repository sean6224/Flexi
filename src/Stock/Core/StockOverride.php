<?php
declare(strict_types=1);
namespace Flexi\Stock\Core;

use Flexi\Exceptions\MethodRegistryException;
use Flexi\Exceptions\OverridePersisterException;
use Flexi\Stock\Traits\MethodRegistryTrait;
use Flexi\Stock\Traits\MethodOverrideTrait;

/**
 * StockOverride is responsible for managing method registrations and overrides
 * using traits that encapsulate dynamic method behavior.
 *
 * It initializes both method registry and override systems upon construction.
 *
 * @throws MethodRegistryException When the method registry fails to initialize.
 */
class StockOverride
{
    use MethodRegistryTrait, MethodOverrideTrait;

    /**
     * @throws MethodRegistryException
     */
    public function __construct()
    {
        $this->initMethodRegistry();
        $this->initMethodOverride();
    }
}
