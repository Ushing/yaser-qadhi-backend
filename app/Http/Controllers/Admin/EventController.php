<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Models\Event;
use App\Query\EventQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;


class EventController extends Controller
{
    protected EventQuery $query;
    protected string $redirectUrl;
    public $user;
    const moduleDirectory = 'admin.events.';

    public function __construct(EventQuery $eventQuery)
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('web')->user();
            return $next($request);
        });
        $this->redirectUrl = 'admin/event';
        $this->query = $eventQuery;
    }

    public function index(): View
    {
        if (is_null($this->user) or !$this->user->can('event-view')) {
            abort(403, 'Sorry!! You are Unauthorized !');
        }
        $data = [
            'moduleName' => 'Event',
            'tableHeads' => ['Sr. No', 'Title', 'Details', 'Date', 'Status', 'Change Status', 'Action'],
            'dataUrl' => $this->redirectUrl . '/get-data',
            'columns' => [
                ['data'=> 'DT_RowIndex', 'name'=> 'DT_RowIndex', 'orderable'=> false, 'searchable'=> false],
                ['data' => 'event_title', 'name' => 'event_title'],
                ['data' => 'event_details', 'name' => 'event_details'],
                ['data' => 'event_date', 'name' => 'event_date'],
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
        if (is_null($this->user) or !$this->user->can('event-create')) {
            abort(403, 'Sorry!! You are Unauthorized  !');
        }
        $data = [
            'moduleName' => 'Event',
        ];
        return view(self::moduleDirectory . 'create', $data);
    }


    public function store(EventRequest $request): RedirectResponse
    {
        if (is_null($this->user) or !$this->user->can('event-create')) {
            abort(403, 'Sorry!! You are Unauthorized  !');
        }
        $event = $this->query->saveEvent($request);
        if ($event) {
            alert()->success('Event', 'Item Created Successfully');
            return redirect()->route('admin.event.index');
        } else {
            alert()->error('Event', 'Failed To Create');
            return redirect()->route('admin.event.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(int $id): View
    {
        if (is_null($this->user) or !$this->user->can('event-view')) {
            abort(403, 'Sorry!! You are Unauthorized !');
        }
        $event = $this->query->find($id);
        $data = [
            'moduleName' => 'Event Details',
            'event' => $event,
        ];
        return view(self::moduleDirectory . 'show', $data);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id):View
    {
        if (is_null($this->user) or !$this->user->can('event-edit')) {
            abort(403, 'Sorry!! You are Unauthorized !');
        }
        $event = $this->query->find($id);
        $data = [
            'moduleName' => 'Event Edit',
            'event' => $event,
        ];
        return view(self::moduleDirectory . 'edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(EventRequest $request, Event $event):RedirectResponse
    {
        if (is_null($this->user) or !$this->user->can('event-edit')) {
            abort(403, 'Sorry!! You are Unauthorized !');
        }
        $updateEvent = $this->query->updateEvent($request, $event);
        if ($updateEvent) {
            alert()->success('Event', 'Item Updated Successfully');
            return redirect()->route('admin.event.index');
        } else {
            alert()->error('Event', 'Failed To Update');
            return redirect()->route('admin.event.index');
        }
    }

    public function destroy($id): JsonResponse
    {
        if (is_null($this->user) or !$this->user->can('event-delete')) {
            abort(403, 'Sorry!! You are Unauthorized!');
        }
        $event = $this->query->find($id);
        $event->delete();
        return response()->json(['status' => true, 'data' => $event]);
    }


    public function statusChange($id): RedirectResponse
    {
        if (is_null($this->user) or !$this->user->can('event-status')) {
            abort(403, 'Sorry!! You are Unauthorized To Change Status !');
        }
        $event = $this->query->find($id);
        $status = $event->status == 0 ? 1 : 0;
        $event->update(['status' => $status]);
        if ($event) {
            if ($event->status == 1) {
                alert()->success('Event Module', 'Item Status Is Active');
            }
            if ($event->status == 0) {
                alert()->success('Event Module', 'Item Status Is Inactive');
            }
        } else {
            alert()->error('Event Module', 'Failed To Update Status');
        }
        return redirect()->route('admin.event.index');
    }
}
