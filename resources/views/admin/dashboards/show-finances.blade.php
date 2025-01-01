<x-app-layout :dashboardType="$dashboardType" :modules="$modules">
  <x-slot name="header">
    <x-sub-nav :userId="$userId" />
  </x-slot>

  <div class="mx-auto w-full max-w-screen-2xl py-16">
    <div class="relative mb-8 shadow-md sm:rounded-lg">
      <div class="min-w-screen-xl mx-auto max-w-screen-xl rounded-lg bg-yns_dark_gray text-white">
        <div class="grid grid-cols-[1.25fr_1.75fr] rounded-lg border border-white">
          <div class="rounded-l-lg border-r border-r-white bg-yns_dark_blue px-8 py-8">
            <div class="mb-8 flex flex-row justify-between">
              <a href="{{ route('admin.dashboard.finances.export', ['dashboardType' => $dashboardType]) }}"
                id="exportButton"
                class="rounded-lg border bg-yns_light_gray px-4 py-2 font-bold text-white transition duration-150 ease-in-out hover:border-yns_yellow hover:text-yns_yellow">Export</a>
              <a href="{{ route('admin.dashboard.create-new-finance', ['dashboardType' => $dashboardType]) }}"
                class="rounded-lg border bg-yns_light_gray px-4 py-2 font-bold text-white transition duration-150 ease-in-out hover:border-yns_yellow hover:text-yns_yellow">New
                Budget</a>
            </div>

            <p class="mb-4 font-heading text-2xl font-bold">Incoming/ Outgoing/ Profit</p>
            <p class="font-heading text-xl text-yns_light_gray" id="totalIncoming">Total Incoming: £0.00</p>
            <p class="mb-4 font-heading text-xl text-yns_light_gray" id="totalOutgoing">Total Outgoing: £0.00</p>
            <p class="font-heading text-2xl font-bold text-white" id="totalProfit">Total Profit: £0.00</p>
            <p class="mt-4 font-heading text-xl font-bold text-white">Avaliable Records:</p>
            <ul class="list-disc" id="financeRecords"></ul>
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
    var serviceableId = "{{ $service->id }}";
    var serviceableType = "{{ $serviceType }}";
    const dashboardType = "{{ $dashboardType }}";

    // Initialize the date picker
    flatpickr("#date-picker", {
      dateFormat: "Y-m-d",
      defaultDate: new Date(),
    });

    let datePicker = flatpickr("#date-picker", {
      dateFormat: "Y-m-d",
    });

    // Listen for filter changes
    $('#finance-filter').change(function() {
      console.log('Selected filter:', $(this).val()); // This should log the selected value
      const selectedFilter = $(this).val();
      adjustDatePicker(selectedFilter); // Ensure this function is working too
    });

    // Initialize chart variables
    let incomeChart, outgoingChart, profitChart;

    console.log(incomeChart, outgoingChart, profitChart);

    // Initialize totals
    let totalIncome = [];
    let totalOutgoing = [];
    let totalProfit = [];

    // Fetch data when filters change
    jQuery('#date-picker').change(function() {
      let filter = jQuery('#finance-filter').val();
      let date = jQuery('#date-picker').val();

      $.ajax({
        url: `/dashboard/${dashboardType}/finances`,
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

          // Update totals
          totalIncome = response.totalIncome; // Update totals here
          totalOutgoing = response.totalOutgoing;
          totalProfit = response.totalProfit;

          // Display totals
          jQuery('#totalIncoming').text('Total Income: ' + formatCurrency(totalIncome));
          jQuery('#totalOutgoing').text('Total Outgoing: ' + formatCurrency(totalOutgoing));
          jQuery('#totalProfit').text('Total Profit: ' + formatCurrency(totalProfit));

          // Generate links for individual finance records
          let financeLinks = '';
          response.financeRecords.forEach(record => {
            financeLinks +=
              `<li class="ml-4 my-2"><a href="${record.link}" target="_blank" class="cursor-pointer hover:text-yns_yellow font-heading transition duration-150 ease-in-out">${record.name}</a></li>`;
          });

          // Display finance links in a specific element
          jQuery('#financeRecords').html(financeLinks);
        },
        error: function(xhr, status, error) {
          console.error("AJAX Error: " + status + ": " + error); // Logs the error details

          // Optionally, display a user-friendly error message
          showFailureNotification("Oops!",
            "Something went wrong while fetching the data. Please try again later.", "error");
        }
      });
    });

    function adjustDatePicker(filter) {
      if (filter === 'day') {
        datePicker.set('dateFormat', 'Y-m-d');
        datePicker.set('mode', 'single');
      } else if (filter === 'week') {
        datePicker.set('dateFormat', 'Y-m-d');
        datePicker.set('mode', 'range');
      } else if (filter === 'month') {
        datePicker.set('dateFormat', 'Y-m');
        datePicker.set('mode', 'single');
        datePicker.set('onReady', function(selectedDates, dateStr, instance) {
          instance.calendarContainer.querySelectorAll('.flatpickr-day').forEach(function(dayElem) {
            dayElem.style.display = 'none';
          });
        });
      } else if (filter === 'year') {
        datePicker.set('dateFormat', 'Y');
        datePicker.set('mode', 'single');
        datePicker.set('onReady', function(selectedDates, dateStr, instance) {
          instance.calendarContainer.querySelectorAll('.flatpickr-month, .flatpickr-day').forEach(function(
            elem) {
            elem.style.display = 'none';
          });
        });
      }
    }

    function updateCharts(data) {
      try {
        // Destroy previous charts if they exist
        if (incomeChart instanceof Chart) {
          incomeChart.destroy();
        }
        if (outgoingChart instanceof Chart) {
          outgoingChart.destroy();
        }
        if (profitChart instanceof Chart) {
          profitChart.destroy();
        }



        // Create labels for the charts from `financeRecords`
        let dates = data.financeRecords.map(record => record
          .date_from); // Replace 'date' with the actual property in financeRecords

        // Ensure there are labels for the chart (fallback to empty array if missing)
        if (!dates || dates.length === 0) {
          dates = ['No data']; // Fallback label
        }

        let incomeData = data.financeRecords.map(record => record.totalIncome);
        let outgoingData = data.financeRecords.map(record => record.totalOutgoing);
        let profitData = data.financeRecords.map(record => record.totalProfit);


        // Create new charts with error handling
        let ctxIncome = document.getElementById('incomeChart').getContext('2d');
        incomeChart = new Chart(ctxIncome, {
          type: 'bar',
          data: {
            labels: dates,
            datasets: [{
              label: 'Income',
              color: 'rgba(255,255,255,0.5)',
              data: incomeData,
              backgroundColor: 'rgba(75, 192, 192, 0.6)',
              borderColor: 'rgba(75, 192, 192, 1)',
              borderWidth: 1,
            }]
          },
          options: {
            responsive: true,
            scales: {
              x: {
                ticks: {
                  color: 'white'
                },
                border: {
                  color: 'rgba(255,255,255,0.5)',
                },
                grid: {
                  color: 'rgba(255,255,255,0.5)',
                },
              },
              y: {
                beginAtZero: true,
                ticks: {
                  color: 'white'
                },
                border: {
                  color: 'rgba(255,255,255,0.5)',
                },
                grid: {
                  color: 'rgba(255,255,255,0.5)',
                },
              }
            }
          }
        });

        let ctxOutgoing = document.getElementById('outgoingChart').getContext('2d');
        outgoingChart = new Chart(ctxOutgoing, {
          type: 'bar',
          data: {
            labels: dates,
            datasets: [{
              label: 'Outgoing',
              data: outgoingData,
              backgroundColor: 'rgba(255, 99, 132, 0.6)'
            }]
          },
          options: {
            responsive: true,
            plugins: {
              legend: {
                labels: {
                  color: 'white',
                }
              }
            },
            scales: {
              x: {
                ticks: {
                  color: 'white'
                },
                border: {
                  color: 'rgba(255,255,255,0.5)',
                },
                grid: {
                  color: 'rgba(255,255,255,0.5)',
                },
              },
              y: {
                ticks: {
                  color: 'white'
                },
                border: {
                  color: 'rgba(255,255,255,0.5)',
                },
                grid: {
                  color: 'rgba(255,255,255,0.5)',

                },
              }
            }
          }
        });

        let ctxProfit = document.getElementById('profitChart').getContext('2d');
        profitChart = new Chart(ctxProfit, {
          type: 'bar',
          data: {
            labels: dates,
            datasets: [{
              label: 'Profit',
              data: profitData,
              backgroundColor: 'rgba(153, 102, 255, 0.6)'
            }]
          },
          options: {
            responsive: true,
            plugins: {
              legend: {
                labels: {
                  color: 'white',
                }
              }
            },
            scales: {
              x: {
                ticks: {
                  color: 'white'
                },
                border: {
                  color: 'rgba(255,255,255,0.5)',
                },
                grid: {
                  color: 'rgba(255,255,255,0.5)',
                },
              },
              y: {
                ticks: {
                  color: 'white'
                },
                border: {
                  color: 'rgba(255,255,255,0.5)',
                },
                grid: {
                  color: 'rgba(255,255,255,0.5)',

                },
              }
            }
          }
        });

        console.log('Charts updated successfully');
      } catch (error) {
        console.error('Error occurred while updating charts:', error);
        showFailureNotification("Chart Update Failed",
          "An error occurred while updating the charts. Please try again later.", "error");
      }
    }

    jQuery('#exportButton').on('click', function() {
      event.preventDefault();
      let date = jQuery('#date-picker').val();
      let filter = jQuery('#finance-filter').val();

      // Gather the data from the graphs
      const graphData = {
        date: date,
        filter: filter,
        totalIncome: totalIncome[0] || '0.00',
        totalOutgoing: totalOutgoing[0] || '0.00',
        totalProfit: totalProfit[0] || '0.00',
      };

      // Make the AJAX request to export the data
      $.ajax({
        url: '/dashboard/{dashboardType}/finances/export',
        method: 'POST',
        data: graphData,
        headers: {
          'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content') // Include CSRF token
        },
        xhrFields: {
          responseType: 'blob' // Set response type to blob for binary data
        },
        success: function(blob, response) {
          // Create a URL for the blob and trigger a download
          const url = window.URL.createObjectURL(blob);
          const a = document.createElement('a');
          a.href = url;
          a.download = 'finances_graph_data.pdf'; // Set the file name
          document.body.appendChild(a);
          a.click(); // Trigger the download
          a.remove(); // Clean up
          window.URL.revokeObjectURL(url); // Release the Blob URL
          showSuccessNotification(response.message);

        },
        error: function(error, jqXHR, textStatus, errorThrown) {
          const response = jqXHR.responseJSON;
          showFailureNotification(response.message || 'An error occurred, please try again later.');
        }
      });
    });
  });
</script>
