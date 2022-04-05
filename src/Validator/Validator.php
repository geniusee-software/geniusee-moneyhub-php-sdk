<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Validator;

use Geniusee\MoneyHubSdk\Exception\ValidatorException;
use Geniusee\MoneyHubSdk\Helpers\JSON;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Validation;

/**
 * @internal
 */
final class Validator
{
    /**
     * @psalm-param array<mixed, Collection> $rules
     */
    public function validate(array $input, array $rules): void
    {
        $validator = Validation::createValidator();
        $errors = $validator->validate($input, $rules);

        if (\count($errors) > 0) {
            $messages = [];
            foreach ($errors as $error) {
                $messages[$error->getPropertyPath()][] = $error->getMessage();
            }

            throw new ValidatorException(JSON::encode($messages));
        }
    }
}
