<?php

namespace App\Console\Commands;

use App\Models\Member;
use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PlanExpiryNotification;

class SendPlanExpiryReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plans:send-expiry-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder emails 7 days before plan expiry';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::now()->toDateString();
        $next7days = Carbon::now()->addDays(7)->toDateString();

        $members = Member::with('plan')
            ->whereHas('plan', function ($query) use ($today, $next7days) {
                $query->whereBetween('plan_expiry', [$today, $next7days]);
            })
            ->get();

        
        foreach ($members as $member) {
            $user = $member->user;
            $planName = $member->plan->name;
            $planExpiry = $member->plan->plan_expiry;
            $user->notify(new PlanExpiryNotification($planName, $planExpiry));
        }

        $this->info('Expiry reminders sent for ' . $members->count() . ' members.'."\n".$user->email);
    }
}
