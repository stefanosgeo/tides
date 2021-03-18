@component('mail::message')
# Video upload completed

Hi {{ auth()->user()->name }},

Your Video "{{ $clip->title }}" is online

@component('mail::button', ['url' =>'https://tides.test'.$clip->path()])
Watch the video
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
