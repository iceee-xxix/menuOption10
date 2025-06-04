@extends('admin.layout')
@section('style')
@endsection
@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 col-md-12 order-1">
                <div class="row d-flex justify-content-center">
                    <div class="col-12">
                        <form action="{{route('menuSave')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card">
                                <div class="card-header">
                                    เพิ่มเมนู
                                    <hr>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-12">
                                            <label for="name" class="form-label">ชื่อเมนู : </label>
                                            <input type="text" class="form-control" id="name" name="name" required>
                                        </div>
                                    </div>
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-12">
                                            <label for="base_price" class="form-label">ราคา : </label>
                                            <input type="text" class="form-control" id="base_price" name="base_price" onkeypress="return event.charCode >= 48 && event.charCode <= 57" required>
                                        </div>
                                    </div>
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-12">
                                            <label for="name" class="form-label">หมวดหมู่อาหาร : </label>
                                            <select class="form-control" name="categories_id" id="categories_id" required>
                                                <option value="" disabled selected>เลือกหมวดหมู่</option>
                                                @foreach($category as $categories)
                                                <option value="{{$categories->id}}">{{$categories->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-12">
                                            <label for="name" class="form-label">รายละเอียด : </label>
                                            <textarea class="form-control" rows="4" name="detail" id="detail"></textarea>
                                        </div>
                                    </div>
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-12">
                                            <label for="file" class="form-label">รูปภาพเมนู : </label>
                                            <input class="form-control" type="file" id="file" name="file">
                                        </div>
                                    </div>
                                    <h6>สำหรับจับเวลา</h6>
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-4">
                                            <div class="form-check form-switch">
                                                <label class="form-check-label" for="is_time">เปิด/ปิด ตัวจับเวลา</label>
                                                <input class="form-check-input" type="checkbox" role="switch" id="is_time" name="is_time">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-2">
                                            <label for="hours" class="form-label">จำนวนชั่วโมงที่ต้องการจับ : </label>
                                            <input class="form-control" type="number" id="hours" name="hours" placeholder="ชั่วโมง" max="10" min="0">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="minutes" class="form-label">&nbsp;</label>
                                            <input class="form-control" type="number" id="minutes" name="minutes" placeholder="นาที" max="59" min="0">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer d-flex justify-content-end">
                                    <button type="submit" class="btn btn-outline-primary">บันทึก</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection