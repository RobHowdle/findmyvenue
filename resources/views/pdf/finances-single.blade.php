<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Finance Record PDF</title>
  <style>
    body {
      font-family: 'DejaVu Sans', sans-serif;
      margin: 0;
      padding: 0;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th,
    td {
      padding: 8px;
      border: 1px solid #000;
      text-align: left;
    }

    h1,
    h3 {
      text-align: center;
    }
  </style>
</head>

<body>

  <h1>Finance Record #{{ $finance->id }}</h1>

  <h3>Finance Period</h3>
  <table>
    <tr>
      <th>Date From</th>
      <td>{{ $finance->date_from }}</td>
    </tr>
    <tr>
      <th>Date To</th>
      <td>{{ $finance->date_to }}</td>
    </tr>
  </table>

  <h3>Incomes</h3>
  <table>
    <tr>
      <th>Ticket Sales</th>
      <td>${{ number_format($finance->income_presale, 2) }}</td>
    </tr>
    <tr>
      <th>Ticket Sales</th>
      <td>${{ number_format($finance->income_otd, 2) }}</td>
    </tr>
    <tr>
      <th>Other Income</th>
      <td>${{ number_format($finance->other_income, 2) }}</td>
    </tr>
    <tr>
      <th>Total Income</th>
      <td>${{ number_format($finance->total_income, 2) }}</td>
    </tr>
  </table>

  <h3>Outgoings</h3>
  <table>
    <tr>
      <th>Venue Hire</th>
      <td>${{ number_format($finance->venue_hire, 2) }}</td>
    </tr>
    <tr>
      <th>Staff Costs</th>
      <td>${{ number_format($finance->staff_costs, 2) }}</td>
    </tr>
    <tr>
      <th>Marketing</th>
      <td>${{ number_format($finance->marketing, 2) }}</td>
    </tr>
    <tr>
      <th>Miscellaneous Expenses</th>
      <td>${{ number_format($finance->misc_expenses, 2) }}</td>
    </tr>
    <tr>
      <th>Total Outgoings</th>
      <td>${{ number_format($finance->total_outgoings, 2) }}</td>
    </tr>
  </table>

  <h3>Final Totals</h3>
  <table>
    <tr>
      <th>Net Profit/Loss</th>
      <td>${{ number_format($finance->total_income - $finance->total_outgoings, 2) }}</td>
    </tr>
  </table>

  <h3>Record Details</h3>
  <table>
    <tr>
      <th>Created At</th>
      <td>{{ $finance->created_at->format('Y-m-d H:i:s') }}</td>
    </tr>
    <tr>
      <th>Updated At</th>
      <td>{{ $finance->updated_at->format('Y-m-d H:i:s') }}</td>
    </tr>
  </table>

</body>

</html>
