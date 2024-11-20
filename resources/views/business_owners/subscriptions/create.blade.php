@extends('layouts.owner')

@section('content')
    <div class="row new-chemist-parent-row">
        <h4 class="mt-3">
            Create Subscription
        </h4>

        <div class="card p-4 mb-4">
            <h6 class="card-title text-center">Amount to be Paid</h6>
            <div class="d-flex justify-content-center align-items-center">
                <h4 class="text-success font-weight-bold">${{ $package->price }}</h4>
            </div>
        </div>

        <form method="POST" action="{{ route('subscription.store') }}" class="row new-chemist-child-row"
           >
            @csrf
            <div class="col-12">
                <div class="row">
                    <!-- Card Holder Name -->
                    <div class="col-12 col-md-6 mb-3">
                        <div class="chemist-input-heading">Card Holder Name</div>
                        <input type="text" name="card_holder_name" id="card_holder_name" required class="form-control" />
                    </div>

                    <!-- Card Number -->
                    <div class="col-12 col-md-6 mb-3">
                        <div class="chemist-input-heading">Card Number</div>
                        <input type="text" name="card_number" id="card_number" required class="form-control"
                            maxlength="19" placeholder="1234 5678 9012 3456" />
                        <div id="card_number_error" class="text-danger mt-2" style="display: none;">Invalid card number
                        </div>
                    </div>

                    <!-- Expiry Month -->
                    <div class="col-12 col-md-6 mb-3">
                        <div class="chemist-input-heading">Expiry Month</div>
                        <input type="text" name="expiry_month" id="expiry_month" required class="form-control"
                            maxlength="2" placeholder="MM" />
                        <div id="expiry_month_error" class="text-danger mt-2" style="display: none;">Invalid month</div>
                    </div>

                    <!-- Expiry Year -->
                    <div class="col-12 col-md-6 mb-3">
                        <div class="chemist-input-heading">Expiry Year</div>
                        <input type="text" name="expiry_year" id="expiry_year" required class="form-control"
                            maxlength="4" placeholder="YYYY" />
                        <div id="expiry_year_error" class="text-danger mt-2" style="display: none;">Invalid year</div>
                    </div>

                    <!-- CVC -->
                    <div class="col-12 col-md-6 mb-3">
                        <div class="chemist-input-heading">CVC</div>
                        <input type="text" name="cvc" id="cvc" required class="form-control" maxlength="3"
                            placeholder="CVC" />
                        <div id="cvc_error" class="text-danger mt-2" style="display: none;">Invalid CVC</div>
                    </div>

                    <!-- Buttons -->
                    <div class="col-12 text-end mt-3">
                        <div class="d-flex justify-content-end">
                            <button class="chemist-cancel-btn me-2" type="button">Cancel</button>
                            <button class="chemist-add-btn" type="submit" id="submit_btn" disabled>Pay Now</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const cardNumberInput = document.getElementById('card_number');
        const expiryMonthInput = document.getElementById('expiry_month');
        const expiryYearInput = document.getElementById('expiry_year');
        const cvcInput = document.getElementById('cvc');
        const submitBtn = document.getElementById('submit_btn');

        // Real-time validation feedback elements
        const cardNumberError = document.getElementById('card_number_error');
        const expiryMonthError = document.getElementById('expiry_month_error');
        const expiryYearError = document.getElementById('expiry_year_error');
        const cvcError = document.getElementById('cvc_error');

        // Luhn Algorithm for card number validation
        function luhnCheck(value) {
            let sum = 0;
            let shouldDouble = false;
            for (let i = value.length - 1; i >= 0; i--) {
                let digit = parseInt(value[i]);
                if (shouldDouble) {
                    digit *= 2;
                    if (digit > 9) digit -= 9;
                }
                sum += digit;
                shouldDouble = !shouldDouble;
            }
            return sum % 10 === 0;
        }

        // Card number validation
        function validateCardNumber() {
            let value = cardNumberInput.value.replace(/\D/g, '');
            let formattedValue = value.replace(/(\d{4})(?=\d)/g, '$1 ').trim(); // Format the card number
            cardNumberInput.value = formattedValue;

            const isValid = luhnCheck(value);
            if (isValid) {
                cardNumberError.style.display = 'none';
                cardNumberInput.classList.remove('is-invalid');
            } else {
                cardNumberError.style.display = 'block';
                cardNumberInput.classList.add('is-invalid');
            }

            checkFormValidity();
        }

        // Expiry month validation
        function validateExpiryMonth() {
            let value = expiryMonthInput.value.replace(/\D/g, ''); // Remove non-numeric characters
            expiryMonthInput.value = value;

            if (value >= 1 && value <= 12) {
                expiryMonthError.style.display = 'none';
                expiryMonthInput.classList.remove('is-invalid');
            } else {
                expiryMonthError.style.display = 'block';
                expiryMonthInput.classList.add('is-invalid');
            }

            checkFormValidity();
        }

        // Expiry year validation
        function validateExpiryYear() {
            let value = expiryYearInput.value.replace(/\D/g, ''); // Remove non-numeric characters
            expiryYearInput.value = value;

            const currentYear = new Date().getFullYear();
            const isValid = value >= currentYear;
            if (isValid) {
                expiryYearError.style.display = 'none';
                expiryYearInput.classList.remove('is-invalid');
            } else {
                expiryYearError.style.display = 'block';
                expiryYearInput.classList.add('is-invalid');
            }

            checkFormValidity();
        }

        // CVC validation
        function validateCVC() {
            let value = cvcInput.value.replace(/\D/g, ''); // Remove non-numeric characters
            cvcInput.value = value;

            if (value.length === 3) {
                cvcError.style.display = 'none';
                cvcInput.classList.remove('is-invalid');
            } else {
                cvcError.style.display = 'block';
                cvcInput.classList.add('is-invalid');
            }

            checkFormValidity();
        }

        // Check if form is valid and enable/disable submit button
        function checkFormValidity() {
            if (
                luhnCheck(cardNumberInput.value.replace(/\D/g, '')) &&
                expiryMonthInput.value >= 1 && expiryMonthInput.value <= 12 &&
                expiryYearInput.value.length === 4 &&
                cvcInput.value.length === 3
            ) {
                submitBtn.disabled = false;
            } else {
                submitBtn.disabled = true;
            }
        }

        // Event listeners
        cardNumberInput.addEventListener('input', validateCardNumber);
        expiryMonthInput.addEventListener('input', validateExpiryMonth);
        expiryYearInput.addEventListener('input', validateExpiryYear);
        cvcInput.addEventListener('input', validateCVC);
    });
</script>
