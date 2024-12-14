<?php

declare(strict_types=1);

const OPERATION_EXIT = 0;
const OPERATION_ADD = 1;
const OPERATION_DELETE = 2;
const OPERATION_PRINT = 3;
const OPERATION_CHANGE = 4;

$operations = [
    OPERATION_EXIT => OPERATION_EXIT . '. Завершить программу.',
    OPERATION_ADD => OPERATION_ADD . '. Добавить товар в список покупок.',
    OPERATION_DELETE => OPERATION_DELETE . '. Удалить товар из списка покупок.',
    OPERATION_PRINT => OPERATION_PRINT . '. Отобразить список покупок.',
    OPERATION_CHANGE => OPERATION_CHANGE . '. Изменить количество товара в списке покупок.',
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
    system('clear');
    // system('cls'); // windows
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
 * Outputs a string to the console followed by a newline.
 *
 * @param string $string The string to be printed.
 *
 * @return void
 */
function printString(string $string, bool $newline = true): void
{
    echo $string . ($newline ? PHP_EOL : '');
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
    printString('');
}

/**
 * Checks if a given key exists in an array.
 *
 * This function utilizes the built-in array_key_exists to determine if
 * a specified key is present in the provided array.
 *
 * @param mixed $key The key to search for in the array.
 * @param array $array The array in which to search for the key.
 *
 * @return bool True if the key exists in the array, false otherwise.
 */
function keyExists(mixed $key, array $array): bool
{
    return array_key_exists($key, $array);
}

/**
 * Checks if a given value exists in an array.
 *
 * This function iterates over the provided array and checks if the value of each
 * item is equal to the given value. If it finds a match, it returns true. If it
 * iterates over the entire array without finding a match, it returns false.
 *
 * @param mixed $value The value to search for in the array.
 * @param array $array The array in which to search for the value.
 *
 * @return bool True if the value exists in the array, false otherwise.
 */
function valueExists(mixed $value, array $array): bool
{
    return in_array($value, $array, true);
}

/**
 * Outputs a message asking the user to press enter to continue and then waits
 * for the user to press enter before continuing.
 *
 * @return void
 */
function waitEnter(): void
{
    printString('Нажмите enter для продолжения');
    fgets(STDIN);
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
    printString('Ваш список покупок пуст');
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
    printString('Ваш список покупок: ');

    foreach ($list as $key => $value) {
        printString("$key - {$value}шт.");
    }
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
    $count = count($list);
    $lastWord = getCorrectWord($count);
    $amount = "Всего $count $lastWord";
    printString($amount);
    waitEnter();
}

/**
 * Returns the correct word for the given count of items.
 *
 * This function takes an integer as an argument and returns a string with the
 * correct word for the given count, taking into account the Russian language
 * rules for pluralization.
 *
 * @param int $count The count of items.
 *
 * @return string The correct word for the given count.
 */
function getCorrectWord(int $count): string
{
    $stringCount = (string) $count;

    if ((substr($stringCount, -1) > 4 && substr($stringCount, -2) < 21))
        return 'позиций';

    if ($stringCount[-1] == 1)
        return 'позиция';

    if ($stringCount[-1] > 4)
        return 'позиций';

    return 'позиции';
}


// ========== Operations

/**
 * Outputs a list of operations to the user and prompts them to select one.
 *
 * This function is used to ask the user to select an operation from the
 * list of available operations. The function outputs the list of operations
 * and waits for user input.
 *
 * @param array $operations The list of operations to be printed.
 *
 * @return void
 */
function printOperations(array $operations): void
{
    printString('Выберите операцию для выполнения: ');
    echo implode(PHP_EOL, $operations) . PHP_EOL . '> ';
};

/**
 * Returns the current list of operations, or a subset of them if the shopping list is empty.
 *
 * If the shopping list is empty, it will return a subset of the operations that does not include
 * the operations for showing the list and deleting items. Otherwise, it will return the full list of operations.
 *
 * @param array $operations The full list of operations.
 * @param array $items The current shopping list.
 *
 * @return array The current list of operations.
 */
function getCurrentOperations(array $operations, array $items): array
{
    return $items ? $operations : array_slice($operations, 0, 2);
}

/**
 * Outputs an error message indicating that the selected operation is unknown.
 *
 * This function is used when the user enters an operation number that does
 * not exist in the list of available operations. It clears the screen and
 * outputs an error message asking the user to try again.
 *
 * @return void
 */

function printWrongOperation(): void
{
    clearScreen();
    printString('!!! Неизвестный номер операции, повторите попытку.');
};

/**
 * Prints the selected operation.
 *
 * This function outputs a message indicating which operation has been
 * selected from the list of available operations. It first prints an empty
 * line for separation and then outputs the name of the selected operation.
 *
 * @param array $operations The list of available operations.
 * @param mixed $operationNumber The number of the selected operation.
 *
 * @return void
 */
function printSelectedOperation(array $operations, mixed $operationNumber): void
{
    printEmptyString();
    $operation = 'Выбрана операция: ' . $operations[$operationNumber];
    printString($operation);
    printEmptyString();
}

// ========== Handle Operation

/**
 * Handles the selected operation on the shopping list.
 *
 * This function takes the list of items and the selected operation number
 * as arguments. It executes the corresponding operation based on the
 * operation number, such as adding, deleting, or printing items in the 
 * shopping list.
 *
 * @param array $items The list of items in the shopping list.
 * @param string $operationNumber The number of the operation to be executed.
 *
 * @return void
 */
function handleOperation(array &$items, string $operationNumber): void
{
    switch ($operationNumber) {
        case OPERATION_ADD:
            operationAdd($items);
            break;

        case OPERATION_DELETE:
            operationDelete($items);
            break;

        case OPERATION_PRINT:
            printShoppingList($items, true);
            break;
    }
}

/**
 * Adds an item to the shopping list.
 *
 * Prompts the user to enter the name of the item to be added to the list.
 * If the user enters an empty string, it outputs an error message and
 * does not add the item to the list.
 *
 * @param array $items The list of items to which to add the new item.
 *
 * @return void
 */
function operationAdd(array &$items): void
{
    printString('Введение название товара для добавления в список:');
    printString('> ', false);
    $itemName = getStringSTDIN();

    if (!$itemName) {
        printString('Название товара не может быть пустым.');
        return;
    }

    $existingItem = getExistingItem($itemName, $items);
    if (getExistingItem($itemName, $items)) {
        $items[$itemName]++;
        return;
    }

    $items[$itemName] = 1;
}

function getExistingItem(mixed $itemName, array $items): mixed
{
    return $items[$itemName] ?? null;
}

/**
 * Deletes an item from the shopping list.
 *
 * Prompts the user to enter the name of the item to be deleted from the list.
 * If the user enters an empty string, it outputs an error message and
 * does not delete the item from the list.
 *
 * @param array $items The list of items from which to delete the item.
 *
 * @return void
 */
function operationDelete(array &$items): void
{
    printList($items);
    printEmptyString();
    printString('Введение название товара для удаления из списка:');
    printString('> ', false);

    $itemName = getStringSTDIN();

    if (!valueExists($itemName, $items)) {
        printString('Такого товара нет в списке.');
        waitEnter();
        return;
    }

    deleteItem($itemName, $items);
}

/**
 * Deletes all occurrences of an item from the shopping list.
 *
 * This function takes an item name and a reference to the shopping list as
 * arguments. It searches for the item in the list and if it finds it, it
 * deletes all occurrences of the item from the list. If the item is not found,
 * the function does nothing.
 *
 * @param mixed $itemName The name of the item to be deleted.
 * @param array $items The shopping list from which to delete the item.
 *
 * @return void
 */
function deleteItem(mixed $itemName, array &$items): void
{
    $key = array_search($itemName, $items, true);

    if ($key === false) return;

    unset($items[$key]);
    deleteItem($itemName, $items);
}

// ========== Select Operation

/**
 * Prompts the user to select an operation from the list of available operations.
 *
 * This function is used to ask the user to select an operation from the
 * list of available operations. The function outputs the list of operations
 * and waits for user input. If the entered operation number is not valid
 * (i.e. does not exist in the list of available operations), the function
 * clears the screen, outputs an error message asking the user to try again
 * and then calls itself recursively until the user enters a valid operation
 * number.
 *
 * @param mixed $operationNumber The number of the operation to be selected.
 * @param array $operations The list of available operations.
 * @param array $items The list of items in the shopping list.
 *
 * @return void
 */
function selectOperation(mixed &$operationNumber, array $operations, array $items): void
{
    printShoppingList($items);
    printEmptyString();

    $currentOperations = getCurrentOperations($operations, $items);
    printOperations($currentOperations);

    $operationNumber = getStringSTDIN();

    if (!keyExists($operationNumber, $currentOperations)) {
        printWrongOperation();
        printEmptyString();
        selectOperation($operationNumber, $operations, $items);
    }
}

// ========== Main

/**
 * Starts the main loop of the program.
 *
 * This function is the entry point of the program. It clears the screen,
 * prompts the user to select an operation, prints the selected operation,
 * handles the operation, and then prints a separator line. If the operation
 * was not the exit operation, it calls itself recursively until the user
 * selects the exit operation. If the user selects the exit operation, it
 * outputs a message indicating that the program is finished.
 *
 * @param array $items The list of items in the shopping list.
 * @param array $operations The list of available operations.
 *
 * @return void
 */
function start(array &$items, array $operations): void
{
    clearScreen();
    selectOperation($operationNumber, $operations, $items);
    printSelectedOperation($operations, $operationNumber);
    handleOperation($items, $operationNumber);
    printString("\n ----- ");

    if ($operationNumber > 0) {
        start($items, $operations);
    } else {
        printString('Программа завершена.');
    }
}

// ========== Start Program

start($items, $operations);
