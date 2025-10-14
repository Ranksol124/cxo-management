<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
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


            return redirect('/portal/profile-settings');

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

        // $currentMember = Member::where('user_id', $member->id)->first();
        // $currentMember->update([
        //     'plan_id' => $this->plan->id,
        // ]);

        // $this->dispatchBrowserEvent('payment-success');
    }
}
