<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Transactions;

use Geniusee\MoneyHubSdk\Collections\AbstractCollection;
use Illuminate\Support\Arr;

final class TransactionAttachmentCollection extends AbstractCollection
{
    /**
     * @psalm-suppress MixedArgument, MixedArrayAccess
     * @psalm-return array<array-key, TransactionAttachment>
     */
    public function get(): array
    {
        /**
         * @psalm-suppress MixedAssignment
         */
        $attachments = !Arr::isAssoc($this->response['data']) ? $this->response['data'] : [$this->response['data']];

        return array_map(
            /**
             * @psalm-param array{id:string, fileType:string, fileName:string, url:string} $attachment
             */
            static fn (array $attachment) => new TransactionAttachment(
                $attachment['id'],
                $attachment['fileType'],
                $attachment['fileName'],
                $attachment['url'],
            ),
            $attachments
        );
    }
}
