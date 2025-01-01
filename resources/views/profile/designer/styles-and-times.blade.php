<x-design-styles-and-mediums></x-design-styles-and-mediums>
<x-working-times :workingTimes="$workingTimes" :dashboardType="$dashboardType" :user="$user" />

<script>
  jQuery(document).ready(function() {
    // Event listener for when checkboxes are clicked
    jQuery('input[name="environment_type[]"]').on('change', function() {
      // Get all selected values from the checkboxes
      let selectedEnvironmentTypes = jQuery('input[name="environment_type[]"]:checked').map(function() {
        return jQuery(this).val();
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
        },
        error: function(xhr, status, error) {
          // Handle error
          console.error('Error updating environment types:', error);
        }
      });
    });
  });
</script>
