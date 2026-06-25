<?php
require __DIR__.'/vendor/autoload.php';

use Carbon\Carbon;

$now = Carbon::now('Asia/Makassar')->startOfDay();

$getValidFutureDay = function ($daysToAdd, $forceTuesday = false) use ($now) {
    $date = (clone $now)->addDays($daysToAdd);
    echo "Starting future day search from: " . $date->format('Y-m-d') . "\n";
    while ($date->isWeekend() || ($forceTuesday && $date->dayOfWeek !== Carbon::TUESDAY) || (!$forceTuesday && $date->dayOfWeek === Carbon::TUESDAY)) {
        $date->addDay();
        echo "Added day: " . $date->format('Y-m-d') . "\n";
    }
    return $date;
};

$getValidPastDay = function ($daysToSub) use ($now) {
    $date = (clone $now)->subDays($daysToSub);
    echo "Starting past day search from: " . $date->format('Y-m-d') . "\n";
    while ($date->isWeekend()) {
        $date->subDay();
        echo "Subbed day: " . $date->format('Y-m-d') . "\n";
    }
    return $date;
};

$getValidPastDay(3);
$getValidPastDay(1);
$getValidFutureDay(1, false);
$getValidFutureDay(2, true);
$getValidFutureDay(7, false);
echo "DONE\n";
