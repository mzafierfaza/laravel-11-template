@extends( 'stisla.layouts.app-table')


@section('title')
{{ $title }}
@endsection

@section('content')
@include('stisla.includes.breadcrumbs.breadcrumb-form')

<div class="section-body">
    <div class="card">
        <div class="card-header">
            <div class="col-2">
                <img src="https://dummyimage.com/200x200&text=kelaskita-courses!" alt="">
            </div>
            <div class="col-md-6">
                <h2 class="section-title">{{ $quizzes->title }}</h2>
                <div class="row col-md-12">
                    <div class="col-2">
                        <p>1 Video</p>
                    </div>
                    <div class="col-2">
                        <p>1 Pdf</p>
                    </div>
                    <div class="col-2">
                        <p>1 Test</p>
                    </div>
                    <div class="col-2">
                        <p>1 Peserta</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="col-md-12">
                <div class="row d-flex justify-content-end">
                    <div class="mx-2">
                        @include('stisla.includes.forms.buttons.btn-csv-download', ['link' => route('quizzes.import_example'), 'label' => 'Contoh Import Soal'])
                    </div>
                    <div class="mx-2">
                        @include('stisla.includes.forms.buttons.btn-add', ['link' => $routeImportQuestion, 'label' => 'Import Soal'])
                    </div>
                </div>
            </div>
            @if ($questions->count() > 0)
            <h2 class="section-title">Questions</h2>
            <div class="table-responsive mt-4">
                <table class="table table-striped table-hovered" id="datatable" data-title="Bab" @endif>
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">{{ __('Paket') }}</th>
                            <th class="text-center">{{ __('Soal') }}</th>
                            <th class="text-center">{{ __('Type') }}</th>
                            <th>{{ __('Aksi') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($questions as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->paket}}</td>
                            <td>{{ $item->question_text}}</td>
                            <td class="text-center">
                                @if ($item->is_essay == true)
                                <span class=" badge badge-primary">Essay</span>
                                @else
                                <span class=" badge badge-success">Pilihan Ganda</span>
                                @endif
                            </td>
                            <td>
                                @include('stisla.includes.forms.buttons.btn-edit', ['link' => route('questions.edit', $item->id)])
                                @include('stisla.includes.forms.buttons.btn-delete', ['link' => route('questions.destroy', [$item->id])])
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
@endpush

@push('js')
@endpush