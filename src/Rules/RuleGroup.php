<?php
    declare(strict_types=1);

    namespace TestTaskAMItems\Rules;

    /**
     * Class RuleGroup
     * Группа взаимоисключающих правил
     * @package TestTaskAMItems\Rules
     */
    class RuleGroup
    {
        /**
         * @var Rule[]
         */
        protected $_group;

        public function __construct(array $group)
        {
            foreach ($group as $rule) {
                if (!($rule instanceof Rule)) {
                    throw new AMItemsException();
                }
            }

            $this->_group = $group;
        }

        /**
         * @return Rule[]
         */
        public function getGroups(): array
        {
            return $this->_group;
        }
    }
