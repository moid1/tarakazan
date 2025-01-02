<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $business->business_name }} - QRCode</title>

    <!-- Include Bootstrap CSS & Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons (For Social Media Links) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    

    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }




        /* Flexbox for equal space distribution */
        .header {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            /* Stack the sections vertically */
            /* Distribute space evenly between the sections */
            height: 80vh;
            /* Set a specific height for the container */
        }

        .header>div {
            /* Optional: Adds a little padding to the individual sections for better spacing */
            padding: 10px;
        }

        .heading-bold {
            font-weight: bold;
            /* Make text very bold */
            font-size: 26px;
            /* Adjust font size as needed */
            margin-bottom: 15px;
            /* Space below the title */
        }

        /* Gift Image Styling */
        .gift-image {
            max-width: 100%;
            /* Make sure image is responsive */
            height: auto;
        }

        /* Get the Gift Button Styling */
        .get-gift-btn {
            padding: 10px 25px;
            font-size: 18px;
            font-weight: bold;
            /* Make text bold */
            border-radius: 30px;
            text-decoration: none;
            color: #6c757d;
            /* Grey text */
            background-color: white;
            /* White background */
            border: 2px solid #6c757d;
            /* Grey border to match the text color */
            display: inline-flex;
            /* Ensures the icon and text are aligned properly */
            align-items: center;
            /* Vertically centers the text and icon */
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .get-gift-btn .fas {
            font-size: 20px;
            /* Adjust icon size */
        }

        .get-gift-btn:hover {
            background-color: #6c757d;
            /* Grey background on hover */
            color: white;
            /* White text on hover */
        }

        .get-gift-btn:hover .fas {
            color: white;
            /* Change icon color to white on hover */
        }

        /* Policy Acceptance Styling */
        .accept-policies {
            font-size: 14px;
            /* Smaller font size for the policy text */
            color: #28a745;
            /* Green text color */
        }

        .accept-policies input[type="checkbox"] {
            accent-color: #28a745;
            /* Change checkbox color to green */
            margin-right: 8px;
            /* Space between checkbox and text */
        }

        .accept-policies label {
            color: #28a745;
            /* Green label color */
        }

        .accept-policies a {
            color: #28a745;
            /* Green link color */
            text-decoration: none;
            /* Remove underline from link */
        }

        .accept-policies a:hover {
            text-decoration: underline;
            /* Underline the link on hover */
        }

        .break-text {
        display: inline-block;  /* Allows text to wrap */
        max-width: 46%;        /* Optional: limits text to the available space */
        word-wrap: break-word;  /* Automatically breaks words that overflow */
        text-align: center;     /* Keep the text centered */
        line-height: 1.4;       /* Adjust line spacing */

    }

    .display-flex{
        display: flex;
    }
    </style>
</head>

<body>

    <div class="container">
        {{-- <div class="d-flex flex-column flex-sm-row align-items-center mb-1 mb-sm-0 justify-content-end mt-3">
            <form id="language-form" action="{{ route('change.lang', ['lang' => 'en']) }}" method="GET"
                class="form-inline">
                @csrf
                <select name="lang" class="form-control" onchange="changeLanguage(this)">
                    <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>English</option>
                    <option value="tr" {{ app()->getLocale() == 'tr' ? 'selected' : '' }}>Türkçe</option>
                </select>
            </form>
        </div> --}}

        <div class="header text-center" id="firstBlock">
            <!-- Title and description -->
            <div class="text-center mt-5">
                <h1 class="heading-bold">
                    {{ __('messages.GET YOUR FREE GIFT') }}
                </h1>
                <p>{{ $business->business_name }}</p>
            </div>

            <!-- Gift Image -->
            <div class="img-fluid text-center">
                <img src="{{ asset('images/bot/gift.png') }}" alt="Gift Image" class="gift-image">
            </div>

            <!-- Get the Gift Button -->
           

            <!-- Checkbox Section: Centered container with left-aligned labels -->
            <div class="d-flex flex-column justify-content-left align-items-left">
                <p class="accept-policies text-left">
                    <input type="checkbox" id="firstAcceptPolicies1" class="accept-checkbox">
                    <label style="text-align: left;text-decoration:underline"
                        for="firstAcceptPolicies1">{{ __('Verilerimin işlenmesini kabul ediyorum.') }}</label>
                </p>

                <p class="accept-policies text-left" style="margin-left: -16px">
                    <input type="checkbox" id="firstAcceptPolicies2" class="accept-checkbox">
                    <label style="text-align: left"
                        for="firstAcceptPolicies2">{{ __('Kampanya şartlarını kabul ediyorum.') }}</label>
                </p>
            </div>
            <div class="text-center">
                <a href="#" class="get-gift-btn" id="getTheGift">
                    <i class="fas fa-gift mr-2"></i> &nbsp;{{ __('messages.GET THE GIFT') }}
                </a>
                <p id="bankToListBtn" class="" style="color: #6c757d;font-style:italic">YENIDEN KAYDOL</p>
            </div>
        </div>


        <div class="display-flex justify-content-center align-items-center" id="secondBlock"
            style="display: none!important; height: 100vh;">
            <div class="text-center mt-5">
              <p> {{ $business->business_name }} </p>
               <h3>{{ __('messages.How would you rate') }}</h3>

                <div class="row justify-content-center mt-4">
                    <div class="col-6 text-center">
                        <div class="border star-selection" data-star='1-3'>
                            <img src="{{ asset('images/bot/3_star.png') }}" class="img-fluid" alt="">
                            <img src="{{ asset('images/bot/2_star.png') }}" class="img-fluid" alt="">
                            <img src="{{ asset('images/bot/star.png') }}" class="img-fluid" alt="">
                            <span>1 - 3</span>
                        </div>
                    </div>
                    <div class="col-6 text-center">
                        <div class="border star-selection" data-star='4-5'>
                            <img src="{{ asset('images/bot/4_star.png') }}" class="img-fluid" alt="">
                            <img src="{{ asset('images/bot/5_star.png') }}" class="img-fluid" alt="">
                            <span>4 - 5</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- 3rd Block --}}

        <div class="header" id="thirdBlock" style="display: none;">
            <!-- Title and description -->
            <div class="text-center mt-5 " style="">
                <p>{{ __('messages.Enter your Phone Number to get the free gift code and exclusive offers') }}</p>
            </div>

            <!-- Gift Image -->
            <div class="img-fluid text-center">
                <img src="{{ asset('images/bot/gift.png') }}" alt="Gift Image" class="gift-image">
            </div>

            <!-- Phone Number and Name Input Fields with Send Icons -->
            <div class=" justify-content-center mt-4">
                <!-- Phone Number Field -->

                <!-- Name Field -->
                <div class="d-flex align-items-center ">
                    <input type="text" id="name" class="form-control"
                        placeholder="{{ __('messages.Type in your name...') }}">

                </div>
                <div class="d-flex align-items-center mt-3">
                    <input type="tel" id="phoneNo" class="form-control" inputmode="numeric" pattern="[0-9]*"
                        placeholder="{{ __('messages.Type in your phone number...') }}" />
                       
                </div>
                <button class="get-gift-btn send-icon-btn" style="margin: 0 auto;
                align-items: center;
                justify-content: center;
                display: flex;
                margin-top: 20px;
            }">
                                <i class="fas fa-gift mr-2"></i> &nbsp;{{ __('DOĞRULA') }}
                            </button>
            </div>


        </div>

        {{-- 4th block --}}

        <div class="header" id="fourthBlock" style="display: none;">
            <!-- Title and description -->
            <div class="text-center mt-5 " style="">
                <p>{{ __('messages.Enter the Code to verify your phone number') }}</p>
            </div>

            <!-- Gift Image -->
            <div class="img-fluid text-center">
                <img src="{{ asset('images/bot/verification.png') }}" alt="Gift Image" class="gift-image">
            </div>

            <!-- Phone Number and Name Input Fields with Send Icons -->
            <div class=" justify-content-center mt-4">
                <!-- Name Field -->
                <div class="d-flex align-items-center ">
                    <input type="text" id="otpCode" inputmode="numeric"  class="form-control"
                        placeholder="{{ __('messages.Type in the code...') }}">
                   
                </div>
                <button class="verification-number get-gift-btn" style="margin: 0 auto;
    align-items: center;
    justify-content: center;
    display: flex
