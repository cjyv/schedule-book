<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name','Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
<!--カレンダー -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar/index.global.min.js'></script>
    <script>

      document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar')
        const calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth'
        })
        calendar.render()
      })

    </script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name','Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">ログイン</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">新規登録</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        ログアウト
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-5">
            @yield('content')
        </main>
    </div>
    <script class="cssdesk" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.0/moment.min.js" type="text/javascript"></script>

<script>
	/* -------- 캘린더 시작 ----------*/
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
			
			initialView: 'dayGridMonth',
			selectable: true,// 달력 일자 드래그 설정가능
			navLinks: true, // 날짜를 선택하면 Day 캘린더나 Week 캘린더로 링크
			editable: true, // 수정 가능
			nowIndicator: true, // 현재 시간 마크
			dayMaxEvents: true, // 이벤트가 오버되면 높이 제한 (+ 몇 개식으로 표현)
			locale: 'ko', // 한국어 설정
			eventAdd: function(obj) { // 이벤트가 추가되면 발생하는 이벤트
				console.log('add');
				
			},
			eventChange: function(obj) { // 이벤트가 수정되면 발생하는 이벤트
				
				// GMT 시간은 9시간 빨라서 9시간 빼줘야됨
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
				
				
				$.ajax({
						  url: "/~team2/admin/ajax_calendar_edit",
						  type: "POST",
						  data : {
								title : obj.event._def.title,
								start: start,
								end: end
						  },
						  traditional: true,
						  async: false, //동기
						  success : function(data){
							  
						  },
						  error : function(request,status,error){
							alert("code = "+ request.status + " message = " + request.responseText + " error = " + error); // 실패 시 처리
							console.log("code = "+ request.status + " message = " + request.responseText + " error = " + error);
						  }
					});
				
			},
			eventRemove: function(obj){ // 이벤트가 삭제되면 발생하는 이벤트
				console.log('remove');
				
			},
			select: function(arg) { // 캘린더에서 드래그로 이벤트 생성
				
				var title = prompt('입력할 일정:');
				if (title) {
					$.ajax({
						  url: "/~team2/admin/ajax_calendar_add",
						  type: "POST",
						  data : {
								title: title,
								start: arg.startStr,
								end: arg.endStr,
								allDay: arg.allDay
						  },
						  traditional: true,
						  async: false, //동기
						  success : function(data){
							  
						  },
						  error : function(request,status,error){
							alert("code = "+ request.status + " message = " + request.responseText + " error = " + error); // 실패 시 처리
							console.log("code = "+ request.status + " message = " + request.responseText + " error = " + error);
						  }
					});

					calendar.addEvent({
						title: title,
						start: arg.startStr,
						end: arg.endStr,
						allDay: arg.allDay
					})
				} 
				
				calendar.unselect()
			},
			droppable: true,
			eventClick: function(arg) { 
				// 있는 일정 클릭시, 
				console.log(arg);
				if (confirm('일정을 삭제하시겠습니까?')) 
				{ 
					$.ajax({
						  url: "/~team2/admin/ajax_calendar_delete",
						  type: "POST",
						  data : {
								title : arg.event._def.title
						  },
						  traditional: true,
						  async: false, //동기
						  success : function(data){
							 
						  },
						  error : function(request,status,error){
							alert("code = "+ request.status + " message = " + request.responseText + " error = " + error); // 실패 시 처리
							console.log("code = "+ request.status + " message = " + request.responseText + " error = " + error);
						  }
					});
					arg.event.remove();
				} 
			},
			eventBorderColor : '#82d1ff', // 이벤트 테두리색
			eventBackgroundColor : '#82d1ff' , // 이벤트 배경색
			headerToolbar: { // 툴바
				left: 'prev,next today',
				center: 'title',
				right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
			},
			events: function(info, successCallback, failureCallback){ // ajax 처리로 데이터를 로딩 시킨다. 
				$.ajax({
					  url: "/~team2/admin/ajax_calendar_load",
					  type: "POST",
					  dataType: "JSON",
					  traditional: true,
					  async: false, //동기
					  success : function(data){
						  
						successCallback(data);
						
					  },
					  error : function(request,status,error){
						alert("code = "+ request.status + " message = " + request.responseText + " error = " + error); // 실패 시 처리
						console.log("code = "+ request.status + " message = " + request.responseText + " error = " + error);
					  }
				});
			}
			
        });
		
        calendar.render(); // 캘린더 렌더링(화면 출력)
    });
	
</body>
</html>
