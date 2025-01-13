<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $business->business_name }} - QRCode</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <link rel="stylesheet" href="{{ asset('custom/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script>
        var businessOwner = @json($business);
        const googleReviewLink =
            `https://search.google.com/local/writereview?placeid={{ $business->google_review }}`;
    </script>
    <script src="{{ asset('custom/index.js') }}"></script>


</head>

<body>

    <!-- ############################# WELCOME PAGE ############################# -->
    <div class="welcome-section custom">

        <div class="logo">
            <img src="{{ asset('images/taraKazan_logo.svg') }}" alt="">
        </div>

        <div class="welcome-img"><img class="bg-img" src="{{ asset('images/bg_pic.svg') }}" alt=""><img
                src="{{ asset('images/welcome_page_pic.svg') }}" alt=""></div>

        <div class="welcome-section-heading">Claim
            Your Free
            Gift...
        </div>

        <div class="welcome-section-sub-heading">{{ $business->business_name }}</div>

        <div class="checkbox-container">

            <label class="checkbox-wrapper">
                <input type="checkbox" class="custom-checkbox" id="firstAcceptPolicies1" />
                <span class="custom-circle"></span>
                I agree to the processing of my personal data.</label>

            <label class="checkbox-wrapper">
                <input type="checkbox" checked class="custom-checkbox" id="firstAcceptPolicies2" />
                <span class="custom-circle"></span>
                I accept the terms of the campaign. </label>
        </div>


        <div class="welcome-btn">
            <button id="get-gift"><img class="welcome-btn-gift" src="{{ asset('images/welcome_btn_gift.svg') }}"
                    alt="">GET
                YOUR GIFT <img class="welcome-btn-arrow" src="{{ asset('images/welcome_arrow.svg') }}"
                    alt=""></button>
        </div>

        <div id="bankToListBtn" class="welcome-section-bottom">REGISTER AGAIN</div>

    </div>

    <!-- ############################# POPUP ############################# -->


    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Information on the Processing of Personal Data
                    </h1>
                </div>

                <div class="modal-body">
                    As <strong>{{ $business->business_name }}</strong>, we process your personal data within the scope
                    of the Personal
                    Data Protection Law No. 6698 ("KVKK") for the following purposes:
                    <ul>
                        <li>Sending gift codes</li>
                        <li>Providing information about the campaign and special offers</li>
                    </ul>
                </div>

                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Legal Basis and Duration of Processing Your
                        Data:</h1>
                </div>

                <div class="modal-body">
                    Your personal data will be processed based on your explicit consent and solely for the purposes
                    mentioned above. Your data will be retained during the campaign period or as required by legal
                    obligations and will then be destroyed.
                </div>


                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Your Rights:</h1>
                </div>

                <div class="modal-body">
                    Under Article 11 of the KVKK, you have the following rights:
                    <ul>
                        <li>To learn whether your personal data is processed or not</li>
                        <li>To request information if your data has been processed</li>
                        <li>To learn the purpose of processing and whether it is used in accordance with its purpose
                        </li>
                        <li>To request the correction, deletion, or destruction of your data if it is incomplete, </li>
                        <li>inaccurate, or improperly processed</li>
                        <li>To demand compensation if you suffer damages due to unlawful processing of your data</li>
                    </ul>
                    To demand compensation if you suffer damages due to unlawful processing of your data
                </div>
                <div class="close-btn">
                    <button data-bs-dismiss="modal"> <img class="popup-close" src="./images/popup_close.svg"
                            alt="">CLOSE <img class="popup-arrow" src="./images/popup_arrow.svg"
                            alt=""></button>
                </div>

            </div>
        </div>
    </div>


    <!-- ############################# VERIFY SECTION ############################# -->
    <div class="">
        <div class="verify-section ">
            <div class="logo">
                <img src="{{ asset('images/taraKazan_logo.svg') }}" alt="">
            </div>

            <div class="welcome-img"><img class="bg-img" src="{{ asset('images/bg_pic.svg') }}" alt=""><img
                    src="{{ asset('images/verify_image.svg') }}" alt=""></div>

            <div class="verify-bottom">
                <div class="verify-bottom-content">

                    <div class="verify-bottom-heading">Enter your phone number for a free <strong>gift code</strong> and
                        <strong>exclusive offers.</strong>
                    </div>

                    <div class="input1-div"><img src="{{ asset('images/verify_input1.svg') }}" alt=""><input
                            placeholder="First Name" type="text" id="name"></div>
                    <div class="input2-div"><img src="{{ asset('images/verify_input2.svg') }}" alt=""><input
                            placeholder="Phone Number" type="tel" id="phoneNo" inputmode="numeric"
                            pattern="[0-9]*">
                    </div>

                    <div class="close-btn w-100">
                        <button id="verify-btn"> <img class="popup-close"
                                src="{{ asset('images/verify_button_lock.svg') }}" alt="">VERIFY <img
                                class="popup-arrow" src="{{ asset('images/popup_arrow.svg') }}"
                                alt=""></button>
                    </div>


                </div>
            </div>


        </div>
    </div>


    <!-- ############################# OTP SECTION ############################# -->
    <div class="otp-section custom">

        <div class="logo">
            <img src="{{ asset('images/taraKazan_logo.svg') }}" alt="">
        </div>

        <div class="welcome-img"><img class="bg-img" src="{{ asset('images/bg_pic.svg') }}" alt=""><img
                src="{{ asset('images/otp_section.svg') }}" alt=""></div>

        <div class="verify-bottom">
            <div class="verify-bottom-content">

                <div class="verify-bottom-heading">Enter the code to verify your phone number.</div>


                <div class="otp-input-div">
                    <input maxlength="1" oninput="this.value = this.value.replace(/[^0-9]/g, '');" placeholder="-"
                        type="tel" class="otp-input">
                    <input maxlength="1" oninput="this.value = this.value.replace(/[^0-9]/g, '');" placeholder="-"
                        type="tel" class="otp-input">
                    <input maxlength="1" oninput="this.value = this.value.replace(/[^0-9]/g, '');" placeholder="-"
                        type="tel" class="otp-input">
                    <input maxlength="1" oninput="this.value = this.value.replace(/[^0-9]/g, '');" placeholder="-"
                        type="tel" class="otp-input">
                    <input maxlength="1" oninput="this.value = this.value.replace(/[^0-9]/g, '');" placeholder="-"
                        type="tel" class="otp-input">
                    <input maxlength="1" oninput="this.value = this.value.replace(/[^0-9]/g, '');" placeholder="-"
                        type="tel" class="otp-input">
                </div>

                <div class="otp-resend-code">Re-send code in</div>

                <div class="timer-container">
                    <div class="progress-circle">
                        <span id="timer">30</span>
                        <small id="small">Sec</small>

                        <svg>
                            <circle cx="50" cy="50" r="48"></circle>
                            <circle cx="50" cy="50" r="48" id="progress"></circle>
                        </svg>
                    </div>
                </div>


                <div class="close-btn w-100">
                    <button id="otp-verify-btn"> <img class="popup-close"
                            src="{{ asset('images/verify_button_lock.svg') }}" alt="">VERIFY <img
                            class="popup-arrow" src="{{ asset('images/popup_arrow.svg') }}" alt=""></button>
                </div>


            </div>
        </div>

    </div>








    <!-- ############################# RATING SECTION ############################# -->
    <div class="rating-section custom">

        <div class="logo">
            <img src="{{ asset('images/taraKazan_logo.svg') }}" alt="">
        </div>

        <div class="welcome-img"><img style="top: 15%;" class="bg-img" src="{{ asset('images/bg_pic.svg') }}"
                alt=""><img src="{{ asset('images/rating_section_img.svg') }}" alt=""></div>

        <div class="verify-bottom">
            <div class="verify-bottom-content">

                <div class="rating-heading">FoodaysCoffee</div>
                <div class="verify-bottom-heading" style="font-weight:500;">How would you rate us?</div>


                <div id="star-rating">
                    <i class="fa fa-star selected" onclick="toggleStar(this,1)"></i>
                    <i class="fa fa-star selected" onclick="toggleStar(this,2)"></i>
                    <i class="fa fa-star selected" onclick="toggleStar(this,3)"></i>
                    <i class="fa fa-star" onclick="toggleStar(this,4)"></i>
                    <i class="fa fa-star" onclick="toggleStar(this,5)"></i>
                </div>

                <div class="close-btn w-100">
                    <button id="rating-btn"> <img class="popup-close"
                            src="{{ asset('images/rating_btn_star.svg') }}" alt="">NEXT <img
                            class="popup-arrow" src="{{ asset('images/popup_arrow.svg') }}" alt=""></button>
                </div>

            </div>
        </div>

    </div>

    <!-- ############################# REVIEW SECTION ############################# -->
    <div class="review-section custom">

        <div class="logo">
            <img src="{{ asset('images/taraKazan_logo.svg') }}" alt="">
        </div>

        <div class="welcome-img"><img style="top: 11%;" class="bg-img" src="{{ asset('images/bg_pic.svg') }}"
                alt=""><img src="{{ asset('images/review_img.svg') }}" alt=""></div>

        <div class="verify-bottom">
            <div class="verify-bottom-content">

                <div class="rating-heading">Rate Us on Google</div>
                <div class="verify-bottom-heading" style="font-weight:500;">Click the button below to leave us a
                    Google review</div>


                <div class="close-btn w-100">
                    <button id="rateUsOnGoogle"> <img class="popup-close"
                            src="{{ asset('images/review_google_icon.svg') }}" alt="">RATE US ON
                        GOOGLE <img class="popup-arrow" src="{{ asset('images/popup_arrow.svg') }}"
                            alt=""></button>
                </div>
                <div class="verify-bottom-heading" style="font-weight:500; margin-top: 1.3rem;">Once you’re done,
                    please reopen this window.</div>

            </div>
        </div>

    </div>

    <!-- ############################# SUCCESS MESSAGE 1 SECTION ############################# -->
    <div class="success-message1 custom" style="height: 100vh">
        <div class="logo">
            <img src="{{ asset('images/taraKazan_logo.svg') }}" alt="">
        </div>

        <div class="welcome-img"><img style="top: 13%;" class="bg-img" src="{{ asset('images/bg_pic.svg') }}"
                alt=""><img src="{{ asset('images/success_msg1.svg') }}" alt=""></div>

        <div class="success-msg-title">Your discount code is being generated</div>

    </div>

    <!-- ############################# SUCCESS MESSAGE 2 SECTION ############################# -->

    <div class="success-message2" style="height: 100vh">
        <div class="logo">
            <img src="{{ asset('images/taraKazan_logo.svg') }}" alt="">
        </div>

        <div class="welcome-img"><img style="top: -5%;" class="bg-img" src="{{ asset('images/bg_pic.svg') }}"
                alt=""><img src="{{ asset('images/success_msg2.svg') }}" alt=""></div>
        <div class="success-msg-title">Claim your gift from our team now</div>
        <div class="success-msg-title2">Show the discount code</div>





    </div>

    <div class="modal fade" id="backToList" tabindex="-1" aria-labelledby="backToList" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <p>Tekrar kayit olmak icin lütfen bir garsona danisin. Size yardimci olmaktan memnuniyet duyariz!</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>



</body>

</html>
