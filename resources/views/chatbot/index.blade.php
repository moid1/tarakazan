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

        .container {
            max-width: 900px;
            margin: 50px auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .chatbox {
            display: flex;
            flex-direction: column;
            gap: 15px;
            padding: 20px;
            flex-grow: 1;
            overflow-y: auto;
            background-color: #f1f1f1;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .chat-message {
            max-width: 80%;
            padding: 12px;
            border-radius: 8px;
            font-size: 16px;
            word-wrap: break-word;
            margin-bottom: 8px;
        }

        .bot-message {
            background-color: #ffa500;
            color: white;
            align-self: flex-start;
        }

        .user-message {
            background-color: #007bff;
            color: white;
            align-self: flex-end;
        }

        .typing {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            margin-right: 5px;
            border-radius: 50%;
            background-color: #ffa500;
            animation: typing 0.6s infinite alternate;
        }

        @keyframes typing {
            0% {
                opacity: 0.3;
            }

            100% {
                opacity: 1;
            }
        }

        .input-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: white;
            padding: 15px;
            border-top: 1px solid #ddd;
        }

        .input-section input {
            width: 100%;
            padding: 12px;
            margin-right: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        .submit-icon {
            font-size: 24px;
            cursor: pointer;
            color: #007bff;
        }

        .submit-icon:hover {
            color: #0056b3;
        }

        .social-links {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .social-links a {
            text-decoration: none;
            margin: 0 10px;
            font-size: 24px;
            color: #333;
        }

        .social-links a:hover {
            color: #ffa500;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="text-center mb-4">
            <h4>Welcome to {{ $business->business_name }} Chatbot!</h4>
            <p>How can we assist you today?</p>
        </div>

        <!-- Chatbox -->
        <div class="chatbox" id="chat-content"></div>

        <!-- User Input Section -->
        <div class="input-section" id="input-section">
            <input type="text" id="user-answer" placeholder="Your answer..." autofocus />
            <span class="submit-icon" id="submit-answer">&#10004;</span>
        </div>

        <!-- Social Links -->
        <div class="social-links" id="social-links">
            @if ($business->facebook)
                <a href="{{ $business->facebook }}" target="_blank" class="fab fa-facebook" data-social="facebook"></a>
            @endif
            @if ($business->instagram)
                <a href="{{ $business->instagram }}" target="_blank" class="fab fa-instagram"
                    data-social="instagram"></a>
            @endif
            @if ($business->tiktok)
                <a href="{{ $business->tiktok }}" target="_blank" class="fab fa-tiktok" data-social="tiktok"></a>
            @endif
        </div>
    </div>

    <script>
        // Initialize counters for each social media platform
        let socialMediaCounts = {
            facebook: 0,
            instagram: 0,
            tiktok: 0
        };

        // Function to handle the click event and track interactions
        function trackSocialMediaClick(platform) {
            // Increment the counter for the clicked platform
            socialMediaCounts[platform]++;

            // Log the count to the console for debugging
            console.log(`${platform} clicked ${socialMediaCounts[platform]} times`);

            // Send the interaction count to the server
            sendSocialMediaCountToServer(platform, socialMediaCounts[platform]);
        }

        // Event listeners for each social media link
        document.querySelectorAll('.social-links a').forEach(link => {
            link.addEventListener('click', function(event) {
                // Get the platform from the data attribute (e.g., 'facebook', 'instagram', 'tiktok')
                const platform = event.target.getAttribute('data-social');
                trackSocialMediaClick(platform);
            });
        });

        // Send the interaction count to the server using fetch
        function sendSocialMediaCountToServer(platform, count) {
            fetch('/update-social-interactions', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token for Laravel
                    },
                    body: JSON.stringify({
                        platform: platform,
                        count: count,
                        business_owner_id: {{ $business->id }}
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Interaction data sent successfully:', data);
                })
                .catch(error => {
                    console.error('Error sending interaction data:', error);
                });
        }
    </script>


    <!-- Scripts -->
    <script>
        var businessOwner = @json($business);
        let currentStep = 0;
        let userName = '';
        let userPhone = '';
        let otpSent = false;
        let customerId = null;
        let couponCode = null;
        document.addEventListener('DOMContentLoaded', function() {
            displayTypingEffect(); // Start typing effect
        });

        function displayTypingEffect() {
            document.getElementById('user-answer').disabled = true;
            const chatContent = document.getElementById('chat-content');
            const typingDiv = document.createElement('div');
            typingDiv.classList.add('chat-message', 'bot-message');
            typingDiv.innerHTML =
                `<p><span class="typing"></span><span class="typing"></span><span class="typing"></span></p>`;
            chatContent.appendChild(typingDiv);
            chatContent.scrollTop = chatContent.scrollHeight;

            setTimeout(() => {
                typingDiv.remove();
                document.getElementById('user-answer').disabled = false;

                // appendChatMessage('bot', 'Welcome to {{ $business->business_name }}!');
                askName();
            }, 2000);
        }

        function appendChatMessage(sender, message) {
            const chatContent = document.getElementById('chat-content');
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('chat-message', sender + '-message');
            messageDiv.innerHTML = `<p>${message}</p>`;
            chatContent.appendChild(messageDiv);
            chatContent.scrollTop = chatContent.scrollHeight;
        }

        function askName() {
            appendChatMessage('bot', 'What is your name?');
            currentStep = 1; // Set to next step: Name input
        }

        // Handle Answer Submission (Shared for all steps)
        document.getElementById('submit-answer').addEventListener('click', function() {
            const userAnswer = document.getElementById('user-answer').value.trim();

            if (userAnswer) {
                appendChatMessage('user', userAnswer); // Display user's answer
                document.getElementById('user-answer').value = ''; // Clear input field

                // Show typing effect before bot responds
                displayBotThinking();

                // Move to the next step
                if (currentStep === 1) {
                    userName = userAnswer;
                    setTimeout(() => askPhone(), 2000);
                } else if (currentStep === 2) {
                    userPhone = userAnswer;
                    setTimeout(() => sendOTP(userPhone, userName), 2000);
                } else if (currentStep === 3) {
                    verifyOTP(userAnswer);
                } else if (currentStep === 4) {
                    setTimeout(() => askReview(), 2000);
                } else if (currentStep === 5) {
                    setTimeout(() => handleConsent(userAnswer), 2000);
                }
            } else {
                alert('Please enter a valid answer.');
            }
        });

        function displayBotThinking() {
            const chatContent = document.getElementById('chat-content');
            const typingDiv = document.createElement('div');
            typingDiv.classList.add('chat-message', 'bot-message');
            typingDiv.innerHTML =
                `<p><span class="typing"></span><span class="typing"></span><span class="typing"></span></p>`;
            chatContent.appendChild(typingDiv);
            chatContent.scrollTop = chatContent.scrollHeight;

            // Remove typing effect after 2 seconds and display the bot's response
            setTimeout(() => {
                typingDiv.remove(); // Remove the typing indicator
            }, 2000);
        }

        function askPhone() {
            appendChatMessage('bot', 'What is your phone number?');
            currentStep = 2;
        }

        function sendOTP(phone, customerName) {
            appendChatMessage('bot', `Sending OTP to ${phone}...`);
            otpSent = false;

            // Make the AJAX call to send the OTP to the server
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
                    }) // Send phone number to the backend
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        otpSent = true; // Mark OTP as sent
                        customerId = data.customer.id
                        // Inform the user that OTP is sent
                        appendChatMessage('bot', 'OTP sent! Please enter the code you received.');
                        // Update the current step in the process
                        currentStep = 3;
                    } else {
                        // Handle failure to send OTP
                        appendChatMessage('bot', 'Failed to send OTP. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error sending OTP:', error);
                    appendChatMessage('bot', 'An error occurred while sending OTP. Please try again later.');
                });
        }

        function verifyOTP(otp) {
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
                        appendChatMessage('bot', 'OTP verified successfully!');
                        couponCode = data.code;

                        // saveCustomerData(userName, userPhone, {{ $business->id }});

                        setTimeout(() => askReview(), 2000);
                    } else {
                        appendChatMessage('bot', 'Invalid OTP. Please try again.');
                        currentStep = 3; // Allow the user to re-enter OTP
                    }
                })
                .catch(error => {
                    console.error('Error saving customer data:', error);
                    appendChatMessage('bot', 'Invalid OTP. Please try again.');
                    currentStep = 3; // Allow the user to re-enter OTP
                });

        }

        function saveCustomerData(name, phone, businessOwnerId) {
            fetch('/save-customer-data', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token for Laravel
                    },
                    body: JSON.stringify({
                        name: name,
                        phone: phone,
                        business_owner_id: businessOwnerId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Customer data saved successfully:', data);
                })
                .catch(error => {
                    console.error('Error saving customer data:', error);
                });
        }

        function askReview() {
            currentStep = 5;

            appendChatMessage('bot', 'Thank you for verifying your number! Please leave a review: ');
            // open google review google_review
            // https://search.google.com/local/writereview?placeid=ChIJN1t_tDeuEmsRUsoyG83frY4
            const googleReviewLink =
                `https://search.google.com/local/writereview?placeid={{ $business->google_review }}`;
            window.open(googleReviewLink, "Google Review", "width=600,height=400,scrollbars=yes,resizable=yes");

            showConsent();

        }

        function handleConsent(consent) {
            appendChatMessage('user', consent);
            appendChatMessage('bot', 'Thank you for your feedback!');
            appendChatMessage('bot', `The process is complete. Here is your coupon code:  ${couponCode}`);
        }

        function showConsent() {
            appendChatMessage('bot', 'Are you willing to receive promotional messages?');

        }
    </script>

</body>

</html>
