<?php

namespace App\Query;

use App\Models\CustomerDetail;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class CustomerQuery extends BaseQuery
{
    /**
     * @var $model
     */
    protected $model;

    /**
     * @var string
     */

    public function __construct(CustomerDetail $customerDetail)
    {
        $this->model = $customerDetail;
    }

    /**
     *
     * @return JsonResponse
     */
    public function getAllData($request): JsonResponse
    {
        $query = $this->model->orderBy('id');
        $permission = Auth::user();
        return DataTables::of($query)
            ->addColumn('action', function ($row) use ($permission) {
                $actions = '';
                if ($permission->can('customer-list-delete')) {
                    $actions .= '<a class="btn btn-danger btn-sm ml-2 mt-2  btn-delete" data-customer-id="' . $row->id . '" href="#" title="Delete"><i class="fas fa-trash"></i></a>';
                }
                return $actions;
            })
            ->addColumn('status_change', function ($row) {
                $toggleAction = '';
                if ($row->status == 1) {
                    $color = 'btn-outline-danger';
                    $status = 'Mark Inactive';
                    $title = 'Click To Change Status Active';
                } else {
                    $color = 'btn-outline-primary';
                    $status = 'Mark Active';
                    $title = 'Click To Change Status Inactive';

                }
                $toggleAction .= '<a  title="' . $title . '" href="' . route('admin.customer-detail.status', [$row->id]) . '" class="btn btn-md btn-sm ml-2 font-weight-bold  ' . $color . ' btn-icon ml-2" >' . $status . '</a>';
                return $toggleAction;
            })
            ->editColumn('status', function ($row) {
                return setStatus($row->status);
            })
            ->editColumn('login_type', function ($row) {
                return $row->login_type ?? 'N/A';
            })
            ->editColumn('device_id', function ($row) {
                $null = '<span class="badge bg-warning m-1 fs-6">None</span>';
                return $row->device_id ?? $null;
            })
            ->addColumn('device_reset', function ($row) {
                $toggleAction = '';
                if ($row->device_id != null) {
                    $color = 'btn-outline-success';
                    $device = 'Reset';
                    $title = 'Click To Reset Device';
                } else {
                    $color = 'btn-outline-info disable';
                    $device = 'No Device';
                    $title = 'No Device';

                }
                $toggleAction .= '<a  title="' . $title . '" href="' . route('admin.customer-detail.device', [$row->id]) . '" class="btn btn-md btn-sm ml-2 font-weight-bold  ' . $color . ' btn-icon ml-2"  >' . $device . '</a>';
                return $toggleAction;
            })
            ->editColumn('user_type', function ($row) {
                return $row->user_type ?? 'N/A';
            })
            ->rawColumns(['action', 'login_type', 'user_type', 'status_change', 'device_reset', 'device_id', 'status'])
            ->addIndexColumn()
            ->make(true);
    }

}
