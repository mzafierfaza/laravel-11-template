<table>
  <thead>
    <tr>
      <th>{{ __('#') }}</th>
      <th class="text-center">{{ __('Nama Depan') }}</th>
      <th class="text-center">{{ __('Nama Belakang') }}</th>
      <th class="text-center">{{ __('Email') }}</th>
      <th class="text-center">{{ __('Jenis Kelamin') }}</th>
      <th class="text-center">{{ __('KTP') }}</th>
      <th class="text-center">{{ __('NPWP') }}</th>
      <th class="text-center">{{ __('Foto') }}</th>
      <th class="text-center">{{ __('Tanggal Lahir') }}</th>
      <th class="text-center">{{ __('Nama Kota') }}</th>
      <th class="text-center">{{ __('Nomor Telepon') }}</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($data as $item)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $item->firstname }}</td>
        <td>{{ $item->lastname }}</td>
        <td>{{ $item->email }}</td>
        <td>{{ $item->gender }}</td>
        <td>{{ $item->ktp }}</td>
        <td>{{ $item->npwp }}</td>
        <td>{{ $item->picture }}</td>
        <td>{{ $item->date_of_birth }}</td>
        <td>{{ $item->region }}</td>
        <td>{{ $item->phone }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
