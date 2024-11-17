<table>
  <thead>
    <tr>
      <th>{{ __('#') }}</th>
      <th class="text-center">{{ __('Kompetensi') }}</th>
      <th class="text-center">{{ __('Kursus') }}</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($data as $item)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $item->competence_id }}</td>
        <td>{{ $item->course_id }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
