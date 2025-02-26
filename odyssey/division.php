<?php

function findDivisibleNumbers($start, $end, $divisor): array{
    if (!$divisor)
        throw new InvalidArgumentException('Divisor is zero');
    $numbers = [];
    $step = $start <= $end ? 1 : -1;
    for ($i = $start; $step === 1 ? $i <= $end : $i >= $end; $i += $step)
        if (!($i % $divisor))
            $numbers[] = $i;
    return $numbers;
}

$findDivisors = findDivisibleNumbers(25, 8, 2);
print(implode("\t", $findDivisors));