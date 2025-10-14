<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Session;
use App\Models\Plan;
use App\Models\UserPaymentRecords;
use Stripe\Stripe;
use Stripe\Charge;

class PaymentController extends Controller
{
 
    public function index($plan_id)
    {
        $plan = Plan::findOrFail($plan_id);
        return view('filament.components.payment_page', compact('plan'));
    }


    public function stripePost(Request $request, $plan_id)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $plan = Plan::findOrFail($plan_id);
        $amount = intval($plan->price * 100);

        try {
            $charge = Charge::create([
                'amount' => $amount,
                'currency' => 'usd',
                'source' => $request->stripeToken,
                'description' => "Payment for plan name: {$plan->name}",
                'metadata' => [
                    'user_id' => auth()->id(),
                    'plan_id' => $plan->id,
                ],
            ]);
            // auth()->user()->update([
            //     'plan_id' => $plan->id,
            // ]);
            $this->storeResponse($charge, $plan);

            Session::flash('success', 'Payment successful!');
            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            return back()->withErrors('Payment failed: ' . $e->getMessage());
        }
    }

    protected function storeResponse($charge, Plan $plan)
    {
        $start_date = now();
        $end_date = now()->addDays($plan->duration_days ?? 30);

        UserPaymentRecords::create([

            'plan_id' => $plan->id,
            // 'response' => json_encode($charge),
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);
    }

}
