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
        <h4><i class="fa fa-trophy"></i> {{ $fullTitle }}</h4>
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

            <div class="col-md-12">
              @include('stisla.includes.forms.inputs.input', ['required'=>true, 'type'=>'text', 'id'=>'title', 'name'=>'title', 'label'=>__('Judul')])
            </div>
            <div class="col-md-4">
              @include('stisla.includes.forms.selects.select2', ['required'=>true, 'id'=>'format', 'name'=>'format', 'label'=>__('Level'),
              'options'=>[
              "begineer" => "Begineer",
              "intermediate" => "Intermediate",
              "advance" => "Advance"
              ],
              'multiple'=>false])
            </div>
            <div class="col-md-4">
              @include('stisla.includes.forms.inputs.input', [ 'type'=>'date', 'id'=>'start_date', 'name'=>'start_date', 'label'=>__('Tanggal Mulai')])
            </div>
            <div class="col-md-4">
              @include('stisla.includes.forms.inputs.input', [ 'type'=>'date', 'id'=>'end_date', 'name'=>'end_date', 'label'=>__('Tanggal Berakhir')])
            </div>
            <div class="col-md-12">
              @include('stisla.includes.forms.editors.summernote', [
              'name' => 'description',
              'label' => 'Description',
              'id' => 'description',
              ])
            </div>
            <div class="col-md-12">
              @include('stisla.includes.forms.editors.summernote', [
              'name' => 'benefit',
              'label' => 'Benefit',
              'id' => 'benefit',
              ])
            </div>
            <div class="alert alert-info col-md-12">
              Setting
            </div>

            <div class="col-md-12">
              @include('stisla.includes.forms.selects.select2', ['required'=>true, 'id'=>'courses', 'name'=>'courses', 'label'=>__('Training'),
              'options'=>$trainings,
              'multiple'=>true])
            </div>
            <div class="col-md-12">
              @include('stisla.includes.forms.selects.select2', ['required'=>true, 'id'=>'persons', 'name'=>'persons', 'label'=>__('Peserta'),
              'options'=>$persons,
              'multiple'=>true])
            </div>

            <div class="col-md-6">
              @include('stisla.includes.forms.inputs.input', ['name' => 'image', 'type' => 'file', 'label' => 'Cover Image', 'accept' => '*'])
            </div>
            <div class="col-md-6">
              @include('stisla.includes.forms.inputs.input', ['name' => 'certificate', 'type' => 'file', 'label' => 'Certificate', 'accept' => '*'])
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