<x-app-layout>
  <x-slot name="header">
    <x-promoter-sub-nav :promoterId="$promoter->id" />
  </x-slot>

  <div class="mx-auto w-full max-w-screen-2xl py-16">
    <div class="relative mb-8 shadow-md sm:rounded-lg">
      <div class="min-w-screen-xl mx-auto max-w-screen-xl rounded-lg bg-ynsDarkGray text-white">
        <div class="grid grid-cols-[1.25fr_1.75fr] rounded-lg border border-white">
          <div class="rounded-l-lg border-r border-r-white bg-ynsDarkBlue px-8 py-8">
            <div class="mb-8 flex flex-row justify-between">
              <a
                class="rounded-lg border bg-ynsLightGray px-4 py-2 font-bold text-black transition duration-150 ease-in-out hover:border-ynsYellow hover:text-ynsYellow">Export</a>
              <a href="{{ route('promoter.dashboard.finances.new') }}"
                class="rounded-lg border bg-ynsLightGray px-4 py-2 font-bold text-black transition duration-150 ease-in-out hover:border-ynsYellow hover:text-ynsYellow">New
                Budget</a>
            </div>

            <p class="mb-4 font-heading text-2xl font-bold">Incoming/ Outgoing/ Profit</p>
            <p class="font-heading text-xl text-ynsLightGray" id="totalIncoming">Total Incoming: £0.00</p>
            <p class="mb-4 font-heading text-xl text-ynsLightGray" id="totalOutgoing">Total Outgoing: £0.00</p>
            <p class="font-heading text-2xl font-bold text-white" id="totalProfit">Total Profit: £0.00</p>
          </div>

          <div class="px-8 py-8">
            <div class="flex items-center justify-between space-x-4">
              <p class="font-heading text-4xl font-bold">Finances</p>
              <!-- Dropdown Filter -->
              <select id="finance-filter" class="text-black">
                <option value="day">Day</option>
                <option value="week">Week</option>
                <option value="month">Month</option>
                <option value="year">Year</option>
              </select>

              <!-- Date Picker -->
              <input type="text" id="date-picker" class="text-black">
            </div>

            <!-- Chart Containers -->
            <div class="h-full w-full">
              <canvas id="incomeChart"></canvas>
              <canvas id="outgoingChart"></canvas>
              <canvas id="profitChart"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>
</x-app-layout>

<script>
  $(document).ready(function() {
    var serviceableId = '{{ $promoter->promoters()->first()->id }}';
    var serviceableType = '{{ get_class($promoter->promoters->first()) }}';

    // Initialize the date picker
    flatpickr("#date-picker", {
      dateFormat: "Y-m-d",
      defaultDate: new Date(),
    });

    // Initialize chart variables
    let incomeChart, outgoingChart, profitChart;

    // Fetch data when filters change
    $('#finance-filter, #date-picker').change(function() {
      let filter = $('#finance-filter').val();
      let date = $('#date-picker').val();

      $.ajax({
        url: '/api/dashboard/promoter/finances',
        method: 'GET',
        data: {
          filter: filter,
          date: date,
          serviceable_id: serviceableId,
          serviceable_type: serviceableType
        },
        success: function(response) {
          // Update the charts with response data
          updateCharts(response);

          $('#totalIncoming').text('Total Income: ' + formatCurrency(response.totalIncome));
          $('#totalOutgoing').text('Total Outgoing: ' + formatCurrency(response.totalOutgoing));
          $('#totalProfit').text('Total Profit: ' + formatCurrency(response.totalProfit));
        }
      });
    });
  });

  function updateCharts(data) {
    console.log('Destroying charts if they exist:', {
      incomeChart,
      outgoingChart,
      profitChart
    });

    if (incomeChart instanceof Chart) {
      incomeChart.destroy();
    }
    if (outgoingChart instanceof Chart) {
      outgoingChart.destroy();
    }
    if (profitChart instanceof Chart) {
      profitChart.destroy();
    }

    // Create new charts
    let ctxIncome = document.getElementById('incomeChart').getContext('2d');
    incomeChart = new Chart(ctxIncome, {
      type: 'bar',
      data: {
        labels: ['Total Income'],
        datasets: [{
          label: 'Income',
          data: [data.totalIncome],
          backgroundColor: 'rgba(75, 192, 192, 0.6)'
        }]
      }
    });

    let ctxOutgoing = document.getElementById('outgoingChart').getContext('2d');
    outgoingChart = new Chart(ctxOutgoing, {
      type: 'bar',
      data: {
        labels: ['Total Outgoing'],
        datasets: [{
          label: 'Outgoing',
          data: [data.totalOutgoing],
          backgroundColor: 'rgba(255, 99, 132, 0.6)'
        }]
      }
    });

    let ctxProfit = document.getElementById('profitChart').getContext('2d');
    profitChart = new Chart(ctxProfit, {
      type: 'bar',
      data: {
        labels: ['Total Profit'],
        datasets: [{
          label: 'Profit',
          data: [data.totalProfit],
          backgroundColor: 'rgba(153, 102, 255, 0.6)'
        }]
      }
    });
  }
</script>
