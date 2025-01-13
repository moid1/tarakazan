
var totalBusinsessRating, phoneNo, name, customerId, couponCode;
const csrfToken = $('meta[name="csrf-token"]').attr('content');

window.open(googleReviewLink, '_blank', 'width=600,height=400,scrollbars=yes,resizable=yes');
document.addEventListener('DOMContentLoaded', function () {

    const checkbox = document.getElementById('firstAcceptPolicies1');
    checkbox.addEventListener('change', function () {
        if (checkbox.checked) {
            const modal = new bootstrap.Modal(document.getElementById('exampleModal'));
            modal.show();
        }
    });
})



function toggleStar(star, index) {
    const stars = document.querySelectorAll('#star-rating i'); // Get all star icons

    // If the clicked star is selected, we need to select all previous stars as well
    if (!star.classList.contains('selected')) {
        // Loop over all stars up to the clicked one and select them
        for (let i = 0; i < index; i++) {
            stars[i].classList.add('selected');
        }
    } else {
        // If the clicked star is already selected, we need to deselect it and all stars after it
        for (let i = index; i < stars.length; i++) {
            stars[i].classList.remove('selected');
        }
    }
}




document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('get-gift').addEventListener('click', function () {
        if (!$('#firstAcceptPolicies1').prop('checked')) {
            alert('Lütfen politikaları kabul edin.');
            return;
        }

        if (!$('#firstAcceptPolicies2').prop('checked')) {
            alert('Lütfen politikaları kabul edin.');
            return;
        }

        document.getElementsByClassName('verify-section')[0].style.display = 'block';
        document.getElementsByClassName('welcome-section')[0].style.display = 'none';
    });
});

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('rating-btn').addEventListener('click', function () {
        document.getElementsByClassName('rating-section')[0].style.display = 'none';
        document.getElementsByClassName('review-section')[0].style.display = 'block';
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const timerElement = document.getElementById("timer");
    // const secondsLabel = document.getElementById("seconds");
    const progressCircle = document.getElementById("progress");
    const small = document.getElementById("small");

    let duration = 30; // Total time in seconds
    const fullDashOffset = 302; // Total dash offset of the circle

    function startTimer() {
        let remainingTime = duration;
        const interval = setInterval(() => {
            // Update the timer text and seconds label
            timerElement.textContent = remainingTime;

            // Calculate the stroke offset for the circular progress
            const offset = (remainingTime / duration) * fullDashOffset;
            progressCircle.style.strokeDashoffset = fullDashOffset - offset;

            remainingTime--;

            if (remainingTime < 0) {
                clearInterval(interval);
                timerElement.textContent = "Resend";
                small.textContent = " "
                // secondsLabel.textContent = "0";
                $('#timer').on('click', function () {
                    sendOTP(phoneNo, name);
                })
            }
        }, 1000);
    }

    // Initialize the circular progress bar
    progressCircle.style.strokeDasharray = fullDashOffset;

    function sendOTP(phone, customerName) {
        fetch('/send-otp-sms', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                phone_no: phone,
                name: customerName,
                business_owner_id: businessOwner.id
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    customerId = data.customer.id
                    document.getElementsByClassName('verify-section')[0].style.display = 'none';
                    document.getElementsByClassName('otp-section')[0].style.display = 'block';
                    startTimer();
                } else if (data.success === false) {
                    alert(data.message)
                    return;
                }
            })
            .catch(error => {
                console.error('Error sending OTP:', error);
            });
    }

    document.getElementById('verify-btn').addEventListener('click', function () {
        name = $('#name').val();
        phoneNo = $('#phoneNo').val();
        if (!name || !phoneNo) {
            alert("ALl fields are rqeuired");
            return;
        }
        sendOTP(phoneNo, name);


    });

    function verifyOTP() {
        const otp = Array.from(document.querySelectorAll('.otp-input')).map(input => input.value).join('');

        // Ensure OTP is exactly 6 digits
        if (otp.length !== 6 || !/^\d{6}$/.test(otp)) {
            alert("Lütfen geçerli bir OTP kodu girin.");
            return;
        }
        fetch('/verify-otp', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                otp: otp,
                customerId: customerId,
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const couponCode = data.code;
                    couponData = data.couponData;
                    document.getElementsByClassName('otp-section')[0].style.display = 'none';
                    document.getElementsByClassName('rating-section')[0].style.display = 'block';
                } else {
                    alert("Doğrulama kodu geçersiz, lütfen tekrar deneyin");
                }
            })
            .catch(error => {
                console.error('Error saving customer data:', error);
            });
        $('html, body').animate({ scrollTop: 0 }, 'slow');
    }

    document.getElementById('otp-verify-btn').addEventListener('click', function () {
        verifyOTP();

    });

    function sendCouponData(dataCoupon) {
        fetch('/send-coupon-code', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(dataCoupon)
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Handle successful response for the second request
                    console.log('Coupon data processed successfully');
                    document.getElementsByClassName('success-message1')[0].style.display = 'none';
                    document.getElementsByClassName('success-message2')[0].style.display = 'block';

                  
                } else {
                    alert("Error processing coupon data");
                }
            })
            .catch(error => {
                console.error('Error sending coupon data:', error);
            });
    }

    $('#rateUsOnGoogle').on('click', function () {
        setTimeout(() => {
            // Send a second request with the coupon data
            sendCouponData(couponData);
        }, 15000);
        window.open(googleReviewLink, '_blank', 'width=600,height=400,scrollbars=yes,resizable=yes');
        document.getElementsByClassName('review-section')[0].style.display = 'none';
        document.getElementsByClassName('success-message1')[0].style.display = 'block';

    });


$("#bankToListBtn").on('click', function(){
    $('#backToList').modal('show');
})


});
