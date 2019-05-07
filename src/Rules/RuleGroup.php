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

    function __construct(array $group)
    {
        foreach ($group as $rule) {
            if (!($rule instanceof Rule)) {
                throw new \Exception();
            }
        }

        $this->_group = $group;
    }

    /**
     * @return Rule[]
     */
    function getGroup(): array
    {
        return $this->_group;
    }
}