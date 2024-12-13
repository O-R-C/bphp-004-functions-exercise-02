<?php

declare(strict_types=1);

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

// ========== Common

/**
 * Clears the console screen.
 *
 * This function uses the system() function to call the underlying OS's
 * command to clear the console screen. On Windows, this is 'cls', on
 * other systems, it is 'clear'.
 *
 * @return void
 */
function clearScreen(): void
{
    // system('clear');
    system('cls'); // windows
}

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

// ========== Shopping list

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
        printEmptyList();
        return;
    }

    printList($list);

    if (!$pause) {
        return;
    }

    printCount($list);
}

/**
 * Prints a message indicating that the shopping list is empty.
 *
 * Outputs a predefined message to inform the user that there are no items
 * in the shopping list.
 *
 * @return void
 */
function printEmptyList(): void
{
    echo 'Ваш список покупок пуст' . PHP_EOL;
}

/**
 * Prints the list of items in the shopping list.
 *
 * Outputs each item in the list on a new line preceded by a header.
 *
 * @param array $list The list of items to be printed.
 *
 * @return void
 */
function printList(array $list): void
{
    echo 'Ваш список покупок: ' . PHP_EOL;
    echo implode(PHP_EOL, $list) . PHP_EOL;
}

/**
 * Outputs a message with the total number of items in the list and waits for the user to press enter before continuing.
 *
 * @param array $list The list of items to be counted.
 *
 * @return void
 */
function printCount(array $list): void
{
    echo 'Всего ' . count($list) . ' позиций. ' . PHP_EOL;
    echo 'Нажмите enter для продолжения';
    fgets(STDIN);
}


// ========== Operations

function printOperations(array $operations): void
{
    echo 'Выберите операцию для выполнения: ' . PHP_EOL;
    echo implode(PHP_EOL, $operations) . PHP_EOL . '> ';
};

function getCurrentOperations(array $operations, array $items): array
{
    return $items ? $operations : array_slice($operations, 0, 2);
}

function keyExists(mixed $key, array $array): bool
{
    return array_key_exists($key, $array);
}


function printWrongOperation(): void
{
    system('clear');
    echo '!!! Неизвестный номер операции, повторите попытку.' . PHP_EOL;
};

// ========== Main

$operationNumber = '';
$keyExist = false;

function selectOperation(mixed &$operationNumber, bool $keyExist, array $operations, array $items): void
{
    if ($keyExist) return;

    printShoppingList($items);
    printEmptyString();

    $currentOperations = getCurrentOperations($operations, $items);
    printOperations($currentOperations);

    $operationNumber = getStringSTDIN();
    $keyExist = keyExists($operationNumber, $currentOperations);

    if (!$keyExist) {
        printWrongOperation();
        printEmptyString();
    }

    selectOperation($operationNumber, $keyExist, $operations, $items);
}

do {
    clearScreen();
    selectOperation($operationNumber, $keyExist, $operations, $items);

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
