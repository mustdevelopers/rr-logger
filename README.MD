# RRLogger - Request and Response Logger for Laravel

`RRLogger` is a Laravel package designed to log incoming and outgoing HTTP requests and responses. This package provides detailed insights into API interactions, offering significant benefits for debugging and auditing.

## Features

- **Automatic Middleware Registration**: The package automatically registers its middleware for logging incoming requests.
- **Configurable Table Name**: Customize the name of the database table where logs are stored.
- **Configurable Logging Options**: Control which fields are hidden from logs and set maximum content lengths.
- **Supports Laravel HTTP Client**: Logs outgoing HTTP requests and responses when using Laravel's HTTP client.
- **Enhanced Debugging**: Provides detailed logs of all API interactions, making it easier to debug issues.
- **Auditing**: Facilitates auditing by maintaining comprehensive records of requests and responses, which can be invaluable for compliance and troubleshooting.

## Installation

### Step 1: Add the Package to Your Laravel Project

1. **Add the package to your `composer.json`**:

   ```json
   "require": {
       "must/rrlogger": "dev-main"
   }
   ```

2. **Update Composer**:

   ```bash
   composer update
   ```

### Step 2: Publish the Configuration and Migrations

1. **Publish the configuration file and migration**:

   ```bash
   php artisan vendor:publish --provider="MUST\RRLogger\Providers\RRLoggerServiceProvider"
   ```

   This command publishes the `rrlogger.php` configuration file to your application's `config` directory and the migration file to your `database/migrations` directory.

2. **Run the migration to create the logs table**:

   ```bash
   php artisan migrate
   ```

## Configuration

### Configuration File

The configuration file is located at `config/rrlogger.php`. You can configure various options related to logging, such as retention days, hidden fields, table name, and maximum content length.

**Example Configuration:**

```php
return [
    'retention_days' => env('RRLOGGER_RETENTION_DAYS', 30),
    'hidden_fields' => env('RRLOGGER_HIDDEN_FIELDS', 'password,pin,new_pin'),
    'table_name' => env('RRLOGGER_TABLE_NAME', 'rrloggers'),
    'max_content_length' => env('RRLOGGER_MAX_CONTENT_LENGTH', 65535),
];
```

- `retention_days`: Number of days to retain the logs in the database.
- `hidden_fields`: Comma-separated list of fields to exclude from request logging.
- `table_name`: Name of the database table where logs are stored.
- `max_content_length`: Maximum length of content to log. Content longer than this value will be truncated.

### Middleware Configuration

The `WriteRRLogs` middleware is automatically registered by the package and added to the `api` middleware group. You do not need to manually configure this in your Laravel application's `Kernel.php`.

## Usage

### Logging Incoming Requests and Responses

The `WriteRRLogs` middleware logs incoming HTTP requests and responses automatically. Ensure the middleware is correctly registered by the package; no manual setup is needed in your Laravel application's `Kernel.php`.

### Logging Outgoing Requests and Responses

To log outgoing HTTP requests made via Laravel’s HTTP client, extend the HTTP client with the `RRLoggerHttpClient` class.

**Example Usage:**

```php
use MUST\RRLogger\Http\HttpClient\RRLoggerHttpClient;

$response = RRLoggerHttpClient::post('https://example.com/api/test-endpoint', [
    'key' => 'value',
]);

// Assert that the response was successful
$response->assertStatus(200);
```

## Benefits

### Enhanced Debugging

By logging detailed request and response data, `RRLogger` helps identify and resolve issues more efficiently. The comprehensive logs provide insights into API interactions, making it easier to debug and trace problems.

### Auditing

`RRLogger` facilitates auditing by maintaining detailed records of all API requests and responses. This capability is useful for compliance, monitoring, and troubleshooting, ensuring that you have a reliable audit trail of interactions with your application.

## Contributing

If you wish to contribute to the development of `RRLogger`, follow these steps:

1. Fork the repository.
2. Create a new branch for your feature or bug fix.
3. Commit your changes and push them to your fork.
4. Open a pull request describing your changes.

## License

`RRLogger` is licensed under the MIT License. See the [LICENSE](LICENSE) file for more details.

## Support

For any issues or support, please open an issue in the [GitHub repository](https://github.com/mustdevelopers/rr-logger/issues).