<?php
    declare(strict_types=1);

    namespace TestTaskAMItems\Rules;

    /**
     * Class BasketDiscountRule
     * @package TestTaskAMItems\Rules
     *
     * Если пользователь выбрал одновременно 3 продукта, он получает скидку 5% от суммы заказа
     */
    class BasketDiscountRule extends Rule
    {
        /**
         * @var int
         */
        protected $_min_count = 1;

        public function __construct(float $discount, int $min_count, array $excludes = null, $only_includes = null)
        {
            $this->_discount = $discount;
            $this->_only_includes = $only_includes;
            $this->_excludes = $excludes;
            $this->_min_count = $min_count;

            if (!is_null($excludes) and !is_null($only_includes)) {
                throw new AMItemsException('Заданы одновременно единственно возможные вхождения и исключения');
            }
        }

        /**
         * @param \TestTaskAMItems\Item[] $items
         * @return \TestTaskAMItems\Rules\RuleCalculationResponse|null
         */
        public function calculate(array $items)
        {
            if (count($items) < $this->_min_count) {
                return null;
            }

            $items_passed = [];
            $items_not_passed = [];
            foreach ($items as $item) {
                if ($this->canPassRule($item)) {
                    $items_passed[] = $item;
                } else {
                    $items_not_passed[] = $item;
                }
            }

            if (count($items_passed) < $this->_min_count) {
                return null;
            }

            $sum = 0;
            foreach ($items_passed as $item) {
                $sum += $item->getPrice();
            }
            $discount = $sum * $this->_discount;

            $response = new RuleCalculationResponse(
                $items_passed,
                $items_not_passed,
                $discount
            );

            return $response;
        }
    }
