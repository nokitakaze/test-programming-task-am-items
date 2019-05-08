<?php

    namespace TestTaskAMItems;

    class ItemType
    {
        protected $_type;

        public function __construct(string $type)
        {
            $this->_type = mb_strtoupper(mb_substr($type, 0, 1));
        }

        public function getType()
        {
            return $this->_type;
        }

        public function isPassItem(Item $item): bool
        {
            return $item->getType()->isEqual($this);
        }

        public function isEqual(ItemType $type): bool
        {
            return ($this->_type == $type->_type);
        }
    }