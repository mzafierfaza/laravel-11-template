<table>
  <thead>
    <tr>
      <th>{{ __('#') }}</th>
      <th class="text-center">{{ __('Kursus') }}</th>
      <th class="text-center">{{ __('Judul') }}</th>
      <th class="text-center">{{ __('Deskripsi') }}</th>
      <th class="text-center">{{ __('Urutan') }}</th>
      <th class="text-center">{{ __('Dihapus Pada') }}</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($data as $item)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $item->course_id }}</td>
        <td>{{ $item->title }}</td>
        <td>{{ $item->description }}</td>
        <td>{{ $item->order }}</td>
        <td>{{ $item->deleted_at }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
