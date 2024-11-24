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

                <h2 class="section-title">{{ $module->title }}</h2>
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
                        @include('stisla.includes.forms.buttons.btn-add', ['link' => $routeCreateMaterial, 'label' => 'Buat Materi'])
                    </div>
                    <div class="mx-2">
                        @include('stisla.includes.forms.buttons.btn-add', ['link' => $routeCreateQuiz, 'label' => 'Buat Test'])
                    </div>
                </div>
            </div>
            @if ($materials->count() > 0)
            <h2 class="section-title">Materi</h2>
            <div class="table-responsive mt-4">
                <table class="table table-striped table-hovered" id="datatable" data-title="Bab" @endif>
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">{{ __('Urutan') }}</th>
                            <th class="text-center">{{ __('Type') }}</th>
                            <th class="text-center">{{ __('Judul') }}</th>
                            <th>{{ __('Aksi') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($materials as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $loop->order ?? '-' }}</td>
                            <td>{{ $item->type }}</td>
                            <td>{{ $item->title }}</td>
                            <td>
                                @include('stisla.includes.forms.buttons.btn-view', ['link' => route('materials.show', [$item->id])])
                                @include('stisla.includes.forms.buttons.btn-edit', ['link' => route('materials.edit', $item->id)])
                                @include('stisla.includes.forms.buttons.btn-delete', ['link' => route('materials.destroy', [$item->id])])
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endsection

    @push('css')
    @endpush

    @push('js')
    @endpush

    @push('scripts')
    <script>

    </script>
    @endpush


    @push('modals')

    @include('stisla.includes.modals.modal-form', [
    'formAction' => $routeCreateMaterial,
    ])
    @endpush