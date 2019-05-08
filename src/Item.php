<?php
    declare(strict_types=1);

    namespace TestTaskAMItems;

    /**
     * Интерфейс товара
     *
     * @package TestTaskAMItems
     */
    interface Item
    {
        /**
         * Тип товара. Одна буква в upper case
         *
         * @return string
         */
        function getType(): string;

        /**
         * Цена товара
         *
         * @return float
         */
        function getPrice(): float;
    }
