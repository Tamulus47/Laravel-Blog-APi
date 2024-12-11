<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\DeleteTrashedPosts;
use App\Jobs\FetchRresults;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command(DeleteTrashedPosts::class)->daily();
Schedule::job(FetchRresults::class)->everySixHours();
