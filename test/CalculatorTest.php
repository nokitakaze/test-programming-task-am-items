<?php
    declare(strict_types=1);

    namespace TestTaskAMItems\Rules\Test;

    use PHPUnit\Framework\TestCase;
    use TestTaskAMItems\Basket;
    use TestTaskAMItems\Calculator;
    use TestTaskAMItems\ItemObject;
    use TestTaskAMItems\ItemType;
    use TestTaskAMItems\Rules\BasketDiscountRule;
    use TestTaskAMItems\Rules\RuleGroup;
    use TestTaskAMItems\Rules\RuleSet;
    use TestTaskAMItems\Rules\TotalCostSelectedItemsRule;

    class CalculatorTest extends TestCase
    {
        /**
         * @return array[]
         * @throws \TestTaskAMItems\Rules\AMItemsException
         */
        protected function dataTestCalculatorSimple(): array
        {
            $type_a = new ItemType('a');
            $type_b = new ItemType('b');
            $type_c = new ItemType('c');
            $type_d = new ItemType('d');
            $type_e = new ItemType('e');
            $type_f = new ItemType('f');
            $type_g = new ItemType('g');
            $type_h = new ItemType('h');
            $type_i = new ItemType('i');
            $type_j = new ItemType('j');
            $type_k = new ItemType('k');
            $type_l = new ItemType('l');
            $type_m = new ItemType('m');

            $a100 = new ItemObject($type_a, 100);
            $a50 = new ItemObject($type_a, 50);
            $a30 = new ItemObject($type_a, 30);

            $b50 = new ItemObject($type_b, 50);
            $b40 = new ItemObject($type_b, 40);

            $c120 = new ItemObject($type_c, 120);

            $d200 = new ItemObject($type_d, 200);

            $e90 = new ItemObject($type_e, 90);
            $e50 = new ItemObject($type_e, 50);
            $f60 = new ItemObject($type_f, 60);
            $g20 = new ItemObject($type_g, 20);

            $k30 = new ItemObject($type_k, 30);
            $l50 = new ItemObject($type_l, 50);
            $m150 = new ItemObject($type_m, 150);

            // Делаем тестовые сеты
            $data = [];

            $subRule1 = new TotalCostSelectedItemsRule(0.1, [$type_a, $type_b]);
            $subRule2 = new TotalCostSelectedItemsRule(0.06, [$type_d, $type_e]);
            $subRule3 = new TotalCostSelectedItemsRule(0.03, [$type_e, $type_f, $type_g]);
            $subRule4 = new TotalCostSelectedItemsRule(0.05, [$type_a, [$type_k, $type_l, $type_m]], 1);
            $subRule5 = new BasketDiscountRule(0.05, 3, [$type_a, $type_c]);
            $subRule6 = new BasketDiscountRule(0.10, 4, [$type_a, $type_c]);
            $subRule7 = new BasketDiscountRule(0.20, 5, [$type_a, $type_c]);

            $rule1 = new RuleGroup([$subRule1]);
            $rule2 = new RuleGroup([$subRule2]);
            $rule3 = new RuleGroup([$subRule3]);
            $rule4 = new RuleGroup([$subRule4]);
            $rule5_7 = new RuleGroup([$subRule7, $subRule6, $subRule5,]);

            $standardRuleSet = new RuleSet([$rule1, $rule2, $rule3, $rule4, $rule5_7]);

            // Нет скидок
            $data[] = [
                new Basket([$a100, $b50]),
                new RuleSet([]),
                0
            ];
            $data[] = [
                new Basket([$a100, $a50]),
                new RuleSet([]),
                0
            ];
            $data[] = [
                new Basket([$a100, clone $a100]),
                new RuleSet([]),
                0
            ];
            // Проверяем что добавление сета не ломает калькуляцию
            $data[] = [
                new Basket([$a100, $a50]),
                $standardRuleSet,
                0
            ];
            $data[] = [
                new Basket([$a100, clone $a100]),
                $standardRuleSet,
                0
            ];

            // Rule 1s
            $data[] = [
                new Basket([$a100, $b50]),
                $standardRuleSet,
                15
            ];
            $data[] = [
                new Basket([$a100, $b50, clone $a100, clone $b50]),
                $standardRuleSet,
                15 * 2
            ];
            $data[] = [
                new Basket([$a100, clone $a100, clone $a100, clone $a100, clone $a100]),
                $standardRuleSet,
                0
            ];
            $data[] = [
                new Basket([$b50, clone $b50, clone $b50, clone $b50, clone $b50]),
                $standardRuleSet,
                50 * 5 * 0.2
            ];
            $data[] = [
                new Basket([$b50, clone $b50, clone $b50, clone $b50]),
                $standardRuleSet,
                50 * 4 * 0.1
            ];
            $data[] = [
                new Basket([$b50, clone $b50, clone $b50]),
                $standardRuleSet,
                50 * 3 * 0.05
            ];
            $data[] = [
                new Basket([$a100, $b50, clone $b50, clone $b50]),
                $standardRuleSet,
                15
            ];
            $data[] = [
                new Basket([$a100, $b50, clone $b50, clone $b50, clone $b50]),
                $standardRuleSet,
                15 + 50 * 3 * 0.05
            ];
            $data[] = [
                new Basket([$a100, $a50, $b50]),
                $standardRuleSet,
                15
            ];
            $data[] = [
                new Basket([$a50, $a100, $b50]),
                $standardRuleSet,
                15
            ];
            $data[] = [
                new Basket([$a50, $b50, $a100]),
                $standardRuleSet,
                15
            ];

            //
            $data[] = [
                new Basket([$a100, $a50, $b50, $d200, $e90]),
                $standardRuleSet,
                15 + 17.4
            ];
            $data[] = [
                new Basket([$a100, $a50, $b50, $d200, $e90, clone $d200, $e50]),
                $standardRuleSet,
                15 + 17.4 + 15
            ];
            // Rule 4
            $data[] = [
                new Basket([$a100, $k30]),
                $standardRuleSet,
                100 * 0.05
            ];
            $data[] = [
                new Basket([$a100, $m150]),
                $standardRuleSet,
                150 * 0.05
            ];
            $data[] = [
                new Basket([$a100, $k30, $m150]),
                $standardRuleSet,
                150 * 0.05
            ];

            // Rule 1 + 4 + 5
            $data[] = [
                new Basket([$a100, $a50, $b50, $k30, clone $k30, $l50, $m150]),
                $standardRuleSet,
                15 + 150 * 0.05 + (30 + 30 + 50) * 0.05
            ];
            $data[] = [
                new Basket([$a100, $a50, $b50, $k30, clone $k30, $m150]),
                $standardRuleSet,
                15 + 150 * 0.05
            ];

            // Rule 3
            $data[] = [
                new Basket([$e90, $f60, $g20]),
                $standardRuleSet,
                (90 + 60 + 20) * 0.03
            ];
            $data[] = [
                new Basket([$e90, $f60, $g20, clone $e90, clone $f60, clone $g20]),
                $standardRuleSet,
                (90 + 60 + 20) * 0.03 * 2
            ];
            $data[] = [
                new Basket([$e50, $e90, $f60, $g20]),
                $standardRuleSet,
                (90 + 60 + 20) * 0.03
            ];
            $data[] = [
                new Basket([$e90, $e50, $f60, $g20]),
                $standardRuleSet,
                (90 + 60 + 20) * 0.03
            ];

            // Rule 3 + 5
            // e,f,g,f,g,f,g
            $data[] = [
                new Basket([$e90, $f60, $g20, clone $f60, clone $g20, clone $f60, clone $g20]),
                $standardRuleSet,
                (90 + 60 + 20) * 0.03 + (60 + 20 + 60 + 20) * 0.1
            ];
            $data[] = [
                new Basket([$e90, $f60, $g20, clone $f60, clone $g20, clone $f60, clone $g20, $a100]),
                $standardRuleSet,
                (90 + 60 + 20) * 0.03 + (60 + 20 + 60 + 20) * 0.1
            ];
            $data[] = [
                new Basket([$e90, $f60, $g20, clone $f60, clone $g20, clone $f60, clone $g20, $b50]),
                $standardRuleSet,
                (90 + 60 + 20) * 0.03 + (60 + 20 + 60 + 20 + 50) * 0.2
            ];
            $data[] = [
                new Basket([$e90, $f60, $g20, clone $f60, clone $g20, clone $f60, clone $g20, $a100, $b50]),
                $standardRuleSet,
                (90 + 60 + 20) * 0.03 + (60 + 20 + 60 + 20) * 0.1 + 15
            ];
            // e,f,g,e,f,g,f,g
            $data[] = [
                new Basket([$e90, $f60, $g20, clone $e90, clone $f60, clone $g20, clone $f60, clone $g20]),
                $standardRuleSet,
                (90 + 60 + 20) * 0.03 * 2
            ];

            // Rule 7 + 8
            $data[] = [
                new Basket([
                    $b50,
                    clone $b50,
                    clone $b50,
                    clone $b50,
                    clone $b50,
                    clone $b50,
                    clone $b50,
                    clone $b50,
                    clone $b50,
                    clone $b50
                ]),// x10
                $standardRuleSet,
                50 * 10 * 0.2
            ];
            $data[] = [
                new Basket([
                    $b50,
                    clone $b50,
                    clone $b50,
                    clone $b50,
                    clone $b50
                ]),// x5
                $standardRuleSet,
                50 * 5 * 0.2
            ];

            return $data;
        }

        /**
         * @return array[]
         * @throws \TestTaskAMItems\Rules\AMItemsException
         */
        public function dataTestCalculator(): array
        {
            $data = [];
            $data = array_merge($data, $this->dataTestCalculatorSimple());

            return $data;
        }

        /**
         * @param \TestTaskAMItems\Basket        $basket
         * @param \TestTaskAMItems\Rules\RuleSet $ruleSet
         * @param float                          $expected_discount
         *
         * @dataProvider dataTestCalculator
         * @throws \Exception
         */
        public function testCalculator(Basket $basket, RuleSet $ruleSet, float $expected_discount)
        {
            $hashes = [];
            $sum = 0;
            foreach ($basket->items as $item) {
                $sum += $item->getPrice();

                $hash = spl_object_hash($item);
                if (in_array($hash, $hashes)) {
                    throw new \Exception('Неправильно заполненные входные данные');
                }
                $hashes[] = $hash;
            }

            $calculator = new Calculator();
            $response = $calculator->calculate($basket, $ruleSet);

            $this->assertEquals($sum, $response->getOriginalPrice());
            $this->assertEquals($expected_discount, $response->getFullDiscountValue());
            $this->assertEquals($sum - $expected_discount, $response->getDiscountedPrice());
        }

        public function testPrimitives()
        {
            // ItemType
            $type_a = new ItemType('a');
            $type_a1 = new ItemType('a');
            $type_b = new ItemType('b');
            $this->assertEquals('A', $type_a->getType());
            $this->assertEquals('B', $type_b->getType());

            $this->assertTrue($type_a->isEqual($type_a1));
            $this->assertFalse($type_a->isEqual($type_b));

            // ItemObject
            $a100 = new ItemObject($type_a, 100);
            $b50 = new ItemObject($type_b, 50);
            $this->assertEquals(100, $a100->getPrice());
            $this->assertEquals(50, $b50->getPrice());

            // Basket
            $basket = new Basket([$a100, $b50]);
            $this->assertEquals(2, count($basket->items));
        }
    }
