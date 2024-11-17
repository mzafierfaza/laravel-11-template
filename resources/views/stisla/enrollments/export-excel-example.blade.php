<table>
  <thead>
    <tr>
      <th>{{ __('#') }}</th>
      <th class="text-center">{{ __('Pengguna') }}</th>
      <th class="text-center">{{ __('Kompetensi') }}</th>
      <th class="text-center">{{ __('Tanggal Pendaftaran') }}</th>
      <th class="text-center">{{ __('Status') }}</th>
      <th class="text-center">{{ __('Dihapus Pada') }}</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($data as $item)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $item->user_id }}</td>
        <td>{{ $item->competence_id }}</td>
        <td>{{ $item->enrolled_date }}</td>
        <td>{{ $item->status }}</td>
        <td>{{ $item->deleted_at }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
