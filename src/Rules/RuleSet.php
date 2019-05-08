<?php
    declare(strict_types=1);

    namespace TestTaskAMItems\Rules;

    /**
     * Class RuleSet
     * Весь сет правил для калькулятора
     * @package TestTaskAMItems\Rules
     */
    class RuleSet
    {
        /**
         * @var RuleGroup[]
         */
        protected $_group;

        public function __construct(array $group)
        {
            $array = [];
            foreach ($group as $rule) {
                if ($rule instanceof RuleGroup) {
                    $array[] = $rule;
                } elseif ($rule instanceof Rule) {
                    $array[] = new RuleGroup([$rule]);
                } else {
                    throw new AMItemsException();
                }
            }

            $this->_group = $array;
        }

        /**
         * @return RuleGroup[]
         */
        public function getGroup(): array
        {
            return $this->_group;
        }
    }
