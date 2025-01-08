const apiUrl = "./api.php";

let today = new Date();
let activeDay;
let month = today.getMonth();
let year = today.getFullYear();
let eventsArr = [];
let isProcessing = false;

async function fetchEvents() {
    try {
        const response = await fetch(`${apiUrl}?action=fetch`);
        eventsArr = await response.json();
        initCalendar();
        updateEvents(activeDay);
    } catch (error) {
        console.error("이벤트 가져오기 실패:", error);
    }
}

async function addEvent(eventData) {
    eventData.day = String(eventData.day).padStart(2, '0'); // day 두 자리 변환
    eventData.month = String(eventData.month).padStart(2, '0'); // month 두 자리 변환

    if (isProcessing) return;
    isProcessing = true;

    try {
        const response = await fetch(`${apiUrl}?action=add`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(eventData),
        });
        const result = await response.json();
        if (result.status === "success") {
            await fetchEvents();
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

function initCalendar() {
    const daysContainer = document.querySelector(".days");
    const date = document.querySelector(".date");
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const prevLastDay = new Date(year, month, 0);
    const prevDays = prevLastDay.getDate();
    const lastDate = lastDay.getDate();
    const day = firstDay.getDay();
    const nextDays = 7 - lastDay.getDay() - 1;

    const paddedMonth = String(month + 1).padStart(2, '0'); // 두 자리로 변환
    date.innerHTML = `${paddedMonth}월 ${year}`;
    let days = "";

    for (let x = day; x > 0; x--) {
        days += `<div class="day prev-date">${prevDays - x + 1}</div>`;
    }

    for (let i = 1; i <= lastDate; i++) {
        const paddedDay = String(i).padStart(2, '0'); // 두 자리로 변환
        const event = eventsArr.some(e => e.day === paddedDay && e.month === paddedMonth && e.year == year);
        if (i === today.getDate() && year === today.getFullYear() && month === today.getMonth()) {
            activeDay = i;
            days += `<div class="day today ${event ? "event" : ""} active">${i}</div>`;
        } else {
            days += `<div class="day ${event ? "event" : ""}">${i}</div>`;
        }
    }

    for (let j = 1; j <= nextDays; j++) {
        days += `<div class="day next-date">${j}</div>`;
    }

    daysContainer.innerHTML = days;
    addDayClickListener();
}

function addDayClickListener() {
    const days = document.querySelectorAll(".day");
    days.forEach(day => {
        day.addEventListener("click", (e) => {
            if (e.target.classList.contains("prev-date")) {
                prevMonth();
                return;
            } else if (e.target.classList.contains("next-date")) {
                nextMonth();
                return;
            }

            activeDay = Number(day.textContent);
            updateEvents(activeDay);

            document.querySelectorAll(".day").forEach(d => d.classList.remove("active"));
            day.classList.add("active");
        });
    });
}

function updateEvents(day) {
    const eventList = document.querySelector(".event-list");
    const eventsHTML = eventsArr
        .filter(e => e.day === String(day).padStart(2, '0') && e.month === String(month + 1).padStart(2, '0') && e.year == year)
        .map(e => `
            <div class="event">
                <strong>${e.title}</strong> (${e.time_from} - ${e.time_to})
                <div>
                    <button class="edit-event-btn" onclick="openEditEvent(${e.id})">수정</button>
                    <button class="delete-event-btn" onclick="deleteEvent(${e.id})">삭제</button>
                </div>
            </div>
        `).join("");

    eventList.innerHTML = eventsHTML || "<p>오늘은 일정이 없습니다.</p>";
}

function openEditEvent(eventId) {
    const event = eventsArr.find(e => e.id == eventId);
    if (!event) return;

    document.querySelector(".edit-event-title").value = event.title;
    document.querySelector(".edit-event-time-from").value = event.time_from;
    document.querySelector(".edit-event-time-to").value = event.time_to;

    const saveEventBtn = document.querySelector(".save-event-btn");
    saveEventBtn.setAttribute("data-edit-id", event.id);

    document.querySelector(".edit-event-wrapper").classList.add("active");
}

document.querySelector(".save-event-btn").addEventListener("click", async () => {
    const title = document.querySelector(".edit-event-title").value;
    const timeFrom = document.querySelector(".edit-event-time-from").value;
    const timeTo = document.querySelector(".edit-event-time-to").value;
    const saveEventBtn = document.querySelector(".save-event-btn");
    const editId = saveEventBtn.getAttribute("data-edit-id");

    if (!title || !timeFrom || !timeTo) {
        alert("모든 필드를 입력하세요.");
        return;
    }

    const eventData = {
        id: editId ? parseInt(editId, 10) : null,
        title,
        time_from: timeFrom,
        time_to: timeTo,
        day: String(activeDay).padStart(2, '0'), // 두 자리 변환
        month: String(month + 1).padStart(2, '0'), // 두 자리 변환
        year
    };

    if (editId) {
        await updateEvent(eventData);
    } else {
        await addEvent(eventData);
    }

    document.querySelector(".edit-event-wrapper").classList.remove("active");
    document.querySelector(".edit-event-title").value = "";
    document.querySelector(".edit-event-time-from").value = "";
    document.querySelector(".edit-event-time-to").value = "";
    saveEventBtn.removeAttribute("data-edit-id");
});

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

document.addEventListener("DOMContentLoaded", fetchEvents);
