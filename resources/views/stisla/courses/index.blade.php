@extends($data->count() > 0 ? 'stisla.layouts.app-table' : 'stisla.layouts.app')

@section('title')
  {{ $title }}
@endsection

@section('content')
  @include('stisla.includes.breadcrumbs.breadcrumb-table')

  <div class="section-body">

    <h2 class="section-title">{{ $title }}</h2>
    <p class="section-lead">{{ __('Merupakan halaman yang menampilkan kumpulan data ' . $title) }}.</p>

    <div class="row">
      <div class="col-12">

        {{-- gunakan jika ingin menampilkan sesuatu informasi --}}
        {{-- <div class="alert alert-info alert-has-icon">
          <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
          <div class="alert-body">
            <div class="alert-title">{{ __('Informasi') }}</div>
            This is a info alert.
          </div>
        </div> --}}

        {{-- gunakan jika mau ada filter --}}
        {{-- <div class="card">
          <div class="card-header">
            <h4><i class="fa fa-filter"></i> Filter Data</h4>
            <div class="card-header-action">
            </div>
          </div>
          <div class="card-body">

            <form action="">
              @csrf
              <div class="row">
                <div class="col-md-3">
                  @include('stisla.includes.forms.inputs.input', [
                      'type' => 'text',
                      'id' => 'filter_text',
                      'required' => false,
                      'label' => __('Pilih Text'),
                      'value' => request('filter_text'),
                  ])
                </div>
                <div class="col-md-3">
                  @include('stisla.includes.forms.inputs.input', [
                      'type' => 'date',
                      'id' => 'filter_date',
                      'required' => true,
                      'label' => __('Pilih Date'),
                      'value' => request('filter_date', date('Y-m-d')),
                  ])
                </div>
                <div class="col-md-3">
                  @include('stisla.includes.forms.selects.select2', [
                      'id' => 'filter_dropdown',
                      'name' => 'filter_dropdown',
                      'label' => __('Pilih Select2'),
                      'options' => $dropdownOptions ?? [],
                      'selected' => request('filter_dropdown'),
                      'with_all' => true,
                  ])
                </div>
              </div>
              <button class="btn btn-primary icon"><i class="fa fa-search"></i> Cari Data</button>
            </form>
          </div>
        </div> --}}

        @if ($data->count() > 0)
          @if ($canExport)
            <div class="card">
              <div class="card-header">
                <h4><i class="fa fa-book"></i> {!! __('Aksi Ekspor <small>(Server Side)</small>') !!}</h4>
                <div class="card-header-action">
                  @include('stisla.includes.forms.buttons.btn-pdf-download', ['link' => $routePdf])
                  @include('stisla.includes.forms.buttons.btn-excel-download', ['link' => $routeExcel])
                  @include('stisla.includes.forms.buttons.btn-csv-download', ['link' => $routeCsv])
                  @include('stisla.includes.forms.buttons.btn-print', ['link' => $routePrint])
                  @include('stisla.includes.forms.buttons.btn-json-download', ['link' => $routeJson])
                </div>
              </div>
            </div>
          @endif

          <div class="card">
            <div class="card-header">
              <h4><i class="fa fa-users"></i> {{ $title }}</h4>

              <div class="card-header-action">
                @if ($canImportExcel)
                  @include('stisla.includes.forms.buttons.btn-import-excel')
                @endif

                @if ($canCreate)
                  @include('stisla.includes.forms.buttons.btn-add', ['link' => $routeCreate])
                @endif
              </div>

            </div>
            <div class="card-body">
              <div class="table-responsive">

                @if ($canExport)
                  <h6 class="text-primary">{!! __('Aksi Ekspor <small>(Client Side)</small>') !!}</h6>
                @endif

                <table class="table table-striped table-hovered" id="datatable"  @if ($canExport) data-export="true" data-title="{{ $title }}" @endif>
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th class="text-center">{{ __('Judul') }}</th>
                      <th class="text-center">{{ __('Deskripsi') }}</th>
                      <th class="text-center">{{ __('Prosedur') }}</th>
                      <th class="text-center">{{ __('Topik') }}</th>
                      <th class="text-center">{{ __('Format') }}</th>
                      <th class="text-center">{{ __('Materi Acak') }}</th>
                      <th class="text-center">{{ __('Premium') }}</th>
                      <th class="text-center">{{ __('Harga') }}</th>
                      <th class="text-center">{{ __('Dibuat Oleh') }}</th>
                      <th class="text-center">{{ __('Aktif') }}</th>
                      <th class="text-center">{{ __('Tanggal Mulai') }}</th>
                      <th class="text-center">{{ __('Tanggal Selesai') }}</th>
                      <th class="text-center">{{ __('Waktu Mulai') }}</th>
                      <th class="text-center">{{ __('Waktu Selesai') }}</th>
                      <th class="text-center">{{ __('Alamat') }}</th>
                      <th class="text-center">{{ __('Pendaftaran Ulang') }}</th>
                      <th class="text-center">{{ __('Maksimal Pendaftaran Ulang') }}</th>
                      <th class="text-center">{{ __('Maksimal Pendaftaran') }}</th>
                      <th class="text-center">{{ __('Tes Kelas') }}</th>
                      <th class="text-center">{{ __('Kelas Selesai') }}</th>
                      <th class="text-center">{{ __('Status') }}</th>
                      <th class="text-center">{{ __('Status Persetujuan') }}</th>
                      <th class="text-center">{{ __('Waktu Persetujuan') }}</th>
                      <th class="text-center">{{ __('Disetujui Oleh') }}</th>
                      <th class="text-center">{{ __('Pengajar') }}</th>
                      <th class="text-center">{{ __('Tentang Pengajar') }}</th>
                      <th class="text-center">{{ __('Gambar') }}</th>
                      <th class="text-center">{{ __('Sertifikat') }}</th>
                      <th class="text-center">{{ __('Sertifikat Dapat Diunduh') }}</th>
                      <th>{{ __('Aksi') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($data as $item)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->description }}</td>
                        <td>{{ $item->procedurs }}</td>
                        <td>{{ $item->topic }}</td>
                        <td>{{ $item->format }}</td>
                        <td>{{ $item->is_random_material }}</td>
                        <td>{{ $item->is_premium }}</td>
                        <td>{{ $item->price }}</td>
                        <td>{{ $item->created_by }}</td>
                        <td>{{ $item->is_active }}</td>
                        <td>{{ $item->start_date }}</td>
                        <td>{{ $item->end_date }}</td>
                        <td>{{ $item->start_time }}</td>
                        <td>{{ $item->end_time }}</td>
                        <td>{{ $item->address }}</td>
                        <td>{{ $item->is_repeat_enrollment }}</td>
                        <td>{{ $item->max_repeat_enrollment }}</td>
                        <td>{{ $item->max_enrollment }}</td>
                        <td>{{ $item->is_class_test }}</td>
                        <td>{{ $item->is_class_finish }}</td>
                        <td>{{ $item->status }}</td>
                        <td>{{ $item->approved_status }}</td>
                        <td>{{ $item->approved_at }}</td>
                        <td>{{ $item->approved_by }}</td>
                        <td>{{ $item->teacher_id }}</td>
                        <td>{{ $item->teacher_about }}</td>
                        <td>{{ $item->image }}</td>
                        <td>{{ $item->certificate }}</td>
                        <td>{{ $item->certificate_can_download }}</td>
                        <td>
                          @if ($canUpdate)
                            @include('stisla.includes.forms.buttons.btn-edit', ['link' => route('courses.edit', [$item->id])])
                          @endif
                          @if ($canDelete)
                            @include('stisla.includes.forms.buttons.btn-delete', ['link' => route('courses.destroy', [$item->id])])
                          @endif
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        @else
          @include('stisla.includes.others.empty-state', ['title' => 'Data ' . $title, 'icon' => 'book', 'link' => $routeCreate])
        @endif
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
  @if ($canImportExcel)
    @include('stisla.includes.modals.modal-import-excel', ['formAction' => $routeImportExcel, 'downloadLink' => $excelExampleLink])
  @endif
@endpush
