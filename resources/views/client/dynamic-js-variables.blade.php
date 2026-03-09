<script>
    var receiver_id = '';
    @auth('web')
    var my_id = "{{ Auth::guard('web')->user()->id }}";
    @endauth
    var base_url = "{{ url('/') }}";
    var isDemo = "{{ env('APP_MODE') ?? 'LIVE' }}"
    var demo_mode_error = "{{ __('This Is Demo Version. You Can Not Change Anything') }}";
</script>