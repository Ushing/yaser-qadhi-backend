<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CouponRequest;
use App\Models\Coupon;
use App\Query\CouponQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CouponController extends Controller
{

    protected $query;
    protected string $redirectUrl;
    const moduleDirectory = 'admin.coupons.';

    public function __construct(CouponQuery $couponQuery)
    {
        $this->middleware('auth');
        $this->redirectUrl = 'admin/coupon';
        $this->query = $couponQuery;
    }

    public function index(): View
    {
        $data = [
            'moduleName' => 'Coupon',
            'tableHeads' => ['Sr. No', 'Code', 'Validate Date', 'Status', 'Action'],
            'dataUrl' => $this->redirectUrl . '/get-data',
            'columns' => [
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false],
                ['data' => 'code', 'name' => 'code'],
                ['data' => 'validate_date', 'name' => 'validate_date'],
                ['data' => 'status', 'name' => 'status'],
                ['data' => 'action', 'name' => 'action', 'orderable' => false],
            ],
        ];
        return view(self::moduleDirectory . 'index', $data);
    }

    public function getData(Request $request): JsonResponse
    {
        return $this->query->getAllData($request);
    }


    public function create(): View
    {
        $data = ['moduleName' => 'Coupon Create',];
        return view(self::moduleDirectory . 'create', $data);
    }

    public function store(CouponRequest $request): RedirectResponse
    {
        $coupon = $this->query->saveCoupon($request);
        if ($coupon) {
            alert()->success('Coupon', 'Item Created Successfully');
            return redirect()->route('admin.coupon.index');
        } else {
            alert()->error('Coupon', 'Failed To Create');
            return redirect()->route('admin.coupon.index');
        }
    }

    public function show(int $id): View
    {
        $coupon = $this->query->find($id);
        $data = [
            'moduleName' => 'Coupon Details',
            'coupon' => $coupon,
        ];
        return view(self::moduleDirectory . 'show', $data);
    }

    public function edit(int $id): View
    {
        $coupon = $this->query->find($id);
        $data = [
            'moduleName' => 'Coupon Edit',
            'coupon' => $coupon,
        ];
        return view(self::moduleDirectory . 'edit', $data);
    }

    public function update(CouponRequest $request, Coupon $coupon): RedirectResponse
    {
        $updateCoupon = $this->query->updateCoupon($request, $coupon);
        if ($updateCoupon) {
            alert()->success('Coupon', 'Item Updated Successfully');
            return redirect()->route('admin.coupon.index');
        } else {
            alert()->error('Coupon', 'Failed To Update');
            return redirect()->route('admin.coupon.index');
        }
    }

    public function destroy($id): JsonResponse
    {
        $coupon = $this->query->find($id);
        $coupon->delete();
        return response()->json(['status' => true, 'data' => $coupon]);
    }


}
