@extends('admin.layout.app')

@section('content')
<section class="content">
  <div class="container-fluid">

    <div class="card">
      <div class="card-header p-2">
        <ul class="nav nav-pills">
          <li class="nav-item">
            <a class="nav-link active" href="#system_info" data-toggle="tab">System Info</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#log_error" data-toggle="tab">Log Error</a>
          </li>
        </ul>
      </div>

      <div class="card-body">
        <div class="tab-content">

          <!-- System Info -->
          <div class="tab-pane fade show active" id="system_info">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Key</th>
                  <th>Value</th>
                </tr>
              </thead>
              <tbody>
                @foreach($info as $key => $value)
                <tr>
                  <td>{{ $key }}</td>
                  <td>{{ $value }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          <!-- Log Error -->
          <div class="tab-pane fade" id="log_error">
            <div class="mb-2 d-flex justify-content-between align-items-center">
              <form method="GET" action="{{ route('admin.system') }}" class="form-inline">
                <select name="level" class="form-control form-control-sm mr-2">
                  <option value="">Semua Level</option>
                  <option value="ERROR" {{ request('level')=='ERROR'?'selected':'' }}>ERROR</option>
                  <option value="CRITICAL" {{ request('level')=='CRITICAL'?'selected':'' }}>CRITICAL</option>
                  <option value="WARNING" {{ request('level')=='WARNING'?'selected':'' }}>WARNING</option>
                  <option value="INFO" {{ request('level')=='INFO'?'selected':'' }}>INFO</option>
                </select>
                <button class="btn btn-sm btn-primary">Filter</button>
              </form>
              <a href="{{ route('admin.system.download') }}" class="btn btn-sm btn-success">Download Log</a>
            </div>

            @if(count($logs) > 0)
              <pre style="max-height:400px; overflow:auto; background:#f8f9fa; padding:10px; border:1px solid #ddd;">
@foreach($logs as $line)
{{ $line }}
@endforeach
              </pre>
            @else
              <p class="text-muted">Tidak ada log error.</p>
            @endif
          </div>

        </div>
      </div>
    </div>

  </div>
</section>
@endsection


@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var triggerTabList = [].slice.call(document.querySelectorAll('#system_info-tab, #log_error-tab'));
    triggerTabList.forEach(function (triggerEl) {
      var tabTrigger = new bootstrap.Tab(triggerEl);
      triggerEl.addEventListener('click', function (event) {
        event.preventDefault();
        tabTrigger.show();
      });
    });
  });
</script>
@endpush
