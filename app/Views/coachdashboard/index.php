<?php
$this->extend('layout/main'); // Extend the main layout
$this->section('body'); // Start the body section
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make a Payment</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .payment-container {
            max-width: 500px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .payment-container h2 {
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        select, #card-element {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        #card-element {
            background: #fff;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #0CA6F7;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0990d1;
        }
        #payment-message {
            margin-top: 10px;
            text-align: center;
            color: red;
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <h2>Make a Payment</h2>
        <form id="payment-form">
            <div class="form-group">
                <label for="plan">Select Plan</label>
                <select id="plan" name="plan_id" required>
                    <option value="">Choose a plan</option>
                    <?php foreach ($plans as $plan): ?>
                        <option value="<?= esc($plan['PlanID']) ?>" data-price="<?= esc($plan['Price']) ?>">
                            <?= esc($plan['PlanName']) ?> - $<?= esc($plan['Price']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="card-element">Credit or Debit Card</label>
                <div id="card-element"></div>
            </div>
            <button type="submit">Pay Now</button>
            <div id="payment-message"></div>
        </form>
    </div>

    <script>
        // Initialize Stripe
        const stripe = Stripe('<?= env('STRIPE_PUBLIC_KEY') ?>'); // Load from .env
        const elements = stripe.elements();
        const card = elements.create('card');
        card.mount('#card-element');

        const form = document.getElementById('payment-form');
        const paymentMessage = document.getElementById('payment-message');
        const planSelect = document.getElementById('plan');

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            paymentMessage.textContent = '';

            const planId = planSelect.value;
            const price = planSelect.options[planSelect.selectedIndex].dataset.price * 100; // Convert to cents

            if (!planId) {
                paymentMessage.textContent = 'Please select a plan.';
                return;
            }

            // Call backend to create PaymentIntent
            try {
                const response = await fetch('/payment/create-payment-intent', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ amount: price, plan_id: planId })
                });
                const { clientSecret, error } = await response.json();

                if (error) {
                    paymentMessage.textContent = error;
                    return;
                }

                // Confirm payment with Stripe
                const result = await stripe.confirmCardPayment(clientSecret, {
                    payment_method: { card }
                });

                if (result.error) {
                    paymentMessage.textContent = result.error.message;
                } else {
                    if (result.paymentIntent.status === 'succeeded') {
                        paymentMessage.style.color = 'green';
                        paymentMessage.textContent = 'Payment successful!';
                        Swal.fire({
                            icon: 'success',
                            title: 'Payment Successful',
                            text: 'Your payment has been processed.',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = '/clientdashboard/mypayment'; // Redirect to payment history
                        });
                    }
                }
            } catch (error) {
                paymentMessage.textContent = 'An error occurred. Please try again.';
            }
        });
    </script>
</body>
</html>

<?php $this->endSection(); ?>