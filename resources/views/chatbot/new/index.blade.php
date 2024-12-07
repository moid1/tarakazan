<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $business->business_name }} - Chatbot</title>

    <!-- Include Bootstrap CSS & Google Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons (For Social Media Links) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>


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
            /* Stack the sections vertically */
            justify-content: space-between;
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
            font-size: 36px;
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
            margin-top: 15px;
            /* Space above the policy text */
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
    </style>
</head>

<body>

    <div class="container">
        <div class="d-flex flex-column flex-sm-row align-items-center mb-3 mb-sm-0 justify-content-end mt-3">
            <form id="language-form" action="{{ route('change.lang', ['lang' => 'en']) }}" method="GET" class="form-inline">
                @csrf
                <select name="lang" class="form-control" onchange="changeLanguage(this)">
                    <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>English</option>
                    <option value="tr" {{ app()->getLocale() == 'tr' ? 'selected' : '' }}>Türkçe</option>
                </select>
            </form>
        </div>
        <div class="header " id="firstBlock">
            <!-- Title and description -->
            <div class="text-center mt-5">
                <h1 class="heading-bold">
                 {{__('messages.GET YOUR FREE GIFT')}}
                </h1>
                <p>{{ $business->business_name }}</p>
            </div>

            <!-- Gift Image -->
            <div class="img-fluid text-center">
                <img src="{{ asset('images/bot/gift.png') }}" alt="Gift Image" class="gift-image">
            </div>

            <!-- Get the Gift Button -->
            <div class="text-center mt-4">
                <a href="#" class="get-gift-btn" id="getTheGift">
                    <i class="fas fa-shopping-cart mr-2 "></i> &nbsp;{{__('messages.GET THE GIFT')}}
                </a>
                <p class="accept-policies">
                    <input type="checkbox" id="firstAcceptPolicies" class="accept-checkbox">
                    <label for="acceptPolicies">{{__('messages.I ACCEPT THE POLICIES')}}</label>
                </p>
            </div>

        </div>

        <div class=" justify-content-center align-items-center" id="secondBlock"
            style="display: none!important; height: 100vh;">
            <div class="text-center mt-5">
                <h3>{{__('messages.How would you rate')}}</h3>
                <p>{{ $business->business_name }} ?</p>

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
                <p>{{__('messages.Enter your Phone Number to get the free gift code and exclusive offers')}}</p>
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
                    <input type="text" id="name" class="form-control" placeholder="{{__('messages.Type in your name...')}}">

                </div>
                <div class="d-flex align-items-center mt-3">
                    <input type="tel" id="phoneNo" class="form-control"
                        placeholder="{{__('messages.Type in your phone number...')}}">
                    <button class="send-icon-btn">
                        <i class="fas fa-paper-plane send-icon"></i>
                    </button>
                </div>
            </div>

            <!-- Accept Policies Checkbox -->
            <div class="text-center ">
                <p class="accept-policies">
                    <input type="checkbox" id="secondPolicy" class="accept-checkbox">
                    <label for="acceptPolicies">{{__('messages.I ACCEPT THE POLICIES')}}</a>.</label>
                </p>
            </div>
        </div>

        {{-- 4th block --}}

        <div class="header" id="fourthBlock" style="display: none;">
            <!-- Title and description -->
            <div class="text-center mt-5 " style="">
                <p>{{__('messages.Enter the Code to verify your phone number')}}</p>
            </div>

            <!-- Gift Image -->
            <div class="img-fluid text-center">
                <img src="{{ asset('images/bot/verification.png') }}" alt="Gift Image" class="gift-image">
            </div>

            <!-- Phone Number and Name Input Fields with Send Icons -->
            <div class=" justify-content-center mt-4">
                <!-- Name Field -->
                <div class="d-flex align-items-center ">
                    <input type="text" id="otpCode" class="form-control" placeholder="{{__('messages.Type in the code...')}}">
                    <button class="verification-number">
                        <i class="fas fa-paper-plane send-icon"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- 5th Block --}}

        <div class="header" id="fifthBlock" style="display: none;">
            <!-- Title and description -->
            <div class="text-center mt-5 " style="">
                <p>{{__('messages.Click the button below and give us a feedback. Reopen this window after you finish')}}</p>
            </div>

            <!-- Gift Image -->
            <div class="img-fluid text-center" id="giveFeedBack">
                <img src="{{ asset('images/bot/feedback.png') }}" alt="Gift Image" class="gift-image">
                <p>{{__('messages.Give a Feedback')}}</p>
            </div>

        </div>

        <div class="" id="sixthBlock" style="display: none;">
            <!-- Title and description -->
            <div class="text-center mt-5 " style="">
                <p>{{__('messages.Your Discount Code is generating')}}</p>
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
                <p>{{__('messages.Get your gift now from one of our team.')}}</p>
                <p>{{__('messages.Show the Discount Code')}}</p>
            </div>

            <!-- Gift Image -->
            <div class="img-fluid text-center" id="">
                <img src="{{ asset('images/bot/show_discount_screen.png') }}" alt="Gift Image" class="gift-image">
            </div>

        </div>


    </div>
    <script>
        function changeLanguage(select) {
            // Update the form's action based on the selected language
            var form = document.getElementById('language-form');
            form.submit();  // Submit the form
        }
    </script>
    <script>
        var businessOwner = @json($business);

        var totalBusinsessRating, phoneNo, name, customerId, couponCode;
        $('#secondBlock').hide();
        $('#thirdBlock').hide();


        $('#getTheGift').on('click', function() {
            if (!$('#firstAcceptPolicies').prop('checked')) {
                alert('Please accept the policies');
                return;
            }

            $('#firstBlock').hide();
            $('#secondBlock').show();
        });

        $('.star-selection').on('click', function() {
            totalBusinsessRating = $(this).data('star');
            $('#firstBlock').hide();
            $('#secondBlock').hide();
            $('#thirdBlock').show();
        });

        $('.send-icon-btn').on('click', function() {
            if (!$('#secondPolicy').prop('checked')) {
                alert('Please accept the policies');
                return;
            }

            name = $('#name').val();
            phoneNo = $('#phoneNo').val();
            sendOTP(phoneNo, name);
         
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

            setTimeout(() => {
                $('#sixthBlock').hide();
                $('#seventhBlock').show();
            }, 2000);
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
                        alert('You are already registered to this business owner')
                        isOKK = false;
                    }
                })
                .catch(error => {
                    console.error('Error sending OTP:', error);
                    isOKK = false;
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
                        couponCode = data.code;
                        $('#secondBlock').hide();
                        $('#thirdBlock').hide();
                        $('#fourthBlock').hide();
                        $('#fifthBlock').show();
                    } else {
                        alert("invalid coupon code");
                    }
                })
                .catch(error => {
                    console.error('Error saving customer data:', error);
                });

        }
    </script>




</body>

</html>
