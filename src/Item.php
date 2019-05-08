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
        public function getType(): ItemType;

        /**
         * Цена товара
         *
         * @return float
         */
        public function getPrice(): float;
    }
