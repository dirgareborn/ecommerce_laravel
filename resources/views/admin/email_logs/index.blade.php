@extends('admin.layout.app')
@section('content')
    <h4>Log Pengiriman Email</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Email</th>
                <th>Subjek</th>
                <th>Status</th>
                <th>Dikirim</th>
                <th>Error</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($logs as $log)
                <tr>
                    <td>{{ $log->recipient_email }}</td>
                    <td>{{ $log->subject }}</td>
                    <td>{{ ucfirst($log->status) }}</td>
                    <td>{{ $log->sent_at ?? '-' }}</td>
                    <td>{{ $log->error_message ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $logs->links() }}
@endsection
