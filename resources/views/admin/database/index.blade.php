@extends('admin.layout.app')

@section('content')
<section class="content">
  <div class="container-fluid">

    @include('admin.partials.alert')

    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Backup & Restore Database</h3>
      </div>

      <div class="card-body">
        <div class="mb-3">
          <a href="{{ route('admin.database.backup') }}" class="btn btn-success">
            <i class="fas fa-download"></i> Backup Database
          </a>
        </div>

        <div class="mb-3">
          <form action="{{ route('admin.database.restore') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="input-group">
              <input type="file" name="backup_file" class="form-control" accept=".sql" required>
              <div class="input-group-append">
                <button type="submit" class="btn btn-primary">
                  <i class="fas fa-upload"></i> Restore Database
                </button>
              </div>
            </div>
          </form>
        </div>

        @if(count($backups) > 0)
        <h5>Daftar Backup:</h5>
        <ul>
          @foreach($backups as $file)
          <li>
            {{ basename($file) }}
            <a href="{{ route('admin.database.download', basename($file)) }}" class="btn btn-sm btn-info">
              <i class="fas fa-download"></i> Download
            </a>
          </li>
          @endforeach
        </ul>
        @endif
      </div>
    </div>

  </div>
</section>
@endsection
