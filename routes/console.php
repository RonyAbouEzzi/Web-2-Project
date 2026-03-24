<?php

use App\Events\AppointmentReminderBroadcast;
use App\Models\Appointment;
use App\Notifications\AppointmentReminder;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('appointments:remind-upcoming', function () {
    $targetDate = now()->addDay()->toDateString();

    $appointments = Appointment::query()
        ->whereDate('appointment_date', $targetDate)
        ->whereIn('status', ['scheduled', 'confirmed'])
        ->get();

    $dispatched = 0;

    foreach ($appointments as $appointment) {
        $dedupeKey = "appointments:reminder:{$appointment->id}:{$targetDate}";

        // Prevent re-broadcasting the same reminder repeatedly during the same day.
        if (! Cache::add($dedupeKey, true, now()->addDays(2))) {
            continue;
        }

        event(new AppointmentReminderBroadcast($appointment, 'daily_scheduler'));
        $appointment->loadMissing(['citizen', 'request']);
        $appointment->citizen?->notify(new AppointmentReminder($appointment, 'daily_scheduler'));
        $dispatched++;
    }

    $this->info("Appointment reminders broadcasted: {$dispatched}");
})->purpose('Broadcast reminders for upcoming appointments');

Schedule::command('appointments:remind-upcoming')->dailyAt('09:00');
