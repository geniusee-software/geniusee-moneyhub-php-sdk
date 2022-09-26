# Moneyhub API Client

### Introduction

This is an PHP client for the [Moneyhub API](https://docs.moneyhubenterprise.com/docs). It currently supports the 
following features:

- Registering users
- Deleting users
- Generating authorisation urls for new and existing users
- Getting access tokens and refresh tokens from an authorisation code
- Refreshing access tokens
- Deleting user connections
- Getting access tokens with client credentials
- CRUD actions for accounts
- CRUD actions for transactions
- Generate authorisation url for payments
- Add Payees
- Get Payees and payments
- Get categories
- CRUD actions on projects
- CRUD actions on transaction attachments
- CRUD actions on transaction splits
- Get a tax return for a subset of transactions
- Get the regular transactions on an account
- Get beneficiaries

Currently this library supports `client_secret_basic` authentication.</br>
JSON example for client_secret_basic
```json
{
  "clientId": "clientId",
  "clientSecret": "clientSecret",
  "responseType": "code"
}
```
### Commands
```text
make psalm - run psalm
make test - run unit tests
make style-fix - run cs-fixer
```




### Usage
This module exposes a factory function that accepts the following configuration:
```php
$factory = new MoneyHubFactory('path_to_config');
```
Now supports php, json file formats.


### Accounts
Accounts API Moneyhub docs - https://docs.moneyhubenterprise.com/reference/get_accounts
```php
$factory->accounts();
```

##### Supported functions
```php
$factory->accounts()->delete(string $userid, string $accountId);
$factory->accounts()->all(string $userId): AccountBalanceCollection;
$factory->accounts()->addNewBalanceForAnAccount(string $userId, string $accountId): AccountBalanceCollection;
$factory->accounts()->one(string $userId, string $accountId): Account;
$factory->accounts()->retrieveTheHistoricalBalancesForAnAccount(
        string $userId,
        string $accountId
    ): AccountBalanceCollection
    
$factory->accounts()->updateSingleAccount(string $userId, string $accountId): Account
```
To add our scopes you can use withScopes method
```php
$factory->accounts()->withScopes();
```
Additional methods
```php
$factory->accounts()->withGrantType();
$factory->accounts()->withParams();
$factory->accounts()->withBodyParams();
```

### Counterparties
Counterparties API Moneyhub docs - https://docs.moneyhubenterprise.com/reference/get_accounts-accountid-counterparties
```php
$factory->transactions();
```

##### Supported functions
```php
$factory->transactions()->all(string $userId): TransactionCollection;
$factory->transactions()->one(string $userId, string $transactionId): Transaction;
$factory->transactions()->createSingleTransaction(string $userId): Transaction;
$factory->transactions()->createMultipleTransactions(string $userId): array;
$factory->transactions()->updateSingleTransaction(string $userId, string $transactionId): Transaction
$factory->transactions()->transactionAttachments(string $userId, string $transactionId): TransactionAttachmentCollection;
$factory->transactions()->retrieveTransactionAttachments(
        string $userId,
        string $transactionId,
        string $fileId
    ): array;
$factory->transactions()->retrieveTransactionSplit(
        string $userId,
        string $transactionId,
    ): array;
$factory->transactions()->splitTransaction(
        string $userId,
        string $transactionId,
    ): TransactionSplit;
$factory->transactions()->pathSplitTransaction(
        string $userId,
        string $transactionId,
        string $splitId,
    ): array;
$factory->transactions()->mergeSplitTransaction(string $userId,string $transactionId): void;

$factory->transactions()->deleteTransactionAttachments(string $userId,string $transactionId,string $fileId): void;

$factory->transactions()->delete(string $userid, string $transactionId): void
```
Additional methods
```php
$factory->transactions()->withGrantType();
$factory->transactions()->withParams();
$factory->transactions()->withBodyParams();
$factory->transactions()->withScopes();
```

### Notification Thresholds
Notification Thresholds API Moneyhub docs - https://docs.moneyhubenterprise.com/reference/get_accounts-accountid-notification-thresholds
```php
$factory->notificationThresholds();
```

##### Supported functions
```php
$factory->notificationThresholds()->all(string $userId, string $accountId): NotificationThresholdsCollection;
$factory->notificationThresholds()->create(string $userId, string $accountId): NotificationThreshold;
$factory->notificationThresholds()->update(string $userId, string $accountId, string $thresholdId): NotificationThreshold;
$factory->notificationThresholds()->delete(string $userId, string $accountId, string $thresholdId): void;
```

Additional methods
```php
$factory->notificationThresholds()->withGrantType();
$factory->notificationThresholds()->withParams();
$factory->notificationThresholds()->withBodyParams();
$factory->notificationThresholds()->withScopes();
```

### Transactions
Transactions API Moneyhub docs - https://docs.moneyhubenterprise.com/reference/get_transactions
```php
$factory->transactions();
```

##### Supported functions
```php
$factory->transactions()->all(string $userId): TransactionCollection;
$factory->transactions()->one(string $userId, string $transactionId): Transaction;
$factory->transactions()->createSingleTransaction(string $userId): Transaction;
$factory->transactions()->createMultipleTransactions(string $userId): array;
$factory->transactions()->updateSingleTransaction(string $userId, string $transactionId): Transaction;
$factory->transactions()->transactionAttachments(string $userId, string $transactionId): TransactionAttachmentCollection;
$factory->transactions()->retrieveTransactionAttachments(
        string $userId,
        string $transactionId,
        string $fileId
    ): TransactionAttachment;
$factory->transactions()->retrieveTransactionSplit(
        string $userId,
        string $transactionId,
    ): array;
$factory->transactions()->splitTransaction(
        string $userId,
        string $transactionId,
    ): TransactionSplit;
$factory->transactions()->pathSplitTransaction(
        string $userId,
        string $transactionId,
        string $splitId,
    ): array;
$factory->transactions()->mergeSplitTransaction(
        string $userId,
        string $transactionId,
    ): void;
$factory->transactions()->deleteTransactionAttachments(
        string $userId,
        string $transactionId,
        string $fileId
    ): void;
$factory->transactions()->delete(string $userid, string $transactionId): void
```

Additional methods
```php
$factory->transactions()->withGrantType();
$factory->transactions()->withParams();
$factory->transactions()->withBodyParams();
$factory->transactions()->withScopes();
```


### Categories
Categories API Moneyhub docs - https://docs.moneyhubenterprise.com/reference/get_categories
```php
$factory->categories();
```

##### Supported functions
```php
$factory->categories()->all(string $userId): CategoriesCollection;
$factory->categories()->one(string $userId, string $categoryId): Category;
$factory->categories()->allCategoryGroups(string $userId): CategoriesGroupCollection;
$factory->categories()->create(string $userId): Category;
```

Additional methods
```php
$factory->categories()->withGrantType();
$factory->categories()->withParams();
$factory->categories()->withBodyParams();
$factory->categories()->withScopes();
```

### Projects
Projects API Moneyhub docs - https://docs.moneyhubenterprise.com/reference/get_projects
```php
$factory->projects();
```

##### Supported functions
```php
$factory->projects()->all(string $userId): ProjectsCollection;
$factory->projects()->one(string $userId, string $projectId): Project;
$factory->projects()->createSingleProject(string $userId): Project;
$factory->projects()->updateSingleProject(string $userId, string $projectId): Project;
$factory->projects()->delete(string $userid, string $accountId): void;
```

Additional methods
```php
$factory->projects()->withGrantType();
$factory->projects()->withParams();
$factory->projects()->withBodyParams();
$factory->projects()->withScopes();
```

### TAX
Tax API Moneyhub docs - https://docs.moneyhubenterprise.com/reference/get_tax
```php
$factory->tax();
```

##### Supported functions
```php
$factory->tax()->retrieveTransactions(
        string $userId,
        string $startDate,
        string $endDate,
        ?string $projectId = null,
        ?string $accountId = null
    ): TaxesCollection;
```

Additional methods
```php
$factory->tax()->withScopes();
```

### Users
Users API Moneyhub docs - https://docs.moneyhubenterprise.com/reference/get_users
```php
$factory->users();
```

##### Supported functions
```php
$factory->users()->all(): UsersCollection;
$factory->users()->one(string $moneyHubUserId): User;
$factory->users()->create(string $clientUserId): User;
$factory->users()->delete(string $moneyHubUserId): void;
```

Additional methods
```php
$factory->users()->withGrantType();
$factory->users()->withParams();
$factory->users()->withBodyParams();
$factory->users()->withScopes();
```

### Sync
Sync API Moneyhub docs - https://docs.moneyhubenterprise.com/reference/post_sync-connectionid
```php
$factory->sync();
```

##### Supported functions
```php
$factory->sync()-ЮsyncAnExistingConnection(string $userId, string $connectionId): SyncDto
```

Additional methods
```php
$factory->sync()->withScopes();
```


