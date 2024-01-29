<?php

namespace App\Query;

use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class EventQuery extends BaseQuery
{
    /**
     * @var $model
     */
    protected $model;

    /**
     * @var string
     */

    public function __construct(Event $event)
    {
        $this->model = $event;
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
            ->addColumn('action', function ($event) use ($permission) {
                $actions = '';
                if ($permission->can('event-edit')) {
                    $actions .= '<a href="' . route('admin.event.edit', [$event->id]) . '" class="btn btn-success btn-sm ml-2 mt-2  " title="Edit"><i class="fa fa-edit"></i></a>';
                }
                if ($permission->can('event-view')) {
                    $actions .= '<a href="' . route('admin.event.show', [$event->id]) . '" class="btn btn-info btn-sm ml-2 mt-2 " title="View"><i class="fas fa-eye-slash"></i></a>';
                }
                if ($permission->can('event-delete')) {
                    $actions .= '<a class="btn btn-danger btn-sm ml-2 mt-2  btn-delete" data-event-id="' . $event->id . '" href="#" title="Delete"><i class="fas fa-trash"></i></a>';
                }
                return $actions;
            })
            ->addColumn('status_change', function ($event) {
                $toggleAction = '';
                if ($event->status == 1) {
                    $color = 'btn-outline-danger';
                    $status = 'Mark Inactive';
                    $title = 'Click To Change Status Active';
                } else {
                    $color = 'btn-outline-primary';
                    $status = 'Mark Active';
                    $title = 'Click To Change Status Inactive';

                }
                $toggleAction .= '<a  title="' . $title . '" href="' . route('admin.event.status', [$event->id]) . '" class="btn btn-md btn-sm ml-2 font-weight-bold  ' . $color . ' btn-icon ml-2" >' . $status . '</a>';
                return $toggleAction;
            })
            ->editColumn('event_details', function ($event) {
                return Str::limit($event->event_details, 30);
            })
            ->editColumn('status', function ($row) {
                return setStatus($row->status);
            })
            ->rawColumns(['action', 'event_details', 'status_change', 'status'])
            ->addIndexColumn()
            ->make(true);
    }

    public function saveEvent($request): string
    {
        try {

            return $this->model->create([
                'event_title' => $request->event_title,
                'event_details' => $request->event_details,
                'event_date' => $request->event_date,
                'status' => $request->status ?? 1,
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateEvent($request, $lecture): string
    {
        try {
            return $lecture->update([
                'event_title' => $request->event_title,
                'event_details' => $request->event_details,
                'event_date' => $request->event_date,
                'status' => $request->status ?? 1,
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


}
