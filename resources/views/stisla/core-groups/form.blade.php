@extends('stisla.layouts.app')

@section('title')
{{ $fullTitle }}
@endsection

@section('content')
@include('stisla.includes.breadcrumbs.breadcrumb-form')

<div class="section-body">

  <h2 class="section-title">{{ $fullTitle }}</h2>
  <p class="section-lead">{{ __('Merupakan halaman yang menampilkan form ' . $title) }}.</p>

  {{-- gunakan jika ingin menampilkan sesuatu informasi --}}
  {{-- <div class="alert alert-info alert-has-icon">
      <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
      <div class="alert-body">
        <div class="alert-title">{{ __('Informasi') }}
</div>
This is a info alert.
</div>
</div> --}}

<div class="row">
  <div class="col-12">

    <div class="card">
      <div class="card-header">
        <h4><i class="fa fa-users"></i> {{ $fullTitle }}</h4>
      </div>
      <div class="card-body">
        <form action="{{ $action }}" method="POST" enctype="multipart/form-data">

          @isset($d)
          @method('PUT')
          @endisset

          <div class="row">
            <div class="col-md-6">
              @include('stisla.includes.forms.inputs.input', ['required'=>true, 'type'=>'text', 'id'=>'name', 'name'=>'name', 'label'=>__('Nama Usaha')])
            </div>
            <div class="col-md-6">
              @include('stisla.includes.forms.inputs.input', ['required'=>true, 'type'=>'text', 'id'=>'owner_name', 'name'=>'owner_name', 'label'=>__('Nama Lengkap Pemilik')])
            </div>
            <div class="col-md-6">
              @include('stisla.includes.forms.selects.select', ['required'=>true, 'id'=>'jenis_badan_usaha', 'name'=>'jenis_badan_usaha', 'label'=>__('Jenis Badan Usaha'),
              'options'=>[
              'perusahaan' => 'Perusahaan',
              'perorangan' => 'Perorangan', ],
              'multiple'=>false])
            </div>

            <div class="col-md-6">
              @include('stisla.includes.forms.selects.select2', ['required'=>true, 'id'=>'bidang_usaha', 'name'=>'bidang_usaha', 'label'=>__('Bidang Usaha'),
              'options'=>$bidang_usaha,
              'multiple'=>false])
            </div>

            <div class="col-md-6">
              @include('stisla.includes.forms.inputs.input', ['required'=>true, 'type'=>'text', 'id'=>'owner_ktp', 'name'=>'owner_ktp', 'label'=>__('Nomor KTP')])
            </div>
            <div class="col-md-6">
              @include('stisla.includes.forms.inputs.input', ['required'=>true, 'type'=>'text', 'id'=>'owner_npwp', 'name'=>'owner_npwp', 'label'=>__('Nomor NPWP')])
            </div>
            <div class="col-md-12">
              @include('stisla.includes.forms.editors.textarea', ['required'=>true, 'type'=>'textarea', 'id'=>'address', 'name'=>'address', 'label'=>__('Alamat')])
            </div>
            <hr>
            <div class="col-md-12">
              @include('stisla.includes.forms.inputs.input', ['required'=>true, 'type'=>'text', 'id'=>'pic_name', 'name'=>'pic_name', 'label'=>__('Nama PIC Perusahaan')])
            </div>
            <div class="col-md-6">
              @include('stisla.includes.forms.inputs.input', ['required'=>true, 'type'=>'text', 'id'=>'pic_phone', 'name'=>'pic_phone', 'label'=>__('Nomor Telefon PIC')])
            </div>
            <div class="col-md-6">
              @include('stisla.includes.forms.inputs.input', ['required'=>true, 'type'=>'email', 'id'=>'pic_email', 'name'=>'pic_email', 'label'=>__('Email PIC Perusahaan')])
            </div>
            <div class="col-md-12">
              <br>

              @csrf

              @include('stisla.includes.forms.buttons.btn-save')
              @include('stisla.includes.forms.buttons.btn-reset')
            </div>
          </div>
        </form>
      </div>
    </div>

  </div>

</div>
</div>
@endsection

@push('css')

@endpush

@push('js')

@endpush