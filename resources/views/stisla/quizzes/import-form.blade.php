@extends( 'stisla.layouts.app')


@section('title')
{{ $title }}
@endsection

@section('content')
@include('stisla.includes.breadcrumbs.breadcrumb-form')

<div class="section-body">
    <div class="card">
        <div class="card-header">
            <div class="col-md-12">
                <form action="{{ $action }}" method="POST" enctype="multipart/form-data">

                    @include('stisla.includes.forms.inputs.input-radio-toggle', [
                    'required' => true,
                    'id' => 'is_essay',
                    'name' => 'is_essay',
                    'label' => 'Tipe Soal',
                    'options' => [
                    0 => "Pilihan Ganda",
                    1 => "Essay",
                    ],
                    ])


                    <!-- <div class="col-md-12"> -->
                    @include('stisla.includes.forms.inputs.input', ['name' => 'import_file', 'type' => 'file', 'label' => 'Import Soal'])
                    <!-- </div> -->

                    @isset($quizzes->file_path)
                    <!-- @if ($d->id != null) -->
                    @include('stisla.includes.forms.buttons.btn-csv-download', ['link' => route('questions.import_example'), 'label' => 'Contoh Import Soal'])
                    @method('PUT')
                    <!-- @endif -->
                    @endisset
                    @csrf
                    <input type="hidden" name="quiz_id" value="{{ $quiz_id }}">
                    @include('stisla.includes.forms.buttons.btn-save')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
@endpush

@push('js')
@endpush