<table>
  <thead>
    <tr>
      <th>{{ __('#') }}</th>
      <th class="text-center">{{ __('Nama Lengkap') }}</th>
      <th class="text-center">{{ __('Umur') }}</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($data as $item)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $item->name }}</td>
        <td>{{ $item->umur }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
