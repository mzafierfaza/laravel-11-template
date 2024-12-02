@extends( 'stisla.layouts.app-table')


@section('title')
{{ $title }}
@endsection

@section('content')
@include('stisla.includes.breadcrumbs.breadcrumb-form')

<div class="section-body">
    <div class="card shadow-sm">
        <div class="card-header p-4">
            <div class="col-md-2 me-4 mb-2">
                <img src="{{$competence->getImage()}}"
                    alt="Course Image"
                    width="150">
            </div>
            <div class="row">
                <div class="col-md-8 d-flex align-items-center">
                    <h3 class="mb-3">{{ $competence->title }}</h3>
                </div>
                <div class="col-md-6 d-flex gap-4 mb-2">
                    <div class="col-md-4">
                        <span class="badge badge-primary">
                            <i class="fas fa-tasks mr-2"></i>
                            {{$coursesCounts}} Kursus
                        </span>
                    </div>
                    <div class="col-md-4">
                        <span class="badge badge-primary">
                            <i class="fas fa-users mr-2"></i>
                            {{$enrollmentsCounts}} Peserta
                        </span>
                    </div>
                    <div class="col-md-4">
                        <span class="badge badge-primary">
                            <i class="fas fa-bolt mr-2"></i>
                            {{$competence->level}}
                        </span>
                    </div>
                    <div class="col-md-4">
                        <span class="badge badge-primary">
                            <i class="fas fa-calendar-days mr-2"></i>
                            Periode {{$competence->periode()}}
                        </span>
                    </div>
                </div>
                <div class="col-md-12 d-flex gap-4">
                    <div class="col-md-4">
                        @if ($competence->approved_status == 0)
                        <span class=" badge badge-warning">Sttaus Pending</span>
                        @elseif ($competence->approved_status == 1)
                        <span class=" badge badge-success">Status Approved</span>
                        @else
                        <span class=" badge badge-danger">Status Rejected</span>
                    </div>
                    @endif
                    @if ($competence->certificate)
                    <div class="col-md-4">
                        @include('stisla.includes.forms.buttons.btn-csv-download', ['link' => $competence->getDocument(), 'label' => 'Sertifikat'])
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="col-md-12">
            <div class="row d-flex justify-content-end">
                @include('stisla.includes.forms.buttons.btn-add', ['link' => $routeCreateCourses, 'label' => 'Tambah Training'])
            </div>
        </div>
        @if ($coursesCounts > 0)
        <h2 class="section-title">Training</h2>
        <div class="table-responsive mt-4">
            <table class="table table-striped table-hovered" id="datatable" data-title="Bab" @endif>
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">{{ __('Urutan') }}</th>
                        <th class="text-center">{{ __('Judul') }}</th>
                        <th class="text-center">{{ __('Topic') }}</th>
                        <th class="text-center">{{ __('Periode') }}</th>
                        <th>{{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($competence_courses as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $loop->order ?? '-' }}</td>
                        <td>{{ $item->course->title }}</td>
                        <td>{{ $item->course->topic }}</td>
                        <td>{{ $item->course->start_date}}</td>
                        <td>
                            @include('stisla.includes.forms.buttons.btn-view', ['link' => route('courses.show', [$item->course_id])])
                            @include('stisla.includes.forms.buttons.btn-delete', ['link' => route('competence-courses.destroy', [$item->id])])
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

@push('modals')
@include('stisla.includes.modals.modal-form', [
'formAction' => $routeCreateCourses,
])
@endpush