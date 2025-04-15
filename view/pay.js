var paymentForm = document.getElementById('paymentForm');

paymentForm.addEventListener('submit', payWithPaystack, false);

function payWithPaystack(e) {
    e.preventDefault();

    // Ensure form fields are correctly selected
    var orderId = document.getElementById('order-id').value; // Hidden input for order ID
    var email = document.getElementById('email-address').value; // Email address
    var amount = document.getElementById('amount').value * 100; // Amount in smallest unit (e.g., kobo or pesewas)

    // Validate fields before proceeding
    if (!orderId || !email || !amount) {
        alert("Order ID, email, and amount are required!");
        return;
    }

    var handler = PaystackPop.setup({
        key: 'pk_test_af4b2b0ff8db40e1e61fd701534af43e14e19f18', // Replace with your Paystack public key
        email: email,
        amount: amount,
        currency: 'GHS',
        ref: "PS_" + Math.random().toString(36).substr(2, 9), // Generate a unique reference
        callback: function (response) {
            var reference = response.reference;

            // Send the reference and order ID to the backend via AJAX
            $.ajax({
                url: "../actions/process.php",
                method: "POST", // Use POST to securely send data
                data: {
                    reference: reference,
                    order_id: orderId // Include order ID in the request
                },
                success: function (response) {
                    try {
                        var res = JSON.parse(response);
                        if (res.status) {
                            // Redirect to the success page with the order ID
                            window.location.href = "../view/success.php?order_id=" + orderId;
                        } else {
                            alert("Payment failed: " + res.message);
                        }
                    } catch (error) {
                        alert("Invalid response from server.");
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                    alert('An error occurred while processing payment.');
                }
            });
        },
        onClose: function () {
            alert('Transaction was not completed, window closed.');
        }
    });
    handler.openIframe();
}
