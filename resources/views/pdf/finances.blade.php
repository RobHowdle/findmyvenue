<!DOCTYPE html>
<html>

<head>
  <title>Finances Report</title>
  <style>
    body {
      font-family: sans-serif;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th,
    td {
      border: 1px solid black;
      padding: 8px;
      text-align: left;
    }

    th {
      background-color: #f2f2f2;
    }
  </style>
</head>

<body>
  <h1>Finances Report</h1>
  <table>
    <thead>
      <tr>
        <th>Date</th>
        <th>Total Income</th>
        <th>Total Outgoing</th>
        <th>Total Profit</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($data as $item)
        <tr>
          <td>{{ $item['date'] }}</td>
          <td>{{ $item['totalIncome'] }}</td>
          <td>{{ $item['totalOutgoing'] }}</td>
          <td>{{ $item['totalProfit'] }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>

</html>
