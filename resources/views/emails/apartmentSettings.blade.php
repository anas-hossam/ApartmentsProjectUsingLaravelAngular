@component('mail::message')
# Introduction

<h1>Welcome to Apartments Website , {{$user->name}} !</h1>
<h3> you can update or Delete your Apartment that at street {{$apartment->street}} by link <a href="http://127.0.0.1:8000/#/apartments/{{ $apartment->id }}/edit">here  </a>  ..</h3>

@component('mail::button', ['url' => 'http://127.0.0.1:8000/#/apartments'])
Apartmets
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
