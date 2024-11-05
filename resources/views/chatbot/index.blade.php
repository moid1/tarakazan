<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $business->business_name }} - Chatbot</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <!-- Add your custom styles here -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .chatbox {
            display: flex;
            flex-direction: column;
            gap: 15px;
            max-height: 500px;
            overflow-y: auto;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        .chat-message {
            max-width: 75%;
            padding: 10px;
            border-radius: 5px;
            word-wrap: break-word;
        }

        .bot-message {
            background-color: #fa8502;
            align-self: flex-start;
            font-size: 16px;
            color: white;
        }

        .user-message {
            background-color: #bce0fc;
            align-self: flex-end;
            font-size: 16px;
        }

        #user-input-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #user-answer {
            flex: 1;
            padding: 10px;
            margin-right: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        #submit-answer {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        #submit-answer:disabled {
            background-color: #ccc;
        }

        .business-details {
            text-align: center;
            margin-bottom: 20px;
        }

        .business-logo {
            border-radius: 50%;
            width: 120px;
            height: 120px;
            object-fit: cover;
        }

        .chatbox::-webkit-scrollbar {
            width: 5px;
        }

        .chatbox::-webkit-scrollbar-thumb {
            background-color: #ccc;
            border-radius: 10px;
        }

        .chatbox::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="text-center w-100">
            <h4 class="">Welcome to {{ $business->business_name }}! Thank you for visiting.</h4>
        </div>
        <div class="business-details">
            <!-- Business Logo and Name -->
            <img src="{{ asset('storage/' . $business->logo) }}" alt="{{ $business->name }} Logo" class="business-logo">
            <h2>{{ $business->name }}</h2>
            <p><strong>Address:</strong> {{ $business->address }}</p>
            <p><strong>Email:</strong> {{ $business->business_email }}</p>
            <p><strong>Country:</strong> {{ $business->country }}</p>
        </div>

        <div class="chatbox" id="chat-content">
            <!-- Initial Bot Message -->
            <div class="chat-message bot-message">
                <p>Hi! Let's get started. I will ask you a few questions...</p>
            </div>
        </div>

        <div id="user-input-section">
            <input type="text" id="user-answer" class="form-control" placeholder="Your answer..." autofocus>
            <button id="submit-answer" type="button">Submit</button>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script>
        const questions = [
            'What is your favorite color?',
            'What is your preferred contact method?',
            'Do you need any assistance with our services?'
        ];

        let currentQuestionIndex = 0;

        document.addEventListener('DOMContentLoaded', function() {
            displayQuestion(currentQuestionIndex);

            // Handle answer submission
            document.getElementById('submit-answer').addEventListener('click', function() {
                const userAnswer = document.getElementById('user-answer').value.trim();

                if (userAnswer) {
                    // Display user answer in chatbox
                    appendChatMessage('user', userAnswer);

                    // Disable input while waiting for next question
                    disableInput();

                    // AJAX request to store the answer and get the next question
                    // submitAnswer(userAnswer);
                } else {
                    alert('Please provide an answer.');
                }
            });

            // Display the current question
            function displayQuestion(index) {
                if (index < questions.length) {
                    appendChatMessage('bot', questions[index]);
                }
            }

            // Append chat message (bot or user)
            function appendChatMessage(sender, message) {
                const chatContent = document.getElementById('chat-content');
                const messageDiv = document.createElement('div');
                messageDiv.classList.add('chat-message');
                messageDiv.classList.add(sender + '-message');
                messageDiv.innerHTML = `<p>${message}</p>`;
                chatContent.appendChild(messageDiv);
                chatContent.scrollTop = chatContent.scrollHeight; // Scroll to the bottom
            }

            // Disable the input field and submit button
            function disableInput() {
                document.getElementById('user-answer').disabled = true;
                document.getElementById('submit-answer').disabled = true;
            }

            // Enable the input field and submit button
            function enableInput() {
                document.getElementById('user-answer').disabled = false;
                document.getElementById('submit-answer').disabled = false;
                document.getElementById('user-answer').value = ''; // Clear input field
            }

            // Submit the answer to the server and get the next question
            function submitAnswer(answer) {
                fetch("{{ route('chatbot.store', $business->slug) }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            answer: answer,
                            question_id: currentQuestionIndex
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            currentQuestionIndex++;

                            // Display the next question
                            if (data.nextQuestion) {
                                enableInput();
                                displayQuestion(currentQuestionIndex);
                            }
                        } else if (data.status === 'done') {
                            appendChatMessage('bot', data.message); // Thank you message
                        }
                    });
            }
        });
    </script>

</body>

</html>
