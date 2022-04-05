<?php

declare(strict_types=1);

namespace Geniusee\Tests\Unit\Users;

use Geniusee\MoneyHubSdk\Helpers\JSON;
use Geniusee\MoneyHubSdk\Users\User;
use Geniusee\MoneyHubSdk\Users\UsersCollection;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class UserCollectionTest extends TestCase
{
    private UsersCollection $userCollection;

    protected function setUp(): void
    {
        $body = file_get_contents('./tests/_fixture/money_hub_answer_users_all.json');
        $this->userCollection = new UsersCollection(JSON::decode($body));
    }

    public function testMeta(): void
    {
        self::assertSame(['total' => 2, 'limit' => 1000, 'offset' => 0], $this->userCollection->getMeta());
    }

    public function testResponse(): void
    {
        self::assertArrayHasKey('data', $this->userCollection->response());
        self::assertArrayHasKey('meta', $this->userCollection->response());
    }

    public function testWhere(): void
    {
        $result = $this->userCollection->where(static fn (User $user) => $user->getUserId() === '62052d078');
        self::assertCount(1, $result);
    }
}
