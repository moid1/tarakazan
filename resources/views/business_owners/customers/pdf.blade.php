<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Owners</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>

    <h3>Customers List</h3>

    <table>
        <thead>
            <tr>
                <th>Id</th>
                <th>Customer Name</th>
                <th>Customer Phone</th>
                <th>Business Owner Name</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($customers as $customer)
                <tr>
                    <td>{{ $customer->id }}</td>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->phone }}</td>
                    <td>{{ $customer->businessOwner->business_name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
