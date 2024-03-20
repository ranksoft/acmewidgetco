# Test Task Shopping Basket CLI

Welcome to the Shopping Basket Command Line Interface (CLI). This application allows users to interact with a shopping basket, add products, calculate totals with potential discounts, and apply delivery charges accordingly.

#### Config Path:
* directory: `./config`
* file: `./config/config.php`
* DI: `./config/services.php`
* Delivery conditions: `./config/delivery_conditions.php`
* Special offers: `./config/offers.php`
* Total collectors: `./config/totals.php`

### Installation
To install the application, please follow the steps below:
1. `git clone https://github.com/repo/acme-widget-co-basket.git`
2. `cd acme-widget-co-basket`
3. `docker-compose up --build`
3. `Go to fpm container`
5. `composer install`

## Run app locally
* Start: `docker-compose up`
* Stop: `docker-compose down`

## Testing

Ensure the integrity of the application by running the test suite with the following command:

1. To run the tests: `./vendor/bin/phpunit tests`
2. To run the phpstan analyse: `./vendor/bin/phpstan analyse`

Confirm that all tests pass before committing changes to the project.

## Structure

The project is organized as follows:

- `config/`: Configuration files defining offers, services, delivery conditions, and total calculation rules.
- `src/`: The source code for the application, domain logic, entities, and services.
  - `Application/Services`: Application level services such as formatters.
  - `Domain/`: Domain layer with entities, factories, interfaces, services, strategies, and total collectors.
  - `Infrastructure/`: Infrastructure-related code such as dependency injection and persistence.
- `tests/`: Tests for the application.
- `var/`: Storage for application-generated files.
- `vendor/`: Composer dependencies.

## Usage

Run the `cli.php` file from your terminal to interact with the application:

```bash
php cli.php
```
You'll be prompted with a menu to perform actions like adding products, viewing the cart, calculating the total, or exiting the application.

## Features

- **Add Products to Basket**: Use product codes to add items to your basket.
- **Calculate Total**: Automatically calculate the total cost of items in the basket, including any applicable discounts and delivery charges.
- **View Basket Items**: List all items in your basket with their details, such as name, quantity, and price.
- **User-Friendly Price Display**: Prices are formatted in a clear and readable way thanks to the built-in formatter.

## Extending the Application

To add new products or special offers to the application:

1. Update the configuration files in the `config/` directory.
2. Introduce new entities or strategies within the `Domain/` directory to incorporate the new logic.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details. This means you are free to use, modify, and distribute the project as you see fit.