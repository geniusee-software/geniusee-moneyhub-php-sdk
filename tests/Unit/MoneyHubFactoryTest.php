<?php

declare(strict_types=1);

namespace Geniusee\Tests\Unit;

use DG\BypassFinals;
use Geniusee\MoneyHubSdk\Accounts\Accounts;
use Geniusee\MoneyHubSdk\Exception\ConfigException;
use Geniusee\MoneyHubSdk\MoneyHubFactory;
use Geniusee\MoneyHubSdk\Users\Users;
use Geniusee\Tests\_fakes\AuthorizationFake;
use PHPUnit\Framework\TestCase;
use ReflectionObject;

/**
 * @internal
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class MoneyHubFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        BypassFinals::enable();
    }

    public function testWithAuth(): void
    {
        $factory = (new MoneyHubFactory())->withAuthorization(new AuthorizationFake());
        $this->assertWithAuth($factory);
    }

    public function testUser(): void
    {
        $factory = (new MoneyHubFactory('./tests/_config/basic_config.php'));
        self::assertInstanceOf(Users::class, $factory->users());
    }

    public function testAccounts(): void
    {
        $factory = (new MoneyHubFactory('./tests/_config/basic_config.php'));
        self::assertInstanceOf(Accounts::class, $factory->accounts());
    }

    public function testBrokenFilePath(): void
    {
        $this->expectException(ConfigException::class);
        $factory = (new MoneyHubFactory('./tests/_config/basic_configa.php'));
        $factory->users();
    }

    private function assertWithAuth(MoneyHubFactory $factory): void
    {
        $property = (new ReflectionObject($factory))->getProperty('auth');
        $property->setAccessible(true);
        self::assertInstanceOf(AuthorizationFake::class, $property->getValue($factory));
    }
}
