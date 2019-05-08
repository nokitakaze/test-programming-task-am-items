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
         * Тип товара
         *
         * @return ItemType
         */
        function getType(): ItemType;

        /**
         * Цена товара
         *
         * @return float
         */
        function getPrice(): float;
    }
