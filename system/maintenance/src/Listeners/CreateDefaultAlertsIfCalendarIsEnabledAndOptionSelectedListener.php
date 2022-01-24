<?php

use App\Models\FeatureList;
use App\Spork;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateDefaultAlertsIfCalendarIsEnabledAndOptionSelectedListener implements ShouldQueue
{
    public function handle()
    {
        if (Spork::hasFeature(FeatureList::FEATURE_CALENDAR)) {

        }
    }
}