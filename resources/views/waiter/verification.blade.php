<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coupon Code Verification</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body and Container Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }

        h1 {

            font-size: 24px;
            color: #333;
        }

        /* Input Group Styles */
        .input-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .input-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
            color: #555;
        }

        .input-group input {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .input-group input:focus {
            outline: none;
            border-color: #007bff;
        }

        /* Button Styles */
        .verify-btn {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .verify-btn:hover {
            background-color: #0056b3;
        }

        /* Status Message Styles */
        .status-message {
            margin-top: 20px;
            font-size: 16px;
            padding: 10px;
            border-radius: 5px;
        }

        .status-message.success {
            background-color: #d4edda;
            color: #155724;
        }

        .status-message.error {
            background-color: #f8d7da;
            color: #721c24;
        }

        .status-message.info {
            background-color: #cce5ff;
            color: #004085;
        }
    </style>
</head>

<body>
    <div class="row">
        <div class="col-lg-6">
            <div class="container">
                <h1>{{ $businessOwner->business_name }} </h1>
                <h2>Coupon Code Verification</h2>
                <form id="coupon-form" style="margin-top:20px" action="{{ route('waiter.verify.code') }}"
                    method="POST">
                    @csrf
                    <div class="input-group">
                        <label for="coupon-code">Coupon Code:</label>
                        <input type="text" id="coupon-code" name="coupon_code" placeholder="Enter Coupon Code"
                            required>
                    </div>
                    <div class="input-group">
                        <label for="mobile-number">Mobile Number:</label>
                        <input type="tel" id="mobile-number" name="phone_no" placeholder="Enter Mobile Number"
                            required>
                    </div>
                    <button type="submit" class="verify-btn">Redeem Code</button>
                </form>

                @if (session('success'))
                    <div id="status-message" class="status-message success">
                        {{ session('success') }}
                    </div>
                @elseif(session('error'))
                    <div id="status-message" class="status-message error">
                        {{ session('error') }}
                    </div>
                @endif

                <a style="margin-top: 20px" class="" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <!-- Font Awesome Logout Icon -->
                    <hr style="margin-top: 20px" />
                    <button class="verify-btn" style="background: red;margin-top:20px">Logout</button>
                </a>

                <!-- Logout Form -->
                <form id="logout-form" style="" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
        <div class="col-lg-6 mt-3">
            <div class="container">
                <h1>{{ $businessOwner->business_name }} </h1>
                <h2>Remove User From BlackList</h2>
                <form id="coupon-form" style="margin-top:20px" action="{{ route('waiter.remove.blacklist') }}"
                    method="POST">
                    @csrf
                    <div class="input-group">
                        <label for="">Enter Phone no:</label>
                        <input type="text" name="phone" id="" name="number" placeholder="Enter Phone No" required>
                    </div>
                    <button type="submit" class="verify-btn">Submit</button>
                </form>

                @if (session('successforBlacklist'))
                    <div id="status-message" class="status-message success">
                        {{ session('successforBlacklist') }}
                    </div>
                @endif


            </div>
        </div>
    </div>



</body>

</html>
