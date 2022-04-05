<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Users;

use Geniusee\MoneyHubSdk\Collections\AbstractCollection;

final class UsersCollection extends AbstractCollection
{
    /**
     * @return array<mixed, User>
     */
    public function get(): array
    {
        /**
         * @psalm-var array<string, mixed> $users
         */
        $users = $this->response['data'];

        return array_map(static fn (array $user) => new User(
            (string)$user['userId'],
            (string)$user['clientUserId'],
            (string)$user['clientId'],
            (string)$user['createdAt'],
            (string)$user['updatedAt'],
            (string)$user['scopes'],
            (string)$user['managedBy'],
            (string)$user['lastAccessed'],
            (string)$user['userType'],
            (array)$user['connectionIds'],
            isset($user['deletedAt']) ? (string)$user['deletedAt'] : null,
            isset($user['clientName']) ? (string)$user['clientName'] : null,
        ), $users);
    }
}
