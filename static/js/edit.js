document.addEventListener("DOMContentLoaded", () => {
    const editEventBtn = document.querySelector(".edit-event-btn");
    const editEventWrapper = document.querySelector(".edit-event-wrapper");
    const saveEventBtn = document.querySelector(".save-event-btn");
    const editEventTitle = document.querySelector(".edit-event-title");
    const editEventFrom = document.querySelector(".edit-event-time-from");
    const editEventTo = document.querySelector(".edit-event-time-to");

    // 일정 추가 버튼 클릭 시 입력창 토글
    editEventBtn.addEventListener("click", () => {
        editEventWrapper.classList.toggle("active");
        // 초기화
        editEventTitle.value = "";
        editEventFrom.value = "";
        editEventTo.value = "";
    });

    // 저장 버튼 클릭 시 일정 추가
    saveEventBtn.addEventListener("click", () => {
        const title = editEventTitle.value.trim();
        const timeFrom = editEventFrom.value.trim();
        const timeTo = editEventTo.value.trim();

        if (!title || !timeFrom || !timeTo) {
            alert("모든 필드를 입력하세요.");
            return;
        }

        // 새 이벤트 추가
        const newEvent = {
            title,
            time_from: timeFrom,
            time_to: timeTo,
            day: activeDay,
            month: month + 1,
            year,
        };

        addEvent(newEvent); // 서버에 이벤트 추가
        editEventWrapper.classList.remove("active"); // 입력창 숨기기
    });
});
