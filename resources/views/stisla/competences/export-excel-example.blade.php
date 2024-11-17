<table>
  <thead>
    <tr>
      <th>{{ __('#') }}</th>
      <th class="text-center">{{ __('Judul') }}</th>
      <th class="text-center">{{ __('Level') }}</th>
      <th class="text-center">{{ __('Sertifikat') }}</th>
      <th class="text-center">{{ __('Sertifikat Dapat Diunduh') }}</th>
      <th class="text-center">{{ __('Gambar') }}</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($data as $item)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $item->title }}</td>
        <td>{{ $item->level }}</td>
        <td>{{ $item->certificate }}</td>
        <td>{{ $item->certificate_can_download }}</td>
        <td>{{ $item->image }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
