<?php

namespace App\Query;

use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class SubscriptionQuery extends BaseQuery
{
    /**
     * @var $model
     */
    protected $model;

    /**
     * @var string
     */

    public function __construct(Subscription $subscription)
    {
        $this->model = $subscription;
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
                if ($permission->can('subscription-edit')) {
                    $actions .= '<a class="btn btn-success btn-sm ml-2" data-size="lg" data-url="' . route('admin.subscription.edit', [$row->id]) . '" data-ajax-popup="true"  data-bs-toggle="tooltip" title="Edit Subscription" data-title="Edit Subscription"  href="#" ><i class="fa fa-edit"></i></a>';
                }
                if ($permission->can('subscription-view')) {
                    $actions .= '<a class="btn btn-info btn-sm ml-2" data-size="lg" data-url="' . route('admin.subscription.show', [$row->id]) . '" data-ajax-popup="true"  data-bs-toggle="tooltip" title="Subscription Details" data-title="Subscription Details"  href="#" ><i class="fas fa-eye-slash"></i></a>';
                }
                if ($permission->can('subscription-delete')) {
                    $actions .= '<a class="btn btn-danger btn-sm ml-2 btn-delete"  data-subscription-id="' . $row->id . '" href="#" title="Delete"><i class="fas fa-trash"></i></a>';
                }

                return $actions;
            })

            ->editColumn('status', function ($row) {
                return setStatus($row->status)  ;
            })

            ->editColumn('plan_description', function ($row) {
                return Str::limit($row->plan_description, 30);
            })

            ->editColumn('duration', function ($row) {
                return ucfirst($row->duration);
            })
            ->addColumn('status_change', function ($row) {
                $toggleAction ='';
                if ($row->status == 1) {
                    $color = 'btn-outline-danger';
                    $status = 'Mark Inactive';
                    $title = 'Click To Change Status Active';
                } else {
                    $color = 'btn-outline-primary';
                    $status = 'Mark Active';
                    $title = 'Click To Change Status Inactive';

                }
                $toggleAction .= '<a href="' . route('admin.subscription.status', [$row->id]) . '" class="btn btn-md ml-2 btn-sm font-weight-bold ' . $color . ' btn-icon ml-2" title="'.$title.'" >'.$status.'</a>';
                return $toggleAction;

            })
            ->rawColumns(['action', 'duration', 'plan_description','status_change', 'status'])
            ->addIndexColumn()
            ->make(true);
    }

    public function saveSubscription($request): string
    {
        try {
            return $this->model->create([
                'plan_name' => $request->plan_name,
                'plan_description' => $request->plan_description,
                'plan_cost' => $request->plan_cost,
                'duration' => $request->duration ?? 'yearly',
                'status' => $request->status ?? 1,
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateSubscription($request, $subscription): string
    {
        try {
            return $subscription->update([
                'plan_name' => $request->plan_name,
                'plan_description' => $request->plan_description,
                'plan_cost' => $request->plan_cost,
                'duration' => $request->duration ?? 'yearly',
                'status' => $request->status ?? 1,
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

}
