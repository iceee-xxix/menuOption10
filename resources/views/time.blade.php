@extends('admin.layout')
@section('style')
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .display-4 {
        font-size: 3rem;
        letter-spacing: 2px;
    }

    .card {
        background: slategrey;
    }
</style>
@endsection
@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row g-4">
            @foreach($table as $rs)
            <div class="col-md-4">
                <div class="card text-white text-center p-4 rounded-4">
                    <h4 class="text-white">ห้อง {{$rs->table_number}}</h4>
                    <div id="timer-{{$rs->id}}" class="display-4">00:00:00</div>
                    <button type="button" class="btn btn-sm btn-primary modalShow" data-id="{{$rs->id}}">รายละเอียด</button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" id="modal-detail">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">รายละเอียดออเดอร์</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="body-html">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
<script>
    $(document).on('click', '.modalShow', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        $.ajax({
            type: "post",
            url: "{{ route('listOrderDetailTime') }}",
            data: {
                id: id
            },
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                if(response != ''){
                    $('#modal-detail').modal('show');
                    $('#body-html').html(response);
                }
            }
        });
    });
    const rooms = <?= json_encode($item) ?>;

    function startCountdown(timerId, startTimeStr, durationHours, durationMinutes) {
        const startTime = new Date(startTimeStr);
        const totalDurationMs = ((durationHours * 60) + durationMinutes) * 60 * 1000;
        const endTime = new Date(startTime.getTime() + totalDurationMs);
        const timerEl = document.getElementById(timerId);

        const interval = setInterval(() => {
            const now = new Date();
            const remaining = Math.floor((endTime - now) / 1000);

            if (remaining > 0) {
                const h = String(Math.floor(remaining / 3600)).padStart(2, '0');
                const m = String(Math.floor((remaining % 3600) / 60)).padStart(2, '0');
                const s = String(remaining % 60).padStart(2, '0');
                timerEl.textContent = `${h}:${m}:${s}`;
            } else {
                clearInterval(interval);
                timerEl.textContent = "หมดเวลา";
                timerEl.classList.add("text-danger");
            }
        }, 1000);
    }

    rooms.forEach(room => {
        startCountdown(room.id, room.start_time, room.duration_hours, room.duration_minutes);
    });
</script>
@endsection