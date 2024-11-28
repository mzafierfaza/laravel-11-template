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
  <!-- <div class="alert alert-info alert-has-icon">
    <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
    <div class="alert-body">
      <div class="alert-title">{{ __('Informasi') }}
      </div>
      This is a info alert.
    </div>
  </div> -->

  <div class="row">
    <div class="col-12">

      <div class="card">
        <div class="card-header">
          <h4><i class="fa fa-book"></i> {{ $fullTitle }}</h4>
        </div>
        <div class="card-body">
          <form action="{{ $action }}" method="POST" enctype="multipart/form-data">

            @isset($d)
            @method('PUT')
            @endisset
            <div class="row">
              <div class="alert alert-info col-md-12">
                Info
              </div>
              <div class="col-md-8">
                @include('stisla.includes.forms.inputs.input', ['required'=>true, 'type'=>'text', 'id'=>'title', 'name'=>'title', 'label'=>__('Judul')])
              </div>
              <div class="col-md-4">
                @include('stisla.includes.forms.inputs.input-radio-toggle', [
                'required' => true,
                'id' => 'berbayar',
                'name' => 'is_active',
                'label' => 'Status',
                'options' => [
                0 => "Non Aktif",
                1 => "Aktive",
                ],
                ])
              </div>
              <div class="col-md-12">
                @include('stisla.includes.forms.editors.summernote', [
                'required' => true,
                'name' => 'deskripsi',
                'label' => 'Deskripsi',
                'id' => 'description',
                ])
              </div>

              <!-- <div class="col-md-6">
              @include('stisla.includes.forms.editors.textarea', ['required'=>true, 'type'=>'textarea', 'id'=>'procedurs', 'name'=>'procedurs', 'label'=>__('Prosedur')])
            </div> -->
              <div class="col-md-12">
                @include('stisla.includes.forms.editors.summernote', [
                'required' => true,
                'name' => 'procedurs',
                'label' => 'Tata Cara',
                'id' => 'procedurs',
                ])
              </div>

              <div class="col-md-6">
                @include('stisla.includes.forms.selects.select2', ['required'=>true, 'id'=>'topik', 'name'=>'topik', 'label'=>__('Topik'), 'options'=>$topik, 'multiple'=>false])
              </div>

              <div class="col-md-6">
                @include('stisla.includes.forms.selects.select2', ['required'=>true, 'id'=>'format', 'name'=>'format', 'label'=>__('Format'),
                'options'=>[
                "online" => "online",
                "offline" => "offline",
                "webinar" => "webinar"
                ],
                'multiple'=>false])
              </div>
              <div class="col-md-6">
                @include('stisla.includes.forms.selects.select', ['required'=>true, 'id'=>'is_random_material', 'name'=>'is_random_material', 'label'=>__('Pemutaran Materi'), 'options'=>[
                1 => "Dapat diputar acak",
                0 => "Tidak dapat diacak"
                ], 'multiple'=>false])
              </div>
              <div class="col-md-6">
                @include('stisla.includes.forms.inputs.input', ['required'=>true, 'type'=>'number', 'id'=>'max_repeat_enrollment', 'name'=>'max_repeat_enrollment', 'label'=>__('Maksimal Pengulangan Training')])
              </div>
              <div class="col-md-4">
                @include('stisla.includes.forms.inputs.input-radio-toggle', [
                'required' => true,
                'id' => 'berbayar',
                'name' => 'is_premium',
                'label' => 'Tipe',
                'options' => [
                0 => "Gratis",
                1 => "Berbayar",
                ],
                ])
              </div>

              <div class="col-md-4">
                @include('stisla.includes.forms.inputs.input-currency', [
                'required' => true,
                'name' => 'price',
                'label' => 'Harga',
                'id' => 'price',
                'currency_type' => 'rupiah',
                'iconText' => 'IDR',
                ])
              </div>
              <div class="col-md-4">
                @include('stisla.includes.forms.inputs.input', [ 'type'=>'number', 'id'=>'max_enrollment', 'name'=>'max_enrollment', 'label'=>__('Kuota Peserta')])
              </div>
              <div class="alert alert-info col-md-12">
                Jadwal dan Lokasi
              </div>

              <div class="col-md-4">
                @include('stisla.includes.forms.inputs.input', [ 'type'=>'date', 'id'=>'start_date', 'name'=>'start_date', 'label'=>__('Tanggal Mulai')])
              </div>

              <div class="col-md-4">
                @include('stisla.includes.forms.inputs.input', [ 'type'=>'date', 'id'=>'end_date', 'name'=>'end_date', 'label'=>__('Tanggal Berakhir')])
              </div>

              <div class="col-md-2">
                @include('stisla.includes.forms.inputs.input', [ 'type'=>'time', 'id'=>'start_time', 'name'=>'start_time', 'label'=>__('Waktu Mulai')])
              </div>

              <div class="col-md-2">
                @include('stisla.includes.forms.inputs.input', [ 'type'=>'time', 'id'=>'end_time', 'name'=>'end_time', 'label'=>__('Waktu Selesai')])
              </div>
              <div class="col-md-12">
                @include('stisla.includes.forms.editors.textarea', [ 'type'=>'textarea', 'id'=>'address', 'name'=>'address', 'label'=>__('Alamat')])
              </div>

              <div class="alert alert-info col-md-12">
                Pengajar
              </div>

              <div class="col-md-12">
                @include('stisla.includes.forms.selects.select2', ['required'=>true, 'id'=>'teacher_id', 'name'=>'teacher_id', 'label'=>__('Pengajar'), 'options'=>$pengajars, 'multiple'=>false])
              </div>

              <div class="col-md-12">
                @include('stisla.includes.forms.editors.summernote', [
                'required' => true,
                'name' => 'teacher_about',
                'label' => 'Tentang Pengajar',
                'id' => 'teacher_about',
                ])
              </div>

              <div class="alert alert-info col-md-12">
                Documents
              </div>

              <div class="col-md-12">
                @include('stisla.includes.forms.inputs.input', ['name' => 'image', 'type' => 'file', 'label' => 'Cover Image', 'accept' => '*'])
                <!-- @include('stisla.includes.forms.inputs.input', ['required' => isset($d) ? false : true, 'name' => 'image', 'type' => 'file', 'label' => 'Cover Image', 'accept' => '*']) -->
              </div>
              <div class="col-md-12">
                @include('stisla.includes.forms.inputs.input', ['name' => 'file', 'type' => 'file', 'label' => 'Sertifikat'])
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