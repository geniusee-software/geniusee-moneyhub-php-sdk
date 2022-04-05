<?php

declare(strict_types=1);

namespace Geniusee\MoneyHubSdk\Projects;

use Geniusee\MoneyHubSdk\Collections\AbstractCollection;
use Illuminate\Support\Arr;

final class ProjectsCollection extends AbstractCollection
{
    /**
     * @psalm-suppress MixedArgument
     * @psalm-return array<mixed, Project>
     */
    public function get(): array
    {
        /**
         * @psalm-var array $projects
         */
        $projects = !Arr::isAssoc($this->response['data']) ? $this->response['data'] : [$this->response['data']];

        return array_map(
            /**
             * @psalm-param array{
             *     id:string,
             *     name:string,
             *     accountIds:array<array-key, string>,
             *     type:string,
             *     dateCreated:?string,
             *     archived:?bool,
             *     } $project
             */
            static fn ($project) => new Project(
                $project['id'],
                $project['name'],
                $project['accountIds'],
                $project['type'],
                $project['dateCreated'],
                $project['archived']
            ),
            $projects
        );
    }
}
