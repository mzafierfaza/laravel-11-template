@php
$icon = $icon ?? 'fa fa-check';
$isAjax = $isAjax ?? false;
$isAjaxYajra = $isAjaxYajra ?? false;
@endphp
<a onclick="approveGlobal(event, '{{ $link }}')" class="btn btn-sm btn-success @if ($icon ?? false) btn-icon icon-left @endif" href="#" data-toggle="tooltip"
    title="{{ $label ?? __('Approve') }}">
    @if ($icon ?? false)
    <i class="{{ $icon }}"></i>
    @endif
    {{ $label ?? false }}
</a>