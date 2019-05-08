<?php
    declare(strict_types=1);

    namespace TestTaskAMItems;

    class ItemObject implements Item
    {
        /**
         * @var string
         */
        public $type;

        /**
         * @var float
         */
        public $price;

        /**
         * Это не геттер поля, это имплементация сигнатуры code contract
         *
         * @return string
         */
        public function getType(): string
        {
            return mb_strtoupper(mb_substr($this->type, 0, 1));
        }

        /**
         * Это не геттер поля, это имплементация сигнатуры code contract
         *
         * @return float
         */
        public function getPrice(): float
        {
            return $this->price;
        }
    }
