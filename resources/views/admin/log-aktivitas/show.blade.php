@extends('layouts.app-custom')

@section('title', 'Detail Log Aktivitas')
@section('page-title', 'Detail Log Aktivitas')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Detail Log Aktivitas</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">ID</th>
                            <td>{{ $log->id }}</td>
                        </tr>
                        <tr>
                            <th>Waktu</th>
                            <td>{{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>User</th>
                            <td>
                                @if($log->user_name)
                                <strong>{{ $log->user_name }}</strong> ({{ $log->user_email }})<br>
                                <small class="text-muted">Role: {{ strtoupper($log->user_role ?? '-') }}</small>
                                @else
                                <span class="text-muted">System</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Aktivitas</th>
                            <td>
                                <span class="badge bg-primary">{{ $log->aktivitas }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Modul</th>
                            <td>
                                <span class="badge bg-secondary">{{ $log->modul }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Keterangan</th>
                            <td>{{ $log->keterangan }}</td>
                        </tr>
                        <tr>
                            <th>IP Address</th>
                            <td>{{ $log->ip_address ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>User Agent</th>
                            <td><small>{{ $log->user_agent ?? '-' }}</small></td>
                        </tr>
                    </table>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.log-aktivitas.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


