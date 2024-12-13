<?php
declare(strict_types= 1);

echo 'Программа для работы с списком покупок' . PHP_EOL;

const OPERATION_EXIT = 0;
const OPERATION_ADD = 1;
const OPERATION_DELETE = 2;
const OPERATION_PRINT = 3;

$operations = [
    OPERATION_EXIT => OPERATION_EXIT . '. Завершить программу.',
    OPERATION_ADD => OPERATION_ADD . '. Добавить товар в список покупок.',
    OPERATION_DELETE => OPERATION_DELETE . '. Удалить товар из списка покупок.',
    OPERATION_PRINT => OPERATION_PRINT . '. Отобразить список покупок.',
];

$items = [];


do {
    // system('clear');
   system('cls'); // windows

    do {
        // if (count($items)) {
        //     echo 'Ваш список покупок: ' . PHP_EOL;
        //     echo implode("\n", $items) . "\n";
        //     echo PHP_EOL;
        // } else {
        //     echo 'Ваш список покупок пуст.' . PHP_EOL;
        // }
        printShoppingList($items);
        printEmptyString();


        echo 'Выберите операцию для выполнения: ' . PHP_EOL;
        // Проверить, есть ли товары в списке? Если нет, то не отображать пункт про удаление товаров
        echo implode(PHP_EOL, $operations) . PHP_EOL . '> ';
        $operationNumber = getStringSTDIN();

        if (!array_key_exists($operationNumber, $operations)) {
            system('clear');

            echo '!!! Неизвестный номер операции, повторите попытку.' . PHP_EOL;
        }

    } while (!array_key_exists($operationNumber, $operations));

    echo 'Выбрана операция: '  . $operations[$operationNumber] . PHP_EOL;

    switch ($operationNumber) {
        case OPERATION_ADD:
            echo "Введение название товара для добавления в список: \n> ";
            $itemName = getStringSTDIN();
            $items[] = $itemName;
            break;

        case OPERATION_DELETE:
            // Проверить, есть ли товары в списке? Если нет, то сказать об этом и попросить ввести другую операцию
            echo 'Текущий список покупок:' . PHP_EOL;
            echo 'Список покупок: ' . PHP_EOL;
            echo implode("\n", $items) . "\n";

            echo 'Введение название товара для удаления из списка:' . PHP_EOL . '> ';
            $itemName = getStringSTDIN();

            if (in_array($itemName, $items, true) !== false) {
                while (($key = array_search($itemName, $items, true)) !== false) {
                    unset($items[$key]);
                }
            }
            break;

        case OPERATION_PRINT:
            printShoppingList($items, true);
            break;
    }

    echo "\n ----- \n";
} while ($operationNumber > 0);

echo 'Программа завершена' . PHP_EOL;

/**
 * Reads a line of input from STDIN and returns it as a string.
 *
 * The trailing newline character is removed by the trim function.
 *
 * @return string
 */
function getStringSTDIN(): string
{
    return trim(fgets(STDIN));
}

/**
 * Prints an empty line.
 *
 * This function is used to separate the output of the program into logical blocks.
 *
 * @return void
 */
function printEmptyString(): void
{
    echo PHP_EOL;
}

/**
 * Prints the list of items in a shopping list.
 *
 * If the list is empty, it outputs a message to that effect.
 *
 * If $pause is true, it will output a message with the total number of items
 * and wait for the user to press enter before continuing.
 *
 * @param array $list The list of items in the shopping list.
 * @param bool $pause Whether to wait for the user to press enter before continuing.
 *
 * @return void
 */
function printShoppingList(array $list, bool $pause = false): void
{
    if (!count($list)) {
        echo 'Ваш список покупок пуст.' . PHP_EOL;
        return;
    }

    echo 'Ваш список покупок: ' . PHP_EOL;
    echo implode(PHP_EOL, $list) . PHP_EOL;

    if ($pause) {
    echo 'Всего ' . count($list) . ' позиций. '. PHP_EOL;
    echo 'Нажмите enter для продолжения';
    fgets(STDIN);
    }
}
