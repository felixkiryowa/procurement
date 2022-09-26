@component('mail::message')

{{ $message }}

<br>
<br>
Regards,<br>
{{ config('app.name')}}
@endcomponent