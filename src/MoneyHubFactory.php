<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk;

use Geniusee\MoneyHubSdk\Accounts\Accounts;
use Geniusee\MoneyHubSdk\Authorization\Authorization;
use Geniusee\MoneyHubSdk\Authorization\AuthorizationStrategy;
use Geniusee\MoneyHubSdk\Authorization\DefaultConfig;
use Geniusee\MoneyHubSdk\Categories\Categories;
use Geniusee\MoneyHubSdk\Config\ConfigStrategy;
use Geniusee\MoneyHubSdk\Counterparties\Counterparties;
use Geniusee\MoneyHubSdk\Exception\ConfigException;
use Geniusee\MoneyHubSdk\Http\ApiClient;
use Geniusee\MoneyHubSdk\NotificationThresholds\NotificationThresholds;
use Geniusee\MoneyHubSdk\Projects\Projects;
use Geniusee\MoneyHubSdk\Sync\Sync;
use Geniusee\MoneyHubSdk\Tax\Taxes;
use Geniusee\MoneyHubSdk\Transactions\Transactions;
use Geniusee\MoneyHubSdk\Users\Users;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

use function is_readable;

final class MoneyHubFactory
{
    private ?Authorization $auth = null;
    private array $config = [];
    private mixed $httpLogMiddleware = null;

    public function __construct(private string $configPath = '')
    {
    }

    public static function fromArrayConfig(array $config): self
    {
        return new self();
    }

    public function withAuthorization(Authorization $authorization): self
    {
        $clone = clone $this;
        $clone->auth = $authorization;

        return $clone;
    }

    /**
     * @throws ConfigException
     */
    public function accounts(): Accounts
    {
        return new Accounts($this->createAuth(), new ApiClient($this->createApi()));
    }

    /**
     * @throws ConfigException
     */
    public function users(): Users
    {
        return new Users($this->createAuth(), new ApiClient($this->createApi()));
    }

    /**
     * @throws ConfigException
     */
    public function projects(): Projects
    {
        return new Projects($this->createAuth(), new ApiClient($this->createApi()));
    }

    /**
     * @throws ConfigException
     */
    public function counterparties(): Counterparties
    {
        return new Counterparties($this->createAuth(), new ApiClient($this->createApi()));
    }

    /**
     * @throws ConfigException
     */
    public function notificationThresholds(): NotificationThresholds
    {
        return new NotificationThresholds($this->createAuth(), new ApiClient($this->createApi()));
    }

    /**
     * @throws ConfigException
     */
    public function tax(): Taxes
    {
        return new Taxes($this->createAuth(), new ApiClient($this->createApi()));
    }

    /**
     * @throws ConfigException
     */
    public function categories(): Categories
    {
        return new Categories($this->createAuth(), new ApiClient($this->createApi()));
    }

    /**
     * @throws ConfigException
     */
    public function sync(): Sync
    {
        return new Sync($this->createAuth(), new ApiClient($this->createApi()));
    }

    /**
     * @throws ConfigException
     */
    public function transactions(): Transactions
    {
        return new Transactions($this->createAuth(), new ApiClient($this->createApi()));
    }

    private function createApi(): ClientInterface
    {
        $config = [];

        return new Client($config);
    }

    /**
     * @throws ConfigException
     */
    private function createAuth(): Authorization
    {
        return $this->auth ?? (new AuthorizationStrategy($this->configParse($this->configPath)))->create();
    }

    /**
     * @throws ConfigException
     */
    private function configParse(string $configPath): array
    {
        if (!is_readable($configPath)) {
            throw new ConfigException('Can not read config file');
        }

        $config = ConfigStrategy::getConfigParser($configPath)->parse();

        return array_merge(DefaultConfig::MONEY_HUB_CONFIG, $config);
    }
}
