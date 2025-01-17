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

        <div class="welcome-section-heading">ÜCRETSİZ
            HEDİYENİ
            KAP
        </div>

        <div class="welcome-section-sub-heading">{{ $business->business_name }}</div>

        <div class="checkbox-container">

            <label class="checkbox-wrapper">
                <input type="checkbox" class="custom-checkbox" id="firstAcceptPolicies1" />
                <span class="custom-circle"></span>
                Verilerimin işlenmesini kabul ediyorum.</label>

            <label class="checkbox-wrapper">
                <input type="checkbox" checked class="custom-checkbox" id="firstAcceptPolicies2" />
                <span class="custom-circle"></span>
                Kampanya şartlarını kabul ediyorum.</label>
        </div>


        <div class="welcome-btn">
            <button id="get-gift"><img class="welcome-btn-gift" src="{{ asset('images/welcome_btn_gift.svg') }}"
                    alt="">HEDİYENİ AL<img class="welcome-btn-arrow" src="{{ asset('images/welcome_arrow.svg') }}"
                    alt=""></button>
        </div>

        <div id="bankToListBtn" class="welcome-section-bottom">YENIDEN KAYDOL</div>

    </div>

    <!-- ############################# POPUP ############################# -->


    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Kişisel Verilerin İşlenmesi Hakkında
                            Bilgilendirme
                    </h1>
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
                            Daha fazla bilgi ve talepleriniz için bizimle iletişime geçebilirsiniz: <strong>{{ $business->business_email }}</strong>
                        </p>
                    </div>
                <div class="close-btn">
                    <button data-bs-dismiss="modal"> <img class="popup-close" src="{{asset('images/popup_close.svg')}}"
                            alt="">Kapat <img class="popup-arrow" src="{{asset('images/popup_arrow.svg')}}"
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

                    <div class="verify-bottom-heading">Ücretsiz hediye kodu ve özel fırsatlar için telefon numaranı gir, fırsatlardan hemen faydalan!
                    </div>

                    <div class="input1-div"><img src="{{ asset('images/verify_input1.svg') }}" alt=""><input
                            placeholder="İsim" type="text" id="name"></div>
                    <div class="input2-div"><img src="{{ asset('images/verify_input2.svg') }}" alt=""><input
                            placeholder="Telefon Numara" type="tel" id="phoneNo" inputmode="numeric"
                            pattern="[0-9]*">
                    </div>

                    <div class="close-btn w-100">
                        <button id="verify-btn"> <img class="popup-close"
                                src="{{ asset('images/verify_button_lock.svg') }}" alt=""> DOĞRULA <img
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

                <div class="verify-bottom-heading">Telefon numaranı doğrulamak için SMS ile gelen kodu gir.</div>

                <div class="otp-input-div">
                    <input maxlength="1" oninput="moveFocus(this, event)" placeholder="-" type="tel" class="otp-input">
                    <input maxlength="1" oninput="moveFocus(this, event)" placeholder="-" type="tel" class="otp-input">
                    <input maxlength="1" oninput="moveFocus(this, event)" placeholder="-" type="tel" class="otp-input">
                    <input maxlength="1" oninput="moveFocus(this, event)" placeholder="-" type="tel" class="otp-input">
                    <input maxlength="1" oninput="moveFocus(this, event)" placeholder="-" type="tel" class="otp-input">
                    <input maxlength="1" oninput="moveFocus(this, event)" placeholder="-" type="tel" class="otp-input">
                </div>
                
                <script>
                    function moveFocus(currentInput, event) {
                        // Only move focus if the input value is not empty and it's a valid number
                        if (currentInput.value && /[0-9]/.test(currentInput.value)) {
                            const nextInput = currentInput.nextElementSibling;
                            if (nextInput) {
                                nextInput.focus();
                            }
                        }
                
                        // Prevent the user from typing anything other than a number
                        currentInput.value = currentInput.value.replace(/[^0-9]/g, '');
                    }
                </script>
                

                <div class="otp-resend-code">Kodu tekrar gönder</div>

                <div class="timer-container" id="timeerContent">
                    <div class="progress-circle">
                        <span id="timer">30</span>
                        <small id="small">Saniye</small>

                        <svg>
                            <circle cx="50" cy="50" r="48"></circle>
                            <circle cx="50" cy="50" r="48" id="progress"></circle>
                        </svg>
                    </div>
                </div>


                <div class="close-btn w-100">
                    <button id="otp-verify-btn"> <img class="popup-close"
                            src="{{ asset('images/verify_button_lock.svg') }}" alt=""> DOĞRULA <img
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

                <div class="rating-heading">{{ $business->business_name }}</div>
                <div class="verify-bottom-heading" style="font-weight:500;">Bizi nasıl değerlendirirsin?</div>


                <div id="star-rating">
                    <i class="fa fa-star selected" onclick="toggleStar(this,1)"></i>
                    <i class="fa fa-star selected" onclick="toggleStar(this,2)"></i>
                    <i class="fa fa-star selected" onclick="toggleStar(this,3)"></i>
                    <i class="fa fa-star" onclick="toggleStar(this,4)"></i>
                    <i class="fa fa-star" onclick="toggleStar(this,5)"></i>
                </div>

                <div class="close-btn w-100">
                    <button id="rating-btn"> <img class="popup-close"
                            src="{{ asset('images/rating_btn_star.svg') }}" alt="">DEVAM <img
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

                <div class="rating-heading">Google’da Bizi Değerlendir</div>
                <div class="verify-bottom-heading" style="font-weight:500;">Google’da bizi değerlendirmek için lütfen aşağıdaki butona tıkla.</div>


                <div class="close-btn w-100">
                    <button id="rateUsOnGoogle"> <img class="popup-close"
                            src="{{ asset('images/review_google_icon.svg') }}" alt="">Google’da değerlendir <img class="popup-arrow" src="{{ asset('images/popup_arrow.svg') }}"
                            alt=""></button>
                </div>
                <div class="verify-bottom-heading" style="font-weight:500; margin-top: 1.3rem;">Tamamladığında
                    bu pencereyi yeniden aç.</div>

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

        <div class="success-msg-title">İndirim kodun oluşturuluyor</div>

    </div>

    <!-- ############################# SUCCESS MESSAGE 2 SECTION ############################# -->

    <div class="success-message2" style="height: 100vh">
        <div class="logo">
            <img src="{{ asset('images/taraKazan_logo.svg') }}" alt="">
        </div>

        <div class="welcome-img"><img style="top: -5%;" class="bg-img" src="{{ asset('images/bg_pic.svg') }}"
                alt=""><img src="{{ asset('images/success_msg2.svg') }}" alt=""></div>
        <div class="success-msg-title">Hemen şimdi ekibimizden hediyeni al.</div>
        <div class="success-msg-title2">SMS ile indirim kodunu göster</div>





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
    <script>
        function moveFocus(currentInput, event) {
            // If a value is entered, move to the next input
            if (currentInput.value && /[0-9]/.test(currentInput.value)) {
                const nextInput = currentInput.nextElementSibling;
                if (nextInput) {
                    nextInput.focus();
                }
            }
            
            // If the input is empty (backspace), move to the previous input
            if (!currentInput.value) {
                const prevInput = currentInput.previousElementSibling;
                if (prevInput) {
                    prevInput.focus();
                }
            }
    
            // Prevent any character other than numbers from being entered
            currentInput.value = currentInput.value.replace(/[^0-9]/g, '');
        }
    </script>


</body>

</html>
