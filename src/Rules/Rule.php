<?php
    declare(strict_types=1);

    namespace TestTaskAMItems\Rules;

    use TestTaskAMItems\Basket;
    use TestTaskAMItems\Item;

    abstract class Rule
    {
        /**
         * @var string[]|null
         */
        protected $_excludes = null;

        /**
         * @var string[]|string[][]|null
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
                    if (is_string($include_rule)) {
                        if ($item->getType() == $include_rule) {
                            return true;
                        }
                    } elseif (is_array($include_rule)) {
                        if (in_array($item->getType(), $include_rule)) {
                            return true;
                        }
                    }
                }

                return false;
            }

            if (is_null($this->_excludes)) {
                return true;
            }

            return !in_array($item->getType(), $this->_excludes);
        }
    }
