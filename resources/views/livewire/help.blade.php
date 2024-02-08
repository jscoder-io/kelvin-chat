<div>
    @if ($section == 'jwt-token')
        @include('help.jwt-token')
    @elseif ($section == 'session-key')
        @include('help.session-key')
    @elseif ($section == 'csrf-token')
        @include('help.csrf-token')
    @elseif ($section == '_csrf')
        @include('help.csrf')
    @endif
</div>