<?php
    declare(strict_types=1);

    namespace TestTaskAMItems\Rules\Test;

    use PHPUnit\Framework\TestCase;
    use TestTaskAMItems\Basket;
    use TestTaskAMItems\Calculator;
    use TestTaskAMItems\Rules\RuleSet;

    class CalculatorTest extends TestCase
    {
        protected function dataTestCalculatorSimple(): array
        {
            return [];
        }

        public function dataTestCalculator(): array
        {
            $data = [];
            $data = array_merge($data, $this->dataTestCalculatorSimple());

            return $data;
        }

        /**
         * @param \TestTaskAMItems\Basket $basket
         * @param \TestTaskAMItems\Rules\RuleSet $ruleSet
         * @param float $expected_discount
         *
         * @dataProvider dataTestCalculator
         */
        public function testCalculator(Basket $basket, RuleSet $ruleSet, float $expected_discount)
        {
            $calculator = new Calculator();
            $response = $calculator->calculate($basket, $ruleSet);

            $sum = 0;
            foreach ($basket->items as $item) {
                $sum += $item->getPrice();
            }

            $this->assertEquals($sum, $response->getOriginalPrice());
            $this->assertEquals($expected_discount, $response->getDiscountedPrice());
        }
    }