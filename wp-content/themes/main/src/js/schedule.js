import makeRequest from './make-request'
import CustomCalendar from './datepicker'
import { getMontNameByNumber } from './datepicker'

let todayDate = new Date()
let todayMonth = todayDate.getMonth()
let todayMonthDay = todayDate.getDate()

let datepickerContainer = document.querySelector('#datepicker')
let currentDate = new Date()
let currentMonth = currentDate.getMonth()
let currentMonthDay = currentDate.getDate()
let currentYear = currentDate.getFullYear()

let currentMonthData = []
let calendarBack, monthContainer, calendar, currentMonthContainer

const weekDays = [
    'Воскресенье',
    'Понедельник',
    'Вторник',
    'Среда',
    'Четверг',
    'Пятница',
    'Суббота'
]

initCalendar()

async function initCalendar() {
    if (!datepickerContainer)
        return false

    monthContainer = document.querySelector('#month-container')

    let nextMonth = currentMonth + 1
    nextMonth = nextMonth > 11 ? 0 : nextMonth

    let nextMonthResult = getMonth({
        date: {
            monthNum: nextMonth,
            year: nextMonth === 0 ? currentYear + 1 : currentYear,
            todayMonthDay,
            todayMonth
        }
    })

    nextMonthResult.then((result) => {
        let addToNextMonth = result.status !== 'not-found'
        createCalendar(addToNextMonth)
    })

    const createCalendar = (addToNextMonth = true) => {
        calendar = new CustomCalendar({
            onDateSelect: onDatePick,
            containerSelector: '#datepicker',
            onToggleMonth: onDayDeselect,
            afterInit,
            addToNextMonth
        })
    }

    let result = await getMonth({
        date: {
            monthNum: currentMonth,
            year: currentYear,
            todayMonthDay,
            todayMonth
        }
    })
    processResult(result)
}

function afterInit() {
    currentMonthContainer = datepickerContainer.querySelector('.section-schedule__calendar-current-month')
    currentMonthContainer.addEventListener('click', onDayDeselect)
}

function onDatePick(argsObject) {
    if (!argsObject.date) {
        onDayDeselect()
    } else {
        onDaySelect(argsObject.date)
    }
}

async function onDaySelect(date) {
    let pickedDate = new Date(date)
    let pickedMonth = pickedDate.getMonth()
    let pickedYear = pickedDate.getFullYear()
    let pickedMonthDay = pickedDate.getDate()

    if (pickedMonth !== currentMonth || pickedYear !== currentYear) {
        currentDate = pickedDate
        currentYear = pickedYear
        currentMonth = pickedMonth
        currentMonthDay = pickedMonthDay

        let result = await getMonth({
            date: {
                monthNum: currentMonth,
                year: currentYear,
                monthDay: currentMonthDay,
                todayMonthDay,
                todayMonth
            }
        })
        processResult(result, currentMonthDay)
    } else {
        if (!currentMonthData.length) {
            insertMonthContent(NotFound())
        } else {
            let filteredDay = currentMonthData.find((dayElem) => dayElem.day == pickedMonthDay)
            if (filteredDay) {
                fillMonthBlocks([filteredDay])
            } else {
                insertMonthContent(NotFound())
            }
        }
    }

    currentMonthContainer.classList.add('active')
}

function onDayDeselect() {
    if (currentMonthContainer)
        currentMonthContainer.classList.remove('active')

    if (currentMonthData.length) {
        fillMonthBlocks(currentMonthData)
    } else {
        insertMonthContent(NotFound('Расписание на месяц не заполнено'))
    }

    if (calendar)
        calendar.unselectDate()
}

function processResult(result, pickedMonthDay = false) {
    switch (result.status) {
        case 'success':
            currentMonthData = result.days
            if (pickedMonthDay) {
                let filteredDay = currentMonthData.find((dayElem) => dayElem.day == pickedMonthDay)
                if (filteredDay) {
                    fillMonthBlocks([filteredDay])
                } else {
                    insertMonthContent(NotFound())
                }
            } else {
                fillMonthBlocks(currentMonthData)
            }
            break

        case 'content-empty':
            currentMonthData = []
            insertMonthContent(NotFound('Расписание на месяц не заполнено'))
            break

        case 'not-found':
        default:
            currentMonthData = []
            insertMonthContent(NotFound())
            break
    }
}

function fillMonthBlocks(daysArray) {
    if (!daysArray || !daysArray.length) {
        insertMonthContent(NotFound())
        return
    }

    let prevDay = 0
    let html = ''

    daysArray.forEach(dayElem => {
        if (!dayElem.sluzhba || !dayElem.sluzhba.length)
            return

        dayElem.sluzhba.forEach(elem => {
            let data = {
                day: dayElem.day,
                monthNum: currentMonth,
                sluzhbaTime: elem['time'],
                sluzhbaText: elem['text'],
            }

            if (dayElem.day != prevDay)
                data.saints = dayElem.saints

            prevDay = dayElem.day
            html += createDayTemplate(data)
        })
    })

    insertMonthContent(html || NotFound())
}

async function getMonth({ date, noPastDates = 1 }) {
    let data = new FormData()
    data.append('action', 'calendar')
    data.append('year', date.year)
    data.append('month_num', date.monthNum)
    data.append('today_month_day', date.todayMonthDay)
    data.append('today_month_num', date.todayMonth)
    data.append('no_past_dates', noPastDates)

    let result = await makeRequest(window.ajaxUrl, data, 'POST')
    return result
}

function createDayTemplate({ day, monthNum, sluzhbaTime, sluzhbaText, saints = '' }) {
    let month = getMontNameByNumber(monthNum, 1)

    let date = new Date(currentYear, monthNum, day)
    let weekDay = weekDays[date.getDay()]

    let tpl = `
        <div class="section-schedule__card">
            <h4 class="h4-400 section-schedule__date">
            ${weekDay}<br>
                ${day} ${month} - ${sluzhbaTime}
            </h4>

            <p class="p1-400 section-schedule__text">
            ${sluzhbaText}
            </p>
    `

    if (saints)
        tpl += `<p class="section-schedule__list-saints">${saints}</p>`

    tpl += '</div>'

    return tpl
}

function insertMonthContent(htmlStr) {
    monthContainer.innerHTML = htmlStr
}

function NotFound(msg = 'На эту дату еще не запланировано служений') {
    return `<p class="p1-400">${msg}</p>`
}