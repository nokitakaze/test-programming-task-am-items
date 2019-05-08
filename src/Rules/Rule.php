<?php
    declare(strict_types=1);

    namespace TestTaskAMItems\Rules;

    use TestTaskAMItems\Item;
    use TestTaskAMItems\ItemType;

    abstract class Rule
    {
        /**
         * @var ItemType[]|null
         */
        protected $_excludes = null;

        /**
         * @var ItemType[]|ItemType[][]|null
         */
        protected $_only_includes = null;

        /**
         * @var float
         */
        protected $_discount;

        /**
         * @param \TestTaskAMItems\Item[] $items
         * @return \TestTaskAMItems\Rules\RuleCalculationResponse|null
         */
        public abstract function calculate(array $items);

        public function canPassRule(Item $item): bool
        {
            if (!is_null($this->_only_includes)) {
                foreach ($this->_only_includes as $include_rule) {
                    if (is_array($include_rule)) {
                        foreach ($include_rule as $sub_rule) {
                            if ($sub_rule->isPassItem($item)) {
                                return true;
                            }
                        }
                    } elseif ($include_rule->isPassItem($item)) {
                        return true;
                    }
                }

                return false;
            }

            if (is_null($this->_excludes)) {
                return true;
            }

            foreach ($this->_excludes as $type) {
                if ($type->isPassItem($item)) {
                    return false;
                }
            }

            return true;
        }
    }
