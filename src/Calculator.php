<?php
    declare(strict_types=1);

    namespace TestTaskAMItems;

    use TestTaskAMItems\Rules\RuleSet;

    class Calculator
    {
        /**
         * В случае объекта с одной чистой функцией, который никогда не будет изменяться и не обращается к своим полям,
         * я бы оформил метод как статичным. Вариант с нестатичным методом ТОЛЬКО расширения класса
         *
         * @param \TestTaskAMItems\Basket $basket
         * @param \TestTaskAMItems\Rules\RuleSet $ruleSet
         * @return \TestTaskAMItems\CalculatorResponse
         */
        public function calculate(Basket $basket, RuleSet $ruleSet): CalculatorResponse
        {
            $response = new CalculatorResponse($basket, $ruleSet);

            $items_left = $basket->items;
            foreach ($ruleSet->getGroups() as $group) {
                foreach ($group->getGroups() as $rule) {
                    $ruleResponse = $rule->calculate($items_left);
                    if (is_null($ruleResponse)) {
                        // Правильно не сработало
                        continue;
                    }

                    $response->addRuleResponse($ruleResponse);

                    break;
                }
            }

            return $response;
        }
    }
