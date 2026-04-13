@extends('admin.layout')

@section('title', 'Liên hệ khách hàng | HOVI CMS')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-1">Liên hệ khách hàng</h1>
            <p class="text-muted mb-0">Danh sách thông tin khách gửi từ các form trên website.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Khách hàng</th>
                        <th>Liên hệ</th>
                        <th>Từ trang</th>
                        <th>Ngày gửi</th>
                        <th class="text-end">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($messages as $message)
                        <tr class="{{ $message->is_read ? '' : 'table-warning' }}">
                            <td>{{ $message->id }}</td>
                            <td>
                                <strong>{{ $message->name }}</strong>
                                @if (!empty($message->service))
                                    <div class="small text-muted">{{ $message->service }}</div>
                                @endif
                            </td>
                            <td>
                                <div>{{ $message->phone ?: '-' }}</div>
                                <div class="small text-muted">{{ $message->email ?: '-' }}</div>
                            </td>
                            <td><code>{{ $message->source_page ?: '-' }}</code></td>
                            <td>{{ $message->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-dark"
                                    href="{{ route('admin.contact-messages.show', $message) }}">Xem</a>

                                <form action="{{ route('admin.contact-messages.destroy', $message) }}" method="post"
                                    class="d-inline" onsubmit="return confirm('Xoá liên hệ này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Xoá</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Chưa có liên hệ nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $messages->links() }}
    </div>
@endsection
