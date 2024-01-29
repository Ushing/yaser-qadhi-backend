<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\HajjStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class HajjStatusController extends Controller
{
    public function getPhase($profile_id)
    {
        $data = HajjStatus::where('profile_id', $profile_id)
            ->select('hajj_checklists.id', 'hajj_checklists.title')
            ->join('hajj_checklists', 'hajj_statuses.checklist_id', '=', 'hajj_checklists.id')
            ->distinct()
            ->get();
        return response()->json($data);
    }

    public function getPlanningStatus($profile_id, $phase_id)
    {
        $data = HajjStatus::where('hajj_statuses.profile_id', $profile_id)->where('hajj_statuses.checklist_id', $phase_id)
            ->join('hajj_sublists', 'hajj_statuses.sublist_id', '=', 'hajj_sublists.id')
            ->join('hajj_checklists', 'hajj_statuses.checklist_id', '=', 'hajj_checklists.id')
            ->select('hajj_statuses.*', 'hajj_sublists.title as task_name')
            ->get();
        return response()->json($data);
    }

    public function postPlanningStatus(Request $request, $id)
    {
        $hajj_status = HajjStatus::findOrFail($id);
        if ($request->status) {
            $hajj_status->status = $request->status;
        }
        if ($request->entry_date) {
            $hajj_status->entry_date = $request->entry_date;
        }
        if ($request->task_level) {
            $hajj_status->task_level = $request->task_level;
        }
        if ($request->isExecuted) {
            $hajj_status->isExecuted = $request->isExecuted;
        }
        if ($request->exeution_date) {
            $hajj_status->exeution_date = $request->exeution_date;
        }

        $hajj_status->save();
        return response()->json(
            ['message'=>'Successfully updated',
                'status'=>200]
        );
    }
    public function getHajjExecution($profile_id, $task_level=null, $date=null)
    {
        if ($task_level!=null) {
            $data = HajjStatus::where('hajj_statuses.profile_id',$profile_id)
                ->where('hajj_statuses.task_level','=',$task_level)
                ->where('hajj_statuses.status','select')
                ->where('hajj_statuses.isExecuted','false')

                ->join('hajj_sublists', 'hajj_statuses.sublist_id', '=', 'hajj_sublists.id')
                ->join('hajj_checklists', 'hajj_statuses.checklist_id', '=', 'hajj_checklists.id')
                ->select('hajj_statuses.*', 'hajj_sublists.title as task_name')
                ->get();
            //return response()->json($data);
        } else {
            $data = HajjStatus::where('hajj_statuses.profile_id', $profile_id)
                ->where('hajj_statuses.status', 'select')
                ->where('hajj_statuses.isExecuted', 'false')
                ->join('hajj_sublists', 'hajj_statuses.sublist_id', '=', 'hajj_sublists.id')
                ->join('hajj_checklists', 'hajj_statuses.checklist_id', '=', 'hajj_checklists.id')
                ->select('hajj_statuses.*', 'hajj_sublists.title as task_name')
                ->get();
            // return response()->json($data);
        }
        return response()->json($data);

    }
    public function getHajjCompleted($profile_id, $task_level=null, $date=null)
    {
        if ($task_level!=null) {
            $data = HajjStatus::where('hajj_statuses.profile_id',$profile_id)
                ->where('hajj_statuses.task_level','=',$task_level)
                ->where('hajj_statuses.status','select')
                ->where('hajj_statuses.isExecuted','false')

                ->join('hajj_sublists', 'hajj_statuses.sublist_id', '=', 'hajj_sublists.id')
                ->join('hajj_checklists', 'hajj_statuses.checklist_id', '=', 'hajj_checklists.id')
                ->select('hajj_statuses.*', 'hajj_sublists.title as task_name')
                ->get();
            //return response()->json($data);
        } else {
            $data = HajjStatus::where('hajj_statuses.profile_id', $profile_id)
                ->where('hajj_statuses.status', 'select')
                ->where('hajj_statuses.isExecuted', 'false')
                ->join('hajj_sublists', 'hajj_statuses.sublist_id', '=', 'hajj_sublists.id')
                ->join('hajj_checklists', 'hajj_statuses.checklist_id', '=', 'hajj_checklists.id')
                ->select('hajj_statuses.*', 'hajj_sublists.title as task_name')
                ->get();
            // return response()->json($data);
        }
        return response()->json($data);

    }
    public function getHajjExecutionByDateRange($profile_id, Request $request)
    {
        $date_from = $request->input('date_form');
        $date_to = $request->input('date_to');
        $today = $request->input('today');
        if ($today) {
            $data = HajjStatus::where('hajj_statuses.profile_id', $profile_id)

                ->where('hajj_statuses.entry_date', $today)
                ->where('hajj_statuses.status', 'select')
                ->where('hajj_statuses.isExecuted', 'false')
                ->get();
        }
        else
        {
        $data = HajjStatus::where('hajj_statuses.profile_id',$profile_id)

            ->whereBetween('hajj_statuses.entry_date',[$date_from, $date_to])
            ->where('hajj_statuses.status','select')
            ->where('hajj_statuses.isExecuted','false')

            ->join('hajj_sublists', 'hajj_statuses.sublist_id', '=', 'hajj_sublists.id')
            ->join('hajj_checklists', 'hajj_statuses.checklist_id', '=', 'hajj_checklists.id')
            ->select('hajj_statuses.*', 'hajj_sublists.title as task_name')
            ->get();

        }
        return response()->json($data);
    }

    public function getAllHajj($profile_id)
    {
        $data = HajjStatus::where('profile_id', $profile_id)
            ->join('hajj_sublists', 'hajj_statuses.sublist_id', '=', 'hajj_sublists.id')
            ->where('isExecuted', 'true')->select('hajj_statuses.*', 'hajj_sublists.title as task_name')->get();
        return response()->json($data);
    }

    public function getHajjStatusCompleted($profile_id)
    {
        $data = HajjStatus::where('profile_id',$profile_id)
            ->join('hajj_sublists', 'hajj_statuses.sublist_id', '=', 'hajj_sublists.id')
            ->where('isExecuted','true')->select('hajj_statuses.*', 'hajj_sublists.title as task_name')->get();
        return response()->json($data);
    }


    public function getPreviousTimePeriodsHajjExecution($profile_id, $period_name)
    {

        if ($period_name == "previous_month") {
            return $this->getPreviousMonthHajjExecution($profile_id);
        } else if ($period_name == "previous_week") {
            return $this->getPreviousWeekHajjExecution($profile_id);
        } else if ($period_name == "previous_day") {
            return $this->getPreviousDayHajjExecution($profile_id);
        }
    }

    public function getHajjStatusByTask(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'profile_id' => ['required', 'integer'],
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 422);
        } else {
            if (!$request->has('filter') or $request->filter == null) {
                $hajjStatus = HajjStatus::where('profile_id', $request->profile_id)->where('isExecuted', 'true')->get();
                if ($hajjStatus->count() == 0) {
                    return response()->json(['success' => true, 'message' => "Hajj status list is empty"], 200);
                } else {
                    return response()->json(['success' => true, 'data' => $hajjStatus,], 200);
                }
            }
            //filter wise query start
            if ($request->has('filter') or $request->filter != null) {
                $today = Carbon::today()->format('Y-m-d');
                $firstDayOfPreviousWeek = Carbon::now()->subWeek()->startOfWeek()->toDateString();
                $lastDayOfPreviousWeek = Carbon::now()->subWeek()->endOfWeek()->toDateString();
                $firstDayOfPreviousMonth = Carbon::now()->startOfMonth()->subMonthsNoOverflow()->toDateString();
                $lastDayOfPreviousMonth = Carbon::now()->subMonthsNoOverflow()->endOfMonth()->toDateString();

                if ($request->filter == 'today' or $request->filter == 'last_week' or $request->filter == 'last_month' or $request->filter == 'past_due' or $request->filter == 'task_level') {
                    if ($request->filter == 'today') {
                        $hajjStatus = HajjStatus::where('profile_id', $request->profile_id)->where('isExecuted', 'true')
                            ->whereDate('execution_date', $today)->get();
                    }
                    if ($request->filter == 'last_week') {
                        $hajjStatus = HajjStatus::where('profile_id', $request->profile_id)->where('isExecuted', 'true')
                            ->whereBetween('execution_date', [$firstDayOfPreviousWeek, $lastDayOfPreviousWeek])->get();
                    }
                    if ($request->filter == 'last_month') {
                        $hajjStatus = HajjStatus::where('profile_id', $request->profile_id)->where('isExecuted', 'true')
                            ->whereBetween('execution_date', [$firstDayOfPreviousMonth, $lastDayOfPreviousMonth])->get();
                    }
                   /*  if ($request->filter == 'past_due') {
                        $hajjStatus = HajjStatus::where('profile_id', $request->profile_id)->where('isExecuted', 'true')
                            ->whereBetween('execution_date', [$firstDayOfPreviousMonth, $lastDayOfPreviousMonth])->get();
                    } */
                  /*   if ($request->filter == 'task_level') {
                        $hajjStatus = HajjStatus::where('profile_id', $request->profile_id)->where('isExecuted', 'true')
                            ->where('task_level',$request->task_level )->get();
                    } */
                    if ($hajjStatus->count() == 0) {
                        return response()->json(['success' => true, 'message' => "Hajj status list is empty"], 200);
                    } else {
                        return response()->json(['success' => true, 'data' => $hajjStatus,], 200);
                    }
                } else {
                    return response()->json(['success' => false, 'message' => "You have entered an wrong filter name"], 200);
                }
            }
            //filter wise query end

            return response()->json(['success' => false, 'message' => "You have entered wrong information"], 200);
        }
    }
    public function getHajjPercentage($id)
    {
      $hajjper =  HajjStatus::where('profile_id',$id)
      ->where('isExecuted', 'true')
      ->count('isExecuted');

      $hajjper2 =  HajjStatus::where('profile_id',$id)
       ->where('isExecuted', 'false')
       ->count('isExecuted');
      //->get(['isExecuted']);
      $trued = ($hajjper/($hajjper +$hajjper2))*100 ;
      $falsed = 100-(($hajjper/($hajjper +$hajjper2))*100);
      return response()->json(array(
       'true' => $trued,
       'false' => $falsed));
      }

}
