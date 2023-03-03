import { Calendar } from "@fullcalendar/core";
import interactionPlugin from "@fullcalendar/interaction";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import listPlugin from "@fullcalendar/list";
import axios from 'axios';

var calendarEl = document.getElementById("calendar");

let calendar = new Calendar(calendarEl, {
    plugins: [interactionPlugin, dayGridPlugin, timeGridPlugin, listPlugin],
    initialView: "dayGridMonth",
    headerToolbar: {
        left: "prev,next today",
        center: "title",
        right: "dayGridMonth,timeGridWeek,listWeek",
    },
    eventBorderColor : '#82d1ff', 
	eventBackgroundColor : '#82d1ff' , 
    selectable: true,
	navLinks: true, 
	editable: true, 
	nowIndicator: true, 
    dayMaxEvents: true,
    locale: "ja",
    eventAdd: function(obj) { 
        console.log('add');
        
    },
    // 日付をクリック、または範囲を選択したイベント
    selectable: true,
    select: function (info) {
        //alert("selected " + info.startStr + " to " + info.endStr);

        // 入力ダイアログ
        const eventName = prompt("イベントを入力してください");
       ;
        if (eventName) {
            // Laravelの登録処理の呼び出し
            axios
                .post("/schedule-add", {
                    start_date: info.start.valueOf(),
                    end_date: info.end.valueOf(),
                    event_name: eventName,
                })
                .then(() => {
                    // イベントの追加
                    calendar.addEvent({
                        title: eventName,
                        start: info.start,
                        end: info.end,
                        allDay: true,
                    });
                })
                .catch((error) => {
                    throw new Error(error);
                    alert("登録に失敗しました");
                });
        }
    },

    eventChange: function(obj) {
        var start = obj.event._instance.range.start;
        if(start.getHours() == 9) {
            start = moment(start).format('YYYY-MM-DD') + " 00:00";
        }
        else {
            start = start.setHours(start.getHours() - 9);
            start = moment(start).format('YYYY-MM-DD hh:mm');
        }
        
        
        var end = obj.event._instance.range.end;
        if(end.getHours() == 9) {
            end = moment(end).format('YYYY-MM-DD') + " 00:00";
        }
        else {
            end = end.setHours(end.getHours() - 9);
            end = moment(end).format('YYYY-MM-DD hh:mm');
        }

    },droppable: true,
    eventRemove: function(obj){ 
        console.log('remove');
        
    },
    events: function (info, successCallback, failureCallback) {
        // Laravelのイベント取得処理の呼び出し
        console.log(info);
        axios
            .post("/schedule-get", {
                start_date: info.start.valueOf(),
                end_date: info.end.valueOf(),
            })
            .then((response) => {
                // 追加したイベントを削除
                calendar.removeAllEvents();
                // カレンダーに読み込み
                successCallback(response.data);
            })
            .catch((error) => {
                // バリデーションエラーなど
                throw new Error(error);
                console.log(error);
                alert("検索失敗");
            });
    },
});
calendar.render();