<?php
declare(strict_types=1);

use AcmeWidgetCo\Domain\Interfaces\BasketInterface;
use AcmeWidgetCo\Domain\Interfaces\FormatterInterface;
use AcmeWidgetCo\Infrastructure\DI\DIContainer;

require_once 'vendor/autoload.php';

$di = new DIContainer();
$priceFormatter = $di->get(FormatterInterface::class);
$basket = $di->get(BasketInterface::class);

while (true) {
    echo "---------------------------\n";
    echo "Choose an action:\n";
    echo "1. Add product to cart;\n";
    echo "2. Get the grand total;\n";
    echo "3. Show cart;\n";
    echo "4. \033[0;31mExit.\033[0m\n";
    echo "----------------------------\n";
    $choice = trim((string)fgets(STDIN));

    switch ($choice) {
        case "1":
            echo "Enter product code:\n";
            $code = trim((string)fgets(STDIN));
            $basket->add($code);
            echo "\033[0;32mThe product has been added to your cart.\033[0m\n";
            break;
        case "2":
            $total = $basket->getTotal()->getGrandTotal();
            echo "Total cart without formatter: \033[0;32m", $total->getAmount(), "\033[0m ", $total->getCurrency(), "\n";
            echo "Total cart: \033[0;32m", $priceFormatter->format($total), "\033[0m ", $total->getCurrency(), "\n";
            break;
        case "3":
            $items = $basket->getItems();
            if (count($items) == 0) {
                echo "\033[0;33mCart is empty.\033[0m\n";
            } else {
                foreach ($items as $item) {
                    echo "Product: ", $item->getProduct()->getName(),
                    ", Code: \033[0;34m", $item->getProduct()->getCode(), "\033[0m",
                    ", Quantity: ", $item->getQuantity(),
                    ", Price: \033[0;32m", $priceFormatter->format($item->getPrice()), "\033[0m ", $item->getPrice()->getCurrency(),
                    ", Price with discount: \033[0;32m", $priceFormatter->format($item->getPriceWithDiscount()), "\033[0m ", $item->getPriceWithDiscount()->getCurrency(), "\n";
                }
            }
            break;
        case "4":
            echo "\033[0;31mExit.\033[0m\n";
            exit(0);
        default:
            echo "Incorrect choice. Please select one of the options provided.\n";
    }
}
