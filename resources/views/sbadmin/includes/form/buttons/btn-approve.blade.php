@php
$icon = $icon ?? 'fa fa-reset';
@endphp
<a class="btn btn-sm btn-primary @if ($icon ?? false) btn-icon icon-left @endif" href="{{ $link }}" data-toggle="tooltip" title="{{ $label ?? __('Approve') }}">
    @if ($icon ?? false)
    <i class="{{ $icon }}"></i>
    @endif
    {{ $label ?? false }}
</a>