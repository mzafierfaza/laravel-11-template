<table>
  <thead>
    <tr>
      <th>{{ __('#') }}</th>
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
      </tr>
    @endforeach
  </tbody>
</table>
