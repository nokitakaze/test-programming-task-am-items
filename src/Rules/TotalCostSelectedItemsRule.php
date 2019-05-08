<?php
    declare(strict_types=1);

    namespace TestTaskAMItems\Rules;

    use TestTaskAMItems\Item;
    use \TestTaskAMItems\ItemType;

    /**
     * Class TotalCostSelectedItemsRule
     * @package TestTaskAMItems\Rules
     *
     * Если одновременно выбраны А и B, то их суммарная стоимость уменьшается на 10% (для каждой пары А и B)
     * Если одновременно выбраны D и E, то их суммарная стоимость уменьшается на 6% (для каждой пары D и E)
     * Если одновременно выбраны E, F, G, то их суммарная стоимость уменьшается на 3% (для каждой тройки E, F, G)
     */
    class TotalCostSelectedItemsRule extends Rule
    {
        /**
         * @var int
         */
        protected $_item_for_discount_count;

        public function __construct(float $discount, array $only_includes, int $item_for_discount_count = PHP_INT_MAX)
        {
            $this->_discount = $discount;
            $this->_only_includes = $only_includes;
            $this->_item_for_discount_count = $item_for_discount_count;
        }

        /**
         * @param \TestTaskAMItems\Item[] $items
         * @return \TestTaskAMItems\Rules\RuleCalculationResponse|null
         */
        public function calculate(array $items)
        {
            $items_left = $items;
            $items_passed = [];
            $discount = 0;
            while (!empty($items_left)) {
                $sub_response = $this->calculateIteration($items_left);
                if (is_null($sub_response)) {
                    break;
                }
                $items_passed = array_merge($items_passed, $sub_response->getItemsPassed());
                $items_left = $sub_response->getItemsNotPassed();
                $discount += $sub_response->getDiscountValue();
            }

            if (empty($items_passed)) {
                return null;
            }

            $response = new RuleCalculationResponse(
                $items_passed,
                $items_left,
                $discount
            );

            return $response;
        }

        /**
         * @param \TestTaskAMItems\Item[] $items
         * @return \TestTaskAMItems\Rules\RuleCalculationResponse|null
         */
        public function calculateIteration(array $items)
        {
            /**
             * @var Item[] $items_by_groups
             * @var Item[] $items_left
             */
            $items_by_groups = [];
            $items_left = [];
            foreach ($items as $item) {
                $items_left[spl_object_hash($item)] = $item;
            }
            foreach ($this->_only_includes as $item_group) {
                /**
                 * @var Item[] $this_group_items
                 * @var ItemType[] $item_group_standard
                 */
                $this_group_items = [];
                $item_group_standard = is_array($item_group) ? $item_group : [$item_group];

                // Можно сделать через array_filter
                foreach ($items_left as $item) {
                    $u = false;
                    foreach ($item_group_standard as $type) {
                        if ($type->isPassItem($item)) {
                            $u = true;
                            break;
                        }
                    }

                    if ($u) {
                        $this_group_items[] = $item;
                    }
                }
                if (empty($this_group_items)) {
                    return null;
                }

                // hint: Задача не говорит нам как поступать, когда в корзине один и тот же товар встречается с разными ценами
                // поэтому я сортирую товары так, чтобы дать наибольшую скидку
                // опционально можно добавить в правило один из трёх вариантов выбора товара: по упорядоченности,
                // по самому дешёвому и по самому дорогому (как здесь и сделано)
                usort($this_group_items, function (Item $a, Item $b) {
                    return $b->getPrice() <=> $a->getPrice();
                });

                $items_by_groups[] = $this_group_items[0];
                unset($items_left[spl_object_hash($this_group_items[0])]);
            }

            // hint: Если одновременно выбраны А и один из [K, L, M], то стоимость выбранного продукта уменьшается на 5%
            // Задача не даёт нам определение "выбранного продукта", поэтому я просто выбираю N самых дорогих из тех,
            // что прошли фильтр
            usort($items_by_groups, function (Item $a, Item $b) {
                return $b->getPrice() <=> $a->getPrice();
            });

            $need_count = min($this->_item_for_discount_count, count($items_by_groups));
            $sum = 0;
            for ($i = 0; $i < $need_count; $i++) {
                $sum += $items_by_groups[$i]->getPrice();
            }
            $discount = $sum * $this->_discount;

            $response = new RuleCalculationResponse(
                array_values($items_by_groups),
                array_values($items_left),
                $discount
            );

            return $response;
        }
    }
