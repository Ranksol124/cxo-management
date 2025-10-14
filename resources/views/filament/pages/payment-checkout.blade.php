<x-filament-panels::page>

    <span
        class="bg-[#0891B2] w-auto text-white text-sm font-medium me-2 px-2.5 py-2.5 rounded-sm dark:bg-red-900 dark:text-red-300">Buy
        plan: {{ $plan->name }} for ${{ number_format($plan->price, 2) }}</span>

    <form id="payment-form">
        <div id="payment-element" style="margin-bottom: 12px;"></div>
        <div id="payment-errors" style="color:red;"></div>
        <button id="submit" class="bg-[#0891B2] rounded-md p-2 text-white text-sm font-semibold"
            style="margin-top: 20px;">
            Pay ${{ number_format($plan->price, 2) }}
        </button>
    </form>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        document.addEventListener('payment-success', function () {
            setTimeout(() => {
                window.location.href = '/portal/plans';
            }, 2000);
        });


        document.addEventListener('DOMContentLoaded', async function () {
            const stripe = Stripe(@json($publishableKey));

            const elements = stripe.elements({
                clientSecret: @json($clientSecret),
                appearance: { theme: 'stripe' },
            });

            const paymentElement = elements.create('payment');
            paymentElement.mount('#payment-element');

            const form = document.getElementById('payment-form');

            form.addEventListener('submit', async (event) => {
                event.preventDefault();

                const { error } = await stripe.confirmPayment({
                    elements,
                    confirmParams: {
                   return_url: '{{ route("filament.pages.payment-checkout", ["planId" => $plan->id]) }}?payment=success',

                    },
                });

                if (error) {
                    document.getElementById('payment-errors').textContent = error.message;
                }
            });
        });
    </script>
</x-filament-panels::page>