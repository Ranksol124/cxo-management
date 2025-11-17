<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use GrahamCampbell\ResultType\Success;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\Plan;
use App\Models\Member;
use App\Models\UserPaymentRecords;
use Illuminate\Support\Facades\Auth;

class PaymentCheckout extends Page
{
    public ?string $clientSecret = null;
    public ?string $publishableKey = null;
    public ?Plan $plan = null;
    public ?int $planId = null;

    protected static ?string $navigationIcon = null;
    protected static ?string $navigationLabel = null;
    protected static bool $shouldRegisterNavigation = false;
    protected static string $view = 'filament.pages.payment-checkout';

    public static function getUrl(
        array $parameters = [],
        bool $isAbsolute = true,
        ?string $panel = null,
        ?\Illuminate\Database\Eloquent\Model $tenant = null
    ): string {
        return route(
            'filament.pages.payment-checkout',
            ['planId' => $parameters['planId'] ?? 3],
            $isAbsolute
        );
    }

    public function mount(?int $planId = null)
    {
        $this->planId = $planId ?? $this->planId;
        $this->plan = Plan::findOrFail($this->planId);

        Stripe::setApiKey(config('services.stripe.secret'));

        if (
            request()->query('payment') === 'success' &&
            request()->query('redirect_status') === 'succeeded' &&
            $paymentIntentId = request()->query('payment_intent')
        ) {

            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);

            $this->paymentSucceeded($paymentIntent);


            return redirect('/portal/my-profile');

        }

        $intent = PaymentIntent::create([
            'amount' => intval($this->plan->price * 100),
            'currency' => 'usd',
            'payment_method_types' => ['card'],
        ]);

        $this->clientSecret = $intent->client_secret;
        $this->publishableKey = config('services.stripe.key');
    }

    public function paymentSucceeded($paymentIntent)
    {
        $member = Auth::user();

        if (!$member) {
            $this->notify('danger', 'You must be logged in to complete payment.');
            return;
        }

        if (
            UserPaymentRecords::where('plan_id', $this->plan->id)
                ->where('member_id', $member->id)
                ->where('created_at', now())
                ->exists()
        ) {
            return;
        }

        UserPaymentRecords::create([
            'member_id' => $member->id,
            'plan_id' => $this->plan->id,
            'start_date' => now(),
            'end_date' => now()->addDays($this->plan->duration_days ?? 30),
            'response' => json_encode($paymentIntent),
        ]);

        $currentMember = Member::where('user_id', $member->id)->first();
        if (!$currentMember) {
            $this->notify('danger', 'This user does not have member account.');
            return;
        }

        // dd($member->id);
        $planName = $this->plan->name;

        switch ($planName) {
            case 'Basic':
                $credits = 10;
                break;
            case 'Silver':
                $credits = 25;
                break;
            case 'Gold':
                $credits = 50;
                break;
            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Plan not found.'
                ]);
        }

        $currentMember->update([
            'plan_id' => $this->plan->id,
            'remaining_credits' => $credits,
        ]);
        // dd($currentMember);
        // $this->dispatchBrowserEvent('payment-success');
    }
}
