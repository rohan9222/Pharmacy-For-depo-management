@if (siteUrlSettings('site_logo') != null || siteUrlSettings('site_logo') != '' || siteUrlSettings('site_name') != '' || siteUrlSettings('site_name') != null)
    @if (siteUrlSettings('site_logo') != null || siteUrlSettings('site_logo') != '')
        <img {{ $attributes }} src="{{ asset( siteUrlSettings('site_logo')) }}" alt="{{ siteUrlSettings('site_name') }}" class="h-8" />
        @if (siteUrlSettings('site_name') != null || siteUrlSettings('site_name') != '')
            <h3 class="text-center">{!! siteUrlSettings('site_name') !!}</h3>
        @else
            <h3 class="text-center">{{ env('APP_NAME') }}</h3>            
        @endif
    @elseif (siteUrlSettings('site_name') != null || siteUrlSettings('site_name') != '')
        <h3 class="text-center">{!! siteUrlSettings('site_name') !!}</h3>
    @endif
@else
    <h3 class="text-center">{{ env('APP_NAME') }}</h3>     
@endif
