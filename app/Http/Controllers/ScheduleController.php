<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
class ScheduleController extends Controller
{

    /**
     * イベントを登録
     *
     * @param  Request  $request
     */
    public function scheduleAdd(Request $request)
    {
        // バリデーション
        $request->validate([
            'start_date' => 'required|max:32',
            'end_date' => 'required|max:32',
            'event_name' => 'required|max:32'
        ]);

        // 登録処理
        $schedule = new Schedule;
        
        // 日付に変換。JavaScriptのタイムスタンプはミリ秒なので秒に変換
        $schedule->start_date = $request->input('start_date');
        $schedule->end_date =  $request->input('end_date');
        $schedule->event_name = $request->input('event_name');
        $schedule->save();
     

        

        return;
    }

    /**
     * イベントを取得
     *
     * @param  Request  $request
     */
    public function scheduleGet(Request $request)
    {
        // バリデーション
        $request->validate([
            'start_date' => 'required|max:32',
            'end_date' => 'required|max:32'
        ]);

        // カレンダー表示期間
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        // 登録処理
        return Schedule::query()
            ->select(
                // FullCalendarの形式に合わせる
                'start_date as start',
                'end_date as end',
                'event_name as title',
                'id'
            )
            // FullCalendarの表示範囲のみ表示
            ->where('end_date', '>', $start_date)
            ->where('start_date', '<', $end_date)
            ->get();
    }

    public function scheduleDelete(Request $request)
{
      // バリデーション
      $request->validate([
  
        'id' => 'required|integer'
    ]);
    $schedule = new Schedule;
    $schedule->where('id',$request->input('id'))->delete();
    return;
}

public function scheduleEdit(Request $request)
{
    
    $request->validate([
        'start_date' => 'required|max:32',
        'end_date' => 'required|max:32',
        'id' => 'required|integer'
    ]);

    $start_date =  $request->input('start_date');
    $end_date = $request->input('end_date');
    $id = $request->input('id');
    $schedule = new Schedule;
  
   $schedule->where('id',$id)->update(['start_date'=> $start_date,'end_date'=>$end_date]);
  //Schedule::update('update Schedule set start_date =: start_date,end_date =: end_date where event_name =: event_name',['start_date'=> $start_date,'end_date'=>$end_date,'event_name'=>$event_name]);
return;
}
}