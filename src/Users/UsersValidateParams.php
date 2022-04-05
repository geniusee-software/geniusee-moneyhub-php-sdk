<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Users;

use Webmozart\Assert\Assert;

/**
 * @internal
 * @psalm-immutable
 */
final class UsersValidateParams
{
    public static function validate(array $params): void
    {
        if (isset($params['limit'])) {
            Assert::greaterThan($params['limit'], -1);
            Assert::lessThan($params['limit'], 1001);
        }

        if (isset($params['offset'])) {
            Assert::greaterThan($params['offset'], -1);
            Assert::lessThan($params['offset'], 1000000001);
        }
    }
}
