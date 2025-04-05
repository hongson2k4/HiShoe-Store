@extends('admin.layout.main')
<!-- @section('title')
    danh sách
@endsection -->
@section('content')
    <a class="btn btn-success m-2" href="{{route('vouchers.create')}}">Thêm mới mã giảm giá mới</a>
    <form action="{{ route('vouchers.list') }}" method="GET" class="form-inline mb-3 float-right">
        <input type="text" name="search" class="form-control mr-2" placeholder="Search Voucher"
            value="{{ request()->query('search') }}">
        <button type="submit" class="btn btn-success">Search</button>
    </form>
    <table class="table">
        <thead>
            <tr>
                <th>STT</th>
                <th>Mã</th>
                <th>Loại giảm giá</th>
                <th>Giảm giá</th>
                <th>Ngày bắt đầu</th>
                <th>Ngày kết thúc</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($vouchers as $key => $u)
                <tr>
                    <td>{{$key + 1}}</td>
                    <td>{{$u->code}}</td>
                    <td>
                        @if ($u->discount_type== 1)
                            Giảm giá cố định
                        @else
                            Giảm giá theo %
                        @endif
                    </td>
                    <td>
                        @if ($u->discount_type== 1)
                        {{number_format($u->discount_value, 0, '', ',')}} VNĐ
                    @else
                      {{$u->discount_value}} %
                    @endif
                    </td>
                    <td>{{$u->start_date}}</td>
                    <td>{{$u->end_date}}</td>

                    <td>

                    <span class="{{ $u->status == 1 ? 'bg-success text-white px-2 rounded' : 'bg-danger text-white px-2 rounded' }}">
                            {{ $u->status == 1 ? 'Hoạt động' : 'Tạm đóng' }}
                        </span>

                    </td>
                    <td>
                        <a class="btn btn-warning" href="{{route('vouchers.edit', $u->id)}}"><i
                                class="fas fa-pencil-alt"></i></a>
                        <form action="{{route('vouchers.delete', $u->id)}}" method="post">
                            @method('DELETE')
                            @csrf
                            <button class="btn btn-danger" onclick="return confirm('Xác nhận xóa ?')"> <i
                                    class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
