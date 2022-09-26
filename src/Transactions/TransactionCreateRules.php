<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Transactions;

use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

final class TransactionCreateRules
{
    public static function rules(): Collection
    {
        return new Collection(
            [
                'accountId' => [new NotBlank(), new Type('string')],
                'amount' => new Collection(
                    [
                        'value' => [new NotBlank(), new Type('integer')],
                    ]
                ),
                'categoryId' => [new NotBlank(), new Type('string')],
                'date' => [new NotBlank(), new Type('string')],
                'longDescription' => [new NotBlank(), new Type('string')],
            ]
        );
    }
}
