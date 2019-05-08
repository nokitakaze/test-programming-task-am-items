<?php
    declare(strict_types=1);

    namespace TestTaskAMItems;

    use TestTaskAMItems\Rules\RuleCalculationResponse;
    use TestTaskAMItems\Rules\RuleSet;

    class CalculatorResponse
    {
        /**
         * @var RuleCalculationResponse[]
         */
        protected $_rule_responses = [];

        /**
         * @var \TestTaskAMItems\Basket $_basket
         */
        protected $_basket;

        /**
         * @var \TestTaskAMItems\Rules\RuleSet $_basket
         */
        protected $_ruleSet;

        public function __construct(Basket $basket, RuleSet $ruleSet)
        {
            $this->_basket = $basket;
            $this->_ruleSet = $ruleSet;
        }

        public function addRuleResponse(RuleCalculationResponse $ruleResponse)
        {
            $this->_rule_responses[] = $ruleResponse;
        }

        /**
         * Цена изначальной корзины (без скидок)
         *
         * @return float
         */
        public function getOriginalPrice(): float
        {
            $sum = 0;
            foreach ($this->_basket->items as $item) {
                $sum += $item->getPrice();
            }

            return $sum;
        }

        /**
         * Общая сумма всех применённых скидок
         *
         * @return float
         */
        public function getFullDiscountValue(): float
        {
            $sum = 0;
            foreach ($this->_rule_responses as $response) {
                $sum += $response->getDiscountValue();
            }

            return $sum;
        }

        /**
         * Цена всей корзины после всех скидок
         *
         * @return float
         */
        public function getDiscountedPrice(): float
        {
            return $this->getOriginalPrice() - $this->getFullDiscountValue();
        }
    }
