<?php
    declare(strict_types=1);

    namespace TestTaskAMItems\Rules;

    class RuleCalculationResponse
    {
        /**
         * @var float
         */
        protected $_discount_value;

        /**
         * @var \TestTaskAMItems\Item[]
         */
        protected $_items_passed;

        /**
         * @var \TestTaskAMItems\Item[]
         */
        protected $_items_not_passed;

        /**
         * RuleCalculationResponse constructor.
         * @param array $items_passed
         * @param array $items_not_passed
         * @param float $discount
         */
        public function __construct(array $items_passed, array $items_not_passed, float $discount)
        {
            $this->_items_passed = $items_passed;
            $this->_items_not_passed = $items_not_passed;
            $this->_discount_value = $discount;
        }

        public function getDiscountValue(): float
        {
            return $this->_discount_value;
        }

        public function getItemsPassed(): array
        {
            return $this->_items_passed;
        }

        public function getItemsNotPassed(): array
        {
            return $this->_items_not_passed;
        }
    }
