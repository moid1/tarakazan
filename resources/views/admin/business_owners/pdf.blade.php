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

    <h3>Business Owners List</h3>

    <table>
        <thead>
            <tr>
                <th>Id</th>
                <th>Business Name</th>
                <th>Address</th>
                <th>Email</th>
                <th>Package</th>
                <th>Slug</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($businessOwners as $businessOwner)
                <tr>
                    <td>{{ $businessOwner->id }}</td>
                    <td>{{ $businessOwner->business_name }}</td>
                    <td>{{ $businessOwner->address }}</td>
                    <td>{{ $businessOwner->business_email }}</td>
                    <td>{{ $businessOwner->package->name ?? 'N/A' }}</td>
                    <td>{{ $businessOwner->slug }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
