<x-grouped-checkbox name="environment_types" label="Select Shooting Environments" :selected="old('environment_types', $environmentTypes)" :groups="$groups" />
<x-working-times :workingTimes="$workingTimes" :dashboardType="$dashboardType" :user="$user" />

<script>
  console.log(dashboardType);
  $(document).ready(function() {
    // Event listener for when checkboxes are clicked
    $('input[name="environment_type[]"]').on('change', function() {
      // Get all selected values from the checkboxes
      let selectedEnvironmentTypes = $('input[name="environment_type[]"]:checked').map(function() {
        return $(this).val();
      }).get();

      // Send the selected values via AJAX
      $.ajax({
        url: '{{ route('photographer.environment-types', ['dashboardType' => $dashboardType]) }}',
        method: 'POST',
        data: {
          _token: '{{ csrf_token() }}',
          environment_types: selectedEnvironmentTypes
        },
        success: function(response) {
          // Handle success (optional: show success message, update UI, etc.)
          console.log(response.message);
          // You can also update the UI to show the updated list of environment types if needed
        },
        error: function(xhr, status, error) {
          // Handle error
          console.error('Error updating environment types:', error);
        }
      });
    });
  });
</script>
