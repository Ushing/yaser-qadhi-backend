<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DuaCategoryRequest;
use App\Http\Requests\SubscriptionRequest;
use App\Models\DuaCategory;
use App\Models\Subscription;
use App\Query\DuaCategoryQuery;
use App\Query\SubscriptionQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SubscriptionController extends Controller
{

    protected $query;
    protected string $redirectUrl;
    public $user;
    const moduleDirectory = 'admin.subscriptions.';

    public function __construct(SubscriptionQuery $subscriptionQuery)
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            return $next($request);
        });
        $this->redirectUrl = 'admin/subscription';
        $this->query = $subscriptionQuery;
    }

    public function index(): View
    {
        if (is_null($this->user) or !$this->user->can('subscription-view')) {
            abort(403, 'Sorry!! You are Unauthorized !');
        }
        $data = [
            'moduleName' => 'Subscription',
            'tableHeads' => ['Sr. No', 'Plan Name', 'Description', 'Cost','Duration','Status', 'Change Status', 'Action'],
            'dataUrl' => $this->redirectUrl . '/get-data',
            'columns' => [
                ['data'=> 'DT_RowIndex', 'name'=> 'DT_RowIndex', 'orderable'=> false, 'searchable'=> false],
                ['data' => 'plan_name', 'name' => 'plan_name'],
                ['data' => 'plan_description', 'name' => 'plan_description'],
                ['data' => 'plan_cost', 'name' => 'plan_cost'],
                ['data' => 'duration', 'name' => 'duration'],
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


    public function create(): View
    {
        if (is_null($this->user) or !$this->user->can('subscription-create')) {
            abort(403, 'Sorry!! You are Unauthorized  !');
        }
        $data = ['moduleName' => 'Subscription Create',];
        return view(self::moduleDirectory . 'create', $data);
    }

    public function store(SubscriptionRequest $request): RedirectResponse
    {
        if (is_null($this->user) or !$this->user->can('subscription-create')) {
            abort(403, 'Sorry!! You are Unauthorized  !');
        }
        $subscription = $this->query->saveSubscription($request);
        if ($subscription) {
            alert()->success('Subscription', 'Item Created Successfully');
            return redirect()->route('admin.subscription.index');
        } else {
            alert()->error('Subscription', 'Failed To Create');
            return redirect()->route('admin.subscription.index');
        }
    }

    public function show(int $id): View
    {
        if (is_null($this->user) or !$this->user->can('subscription-view')) {
            abort(403, 'Sorry!! You are Unauthorized !');
        }
        $subscription = $this->query->find($id);
        $data = [
            'moduleName' => 'Subscription Details',
            'subscription' => $subscription,
        ];
        return view(self::moduleDirectory . 'show', $data);
    }

    public function edit(int $id): View
    {
        if (is_null($this->user) or !$this->user->can('subscription-edit')) {
            abort(403, 'Sorry!! You are Unauthorized !');
        }
        $subscription = $this->query->find($id);
        $data = [
            'moduleName' => 'Subscription Edit',
            'subscription' => $subscription,
        ];
        return view(self::moduleDirectory . 'edit', $data);
    }

    public function update(SubscriptionRequest $request, Subscription $subscription): RedirectResponse
    {
        if (is_null($this->user) or !$this->user->can('subscription-edit')) {
            abort(403, 'Sorry!! You are Unauthorized !');
        }
        $updateSubscription = $this->query->updateSubscription($request, $subscription);
        if ($updateSubscription) {
            alert()->success('Subscription', 'Item Updated Successfully');
            return redirect()->route('admin.subscription.index');
        } else {
            alert()->error('Subscription', 'Failed To Update');
            return redirect()->route('admin.subscription.index');
        }
    }

    public function destroy($id): JsonResponse
    {
        if (is_null($this->user) or !$this->user->can('subscription-delete')) {
            abort(403, 'Sorry!! You are Unauthorized!');
        }
        $subscription = $this->query->find($id);
        $subscription->delete();
        return response()->json(['status' => true, 'data' => $subscription]);
    }

    public function statusChange($id): RedirectResponse
    {
        if (is_null($this->user) or !$this->user->can('subscription-status')) {
            abort(403, 'Sorry!! You are Unauthorized To Change Status !');
        }
        $subscription = $this->query->find($id);
        $status = $subscription->status == 0 ? 1 : 0;
        $subscription->update(['status' => $status]);
        if ($subscription) {
            if ($subscription->status == 1) {
                alert()->success('Subscription', 'Item Status Is Active');
            }
            if ($subscription->status == 0) {
                alert()->success('Subscription', 'Item Status Is Inactive');
            }
        } else {
            alert()->error('Subscription', 'Failed To Update Status');

        }
        return redirect()->route('admin.subscription.index');
    }
}
