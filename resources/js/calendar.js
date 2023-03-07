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
    eventAdd: function(info) { 
        console.log('add');
        
    },
    // 日付をクリック、または範囲を選択したイベント
    selectable: true,
    select: function (info) {
        //alert("selected " + info.startStr + " to " + info.endStr);

        // 入力ダイアログ
        const eventName = prompt("イベントを入力してください");
       
        if (eventName) {
            // Laravelの登録処理の呼び出し
            axios
                .post("/schedule-add", {
                    start_date: info.startStr,
                    end_date: info.endStr,
                    event_name: eventName,
                    allDay:true
                })
                .then(() => {
                    // イベントの追加
                    
                   if ( info.startStr.indexOf("00:00:00")!=-1&&info.endStr.indexOf("00:00:00")!=-1) {
                    calendar.addEvent({
                        title: eventName,
                        start: info.startStr,
                        end: info.endStr,
                        allDay:true
                    });
                   } else{
                    calendar.addEvent({
                        title: eventName,
                        start: info.startStr,
                        end: info.endStr
                    });
                }
                console.log(info.end);
                    
                    alert("登録しました。");
                })
                .catch((error) => {
                    throw new Error(error);
                    alert("登録に失敗しました");
                });
        }
    },

    eventChange: function(info) {
        var start = info.event._instance.range.start;
        if(start.getHours() == 9) {
            start = moment(start).format('YYYY-MM-DD') + " 00:00";
        }
        else {
            start = start.setHours(start.getHours() - 9);
            start = moment(start).format('YYYY-MM-DD hh:mm');
        }
        
        
        var end = info.event._instance.range.end;
        if(end.getHours() == 9) {
            end = moment(end).format('YYYY-MM-DD') + " 00:00";
        }
        else {
            end = end.setHours(end.getHours() - 9);
            end = moment(end).format('YYYY-MM-DD hh:mm');
        }
        console.log(start);
        console.log(end);
       console.log(info.event._def.publicId);
        axios
        .post("/schedule-edit", {
            start_date: start,
            end_date: end,
            id: info.event._def.publicId
        })
        .then((response) => {
           alert("修正成功");
        })
        .catch((error) => {
            // バリデーションエラーなど
          
            console.log(error);
            alert("修正失敗");
        });


    },droppable: true,
    eventRemove: function(info){ 
        console.log('remove');
    
    },
    eventClick: function(info) { 
        console.log(info);
        if (confirm('イベントを削除しますか?')) 
        { 
            axios
            .post("/schedule-delete", {
                id: info.event._def.publicId

            })
            .then(() => {
                info.event.remove();
                alert("イベントの削除を成功しました。");
            })
            .catch((error) => {
                console.log(error);
                alert("イベントの削除を失敗しました。");
            });
        }

    },

    events: function (info, successCallback, failureCallback) {
        // Laravelのイベント取得処理の呼び出し
        console.log(info);
        axios
            .post("/schedule-get", {
                start_date: info.startStr,
                end_date: info.endStr,
                
            })
            .then((response) => {
                // 追加したイベントを削除
                //calendar.removeAllEvents();
                // カレンダーに読み込み
                for (let i = 0; i < response.data.length; i++) {
                   
                    if(response.data[i].start.indexOf("00:00:00")!=-1&&response.data[i].end.indexOf("00:00:00")!=-1){
                        response.data[i].start=moment(response.data[i].start).format('YYYY-MM-DD');
                        response.data[i].end=moment(response.data[i].end).format('YYYY-MM-DD');
                    }
                }
                //console.log(response.data);
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