;
    margin-top: 20px;
}">
                    <i class="fas fa-gift mr-2"></i> &nbsp;{{ __('DOGRULA') }}
                </button>
            </div>
        </div>

        {{-- 5th Block --}}

        <div class="header" id="fifthBlock" style="display: none;">
            <!-- Title and description -->
            <div class="text-center mt-5 " style="">
                <p>Aşağıdaki butona tıklayarak <br> bize Google değerlendirmesi biraku.</p>
            </div>

            <!-- Gift Image -->
            <div class="img-fluid text-center" id="giveFeedBack" style="border: 2px solid black;
    border: 1;
    margin: 20px;
    padding-top: 25px;
    padding-bottom: 25px;">
                <img src="{{ asset('images/bot/feedback.png') }}" alt="Gift Image" class="gift-image">
                <p style="margin-top: 18px">{{ __('messages.Give a Feedback') }}</p>
            </div>
            <div>
                <p style="color: #6c757d;
    font-weight: bold;
    text-align: center;">Bitirdiğinizde bu pencereyi yeniden açın</p>
            </div>

        </div>

        <div class="" id="sixthBlock" style="display: none;">
            <!-- Title and description -->
            <div class="text-center mt-5 " style="">
                <p>{{ __('messages.Your Discount Code is generating') }}</p>
            </div>

            <!-- Gift Image -->
            <div class="img-fluid text-center mt-5" id="giveFeedBack">
                <img src="{{ asset('images/bot/discount_screen.png') }}" alt="Gift Image" class="gift-image">
            </div>


        </div>

        {{-- 7th Block --}}

        <div class="header" id="seventhBlock" style="display: none;justify-content:space-evenly">
            <!-- Title and description -->
            <div class="text-center mt-5 " style="">
                <p>{{ __('messages.Get your gift now from one of our team.') }}</p>
                <p>{{ __('messages.Show the Discount Code') }}</p>
            </div>

            <!-- Gift Image -->
            <div class="img-fluid text-center" id="">
                <img src="{{ asset('images/bot/show_discount_screen.png') }}" alt="Gift Image" class="gift-image">
            </div>

        </div>


        <!-- Modal for Personal Data Information -->
        <div class="modal fade" id="personalDataModal" tabindex="-1" aria-labelledby="personalDataModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="personalDataModalLabel">Kişisel Verilerin İşlenmesi Hakkında
                            Bilgilendirme</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>
                            <strong>{{ $business->business_name }}</strong> olarak, 6698 sayılı Kişisel Verilerin Korunması Kanunu
                            (“KVKK”) kapsamında, kişisel verilerinizi şu amaçlarla işliyoruz:
                        </p>
                        <ul>
                            <li>Hediye kodu gönderimi</li>
                            <li>Kampanya ve özel fırsatlar hakkında bilgilendirme</li>
                        </ul>
                        <p>
                            <strong>Verilerinizin İşlenme Yasal Dayanağı ve Süresi:</strong><br>
                            Kişisel verileriniz, açık rızanıza dayanarak işlenecek ve yalnızca yukarıdaki amaçlar
                            doğrultusunda saklanacaktır. Verileriniz kampanya süresi boyunca veya yasal zorunluluklar
                            gereği saklanacak, ardından imha edilecektir.
                        </p>
                        <p>
                            <strong>Haklarınız:</strong><br>
                            KVKK’nın 11. maddesi kapsamında şu haklara sahipsiniz:
                        </p>
                        <ul>
                            <li>Kişisel verilerinizin işlenip işlenmediğini öğrenme</li>
                            <li>İşlenme amacını öğrenme ve amaca uygun kullanılıp kullanılmadığını kontrol etme</li>
                            <li>Verilerinizin düzeltilmesini, silinmesini veya yok edilmesini talep etme</li>
                            <li>Kişisel verilerinizin kanuna aykırı işlenmesi durumunda zarara uğrarsanız tazminat talep
                                etme</li>
                        </ul>
                        <p>
                            Daha fazla bilgi ve talepleriniz için bizimle iletişime geçebilirsiniz: <strong>{{{ $business->business_email }}}</strong>
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="backToList" tabindex="-1" aria-labelledby="backToList" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <!-- Modal Header with Close Button -->
                   
                    <!-- Modal Body -->
                    <div class="modal-body">
                        <p>Tekrar kayit olmak icin lütfen bir garsona danisin. Size yardimci olmaktan memnuniyet duyariz!</p>
                    </div>
                </div>
            </div>
        </div>
        
        

    </div>
    <script>
        let couponData = null;
        function changeLanguage(select) {
            // Update the form's action based on the selected language
            var form = document.getElementById('language-form');
            form.submit(); // Submit the form
        }
    </script>
    <script>
        var businessOwner = @json($business);

        var totalBusinsessRating, phoneNo, name, customerId, couponCode;
        $('#secondBlock').hide();
        $('#thirdBlock').hide();


        $('#getTheGift').on('click', function() {
            if (!$('#firstAcceptPolicies1').prop('checked')) {
                alert('Lütfen politikaları kabul edin.');
                return;
            }

            if (!$('#firstAcceptPolicies2').prop('checked')) {
                alert('Lütfen politikaları kabul edin.');
                return;
            }

            $('#firstBlock').hide();
            $('#thirdBlock').show();
            $('html, body').animate({ scrollTop: 0 }, 'slow');

        });

        $('.star-selection').on('click', function() {
            totalBusinsessRating = $(this).data('star');
            $('#firstBlock').hide();
            $('#secondBlock').hide();
            $('#fifthBlock').show();
            // $('#thirdBlock').show();
            $('html, body').animate({ scrollTop: 0 }, 'slow');
        });

        $('.send-icon-btn').on('click', function() {

            name = $('#name').val();
            phoneNo = $('#phoneNo').val();
            sendOTP(phoneNo, name);
            $('html, body').animate({ scrollTop: 0 }, 'slow');
        });

        $('.verification-number').on('click', function() {

            verifyOTP();
        });

        $('#giveFeedBack').on('click', function() {
            $('#secondBlock').hide();
            $('#thirdBlock').hide();
            $('#fourthBlock').hide();
            $('#fifthBlock').hide();
            // open a place ID LINK

            const googleReviewLink =
                `https://search.google.com/local/writereview?placeid={{ $business->google_review }}`;
            window.open(googleReviewLink, '_blank', 'width=600,height=400,scrollbars=yes,resizable=yes');


            $('#sixthBlock').show();
                        // Delay the second fetch by 15 seconds
                setTimeout(() => {
                // Send a second request with the coupon data
                sendCouponData(couponData);
                $('#sixthBlock').hide();
                $('#seventhBlock').show();
                $('html, body').animate({ scrollTop: 0 }, 'slow');
            }, 15000);
        });
    </script>

    <script>
        function sendOTP(phone, customerName) {
            otpSent = false;
            let isOKK = false;
            fetch('/send-otp-sms', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
                        otpSent = true; // Mark OTP as sent
                        customerId = data.customer.id
                        isOKK = true;
                        $('#thirdBlock').hide();
                        $('#fourthBlock').show();
                    } else if (data.success === false) {
                        alert(data.message)
                        isOKK = false;
                    }
                })
                .catch(error => {
                    console.error('Error sending OTP:', error);
                    isOKK = false;
                    // alert('You are already verified');
                });

            return isOKK;
        }

        function verifyOTP() {
    const otp = $('#otpCode').val();
    fetch('/verify-otp', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token for Laravel
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
            
            // Hide and show relevant blocks
            $('#thirdBlock').hide();
            $('#fourthBlock').hide();
            $('#secondBlock').show();
        } else {
            alert("Doğrulama kodu geçersiz, lütfen tekrar deneyin");
        }
    })
    .catch(error => {
        console.error('Error saving customer data:', error);
    });
    $('html, body').animate({ scrollTop: 0 }, 'slow');
}

// Function to send coupon data (to avoid repetition)
function sendCouponData(dataCoupon) {
    fetch('/send-coupon-code', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token for Laravel
        },
        body: JSON.stringify(dataCoupon)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Handle successful response for the second request
            console.log('Coupon data processed successfully');
        } else {
            alert("Error processing coupon data");
        }
    })
    .catch(error => {
        console.error('Error sending coupon data:', error);
    });
}

$("#bankToListBtn").on('click', function(){
    $('#backToList').modal('show');
})

        document.addEventListener('DOMContentLoaded', function() {
            const secondCheckbox = document.getElementById('firstAcceptPolicies1');

            secondCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    // Show the modal if checkbox is checked
                    $('#personalDataModal').modal('show');
                }
            });
        });
    </script>




</body>

</html>
