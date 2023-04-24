<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Shop;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CompareController extends Controller
{
    public function index(): View
    {
        return view('user.shops.compare');
    }

    public function compare(Request $request): Response
    {
        $ids = $request->string('ids')->toString();
        $ids = str_replace(['[', ']'], '', $ids);
        $ids = explode(',', $ids);

        $shops = Shop::whereIn('id', $ids)
            ->withCount('services')
            ->withCount('workers')
            ->withCount('orders')
            ->get();

        // generate comparation table
        $table = [];

        foreach ($shops as $shop) {
            $table[$shop->id] = [
                'name' => $shop->company_name,
                'address' => $shop->company_address,
                'phone' => $shop->company_phone,
                'description' => $shop->description,
                'average_mark' => $shop->shopAverageMark,
                'services_count' => $shop->services_count,
                'workers_count' => $shop->workers_count,
                'orders_count' => $shop->orders_count,
                'created_at' => $shop->created_at->format('Y-m-d H:i'),
            ];
        }

        $columns = 12 / count($shops);
        $html = '<div class="row">';
        foreach ($shops as $shop) {
            $html .= '<div class="col-md-' . $columns . '">';
            $html .= '<table class="table table-bordered">';
            $html .= '<tr><th>' . trans('Name') . '</th><td>' . $table[$shop->id]['name'] . '</td></tr>';
            $html .= '<tr><th>' . trans('Address') . '</th><td>' . $table[$shop->id]['address'] . '</td></tr>';
            $html .= '<tr><th>' . trans('Phone') . '</th><td>' . $table[$shop->id]['phone'] . '</td></tr>';
            $html .= '<tr><th>' . trans('Description') . '</th><td>' . $table[$shop->id]['description'] . '</td></tr>';
            $html .= '<tr><th>' . trans('Average mark') . '</th><td>' . $table[$shop->id]['average_mark'] . ' / 5</td></tr>';
            $html .= '<tr><th>' . trans('Services count') . '</th><td>' . $table[$shop->id]['services_count'] . '</td></tr>';
            $html .= '<tr><th>' . trans('Workers count') . '</th><td> ' . $table[$shop->id]['workers_count'] . '</td></tr>';
            $html .= '<tr><th>' . trans('Orders count') . '</th><td> ' . $table[$shop->id]['orders_count'] . '</td></tr>';
            $html .= '<tr><th>' . trans('Registration date') . '</th><td>' . $table[$shop->id]['created_at'] . '</td></tr>';
            $html .= '</table>';
            $html .= '</div>';
        }
        $html .= '</div>';

        return response($html);
    }
}
