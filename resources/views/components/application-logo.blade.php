@if (siteUrlSettings('site_logo') == null)
    <h3 class="text-center">{{ env('APP_NAME') }}</h3>
@else
    <img  class="w-100" src="{{ asset(siteUrlSettings('site_logo')) }}" alt="">        
@endif