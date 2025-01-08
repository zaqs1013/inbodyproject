const apiUrl = "../static/php/api.php";
let eventsArr = [];
let isProcessing = false;

// serverSelectedDate 사용  <script>const serverSelectedDate = "yyyy-mm-dd"</script>로 todo.php에서 JS로 날짜를 전달받음음
let year, month, activeDay;
if (typeof serverSelectedDate === "string" && serverSelectedDate.length === 10) {
    // serverSelectedDate 예: "2025-01-08"
    const [yy, mm, dd] = serverSelectedDate.split("-");
    year = parseInt(yy, 10);
    // month는 0부터 시작이므로 -1
    month = parseInt(mm, 10) - 1;
    activeDay = parseInt(dd, 10);
} else {
    // 혹시 serverSelectedDate가 없으면 오늘 날짜로 표기
    let today = new Date();
    year = today.getFullYear();
    month = today.getMonth();
    activeDay = today.getDate();
}


// 이벤트 관련 함수들
async function fetchEvents() {
    try {
        const response = await fetch(`${apiUrl}?action=fetch`);
        eventsArr = await response.json();
        initCalendar();        // 달력 그리기
        updateEvents(activeDay); // 선택 날짜 일정 표시
    } catch (error) {
        console.error("이벤트 가져오기 실패:", error);
    }
}

async function addEvent(eventData) {
    if (isProcessing) return;
    isProcessing = true;

    // day, month를 두 자리로 맞춤
    eventData.day = String(eventData.day).padStart(2, '0');
    eventData.month = String(eventData.month).padStart(2, '0');

    try {
        const response = await fetch(`${apiUrl}?action=add`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(eventData),
        });
        const result = await response.json();
        if (result.status === "success") {
            await fetchEvents();  // 재조회
            alert("일정이 추가되었습니다!");
        } else {
            alert(result.message);
        }
    } catch (error) {
        console.error("이벤트 추가 실패:", error);
    } finally {
        isProcessing = false;
    }
}

async function updateEvent(eventData) {
    if (isProcessing) return;
    isProcessing = true;

    try {
        const response = await fetch(`${apiUrl}?action=update`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(eventData),
        });
        const result = await response.json();
        if (result.status === "success") {
            await fetchEvents();
            alert("일정이 수정되었습니다!");
        } else {
            alert(result.message);
        }
    } catch (error) {
        console.error("이벤트 수정 실패:", error);
    } finally {
        isProcessing = false;
    }
}

async function deleteEvent(eventId) {
    if (isProcessing) return;
    isProcessing = true;

    try {
        const response = await fetch(`${apiUrl}?action=delete`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id: eventId }),
        });
        const result = await response.json();
        if (result.status === "success") {
            await fetchEvents();
            alert("일정이 삭제되었습니다!");
        } else {
            alert(result.message);
        }
    } catch (error) {
        console.error("이벤트 삭제 실패:", error);
    } finally {
        isProcessing = false;
    }
}


// 달력 그리기

function initCalendar() {
    const daysContainer = document.querySelector(".days");
    const dateLabel = document.querySelector(".date");
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const prevLastDay = new Date(year, month, 0);

    const prevDays = prevLastDay.getDate();
    const lastDate = lastDay.getDate();
    const firstDayIndex = firstDay.getDay();
    const lastDayIndex = lastDay.getDay();
    const nextDays = 7 - lastDayIndex - 1;

    // 월 표시 (두 자리)
    const paddedMonth = String(month + 1).padStart(2, '0');
    dateLabel.textContent = `${paddedMonth}월 ${year}`;

    let daysHtml = "";

    // 이전 달 일자들
    for (let x = firstDayIndex; x > 0; x--) {
        daysHtml += `<div class="day prev-date">${prevDays - x + 1}</div>`;
    }

    // 이번 달 일자들
    for (let i = 1; i <= lastDate; i++) {
        const paddedDay = String(i).padStart(2, '0');
        // 이 날짜에 이벤트가 있는지
        const hasEvent = eventsArr.some(e =>
            e.day === paddedDay && e.month === paddedMonth && e.year == year
        );

        if (i === activeDay) {
            // 선택된 날짜(서버에서 받아온 날짜 or 현재)에는 active
            daysHtml += `<div class="day ${hasEvent ? 'event' : ''} active">${i}</div>`;
        } else {
            daysHtml += `<div class="day ${hasEvent ? 'event' : ''}">${i}</div>`;
        }
    }

    // 다음 달 일자들
    for (let j = 1; j <= nextDays; j++) {
        daysHtml += `<div class="day next-date">${j}</div>`;
    }

    daysContainer.innerHTML = daysHtml;
    addDayClickListener();
}


