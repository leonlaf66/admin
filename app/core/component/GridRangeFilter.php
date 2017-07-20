<?php
namespace module\core\component;

class GridRangeFilter
{
    public static function range($input1, $input2)
    {
        return <<<EOF
<div class="filter-item-group">
    <div class="item first">
        {$input1}
    </div>
    <div class="item last">
        {$input2}
    </div>
</div>
EOF;
    }
}