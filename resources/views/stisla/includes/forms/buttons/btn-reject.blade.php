@php
$icon = $icon ?? 'fa fa-times';
$isAjax = $isAjax ?? false;
$isAjaxYajra = $isAjaxYajra ?? false;
@endphp
<a onclick="rejectGlobal(event, '{{ $link }}')" class="btn btn-sm btn-warning @if ($icon ?? false) btn-icon icon-left @endif" href="#" data-toggle="tooltip"
    title="{{ $label ?? __('Reject') }}">
    @if ($icon ?? false)
    <i class="{{ $icon }}"></i>
    @endif
    {{ $label ?? false }}
</a>