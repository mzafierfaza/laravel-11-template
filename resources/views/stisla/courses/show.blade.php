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

                <h2 class="section-title">{{ $course->title }}</h2>
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
                    @include('stisla.includes.forms.buttons.btn-add', ['link' => $routeCreateModule, 'label' => 'Buat Bab'])
                </div>
            </div>
            @if ($babs->count() > 0)
            <div class="table-responsive mt-4">
                <table class="table table-striped table-hovered" id="datatable" data-title="Bab" @endif>
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">{{ __('Urutan') }}</th>
                            <th class="text-center">{{ __('Nama') }}</th>
                            <th class="text-center">{{ __('Bacaan') }}</th>
                            <th class="text-center">{{ __('Video') }}</th>
                            <th class="text-center">{{ __('Test') }}</th>
                            <th>{{ __('Aksi') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($babs as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $loop->order ?? '-' }}</td>
                            <td>{{ $item->title }}</td>
                            <td>{{ $item->countText() }}</td>
                            <td>{{ $item->countVideo() }}</td>
                            <td>{{ $item->countTest() }}</td>
                            <td>
                                @include('stisla.includes.forms.buttons.btn-view', ['link' => route('modules.show', [$item->id])])
                                @include('stisla.includes.forms.buttons.btn-edit', ['link' => route('modules.edit', $item->id)])
                                @include('stisla.includes.forms.buttons.btn-delete', ['link' => route('modules.destroy', [$item->id])])
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
    'formAction' => $routeCreateModule,
    ])
    @endpush