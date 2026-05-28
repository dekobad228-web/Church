import localeRu from 'air-datepicker/locale/ru';

function delegate(eventName, elementSelector, handler, listener = document) {
    if (!listener)
        return false;

    listener.addEventListener(eventName, function (e) {
        for (var target = e.target; target && target != this; target = target.parentNode) {
            if (target.matches(elementSelector)) {
                handler.call(target, e, target);
                break;
            }
        }
    });
}

export function getMontNameByNumber(monthNumber, type = 0) {
    let months = [
        ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
        ['Января', 'Февраля', 'Марта', 'Апреля', 'Мая', 'Июня', 'Июля', 'Августа', 'Сентября', 'Октября', 'Ноября', 'Декабря']
    ]
    return months[type][monthNumber]
}

export default class CustomCalendar {
    constructor({ onDateSelect, containerSelector, addToNextMonth = true, onToggleMonth = null, afterInit = null }) {
        this.AirDatepicker = null
        this.datepicker = null
        this.datepickerContainer = null
        this.currentMonthContainer = null

        this.todayDate = new Date()
        this.todayMonth = this.todayDate.getMonth()
        this.todayMonthDay = this.todayDate.getDate()
        this.todayYear = this.todayDate.getFullYear()

        this.pickedDate = null
        this.isShowingNextMonth = false
        this.isDateSelected = false

        import(/* webpackChunkName: "air-datepicker" */ 'air-datepicker')
            .then(module => {
                this.AirDatepicker = module.default
            })
            .then(() => {
                this._initCalendar({ onDateSelect, containerSelector, addToNextMonth, onToggleMonth })
            })
            .then(() => {
                if (afterInit)
                    afterInit()
            })
    }

    _initCalendar({ onDateSelect, containerSelector, addToNextMonth, onToggleMonth }) {
        this.datepickerContainer = document.querySelector(containerSelector)
        if (!this.datepickerContainer)
            return false

        let maxMonth = this.todayMonth + 1
        if (maxMonth > 11)
            maxMonth = 0

        let maxYear = maxMonth === 0 ? this.todayYear + 1 : this.todayYear

        this.datepicker = new this.AirDatepicker(this.datepickerContainer, {
            locale: localeRu,
            onSelect: ({ date }) => {
                this.pickedDate = date
                onDateSelect({ date })
            },
            onRenderCell: ({ date, cellType }) => {
                if (cellType === 'day') {
                    const dateMonth = date.getMonth()
                    const dateDay = date.getDate()

                    const isPastDay = dateMonth === this.todayMonth && dateDay < this.todayMonthDay
                    const isPastMonth = dateMonth < this.todayMonth && date.getFullYear() <= this.todayYear

                    if (isPastDay || isPastMonth)
                        return { disabled: true }
                }
            },
            minView: 'days',
            showOtherMonths: true,
            minDate: new Date(this.todayYear, this.todayMonth, this.todayMonthDay),
            maxDate: new Date(maxYear, maxMonth, 31)
        })

        if (addToNextMonth) {
            delegate('click', '[data-nav-toggle-month]', (event, btn) => {
                this._onToggleMonth(event, btn)
                if (onToggleMonth)
                    onToggleMonth()
            }, this.datepickerContainer)
        }

        let todayMonthName = getMontNameByNumber(this.todayMonth)
        let nextMonth = this.todayMonth + 1 > 11 ? 0 : this.todayMonth + 1
        let nextMonthName = getMontNameByNumber(nextMonth)

        const btnRightSvg = `
            <svg width="8" height="12" viewBox="0 0 8 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M1.24952 12L0 10.4012L4.95238 6L0 1.59878L1.24952 -2.95073e-07L8 6L1.24952 12Z" fill="#1B1B1C"/>
            </svg>`

        let navigationTpl = `
            <div class="section-schedule__navigation">
                <div class="section-schedule__current" data-current-month>
                    <p class="p1-400">${todayMonthName}</p>
                </div>
                ${addToNextMonth ? `
                <div class="section-schedule__toggle" data-nav-toggle-month>
                    <p class="p1-400">${nextMonthName}</p>
                    ${btnRightSvg}
                </div>` : ''}
            </div>
        `

        this.datepickerContainer.insertAdjacentHTML('afterbegin', navigationTpl)
        this.currentMonthContainer = this.datepickerContainer.querySelector('[data-current-month]')

        return true
    }

    _onToggleMonth(event, btn) {
        if (this.isShowingNextMonth)
            this.datepicker.prev()
        else
            this.datepicker.next()

        this.isShowingNextMonth = !this.isShowingNextMonth

        const viewDate = this.datepicker.viewDate
        const viewMonth = viewDate.getMonth()

        let altMonth = this.isShowingNextMonth ? viewMonth - 1 : viewMonth + 1
        if (altMonth < 0)
            altMonth = 11
        else if (altMonth > 11)
            altMonth = 0

        const btnRightSvg = `
        <svg width="8" height="12" viewBox="0 0 8 12" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M1.24952 12L0 10.4012L4.95238 6L0 1.59878L1.24952 -2.95073e-07L8 6L1.24952 12Z" fill="#1B1B1C"/>
        </svg>`

        const btnLeftSvg = `
        <svg width="8" height="12" viewBox="0 0 8 12" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M6.75048 2.95073e-07L8 1.59878L3.04762 6L8 10.4012L6.75048 12L0 6L6.75048 2.95073e-07Z" fill="#1B1B1C"/>
        </svg>`

        let altMonthName = getMontNameByNumber(altMonth)
        let viewMonthName = getMontNameByNumber(viewMonth)

        btn.innerHTML = this.isShowingNextMonth
            ? `${btnLeftSvg} <p class="p1-400">${altMonthName}</p>`
            : `<p class="p1-400">${altMonthName}</p> ${btnRightSvg}`

        this.currentMonthContainer.innerHTML = `<p class="p1-400">${viewMonthName}</p>`
    }

    unselectDate() {
        if (this.datepicker && this.pickedDate)
            this.datepicker.unselectDate(this.pickedDate)
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const btns = document.querySelectorAll('.btn-datepicker')

    btns.forEach((btn) => {
        const container = btn.closest('.section-schedule__datepicker')
        if (!container) return

        const datepicker = container.querySelector('#datepicker')
        if (!datepicker) return

        btn.addEventListener('click', () => {
            datepicker.classList.toggle('active')
        })
    })
})