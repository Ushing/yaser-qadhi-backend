<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Query\CustomerQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;


class CustomerDetailController extends Controller
{
    protected $query;
    protected string $redirectUrl;
    public $user;
    const moduleDirectory = 'admin.customer-details.';

    public function __construct(CustomerQuery $customerQuery)
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            return $next($request);
        });
        $this->redirectUrl = 'admin/customer-detail';
        $this->query = $customerQuery;
    }

    public function index(): View
    {
        if (is_null($this->user) or !$this->user->can('customer-list-view')) {
            abort(403, 'Sorry!! You are Unauthorized!');
        }
        $data = [
            'moduleName' => 'Lists Of Customers',
            'tableHeads' => ['Sr. No', 'Name', 'Email', 'Login Type', 'Device', 'Reset Device', 'User Type', 'Status', 'Change Status', 'Action'],
            'dataUrl' => $this->redirectUrl . '/get-data',
            'columns' => [
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false],
                ['data' => 'name', 'name' => 'name'],
                ['data' => 'email', 'name' => 'email'],
                ['data' => 'login_type', 'name' => 'login_type'],
                ['data' => 'device_id', 'name' => 'device_id'],
                ['data' => 'device_reset', 'name' => 'device_reset'],
                ['data' => 'user_type', 'name' => 'user_type'],
                ['data' => 'status', 'name' => 'status'],
                ['data' => 'status_change', 'name' => 'status_change'],
                ['data' => 'action', 'name' => 'action', 'orderable' => false],
            ],
        ];
        return view(self::moduleDirectory . 'index', $data);
    }

    public function getData(Request $request): JsonResponse
    {
        return $this->query->getAllData($request);
    }

    public function destroy($id): JsonResponse
    {
        if (is_null($this->user) or !$this->user->can('customer-list-delete')) {
            abort(403, 'Sorry!! You are Unauthorized!');
        }
        $customer = $this->query->find($id);
        $customer->delete();
        return response()->json(['status' => true, 'data' => $customer]);
    }

    public function statusChange($id): RedirectResponse
    {
        $customer = $this->query->find($id);
        $status = $customer->status == 0 ? 1 : 0;
        $customer->update(['status' => $status]);
        if ($customer) {
            if ($customer->status == 1) {
                alert()->success('Customer Details', 'Customer Status Is Active');
            }
            if ($customer->status == 0) {
                alert()->success('Customer Details', 'Customer Status Is Inactive');
            }
        } else {
            alert()->error('Customer Details', 'Failed To Update Status');
        }
        return redirect()->route('admin.customer-detail.index');
    }

    public function resetDevice($id): RedirectResponse
    {
        $customer = $this->query->find($id);
        if ($customer->device_id != null) {
            $update = $customer->update(['device_id' => null]);
            if ($update) {
                alert()->success('Customer Device', 'Customer Device Is Successfully Reset');
            }
        } else {
            alert()->info('Customer Details', 'Customer Device Is Already Empty');
        }
        return redirect()->route('admin.customer-detail.index');

    }
}
