<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\MenuOption;
use App\Models\Orders;
use App\Models\OrdersDetails;
use App\Models\OrdersOption;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Timer extends Controller
{
    public function time()
    {
        $data['function_key'] = __FUNCTION__;
        $table = Table::where('is_time', 1)->get();
        $item = [];
        foreach ($table as $rs) {
            $table_id = $rs->id;
            $hours = 0;
            $minutes = 0;
            $order = Orders::where('table_id', $rs->id)
                ->whereIn('status', [1, 2])
                ->whereHas('orderdetail.menu', function ($query) {
                    $query->where('is_time', 1);
                })
                ->with([
                    'orderdetail' => function ($q) {
                        $q->whereHas('menu', function ($query) {
                            $query->where('is_time', 1);
                        })->with('menu');
                    }
                ])
                ->get();
            if ($order->isNotEmpty()) {
                foreach ($order as $rs) {
                    foreach ($rs->orderdetail as $rs) {
                        $hours = $hours + $rs->menu->hours;
                        $minutes = $minutes + $rs->menu->minutes;
                    }
                }
                $hours = $hours + floor($minutes / 60);
                $minutes = $minutes % 60;
                $item[] = [
                    'id' => 'timer-' . $table_id,
                    'start_time' => date('Y-m-d\TH:i:s', strtotime($order[0]->created_at)),
                    'duration_hours' => $hours,
                    'duration_minutes' => $minutes
                ];
            }
        }
        $data['table'] = $table;
        $data['item'] = $item;
        return view('time', $data);
    }

    public function listOrderDetailTime(Request $request)
    {
        $orders = Orders::where('table_id', $request->input('id'))
            ->whereIn('status', [1, 2])
            ->get();
        $info = '';
        foreach ($orders as $order) {
            $info .= '<div class="mb-3">';
            $info .= '<div class="row"><div class="col d-flex align-items-end"><h5 class="text-primary mb-2">เลขออเดอร์ #: ' . $order->id . '</h5></div></div>';
            $orderDetails = OrdersDetails::where('order_id', $order->id)->get()->groupBy('menu_id');
            foreach ($orderDetails as $details) {
                $menuName = optional($details->first()->menu)->name ?? 'ไม่พบชื่อเมนู';
                $orderOption = OrdersOption::where('order_detail_id', $details->first()->id)->get();
                foreach ($details as $detail) {
                    $detailsText = [];
                    if ($orderOption->isNotEmpty()) {
                        foreach ($orderOption as $key => $option) {
                            $optionName = MenuOption::find($option->option_id);
                            $detailsText[] = $optionName->type;
                        }
                        $detailsText = implode(',', $detailsText);
                    }
                    $optionType = $menuName;
                    $priceTotal = number_format($detail->price, 2);
                    $info .= '<ul class="list-group mb-1 shadow-sm rounded">';
                    $info .= '<li class="list-group-item d-flex justify-content-between align-items-start">';
                    $info .= '<div class="flex-grow-1">';
                    $info .= '<div><span class="fw-bold">' . htmlspecialchars($optionType) . '</span></div>';
                    if (!empty($detailsText)) {
                        $info .= '<div class="small text-secondary mb-1 ps-2">+ ' . $detailsText . '</div>';
                    }
                    $info .= '</div>';
                    $info .= '<div class="text-end d-flex flex-column align-items-end">';
                    $info .= '<div class="mb-1">จำนวน: ' . $detail->quantity . '</div>';
                    $info .= '<div>';
                    $info .= '<button class="btn btn-sm btn-primary me-1">' . $priceTotal . ' บาท</button>';
                    $info .= '</div>';
                    $info .= '</div>';
                    $info .= '</li>';
                    $info .= '</ul>';
                }
            }
            $info .= '</div>';
        }
        echo $info;
    }
}
