<?php

namespace App\Query;

use App\Models\Coupon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class CouponQuery extends BaseQuery
{
    /**
     * @var $model
     */
    protected $model;

    /**
     * @var string
     */

    public function __construct(Coupon $coupon)
    {
        $this->model = $coupon;
    }

    /**
     *
     * @return JsonResponse
     */
    public function getAllData($request): JsonResponse
    {
        $query = $this->model->orderBy('id');
        return DataTables::of($query)
            ->addColumn('action', function ($row) {
                $actions = '';
                $actions .= '<a class="btn btn-success btn-sm ml-2" data-size="lg" data-url="' . route('admin.coupon.edit', [$row->id]) . '" data-ajax-popup="true"  data-bs-toggle="tooltip" title="Edit Coupon" data-title="Edit Coupon"  href="#" ><i class="fa fa-edit"></i></a>';
                $actions .= '<a class="btn btn-info btn-sm ml-2" data-size="lg" data-url="' . route('admin.coupon.show', [$row->id]) . '" data-ajax-popup="true"  data-bs-toggle="tooltip" title="Coupon Details" data-title="Coupon Details"  href="#" ><i class="fas fa-eye-slash"></i></a>';
                $actions .= '<a class="btn btn-danger btn-sm ml-2 btn-delete"  data-coupon-id="' . $row->id . '" href="#" title="Delete"><i class="fas fa-trash"></i></a>';
                return $actions;
            })
            ->editColumn('status', function ($row) {
                if ($row->status == 'true'){
                    $badge = "badge badge-info fs-7";
                }else{
                    $badge = "badge badge-danger fs-7";

                }
                return '<span class="'.$badge.'">' . ucwords($row->status) . '</span>';

            })

            ->rawColumns(['action','status'])
            ->addIndexColumn()
            ->make(true);
    }

    public function saveCoupon($request): string
    {
        try {
            return $this->model->create([
                'code' => Str::upper(Str::random(8)),
                'validate_date' => $request->validate_date,
                'status' => $request->status,
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateCoupon($request, $coupon): string
    {
        try {
            return $coupon->update([
                'code' => $request->code,
                'validate_date' => $request->validate_date,
                'status' => $request->status,

            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

}
