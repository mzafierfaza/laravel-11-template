<table>
  <thead>
    <tr>
      <th>{{ __('#') }}</th>
      <th class="text-center">{{ __('Nama Group') }}</th>
      <th class="text-center">{{ __('Jenis Badan Usaha') }}</th>
      <th class="text-center">{{ __('Nama Badan Usaha') }}</th>
      <th class="text-center">{{ __('Nama Pemilik') }}</th>
      <th class="text-center">{{ __('No. KTP Pemilik') }}</th>
      <th class="text-center">{{ __('NPWP Pemilik') }}</th>
      <th class="text-center">{{ __('Alamat') }}</th>
      <th class="text-center">{{ __('Nama PIC') }}</th>
      <th class="text-center">{{ __('No. Telepon PIC') }}</th>
      <th class="text-center">{{ __('Email PIC') }}</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($data as $item)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $item->name }}</td>
        <td>{{ $item->jenis_badan_usaha }}</td>
        <td>{{ $item->badan_usaha }}</td>
        <td>{{ $item->owner_name }}</td>
        <td>{{ $item->owner_ktp }}</td>
        <td>{{ $item->owner_npwp }}</td>
        <td>{{ $item->address }}</td>
        <td>{{ $item->pic_name }}</td>
        <td>{{ $item->pic_phone }}</td>
        <td>{{ $item->pic_email }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