// 날짜 클릭 시 이벤트
function addDayClickListener() {
    const dayElements = document.querySelectorAll(".day");
    dayElements.forEach(dayEl => {
        dayEl.addEventListener("click", (e) => {
            if (e.target.classList.contains("prev-date")) {
                prevMonth();
                return;
            } else if (e.target.classList.contains("next-date")) {
                nextMonth();
                return;
            }

            // day.textContent → "1", "2", ...
            const clickedDay = Number(dayEl.textContent);
            const clickedMonth = String(month + 1).padStart(2, "0");
            const clickedYear = String(year);

            const formattedDate = `${clickedYear}-${clickedMonth}-${String(clickedDay).padStart(2, "0")}`;

            // 페이지 리로드 -> todo.php?date=...
            window.location.href = `todo.php?date=${formattedDate}`;
        });
    });
}


//일정 리스트 업데이트
function updateEvents(day) {
    const eventList = document.querySelector(".event-list");
    if (!eventList) return;  // 혹시 없을 경우 방어

    const paddedDay = String(day).padStart(2, '0');
    const paddedMonth = String(month + 1).padStart(2, '0');

    const dayEvents = eventsArr.filter(e =>
        e.day === paddedDay && e.month === paddedMonth && e.year == year
    );

    if (dayEvents.length === 0) {
        eventList.innerHTML = "<p>오늘은 일정이 없습니다.</p>";
        return;
    }

    let html = "";
    dayEvents.forEach(e => {
        html += `
        <div class="event">
            <strong>${e.title}</strong> (${e.time_from} - ${e.time_to})
            <div>
                <button class="edit-event-btn" onclick="openEditEvent(${e.id})">수정</button>
                <button class="delete-event-btn" onclick="deleteEvent(${e.id})">삭제</button>
            </div>
        </div>`;
    });

    eventList.innerHTML = html;
}

// 일정 수정 창 열기
function openEditEvent(eventId) {
    const event = eventsArr.find(e => e.id == eventId);
    if (!event) return;

    document.querySelector(".edit-event-title").value = event.title;
    document.querySelector(".edit-event-time-from").value = event.time_from;
    document.querySelector(".edit-event-time-to").value = event.time_to;

    const saveBtn = document.querySelector(".save-event-btn");
    saveBtn.setAttribute("data-edit-id", event.id);

    document.querySelector(".edit-event-wrapper").classList.add("active");
}


// 일정 추가/수정 버튼
document.querySelector(".save-event-btn").addEventListener("click", async () => {
    const title = document.querySelector(".edit-event-title").value;
    const timeFrom = document.querySelector(".edit-event-time-from").value;
    const timeTo = document.querySelector(".edit-event-time-to").value;
    const saveBtn = document.querySelector(".save-event-btn");
    const editId = saveBtn.getAttribute("data-edit-id");

    if (!title || !timeFrom || !timeTo) {
        alert("모든 필드를 입력하세요.");
        return;
    }

    // 지금 달력에서 선택된 날짜 = activeDay
    const eventData = {
        id: editId ? parseInt(editId, 10) : null,
        title,
        time_from: timeFrom,
        time_to: timeTo,
        day: String(activeDay).padStart(2, '0'),
        month: String(month + 1).padStart(2, '0'),
        year
    };

    if (editId) {
        await updateEvent(eventData);
    } else {
        await addEvent(eventData);
    }

    // 창 닫기 & 입력값 초기화
    document.querySelector(".edit-event-wrapper").classList.remove("active");
    document.querySelector(".edit-event-title").value = "";
    document.querySelector(".edit-event-time-from").value = "";
    document.querySelector(".edit-event-time-to").value = "";
    saveBtn.removeAttribute("data-edit-id");
});


//이전/다음 달 이동
function prevMonth() {
    month--;
    if (month < 0) {
        month = 11;
        year--;
    }
    initCalendar();
}

function nextMonth() {
    month++;
    if (month > 11) {
        month = 0;
        year++;
    }
    initCalendar();
}

document.querySelector(".prev").addEventListener("click", prevMonth);
document.querySelector(".next").addEventListener("click", nextMonth);


// 페이지 로드시 실행
document.addEventListener("DOMContentLoaded", () => {
    fetchEvents(); // 전체 이벤트 불러온 뒤 initCalendar + updateEvents
});
