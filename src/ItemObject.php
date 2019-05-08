<?php
    declare(strict_types=1);

    namespace TestTaskAMItems;

    class ItemObject implements Item
    {
        /**
         * @var string
         */
        protected $_type;

        /**
         * @var float
         */
        protected $_price;

        public function __construct(ItemType $type, float $price)
        {
            $this->_type = $type;
            $this->_price = $price;
        }

        /**
         * Тип товара
         *
         * @return \TestTaskAMItems\ItemType
         */
        public function getType(): ItemType
        {
            return $this->_type;
        }

        /**
         * Цена товара
         *
         * @return float
         */
        public function getPrice(): float
        {
            return $this->_price;
        }
    }
