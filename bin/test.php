#!/usr/bin/env php
<?php
    require_once __DIR__ . '/../vendor/autoload.php';

    use TestTaskAMItems\Basket;
    use TestTaskAMItems\Calculator;
    use TestTaskAMItems\ItemType;
    use TestTaskAMItems\ItemObject;
    use TestTaskAMItems\Rules\BasketDiscountRule;
    use TestTaskAMItems\Rules\RuleGroup;
    use TestTaskAMItems\Rules\RuleSet;
    use TestTaskAMItems\Rules\TotalCostSelectedItemsRule;

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

    // Берём данные из argc
    $options = getopt('i:');
    $items = [];
    foreach (preg_split('_[,;\\s]_', $options['i']) as $sub_option) {
        $sub_option = trim($sub_option);
        if (empty($sub_option)) {
            continue;
        }

        $a = explode('=', $sub_option);
        $item = new ItemObject(new ItemType($a[0]), floatval($a[1]));
        $items[] = $item;
    }
    $basket = new Basket($items);
    $calculator = new Calculator();
    $response = $calculator->calculate($basket, $standardRuleSet);

    echo sprintf(
        "Original price:\t\t%s\n" . "Discount sum:\t\t%s\n" . "Discounted price:\t%s\n",
        $response->getOriginalPrice(),
        $response->getFullDiscountValue(),
        $response->getDiscountedPrice()
    );

?>