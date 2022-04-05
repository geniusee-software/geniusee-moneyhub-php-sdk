<?php

declare(strict_types=1);

namespace Geniusee\Tests\Unit\Accounts;

use DG\BypassFinals;
use Geniusee\MoneyHubSdk\Accounts\Accounts;
use Geniusee\MoneyHubSdk\Authorization\Authorization;
use Geniusee\MoneyHubSdk\Exception\RequestException;
use Geniusee\MoneyHubSdk\Exception\ValidatorException;
use Geniusee\MoneyHubSdk\Http\ApiClient;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class AccountsTest extends TestCase
{
    private ApiClient $apiMock;
    private Authorization $authMock;

    protected function setUp(): void
    {
        BypassFinals::enable();
        $this->apiMock = $this->createMock(ApiClient::class);
        $this->authMock = $this->createMock(Authorization::class);
    }

    public function testCreateSingleAccountException(): void
    {
        $this->expectException(ValidatorException::class);
        $newAccount = new Accounts($this->authMock, $this->apiMock);
        $newAccount->createSingleAccount('user_id');
    }

    public function testCreateSingleAccount(): void
    {
        $body = file_get_contents('./tests/_fixture/account_single.json');
        $httpResponse = new Response(200, [], $body);
        $this->apiMock->expects(self::once())->method('send')->willReturn($httpResponse);
        $newAccount = (new Accounts($this->authMock, $this->apiMock))
            ->withBodyParams(
                [
                    'accountName' => 'account_name',
                    'providerName' => 'provider_name',
                    'type' => 'cash:current',
                    'balance' => ['amount' => ['value' => 2], 'date' => '2022-02-12'],
                ]
            );
        $newAccount->createSingleAccount('user_id');
    }

    public function testGetOneAccount(): void
    {
        $body = file_get_contents('./tests/_fixture/account_single.json');
        $httpResponse = new Response(200, [], $body);
        $this->apiMock->expects(self::once())->method('send')->willReturn($httpResponse);
        $newAccount = new Accounts($this->authMock, $this->apiMock);

        $newAccount->one('user_id', 'account_id');
    }

    public function testUpdateSingleAccountException(): void
    {
        $this->expectException(ValidatorException::class);
        $newAccount = new Accounts($this->authMock, $this->apiMock);
        $newAccount->updateSingleAccount('user_id', 'account_id');
    }

    public function testUpdateSingleAccount(): void
    {
        $body = file_get_contents('./tests/_fixture/account_single.json');
        $httpResponse = new Response(200, [], $body);
        $this->apiMock->expects(self::once())->method('send')->willReturn($httpResponse);
        $newAccount = (new Accounts($this->authMock, $this->apiMock))
            ->withBodyParams(
                [
                    'accountName' => 'account_name',
                    'providerName' => 'provider_name',
                ]
            );
        $newAccount->updateSingleAccount('user_id', 'account_id');
    }

    public function testDeleteException(): void
    {
        $this->expectException(RequestException::class);
        $httpResponse = new Response(500);
        $this->apiMock->method('send')->willReturn($httpResponse);
        $newAccount = new Accounts($this->authMock, $this->apiMock);
        $newAccount->delete('user_id', 'account_id');
    }

    public function testDelete(): void
    {
        $httpResponse = new Response(204);
        $this->apiMock->expects(self::once())->method('send')->willReturn($httpResponse);
        $newAccount = new Accounts($this->authMock, $this->apiMock);
        $newAccount->delete('user_id', 'account_id');
    }

    public function testAddNewBalanceForAnAccount(): void
    {
        $body = file_get_contents('./tests/_fixture/balance_account.json');
        $httpResponse = new Response(200, [], $body);
        $this->apiMock->expects(self::once())->method('send')->willReturn($httpResponse);
        $newAccount = (new Accounts($this->authMock, $this->apiMock))
            ->withBodyParams(['amount' => ['value' => 1], 'date' => '2022-02-14']);
        $newAccount->addNewBalanceForAnAccount('user_id', 'account_id');
    }

    public function testRetrieveTheHistoricalBalancesForAnAccount(): void
    {
        $body = file_get_contents('./tests/_fixture/historical_data.json');
        $httpResponse = new Response(200, [], $body);
        $this->apiMock->expects(self::once())->method('send')->willReturn($httpResponse);
        $newAccount = (new Accounts($this->authMock, $this->apiMock));
        $newAccount->retrieveTheHistoricalBalancesForAnAccount('user_id', 'account_id');
    }
}
