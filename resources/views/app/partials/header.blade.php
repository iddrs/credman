@php
    if (!isset($level)) {
        $level = '1';
    }
@endphp

<h{{ $level }} class="ui dividing header">

    @isset($icon)
        @include('app.partials.icon', ['icon' => $icon])
    @endisset

    <div class="content">
        {{ $title }}

        @isset($subtitle)
        <div class="sub header">{{ $subtitle }}</div>
        @endisset
    </div>

</h{{ $level }}>
